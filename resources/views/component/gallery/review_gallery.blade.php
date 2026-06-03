<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: '#C9A74E',
                        dark: '#0a0a0a',
                    }
                }
            }
        }
    </script>
    <title>Kapakapa Art Gallery | Review Gallery</title>
    <style>
        html, body {
            background-color: #0a0a0a !important;
            color: #d1d5db !important;
            margin: 0;
            padding: 0;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #0a0a0a; }
        ::-webkit-scrollbar-thumb { background: #C9A74E; }

        /* Mencegah kebocoran background putih */
        .min-h-screen, main, section {
            background-color: transparent !important;
        }

        #modalImage {
            transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1), transform-origin 0.15s ease;
            will-change: transform, transform-origin;
        }

        #zoomIndicator.fade-out {
            animation: fade-out 0.4s ease forwards;
        }

        @keyframes fade-out {
            from { opacity: 1; backdrop-filter: blur(4px); }
            to { opacity: 0; backdrop-filter: blur(0px); }
        }

        /* Custom Scrollbar untuk Gallery Look */
        .custom-scrollbar::-webkit-scrollbar {
            width: 2px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(212, 175, 55, 0.2); /* Warna Gold transparan */
            transition: all 0.3s;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(212, 175, 55, 0.8);
        }

        /* Custom Styles for Modern Art Aesthetic */

        #commentOverlay {
            /* Background dengan tekstur noise halus agar terasa seperti kertas/kanvas */
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3仿真%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            background-blend-mode: overlay;
        }

        #commentOverlay .max-w-md {
            /* Glassmorphism yang lebih artistik */
            backdrop-filter: blur(40px) saturate(150%);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            position: relative;
        }

        /* Elemen Dekoratif: Garis Vertikal tipis khas desain Modernist */
        #commentOverlay .max-w-md::before {
            content: '';
            position: absolute;
            top: 0;
            left: 20px;
            width: 1px;
            height: 100%;
            background: linear-gradient(to bottom, transparent, rgba(212, 175, 55, 0.1), transparent);
            pointer-events: none;
        }

        /* Judul dengan letter-spacing ekstrim untuk kesan mewah */
        h3 {
            letter-spacing: -0.02em;
            word-spacing: 0.1em;
        }

        /* Styling Scrollbar khusus agar tidak merusak estetika */
        .custom-scrollbar::-webkit-scrollbar {
            width: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(212, 175, 55, 0.3);
            border-radius: 20px;
        }

        /* Animasi Entry untuk pesan */
        .flex.gap-3 {
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Input Interaction */
        input::placeholder {
            color: rgba(255, 255, 255, 0.2);
            text-transform: lowercase;
            letter-spacing: 0.05em;
        }

        /* Warna Emas Spesifik (Art Gallery Gold) */
        .text-gold {
            color: #D4AF37;
            filter: drop-shadow(0 0 2px rgba(212, 175, 55, 0.2));
        }

        .bg-gold {
            background-color: #D4AF37;
        }

        /* Efek Border Hover */
        .border-white\/5 {
            transition: border-color 0.4s ease;
        }

        .max-w-md:hover .border-white\/5 {
            border-color: rgba(212, 175, 55, 0.2);
        }

        .swiper-button-next, .swiper-button-prev {
            color: #C9A74E !important;
            background: rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
            width: 44px !important;
            height: 44px !important;
            border-radius: 50%;
            border: 1px solid rgba(201,167,78,0.2);
            transition: all 0.3s ease;
        }
        .swiper-button-next:after, .swiper-button-prev:after { font-size: 16px !important; }
        .swiper-button-next:hover, .swiper-button-prev:hover {
            background: rgba(201,167,78,0.1);
            border-color: #C9A74E;
        }
        .swiper-pagination-bullet { background: #fff !important; opacity: 0.3 !important; }
        .swiper-pagination-bullet-active {
            background: #C9A74E !important; opacity: 1 !important;
            width: 20px !important; border-radius: 4px !important;
        }

        .reviewSwiper,
        .reviewSwiper .swiper-slide {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body class="bg-dark text-gray-300 overflow-x-hidden">
    @include('component.layout.navbar')

    <main class="min-h-screen">
        <header class="max-w-7xl mx-auto pl-4 md:pl-6 pt-32 pb-20" data-aos="fade-right">
            <div class="relative">
                <h1 class="relative z-10 text-8xl md:text-9xl font-serif text-gray-300 tracking-tighter leading-none">
                    Gallery: {{ $styleName }}<span class="text-gold">.</span>
                </h1>

                <div class="mt-12 flex items-center gap-6 group">
                    <div class="flex items-center gap-4">
                        <span id="artworks-count" class="text-2xl font-light text-[#C9A74E] font-serif italic">
                            {{ $artworks->total() > 0 ? sprintf('%02d', $artworks->total()) : '00' }}
                        </span>
                        <div class="h-[1px] w-8 bg-zinc-700 group-hover:w-12 transition-all duration-500"></div>
                        <span class="text-[12px] text-zinc-400 font-medium uppercase tracking-[0.4em]">Pieces</span>
                    </div>
                </div>
            </div>
        </header>

        <section class="max-w-7xl mx-auto pl-4 md:pl-6 mb-24">
            <div class="border-t border-b border-white/5 py-16">
                <div class="relative w-full max-w-2xl group">
                    <span class="absolute -top-8 left-0 text-[9px] uppercase tracking-[0.4em] text-gray-300 group-focus-within:text-gold transition-colors">
                        Search Archive
                    </span>
                    <div class="relative w-full">
                        <input type="text" placeholder="KEYWORDS..." 
                            class="bg-transparent py-6 w-full text-sm tracking-[0.5em] text-gray-300 placeholder:text-zinc-800 uppercase border-b border-zinc-800 focus:outline-none focus:border-transparent focus:ring-0 peer transition-all duration-300">
                        <span class="absolute bottom-0 left-0 h-[1px] bg-gold w-0 transition-all duration-700 peer-focus:w-full"></span>
                        <div class="absolute right-0 bottom-6 flex items-center text-zinc-600 peer-focus:text-gold transition-all duration-500 group-hover:translate-x-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 7l-10 10M7 7h10v10"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="px-8 md:px-20 pb-20">
            <div id="grid-container" class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-12 gap-y-20">
                @include('component.gallery.partials.review-grid', compact('artworks'))
            </div>

            @include('component.partials.pagination-bar', [
                'paginator'   => $artworks,
                'containerId' => 'grid-container',
            ])
        </section>
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

    <div id="artModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 md:p-8">
        <div class="absolute inset-0 bg-black/95 backdrop-blur-sm"></div>
        
        <div class="relative bg-zinc-900 border border-white/10 w-full max-w-5xl flex flex-col md:flex-row shadow-2xl animate-in fade-in zoom-in duration-300">
            
            <button onclick="closeModal()" class="absolute top-5 right-5 z-[130] group outline-none">
                <div class="relative w-11 h-11 flex items-center justify-center rounded-full bg-white/5 border border-white/20 group-hover:border-gold/60 transition-all duration-300 shadow-lg overflow-hidden">
                    <div class="absolute inset-0 rounded-full bg-gold translate-y-full group-hover:translate-y-0 transition-transform duration-500 ease-out"></div>
                    <svg class="relative z-10 w-5 h-5 text-white group-hover:text-black group-hover:rotate-90 transition-all duration-300" 
                        fill="none" stroke="currentColor" stroke-width="2" 
                        stroke-linecap="round" viewBox="0 0 24 24">
                        <path d="M6 6l12 12M6 18L18 6"/>
                    </svg>
                </div>
            </button>

            <div id="imageContainer" class="w-full md:w-2/3 bg-black relative" style="height: 85vh;">

                <div id="reviewPageIndicator" class="absolute top-5 right-5 z-20 px-3 py-1.5 rounded-full border border-white/10 bg-black/50 backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-500">
                    <span class="text-white text-[11px] tracking-[0.1em]">
                        <span id="reviewCurrentPage">1</span> / <span id="reviewTotalPages">1</span>
                    </span>
                </div>

                <div class="swiper reviewSwiper w-full h-full">
                    <div class="swiper-wrapper" id="reviewSwiperWrapper"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-pagination !bottom-6"></div>
                </div>
            </div>

            <div class="w-full md:w-1/3 p-8 md:p-12 flex flex-col justify-center border-t md:border-t-0 md:border-l border-white/5 bg-zinc-900/50">
                <h2 id="modalTitle" class="text-4xl font-serif text-gray-300 mb-2"></h2>
                <p id="modalAuthor" class="text-gold tracking-[0.2em] uppercase text-xs mb-8"></p>
                
                <div class="space-y-6">
                    <div class="border-b border-white/5 pb-4">
                        <span class="text-zinc-500 text-[10px] uppercase tracking-widest block mb-1">Collection Info</span>
                        <p id="modalCount" class="text-gray-300 text-sm"></p>
                    </div>

                    <div id="modalPriceWrap" class="hidden border-b border-white/5 pb-4">
                        <span class="text-zinc-500 text-[10px] uppercase tracking-widest block mb-1">Price</span>
                        <p id="modalPrice" class="text-gold font-serif italic text-2xl"></p>
                    </div>
                    
                    <p class="text-zinc-400 text-sm leading-relaxed font-light italic">
                        "This artwork is part of a curated collection showcasing the intersection of classical technique and modern vision."
                    </p>

                    <div class="mt-8 flex gap-3">
                        <button class="group relative flex-1 overflow-hidden border border-[#C9A74E]/30 py-4 transition-all duration-500">
                            <div class="absolute inset-0 bg-[#C9A74E] translate-y-full group-hover:translate-y-0 transition-transform duration-500 ease-out"></div>
                            
                            <span class="relative z-10 text-[#C9A74E] group-hover:text-black text-[9px] font-bold uppercase tracking-[0.3em] flex items-center justify-center transition-colors duration-500">
                                Buy Now
                            </span>

                            <div class="absolute inset-0 border border-transparent group-hover:border-[#C9A74E] transition-colors duration-500"></div>
                        </button>
                        
                        <div class="flex gap-2">
                            <button onclick="toggleCommentModal()" class="group relative w-12 h-12 flex items-center justify-center border border-[#C9A74E]/30 overflow-hidden transition-all duration-500">
                                <div class="absolute inset-0 bg-[#C9A74E] translate-y-full group-hover:translate-y-0 transition-transform duration-500 ease-out"></div>
                                
                                <svg class="relative z-10 w-5 h-5 text-[#C9A74E] transition-all duration-500 group-hover:text-black group-hover:scale-110 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>

                                <div class="absolute inset-0 border border-transparent group-hover:border-[#C9A74E] transition-colors duration-500"></div>
                            </button>

                            <button class="group relative w-12 h-12 flex items-center justify-center border border-rose-500/30 overflow-hidden transition-all duration-500">
                                <div class="absolute inset-0 bg-rose-500 translate-y-full group-hover:translate-y-0 transition-transform duration-500 ease-out"></div>
                                
                                <svg class="relative z-10 w-5 h-5 text-rose-500 transition-all duration-500 group-hover:text-white group-hover:scale-110 group-hover:-rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>

                                <div class="absolute inset-0 border border-transparent group-hover:border-rose-500 transition-colors duration-500"></div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="commentOverlay" 
                class="fixed inset-0 z-[140] bg-black/90 backdrop-blur-2xl flex items-center justify-center p-6 invisible opacity-0 transition-all duration-500 ease-in-out" 
                onclick="if(event.target === this) toggleCommentModal()">

                <div id="commentContent" 
                    class="w-full max-w-md flex flex-col bg-zinc-900/80 rounded-3xl border border-white/5 h-[80vh] max-h-[600px] overflow-hidden shadow-2xl transform scale-95 transition-all duration-500 ease-out">

                    <div class="p-6 border-b border-white/10 flex justify-between items-center bg-zinc-900/50">
                        <div>
                            <h3 class="text-gray-300 font-serif italic text-2xl tracking-tight">Curator's Notes</h3>
                            <p class="text-[9px] text-gold uppercase tracking-[0.4em] mt-1">Community Discussion</p>
                        </div>
                        <button onclick="toggleCommentModal()" class="group relative p-2 outline-none flex items-center justify-center transition-all duration-300">
                            <div class="absolute inset-0 rounded-full border border-[#C9A74E]/0 group-hover:border-[#C9A74E]/20 group-hover:scale-110 transition-all duration-500"></div>
                            <div class="absolute inset-0 rounded-full bg-[#C9A74E]/0 group-hover:bg-[#C9A74E]/5 transition-all duration-500"></div>

                            <svg class="relative z-10 w-6 h-6 text-white/20 group-hover:text-[#C9A74E] transition-all duration-500 group-hover:rotate-90" 
                                fill="none" 
                                stroke="currentColor" 
                                viewBox="0 0 24 24">
                                <path d="M6 18L18 6M6 6l12 12" 
                                    stroke-width="1.2" 
                                    stroke-linecap="round" 
                                    stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto p-6 space-y-6 custom-scrollbar bg-black/20">
                        <div class="flex gap-3 max-w-[90%] group">
                            <div class="w-8 h-8 rounded-full bg-zinc-800 border border-white/5 flex-shrink-0 flex items-center justify-center text-[10px] text-zinc-500 font-bold group-hover:border-gold/30 transition-colors">AD</div>
                            <div class="bg-white/[0.03] border border-white/5 p-4 rounded-2xl rounded-tl-none">
                                <p class="text-[12px] text-zinc-400 font-light leading-relaxed italic">
                                    "Pencahayaan yang sangat dramatis. Terlihat seperti perpaduan gaya klasik Caravaggio."
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-3 flex-row-reverse max-w-[90%] ml-auto group">
                            <div class="w-8 h-8 rounded-full bg-gold/10 border border-gold/30 flex-shrink-0 flex items-center justify-center text-[10px] text-gold font-bold group-hover:bg-gold/20 transition-all">EV</div>
                            <div class="bg-gold/5 border border-gold/20 p-4 rounded-2xl rounded-tr-none text-right">
                                <p class="text-[12px] text-gray-300 italic font-light leading-relaxed">
                                    "I love how the textures pop out when zoomed in."
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-zinc-900/80 border-t border-white/5">
                        <div class="relative flex items-center gap-3 bg-white/[0.03] border border-white/10 p-1.5 pl-5 rounded-full focus-within:border-gold/50 transition-all duration-500">
                            <input type="text" placeholder="Share your perspective..." class="flex-1 bg-transparent py-2 text-xs text-white outline-none italic placeholder:text-zinc-600">
                            <button class="group relative bg-[#C9A74E] text-black p-2.5 rounded-full overflow-hidden transition-all duration-500 hover:bg-[#B3923E] active:scale-90 shadow-lg shadow-[#C9A74E]/20">
        
                                <div class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-700 bg-gradient-to-r from-transparent via-white/40 to-transparent"></div>

                                <svg class="w-3.5 h-3.5 rotate-45 transition-all duration-500 group-hover:-translate-y-10 group-hover:translate-x-10 opacity-100 group-hover:opacity-0" 
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>

                                <svg class="absolute inset-0 m-auto w-3.5 h-3.5 rotate-45 -translate-x-10 translate-y-10 opacity-0 transition-all duration-500 group-hover:translate-x-0 group-hover:translate-y-0 group-hover:opacity-100" 
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.querySelector('input[placeholder="KEYWORDS..."]')?.addEventListener('input', function() {
            const query = this.value.trim();
            
            clearTimeout(this._searchTimeout);
            this._searchTimeout = setTimeout(async () => {
                const grid = document.getElementById(GRID_ID);
                grid.style.opacity = '0.3';
                grid.style.pointerEvents = 'none';
                
                const params = new URLSearchParams({ 
                    page: 1, 
                    search: query,
                    ...EXTRA_PARAMS 
                });
                
                const res = await fetch(`${AJAX_URL}?${params}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                });
                const data = await res.json();
                grid.innerHTML = data.html;
                currentPage = data.current_page;
                lastPage = data.last_page;
                updatePaginationUI();
                if (typeof AOS !== 'undefined') AOS.refreshHard();
                grid.style.opacity = '1';
                grid.style.pointerEvents = 'auto';
            }, 400);
        });

        // --- LOGIKA ZOOM (Smooth & Intuitive) ---
        function zoomIn(event) {
            const img = event.currentTarget;
            const { left, top, width, height } = img.getBoundingClientRect();
            
            // Hitung posisi kursor dalam persen
            const x = ((event.clientX - left) / width) * 100;
            const y = ((event.clientY - top) / height) * 100;

            img.style.transformOrigin = `${x}% ${y}%`;
            img.style.transform = "scale(2.5)";
        }

        function zoomOut(event) {
            const img = event.currentTarget;
            img.style.transform = "scale(1)";
            img.style.transformOrigin = "center center";
        }

        // --- LOGIKA MODAL UTAMA ---
        let reviewSwiper = null;
        let reviewIndicatorTimeout = null;

        function initReviewSwiper() {
            if (reviewSwiper) reviewSwiper.destroy(true, true);
            reviewSwiper = new Swiper(".reviewSwiper", {
                loop: false,
                speed: 700,
                grabCursor: true,
                pagination: { el: ".swiper-pagination", clickable: true },
                navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
                keyboard: { enabled: true },
                on: {
                    slideChange: function() { updateReviewIndicator(this.activeIndex + 1, this.slides.length); },
                    init: function() { updateReviewIndicator(this.activeIndex + 1, this.slides.length); }
                }
            });
        }

        let isReviewScrolling = false;

        document.addEventListener('wheel', (e) => {
            // Cek modal terbuka
            const modal = document.getElementById('artModal');
            if (!modal || modal.classList.contains('hidden') || !reviewSwiper) return;

            // Hanya aktif saat hover di area swiper
            const isOverSwiper = e.target.closest('.reviewSwiper');
            if (!isOverSwiper) return;

            e.preventDefault();
            if (isReviewScrolling) return;

            isReviewScrolling = true;
            if (e.deltaY > 0) {
                reviewSwiper.slideNext();
            } else {
                reviewSwiper.slidePrev();
            }

            setTimeout(() => {
                isReviewScrolling = false;
            }, 300); // debounce 300ms
        }, { passive: false });

        function updateReviewIndicator(current, total) {
            const indicator = document.getElementById('reviewPageIndicator');
            document.getElementById('reviewCurrentPage').innerText = current;
            document.getElementById('reviewTotalPages').innerText = total;
            indicator.classList.remove('opacity-0');
            indicator.classList.add('opacity-100');
            if (reviewIndicatorTimeout) clearTimeout(reviewIndicatorTimeout);
            reviewIndicatorTimeout = setTimeout(() => {
                indicator.classList.remove('opacity-100');
                indicator.classList.add('opacity-0');
            }, 2000);
        }

        function openModal(imagesJson, title, author, count, price) {
            const modal   = document.getElementById('artModal');
            const wrapper = document.getElementById('reviewSwiperWrapper');

            // Parse images — bisa berupa JSON array string atau URL tunggal
            let imagesArray = [];
            try {
                const parsed = JSON.parse(imagesJson);
                imagesArray = Array.isArray(parsed) ? parsed : [parsed];
            } catch(e) {
                imagesArray = [imagesJson];
            }

            // Inject slides
            wrapper.innerHTML = '';
            imagesArray.forEach(imgUrl => {
                const slide = document.createElement('div');
                slide.className = 'swiper-slide';
                slide.innerHTML = `
                    <img src="${imgUrl}" alt="${title}"
                        class="w-full h-full object-cover transition-transform duration-700 ease-out cursor-zoom-in"
                        onmousemove="zoomIn(event)"
                        onmouseleave="zoomOut(event)">
                `;
                wrapper.appendChild(slide);
            });

            document.getElementById('modalTitle').innerText  = title;
            document.getElementById('modalAuthor').innerText = author;
            document.getElementById('modalCount').innerText  = count;

            // Tampilkan harga
            const priceEl   = document.getElementById('modalPrice');
            const priceWrap = document.getElementById('modalPriceWrap');
            if (price && price.trim() !== '') {
                priceEl.innerText = price;
                priceWrap.classList.remove('hidden');
            } else {
                priceWrap.classList.add('hidden');
            }

            setTimeout(() => {
                initReviewSwiper();
                updateReviewIndicator(1, imagesArray.length);
            }, 100);

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('artModal');
            const commentOverlay = document.getElementById('commentOverlay');
            const modalImg = document.getElementById('modalImage');

            // Sembunyikan Modal Utama
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            
            // Reset Zoom Gambar agar saat buka gambar lain tidak miring
            if (modalImg) modalImg.style.transform = "scale(1)";

            // Pastikan Overlay Komentar juga ikut tertutup
            if (commentOverlay) {
                commentOverlay.classList.add('hidden');
                commentOverlay.classList.remove('flex');
            }
            
            document.body.style.overflow = 'auto'; // Unlock scroll body
        }

        // --- LOGIKA KOMENTAR ---
        function toggleCommentModal() {
            const overlay = document.getElementById('commentOverlay');
            const content = document.getElementById('commentContent');

            if (overlay.classList.contains('invisible')) {
                // Tampilkan Modal
                overlay.classList.remove('invisible');
                overlay.classList.add('opacity-100');
                
                // Efek Scale Up konten
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            } else {
                // Sembunyikan Modal
                content.classList.remove('scale-100');
                content.classList.add('scale-95');
                
                overlay.classList.remove('opacity-100');
                
                // Tunggu transisi selesai sebelum memberikan invisible
                setTimeout(() => {
                    overlay.classList.add('invisible');
                }, 500); // 500ms sesuai duration-500 di HTML
            }
        }

        // --- LOGIKA PENUTUPAN MODAL UTAMA JUGA MENUTUP OVERLAY KOMENTAR ---
        function closeModal() {
            const modal = document.getElementById('artModal');
            const commentOverlay = document.getElementById('commentOverlay');

            modal.classList.add('hidden');
            modal.classList.remove('flex');
            
            // Pastikan overlay komentar ikut tertutup saat modal utama ditutup
            if (commentOverlay) {
                commentOverlay.classList.add('hidden');
                commentOverlay.classList.remove('flex');
            }
            
            document.body.style.overflow = 'auto';
        }

        AOS.init({ duration: 1200, once: true, offset: 50, easing: 'ease-in-out-cubic' });
        const btn = document.getElementById('backToTop');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 500) {
                btn.classList.remove('opacity-0', 'translate-y-10', 'pointer-events-none');
                btn.classList.add('opacity-100', 'translate-y-0', 'pointer-events-auto');
            } else {
                btn.classList.add('opacity-0', 'translate-y-10', 'pointer-events-none');
                btn.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
            }
        });

        document.querySelector('input[placeholder="KEYWORDS..."]')?.addEventListener('input', function() {
            clearTimeout(this._searchTimeout);
            this._searchTimeout = setTimeout(async () => {
                const grid = document.getElementById(GRID_ID);
                grid.style.opacity = '0.3';
                grid.style.pointerEvents = 'none';

                const params = new URLSearchParams({ 
                    page: 1, 
                    search: this.value.trim(),
                    ...EXTRA_PARAMS 
                });

                const res = await fetch(`${AJAX_URL}?${params}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                });
                const data = await res.json();
                grid.innerHTML = data.html;
                currentPage = data.current_page;
                lastPage = data.last_page;

                const countEl = document.getElementById('artworks-count');
                if (countEl && data.total !== undefined) {
                    countEl.textContent = String(data.total).padStart(2, '0');
                }

                updatePaginationUI();
                if (typeof AOS !== 'undefined') AOS.refreshHard();
                grid.style.opacity = '1';
                grid.style.pointerEvents = 'auto';
            }, 400);
        });

        btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    </script>

    <script>
    const AJAX_URL     = "{{ route('gallery') }}";
    const EXTRA_PARAMS = { style: "{{ addslashes($styleName) }}" }; 
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

            const countEl = document.getElementById('artworks-count');
            if (countEl && data.total) {
                countEl.textContent = String(data.total).padStart(2, '0');
            }

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

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</body>
</html>