<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    /* ANIMASI: Bingkai menggunakan Emas */
    @keyframes frameDrawBorder {
        0% { border-color: rgba(201, 167, 78, 0.05); }
        50% { border-color: rgba(201, 167, 78, 0.4); }
        100% { border-color: rgba(201, 167, 78, 0.25); }
    }

    /* ANIMASI: Partikel menggunakan warna Emas (#C9A74E) */
    @keyframes goldParticle {
        0% { transform: translateY(0) scale(1); opacity: 0; }
        20% { opacity: 0.7; }
        50% { transform: translateY(-40px) scale(1.2); opacity: 0.9; }
        80% { opacity: 0.5; }
        100% { transform: translateY(-80px) scale(1.5); opacity: 0; }
    }

    @keyframes textFadeIn {
        from { opacity: 0; transform: translateY(10px); filter: blur(3px); }
        to { opacity: 0.8; transform: translateY(0); filter: blur(0); }
    }

    .animate-frame-draw { animation: frameDrawBorder 4s ease-out forwards; }
    .animate-text-reveal { animation: textFadeIn 2s cubic-bezier(0.22, 1, 0.36, 1) 0.5s forwards; opacity: 0; }
    .gold-particle { position: absolute; background: #C9A74E; border-radius: 50%; pointer-events: none; opacity: 0; animation: goldParticle linear infinite; }
    
    /* Custom Gold Color */
    .text-gold { color: #C9A74E; }
    .border-gold { border-color: #C9A74E; }
    .bg-gold { background-color: #C9A74E; }
</style>

<footer class="bg-[#0a0a0a] text-[#E5E5E5] pt-32 pb-12 overflow-hidden relative font-sans tracking-wide">
    <div class="absolute top-0 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-[#C9A74E]/30 to-transparent"></div>
    <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-[#C9A74E]/5 blur-[120px] rounded-full pointer-events-none"></div>

    <div class="max-w-[1440px] mx-auto px-10 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-12 lg:gap-8 mb-24 items-stretch">
            
            <div class="md:col-span-12 lg:col-span-4 flex flex-col justify-between space-y-12 border-r border-white/5 pr-8">
                <div class="group cursor-default">
                    <h2 class="text-4xl font-serif font-bold tracking-[0.2em] uppercase leading-none text-gray-300 transition-all duration-500">
                        Kapakapa
                        <span class="text-[#C9A74E] font-light italic block text-2xl tracking-[0.1em] mt-4 group-hover:translate-x-3 transition-transform duration-500">Art Gallery</span>
                    </h2>
                </div>
                
                <div class="relative">
                    <div class="absolute -inset-10 bg-[#C9A74E]/5 blur-[50px] rounded-full pointer-events-none"></div>
                    <div class="relative z-10 border-l-2 border-[#C9A74E]/30 pl-8 py-2">
                        <p class="text-gray-300 text-lg leading-relaxed font-light italic max-w-md">
                            " Where timeless masterpieces meet contemporary vision. A premier digital sanctuary for curated fine arts."
                        </p>
                        <div class="mt-8 flex items-center gap-6">
                            <div class="h-[1px] w-24 bg-[#C9A74E]/40"></div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-6">
                    <a href="#" target="_blank" class="w-12 h-12 rounded-full border border-white/10 flex items-center justify-center text-gray-400 transition-all duration-500 hover:text-[#C9A74E] hover:border-[#C9A74E] hover:bg-[#C9A74E]/5 group">
                        <i class="fa-brands fa-instagram text-xl transition-transform duration-500 group-hover:rotate-12"></i>
                    </a>
                    <a href="#" target="_blank" class="w-12 h-12 rounded-full border border-white/10 flex items-center justify-center text-gray-400 transition-all duration-500 hover:text-[#C9A74E] hover:border-[#C9A74E] hover:bg-[#C9A74E]/5 group">
                        <i class="fa-brands fa-tiktok text-xl"></i>
                    </a>
                    <a href="#" target="_blank" class="w-12 h-12 rounded-full border border-white/10 flex items-center justify-center text-gray-400 transition-all duration-500 hover:text-[#C9A74E] hover:border-[#C9A74E] hover:bg-[#C9A74E]/5 group">
                        <i class="fa-brands fa-facebook-f text-xl"></i>
                    </a>
                </div>
            </div>

            <div class="md:col-span-6 lg:col-span-4 grid grid-cols-2 gap-8 border-r border-white/5 px-4">
                <div class="space-y-10">
                    <h4 class="text-[#C9A74E] uppercase tracking-[0.4em] text-[12px] font-bold flex items-center gap-2">
                        Explore
                    </h4>
                    <ul class="space-y-8 text-lg font-light text-gray-400">
                        <li>
                            <a href="/artists" class="group flex items-baseline gap-2 transition-colors duration-300 hover:text-gray-300">
                            <span class="font-mono text-sm text-[#C9A74E]">01.</span>
                            <span class="relative">
                                Artists
                                <span class="absolute -bottom-1 left-0 h-[1.5px] w-0 bg-[#C9A74E] transition-all duration-300 ease-out group-hover:w-full"></span>
                            </span>
                            </a>
                        </li>

                        <li>
                            <a href="/gallery" class="group flex items-baseline gap-2 transition-colors duration-300 hover:text-gray-300">
                            <span class="font-mono text-sm text-[#C9A74E]">02.</span>
                            <span class="relative">
                                Gallery
                                <span class="absolute -bottom-1 left-0 h-[1.5px] w-0 bg-[#C9A74E] transition-all duration-300 ease-out group-hover:w-full"></span>
                            </span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="space-y-10">
                    <h4 class="text-[#C9A74E] uppercase tracking-[0.4em] text-[12px] font-bold">Opening</h4>
                    <div class="space-y-6">
                        <div class="group">
                            <p class="text-gray-500 text-[12px] uppercase tracking-widest mb-1">Weekdays</p>
                            <p class="text-gray-300 text-sm font-light">10:00 — 20:00</p>
                        </div>
                        <div class="group">
                            <p class="text-gray-500 text-[12px] uppercase tracking-widest mb-1">Weekends</p>
                            <p class="text-gray-300 text-sm font-light">11:00 — 18:00</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-6 lg:col-span-4 flex flex-col justify-between pl-8">
                <div class="space-y-10">
                    <h4 class="text-[#C9A74E] uppercase tracking-[0.4em] text-[12px] font-bold flex items-center gap-2">
                        Connect
                    </h4>
                    <div class="space-y-12">
                        <div class="group">
                            <span class="text-[10px] font-bold tracking-[0.3em] text-slate-600 uppercase block mb-3">Email</span>
                            <a href="mailto:gallery@kapakapa.art" class="text-xl font-light text-gray-300 hover:text-[#C9A74E] transition-all duration-300">
                                gallery@kapakapa.art
                            </a>
                        </div>
                        <div class="group border-t border-white/5 pt-8">
                            <span class="text-[10px] font-bold tracking-[0.3em] text-slate-600 uppercase block mb-3">Number</span>
                            <a href="tel:+6281389256833" class="text-xl font-light text-gray-300 hover:text-[#C9A74E] transition-all duration-300">
                                +62 813 8925 6833
                            </a>
                        </div>
                    </div>
                </div>

                <div class="pt-12 border-t border-white/5">
                    <p class="text-[10px] text-slate-500 uppercase tracking-[0.5em] leading-loose">
                        Based in Indonesia<br/>
                        Serving Global Connoisseurs
                    </p>
                </div>
            </div>

        </div>

        <div class="border-t border-white/10 pt-16 relative">
            <div class="absolute top-0 left-0 w-40 h-[2px] bg-gold"></div>

            <div class="flex flex-col md:flex-row justify-between items-end gap-10">
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <p class="text-[11px] md:text-[13px] uppercase tracking-[0.4em] text-slate-500 font-medium">
                            &copy; 2026 <span class="text-gray-300 font-bold">Kapakapa Art Gallery</span> 
                        </p>
                        <span class="h-4 w-[1px] bg-white/20 hidden md:block"></span> 
                        <p class="text-[11px] md:text-[12px] uppercase tracking-[0.3em] text-slate-600 italic">
                            The Living Canvas
                        </p>
                    </div>
                </div>
            </div>

            <div class="absolute bottom-0 right-0 opacity-[0.02] pointer-events-none select-none hidden lg:block">
                <h2 class="text-9xl font-serif font-bold -mb-6 tracking-tighter text-white uppercase">Kapakapa</h2>
            </div>
        </div>
    </div>
</footer>