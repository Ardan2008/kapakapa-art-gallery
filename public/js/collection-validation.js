/* ═══════════════════════════════════════════════════════════════
   Kapakapa Art Gallery — Collection Form Validation
   Shared between create-collection and edit-collection views
═══════════════════════════════════════════════════════════════ */

/* ── Inject validation styles ──────────────────────────────── */
(function injectStyles() {
    const style = document.createElement('style');
    style.textContent = `
        /* Field states */
        .kv-field { position: relative; }

        .kv-input-valid {
            border-color: #C9A74E !important;
            box-shadow: 0 0 0 2px rgba(201,167,78,0.08);
        }
        .kv-input-error {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 2px rgba(239,68,68,0.08);
        }

        /* Inline error message */
        .kv-error-msg {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 5px;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #ef4444;
            opacity: 0;
            transform: translateY(-4px);
            transition: opacity 0.2s ease, transform 0.2s ease;
            pointer-events: none;
        }
        .kv-error-msg.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .kv-error-msg::before {
            content: '';
            display: inline-block;
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #ef4444;
            flex-shrink: 0;
        }

        /* Valid tick */
        .kv-valid-tick {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%) scale(0);
            width: 14px;
            height: 14px;
            background: #C9A74E;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.25s cubic-bezier(0.34,1.56,0.64,1);
            pointer-events: none;
            z-index: 2;
        }
        .kv-valid-tick.show { transform: translateY(-50%) scale(1); }
        .kv-valid-tick svg { width: 8px; height: 8px; stroke: #000; stroke-width: 3; }

        /* Artist section progress bar */
        #kv-artist-progress-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        #kv-artist-progress-track {
            flex: 1;
            height: 2px;
            background: rgba(255,255,255,0.06);
            border-radius: 99px;
            overflow: hidden;
        }
        #kv-artist-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #C9A74E, #DBBC6A);
            border-radius: 99px;
            width: 0%;
            transition: width 0.4s cubic-bezier(0.4,0,0.2,1);
        }
        #kv-artist-progress-label {
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #C9A74E;
            white-space: nowrap;
            min-width: 52px;
            text-align: right;
        }

        /* Artwork slide progress dots */
        #kv-slide-dots {
            display: flex;
            gap: 5px;
            align-items: center;
            margin-top: 6px;
        }
        .kv-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            transition: background 0.3s, transform 0.3s;
        }
        .kv-dot.done     { background: #C9A74E; }
        .kv-dot.partial  { background: rgba(201,167,78,0.35); transform: scale(1.2); }
        .kv-dot.error    { background: #ef4444; }
        .kv-dot.active   { transform: scale(1.4); }

        /* Toast notification */
        #kv-toast-container {
            position: fixed;
            bottom: 28px;
            right: 28px;
            z-index: 20000;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }
        .kv-toast {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 18px;
            background: #1e1e1e;
            border: 1px solid rgba(255,255,255,0.07);
            border-left: 3px solid #ef4444;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.5);
            min-width: 260px;
            max-width: 340px;
            pointer-events: all;
            animation: kvToastIn 0.3s cubic-bezier(0.34,1.56,0.64,1) forwards;
        }
        .kv-toast.success { border-left-color: #C9A74E; }
        .kv-toast-icon {
            flex-shrink: 0;
            width: 18px;
            height: 18px;
            margin-top: 1px;
        }
        .kv-toast-body { flex: 1; }
        .kv-toast-title {
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #fff;
            margin-bottom: 2px;
        }
        .kv-toast-desc {
            font-size: 10px;
            color: rgba(255,255,255,0.4);
            font-weight: 600;
            line-height: 1.5;
        }
        .kv-toast-close {
            flex-shrink: 0;
            background: none;
            border: none;
            color: rgba(255,255,255,0.2);
            cursor: pointer;
            font-size: 14px;
            line-height: 1;
            padding: 0;
            transition: color 0.2s;
        }
        .kv-toast-close:hover { color: rgba(255,255,255,0.6); }

        @keyframes kvToastIn {
            from { opacity: 0; transform: translateX(20px) scale(0.95); }
            to   { opacity: 1; transform: translateX(0) scale(1); }
        }
        @keyframes kvToastOut {
            to { opacity: 0; transform: translateX(20px) scale(0.9); }
        }
        .kv-toast.leaving {
            animation: kvToastOut 0.25s ease forwards;
        }

        /* Preview box error state */
        .kv-box-error {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 2px rgba(239,68,68,0.12);
        }

        /* File size error overlay — muncul di dalam box */
        .kv-file-size-overlay {
            position: absolute;
            inset: 0;
            background: rgba(239, 68, 68, 0.92);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            z-index: 10;
            opacity: 0;
            transform: scale(0.92);
            transition: opacity 0.2s ease, transform 0.2s ease;
            pointer-events: none;
            border-radius: inherit;
        }
        .kv-file-size-overlay.visible {
            opacity: 1;
            transform: scale(1);
        }
        .kv-file-size-overlay svg {
            width: 20px;
            height: 20px;
            stroke: #fff;
            stroke-width: 2.5;
            flex-shrink: 0;
        }
        .kv-file-size-overlay span {
            font-size: 7px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #fff;
            text-align: center;
            line-height: 1.4;
            padding: 0 6px;
        }

        /* Submit button shake */
        @keyframes kvShake {
            0%,100% { transform: translateX(0); }
            15%      { transform: translateX(-5px); }
            30%      { transform: translateX(5px); }
            45%      { transform: translateX(-4px); }
            60%      { transform: translateX(4px); }
            75%      { transform: translateX(-2px); }
            90%      { transform: translateX(2px); }
        }
        .kv-shake { animation: kvShake 0.45s ease; }
    `;
    document.head.appendChild(style);
})();

/* ── File size config ───────────────────────────────────────── */
const KV_MAX_FILE_MB    = 5;
const KV_MAX_FILE_BYTES = KV_MAX_FILE_MB * 1024 * 1024;

function kvValidateFileSize(file, boxEl, labelEl) {
    if (!file) return true;
    if (file.size > KV_MAX_FILE_BYTES) {
        const sizeMB = (file.size / 1024 / 1024).toFixed(1);
        if (boxEl) {
            boxEl.classList.add('kv-box-error');
            let overlay = boxEl.querySelector('.kv-file-size-overlay');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.className = 'kv-file-size-overlay';
                overlay.innerHTML =
                    '<svg viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round">'
                    + '<circle cx="12" cy="12" r="10"/>'
                    + '<line x1="12" y1="8" x2="12" y2="12"/>'
                    + '<line x1="12" y1="16" x2="12.01" y2="16"/>'
                    + '</svg>'
                    + '<span>' + sizeMB + ' MB • max ' + KV_MAX_FILE_MB + ' MB</span>';
                boxEl.appendChild(overlay);
            } else {
                overlay.querySelector('span').textContent = sizeMB + ' MB • max ' + KV_MAX_FILE_MB + ' MB';
            }
            requestAnimationFrame(() => overlay.classList.add('visible'));
            clearTimeout(overlay._kvTimer);
            overlay._kvTimer = setTimeout(() => {
                overlay.classList.remove('visible');
                boxEl.classList.remove('kv-box-error');
            }, 3000);
        }
        kvToast('File too large', (labelEl || 'Image') + ' is ' + sizeMB + ' MB — max allowed is ' + KV_MAX_FILE_MB + ' MB.');
        return false;
    }
    /* Clear error jika valid */
    if (boxEl) {
        boxEl.classList.remove('kv-box-error');
        const ov = boxEl.querySelector('.kv-file-size-overlay');
        if (ov) ov.classList.remove('visible');
    }
    return true;
}

/* ── Toast ───────────────────────────────────────────────────── */
(function mountToastContainer() {
    const el = document.createElement('div');
    el.id = 'kv-toast-container';
    document.body.appendChild(el);
})();

function kvToast(title, desc = '', type = 'error') {
    const container = document.getElementById('kv-toast-container');
    const toast = document.createElement('div');
    toast.className = `kv-toast${type === 'success' ? ' success' : ''}`;

    const iconColor = type === 'success' ? '#C9A74E' : '#ef4444';
    const iconPath  = type === 'success'
        ? `<polyline points="20 6 9 17 4 12" />`
        : `<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>`;

    toast.innerHTML = `
        <svg class="kv-toast-icon" viewBox="0 0 24 24" fill="none" stroke="${iconColor}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>${iconPath}
        </svg>
        <div class="kv-toast-body">
            <div class="kv-toast-title">${title}</div>
            ${desc ? `<div class="kv-toast-desc">${desc}</div>` : ''}
        </div>
        <button class="kv-toast-close" onclick="kvDismissToast(this.closest('.kv-toast'))">✕</button>
    `;

    container.appendChild(toast);
    setTimeout(() => kvDismissToast(toast), 4500);
}

function kvDismissToast(toast) {
    if (!toast || toast.classList.contains('leaving')) return;
    toast.classList.add('leaving');
    setTimeout(() => toast.remove(), 250);
}

/* ── Artist progress bar ─────────────────────────────────────── */
function kvMountArtistProgress() {
    const biographyHeading = document.querySelector('h2');
    if (!biographyHeading || document.getElementById('kv-artist-progress-wrap')) return;

    const wrap = document.createElement('div');
    wrap.id = 'kv-artist-progress-wrap';
    wrap.innerHTML = `
        <div id="kv-artist-progress-track">
            <div id="kv-artist-progress-bar"></div>
        </div>
        <div id="kv-artist-progress-label">0 / 5</div>
    `;
    biographyHeading.after(wrap);
}

function kvUpdateArtistProgress() {
    const bar   = document.getElementById('kv-artist-progress-bar');
    const label = document.getElementById('kv-artist-progress-label');
    if (!bar) return;

    const fields = [
        document.querySelector('[name="artist[name]"]'),
        document.querySelector('[name="artist[birthplace]"]'),
        document.querySelector('[name="artist[career]"]'),
        document.querySelector('[name="artist[desc]"]'),
    ];
    const profileDone = (() => {
        const img = document.getElementById('profileDisplay');
        const src = img?.src || '';
        return src && !src.includes('dicebear') && !src.includes('seed=artist');
    })();

    const total = 5;
    let done = profileDone ? 1 : 0;
    fields.forEach(f => { if (f && f.value.trim()) done++; });

    const pct = Math.round((done / total) * 100);
    bar.style.width   = pct + '%';
    label.textContent = `${done} / ${total}`;
    label.style.color = done === total ? '#C9A74E' : 'rgba(201,167,78,0.5)';
}

/* ── Slide dots ─────────────────────────────────────────────── */
function kvMountSlideDots() {
    const counter = document.getElementById('collection-slide-counter');
    if (!counter || document.getElementById('kv-slide-dots')) return;

    const dots = document.createElement('div');
    dots.id = 'kv-slide-dots';
    counter.after(dots);
}

function kvUpdateSlideDots() {
    const dots = document.getElementById('kv-slide-dots');
    if (!dots) return;

    dots.innerHTML = '';
    for (let i = 0; i < totalCollectionSlides; i++) {
        const dot    = document.createElement('div');
        const status = kvGetSlideStatus(i);
        dot.className = `kv-dot ${status}${i === currentCollectionSlide ? ' active' : ''}`;
        dots.appendChild(dot);
    }
}

function kvGetSlideStatus(slideIdx) {
    const slide = document.querySelector(`.collection-slide[data-index="${slideIdx}"]`);
    if (!slide) return '';

    const required = slide.querySelectorAll('[required]');
    const media    = slide.querySelectorAll('[id^="mediaImg_"]');
    const cert     = document.getElementById(`certImg_${slideIdx}`);

    let filled = 0, total = required.length;
    required.forEach(f => { if (f.value?.trim()) filled++; });

    const hasMedia = [...media].some(img => !img.classList.contains('hidden'));
    const hasCert  = cert && !cert.classList.contains('hidden');

    if (filled === 0 && !hasMedia && !hasCert) return '';
    if (filled === total) return 'done';
    if (filled > 0) return 'partial';
    return '';
}

/* ── Field-level validation ─────────────────────────────────── */
function kvValidateField(input, rules = {}) {
    const val   = input.value.trim();
    const isTA  = input.tagName === 'TEXTAREA';
    const isSel = input.tagName === 'SELECT';
    let error   = null;

    if (rules.required && !val) {
        error = rules.requiredMsg || 'This field is required';
    } else if (rules.minLength && val.length < rules.minLength) {
        error = `Min ${rules.minLength} characters`;
    } else if (rules.min !== undefined && parseFloat(val) < rules.min) {
        error = `Min value is ${rules.min}`;
    } else if (rules.pattern && val && !rules.pattern.test(val)) {
        error = rules.patternMsg || 'Invalid format';
    }

    const wrapper = input.closest('.kv-field') || input.parentElement;

    let msg = wrapper.querySelector('.kv-error-msg');
    if (!msg) {
        msg = document.createElement('div');
        msg.className = 'kv-error-msg';
        input.after(msg);
    }

    if (!isTA && !isSel) {
        let tick = wrapper.querySelector('.kv-valid-tick');
        if (!tick) {
            tick = document.createElement('div');
            tick.className = 'kv-valid-tick';
            tick.innerHTML = `<svg viewBox="0 0 12 12" fill="none"><polyline points="2,6 5,9 10,3"/></svg>`;
            input.parentElement.style.position = 'relative';
            input.parentElement.appendChild(tick);
        }
        tick.classList.toggle('show', !error && !!val);
    }

    if (error) {
        input.classList.remove('kv-input-valid');
        input.classList.add('kv-input-error');
        msg.textContent = error;
        msg.classList.add('visible');
    } else {
        input.classList.remove('kv-input-error');
        if (val) input.classList.add('kv-input-valid');
        else     input.classList.remove('kv-input-valid');
        msg.classList.remove('visible');
    }

    return !error;
}

/* ── Attach live listeners to artist fields ─────────────────── */
function kvBindArtistFields() {
    const rules = {
        'artist[name]':       { required: true, minLength: 2, pattern: /^[^0-9]+$/, patternMsg: 'Name cannot contain numbers' },
        'artist[birthplace]': { required: true, minLength: 2 },
        'artist[career]':     { required: true, pattern: /\d/, patternMsg: 'Include a year (e.g. 2015 – Now)' },
        'artist[desc]':       { required: true, minLength: 20, requiredMsg: 'Tell us about the artist' },
    };

    Object.entries(rules).forEach(([name, rule]) => {
        const el = document.querySelector(`[name="${name}"]`);
        if (!el) return;
        el.addEventListener('input',  () => { kvValidateField(el, rule); kvUpdateArtistProgress(); });
        el.addEventListener('change', () => { kvValidateField(el, rule); kvUpdateArtistProgress(); });
    });
}

/* ── Bind artwork slide fields ──────────────────────────────── */
function kvBindSlideFields(slideIdx) {
    const slide = document.querySelector(`.collection-slide[data-index="${slideIdx}"]`);
    if (!slide) return;

    const fieldRules = {
        [`artwork[${slideIdx}][name]`]:      { required: true, minLength: 2 },
        [`artwork[${slideIdx}][desc]`]:      { required: true, minLength: 10 },
        [`artwork[${slideIdx}][painterRef]`]:{ required: true },
        [`artwork[${slideIdx}][style]`]:     { required: true, requiredMsg: 'Choose a style' },
        [`artwork[${slideIdx}][stock]`]:     { required: true, min: 1 },
        [`artwork[${slideIdx}][maxLimit]`]:  { required: true, min: 1 },
        [`artwork[${slideIdx}][salePrice]`]: { required: true, min: 0.01, requiredMsg: 'Set a sale price' },
    };

    Object.entries(fieldRules).forEach(([name, rule]) => {
        const el = slide.querySelector(`[name="${name}"]`);
        if (!el) return;
        el.addEventListener('input',  () => { kvValidateField(el, rule); kvUpdateSlideDots(); });
        el.addEventListener('change', () => { kvValidateField(el, rule); kvUpdateSlideDots(); });
    });
}

/* ── Bind file size checks to slide media/cert inputs ───────── */
/*
 * FIX: Masalah sebelumnya adalah `onchange="previewMedia()"` di HTML
 * dipanggil SEBELUM listener dari addEventListener ini, sehingga gambar
 * sudah ter-render meskipun file melebihi 5MB.
 *
 * Solusi: Kita REPLACE atribut onchange inline dengan removeAttribute,
 * lalu pasang satu listener terpadu yang:
 *   1. Validasi size DULU — jika gagal, reset & stop
 *   2. Jika lolos, baru panggil previewMedia / handleCertUpload
 */
function kvBindSlideFileInputs(slideIdx) {

    /* ── Media slots 0–2 ── */
    [0, 1, 2].forEach(j => {
        const input = document.getElementById(`mediaInput_${slideIdx}_${j}`);
        const box   = document.getElementById(`mediaBox_${slideIdx}_${j}`);
        if (!input) return;

        /* Hapus onchange inline agar tidak double-fire */
        input.removeAttribute('onchange');

        input.addEventListener('change', function () {
            const file = this.files?.[0];
            if (!file) return;

            /* 1. Validasi size — jika gagal, reset input & tampilkan error */
            if (!kvValidateFileSize(file, box, `Media slot ${j + 1}`)) {
                this.value = '';
                return; /* stop — jangan lanjut ke preview */
            }

            /* 2. Lolos validasi — jalankan preview seperti biasa */
            previewMedia(this, slideIdx, j);
        });
    });

    /* ── Certificate slot ── */
    const certInput = document.getElementById(`certInput_${slideIdx}`);
    const certBox   = document.getElementById(`certBox_${slideIdx}`);
    if (!certInput) return;

    /* Hapus onchange inline */
    certInput.removeAttribute('onchange');

    certInput.addEventListener('change', function () {
        const file = this.files?.[0];
        if (!file) return;

        /* 1. Validasi size */
        if (!kvValidateFileSize(file, certBox, 'Certificate')) {
            this.value = '';
            return;
        }

        /* 2. Lolos — jalankan upload handler seperti biasa */
        handleCertUpload(this, slideIdx);
    });
}

/* ── Full form validation (on submit) ───────────────────────── */
function kvValidateAll() {
    let valid      = true;
    let firstError = null;

    /* Artist fields */
    const artistRules = {
        'artist[name]':       { required: true, minLength: 2, pattern: /^[^0-9]+$/, patternMsg: 'Name cannot contain numbers' },
        'artist[birthplace]': { required: true, minLength: 2 },
        'artist[career]':     { required: true, pattern: /\d/, patternMsg: 'Include a year (e.g. 2015 – Now)' },
        'artist[desc]':       { required: true, minLength: 20, requiredMsg: 'Tell us about the artist' },
    };
    Object.entries(artistRules).forEach(([name, rule]) => {
        const el = document.querySelector(`[name="${name}"]`);
        if (el && !kvValidateField(el, rule)) {
            valid = false;
            if (!firstError) firstError = { el, label: name.replace('artist[','').replace(']','') };
        }
    });

    /* Profile photo */
    const profileImg = document.getElementById('profileDisplay');
    const profileSrc = profileImg?.src || '';
    if (!profileSrc || profileSrc.includes('dicebear') || profileSrc.includes('seed=artist')) {
        valid = false;
        if (!firstError) firstError = { el: null, label: 'profile photo' };
    }

    /* Artwork slides */
    const slideErrors = [];
    for (let i = 0; i < totalCollectionSlides; i++) {
        const slide = document.querySelector(`.collection-slide[data-index="${i}"]`);
        if (!slide) continue;

        const slideRules = {
            [`artwork[${i}][name]`]:      { required: true, minLength: 2 },
            [`artwork[${i}][desc]`]:      { required: true, minLength: 10 },
            [`artwork[${i}][painterRef]`]:{ required: true },
            [`artwork[${i}][style]`]:     { required: true, requiredMsg: 'Choose a style' },
            [`artwork[${i}][stock]`]:     { required: true, min: 1 },
            [`artwork[${i}][maxLimit]`]:  { required: true, min: 1 },
            [`artwork[${i}][salePrice]`]: { required: true, min: 0.01, requiredMsg: 'Set a sale price' },
        };

        let slideHasError = false;
        Object.entries(slideRules).forEach(([name, rule]) => {
            const el = slide.querySelector(`[name="${name}"]`);
            if (el && !kvValidateField(el, rule)) {
                valid = false;
                slideHasError = true;
                if (!firstError) firstError = { el, slideIdx: i };
            }
        });

        /* Certificate check */
        const certImg   = document.getElementById(`certImg_${i}`);
        const certBox   = document.getElementById(`certBox_${i}`);
        const certInput = document.getElementById(`certInput_${i}`);
        const hasCert   = (certImg && !certImg.classList.contains('hidden')) ||
                          (certInput && certInput.files?.length > 0);
        if (!hasCert && certBox) {
            certBox.classList.add('kv-box-error');
            valid = false;
            slideHasError = true;
            if (!firstError) firstError = { el: certBox, slideIdx: i, label: 'certificate' };
        } else if (certBox) {
            certBox.classList.remove('kv-box-error');
        }

        if (slideHasError) slideErrors.push(i + 1);
    }

    kvUpdateSlideDots();

    if (!valid) {
        const btn = document.querySelector('[type="submit"]');
        btn?.classList.remove('kv-shake');
        void btn?.offsetWidth;
        btn?.classList.add('kv-shake');
        setTimeout(() => btn?.classList.remove('kv-shake'), 500);

        if (slideErrors.length) {
            kvToast(
                'Incomplete artwork data',
                `Artwork ${slideErrors.join(', ')} ${slideErrors.length > 1 ? 'have' : 'has'} missing fields.`
            );
        } else if (firstError?.label === 'profile photo') {
            kvToast('Profile photo missing', 'Upload a photo for the artist.');
        } else {
            kvToast('Artist info incomplete', 'Fill all required fields in the biography section.');
        }

        if (firstError?.slideIdx !== undefined && firstError.slideIdx !== currentCollectionSlide) {
            navigateCollectionSlide(firstError.slideIdx - currentCollectionSlide);
            setTimeout(() => firstError.el?.focus(), 300);
        } else {
            firstError?.el?.focus();
            firstError?.el?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    return valid;
}

/* ── Hook into updateCollectionSlides ───────────────────────── */
const _origUpdateCollectionSlides = updateCollectionSlides;
updateCollectionSlides = function(qty) {
    _origUpdateCollectionSlides(qty);

    for (let i = 0; i < (parseInt(qty) || 1); i++) {
        kvBindSlideFields(i);
        kvBindSlideFileInputs(i);
    }
    kvUpdateSlideDots();
    kvMountSlideDots();
};

/* ── Hook into handleFormSubmit ─────────────────────────────── */
const _origHandleFormSubmit = handleFormSubmit;
handleFormSubmit = async function(event) {
    event.preventDefault();
    if (!kvValidateAll()) return;
    await _origHandleFormSubmit(event);
};

/* ── Init on DOM ready ──────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
    kvMountArtistProgress();
    kvBindArtistFields();
    kvUpdateArtistProgress();
    kvMountSlideDots();

    /* Profile — size check + progress */
    const profileInput = document.getElementById('profileInput');
    if (profileInput) {
        /* Hapus onchange inline supaya tidak double-fire */
        profileInput.removeAttribute('onchange');

        profileInput.addEventListener('change', function () {
            const file       = this.files?.[0];
            const profileBox = document.getElementById('profileDisplay')?.closest('.group');

            /* 1. Validasi size dulu */
            if (!kvValidateFileSize(file, profileBox, 'Profile photo')) {
                this.value = '';
                document.getElementById('profileDisplay').src =
                    'https://api.dicebear.com/8.x/notionists/svg?seed=artist';
                setTimeout(kvUpdateArtistProgress, 100);
                return; /* stop — jangan lanjut ke preview */
            }

            /* 2. Lolos — jalankan previewProfile seperti biasa */
            previewProfile(this);
            setTimeout(kvUpdateArtistProgress, 100);
        });
    }
});