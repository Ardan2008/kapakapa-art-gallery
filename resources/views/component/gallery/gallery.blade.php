<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
    <title>Kuthoadem Gallery | Gallery</title>
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

            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-12 gap-y-20">
                @foreach($styles as $item)
                <div class="group block relative" data-aos="fade-up">
                    <a href="{{ route('gallery', ['style' => $item['title']]) }}" class="absolute inset-0 z-10" aria-label="View Gallery"></a>

                    <div class="relative flex gap-2 h-[450px] overflow-hidden mb-8 transition-all duration-700 group-hover:shadow-[0_40px_80px_-20px_rgba(0,0,0,1)]">
                        <div class="w-2/3 h-full overflow-hidden bg-zinc-950 grayscale group-hover:grayscale-0 transition-all duration-1000 ease-in-out">
                            <img src="{{ $item['main_img'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover scale-110 group-hover:scale-100 transition-transform duration-1000">
                        </div>
                        
                        <div class="w-1/3 flex flex-col gap-2">
                            <div class="h-1/2 overflow-hidden bg-zinc-950 grayscale group-hover:grayscale-0 transition-all duration-1000 delay-75">
                                <img src="{{ $item['sub_img1'] }}" class="w-full h-full object-cover">
                            </div>
                            <div class="h-1/2 overflow-hidden bg-zinc-950 relative grayscale group-hover:grayscale-0 transition-all duration-1000 delay-150">
                                <img src="{{ $item['sub_img2'] }}" class="w-full h-full object-cover opacity-30 group-hover:opacity-100 transition-opacity">
                                <div class="absolute inset-0 flex items-center justify-center bg-[#0a0a0a]/60 group-hover:bg-transparent transition-all duration-500">
                                    <span class="text-[#c9a74e] font-serif italic text-2xl group-hover:scale-110 transition-transform">+{{ $item['count'] }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="absolute inset-0 border border-[#c9a74e]/0 group-hover:border-[#c9a74e]/20 transition-all duration-700 pointer-events-none"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a]/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>

                    <div class="space-y-4 px-2">
                        <div class="flex items-center gap-4">
                            <div class="h-[1px] w-0 group-hover:w-16 bg-[#c9a74e] transition-all duration-700 ease-out"></div>
                            <h3 class="text-3xl font-serif text-[#d1d5db] group-hover:text-[#c9a74e] transition-colors duration-500 italic tracking-tight">
                                {{ $item['title'] }}
                            </h3>
                        </div>
                        <div class="flex justify-between items-center text-[10px] uppercase tracking-[0.4em] text-zinc-500 pl-0 group-hover:pl-4 transition-all duration-700">
                            
                            <div class="relative z-20 group/author inline-block hover:text-white transition-colors">
                                <span>{{ $item['author'] }}</span>
                            </div>

                            <span class="text-[#c9a74e]/40 group-hover:text-[#c9a74e] transition-colors relative z-20">{{ $item['count'] }} pieces</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </section>

            <div class="mt-12 flex items-center gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center bg-zinc-900 text-gray-300 text-sm font-bold border border-white/5">
                        01
                    </div>
                    <span class="text-gray-700 font-light">/</span>
                    <span class="text-[11px] uppercase tracking-[0.2em] font-medium text-gray-300">{{ sprintf('%02d', count($styles)) }}</span>
                </div>

                <a href="#" class="group">
                    <div class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center 
                                bg-transparent group-hover:bg-gold group-hover:border-gold 
                                transition-all duration-500 ease-out">
                        <svg class="w-4 h-4 text-gray-300 group-hover:text-black transform group-hover:translate-x-0.5 transition-all" 
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M9 5l7 7-7 7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </a>
            </div>
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
</body>
</html>