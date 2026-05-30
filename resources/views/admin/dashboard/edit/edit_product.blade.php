<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kapakapa Art Gallery | Edit Collection</title>
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

        .swal2-container { z-index: 10001 !important; }

        .preview-box {
            position: relative;
            width: 100%;
            aspect-ratio: 1;
            background: #262626;
            border: 2px dashed #404040;
            border-radius: 1rem;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .preview-box:hover { border-color: #C9A74E; background: #2a2a2a; }
        .preview-box img { width: 100%; height: 100%; object-fit: cover; }
    </style>
</head>
<body class="bg-[#1a1a1a] text-gray-300 antialiased font-sans custom-scrollbar">

    <div class="flex min-h-screen">
        @include('admin.dashboard.layouts.sidebar')

        <div class="flex flex-col flex-1 min-w-0 bg-[#1a1a1a]">
            <header class="flex items-center justify-between p-5 border-b bg-[#1a1a1a] border-neutral-800/60 sticky top-0 z-30">
                <div class="flex items-center gap-5">
                    <a href="{{ route('submit_artworks') }}" class="p-2.5 text-gray-400 hover:text-yellow-500 rounded-xl transition-colors">
                        <i data-lucide="arrow-left" class="w-6 h-6"></i>
                    </a>
                    <div class="flex flex-col leading-tight">
                        <h1 class="text-xl font-black text-white lg:text-3xl italic uppercase">
                            <span class="bg-gradient-to-r from-white via-gray-400 to-white bg-[length:200%_auto] bg-clip-text text-transparent animate-shimmer">
                                Edit Collection
                            </span>
                        </h1>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-6 lg:p-10">
                <div class="max-w-7xl mx-auto">
                    <form id="artForm" onsubmit="handleFormSubmit(event)">
                        <input type="hidden" name="artist[id]" id="artist_id" value="{{ $artist->id }}">
                        @csrf
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                            {{-- Left Column: Artist Bio --}}
                            <div class="lg:col-span-5 space-y-10 border-r border-white/5 pr-0 lg:pr-12">
                                <div class="space-y-6">
                                    <h2 class="text-2xl text-white font-black tracking-tight flex items-center gap-3">
                                        <span class="w-1.5 h-8 bg-[#C9A74E]"></span> ARTIST BIOGRAPHY
                                    </h2>
                                    <div class="space-y-6">
                                        <div class="flex items-center gap-6">
                                            <div class="w-24 h-24 flex-shrink-0">
                                                <label class="block text-[10px] text-gray-500 font-black uppercase tracking-widest mb-3">Profile</label>
                                                <input type="file" name="artist[profile]" id="profileInput" accept="image/*" class="hidden" onchange="previewProfile(this)">
                                                <div onclick="document.getElementById('profileInput').click()" class="group relative w-full h-full rounded-2xl overflow-hidden border border-white/5 cursor-pointer bg-neutral-800/50">
                                                    <img id="profileDisplay" src="{{ $artist->profile_url ?? 'https://api.dicebear.com/8.x/notionists/svg?seed=artist' }}" class="w-full h-full object-cover opacity-50 group-hover:opacity-100 transition-all" />
                                                    <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all">
                                                        <i data-lucide="camera" class="w-5 h-5 text-[#C9A74E]"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <label class="block text-[10px] text-gray-500 font-black uppercase tracking-widest mb-3">Painter Name</label>
                                                <input type="text" name="artist[name]" value="{{ $artist->name }}" placeholder="Full Name" class="w-full bg-neutral-800/30 border border-white/5 text-white rounded-xl px-5 py-3.5 focus:border-[#C9A74E]/50 outline-none transition-all text-sm font-bold" required>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[10px] text-gray-500 font-black uppercase tracking-widest mb-3">Birthplace</label>
                                                <input type="text" name="artist[birthplace]" value="{{ $artist->birthplace }}" placeholder="City, Country" class="w-full bg-neutral-800/30 border border-white/5 text-white rounded-xl px-5 py-3.5 focus:border-[#C9A74E]/50 outline-none transition-all text-sm font-bold" required>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] text-gray-500 font-black uppercase tracking-widest mb-3">Career</label>
                                                <input type="text" name="artist[career]" value="{{ $artist->career }}" placeholder="e.g. 2015 - Now" class="w-full bg-neutral-800/30 border border-white/5 text-white rounded-xl px-5 py-3.5 focus:border-[#C9A74E]/50 outline-none transition-all text-sm font-bold" required>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] text-gray-500 font-black uppercase tracking-widest mb-3">Biography</label>
                                            <textarea name="artist[desc]" placeholder="Brief story..." class="w-full bg-neutral-800/30 border border-white/5 text-white rounded-2xl px-5 py-4 focus:border-[#C9A74E]/50 outline-none transition-all text-sm font-bold h-24" required>{{ $artist->bio }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-10 border-t border-white/5 space-y-6">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-xs text-[#C9A74E] font-black uppercase tracking-widest">Collection Setup</h3>
                                        <div class="flex items-center gap-3">
                                            <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Qty:</span>
                                            <input type="number" id="collection-qty" value="{{ count($artist->artworks) }}" min="1" max="10" oninput="updateCollectionSlides(this.value)" class="w-16 bg-neutral-800 border border-[#C9A74E]/30 text-white rounded-lg px-3 py-2 text-xs font-bold focus:border-[#C9A74E] outline-none transition-all">
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-gray-600 font-medium leading-relaxed italic">Specify the number of artworks to include in this collection. Each will have its own slide for details and media.</p>
                                </div>
                            </div>

                            {{-- Right Column: Dynamic Artwork Slides --}}
                            <div class="lg:col-span-7 flex flex-col min-h-[500px]">
                                <div class="flex items-center justify-between mb-8">
                                    <div class="flex items-center gap-4">
                                        <span class="p-2 bg-[#C9A74E]/10 rounded-lg text-[#C9A74E]">
                                            <i data-lucide="layout-grid" class="w-5 h-5"></i>
                                        </span>
                                        <div>
                                            <h3 class="text-lg text-white font-black tracking-tight uppercase">Artwork Details</h3>
                                            <p id="collection-slide-counter" class="text-[10px] text-[#C9A74E] font-bold uppercase tracking-widest">Artwork 1 of 1</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="button" onclick="navigateCollectionSlide(-1)" id="collPrevBtn" class="p-3 bg-neutral-800 border border-white/5 text-gray-500 hover:text-white rounded-xl transition-all disabled:opacity-20">
                                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                        </button>
                                        <button type="button" onclick="navigateCollectionSlide(1)" id="collNextBtn" class="p-3 bg-neutral-800 border border-white/5 text-gray-500 hover:text-white rounded-xl transition-all disabled:opacity-20">
                                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>

                                <div id="collection-slides-container" class="flex-1 space-y-8">
                                    {{-- Dynamic Slides Injected Here --}}
                                </div>

                                <div class="flex justify-end gap-4 mt-12 pt-8 border-t border-white/5">
                                    <button type="button" onclick="handleCancel()" class="px-8 py-4 bg-transparent border border-white/10 text-gray-400 hover:text-white rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-10 py-4 bg-[#C9A74E] hover:bg-[#DBBC6A] text-black rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-[#C9A74E]/20 transition-all hover:scale-105">
                                        Update Collection
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    {{-- IMAGE PREVIEW MODAL --}}
    <div id="imagePreviewModal" class="fixed inset-0 z-[10002] hidden items-center justify-center p-4 bg-black/95 backdrop-blur-sm">
        <div class="relative max-w-4xl w-full flex flex-col items-center">
            <img id="modalImagePreview" src="" class="max-h-[70vh] rounded-2xl mb-8 object-contain shadow-2xl">
            <div class="flex gap-6">
                <button onclick="triggerFileChange()" class="px-8 py-3 bg-[#C9A74E] text-black font-black uppercase text-xs tracking-widest rounded-xl hover:bg-[#DBBC6A] transition-all">
                    Change Photo
                </button>
                <button onclick="closePreviewModal()" class="px-8 py-3 bg-neutral-800 text-white font-black uppercase text-xs tracking-widest rounded-xl hover:bg-neutral-700 transition-all">
                    Back
                </button>
            </div>
        </div>
    </div>

    <script>
        const categories = @json($categories);
        const artistArtworks = @json($artist->artworks);
        let currentCollectionSlide = 0;
        let totalCollectionSlides = 1;
        let activeInputId = null;

        function previewProfile(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profileDisplay').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function updateCollectionSlides(qty) {
            qty = parseInt(qty) || 1;
            totalCollectionSlides = qty;
            currentCollectionSlide = 0;
            
            const container = document.getElementById('collection-slides-container');
            container.innerHTML = '';
            
            for (let i = 0; i < qty; i++) {
                const art = artistArtworks[i] || {};
                const images = art.images || [];

                container.innerHTML += `
                    <div class="collection-slide space-y-8 ${i === 0 ? '' : 'hidden'}" data-index="${i}">
                        <input type="hidden" name="artwork[${i}][id]" id="artwork_id_${i}" value="${art.id || ''}">
                        <div class="grid grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <h4 class="text-[10px] text-gray-500 font-black uppercase tracking-[0.2em]">Identification</h4>
                                <div>
                                    <label class="block text-[8px] text-gray-600 font-black uppercase tracking-widest mb-2">Art Name</label>
                                    <input type="text" name="artwork[${i}][name]" value="${art.title || ''}" placeholder="Masterpiece #${i+1}" class="w-full bg-neutral-800/50 border border-white/5 text-white rounded-xl px-4 py-3 focus:border-[#C9A74E]/50 outline-none transition-all text-xs font-bold" required>
                                </div>
                                <div>
                                    <label class="block text-[8px] text-gray-600 font-black uppercase tracking-widest mb-2">Description</label>
                                    <textarea name="artwork[${i}][desc]" placeholder="Story..." class="w-full bg-neutral-800/50 border border-white/5 text-white rounded-xl px-4 py-3 focus:border-[#C9A74E]/50 outline-none transition-all text-xs font-bold h-20" required>${art.art_desc || ''}</textarea>
                                </div>
                            </div>
                            <div class="space-y-6">
                                <h4 class="text-[10px] text-gray-500 font-black uppercase tracking-[0.2em]">Product Details</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[8px] text-gray-600 font-black uppercase tracking-widest mb-2">Ref</label>
                                        <input type="text" name="artwork[${i}][painterRef]" value="${art.painter_ref || ''}" placeholder="Signature" class="w-full bg-neutral-800/50 border border-white/5 text-white rounded-xl px-4 py-3 focus:border-[#C9A74E]/50 outline-none transition-all text-xs font-bold" required>
                                    </div>
                                    <div>
                                        <label class="block text-[8px] text-gray-600 font-black uppercase tracking-widest mb-2">Style</label>
                                        <select name="artwork[${i}][style]" class="w-full bg-neutral-800/50 border border-white/5 text-white rounded-xl px-4 py-3 focus:border-[#C9A74E]/50 outline-none transition-all text-xs font-bold appearance-none" required>
                                            <option value="" disabled>Style</option>
                                            ${categories.map(cat => `<option value="${cat}" ${art.category === cat ? 'selected' : ''}>${cat}</option>`).join('')}
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[8px] text-gray-600 font-black uppercase tracking-widest mb-2">Stock</label>
                                        <input type="number" name="artwork[${i}][stock]" value="${art.stock || 1}" class="w-full bg-neutral-800/50 border border-white/5 text-white rounded-xl px-4 py-3 focus:border-[#C9A74E]/50 outline-none transition-all text-xs font-bold text-center" required>
                                    </div>
                                    <div>
                                        <label class="block text-[8px] text-gray-600 font-black uppercase tracking-widest mb-2">Limit</label>
                                        <input type="number" name="artwork[${i}][maxLimit]" value="${art.max_limit || 1}" class="w-full bg-neutral-800/50 border border-white/5 text-white rounded-xl px-4 py-3 focus:border-[#C9A74E]/50 outline-none transition-all text-xs font-bold text-center" required>
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 gap-3">
                                    <div>
                                        <label class="block text-[8px] text-gray-600 font-black uppercase tracking-widest mb-2">Width</label>
                                        <input type="number" step="0.01" name="artwork[${i}][width]" value="${art.width || ''}" placeholder="W" class="w-full bg-neutral-800/50 border border-white/5 text-white rounded-xl px-4 py-3 focus:border-[#C9A74E]/50 outline-none transition-all text-xs font-bold text-center">
                                    </div>
                                    <div>
                                        <label class="block text-[8px] text-gray-600 font-black uppercase tracking-widest mb-2">Height</label>
                                        <input type="number" step="0.01" name="artwork[${i}][height]" value="${art.height || ''}" placeholder="H" class="w-full bg-neutral-800/50 border border-white/5 text-white rounded-xl px-4 py-3 focus:border-[#C9A74E]/50 outline-none transition-all text-xs font-bold text-center">
                                    </div>
                                    <div>
                                        <label class="block text-[8px] text-gray-600 font-black uppercase tracking-widest mb-2">Unit</label>
                                        <select name="artwork[${i}][unit]" class="w-full bg-neutral-800/50 border border-white/5 text-white rounded-xl px-4 py-3 focus:border-[#C9A74E]/50 outline-none transition-all text-xs font-bold appearance-none">
                                            <option value="cm" ${art.unit === 'cm' ? 'selected' : ''}>cm</option>
                                            <option value="inch" ${art.unit === 'inch' ? 'selected' : ''}>inch</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-8 pt-6 border-t border-white/5">
                            <div class="space-y-6">
                                <h4 class="text-[10px] text-gray-500 font-black uppercase tracking-[0.2em]">Art Pricing</h4>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#C9A74E] font-black text-sm">$</span>
                                    <input type="number" name="artwork[${i}][salePrice]" value="${art.sale_price || ''}" placeholder="Sale Price" class="w-full bg-neutral-800/50 border border-white/5 text-white rounded-xl pl-8 pr-4 py-3 focus:border-[#C9A74E]/50 outline-none transition-all text-xs font-bold" required>
                                </div>
                            </div>
                            <div class="space-y-6">
                                <h4 class="text-[10px] text-gray-500 font-black uppercase tracking-[0.2em]">Media & Certificate</h4>
                                <div class="grid grid-cols-4 gap-3">
                                    <div class="col-span-3 grid grid-cols-3 gap-2">
                                        ${[0, 1, 2].map(j => `
                                            <div class="preview-box ${images[j] ? '' : 'border-dashed'}" id="mediaBox_${i}_${j}" onclick="handleMediaClick(${i}, ${j})">
                                                <input type="file" name="artwork[${i}][media][]" id="mediaInput_${i}_${j}" accept="image/*" class="hidden" onchange="previewMedia(this, ${i}, ${j})">
                                                <div id="mediaPlaceholder_${i}_${j}" class="absolute inset-0 flex flex-col items-center justify-center text-neutral-600 ${images[j] ? 'hidden' : ''}">
                                                    <i data-lucide="image" class="w-4 h-4 mb-1"></i>
                                                    <span class="text-[6px] font-black uppercase">Slot ${j+1}</span>
                                                </div>
                                                <img id="mediaImg_${i}_${j}" src="${images[j] || ''}" class="${images[j] ? '' : 'hidden'}">
                                            </div>
                                        `).join('')}
                                    </div>
                                    <div class="col-span-1">
                                        <input type="file" name="artwork[${i}][certificate]" id="certInput_${i}" accept="image/*,.pdf" class="hidden" onchange="handleCertUpload(this, ${i})">
                                        <div onclick="document.getElementById('certInput_${i}').click()" class="preview-box ${art.certificate_url ? 'border-solid border-green-500/50' : 'border-dashed border-red-500/20'}" id="certBox_${i}">
                                            <div id="certPlaceholder_${i}" class="absolute inset-0 flex flex-col items-center justify-center text-red-500/40 ${art.certificate_url ? 'hidden' : ''}">
                                                <i data-lucide="shield-check" class="w-4 h-4 mb-1"></i>
                                                <span class="text-[6px] font-black uppercase">Cert</span>
                                            </div>
                                            <img id="certImg_${i}" src="${art.certificate_url || ''}" class="${art.certificate_url && !art.certificate_url.endsWith('.pdf') ? '' : 'hidden'}">
                                            ${art.certificate_url && art.certificate_url.endsWith('.pdf') ? `<div class="absolute inset-0 flex items-center justify-center text-green-500"><i data-lucide="file-text" class="w-8 h-8"></i></div>` : ''}
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

        function handleMediaClick(artIdx, mediaIdx) {
            const img = document.getElementById(`mediaImg_${artIdx}_${mediaIdx}`);
            if (img && !img.classList.contains('hidden') && img.src) {
                openPreviewModal(img.src, `mediaInput_${artIdx}_${mediaIdx}`);
            } else {
                document.getElementById(`mediaInput_${artIdx}_${mediaIdx}`).click();
            }
        }

        function previewMedia(input, artIdx, mediaIdx) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById(`mediaImg_${artIdx}_${mediaIdx}`);
                    const placeholder = document.getElementById(`mediaPlaceholder_${artIdx}_${mediaIdx}`);
                    const box = document.getElementById(`mediaBox_${artIdx}_${mediaIdx}`);
                    
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    box.classList.remove('border-dashed');
                    box.style.borderColor = '#C9A74E';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function handleCertUpload(input, index) {
            if (input.files && input.files[0]) {
                const placeholder = document.getElementById(`certPlaceholder_${index}`);
                const box = document.getElementById(`certBox_${index}`);
                const img = document.getElementById(`certImg_${index}`);
                
                if (input.files[0].type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        img.src = e.target.result;
                        img.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                    }
                    reader.readAsDataURL(input.files[0]);
                } else {
                    placeholder.innerHTML = `<i data-lucide="file-text" class="w-4 h-4 mb-1"></i><span class="text-[6px] font-black uppercase">PDF</span>`;
                    img.classList.add('hidden');
                    placeholder.classList.remove('hidden');
                    lucide.createIcons();
                }
                box.classList.remove('border-dashed');
                box.style.borderColor = '#22c55e';
            }
        }

        function openPreviewModal(src, inputId) {
            activeInputId = inputId;
            document.getElementById('modalImagePreview').src = src;
            document.getElementById('imagePreviewModal').classList.remove('hidden');
            document.getElementById('imagePreviewModal').classList.add('flex');
        }

        function closePreviewModal() {
            document.getElementById('imagePreviewModal').classList.add('hidden');
            document.getElementById('imagePreviewModal').classList.remove('flex');
            activeInputId = null;
        }

        function triggerFileChange() {
            if (activeInputId) {
                document.getElementById(activeInputId).click();
                closePreviewModal();
            }
        }

        function navigateCollectionSlide(dir) {
            const next = currentCollectionSlide + dir;
            if (next >= 0 && next < totalCollectionSlides) {
                currentCollectionSlide = next;
                const slides = document.querySelectorAll('.collection-slide');
                slides.forEach((s, i) => s.classList.toggle('hidden', i !== currentCollectionSlide));
                updateCollectionNav();
            }
        }

        function updateCollectionNav() {
            document.getElementById('collection-slide-counter').innerText = `Artwork ${currentCollectionSlide + 1} of ${totalCollectionSlides}`;
            document.getElementById('collPrevBtn').disabled = currentCollectionSlide === 0;
            document.getElementById('collNextBtn').disabled = currentCollectionSlide === totalCollectionSlides - 1;
        }

        function handleCancel() {
            Swal.fire({
                title: 'Discard Changes?',
                text: 'Unsaved changes will be lost. Are you sure you want to discard this session?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#404040',
                confirmButtonText: 'Yes, discard',
                background: '#1a1a1a',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('submit_artworks') }}";
                }
            });
        }

        async function handleFormSubmit(event) {
            event.preventDefault();
            
            const result = await Swal.fire({
                title: 'Final Review',
                text: 'Please review all entries for accuracy before publishing. Proceed with submission?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#C9A74E',
                cancelButtonColor: '#404040',
                confirmButtonText: 'Yes, Publish',
                background: '#1a1a1a',
                color: '#fff'
            });

            if (!result.isConfirmed) return;

            const form = event.target;
            const formData = new FormData(form);

            Swal.fire({
                title: 'Updating Collection...',
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
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();
                if (data.success) {
                    await Swal.fire({ icon: 'success', title: 'Updated!', text: data.message, background: '#1a1a1a', color: '#fff' });
                    window.location.href = "{{ route('submit_artworks') }}";
                } else {
                    throw new Error(data.message || 'Validation failed');
                }
            } catch (error) {
                Swal.fire({ icon: 'error', title: 'Error', text: error.message, background: '#1a1a1a', color: '#fff' });
            }
        }

        // Initialize slides based on existing count
        updateCollectionSlides(artistArtworks.length);
    </script>
</body>
</html>