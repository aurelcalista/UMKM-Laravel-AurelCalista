/**
 * profile.js — Seoullicious
 *
 * File ini di-load HANYA di halaman profile (route profile.*).
 * Fungsi-fungsi seperti spSwitch, spTogglePw, openPhotoModal, closePhotoModal,
 * spCheckStrength, spCheckMatch, dll sudah di-define di @push('scripts')
 * dalam profile.blade.php dan sudah berjalan dengan benar.
 *
 * File ini HANYA bertugas:
 * 1. Inisialisasi event listener yang perlu setup setelah DOM ready
 * 2. Hal-hal yang tidak bisa diinline di blade (misal: kompleks / reusable)
 *
 * JANGAN re-define fungsi yang sudah ada di @push('scripts') profile.blade.php
 * karena akan menyebabkan konflik dan override fungsi yang benar.
 */

document.addEventListener('DOMContentLoaded', function () {

    // ─── Tutup modal saat klik backdrop ─────────────────────────────────────
    var modalIds = ['spPhotoModal', 'spLogoutConfirm', 'spDeleteConfirm'];
    modalIds.forEach(function (id) {
        var el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('click', function (e) {
            if (e.target === this) {
                this.classList.remove('open');
                document.body.style.overflow = '';
            }
        });
    });

    // ─── Auto-open panel jika ada hash di URL ───────────────────────────────
    // Contoh: profile#riwayat → buka panel riwayat
    var hash = window.location.hash.replace('#', '');
    var validPanels = ['biodata', 'pesanan', 'riwayat', 'favorit', 'akun'];
    if (hash && validPanels.includes(hash)) {
        var btn = document.querySelector('[onclick*="spSwitch(\'' + hash + '\'"]');
        if (typeof spSwitch === 'function') spSwitch(hash, btn);
    }

    // ─── Upload form: bind submit handler ────────────────────────────────────
    // Handler utama sudah ada di @push('scripts') profile.blade.php.
    // Di sini hanya pastikan tombol simpan tidak di-submit dua kali.
    var spUpForm = document.getElementById('spUploadForm');
    if (spUpForm) {
        // Cegah double-submit
        spUpForm.addEventListener('submit', function () {
            var btn = this.querySelector('button[type="submit"]');
            if (btn && !btn.disabled) {
                // Dibiarkan handler utama yang handle, hanya tambahkan loading visual
                setTimeout(function () {
                    if (btn) btn.textContent = '⏳ Menyimpan...';
                }, 10);
            }
        });
    }

    // ─── Sidebar nav: mark active dari URL ──────────────────────────────────
    // Jika user baru datang (bukan klik navigasi), coba baca session open_panel
    // yang sudah di-handle di @push('scripts') blade. Tidak perlu diulang di sini.

    // ─── Keyboard shortcut: Escape menutup modal ────────────────────────────
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        var openModal = document.querySelector(
            '#spPhotoModal.open, #spLogoutConfirm.open, #spDeleteConfirm.open'
        );
        if (!openModal) return;
        openModal.classList.remove('open');
        document.body.style.overflow = '';
    });

    // ─── Mobile: sidebar nav jadi select di layar kecil (opsional) ──────────
    // Jika lebar layar < 640px dan user klik nav item, scroll ke panel
    var navItems = document.querySelectorAll('.sp-nav-item');
    navItems.forEach(function (item) {
        item.addEventListener('click', function () {
            if (window.innerWidth < 768) {
                var panels = document.querySelector('.sp-panels');
                if (panels) {
                    setTimeout(function () {
                        panels.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 100);
                }
            }
        });
    });

    // ─── Animasi masuk untuk stat pills ──────────────────────────────────────
    var statPills = document.querySelectorAll('.sp-stat-pill, .sp-mini-stat');
    if (statPills.length && 'IntersectionObserver' in window) {
        var statObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry, i) {
                if (entry.isIntersecting) {
                    setTimeout(function () {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, i * 80);
                    statObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        statPills.forEach(function (el) {
            el.style.opacity = '0';
            el.style.transform = 'translateY(16px)';
            el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            statObserver.observe(el);
        });
    }

    // ─── Order card hover effect ─────────────────────────────────────────────
    document.querySelectorAll('.sp-order-card').forEach(function (card) {
        card.addEventListener('mouseenter', function () {
            this.style.transform = 'translateY(-2px)';
            this.style.transition = 'transform 0.2s ease';
        });
        card.addEventListener('mouseleave', function () {
            this.style.transform = '';
        });
    });

});