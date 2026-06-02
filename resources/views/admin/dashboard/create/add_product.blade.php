<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kapakapa Art Gallery | Add New Collection</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Scrollbar — hidden visually, functional */
        .custom-scrollbar {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .custom-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* Shimmer animation */
        @keyframes shimmer {
            to { background-position: 200% center; }
        }
        .animate-shimmer {
            animation: shimmer 3s linear infinite;
        }

        /* Fade-in animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px) scale(0.95); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        .fade-in {
            animation: fadeIn 0.2s ease-out;
        }

        /* SweetAlert z-index override */
        .swal2-container {
            z-index: 10001 !important;
        }

        /* Media / certificate preview box */
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

        /* Override khusus untuk cert box agar menyesuaikan gambar */
        .cert-preview-box {
            aspect-ratio: unset;       /* hapus paksa square */
            min-height: 80px;
            height: auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cert-preview-box img {
            width: 100%;
            height: auto;              /* tinggi otomatis ikuti aspect ratio */
            object-fit: contain;       /* tidak crop, tampil penuh */
            max-height: 200px;
        }

        .preview-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .preview-box:hover {
            border-color: #C9A74E;
            background: #2a2a2a;
        }
    </style>
</head>
<body class="bg-[#1a1a1a] text-gray-300 antialiased font-sans custom-scrollbar">

    <div class="flex min-h-screen">
        @include('admin.dashboard.layouts.sidebar')

        <div class="flex flex-col flex-1 min-w-0 bg-[#1a1a1a]">

            {{-- Header --}}
            <header class="flex items-center justify-between p-5 border-b bg-[#1a1a1a] border-neutral-800/60 sticky top-0 z-30">
                <div class="flex items-center gap-5">
                    <a href="{{ route('submit_artworks') }}" class="p-2.5 text-gray-400 hover:text-yellow-500 rounded-xl transition-colors">
                        <i data-lucide="arrow-left" class="w-6 h-6"></i>
                    </a>
                    <h1 class="text-xl font-black text-white lg:text-3xl italic uppercase">
                        <span class="bg-gradient-to-r from-white via-gray-400 to-white bg-[length:200%_auto] bg-clip-text text-transparent animate-shimmer">
                            Add New Collection
                        </span>
                    </h1>
                </div>
            </header>

            {{-- Main Content --}}
            <main class="flex-1 p-6 lg:p-10">
                <div class="max-w-7xl mx-auto">
                    <form id="artForm" onsubmit="handleFormSubmit(event)">
                        <input type="hidden" name="artist[id]" id="artist_id">
                        @csrf

                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

                            {{-- ───────────────────────────────────────
                                 Left Column: Artist Biography
                            ─────────────────────────────────────── --}}
                            <div class="lg:col-span-5 space-y-10 border-r border-white/5 pr-0 lg:pr-12">

                                {{-- Biography Section --}}
                                <div class="space-y-6">
                                    <h2 class="text-2xl text-white font-black tracking-tight flex items-center gap-3">
                                        <span class="w-1.5 h-8 bg-[#C9A74E]"></span>
                                        ARTIST BIOGRAPHY
                                    </h2>

                                    <div class="space-y-6">
                                        {{-- Profile Photo + Name --}}
                                        <div class="flex items-center gap-6">
                                            <div class="w-24 h-24 flex-shrink-0">
                                                <label class="block text-[10px] text-gray-500 font-black uppercase tracking-widest mb-3">Profile</label>
                                                <input type="file" name="artist[profile]" id="profileInput" accept="image/*" class="hidden" onchange="previewProfile(this)">
                                                <div onclick="document.getElementById('profileInput').click()"
                                                     class="group relative w-full h-full rounded-2xl overflow-hidden border border-white/5 cursor-pointer bg-neutral-800/50">
                                                    <img id="profileDisplay"
                                                         src="https://api.dicebear.com/8.x/notionists/svg?seed=artist"
                                                         class="w-full h-full object-cover opacity-50 group-hover:opacity-100 transition-all" />
                                                    <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all">
                                                        <i data-lucide="camera" class="w-5 h-5 text-[#C9A74E]"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex-1">
                                                <label class="block text-[10px] text-gray-500 font-black uppercase tracking-widest mb-3">Painter Name</label>
                                                <input type="text" name="artist[name]" placeholder="Full Name"
                                                       class="w-full bg-neutral-800/30 border border-white/5 text-white rounded-xl px-5 py-3.5 focus:border-[#C9A74E]/50 outline-none transition-all text-sm font-bold"
                                                       required>
                                            </div>
                                        </div>

                                        {{-- Birthplace + Career --}}
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="pt-5">
                                                <label class="block text-[10px] text-gray-500 font-black uppercase tracking-widest mb-3">Birthplace</label>
                                                <input type="text" name="artist[birthplace]" placeholder="City, Country"
                                                       class="w-full bg-neutral-800/30 border border-white/5 text-white rounded-xl px-5 py-3.5 focus:border-[#C9A74E]/50 outline-none transition-all text-sm font-bold"
                                                       required>
                                            </div>
                                            <div class="pt-5">
                                                <label class="block text-[10px] text-gray-500 font-black uppercase tracking-widest mb-3">Career</label>
                                                <input type="text" name="artist[career]" placeholder="e.g. 2015 - Now"
                                                       class="w-full bg-neutral-800/30 border border-white/5 text-white rounded-xl px-5 py-3.5 focus:border-[#C9A74E]/50 outline-none transition-all text-sm font-bold"
                                                       required>
                                            </div>
                                        </div>

                                        {{-- Biography --}}
                                        <div>
                                            <label class="block text-[10px] text-gray-500 font-black uppercase tracking-widest mb-3">Biography</label>
                                            <textarea name="artist[desc]" placeholder="Brief story..."
                                                      class="w-full bg-neutral-800/30 border border-white/5 text-white rounded-2xl px-5 py-4 focus:border-[#C9A74E]/50 outline-none transition-all text-sm font-bold h-24"
                                                      required></textarea>
                                        </div>
                                    </div>
                                </div>

                                {{-- Collection Setup --}}
                                <div class="pt-10 border-t border-white/5 space-y-6">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-xs text-[#C9A74E] font-black uppercase tracking-widest">Collection Setup</h3>
                                        <div class="flex items-center gap-3">
                                            <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Qty:</span>
                                            <input type="number" id="collection-qty"
                                                   value="1" min="1" max="10"
                                                   oninput="updateCollectionSlides(this.value)"
                                                   class="w-16 bg-neutral-800 border border-[#C9A74E]/30 text-white rounded-lg px-3 py-2 text-xs font-bold focus:border-[#C9A74E] outline-none transition-all">
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-gray-600 font-medium leading-relaxed italic">
                                        Specify the number of artworks to include in this collection. Each will have its own slide for details and media.
                                    </p>
                                </div>

                            </div>

                            {{-- ───────────────────────────────────────
                                 Right Column: Dynamic Artwork Slides
                            ─────────────────────────────────────── --}}
                            <div class="lg:col-span-7 flex flex-col min-h-[500px]">

                                {{-- Slide Navigation Header --}}
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
                                        <button type="button" onclick="navigateCollectionSlide(-1)" id="collPrevBtn"
                                                class="p-3 bg-neutral-800 border border-white/5 text-gray-500 hover:text-white rounded-xl transition-all disabled:opacity-20">
                                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                        </button>
                                        <button type="button" onclick="navigateCollectionSlide(1)" id="collNextBtn"
                                                class="p-3 bg-neutral-800 border border-white/5 text-gray-500 hover:text-white rounded-xl transition-all disabled:opacity-20">
                                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- Slides Container --}}
                                <div id="collection-slides-container" class="flex-1 space-y-8">
                                    {{-- Dynamic slides injected via JS --}}
                                </div>

                                {{-- Form Actions --}}
                                <div class="flex justify-end gap-4 mt-12 pt-8 border-t border-white/5">
                                    <button type="button" onclick="handleCancel()"
                                            class="px-8 py-4 bg-transparent border border-white/10 text-gray-400 hover:text-white rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                            class="px-10 py-4 bg-[#C9A74E] hover:bg-[#DBBC6A] text-black rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-[#C9A74E]/20 transition-all hover:scale-105">
                                        Publish Collection
                                    </button>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </main>

        </div>
    </div>

    {{-- ─────────────────────────────────────────────
         Image Preview Modal
    ───────────────────────────────────────────── --}}
    <div id="imagePreviewModal"
         class="fixed inset-0 z-[10002] hidden items-center justify-center p-4 bg-black/95 backdrop-blur-sm">
        <div class="relative max-w-4xl w-full flex flex-col items-center">
            <img id="modalImagePreview" src="" class="max-h-[70vh] rounded-2xl mb-8 object-contain shadow-2xl">
            <div class="flex gap-6">
                <button onclick="triggerFileChange()"
                        class="px-8 py-3 bg-[#C9A74E] text-black font-black uppercase text-xs tracking-widest rounded-xl hover:bg-[#DBBC6A] transition-all">
                    Change Photo
                </button>
                <button onclick="closePreviewModal()"
                        class="px-8 py-3 bg-neutral-800 text-white font-black uppercase text-xs tracking-widest rounded-xl hover:bg-neutral-700 transition-all">
                    Back
                </button>
            </div>
        </div>
    </div>

    <script>
        const categories = @json($categories);

        let currentCollectionSlide = 0;
        let totalCollectionSlides  = 1;
        let activeInputId          = null;

        /* ── Profile Preview ─────────────────────────────── */
        function previewProfile(input) {
            if (!input.files?.length) return;
            const reader = new FileReader();
            reader.onload = e => document.getElementById('profileDisplay').src = e.target.result;
            reader.readAsDataURL(input.files[0]);
        }

        /* ── Build / Rebuild Slides ──────────────────────── */
        function updateCollectionSlides(qty) {
            qty = parseInt(qty) || 1;
            totalCollectionSlides  = qty;
            currentCollectionSlide = 0;

            const container = document.getElementById('collection-slides-container');
            container.innerHTML = '';

            for (let i = 0; i < qty; i++) {
                container.innerHTML += `
                    <div class="collection-slide space-y-8 ${i === 0 ? '' : 'hidden'}" data-index="${i}">

                        <input type="hidden" name="artwork[${i}][id]" id="artwork_id_${i}">

                        {{-- Identification + Product Details --}}
                        <div class="grid grid-cols-2 gap-8">

                            {{-- Identification --}}
                            <div class="space-y-6">
                                <h4 class="text-[10px] text-gray-500 font-black uppercase tracking-[0.2em]">Identification</h4>
                                <div>
                                    <label class="block text-[8px] text-gray-600 font-black uppercase tracking-widest mb-2">Art Name</label>
                                    <input type="text" name="artwork[${i}][name]" placeholder="Masterpiece #${i + 1}"
                                           class="w-full bg-neutral-800/50 border border-white/5 text-white rounded-xl px-4 py-3 focus:border-[#C9A74E]/50 outline-none transition-all text-xs font-bold"
                                           required>
                                </div>
                                <div>
                                    <label class="block text-[8px] text-gray-600 font-black uppercase tracking-widest mb-2">Description</label>
                                    <textarea name="artwork[${i}][desc]" placeholder="Story..."
                                              class="w-full bg-neutral-800/50 border border-white/5 text-white rounded-xl px-4 py-3 focus:border-[#C9A74E]/50 outline-none transition-all text-xs font-bold h-20"
                                              required></textarea>
                                </div>
                            </div>

                            {{-- Product Details --}}
                            <div class="space-y-6">
                                <h4 class="text-[10px] text-gray-500 font-black uppercase tracking-[0.2em]">Product Details</h4>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[8px] text-gray-600 font-black uppercase tracking-widest mb-2">Ref</label>
                                        <input type="text" name="artwork[${i}][painterRef]" placeholder="Signature"
                                               class="w-full bg-neutral-800/50 border border-white/5 text-white rounded-xl px-4 py-3 focus:border-[#C9A74E]/50 outline-none transition-all text-xs font-bold"
                                               required>
                                    </div>
                                    <div>
                                        <label class="block text-[8px] text-gray-600 font-black uppercase tracking-widest mb-2">Style</label>
                                        <select name="artwork[${i}][style]"
                                                class="w-full bg-neutral-800/50 border border-white/5 text-white rounded-xl px-4 py-3 focus:border-[#C9A74E]/50 outline-none transition-all text-xs font-bold appearance-none"
                                                required>
                                            <option value="" disabled selected>Style</option>
                                            ${categories.map(cat => `<option value="${cat}">${cat}</option>`).join('')}
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-3">
                                    <div>
                                        <label class="block text-[8px] text-gray-600 font-black uppercase tracking-widest mb-2">Width</label>
                                        <input type="number" step="0.01" name="artwork[${i}][width]" placeholder="W"
                                               class="w-full bg-neutral-800/50 border border-white/5 text-white rounded-xl px-4 py-3 focus:border-[#C9A74E]/50 outline-none transition-all text-xs font-bold text-center">
                                    </div>
                                    <div>
                                        <label class="block text-[8px] text-gray-600 font-black uppercase tracking-widest mb-2">Height</label>
                                        <input type="number" step="0.01" name="artwork[${i}][height]" placeholder="H"
                                               class="w-full bg-neutral-800/50 border border-white/5 text-white rounded-xl px-4 py-3 focus:border-[#C9A74E]/50 outline-none transition-all text-xs font-bold text-center">
                                    </div>
                                    <div>
                                        <label class="block text-[8px] text-gray-600 font-black uppercase tracking-widest mb-2">Unit</label>
                                        <select name="artwork[${i}][unit]"
                                                class="w-full bg-neutral-800/50 border border-white/5 text-white rounded-xl px-4 py-3 focus:border-[#C9A74E]/50 outline-none transition-all text-xs font-bold appearance-none">
                                            <option value="cm">cm</option>
                                            <option value="inch">inch</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Pricing + Media --}}
                        <div class="grid grid-cols-2 gap-8 pt-6 border-t border-white/5">

                            {{-- Art Pricing --}}
                            <div class="space-y-6">
                                <h4 class="text-[10px] text-gray-500 font-black uppercase tracking-[0.2em]">Art Pricing</h4>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#C9A74E] font-black text-xs">$</span>
                                    <input type="number" name="artwork[${i}][salePrice]" placeholder="Sale Price"
                                           class="w-full bg-neutral-800/50 border border-white/5 text-white rounded-xl pl-8 pr-4 py-3 focus:border-[#C9A74E]/50 outline-none transition-all text-xs font-bold"
                                           required>
                                </div>
                            </div>

                            {{-- Media & Certificate --}}
                            <div class="space-y-6">
                                <h4 class="text-[10px] text-gray-500 font-black uppercase tracking-[0.2em]">Media & Certificate</h4>
                                <div class="grid grid-cols-4 gap-3">

                                    {{-- Media Slots (3) --}}
                                    <div class="col-span-3 grid grid-cols-3 gap-2">
                                        ${[0, 1, 2].map(j => `
                                            <div class="preview-box"
                                                 id="mediaBox_${i}_${j}"
                                                 onclick="handleMediaClick(${i}, ${j})">
                                                <input type="file" name="artwork[${i}][media][${j}]"
                                                    id="mediaInput_${i}_${j}"
                                                       accept="image/*"
                                                       class="hidden"
                                                       onchange="previewMedia(this, ${i}, ${j})">
                                                <div id="mediaPlaceholder_${i}_${j}"
                                                     class="absolute inset-0 flex flex-col items-center justify-center text-neutral-600">
                                                    <i data-lucide="image" class="w-4 h-4 mb-1"></i>
                                                    <span class="text-[6px] font-black uppercase">Slot ${j + 1}</span>
                                                </div>
                                                <img id="mediaImg_${i}_${j}" class="hidden">
                                            </div>
                                        `).join('')}
                                    </div>

                                    {{-- Certificate Slot --}}
                                    <div class="col-span-1">
                                        <input type="file" name="artwork[${i}][certificate]"
                                               id="certInput_${i}"
                                               accept="image/*,.pdf"
                                               class="hidden"
                                               required
                                               onchange="handleCertUpload(this, ${i})">
                                        <div onclick="document.getElementById('certInput_${i}').click()"
                                            class="preview-box cert-preview-box border-red-500/20"
                                            id="certBox_${i}">
                                            <div id="certPlaceholder_${i}"
                                                 class="absolute inset-0 flex flex-col items-center justify-center text-red-500/40">
                                                <i data-lucide="shield-check" class="w-4 h-4 mb-1"></i>
                                                <span class="text-[6px] font-black uppercase">Cert</span>
                                            </div>
                                            <img id="certImg_${i}" class="hidden">
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

        /* ── Media Interactions ──────────────────────────── */
        function handleMediaClick(artIdx, mediaIdx) {
            const img = document.getElementById(`mediaImg_${artIdx}_${mediaIdx}`);
            if (img && !img.classList.contains('hidden')) {
                openPreviewModal(img.src, `mediaInput_${artIdx}_${mediaIdx}`);
            } else {
                document.getElementById(`mediaInput_${artIdx}_${mediaIdx}`).click();
            }
        }

        function previewMedia(input, artIdx, mediaIdx) {
            if (!input.files?.length) return;
            const reader = new FileReader();
            reader.onload = e => {
                const img         = document.getElementById(`mediaImg_${artIdx}_${mediaIdx}`);
                const placeholder = document.getElementById(`mediaPlaceholder_${artIdx}_${mediaIdx}`);
                const box         = document.getElementById(`mediaBox_${artIdx}_${mediaIdx}`);

                img.src = e.target.result;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
                box.style.borderStyle = 'solid';
                box.style.borderColor = '#C9A74E';
            };
            reader.readAsDataURL(input.files[0]);
        }

        function handleCertUpload(input, index) {
            if (!input.files?.length) return;
            const placeholder = document.getElementById(`certPlaceholder_${index}`);
            const box         = document.getElementById(`certBox_${index}`);
            const img         = document.getElementById(`certImg_${index}`);

            if (input.files[0].type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = e => {
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                placeholder.innerHTML = `<i data-lucide="file-text" class="w-4 h-4 mb-1"></i><span class="text-[6px] font-black uppercase">PDF</span>`;
                img.classList.add('hidden');
                placeholder.classList.remove('hidden');
                lucide.createIcons();
            }

            box.style.borderStyle = 'solid';
            box.style.borderColor = '#22c55e';
        }

        /* ── Image Preview Modal ─────────────────────────── */
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

        /* ── Slide Navigation ────────────────────────────── */
        function navigateCollectionSlide(dir) {
            const next = currentCollectionSlide + dir;
            if (next < 0 || next >= totalCollectionSlides) return;

            currentCollectionSlide = next;
            document.querySelectorAll('.collection-slide')
                    .forEach((s, i) => s.classList.toggle('hidden', i !== currentCollectionSlide));
            updateCollectionNav();
        }

        function updateCollectionNav() {
            document.getElementById('collection-slide-counter').innerText =
                `Artwork ${currentCollectionSlide + 1} of ${totalCollectionSlides}`;
            document.getElementById('collPrevBtn').disabled = currentCollectionSlide === 0;
            document.getElementById('collNextBtn').disabled = currentCollectionSlide === totalCollectionSlides - 1;
        }

        /* ── Cancel ──────────────────────────────────────── */
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
            }).then(result => {
                if (result.isConfirmed) window.location.href = "{{ route('submit_artworks') }}";
            });
        }

        /* ── Form Submit ─────────────────────────────────── */
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

            // ── Build FormData manual, skip file kosong ──
            const form = event.target;
            const formData = new FormData();

            // 1. Append semua field non-file (text, textarea, select, hidden)
            form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(el => {
                if (el.name && el.value !== '') {
                    formData.append(el.name, el.value);
                }
            });

            // 2. Append CSRF
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            // 3. Append profile artist jika ada
            const profileInput = document.getElementById('profileInput');
            if (profileInput && profileInput.files.length > 0 && profileInput.files[0].size > 0) {
                formData.append('artist[profile]', profileInput.files[0]);
            }

            // 4. Append media per artwork per slot
            for (let i = 0; i < totalCollectionSlides; i++) {
                for (let j = 0; j <= 2; j++) {
                    const mediaInput = document.getElementById(`mediaInput_${i}_${j}`);
                    if (mediaInput && mediaInput.files.length > 0 && mediaInput.files[0].size > 0) {
                        formData.append(`artwork[${i}][media][${j}]`, mediaInput.files[0]);
                    }
                }
                
                // Certificate
                const certInput = document.getElementById(`certInput_${i}`);
                if (certInput && certInput.files.length > 0 && certInput.files[0].size > 0) {
                    formData.append(`artwork[${i}][certificate]`, certInput.files[0]);
                }
            }

            Swal.fire({
                title: 'Publishing Collection...',
                background: '#1a1a1a',
                color: '#fff',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
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
                    await Swal.fire({ icon: 'success', title: 'Success!', text: data.message, background: '#1a1a1a', color: '#fff' });
                    window.location.href = "{{ route('submit_artworks') }}";
                } else {
                    throw new Error(data.message || 'Validation failed');
                }
            } catch (error) {
                Swal.fire({ icon: 'error', title: 'Error', text: error.message, background: '#1a1a1a', color: '#fff' });
            }
        }

        /* ── Init ────────────────────────────────────────── */
        updateCollectionSlides(1);
    </script>

    {{-- Validation (load after main script) --}}
    <script src="{{ asset('js/collection-validation.js') }}"></script>
</body>
</html>