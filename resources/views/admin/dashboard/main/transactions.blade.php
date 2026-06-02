<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kapakapa Art Gallery | Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #333; border-radius: 10px; }

        @keyframes shimmer { to { background-position: 200% center; } }
        .animate-shimmer { animation: shimmer 3s linear infinite; }
        
        .hidden-modal { display: none !important; }
        
        .fade-in { animation: fadeIn 0.2s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .swal2-container {
            z-index: 10001 !important;
        }

        /* Animasi Getar */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            75% { transform: translateX(-5px); }
        }

        .shake-error {
            animation: shake 0.3s ease-in-out;
            border-color: #ef4444 !important; /* Warna merah (Red-500) */
            box-shadow: 0 0 10px rgba(239, 68, 68, 0.2);
        }

        /* top artwoks */

        /* 1. Sembunyikan scrollbar visual namun fungsi scroll tetap aktif */
        .no-scrollbar::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
        }

        .no-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }

        /* 2. Pseudo-elements untuk menciptakan bayangan gradien saat scroll */
        .scroll-shadow-container::before,
        .scroll-shadow-container::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            height: 40px; /* Tinggi area bayangan */
            z-index: 20; /* Harus lebih tinggi dari z-10 konten */
            pointer-events: none; /* Klik tembus ke konten di bawahnya */
            transition: opacity 0.3s ease;
        }

        /* Bayangan Atas (Gradien dari Hitam ke Transparan) */
        .scroll-shadow-container::before {
            top: 0;
            background: linear-gradient(to bottom, #1a1a1a 0%, rgba(26, 26, 26, 0) 100%);
        }

        /* Bayangan Bawah (Gradien dari Transparan ke Hitam) */
        .scroll-shadow-container::after {
            bottom: 0;
            background: linear-gradient(to top, #1a1a1a 0%, rgba(26, 26, 26, 0) 100%);
        }

        /* table modal logic */
        /* Mengatur tampilan scrollbar agar tipis dan elegan */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #333; /* Warna scrollbar saat diam */
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #C9A74E; /* Warna scrollbar saat di-hover (menyesuaikan tema emasmu) */
        }

        /* Memastikan header tetap solid saat di-scroll */
        thead.sticky th {
            background-color: #111;
            box-shadow: inset 0 -1px 0 #262626; /* Pengganti border agar tidak hilang saat sticky */
        }
    </style>
</head>
<body class="bg-[#1a1a1a] text-gray-300 antialiased font-sans">

    <div class="flex h-screen overflow-hidden">
        @include('admin.dashboard.layouts.sidebar')

        <div class="flex flex-col flex-1 min-w-0 overflow-hidden bg-[#1a1a1a]">
            <header class="flex items-center justify-between p-5 border-b bg-[#1a1a1a] border-neutral-800/60 sticky top-0 z-30">
                <div class="flex items-center gap-5">
                    <button onclick="handleNavDrawer(true, event)" class="p-2.5 lg:hidden text-gray-400 hover:text-yellow-500 rounded-xl">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <div class="flex flex-col leading-tight">
                        <h1 class="text-xl font-black text-white lg:text-3xl italic uppercase">
                            <span class="bg-gradient-to-r from-white via-gray-400 to-white bg-[length:200%_auto] bg-clip-text text-transparent animate-shimmer">
                                Admin Dashboard
                            </span>
                        </h1>
                        <p class="text-[10px] md:text-xs font-bold tracking-[0.3em] uppercase text-[#C9A74E] mt-1 opacity-90 flex items-center gap-2">
                            <span class="h-[1px] w-8 bg-[#C9A74E]/50"></span>
                            Welcome to your operational control center.
                        </p>
                    </div>
                </div>

                <div class="relative">
                    <button onclick="toggleProfileDropdown(event)" id="profileButton" 
                        class="flex items-center p-1.5 rounded-full border border-neutral-800 bg-neutral-900/50 hover:border-yellow-500/50 transition-all group">
                        
                        <img src="https://api.dicebear.com/8.x/notionists/svg?seed=user" 
                            class="w-10 h-10 lg:w-12 lg:h-12 rounded-full ring-2 ring-neutral-800 group-hover:ring-yellow-500/30 transition-all">
                        
                        <div class="hidden md:block px-4 text-left">
                            <p class="text-sm font-bold text-gray-300 tracking-wide">Alex Morgan</p>
                            <p class="text-[11px] text-gray-500 font-medium">Super Admin</p>
                        </div>
                    </button>

                    <div id="profileDropdown" class="absolute right-0 mt-4 w-56 rounded-2xl border border-neutral-800 bg-neutral-900 shadow-2xl z-50 hidden-modal fade-in">
                        <div class="p-2">
                            <button onclick="openSettings()" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm text-gray-400 hover:bg-neutral-800 rounded-xl transition-all">
                                <i data-lucide="settings" class="w-4 h-4 flex-shrink-0"></i>
                                <span>Settings</span>
                            </button>

                            <button onclick="handleLogout()" class="w-full mt-1 flex items-center justify-start gap-3 px-3 py-2.5 text-sm text-red-400 hover:bg-red-500/10 rounded-xl transition-all">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                                <span>Sign Out</span>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6 lg:p-10 custom-scrollbar">
                @yield('content')

                <div class="flex-1 overflow-y-auto p-6 lg:p-10 custom-scrollbar min-h-screen">
                    <div class="max-w-7xl mx-auto space-y-8">
                        
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            
                            <div class="lg:col-span-2 bg-[#1a1a1a] border border-neutral-800 rounded-[2.5rem] p-8 relative overflow-hidden group">
                                <div class="flex justify-between items-center mb-8 relative z-10">
                                    <div>
                                        <h2 class="text-2xl font-bold text-gray-200">Last Week Transactions</h2>
                                        <p class="text-gray-500 text-sm mt-1">Real-time sales performance overview</p>
                                    </div>
                                    
                                    <div class="relative custom-dropdown">
                                        <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" 
                                                class="text-gray-500 hover:text-[#C9A74E] transition-all p-2 rounded-xl hover:bg-neutral-800/50">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu hidden absolute right-0 mt-2 w-56 bg-neutral-900 border border-neutral-800 rounded-2xl shadow-2xl z-[110] backdrop-blur-xl">
                                            <div class="py-2">
                                                <div class="px-4 py-2 text-[10px] font-black text-gray-600 uppercase tracking-widest">Options</div>
                                                <button class="w-full text-left px-4 py-3 text-sm text-gray-400 hover:bg-neutral-800 hover:text-[#C9A74E] flex items-center gap-3 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                    Export Data
                                                </button>
                                                <button class="w-full text-left px-4 py-3 text-sm text-gray-400 hover:bg-neutral-800 hover:text-[#C9A74E] flex items-center gap-3 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                    Refresh
                                                </button>
                                                <hr class="border-neutral-800 my-1 mx-2">
                                                <button class="w-full text-left px-4 py-3 text-sm text-red-500/80 hover:bg-red-500/10 flex items-center gap-3 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    Remove Widget
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="h-[320px] w-full relative z-10">
                                    <canvas id="visitorBarChart"></canvas>
                                </div>
                            </div>





                            <div class="relative">
                                {{-- List Artworks --}}
                                <div class="lg:col-span-1 bg-[#1a1a1a] border border-neutral-800 rounded-[2.5rem] p-8 flex flex-col h-full">
                                    <h2 class="text-xl font-bold text-gray-300 mb-8">Top Selling Artworks</h2>
                                    <div class="space-y-4 overflow-y-auto pr-2 no-scrollbar" style="max-height: 400px;">
                                        @foreach($topArtworks as $art)
                                            <div onclick='openArtModal(@json($art))' 
                                                class="relative group cursor-pointer overflow-hidden rounded-2xl bg-black border border-neutral-800 hover:border-[#C9A74E]/40 transition-all p-4 flex items-center gap-4">
                                                <div class="relative z-10 flex-shrink-0 w-10 h-10 bg-[#C9A74E] rounded-lg flex items-center justify-center text-black font-black">
                                                    {{ $art['rank'] }}
                                                </div>
                                                <div class="relative z-10 flex-1">
                                                    <h3 class="text-gray-200 font-bold text-sm leading-tight">{{ $art['title'] }}</h3>
                                                    <p class="text-[10px] text-gray-500 font-medium">{{ $art['sold_count'] }} Items Sold</p>
                                                </div>
                                                <div class="absolute inset-0 z-0 opacity-10 group-hover:opacity-30 transition-all duration-700">
                                                    <img src="{{ $art['image'] }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- MODAL DETAIL --}}
                                <div id="artModal" class="fixed inset-0 z-[998] hidden items-center justify-center p-4 bg-black/95 backdrop-blur-sm opacity-0 transition-opacity duration-300">
                                    <div id="modalContent" class="relative bg-[#0a0a0a] border border-neutral-900 rounded-3xl overflow-hidden max-w-6xl w-full scale-95 transition-transform duration-300 shadow-2xl">
                                        
                                        {{-- Header Section --}}
                                        <div class="p-10 pb-6 flex justify-between items-end"> {{-- Ubah ke items-end agar sejajar ke bawah --}}
                                            {{-- Sisi Kiri: Judul --}}
                                            <div class="relative">
                                                <h2 class="text-3xl font-bold text-gray-300 tracking-tight leading-none">Top Selling Artworks</h2>
                                                <div class="flex items-center gap-2 mt-3">
                                                    <span class="h-1 w-8 bg-[#C9A74E] rounded-full"></span>
                                                    <p class="text-gray-500 text-sm font-medium tracking-wide" id="modalCategoryLabel">By Realisme Art</p>
                                                </div>
                                            </div>
                                            
                                            {{-- Sisi Kanan: Tombol Filter (Lebih Rendah & Jauh) --}}
                                            <div class="relative mr-24 mb-1 group" id="filterDropdownContainer">
                                                {{-- Tombol Filter --}}
                                                <button onclick="toggleFilterDropdown()" class="bg-[#1a1a1a] hover:bg-[#252525] text-gray-300 text-xs font-bold py-3 px-6 rounded-xl border border-neutral-800 transition-all flex items-center gap-3 shadow-lg hover:border-[#C9A74E]/50 active:scale-95">
                                                    <svg class="w-4 h-4 text-[#C9A74E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                                                    </svg>
                                                    <span id="currentFilterText">FILTER BY</span>
                                                    <svg class="w-3 h-3 ml-1 text-gray-500 transition-transform duration-300" id="dropdownArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </button>

                                                {{-- Menu List (Hidden by default) --}}
                                                <div id="filterMenu" class="absolute right-0 mt-2 w-48 bg-[#151515] border border-neutral-800 rounded-xl shadow-2xl overflow-hidden hidden z-[100] opacity-0 translate-y-2 transition-all duration-200">
                                                    <div class="py-1">
                                                        <div class="py-1">
                                                            {{-- Murah ke Mahal -> Price: Low to High --}}
                                                            <button onclick="applySort('asc')" class="w-full text-left px-4 py-3 text-xs text-gray-400 hover:bg-[#C9A74E] hover:text-black transition-all duration-300 font-bold uppercase tracking-widest flex justify-between items-center group">
                                                                <span>Price: Low to High</span>
                                                                <svg class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"/>
                                                                </svg>
                                                            </button>

                                                            {{-- Mahal ke Murah -> Price: High to Low --}}
                                                            <button onclick="applySort('desc')" class="w-full text-left px-4 py-3 text-xs text-gray-400 hover:bg-[#C9A74E] hover:text-black transition-all duration-300 font-bold uppercase tracking-widest border-t border-neutral-800/50 flex justify-between items-center group">
                                                                <span>Price: High to Low</span>
                                                                <svg class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Tombol Close (X) Tetap di Pojok Atas --}}
                                        <button onclick="closeArtModal()" class="absolute top-8 right-8 z-50 group">
                                            <div class="relative flex items-center justify-center w-12 h-12 transition-all duration-300 rounded-full bg-white/5 border border-white/10 group-hover:bg-red-500/20 group-hover:border-red-500/50 group-hover:rotate-90 shadow-xl backdrop-blur-md">
                                                <svg class="w-6 h-6 text-gray-400 group-hover:text-red-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="M6 18L18 6M6 6l12 12" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                <div class="absolute inset-0 rounded-full blur-lg bg-red-500/0 group-hover:bg-red-500/20 transition-all duration-300"></div>
                                            </div>
                                        </button>

                                        {{-- Table Container --}}
                                        <div class="px-8 pb-8">
                                            <div class="overflow-hidden rounded-xl border border-neutral-800 bg-[#111]">
                                                <div class="max-h-[450px] overflow-y-auto custom-scrollbar">
                                                    <table class="w-full text-left border-collapse">
                                                        <thead class="sticky top-0 z-10"> <tr class="border-b border-neutral-800 bg-[#111] text-[11px] uppercase tracking-widest">
                                                                <th class="px-6 py-4 text-gray-400 font-bold">Artworks Name</th>
                                                                <th class="px-6 py-4 text-gray-400 font-bold border-l border-neutral-800/50 text-center">Artist</th>
                                                                <th class="px-6 py-4 text-gray-400 font-bold border-l border-neutral-800/50 text-center">Collector</th>
                                                                <th class="px-6 py-4 text-gray-400 font-bold border-l border-neutral-800/50 text-center">Price</th>
                                                                <th class="px-6 py-4 text-gray-400 font-bold border-l border-neutral-800/50 text-center">Date Time</th>
                                                                <th class="px-6 py-4 text-gray-400 font-bold border-l border-neutral-800/50 text-center">Details</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-neutral-800" id="modalTableBody">
                                                            {{-- Data akan di-render oleh JavaScript --}}
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- FULL PREVIEW --}}
                                <div id="fullPreview" onclick="closeFullPreview()" class="fixed inset-0 z-[999] hidden items-center justify-center bg-black/95 backdrop-blur-2xl opacity-0 transition-opacity duration-300 cursor-zoom-out p-8">
                                    <img id="previewImg" src="" class="max-w-full max-h-[85vh] object-contain rounded-sm scale-95 transition-transform duration-500">
                                </div>
                            </div>

                            {{-- Recent Activities --}}
                            <div class="lg:col-span-3 bg-[#1a1a1a] border border-neutral-800 rounded-[2.5rem] p-8 shadow-sm overflow-hidden relative">
                                
                                {{-- Header --}}
                                <div class="flex items-center gap-3 mb-8">
                                    <div class="w-2 h-2 bg-[#C9A74E] rounded-full animate-pulse"></div>
                                    <h3 class="text-[10px] font-black text-[#C9A74E] uppercase tracking-[0.3em]">Previous Transactions</h3>
                                </div>
                                
                                {{-- Area Konten Interaktif --}}
                                <div class="relative group">
                                    
                                    {{-- TOMBOL NAVIGASI KIRI (Absolute) --}}
                                    <button onclick="sideScrollActivities('left')" 
                                        class="absolute left-4 top-1/2 -translate-y-1/2 z-20 p-4 rounded-2xl border border-neutral-800 bg-[#1a1a1a]/80 text-[#C9A74E] backdrop-blur-md opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-[#C9A74E] hover:text-black shadow-2xl cursor-pointer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
                                    </button>

                                    {{-- TOMBOL NAVIGASI KANAN (Absolute) --}}
                                    <button onclick="sideScrollActivities('right')" 
                                        class="absolute right-4 top-1/2 -translate-y-1/2 z-20 p-4 rounded-2xl border border-neutral-800 bg-[#1a1a1a]/80 text-[#C9A74E] backdrop-blur-md opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-[#C9A74E] hover:text-black shadow-2xl cursor-pointer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                                    </button>

                                    {{-- Shadow Gradients (Kiri & Kanan) --}}
                                    <div class="absolute left-0 top-0 bottom-0 w-24 z-10 bg-gradient-to-r from-[#1a1a1a] to-transparent pointer-events-none"></div>
                                    <div class="absolute right-0 top-0 bottom-0 w-24 z-10 bg-gradient-to-l from-[#1a1a1a] to-transparent pointer-events-none"></div>

                                    {{-- AREA SCROLL HORIZONTAL (ID: activityScroll) --}}
                                    <div id="activityScroll" class="flex gap-6 overflow-x-auto pb-6 no-scrollbar cursor-grab active:cursor-grabbing snap-x scroll-smooth select-none">
                                        @php
                                            // Data Dummy diperbanyak jadi 10
                                            $names = ['Calarine Clark', 'Emma Alexa', 'Julian Vance', 'Sofia Loren', 'Marcus Aurelius', 'Elena Gilbert', 'Damon Salvatore', 'Bonnie Bennett', 'Alaric Saltzman', 'Caroline Forbes'];
                                            $actions = ['Acquired "Golden Silence"', 'Completed payment', 'Bid on "Eternal Flow"', 'Purchased "Night Sky"', 'Unlocked "Vintage Soul"'];
                                            $artworks = ['Golden Silence', 'Eternal Flow', 'Night Sky', 'Vintage Soul', 'Abstract Dream'];
                                        @endphp

                                        @for ($i = 0; $i < 10; $i++)
                                            @php
                                                $name = $names[$i];
                                                $amount = '$' . number_format(rand(1000, 9000));
                                                $action = $actions[rand(0, 4)];
                                                $artwork = $artworks[rand(0, 4)];
                                                
                                                // Membuat object data untuk dikirim ke JS
                                                $activityData = [
                                                    'name' => $name,
                                                    'action' => $action,
                                                    'amount' => $amount,
                                                    'artwork' => $artwork,
                                                    'time' => rand(1, 59) . ' minutes ago',
                                                    'avatar' => "https://api.dicebear.com/7.x/avataaars/svg?seed=" . urlencode($name)
                                                ];
                                            @endphp

                                            {{-- CARD ITEM (Klik untuk membuka modal) --}}
                                            <div onclick='openActivityModal(@json($activityData))' 
                                                class="flex-none w-[350px] bg-[#141414] border border-neutral-800 rounded-3xl p-6 flex items-center justify-between group/card hover:border-[#C9A74E]/30 transition-all duration-500 snap-center">
                                                
                                                <div class="flex items-center gap-5">
                                                    <div class="w-14 h-14 rounded-2xl bg-neutral-800 border border-neutral-700 overflow-hidden rotate-3 group-hover/card:rotate-0 transition-transform">
                                                        <img src="{{ $activityData['avatar'] }}" alt="avatar" class="w-full h-full object-cover">
                                                    </div>
                                                    <div>
                                                        <h4 class="text-gray-300 font-bold text-base italic truncate w-32">{{ $name }}</h4>
                                                        <p class="text-gray-400 text-[10px] mt-1 line-clamp-1 italic">{{ $action }}</p>
                                                    </div>
                                                </div>
                                                <div class="text-right ml-4">
                                                    <span class="text-gray-600 text-[9px] uppercase font-black tracking-widest block mb-1">Value</span>
                                                    <span class="text-gray-300 text-xl font-black italic group-hover/card:scale-110 block transition-transform">{{ $amount }}</span>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>

                            {{-- Activity Modal --}}
                            <div id="activityModal" 
                                class="fixed inset-0 z-[999] hidden items-center justify-center p-4 bg-black/95 backdrop-blur-xl opacity-0 transition-opacity duration-300 cursor-default">
                                
                                {{-- Box Content --}}
                                <div class="relative bg-[#1a1a1a] border border-neutral-800 rounded-[3rem] overflow-hidden max-w-xl w-full shadow-[0_0_60px_rgba(201,167,78,0.15)] scale-95 transition-transform duration-300" id="activityModalContent">
                                    
                                    {{-- Tombol Close (X) --}}
                                    <button onclick="closeActivityModal()" class="absolute top-6 right-6 z-50 p-3 bg-black/50 text-white rounded-full hover:bg-red-500 transition-colors cursor-pointer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>

                                    <div class="p-12">
                                        {{-- Header Profil --}}
                                        <div class="flex items-center gap-6 mb-10 border-b border-neutral-800 pb-10">
                                            <div class="w-24 h-24 rounded-3xl bg-neutral-900 border-2 border-[#C9A74E]/30 p-1 flex-shrink-0">
                                                <img id="actModalAvatar" src="" alt="avatar" class="w-full h-full object-cover rounded-2xl">
                                            </div>
                                            <div>
                                                <span class="text-[#C9A74E] font-black text-[10px] tracking-[0.3em] uppercase mb-1 block">Collector Profile</span>
                                                <h2 id="actModalName" class="text-3xl font-extrabold text-gray-300 leading-tight italic mb-1"></h2>
                                                <p id="actModalTime" class="text-gray-600 text-xs font-mono"></p>
                                            </div>
                                        </div>

                                        {{-- Detail Data Aktivitas --}}
                                        <div class="space-y-6">
                                            {{-- Baris 1: Tindakan --}}
                                            <div class="flex flex-col bg-[#141414] border border-neutral-800 rounded-2xl p-6">
                                                <span class="text-gray-600 text-[9px] uppercase tracking-widest mb-2 font-bold">Activity Type</span>
                                                <p id="actModalAction" class="text-gray-300 text-lg font-medium tracking-wide italic leading-snug"></p>
                                            </div>

                                            {{-- Baris 2: Artwork & Nilai --}}
                                            <div class="grid grid-cols-2 gap-6">
                                                <div class="bg-[#141414] border border-neutral-800 rounded-2xl p-6">
                                                    <span class="text-gray-600 text-[9px] uppercase tracking-widest mb-1 font-bold">Target Artwork</span>
                                                    <p id="actModalArtwork" class="text-[#C9A74E] text-xl font-bold truncate"></p>
                                                </div>
                                                <div class="bg-[#141414] border border-neutral-800 rounded-2xl p-6 text-right">
                                                    <span class="text-gray-600 text-[9px] uppercase tracking-widest mb-1 font-bold">Transaction Value</span>
                                                    <p id="actModalAmount" class="text-gray-300 text-3xl font-black italic tracking-tighter"></p>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Footer --}}
                                        <div class="mt-12 pt-8 border-t border-white/[0.05] flex justify-center">
                                            <div class="flex items-center gap-6">
                                                {{-- Garis dekoratif kiri --}}
                                                <div class="h-[1px] w-8 bg-gradient-to-r from-transparent to-[#C9A74E]/40"></div>

                                                <div class="flex items-center gap-3">
                                                    <span class="text-gray-300 text-[9px] uppercase tracking-[0.6em] font-light leading-none">
                                                        Secure Live Feed Verified
                                                    </span>
                                                </div>

                                                {{-- Garis dekoratif kanan --}}
                                                <div class="h-[1px] w-8 bg-gradient-to-l from-transparent to-[#C9A74E]/40"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>  
            </main>
        </div>
    </div>

    <div id="settingsModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4 bg-black/80 backdrop-blur-sm transition-all duration-300">
        <div class="relative w-full max-w-md bg-[#1a1a1a] border border-neutral-800 rounded-[2.5rem] shadow-2xl overflow-hidden scale-95 transition-transform duration-300" id="settingsModalContent">
            
            <div class="p-6 border-b border-neutral-800/50 bg-neutral-900/30 text-center">
                <h3 class="text-xl font-bold text-gray-200">Account Settings</h3>
                <p class="text-[10px] text-gray-500 mt-1 uppercase tracking-[0.2em]">Manage your security</p>
            </div>

            <div class="p-8 space-y-8"> 
                {{-- Username (Readonly) --}}
                <div class="space-y-3"> 
                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-2 block mb-1">Username</label>
                    <div class="relative">
                        <input type="text" value="{{ optional(Auth::user())->username }}" readonly 
                            class="w-full bg-neutral-900/50 border border-neutral-800/50 text-gray-500 rounded-2xl px-5 py-4 cursor-not-allowed outline-none text-sm">
                        <div class="absolute right-5 top-1/2 -translate-y-1/2 text-neutral-700">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>

                {{-- New Password --}}
                <div class="space-y-3"> <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-2 block mb-1">New Password</label>
                    <div class="relative group">
                        <input id="newPasswordInput" type="password" placeholder="••••••••"
                            class="w-full bg-neutral-800/20 border border-neutral-800 text-white rounded-2xl px-5 py-4 focus:border-[#C9A74E]/50 focus:bg-neutral-800/40 outline-none transition-all text-sm placeholder:text-neutral-700">
                        
                        <button type="button" onclick="togglePassword('newPasswordInput', this)" 
                            class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-500 hover:text-[#C9A74E] transition-colors">
                            <i data-lucide="eye" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-4"> <button onclick="closeSettings()" 
                        class="flex-1 py-4 text-xs font-bold text-gray-400 bg-neutral-800/30 border border-neutral-800/50 rounded-2xl hover:bg-neutral-800 hover:text-white transition-all active:scale-95 uppercase tracking-widest">
                        Cancel
                    </button>
                    <button onclick="handleUpdateSettings()" id="btnUpdateSettings" 
                        class="flex-1 py-4 text-xs font-bold text-black bg-[#C9A74E] rounded-2xl hover:bg-[#d4b563] hover:shadow-[0_0_20px_rgba(201,167,78,0.2)] transition-all active:scale-95 uppercase tracking-widest">
                        Update
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="logoutModal" class="fixed inset-0 z-[9999] hidden-modal">
        <div onclick="closeLogoutModal()" class="absolute inset-0 bg-black/90 backdrop-blur-md"></div>
        <div class="relative flex min-h-screen items-center justify-center p-4">
            <div class="relative w-full max-w-sm p-8 bg-neutral-900 border border-neutral-800 rounded-3xl text-center fade-in" onclick="event.stopPropagation()">
                <h3 class="text-2xl font-bold text-white mb-2">Are you sure?</h3>
                <p class="text-gray-400 text-sm mb-8">You will be logged out.</p>
                <div class="flex flex-col gap-3">
                    <button onclick="handleLogout()" 
                        class="w-full py-4 bg-red-600 text-white font-bold rounded-2xl transition-all duration-300 hover:bg-red-500 hover:shadow-[0_0_20px_rgba(220,38,38,0.4)] active:scale-95">
                        CONFIRM LOG OUT
                    </button>
                    
                    <button onclick="closeLogoutModal()" 
                        class="w-full py-3 text-gray-500 font-medium transition-colors hover:text-white">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <form id="logout-form" action="/" method="GET" class="hidden">@csrf</form>

    <script>
        const activitySlider = document.getElementById('activityScroll');

        // 1. Fungsi Navigasi Tombol Kanan/Kiri
        function sideScrollActivities(direction) {
            // Menggeser sejauh lebar satu kartu (350px) + gap (24px)
            const scrollByAmount = 374; 
            if (direction === 'left') {
                activitySlider.scrollBy({ left: -scrollByAmount, behavior: 'smooth' });
            } else {
                activitySlider.scrollBy({ left: scrollByAmount, behavior: 'smooth' });
            }
        }

        // 2. Fitur Drag-to-Scroll menggunakan Mouse
        let isMouseDown = false;
        let startClickX;
        let initialScrollLeft;

        activitySlider.addEventListener('mousedown', (e) => {
            isMouseDown = true;
            // Ubah kursor jadi tangan mengepal saat drag
            activitySlider.classList.replace('cursor-grab', 'cursor-grabbing');
            startClickX = e.pageX - activitySlider.offsetLeft;
            initialScrollLeft = activitySlider.scrollLeft;
        });

        activitySlider.addEventListener('mouseleave', () => {
            isMouseDown = false;
            activitySlider.classList.replace('cursor-grabbing', 'cursor-grab');
        });

        activitySlider.addEventListener('mouseup', () => {
            isMouseDown = false;
            activitySlider.classList.replace('cursor-grabbing', 'cursor-grab');
        });

        activitySlider.addEventListener('mousemove', (e) => {
            if (!isMouseDown) return; // Berhenti jika mouse tidak ditekan
            e.preventDefault();
            const currentX = e.pageX - activitySlider.offsetLeft;
            // Angka 2 adalah multiplier kecepatan scroll
            const walkX = (currentX - startClickX) * 2; 
            activitySlider.scrollLeft = initialScrollLeft - walkX;
        });


        // logic modal mockup
        const actModal = document.getElementById('activityModal');
        const actModalContent = document.getElementById('activityModalContent');

        // Fungsi Membuka Modal dan Mengisi Data
        function openActivityModal(data) {
            // Isi elemen modal dengan data yang dikirim dari kartu
            document.getElementById('actModalAvatar').src = data.avatar;
            document.getElementById('actModalName').innerText = data.name;
            document.getElementById('actModalTime').innerText = 'Logged: ' + data.time;
            document.getElementById('actModalAction').innerText = data.action;
            document.getElementById('actModalArtwork').innerText = data.artwork;
            document.getElementById('actModalAmount').innerText = data.amount;

            // Tampilkan container modal (gunakan flex agar items-center berfungsi)
            actModal.classList.remove('hidden');
            actModal.classList.add('flex');

            // Trigger animasi masuk (fade in & scale up)
            setTimeout(() => {
                actModal.classList.add('opacity-100');
                actModalContent.classList.remove('scale-95');
                actModalContent.classList.add('scale-100');
            }, 10); // Delay sangat kecil agar browser sempat render class 'flex'
        }

        // Fungsi Menutup Modal
        function closeActivityModal() {
            // Trigger animasi keluar (fade out & scale down)
            actModal.classList.remove('opacity-100');
            actModalContent.classList.remove('scale-100');
            actModalContent.classList.add('scale-95');

            // Sembunyikan elemen setelah animasi selesai (300ms sesuai durasi di CSS)
            setTimeout(() => {
                actModal.classList.add('hidden');
                actModal.classList.remove('flex');
            }, 300);
        }

        // Menutup Modal jika mengklik area luar box (layar belakang)
        window.onclick = function(event) {
            if (event.target == actModal) {
                closeActivityModal();
            }
        }

        // Menutup Modal jika menekan tombol Escape (ESC)
        document.onkeydown = function(evt) {
            evt = evt || window.event;
            if (evt.keyCode == 27 && !actModal.classList.contains('hidden')) {
                closeActivityModal();
            }
        };

        // logic untuk dropdown filter pada modal
        function toggleFilterDropdown() {
            const menu = document.getElementById('filterMenu');
            const arrow = document.getElementById('dropdownArrow');
            
            const isHidden = menu.classList.contains('hidden');
            
            if (isHidden) {
                menu.classList.remove('hidden');
                // Trigger transition
                requestAnimationFrame(() => {
                    menu.classList.add('opacity-100', 'translate-y-0');
                    menu.classList.remove('opacity-0', 'translate-y-2');
                    arrow.classList.add('rotate-180');
                });
            } else {
                closeFilterDropdown();
            }
        }

        function closeFilterDropdown() {
            const menu = document.getElementById('filterMenu');
            const arrow = document.getElementById('dropdownArrow');
            
            menu.classList.remove('opacity-100', 'translate-y-0');
            menu.classList.add('opacity-0', 'translate-y-2');
            arrow.classList.remove('rotate-180');
            
            // Wait for transition to finish before hiding
            setTimeout(() => menu.classList.add('hidden'), 200);
        }

        // data sorting logic
        function applySort(order) {
            const textSpan = document.getElementById('currentFilterText');
            
            // Sort existing transactions array
            transactions.sort((a, b) => {
                // Remove currency symbols and commas to compare as numbers
                const priceA = parseFloat(a.price.replace(/[^0-9.-]+/g, ""));
                const priceB = parseFloat(b.price.replace(/[^0-9.-]+/g, ""));
                
                return order === 'asc' ? priceA - priceB : priceB - priceA;
            });

            // Update button display text
            textSpan.innerText = order === 'asc' ? 'PRICE: LOW TO HIGH' : 'PRICE: HIGH TO LOW';
            
            // Re-render table with sorted data
            renderTableRows();
            closeFilterDropdown();
        }



        // Global click listener to close dropdown when clicking outside
        window.addEventListener('click', (event) => {
            if (!event.target.closest('#filterDropdownContainer')) {
                closeFilterDropdown();
            }
        });

        // -- Logic untuk artwork --
        // Initialize as empty, will be populated via AJAX
        let transactions = [];

        // open the modal and render the table
        async function openArtModal(categoryData = null) {
            const modal = document.getElementById('artModal');
            const modalContent = document.getElementById('modalContent');
            const tableBody = document.getElementById('modalTableBody');
            
            if (!categoryData) return;

            // Update Label
            document.getElementById('modalCategoryLabel').innerText = `By ${categoryData.title} Art`;
            
            // Show Loading State in Table
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-10 h-10 border-4 border-[#C9A74E]/20 border-t-[#C9A74E] rounded-full animate-spin"></div>
                            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest">Fetching Sold Artworks...</p>
                        </div>
                    </td>
                </tr>
            `;

            // Display Modal with Animation
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            requestAnimationFrame(() => {
                modal.classList.add('opacity-100');
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            });

            try {
                // Fetch Data from Backend
                const response = await fetch(`/api/dashboard/sold-artworks/${encodeURIComponent(categoryData.title)}`);
                transactions = await response.json();
                
                // Render the table rows
                renderTableRows();
            } catch (error) {
                console.error("Error fetching sold artworks:", error);
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center text-red-500/80 font-bold uppercase tracking-widest text-xs">
                            Failed to load data. Please try again.
                        </td>
                    </tr>
                `;
            }
        }

        // Closes the modal with a smooth fade-out animation
        function closeArtModal() {
            const modal = document.getElementById('artModal');
            const modalContent = document.getElementById('modalContent');
            
            // Start exit animation
            modal.classList.remove('opacity-100');
            modalContent.classList.replace('scale-100', 'scale-95');
            modalContent.classList.add('opacity-0');
            
            // Hide from DOM after transition completes (300ms)
            setTimeout(() => {
                modal.classList.replace('flex', 'hidden');
            }, 300);
        }

        // optimized table rendering function
        function renderTableRows() {
            const tableBody = document.getElementById('modalTableBody');
            
            if (!tableBody) return;

            tableBody.innerHTML = transactions.map(data => {
                // Prepare activity data to reuse the existing activity modal
                const detailData = {
                    name: data.collector,
                    artwork: data.artwork,
                    amount: data.price,
                    time: data.date,
                    action: `Purchased "${data.artwork}" by ${data.artist}`,
                    avatar: `https://api.dicebear.com/7.x/avataaars/svg?seed=${encodeURIComponent(data.collector)}`
                };

                return `
                    <tr class="hover:bg-white/[0.03] transition-all duration-300 group border-b border-neutral-800/30">
                        <td class="px-6 py-5 text-gray-200 font-bold text-sm uppercase tracking-tight">
                            ${data.artwork}
                        </td>
                        <td class="px-6 py-5 text-gray-400 text-sm border-l border-neutral-800/50 text-center">
                            ${data.artist}
                        </td>
                        <td class="px-6 py-5 text-gray-400 text-sm border-l border-neutral-800/50 text-center">
                            ${data.collector}
                        </td>
                        <td class="px-6 py-5 text-[#C9A74E] font-black text-sm border-l border-neutral-800/50 text-center">
                            ${data.price}
                        </td>
                        <td class="px-6 py-5 text-gray-500 text-xs border-l border-neutral-800/50 text-center font-mono">
                            ${data.date}
                        </td>
                        <td class="px-6 py-5 border-l border-neutral-800/50 text-center">
                            <button onclick='openActivityModal(${JSON.stringify(detailData).replace(/'/g, "&apos;")})' 
                                    class="bg-[#1a1a1a] hover:bg-[#C9A74E] text-gray-400 hover:text-black text-[10px] font-bold py-2 px-5 rounded-lg border border-neutral-800 hover:border-[#C9A74E] transition-all uppercase tracking-widest shadow-lg active:scale-90">
                                Details
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // logic untuk dropdown menu pada widget
        function openWidgetMenu(buttonElement) {
            // Mencari elemen menu yang berada tepat setelah button
            const targetMenu = buttonElement.nextElementSibling;
            
            // Amankan: Tutup semua menu lain yang mungkin terbuka agar tidak numpuk
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                if (menu !== targetMenu) {
                    menu.classList.add('hidden');
                }
            });

            // Toggle (Munculkan/Sembunyikan) menu yang diklik
            targetMenu.classList.toggle('hidden');
        }

        // Logika klik di luar elemen untuk menutup menu
        window.addEventListener('click', function(event) {
            // Jika yang diklik bukan bagian dari dropdown, sembunyikan semua menu
            if (!event.target.closest('.custom-dropdown')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.add('hidden');
                });
            }
        });

        // Close dropdown saat klik di luar area
        window.addEventListener('click', function(e) {
            if (!e.target.closest('.custom-dropdown')) {
                document.querySelectorAll('.dropdown-menu').forEach(m => {
                    m.classList.add('hidden');
                    const arrow = m.previousElementSibling.querySelector('.arrow-icon');
                    if(arrow) arrow.classList.remove('rotate-180');
                });
            }
        });

        // Chart untuk Visitor Bar Chart
        document.addEventListener('DOMContentLoaded', () => {
            const ctxBar = document.getElementById('visitorBarChart').getContext('2d');
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Visitors',
                        data: [920, 480, 510, 940, 500, 800, 960, 220, 530, 450, 260, 320],
                        backgroundColor: '#C9A74E',
                        hoverBackgroundColor: '#A88C3F',
                        borderRadius: 8, // Sedikit lebih bulat agar modern
                        barThickness: 28, // Ukuran bar lebih tebal (sebelumnya 20)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            top: 10 // Memberi ruang di atas bar tertinggi
                        }
                    },
                    plugins: { 
                        legend: { display: false } 
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 1000,
                            ticks: { 
                                stepSize: 250, 
                                color: '#6b7280',
                                font: {
                                    size: 13 // Ukuran font angka samping diperbesar
                                }
                            },
                            grid: { 
                                color: 'rgba(255, 255, 255, 0.05)', 
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { 
                                color: '#9ca3af', // Warna abu lebih terang agar terbaca
                                font: {
                                    size: 13, // Ukuran font bulan diperbesar
                                    weight: '500'
                                }
                            }
                        }
                    }
                }
            });
        });


        // 1. STATE GLOBAL
        let isDrawerVisible = false;
        const menuStates = {
            'main-menu-content': true,
            'features-content': true,
            'tools-content': true
        };

        // 2. FUNGSI NAVIGASI (SIDEBAR) - SATU FUNGSI SAJA
        // Gunakan ini untuk tombol hamburger: onclick="handleNavDrawer(true, event)"
        function handleNavDrawer(open, event) {
            if (event) event.stopPropagation();
            
            // Pastikan ID ini sama dengan yang ada di HTML Anda
            const sidebar = document.getElementById('mainSidebar') || document.getElementById('main-sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            // Elemen Path Icon (Hamburger animation)
            const path1 = document.getElementById('path1');
            const path2 = document.getElementById('path2');
            const path3 = document.getElementById('path3');

            isDrawerVisible = open;

            if (isDrawerVisible) {
                sidebar?.classList.remove('-translate-x-full');
                overlay?.classList.remove('hidden');
                setTimeout(() => overlay?.classList.add('opacity-100'), 10);
                
                // Animasi Icon ke "X"
                path1?.setAttribute('d', 'M6 18L18 6');
                if(path2) path2.style.opacity = '0';
                path3?.setAttribute('d', 'M6 6l12 12');
                
                document.body.style.overflow = 'hidden'; // Lock scroll
            } else {
                sidebar?.classList.add('-translate-x-full');
                overlay?.classList.remove('opacity-100');
                setTimeout(() => overlay?.classList.add('hidden'), 300);
                
                // Animasi Icon ke Hamburger
                path1?.setAttribute('d', 'M4 6h16');
                if(path2) path2.style.opacity = '1';
                path3?.setAttribute('d', 'M4 18h16');
                
                document.body.style.overflow = ''; // Unlock scroll
            }
        }

        // 3. FUNGSI ACCORDION
        function switchMenuAccordion(contentId, arrowId) {
            const content = document.getElementById(contentId);
            const arrow = document.getElementById(arrowId);
            if(!content) return;

            menuStates[contentId] = !menuStates[contentId];

            if (menuStates[contentId]) {
                content.classList.replace('grid-rows-[0fr]', 'grid-rows-[1fr]');
                content.classList.replace('opacity-0', 'opacity-100');
                if(arrow) arrow.style.transform = 'rotate(0deg)';
            } else {
                content.classList.replace('grid-rows-[1fr]', 'grid-rows-[0fr]');
                content.classList.replace('opacity-100', 'opacity-0');
                if(arrow) arrow.style.transform = 'rotate(-90deg)';
            }
        }

        // 4. COUNTER & CHART (DOM CONTENT LOADED)
        document.addEventListener('DOMContentLoaded', () => {
            
            // --- ANIMASI COUNTER ---
            const counters = document.querySelectorAll('.counter-value');
            const duration = 3000;

            counters.forEach(counter => {
                const target = +counter.getAttribute('data-target');
                const startTime = performance.now();

                const updateCount = (currentTime) => {
                    const elapsedTime = currentTime - startTime;
                    const progress = Math.min(elapsedTime / duration, 1);
                    const easeOutCubic = 1 - Math.pow(1 - progress, 3);
                    const currentNumber = Math.floor(easeOutCubic * target);
                    
                    counter.innerText = currentNumber.toLocaleString('en-US');
                    if (progress < 1) requestAnimationFrame(updateCount);
                    else counter.innerText = target.toLocaleString('en-US');
                };
                requestAnimationFrame(updateCount);
            });

            // --- CHART ---
            const canvas = document.getElementById('artSalesChart');
            if (canvas) {
                const ctx = canvas.getContext('2d');
                const dataSets = {
                    monthly: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        data: [45, 52, 48, 70, 65, 85, 78, 92, 110, 95, 105, 120]
                    },
                    yearly: {
                        labels: ['2021', '2022', '2023', '2024', '2025', '2026'],
                        data: [450, 620, 890, 1100, 1400, 1850]
                    }
                };

                const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, 'rgba(201, 167, 78, 0.25)');
                gradient.addColorStop(1, 'rgba(201, 167, 78, 0)');

                const myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: dataSets.monthly.labels,
                        datasets: [{
                            label: 'Market Value',
                            data: dataSets.monthly.data,
                            borderColor: '#C9A74E',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            backgroundColor: gradient,
                            pointRadius: 6,
                            pointBackgroundColor: '#C9A74E',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { grid: { color: 'rgba(255, 255, 255, 0.04)' }, ticks: { color: '#737373' } },
                            x: { grid: { display: false }, ticks: { color: '#a3a3a3' } }
                        }
                    }
                });

                // Tab Switcher
                document.getElementById('btn-monthly')?.addEventListener('click', () => {
                    myChart.data.labels = dataSets.monthly.labels;
                    myChart.data.datasets[0].data = dataSets.monthly.data;
                    myChart.update();
                });
                document.getElementById('btn-yearly')?.addEventListener('click', () => {
                    myChart.data.labels = dataSets.yearly.labels;
                    myChart.data.datasets[0].data = dataSets.yearly.data;
                    myChart.update();
                });
            }
        });

        // 5. MODAL & DROPDOWN HANDLERS
        function toggleProfileDropdown(event) {
            if (event) event.stopPropagation();
            document.getElementById('profileDropdown').classList.toggle('hidden-modal');
        }

        // Klik di luar untuk menutup
        window.onclick = function(event) {
            // Tutup Sidebar jika klik overlay
            if (event.target.id === 'sidebarOverlay') {
                handleNavDrawer(false);
            }
            // Tutup Profile Dropdown
            if (!event.target.closest('#profileButton')) {
                const drop = document.getElementById('profileDropdown');
                if(drop) drop.classList.add('hidden-modal');
            }
        }

        // Modal Handlers dengan Scroll Lock
        function openSettings() {
            const modal = document.getElementById('settingsModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            // Beri sedikit delay agar transisi scale terlihat
            setTimeout(() => {
                document.getElementById('settingsModalContent').classList.remove('scale-95');
                document.getElementById('settingsModalContent').classList.add('scale-100');
            }, 10);
        }

        function closeSettings() {
            const modal = document.getElementById('settingsModal');
            document.getElementById('settingsModalContent').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 200);
        }

        function openLogoutModal() { 
            document.getElementById('profileDropdown').classList.add('hidden-modal');
            document.getElementById('logoutModal').classList.remove('hidden-modal'); 
            lockScroll(true);
        }

        function closeLogoutModal() { 
            document.getElementById('logoutModal').classList.add('hidden-modal'); 
            lockScroll(false);
        }

        lucide.createIcons();

        async function handleLogout() {
            // Locate the CSRF meta tag
            const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
            const token = csrfTokenElement ? csrfTokenElement.getAttribute('content') : null;

            // Initial Validation: If token is missing, stop process and alert the user
            if (!token) {
                console.error("CSRF token meta tag is missing!");
                return Swal.fire({
                    title: 'SYSTEM ERROR',
                    text: 'Security token (CSRF) was not found. Please refresh the page (F5).',
                    icon: 'error',
                    background: '#151515',
                    color: '#ffffff',
                    confirmButtonColor: '#C9A74E'
                });
            }

            // Logout Confirmation Dialog
            const result = await Swal.fire({
                title: 'LOGOUT',
                text: 'Are you sure you want to end your current session?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#C9A74E',
                cancelButtonColor: '#333333',
                confirmButtonText: 'Yes, Sign Out',
                cancelButtonText: 'Cancel',
                background: '#151515',
                color: '#ffffff',
                customClass: {
                    popup: 'border border-zinc-800'
                }
            });

            // If the user confirms logout
            if (result.isConfirmed) {
                // Show loading state to prevent double clicks
                Swal.fire({
                    title: 'Signing out...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    background: '#151515',
                    color: '#ffffff',
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    const response = await fetch('/api/logout', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token
                        }
                    });

                    // Check if response is successful (200-299)
                    if (response.ok) {
                        // Redirect to homepage or login page
                        window.location.href = '/'; 
                    } else {
                        // Attempt to parse error message from server
                        const contentType = response.headers.get("content-type");
                        let errorMessage = 'Logout failed. Please try again.';
                        
                        if (contentType && contentType.includes("application/json")) {
                            const data = await response.json();
                            errorMessage = data.message || errorMessage;
                        }

                        throw new Error(errorMessage);
                    }

                } catch (error) {
                    console.error("Logout Error:", error);
                    
                    // Show error alert if request fails
                    Swal.fire({
                        title: 'ERROR',
                        text: error.message || 'An unexpected server error occurred.',
                        icon: 'error',
                        background: '#1a1a1a',
                        color: '#ffffff',
                        confirmButtonColor: '#C9A73E'
                    });
                }
            }
        }
        
        // button update settings
        async function handleUpdateSettings() {
            const newPassword = document.getElementById('newPasswordInput').value;

            // Validasi simpel
            if (!newPassword) {
                return Swal.fire({
                    icon: 'error',
                    title: 'Empty Field',
                    text: 'Please enter a new password',
                    background: '#1a1a1a',
                    color: '#fff'
                });
            }

            // Tampilkan Loading
            Swal.fire({
                title: 'Updating...',
                didOpen: () => Swal.showLoading(),
                background: '#151515',
                color: '#fff',
                allowOutsideClick: false
            });

            try {
                const response = await fetch('/api/update-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json', // Memberitahu server kita minta JSON
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ password: newPassword }),
                    credentials: 'same-origin' // Memastikan cookie session ikut terkirim
                });

                const data = await response.json();

                if (response.ok) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'SUCCESS',
                        text: 'Password updated successfully!',
                        background: '#151515',
                        color: '#fff',
                        confirmButtonColor: '#C9A74E'
                    });
                    closeSettings();
                    document.getElementById('newPasswordInput').value = ''; // Reset input
                } else {
                    throw new Error(data.message || 'Failed to update');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'ERROR',
                    text: error.message,
                    background: '#151515',
                    color: '#fff'
                });
            }
        }

        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('svg');
            const isPassword = input.type === 'password';
            
            input.type = isPassword ? 'text' : 'password';
            
            // Ganti icon mata (Eye vs Eye-off)
            if (isPassword) {
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.822 7.822L21 21m-2.278-2.278L15.07 15.07m-4.414-4.414L12 12m0 0l.93-.93M12 12l.93.93" />`;
            } else {
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
            }
        }
    </script>
</body>
</html>