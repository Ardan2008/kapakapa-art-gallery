<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kapakapa Art Gallery | Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- calender --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #333;
            border-radius: 10px;
        }

        /* Animations */
        @keyframes shimmer {
            to { background-position: 200% center; }
        }

        .animate-shimmer {
            animation: shimmer 3s linear infinite;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .fade-in {
            animation: fadeIn 0.2s ease-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            75% { transform: translateX(-5px); }
        }

        .shake-error {
            animation: shake 0.3s ease-in-out;
            border-color: #ef4444 !important;
            box-shadow: 0 0 10px rgba(239, 68, 68, 0.2);
        }

        @keyframes rotation {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Layout & Modals */
        .hidden-modal {
            display: none !important;
        }

        .swal2-container {
            z-index: 10001 !important;
        }

        .bg-main-dark,
        .bg-section-gray,
        .bg-modal-dark {
            background-color: #1a1a1a;
        }

        .text-gold {
            color: #C9A74E;
        }

        .modal-backdrop,
        .confirm-backdrop,
        .loading-backdrop {
            position: fixed;
            inset: 0;
            z-index: 10000;
            display: none;
            align-items: center;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(4px);
        }

        .modal-active {
            display: flex;
        }

        .modal-content,
        .confirm-content {
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            transform: scale(0.95);
            background: #1a1a1a;
            box-shadow: 0 50px 100px -20px rgba(0, 0, 0, 0.8);
        }

        .modal-active .modal-content,
        .modal-active .confirm-content {
            transform: scale(1);
        }

        .modal-header-gradient {
            background: linear-gradient(to bottom, rgba(201, 167, 78, 0.05), rgba(0, 0, 0, 0));
        }

        /* Form Elements */
        .input-mockup-dark {
            background: #222 !important;
            border: 1px solid #333 !important;
            color: #d1d5db;
            padding: 10px 15px;
            border-radius: 12px;
            font-size: 13px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .input-mockup-dark:hover {
            border-color: rgba(201, 167, 78, 0.3) !important;
            background: #252525 !important;
        }

        /* Custom Art Checkbox */
        .art-checkbox {
            appearance: none;
            -webkit-appearance: none;
            width: 22px;
            height: 22px;
            border: 2px solid #333;
            border-radius: 8px;
            background: linear-gradient(145deg, #222, #1a1a1a);
            cursor: pointer;
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .art-checkbox:hover {
            border-color: #C9A74E;
            box-shadow: 0 0 10px rgba(201, 167, 78, 0.1);
        }

        .art-checkbox:checked {
            background: #C9A74E;
            border-color: #C9A74E;
            box-shadow: 0 0 15px rgba(201, 167, 78, 0.3);
        }

        .art-checkbox:checked::after {
            content: '';
            position: absolute;
            left: 7px;
            top: 3px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .label-select-all span {
            transition: all 0.3s ease;
        }

        .label-select-all:hover span {
            color: #C9A74E;
            letter-spacing: 0.15em;
        }

        /* Flatpickr Theme */
        .flatpickr-calendar {
            background: #222 !important;
            border: 1px solid #333 !important;
            box-shadow: 0 20px 40px rgba(0,0,0,0.6) !important;
            border-radius: 20px !important;
            font-family: inherit !important;
        }

        .flatpickr-day.selected {
            background: #C9A74E !important;
            border-color: #C9A74E !important;
        }

        .flatpickr-day:hover {
            background: #333 !important;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months {
            background: #222 !important;
            color: white !important;
        }

        .flatpickr-calendar.arrowTop:before,
        .flatpickr-calendar.arrowTop:after {
            border-bottom-color: #333 !important;
        }

        /* Loader Section */
        .loading-backdrop {
            background-color: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(8px);
        }

        .loading-box {
            background: #222;
            padding: 4rem 5rem;
            border-radius: 40px;
            border: 1px solid #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 40px 70px -15px rgba(0, 0, 0, 0.7);
            min-width: 320px;
        }

        .loader {
            width: 65px;
            height: 65px;
            border: 6px solid #C9A74E;
            border-bottom-color: transparent;
            border-radius: 50%;
            display: inline-block;
            animation: rotation 1s linear infinite;
            margin-bottom: 2rem;
        }

        .text-processing {
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.3em;
            color: #ffffff;
            margin-bottom: 0.5rem;
        }

        .text-memproses {
            font-size: 12px;
            color: #C9A74E;
            font-style: italic;
            letter-spacing: 0.1em;
        }

        /* Interactive Elements */
        .refresh-spin {
            animation: spin-once 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes spin-once {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .btn-refresh-gold {
            background: linear-gradient(145deg, #222, #1a1a1a);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
            transition: all 0.3s ease;
        }

        .btn-refresh-gold:hover {
            border-color: #C9A74E;
            color: #C9A74E;
            box-shadow: 0 0 15px rgba(201, 167, 78, 0.2);
            transform: translateY(-1px);
        }

        .btn-refresh-gold:active {
            transform: translateY(1px) scale(0.95);
        }

        .select-luxury {
            background: linear-gradient(145deg, #222, #1a1a1a);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .select-luxury:hover {
            border-color: rgba(201, 167, 78, 0.5);
            background: #252525;
        }

        .select-luxury:focus {
            border-color: #C9A74E;
            box-shadow: 0 0 15px rgba(201, 167, 78, 0.15);
            color: #fff;
        }

        .select-luxury option {
            background-color: #1a1a1a;
            color: #d1d5db;
            padding: 10px;
        }

        .calendar-luxury {
            background: linear-gradient(145deg, #222, #1a1a1a);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .calendar-luxury:hover {
            border-color: rgba(201, 167, 78, 0.6);
            box-shadow: 0 0 20px rgba(201, 167, 78, 0.1);
            transform: translateY(-1px);
        }

        .calendar-luxury:active {
            transform: translateY(1px) scale(0.98);
        }

        .border-divider {
            border-color: rgba(255, 255, 255, 0.03);
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

                @php
                    $transactions = [
                        [
                            'id' => 1, 'name' => 'Maudy ceysa', 'full_name' => 'Maudy Ceysa Putri',
                            'avatar' => 'https://api.dicebear.com/8.x/avataaars/svg?seed=Maudy',
                            'whatsapp' => '08985432123456', 'purchase' => 'Tiny Chaos in Pastel',
                            'date' => '14 Jul 2026', 'payment_method' => 'VISA',
                            'payment_icon' => 'https://unavatar.io/duckduckgo/visa.com',
                            'payment_number' => '98765437***', 'price' => 50000000, 'rating' => 5,
                        ],
                        [
                            'id' => 2, 'name' => 'Kay junior', 'full_name' => 'Kay Junior Robert',
                            'avatar' => 'https://api.dicebear.com/8.x/avataaars/svg?seed=Kay',
                            'whatsapp' => '34567899876781', 'purchase' => 'Abstract Symphony',
                            'date' => '23 May 2024', 'payment_method' => 'BCA',
                            'payment_icon' => 'https://unavatar.io/duckduckgo/bca.co.id',
                            'payment_number' => '98234570***', 'price' => 35000000, 'rating' => 4,
                        ],
                        [
                            'id' => 3, 'name' => 'Rian Adhitia', 'full_name' => 'Rian Adhitia Nugraha',
                            'avatar' => 'https://api.dicebear.com/8.x/avataaars/svg?seed=Rian',
                            'whatsapp' => '081233445566', 'purchase' => 'Midnight Serenity',
                            'date' => '10 Jun 2024', 'payment_method' => 'BNI',
                            'payment_icon' => 'https://unavatar.io/duckduckgo/bni.co.id',
                            'payment_number' => '0233481***', 'price' => 12500000, 'rating' => 5,
                        ],
                        [
                            'id' => 4, 'name' => 'Siska Kohl', 'full_name' => 'Siska Kohl Putri',
                            'avatar' => 'https://api.dicebear.com/8.x/avataaars/svg?seed=Siska',
                            'whatsapp' => '087766554433', 'purchase' => 'Golden Hour Bloom',
                            'date' => '05 Aug 2024', 'payment_method' => 'PayPal',
                            'payment_icon' => 'https://unavatar.io/duckduckgo/paypal.com',
                            'payment_number' => 'siska.kohl@mail.com', 'price' => 85000000, 'rating' => 5,
                        ],
                        [
                            'id' => 5, 'name' => 'Budi Setiawan', 'full_name' => 'Budi Setiawan Binomo',
                            'avatar' => 'https://api.dicebear.com/8.x/avataaars/svg?seed=Budi',
                            'whatsapp' => '085544332211', 'purchase' => 'Digital Illusion',
                            'date' => '12 Sep 2024', 'payment_method' => 'Mandiri',
                            'payment_icon' => 'https://unavatar.io/duckduckgo/bankmandiri.co.id',
                            'payment_number' => '14200123***', 'price' => 42000000, 'rating' => 3,
                        ],
                        [
                            'id' => 6, 'name' => 'Clara Smith', 'full_name' => 'Clara Smith Richardson',
                            'avatar' => 'https://api.dicebear.com/8.x/avataaars/svg?seed=Clara',
                            'whatsapp' => '089911223344', 'purchase' => 'Velvet Dreams',
                            'date' => '20 Oct 2024', 'payment_method' => 'Mastercard',
                            'payment_icon' => 'https://unavatar.io/duckduckgo/mastercard.com',
                            'payment_number' => '54127500***', 'price' => 27500000, 'rating' => 4,
                        ],
                        [
                            'id' => 7, 'name' => 'Dwi Handoko', 'full_name' => 'Dwi Handoko Putra',
                            'avatar' => 'https://api.dicebear.com/8.x/avataaars/svg?seed=Dwi',
                            'whatsapp' => '081199887766', 'purchase' => 'Rustic Harmony',
                            'date' => '02 Nov 2024', 'payment_method' => 'BRI',
                            'payment_icon' => 'https://unavatar.io/duckduckgo/bri.co.id',
                            'payment_number' => '00120100***', 'price' => 18900000, 'rating' => 4,
                        ],
                        [
                            'id' => 8, 'name' => 'Elena G', 'full_name' => 'Elena Gomeriz',
                            'avatar' => 'https://api.dicebear.com/8.x/avataaars/svg?seed=Elena',
                            'whatsapp' => '082233445577', 'purchase' => 'Ocean Whispers',
                            'date' => '15 Dec 2024', 'payment_method' => 'GOPAY',
                            'payment_icon' => 'https://unavatar.io/duckduckgo/gojek.com',
                            'payment_number' => '08223344***', 'price' => 5500000, 'rating' => 5,
                        ],
                        [
                            'id' => 9, 'name' => 'Ferry Salim', 'full_name' => 'Ferry Salim Wijaya',
                            'avatar' => 'https://api.dicebear.com/8.x/avataaars/svg?seed=Ferry',
                            'whatsapp' => '081344556677', 'purchase' => 'Ethereal Soul',
                            'date' => '10 Jan 2025', 'payment_method' => 'OVO',
                            'payment_icon' => 'https://unavatar.io/duckduckgo/ovo.id',
                            'payment_number' => '08134455***', 'price' => 3200000, 'rating' => 4,
                        ],
                        [
                            'id' => 10, 'name' => 'Gerry K', 'full_name' => 'Gerry Kurniawan',
                            'avatar' => 'https://api.dicebear.com/8.x/avataaars/svg?seed=Gerry',
                            'whatsapp' => '087811223344', 'purchase' => 'Modern Chaos',
                            'date' => '28 Feb 2025', 'payment_method' => 'DANA',
                            'payment_icon' => 'https://unavatar.io/duckduckgo/dana.id',
                            'payment_number' => '08781122***', 'price' => 9000000, 'rating' => 5,
                        ],
                    ];
                @endphp

                <div class="bg-main-dark min-h-screen p-6 font-sans">
                    
                    <div class="flex flex-col md:flex-row justify-between gap-4 mb-8">
                        <div class="flex items-center gap-3">
                            <button onclick="handleRefresh(this)" 
                                class="btn-refresh-gold p-3 bg-[#222] rounded-full text-gray-400 border border-neutral-800 flex items-center justify-center group">
                                
                                <svg id="refreshIcon" class="w-4 h-4 transition-colors group-hover:text-gold" 
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                            </button>

                            <div id="calendarTrigger" class="relative calendar-luxury bg-section-gray border border-neutral-800 text-gray-300 py-2.5 px-6 rounded-full text-xs flex items-center gap-3 cursor-pointer group overflow-hidden">
                                
                                <svg class="w-4 h-4 text-gold transition-transform group-hover:scale-110 duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                
                                <span id="displayDate" class="font-medium tracking-wide group-hover:text-white transition-colors uppercase">10/04/2026</span>

                                <input type="text" id="datePicker" class="absolute inset-0 opacity-0 cursor-pointer">
                            </div>

                            <div class="relative group">
                                <select id="categoryFilter" class="select-luxury bg-section-gray border border-neutral-800 text-gray-400 py-2.5 px-6 rounded-full text-xs outline-none cursor-pointer appearance-none pr-12 w-full md:w-auto font-medium tracking-wide">
                                    <option value="" disabled selected>Art category</option>
                                    <option value="all">All Categories</option>
                                    <option value="painting">Painting</option>
                                    <option value="sculpture">Sculpture</option>
                                    <option value="digital">Digital Art</option>
                                </select>
                                
                                <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none transition-transform duration-300 group-hover:translate-y-[-40%] group-focus-within:rotate-180">
                                    <svg class="w-3 h-3 text-gray-500 group-hover:text-gold transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <button id="bulkDeleteBtn" onclick="showConfirm('bulk')" class="hidden bg-red-600/10 text-red-500 border border-red-500/30 px-6 py-2.5 rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all">
                                Delete (<span id="selectedCount">0</span>)
                            </button>

                            <label class="flex items-center gap-3 cursor-pointer label-select-all group">
                                <span class="text-[10px] font-black text-gray-500 uppercase tracking-[0.15em] select-none">
                                    Select All
                                </span>
                                
                                <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)" 
                                    class="art-checkbox">
                            </label>
                        </div>
                    </div>

                    <div class="bg-section-gray rounded-[2rem] border border-neutral-800 overflow-hidden shadow-2xl">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-[#151515] border-b border-neutral-800">
                                        <th class="p-5 w-16 text-center border-r border-neutral-800"></th>
                                        <th class="p-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-r border-neutral-800">Collector</th>
                                        <th class="p-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-r border-neutral-800">WhatsApp</th>
                                        <th class="p-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-r border-neutral-800">Purchase</th>
                                        <th class="p-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-r border-neutral-800">Date Time</th>
                                        <th class="p-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-r border-neutral-800">Payment</th>
                                        <th class="p-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right border-r border-neutral-800">Price</th>
                                        <th class="p-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center border-r border-neutral-800">Rating</th>
                                        <th class="p-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody" class="divide-y divide-neutral-900">
                                    @foreach($transactions as $index => $item)
                                    <tr id="row-{{ $index }}" class="group hover:bg-black/30 transition-all">
                                        <td class="p-5 text-center border-r border-neutral-800/50">
                                            <input type="checkbox" onchange="updateSelectedCount()" class="row-checkbox art-checkbox">
                                        </td>
                                        <td class="p-5 border-r border-neutral-800/50">
                                            <div class="flex items-center gap-4">
                                                <img src="{{ $item['avatar'] }}" class="w-10 h-10 rounded-full border border-neutral-700">
                                                <span class="text-sm font-semibold text-gray-300">{{ $item['name'] }}</span>
                                            </div>
                                        </td>
                                        <td class="p-5 text-xs text-gray-300 font-mono border-r border-neutral-800/50">{{ $item['whatsapp'] }}</td>
                                        <td class="p-5 text-xs italic text-gray-300 border-r border-neutral-800/50">"{{ $item['purchase'] }}"</td>
                                        <td class="p-5 text-xs text-gray-300 border-r border-neutral-800/50">{{ date('d/m/Y', strtotime($item['date'])) }}</td>
                                        <td class="p-5 border-r border-neutral-800/50">
                                            <div class="flex items-center gap-2">
                                                <img src="{{ $item['payment_icon'] }}" class="h-4 filter brightness-125">
                                                <span class="text-[10px] text-gray-400 font-mono">{{ $item['payment_number'] }}</span>
                                            </div>
                                        </td>
                                        <td class="p-5 text-right font-black text-gold text-sm tracking-tight border-r border-neutral-800/50">$ {{ number_format($item['price'], 0, ',', '.') }}</td>
                                        <td class="p-5 text-center border-r border-neutral-800/50">
                                            <div class="flex justify-center gap-0.5">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg class="w-3 h-3 {{ $i <= $item['rating'] ? 'text-orange-500 fill-orange-500' : 'text-gray-600' }}" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                        </td>
                                        <td class="p-5 text-center">
                                            <button onclick="openDetailModal({{ json_encode($item) }}, 'row-{{ $index }}')" class="px-5 py-2 bg-neutral-800 text-gray-300 border border-neutral-700 rounded-lg text-[10px] font-bold uppercase tracking-widest hover:border-gold hover:text-gold transition-all">Detail</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>

    <div id="detailModal" class="modal-backdrop p-4">
        <div onclick="closeDetailModal()" class="absolute inset-0 bg-black/80 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-lg bg-[#0f0f0f] rounded-[40px] border border-neutral-800 overflow-hidden modal-content shadow-2xl">
            
            <div class="bg-neutral-800/20 h-28 relative border-b border-neutral-800/50">
                <div class="absolute -bottom-10 left-10">
                    <div class="w-24 h-24 rounded-full border-[6px] border-[#0f0f0f] overflow-hidden bg-neutral-900 shadow-2xl">
                        <img id="d-avatar" src="" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>

            <div class="pt-16 px-10 pb-12">
                <h2 id="d-title-name" class="text-3xl font-black text-gray-300 tracking-tighter mb-8 uppercase italic border-l-4 border-gold pl-4 leading-none"></h2>
                
                <div class="space-y-4">
                    <div class="flex items-center group">
                        <div class="w-32 text-[11px] font-bold text-neutral-500 uppercase tracking-[0.2em]">Collector</div>
                        <div id="d-name" class="flex-1 bg-white/5 px-5 py-3 rounded-2xl text-base text-neutral-200 border border-white/5 transition-all group-hover:border-neutral-700"></div>
                    </div>

                    <div class="flex items-center group">
                        <div class="w-32 text-[11px] font-bold text-neutral-500 uppercase tracking-[0.2em]">WhatsApp</div>
                        <div id="d-wa" class="flex-1 bg-white/5 px-5 py-3 rounded-2xl text-sm text-neutral-400 font-mono tracking-widest border border-white/5 transition-all group-hover:border-neutral-700"></div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-32 pt-4 text-[11px] font-bold text-neutral-500 uppercase tracking-[0.2em]">Purchase</div>
                        <div class="flex-1 bg-gradient-to-br from-white/5 to-transparent p-4 rounded-[2rem] border border-white/5 flex gap-4">
                            <div class="w-20 h-20 rounded-xl overflow-hidden border border-white/10 shadow-lg shrink-0">
                                <img src="https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?q=80&w=200" class="w-full h-full object-cover">
                            </div>
                            <div class="flex flex-col justify-center overflow-hidden">
                                <span id="d-purchase-name" class="text-sm text-gray-300 font-bold italic mb-1 truncate"></span>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] text-neutral-500 font-bold uppercase tracking-tighter">Date:</span>
                                    <span id="d-date" class="text-xs text-gold font-mono font-bold"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center group">
                        <div class="w-32 text-[11px] font-bold text-neutral-500 uppercase tracking-[0.2em]">Payment</div>
                        <div class="flex-1 bg-white/5 px-5 py-3 rounded-2xl flex items-center justify-between border border-white/5 group-hover:border-neutral-700">
                            <div class="flex items-center gap-3">
                                <div class="w-14 h-10 rounded-md bg-neutral-800/50 flex items-center justify-center p-1.5 shadow-sm border border-neutral-700">
                                    <img id="d-pay-icon" src="" alt="Payment Method" class="w-full h-full object-contain filter brightness-110">
                                </div>
                                <span id="d-pay-number" class="text-xs text-neutral-400 font-mono"></span>
                            </div>
                            <div id="d-rating-stars" class="flex gap-1"></div>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="w-32 text-[11px] font-bold text-neutral-500 uppercase tracking-[0.2em]">Value</div>
                        <div id="d-price-val" class="flex-1 bg-gold/5 px-6 py-5 rounded-2xl font-black text-gold text-2xl border border-gold/20 tracking-tighter text-right shadow-[inset_0_0_20px_rgba(201,167,78,0.05)]"></div>
                    </div>
                </div>

                <div class="mt-10 flex gap-4">
                    <button onclick="closeDetailModal()" class="flex-1 py-4 bg-neutral-900/50 text-neutral-400 rounded-full text-xs font-black uppercase tracking-widest hover:text-white hover:bg-neutral-800 transition-all border border-neutral-800/50">Cancel</button>
                    <button onclick="showConfirm('single')" class="flex-1 py-4 bg-red-950/20 text-red-500 border border-red-500/20 rounded-full text-xs font-black uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all shadow-lg hover:shadow-red-600/20">Delete Record</button>
                </div>
            </div>
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

    <form id="logout-form" action="/" method="GET" class="hidden">@csrf</form>

    <script>
        let deleteMode = 'single'; 
        let currentRowId = null;   

        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. Inisialisasi Flatpickr
            if (typeof flatpickr !== "undefined") {
                flatpickr("#datePicker", {
                    dateFormat: "d/m/Y",
                    defaultDate: "10/04/2026",
                    onChange: function(selectedDates, dateStr) {
                        const displayElem = document.getElementById('displayDate');
                        if (displayElem) displayElem.innerText = dateStr;
                    }
                });
            }

            flatpickr("#datePicker", {
                dateFormat: "d/m/Y",        // Format yang ditampilkan ke user
                defaultDate: "10/04/2026",  // Tanggal awal
                disableMobile: "true",      // Memaksa tema luxury tetap muncul di HP (bukan kalender native)
                onChange: function(selectedDates, dateStr) {
                    // Update teks pada span displayDate
                    const displayElem = document.getElementById('displayDate');
                    if (displayElem) {
                        displayElem.innerText = dateStr;
                        
                        // Memberikan efek animasi sedikit saat berubah
                        displayElem.classList.add('text-gold');
                        setTimeout(() => displayElem.classList.remove('text-gold'), 300);
                    }
                    
                    // Panggil fungsi filter tabel jika ada
                    if (typeof filterTableByDate === "function") {
                        filterTableByDate(dateStr);
                    }
                }
            });

            // 2. Logika Dropdown Category Filter
            const categoryFilter = document.getElementById('categoryFilter');
            if (categoryFilter) {
                categoryFilter.addEventListener('change', function() {
                    const selectedValue = this.value;

                    // Loading state yang elegan menggunakan SweetAlert
                    Swal.fire({
                        title: 'Filtering...',
                        background: '#1a1a1a',
                        color: '#fff',
                        timer: 400,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                            const container = Swal.getContainer();
                            if (container) container.style.zIndex = '10001';
                        }
                    });

                    filterTableByCategory(selectedValue);
                });
            }
        });

        function filterTableByDate(selectedDate) {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                // Asumsi kolom tanggal ada di baris tersebut atau gunakan data-attribute
                const rowDate = row.getAttribute('data-date') || row.innerText; 
                
                if (rowDate.includes(selectedDate)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        /**
         * Filter Logic
         * Menyaring baris berdasarkan atribut 'data-category' pada tag <tr>
         */
        function filterTableByCategory(category) {
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                // Mengambil data category dari atribut data-category atau teks di dalam baris
                const rowCategory = row.getAttribute('data-category') || row.innerText.toLowerCase();

                if (category === 'all' || category === '') {
                    row.style.display = ''; 
                } else if (rowCategory.toLowerCase().includes(category.toLowerCase())) {
                    row.style.display = ''; 
                } else {
                    row.style.display = 'none'; 
                }
            });
        }

        /**
         * Refresh Page Logic
         */
        function handleRefresh(btn) {
            const icon = btn.querySelector('#refreshIcon');
            if (icon) icon.classList.add('refresh-spin');
            
            setTimeout(() => {
                location.reload();
            }, 500);
        }

        /**
         * Checkbox & Bulk Actions Logic
         */
        function toggleSelectAll(source) {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => {
                // Hanya tandai baris yang sedang terlihat (tidak terkena filter)
                const row = cb.closest('tr');
                if (row && row.style.display !== 'none') {
                    cb.checked = source.checked;
                }
            });
            updateSelectedCount();
        }

        function updateSelectedCount() {
            const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
            const deleteBtn = document.getElementById('bulkDeleteBtn');
            const countSpan = document.getElementById('selectedCount');
            
            if (countSpan) countSpan.innerText = checkedCount;
            if (deleteBtn) {
                deleteBtn.classList.toggle('hidden', checkedCount === 0);
            }
        }

        /**
         * Detail Modal Logic
         */
        function openDetailModal(item, rowId) {
            currentRowId = rowId;
            
            // Mapping ID HTML ke Property Object Item
            // Ini memastikan ID 'd-pay-icon' mengambil data dari 'item.payment_icon'
            const fields = {
                'd-avatar': item.avatar,
                'd-pay-icon': item.payment_icon, // Pastikan di objek data namanya 'payment_icon'
                'd-title-name': item.full_name || item.name,
                'd-name': item.name,
                'd-wa': item.whatsapp,
                'd-purchase-name': item.purchase,
                'd-date': item.date,
                'd-pay-number': item.payment_number
            };

            // Update elemen berdasarkan mapping di atas
            Object.keys(fields).forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    if (id === 'd-avatar' || id === 'd-pay-icon') {
                        // Jika data gambar ada, masukkan ke src. Jika tidak, beri string kosong
                        el.src = fields[id] || ''; 
                        
                        // Tambahan: Jika ini adalah icon payment dan data kosong, 
                        // sembunyikan atau beri placeholder agar tidak putih polos
                        if (id === 'd-pay-icon' && !fields[id]) {
                            el.style.opacity = "0"; // Atau el.src = "path/to/default-icon.png"
                        } else {
                            el.style.opacity = "1";
                        }
                    } else {
                        el.innerText = fields[id] || '-';
                    }
                }
            });

            // Price khusus formatting
            const priceVal = document.getElementById('d-price-val');
            if (priceVal) {
                priceVal.innerText = "$ " + (item.price || 0).toLocaleString('id-ID');
            }
            
            // Rating Stars
            const starContainer = document.getElementById('d-rating-stars');
            if (starContainer) {
                starContainer.innerHTML = ''; 
                const rating = item.rating || 0;
                for (let i = 1; i <= 5; i++) {
                    const isFilled = i <= rating;
                    starContainer.innerHTML += `
                        <svg class="w-3.5 h-3.5 ${isFilled ? 'text-orange-500 fill-orange-500' : 'text-neutral-800 fill-neutral-800'}" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>`;
                }
            }
            
            const modal = document.getElementById('detailModal');
            if (modal) modal.classList.add('modal-active');
        }

        function closeDetailModal() {
            const modal = document.getElementById('detailModal');
            if (modal) modal.classList.remove('modal-active');
        }

        /**
         * Confirmation & Delete Logic (SweetAlert2)
         */
        function showConfirm(mode) {
            deleteMode = mode;
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#333',
                confirmButtonText: 'Yes, delete it!',
                background: '#1a1a1a',
                color: '#fff',
                customClass: {
                    container: 'z-[10001]' // Pastikan muncul di depan modal manapun
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    executeDelete();
                }
            });
        }

        function executeDelete() {
            // Layar Loading
            Swal.fire({
                title: 'Deleting...',
                background: '#1a1a1a',
                color: '#fff',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            setTimeout(() => {
                if (deleteMode === 'single') {
                    if (currentRowId) {
                        const element = document.getElementById(currentRowId);
                        if (element) element.remove();
                        closeDetailModal();
                    }
                } else if (deleteMode === 'bulk') {
                    const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
                    checkedBoxes.forEach(cb => {
                        const row = cb.closest('tr');
                        if (row) row.remove();
                    });
                    const selectAll = document.getElementById('selectAll');
                    if (selectAll) selectAll.checked = false;
                }
                
                updateSelectedCount();

                // Notifikasi Sukses
                Swal.fire({
                    title: 'Deleted!',
                    text: 'Data deleted successfully.',
                    icon: 'success',
                    background: '#1a1a1a',
                    iconColor: '#C9A74E',
                    color: '#D1D5DB',
                    timer: 1500,
                    showConfirmButton: false
                });
            }, 1200);
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

        // back to top
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('backToTop');
            
            // Cari elemen utama yang memiliki scroll (biasanya main atau div dengan overflow-y-auto)
            const contentContainer = document.querySelector('main') || document.querySelector('.overflow-y-auto') || window;

            const toggleBtn = (scrolled) => {
                if (scrolled > 300) {
                    btn.classList.remove('opacity-0', 'invisible', 'translate-y-10');
                    btn.classList.add('opacity-100', 'visible', 'translate-y-0');
                } else {
                    btn.classList.add('opacity-0', 'invisible', 'translate-y-10');
                    btn.classList.remove('opacity-100', 'visible', 'translate-y-0');
                }
            };

            // Cek scroll pada window
            window.addEventListener('scroll', () => toggleBtn(window.scrollY));

            // Cek scroll jika kamu menggunakan container khusus (Dashboard style)
            if (contentContainer !== window) {
                contentContainer.addEventListener('scroll', () => toggleBtn(contentContainer.scrollTop));
            }

            // Fungsi Klik
            btn.addEventListener('click', () => {
                if (contentContainer === window) {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    contentContainer.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        });
    </script>

    <button id="backToTop" class="fixed bottom-8 right-8 z-[99999] opacity-0 invisible translate-y-10 transition-all duration-500 group">
        <div class="relative flex items-center justify-center w-12 h-12 rounded-full bg-neutral-900/80 backdrop-blur-md border border-neutral-800 transition-all duration-300 ease-out hover:scale-110 hover:bg-neutral-800 hover:border-neutral-700 active:scale-95">
            <svg class="w-5 h-5 text-gold transition-transform duration-300 ease-out group-hover:-translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
            </svg>
        </div>
    </button>
</body>
</html>