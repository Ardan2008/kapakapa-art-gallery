<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: '#c9a74e',
                        dark: '#0a0a0a',
                    }
                }
            }
        }
    </script>
    <title>Kapakapa Art Gallery | Gallery</title>
    <style>
        html, body {
            background-color: #0a0a0a !important;
            color: #d1d5db !important;
            margin: 0;
            padding: 0;
        }

        /* Scrollbar kustom tema gelap */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0a0a0a; }
        ::-webkit-scrollbar-thumb { background: #c9a74e; border-radius: 10px; }

        /* Menghilangkan potensi background putih dari kontainer luar */
        .min-h-screen, main, section {
            background-color: transparent !important;
        }
    </style>
</head>
<body class="bg-[#0a0a0a] text-[#d1d5db] overflow-x-hidden">
    
    @include('component.layout.navbar')

    <div class="min-h-screen selection:bg-[#c9a74e]/30">
        <main class="max-w-[1600px] mx-auto px-8 pt-10 pb-20 lg:pt-14">
            
            <header class="relative mb-24 border-l border-[#c9a74e]/40 pl-8 lg:pl-12" data-aos="fade-right">
                <span class="text-[10px] uppercase tracking-[0.5em] text-[#c9a74e] font-bold mb-3 block">Curated Collection</span>
                <h1 class="text-6xl md:text-8xl font-serif italic tracking-tighter leading-none mb-6 text-[#d1d5db]">
                    The Gallery
                </h1>
                <div class="max-w-2xl">
                    <p class="text-xl md:text-2xl font-light leading-relaxed text-gray-400">
                        "Every artwork tells a story—shaped by emotion, inspired by imagination, and brought to life through the artist’s soul."
                    </p>
                </div>
            </header>

            <section id="grid-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-12 gap-y-20">
                @include('component.gallery.partials.gallery-grid', compact('styles'))
            </section>

            @include('component.partials.pagination-bar', [
                'paginator'   => $styles,
                'containerId' => 'grid-container',
            ])
        </main>

        @include('component.layout.footer')

        <button id="backToTop" class="fixed bottom-12 right-12 z-[60] flex items-center justify-center group opacity-0 translate-y-10 transition-all duration-700 pointer-events-none">
            <div class="relative flex items-center justify-center w-12 h-12 border border-white/10 group-hover:border-gold rounded-full transition-all duration-500 bg-black/40 backdrop-blur-md shadow-lg overflow-hidden group-hover:shadow-[0_0_20px_rgba(201,167,78,0.3)]">
                <svg class="w-5 h-5 text-slate-400 group-hover:text-gold transition-transform duration-500 group-hover:-translate-y-12" 
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 15l7-7 7 7"/>
                </svg>
                <svg class="absolute w-5 h-5 text-black translate-y-12 group-hover:translate-y-0 transition-transform duration-500" 
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 15l7-7 7 7"/>
                </svg>
                <div class="absolute inset-0 bg-gold scale-0 group-hover:scale-100 transition-transform duration-500 -z-10 rounded-full"></div>
            </div>
        </button>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1200,
            once: true,
            offset: 50,
            easing: 'ease-in-out-cubic',
        });

        const btn = document.getElementById('backToTop');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 600) {
                btn.classList.remove('opacity-0', 'translate-y-10', 'pointer-events-none');
                btn.classList.add('opacity-100', 'translate-y-0', 'pointer-events-auto');
            } else {
                btn.classList.add('opacity-0', 'translate-y-10', 'pointer-events-none');
                btn.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
            }
        });

        btn.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>

    <script>
    const AJAX_URL     = "{{ route('gallery') }}";
    const EXTRA_PARAMS = {};
    const GRID_ID      = 'grid-container';

    async function changePage(page) {
        if (page < 1 || page > lastPage || isFetching) return;
        isFetching = true;
        const grid = document.getElementById(GRID_ID);
        grid.style.opacity = '0.3';
        grid.style.pointerEvents = 'none';
        try {
            const params = new URLSearchParams({ page, ...EXTRA_PARAMS });
            const res = await fetch(`${AJAX_URL}?${params}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            });
            const data = await res.json();
            grid.innerHTML = data.html;
            currentPage = data.current_page;
            lastPage    = data.last_page;
            updatePaginationUI();
            if (typeof AOS !== 'undefined') AOS.refreshHard();
            grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } catch (err) {
            console.error('Pagination error:', err);
        } finally {
            grid.style.opacity = '1';
            grid.style.pointerEvents = 'auto';
            isFetching = false;
        }
    }

    function updatePaginationUI() {
        const bar  = document.getElementById('pagination-bar');
        const prev = document.getElementById('btn-prev');
        const next = document.getElementById('btn-next');

        if (lastPage <= 1) {
            bar.classList.add('opacity-0', 'pointer-events-none');
        } else {
            bar.classList.remove('opacity-0', 'pointer-events-none');
        }

        document.getElementById('page-current').textContent = String(currentPage).padStart(2, '0');
        document.getElementById('page-total').textContent   = String(lastPage).padStart(2, '0');

        prev.classList.toggle('opacity-30',          currentPage <= 1);
        prev.classList.toggle('pointer-events-none', currentPage <= 1);
        next.classList.toggle('opacity-30',          currentPage >= lastPage);
        next.classList.toggle('pointer-events-none', currentPage >= lastPage);
    }
    </script>

    @include('component.layout.content-protection')
</body>
</html>