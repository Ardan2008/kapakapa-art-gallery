{{-- OVERLAYS --}}

{{-- Fullscreen protection overlay (print / devtools) --}}
<div id="kpk-overlay"
     aria-hidden="true"
     style="display:none;position:fixed;inset:0;z-index:2147483647;
            background:rgba(5,5,5,0.97);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);
            align-items:center;justify-content:center;flex-direction:column;gap:16px;">
    <svg width="48" height="48" viewBox="0 0 24 24" fill="none"
         stroke="#C9A74E" stroke-width="1" stroke-linecap="round">
        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
    </svg>
    <p style="color:#C9A74E;font-family:'Playfair Display',serif;font-style:italic;
              font-size:1.5rem;letter-spacing:-.02em;margin:0;">Content Protected</p>
    <p style="color:#C9A74E;font-family:'Plus Jakarta Sans',sans-serif;font-size:.75rem;
              letter-spacing:.15em;text-transform:uppercase;margin:0;opacity:.7;">
        Screenshot &amp; screen recording tidak diizinkan
    </p>
    <p style="color:#666;font-family:'Plus Jakarta Sans',sans-serif;font-size:.7rem;
              letter-spacing:.3em;text-transform:uppercase;margin:0;">
        Kapakapa Art Gallery &copy; {{ date('Y') }}
    </p>
</div>

{{-- Screenshot detection banner --}}
<div id="kpk-ss-banner"
     aria-hidden="true"
     style="display:none;position:fixed;inset:0;z-index:2147483646;
            background:rgba(0,0,0,0.92);backdrop-filter:blur(30px);-webkit-backdrop-filter:blur(30px);
            align-items:center;justify-content:center;flex-direction:column;gap:20px;">
    <svg width="56" height="56" viewBox="0 0 24 24" fill="none"
         stroke="#C9A74E" stroke-width="1" stroke-linecap="round">
        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
        <line x1="2" y1="2" x2="22" y2="22" stroke="#ff4444" stroke-width="2"/>
    </svg>
    <p style="color:#C9A74E;font-family:'Playfair Display',serif;font-style:italic;
              font-size:1.6rem;letter-spacing:-.02em;margin:0;text-align:center;">
        Screenshot Diblokir
    </p>
    <p style="color:#aaa;font-family:'Plus Jakarta Sans',sans-serif;font-size:.72rem;
              letter-spacing:.25em;text-transform:uppercase;margin:0;
              text-align:center;max-width:320px;line-height:1.8;">
        Pengambilan gambar konten ini tidak diizinkan.<br>
        Semua karya dilindungi hak cipta.
    </p>
    <p style="color:#555;font-family:'Plus Jakarta Sans',sans-serif;font-size:.6rem;
              letter-spacing:.3em;text-transform:uppercase;margin:0;">
        Kapakapa Art Gallery &copy; {{ date('Y') }}
    </p>
</div>

{{-- DevTools warning bar --}}
<div id="kpk-devtools-banner"
     aria-hidden="true"
     style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:2147483645;
            background:linear-gradient(90deg,#0a0a0a,#1a1200,#0a0a0a);
            border-top:1px solid rgba(201,167,78,.25);
            padding:12px 24px;align-items:center;justify-content:center;gap:12px;">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
         stroke="#C9A74E" stroke-width="1.5" stroke-linecap="round">
        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
        <line x1="12" y1="9" x2="12" y2="13"/>
        <line x1="12" y1="17" x2="12.01" y2="17"/>
    </svg>
    <span style="color:#C9A74E;font-family:'Plus Jakarta Sans',sans-serif;
                 font-size:.65rem;letter-spacing:.35em;text-transform:uppercase;">
        Unauthorized inspection of this content is prohibited
    </span>
</div>

<style>
    /* ═══════════════════════════════════════════════════════════
    ARTWORK IMAGE SELECTORS
    Reused across blur rules, watermark, and violation states
    ═══════════════════════════════════════════════════════════ */

    /* Shorthand: all artwork image containers */
    .artwork-slide,
    .swiper-slide,
    .artwork-card-container,
    [class*="swiper"],
    [class*="slide"],
    [class*="artwork"],
    .group > div:has(> img),
    .group > div:has(> .artwork-slider) {
        position: relative !important;
    }

    /* ── Artwork images: blur by default ── */
    .artwork-slide img,
    .swiper-slide img,
    .artwork-card-container img,
    [class*="swiper"] img,
    [class*="slide"] img,
    [class*="artwork"] img,
    .group img {
        filter: blur(18px) brightness(0.6) !important;
        transition: filter 0.4s ease !important;
        max-width: 100% !important;
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
    }

    /* ── Clear blur when user is actively interacting ── */
    body.kpk-active .artwork-slide img,
    body.kpk-active .swiper-slide img,
    body.kpk-active .artwork-card-container img,
    body.kpk-active [class*="swiper"] img,
    body.kpk-active [class*="slide"] img,
    body.kpk-active [class*="artwork"] img,
    body.kpk-active .group img {
        filter: none !important;
    }

    /* ── Hard blur on violation ── */
    body.kpk-violation .artwork-slide img,
    body.kpk-violation .swiper-slide img,
    body.kpk-violation .artwork-card-container img,
    body.kpk-violation [class*="swiper"] img,
    body.kpk-violation [class*="slide"] img,
    body.kpk-violation [class*="artwork"] img,
    body.kpk-violation .group img {
        filter: blur(25px) brightness(0.3) !important;
        transition: filter 0s !important;
    }

    /* ═══════════════════════════════════════════════════════════
    SCREENSHOT STATE — blur entire page instantly
    ═══════════════════════════════════════════════════════════ */
    body.kpk-screenshot > *:not(#kpk-overlay):not(#kpk-ss-banner):not(#kpk-devtools-banner):not(script):not(style) {
        filter: blur(30px) brightness(0.2) !important;
        pointer-events: none !important;
        transition: filter 0s !important;
    }

    /* ═══════════════════════════════════════════════════════════
    GLOBAL ANTI-THEFT
    ═══════════════════════════════════════════════════════════ */

    /* Prevent image drag & selection */
    img {
        -webkit-user-drag: none !important;
        user-drag: none !important;
        pointer-events: none !important;
        -webkit-user-select: none !important;
        user-select: none !important;
    }

    /* Prevent artwork title selection */
    .font-serif,
    [data-aos],
    .group .font-serif {
        -webkit-user-select: none !important;
        user-select: none !important;
    }

    /* Print: show only protection overlay */
    @media print {
        body > *:not(#kpk-overlay) { visibility: hidden !important; }
        #kpk-overlay { display: flex !important; visibility: visible !important; }
    }
</style>

<script>
    (function () {
        'use strict';

        /* ── Element references ── */
        var overlay        = document.getElementById('kpk-overlay');
        var ssBanner       = document.getElementById('kpk-ss-banner');
        var devtoolsBanner = document.getElementById('kpk-devtools-banner');

        /* ── Timers ── */
        var overlayTimer   = null;
        var violationTimer = null;
        var ssBannerTimer  = null;
        var activeTimer    = null;

        /* ════════════════════════════════════════════════════
        ACTIVE STATE
        Images are clear only while the user is interacting.
        Reverts to blur after 8 seconds of inactivity.
        ════════════════════════════════════════════════════ */

        function setActive() {
            if (document.body.classList.contains('kpk-violation')) return;
            if (document.body.classList.contains('kpk-screenshot')) return;
            document.body.classList.add('kpk-active');
            clearTimeout(activeTimer);
            activeTimer = setTimeout(function () {
                document.body.classList.remove('kpk-active');
            }, 8000);
        }

        function setInactive() {
            clearTimeout(activeTimer);
            document.body.classList.remove('kpk-active');
        }

        ['mousemove', 'mouseenter', 'scroll', 'touchstart', 'touchmove', 'click'].forEach(function (evt) {
            document.addEventListener(evt, setActive, { passive: true });
        });

        /* ════════════════════════════════════════════════════
        VIOLATION  — watermark visible for 4 seconds
        ════════════════════════════════════════════════════ */

        function triggerViolation() {
            setInactive();
            document.body.classList.add('kpk-violation');
            clearTimeout(violationTimer);
            violationTimer = setTimeout(function () {
                document.body.classList.remove('kpk-violation');
            }, 4000);
        }

        /* ════════════════════════════════════════════════════
        SCREENSHOT PROTECTION
        Blurs the entire page instantly, shows banner 3 sec.
        ════════════════════════════════════════════════════ */

        function triggerScreenshot() {
            setInactive();
            document.body.classList.add('kpk-screenshot', 'kpk-violation');
            if (ssBanner) ssBanner.style.display = 'flex';
            clearTimeout(ssBannerTimer);
            ssBannerTimer = setTimeout(function () {
                document.body.classList.remove('kpk-screenshot', 'kpk-violation');
                if (ssBanner) ssBanner.style.display = 'none';
            }, 3000);
        }

        function showOverlay() {
            if (!overlay) return;
            overlay.style.display = 'flex';
            clearTimeout(overlayTimer);
            overlayTimer = setTimeout(function () {
                overlay.style.display = 'none';
            }, 2500);
        }

        /* ════════════════════════════════════════════════════
        1. CONTEXT MENU
        ════════════════════════════════════════════════════ */

        document.addEventListener('contextmenu', function (e) {
            e.preventDefault();
            e.stopPropagation();
            triggerViolation();
            return false;
        }, true);

        /* ════════════════════════════════════════════════════
        2. KEYBOARD SHORTCUTS
        ════════════════════════════════════════════════════ */

        function isTypingTarget(el) {
            return el && (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA' || el.isContentEditable);
        }

        function block(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        document.addEventListener('keydown', function (e) {
            var k     = e.key ? e.key.toUpperCase() : '';
            var ctrl  = e.ctrlKey;
            var meta  = e.metaKey;
            var shift = e.shiftKey;
            var alt   = e.altKey;
            var code  = e.keyCode;

            /* DevTools / source inspection */
            if (code === 123)                                       { block(e); triggerViolation(); return; } // F12
            if (ctrl && shift && (k==='I'||k==='J'||k==='C'))      { block(e); triggerViolation(); return; } // Ctrl+Shift+I/J/C
            if (ctrl && k === 'U')                                  { block(e); triggerViolation(); return; } // Ctrl+U
            if (ctrl && k === 'S')                                  { block(e); triggerViolation(); return; } // Ctrl+S
            if (ctrl && k === 'A' && !isTypingTarget(e.target))    { block(e); triggerViolation(); return; } // Ctrl+A
            if (ctrl && k === 'P')                                  { block(e); showOverlay(); triggerViolation(); return; } // Ctrl+P

            /* Screenshot — Windows/Linux (PrtScr = keyCode 44) */
            if (code === 44 || (alt && code === 44) || (meta && code === 44) ||
                (ctrl && code === 44) || (shift && code === 44))   { block(e); triggerScreenshot(); return; }

            /* Screenshot — Windows Snipping Tool */
            if (meta && shift && k === 'S')                        { block(e); triggerScreenshot(); return; }

            /* Screenshot — macOS */
            if (meta && shift && (k==='3'||k==='4'||k==='5'||k==='6'))  { block(e); triggerScreenshot(); return; }
            if (meta && ctrl  && shift && (k==='3'||k==='4'))            { block(e); triggerScreenshot(); return; }

        }, true);

        /* keyup: PrtScr often only fires reliably on keyup */
        document.addEventListener('keyup', function (e) {
            var k = e.key ? e.key.toUpperCase() : '';
            if (e.keyCode === 44)                                          { triggerScreenshot(); }
            if (e.metaKey && e.shiftKey && (k==='3'||k==='4'||k==='5'))  { triggerScreenshot(); }
        }, true);

        /* ════════════════════════════════════════════════════
        3. IMAGE DRAG
        ════════════════════════════════════════════════════ */

        document.addEventListener('dragstart', function (e) {
            if (e.target && e.target.tagName === 'IMG') {
                e.preventDefault();
                triggerViolation();
            }
        }, true);

        /* ════════════════════════════════════════════════════
        4. PRINT
        ════════════════════════════════════════════════════ */

        window.addEventListener('beforeprint', function () {
            showOverlay();
            triggerViolation();
        }, true);

        if (window.matchMedia) {
            var printMQ = window.matchMedia('print');
            var onPrint = function (m) { if (m.matches) { showOverlay(); triggerViolation(); } };
            try   { printMQ.addEventListener('change', onPrint); }
            catch (_) { printMQ.addListener(onPrint); }
        }

        /* ════════════════════════════════════════════════════
        5. DOWNLOAD LINKS
        ════════════════════════════════════════════════════ */

        document.addEventListener('click', function (e) {
            var el = e.target.closest('a[download]');
            if (el) {
                e.preventDefault();
                e.stopPropagation();
                triggerViolation();
            }
        }, true);

        /* ════════════════════════════════════════════════════
        6. COPY
        ════════════════════════════════════════════════════ */

        document.addEventListener('copy', function (e) {
            if (isTypingTarget(document.activeElement)) return;
            e.preventDefault();
            if (e.clipboardData) e.clipboardData.setData('text/plain', '');
            triggerViolation();
        }, true);

        /* ════════════════════════════════════════════════════
        7. DEVTOOLS DETECTION (window size heuristic)
        ════════════════════════════════════════════════════ */

        var devtoolsOpen = false;

        setInterval(function () {
            var isOpen = (window.outerWidth  - window.innerWidth  > 160) ||
                        (window.outerHeight - window.innerHeight > 160);

            if (isOpen && !devtoolsOpen) {
                devtoolsOpen = true;
                if (devtoolsBanner) devtoolsBanner.style.display = 'flex';
                setInactive();
                document.body.classList.add('kpk-violation');
                clearTimeout(violationTimer);
            } else if (!isOpen && devtoolsOpen) {
                devtoolsOpen = false;
                if (devtoolsBanner) devtoolsBanner.style.display = 'none';
                document.body.classList.remove('kpk-violation');
            }
        }, 1000);

        /* ════════════════════════════════════════════════════
        8. WINDOW / TAB FOCUS
        Window blur fires instantly — key defence against
        Win+Shift+S (Snipping Tool steals focus immediately).
        ════════════════════════════════════════════════════ */

        window.addEventListener('blur', function () {
            setInactive();
            document.body.classList.add('kpk-screenshot');
        });

        window.addEventListener('focus', function () {
            setTimeout(function () {
                if (!document.body.classList.contains('kpk-violation')) {
                    document.body.classList.remove('kpk-screenshot');
                }
            }, 800);
        });

        document.addEventListener('visibilitychange', function () {
            if (document.visibilityState === 'hidden') {
                setInactive();
                document.body.classList.add('kpk-screenshot');
            } else {
                setTimeout(function () {
                    if (!document.body.classList.contains('kpk-violation')) {
                        document.body.classList.remove('kpk-screenshot');
                    }
                }, 800);
            }
        });

        /* ════════════════════════════════════════════════════
        9. FULLSCREEN CHANGE
        ════════════════════════════════════════════════════ */

        function onFullscreenChange() {
            var active = document.fullscreenElement      ||
                        document.webkitFullscreenElement ||
                        document.mozFullScreenElement    ||
                        document.msFullscreenElement;
            if (!active) triggerViolation();
        }

        ['fullscreenchange', 'webkitfullscreenchange', 'mozfullscreenchange', 'MSFullscreenChange']
            .forEach(function (evt) { document.addEventListener(evt, onFullscreenChange); });
    })();
</script>