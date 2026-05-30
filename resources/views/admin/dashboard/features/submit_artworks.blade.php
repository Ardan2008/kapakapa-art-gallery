<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kapakapa Art Gallery | Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        .active-gold {
            background-color: #C9A74E !important;
            color: black !important;
            border-color: #C9A74E !important;
        }

        /* Animasi untuk menghaluskan filter */
        .collection-item.hidden-item {
            display: none;
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

                {{-- Data fetched from ArtWorkController --}}

                <div class="min-h-screen py-20 px-6 font-sans antialiased text-gray-200">
                    <div class="max-w-7xl mx-auto space-y-16">
                        
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 border-b border-white/5 pb-10">
                            <div id="art-title-section" class="space-y-1">

                                <div class="flex items-center gap-3 mb-2">
                                    <span class="h-[1px] w-8 bg-[#C9A74E]"></span>
                                    <p class="text-[10px] text-[#C9A74E] tracking-[0.4em] uppercase font-bold">Premium Collection</p>
                                </div>
                                <h2 class="text-5xl md:text-6xl font-extralight tracking-tighter text-gray-300 leading-none">
                                    THE <span class="font-black text-[#C9A74E] italic">ART</span> PRODUCT
                                </h2>
                            </div>
                            
                            <a id="add-collection-btn" href="{{ route('add_product') }}" class="group relative flex items-center justify-center gap-3 px-8 py-4 bg-[#C9A74E] hover:bg-[#DBBC6A] text-black rounded-full transition-all duration-500 active:scale-95 shadow-[0_0_20px_rgba(201,167,78,0.2)]">
                                <svg id="plus-icon" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                </svg>
                                <span id="btn-text" class="text-xs font-black tracking-[0.1em] uppercase">Add New Collection</span>
                            </a>
                        </div>

                        <div id="art-display-section" class="space-y-10">

                            <div class="flex flex-col items-end gap-4 mb-12">
                                <div class="relative inline-block text-left w-64">
                                    <button id="dropdownBtn" onclick="toggleDropdown()" class="w-full flex items-center justify-between px-6 py-3 bg-neutral-900 border border-white/10 rounded-xl text-gray-300 text-xs font-bold uppercase tracking-widest hover:border-[#C9A74E] transition-all duration-300">
                                        <span id="selectedCategoryLabel">All Collections</span>
                                        <svg id="dropdownArrow" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>

                                    <div id="dropdownMenu" class="hidden absolute z-50 mt-2 w-full bg-neutral-900 border border-white/10 rounded-xl overflow-hidden shadow-2xl backdrop-blur-xl">
                                        <div class="py-1">
                                            <button onclick="selectCategory('all', 'All Collections')" class="w-full text-left px-6 py-3 text-[10px] text-gray-400 hover:bg-[#C9A74E] hover:text-black transition-colors font-bold uppercase">All Collections</button>
                                            @foreach($categories as $cat)
                                                <button onclick="selectCategory('{{ $cat }}', '{{ $cat }}')" class="w-full text-left px-6 py-3 text-[10px] text-gray-400 hover:bg-[#C9A74E] hover:text-black transition-colors font-bold uppercase border-t border-white/5">
                                                    {{ $cat }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10" id="collection-grid">
                                @foreach($collections as $item)
                                <div class="collection-item group relative transition-all duration-500" data-category="{{ $item['category'] }}" id="collection-card-{{ $item['id'] }}">

                                    <div class="absolute -inset-1 bg-gradient-to-b from-[#C9A74E]/20 to-transparent rounded-[2rem] blur opacity-0 group-hover:opacity-100 transition duration-500"></div>
                                    
                                    <div class="relative bg-neutral-900/80 backdrop-blur-xl rounded-[2rem] p-5 border border-white/5 transition-all duration-500 group-hover:-translate-y-2 group-hover:border-[#C9A74E]/30">
                                        <span class="absolute top-6 right-8 text-5xl font-black text-white/5 italic select-none">{{ $item['id'] }}</span>
                                        
                                        <div class="grid grid-cols-3 gap-3 h-72 mb-8">
                                            <div class="col-span-2 overflow-hidden rounded-2xl shadow-2xl">
                                                <img src="{{ $item['images'][0] }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-1000 group-hover:scale-105">
                                            </div>
                                            <div class="flex flex-col gap-3">
                                                <div class="h-1/2 overflow-hidden rounded-2xl">
                                                    <img src="{{ $item['images'][1] }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                                                </div>
                                                <div class="h-1/2 overflow-hidden rounded-2xl border border-[#C9A74E]/20">
                                                    <img src="{{ $item['images'][2] }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex justify-between items-center px-2">
                                            <div>
                                                <h3 class="text-xl font-bold text-gray-300 tracking-tight uppercase">{{ $item['title'] }}</h3>
                                                <p class="text-[10px] text-[#C9A74E] font-bold tracking-[0.3em] uppercase mt-1 opacity-80">{{ $item['artist'] }}</p>
                        @if(!empty($item['dimensions']))
                            <p class="text-[8px] text-gray-500 font-medium tracking-[0.2em] uppercase mt-2">
                                <span class="text-gray-400">Dim:</span> {{ $item['dimensions'] }}
                            </p>
                        @endif
                                            </div>
                                            <div class="flex gap-2">
                                                <button class="btn-add-art p-3 rounded-full bg-white/5 text-[#C9A74E] hover:bg-[#C9A74E] hover:text-black transition-all duration-300" 
                                                    title="Add New Art"
                                                    data-artist-name="{{ $item['full_artist_name'] }}"
                                                    data-artist-birthplace="{{ $item['birthplace'] }}"
                                                    data-artist-career="{{ $item['career'] }}"
                                                    data-artist-desc="{{ $item['artist_desc'] }}">
                                                    <i data-lucide="plus-circle" class="w-4 h-4"></i>
                                                </button>
                                                <button onclick="handleEdit('{{ $item['id'] }}')" class="p-3 rounded-full bg-white/5 text-gray-400 hover:bg-[#C9A74E] hover:text-black transition-all duration-300">
                                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                                </button>
                                                <button onclick="confirmDelete('{{ $item['id'] }}', '{{ $item['title'] }}')" class="p-3 rounded-full bg-white/5 text-red-400/40 hover:bg-red-500/20 hover:text-red-500 transition-all duration-300">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
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

    {{-- MULTI-FORM WIZARD MODAL --}}
    <div id="multiFormModal" class="fixed inset-0 z-[10000] hidden items-center justify-center p-4 bg-black/90 backdrop-blur-xl">
        <div class="relative w-full max-w-4xl bg-[#1a1a1a] border border-white/5 rounded-[3rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            {{-- Header --}}
            <div class="p-8 border-b border-white/5 flex justify-between items-center bg-neutral-900/50">
                <div class="flex-1">
                    <h2 class="text-2xl font-black text-white tracking-tight flex items-center gap-3">
                        <span class="w-1.5 h-8 bg-[#C9A74E]"></span> 
                        BATCH ART UPLOAD
                    </h2>
                    <div class="flex items-center gap-4 mt-2">
                        <p id="multiFormSubtitle" class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em]">Artist: Leonardo Da Vinci</p>
                        <span class="h-1 w-4 bg-white/10"></span>
                        <div class="flex items-center gap-2">
                            <label class="text-[10px] text-[#C9A74E] font-black uppercase tracking-widest">Qty:</label>
                            <input type="number" id="art-count-input" min="1" max="10" value="1" class="w-16 bg-neutral-800 border border-white/10 text-white rounded-lg px-2 py-1 text-xs font-bold focus:border-[#C9A74E] outline-none">
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <div class="text-right">
                        <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest mb-1">Progress</p>
                        <p id="slideCounter" class="text-xs font-black text-[#C9A74E]">Step 1 of 4</p>
                    </div>
                    <button onclick="closeMultiForm()" class="p-3 hover:bg-white/5 rounded-full text-gray-500 hover:text-white transition-all">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>

            {{-- Progress Bar --}}
            <div class="h-1 w-full bg-white/5">
                <div id="multiProgressBar" class="h-full bg-[#C9A74E] transition-all duration-500" style="width: 25%"></div>
            </div>

            {{-- Content --}}
            <div id="dynamic-art-container" class="flex-1 overflow-y-auto p-8 md:p-12 custom-scrollbar bg-gradient-to-b from-neutral-900/20 to-transparent">
                {{-- Slides will be injected here via JS loop --}}
            </div>

            {{-- Footer --}}
            <div class="p-8 border-t border-white/5 bg-neutral-900/50 flex justify-between items-center">
                <button id="prevBtn" onclick="navigateSlide(-1)" class="px-8 py-4 bg-transparent border border-white/10 text-gray-500 hover:text-white rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all disabled:opacity-0">
                    Previous
                </button>
                <div class="flex gap-4">
                    <button id="nextBtn" onclick="navigateSlide(1)" class="px-10 py-4 bg-[#C9A74E] text-black rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-lg shadow-[#C9A74E]/20">
                        Next Step
                    </button>
                    <button id="multiSubmitBtn" onclick="submitMultiForm()" class="hidden px-10 py-4 bg-white text-black rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-lg shadow-white/20">
                        Publish All Artworks
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const categories = @json($categories);
        let currentCollectionSlide = 0;
        let totalCollectionSlides = 1;


        // Dropdown Toggle
        function toggleDropdown() {
            const menu = document.getElementById('dropdownMenu');
            const arrow = document.getElementById('dropdownArrow');
            menu.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        }

        // Category Selection & Filtering
        function selectCategory(category, label) {
            document.getElementById('selectedCategoryLabel').innerText = label;
            toggleDropdown();

            const items = document.querySelectorAll('.collection-item');
            items.forEach(item => {
                const itemCat = item.getAttribute('data-category');
                
                // Animasi Fade Out
                item.style.opacity = '0';
                item.style.transform = 'translateY(10px)';

                setTimeout(() => {
                    if (category === 'all' || itemCat === category) {
                        item.style.display = 'block';
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'translateY(0)';
                        }, 50);
                    } else {
                        item.style.display = 'none';
                    }
                }, 300);
            });
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.closest('#dropdownBtn')) {
                document.getElementById('dropdownMenu').classList.add('hidden');
                document.getElementById('dropdownArrow').classList.remove('rotate-180');
            }
        }

        function toggleAddForm() {
            const formSection = document.getElementById('add-artwork-form-section');
            const btn = document.getElementById('add-collection-btn');
            const btnText = document.getElementById('btn-text');
            const icon = document.getElementById('plus-icon');
            
            const isHidden = formSection.classList.contains('hidden');
            
            if (isHidden) {
                // Reset IDs if it was previously editing
                document.getElementById('artist_id').value = '';
                document.getElementById('artForm').reset();
                document.getElementById('profileDisplay').src = 'https://api.dicebear.com/8.x/notionists/svg?seed=artist';

                formSection.classList.remove('hidden');

                btnText.innerText = 'Close Form';
                icon.style.transform = 'rotate(45deg)';
                
                // Initialize slides
                updateCollectionSlides(document.getElementById('collection-qty').value);
                
                setTimeout(() => {
                    formSection.classList.remove('opacity-0', 'translate-y-10');
                }, 10);
            } else {
                formSection.classList.add('opacity-0', 'translate-y-10');
                btnText.innerText = 'Add New Collection';
                icon.style.transform = 'rotate(0deg)';
                document.getElementById('add-collection-btn').classList.remove('hidden');
                setTimeout(() => {
                    formSection.classList.add('hidden');
                }, 700);
            }
        }

        function updateCollectionSlides(qty) {
            qty = parseInt(qty) || 1;
            totalCollectionSlides = qty;
            currentCollectionSlide = 0;
            
            const container = document.getElementById('collection-slides-container');
            container.innerHTML = '';
            
            for (let i = 0; i < qty; i++) {
                container.innerHTML += `
                    <div class="collection-slide space-y-8 ${i === 0 ? '' : 'hidden'}" data-index="${i}">
                        <input type="hidden" name="artwork[${i}][id]" id="artwork_id_${i}">
                        <div class="grid grid-cols-2 gap-8">

                            <div class="space-y-6">
                                <h4 class="text-[10px] text-gray-500 font-black uppercase tracking-[0.2em]">Identification</h4>
                                <div>
                                    <label class="block text-[8px] text-gray-600 font-black uppercase tracking-widest mb-2">Art Name</label>
                                    <input type="text" name="artwork[${i}][name]" placeholder="Masterpiece #${i+1}" class="w-full bg-neutral-800/50 border border-white/5 text-white rounded-xl px-4 py-3 focus:border-[#C9A74E]/50 outline-none transition-all text-xs font-bold" required>
                                </div>
                                <div>
                                    <label class="block text-[8px] text-gray-600 font-black uppercase tracking-widest mb-2">Description</label>
                                    <textarea name="artwork[${i}][desc]" placeholder="Story..." class="w-full bg-neutral-800/50 border border-white/5 text-white rounded-xl px-4 py-3 focus:border-[#C9A74E]/50 outline-none transition-all text-xs font-bold h-20" required></textarea>
                                </div>
                            </div>
                            <div class="space-y-6">
                                <h4 class="text-[10px] text-gray-500 font-black uppercase tracking-[0.2em]">Product Details</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[8px] text-gray-600 font-black uppercase tracking-widest mb-2">Ref</label>
                                        <input type="text" name="artwork[${i}][painterRef]" placeholder="Signature" class="w-full bg-neutral-800/50 border border-white/5 text-white rounded-xl px-4 py-3 focus:border-[#C9A74E]/50 outline-none transition-all text-xs font-bold" required>
                                    </div>
                                    <div>
                                        <label class="block text-[8px] text-gray-600 font-black uppercase tracking-widest mb-2">Style</label>
                                        <select name="artwork[${i}][style]" class="w-full bg-neutral-800/50 border border-white/5 text-white rounded-xl px-4 py-3 focus:border-[#C9A74E]/50 outline-none transition-all text-xs font-bold appearance-none" required>
                                            <option value="" disabled selected>Style</option>
                                            ${categories.map(cat => `<option value="${cat}">${cat}</option>`).join('')}
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[8px] text-gray-600 font-black uppercase tracking-widest mb-2">Stock</label>
                                        <input type="number" name="artwork[${i}][stock]" value="1" class="w-full bg-neutral-800/50 border border-white/5 text-white rounded-xl px-4 py-3 focus:border-[#C9A74E]/50 outline-none transition-all text-xs font-bold text-center" required>
                                    </div>
                                    <div>
                                        <label class="block text-[8px] text-gray-600 font-black uppercase tracking-widest mb-2">Limit</label>
                                        <input type="number" name="artwork[${i}][maxLimit]" value="1" class="w-full bg-neutral-800/50 border border-white/5 text-white rounded-xl px-4 py-3 focus:border-[#C9A74E]/50 outline-none transition-all text-xs font-bold text-center" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-8 pt-6 border-t border-white/5">
                            <div class="space-y-6">
                                <h4 class="text-[10px] text-gray-500 font-black uppercase tracking-[0.2em]">Art Pricing</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#C9A74E] font-black text-xs">$</span>
                                        <input type="number" name="artwork[${i}][basePrice]" placeholder="Base" class="w-full bg-neutral-800/50 border border-white/5 text-white rounded-xl pl-8 pr-4 py-3 focus:border-[#C9A74E]/50 outline-none transition-all text-xs font-bold" required>
                                    </div>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#C9A74E] font-black text-xs">$</span>
                                        <input type="number" name="artwork[${i}][salePrice]" placeholder="Sale" class="w-full bg-neutral-800/50 border border-white/5 text-white rounded-xl pl-8 pr-4 py-3 focus:border-[#C9A74E]/50 outline-none transition-all text-xs font-bold">
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-6">
                                <h4 class="text-[10px] text-gray-500 font-black uppercase tracking-[0.2em]">Media & Certificate</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[8px] text-gray-600 font-black uppercase tracking-widest mb-2">Photos (Max 3)</label>
                                        <input type="file" name="artwork[${i}][media][]" id="mediaInput_${i}" accept="image/*" multiple class="hidden" onchange="handleCollectionMedia(this, ${i})">
                                        <div onclick="document.getElementById('mediaInput_${i}').click()" class="w-full h-12 border-2 border-dashed border-neutral-700 rounded-xl flex items-center justify-center gap-2 cursor-pointer hover:border-[#C9A74E] transition-all group">
                                            <i data-lucide="plus" class="w-3 h-3 text-neutral-500 group-hover:text-[#C9A74E]"></i>
                                            <span id="mediaLabel_${i}" class="text-[8px] font-black text-neutral-600 group-hover:text-[#C9A74E] uppercase">Upload</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[8px] text-red-500 font-black uppercase tracking-widest mb-2">Certificate (Mandatory)</label>
                                        <input type="file" name="artwork[${i}][certificate]" id="certInput_${i}" accept="image/*,.pdf" class="hidden" required onchange="handleCollectionCert(this, ${i})">
                                        <div onclick="document.getElementById('certInput_${i}').click()" class="w-full h-12 border-2 border-dashed border-red-500/30 rounded-xl flex items-center justify-center gap-2 cursor-pointer hover:border-red-500 transition-all group">
                                            <i data-lucide="shield-check" class="w-3 h-3 text-red-500/50 group-hover:text-red-500"></i>
                                            <span id="certLabel_${i}" class="text-[8px] font-black text-red-500/50 group-hover:text-red-500 uppercase">Mandatory</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            updateCollectionNav();
            lucide.createIcons();
        }

        function navigateCollectionSlide(dir) {
            const next = currentCollectionSlide + dir;
            if (next >= 0 && next < totalCollectionSlides) {
                currentCollectionSlide = next;
                
                const slides = document.querySelectorAll('.collection-slide');
                slides.forEach((s, i) => {
                    s.classList.toggle('hidden', i !== currentCollectionSlide);
                });
                
                updateCollectionNav();
            }
        }

        function updateCollectionNav() {
            document.getElementById('collection-slide-counter').innerText = `Artwork ${currentCollectionSlide + 1} of ${totalCollectionSlides}`;
            document.getElementById('collPrevBtn').disabled = currentCollectionSlide === 0;
            document.getElementById('collNextBtn').disabled = currentCollectionSlide === totalCollectionSlides - 1;
        }

        function handleCollectionMedia(input, index) {
            const files = input.files;
            if (files.length > 3) {
                Swal.fire({ icon: 'error', title: 'Limit Exceeded', text: 'Max 3 photos allowed.', background: '#1a1a1a', color: '#fff' });
                input.value = '';
                return;
            }
            document.getElementById(`mediaLabel_${index}`).innerText = `${files.length} Photos Selected`;
            document.getElementById(`mediaLabel_${index}`).classList.add('text-[#C9A74E]');
        }

        function handleCollectionCert(input, index) {
            if (input.files && input.files[0]) {
                document.getElementById(`certLabel_${index}`).innerText = 'Certificate Added';
                document.getElementById(`certLabel_${index}`).classList.remove('text-red-500/50');
                document.getElementById(`certLabel_${index}`).classList.add('text-green-500');
            }
        }


        function previewProfile(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profileDisplay').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function handleMediaUpload(input) {
            const container = document.getElementById('mediaContainer');
            if (input.files) {
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = "w-24 h-24 bg-neutral-800 rounded-2xl overflow-hidden relative group border border-white/5";
                        div.innerHTML = `
                            <img src="${e.target.result}" class="object-cover w-full h-full opacity-60">
                            <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-[8px] font-black bg-red-500/80 px-2 py-1 rounded text-white uppercase tracking-widest">Delete</button>
                            </div>
                        `;
                        container.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }

        async function handleFormSubmit(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            Swal.fire({
                title: 'Publishing Collection...',
                html: 'Processing batch artwork submission...',
                background: '#1a1a1a',
                color: '#fff',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            try {
                const response = await fetch('/api/artworks/store-collection', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const result = await response.json();

                if (result.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: result.message,
                        background: '#1a1a1a',
                        color: '#fff',
                        iconColor: '#C9A74E',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Dynamic Update/Append
                    if (result.artwork) {
                        const existingCard = document.getElementById(`collection-card-${result.artwork.id}`);
                        if (existingCard) {
                            existingCard.remove();
                        }
                        appendNewArtworkCard(result.artwork);
                    }
                    
                    // Reset and Close
                    form.reset();
                    document.getElementById('artist_id').value = '';
                    document.getElementById('profileDisplay').src = 'https://api.dicebear.com/8.x/notionists/svg?seed=artist';
                    toggleAddForm();
                    lucide.createIcons();
                } else {
                    throw new Error(result.message || 'Validation failed');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Submission Failed',
                    text: error.message,
                    background: '#1a1a1a',
                    color: '#fff'
                });
            }
        }

        function appendNewArtworkCard(art) {
            // Show the sections if they were hidden
            document.getElementById('art-title-section').classList.remove('hidden');
            document.getElementById('art-display-section').classList.remove('hidden');

            const grid = document.getElementById('collection-grid');
            const card = document.createElement('div');
            card.id = `collection-card-${art.id}`;
            card.className = "collection-item group relative transition-all duration-500 opacity-0 translate-y-10";
            card.setAttribute('data-category', art.category);

            
            card.innerHTML = `
                <div class="absolute -inset-1 bg-gradient-to-b from-[#C9A74E]/20 to-transparent rounded-[2rem] blur opacity-0 group-hover:opacity-100 transition duration-500"></div>
                
                <div class="relative bg-neutral-900/80 backdrop-blur-xl rounded-[2rem] p-5 border border-white/5 transition-all duration-500 group-hover:-translate-y-2 group-hover:border-[#C9A74E]/30">
                    <span class="absolute top-6 right-8 text-5xl font-black text-white/5 italic select-none">${art.id}</span>
                    
                    <div class="grid grid-cols-3 gap-3 h-72 mb-8">
                        <div class="col-span-2 overflow-hidden rounded-2xl shadow-2xl">
                            <img src="${art.images[0]}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-1000 group-hover:scale-105">
                        </div>
                        <div class="flex flex-col gap-3">
                            <div class="h-1/2 overflow-hidden rounded-2xl">
                                <img src="${art.images[1]}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                            </div>
                            <div class="h-1/2 overflow-hidden rounded-2xl border border-[#C9A74E]/20">
                                <img src="${art.images[2]}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center px-2">
                        <div>
                            <h3 class="text-xl font-bold text-gray-300 tracking-tight uppercase">${art.title}</h3>
                            <p class="text-[10px] text-[#C9A74E] font-bold tracking-[0.3em] uppercase mt-1 opacity-80">${art.artist}</p>
                        </div>
                        <div class="flex gap-2">
                            <button class="btn-add-art p-3 rounded-full bg-white/5 text-[#C9A74E] hover:bg-[#C9A74E] hover:text-black transition-all duration-300" 
                                title="Add New Art"
                                data-artist-name="${art.full_artist_name}"
                                data-artist-birthplace="${art.birthplace || ''}"
                                data-artist-career="${art.career || ''}"
                                data-artist-desc="${art.artist_desc || ''}">
                                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                            </button>
                            <button onclick="handleEdit('${art.id}')" class="p-3 rounded-full bg-white/5 text-gray-400 hover:bg-[#C9A74E] hover:text-black transition-all duration-300">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </button>
                            <button onclick="confirmDelete('${art.id}', '${art.title}')" class="p-3 rounded-full bg-white/5 text-red-400/40 hover:bg-red-500/20 hover:text-red-500 transition-all duration-300">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            grid.prepend(card);
            
            // Trigger animation
            requestAnimationFrame(() => {
                card.classList.remove('opacity-0', 'translate-y-10');
            });
            lucide.createIcons();
        }

        function handleEdit(id) {
            window.location.href = '/edit_product/' + id;
        }

        function confirmDelete(id, title) {
            Swal.fire({
                title: 'Are you sure?',
                text: `The collection "${title}" will be permanently deleted!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#303030',
                confirmButtonText: 'Yes, Delete!',
                cancelButtonText: 'Cancel',
                background: '#171717',
                color: '#ffffff',
                iconColor: '#ef4444'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Currently Deleting...',
                        allowOutsideClick: false,
                        background: '#171717',
                        color: '#ffffff',
                        didOpen: () => { Swal.showLoading(); }
                    });

                    fetch(`/api/artworks/collection/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: data.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false,
                                background: '#171717',
                                color: '#ffffff',
                                iconColor: '#C9A74E'
                            });
                            
                            const card = document.getElementById(`collection-card-${id}`);
                            if (card) {
                                card.classList.add('opacity-0', 'scale-95');
                                setTimeout(() => card.remove(), 500);
                            }
                        } else {
                            throw new Error(data.message);
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: error.message,
                            background: '#1a1a1a',
                            color: '#fff'
                        });
                    });
                }
            });
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
        // --- EVENT DELEGATION ---
        document.getElementById('collection-grid').addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-add-art');
            if (btn) {
                const artistData = {
                    name: btn.dataset.artistName,
                    birthplace: btn.dataset.artistBirthplace,
                    career: btn.dataset.artistCareer,
                    desc: btn.dataset.artistDesc
                };
                initMultiForm(artistData, 1); // Start with 1
            }
        });

        // Listen for Qty change
        document.getElementById('art-count-input').addEventListener('input', function(e) {
            const count = parseInt(e.target.value) || 1;
            if (selectedArtistData) {
                generateSlides(count);
                currentSlide = 0;
                totalSlides = count * 4;
                updateSlideDisplay();
                lucide.createIcons();
            }
        });

        // --- MULTI-FORM LOGIC ---

        function initMultiForm(artistData, count) {
            selectedArtistData = artistData;
            currentSlide = 0;
            totalSlides = count * 4;

            document.getElementById('art-count-input').value = count;
            document.getElementById('multiFormSubtitle').innerText = `Artist: ${artistData.name}`;
            generateSlides(count);
            
            document.getElementById('multiFormModal').classList.remove('hidden');
            document.getElementById('multiFormModal').classList.add('flex');
            updateSlideDisplay();
            lucide.createIcons();
        }

        function generateSlides(count) {
            const container = document.getElementById('dynamic-art-container');
            container.innerHTML = '';

            for (let i = 0; i < count; i++) {
                const artNum = i + 1;
                
                // Slide 1: Art Identification
                container.innerHTML += `
                    <div class="multi-slide space-y-8 d-none" data-art-index="${i}" data-section="0">
                        <div class="flex items-center gap-4 mb-10">
                            <span class="text-4xl font-black text-[#C9A74E]/20 italic">#${artNum}</span>
                            <h3 class="text-xl text-white font-black tracking-tight uppercase">Art Identification</h3>
                        </div>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-[10px] text-gray-500 font-black uppercase tracking-widest mb-3">Art Name</label>
                                <input type="text" name="artwork[${i}][title]" class="multi-input w-full bg-neutral-800/30 border border-white/5 text-white rounded-2xl px-6 py-4 focus:border-[#C9A74E]/50 outline-none transition-all text-sm font-bold" data-field="title" placeholder="Name of masterpiece ${artNum}">
                            </div>
                            <div>
                                <label class="block text-[10px] text-gray-500 font-black uppercase tracking-widest mb-3">Art Description</label>
                                <textarea name="artwork[${i}][desc]" class="multi-input w-full bg-neutral-800/30 border border-white/5 text-white rounded-[2rem] px-6 py-5 focus:border-[#C9A74E]/50 outline-none transition-all text-sm font-bold h-40 leading-relaxed" data-field="desc" placeholder="Tell the story..."></textarea>
                            </div>
                        </div>
                    </div>
                `;

                // Slide 2: Product Detail
                container.innerHTML += `
                    <div class="multi-slide space-y-8 d-none" data-art-index="${i}" data-section="1">
                        <div class="flex items-center gap-4 mb-10">
                            <span class="text-4xl font-black text-[#C9A74E]/20 italic">#${artNum}</span>
                            <h3 class="text-xl text-white font-black tracking-tight uppercase">Product Detail</h3>
                        </div>
                        <div class="space-y-5">
                            <div>
                                <label class="block text-[10px] text-gray-500 font-black uppercase tracking-widest mb-3">Painter Reference</label>
                                <input type="text" name="artwork[${i}][painterRef]" class="multi-input w-full bg-neutral-800/30 border border-white/5 text-white rounded-xl px-5 py-3.5 focus:border-[#C9A74E]/50 outline-none transition-all text-sm font-bold" data-field="painterRef" placeholder="By Signature">
                            </div>
                            <div>
                                <label class="block text-[10px] text-gray-500 font-black uppercase tracking-widest mb-3">Art Style</label>
                                <select name="artwork[${i}][style]" class="multi-input w-full bg-neutral-800/30 border border-white/5 text-white rounded-xl px-5 py-3.5 focus:border-[#C9A74E]/50 outline-none transition-all text-sm font-bold appearance-none cursor-pointer" data-field="style">
                                    <option value="" disabled selected>Select Style</option>
                                    ${categories.map(cat => `<option value="${cat}">${cat}</option>`).join('')}
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] text-gray-500 font-black uppercase tracking-widest mb-3">Stock</label>
                                    <input type="number" name="artwork[${i}][stock]" class="multi-input w-full bg-neutral-800/30 border border-white/5 text-white rounded-xl px-5 py-3.5 focus:border-[#C9A74E]/50 outline-none transition-all text-sm font-bold text-center" data-field="stock" value="1">
                                </div>
                                <div>
                                    <label class="block text-[10px] text-gray-500 font-black uppercase tracking-widest mb-3">Max Limit</label>
                                    <input type="number" name="artwork[${i}][maxLimit]" class="multi-input w-full bg-neutral-800/30 border border-white/5 text-white rounded-xl px-5 py-3.5 focus:border-[#C9A74E]/50 outline-none transition-all text-sm font-bold text-center" data-field="maxLimit" value="1">
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                // Slide 3: Pricing
                container.innerHTML += `
                    <div class="multi-slide space-y-8 d-none" data-art-index="${i}" data-section="2">
                        <div class="flex items-center gap-4 mb-10">
                            <span class="text-4xl font-black text-[#C9A74E]/20 italic">#${artNum}</span>
                            <h3 class="text-xl text-white font-black tracking-tight uppercase">Art Pricing</h3>
                        </div>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-[10px] text-gray-500 font-black uppercase tracking-widest mb-3">Base Price</label>
                                <div class="relative">
                                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-[#C9A74E] font-black text-sm">$</span>
                                    <input type="number" name="artwork[${i}][basePrice]" class="multi-input w-full bg-neutral-800/30 border border-white/5 text-white rounded-xl pl-10 pr-5 py-3.5 focus:border-[#C9A74E]/50 outline-none transition-all text-sm font-bold" data-field="basePrice" placeholder="0">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] text-gray-500 font-black uppercase tracking-widest mb-3">Sale Price</label>
                                <div class="relative">
                                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-[#C9A74E] font-black text-sm">$</span>
                                    <input type="number" name="artwork[${i}][salePrice]" class="multi-input w-full bg-neutral-800/30 border border-white/5 text-white rounded-xl pl-10 pr-5 py-3.5 focus:border-[#C9A74E]/50 outline-none transition-all text-sm font-bold" data-field="salePrice" placeholder="0">
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                // Slide 4: Assets & Certificate
                container.innerHTML += `
                    <div class="multi-slide space-y-8 d-none" data-art-index="${i}" data-section="3">
                        <div class="flex items-center gap-4 mb-8">
                            <span class="text-4xl font-black text-[#C9A74E]/20 italic">#${artNum}</span>
                            <h3 class="text-xl text-white font-black tracking-tight uppercase">Media & Certificate</h3>
                        </div>
                        
                        <div class="space-y-8">
                            <div>
                                <label class="block text-[10px] text-gray-500 font-black uppercase tracking-widest mb-4">Art Photos (Max 3, Total 20MB)</label>
                                <input type="file" name="artwork[${i}][images][]" class="multi-file-input hidden" id="multiFileInput_${i}" accept="image/*" multiple onchange="handleMultiMedia(this, ${i})">
                                <div class="flex flex-wrap gap-4" id="multiMediaContainer_${i}">
                                    <div onclick="document.getElementById('multiFileInput_${i}').click()" class="w-24 h-24 border-2 border-dashed border-neutral-700 rounded-2xl flex flex-col items-center justify-center cursor-pointer hover:border-[#C9A74E] hover:bg-[#C9A74E]/5 transition-all group">
                                        <i data-lucide="camera" class="text-neutral-500 group-hover:text-[#C9A74E] w-6 h-6 mb-1"></i>
                                        <span class="text-[8px] font-black text-neutral-600 group-hover:text-[#C9A74E] uppercase tracking-widest">Add Photo</span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-6 border-t border-white/5">
                                <label class="block text-[10px] text-[#C9A74E] font-black uppercase tracking-widest mb-4">Authenticity Certificate</label>
                                <input type="file" name="artwork[${i}][certificate]" class="hidden" id="certInput_${i}" accept=".pdf,image/*" onchange="handleCertUpload(this, ${i})">
                                <div onclick="document.getElementById('certInput_${i}').click()" class="w-full bg-neutral-800/30 border border-white/5 hover:border-[#C9A74E]/50 rounded-2xl p-6 transition-all cursor-pointer group">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <div class="p-3 bg-[#C9A74E]/10 rounded-xl text-[#C9A74E]">
                                                <i data-lucide="shield-check" class="w-6 h-6"></i>
                                            </div>
                                            <div>
                                                <p id="certName_${i}" class="text-sm font-bold text-gray-300">Upload Certificate</p>
                                                <p class="text-[10px] text-gray-500 uppercase tracking-widest">PDF or Image (Max 5MB)</p>
                                            </div>
                                        </div>
                                        <i data-lucide="upload-cloud" class="w-5 h-5 text-neutral-600 group-hover:text-[#C9A74E] transition-colors"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
        }

        function navigateSlide(direction) {
            const nextSlide = currentSlide + direction;
            if (nextSlide >= 0 && nextSlide < totalSlides) {
                currentSlide = nextSlide;
                updateSlideDisplay();
            }
        }

        function updateSlideDisplay() {
            const slides = document.querySelectorAll('.multi-slide');
            slides.forEach((slide, idx) => {
                slide.classList.toggle('d-none', idx !== currentSlide);
            });

            // Update Counter & Progress
            const progress = ((currentSlide + 1) / totalSlides) * 100;
            document.getElementById('multiProgressBar').style.width = `${progress}%`;
            document.getElementById('slideCounter').innerText = `Step ${currentSlide + 1} of ${totalSlides}`;

            // Update Buttons
            document.getElementById('prevBtn').disabled = currentSlide === 0;
            
            const isLast = currentSlide === totalSlides - 1;
            document.getElementById('nextBtn').classList.toggle('hidden', isLast);
            document.getElementById('multiSubmitBtn').classList.toggle('hidden', !isLast);
        }

        function closeMultiForm() {
            document.getElementById('multiFormModal').classList.add('hidden');
            document.getElementById('multiFormModal').classList.remove('flex');
        }

        function handleCertUpload(input, artIndex) {
            if (input.files && input.files[0]) {
                const name = input.files[0].name;
                document.getElementById(`certName_${artIndex}`).innerText = name;
                document.getElementById(`certName_${artIndex}`).classList.add('text-[#C9A74E]');
            }
        }

        function handleMultiMedia(input, artIndex) {
            const container = document.getElementById(`multiMediaContainer_${artIndex}`);
            const files = Array.from(input.files);

            if (files.length > 3) {
                Swal.fire({ icon: 'error', title: 'Limit Exceeded', text: 'Max 3 photos allowed.', background: '#1a1a1a', color: '#fff' });
                input.value = '';
                return;
            }

            // Clear existing previews except the "Add" button
            const addButton = container.firstElementChild;
            container.innerHTML = '';
            container.appendChild(addButton);

            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = "w-24 h-24 bg-neutral-800 rounded-2xl overflow-hidden relative group border border-white/5";
                    div.innerHTML = `
                        <img src="${e.target.result}" class="object-cover w-full h-full opacity-60">
                        <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-[8px] font-black text-white uppercase tracking-widest">OK</span>
                        </div>
                    `;
                    container.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
        }

        async function submitMultiForm() {
            const formData = new FormData();
            
            // Add Artist Data
            formData.append('artistData[name]', selectedArtistData.name);
            formData.append('artistData[birthplace]', selectedArtistData.birthplace);
            formData.append('artistData[career]', selectedArtistData.career);
            formData.append('artistData[desc]', selectedArtistData.desc);

            // Add Artworks Data
            const numArtworks = parseInt(document.getElementById('art-count-input').value);
            for (let i = 0; i < numArtworks; i++) {
                const artSlides = document.querySelectorAll(`.multi-slide[data-art-index="${i}"]`);
                artSlides.forEach(slide => {
                    const inputs = slide.querySelectorAll('.multi-input');
                    inputs.forEach(input => {
                        formData.append(`artwork[${i}][${input.dataset.field}]`, input.value);
                    });
                });

                // Add Images
                const fileInput = document.getElementById(`multiFileInput_${i}`);
                if (fileInput.files) {
                    Array.from(fileInput.files).forEach(file => {
                        formData.append(`artwork[${i}][images][]`, file);
                    });
                }

                // Add Certificate
                const certInput = document.getElementById(`certInput_${i}`);
                if (certInput.files && certInput.files[0]) {
                    formData.append(`artwork[${i}][certificate]`, certInput.files[0]);
                }
            }

            Swal.fire({
                title: 'Publishing Batch...',
                html: 'Processing multi-artwork submission...',
                background: '#1a1a1a', color: '#fff', allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            try {
                const response = await fetch('/api/artworks/store-multiple', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                });

                const result = await response.json();

                if (result.success) {
                    await Swal.fire({ icon: 'success', title: 'Batch Success!', text: result.message, background: '#1a1a1a', color: '#fff', timer: 2000, showConfirmButton: false });
                    result.artworks.forEach(art => appendNewArtworkCard(art));
                    closeMultiForm();
                } else {
                    throw new Error(result.message || 'Submission failed');
                }
            } catch (error) {
                Swal.fire({ icon: 'error', title: 'Batch Upload Failed', text: error.message, background: '#1a1a1a', color: '#fff' });
            }
        }
    </script>
    <style>
        .d-none { display: none !important; }
    </style>
</body>
</html>