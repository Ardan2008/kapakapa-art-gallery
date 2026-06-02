@foreach($artworks as $index => $art)
                        @php
                            $artImages = is_array($art->images) ? $art->images : json_decode($art->images, true);
                            $imagesJson = json_encode($artImages);
                        @endphp
                        <a href="javascript:void(0)" 
                        onclick="openModal({{ $imagesJson }}, '{{ addslashes($art->title) }}', '{{ addslashes($art->category) }}', '{{ $art->width }}', '{{ $art->height }}', '{{ $art->unit }}', '{{ $art->certificate_url }}', '{{ $art->price ?? '' }}')"
                        data-aos="fade-up" 
                        data-aos-delay="{{ ($index % 5) * 100 }}"
                        data-aos-duration="1000"
                        class="group cursor-pointer block {{ ($index % 2 == 1) ? 'lg:mt-24' : '' }} smooth-transition">
                            
                            <div class="relative aspect-[10/14] mb-10 bg-zinc-900 border border-white/5 group-hover:border-[#C9A74E]/20 overflow-visible smooth-transition">
                                <div class="absolute -top-4 -left-4 flex items-center gap-3 opacity-0 group-hover:opacity-100 smooth-transition transform translate-y-2 group-hover:translate-y-0 z-10">
                                    <div class="w-1.5 h-1.5 bg-[#C9A74E] rounded-full"></div>
                                    <div class="w-12 h-[0.5px] bg-[#C9A74E]/40"></div>
                                </div>
                                
                                <div class="w-full h-full overflow-hidden relative artwork-card-container">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-60 group-hover:opacity-30 smooth-transition z-20 pointer-events-none"></div>
                                    <div class="artwork-slider h-full">
                                        @php
                                            $artImages = is_array($art->images) ? $art->images : json_decode($art->images, true);
                                            // Take only up to 3 images as per requirement
                                            $displayImages = array_slice($artImages ?? [], 0, 3);
                                        @endphp
                                        @foreach($displayImages as $imgIndex => $imagePath)
                                            <div class="artwork-slide">
                                                <img src="{{ $imagePath }}" 
                                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[1.2s] ease-out" 
                                                    alt="{{ $art->title }}">
                                            </div>
                                        @endforeach
                                        @if(empty($displayImages))
                                            <div class="artwork-slide">
                                                <img src="https://via.placeholder.com/500x700?text=No+Image" class="w-full h-full object-cover">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="px-1 text-center sm:text-left">
                                <h3 class="text-lg md:text-xl font-serif text-gray-300 mb-5 tracking-wide smooth-transition group-hover:text-gold group-hover:italic group-hover:translate-x-2">
                                    {{ $art->title }}
                                </h3>
                                <div class="flex items-center justify-center sm:justify-start gap-3 mb-4">
                                    <div class="w-1 h-1 bg-gold rounded-full opacity-20 group-hover:opacity-100 smooth-transition"></div>
                                    <div class="h-[1px] bg-[#C9A74E]/20 w-8 smooth-transition group-hover:w-16 group-hover:bg-[#C9A74E]/50"></div>
                                </div>
                                <p class="text-[9px] uppercase tracking-[0.6em] text-slate-500 font-medium group-hover:text-slate-200 smooth-transition">
                                    {{ $art->category }} // {{ $art->width && $art->height ? $art->width . ' x ' . $art->height . ' ' . $art->unit : 'No Dimensions' }}
                                </p>
                                @if($art->price)
                                    <p class="text-gold font-serif italic text-base mt-2 tracking-normal normal-case">
                                        $ {{ number_format($art->price, 2, '.', ',') }}
                                    </p>
                                @endif
                            </div>
                        </a>
                    @endforeach