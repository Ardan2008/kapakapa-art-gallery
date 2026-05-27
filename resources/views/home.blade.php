<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuthoadem Gallery</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        html {
            scroll-behavior: smooth;
            /* Mencegah pergerakan patah-patah pada trackpad/scroll wheel */
            -webkit-font-smoothing: antialiased;
        }

        /* Optimasi durasi scroll untuk elemen reveal agar tidak kaku */
        .reveal {
            will-change: transform, opacity;
        }

        body { font-family: 'Inter', sans-serif; scroll-behavior: smooth; }
        .font-serif { font-family: 'Playfair Display', serif; }
        
        /* Reveal Animation */
        .reveal { opacity: 0; 
            transform: translateY(30px); 
            transition: all 1s cubic-bezier(0.22, 1, 0.36, 1); 
        }

        .reveal.active { opacity: 1; transform: translateY(0); }

        /* Custom Mobile Menu */
        #mobileMenu {
            transition: all 0.6s cubic-bezier(0.77, 0, 0.175, 1);
            clip-path: circle(0% at 100% 0%);
        }

        #mobileMenu.active {
            clip-path: circle(150% at 100% 0%);
        }

        /* Floating Effect for Art Elements */
        @keyframes floating {
            0% { transform: translateY(0px) rotate(3deg); }
            50% { transform: translateY(-20px) rotate(1deg); }
            100% { transform: translateY(0px) rotate(3deg); }
        }

        .float-art { animation: floating 6s ease-in-out infinite; }

        /* Pastikan body tidak bisa di-scroll selama loading */
        body.loading {
            overflow: hidden !important;
            height: 100vh !important;
            position: fixed;
            width: 100%;
        }

        .marquee-left { animation: scrollLeft 40s linear infinite; }
        .marquee-right { animation: scrollRight 40s linear infinite; }

        @keyframes scrollLeft {
            from { transform: translateX(0); }
            to { transform: translateX(-50%); }
        }
        @keyframes scrollRight {
            from { transform: translateX(-50%); }
            to { transform: translateX(0); }
        }

        #main-brand {
            text-shadow: 0 10px 40px rgba(0,0,0,0.5);
            -webkit-font-smoothing: antialiased;
        }

        #art-loader {
            will-change: opacity;
        }
    </style>
</head>
<body class="bg-[#fafafa] antialiased text-gray-300 overflow-x-hidden">

    <div id="art-loader" style="display: none;" class="fixed inset-0 z-[99999] bg-[#050505] overflow-hidden touch-none pointer-events-auto">
        
        <div id="loader-content-wrap" class="relative w-full h-full flex items-center justify-center">
            
            <div class="absolute inset-0 flex flex-col justify-around opacity-[0.04] select-none pointer-events-none skew-y-[-12deg] scale-110">
                <h2 class="text-[15vw] font-black text-white whitespace-nowrap leading-none marquee-left">KUTHOADEM KUTHOADEM KUTHOADEM</h2>
                <h2 class="text-[15vw] font-black text-white whitespace-nowrap leading-none marquee-right">KUTHOADEM KUTHOADEM KUTHOADEM</h2>
                <h2 class="text-[15vw] font-black text-white whitespace-nowrap leading-none marquee-left">KUTHOADEM KUTHOADEM KUTHOADEM</h2>
            </div>

            <svg class="absolute inset-0 w-full h-full opacity-20 pointer-events-none" viewBox="0 0 1000 1000">
                <path class="art-line" d="M-100,200 Q500,0 1100,200" stroke="#C9A74E" stroke-width="1" fill="none" />
                <path class="art-line" d="M-100,800 Q500,1000 1100,800" stroke="#C9A74E" stroke-width="1" fill="none" />
                <circle class="art-line" cx="15%" cy="25%" r="60" stroke="#C9A74E" stroke-width="0.5" fill="none" />
                <line class="art-line" x1="0" y1="0" x2="1000" y2="1000" stroke="#C9A74E" stroke-width="0.2" />
            </svg>

            <div class="relative z-10 text-center">
                <div class="overflow-hidden">
                    <h1 id="main-brand" class="text-gray-300 font-serif italic text-[16vw] md:text-[12vw] leading-none tracking-tighter opacity-0 translate-y-full">
                        Kuthoadem
                    </h1>
                </div>
                <div id="sub-brand" class="flex items-center justify-center gap-4 mt-8 opacity-0">
                    <div class="w-12 h-[1px] bg-[#C9A74E]"></div>
                    <p class="text-[#C9A74E] tracking-[1.2em] text-[10px] md:text-sm uppercase font-light">Gallery</p>
                    <div class="w-12 h-[1px] bg-[#C9A74E]"></div>
                </div>
            </div>
        </div>

        <div id="swipe-container" class="absolute inset-0 z-[100000] translate-y-full">
            <svg class="absolute top-[-118px] w-full h-[120px] fill-[#C9A74E]" viewBox="0 0 1440 120" preserveAspectRatio="none">
                <path d="M0,64L120,80C240,96,480,128,720,128C960,128,1200,96,1320,80L1440,64V120H0Z"></path>
            </svg>
            <div class="w-full h-full bg-[#C9A74E]"></div>
        </div>
    </div>

    <section class="relative min-h-screen w-full overflow-hidden bg-[#0a0a0a] flex flex-col">
        <nav class="relative z-50 flex items-center justify-between px-6 md:px-16 py-8 text-gray-300">
            <a href="/">
                <div class="text-2xl md:text-3xl font-bold tracking-[0.1em] uppercase font-serif text-gray-300">
                    Kuthoadem<span class="font-light italic opacity-70 ml-1 text-amber-400">Gallery</span>
                </div>
            </a>

            <div class="hidden xl:flex items-center gap-12 text-[15px] uppercase tracking-[0.3em]" style="font-family: 'Cormorant Garamond', serif;">
                
                <a href="/" class="group relative py-1">
                    <span>Home</span>
                    <span class="absolute bottom-0 left-0 w-full h-[1px] bg-[#C9A74E] scale-x-0 origin-left transition-transform duration-500 ease-out group-hover:scale-x-100"></span>
                </a>

                <a href="/artists" class="group relative py-1">
                    <span>Artists</span>
                    <span class="absolute bottom-0 left-0 w-full h-[1px] bg-[#C9A74E] scale-x-0 origin-left transition-transform duration-500 ease-out group-hover:scale-x-100"></span>
                </a>

                <a href="/gallery" class="group relative py-1">
                    <span>Gallery</span>
                    <span class="absolute bottom-0 left-0 w-full h-[1px] bg-[#C9A74E] scale-x-0 origin-left transition-transform duration-500 ease-out group-hover:scale-x-100"></span>
                </a>
                
            </div>

            <button id="hamburgerBtn" class="xl:hidden flex flex-col gap-2 items-end focus:outline-none group">
                <span class="w-8 h-[1px] bg-white group-hover:w-10 transition-all"></span>
                <span class="w-6 h-[1px] bg-[#C9A74E]"></span>
            </button>
        </nav>

        {{-- hero section --}}
        <div id="hero-section" class="relative min-h-screen w-full overflow-hidden flex items-center justify-center px-6 md:px-20 py-20 font-sans">
            
            <div class="absolute inset-0">
                <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#ffffff 0.5px, transparent 0.5px); background-size: 30px 30px;"></div>
                
                <div class="absolute top-1/4 -left-20 w-[500px] h-[500px] bg-amber-500/5 rounded-full blur-[120px] pointer-events-none"></div>
                <div class="absolute bottom-1/4 -right-20 w-[400px] h-[400px] bg-blue-500/5 rounded-full blur-[100px] pointer-events-none"></div>
            </div>

            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute top-20 right-[10%] w-32 h-32 border border-white/5 rounded-full rotate-12"></div>
                <div class="absolute bottom-40 left-[5%] w-24 h-24 border border-amber-400/10 rotate-45"></div>
                
                <div class="absolute top-1/2 left-10 w-32 h-[1px] bg-gradient-to-r from-transparent to-amber-400/30 rotate-45"></div>
                <div class="absolute bottom-1/3 right-20 w-48 h-[1px] bg-gradient-to-l from-transparent to-gray-500/30 -rotate-12"></div>
            </div>

            <div class="relative z-20 flex flex-col items-center text-center max-w-5xl">
                
                <div class="flex items-center gap-4 mb-8" data-aos="fade-up">
                    <div class="w-12 h-[1px] bg-gradient-to-r from-transparent to-amber-500"></div>
                    <span class="text-[#C9A74E] uppercase tracking-[0.6em] text-[10px] md:text-xs font-semibold">
                        The Painted Revivale
                    </span>
                    <div class="w-12 h-[1px] bg-gradient-to-l from-transparent to-amber-500"></div>
                </div>

                <h1 class="text-gray-300 text-6xl md:text-8xl lg:text-[10rem] font-serif leading-[0.85] mb-12 select-none tracking-tighter" 
                    data-aos="fade-up" data-aos-delay="200">
                    Canvas <br> 
                    <span class="flex items-center justify-center md:justify-start gap-4 mt-4">
                        <span class="italic font-light text-gray-500/40 text-4xl md:text-6xl lg:text-7xl tracking-tight">
                            of
                        </span>
                        <span class="italic font-light text-gray-500/60 tracking-tight">
                            Eternity
                        </span>
                    </span>
                </h1>
                
                <div class="flex flex-col items-center gap-8" data-aos="fade-up" data-aos-delay="400">
                    
                    <p class="text-gray-400/80 text-base md:text-xl max-w-xl font-light leading-relaxed tracking-wide">
                        A sanctuary where art comes alive across time. Explore paintings that capture emotion and timeless beauty. From classic to modern works, each piece tells a story beyond words, inviting you into a world of color and expression.
                    </p>

                    <div class="relative z-20 flex flex-col items-center text-center max-w-5xl">
                        <div class="flex flex-col items-center gap-3">
                            <div id="scroll-icon" class="w-[26px] h-[42px] border-2 border-amber-400/30 rounded-full relative flex justify-center cursor-pointer transition-opacity duration-300">
                                <div class="w-1 h-2 bg-amber-400 rounded-full mt-2 animate-bounce shadow-[0_0_8px_rgba(251,191,36,0.6)]"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <div class="bg-[#0a0a0a] min-h-screen">

    <section class="max-w-[1600px] mx-auto px-6 -translate-y-16 relative z-30">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
            <?php 
            $galleryImages = ['45', '46', '47', '48', '49'];
            
            foreach ($galleryImages as $imgId): 
            ?>
                <div class="relative h-56 md:h-80 overflow-hidden group bg-[#1a1a1a] border border-white/5 shadow-2xl block">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-80 group-hover:opacity-30 transition-all z-10 duration-700"></div>
                    
                    <img src="https://picsum.photos/600/800?random=<?php echo $imgId; ?>" 
                        class="absolute inset-0 object-cover w-full h-full grayscale group-hover:grayscale-0 group-hover:scale-110 transition-all duration-1000 ease-out" 
                        alt="Gallery Artwork">

                </div>
            <?php endforeach; ?>
        </div>
    </section>

    {{-- Artists Section --}}


    <div class="bg-[#0a0a0a] min-h-screen">
        <section class="relative py-32 bg-[#0a0a0a] overflow-hidden text-gray-400">
            <div class="absolute top-0 left-0 w-full h-full pointer-events-none opacity-[0.02] select-none uppercase font-black text-[20vw] leading-none text-white">
                Visionaries
            </div>

            <div class="max-w-[1440px] mx-auto px-8 relative z-10">
                <div class="flex flex-col md:flex-row justify-between items-start mb-24 gap-12">
                    <div class="md:w-1/2" data-aos="fade-right" data-aos-duration="1000">
                        <h2 class="text-[10px] uppercase tracking-[0.8em] text-[#C9A74E] font-bold mb-6">The Collective</h2>
                        <h3 class="text-6xl md:text-8xl font-serif italic leading-none text-gray-300">
                            Behind the <br> <span class="md:ml-20 text-gray-300">Mastery</span>
                        </h3>
                    </div>

                    <div class="md:w-1/3 mt-10 md:mt-24" data-aos="fade-left" data-aos-delay="300" data-aos-duration="1000">
                        <p class="text-gray-400 text-sm leading-relaxed font-light border-l-2 border-[#C9A74E]/40 pl-8 mb-6">
                            We collaborate with artists who dare to challenge the status quo, blending ancestral techniques with the raw energy of the modern era.
                        </p>
                        
                        <div class="pl-10"> 
                            <a href="{{ route('artists') }}" class="group inline-flex items-center gap-3 text-[#C9A74E] text-[10px] uppercase tracking-[0.3em] font-bold transition-all">
                                <span>Explore Full Artists</span>
                                <span class="w-12 h-[1px] bg-[#C9A74E]/30 transition-all group-hover:w-20 group-hover:bg-[#C9A74E]"></span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="swiper artistSwiper">
                    <div class="swiper-wrapper">
                        @foreach ($featured_artists as $index => $artist)
                            <div class="swiper-slide pb-20">
                                <div class="group" 
                                    data-aos="fade-up" 
                                    data-aos-delay="{{ ($index % 3 + 1) * 200 }}" 
                                    data-aos-duration="1000">
                                    
                                    <a href="{{ route('profile_art', $artist->id) }}" class="block cursor-pointer">
                                        
                                        <div class="relative aspect-[4/5] overflow-hidden bg-[#1a1a1a] mb-8 shadow-2xl">
                                            <img src="{{ $artist->profile_url ?? 'https://api.dicebear.com/8.x/notionists/svg?seed=' . urlencode($artist->name) }}" 
                                                class="w-full h-full object-cover transition-all duration-[1.5s] ease-out grayscale group-hover:grayscale-0 group-hover:scale-105" 
                                                alt="{{ $artist->name }}">
                                            
                                            <div class="absolute inset-0 bg-black/40 group-hover:bg-transparent transition-colors duration-500"></div>
                                        </div>

                                        <div class="relative text-center md:text-left">
                                            <h4 class="text-3xl font-serif text-gray-300 mb-2 group-hover:text-[#C9A74E] transition-colors duration-500 italic">
                                                {{ $artist->name }}
                                            </h4>
                                            <div class="flex items-center justify-center md:justify-start gap-3">
                                                <span class="w-4 h-[1px] bg-[#C9A74E]/50 transition-all group-hover:w-8 group-hover:bg-[#C9A74E]"></span>
                                                <span class="text-[10px] uppercase tracking-[0.4em] text-[#C9A74E] font-medium">
                                                    {{ $artist->birthplace }}
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Gallery --}}

    <section class="max-w-full bg-[#0a0a0a] py-32 px-8 overflow-hidden">
        <div class="max-w-[1440px] mx-auto">
            
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-32 gap-12">
                <div data-aos="fade-up">
                    <span class="text-[#C9A74E] text-[10px] uppercase tracking-[0.8em] font-bold mb-6 block">Seasonal Collection</span>
                    <h2 class="text-6xl md:text-8xl font-serif italic text-gray-300 leading-none">Featured <br> <span class="text-gray-300">Curations</span></h2>
                    <div class="h-[1px] w-24 bg-[#C9A74E] mt-10"></div>
                </div>
                
                <div class="md:max-w-xs" data-aos="fade-up" data-aos-delay="200">
                    <p class="text-gray-400 text-sm leading-relaxed font-light border-l-2 border-[#C9A74E]/40 pl-8 mb-6">
                        A meticulous selection of masterpieces, bridging the gap between classical soul and contemporary vision.
                    </p>
                    
                    <div class="pl-8">
                        <a href="/gallery" class="group inline-flex items-center gap-3 text-[#C9A74E] text-[10px] uppercase tracking-[0.3em] font-bold transition-all">
                            Explore Full Gallery 
                            <span class="w-12 h-[1px] bg-[#C9A74E]/30 transition-all group-hover:w-20 group-hover:bg-[#C9A74E]"></span>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-12 gap-y-24 items-start">
                @foreach ($featured_categories as $index => $art)
                    <div class="group" 
                        data-aos="fade-up" 
                        data-aos-delay="{{ ($index % 3) * 150 }}">
                        
                        <div class="relative overflow-hidden mb-10 bg-[#0f0f0f] p-4 shadow-2xl transition-all duration-700 group-hover:shadow-[#C9A74E]/5 group-hover:-translate-y-3">
                            
                            <div class="overflow-hidden aspect-[4/5] relative">
                                <img src="{{ $art['image'] }}" 
                                    class="w-full h-full object-cover grayscale-[0.6] group-hover:grayscale-0 transition-all duration-[2s] ease-out group-hover:scale-105" 
                                    alt="{{ $art['title'] }}">
                                
                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-center justify-center backdrop-blur-[2px]">
                                    <div class="transform translate-y-10 group-hover:translate-y-0 transition-all duration-500">
                                        <a href="{{ $art['link'] }}" class="px-10 py-4 border border-[#C9A74E] text-[#C9A74E] text-[9px] uppercase tracking-[0.5em] hover:bg-[#C9A74E] hover:text-black transition-colors duration-300 cursor-pointer font-bold inline-block">
                                            View Work
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="absolute inset-0 border border-white/5 pointer-events-none"></div>
                        </div>
                        
                        <div class="px-1">
                            <div class="flex justify-between items-baseline mb-4">
                                <h3 class="font-serif text-2xl text-gray-300 group-hover:text-[#C9A74E] transition-colors duration-500 italic">
                                    {{ $art['title'] }}
                                </h3>
                                <span class="h-[1px] flex-grow mx-6 bg-white/10 group-hover:bg-[#C9A74E]/30 transition-all"></span>
                                <span class="text-[#C9A74E] font-serif italic text-lg">
                                    {{ $art['price'] }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center opacity-60 group-hover:opacity-100 transition-opacity">
                                <a href="/artists_profile" class="group inline-block">
                                    <p class="text-[9px] text-gray-300 uppercase tracking-[0.3em] font-medium transition-colors group-hover:text-white">
                                        {{ $art['medium'] }}
                                    </p>
                                </a>
                                <p class="text-[15px] text-gray-300 italic font-serif">
                                    {{ $art['year'] }}
                                </p>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Mobile menu --}}
    <div id="mobileMenu" class="fixed inset-0 z-[100] bg-[#f8f8f8] flex flex-col p-10 xl:hidden">
        <button id="closeMenuBtn" class="self-end text-slate-900 hover:text-amber-500 transition-colors focus:outline-none">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <div class="flex flex-col gap-8 mt-10">
            <span class="text-amber-600 text-[10px] uppercase tracking-[0.5em] font-bold">Discover</span>
            <a href="/" class="text-slate-900 text-4xl md:text-5xl font-serif italic border-b border-slate-200 pb-4 hover:pl-4 hover:text-amber-700 hover:border-amber-200 transition-all">Home</a>
            <a href="/artists" class="text-slate-900 text-4xl md:text-5xl font-serif italic border-b border-slate-200 pb-4 hover:pl-4 hover:text-amber-700 hover:border-amber-200 transition-all">Artists</a>
            <a href="/gallery" class="text-slate-900 text-4xl md:text-5xl font-serif italic border-b border-slate-200 pb-4 hover:pl-4 hover:text-amber-700 hover:border-amber-200 transition-all">Gallery</a>
        </div>
    </div>

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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        window.addEventListener('load', () => {
            // Inisialisasi AOS
            AOS.init({
                duration: 1000,
                once: true,
                disableMutationObserver: false,
            });

            setTimeout(() => {
                AOS.refresh();
            }, 2000); 

            // Initialize Artist Carousel
            new Swiper('.artistSwiper', {
                slidesPerView: 1,
                spaceBetween: 40,
                loop: true,
                autoplay: {
                    delay: 3500,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                    },
                    1024: {
                        slidesPerView: 3,
                    },
                }
            });
        });

        // Scroll Effect untuk Hero Section
        window.addEventListener('scroll', function() {
            const heroSection = document.getElementById('hero-section');
            const scrollIcon = document.getElementById('scroll-icon');
            
            // Mendapatkan posisi scroll saat ini
            let scrollValue = window.scrollY;
            
            // Hitung Opacity (Mulai memudar dari 0px sampai 500px scroll)
            // Semakin besar scrollValue, semakin kecil opacity-nya
            let opacityValue = 1 - (scrollValue / 500);
            
            // Berikan batas minimal 0 agar tidak minus
            if (opacityValue < 0) opacityValue = 0;

            // Terapkan ke elemen utama (Konten)
            // Kita juga tambahkan sedikit efek translateY agar konten seperti terdorong ke atas
            const content = heroSection.querySelector('.relative.z-20');
            if (content) {
                content.style.opacity = opacityValue;
                content.style.transform = `translateY(-${scrollValue * 0.2}px)`;
            }

            // Khusus ikon scroll, buat menghilang lebih cepat
            if (scrollIcon) {
                scrollIcon.style.opacity = 1 - (scrollValue / 150);
            }
            
            // Opsional: Membuat background bergerak lebih lambat (Parallax)
            const background = heroSection.querySelector('.absolute.inset-0');
            if (background) {
                background.style.transform = `translateY(${scrollValue * 0.4}px)`;
            }
        });

        // Fungsi tambahan: klik ikon untuk scroll ke bawah otomatis
        document.getElementById('scroll-icon').addEventListener('click', () => {
            window.scrollTo({
                top: window.innerHeight,
                behavior: 'smooth'
            });
        });

        const hasSeenOpening = sessionStorage.getItem('hasSeenOpening');

        if (hasSeenOpening) {
            const hideLoader = () => {
                const loader = document.getElementById('art-loader');
                if (loader) loader.style.display = 'none';
                document.body.classList.remove('loading');
                
                if (typeof AOS !== 'undefined') {
                    AOS.init({ duration: 800, once: true });
                }
                document.querySelectorAll('.reveal').forEach(el => el.classList.add('active'));
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', hideLoader);
            } else {
                hideLoader();
            }
        }

        window.addEventListener('load', () => {
            if (hasSeenOpening) return;

            const loader = document.getElementById('art-loader');
            loader.style.display = 'block';
            document.body.classList.add('loading');
            window.scrollTo(0, 0);

            const tl = gsap.timeline({
                defaults: { ease: "power2.inOut" }, 
                onComplete: () => {
                    loader.style.display = 'none';
                    document.body.classList.remove('loading');
                    sessionStorage.setItem('hasSeenOpening', 'true');
                    document.querySelectorAll('.reveal').forEach(el => el.classList.add('active'));
                    AOS.refresh();
                }
            });

            // Munculkan garis-garis pelan dulu (Elegant Entrance)
            tl.from(".art-line", { 
                opacity: 0, 
                duration: 1.5, 
                stagger: 0.1, // Stagger lebih lambat agar terasa mengalir
                ease: "power2.out" 
            });

            // Main brand muncul dengan gerakan naik yang dramatis tapi tenang
            tl.to("#main-brand", { 
                opacity: 1, 
                y: 0, 
                duration: 1.4, 
                ease: "expo.out" 
            }, "-=1"); // Tumpang tindih sedikit dengan art-line

            // Sub brand muncul perlahan setelah main brand
            tl.to("#sub-brand", { 
                opacity: 1, 
                duration: 1, 
                ease: "power1.out" 
            }, "-=0.6");

            // Jeda baca 
            tl.to({}, { duration: 1.2 });

            tl.addLabel("exit");

            // Animasi keluar yang mewah (Slow to Fast)
            tl.to("#swipe-container", { 
                y: "-100%", 
                duration: 1.3, 
                ease: "expo.inOut" 
            }, "exit");

            tl.to("#loader-content-wrap", { 
                y: "-120%", 
                duration: 1.5, 
                ease: "expo.inOut" 
            }, "exit");

            tl.to("#art-loader", { 
                opacity: 0, 
                duration: 0.5 
            }, "-=0.4");

            AOS.init({ duration: 800, once: true });
        });

        // Mobile Menu Toggle
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const closeMenuBtn = document.getElementById('closeMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');

        hamburgerBtn.addEventListener('click', () => {
            mobileMenu.classList.add('active');
            document.body.style.overflow = 'hidden';
        });

        closeMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.remove('active');
            document.body.style.overflow = '';
        });

        // Scroll Observer for Reveal Animations
        const observerOptions = { threshold: 0.15 };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

        // back to button logic
        window.addEventListener('scroll', function() {
            const btn = document.getElementById('backToTop');
            if (window.scrollY > 400) {
                btn.classList.remove('opacity-0', 'translate-y-10', 'pointer-events-none');
                btn.classList.add('opacity-100', 'translate-y-0', 'pointer-events-all');
            } else {
                btn.classList.add('opacity-0', 'translate-y-10', 'pointer-events-none');
                btn.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-all');
            }
        });

        // Logika scroll ke atas
        document.getElementById('backToTop').addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
</body>
</html>