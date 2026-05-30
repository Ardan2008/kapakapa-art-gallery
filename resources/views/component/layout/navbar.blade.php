@vite(['resources/css/app.css', 'resources/js/app.js'])

<nav class="bg-[#0a0a0a] border-b border-white/10 relative z-[50]">
    <div class="py-5 px-6 md:px-12 flex items-center justify-between">
        
        <div class="flex items-center">
            <a href="/">
                <div class="text-xl md:text-2xl font-bold tracking-[0.15em] uppercase font-serif text-gray-300 leading-none">
                    Kapakapa<span class="font-light italic ml-2 text-[#C9A74E]">Art Gallery</span>
                </div>
            </a>
        </div>

        <div class="hidden xl:flex items-center gap-10 text-[11px] uppercase tracking-[0.25em] font-bold text-gray-300">
            <a href="/" class="group relative py-2 transition duration-300 hover:text-white">
                Home 
                <span class="absolute bottom-0 left-0 h-[3px] w-0 bg-[#C9A74E] transition-all duration-300 group-hover:w-full shadow-[0_0_15px_rgba(201,167,78,0.6)]"></span>
            </a>
            <a href="{{ route('artists') }}" class="group relative py-2 transition duration-300 hover:text-white">
                Artists 
                <span class="absolute bottom-0 left-0 h-[3px] w-0 bg-[#C9A74E] transition-all duration-300 group-hover:w-full shadow-[0_0_15px_rgba(201,167,78,0.6)]"></span>
            </a>
            <a href="{{ route('gallery') }}" class="group relative py-2 transition duration-300 hover:text-white">
                Gallery 
                <span class="absolute bottom-0 left-0 h-[3px] w-0 bg-[#C9A74E] transition-all duration-300 group-hover:w-full shadow-[0_0_15px_rgba(201,167,78,0.6)]"></span>
            </a>
        </div>

        <div class="xl:hidden flex items-center">
            <button id="menu-btn" class="relative w-12 h-12 flex items-center justify-center z-50 group focus:outline-none">
                <div class="absolute inset-0 scale-0 group-hover:scale-100 bg-[#C9A74E]/10 rounded-full transition-transform duration-500"></div>
                
                <div class="relative w-7 h-5 flex flex-col justify-between items-center">
                    <span id="line-1" class="w-full h-[2px] bg-white transition-all duration-500 ease-in-out"></span>
                    <span id="line-2" class="w-full h-[2px] bg-[#C9A74E] transition-all duration-500 ease-in-out shadow-[0_0_8px_rgba(201,167,78,0.4)]"></span>
                    <span id="line-3" class="w-full h-[2px] bg-slate-500 transition-all duration-500 ease-in-out"></span>
                </div>
            </button>
        </div>
    </div>

    <div id="mobile-menu" 
        style="clip-path: circle(0% at 90% 5%);" 
        class="hidden fixed inset-0 bg-black/98 backdrop-blur-2xl z-[40] flex items-center justify-center transition-all duration-700 ease-[cubic-bezier(0.77,0,0.175,1)]">
        
        <div class="w-full px-10 flex flex-col gap-10 items-center text-center">
            <div class="flex flex-col gap-10 text-[14px] uppercase tracking-[0.5em] font-bold text-gray-300">
                <a href="/" class="group relative py-2 mobile-item opacity-0 translate-y-8 transition-all duration-700 delay-100 hover:text-white">
                    Home
                    <span class="absolute bottom-0 left-0 w-0 h-[3px] bg-[#C9A74E] transition-all duration-300 group-hover:w-full shadow-[0_0_15px_rgba(201,167,78,0.6)]"></span>
                </a>
                <a href="{{ route('artists') }}" class="group relative py-2 mobile-item opacity-0 translate-y-8 transition-all duration-700 delay-200 hover:text-white">
                    Artists
                    <span class="absolute bottom-0 left-0 w-0 h-[3px] bg-[#C9A74E] transition-all duration-300 group-hover:w-full shadow-[0_0_15px_rgba(201,167,78,0.6)]"></span>
                </a>
                <a href="{{ route('gallery') }}" class="group relative py-2 mobile-item opacity-0 translate-y-8 transition-all duration-700 delay-300 hover:text-white">
                    Gallery
                    <span class="absolute bottom-0 left-0 w-0 h-[3px] bg-[#C9A74E] transition-all duration-300 group-hover:w-full shadow-[0_0_15px_rgba(201,167,78,0.6)]"></span>
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuBtn = document.getElementById('menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const l1 = document.getElementById('line-1');
        const l2 = document.getElementById('line-2');
        const l3 = document.getElementById('line-3');

        menuBtn.addEventListener('click', function () {
            const isClosed = mobileMenu.classList.contains('hidden');

            if (isClosed) {
                // --- PROSES BUKA ---
                mobileMenu.classList.remove('hidden');
                
                setTimeout(() => {
                    mobileMenu.style.clipPath = "circle(150% at 90% 5%)";
                    
                    document.querySelectorAll('.mobile-item').forEach((item, index) => {
                        setTimeout(() => {
                            item.classList.replace('opacity-0', 'opacity-100');
                            item.classList.replace('translate-y-8', 'translate-y-0');
                        }, index * 100);
                    });
                }, 50);

                // Animasi Hamburger ke "X"
                l1.style.transform = "translateY(9px) rotate(45deg)";
                l1.classList.add('bg-[#C9A74E]');
                l1.classList.remove('bg-white');

                l2.style.opacity = "0";
                l2.style.transform = "translateX(-20px)";

                l3.style.transform = "translateY(-9px) rotate(-45deg)";
                l3.classList.add('bg-[#C9A74E]');
                l3.classList.remove('bg-slate-500');

                document.body.style.overflow = 'hidden';
            } else {
                // --- PROSES TUTUP ---
                mobileMenu.style.clipPath = "circle(0% at 90% 5%)";
                
                // Kembalikan garis ke posisi semula
                l1.style.transform = "translateY(0) rotate(0)";
                l1.classList.remove('bg-[#C9A74E]');
                l1.classList.add('bg-white');

                l2.style.opacity = "1";
                l2.style.transform = "translateX(0)";

                l3.style.transform = "translateY(0) rotate(0)";
                l3.classList.remove('bg-[#C9A74E]');
                l3.classList.add('bg-slate-500');

                // Tunggu animasi clip-path selesai sebelum menyembunyikan element
                setTimeout(() => {
                    mobileMenu.classList.add('hidden');
                    
                    // Reset posisi item agar animasi bisa diulang saat dibuka lagi
                    document.querySelectorAll('.mobile-item').forEach(item => {
                        item.classList.replace('opacity-100', 'opacity-0');
                        item.classList.replace('translate-y-0', 'translate-y-8');
                    });
                }, 700);
                
                document.body.style.overflow = '';
            }
        });
    });
</script>