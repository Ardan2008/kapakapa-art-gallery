<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kapakapa Art Gallery | Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .cust   om-scrollbar::-webkit-scrollbar { width: 4px; }
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

        /* Most Viewed Styles */

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

        /* image detail */
        @keyframes pulse-soft {
            0%, 100% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.1); opacity: 1; }
        }

        .animate-pulse {
            animation: pulse-soft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* seksi kanan */
        /* Menghilangkan scrollbar di semua browser */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }

        /* Memastikan scroll terasa halus di layar sentuh */
        .no-scrollbar {
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
        }

        /* calender */
        .flatpickr-calendar {
            background: #1a1a1a !important;
            border: 1px solid #262626 !important;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5) !important;
            border-radius: 1.5rem !important;
        }
        .flatpickr-day.selected, .flatpickr-day.selected:hover {
            background: #C9A74E !important;
            border-color: #C9A74E !important;
            color: black !important;
            font-weight: bold;
        }
        .flatpickr-months .flatpickr-month, .flatpickr-current-month .flatpickr-monthDropdown-months {
            color: white !important;
            fill: white !important;
        }
        .flatpickr-weekday {
            color: #C9A74E !important;
        }
        .flatpickr-day {
            color: #a3a3a3 !important;
        }
        .flatpickr-day:hover {
            background: #262626 !important;
        }

        /* Container utama dropdown bulan & tahun */
        .flatpickr-monthDropdown-months, 
        .flatpickr-monthDropdown-month {
            background-color: #1a1a1a !important;
            color: #D1D5DB !important;
        }

        /* Bagian pembungkus dropdown (untuk memastikan tidak ada area putih yang tersisa) */
        .flatpickr-current-month .flatpickr-monthDropdown-months {
            background: #1a1a1a !important;
            border: none !important;
            padding: 2px 5px;
            border-radius: 4px;
        }

        /* Gaya untuk input tahun */
        .flatpickr-current-month input.cur-year {
            background: #1a1a1a !important;
            color: #D1D5DB !important;
        }

        /* Mengubah warna hover pada pilihan di dalam dropdown */
        .flatpickr-monthDropdown-month:hover {
            background-color: #262626 !important;
        }

        /* Menghilangkan border default browser pada saat dropdown diklik (focus) */
        .flatpickr-monthDropdown-months:focus {
            outline: none !important;
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

                <div class="max-w-7xl mx-auto space-y-8 min-h-screen">
                    @php
                        // Data Terpusat - Most Liked
                        $MostLikedArtworks = [
                            [
                                'title' => 'The Starry Night', 
                                'artist' => 'Vincent van Gogh, 1889', 
                                'image' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?q=80&w=400', 
                                'desc' => 'Sebuah representasi emosional dari pemandangan malam melalui jendela kamar rumah sakit jiwa Van Gogh.', 
                                'quote' => 'I dream my painting and I paint my dream.'
                            ],
                            [
                                'title' => 'Skull with Burning Cigarette', 
                                'artist' => 'Vincent van Gogh, 1886', 
                                'image' => 'https://plus.unsplash.com/premium_photo-1768998744557-d198775e8919?q=80&w=1055&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 
                                'desc' => 'Karya humor gelap dari masa awal Van Gogh di Antwerp.', 
                                'quote' => 'Art is to console those who are broken by life.'
                            ],
                            [
                                'title' => 'The Birth of Venus', 
                                'artist' => 'Sandro Botticelli, 1486', 
                                'image' => 'https://images.unsplash.com/photo-1582201942988-13e60e4556ee?q=80&w=400', 
                                'desc' => 'Dewi Venus muncul dari laut sebagai wanita dewasa, tiba di pantai setelah kelahirannya.', 
                                'quote' => 'Pure beauty is the reflection of the divine.'
                            ],
                            [
                                'title' => 'Ophelia', 
                                'artist' => 'John Everett Millais, 1851', 
                                'image' => 'https://plus.unsplash.com/premium_photo-1709817200255-d8688ced7490?q=80&w=1062&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 
                                'desc' => 'Menggambarkan karakter Shakespeare, Ophelia, menyanyi sesaat sebelum ia tenggelam.', 
                                'quote' => 'Too much of water hast thou, poor Ophelia.'
                            ],
                            [
                                'title' => 'Wanderer above the Sea of Fog', 
                                'artist' => 'Caspar David Friedrich, 1818', 
                                'image' => 'https://images.unsplash.com/photo-1501472312651-726afe119ff1?q=80&w=400', 
                                'desc' => 'Seorang pria berdiri di atas tebing berbatu, membelakangi pengamat, menatap ke arah laut kabut.', 
                                'quote' => 'The artist should paint what he sees before him.'
                            ],
                            [
                                'title' => 'Wheat Field with Crows', 
                                'artist' => 'Vincent van Gogh, 1890', 
                                'image' => 'https://images.unsplash.com/photo-1683753018909-37d1d9c9b435?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 
                                'desc' => 'Sering disebut sebagai salah satu karya terakhir Van Gogh yang penuh gejolak.', 
                                'quote' => 'The sadness will last forever.'
                            ],
                            [
                                'title' => 'The Fallen Angel', 
                                'artist' => 'Alexandre Cabanel, 1847', 
                                'image' => 'https://images.unsplash.com/photo-1582555172866-f73bb12a2ab3?q=80&w=400', 
                                'desc' => 'Penggambaran dramatis kemarahan dan kesedihan di mata Lucifer setelah diusir dari surga.', 
                                'quote' => 'Better to reign in Hell than serve in Heaven.'
                            ],
                            [
                                'title' => 'Girl with a Pearl Earring', 
                                'artist' => 'Johannes Vermeer, 1665', 
                                'image' => 'https://images.unsplash.com/photo-1578301978018-3005759f48f7?q=80&w=400', 
                                'desc' => 'Sering disebut sebagai "Mona Lisa dari Utara", berfokus pada tatapan mata dan pantulan cahaya pada anting-anting.', 
                                'quote' => 'Master of Light and Silence.'
                            ],
                            [
                                'title' => 'The Scream', 
                                'artist' => 'Edvard Munch, 1893', 
                                'image' => 'https://images.unsplash.com/photo-1612812166620-a072f77ec45b?w=400', 
                                'desc' => 'Ikon seni modern yang melambangkan kecemasan eksistensial manusia.', 
                                'quote' => 'I sensed a scream passing through nature.'
                            ],
                            [
                                'title' => 'Composition VII', 
                                'artist' => 'Wassily Kandinsky, 1913', 
                                'image' => 'https://images.unsplash.com/photo-1541963463532-d68292c34b19?q=80&w=400', 
                                'desc' => 'Kombinasi warna dan bentuk abstrak yang kompleks yang bertujuan untuk memicu respon emosional murni.', 
                                'quote' => 'Color is a power which directly influences the soul.'
                            ]
                        ];

                        // Data Terpusat - Most Viewed Styles (Sidebar)
                        $MostViewedStyles = [
                            [
                                'rank'   => 1, 
                                'title'  => 'Impressionism', 
                                'artist' => 'Claude Monet Style', 
                                'image'  => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?w=400'
                            ],
                            [
                                'rank'   => 2, 
                                'title'  => 'Surrealism', 
                                'artist' => 'Salvador Dalí Style', 
                                'image'  => 'https://images.unsplash.com/photo-1549490349-8643362247b5?w=400'
                            ],
                            [
                                'rank'   => 3, 
                                'title'  => 'Renaissance', 
                                'artist' => 'Da Vinci Technique', 
                                'image'  => 'https://images.unsplash.com/photo-1615529151169-7b1ff50dc7f2?w=400'
                            ],
                            [
                                'rank'   => 4, 
                                'title'  => 'Romanticism', 
                                'artist' => 'Cabanel Approach', 
                                'image'  => 'https://images.unsplash.com/photo-1582555172866-f73bb12a2ab3?w=400'
                            ],
                            [
                                'rank'   => 5, 
                                'title'  => 'Abstract Exp', 
                                'artist' => 'Jackson Pollock Style', 
                                'image'  => 'https://images.unsplash.com/photo-1541963463532-d68292c34b19?w=400'
                            ],
                            [
                                'rank'   => 6, 
                                'title'  => 'Pop Art', 
                                'artist' => 'Andy Warhol Style', 
                                'image'  => 'https://images.unsplash.com/photo-1632267071031-1cda764f851f?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'
                            ],
                            [
                                'rank'   => 7, 
                                'title'  => 'Expressionism', 
                                'artist' => 'Edvard Munch Style', 
                                'image'  => 'https://images.unsplash.com/photo-1612812166620-a072f77ec45b?w=400'
                            ],
                            [
                                'rank'   => 8, 
                                'title'  => 'Baroque', 
                                'artist' => 'Rembrandt Lighting', 
                                'image'  => 'https://images.unsplash.com/photo-1578301978018-3005759f48f7?w=400'
                            ],
                            [
                                'rank'   => 9, 
                                'title'  => 'Fauvism', 
                                'artist' => 'Henri Matisse Style', 
                                'image'  => 'https://images.unsplash.com/photo-1659188299275-6782cd1654a1?q=80&w=1031&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'
                            ],
                            [
                                'rank'   => 10, 
                                'title'  => 'Contemporary', 
                                'artist' => 'Modern Gallery', 
                                'image'  => 'https://images.unsplash.com/photo-1582201942988-13e60e4556ee?w=400'
                            ],
                        ];
                    @endphp

                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                        {{-- SEKSI KIRI --}}
                        <div class="lg:col-span-3 space-y-10">
                            <div class="relative flex flex-col md:flex-row items-start md:items-end justify-between border-b border-neutral-800 pb-6 pt-4 -mx-4 px-4 transition-all duration-300 z-40">
                                
                                {{-- Grup Teks Kiri --}}
                                <div class="mb-4 md:mb-0">
                                    <span class="text-[#C9A74E] text-xs font-bold uppercase tracking-[0.3em] mb-2 block">Curated Selection</span>
                                    <h2 class="text-3xl md:text-4xl font-light text-white tracking-tighter">
                                        Most Liked <span class="font-serif italic text-[#C9A74E]">Artworks</span>
                                    </h2>
                                </div>

                                {{-- Grup Kontrol Kanan --}}
                                <div class="flex items-center gap-3">
                                    
                                    <div class="relative dropdown-container">
                                        <button id="btnSort" class="flex items-center gap-3 bg-[#1a1a1a] text-gray-300 px-4 py-2.5 md:px-5 md:py-3 rounded-xl border border-neutral-800 hover:border-[#C9A74E] transition-all focus:outline-none group">
                                            <svg class="w-4 h-4 text-[#C9A74E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                                            </svg>
                                            <span class="text-sm font-medium tracking-wide">Sort</span>
                                            <svg class="w-4 h-4 text-neutral-600 group-hover:text-[#C9A74E] transition-transform duration-300 chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                        <div id="menuSort" class="hidden absolute right-0 mt-2 w-48 bg-[#1a1a1a] border border-neutral-800 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] p-2 z-50 transition-all opacity-0 scale-95 origin-top-right">
                                            <div class="px-3 py-2 text-[10px] text-[#C9A74E] font-bold uppercase tracking-widest border-b border-neutral-800 mb-1">Order by</div>
                                            <button class="w-full text-left px-4 py-2.5 text-sm text-gray-300 hover:bg-[#C9A74E] hover:text-black rounded-lg transition-all">Most Popular</button>
                                            <button class="w-full text-left px-4 py-2.5 text-sm text-gray-300 hover:bg-[#C9A74E] hover:text-black rounded-lg transition-all">Recently Added</button>
                                        </div>
                                    </div>

                                    <div class="relative dropdown-container">
                                        <button id="btnFilter" class="flex items-center gap-3 bg-[#1a1a1a] text-gray-300 px-4 py-2.5 md:px-5 md:py-3 rounded-xl border border-neutral-800 hover:border-[#C9A74E] transition-all focus:outline-none group">
                                            <svg class="w-4 h-4 text-[#C9A74E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                            </svg>
                                            <span class="text-sm font-medium tracking-wide">Filter</span>
                                            <svg class="w-4 h-4 text-neutral-600 group-hover:text-[#C9A74E] transition-transform duration-300 chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                        <div id="menuFilter" class="hidden absolute right-0 mt-2 w-56 bg-[#1a1a1a] border border-neutral-800 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] p-2 z-50 transition-all opacity-0 scale-95 origin-top-right">
                                            <div class="px-3 py-2 text-[10px] text-[#C9A74E] font-bold uppercase tracking-widest border-b border-neutral-800 mb-1">Category</div>
                                            <button class="w-full text-left px-4 py-2.5 text-sm text-gray-300 hover:bg-[#C9A74E] hover:text-black rounded-lg transition-all">Renaissance</button>
                                            <button class="w-full text-left px-4 py-2.5 text-sm text-gray-300 hover:bg-[#C9A74E] hover:text-black rounded-lg transition-all">Modernism</button>
                                        </div>
                                    </div>

                                    <div class="relative dropdown-container">
                                        <button id="btnDate" class="flex items-center gap-3 bg-[#1a1a1a] text-gray-300 px-4 py-2.5 md:px-5 md:py-3 rounded-xl border border-neutral-800 hover:border-[#C9A74E] transition-all focus:outline-none group">
                                            <svg class="w-4 h-4 text-[#C9A74E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span id="dateText" class="text-sm font-medium tracking-wide">Date</span>
                                            <svg class="w-4 h-4 text-neutral-600 transition-transform duration-300 chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                @foreach($MostLikedArtworks as $art)
                                <div onclick='openArtModal(@json($art))' 
                                    class="group cursor-pointer relative bg-[#141414] border border-neutral-900 rounded-[2.5rem] overflow-hidden transition-all duration-700 hover:border-[#C9A74E]/30 hover:-translate-y-2">
                                    
                                    <div class="relative aspect-[4/5] overflow-hidden m-3 rounded-[2rem]">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10 flex flex-col justify-end p-6">
                                            <span class="text-white/60 text-[10px] uppercase tracking-widest mb-1 translate-y-4 group-hover:translate-y-0 transition-transform duration-500">View Masterpiece</span>
                                            <div class="h-[1px] w-8 bg-[#C9A74E] translate-y-4 group-hover:translate-y-0 transition-transform duration-700 delay-75"></div>
                                        </div>

                                        <img src="{{ $art['image'] }}" 
                                            class="w-full h-full object-cover grayscale-[0.3] group-hover:grayscale-0 transition-all duration-[1.5s] ease-out group-hover:scale-110">
                                        
                                        <div class="absolute top-4 right-4 z-20">
                                            <div class="bg-black/60 backdrop-blur-md border border-[#C9A74E]/30 px-4 py-1.5 rounded-full shadow-xl transition-all duration-500 group-hover:border-[#C9A74E]">
                                                <span class="text-[#C9A74E] text-[11px] font-black tracking-tighter italic">
                                                    TOP {{ $loop->iteration }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="px-6 py-5 relative">
                                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-[2px] h-0 bg-[#C9A74E] group-hover:h-1/2 transition-all duration-500"></div>
                                        
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="text-gray-100 font-medium text-lg leading-tight group-hover:text-[#C9A74E] transition-colors duration-300">
                                                    {{ $art['title'] }}
                                                </h3>
                                                <p class="text-neutral-500 text-xs mt-2 font-light tracking-wide uppercase italic">
                                                    {{ $art['artist'] }}
                                                </p>
                                            </div>
                                            
                                            <div class="flex flex-col items-end">
                                                <span class="text-[#C9A74E] font-serif text-3xl leading-none h-4 opacity-20 group-hover:opacity-100 transition-opacity italic">”</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-[#C9A74E]/5 blur-[50px] rounded-full group-hover:bg-[#C9A74E]/10 transition-colors"></div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- SEKSI KANAN --}}
                        <div class="lg:col-span-1 sticky top-8 bg-[#1a1a1a] border border-neutral-800 rounded-[2.5rem] p-8 h-[600px] flex flex-col relative overflow-hidden">
                            
                            <h2 class="text-xl font-bold text-gray-300 mb-6 flex-shrink-0">Most Viewed Styles</h2>
                            
                            <div class="relative flex-1 overflow-hidden">
                                
                                <div class="absolute top-0 left-0 right-0 h-12 bg-gradient-to-b from-[#1a1a1a] to-transparent z-20 pointer-events-none"></div>

                                <div class="h-full overflow-y-auto no-scrollbar space-y-4 pb-12 pt-4">
                                    @foreach($MostViewedStyles as $style)
                                    <div onclick='openArtModal(@json($style))' 
                                        class="relative group cursor-pointer overflow-hidden rounded-2xl bg-black border border-neutral-800 p-4 flex items-center gap-4 transition-all hover:border-[#C9A74E]/40">
                                        
                                        <div class="relative z-10 w-8 h-8 bg-[#C9A74E] rounded-lg flex items-center justify-center text-black font-black text-xs shadow-lg">
                                            {{ $style['rank'] }}
                                        </div>
                                        
                                        <div class="relative z-10">
                                            <h3 class="text-gray-200 font-bold text-sm group-hover:text-white transition-colors">{{ $style['title'] }}</h3>
                                            <p class="text-gray-500 text-[10px] italic">{{ $style['artist'] }}</p>
                                        </div>

                                        <div class="absolute inset-0 z-0 opacity-10 group-hover:opacity-30 transition-all duration-700">
                                            <img src="{{ $style['image'] }}" class="w-full h-full object-cover grayscale">
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-[#1a1a1a] via-[#1a1a1a]/80 to-transparent z-20 pointer-events-none"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            {{-- MODAL DETAIL (Hanya satu ID artModal) --}}
            <div id="artModal" class="fixed inset-0 z-[998] hidden items-center justify-center p-4 bg-black/95 backdrop-blur-xl opacity-0 transition-opacity duration-300">
                <div class="relative bg-[#1a1a1a] border border-neutral-800 rounded-[3rem] overflow-hidden max-w-4xl w-full shadow-2xl scale-95 transition-transform duration-300" id="modalContent">
                    <button onclick="closeArtModal()" class="absolute top-6 right-6 z-50 p-3 bg-black/50 text-white rounded-full hover:bg-red-500 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>

                    <div class="flex flex-col md:row h-full md:flex-row">
                        <div class="w-full md:w-1/2 h-[350px] md:h-[550px] relative group cursor-zoom-in overflow-hidden" onclick="openFullPreview()">
                            <div class="absolute inset-0 z-10 flex items-center justify-center bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="bg-white/20 backdrop-blur-md border border-white/30 p-4 rounded-full animate-pulse">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                    </svg>
                                </div>
                                <span class="absolute bottom-6 text-white text-[10px] tracking-[0.3em] uppercase font-bold drop-shadow-md">
                                    Click to expand
                                </span>
                            </div>

                            <img id="modalImage" src="" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        </div>

                        <div class="w-full md:w-1/2 p-10 md:p-14 flex flex-col justify-center bg-[#1a1a1a] relative">
                            <div class="absolute top-10 right-10 text-8xl font-serif text-[#C9A74E]/5 select-none uppercase italic font-black">Art</div>
                            
                            <h2 id="modalTitle" class="text-4xl font-black text-gray-200 mb-6 tracking-tighter"></h2>

                            <div class="space-y-8">
                                <div class="border-l-2 border-[#C9A74E]/30 pl-6">
                                    <span class="text-gray-500 text-[10px] uppercase tracking-widest block">Creator</span>
                                    <p id="modalArtist" class="text-gray-300 text-xl font-medium italic"></p>
                                </div>

                                <div>
                                    <span class="text-gray-500 text-[10px] uppercase tracking-widest block mb-2">Curator Notes</span>
                                    <p id="modalDesc" class="text-gray-400 text-sm leading-relaxed font-light italic"></p>
                                </div>

                                <div id="quoteBox" class="bg-black/40 p-6 rounded-2xl border border-neutral-800 relative hidden">
                                    <span class="absolute -top-4 -left-2 text-4xl text-[#C9A74E] font-serif">“</span>
                                    <p id="modalQuote" class="text-[#C9A74E] text-sm italic font-medium"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FULL IMAGE PREVIEW --}}
            <div id="fullPreview" onclick="closeFullPreview()" class="fixed inset-0 z-[999] hidden flex-col items-center justify-center bg-black/98 backdrop-blur-2xl opacity-0 transition-opacity duration-300 cursor-zoom-out p-4">
                <img id="previewImg" src="" class="max-w-full max-h-[85vh] object-contain shadow-2xl scale-95 transition-transform duration-500">
                <p class="mt-6 text-white/40 text-[10px] uppercase tracking-[0.4em]">- Click anywhere to close -</p>
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
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. Inisialisasi Flatpickr untuk Date
            const fp = flatpickr("#btnDate", {
                disableMobile: true, // Tanpa tanda petik agar terbaca sebagai boolean
                dateFormat: "d M Y",
                // BUG FIX: Tambahkan ini agar dropdown bulan/tahun tidak tertutup saat diklik
                ignoredFocusElements: [document.getElementById('btnDate')],
                
                onOpen: function(selectedDates, dateStr, instance) {
                    closeCustomMenus();
                    instance.element.querySelector('.chevron').classList.add('rotate-180', 'text-[#C9A74E]');
                    
                    // BUG FIX: Beri tanda pada kalender agar tidak dianggap "luar area" oleh event click global
                    const calendar = instance.calendarContainer;
                    calendar.classList.add('dropdown-container');
                },
                onClose: function(selectedDates, dateStr, instance) {
                    instance.element.querySelector('.chevron').classList.remove('rotate-180', 'text-[#C9A74E]');
                },
                onChange: function(selectedDates, dateStr) {
                    document.getElementById('dateText').innerText = dateStr;
                }
            });

            // Perbarui juga bagian Event Listener Click Global Anda:
            document.addEventListener('click', function(e) {
                // BUG FIX: Periksa apakah klik berasal dari kalender Flatpickr
                const isFlatpickrClick = e.target.closest('.flatpickr-calendar');
                
                if (!e.target.closest('.dropdown-container') && !isFlatpickrClick) {
                    closeCustomMenus();
                    fp.close();
                }
            });

            // 2. Definisi Menu Custom (Sort & Filter)
            const customMenus = [
                { btnId: 'btnSort', menuId: 'menuSort' },
                { btnId: 'btnFilter', menuId: 'menuFilter' }
            ];

            function closeCustomMenus() {
                customMenus.forEach(item => {
                    const menu = document.getElementById(item.menuId);
                    const btn = document.getElementById(item.btnId);
                    if(menu) {
                        menu.classList.add('hidden');
                        menu.classList.add('opacity-0', 'scale-95');
                    }
                    if(btn) {
                        const chevron = btn.querySelector('.chevron');
                        if(chevron) chevron.classList.remove('rotate-180', 'text-[#C9A74E]');
                    }
                });
            }

            // 3. Logic Toggle untuk Sort dan Filter
            customMenus.forEach(item => {
                const btn = document.getElementById(item.btnId);
                const menu = document.getElementById(item.menuId);

                if(btn && menu) {
                    btn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const isHidden = menu.classList.contains('hidden');
                        
                        // Tutup semuanya dulu agar tidak tumpang tindih
                        fp.close();
                        closeCustomMenus();

                        if (isHidden) {
                            menu.classList.remove('hidden');
                            // Delay tipis agar animasi opacity jalan
                            setTimeout(() => {
                                menu.classList.remove('opacity-0', 'scale-95');
                            }, 10);
                            
                            const chevron = btn.querySelector('.chevron');
                            if(chevron) chevron.classList.add('rotate-180', 'text-[#C9A74E]');
                        }
                    });
                }
            });

            // 4. Klik di luar untuk menutup semua menu
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.dropdown-container')) {
                    closeCustomMenus();
                    fp.close();
                }
            });
        });

        // MODAL ARTWORK DETAIL
        function openArtModal(art) {
            const modal = document.getElementById('artModal');
            const content = document.getElementById('modalContent');
            
            // Fill Data
            document.getElementById('modalImage').src = art.image;
            document.getElementById('modalTitle').innerText = art.title;
            document.getElementById('modalArtist').innerText = art.artist;
            document.getElementById('modalDesc').innerText = art.desc || "No additional description available.";
            
            // Handle Quote
            const quoteBox = document.getElementById('quoteBox');
            if(art.quote) {
                document.getElementById('modalQuote').innerText = art.quote;
                quoteBox.classList.remove('hidden');
            } else {
                quoteBox.classList.add('hidden');
            }

            // Show Modal with Animation
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.classList.add('opacity-100');
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            }, 10);
        }

        function closeArtModal() {
            const modal = document.getElementById('artModal');
            modal.classList.remove('opacity-100');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }

        function openFullPreview() {
            const preview = document.getElementById('fullPreview');
            const img = document.getElementById('previewImg');
            img.src = document.getElementById('modalImage').src;
            
            preview.classList.remove('hidden');
            preview.classList.add('flex');
            setTimeout(() => {
                preview.classList.add('opacity-100');
                img.classList.add('scale-100');
            }, 10);
        }

        function closeFullPreview() {
            const preview = document.getElementById('fullPreview');
            preview.classList.remove('opacity-100');
            setTimeout(() => preview.classList.add('hidden'), 300);
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