<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* ── SweetAlert2 Gold Theme ── */
    .swal-gold-popup {
        border: 1px solid rgba(201,167,78,0.15) !important;
        border-radius: 16px !important;
        box-shadow: 0 32px 64px rgba(0,0,0,0.8) !important;
        padding: 2rem 1.5rem !important;
    }

    .swal-gold-confirm {
        background: #C9A74E !important;
        color: #000 !important;
        font-size: 10px !important;
        font-weight: 600 !important;
        letter-spacing: 0.2em !important;
        text-transform: uppercase !important;
        padding: 12px 32px !important;       /* ← lebih longgar */
        border-radius: 999px !important;
        border: none !important;
        cursor: pointer !important;
        transition: background 0.2s !important;
        min-width: 110px !important;          /* ← lebar minimum */
    }
    .swal-gold-confirm:hover { background: rgba(201,167,78,0.8) !important; }

    .swal-gold-cancel {
        background: transparent !important;
        color: #71717a !important;
        font-size: 10px !important;
        font-weight: 500 !important;
        letter-spacing: 0.2em !important;
        text-transform: uppercase !important;
        padding: 12px 32px !important;       /* ← lebih longgar */
        border-radius: 999px !important;
        border: 1px solid rgba(255,255,255,0.08) !important;
        cursor: pointer !important;
        transition: all 0.2s !important;
        min-width: 110px !important;          /* ← lebar minimum */
    }
    .swal-gold-cancel:hover { color: #e4e4e7 !important; border-color: rgba(255,255,255,0.2) !important; }

    /* Warning icon → gold */
    .swal-gold-icon.swal2-warning {
        border-color: #C9A74E !important;
        color: #C9A74E !important;
    }

    /* Success icon → gold */
    .swal-gold-success-icon.swal2-success {
        border-color: #C9A74E !important;
        color: #C9A74E !important;
    }
    .swal-gold-success-icon.swal2-success [class^=swal2-success-line] {
        background-color: #C9A74E !important;
    }
    .swal-gold-success-icon.swal2-success .swal2-success-ring {
        border-color: rgba(201,167,78,0.3) !important;
    }

    /* Progress bar → gold */
    .swal-gold-progress {
        background: #C9A74E !important;
    }

    /* Jarak antar button */
    .swal-gold-actions {
        gap: 12px !important;
        margin-top: 1.75rem !important;
    }

    /* Pastikan SweetAlert2 muncul di atas commentOverlay (z-[200]) */
    .swal-above-overlay {
        z-index: 9999 !important;
    }

    /* Backdrop Swal tidak menutup overlay comment */
    .swal2-backdrop-show {
        z-index: 9998 !important;
    }
</style>

@php $googleUser = session('google_user'); @endphp

    <div id="commentOverlay"
    class="fixed inset-0 z-[200] invisible opacity-0 transition-all duration-500 ease-in-out flex items-center justify-center"
    data-open="false">

    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

    <div id="commentContent"
        class="relative w-full max-w-[420px] max-h-[85vh] bg-zinc-950 border border-white/5
                rounded-lg flex flex-col shadow-2xl scale-95 transition-transform duration-500 ease-in-out"
        style="box-shadow: 0 32px 64px rgba(0,0,0,0.7);">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-white/5 flex-shrink-0">
            <div class="flex items-center gap-3">
                <span class="h-[1px] w-5 bg-gold/50"></span>
                <h3 class="text-white text-sm font-medium tracking-[0.15em] uppercase">Notes</h3>
                <span id="commentCountBadge"
                    class="text-gold text-[10px] font-serif italic transition-transform duration-300">…</span>
            </div>
            <button onclick="toggleCommentModal()"
                    class="group w-8 h-8 flex items-center justify-center rounded-full border border-white/10
                        hover:border-gold/40 transition-all duration-300">
                <svg class="w-4 h-4 text-zinc-500 group-hover:text-gold group-hover:rotate-90 transition-all duration-300"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 6l12 12M6 18L18 6"/>
                </svg>
            </button>
        </div>

        {{-- Comment List --}}
        <div id="commentList"
            class="flex-1 overflow-y-auto px-5 py-4 space-y-1 custom-scrollbar">

            <div id="commentLoading" class="flex flex-col items-center justify-center py-16 gap-4">
                <div class="w-8 h-8 border-2 border-gold/20 border-t-gold rounded-full animate-spin"></div>
                <span class="text-zinc-600 text-[10px] uppercase tracking-[0.3em]">Loading notes…</span>
            </div>

            <div id="commentEmptyState" class="hidden flex-col items-center justify-center py-16 gap-3 text-center">
                <div class="w-12 h-12 rounded-full border border-white/5 flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <p class="text-zinc-600 text-xs font-light italic">Be the first to leave a note.</p>
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex-shrink-0 border-t border-white/5">

            @if ($googleUser)
                <div class="flex items-center justify-between px-5 py-3 bg-white/[0.02] border-b border-white/5">
                    <div class="flex items-center gap-3">
                        @if ($googleUser['avatar'])
                            <img src="{{ $googleUser['avatar'] }}" alt="{{ $googleUser['name'] }}"
                                class="w-7 h-7 rounded-full border border-gold/20 object-cover">
                        @else
                            <div class="w-7 h-7 rounded-full border border-gold/20 bg-gold/10
                                        flex items-center justify-center text-[10px] font-bold text-gold">
                                {{ strtoupper(substr($googleUser['name'], 0, 2)) }}
                            </div>
                        @endif
                        <div class="flex flex-col leading-tight">
                            <span class="text-white text-[11px] font-medium">{{ $googleUser['name'] }}</span>
                            <span class="text-zinc-600 text-[9px] uppercase tracking-[0.2em]">{{ $googleUser['email'] }}</span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('auth.google.logout') }}" class="m-0">
                        @csrf
                        <input type="hidden" name="redirect" id="logoutRedirect" value="{{ url()->current() }}">
                        <input type="hidden" name="reopen_artwork_id" id="logoutArtworkId" value="">
                        <button type="submit"
                                class="group flex items-center gap-1.5 text-[9px] uppercase tracking-[0.2em]
                                    text-zinc-600 hover:text-rose-400 transition-colors duration-300 px-2 py-1.5"
                                onclick="
                                    const id = _currentCommentArtworkId;
                                    document.getElementById('logoutArtworkId').value = id;
                                    document.getElementById('logoutRedirect').value = window.location.pathname + window.location.search + (id ? '#artwork=' + id : '');
                                ">
                            <svg class="w-3 h-3 group-hover:rotate-12 transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Sign out
                        </button>
                    </form>
                </div>

                <div class="px-4 py-3">
                    <div id="stickerPanel" class="hidden mb-2 bg-zinc-900 border border-white/8 rounded-2xl overflow-hidden">
                        <div class="flex border-b border-white/5">
                            <button onclick="_switchTab('sticker')" id="tabSticker"
                                    class="flex-1 py-2.5 flex items-center justify-center gap-1.5 text-[10px] uppercase tracking-[0.2em] text-gold border-b-2 border-gold transition-all">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="M8 9.5 C8 8.7 8.7 8 9.5 8 C10.3 8 11 8.7 11 9.5"/>
                                    <path d="M13 9.5 C13 8.7 13.7 8 14.5 8 C15.3 8 16 8.7 16 9.5"/>
                                    <path d="M8.5 15 Q12 18 15.5 15"/>
                                    <path d="M12 2 C12 2 15 5 19 5" stroke-width="1.2" opacity="0.4"/>
                                </svg>
                                Stickers
                            </button>
                            <button onclick="_switchTab('gif')" id="tabGif"
                                    class="flex-1 py-2.5 flex items-center justify-center gap-1.5 text-[10px] uppercase tracking-[0.2em] text-zinc-500 border-b-2 border-transparent hover:text-zinc-300 transition-all">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="6" width="20" height="12" rx="3"/>
                                    <path d="M9 12 H6 M6 9.5 V14.5"/>
                                    <path d="M12 9.5 V14.5"/>
                                    <path d="M22 9 L17 12 L22 15"/>
                                </svg>
                                GIF
                            </button>
                        </div>
                        <div id="panelSticker" class="p-3">
                            <input id="stickerSearch" type="text" placeholder="Search stickers..."
                                class="w-full bg-white/[0.04] border border-white/8 rounded-lg px-3 py-2 text-[11px] text-zinc-300 placeholder:text-zinc-600 focus:outline-none focus:border-gold/30 mb-3 transition-all">
                            <div id="stickerGrid" class="grid grid-cols-4 gap-1 max-h-[160px] overflow-y-auto custom-scrollbar"></div>
                        </div>
                        <div id="panelGif" class="hidden p-3">
                            <input id="gifSearch" type="text" placeholder="Search GIFs..."
                                class="w-full bg-white/[0.04] border border-white/8 rounded-lg px-3 py-2 text-[11px] text-zinc-300 placeholder:text-zinc-600 focus:outline-none focus:border-gold/30 mb-3 transition-all">
                            <div id="gifGrid" class="grid grid-cols-3 gap-1.5 max-h-[160px] overflow-y-auto custom-scrollbar"></div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 bg-white/[0.04] border border-white/8 rounded-full px-3 py-2 backdrop-blur-sm focus-within:border-gold/30 transition-all duration-300">
                        @if ($googleUser['avatar'])
                            <img src="{{ $googleUser['avatar'] }}"
                                class="w-7 h-7 rounded-full border border-gold/20 flex-shrink-0 object-cover" alt="">
                        @else
                            <div class="w-7 h-7 rounded-full bg-gold/10 border border-gold/20
                                        flex items-center justify-center text-[9px] font-bold text-gold flex-shrink-0">
                                {{ strtoupper(substr($googleUser['name'], 0, 2)) }}
                            </div>
                        @endif

                        <button onclick="_toggleStickerPanel()"
                                class="flex-shrink-0 text-zinc-500 hover:text-gold transition-colors">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M7 2h10a5 5 0 015 5v10a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5z"/>
                                <path d="M10.5 2c0 0 1.5-1.5 3 0" stroke-width="1.2"/>
                                <path d="M8.5 10.5 L10.5 10.5" stroke-width="1.8"/>
                                <circle cx="15" cy="10.5" r="1" fill="currentColor" stroke="none"/>
                                <path d="M9 14.5 Q12 17 15 14.5"/>
                            </svg>
                        </button>

                        <textarea id="commentInput" maxlength="280" rows="1" placeholder="Leave a note…"
                                class="flex-1 bg-transparent text-[12px] text-zinc-300 placeholder:text-zinc-600
                                        focus:outline-none resize-none leading-none custom-scrollbar"
                                style="min-height:20px; max-height:80px; padding-top:2px;"
                                oninput="this.style.height='auto'; this.style.height=Math.min(this.scrollHeight,80)+'px'"></textarea>

                        <button onclick="submitComment()"
                                class="group w-8 h-8 flex items-center justify-center flex-shrink-0
                                    rounded-full bg-gold hover:bg-gold/80 transition-all duration-300">
                            <svg id="sendIcon" class="w-4 h-4 text-black" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                            </svg>
                            <svg id="sendSpinner" class="hidden w-4 h-4 text-black animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-20"/>
                                <path fill="currentColor" class="opacity-60" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                        </button>
                    </div>
                </div>

            @else
                <div class="px-5 py-6 flex flex-col items-center text-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-white/[0.03] border border-white/8
                                flex items-center justify-center mb-1">
                        <svg class="w-5 h-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-zinc-300 text-[11px] font-medium tracking-wide mb-1">Sign in to leave a note</p>
                        <p class="text-zinc-600 text-[10px] font-light">Join the conversation with your Google account.</p>
                    </div>
                    <a id="googleSignInBtn"
                    href="{{ route('auth.google') }}?redirect={{ urlencode(url()->current()) }}"
                    onclick="
                        const base = '{{ route('auth.google') }}';
                        const redirect = encodeURIComponent(window.location.href + '#artwork=' + (_currentCommentArtworkId || ''));
                        this.href = base + '?redirect=' + redirect;
                    "
                    class="group flex items-center gap-3 w-full justify-center
                            bg-white/[0.04] border border-white/10 rounded-xl px-5 py-3
                            hover:border-gold/30 hover:bg-white/[0.07] transition-all duration-500">
                        <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        <span class="text-[11px] text-zinc-300 group-hover:text-white tracking-[0.15em] uppercase font-medium transition-colors duration-300">
                            Continue with Google
                        </span>
                    </a>
                </div>
            @endif

        </div>
    </div>

    <script>
        window._GIPHY_KEY = "{{ config('services.giphy.key') }}";
    </script>
</div>