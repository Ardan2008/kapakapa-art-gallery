@php $googleUser = session('google_user'); @endphp

@if ($googleUser)
    {{-- ── Logged-in: avatar + dropdown ── --}}
    <div class="relative" x-data="{ open: false }" @click.outside="open = false">

        <button @click="open = !open"
                class="flex items-center gap-2.5 group focus:outline-none">
            @if ($googleUser['avatar'])
                <img src="{{ $googleUser['avatar'] }}"
                     alt="{{ $googleUser['name'] }}"
                     class="w-8 h-8 rounded-full border border-gold/30 object-cover
                            group-hover:border-gold transition-colors duration-300">
            @else
                <div class="w-8 h-8 rounded-full border border-gold/30 bg-gold/10
                            flex items-center justify-center text-[10px] font-bold text-gold
                            group-hover:border-gold transition-colors duration-300">
                    {{ strtoupper(substr($googleUser['name'], 0, 2)) }}
                </div>
            @endif
            <span class="hidden md:block text-[10px] uppercase tracking-[0.2em] text-zinc-400
                         group-hover:text-white transition-colors duration-300">
                {{ explode(' ', $googleUser['name'])[0] }}
            </span>
            <svg class="w-3 h-3 text-zinc-600 transition-transform duration-300"
                 :class="open ? 'rotate-180' : ''"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        {{-- Dropdown --}}
        <div x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-1"
             class="absolute right-0 top-full mt-3 w-56 bg-zinc-900 border border-white/10
                    shadow-2xl rounded-xl overflow-hidden z-50"
             style="display:none">

            {{-- Profile info --}}
            <div class="px-4 py-4 border-b border-white/5">
                <p class="text-white text-[12px] font-medium truncate">{{ $googleUser['name'] }}</p>
                <p class="text-zinc-500 text-[10px] truncate mt-0.5">{{ $googleUser['email'] }}</p>
            </div>

            {{-- Sign out --}}
            <form method="POST" action="{{ route('auth.google.logout') }}" class="m-0">
                @csrf
                <input type="hidden" name="redirect" value="{{ url()->current() }}">
                <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-3 text-left
                               text-[10px] uppercase tracking-[0.2em] text-zinc-400
                               hover:text-rose-400 hover:bg-white/[0.03]
                               transition-all duration-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Sign out
                </button>
            </form>
        </div>
    </div>

@else
    {{-- ── Not logged-in: Sign in button ── --}}
    <a href="{{ route('auth.google') }}?redirect={{ urlencode(url()->current()) }}"
       class="group flex items-center gap-2.5 border border-white/10 rounded-full px-4 py-2
              hover:border-gold/40 hover:bg-white/[0.03] transition-all duration-500 cursor-pointer">
        <svg class="w-3.5 h-3.5 flex-shrink-0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
        <span class="text-[10px] uppercase tracking-[0.2em] text-zinc-400
                     group-hover:text-white transition-colors duration-300">
            Sign in
        </span>
    </a>
@endif