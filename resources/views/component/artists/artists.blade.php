<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kapakapa Art Gallery | Artists</title>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: '#C9A74E',
                        dark: '#050505',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap');

        h1, h3, .font-serif { font-family: 'Playfair Display', serif; }
        
        /* 60-70% Background Utama: Hitam Pekat */
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            -webkit-font-smoothing: antialiased; 
            background-color: #0a0a0a; 
            color: #ffffff;
        }
        
        .transition-luxury {
            transition: all 0.7s cubic-bezier(0.2, 1, 0.3, 1);
        }

        /* Custom Scrollbar Gold */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #050505; }
        ::-webkit-scrollbar-thumb { background: #C9A74E; }
    </style>
</head>
<body>
    @include('component.layout.navbar')


    <div class="min-h-screen selection:bg-gold/30">
        <main class="max-w-[1600px] mx-auto px-8 pt-10 pb-20 lg:pt-14">
            
            <header class="relative mb-24 border-l border-gold/40 pl-8 lg:pl-12">
                <span class="text-[10px] uppercase tracking-[0.5em] text-gold font-bold mb-3 block">Curated Collection</span>
                <h1 class="text-6xl md:text-8xl font-serif italic tracking-tighter leading-none mb-6 text-gray-300">
                    The Artists
                </h1>
                <div class="max-w-2xl">
                    <p class="text-xl md:text-2xl font-light leading-relaxed text-gray-300">
                        "Every artist dips his brush in his own soul."
                    </p>
                </div>
            </header>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-y-20 gap-x-10">
                @foreach($artists as $index => $artist)
                    <a href="{{ route('profile_art', $artist->id) }}" 
                    class="group cursor-pointer block {{ ($index % 5 == 1 || $index % 5 == 3) ? 'lg:mt-16' : '' }}"
                    data-aos="fade-up" 
                    data-aos-delay="{{ ($index % 5) * 100 }}"
                    data-aos-duration="1000">
                        
                        <div class="relative overflow-hidden aspect-[10/14] mb-7 bg-zinc-900 border border-white/5 transition-luxury group-hover:border-gold/50">
                            <img src="{{ $artist->profile_url ? asset($artist->profile_url) : 'https://api.dicebear.com/8.x/notionists/svg?seed=' . urlencode($artist->name) }}" 
                                class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-luxury group-hover:scale-110"
                                alt="{{ $artist->name }}">
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-luxury"></div>
                        </div>

                        <div class="px-1">
                            <h3 class="text-2xl font-serif leading-tight text-gray-300 transition-luxury group-hover:italic group-hover:text-gold">
                                {{ $artist->name }}
                            </h3>
                            
                            <div class="flex items-center gap-4 mt-3">
                                <div class="h-[1px] bg-gold/50 w-10 transition-all group-hover:w-16 group-hover:bg-gold"></div>
                                <p class="text-[9px] uppercase tracking-[0.3em] text-gray-300 font-semibold group-hover:text-slate-300 transition-colors">
                                    {{ $artist->birthplace }}
                                </p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-12 flex items-center gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center bg-zinc-900 text-gray-300 text-sm font-bold border border-white/5">
                        01
                    </div>
                    <span class="text-gray-700 font-light">/</span>
                    <span class="text-[11px] uppercase tracking-[0.2em] font-medium text-gray-300">12</span>
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
            duration: 1000,
            once: true,
            offset: 100,
            easing: 'ease-out-cubic',
        });

        const btn = document.getElementById('backToTop');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 400) {
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