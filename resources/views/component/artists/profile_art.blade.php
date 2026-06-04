<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600&family=Playfair+Display:ital,wght@0,700;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/comment-system.css') }}">
    <title>Kapakapa Art Gallery | Artist Profile</title>
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #0a0a0a; 
            color: #94a3b8; 
        } 
        .font-serif { font-family: 'Playfair Display', serif; }
        .text-gold  { color: #C9A74E; }
        .bg-gold    { background-color: #C9A74E; }
        .border-gold { border-color: #C9A74E; }

        .smooth-transition {
            transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .artwork-slider {
            display: flex;
            width: 100%;
            height: 100%;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: transform;
        }

        .artwork-slide {
            flex-shrink: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            position: relative;
        }

        .artwork-slider img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .swiper-button-next, .swiper-button-prev {
            color: #C9A74E !important;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            width: 50px !important;
            height: 50px !important;
            border-radius: 50%;
            border: 1px solid rgba(201, 167, 78, 0.2);
            transition: all 0.3s ease;
        }
        .swiper-button-next:after, .swiper-button-prev:after {
            font-size: 18px !important;
            font-weight: bold;
        }
        .swiper-button-next:hover, .swiper-button-prev:hover {
            background: rgba(201, 167, 78, 0.1);
            border-color: #C9A74E;
            box-shadow: 0 0 15px rgba(201, 167, 78, 0.3);
        }
        .swiper-pagination-bullet {
            background: #fff !important;
            opacity: 0.3 !important;
        }
        .swiper-pagination-bullet-active {
            background: #C9A74E !important;
            opacity: 1 !important;
            width: 20px !important;
            border-radius: 4px !important;
            transition: width 0.3s ease;
        }

        .mySwiper { width: 100%; height: 100%; }
        .swiper-slide { width: 100%; height: 100%; overflow: hidden; }

        #mobilePageIndicator {
            transition: opacity 0.5s ease;
            backdrop-filter: blur(10px) !important;
            -webkit-backdrop-filter: blur(10px) !important;
            background-color: rgba(0, 0, 0, 0.5) !important;
            z-index: 999 !important;
        }

        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.02); }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(201, 167, 78, 0.2);
            border-radius: 10px;
        }
    </style>
</head>
<body class="antialiased selection:bg-gold selection:text-black">
    
    @include('component.layout.navbar')

    <section class="relative z-20 py-24 px-8 md:px-20">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row items-center lg:items-start gap-12 md:gap-20">
                
                <div class="relative flex-shrink-0" data-aos="fade-up" data-aos-duration="2000">
                    <div class="absolute -inset-3 border border-[#C9A74E]/10 rounded-full"></div>
                    <div class="w-48 h-48 md:w-64 md:h-64 rounded-full overflow-hidden border border-[#C9A74E]/20">
                        @if($artist->profile_url)
                            <img src="{{ asset($artist->profile_url) }}" 
                                class="w-full h-full object-cover grayscale hover:grayscale-0 hover:scale-110 transition-transform duration-[3s] ease-out" 
                                alt="{{ $artist->name }}">
                        @else
                            <img src="https://api.dicebear.com/8.x/notionists/svg?seed={{ urlencode($artist->name) }}" 
                                class="w-full h-full object-cover grayscale hover:grayscale-0 hover:scale-110 transition-transform duration-[3s] ease-out" 
                                alt="{{ $artist->name }}">
                        @endif
                    </div>
                </div>

                <div class="flex-grow text-center lg:text-left pt-4">
                    <div class="flex flex-col lg:flex-row lg:items-baseline gap-4 md:gap-8 mb-4">
                        <h2 class="text-5xl md:text-7xl font-serif font-bold text-gray-300 tracking-tight">
                            {{ $artist->name }}
                        </h2>
                    </div>

                    <p class="text-gold/60 font-sans tracking-[0.4em] text-[11px] uppercase mb-10 font-medium">
                        {{ $artist->birthplace }}, {{ $artist->career }}
                    </p>

                    <div class="relative max-w-4xl">
                        <div id="bioText"
                             class="text-slate-300/90 text-lg md:text-xl leading-[1.9] font-light tracking-wide overflow-hidden"
                             style="max-height: 112px; transition: max-height 0.45s cubic-bezier(0.4,0,0.2,1), opacity 0.3s ease;">
                            {!! nl2br(e($artist->bio)) !!}
                        </div>

                        <div class="absolute -left-6 top-0 w-[1px] h-full bg-gradient-to-b from-transparent via-[#C9A74E]/20 to-transparent pointer-events-none"></div>

                        <div id="textOverlay"
                             class="absolute bottom-0 left-0 w-full h-28 pointer-events-none"
                             style="transition: opacity 0.25s ease; background: linear-gradient(to top, #0a0a0a 30%, rgba(10,10,10,0.6) 65%, transparent 100%);"></div>
                    </div>
                    
                    <div class="mt-8">
                        <button id="readMoreBtn" class="group flex items-center gap-4 text-gray-300 hover:text-gold transition-all duration-300">
                            <span id="btnLine" class="h-[1px] w-12 bg-[#C9A74E] group-hover:w-20 transition-all"></span>
                            <span id="btnText" class="uppercase tracking-[0.4em] text-[10px] font-bold">Read Full Biography</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pt-0 pb-20 px-8 md:px-20 -mt-4">
        <div class="max-w-7xl mx-auto">
            
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-20 gap-8 border-b border-white/5 pb-10">
                <div class="relative group">
                    <div class="flex items-baseline gap-6 relative z-10">
                        <span class="text-6xl font-serif text-gray-300 leading-none tracking-tighter transition-all duration-700 group-hover:italic group-hover:text-gold">
                            {{ $artworks->total() }}
                        </span>
                        <div class="flex flex-col">
                            <span class="text-gold text-[8px] uppercase tracking-[0.8em] font-bold mb-1">Archive</span>
                            <span class="text-slate-500 uppercase tracking-[0.4em] text-[10px] font-medium">Items Collection</span>
                        </div>
                    </div>
                    <div class="absolute -bottom-2 left-0 w-0 h-[1px] bg-[#C9A74E]/50 group-hover:w-full transition-all duration-1000"></div>
                </div>

                <div class="relative flex items-center group w-full md:w-96">
                    <span class="absolute left-0 text-[9px] uppercase tracking-[0.3em] text-gray-300 font-bold group-focus-within:text-[#C9A74E] group-hover:text-slate-400 transition-colors duration-500">
                        Search //
                    </span>
                    <input id="artistSearchInput" type="text" placeholder="TYPE OF ART..." 
                        class="bg-transparent border-b border-white/10 rounded-none py-2 pl-24 pr-10 w-full text-xs tracking-[0.2em] text-white focus:outline-none focus:border-[#C9A74E]/50 transition-all placeholder:text-slate-800 placeholder:italic uppercase">
                    <svg class="absolute right-0 w-4 h-4 text-slate-700 transition-all duration-700 transform group-hover:rotate-90 group-hover:text-[#C9A74E] group-focus-within:text-[#C9A74E]" 
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <div class="absolute -bottom-[2.5px] -right-[1px] w-1 h-1 bg-[#C9A74E] rounded-full opacity-0 group-focus-within:opacity-100 group-hover:opacity-100 transition-all duration-500 shadow-[0_0_8px_#C9A74E]"></div>
                </div>
            </div>

            <div id="grid-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-y-32 gap-x-12">
                @include('component.artists.partials.artworks-grid', compact('artworks', 'artist'))
            </div>

            @include('component.partials.pagination-bar', [
                'paginator'   => $artworks,
                'containerId' => 'grid-container',
            ])
        </div>
    </section>

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

    {{-- ===== MODAL ===== --}}
    <div id="artModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 md:p-8">
        <div class="absolute inset-0 bg-black/95 backdrop-blur-sm"></div>
        
        <div class="relative bg-zinc-900 border border-white/10 w-full max-w-4xl max-h-[85vh] overflow-hidden flex flex-col md:flex-row shadow-2xl animate-in fade-in zoom-in duration-300">
            
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

            <div id="imageContainer" class="w-full md:w-3/5 bg-[#050505] relative" style="height: 85vh;">
                <div id="mobilePageIndicator" class="absolute top-5 right-5 px-3 py-1.5 rounded-full border border-white/10 opacity-0 pointer-events-none transition-opacity duration-500">
                    <span class="text-white text-[11px] font-medium tracking-[0.1em]">
                        <span id="currentPage">1</span> / <span id="totalPages">3</span>
                    </span>
                </div>

                <div class="swiper mySwiper w-full h-full">
                    <div class="swiper-wrapper" id="modalSwiperWrapper"></div>
                    <div class="swiper-button-next hidden md:flex"></div>
                    <div class="swiper-button-prev hidden md:flex"></div>
                    <div class="swiper-pagination hidden md:block !bottom-8"></div>
                </div>

                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 pointer-events-none hidden md:block">
                    <div class="flex flex-col items-center gap-3 opacity-0 group-hover/zoom:opacity-100 translate-y-4 group-hover/zoom:translate-y-0 transition-all duration-700 ease-out">
                        <span class="text-[8px] text-gold/60 uppercase tracking-[0.5em] whitespace-nowrap bg-black/40 backdrop-blur-md px-5 py-2.5 border border-gold/10 rounded-full shadow-2xl">
                            Scroll or use arrows to navigate
                        </span>
                    </div>
                </div>
            </div>

            <div class="w-full md:w-2/5 p-6 md:p-8 flex flex-col justify-between border-t md:border-t-0 md:border-l border-white/5 bg-zinc-900/40 backdrop-blur-md overflow-y-auto max-h-[85vh] relative custom-scrollbar">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gold/5 blur-[80px] pointer-events-none"></div>

                <div class="relative z-10">
                    <header class="mb-10">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="h-[1px] w-8 bg-gold/50"></span>
                            <span class="text-gold text-[9px] uppercase tracking-[0.4em] font-medium">Original Work</span>
                        </div>
                        <h2 id="modalTitle" class="text-4xl md:text-5xl font-serif text-white mb-3 leading-[1.1] tracking-tight"></h2>
                        <p id="modalAuthor" class="text-zinc-400 tracking-[0.25em] uppercase text-[10px] font-light"></p>
                    </header>
                    
                    <div class="space-y-8">
                        <div class="group bg-white/[0.02] border border-white/5 p-5 rounded-2xl transition-all duration-500 hover:bg-white/[0.04]">
                            <span class="text-zinc-500 text-[9px] uppercase tracking-[0.2em] block mb-2 font-medium">Physical Dimensions</span>
                            <div class="flex items-end gap-2">
                                <p id="modalDimensions" class="text-gray-200 text-xl font-light font-serif italic"></p>
                                <span class="text-[10px] text-zinc-600 mb-1.5 uppercase">Certified</span>
                            </div>
                        </div>

                        <div id="modalPriceWrap" class="hidden group bg-white/[0.02] border border-white/5 p-5 rounded-2xl transition-all duration-500 hover:bg-white/[0.04]">
                            <span class="text-zinc-500 text-[9px] uppercase tracking-[0.2em] block mb-2 font-medium">Price</span>
                            <p id="modalPrice" class="text-gold font-serif italic text-2xl"></p>
                        </div>
                        
                        <div class="relative pl-6 border-l border-gold/20">
                            <p class="text-zinc-400 text-[13px] leading-relaxed font-light italic">
                                "This artwork is part of a curated collection showcasing the intersection of classical technique and modern vision."
                            </p>
                        </div>

                        <div class="pt-4">
                            <span class="text-zinc-500 text-[9px] uppercase tracking-[0.2em] block mb-4 font-medium">Authentication Asset</span>
                            <div onclick="toggleCertModal()" 
                                class="group relative cursor-pointer bg-gradient-to-br from-gold/10 to-transparent border border-gold/20 p-5 rounded-2xl overflow-hidden transition-all duration-700 hover:border-gold/50 hover:shadow-[0_0_30px_rgba(201,167,78,0.1)]">
                                
                                <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_120%,rgba(201,167,78,0.15),transparent)] opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                                <div class="absolute -right-2 -bottom-2 opacity-[0.03] group-hover:opacity-[0.08] transition-all duration-1000 group-hover:rotate-45 group-hover:scale-125">
                                    <svg class="w-32 h-32 text-gold" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L9 4H5V8L2 11V13L5 16V20H9L12 23L15 20H19V16L22 13V11L19 8V4H15L12 1Z"/></svg>
                                </div>
                                
                                <div class="relative flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 flex-shrink-0 flex items-center justify-center border border-gold/20 rounded-full bg-black/40 group-hover:border-gold/50 transition-all duration-500">
                                            <svg class="w-5 h-5 text-gold animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-white text-[11px] font-medium uppercase tracking-[0.1em] mb-0.5">Certificate COA</h4>
                                            <p class="text-zinc-500 text-[10px] font-light">Tamper-proof Digital Document</p>
                                        </div>
                                    </div>
                                    <div class="text-gold/40 group-hover:text-gold transition-colors">
                                        <svg class="w-5 h-5 translate-x-0 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-width="1.5"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-12 flex items-center gap-4 relative z-10">
                    <button class="flex-1 group relative overflow-hidden bg-[#C9A74E] p-[1px] transition-all duration-500">
                        <div class="relative bg-[#C9A74E] py-4 transition-all duration-300 group-hover:bg-[#1a1a1a] border border-transparent group-hover:border-[#C9A74E]">
                            <span class="text-black group-hover:text-[#C9A74E] text-[10px] font-bold uppercase tracking-[0.3em] flex items-center justify-center gap-2">
                                Buy Now
                            </span>
                        </div>
                    </button>
                    
                    <div class="flex gap-4">
                        <button onclick="toggleCommentModal()" class="group relative w-14 h-14 flex items-center justify-center rounded-full border border-white/10 text-white/40 transition-all duration-500 overflow-hidden">
                            <div class="absolute inset-0 bg-[#C9A74E] translate-y-full group-hover:translate-y-0 transition-transform duration-500 ease-out"></div>
                            <svg class="relative z-10 w-5 h-5 transition-all duration-500 group-hover:text-black group-hover:scale-125 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <div class="absolute inset-0 rounded-full border border-transparent group-hover:border-[#C9A74E] transition-colors duration-500"></div>
                        </button>

                        <button class="group relative w-14 h-14 flex items-center justify-center rounded-full border border-white/10 text-white/40 transition-all duration-500 overflow-hidden">
                            <div class="absolute inset-0 bg-rose-500 translate-y-full group-hover:translate-y-0 transition-transform duration-500 ease-out"></div>
                            <svg class="relative z-10 w-5 h-5 transition-all duration-500 group-hover:text-white group-hover:scale-125 group-hover:-rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <div class="absolute inset-0 rounded-full border border-transparent group-hover:border-rose-500 transition-colors duration-500"></div>
                        </button>
                    </div>
                </div>
            </div>

            @include('component.partials.comment-overlay')

            <div id="certOverlay" 
                class="fixed inset-0 z-[150] bg-black/98 backdrop-blur-3xl hidden items-center justify-center p-4 md:p-12 transition-all duration-700 cursor-pointer"
                onclick="closeCertOnClickOutside(event)">
                
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-gold/5 rounded-full blur-[120px] pointer-events-none"></div>

                <div class="relative w-full max-w-4xl animate-in fade-in zoom-in-95 duration-700 cursor-default">
                    <div class="relative bg-zinc-900/40 border border-white/5 p-2 md:p-4 shadow-[0_0_100px_rgba(0,0,0,0.8)] backdrop-blur-md">
                        <div class="relative border border-gold/20 p-1">
                            <div class="relative border border-gold/10 bg-black overflow-hidden group">
                                <img id="certImageActual" 
                                    src="/img/sertif.png" 
                                    alt="Certificate of Authenticity"
                                    class="w-full h-auto object-contain shadow-2xl transition-transform duration-1000 group-hover:scale-[1.01]">
                                <div class="absolute inset-0 bg-gradient-to-tr from-black/20 via-transparent to-white/5 pointer-events-none"></div>
                            </div>
                        </div>
                        <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 -translate-x-1 -translate-y-1"></div>
                        <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 translate-x-1 translate-y-1"></div>
                    </div>

                    <div class="absolute -bottom-16 left-0 right-0 text-center">
                        <span class="text-[9px] text-gray-300 uppercase tracking-[0.6em] select-none font-light">
                            Click anywhere outside or press <span class="text-gold/40">ESC</span> to return
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100,
            easing: 'ease-out-cubic',
        });

        // --- ZOOM ---
        function zoomIn(event) {
            const img = event.currentTarget;
            const { left, top, width, height } = img.getBoundingClientRect();
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

        // --- SWIPER ---
        let swiper = null;
        let mobileIndicatorTimeout = null;

        function initSwiper() {
            if (swiper) swiper.destroy(true, true);
            swiper = new Swiper(".mySwiper", {
                loop: false,
                speed: 800,
                grabCursor: true,
                pagination: { el: ".swiper-pagination", clickable: true },
                navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
                keyboard: { enabled: true },
                on: {
                    slideChange: function() { updateMobileIndicator(this.activeIndex + 1, this.slides.length); },
                    init:        function() { updateMobileIndicator(this.activeIndex + 1, this.slides.length); }
                }
            });
        }

        let isScrolling = false;
        document.addEventListener('wheel', (e) => {
            const modal = document.getElementById('artModal');
            if (!modal || modal.classList.contains('hidden') || !swiper) return;
            const isOverSwiper = e.target.closest('.mySwiper');
            if (!isOverSwiper) return;
            e.preventDefault();
            if (isScrolling) return;
            isScrolling = true;
            if (e.deltaY > 0) { swiper.slideNext(); } else { swiper.slidePrev(); }
            setTimeout(() => { isScrolling = false; }, 300);
        }, { passive: false });

        function updateMobileIndicator(current, total) {
            const indicator = document.getElementById('mobilePageIndicator');
            const currentEl = document.getElementById('currentPage');
            const totalEl   = document.getElementById('totalPages');
            if (!indicator || !currentEl || !totalEl) return;
            currentEl.innerText = current;
            totalEl.innerText   = total;
            indicator.classList.remove('opacity-0');
            indicator.classList.add('opacity-100');
            if (mobileIndicatorTimeout) clearTimeout(mobileIndicatorTimeout);
            mobileIndicatorTimeout = setTimeout(() => {
                indicator.classList.remove('opacity-100');
                indicator.classList.add('opacity-0');
            }, 2000);
        }

        // --- MODAL ---
        function openModal(images, title, author, width, height, unit, certificate, price, artworkId) {
            const modal   = document.getElementById('artModal');
            const wrapper = document.getElementById('modalSwiperWrapper');
            wrapper.innerHTML = '';

            const imagesArray = Array.isArray(images) ? images : [images];
            imagesArray.forEach(imgUrl => {
                const slide = document.createElement('div');
                slide.className = 'swiper-slide';
                slide.innerHTML = `
                    <img src="${imgUrl}" alt="${title}" 
                        class="w-full h-full object-cover transition-transform duration-700 ease-[cubic-bezier(0.23,1,0.32,1)] cursor-zoom-in"
                        onmousemove="zoomIn(event)" 
                        onmouseleave="zoomOut(event)">
                `;
                wrapper.appendChild(slide);
            });

            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalAuthor').innerText = author;
            document.getElementById('modalDimensions').innerText =
                (width && height) ? `${width} x ${height} ${unit}` : 'Dimensions not specified';

            const certImg = document.getElementById('certImageActual');
            certImg.src = (certificate && certificate.trim() !== '') ? certificate : '/img/sertif.png';

            const priceEl   = document.getElementById('modalPrice');
            const priceWrap = document.getElementById('modalPriceWrap');
            if (price && price.toString().trim() !== '') {
                priceEl.innerText = '$ ' + parseFloat(price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                priceWrap.classList.remove('hidden');
            } else {
                priceWrap.classList.add('hidden');
            }

            setTimeout(() => {
                initSwiper();
                updateMobileIndicator(1, imagesArray.length);
            }, 100);

            _currentCommentArtworkId = artworkId;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal   = document.getElementById('artModal');
            const overlay = document.getElementById('commentOverlay');
            const content = document.getElementById('commentContent');

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            _stopPoll();
            if (overlay) {
                overlay.classList.add('invisible', 'opacity-0');
                overlay.classList.remove('opacity-100');
                overlay.dataset.open = 'false';
            }
            if (content) {
                content.classList.remove('scale-100');
                content.classList.add('scale-95');
            }
            document.body.style.overflow = 'auto';
        }

        // --- KOMENTAR ---
        function toggleCommentModal() {
            const overlay = document.getElementById('commentOverlay');
            const content = document.getElementById('commentContent');
            if (!overlay || !content) return;

            const isOpen = overlay.dataset.open === 'true';

            if (!isOpen) {
                if (!_currentCommentArtworkId) return;

                _commentState.comments  = [];
                _commentState.lastCount = 0;
                _commentState.hasLoaded = false;
                _commentState.isSending = false;

                const list = document.getElementById('commentList');
                if (list) [...list.querySelectorAll('.comment-node')].forEach(n => n.remove());
                const loading = document.getElementById('commentLoading');
                const empty   = document.getElementById('commentEmptyState');
                loading && loading.classList.remove('hidden');
                empty   && empty.classList.add('hidden');

                const badge = document.getElementById('commentCountBadge');
                if (badge) badge.textContent = '…';
                const input   = document.getElementById('commentInput');
                const counter = document.getElementById('charCount');
                if (input)   { input.value = ''; input._bound = false; }
                if (counter) { counter.textContent = '0 / 280'; counter.style.color = ''; }

                overlay.classList.remove('invisible', 'opacity-0');
                overlay.classList.add('opacity-100');
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
                overlay.dataset.open = 'true';

                _fetchComments(_currentCommentArtworkId).then(items => {
                    if (!items) { _hideLoading(); return; }
                    _renderAll(document.getElementById('commentList'), items);
                    _commentState.comments  = items;
                    _commentState.lastCount = items.length;
                    _commentState.hasLoaded = true;
                    _setupInput();
                });
                _startPoll(_currentCommentArtworkId);
                _startTimeRefresh();

            } else {
                _stopPoll();
                content.classList.remove('scale-100');
                content.classList.add('scale-95');
                overlay.classList.remove('opacity-100');
                overlay.classList.add('opacity-0');
                setTimeout(() => {
                    overlay.classList.add('invisible');
                    overlay.dataset.open = 'false';
                }, 500);
            }
        }

        function closeCertOnClickOutside(event) {
            if (event.target === document.getElementById('certOverlay')) toggleCertModal();
        }

        function toggleCertModal() {
            const overlay = document.getElementById('certOverlay');
            if (overlay.classList.contains('hidden')) {
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
                document.body.style.overflow = 'hidden';
            } else {
                overlay.classList.add('hidden');
                overlay.classList.remove('flex');
                document.body.style.overflow = '';
            }
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                const certOverlay = document.getElementById('certOverlay');
                if (certOverlay && !certOverlay.classList.contains('hidden')) toggleCertModal();
            }
        });

        // FIX BUG #7 (duplikasi scroll listener): Satu handler saja
        window.addEventListener('scroll', function() {
            const btn = document.getElementById('backToTop');
            if (!btn) return;
            if (window.scrollY > 400) {
                btn.classList.remove('opacity-0', 'translate-y-10', 'pointer-events-none');
                btn.classList.add('opacity-100', 'translate-y-0', 'pointer-events-auto');
            } else {
                btn.classList.add('opacity-0', 'translate-y-10', 'pointer-events-none');
                btn.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
            }
        });

        document.getElementById('backToTop').addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // --- BIOGRAPHY TOGGLE ---
        // FIX BUG #9: Pakai flag boolean eksplisit, bukan cek string max-height
        const bioText    = document.getElementById('bioText');
        const readMoreBtn = document.getElementById('readMoreBtn');
        const btnText    = document.getElementById('btnText');
        const btnLine    = document.getElementById('btnLine');
        const textOverlay = document.getElementById('textOverlay');

        let bioExpanded = false; // FIX: flag eksplisit, tidak bergantung pada inline style

        readMoreBtn.addEventListener('click', function() {
            if (!bioExpanded) {
                bioText.style.maxHeight = bioText.scrollHeight + "px";
                bioText.style.opacity   = "1";
                textOverlay.style.opacity = "0";
                btnText.innerText = 'Show Less';
                btnLine.style.width = "80px";
                bioExpanded = true;
            } else {
                bioText.style.maxHeight = "112px";
                textOverlay.style.opacity = "1";
                btnText.innerText = 'Read Full Biography';
                btnLine.style.width = "48px";
                bioExpanded = false;
                setTimeout(() => {
                    bioText.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 300);
            }
        });

        // --- ARTWORK CARD HOVER SLIDER ---
        document.addEventListener('DOMContentLoaded', function() {
            const artworkContainers = document.querySelectorAll('.artwork-card-container');
            const intervals = new Map();

            const preloadImages = () => {
                document.querySelectorAll('.artwork-image').forEach(img => {
                    if (!img.classList.contains('active')) {
                        new Image().src = img.src;
                    }
                });
            };
            if (window.performance && window.performance.mark) {
                window.addEventListener('load', preloadImages);
            } else {
                setTimeout(preloadImages, 1000);
            }

            artworkContainers.forEach(container => {
                const slider = container.querySelector('.artwork-slider');
                const images = slider ? slider.querySelectorAll('img') : [];
                if (images.length <= 1) return;

                const parentLink = container.closest('a');
                if (!parentLink) return;

                parentLink.addEventListener('mouseenter', () => {
                    if (intervals.has(container)) {
                        const session = intervals.get(container);
                        clearTimeout(session.timeout);
                        clearInterval(session.interval);
                    }
                    const timeout = setTimeout(() => {
                        let currentIndex = 0;
                        const interval = setInterval(() => {
                            currentIndex = (currentIndex + 1) % images.length;
                            slider.style.transform = `translateX(-${currentIndex * 100}%)`;
                        }, 1500);
                        intervals.set(container, { timeout, interval });
                    }, 800);
                    intervals.set(container, { timeout, interval: null });
                });

                parentLink.addEventListener('mouseleave', () => {
                    if (intervals.has(container)) {
                        const session = intervals.get(container);
                        clearTimeout(session.timeout);
                        if (session.interval) clearInterval(session.interval);
                        intervals.delete(container);
                    }
                    slider.style.transform = `translateX(0)`;
                });
            });

            // Search listener — satu kali, pakai ID eksplisit
            document.getElementById('artistSearchInput')?.addEventListener('input', function() {
                clearTimeout(this._searchTimeout);
                this._searchTimeout = setTimeout(async () => {
                    const grid = document.getElementById(GRID_ID);
                    if (!grid) return;
                    grid.style.opacity = '0.3';
                    grid.style.pointerEvents = 'none';

                    const params = new URLSearchParams({ page: 1, search: this.value.trim(), ...EXTRA_PARAMS });

                    try {
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
                    } catch (err) {
                        console.error('Search error:', err);
                    } finally {
                        grid.style.opacity = '1';
                        grid.style.pointerEvents = 'auto';
                    }
                }, 400);
            });
        });
    </script>

    <script>
    const AJAX_URL     = "{{ route('profile_art', $artist->id) }}";
    const EXTRA_PARAMS = {};
    const GRID_ID      = 'grid-container';

    // FIX BUG #6: Inisialisasi variabel wajib dari Blade — tidak akan undefined lagi
    let currentPage = {{ $artworks->currentPage() }};
    let lastPage    = {{ $artworks->lastPage() }};
    let isFetching  = false;

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

        // FIX: Null guard agar tidak error saat pagination bar tidak ada
        if (!bar || !prev || !next) return;

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
    <script src="{{ asset('js/comment-system.js') }}"></script>
</body>
</html>