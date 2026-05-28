<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Seoullicious – Korean Food')</title>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="{{ asset('css/home.css') }}" rel="stylesheet">
<link href="{{ asset('css/testi.css') }}" rel="stylesheet">
<link href="{{ asset('css/checkout.css') }}" rel="stylesheet">
<link href="{{ asset('css/profile-show.css') }}" rel="stylesheet">
<link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=Noto+Serif+KR:wght@300;400;600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stack('styles')
</head>
<body class="@yield('body-class')">

@include('partials.loading')
@include('partials.navbar')

@yield('content')

@include('partials.footer')

@if(request()->routeIs('home'))
    @include('partials.favorite')
    @include('partials.popup')
    @include('partials.testi')
@endif

@php $cartTotalItems = collect(session('cart', []))->sum('jumlah'); @endphp

<script>
const IS_LOGGED_IN = {{ auth()->check() ? 'true' : 'false' }};
const CART_TOTAL   = {{ $cartTotalItems }};

function getMenuData() {
    return window.menuData || [];
}

// ─── LOADING ─────────────────────────────────────────────────────────────────
// Paksa hide setelah 2 detik, tidak tunggu window.load
setTimeout(() => {
    const loading = document.getElementById('loading-screen');
    if (loading) loading.classList.add('hidden');
}, 2000);

// Fallback paksa display:none kalau hidden class tidak bekerja
setTimeout(() => {
    const loading = document.getElementById('loading-screen');
    if (loading) {
        loading.classList.add('hidden');
        loading.style.display = 'none';
    }
}, 4500);

const formatRp = n => 'Rp ' + n.toLocaleString('id-ID').replace(/,/g, '.');

// ─── CART ────────────────────────────────────────────────────────────────────
function updateCartBadge(total = null) {
    const badge = document.getElementById('cartBadge');
    const ccb   = document.getElementById('cartCountBadge');
    const poc   = document.getElementById('profileOrderCount');
    let count   = total ?? CART_TOTAL;

    if (badge) {
        badge.textContent    = count;
        badge.style.display  = count > 0 ? 'flex' : 'none';
    }
    if (ccb) ccb.textContent = count + ' item';
    if (poc) poc.textContent = count;
}

function openCart() {
    if (!requireAuth()) return;
    window.location.href = '{{ route("cart.index") }}';
}
updateCartBadge();

// ─── AUTH GUARD ───────────────────────────────────────────────────────────────
function requireAuth(callback) {
    if (!IS_LOGGED_IN) {
        Swal.fire({
            icon: 'warning', title: 'Login Diperlukan', text: 'Silakan login terlebih dahulu!',
            confirmButtonColor: '#8B1A1A', confirmButtonText: 'Login Sekarang',
            showCancelButton: true, cancelButtonText: 'Nanti Saja'
        }).then(r => { if (r.isConfirmed) window.location.href = '{{ route("login") }}'; });
        return false;
    }
    if (callback) callback();
    return true;
}

// ─── MOBILE NAV ───────────────────────────────────────────────────────────────
let mobileMenuOpen = false;
function toggleMobileMenu() {
    mobileMenuOpen = !mobileMenuOpen;
    const menu = document.getElementById('mobileMenu');
    const ham  = document.getElementById('hamburger');
    if (!menu || !ham) return;
    menu.classList.toggle('visible', true);
    setTimeout(() => menu.classList.toggle('open', mobileMenuOpen), 10);
    ham.classList.toggle('open', mobileMenuOpen);
}
function closeMobileMenu() {
    mobileMenuOpen = false;
    const menu = document.getElementById('mobileMenu');
    const ham  = document.getElementById('hamburger');
    if (!menu || !ham) return;
    menu.classList.remove('open');
    ham.classList.remove('open');
    setTimeout(() => menu.classList.remove('visible'), 350);
}
document.addEventListener('click', e => {
    if (mobileMenuOpen && !e.target.closest('#mobileMenu') && !e.target.closest('#hamburger'))
        closeMobileMenu();
});

// ─── NAVBAR SCROLL ────────────────────────────────────────────────────────────
window.addEventListener('scroll', () => {
    const nb = document.getElementById('navbar');
    if (nb) nb.classList.toggle('scrolled', window.scrollY > 50);
});

// ─── SCROLL REVEAL ────────────────────────────────────────────────────────────
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting)
            setTimeout(() => entry.target.classList.add('visible'), parseInt(entry.target.dataset.delay) || 0);
    });
}, { threshold: 0.1 });
document.querySelectorAll('[data-reveal], .event-card, .location-card').forEach((el, i) => {
    el.dataset.delay = (i % 4) * 120;
    revealObserver.observe(el);
});
['aboutImg1','aboutImg2','aboutBadge','aboutRight','resLeft','resForm'].forEach(id => {
    const el = document.getElementById(id);
    if (el) revealObserver.observe(el);
});
document.querySelectorAll('.event-card').forEach((c, i) => c.style.transitionDelay = (i * 0.15) + 's');
document.querySelectorAll('.location-card').forEach((c, i) => c.style.transitionDelay = (i * 0.12) + 's');

// ─── PROFILE PANEL SWITCH ─────────────────────────────────────────────────────
function switchPanel(id, el) {
    document.querySelectorAll('.profile-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.profile-nav-item').forEach(n => n.classList.remove('active'));
    const panel = document.getElementById('panel-' + id);
    if (panel) panel.classList.add('active');
    if (el) el.classList.add('active');
}

// ─── LOGOUT ───────────────────────────────────────────────────────────────────
function confirmLogout() {
    Swal.fire({
        title: 'Yakin mau logout?', text: 'Kamu harus login lagi untuk mengakses akun.',
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#8B1A1A', cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Logout', cancelButtonText: 'Batal'
    }).then(r => { if (r.isConfirmed) document.getElementById('logout-form').submit(); });
}

// ─── PHOTO MODAL ──────────────────────────────────────────────────────────────
let selectedFile = null, capturedBlob = null, cameraStream = null;

function openPhotoModal() {
    const spModal = document.getElementById('spPhotoModal');
    if (spModal) { spModal.classList.add('open'); document.body.style.overflow = 'hidden'; return; }
    const el = document.getElementById('photoModal');
    if (el) el.style.display = 'flex';
}
function closePhotoModal() {
    const spModal = document.getElementById('spPhotoModal');
    if (spModal) { spModal.classList.remove('open'); document.body.style.overflow = ''; return; }
    stopCamera();
    const modal     = document.getElementById('photoModal');
    const saveArea  = document.getElementById('save-photo-area');
    const fotoInput = document.getElementById('foto-input');
    if (modal)     modal.style.display    = 'none';
    if (saveArea)  saveArea.style.display = 'none';
    if (fotoInput) fotoInput.value        = '';
    selectedFile = null; capturedBlob = null;
}
function confirmDeletePhoto() {
    const photoModal = document.getElementById('photoModal');
    if (photoModal) photoModal.style.display = 'none';
    Swal.fire({
        title: 'Hapus Foto Profil?', text: 'Foto profilmu akan dihapus.',
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#8B1A1A', cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal'
    }).then(r => {
        if (r.isConfirmed) document.getElementById('delete-photo-form').submit();
        else if (photoModal) photoModal.style.display = 'flex';
    });
}
function previewPhoto(event) {
    const file = event.target.files[0]; if (!file) return;
    selectedFile = file; capturedBlob = null;
    const reader = new FileReader();
    reader.onload = e => showPreview(e.target.result);
    reader.readAsDataURL(file); showSaveArea();
}
function showPreview(src) {
    const img      = document.getElementById('modal-preview-img');
    const initials = document.getElementById('modal-preview-initials');
    if (img)     { img.src = src; img.style.display = 'block'; }
    if (initials)  initials.style.display = 'none';
}
function showSaveArea() {
    const el = document.getElementById('save-photo-area');
    if (el) el.style.display = 'block';
}
async function openCamera() {
    try {
        cameraStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
        const vid = document.getElementById('camera-video');
        if (vid) vid.srcObject = cameraStream;
        const ca = document.getElementById('camera-area');
        const ma = document.querySelector('.photo-modal-actions');
        if (ca) ca.style.display = 'block';
        if (ma) ma.style.display = 'none';
    } catch (err) { alert('Tidak dapat mengakses kamera.'); }
}
function stopCamera() {
    if (cameraStream) { cameraStream.getTracks().forEach(t => t.stop()); cameraStream = null; }
    const cameraArea   = document.getElementById('camera-area');
    const modalActions = document.querySelector('.photo-modal-actions');
    if (cameraArea)   cameraArea.style.display   = 'none';
    if (modalActions) modalActions.style.display = 'flex';
}
function capturePhoto() {
    const video  = document.getElementById('camera-video');
    const canvas = document.getElementById('camera-canvas');
    if (!video || !canvas) return;
    canvas.width = video.videoWidth; canvas.height = video.videoHeight;
    const ctx = canvas.getContext('2d');
    ctx.translate(canvas.width, 0); ctx.scale(-1, 1); ctx.drawImage(video, 0, 0);
    const dataUrl = canvas.toDataURL('image/jpeg', 0.92);
    showPreview(dataUrl);
    const b64 = document.getElementById('foto-base64-input');
    if (b64) b64.value = dataUrl;
    capturedBlob = dataUrl; selectedFile = null;
    stopCamera(); showSaveArea();
}
function updateAvatarOnPage(url) {
    const wrap = document.querySelector('.profile-avatar-wrap'); if (!wrap) return;
    let img = wrap.querySelector('.main-avatar, #profile-avatar-preview');
    const initials = document.getElementById('profile-avatar-initials');
    if (!img) { img = document.createElement('img'); img.className = 'main-avatar'; img.id = 'profile-avatar-preview'; wrap.appendChild(img); }
    img.src = url + '?t=' + Date.now(); img.style.display = 'block';
    if (initials) initials.style.display = 'none';
    const btnDelete = document.getElementById('btn-delete-photo');
    if (btnDelete) btnDelete.style.display = 'flex';
}

// ─── TOAST ────────────────────────────────────────────────────────────────────
function showToast(icon, msg) {
    const t = document.getElementById('toast');
    if (t) {
        const ti = document.getElementById('toastIcon');
        const tm = document.getElementById('toastMsg');
        if (ti) ti.textContent = icon;
        if (tm) tm.textContent = msg;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 2800);
    } else {
        const el = document.createElement('div');
        el.className   = 'toast-notif';
        el.textContent = (icon + ' ' + msg).trim();
        document.body.appendChild(el);
        setTimeout(() => el.remove(), 3000);
    }
}

// ─── PASSWORD HELPERS ─────────────────────────────────────────────────────────
function togglePw(inputId) {
    const input = document.getElementById(inputId); if (!input) return;
    input.type = input.type === 'text' ? 'password' : 'text';
}
function checkStrength(val) {
    const bar   = document.getElementById('strength-bar');
    const label = document.getElementById('strength-label');
    if (!bar || !label) return;
    let score = 0;
    if (val.length >= 8)          score++;
    if (/[A-Z]/.test(val))        score++;
    if (/[0-9]/.test(val))        score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const levels = [
        { pct: '25%', color: '#e53e3e', text: 'Lemah' },
        { pct: '50%', color: '#dd6b20', text: 'Cukup' },
        { pct: '75%', color: '#d69e2e', text: 'Kuat'  },
        { pct: '100%',color: '#38a169', text: 'Sangat Kuat' }
    ];
    const lvl = levels[Math.max(0, score - 1)];
    if (!val.length) { bar.style.width = '0%'; label.textContent = ''; return; }
    bar.style.width      = lvl.pct;
    bar.style.background = lvl.color;
    label.textContent    = lvl.text;
    label.style.color    = lvl.color;
}
function checkMatch() {
    const pw      = document.getElementById('new_password');
    const confirm = document.getElementById('password_confirmation');
    const label   = document.getElementById('match-label');
    if (!pw || !confirm || !label) return;
    if (!confirm.value) { label.textContent = ''; return; }
    const match        = pw.value === confirm.value;
    label.textContent  = match ? '✅ Password cocok' : '❌ Password tidak cocok';
    label.style.color  = match ? '#38a169' : '#e53e3e';
}

// ─── FAVORITES ────────────────────────────────────────────────────────────────
window.favorites = JSON.parse(localStorage.getItem('seoullicious_fav') || '[]');
function saveFavorites() { localStorage.setItem('seoullicious_fav', JSON.stringify(window.favorites)); }

function updateFavBadge() {
    const badge = document.getElementById('favBadge');
    if (badge) {
        badge.textContent   = window.favorites.length;
        badge.style.display = window.favorites.length > 0 ? 'flex' : 'none';
    }
    const fb = document.getElementById('favBtn');
    if (fb) fb.classList.toggle('active', window.favorites.length > 0);
}
function openFav() {
    requireAuth(() => {
        renderFav();
        const fp = document.getElementById('favPanel');
        const fo = document.getElementById('favOverlay');
        if (fp) fp.classList.add('open');
        if (fo) fo.classList.add('open');
        document.body.classList.add('panel-open');
    });
}
function closeFav() {
    const fp = document.getElementById('favPanel');
    const fo = document.getElementById('favOverlay');
    if (fp) fp.classList.remove('open');
    if (fo) fo.classList.remove('open');
    document.body.classList.remove('panel-open');
}
function openProfile() {
    requireAuth(() => {
        const pp = document.getElementById('profilePanel');
        const po = document.getElementById('profileOverlay');
        if (pp) pp.classList.add('open');
        if (po) po.classList.add('open');
        document.body.classList.add('panel-open');
        const pfc = document.getElementById('profileFavCount');
        if (pfc) pfc.textContent = window.favorites.length;
    });
}
function closeProfile() {
    const pp = document.getElementById('profilePanel');
    const po = document.getElementById('profileOverlay');
    if (pp) pp.classList.remove('open');
    if (po) po.classList.remove('open');
    document.body.classList.remove('panel-open');
}

// ─── MENU FUNCTIONS ───────────────────────────────────────────────────────────
function menuImgHTML(m) {
    return `<div class="menu-img"><img src="${m.img}" alt="${m.name}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"><div class="menu-img-fallback" style="display:none">${m.emoji}</div></div>`;
}

function renderMenu(filter) {
    filter = filter || 'all';
    const grid     = document.getElementById('menuGrid');
    const menuData = getMenuData();
    if (!grid || !menuData.length) return;
    const items = filter === 'all' ? menuData : menuData.filter(m => m.cat === filter);
    grid.innerHTML = items.map(m => {
        const isFav      = window.favorites.includes(m.id);
        const stockLabel = m.stock === 0
            ? `<div class="out-of-stock">Habis</div>`
            : m.stock <= 5 ? `<div class="low-stock">Sisa ${m.stock}</div>` : '';
        return `<div class="menu-card" data-id="${m.id}">
            ${stockLabel}
            ${menuImgHTML(m)}
            <div class="menu-info">
                <div class="menu-name">${m.name}</div>
                <div class="menu-desc">${(m.desc || '').substring(0, 80)}...</div>
                <div class="menu-bottom">
                    <div class="menu-price">${formatRp(m.price)}</div>
                    <div class="menu-actions">
                        <button class="fav-btn${isFav ? ' active' : ''}" onclick="toggleFav(event,${m.id})">${isFav ? '❤️' : '🤍'}</button>
                        <button class="detail-btn" onclick='openMenuPopup(${JSON.stringify(m)})'>Selengkapnya</button>
                    </div>
                </div>
            </div>
        </div>`;
    }).join('');
    setTimeout(() => {
        grid.querySelectorAll('.menu-card').forEach((c, i) => {
            c.style.transitionDelay = (i * 0.07) + 's';
            c.classList.add('visible');
        });
    }, 40);
}

function filterMenu(cat, btn) {
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    if (btn) btn.classList.add('active');
    renderMenu(cat);
}

function renderFav() {
    const body     = document.getElementById('favBody'); if (!body) return;
    const menuData = getMenuData();
    if (!menuData.length || window.favorites.length === 0) {
        body.innerHTML = `<div class="fav-empty"><div style="font-size:2.8rem">💔</div><div style="font-size:0.92rem">Belum ada menu favorit</div></div>`;
        return;
    }
    body.innerHTML = window.favorites.map(id => {
        const m = menuData.find(x => x.id === id); if (!m) return '';
        return `<div class="fav-item">
            <div class="fav-item-img"><img src="${m.img}" alt="${m.name}" style="width:100%;height:100%;object-fit:cover;border-radius:8px" onerror="this.style.display='none'"></div>
            <div class="fav-item-info"><div class="fav-item-name">${m.name}</div><div class="fav-item-price">${formatRp(m.price)}</div></div>
            <button class="fav-item-add" onclick="addToCartFromFav(${id})">+ Keranjang</button>
            <button class="fav-item-remove" onclick="toggleFav(null,${id})">✕</button>
        </div>`;
    }).join('');
}

function toggleFav(e, id) {
    if (e) e.stopPropagation();
    if (!requireAuth()) return;
    const menuData = getMenuData();
    if (!menuData.length) return;
    const idx = window.favorites.indexOf(id);
    const m   = menuData.find(x => x.id === id); if (!m) return;
    if (idx === -1) {
        window.favorites.push(id);
        Swal.fire({ icon: 'success', title: '❤️ Favorit!', text: `${m.name} ditambahkan ke favorit`, confirmButtonColor: '#8B1A1A', timer: 1500, showConfirmButton: false });
    } else {
        window.favorites.splice(idx, 1);
        Swal.fire({ icon: 'info', title: '💔 Dihapus!', text: `${m.name} dihapus dari favorit`, confirmButtonColor: '#8B1A1A', timer: 1500, showConfirmButton: false });
    }
    saveFavorites(); updateFavBadge(); renderMenu(); renderFav();
    const pfc = document.getElementById('profileFavCount'); if (pfc) pfc.textContent = window.favorites.length;
}

let currentMenuId = null, popupQty = 1;

function openMenuPopup(produk) {
    currentMenuId = produk.id;
    popupQty      = 1;

    document.getElementById('popupQty').textContent       = '1';
    document.getElementById('popupName').textContent      = produk.name;
    document.getElementById('popupDesc').textContent      = produk.desc || '';
    document.getElementById('popupCookTime').textContent  = produk.cookTime || '15 menit';
    document.getElementById('popupSpicy').textContent     = produk.spicy || 'Tidak pedas';
    document.getElementById('popupCalori').textContent    = produk.calori || '-';
    document.getElementById('popupPortion').textContent   = produk.portion || '1 porsi';
    document.getElementById('popupPrice').textContent     = formatRp(produk.price);
    document.getElementById('popupStockNum').textContent  = produk.stock + ' porsi';
    const stockPercent =
    Math.min((produk.stock / 20) * 100, 100);
    document.getElementById('stockBarFill').style.width =
    stockPercent + '%';
    document.getElementById('formProdukId').value         = produk.id;
    document.getElementById('formJumlah').value           = 1;

    document.getElementById('popupEmoji').innerHTML =
        `<img src="${produk.img}" style="width:100%;height:100%;object-fit:cover;border-radius:inherit">`;

    document.getElementById('menuPopupBackdrop').classList.add('open');
    document.body.classList.add('panel-open');
}

function closeMenuPopup(e, force) {
    const backdrop = document.getElementById('menuPopupBackdrop');
    if (!backdrop) return;
    if (force || !e || e.target === backdrop) {
        backdrop.classList.remove('open');
        document.body.classList.remove('panel-open');
    }
}

function togglePopupFav() {
    toggleFav(null, currentMenuId);
    const isFav = window.favorites.includes(currentMenuId);
    const fb    = document.getElementById('popupFavBtn');
    if (fb) { fb.textContent = isFav ? '❤️' : '🤍'; fb.classList.toggle('active', isFav); }
}

// ─── ADD TO CART (FAVORIT) ────────────────────────────────────────────────────
async function addToCartFromFav(id) {
    const menuData = getMenuData();
    const m        = menuData.find(x => x.id === id);
    if (!m || !requireAuth()) return;
    try {
        const res = await fetch('/cart/add', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ produk_id: m.id, jumlah: 1 })
        });
        const data = await res.json();
        if (data.success) {
            showToast('🛒', `${m.name} ditambahkan!`);
            updateCartBadge(data.cartCount);
        } else {
            showToast('❌', data.message || 'Gagal menambahkan ke keranjang.');
        }
    } catch (e) {
        showToast('❌', 'Terjadi kesalahan koneksi.');
    }
}

// ─── SEARCH ───────────────────────────────────────────────────────────────────
function searchSelect(id) {
    const sr = document.getElementById('searchResults'); if (sr) sr.classList.remove('open');
    const si = document.getElementById('searchInput');   if (si) si.value = '';
    openMenuPopup(id);
}
document.addEventListener('click', e => {
    if (!e.target.closest('.nav-search')) {
        const sr = document.getElementById('searchResults');
        if (sr) sr.classList.remove('open');
    }
});

// ─── REVIEW MODAL ─────────────────────────────────────────────────────────────
let reviewRating = 0;
const ratingLabels = ['', 'Kurang 😕', 'Cukup 😐', 'Bagus 😊', 'Sangat Bagus 😄', 'Luar Biasa! 🤩'];
const avatarEmoji  = ['👨', '👩', '🧑', '👦', '👧'];

function openReviewModal() {
    if (!requireAuth()) return;
    reviewRating = 0;
    ['reviewName', 'reviewCity', 'reviewMenu', 'reviewText'].forEach(id => {
        const el = document.getElementById(id); if (el) el.value = '';
    });
    const cc = document.getElementById('reviewCharCount');   if (cc) cc.textContent = '(0/300)';
    const rl = document.getElementById('reviewRatingLabel'); if (rl) rl.textContent = 'Tap bintang untuk memberi rating';
    updateStarUI(0);
    const modal = document.getElementById('reviewModalBackdrop');
    if (modal) { modal.classList.add('open'); document.body.classList.add('panel-open'); }
}
function closeReviewModal(e, force) {
    const modal = document.getElementById('reviewModalBackdrop');
    if (!modal) return;
    if (force || !e || e.target === modal) {
        modal.classList.remove('open');
        document.body.classList.remove('panel-open');
    }
}
function setRating(val) {
    reviewRating = val; updateStarUI(val);
    const lbl = document.getElementById('reviewRatingLabel');
    if (lbl) { lbl.textContent = ratingLabels[val]; lbl.style.color = val >= 4 ? 'var(--gold)' : val === 3 ? '#9A7070' : 'var(--red)'; }
     const ri = document.getElementById('ratingInput');
    if (ri) ri.value = val;
}
function updateStarUI(val) {
    document.querySelectorAll('.star-btn').forEach(btn => btn.classList.toggle('active', parseInt(btn.dataset.val) <= val));
}
function updateCharCount(el) {
    const len = el.value.length;
    const cc  = document.getElementById('reviewCharCount'); if (cc) cc.textContent = `(${len}/300)`;
    if (len > 300) el.value = el.value.slice(0, 300);
}
function submitReview() {
    const name = (document.getElementById('reviewName') || {}).value?.trim() || '';
    const city = (document.getElementById('reviewCity') || {}).value?.trim() || '';
    const text = (document.getElementById('reviewText') || {}).value?.trim() || '';
    if (!reviewRating)          { Swal.fire({ icon: 'warning', title: 'Rating Kosong',  text: 'Kasih rating dulu yuk!',      confirmButtonColor: '#8B1A1A' }); return; }
    if (!name)                  { Swal.fire({ icon: 'warning', title: 'Nama Kosong',    text: 'Nama kamu belum diisi!',      confirmButtonColor: '#8B1A1A' }); return; }
    if (!text || text.length < 10) { Swal.fire({ icon: 'warning', title: 'Ulasan Pendek', text: 'Minimal 10 karakter.', confirmButtonColor: '#8B1A1A' }); return; }
    const starsStr = '★'.repeat(reviewRating) + '☆'.repeat(5 - reviewRating);
    const avatar   = avatarEmoji[Math.floor(Math.random() * avatarEmoji.length)];
    const card     = document.createElement('div');
    card.className = 'testi-card new-review';
    card.innerHTML = `<div class="testi-stars">${starsStr}</div><div class="testi-text">"${text}"</div><div class="testi-author"><div class="testi-avatar">${avatar}</div><div><div class="testi-name">${name}</div><div class="testi-loc">${city || 'Indonesia'}</div></div></div>`;
    const track = document.getElementById('testiTrack');
    if (track) track.insertBefore(card, track.firstChild);
    if (window._testiSetup) window._testiSetup();
    closeReviewModal(null, true);
    Swal.fire({ icon: 'success', title: 'Terima Kasih! 🎉', text: 'Ulasanmu berhasil dikirim!', confirmButtonColor: '#8B1A1A', timer: 2000, showConfirmButton: false });
}

// ─── TESTIMONIALS SLIDER ──────────────────────────────────────────────────────
(function () {
    const track    = document.getElementById('testiTrack');
    const outer    = document.getElementById('testiOuter');
    const dotsWrap = document.getElementById('testiDots');
    const prevBtn  = document.getElementById('testiPrev');
    const nextBtn  = document.getElementById('testiNext');
    if (!track) return;
    const cards = track.querySelectorAll('.testi-card');
    let current = 0, perView = 3, autoTimer, touchStartX = 0;
    function getPerView()   { const w = window.innerWidth; if (w < 768) return 1; if (w < 1024) return 2; return 3; }
    function totalPages()   { return Math.ceil(cards.length / perView); }
    function buildDots() {
        if (!dotsWrap) return;
        dotsWrap.innerHTML = '';
        for (let i = 0; i < totalPages(); i++) {
            const d = document.createElement('button');
            d.className = 'testi-dot' + (i === 0 ? ' active' : '');
            d.setAttribute('aria-label', 'Halaman ' + (i + 1));
            d.onclick = () => goTo(i);
            dotsWrap.appendChild(d);
        }
    }
    function goTo(page) {
        current = Math.max(0, Math.min(page, totalPages() - 1));
        const cardW = cards[0].offsetWidth + 22;
        track.style.transform = `translateX(${-current * perView * cardW}px)`;
        if (dotsWrap) dotsWrap.querySelectorAll('.testi-dot').forEach((d, i) => d.classList.toggle('active', i === current));
        if (prevBtn) prevBtn.disabled = current === 0;
        if (nextBtn) nextBtn.disabled = current >= totalPages() - 1;
    }
    function setup() {
        perView = getPerView();
        if (!outer) return;
        const gap = 22, outerW = outer.offsetWidth, cardW = (outerW - gap * (perView - 1)) / perView;
        cards.forEach(c => { c.style.flexBasis = cardW + 'px'; c.style.minWidth = cardW + 'px'; });
        current = 0; buildDots(); goTo(0);
    }
    function startAuto() { clearInterval(autoTimer); autoTimer = setInterval(() => goTo(current + 1 >= totalPages() ? 0 : current + 1), 5000); }
    window.testiSlide = function (dir) { clearInterval(autoTimer); goTo(current + dir); startAuto(); };
    if (outer) {
        outer.addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; clearInterval(autoTimer); }, { passive: true });
        outer.addEventListener('touchend',   e => { const dx = e.changedTouches[0].clientX - touchStartX; if (Math.abs(dx) > 50) goTo(current + (dx < 0 ? 1 : -1)); startAuto(); }, { passive: true });
        outer.addEventListener('mouseenter', () => clearInterval(autoTimer));
        outer.addEventListener('mouseleave', startAuto);
    }
    if (cards.length > 0) { setup(); startAuto(); }
    window.addEventListener('resize', setup);
    window._testiSetup = setup;
})();

// ─── DOM READY ────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {

    // Flash messages (dipindah ke sini, bukan di window.load)
    @if(session('success'))
    setTimeout(() => Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', confirmButtonColor: '#8B1A1A', timer: 3000 }), 2200);
    @endif
    @if(session('error'))
    setTimeout(() => Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}', confirmButtonColor: '#8B1A1A' }), 2200);
    @endif
    @if(session('warning'))
    setTimeout(() => Swal.fire({ icon: 'warning', title: 'Peringatan', text: '{{ session('warning') }}', confirmButtonColor: '#8B1A1A' }), 2200);
    @endif

    const uploadForm = document.getElementById('upload-photo-form');
    if (uploadForm) {
        uploadForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData  = new FormData();
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            formData.append('_token', csrfToken);
            if (selectedFile)      formData.append('foto', selectedFile, selectedFile.name);
            else if (capturedBlob) formData.append('foto_base64', capturedBlob);
            else { alert('Pilih foto terlebih dahulu.'); return; }
            const btnSave = this.querySelector('.photo-btn-save');
            if (btnSave) { btnSave.disabled = true; btnSave.textContent = 'Menyimpan...'; }
            fetch(this.action, { method: 'POST', credentials: 'same-origin', body: formData, headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(data => {
                    if (data.success) { updateAvatarOnPage(data.url); closePhotoModal(); showToast('✅', 'Foto profil berhasil diperbarui!'); }
                    else alert(data.message || 'Gagal mengunggah foto.');
                })
                .catch(() => alert('Terjadi kesalahan koneksi.'))
                .finally(() => { if (btnSave) { btnSave.disabled = false; btnSave.textContent = '💾 Simpan Foto'; } });
        });
    }

    const menuData = getMenuData();
    if (menuData.length > 0) renderMenu('all');
    updateFavBadge();

    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const data = getMenuData();
            const q    = this.value.trim().toLowerCase();
            const sr   = document.getElementById('searchResults');
            if (!sr) return;
            if (!q) { sr.classList.remove('open'); return; }
            const results = data.filter(m => m.name.toLowerCase().includes(q) || (m.desc && m.desc.toLowerCase().includes(q)));
            sr.innerHTML  = results.length === 0
                ? `<div class="search-no-result">Tidak ada hasil untuk "${q}"</div>`
                : results.map(m => `<div class="search-result-item" onclick="searchSelect(${m.id})"><div class="search-result-icon"><img src="${m.img}" alt="${m.name}" style="width:100%;height:100%;object-fit:cover;border-radius:6px" onerror="this.style.display='none'"></div><div><div class="search-result-name">${m.name}</div><div class="search-result-price">${formatRp(m.price)}</div></div></div>`).join('');
            sr.classList.add('open');
        });
    }

    const mobileSearch = document.getElementById('mobileSearchInput');
    if (mobileSearch) {
        mobileSearch.addEventListener('input', function () {
            const data    = getMenuData();
            const q       = this.value.trim().toLowerCase(); if (!q) return;
            const results = data.filter(m => m.name.toLowerCase().includes(q));
            if (results.length > 0) { closeMobileMenu(); openMenuPopup(results[0]); this.value = ''; }
        });
    }

    document.querySelectorAll('.star-btn').forEach(btn => {
        btn.addEventListener('mouseenter', () => {
            const val = parseInt(btn.dataset.val);
            document.querySelectorAll('.star-btn').forEach(b => b.classList.toggle('hovered', parseInt(b.dataset.val) <= val));
        });
        btn.addEventListener('mouseleave', () => document.querySelectorAll('.star-btn').forEach(b => b.classList.remove('hovered')));
    });

    const greetingEl = document.getElementById('greeting-typed');
    if (greetingEl) {
        const namaUser = "{{ addslashes(optional(Auth::user())->name ?? 'Pelanggan') }}".split(' ')[0] || 'Pelanggan';
        const messages = [`Halo, ${namaUser}! 👋 Selamat datang kembali.`, `Semoga harimu menyenangkan! ☀️`, `Mau pesan apa hari ini? 🍜`];
        let msgIdx = 0, charIdx = 0, deleting = false;
        function type() {
            const cur = messages[msgIdx];
            greetingEl.textContent = deleting ? cur.slice(0, --charIdx) : cur.slice(0, ++charIdx);
            if (!deleting && charIdx === cur.length) { deleting = true; setTimeout(type, 2200); return; }
            if (deleting && charIdx === 0) { deleting = false; msgIdx = (msgIdx + 1) % messages.length; }
            setTimeout(type, deleting ? 35 : 65);
        }
        setTimeout(type, 500);
    }

    document.querySelectorAll('.faq-question').forEach(button => {
        button.addEventListener('click', () => {
            const faqItem  = button.parentElement;
            const isActive = faqItem.classList.contains('active');
            document.querySelectorAll('.faq-item').forEach(item => item.classList.remove('active'));
            if (!isActive) faqItem.classList.add('active');
        });
    });
});
</script>

@yield('scripts')
@stack('scripts')
@if(request()->routeIs('profile.*'))
    <script src="{{ asset('js/profile.js') }}"></script>
@endif
</body>
</html>