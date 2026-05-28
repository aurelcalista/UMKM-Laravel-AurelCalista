@extends('layouts.app')

@section('title', 'Edit Profil')
@section('body-class', 'profile-page')

@section('content')

<style>
/* ── WRAP ── */
.ep-wrap {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--cream, #FDF8F0);
    min-height: 100vh;
    padding: 96px 0 64px;
}
.ep-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 24px;
}

/* ── PAGE HEADER ── */
.ep-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 28px;
}
.ep-back-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 16px;
    border-radius: 50px;
    background: white;
    border: 1.5px solid rgba(123,24,24,0.12);
    color: #7B1818;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
    font-family: inherit;
}
.ep-back-btn:hover { background: #FFF0F0; border-color: #7B1818; }
.ep-back-btn svg { width: 15px; height: 15px; fill: #7B1818; }
.ep-header-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.8rem;
    font-weight: 700;
    color: #1C0A0A;
}
.ep-header-sub { font-size: 0.82rem; color: #8C6E6E; margin-top: 2px; }

/* ── LAYOUT ── */
.ep-body {
    display: grid;
    grid-template-columns: 220px 1fr;
    gap: 22px;
    align-items: start;
}

/* ── SIDEBAR NAV ── */
.ep-sidebar {
    background: white;
    border-radius: 20px;
    border: 1px solid rgba(123,24,24,0.10);
    box-shadow: 0 2px 12px rgba(28,10,10,0.06);
    overflow: hidden;
    position: sticky;
    top: 110px;
}
.ep-nav-section {
    font-size: 0.6rem;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #B89898;
    font-weight: 700;
    padding: 14px 16px 6px;
}
.ep-nav-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 11px 16px;
    font-size: 0.83rem;
    font-weight: 500;
    color: #2D1515;
    cursor: pointer;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    font-family: inherit;
    text-decoration: none;
    transition: background 0.2s, color 0.2s;
    position: relative;
}
.ep-nav-item svg { width: 16px; height: 16px; fill: #8C6E6E; flex-shrink: 0; transition: fill 0.2s; }
.ep-nav-item:hover { background: #FDF8F0; color: #7B1818; }
.ep-nav-item:hover svg { fill: #7B1818; }
.ep-nav-item.active {
    background: linear-gradient(135deg, #FFF0F0, #FAF0DC);
    color: #7B1818;
    font-weight: 700;
}
.ep-nav-item.active svg { fill: #7B1818; }
.ep-nav-item.active::before {
    content: '';
    position: absolute;
    left: 0; top: 18%; bottom: 18%;
    width: 3px;
    background: #7B1818;
    border-radius: 0 3px 3px 0;
}
.ep-nav-item.danger { color: #C53030; }
.ep-nav-item.danger svg { fill: #C53030; }
.ep-nav-item.danger:hover { background: #FFF5F5; }
.ep-nav-divider { height: 1px; background: rgba(123,24,24,0.08); margin: 6px 0; }

/* ── PANELS ── */
.ep-panel { display: none; }
.ep-panel.active { display: block; }

/* ── CARD ── */
.ep-card {
    background: white;
    border-radius: 20px;
    border: 1px solid rgba(123,24,24,0.10);
    box-shadow: 0 2px 12px rgba(28,10,10,0.06);
    overflow: hidden;
    margin-bottom: 20px;
}
.ep-card-header {
    padding: 22px 28px 18px;
    border-bottom: 1px solid rgba(123,24,24,0.08);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}
.ep-card-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.3rem;
    font-weight: 700;
    color: #1C0A0A;
}
.ep-card-desc {
    font-size: 0.79rem;
    color: #8C6E6E;
    margin-top: 3px;
}
.ep-card-body { padding: 26px 28px; }

/* ── FORM ── */
.ep-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
    margin-bottom: 18px;
}
.ep-form-row.single { grid-template-columns: 1fr; }
.ep-form-field { display: flex; flex-direction: column; gap: 6px; }
.ep-form-label {
    font-size: 0.68rem;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: #8C6E6E;
    font-weight: 700;
}
.ep-form-label .req { color: #7B1818; }
.ep-form-input {
    width: 100%;
    padding: 11px 14px;
    border: 1.5px solid rgba(123,24,24,0.15);
    border-radius: 10px;
    font-size: 0.9rem;
    font-family: inherit;
    color: #1C0A0A;
    background: #FDF8F0;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
}
.ep-form-input:focus {
    border-color: #7B1818;
    background: white;
    box-shadow: 0 0 0 3px rgba(123,24,24,0.08);
}
.ep-form-input:disabled, .ep-form-input[readonly] {
    opacity: 0.55;
    cursor: not-allowed;
    background: #F5EDD8;
}
.ep-form-input.is-error { border-color: #E53E3E; }
textarea.ep-form-input { min-height: 90px; resize: vertical; }
.ep-field-note { font-size: 0.7rem; color: #B89898; }
.ep-input-error { font-size: 0.72rem; color: #E53E3E; font-weight: 500; }

/* Password input */
.ep-input-pw { position: relative; }
.ep-input-pw .ep-form-input { padding-right: 44px; }
.ep-toggle-pw {
    position: absolute;
    right: 12px; top: 50%;
    transform: translateY(-50%);
    background: none; border: none;
    cursor: pointer; color: #8C6E6E;
    transition: color 0.2s;
    padding: 4px; line-height: 1;
}
.ep-toggle-pw:hover { color: #7B1818; }
.ep-toggle-pw svg { width: 16px; height: 16px; fill: currentColor; }

/* Strength */
.ep-strength-wrap {
    height: 3px;
    background: rgba(123,24,24,0.1);
    border-radius: 50px;
    overflow: hidden;
    margin-top: 6px;
}
.ep-strength-bar { height: 100%; border-radius: 50px; transition: width 0.4s, background 0.4s; width: 0; }
.ep-strength-label { font-size: 0.7rem; margin-top: 3px; }
.ep-match-label { font-size: 0.72rem; margin-top: 3px; }

/* Submit */
.ep-btn-save {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #7B1818;
    color: #FDF8F0;
    padding: 12px 26px;
    border-radius: 50px;
    font-size: 0.86rem;
    font-weight: 700;
    border: none;
    cursor: pointer;
    font-family: inherit;
    transition: all 0.3s;
    box-shadow: 0 4px 16px rgba(123,24,24,0.25);
}
.ep-btn-save:hover { background: #9E2020; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(123,24,24,0.32); }
.ep-btn-save svg { width: 15px; height: 15px; fill: currentColor; }

/* Alert */
.ep-alert {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-size: 0.84rem;
    font-weight: 500;
}
.ep-alert-success { background: #F0FFF4; color: #276749; border: 1px solid #9AE6B4; }
.ep-alert-error   { background: #FFF5F5; color: #C53030; border: 1px solid #FEB2B2; }

/* Danger zone */
.ep-danger-zone {
    border: 1.5px solid #FEB2B2;
    border-radius: 16px;
    padding: 22px 24px;
    background: #FFF5F5;
}
.ep-danger-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.1rem;
    font-weight: 700;
    color: #C53030;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.ep-danger-title svg { width: 18px; height: 18px; fill: #C53030; }
.ep-danger-desc { font-size: 0.82rem; color: #9B2C2C; line-height: 1.6; margin-bottom: 16px; }
.ep-btn-danger {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #C53030;
    color: white;
    padding: 11px 22px;
    border-radius: 50px;
    font-size: 0.82rem;
    font-weight: 700;
    border: none;
    cursor: pointer;
    font-family: inherit;
    transition: background 0.2s;
}
.ep-btn-danger:hover { background: #9B2C2C; }

/* Delete confirm modal */
.ep-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(28,10,10,0.75);
    backdrop-filter: blur(8px);
    z-index: 1300;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s;
}
.ep-modal-overlay.open { opacity: 1; pointer-events: all; }
.ep-modal {
    background: white;
    border-radius: 24px;
    max-width: 400px;
    width: 100%;
    padding: 32px 28px;
    text-align: center;
    transform: scale(0.95);
    transition: transform 0.3s;
}
.ep-modal-overlay.open .ep-modal { transform: scale(1); }
.ep-modal-icon { font-size: 3rem; margin-bottom: 16px; }
.ep-modal-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.4rem;
    font-weight: 700;
    color: #C53030;
    margin-bottom: 8px;
}
.ep-modal-desc { font-size: 0.84rem; color: #8C6E6E; line-height: 1.65; margin-bottom: 20px; }
.ep-modal-actions { display: flex; gap: 10px; }
.ep-modal-actions button, .ep-modal-actions .ep-btn-cancel { flex: 1; }
.ep-btn-cancel {
    padding: 12px 20px;
    border-radius: 50px;
    border: 1.5px solid rgba(123,24,24,0.15);
    background: transparent;
    font-size: 0.86rem;
    font-weight: 600;
    cursor: pointer;
    font-family: inherit;
    color: #2D1515;
    transition: background 0.2s;
}
.ep-btn-cancel:hover { background: #FDF8F0; }

/* Responsive */
@media (max-width: 768px) {
    .ep-body { grid-template-columns: 1fr; }
    .ep-sidebar {
        position: static;
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        padding: 12px;
        border-radius: 16px;
    }
    .ep-nav-section, .ep-nav-divider { display: none; }
    .ep-nav-item {
        width: auto;
        padding: 8px 14px;
        border-radius: 50px;
        font-size: 0.78rem;
        border: 1.5px solid rgba(123,24,24,0.15);
        background: #FDF8F0;
    }
    .ep-nav-item.active {
        background: #7B1818;
        color: white;
        border-color: #7B1818;
    }
    .ep-nav-item.active svg { fill: white; }
    .ep-nav-item.active::before { display: none; }
    .ep-form-row { grid-template-columns: 1fr; }
    .ep-card-body { padding: 18px 16px; }
    .ep-card-header { padding: 16px; }
}
@media (max-width: 480px) {
    .ep-wrap { padding: 72px 0 48px; }
    .ep-container { padding: 0 14px; }
}
</style>

<div class="ep-wrap">
<div class="ep-container">

    {{-- Header --}}
    <div class="ep-header">
        <a href="{{ route('profile.show') }}" class="ep-back-btn">
            <svg viewBox="0 0 24 24">
                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
            </svg>
            Kembali
        </a>

        <div>
            <div class="ep-header-title">Pengaturan Akun</div>
            <div class="ep-header-sub">
                Kelola informasi pribadi dan keamanan akunmu
            </div>
        </div>
    </div>

    <div class="ep-body">

        {{-- SIDEBAR --}}
        <aside class="ep-sidebar">

            <div class="ep-nav-section">Pengaturan</div>

            <button class="ep-nav-item active"
                    onclick="epSwitch('profil', this)">
                <svg viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                Edit Profil
            </button>

            <button class="ep-nav-item"
                    onclick="epSwitch('password', this)">
                <svg viewBox="0 0 24 24">
                    <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                </svg>
                Ganti Password
            </button>

            <div class="ep-nav-divider"></div>

            <button class="ep-nav-item danger"
                    onclick="epSwitch('hapus', this)">
                <svg viewBox="0 0 24 24">
                    <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                </svg>
                Hapus Akun
            </button>

        </aside>


        {{-- CONTENT --}}
        <div>

            {{-- PROFILE --}}
            <div class="ep-panel active" id="ep-panel-profil">
                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- PASSWORD --}}
            <div class="ep-panel" id="ep-panel-password">
                @include('profile.partials.update-password-form')
            </div>

            {{-- DELETE --}}
            <div class="ep-panel" id="ep-panel-hapus">
                @include('profile.partials.delete-user-form')
            </div>

        </div>

    </div>
</div>
</div>

@push('scripts')
<script>

function epSwitch(name, el) {
    document.querySelectorAll('.ep-panel')
        .forEach(p => p.classList.remove('active'));

    document.querySelectorAll('.ep-nav-item')
        .forEach(n => n.classList.remove('active'));

    document.getElementById('ep-panel-' + name)
        .classList.add('active');

    el.classList.add('active');
}

function epTogglePw(id) {
    const inp = document.getElementById(id);
    if (inp) {
        inp.type = inp.type === 'password' ? 'text' : 'password';
    }
}

function epCheckStrength(v) {

    const bar = document.getElementById('epStrBar');
    const lbl = document.getElementById('epStrLabel');

    if (!bar || !lbl) return;

    let score = 0;

    if (v.length >= 8) score++;
    if (v.length >= 12) score++;
    if (/[A-Z]/.test(v) && /[a-z]/.test(v)) score++;
    if (/[0-9]/.test(v)) score++;
    if (/[^A-Za-z0-9]/.test(v)) score++;

    const levels = [
        { w:'20%', c:'#E53E3E', t:'Sangat Lemah' },
        { w:'40%', c:'#DD6B20', t:'Lemah' },
        { w:'60%', c:'#D69E2E', t:'Cukup' },
        { w:'80%', c:'#38A169', t:'Kuat' },
        { w:'100%', c:'#276749', t:'Sangat Kuat' },
    ];

    const lvl = levels[Math.min(score, 4)];

    bar.style.width = v ? lvl.w : '0';
    bar.style.background = lvl.c;

    lbl.textContent = v ? lvl.t : '';
    lbl.style.color = lvl.c;
}

function epCheckMatch() {

    const a = document.getElementById('epPwNew');
    const b = document.getElementById('epPwConf');
    const lbl = document.getElementById('epMatchLabel');

    if (!a || !b || !lbl || !b.value) {
        lbl.textContent = '';
        return;
    }

    const match = a.value === b.value;

    lbl.textContent = match
        ? '✓ Password cocok'
        : '✗ Password tidak cocok';

    lbl.style.color = match
        ? '#276749'
        : '#E53E3E';
}

</script>
@endpush

@endsection