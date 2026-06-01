@foreach($styles as $item)
                <div class="group block relative" data-aos="fade-up">
                    <a href="{{ route('gallery', ['style' => $item['title']]) }}" class="absolute inset-0 z-10" aria-label="View Gallery"></a>

                    <div class="relative flex gap-2 h-[450px] overflow-hidden mb-8 transition-all duration-700 group-hover:shadow-[0_40px_80px_-20px_rgba(0,0,0,1)]">
                        <div class="w-2/3 h-full overflow-hidden bg-zinc-950 grayscale group-hover:grayscale-0 transition-all duration-1000 ease-in-out">
                            <img src="{{ $item['main_img'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover scale-110 group-hover:scale-100 transition-transform duration-1000">
                        </div>
                        
                        <div class="w-1/3 flex flex-col gap-2">
                            <div class="h-1/2 overflow-hidden bg-zinc-950 grayscale group-hover:grayscale-0 transition-all duration-1000 delay-75">
                                <img src="{{ $item['sub_img1'] }}" class="w-full h-full object-cover">
                            </div>
                            <div class="h-1/2 overflow-hidden bg-zinc-950 relative grayscale group-hover:grayscale-0 transition-all duration-1000 delay-150">
                                <img src="{{ $item['sub_img2'] }}" class="w-full h-full object-cover opacity-30 group-hover:opacity-100 transition-opacity">
                                <div class="absolute inset-0 flex items-center justify-center bg-[#0a0a0a]/60 group-hover:bg-transparent transition-all duration-500">
                                    <span class="text-[#c9a74e] font-serif italic text-2xl group-hover:scale-110 transition-transform">+{{ $item['count'] }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="absolute inset-0 border border-[#c9a74e]/0 group-hover:border-[#c9a74e]/20 transition-all duration-700 pointer-events-none"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a]/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>

                    <div class="space-y-4 px-2">
                        <div class="flex items-center gap-4">
                            <div class="h-[1px] w-0 group-hover:w-16 bg-[#c9a74e] transition-all duration-700 ease-out"></div>
                            <h3 class="text-3xl font-serif text-[#d1d5db] group-hover:text-[#c9a74e] transition-colors duration-500 italic tracking-tight">
                                {{ $item['title'] }}
                            </h3>
                        </div>
                        <div class="flex justify-between items-center text-[10px] uppercase tracking-[0.4em] text-zinc-500 pl-0 group-hover:pl-4 transition-all duration-700">
                            
                            <div class="relative z-20 group/author inline-block hover:text-white transition-colors">
                                <span>{{ $item['author'] }}</span>
                            </div>

                            <span class="text-[#c9a74e]/40 group-hover:text-[#c9a74e] transition-colors relative z-20">{{ $item['count'] }} pieces</span>
                        </div>
                    </div>
                </div>
                @endforeach