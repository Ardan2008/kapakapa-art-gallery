/* ═══════════════════════════════════════════════════════════
   STATE
═══════════════════════════════════════════════════════════ */
let _currentCommentArtworkId = null;

const _commentState = {
    comments:   [],
    lastCount:  0,
    pollTimer:  null,
    timeTimer:  null,
    isSending:  false,
    hasLoaded:  false,
};

/* ═══════════════════════════════════════════════════════════
   HELPERS
═══════════════════════════════════════════════════════════ */
function _el(id) { return document.getElementById(id); }

function _escHtml(str) {
    return String(str || '')
        .replace(/&/g, '&amp;').replace(/</g, '&lt;')
        .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function _timeAgo(dateStr) {
    const sec = (Date.now() - new Date(dateStr).getTime()) / 1000;
    if (sec < 5)     return 'just now';
    if (sec < 60)    return Math.floor(sec) + 's';
    if (sec < 3600)  return Math.floor(sec / 60) + 'm';
    if (sec < 86400) return Math.floor(sec / 3600) + 'h';
    return Math.floor(sec / 86400) + 'd';
}

const _COLORS = [
    { bg: 'rgba(201,167,78,.12)',  border: 'rgba(201,167,78,.3)',  text: '#C9A74E' },
    { bg: 'rgba(99,179,237,.12)',  border: 'rgba(99,179,237,.3)',  text: '#63B3ED' },
    { bg: 'rgba(154,215,160,.12)', border: 'rgba(154,215,160,.3)', text: '#9AD7A0' },
    { bg: 'rgba(237,137,137,.12)', border: 'rgba(237,137,137,.3)', text: '#ED8989' },
    { bg: 'rgba(183,148,246,.12)', border: 'rgba(183,148,246,.3)', text: '#B794F6' },
];

function _avatarColor(name) {
    const code = [...String(name)].reduce((a, c) => a + c.charCodeAt(0), 0);
    return _COLORS[code % _COLORS.length];
}

function _initials(name) {
    return String(name).trim().split(' ')
        .map(w => w[0] || '').join('').toUpperCase().slice(0, 2) || 'AN';
}

/* ═══════════════════════════════════════════════════════════
   BUILD COMMENT NODE
═══════════════════════════════════════════════════════════ */
function _buildComment(c, isNew = false) {
    const isOwn = !!c.is_own || !!c._optimistic;
    const id    = c.id || c._tmpId;
    const time  = _timeAgo(c.created_at || new Date().toISOString());

    const div = document.createElement('div');
    div.className = `comment-node flex gap-3 py-3 px-2 rounded-2xl ${isNew ? 'comment-slide-in' : ''} ${isOwn ? 'flex-row-reverse' : ''}`;
    div.dataset.cid = id;
    div.style.transition = 'background 0.2s ease';
    div.onmouseenter = () => div.style.background = 'rgba(255,255,255,0.02)';
    div.onmouseleave = () => div.style.background = '';

    // Avatar
    let avatarHtml;
    if (c.avatar) {
        avatarHtml = `<img src="${_escHtml(c.avatar)}" alt="${_escHtml(c.name || '')}"
                          class="flex-shrink-0 w-8 h-8 rounded-full border border-white/10 object-cover"
                          onerror="this.style.display='none'">`;
    } else {
        const color = _avatarColor(c.name || 'Anonymous');
        const inits = _initials(c.name || 'Anonymous');
        avatarHtml = `<div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                  text-[10px] font-bold border"
                           style="background:${color.bg}; border-color:${color.border}; color:${color.text}">
                          ${inits}
                      </div>`;
    }

    // Body content — sticker/gif tampil sebagai gambar, teks biasa sebagai teks
    let bodyContent;
    const type = c.type || 'text';
    if ((type === 'gif' || type === 'sticker') && c.sticker_url) {
        bodyContent = `<img src="${_escHtml(c.sticker_url)}"
                            class="max-w-[160px] rounded-xl block"
                            alt="${type}"
                            loading="lazy">`;
    } else {
        bodyContent = _escHtml(c.body || '');
    }

    // Action menu — hanya untuk komentar sendiri dan bukan optimistic
    const actionMenu = (isOwn && !c._optimistic) ? `
        <div class="comment-actions flex gap-1 mt-1 ${isOwn ? 'justify-end' : ''}">
            ${type === 'text' ? `
            <button onclick="_editComment('${id}')"
                    class="text-[9px] text-zinc-600 hover:text-gold transition-colors px-1.5 py-0.5 rounded border border-transparent hover:border-gold/20">
                Edit
            </button>` : ''}
            <button onclick="_deleteComment('${id}', ${_currentCommentArtworkId})"
                    class="text-[9px] text-zinc-600 hover:text-rose-400 transition-colors px-1.5 py-0.5 rounded border border-transparent hover:border-rose-400/20">
                Delete
            </button>
        </div>` : '';

    div.innerHTML = `
        ${avatarHtml}
        <div class="flex flex-col gap-1 max-w-[calc(100%-44px)] ${isOwn ? 'items-end' : 'items-start'}">
            <div class="flex items-baseline gap-2 ${isOwn ? 'flex-row-reverse' : ''}">
                <span class="text-[10px] font-medium tracking-wide text-zinc-400">
                    ${_escHtml(c.name || 'Anonymous')}
                </span>
                <span class="comment-time text-[9px] text-zinc-600"
                      data-ts="${c.created_at || new Date().toISOString()}">${time}</span>
            </div>
            <div class="comment-body-wrap px-4 py-2.5 text-[12px] leading-relaxed font-light text-zinc-300 border
                ${isOwn
                    ? 'bg-gold/5 border-gold/20 rounded-2xl rounded-tr-none text-right'
                    : 'bg-white/[0.03] border-white/5 rounded-2xl rounded-tl-none'}">
                ${bodyContent}
            </div>
            ${c._optimistic ? `<span class="sending-label flex items-center gap-1 text-[9px] text-zinc-600">
                <svg class="w-2.5 h-2.5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-20"/>
                    <path fill="currentColor" class="opacity-60" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>Sending…</span>` : ''}
            ${actionMenu}
        </div>
    `;
    return div;
}

/* ═══════════════════════════════════════════════════════════
   RENDER
═══════════════════════════════════════════════════════════ */
function _scrollBottom(smooth = true) {
    const list = _el('commentList');
    if (!list) return;
    setTimeout(() => list.scrollTo({ top: list.scrollHeight, behavior: smooth ? 'smooth' : 'instant' }), 50);
}

function _updateBadge(n) {
    const badge = _el('commentCountBadge');
    if (!badge) return;
    const label = n === 1 ? '1 note' : `${n} notes`;
    if (badge.textContent !== label) {
        badge.style.transform = 'scale(1.3)';
        badge.textContent = label;
        setTimeout(() => (badge.style.transform = ''), 350);
    }
}

function _showLoading() {
    _el('commentLoading')    && _el('commentLoading').classList.remove('hidden');
    _el('commentEmptyState') && _el('commentEmptyState').classList.add('hidden');
    document.querySelectorAll('.comment-node').forEach(n => n.remove());
}

function _hideLoading() {
    _el('commentLoading') && _el('commentLoading').classList.add('hidden');
}

function _renderAll(list, items) {
    document.querySelectorAll('.comment-node').forEach(n => n.remove());
    _hideLoading();
    if (!items || items.length === 0) {
        _el('commentEmptyState') && _el('commentEmptyState').classList.remove('hidden');
        _updateBadge(0);
        return;
    }
    _el('commentEmptyState') && _el('commentEmptyState').classList.add('hidden');
    items.forEach(c => list.appendChild(_buildComment(c, false)));
    _updateBadge(items.length);
    _scrollBottom(false);
}

function _appendNew(list, items) {
    const existing = new Set([...list.querySelectorAll('.comment-node')].map(n => n.dataset.cid));
    let added = 0;
    items.forEach(c => {
        if (!existing.has(String(c.id))) {
            list.appendChild(_buildComment(c, true));
            added++;
        }
    });
    if (added > 0) {
        _updateBadge(items.length);
        _scrollBottom(true);
        const badge = _el('commentCountBadge');
        if (badge) { badge.style.color = '#C9A74E'; setTimeout(() => (badge.style.color = ''), 1500); }
    }
}

/* ═══════════════════════════════════════════════════════════
   AUTH PROMPT
═══════════════════════════════════════════════════════════ */
function _showAuthPrompt(loginUrl) {
    const btn = _el('googleSignInBtn');
    if (btn && loginUrl) {
        try {
            const url = new URL(loginUrl, window.location.origin);
            url.searchParams.set('redirect', window.location.href);
            btn.href = url.toString();
        } catch(e) {}
    }
    // Shake the sign-in section
    const section = btn?.closest('div');
    if (section) {
        section.style.transform = 'translateX(-4px)';
        setTimeout(() => (section.style.transform = 'translateX(4px)'), 80);
        setTimeout(() => (section.style.transform = ''), 160);
    }
}

/* ═══════════════════════════════════════════════════════════
   API
═══════════════════════════════════════════════════════════ */
async function _fetchComments(id) {
    try {
        const res = await fetch(`/artworks/${id}/comments`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' },
        });
        if (!res.ok) throw new Error(res.status);
        const data = await res.json();
        return data.comments || data || [];
    } catch (e) {
        console.warn('[Comments] fetch error:', e);
        return null;
    }
}

async function _postComment(id, payload) {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const res = await fetch(`/artworks/${id}/comments`, {
        method: 'POST',
        headers: {
            'Content-Type':     'application/json',
            Accept:             'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN':     csrf,
        },
        body: JSON.stringify(payload),
    });
    const data = await res.json();
    if (res.status === 401) return { _unauthenticated: true, login_url: data.login_url };
    if (!res.ok) throw new Error(res.status);
    return data.comment || data;
}

/* ═══════════════════════════════════════════════════════════
   POLLING
═══════════════════════════════════════════════════════════ */
function _startPoll(id) {
    _stopPoll();
    _commentState.pollTimer = setInterval(async () => {
        if (!_currentCommentArtworkId) return;
        const items = await _fetchComments(id);
        if (!items) return;
        const list = _el('commentList');
        if (!list) return;
        if (items.length > _commentState.lastCount) {
            _appendNew(list, items);
            _commentState.lastCount = items.length;
            _commentState.comments  = items;
        }
    }, 8000);
}

function _stopPoll() {
    if (_commentState.pollTimer) { clearInterval(_commentState.pollTimer); _commentState.pollTimer = null; }
}

function _startTimeRefresh() {
    if (_commentState.timeTimer) clearInterval(_commentState.timeTimer);
    _commentState.timeTimer = setInterval(() => {
        document.querySelectorAll('.comment-time[data-ts]').forEach(el => {
            el.textContent = _timeAgo(el.dataset.ts);
        });
    }, 30_000);
}

/* ═══════════════════════════════════════════════════════════
   INPUT SETUP
═══════════════════════════════════════════════════════════ */
function _setupInput() {
    const input = _el('commentInput');
    if (!input || input._bound) return;
    input._bound = true;

    input.addEventListener('input', () => {
        input.style.height = 'auto';
        input.style.height = Math.min(input.scrollHeight, 120) + 'px';
    });

    input.addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); submitComment(); }
    });
}

/* ═══════════════════════════════════════════════════════════
   OPTIMISTIC HELPER — shared by text & sticker submit
═══════════════════════════════════════════════════════════ */
function _optimisticInsert(previewData) {
    const list = _el('commentList');
    if (!list) return null;

    const tmpId = '_tmp_' + Date.now();
    const node  = _buildComment({
        id: tmpId, _tmpId: tmpId, _optimistic: true,
        name: '…', avatar: null,
        body:        previewData.body        || null,
        sticker_url: previewData.sticker_url || null,
        type:        previewData.type        || 'text',
        created_at:  new Date().toISOString(),
    }, true);

    _el('commentEmptyState') && _el('commentEmptyState').classList.add('hidden');
    list.appendChild(node);
    _scrollBottom(true);
    _commentState.lastCount++;
    _updateBadge(_commentState.lastCount);
    return tmpId;
}

function _optimisticConfirm(tmpId, saved) {
    const list = _el('commentList');
    const old  = list?.querySelector(`[data-cid="${tmpId}"]`);
    if (old) {
        const real = _buildComment({ ...saved, is_own: true }, false);
        real.classList.add('comment-confirmed');
        old.replaceWith(real);
    }
    _commentState.comments.push(saved);
}

function _optimisticFail(tmpId) {
    const list   = _el('commentList');
    const failed = list?.querySelector(`[data-cid="${tmpId}"]`);
    if (failed) {
        failed.style.opacity = '0.4';
        const lbl = failed.querySelector('.sending-label');
        if (lbl) { lbl.style.color = '#f87171'; lbl.textContent = '✕ Failed — try again'; }
    }
    _commentState.lastCount--;
    _updateBadge(_commentState.lastCount);
}

/* ═══════════════════════════════════════════════════════════
   PUBLIC — toggleCommentModal
═══════════════════════════════════════════════════════════ */
function toggleCommentModal() {
    const overlay = _el('commentOverlay');
    const content = _el('commentContent');
    if (!overlay || !content) return;

    const isOpen = overlay.dataset.open === 'true';

    if (!isOpen) {
        if (!_currentCommentArtworkId) return;

        // Reset state
        _commentState.comments  = [];
        _commentState.lastCount = 0;
        _commentState.hasLoaded = false;
        _commentState.isSending = false;

        // Reset list UI
        const list = _el('commentList');
        if (list) [...list.querySelectorAll('.comment-node')].forEach(n => n.remove());
        _el('commentLoading')    && _el('commentLoading').classList.remove('hidden');
        _el('commentEmptyState') && _el('commentEmptyState').classList.add('hidden');

        // Reset badge & input
        const badge = _el('commentCountBadge');
        if (badge) badge.textContent = '…';
        const input = _el('commentInput');
        if (input) { input.value = ''; input._bound = false; input.style.height = 'auto'; }

        // Open overlay
        overlay.classList.remove('invisible', 'opacity-0');
        overlay.classList.add('opacity-100');
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
        overlay.dataset.open = 'true';

        // Fetch & poll
        _fetchComments(_currentCommentArtworkId).then(items => {
            if (!items) { _hideLoading(); return; }
            _renderAll(_el('commentList'), items);
            _commentState.comments  = items;
            _commentState.lastCount = items.length;
            _commentState.hasLoaded = true;
            _setupInput();
        });
        _startPoll(_currentCommentArtworkId);
        _startTimeRefresh();

    } else {
        // Close
        _stopPoll();
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        overlay.classList.remove('opacity-100');
        overlay.classList.add('opacity-0');
        setTimeout(() => {
            overlay.classList.add('invisible');
            overlay.dataset.open = 'false';
        }, 500);
    }
}

/* ═══════════════════════════════════════════════════════════
   PUBLIC — submitComment (text)
═══════════════════════════════════════════════════════════ */
async function submitComment() {
    if (_commentState.isSending || !_currentCommentArtworkId) return;

    const input   = _el('commentInput');
    const icon    = _el('sendIcon');
    const spinner = _el('sendSpinner');

    const body = input?.value?.trim();
    if (!body) {
        const wrap = input?.closest('div');
        if (wrap) {
            wrap.style.transform = 'translateX(-4px)';
            setTimeout(() => (wrap.style.transform = 'translateX(4px)'), 80);
            setTimeout(() => (wrap.style.transform = ''), 160);
        }
        return;
    }

    const tmpId = _optimisticInsert({ body, type: 'text' });

    _commentState.isSending = true;
    if (input) { input.value = ''; input.style.height = 'auto'; }
    if (icon)    icon.classList.add('hidden');
    if (spinner) spinner.classList.remove('hidden');

    try {
        const saved = await _postComment(_currentCommentArtworkId, { body, type: 'text' });
        if (saved._unauthenticated) {
            _el('commentList')?.querySelector(`[data-cid="${tmpId}"]`)?.remove();
            _commentState.lastCount--;
            _updateBadge(_commentState.lastCount);
            _showAuthPrompt(saved.login_url);
            return;
        }
        _optimisticConfirm(tmpId, saved);
    } catch (err) {
        console.error('[Comments] send failed:', err);
        _optimisticFail(tmpId);
    } finally {
        _commentState.isSending = false;
        if (spinner) spinner.classList.add('hidden');
        if (icon)    icon.classList.remove('hidden');
        input?.focus();
    }
}

/* ═══════════════════════════════════════════════════════════
   PUBLIC — submitSticker / GIF
   Dipanggil langsung saat user klik stiker/gif di panel
═══════════════════════════════════════════════════════════ */
async function _submitSticker(stickerUrl, type) {
    if (!_currentCommentArtworkId) return;

    // Tutup panel picker dulu
    _el('stickerPanel') && _el('stickerPanel').classList.add('hidden');

    // Optimistic insert — tampilkan gambar langsung
    const tmpId = _optimisticInsert({ sticker_url: stickerUrl, type });

    const icon    = _el('sendIcon');
    const spinner = _el('sendSpinner');
    if (icon)    icon.classList.add('hidden');
    if (spinner) spinner.classList.remove('hidden');

    try {
        const saved = await _postComment(_currentCommentArtworkId, {
            body:        '',
            sticker_url: stickerUrl,
            type:        type,
        });

        if (saved._unauthenticated) {
            _el('commentList')?.querySelector(`[data-cid="${tmpId}"]`)?.remove();
            _commentState.lastCount--;
            _updateBadge(_commentState.lastCount);
            _showAuthPrompt(saved.login_url);
            return;
        }
        _optimisticConfirm(tmpId, saved);
    } catch (err) {
        console.error('[Comments] sticker send failed:', err);
        _optimisticFail(tmpId);
    } finally {
        if (spinner) spinner.classList.add('hidden');
        if (icon)    icon.classList.remove('hidden');
    }
}

/* ═══════════════════════════════════════════════════════════
   EDIT / DELETE
═══════════════════════════════════════════════════════════ */
async function _deleteComment(id, artworkId) {
    const result = await Swal.fire({
        title: '<span style="font-size:15px; letter-spacing:0.15em; text-transform:uppercase; font-weight:500; color:#e4e4e7">Delete this note?</span>',
        html:  '<span style="font-size:11px; color:#71717a; letter-spacing:0.05em">This action cannot be undone.</span>',
        icon: 'warning',
        background: '#09090b',
        color: '#e4e4e7',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText:  'Cancel',
        buttonsStyling: false,
        reverseButtons: true,
        customClass: {
            popup:         'swal-gold-popup',
            confirmButton: 'swal-gold-confirm',
            cancelButton:  'swal-gold-cancel',
            icon:          'swal-gold-icon',
            actions:       'swal-gold-actions',
        },
    });

    if (!result.isConfirmed) return;

    // ── Loading screen ──
    Swal.fire({
        background: '#09090b',
        color: '#e4e4e7',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        customClass: { popup: 'swal-gold-popup' },
        html: `
            <div style="display:flex; flex-direction:column; align-items:center; gap:20px; padding:12px 0">
                <div style="position:relative; width:48px; height:48px;">
                    <svg style="animation:spin 1s linear infinite; width:48px; height:48px;"
                         viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="24" cy="24" r="20"
                                stroke="rgba(201,167,78,0.15)" stroke-width="3"/>
                        <path d="M24 4 A20 20 0 0 1 44 24"
                              stroke="#C9A74E" stroke-width="3"
                              stroke-linecap="round"/>
                    </svg>
                    <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center;">
                        <div style="width:6px; height:6px; border-radius:999px; background:#C9A74E;
                                    animation:pulse 1s ease-in-out infinite;"></div>
                    </div>
                </div>
                <span style="font-size:10px; text-transform:uppercase; letter-spacing:0.25em; color:#71717a;">
                    Deleting…
                </span>
            </div>
            <style>
                @keyframes spin  { to { transform: rotate(360deg); } }
                @keyframes pulse { 0%,100% { opacity:.3; transform:scale(.8); }
                                   50%      { opacity:1;  transform:scale(1.2); } }
            </style>
        `,
    });

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    try {
        const res = await fetch(`/artworks/${artworkId}/comments/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
        });

        if (res.ok) {
            const node = document.querySelector(`.comment-node[data-cid="${id}"]`);
            if (node) {
                node.style.opacity    = '0';
                node.style.transform  = 'scale(0.95)';
                node.style.transition = 'all 0.3s ease';
                setTimeout(() => node.remove(), 300);
            }
            _commentState.lastCount--;
            _updateBadge(_commentState.lastCount);

            await Swal.fire({
                title: '<span style="font-size:14px; letter-spacing:0.15em; text-transform:uppercase; font-weight:500; color:#e4e4e7">Deleted</span>',
                html:  '<span style="font-size:11px; color:#71717a; letter-spacing:0.05em">Your note has been removed.</span>',
                icon: 'success',
                background: '#09090b',
                color: '#e4e4e7',
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false,
                customClass: {
                    popup:            'swal-gold-popup',
                    icon:             'swal-gold-success-icon',
                    timerProgressBar: 'swal-gold-progress',
                },
            });
        }
    } catch (e) {
        console.error(e);
        Swal.fire({
            title: '<span style="font-size:14px; letter-spacing:0.15em; text-transform:uppercase; color:#e4e4e7">Failed</span>',
            html:  '<span style="font-size:11px; color:#71717a">Something went wrong. Please try again.</span>',
            icon: 'error',
            background: '#09090b',
            color: '#e4e4e7',
            showConfirmButton: true,
            confirmButtonText: 'OK',
            buttonsStyling: false,
            customClass: {
                popup:         'swal-gold-popup',
                confirmButton: 'swal-gold-confirm',
                actions:       'swal-gold-actions',
            },
        });
    }
}

function _editComment(id) {
    const node = document.querySelector(`.comment-node[data-cid="${id}"]`);
    if (!node) return;
    const bodyWrap   = node.querySelector('.comment-body-wrap');
    const currentText = bodyWrap.textContent.trim();

    bodyWrap.innerHTML = `
        <div class="flex flex-col gap-2 w-full">
            <textarea class="w-full bg-transparent text-[12px] text-zinc-300 focus:outline-none
                             resize-none border-b border-gold/30 pb-1"
                      rows="2" maxlength="280">${_escHtml(currentText)}</textarea>
            <div class="flex gap-2 justify-end">
                <button onclick="_cancelEdit('${id}', \`${currentText.replace(/`/g,'\\`')}\`)"
                        class="text-[9px] text-zinc-500 hover:text-zinc-300 px-2 py-0.5 border border-white/10 rounded transition-colors">
                    Cancel
                </button>
                <button onclick="_saveEdit('${id}')"
                        class="text-[9px] text-black bg-gold hover:bg-gold/80 px-2 py-0.5 rounded transition-colors">
                    Save
                </button>
            </div>
        </div>
    `;
    bodyWrap.querySelector('textarea')?.focus();
}

function _cancelEdit(id, originalText) {
    const node = document.querySelector(`.comment-node[data-cid="${id}"]`);
    if (!node) return;
    node.querySelector('.comment-body-wrap').innerHTML = _escHtml(originalText);
}

async function _saveEdit(id) {
    const node = document.querySelector(`.comment-node[data-cid="${id}"]`);
    if (!node) return;
    const bodyWrap = node.querySelector('.comment-body-wrap');
    const newBody  = bodyWrap.querySelector('textarea')?.value.trim();
    if (!newBody) return;

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    try {
        const res  = await fetch(`/artworks/${_currentCommentArtworkId}/comments/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type':     'application/json',
                'X-CSRF-TOKEN':     csrf,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ body: newBody }),
        });
        const data = await res.json();
        if (res.ok) bodyWrap.innerHTML = _escHtml(data.comment.body);
    } catch (e) { console.error(e); }
}

/* ═══════════════════════════════════════════════════════════
   STICKER / GIF PANEL
═══════════════════════════════════════════════════════════ */
let _gifSearchTimeout     = null;
let _stickerSearchTimeout = null;

function _toggleStickerPanel() {
    const panel = _el('stickerPanel');
    if (!panel) return;
    if (panel.classList.contains('hidden')) {
        panel.classList.remove('hidden');
        _switchTab('sticker');
        const s = _el('stickerSearch');
        if (s) s.value = '';
        _loadStickers('');
    } else {
        panel.classList.add('hidden');
    }
}

function _switchTab(tab) {
    const panelSticker = _el('panelSticker');
    const panelGif     = _el('panelGif');
    const tabSticker   = _el('tabSticker');
    const tabGif       = _el('tabGif');
    if (!panelSticker || !panelGif) return;

    if (tab === 'sticker') {
        panelSticker.classList.remove('hidden');
        panelGif.classList.add('hidden');
        tabSticker?.classList.add('text-gold', 'border-gold');
        tabSticker?.classList.remove('text-zinc-500', 'border-transparent');
        tabGif?.classList.remove('text-gold', 'border-gold');
        tabGif?.classList.add('text-zinc-500', 'border-transparent');
        _loadStickers('');

        const s = _el('stickerSearch');
        if (s && !s._bound) {
            s._bound = true;
            s.addEventListener('input', () => {
                clearTimeout(_stickerSearchTimeout);
                _stickerSearchTimeout = setTimeout(() => _loadStickers(s.value), 500);
            });
        }
    } else {
        panelGif.classList.remove('hidden');
        panelSticker.classList.add('hidden');
        tabGif?.classList.add('text-gold', 'border-gold');
        tabGif?.classList.remove('text-zinc-500', 'border-transparent');
        tabSticker?.classList.remove('text-gold', 'border-gold');
        tabSticker?.classList.add('text-zinc-500', 'border-transparent');
        _loadGifs('');

        const g = _el('gifSearch');
        if (g && !g._bound) {
            g._bound = true;
            g.addEventListener('input', () => {
                clearTimeout(_gifSearchTimeout);
                _gifSearchTimeout = setTimeout(() => _loadGifs(g.value), 500);
            });
        }
    }
}

function _gridLoadingHtml(cols) {
    return `<div class="col-span-${cols} flex justify-center py-6">
        <div class="w-6 h-6 border-2 border-gold/20 border-t-gold rounded-full animate-spin"></div>
    </div>`;
}

async function _loadStickers(query) {
    const grid = _el('stickerGrid');
    if (!grid) return;
    grid.innerHTML = _gridLoadingHtml(4);

    const key = window._GIPHY_KEY || '';
    const url = query
        ? `https://api.giphy.com/v1/stickers/search?api_key=${key}&q=${encodeURIComponent(query)}&limit=16&rating=g`
        : `https://api.giphy.com/v1/stickers/trending?api_key=${key}&limit=16&rating=g`;

    try {
        const res  = await fetch(url);
        const data = await res.json();
        grid.innerHTML = '';

        if (!data.data || data.data.length === 0) {
            grid.innerHTML = `<p class="col-span-4 text-center text-zinc-600 text-[10px] py-4">No stickers found</p>`;
            return;
        }

        data.data.forEach(sticker => {
            const previewUrl = sticker.images?.fixed_width_small?.url || sticker.images?.original?.url;
            const fullUrl    = sticker.images?.original?.url;
            if (!previewUrl || !fullUrl) return;

            const img = document.createElement('img');
            img.src   = previewUrl;
            img.className = 'w-full h-16 object-contain rounded-lg cursor-pointer hover:bg-white/5 hover:scale-110 transition-all duration-200 p-1';
            img.loading   = 'lazy';
            // ← langsung submit saat diklik
            img.addEventListener('click', () => _submitSticker(fullUrl, 'sticker'));
            grid.appendChild(img);
        });
    } catch (e) {
        grid.innerHTML = `<p class="col-span-4 text-center text-zinc-600 text-[10px] py-4">Failed to load stickers</p>`;
    }
}

async function _loadGifs(query) {
    const grid = _el('gifGrid');
    if (!grid) return;
    grid.innerHTML = _gridLoadingHtml(3);

    const key = window._GIPHY_KEY || '';
    const url = query
        ? `https://api.giphy.com/v1/gifs/search?api_key=${key}&q=${encodeURIComponent(query)}&limit=12&rating=g`
        : `https://api.giphy.com/v1/gifs/trending?api_key=${key}&limit=12&rating=g`;

    try {
        const res  = await fetch(url);
        const data = await res.json();
        grid.innerHTML = '';

        if (!data.data || data.data.length === 0) {
            grid.innerHTML = `<p class="col-span-3 text-center text-zinc-600 text-[10px] py-4">No GIFs found</p>`;
            return;
        }

        data.data.forEach(gif => {
            const previewUrl = gif.images?.fixed_height_small?.url || gif.images?.original?.url;
            const fullUrl    = gif.images?.original?.url;
            if (!previewUrl || !fullUrl) return;

            const img = document.createElement('img');
            img.src   = previewUrl;
            img.className = 'w-full h-20 object-cover rounded-lg cursor-pointer hover:opacity-80 hover:scale-105 transition-all duration-200';
            img.loading   = 'lazy';
            // ← langsung submit saat diklik
            img.addEventListener('click', () => _submitSticker(fullUrl, 'gif'));
            grid.appendChild(img);
        });
    } catch (e) {
        grid.innerHTML = `<p class="col-span-3 text-center text-zinc-600 text-[10px] py-4">Failed to load GIFs</p>`;
    }
}