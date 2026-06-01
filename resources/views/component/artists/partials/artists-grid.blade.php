@foreach($artists as $index => $artist)
                    <a href="{{ route('profile_art', $artist->id) }}" 
                    class="group cursor-pointer block {{ ($index % 5 == 1 || $index % 5 == 3) ? 'lg:mt-16' : '' }}"
                    data-aos="fade-up" 
                    data-aos-delay="{{ ($index % 5) * 100 }}"
                    data-aos-duration="1000">
                        
                        <div class="relative overflow-hidden aspect-[10/14] mb-7 bg-zinc-900 border border-white/5 transition-luxury group-hover:border-gold/50">
                            <img src="{{ $artist->profile_url ? asset($artist->profile_url) : 'https://api.dicebear.com/8.x/notionists/svg?seed=' . urlencode($artist->name) }}" 
                                class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-luxury group-hover:scale-110"
                                alt="{{ $artist->name }}">
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-luxury"></div>
                        </div>

                        <div class="px-1">
                            <h3 class="text-2xl font-serif leading-tight text-gray-300 transition-luxury group-hover:italic group-hover:text-gold">
                                {{ $artist->name }}
                            </h3>
                            
                            <div class="flex items-center gap-4 mt-3">
                                <div class="h-[1px] bg-gold/50 w-10 transition-all group-hover:w-16 group-hover:bg-gold"></div>
                                <p class="text-[9px] uppercase tracking-[0.3em] text-gray-300 font-semibold group-hover:text-slate-300 transition-colors">
                                    {{ $artist->birthplace }}
                                </p>
                            </div>
                        </div>
                    </a>
                @endforeach