<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Kapakapa Art Gallery') }}</title>
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/heroicons@2.0.16/24/outline/index.js" defer></script>
</head>
<body class="bg-[#1a1a1a] text-gray-300 antialiased font-sans">

    <button 
        id="hamburgerBtn"
        onclick="toggleSidebar()" 
        type="button"
        class="lg:hidden fixed top-5 left-5 z-[100] p-3 bg-neutral-900 border border-neutral-800 rounded-xl shadow-2xl cursor-pointer active:scale-95 transition-transform"
        aria-label="Toggle Sidebar"
    >
        <svg id="hamburgerIcon" class="w-6 h-6 text-[#C9A74E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path id="path1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16" />
            <path id="path2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12h16" />
            <path id="path3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 18h16" />
        </svg>
    </button>

    <div 
        id="sidebarOverlay"
        onclick="toggleSidebar(false)"
        class="fixed inset-0 z-[80] bg-black/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300 lg:hidden"
    ></div>

    <div class="flex h-screen overflow-hidden">
        
        <aside 
            id="mainSidebar"
            class="fixed inset-y-0 left-0 z-[90] flex flex-col w-72 h-screen p-6 transition-transform duration-300 ease-in-out transform -translate-x-full lg:translate-x-0 bg-[#1a1a1a] border-r border-neutral-800 lg:static"
        >
            <div class="relative flex flex-col items-center justify-center pt-2 pb-8 group">
                <div class="flex items-center gap-2 mb-3 opacity-40">
                    <div class="w-6 h-[1px] bg-gradient-to-r from-transparent to-[#C9A74E]"></div>
                    <div class="w-1 h-1 rotate-45 border border-[#C9A74E]"></div>
                    <div class="w-6 h-[1px] bg-gradient-to-l from-transparent to-[#C9A74E]"></div>
                </div>

                <div class="relative z-10 flex items-center justify-center w-full">
                    <div class="absolute left-4 flex flex-col gap-1 items-center">
                        <div class="w-[1.5px] h-3 bg-gradient-to-t from-[#C9A74E] to-transparent"></div>
                        <div class="w-1 h-1 rounded-full bg-[#C9A74E]"></div>
                    </div>

                    <div class="text-center px-10">
                        <h1 class="text-xl font-black tracking-[0.2em] text-gray-300 uppercase leading-none">
                            Kapakapa Art Gallery
                        </h1>
                        <div class="flex items-center justify-center gap-2 mt-2">
                            <div class="h-[1px] w-3 bg-neutral-700"></div>
                            <span class="text-[8px] tracking-[0.4em] text-[#C9A74E] uppercase font-bold opacity-80">Gallery</span>
                            <div class="h-[1px] w-3 bg-neutral-700"></div>
                        </div>
                    </div>

                    <div class="absolute right-4 flex flex-col gap-1 items-center">
                        <div class="w-1 h-1 rounded-full bg-[#C9A74E]"></div>
                        <div class="w-[1.5px] h-3 bg-gradient-to-b from-[#C9A74E] to-transparent"></div>
                    </div>
                </div>
            </div>

            <div class="relative mb-8 group">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 group-focus-within:text-[#C9A74E] transition-colors duration-300">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" placeholder="Search artworks..." 
                    class="w-full py-2.5 pl-10 pr-12 text-sm bg-neutral-800/30 border border-neutral-800 rounded-xl text-gray-300 placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-yellow-500/40 focus:bg-neutral-800/50 transition-all duration-300">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none opacity-20 group-focus-within:opacity-40 transition-opacity">
                    <div class="flex flex-col items-center">
                        <span class="text-[10px] font-black tracking-tighter text-[#C9A74E] border border-[#C9A74E]/50 rounded-md px-1 py-0">K</span>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-2 space-y-4 overflow-hidden">

                <div class="space-y-2">
                    <div onclick="toggleSection('main-menu-content', 'main-menu-arrow')" 
                        class="flex items-center justify-between px-4 py-2 text-[10px] font-bold tracking-[0.2em] text-gray-500 uppercase cursor-pointer hover:text-gray-300 transition-all group">
                        <span>Main Menu</span>
                        <svg id="main-menu-arrow" class="w-3 h-3 transition-transform duration-500 ease-out" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>

                    <div id="main-menu-content" class="grid grid-rows-[1fr] opacity-100 transition-all duration-500 ease-[cubic-bezier(0.4,0,0.2,1)]">
                        <div class="overflow-hidden">
                            <div class="relative ml-6 border-l border-neutral-800/80 space-y-1 pb-2">
                                
                                <div class="relative">
                                    <div class="absolute -left-[1px] top-6 w-3 h-4 border-l border-b border-neutral-800/80 rounded-bl-lg"></div>
                                    <a href="/master" class="group relative flex items-center gap-3 ml-6 mr-4 px-4 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('master*') ? 'text-gray-300' : 'text-gray-500 hover:text-gray-300' }}">
                                        @if(request()->is('master*'))
                                            <div class="absolute inset-0 bg-gradient-to-r from-neutral-800/80 via-neutral-800/40 to-transparent rounded-xl border border-white/5 shadow-xl"></div>
                                            <div class="absolute left-0 top-1/4 bottom-1/4 w-[2px] bg-[#C9A74E] rounded-full shadow-[0_0_12px_rgba(234,179,8,0.4)]"></div>
                                        @endif
                                        <div class="relative flex items-center gap-3">
                                            <svg class="w-5 h-5 {{ request()->is('master*') ? 'text-[#C9A74E]' : 'group-hover:text-[#C9A74E]' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                            <span>Dashboard</span>
                                        </div>
                                    </a>
                                </div>

                                <div class="relative">
                                    <div class="absolute -left-[1px] top-0 h-6 border-l border-neutral-800/80"></div>
                                    <div class="absolute -left-[1px] top-6 w-3 h-4 border-l border-b border-neutral-800/80 rounded-bl-lg"></div>
                                    <a href="/transactions" class="group relative flex items-center gap-3 ml-6 mr-4 px-4 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('transactions*') ? 'text-gray-300' : 'text-gray-500 hover:text-gray-300' }}">
                                        @if(request()->is('transactions*'))
                                            <div class="absolute inset-0 bg-gradient-to-r from-neutral-800/80 via-neutral-800/40 to-transparent rounded-xl border border-white/5 shadow-xl"></div>
                                            <div class="absolute left-0 top-1/4 bottom-1/4 w-[2px] bg-[#C9A74E] rounded-full shadow-[0_0_12px_rgba(234,179,8,0.4)]"></div>
                                        @endif
                                        <div class="relative flex items-center gap-3">
                                            <svg class="w-5 h-5 {{ request()->is('transactions*') ? 'text-[#C9A74E]' : 'group-hover:text-[#C9A74E]' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                            <span>Transactions</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <div onclick="toggleSection('features-content', 'features-arrow')" 
                        class="flex items-center justify-between px-4 py-2 text-[10px] font-bold tracking-[0.2em] text-gray-500 uppercase cursor-pointer hover:text-gray-300 transition-all group">
                        <span>Features</span>
                        <svg id="features-arrow" class="w-3 h-3 transition-transform duration-500 ease-out" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>

                    <div id="features-content" class="grid grid-rows-[1fr] opacity-100 transition-all duration-500 ease-[cubic-bezier(0.4,0,0.2,1)]">
                        <div class="overflow-hidden">
                            <div class="relative ml-6 border-l border-neutral-800/80 space-y-1 pb-2">
                                
                                <div class="relative">
                                    <div class="absolute -left-[1px] top-6 w-3 h-4 border-l border-b border-neutral-800/80 rounded-bl-lg"></div>
                                    <a href="/collectors" class="group relative flex items-center gap-3 ml-6 mr-4 px-4 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('collectors*') ? 'text-gray-300' : 'text-gray-500 hover:text-gray-300' }}">
                                        @if(request()->is('collectors*'))
                                            <div class="absolute inset-0 bg-gradient-to-r from-neutral-800/80 via-neutral-800/40 to-transparent rounded-xl border border-white/5 shadow-xl"></div>
                                            <div class="absolute left-0 top-1/4 bottom-1/4 w-[2px] bg-[#C9A74E] rounded-full shadow-[0_0_12px_rgba(234,179,8,0.4)]"></div>
                                        @endif
                                        <div class="relative flex items-center gap-3">
                                            <svg class="w-5 h-5 {{ request()->is('collectors*') ? 'text-[#C9A74E]' : 'group-hover:text-[#C9A74E]' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                            <span>Collectors</span>
                                        </div>
                                    </a>
                                </div>

                                <div class="relative">
                                    <div class="absolute -left-[1px] top-0 h-6 border-l border-neutral-800/80"></div>
                                    <div class="absolute -left-[1px] top-6 w-3 h-4 border-l border-b border-neutral-800/80 rounded-bl-lg"></div>
                                    <a href="/submit_artworks" class="group relative flex items-center gap-3 ml-6 mr-4 px-4 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('submit_artworks*') ? 'text-gray-300' : 'text-gray-500 hover:text-gray-300' }}">
                                        @if(request()->is('submit_artworks*'))
                                            <div class="absolute inset-0 bg-gradient-to-r from-neutral-800/80 via-neutral-800/40 to-transparent rounded-xl border border-white/5 shadow-xl"></div>
                                            <div class="absolute left-0 top-1/4 bottom-1/4 w-[2px] bg-[#C9A74E] rounded-full shadow-[0_0_12px_rgba(234,179,8,0.4)]"></div>
                                        @endif
                                        <div class="relative flex items-center gap-3">
                                            <svg class="w-5 h-5 {{ request()->is('submit_artworks*') ? 'text-[#C9A74E]' : 'group-hover:text-[#C9A74E]' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            <span>Submit Artworks</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <div onclick="toggleSection('tools-content', 'tools-arrow')" 
                        class="flex items-center justify-between px-4 py-2 text-[10px] font-bold tracking-[0.2em] text-gray-500 uppercase cursor-pointer hover:text-gray-300 transition-all group">
                        <span>Tools</span>
                        <svg id="tools-arrow" class="w-3 h-3 transition-transform duration-500 ease-out" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>

                    <div id="tools-content" class="grid grid-rows-[1fr] opacity-100 transition-all duration-500 ease-[cubic-bezier(0.4,0,0.2,1)]">
                        <div class="overflow-hidden">
                            <div class="relative ml-6 border-l border-neutral-800/80 space-y-1 pb-2">
                                <div class="relative">
                                    <div class="absolute -left-[1px] top-6 w-3 h-4 border-l border-b border-neutral-800/80 rounded-bl-lg"></div>
                                    <a href="/trending" class="group relative flex items-center gap-3 ml-6 mr-4 px-4 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('trending*') ? 'text-gray-300' : 'text-gray-500 hover:text-gray-300' }}">
                                        @if(request()->is('trending*'))
                                            <div class="absolute inset-0 bg-gradient-to-r from-neutral-800/80 via-neutral-800/40 to-transparent rounded-xl border border-white/5 shadow-xl"></div>
                                            <div class="absolute left-0 top-1/4 bottom-1/4 w-[2px] bg-[#C9A74E] rounded-full shadow-[0_0_12px_rgba(234,179,8,0.4)]"></div>
                                        @endif
                                        <div class="relative flex items-center gap-3">
                                            <svg class="w-5 h-5 {{ request()->is('featured*') ? 'text-[#C9A74E]' : 'group-hover:text-[#C9A74E]' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
                                            <span>Trending</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="pt-4 mt-auto">
                <div class="relative p-4 overflow-hidden rounded-xl bg-neutral-900/40 border border-neutral-800 transition-all duration-500 hover:border-[#C9A74E] group">
                    <div class="absolute -top-6 -right-6 w-16 h-16 bg-[#C9A74E]/5 blur-[30px] rounded-full group-hover:bg-[#C9A74E]/10 transition-all duration-700"></div>
                    <div class="relative z-10 flex flex-col items-center gap-3">
                        <div class="flex items-center gap-2.5 opacity-40 group-hover:opacity-80 transition-opacity">
                            <div class="w-6 h-[1px] bg-gradient-to-r from-transparent to-[#C9A74E]/50"></div>
                            <div class="w-1 h-1 rotate-45 border border-[#C9A74E]/80"></div>
                            <div class="w-6 h-[1px] bg-gradient-to-l from-transparent to-[#C9A74E]/50"></div>
                        </div>
                        <div class="text-center">
                            <h4 class="text-[14px] font-bold tracking-[0.2em] text-gray-100 uppercase leading-none">Kapakapa Art Gallery</h4>
                            <p class="text-[9px] tracking-[0.4em] text-[#C9A74E] uppercase font-medium mt-1.5">Gallery</p>
                        </div>
                        <p class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold opacity-80 group-hover:opacity-100 transition-opacity">&copy; 2026</p>
                    </div>
                </div>
            </div>
        </aside>
    </div>
    
    <script>
        let isSidebarOpen = false;
        const sectionStates = {
            'main-menu-content': true,
            'features-content': true,
            'tools-content': true
        };

        function toggleSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const btn = document.getElementById('hamburgerBtn'); // Ambil element button
            
            isSidebarOpen = !isSidebarOpen;

            if (isSidebarOpen) {
                // Sidebar muncul
                sidebar.classList.remove('-translate-x-full');
                
                // Overlay muncul
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.add('opacity-100'), 10);

                // SEMBUNYIKAN TOMBOL HAMBURGER
                btn.classList.add('opacity-0', 'pointer-events-none');
            } else {
                // Sidebar tutup
                sidebar.classList.add('-translate-x-full');
                
                // Overlay hilang
                overlay.classList.remove('opacity-100');
                setTimeout(() => overlay.classList.add('hidden'), 300);

                // MUNCULKAN KEMBALI TOMBOL HAMBURGER
                btn.classList.remove('opacity-0', 'pointer-events-none');
            }
        }

        // Fungsi Accordion Menu (Tetap sama)
        function toggleSection(contentId, arrowId) {
            const content = document.getElementById(contentId);
            const arrow = document.getElementById(arrowId);
            
            sectionStates[contentId] = !sectionStates[contentId];

            if (sectionStates[contentId]) {
                content.classList.replace('grid-rows-[0fr]', 'grid-rows-[1fr]');
                content.classList.replace('opacity-0', 'opacity-100');
                arrow.style.transform = 'rotate(0deg)';
            } else {
                content.classList.replace('grid-rows-[1fr]', 'grid-rows-[0fr]');
                content.classList.replace('opacity-100', 'opacity-0');
                arrow.style.transform = 'rotate(-90deg)';
            }
        }

        // Event Listener: Klik pada area hitam untuk menutup
        document.getElementById('sidebarOverlay').addEventListener('click', () => {
            if (isSidebarOpen) toggleSidebar();
        });
    </script>

</body>
</html>