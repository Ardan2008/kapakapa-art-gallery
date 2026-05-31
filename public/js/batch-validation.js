/* ═══════════════════════════════════════════════════════════════
   Kapakapa Art Gallery — Batch Art Upload Validation
   Handles multi-step form inside #multiFormModal
═══════════════════════════════════════════════════════════════ */

/* ── Inject styles ──────────────────────────────────────────── */
(function bvInjectStyles() {
    const s = document.createElement('style');
    s.textContent = `

        /* ── Field wrapper ── */
        .bv-field { position: relative; }

        /* ── Input states ── */
        .bv-input-valid {
            border-color: #C9A74E !important;
            box-shadow: 0 0 0 2px rgba(201,167,78,0.08) !important;
        }
        .bv-input-error {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 2px rgba(239,68,68,0.10) !important;
        }

        /* ── Inline error message ── */
        .bv-error-msg {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 6px;
            font-size: 9px;
            font-weight: 900;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #ef4444;
            opacity: 0;
            transform: translateY(-4px);
            transition: opacity 0.2s, transform 0.2s;
            pointer-events: none;
            line-height: 1;
        }
        .bv-error-msg.visible { opacity: 1; transform: translateY(0); }
        .bv-error-msg::before {
            content: '';
            width: 4px; height: 4px;
            border-radius: 50%;
            background: #ef4444;
            flex-shrink: 0;
        }

        /* ── Valid tick badge ── */
        .bv-valid-tick {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%) scale(0);
            width: 18px; height: 18px;
            background: #C9A74E;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            transition: transform 0.25s cubic-bezier(0.34,1.56,0.64,1);
            pointer-events: none;
            z-index: 3;
        }
        .bv-valid-tick.show { transform: translateY(-50%) scale(1); }
        .bv-valid-tick svg { width: 9px; height: 9px; stroke: #000; stroke-width: 3; }

        /* ── Textarea char counter ── */
        .bv-char-counter {
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.15);
            text-align: right;
            margin-top: 4px;
            transition: color 0.2s;
        }
        .bv-char-counter.ok  { color: #C9A74E; }
        .bv-char-counter.err { color: #ef4444; }

        /* ── Step dots in header ── */
        #bv-step-dots {
            display: flex;
            gap: 6px;
            align-items: center;
            margin-top: 6px;
        }
        .bv-step-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
            transition: background 0.3s, transform 0.3s, width 0.3s;
        }
        .bv-step-dot.active  { background: #C9A74E; width: 18px; border-radius: 3px; }
        .bv-step-dot.done    { background: #C9A74E; opacity: 0.5; }
        .bv-step-dot.error   { background: #ef4444; }

        /* ── Next/Submit button shake ── */
        @keyframes bvShake {
            0%,100% { transform: translateX(0) scale(1); }
            15%      { transform: translateX(-6px) scale(0.98); }
            30%      { transform: translateX(6px) scale(0.98); }
            45%      { transform: translateX(-4px); }
            60%      { transform: translateX(4px); }
            75%      { transform: translateX(-2px); }
            90%      { transform: translateX(2px); }
        }
        .bv-shake { animation: bvShake 0.45s ease; }

        /* ── Slide entrance animation ── */
        @keyframes bvSlideIn {
            from { opacity: 0; transform: translateX(18px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        .bv-slide-enter { animation: bvSlideIn 0.25s cubic-bezier(0.4,0,0.2,1) forwards; }

        /* ── File upload overlay error ── */
        .bv-file-overlay {
            position: absolute;
            inset: 0;
            background: rgba(239,68,68,0.93);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            z-index: 10;
            border-radius: inherit;
            opacity: 0;
            transform: scale(0.9);
            transition: opacity 0.2s, transform 0.2s;
            pointer-events: none;
        }
        .bv-file-overlay.visible { opacity: 1; transform: scale(1); }
        .bv-file-overlay svg { width: 22px; height: 22px; stroke: #fff; stroke-width: 2.5; }
        .bv-file-overlay span {
            font-size: 8px; font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #fff;
            text-align: center;
            padding: 0 8px;
        }

        /* ── Certificate upload box error ── */
        .bv-cert-error {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 2px rgba(239,68,68,0.12) !important;
        }

        /* ── Toast ── */
        #bv-toast-wrap {
            position: fixed;
            bottom: 28px; right: 28px;
            z-index: 30000;
            display: flex; flex-direction: column; gap: 10px;
            pointer-events: none;
        }
        .bv-toast {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 18px;
            background: #1e1e1e;
            border: 1px solid rgba(255,255,255,0.07);
            border-left: 3px solid #ef4444;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.55);
            min-width: 260px; max-width: 340px;
            pointer-events: all;
            animation: bvToastIn 0.3s cubic-bezier(0.34,1.56,0.64,1) forwards;
        }
        .bv-toast.success { border-left-color: #C9A74E; }
        .bv-toast-icon { flex-shrink: 0; width: 18px; height: 18px; margin-top: 1px; }
        .bv-toast-body { flex: 1; }
        .bv-toast-title {
            font-size: 10px; font-weight: 900;
            text-transform: uppercase; letter-spacing: 0.1em;
            color: #fff; margin-bottom: 2px;
        }
        .bv-toast-desc {
            font-size: 10px; color: rgba(255,255,255,0.4);
            font-weight: 600; line-height: 1.5;
        }
        .bv-toast-close {
            flex-shrink: 0; background: none; border: none;
            color: rgba(255,255,255,0.2); cursor: pointer;
            font-size: 14px; line-height: 1; padding: 0;
            transition: color 0.2s;
        }
        .bv-toast-close:hover { color: rgba(255,255,255,0.6); }

        @keyframes bvToastIn {
            from { opacity: 0; transform: translateX(20px) scale(0.95); }
            to   { opacity: 1; transform: translateX(0) scale(1); }
        }
        @keyframes bvToastOut {
            to { opacity: 0; transform: translateX(20px) scale(0.9); }
        }
        .bv-toast.leaving { animation: bvToastOut 0.25s ease forwards; }

        /* ── Section label "required" asterisk ── */
        .bv-required-label::after {
            content: ' *';
            color: #ef4444;
            font-size: 9px;
        }

        /* ── Progress bar flash on error ── */
        @keyframes bvBarFlash {
            0%,100% { opacity: 1; }
            40%      { opacity: 0.3; }
        }
        .bv-bar-flash { animation: bvBarFlash 0.4s ease; }
    `;
    document.head.appendChild(s);
})();

/* ── Toast container ────────────────────────────────────────── */
(function bvMountToast() {
    if (document.getElementById('bv-toast-wrap')) return;
    const wrap = document.createElement('div');
    wrap.id = 'bv-toast-wrap';
    document.body.appendChild(wrap);
})();

/* ── Constants ──────────────────────────────────────────────── */
const BV_MAX_MB    = 5;
const BV_MAX_BYTES = BV_MAX_MB * 1024 * 1024;

/* ── Validation rules per section ──────────────────────────── */
const BV_RULES = {
    0: { /* Art Identification */
        title: { required: true, minLength: 2, pattern: /^[^0-9]+$/, patternMsg: 'Art name cannot contain numbers', label: 'Art Name' },
        desc:  { required: true, minLength: 15, label: 'Art Description' },
    },
    1: { /* Product Detail */
        painterRef: { required: true, label: 'Painter Reference' },
        style:      { required: true, label: 'Art Style' },
        stock:      { required: true, min: 1, label: 'Stock' },
        maxLimit:   { required: true, min: 1, label: 'Max Limit' },
    },
    2: { /* Pricing */
        basePrice:  { required: true, min: 0.01, label: 'Base Price' },
    },
    3: { /* Media & Certificate — file-only, handled separately */
    },
};

/* ── Toast ──────────────────────────────────────────────────── */
function bvToast(title, desc = '', type = 'error') {
    const wrap  = document.getElementById('bv-toast-wrap');
    const toast = document.createElement('div');
    toast.className = `bv-toast${type === 'success' ? ' success' : ''}`;

    const iconColor = type === 'success' ? '#C9A74E' : '#ef4444';
    const iconPath  = type === 'success'
        ? `<polyline points="20 6 9 17 4 12"/>`
        : `<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>`;

    toast.innerHTML = `
        <svg class="bv-toast-icon" viewBox="0 0 24 24" fill="none"
             stroke="${iconColor}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>${iconPath}
        </svg>
        <div class="bv-toast-body">
            <div class="bv-toast-title">${title}</div>
            ${desc ? `<div class="bv-toast-desc">${desc}</div>` : ''}
        </div>
        <button class="bv-toast-close" onclick="bvDismissToast(this.closest('.bv-toast'))">✕</button>
    `;
    wrap.appendChild(toast);
    setTimeout(() => bvDismissToast(toast), 4500);
}

function bvDismissToast(toast) {
    if (!toast || toast.classList.contains('leaving')) return;
    toast.classList.add('leaving');
    setTimeout(() => toast.remove(), 250);
}

/* ── Shake button ───────────────────────────────────────────── */
function bvShakeBtn(btnId) {
    const btn = document.getElementById(btnId);
    if (!btn) return;
    btn.classList.remove('bv-shake');
    void btn.offsetWidth;
    btn.classList.add('bv-shake');
    setTimeout(() => btn.classList.remove('bv-shake'), 500);
}

/* ── Render inline error / clear it ────────────────────────── */
function bvSetFieldError(input, message) {
    const wrapper = input.closest('.bv-field') || input.parentElement;

    input.classList.remove('bv-input-valid');
    input.classList.add('bv-input-error');

    /* Error message */
    let msg = wrapper.querySelector('.bv-error-msg');
    if (!msg) {
        msg = document.createElement('div');
        msg.className = 'bv-error-msg';
        input.after(msg);
    }
    msg.textContent = message;
    msg.classList.add('visible');

    /* Hide valid tick */
    const tick = wrapper.querySelector('.bv-valid-tick');
    if (tick) tick.classList.remove('show');
}

function bvClearFieldError(input) {
    const wrapper = input.closest('.bv-field') || input.parentElement;

    input.classList.remove('bv-input-error');
    if (input.value.trim()) input.classList.add('bv-input-valid');
    else                    input.classList.remove('bv-input-valid');

    const msg = wrapper.querySelector('.bv-error-msg');
    if (msg) msg.classList.remove('visible');

    /* Show valid tick for non-select text inputs */
    if (input.tagName !== 'SELECT' && input.tagName !== 'TEXTAREA') {
        let tick = wrapper.querySelector('.bv-valid-tick');
        if (!tick) {
            tick = document.createElement('div');
            tick.className = 'bv-valid-tick';
            tick.innerHTML = `<svg viewBox="0 0 12 12" fill="none"><polyline points="2,6 5,9 10,3"/></svg>`;
            wrapper.style.position = 'relative';
            wrapper.appendChild(tick);
        }
        tick.classList.toggle('show', !!input.value.trim());
    }
}

/* ── Validate a single input ────────────────────────────────── */
function bvValidateInput(input, rule) {
    const val = input.value.trim();
    let error = null;

    if (rule.required && !val)                             error = `${rule.label} is required`;
    else if (rule.minLength && val.length < rule.minLength) error = `Min ${rule.minLength} characters`;
    else if (rule.min !== undefined && parseFloat(val) < rule.min) error = `Min value is ${rule.min}`;
    else if (rule.pattern && val && !rule.pattern.test(val)) error = rule.patternMsg || 'Invalid format';

    if (error) { bvSetFieldError(input, error); return false; }
    bvClearFieldError(input);
    return true;
}

/* ── Bind live validation to a slide's inputs ───────────────── */
function bvBindSlide(artIdx, sectionIdx) {
    const slide = document.querySelector(
        `.multi-slide[data-art-index="${artIdx}"][data-section="${sectionIdx}"]`
    );
    if (!slide) return;

    const rules = BV_RULES[sectionIdx] || {};

    /* Text / number / select inputs */
    Object.entries(rules).forEach(([field, rule]) => {
        const input = slide.querySelector(`[name="artwork[${artIdx}][${field}]"]`);
        if (!input) return;

        /* Wrap in .bv-field if not already */
        if (!input.closest('.bv-field')) {
            const wrap = document.createElement('div');
            wrap.className = 'bv-field';
            input.parentNode.insertBefore(wrap, input);
            wrap.appendChild(input);
        }

        ['input', 'change'].forEach(evt =>
            input.addEventListener(evt, () => bvValidateInput(input, rule))
        );
    });

    /* Textarea: add char counter */
    slide.querySelectorAll('textarea').forEach(ta => {
        const min  = rules[ta.dataset.field]?.minLength || 0;
        let counter = ta.parentElement.querySelector('.bv-char-counter');
        if (!counter) {
            counter = document.createElement('div');
            counter.className = 'bv-char-counter';
            ta.after(counter);
        }
        const update = () => {
            const len = ta.value.trim().length;
            counter.textContent = min ? `${len} / ${min}+ chars` : `${len} chars`;
            counter.className   = 'bv-char-counter ' + (len >= min ? 'ok' : (len > 0 ? 'err' : ''));
        };
        ta.addEventListener('input', update);
        update();
    });

    /* Section 3: file inputs */
    if (sectionIdx === 3) {
        bvBindFileInputs(artIdx);
    }
}

/* ── Bind file size validation for section 3 ────────────────── */
function bvBindFileInputs(artIdx) {
    /* Art photos */
    const mediaInput = document.getElementById(`multiFileInput_${artIdx}`);
    if (mediaInput && !mediaInput._bvBound) {
        mediaInput._bvBound = true;
        mediaInput.removeAttribute('onchange');
        mediaInput.addEventListener('change', function () {
            const files = Array.from(this.files);
            let oversized = null;
            for (const f of files) {
                if (f.size > BV_MAX_BYTES) { oversized = f; break; }
            }
            if (oversized) {
                const mb = (oversized.size / 1024 / 1024).toFixed(1);
                this.value = '';
                bvToast('File too large', `"${oversized.name}" is ${mb} MB — max ${BV_MAX_MB} MB.`);
                /* Flash the upload zone */
                const zone = document.getElementById(`multiMediaContainer_${artIdx}`)?.firstElementChild;
                if (zone) { zone.style.borderColor = '#ef4444'; setTimeout(() => zone.style.borderColor = '', 2000); }
                return;
            }
            /* Lolos → original handler */
            handleMultiMedia(this, artIdx);
        });
    }

    /* Certificate */
    const certInput = document.getElementById(`certInput_${artIdx}`);
    if (certInput && !certInput._bvBound) {
        certInput._bvBound = true;
        certInput.removeAttribute('onchange');
        certInput.addEventListener('change', function () {
            const file = this.files?.[0];
            if (!file) return;

            if (file.size > BV_MAX_BYTES) {
                const mb = (file.size / 1024 / 1024).toFixed(1);
                this.value = '';
                bvToast('Certificate too large', `File is ${mb} MB — max ${BV_MAX_MB} MB.`);
                const box = this.closest('div[onclick]')?.nextElementSibling
                    || document.querySelector(`#certInput_${artIdx}`)?.closest('div[onclick]');
                if (box) { box.classList.add('bv-cert-error'); setTimeout(() => box.classList.remove('bv-cert-error'), 2500); }
                return;
            }
            /* Lolos → original handler */
            handleCertUpload(this, artIdx);
        });
    }
}

/* ── Step dots ──────────────────────────────────────────────── */
function bvMountStepDots() {
    const counter = document.getElementById('slideCounter');
    if (!counter || document.getElementById('bv-step-dots')) return;

    const dots = document.createElement('div');
    dots.id = 'bv-step-dots';
    counter.after(dots);
}

function bvUpdateStepDots(currentSlide, totalSlides, errorSteps = new Set()) {
    const dots = document.getElementById('bv-step-dots');
    if (!dots) return;
    dots.innerHTML = '';
    for (let i = 0; i < totalSlides; i++) {
        const d = document.createElement('div');
        let cls = 'bv-step-dot';
        if (i === currentSlide)      cls += ' active';
        else if (errorSteps.has(i))  cls += ' error';
        else if (i < currentSlide)   cls += ' done';
        d.className = cls;
        dots.appendChild(d);
    }
}

/* ── Validate current slide before advancing ─────────────────── */
const bvErrorSteps = new Set();

function bvValidateSlide(artIdx, sectionIdx) {
    const slide = document.querySelector(
        `.multi-slide[data-art-index="${artIdx}"][data-section="${sectionIdx}"]`
    );
    if (!slide) return true;

    const rules = BV_RULES[sectionIdx] || {};
    let valid = true;
    let firstBad = null;

    Object.entries(rules).forEach(([field, rule]) => {
        const input = slide.querySelector(`[name="artwork[${artIdx}][${field}]"]`);
        if (!input) return;
        if (!bvValidateInput(input, rule)) {
            valid = false;
            if (!firstBad) firstBad = input;
        }
    });

    /* Section 3: certificate required */
    if (sectionIdx === 3) {
        const certInput = document.getElementById(`certInput_${artIdx}`);
        const hasCert   = certInput && certInput.files?.length > 0;
        const certBox   = certInput?.closest('[onclick]');
        if (!hasCert) {
            valid = false;
            if (certBox) { certBox.classList.add('bv-cert-error'); setTimeout(() => certBox.classList.remove('bv-cert-error'), 2500); }
            if (!firstBad) firstBad = certBox;
            bvToast('Certificate required', 'Upload the authenticity certificate to continue.');
        }
    }

    if (!valid) {
        const slideIdx = artIdx * 4 + sectionIdx;
        bvErrorSteps.add(slideIdx);
        bvUpdateStepDots(window.currentSlide, window.totalSlides, bvErrorSteps);

        /* Flash progress bar */
        const bar = document.getElementById('multiProgressBar');
        if (bar) { bar.classList.remove('bv-bar-flash'); void bar.offsetWidth; bar.classList.add('bv-bar-flash'); setTimeout(() => bar.classList.remove('bv-bar-flash'), 400); }

        /* Shake Next / Submit button */
        const isLast = window.currentSlide === window.totalSlides - 1;
        bvShakeBtn(isLast ? 'multiSubmitBtn' : 'nextBtn');

        /* Toast summary */
        const sectionNames = ['Art Identification', 'Product Detail', 'Pricing', 'Media & Certificate'];
        const missingFields = Object.entries(rules)
            .filter(([field, rule]) => {
                const inp = slide.querySelector(`[name="artwork[${artIdx}][${field}]"]`);
                return inp && !inp.value.trim();
            })
            .map(([_, rule]) => rule.label);

        if (missingFields.length) {
            bvToast(
                `${sectionNames[sectionIdx]} incomplete`,
                `Missing: ${missingFields.join(', ')}`
            );
        }

        /* Focus first error */
        setTimeout(() => {
            if (firstBad && firstBad.focus) firstBad.focus();
        }, 100);

    } else {
        const slideIdx = artIdx * 4 + sectionIdx;
        bvErrorSteps.delete(slideIdx);
        bvUpdateStepDots(window.currentSlide, window.totalSlides, bvErrorSteps);
    }

    return valid;
}

/* ── Hook into navigateSlide ────────────────────────────────── */
const _bvOrigNavigateSlide = navigateSlide;
navigateSlide = function (direction) {
    /* Only validate when going FORWARD */
    if (direction > 0) {
        const artIdx     = Math.floor(window.currentSlide / 4);
        const sectionIdx = window.currentSlide % 4;

        if (!bvValidateSlide(artIdx, sectionIdx)) return; /* blocked */
    }

    _bvOrigNavigateSlide(direction);

    /* After navigation, bind the new slide's inputs & update dots */
    const newArtIdx     = Math.floor(window.currentSlide / 4);
    const newSectionIdx = window.currentSlide % 4;
    bvBindSlide(newArtIdx, newSectionIdx);
    bvUpdateStepDots(window.currentSlide, window.totalSlides, bvErrorSteps);

    /* Animate the new slide */
    const newSlide = document.querySelector(
        `.multi-slide[data-art-index="${newArtIdx}"][data-section="${newSectionIdx}"]`
    );
    if (newSlide) {
        newSlide.classList.remove('bv-slide-enter');
        void newSlide.offsetWidth;
        newSlide.classList.add('bv-slide-enter');
        setTimeout(() => newSlide.classList.remove('bv-slide-enter'), 300);
    }
};

/* ── Hook into submitMultiForm ──────────────────────────────── */
const _bvOrigSubmit = submitMultiForm;
submitMultiForm = async function () {
    /* Validate last slide (Media & Certificate) for each artwork */
    const numArtworks = parseInt(document.getElementById('art-count-input').value) || 1;
    let allValid = true;

    for (let i = 0; i < numArtworks; i++) {
        if (!bvValidateSlide(i, 3)) {
            allValid = false;
        }
    }
    if (!allValid) {
        bvShakeBtn('multiSubmitBtn');
        return;
    }
    await _bvOrigSubmit();
};

/* ── Hook into generateSlides — bind first slide after render ── */
const _bvOrigGenerateSlides = generateSlides;
generateSlides = function (count) {
    _bvOrigGenerateSlides(count);

    /* Reset error tracking */
    bvErrorSteps.clear();

    /* Bind slide 0 of artwork 0 */
    requestAnimationFrame(() => {
        bvBindSlide(0, 0);
        bvMountStepDots();
        bvUpdateStepDots(0, count * 4, bvErrorSteps);
    });
};

/* ── Hook into initMultiForm — ensure dots mount ────────────── */
const _bvOrigInitMultiForm = initMultiForm;
initMultiForm = function (artistData, count) {
    _bvOrigInitMultiForm(artistData, count);
    requestAnimationFrame(() => {
        bvMountStepDots();
        bvUpdateStepDots(0, count * 4, bvErrorSteps);
    });
};