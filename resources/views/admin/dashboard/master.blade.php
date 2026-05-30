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

        /* button hover (update settings) */
        .btn-trace {
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
        }

        /* Layer Animasi Garis */
        .btn-trace::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            /* Menggunakan putih/putih transparan agar terlihat berkilau di atas emas */
            background: conic-gradient(
                transparent, 
                black, 
                transparent 30%
            ); 
            transition: opacity 0.5s;
            /* Durasi dipercepat dari 4s ke 1.5s agar lebih agresif */
            animation: rotate 1.5s linear infinite; 
            opacity: 0;
            z-index: 1;
        }

        .btn-trace:hover::before {
            opacity: 1;
        }

        /* Layer Penutup (Membentuk Ketebalan Garis) */
        .btn-trace::after {
            content: '';
            position: absolute;
            /* Inset dinaikkan ke 3px agar garis terlihat lebih tebal */
            inset: 3px; 
            background: #C9A74E;
            border-radius: 13px; /* Sedikit lebih kecil dari parent agar lengkungan pas */
            z-index: 2;
        }

        .btn-trace span {
            position: relative;
            z-index: 3;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
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

                <div class="space-y-10 animate-fade-in p-6">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        @php
                            $stats = [
                                [
                                    'label' => 'Total Income', 
                                    'value' => '82600',
                                    'display' => '$82,600', 
                                    'prefix' => '$',
                                    'trend' => 'Growing', 
                                    'icon' => 'M20 22H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2zM9 6c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h6c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2H9zm3 2a2 2 0 0 1 2 2v1h-4v-1a2 2 0 0 1 2-2zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2z'
                                ],
                                [
                                    'label' => 'Total Orders', 
                                    'value' => '1240', 
                                    'display' => '1,240',
                                    'prefix' => '',
                                    'trend' => 'Active', 
                                    'icon' => 'M21 8l-9-4-9 4v8l9 4 9-4V8zm-9 11.5V12L4 8.5v7l8 4zm1-7.5v7.5l8-4v-7l-8 3.5z'
                                ],
                                [
                                    'label' => 'Total Visitors', 
                                    'value' => '42800', 
                                    'display' => '42.8K',
                                    'prefix' => '',
                                    'trend' => 'High Traffic', 
                                    'icon' => 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2m8-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm14 10v-2a4 4 0 0 0-3-3.87m-4-12a4 4 0 0 1 0 7.75'
                                ]
                            ];
                        @endphp

                        @foreach($stats as $stat)
                        <div class="relative group bg-neutral-900/50 border border-neutral-800 rounded-3xl p-7 transition-all duration-500 hover:border-yellow-500/40 hover:translate-y-[-5px]">
                            <div class="flex justify-between items-start mb-4">
                                <div class="p-3 bg-yellow-500/5 rounded-2xl border border-yellow-500/10 group-hover:bg-yellow-500/10 transition-colors">
                                    <svg class="w-6 h-6 text-[#C9A74E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $stat['icon'] }}"></path>
                                    </svg>
                                </div>
                                <span class="text-[10px] font-bold tracking-widest text-yellow-500/50 uppercase">{{ $stat['trend'] }}</span>
                            </div>
                            <p class="text-xs font-medium text-neutral-500 uppercase tracking-widest mb-1">{{ $stat['label'] }}</p>
                            
                            <h3 class="text-4xl font-light text-white tracking-tighter">
                                <span>{{ $stat['prefix'] }}</span><span class="counter-value" data-target="{{ $stat['value'] }}">0</span>
                            </h3>
                        </div>
                        @endforeach
                    </div>

                    {{-- CHART SECTION --}}
                    <div class="bg-neutral-900/30 border border-neutral-800/80 rounded-[3rem] p-10 backdrop-blur-sm">
                        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-12 gap-8">
                            <div class="space-y-2">
                                <div class="flex items-center gap-6">
                                    <div class="relative">
                                        <h2 class="text-3xl font-light text-white tracking-tighter flex items-center gap-3">
                                            <span class="p-2 bg-white/5 border border-white/10 rounded-xl backdrop-blur-md">
                                                <svg class="w-5 h-5 text-[#C9A74E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </span>
                                            Sales Analytics
                                        </h2>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center bg-[#1a1a1a] border border-neutral-800 p-1.5 rounded-2xl">
                                <button id="btn-monthly" onclick="switchTab('monthly')"
                                    class="tab-btn px-8 py-2.5 text-[10px] font-black tracking-[0.2em] bg-[#C9A74E] text-black rounded-xl uppercase transition-all duration-300">
                                    Monthly
                                </button>
                                <button id="btn-yearly" onclick="switchTab('yearly')"
                                    class="tab-btn px-8 py-2.5 text-[10px] font-black tracking-[0.2em] text-neutral-500 hover:text-white rounded-xl uppercase transition-all duration-300">
                                    Yearly
                                </button>
                            </div>
                        </div>

                        {{-- Legend & Detailed Info --}}
                        <div class="flex flex-wrap gap-12 mb-12">
                            <div class="pl-6">
                                <p class="text-[10px] uppercase tracking-[0.2em] text-neutral-500 mb-1">Income</p>
                                <span class="text-2xl font-light text-white">$82.600</span>
                            </div>
                            <div class="border-l-2 border-[#C9A74E] pl-6">
                                <p class="text-[10px] uppercase tracking-[0.2em] text-neutral-500 mb-1">Expenses</p>
                                <span class="text-2xl font-light text-white">$4.130</span>
                            </div>
                            <div class="border-l-2 border-[#C9A74E] pl-6">
                                <p class="text-[10px] uppercase tracking-[0.2em] text-neutral-500 mb-1">Expenses</p>
                                <span class="text-2xl font-light text-white">$112.000</span>
                            </div>
                        </div>

                        {{-- Real Chart Container --}}
                        <div class="relative w-full h-[400px] cursor-crosshair">
                            <canvas id="artSalesChart"></canvas>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                        <div class="lg:col-span-2 bg-[#1a1a1a] border border-neutral-800 rounded-[2.5rem] p-8 shadow-sm flex flex-col">
                            
                            <div class="flex justify-between items-center mb-10">
                                <div>
                                    <h2 class="text-2xl font-bold text-white/90">Visitors</h2>
                                    <p class="text-xs text-neutral-500 mt-1">Daily traffic analytics</p>
                                </div>
                                
                                <div class="flex items-center gap-3">
                                    <div class="relative group/select">
                                        <select id="yearFilter" onchange="updateYearlyData(this.value)" 
                                            class="appearance-none bg-white/[0.03] border border-white/10 text-neutral-300 text-[10px] font-black uppercase tracking-[0.15em] rounded-full px-6 py-2.5 outline-none cursor-pointer hover:bg-white/[0.08] hover:border-white/20 hover:text-white focus:border-[#C9A74E]/60 transition-all duration-300 pr-10 backdrop-blur-md">
                                            <option value="2026" class="bg-[#1a1a1a] text-white">2026</option>
                                            <option value="2025" class="bg-[#1a1a1a] text-white">2025</option>
                                            <option value="2024" class="bg-[#1a1a1a] text-white">2024</option>
                                        </select>

                                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none transition-transform duration-300 group-hover/select:translate-y-[-40%]">
                                            <svg class="w-2.5 h-2.5 text-neutral-500 group-hover/select:text-[#C9A74E] transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>
                                        </div>

                                        <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-0 h-[1px] bg-gradient-to-r from-transparent via-[#C9A74E] to-transparent transition-all duration-500 group-hover/select:w-1/2 opacity-50"></div>
                                    </div>

                                    <div class="relative custom-dropdown">
                                        <button type="button" 
                                            onclick="openWidgetMenu(this, event)" 
                                            class="text-gray-500 hover:text-[#C9A74E] transition-all focus:outline-none p-2 rounded-xl hover:bg-neutral-800/50">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                            </svg>
                                        </button>

                                        <div class="dropdown-menu hidden absolute right-0 mt-2 w-56 bg-[#161616] border border-neutral-800 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] z-[110] overflow-hidden backdrop-blur-xl">
                                            <div class="py-2">
                                                <div class="px-4 py-2 text-[10px] font-black text-gray-600 uppercase tracking-[0.2em] mb-1">Options</div>

                                                <button class="w-full text-left px-4 py-3 text-sm text-gray-400 hover:bg-neutral-800 hover:text-[#C9A74E] transition-colors flex items-center gap-3 group">
                                                    <svg class="w-4 h-4 text-gray-500 group-hover:text-[#C9A74E] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                    </svg>
                                                    Export Data
                                                </button>

                                                <button onclick="location.reload()" class="w-full text-left px-4 py-3 text-sm text-gray-400 hover:bg-neutral-800 hover:text-[#C9A74E] transition-colors flex items-center gap-3 group">
                                                    <svg class="w-4 h-4 text-gray-500 group-hover:text-[#C9A74E] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                    </svg>
                                                    Refresh
                                                </button>

                                                <hr class="border-neutral-800 my-1 mx-2">

                                                <a href="javascript:void(0)" onclick="openRemoveModal(this, event)" class="block px-4 py-3 text-sm text-red-500/80 hover:bg-red-500/10 transition-colors flex items-center gap-3 group">
                                                    <svg class="w-4 h-4 text-red-500/50 group-hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Remove Widget
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="relative flex-grow w-full min-h-[320px]">
                                <canvas id="visitorBarChart"></canvas>
                            </div>
                        </div>

                        {{-- growth customer countrys --}}
                        @php
                            $countries = [
                                ['name' => 'Indonesia', 'code' => 'id', 'count' => 892],
                                ['name' => 'United States', 'code' => 'us', 'count' => 1],
                                ['name' => 'Japan', 'code' => 'jp', 'count' => 3],
                                ['name' => 'Germany', 'code' => 'de', 'count' => 2],
                                ['name' => 'Hong Kong', 'code' => 'hk', 'count' => 8],
                                ['name' => 'Australia', 'code' => 'au', 'count' => 1],
                                ['name' => 'Brazil', 'code' => 'br', 'count' => 452],
                                ['name' => 'United Kingdom', 'code' => 'gb', 'count' => 312],
                                ['name' => 'Canada', 'code' => 'ca', 'count' => 128],
                                ['name' => 'France', 'code' => 'fr', 'count' => 95],
                            ];

                            // Ambil 6 besar saja untuk tampilan di widget luar
                            $previewCountries = array_slice($countries, 0, 6);
                        @endphp
                        <div class="lg:col-span-1 border border-white/5 rounded-[2.5rem] p-8 shadow-2xl flex flex-col relative overflow-hidden group">
                            <div class="absolute -top-24 -right-24 w-48 h-48 bg-[#C9A74E]/10 blur-[80px] rounded-full group-hover:bg-[#C9A74E]/20 transition-all duration-700"></div>

                            <div class="flex justify-between items-start mb-10 relative z-10">
                                <div>
                                    <h2 class="text-xl font-bold text-white/90 tracking-tight">Customers Growth</h2>
                                    <p class="text-sm text-gray-500 mt-1">Track customers by country</p>
                                </div>
                                
                                <div class="relative group/select">
                                    <select id="timeFilter" 
                                            onchange="filterData(this.value)" 
                                            class="appearance-none bg-neutral-900/40 backdrop-blur-md border border-white/5 text-neutral-400 text-[10px] sm:text-[11px] font-black uppercase tracking-[0.2em] rounded-full px-6 py-3 outline-none cursor-pointer 
                                                focus:border-[#C9A74E]/50 focus:text-white focus:ring-4 focus:ring-[#C9A74E]/5
                                                hover:bg-neutral-800/80 hover:border-white/10 hover:text-neutral-200
                                                transition-all duration-300 pr-12 shadow-lg">
                                        <option value="today" class="bg-[#1a1a1a] text-white">Today</option>
                                        <option value="yesterday" class="bg-[#1a1a1a] text-white">Yesterday</option>
                                        <option value="7days" class="bg-[#1a1a1a] text-white">7 Days</option>
                                    </select>
                                    
                                    <div class="absolute inset-0 rounded-full bg-gradient-to-tr from-[#C9A74E]/10 to-transparent opacity-0 group-hover/select:opacity-100 pointer-events-none transition-opacity duration-500"></div>

                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none flex items-center gap-2 border-l border-white/10 pl-3">
                                        <svg class="w-3 h-3 text-neutral-500 group-hover/select:text-[#C9A74E] group-hover/select:translate-y-0.5 transition-all duration-300" 
                                            fill="none" 
                                            stroke="currentColor" 
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-y-10 gap-x-4 mb-10 relative z-10">
                                @foreach($previewCountries as $country)
                                    <div class="flex flex-col items-center group/item cursor-default">
                                        <div class="relative mb-3">
                                            <div class="absolute inset-0 bg-[#C9A74E]/20 rounded-full blur-md opacity-0 group-hover/item:opacity-100 transition-opacity duration-500"></div>
                                            
                                            <div class="relative w-16 h-16 rounded-full border-[3px] border-neutral-800 p-1 bg-neutral-900 overflow-hidden transition-transform duration-500 group-hover/item:scale-110 group-hover/item:border-[#C9A74E]">
                                                <img src="https://flagcdn.com/w160/{{ $country['code'] }}.png" alt="{{ $country['name'] }}" class="w-full h-full object-cover rounded-full grayscale-[0.3] group-hover/item:grayscale-0 transition-all">
                                            </div>
                                        </div>
                                        
                                        <span class="text-[10px] text-neutral-500 uppercase font-black tracking-[0.15em] mb-1 group-hover/item:text-neutral-300 transition-colors">{{ $country['name'] }}</span>
                                        <span class="text-xl font-bold text-white tabular-nums">{{ number_format($country['count']) }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <button onclick="toggleCountriesModal()" class="group/btn relative w-full py-4 bg-gradient-to-b from-neutral-800 to-neutral-900 hover:from-neutral-700 hover:to-neutral-800 border border-white/5 rounded-2xl transition-all duration-300 active:scale-[0.98] overflow-hidden">
                                <div class="absolute inset-0 bg-[#C9A74E]/5 opacity-0 group-hover/btn:opacity-100 transition-opacity"></div>
                                <span class="relative flex items-center justify-center gap-3 text-white text-xs font-bold uppercase tracking-[0.2em]">
                                    View All Regions
                                    <svg class="w-8 h-8 text-[#C9A74E] group-hover/btn:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </span>
                            </button>
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

            <div class="p-8 space-y-8"> {{-- Username (Readonly) --}}
                <div class="space-y-3"> <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-2 block mb-1">Username</label>
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

    <div id="countriesModal" class="fixed inset-0 z-[1000] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-xl opacity-0 transition-opacity duration-500 ease-out" id="modalBackdrop" onclick="toggleCountriesModal()"></div>
        
        <div class="relative w-full max-w-2xl bg-[#161616]/90 border border-white/10 rounded-[3rem] shadow-[0_30px_100px_rgba(0,0,0,0.8)] transform scale-95 opacity-0 transition-all duration-500 ease-out p-10" id="modalContainer">
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-white tracking-tight">Global Presence</h2>
                    <p class="text-neutral-500 text-sm">Detailed distribution by territory</p>
                </div>
                <button onclick="toggleCountriesModal()" class="w-12 h-12 flex items-center justify-center bg-neutral-900 hover:bg-red-500/10 border border-white/5 rounded-full text-neutral-400 hover:text-red-500 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="relative mb-8">
                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-neutral-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input type="text" onkeyup="searchCountry(this.value)" placeholder="Search region..." class="w-full bg-neutral-900/50 border border-white/5 rounded-2xl py-4 pl-14 pr-6 text-white outline-none focus:border-[#C9A74E]/30 focus:ring-4 focus:ring-[#C9A74E]/5 transition-all">
            </div>

            <div id="modalCountryGrid" class="max-h-[55vh] overflow-y-auto pr-4 custom-scrollbar grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($countries as $country)
                    <div class="country-item group flex items-center justify-between bg-neutral-900/30 hover:bg-[#C9A74E]/5 border border-white/5 hover:border-[#C9A74E]/20 p-5 rounded-[1.5rem] transition-all">
                        <div class="flex items-center gap-4">
                            <img src="https://flagcdn.com/w80/{{ $country['code'] }}.png" class="w-12 h-12 rounded-2xl object-cover">
                            <div>
                                <p class="text-xs text-neutral-500 font-bold uppercase tracking-widest">{{ $country['name'] }}</p>
                                <p class="text-xl font-bold text-white group-hover:text-[#C9A74E] transition-colors">{{ number_format($country['count']) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <form id="logout-form" action="/" method="GET" class="hidden">@csrf</form>

    <script>
        function toggleCountriesModal() {
            const modal = document.getElementById('countriesModal');
            const backdrop = document.getElementById('modalBackdrop');
            const container = document.getElementById('modalContainer');

            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    backdrop.classList.add('opacity-100');
                    container.classList.remove('scale-95', 'opacity-0');
                    container.classList.add('scale-100', 'opacity-100');
                }, 10);
                document.body.style.overflow = 'hidden';
            } else {
                backdrop.classList.remove('opacity-100');
                container.classList.remove('scale-100', 'opacity-100');
                container.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }, 500);
            }
        }

        function searchCountry(query) {
            const items = document.querySelectorAll('.country-item');
            query = query.toLowerCase();
            items.forEach(item => {
                const text = item.innerText.toLowerCase();
                item.style.display = text.includes(query) ? 'flex' : 'none';
            });
        }

        function filterData(val) {
            // Efek transisi saat filter diubah
            const grid = document.querySelector('.grid-cols-3');
            grid.style.transform = 'translateY(10px)';
            grid.style.opacity = '0';
            
            setTimeout(() => {
                grid.style.transform = 'translateY(0)';
                grid.style.opacity = '1';
                console.log('Data filtered by:', val);
            }, 400);
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

        // chart Visitor Bar Chart
        let visitorChart; // Variabel global untuk menyimpan instance chart

        document.addEventListener('DOMContentLoaded', () => {
            const ctxBar = document.getElementById('visitorBarChart').getContext('2d');
            
            // Inisialisasi Chart
            visitorChart = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Visitors',
                        data: [920, 480, 510, 940, 500, 800, 960, 220, 530, 260, 470, 620], // Data awal (2026)
                        backgroundColor: '#C9A74E', 
                        hoverBackgroundColor: '#A88C3F', 
                        borderRadius: 6,
                        barThickness: 25,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 1000,
                            ticks: { stepSize: 250, color: '#6b7280' },
                            grid: { color: 'rgba(255, 255, 255, 0.05)', drawBorder: false }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#6b7280' }
                        }
                    }
                }
            });
        });

        // Fungsi untuk update data chart berdasarkan tahun
        function updateYearlyData(year) {
            // Simulasi data per tahun
            const yearlyData = {
                '2026': [920, 480, 510, 940, 500, 800, 960, 220, 530, 260, 470, 620],
                '2025': [400, 300, 600, 800, 450, 700, 850, 320, 600, 400, 550, 900],
                '2024': [200, 450, 300, 500, 250, 400, 600, 150, 300, 200, 350, 500]
            };

            if (visitorChart) {
                // Update data pada dataset pertama
                visitorChart.data.datasets[0].data = yearlyData[year];
                
                // Animasi transisi smooth
                visitorChart.update();
                
                console.log(`Chart updated for year: ${year}`);
            }
        }

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

        function switchTab(type) {
            const btnMonthly = document.getElementById('btn-monthly');
            const btnYearly = document.getElementById('btn-yearly');

            // Reset Style Kedua Tombol (Kembalikan ke tampilan default/tidak aktif)
            [btnMonthly, btnYearly].forEach(btn => {
                btn.classList.remove('bg-[#C9A74E]', 'text-black');
                btn.classList.add('text-neutral-500', 'hover:text-white');
            });

            // Berikan Style Aktif ke tombol yang dipilih
            if (type === 'monthly') {
                btnMonthly.classList.add('bg-[#C9A74E]', 'text-black');
                btnMonthly.classList.remove('text-neutral-500', 'hover:text-white');
            } else {
                btnYearly.classList.add('bg-[#C9A74E]', 'text-black');
                btnYearly.classList.remove('text-neutral-500', 'hover:text-white');
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