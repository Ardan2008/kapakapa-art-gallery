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
    <script src="https://cdn.jsdelivr.net/npm/xlsx-js-style@1.2.0/dist/xlsx.bundle.js"></script>
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
                                    'label'    => 'Total Income',
                                    'value'    => '82600',
                                    'prefix'   => '$',
                                    'trend'    => 'Growing',
                                    'key'      => 'total_income',    
                                    'icon'     => 'M20 22H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2zM9 6c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h6c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2H9zm3 2a2 2 0 0 1 2 2v1h-4v-1a2 2 0 0 1 2-2zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2z'
                                ],
                                [
                                    'label'    => 'Total Orders',
                                    'value'    => '1240',
                                    'prefix'   => '',
                                    'trend'    => 'Active',
                                    'key'      => 'total_orders',   
                                    'icon'     => 'M21 8l-9-4-9 4v8l9 4 9-4V8zm-9 11.5V12L4 8.5v7l8 4zm1-7.5v7.5l8-4v-7l-8 3.5z'
                                ],
                                [
                                    'label'    => 'Total Visitors',
                                    'value'    => '42800',
                                    'prefix'   => '',
                                    'trend'    => 'High Traffic',
                                    'key'      => 'total_visitors',  
                                    'icon'     => 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2m8-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm14 10v-2a4 4 0 0 0-3-3.87m-4-12a4 4 0 0 1 0 7.75'
                                ],
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
                                <span class="stat-prefix">{{ $stat['prefix'] }}</span>
                                {{-- data-key dipakai JS untuk update dari API --}}
                                <span class="counter-value" 
                                    data-target="{{ $stat['value'] }}" 
                                    data-key="{{ $stat['key'] }}">0</span>
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
                                            {{-- Dikosongkan — diisi JS otomatis --}}
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

                                                <button onclick="exportVisitorData()" class="w-full text-left px-4 py-3 text-sm text-gray-400 hover:bg-neutral-800 hover:text-[#C9A74E] transition-colors flex items-center gap-3 group">
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
                        <div class="lg:col-span-1 border border-white/5 rounded-[2.5rem] p-8 shadow-2xl flex flex-col relative overflow-hidden group" id="customerWidget">
                            <div class="absolute -top-24 -right-24 w-48 h-48 bg-[#C9A74E]/10 blur-[80px] rounded-full group-hover:bg-[#C9A74E]/20 transition-all duration-700"></div>

                            <div class="flex justify-between items-start mb-10 relative z-10">
                                <div>
                                    <h2 class="text-xl font-bold text-white/90 tracking-tight">Customers Growth</h2>
                                    <p class="text-sm text-gray-500 mt-1">Track customers by country</p>
                                </div>
                                
                                <div class="relative group/select">
                                    <select id="timeFilter" 
                                            onchange="fetchCustomerCountries(this.value)" 
                                            class="appearance-none bg-neutral-900/40 backdrop-blur-md border border-white/5 text-neutral-400 text-[10px] sm:text-[11px] font-black uppercase tracking-[0.2em] rounded-full px-6 py-3 outline-none cursor-pointer 
                                                focus:border-[#C9A74E]/50 focus:text-white focus:ring-4 focus:ring-[#C9A74E]/5
                                                hover:bg-neutral-800/80 hover:border-white/10 hover:text-neutral-200
                                                transition-all duration-300 pr-12 shadow-lg">
                                        <option value="today"     class="bg-[#1a1a1a] text-white">Today</option>
                                        <option value="yesterday" class="bg-[#1a1a1a] text-white">Yesterday</option>
                                        <option value="7days"     class="bg-[#1a1a1a] text-white">7 Days</option>
                                    </select>
                                    
                                    <div class="absolute inset-0 rounded-full bg-gradient-to-tr from-[#C9A74E]/10 to-transparent opacity-0 group-hover/select:opacity-100 pointer-events-none transition-opacity duration-500"></div>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none flex items-center gap-2 border-l border-white/10 pl-3">
                                        <svg class="w-3 h-3 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Grid preview 6 negara — diisi JS --}}
                            <div id="countryPreviewGrid" class="grid grid-cols-3 gap-y-10 gap-x-4 mb-10 relative z-10 transition-all duration-300">
                                {{-- skeleton loading --}}
                                @for ($i = 0; $i < 6; $i++)
                                <div class="flex flex-col items-center animate-pulse">
                                    <div class="w-16 h-16 rounded-full bg-neutral-800 mb-3"></div>
                                    <div class="w-12 h-2 bg-neutral-800 rounded mb-2"></div>
                                    <div class="w-8 h-4 bg-neutral-700 rounded"></div>
                                </div>
                                @endfor
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
                {{-- diisi JS --}}
            </div>
        </div>
    </div>

    <form id="logout-form" action="/" method="GET" class="hidden">@csrf</form>

    <script>
        // ═══════════════════════════════════════════════════════════
        // STATE GLOBAL
        // ═══════════════════════════════════════════════════════════
        let isDrawerVisible = false;
        const menuStates = {
            'main-menu-content': true,
            'features-content': true,
            'tools-content': true
        };

        // Variabel chart — deklarasi di scope global, inisialisasi di DOMContentLoaded
        let visitorChart = null;
        let artSalesChart = null;
        let visitorOnlineInterval = null;
        let visitorLiveInterval = null;

        const VISITOR_MONTHS = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        function getVisitorFallback(year) {
            const seed = parseInt(year) % 100;
            const curMonth = new Date().getMonth(); // 0-based
            return Array.from({ length: 12 }, (_, i) => {
                // Bulan yang belum terjadi = 0
                if (i > curMonth) return 0;
                return Math.round(200 + (seed * 3) + (Math.sin((i + seed) * 0.8) * 150) + (i % 3 * 80));
            });
        }

        function animateCounter(el, targetValue) {
            const startTime = performance.now();
            const duration = 2000;
            const update = (currentTime) => {
                const progress = Math.min((currentTime - startTime) / duration, 1);
                const easeOut = 1 - Math.pow(1 - progress, 3);
                el.innerText = Math.floor(easeOut * targetValue).toLocaleString('en-US');
                if (progress < 1) requestAnimationFrame(update);
                else el.innerText = targetValue.toLocaleString('en-US');
            };
            requestAnimationFrame(update);
        }

        // ═══════════════════════════════════════════════════════════
        // DOM CONTENT LOADED — satu blok saja
        // ═══════════════════════════════════════════════════════════
        document.addEventListener('DOMContentLoaded', () => {

            // ── 1. ANIMASI COUNTER ──────────────────────────────────
            fetchDashboardStats();
            setInterval(fetchDashboardStats, 60000);

            // ── 1b. FETCH STATS DARI API — update counter cards ────
            async function fetchDashboardStats() {
                try {
                    const res = await fetch('/api/dashboard/stats', {
                        headers: { 'Accept': 'application/json' }
                    });

                    if (!res.ok) throw new Error('HTTP ' + res.status);

                    const json = await res.json();
                    console.log('[Dashboard Stats] Data dari API:', json);

                    // Update setiap counter card yang punya data-key
                    document.querySelectorAll('.counter-value[data-key]').forEach(el => {
                        const key = el.getAttribute('data-key');
                        if (json[key] !== undefined) {
                            animateCounter(el, json[key]);
                        }
                    });

                } catch (err) {
                    console.warn('[Dashboard Stats] API gagal, tetap pakai nilai default dari blade:', err.message);
                    // Fallback: animasikan saja nilai default yang sudah ada di data-target
                    document.querySelectorAll('.counter-value').forEach(el => {
                        animateCounter(el, +el.getAttribute('data-target'));
                    });
                }
            }

            // ── SALES ANALYTICS CHART (Line) ────────────────────
            const salesCanvas = document.getElementById('artSalesChart');
            if (salesCanvas) {
                const ctx = salesCanvas.getContext('2d');
                const dataSets = {
                    monthly: {
                        labels: VISITOR_MONTHS,
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

                artSalesChart = new Chart(ctx, {
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
                            y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#737373' } },
                            x: { grid: { display: false }, ticks: { color: '#a3a3a3' } }
                        }
                    }
                });

                document.getElementById('btn-monthly')?.addEventListener('click', () => {
                    artSalesChart.data.labels = dataSets.monthly.labels;
                    artSalesChart.data.datasets[0].data = dataSets.monthly.data;
                    artSalesChart.update();
                });
                document.getElementById('btn-yearly')?.addEventListener('click', () => {
                    artSalesChart.data.labels = dataSets.yearly.labels;
                    artSalesChart.data.datasets[0].data = dataSets.yearly.data;
                    artSalesChart.update();
                });
            }

            // ── BUILD YEAR DROPDOWN — otomatis dari tahun sekarang ──
            async function buildYearDropdown() {
                const select = document.getElementById('yearFilter');
                if (!select) return;

                const currentYear = new Date().getFullYear();

                try {
                    const res = await fetch('/api/dashboard/visitors/years', {
                        headers: { 'Accept': 'application/json' }
                    });

                    if (!res.ok) throw new Error('HTTP ' + res.status);

                    const json = await res.json();
                    const years = json.years; // Array dari server, sudah desc

                    select.innerHTML = '';
                    years.forEach(y => {
                        const opt = document.createElement('option');
                        opt.value = y;
                        opt.textContent = y;
                        opt.className = 'bg-[#1a1a1a] text-white';
                        if (parseInt(y) === currentYear) opt.selected = true;
                        select.appendChild(opt);
                    });

                } catch (err) {
                    // Fallback: generate dari tahun sekarang mundur 3 tahun
                    console.warn('[Year Dropdown] API gagal, pakai fallback:', err.message);
                    select.innerHTML = '';
                    for (let y = currentYear; y >= currentYear - 2; y--) {
                        const opt = document.createElement('option');
                        opt.value = y;
                        opt.textContent = y;
                        opt.className = 'bg-[#1a1a1a] text-white';
                        if (y === currentYear) opt.selected = true;
                        select.appendChild(opt);
                    }
                }

                // Setelah dropdown selesai dibangun, fetch data tahun aktif
                const activeYear = select.value ?? currentYear.toString();
                fetchVisitorStats(activeYear);
            }

            // ── VISITOR BAR CHART — inisialisasi sekali ─────────
            const barCanvas = document.getElementById('visitorBarChart');
            if (barCanvas) {
                visitorChart = new Chart(barCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: VISITOR_MONTHS,
                        datasets: [{
                            label: 'Visitors',
                            data: new Array(12).fill(0),
                            backgroundColor: new Array(12).fill('#C9A74E'),
                            hoverBackgroundColor: '#A88C3F',
                            borderRadius: 6,
                            barThickness: 25,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: { duration: 500 },
                        plugins: { 
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 200,          // ← default max 200
                                ticks: { 
                                    stepSize: 50,  // ← step 50 agar tidak terlalu jarang
                                    color: '#6b7280' 
                                },
                                grid: { color: 'rgba(255,255,255,0.05)', drawBorder: false }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { color: '#6b7280' }
                            }
                        }
                    }
                });

                // Build dropdown dulu, baru fetch data tahun aktif
                buildYearDropdown();

                const defaultYear = new Date().getFullYear().toString();

                // Polling: refresh data tiap 30 detik
                visitorLiveInterval = setInterval(() => {
                    const y = document.getElementById('yearFilter')?.value ?? new Date().getFullYear().toString();
                    fetchVisitorStats(y);
                }, 30000);

                // Polling: online count tiap 10 detik
                visitorOnlineInterval = setInterval(fetchOnlineCount, 10000);
            }

            // Init Lucide icons
            lucide.createIcons();

            // Fetch customer countries saat load
            fetchCustomerCountries('today');

            // Polling tiap 60 detik
            customerCountryInterval = setInterval(() => {
                const period = document.getElementById('timeFilter')?.value ?? 'today';
                fetchCustomerCountries(period);
            }, 60000);
        });

        // ═══════════════════════════════════════════════════════════
        // VISITOR CHART — FUNGSI API
        // ═══════════════════════════════════════════════════════════

        async function fetchVisitorStats(year) {
            try {
                const res = await fetch(`/api/dashboard/visitors?year=${year}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? ''
                    }
                });

                if (!res.ok) throw new Error('HTTP ' + res.status);

                const json = await res.json();
                console.log('[Visitor Chart] Data dari API:', json);
                applyVisitorData(json.monthly, json.online ?? null);

            } catch (err) {
                console.warn('[Visitor Chart] API gagal, pakai fallback. Error:', err.message);
                applyVisitorData(getVisitorFallback(year)); // ← pakai fungsi, bukan objek statis
            }
        }

        async function fetchOnlineCount() {
            try {
                const res = await fetch('/api/dashboard/visitors/online', {
                    headers: { 'Accept': 'application/json' }
                });
                if (!res.ok) return;
                const json = await res.json();
                renderOnlineCount(json.online);
            } catch (_) {}
        }

        function applyVisitorData(monthly, online = null) {
            if (!visitorChart) return;

            const curMonth = new Date().getMonth();
            // Hitung max hanya dari bulan yang sudah terjadi
            const relevantData = monthly.slice(0, curMonth + 1);
            const maxVal = Math.max(...relevantData, 200);

            visitorChart.data.datasets[0].data = monthly;
            visitorChart.data.datasets[0].backgroundColor = monthly.map((_, i) =>
                i === curMonth ? '#E8C46A' : '#C9A74E'
            );
            visitorChart.options.scales.y.max = Math.ceil(maxVal * 1.2 / 250) * 250;
            visitorChart.update();

            if (online !== null) renderOnlineCount(online);
        }

        function renderOnlineCount(count) {
            const el = document.getElementById('visitorOnlineCount');
            if (el) el.textContent = Number(count).toLocaleString('id-ID');
        }

        // Handler select tahun — dipanggil dari onchange di HTML
        function updateYearlyData(year) {
            fetchVisitorStats(year);
        }

        // ═══════════════════════════════════════════════════════════
        // SIDEBAR & ACCORDION
        // ═══════════════════════════════════════════════════════════
        function handleNavDrawer(open, event) {
            if (event) event.stopPropagation();
            const sidebar = document.getElementById('mainSidebar') || document.getElementById('main-sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const path1 = document.getElementById('path1');
            const path2 = document.getElementById('path2');
            const path3 = document.getElementById('path3');

            isDrawerVisible = open;

            if (open) {
                sidebar?.classList.remove('-translate-x-full');
                overlay?.classList.remove('hidden');
                setTimeout(() => overlay?.classList.add('opacity-100'), 10);
                path1?.setAttribute('d', 'M6 18L18 6');
                if (path2) path2.style.opacity = '0';
                path3?.setAttribute('d', 'M6 6l12 12');
                document.body.style.overflow = 'hidden';
            } else {
                sidebar?.classList.add('-translate-x-full');
                overlay?.classList.remove('opacity-100');
                setTimeout(() => overlay?.classList.add('hidden'), 300);
                path1?.setAttribute('d', 'M4 6h16');
                if (path2) path2.style.opacity = '1';
                path3?.setAttribute('d', 'M4 18h16');
                document.body.style.overflow = '';
            }
        }

        function switchMenuAccordion(contentId, arrowId) {
            const content = document.getElementById(contentId);
            const arrow = document.getElementById(arrowId);
            if (!content) return;

            menuStates[contentId] = !menuStates[contentId];

            if (menuStates[contentId]) {
                content.classList.replace('grid-rows-[0fr]', 'grid-rows-[1fr]');
                content.classList.replace('opacity-0', 'opacity-100');
                if (arrow) arrow.style.transform = 'rotate(0deg)';
            } else {
                content.classList.replace('grid-rows-[1fr]', 'grid-rows-[0fr]');
                content.classList.replace('opacity-100', 'opacity-0');
                if (arrow) arrow.style.transform = 'rotate(-90deg)';
            }
        }

        // ═══════════════════════════════════════════════════════════
        // MODAL & DROPDOWN
        // ═══════════════════════════════════════════════════════════
        function toggleProfileDropdown(event) {
            if (event) event.stopPropagation();
            document.getElementById('profileDropdown').classList.toggle('hidden-modal');
        }

        window.onclick = function(event) {
            if (event.target.id === 'sidebarOverlay') handleNavDrawer(false);
            if (!event.target.closest('#profileButton')) {
                document.getElementById('profileDropdown')?.classList.add('hidden-modal');
            }
        };

        function openWidgetMenu(buttonElement) {
            const targetMenu = buttonElement.nextElementSibling;
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                if (menu !== targetMenu) menu.classList.add('hidden');
            });
            targetMenu.classList.toggle('hidden');
        }

        window.addEventListener('click', function(event) {
            if (!event.target.closest('.custom-dropdown')) {
                document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
            }
        });

        function switchTab(type) {
            const btnMonthly = document.getElementById('btn-monthly');
            const btnYearly = document.getElementById('btn-yearly');
            [btnMonthly, btnYearly].forEach(btn => {
                btn.classList.remove('bg-[#C9A74E]', 'text-black');
                btn.classList.add('text-neutral-500', 'hover:text-white');
            });
            if (type === 'monthly') {
                btnMonthly.classList.add('bg-[#C9A74E]', 'text-black');
                btnMonthly.classList.remove('text-neutral-500', 'hover:text-white');
            } else {
                btnYearly.classList.add('bg-[#C9A74E]', 'text-black');
                btnYearly.classList.remove('text-neutral-500', 'hover:text-white');
            }
        }

        function openSettings() {
            const modal = document.getElementById('settingsModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                document.getElementById('settingsModalContent').classList.remove('scale-95');
                document.getElementById('settingsModalContent').classList.add('scale-100');
            }, 10);
        }

        function closeSettings() {
            document.getElementById('settingsModalContent').classList.add('scale-95');
            setTimeout(() => {
                const modal = document.getElementById('settingsModal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 200);
        }

        function openLogoutModal() {
            document.getElementById('profileDropdown').classList.add('hidden-modal');
            document.getElementById('logoutModal').classList.remove('hidden-modal');
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.add('hidden-modal');
        }

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
                item.style.display = item.innerText.toLowerCase().includes(query) ? 'flex' : 'none';
            });
        }

        function filterData(val) {
            const grid = document.querySelector('.grid-cols-3');
            if (!grid) return;
            grid.style.transform = 'translateY(10px)';
            grid.style.opacity = '0';
            setTimeout(() => {
                grid.style.transform = 'translateY(0)';
                grid.style.opacity = '1';
            }, 400);
        }

        // ═══════════════════════════════════════════════════════════
        // CUSTOMER COUNTRIES — DINAMIS
        // ═══════════════════════════════════════════════════════════
        let customerCountriesData = [];
        let customerCountryInterval = null;

        async function fetchCustomerCountries(period = 'today') {
            try {
                const res = await fetch(`/api/dashboard/customers?period=${period}`, {
                    headers: { 'Accept': 'application/json' }
                });
                if (!res.ok) throw new Error('HTTP ' + res.status);
                const json = await res.json();
                customerCountriesData = json.countries;
                renderCountryPreview(customerCountriesData);
                renderCountryModal(customerCountriesData);
            } catch (err) {
                console.warn('[Customer Countries] API gagal:', err.message);
            }
        }

        function renderCountryPreview(countries) {
            const grid = document.getElementById('countryPreviewGrid');
            if (!grid) return;

            const preview = countries.slice(0, 6);

            grid.style.opacity = '0';
            grid.style.transform = 'translateY(8px)';

            setTimeout(() => {
                grid.innerHTML = preview.map(c => `
                    <div class="flex flex-col items-center group/item cursor-default">
                        <div class="relative mb-3">
                            <div class="absolute inset-0 bg-[#C9A74E]/20 rounded-full blur-md opacity-0 group-hover/item:opacity-100 transition-opacity duration-500"></div>
                            <div class="relative w-16 h-16 rounded-full border-[3px] border-neutral-800 p-1 bg-neutral-900 overflow-hidden transition-transform duration-500 group-hover/item:scale-110 group-hover/item:border-[#C9A74E]">
                                <img src="https://flagcdn.com/w160/${c.country_code}.png" 
                                    alt="${c.country_name}" 
                                    class="w-full h-full object-cover rounded-full grayscale-[0.3] group-hover/item:grayscale-0 transition-all"
                                    onerror="this.src='https://flagcdn.com/w160/un.png'">
                            </div>
                        </div>
                        <span class="text-[10px] text-neutral-500 uppercase font-black tracking-[0.15em] mb-1 group-hover/item:text-neutral-300 transition-colors text-center leading-tight">${c.country_name}</span>
                        <span class="text-xl font-bold text-white tabular-nums">${Number(c.count).toLocaleString()}</span>
                    </div>
                `).join('');

                grid.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                grid.style.opacity = '1';
                grid.style.transform = 'translateY(0)';
            }, 150);
        }

        function renderCountryModal(countries) {
            const grid = document.getElementById('modalCountryGrid');
            if (!grid) return;

            grid.innerHTML = countries.map(c => `
                <div class="country-item group flex items-center justify-between bg-neutral-900/30 hover:bg-[#C9A74E]/5 border border-white/5 hover:border-[#C9A74E]/20 p-5 rounded-[1.5rem] transition-all">
                    <div class="flex items-center gap-4">
                        <img src="https://flagcdn.com/w80/${c.country_code}.png" 
                            class="w-12 h-12 rounded-2xl object-cover"
                            onerror="this.src='https://flagcdn.com/w80/un.png'">
                        <div>
                            <p class="text-xs text-neutral-500 font-bold uppercase tracking-widest">${c.country_name}</p>
                            <p class="text-xl font-bold text-white group-hover:text-[#C9A74E] transition-colors">${Number(c.count).toLocaleString()}</p>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Override searchCountry agar pakai data dinamis
        function searchCountry(query) {
            const items = document.querySelectorAll('.country-item');
            query = query.toLowerCase();
            items.forEach(item => {
                item.style.display = item.innerText.toLowerCase().includes(query) ? 'flex' : 'none';
            });
        }

        function exportVisitorData() {
            if (!visitorChart) return;

            const year   = document.getElementById('yearFilter')?.value ?? new Date().getFullYear();
            const data   = visitorChart.data.datasets[0].data;
            const labels = visitorChart.data.labels;
            const total  = data.reduce((a, b) => a + b, 0);
            const maxVal = Math.max(...data);
            const maxMonth = labels[data.indexOf(maxVal)];

            // ── Style helpers ────────────────────────────────────────
            const GOLD   = 'FFC9A74E';
            const DARK   = 'FF1A1A1A';
            const DARK2  = 'FF2A2A2A';
            const DARK3  = 'FF222222';
            const WHITE  = 'FFFFFFFF';
            const GRAY   = 'FF9CA3AF';
            const LIGHT  = 'FFF5F0E8';

            const borderThin = {
                top:    { style: 'thin', color: { rgb: 'FF444444' } },
                bottom: { style: 'thin', color: { rgb: 'FF444444' } },
                left:   { style: 'thin', color: { rgb: 'FF444444' } },
                right:  { style: 'thin', color: { rgb: 'FF444444' } },
            };

            const cell = (v, opts = {}) => ({
                v,
                t: typeof v === 'number' ? 'n' : 's',
                s: {
                    font:      { name: 'Calibri', sz: opts.sz ?? 11, bold: opts.bold ?? false, color: { rgb: opts.color ?? WHITE } },
                    fill:      { fgColor: { rgb: opts.bg ?? DARK } },
                    alignment: { horizontal: opts.align ?? 'left', vertical: 'center', wrapText: false },
                    border:    opts.border ? borderThin : {},
                }
            });

            // ── Susun baris ──────────────────────────────────────────
            const ws_data = [];

            // Row 0 — judul besar
            ws_data.push([
                { v: `KAPAKAPA ART GALLERY`, t: 's', s: {
                    font: { name: 'Calibri', sz: 16, bold: true, color: { rgb: GOLD } },
                    fill: { fgColor: { rgb: DARK } },
                    alignment: { horizontal: 'left', vertical: 'center' }
                }},
                cell(''), cell(''), cell('')
            ]);

            // Row 1 — sub judul
            ws_data.push([
                { v: `Visitor Analytics Report — ${year}`, t: 's', s: {
                    font: { name: 'Calibri', sz: 12, bold: false, color: { rgb: GRAY } },
                    fill: { fgColor: { rgb: DARK } },
                    alignment: { horizontal: 'left', vertical: 'center' }
                }},
                cell(''), cell(''), cell('')
            ]);

            // Row 2 — generated date
            ws_data.push([
                { v: `Generated: ${new Date().toLocaleDateString('id-ID', { dateStyle: 'long' })}`, t: 's', s: {
                    font: { name: 'Calibri', sz: 9, color: { rgb: 'FF6B7280' } },
                    fill: { fgColor: { rgb: DARK } },
                    alignment: { horizontal: 'left', vertical: 'center' }
                }},
                cell(''), cell(''), cell('')
            ]);

            // Row 3 — spacer
            ws_data.push([cell(''), cell(''), cell(''), cell('')]);

            // Row 4 — header kolom
            const headerStyle = (label) => ({
                v: label, t: 's', s: {
                    font: { name: 'Calibri', sz: 11, bold: true, color: { rgb: DARK } },
                    fill: { fgColor: { rgb: GOLD } },
                    alignment: { horizontal: 'center', vertical: 'center' },
                    border: borderThin,
                }
            });
            ws_data.push([
                headerStyle('No.'),
                headerStyle('Month'),
                headerStyle('Visitors'),
                headerStyle('Share (%)'),
            ]);

            // Row 5–16 — data bulan
            labels.forEach((month, i) => {
                const isCurrentMonth = i === new Date().getMonth();
                const isPeak = data[i] === maxVal && maxVal > 0;
                const rowBg  = isPeak ? 'FF2D2510' : (i % 2 === 0 ? DARK2 : DARK3);
                const txtCol = isPeak ? GOLD : WHITE;
                const share  = total > 0 ? +((data[i] / total) * 100).toFixed(1) : 0;

                ws_data.push([
                    cell(i + 1,   { bg: rowBg, color: txtCol, align: 'center', border: true }),
                    cell(month,   { bg: rowBg, color: isPeak ? GOLD : (isCurrentMonth ? 'FFE8C46A' : WHITE), bold: isPeak, border: true }),
                    cell(data[i], { bg: rowBg, color: txtCol, align: 'right',  border: true }),
                    cell(share,   { bg: rowBg, color: txtCol, align: 'right',  border: true, sz: 10 }),
                ]);
            });

            // Row 17 — spacer
            ws_data.push([cell('', { bg: DARK }), cell('', { bg: DARK }), cell('', { bg: DARK }), cell('', { bg: DARK })]);

            // Row 18 — TOTAL
            ws_data.push([
                cell('',       { bg: 'FF111111' }),
                { v: 'TOTAL', t: 's', s: {
                    font: { name: 'Calibri', sz: 12, bold: true, color: { rgb: GOLD } },
                    fill: { fgColor: { rgb: 'FF111111' } },
                    alignment: { horizontal: 'left', vertical: 'center' },
                    border: borderThin,
                }},
                { v: total, t: 'n', s: {
                    font: { name: 'Calibri', sz: 12, bold: true, color: { rgb: GOLD } },
                    fill: { fgColor: { rgb: 'FF111111' } },
                    alignment: { horizontal: 'right', vertical: 'center' },
                    border: borderThin,
                }},
                { v: '100%', t: 's', s: {
                    font: { name: 'Calibri', sz: 11, bold: true, color: { rgb: GOLD } },
                    fill: { fgColor: { rgb: 'FF111111' } },
                    alignment: { horizontal: 'right', vertical: 'center' },
                    border: borderThin,
                }},
            ]);

            // Row 19 — Peak Month
            ws_data.push([
                cell('',         { bg: 'FF111111' }),
                cell('Peak Month', { bg: 'FF111111', color: GRAY, sz: 10 }),
                cell(maxMonth,   { bg: 'FF111111', color: 'FFE8C46A', bold: true }),
                cell(`${maxVal} visitors`, { bg: 'FF111111', color: GRAY, sz: 10 }),
            ]);

            // ── Buat worksheet ───────────────────────────────────────
            const ws = XLSX.utils.aoa_to_sheet(ws_data);

            ws['!cols'] = [
                { wch: 6  },
                { wch: 16 },
                { wch: 14 },
                { wch: 14 },
            ];

            ws['!rows'] = [
                { hpt: 28 }, // judul
                { hpt: 20 }, // sub
                { hpt: 16 }, // date
                { hpt: 10 }, // spacer
                { hpt: 22 }, // header
                ...Array(12).fill({ hpt: 20 }),
                { hpt: 8  },
                { hpt: 22 },
                { hpt: 18 },
            ];

            ws['!merges'] = [
                { s: { r: 0, c: 0 }, e: { r: 0, c: 3 } },
                { s: { r: 1, c: 0 }, e: { r: 1, c: 3 } },
                { s: { r: 2, c: 0 }, e: { r: 2, c: 3 } },
            ];

            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, `Visitors ${year}`);
            XLSX.writeFile(wb, `Kapakapa_Visitors_${year}.xlsx`);

            document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));

            Swal.fire({
                icon: 'success', title: 'Exported!',
                text: `Kapakapa_Visitors_${year}.xlsx berhasil diunduh.`,
                background: '#151515', color: '#fff',
                confirmButtonColor: '#C9A74E',
                iconColor: '#C9A74E', 
                timer: 2500, showConfirmButton: false
            });
        }

        // ═══════════════════════════════════════════════════════════
        // AUTH — LOGOUT & SETTINGS
        // ═══════════════════════════════════════════════════════════
        async function handleLogout() {
            const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
            const token = csrfTokenElement ? csrfTokenElement.getAttribute('content') : null;

            if (!token) {
                return Swal.fire({
                    title: 'SYSTEM ERROR',
                    text: 'Security token (CSRF) was not found. Please refresh the page (F5).',
                    icon: 'error', background: '#151515', color: '#ffffff', confirmButtonColor: '#C9A74E'
                });
            }

            const result = await Swal.fire({
                title: 'LOGOUT',
                text: 'Are you sure you want to end your current session?',
                icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#C9A74E', cancelButtonColor: '#333333',
                confirmButtonText: 'Yes, Sign Out', cancelButtonText: 'Cancel',
                background: '#151515', color: '#ffffff',
                customClass: { popup: 'border border-zinc-800' }
            });

            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Signing out...', allowOutsideClick: false, showConfirmButton: false,
                    background: '#151515', color: '#ffffff', didOpen: () => Swal.showLoading()
                });

                try {
                    const response = await fetch('/api/logout', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': token }
                    });

                    if (response.ok) {
                        window.location.href = '/';
                    } else {
                        const contentType = response.headers.get('content-type');
                        let errorMessage = 'Logout failed. Please try again.';
                        if (contentType?.includes('application/json')) {
                            const data = await response.json();
                            errorMessage = data.message || errorMessage;
                        }
                        throw new Error(errorMessage);
                    }
                } catch (error) {
                    Swal.fire({
                        title: 'ERROR', text: error.message || 'An unexpected server error occurred.',
                        icon: 'error', background: '#1a1a1a', color: '#ffffff', confirmButtonColor: '#C9A74E'
                    });
                }
            }
        }

        async function handleUpdateSettings() {
            const newPassword = document.getElementById('newPasswordInput').value;
            if (!newPassword) {
                return Swal.fire({ icon: 'error', title: 'Empty Field', text: 'Please enter a new password', background: '#1a1a1a', color: '#fff' });
            }

            Swal.fire({ title: 'Updating...', didOpen: () => Swal.showLoading(), background: '#151515', color: '#fff', allowOutsideClick: false });

            try {
                const response = await fetch('/api/update-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ password: newPassword }),
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (response.ok) {
                    await Swal.fire({ icon: 'success', title: 'SUCCESS', text: 'Password updated successfully!', background: '#151515', color: '#fff', confirmButtonColor: '#C9A74E' });
                    closeSettings();
                    document.getElementById('newPasswordInput').value = '';
                } else {
                    throw new Error(data.message || 'Failed to update');
                }
            } catch (error) {
                Swal.fire({ icon: 'error', title: 'ERROR', text: error.message, background: '#151515', color: '#fff' });
            }
        }

        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('svg');
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            if (isPassword) {
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.822 7.822L21 21m-2.278-2.278L15.07 15.07m-4.414-4.414L12 12m0 0l.93-.93M12 12l.93.93" />`;
            } else {
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
            }
        }
    </script>
</body>
</html>