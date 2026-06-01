@forelse($artworks ?? [] as $item)
                @php
                    $images = is_array($item->images) ? $item->images : json_decode($item->images, true);
                    $main_img = (is_array($images) && count($images) > 0) ? $images[0] : ($item->image_url ?? 'https://via.placeholder.com/800');
                    $sub_img1 = (is_array($images) && count($images) > 1) ? $images[1] : $main_img;
                    $sub_img2 = (is_array($images) && count($images) > 2) ? $images[2] : $main_img;
                    $author = $item->artist ?? 'Unknown Artist';
                    $dimensions = ($item->width && $item->height) ? $item->width . ' x ' . $item->height . ' ' . $item->unit : 'N/A';
                @endphp
                <div onclick="openModal('{{ $main_img }}', '{{ addslashes($item->title) }}', '{{ addslashes($author) }}', '{{ $dimensions }}')" 
                    class="group block cursor-pointer" 
                    data-aos="fade-up">
                    
                    {{-- Container Gambar --}}
                    <div class="relative flex gap-2 h-[450px] overflow-hidden mb-8 transition-all duration-700 group-hover:shadow-[0_40px_80px_-20px_rgba(0,0,0,0.9)]">
                        
                        {{-- Gambar Utama (Besar) --}}
                        <div class="w-2/3 h-full overflow-hidden bg-zinc-900 grayscale group-hover:grayscale-0 transition-all duration-1000 ease-in-out">
                            <img src="{{ $main_img }}" alt="{{ $item->title }}" class="w-full h-full object-cover scale-110 group-hover:scale-100 transition-transform duration-1000">
                        </div>

                        {{-- Gambar Samping (Kecil) --}}
                        <div class="w-1/3 flex flex-col gap-2">
                            <div class="h-1/2 overflow-hidden bg-zinc-900 grayscale group-hover:grayscale-0 transition-all duration-1000 delay-75">
                                <img src="{{ $sub_img1 }}" class="w-full h-full object-cover">
                            </div>
                            <div class="h-1/2 overflow-hidden bg-zinc-900 relative grayscale group-hover:grayscale-0 transition-all duration-1000 delay-150">
                                <img src="{{ $sub_img2 }}" class="w-full h-full object-cover opacity-30 group-hover:opacity-100 transition-opacity">
                                <div class="absolute inset-0 flex items-center justify-center bg-dark/60 group-hover:bg-transparent transition-all duration-500">
                                    <span class="text-gold font-serif italic text-2xl group-hover:scale-110 transition-transform">+{{ is_array($images) ? count($images) : 0 }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Border Overlay --}}
                        <div class="absolute inset-0 border border-gold/0 group-hover:border-gold/20 transition-all duration-700 pointer-events-none"></div>
                    </div>

                    {{-- Informasi Judul & Author --}}
                    <div class="space-y-4 px-2">
                        <div class="flex items-center gap-4">
                            <div class="h-[1px] w-0 group-hover:w-16 bg-gold transition-all duration-700 ease-out"></div>
                            <h3 class="text-3xl font-serif text-gray-300 group-hover:text-gold transition-colors duration-500 italic tracking-tight">
                                {{ $item->title }}
                            </h3>
                        </div>
                        <div class="flex justify-between items-center text-[10px] uppercase tracking-[0.4em] text-gray-500 pl-0 group-hover:pl-4 transition-all duration-700">
                            <span>By {{ $author }}</span>
                            <span class="text-gold/40 group-hover:text-gold">{{ $dimensions }}</span>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <p class="text-gold/60 font-serif italic text-2xl">No artworks found for this style yet.</p>
                        <a href="{{ route('gallery') }}" class="mt-8 inline-block text-[10px] uppercase tracking-[0.4em] text-gray-400 hover:text-gold transition-colors border-b border-white/10 pb-2">Return to All Collections</a>
                    </div>
                @endforelse