{{--
    Proteksi yang aktif:
      ✓ Klik-kanan (context menu) diblokir
      ✓ Keyboard shortcuts inspeksi diblokir (F12, Ctrl+Shift+I/J/C/U, Ctrl+S)
      ✓ Drag & drop gambar diblokir
      ✓ Seleksi teks diblokir pada elemen gambar & judul artwork
      ✓ Print screen & Ctrl+P → watermark muncul + overlay
      ✓ Download via <a download> diblokir
      ✓ DevTools detection → watermark muncul + banner peringatan
      ✓ Watermark TERSEMBUNYI saat normal, MUNCUL saat pelanggaran
      ✓ Watermark otomatis hilang setelah 4 detik
    ============================================================
--}}

{{-- OVERLAY: muncul saat PrintScreen / Ctrl+P --}}
<div id="kpk-protect-overlay"
     aria-hidden="true"
     style="
        display:none;
        position:fixed;
        inset:0;
        z-index:2147483647;
        background:rgba(5,5,5,0.97);
        backdrop-filter:blur(20px);
        -webkit-backdrop-filter:blur(20px);
        align-items:center;
        justify-content:center;
        flex-direction:column;
        gap:16px;
     ">
    <svg width="48" height="48" viewBox="0 0 24 24" fill="none"
         stroke="#C9A74E" stroke-width="1" stroke-linecap="round">
        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
    </svg>
    <p style="color:#C9A74E;font-family:'Playfair Display',serif;font-style:italic;
              font-size:1.5rem;letter-spacing:-.02em;margin:0;">
        Content Protected
    </p>
    <p style="color:#666;font-family:'Plus Jakarta Sans',sans-serif;
              font-size:.7rem;letter-spacing:.3em;text-transform:uppercase;margin:0;">
        Kapakapa Art Gallery © {{ date('Y') }}
    </p>
</div>

{{-- DEVTOOLS WARNING BANNER --}}
<div id="kpk-devtools-banner"
     aria-hidden="true"
     style="
        display:none;
        position:fixed;
        bottom:0; left:0; right:0;
        z-index:2147483646;
        background:linear-gradient(90deg,#0a0a0a,#1a1200,#0a0a0a);
        border-top:1px solid rgba(201,167,78,.25);
        padding:12px 24px;
        align-items:center;
        justify-content:center;
        gap:12px;
     ">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
         stroke="#C9A74E" stroke-width="1.5" stroke-linecap="round">
        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
        <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
    </svg>
    <span style="color:#C9A74E;font-family:'Plus Jakarta Sans',sans-serif;
                 font-size:.65rem;letter-spacing:.35em;text-transform:uppercase;">
        Unauthorized inspection of this content is prohibited
    </span>
</div>

<style>
    /* ══════════════════════════════════════════════════
       WATERMARK — TERSEMBUNYI secara default
       Hanya muncul saat JS menambahkan class .kpk-violation
       pada <body> atau container gambar
    ══════════════════════════════════════════════════ */

    .artwork-slide,
    .swiper-slide,
    .artwork-card-container,
    .group > div:has(> img),
    .group > div:has(> .artwork-slider) {
        position: relative !important;
    }

    /* Canvas watermark — tersembunyi saat normal */
    .kpk-wm-canvas {
        position: absolute !important;
        inset: 0 !important;
        width: 100% !important;
        height: 100% !important;
        z-index: 40 !important;
        pointer-events: none !important;
        user-select: none !important;
        -webkit-user-select: none !important;

        /* TERSEMBUNYI by default */
        opacity: 0 !important;
        transition: opacity 0.3s ease !important;
    }

    /* MUNCUL saat body punya class violation */
    body.kpk-violation .kpk-wm-canvas {
        opacity: 1 !important;
    }

    /* CSS ::after watermark — juga tersembunyi by default */
    .artwork-slide::after,
    .swiper-slide::after,
    .artwork-card-container::after {
        content: 'KAPAKAPA ART GALLERY © {{ date('Y') }}  ·  KAPAKAPA ART GALLERY © {{ date('Y') }}  ·  KAPAKAPA ART GALLERY © {{ date('Y') }}';
        position: absolute;
        inset: 0;
        z-index: 50;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: clamp(9px, 1vw, 11px);
        font-weight: 700;
        letter-spacing: .25em;
        text-transform: uppercase;
        white-space: nowrap;
        overflow: hidden;
        transform: rotate(-35deg) scale(2.2);
        transform-origin: center center;
        pointer-events: none !important;
        user-select: none !important;
        -webkit-user-select: none !important;

        /* TERSEMBUNYI by default */
        color: rgba(201, 167, 78, 0) !important;
        transition: color 0.3s ease !important;
    }

    /* MUNCUL saat violation */
    body.kpk-violation .artwork-slide::after,
    body.kpk-violation .swiper-slide::after,
    body.kpk-violation .artwork-card-container::after {
        color: rgba(201, 167, 78, 0.35) !important;
    }

    /* ── Anti-drag gambar ── */
    img {
        -webkit-user-drag: none !important;
        -khtml-user-drag:  none !important;
        -moz-user-drag:    none !important;
        -o-user-drag:      none !important;
        user-drag:         none !important;
        pointer-events:    none !important;
        -webkit-user-select: none !important;
        -moz-user-select:    none !important;
        -ms-user-select:     none !important;
        user-select:         none !important;
    }

    .artwork-slide img,
    .swiper-slide img,
    .artwork-card-container img,
    .group img {
        max-width: 100% !important;
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
    }

    /* ── Anti-select teks karya ── */
    .font-serif,
    [data-aos],
    .group .font-serif {
        -webkit-user-select: none !important;
        -moz-user-select:    none !important;
        -ms-user-select:     none !important;
        user-select:         none !important;
    }

    /* ── Print ── */
    @media print {
        body > *:not(#kpk-protect-overlay) { visibility: hidden !important; }
        #kpk-protect-overlay {
            display: flex !important;
            visibility: visible !important;
        }
    }
</style>

<script>
(function () {
    'use strict';

    var overlay      = document.getElementById('kpk-protect-overlay');
    var banner       = document.getElementById('kpk-devtools-banner');
    var overlayTimer = null;
    var violationTimer = null;

    /* ════════════════════════════════════════════
       CORE: Aktifkan watermark sementara
       Durasi: 4000ms lalu hilang sendiri
       Dipanggil setiap kali ada pelanggaran
    ════════════════════════════════════════════ */
    function triggerViolation() {
        document.body.classList.add('kpk-violation');
        clearTimeout(violationTimer);
        violationTimer = setTimeout(function () {
            document.body.classList.remove('kpk-violation');
        }, 4000);
    }

    /* ════════════════════════════════════════════
       1. KLIK KANAN
    ════════════════════════════════════════════ */
    document.addEventListener('contextmenu', function (e) {
        e.preventDefault();
        e.stopPropagation();
        triggerViolation();
        return false;
    }, true);

    /* ════════════════════════════════════════════
       2. KEYBOARD SHORTCUTS
    ════════════════════════════════════════════ */
    document.addEventListener('keydown', function (e) {
        var k     = e.key ? e.key.toUpperCase() : '';
        var ctrl  = e.ctrlKey || e.metaKey;
        var shift = e.shiftKey;

        // F12
        if (e.keyCode === 123) {
            block(e); triggerViolation(); return;
        }
        // Ctrl+Shift+I/J/C — DevTools
        if (ctrl && shift && (k==='I'||k==='J'||k==='C')) {
            block(e); triggerViolation(); return;
        }
        // Ctrl+U — View Source
        if (ctrl && k === 'U') {
            block(e); triggerViolation(); return;
        }
        // Ctrl+S — Save
        if (ctrl && k === 'S') {
            block(e); triggerViolation(); return;
        }
        // Ctrl+P — Print
        if (ctrl && k === 'P') {
            showOverlay(); triggerViolation(); block(e); return;
        }
        // PrtScr (keyCode 44)
        if (e.keyCode === 44) {
            showOverlay(); triggerViolation(); return;
        }
        // Win+Shift+S (keyCode 83 + metaKey + shift) — Windows Snipping
        if (e.metaKey && shift && k === 'S') {
            showOverlay(); triggerViolation(); block(e); return;
        }
        // Ctrl+A — Select All (di luar input)
        if (ctrl && k === 'A' && !isTypingTarget(e.target)) {
            block(e); triggerViolation(); return;
        }
    }, true);

    function block(e) { e.preventDefault(); e.stopPropagation(); }

    function isTypingTarget(el) {
        return el && (
            el.tagName === 'INPUT'    ||
            el.tagName === 'TEXTAREA' ||
            el.isContentEditable
        );
    }

    /* ════════════════════════════════════════════
       3. DRAG gambar
    ════════════════════════════════════════════ */
    document.addEventListener('dragstart', function (e) {
        if (e.target && e.target.tagName === 'IMG') {
            e.preventDefault();
            triggerViolation();
        }
    }, true);

    /* ════════════════════════════════════════════
       4. PRINT / BEFORE-PRINT
    ════════════════════════════════════════════ */
    if (window.matchMedia) {
        try {
            window.matchMedia('print').addEventListener('change', function (m) {
                if (m.matches) { showOverlay(); triggerViolation(); }
            });
        } catch(e) {
            window.matchMedia('print').addListener(function (m) {
                if (m.matches) { showOverlay(); triggerViolation(); }
            });
        }
    }
    window.addEventListener('beforeprint', function () {
        showOverlay(); triggerViolation();
    }, true);

    /* ════════════════════════════════════════════
       5. DOWNLOAD link
    ════════════════════════════════════════════ */
    document.addEventListener('click', function (e) {
        var el = e.target.closest('a[download]');
        if (el) {
            e.preventDefault();
            e.stopPropagation();
            triggerViolation();
        }
    }, true);

    /* ════════════════════════════════════════════
       6. COPY
    ════════════════════════════════════════════ */
    document.addEventListener('copy', function (e) {
        if (isTypingTarget(document.activeElement)) return;
        e.preventDefault();
        if (e.clipboardData) e.clipboardData.setData('text/plain', '');
        triggerViolation();
    }, true);

    /* ════════════════════════════════════════════
       7. DEVTOOLS SIZE DETECTION
       Saat terbuka → watermark muncul + banner
       Saat tutup   → watermark hilang + banner hilang
    ════════════════════════════════════════════ */
    var devtoolsOpen = false;
    function checkDevtools() {
        var isOpen = (window.outerWidth  - window.innerWidth  > 160) ||
                     (window.outerHeight - window.innerHeight > 160);

        if (isOpen && !devtoolsOpen) {
            devtoolsOpen = true;
            if (banner) banner.style.display = 'flex';
            /* Watermark terus muncul selama DevTools terbuka */
            document.body.classList.add('kpk-violation');
            clearTimeout(violationTimer); // jangan auto-hilang selama devtools masih buka
        } else if (!isOpen && devtoolsOpen) {
            devtoolsOpen = false;
            if (banner) banner.style.display = 'none';
            /* Watermark hilang saat DevTools ditutup */
            document.body.classList.remove('kpk-violation');
        }
    }
    setInterval(checkDevtools, 1000);

    /* ════════════════════════════════════════════
       8. WATERMARK CANVAS — inject ke semua img karya
          Tersembunyi by default via CSS opacity:0
          Muncul otomatis saat body.kpk-violation aktif
    ════════════════════════════════════════════ */
    var WM_TEXT  = 'KAPAKAPA ART GALLERY \u00A9 {{ date('Y') }}';
    var WM_COLOR = 'rgba(201,167,78,0.35)';

    function injectCanvasWatermark(imgEl) {
        var parent = imgEl.parentElement;
        if (!parent) return;
        if (parent.querySelector('.kpk-wm-canvas')) return;

        var pos = window.getComputedStyle(parent).position;
        if (pos === 'static') parent.style.position = 'relative';

        var canvas = document.createElement('canvas');
        canvas.className = 'kpk-wm-canvas';
        parent.appendChild(canvas);

        function drawWatermark() {
            var w = parent.offsetWidth;
            var h = parent.offsetHeight;
            if (!w || !h) return;

            canvas.width  = w;
            canvas.height = h;
            var ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, w, h);

            ctx.save();
            ctx.translate(w / 2, h / 2);
            ctx.rotate(-35 * Math.PI / 180);

            ctx.font         = 'bold ' + Math.max(9, Math.round(w * 0.018)) + 'px "Plus Jakarta Sans", sans-serif';
            ctx.fillStyle    = WM_COLOR;
            ctx.textAlign    = 'center';
            ctx.textBaseline = 'middle';

            var lineH  = Math.max(28, Math.round(h * 0.08));
            var lines  = Math.ceil(h / lineH) + 4;
            var startY = -lineH * Math.ceil(lines / 2);

            for (var i = 0; i < lines; i++) {
                ctx.fillText(
                    WM_TEXT + '   \u00B7   ' + WM_TEXT + '   \u00B7   ' + WM_TEXT,
                    0,
                    startY + i * lineH
                );
            }
            ctx.restore();
        }

        if (imgEl.complete && imgEl.naturalWidth) {
            drawWatermark();
        } else {
            imgEl.addEventListener('load', drawWatermark);
        }

        if (window.ResizeObserver) {
            new ResizeObserver(drawWatermark).observe(parent);
        }
    }

    function watermarkAll() {
        document.querySelectorAll(
            '.swiper-slide img, .artwork-slide img, .artwork-card-container img'
        ).forEach(function (img) {
            injectCanvasWatermark(img);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', watermarkAll);
    } else {
        watermarkAll();
    }

    if (window.MutationObserver) {
        new MutationObserver(function (mutations) {
            mutations.forEach(function (m) {
                m.addedNodes.forEach(function (node) {
                    if (node.nodeType !== 1) return;
                    var imgs = node.tagName === 'IMG'
                        ? [node]
                        : Array.from(node.querySelectorAll('img'));
                    imgs.forEach(function (img) {
                        if (img.closest('.swiper-slide, .artwork-slide, .artwork-card-container')) {
                            injectCanvasWatermark(img);
                        }
                    });
                });
            });
        }).observe(document.body, { childList: true, subtree: true });
    }

    /* ════════════════════════════════════════════
       HELPER: tampilkan overlay sementara
    ════════════════════════════════════════════ */
    function showOverlay() {
        if (!overlay) return;
        overlay.style.display = 'flex';
        clearTimeout(overlayTimer);
        overlayTimer = setTimeout(function () {
            overlay.style.display = 'none';
        }, 2500);
    }

})();
</script>