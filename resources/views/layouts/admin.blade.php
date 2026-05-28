<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>@yield('title', 'Dashboard') — Seoullicious Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.31.0/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --maroon:       #7B1F1F;
            --maroon-light: #9B2C2C;
            --maroon-mid:   rgba(123,31,31,0.12);
            --cream:        #F5EFE6;
            --cream-dark:   #EDE4D8;
            --sand:         #D4B99A;
            --warm-white:   #FDFAF6;
            --text-dark:    #2C1810;
            --text-body:    #4A3728;
            --text-muted:   #9C8070;
            --gold:         #C9982A;
            --success: #2D7A4F;
            --info:    #1A6B9A;
            --warning: #B07D15;
            --danger:  #B91C1C;
            --sidebar-w: 252px;
            --topbar-h:  60px;
            --radius:    12px;
            --radius-sm: 8px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.875rem;
            background: var(--cream);
            color: var(--text-body);
            -webkit-font-smoothing: antialiased;
        }

        a { text-decoration: none; color: inherit; }

        /* ══════ SIDEBAR ══════ */
        .sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: var(--maroon);
            z-index: 1000;
            display: flex; flex-direction: column;
            transition: width 0.28s cubic-bezier(.4,0,.2,1);
            overflow: hidden;
        }

        .sidebar.collapsed { width: 64px; }

        .sidebar-brand {
            height: var(--topbar-h);
            display: flex; align-items: center;
            padding: 0 1rem; gap: 0.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            flex-shrink: 0;
        }

        .brand-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: rgba(255,255,255,0.15);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; flex-shrink: 0;
        }

        .brand-info { overflow: hidden; white-space: nowrap; flex: 1; transition: opacity 0.2s; }
        .sidebar.collapsed .brand-info { opacity: 0; pointer-events: none; }

        .brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 1rem; font-weight: 700; color: #fff;
        }
        .brand-sub { font-size: 0.6rem; color: rgba(255,255,255,0.45); text-transform: uppercase; letter-spacing: 0.7px; }

        .sidebar-nav { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 0.6rem 0 1rem; }
        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 99px; }

        .nav-section {
            font-size: 0.6rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.9px; color: rgba(255,255,255,0.3);
            padding: 0.85rem 1rem 0.3rem;
            white-space: nowrap; overflow: hidden;
        }
        .sidebar.collapsed .nav-section { height: 0; padding: 0; overflow: hidden; }

        .nav-item {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.55rem 1rem;
            color: rgba(255,255,255,0.65);
            font-size: 0.8rem; font-weight: 500;
            white-space: nowrap; cursor: pointer;
            transition: all 0.15s; position: relative;
        }

        .nav-item i { font-size: 1.1rem; flex-shrink: 0; min-width: 20px; text-align: center; }
        .nav-label { overflow: hidden; white-space: nowrap; }

        .sidebar.collapsed .nav-item { justify-content: center; padding: 0.6rem 0; }
        .sidebar.collapsed .nav-label { display: none; }

        .nav-item:hover { color: #fff; background: rgba(255,255,255,0.1); }

        .nav-item.active {
            color: #fff; background: rgba(255,255,255,0.15); font-weight: 600;
        }
        .nav-item.active::before {
            content: ''; position: absolute; left: 0; top: 15%; bottom: 15%;
            width: 3px; border-radius: 0 3px 3px 0; background: var(--gold);
        }
        .sidebar.collapsed .nav-item.active::before { display: none; }

        .nav-item.danger { color: rgba(255,150,150,0.8); }
        .nav-item.danger:hover { color: #FCA5A5; background: rgba(239,68,68,0.12); }

        /* ══════ TOPBAR ══════ */
        .topbar {
            position: fixed;
            top: 0; left: var(--sidebar-w); right: 0;
            height: var(--topbar-h);
            background: var(--cream);
            border-bottom: 1px solid var(--sand);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 1.25rem 0 0;
            z-index: 900;
            transition: left 0.28s cubic-bezier(.4,0,.2,1);
        }

        .topbar.expanded { left: 64px; }

        /* Toggle selalu ada di topbar */
        .topbar-toggle {
            width: 52px; height: var(--topbar-h);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; border: none; background: transparent;
            color: var(--text-muted); font-size: 1.15rem;
            transition: color 0.15s, background 0.15s;
            flex-shrink: 0;
        }
        .topbar-toggle:hover { color: var(--maroon); background: var(--cream-dark); }

        .topbar-search {
            display: flex; align-items: center; gap: 0.5rem;
            background: var(--warm-white); border: 1px solid var(--sand);
            border-radius: 10px; padding: 0.42rem 0.85rem; min-width: 220px;
        }
        .topbar-search input {
            border: none; outline: none; background: transparent;
            font-size: 0.8rem; color: var(--text-body); font-family: inherit; width: 100%;
        }
        .topbar-search input::placeholder { color: var(--text-muted); }
        .topbar-search i { color: var(--text-muted); font-size: 0.95rem; }

        .topbar-right { display: flex; align-items: center; gap: 0.4rem; }

        .btn-icon {
            width: 38px; height: 38px; border-radius: 10px;
            border: 1px solid var(--sand); background: var(--warm-white);
            color: var(--text-body); cursor: pointer;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 1rem; transition: all 0.15s; position: relative;
        }
        .btn-icon:hover { background: var(--maroon); border-color: var(--maroon); color: #fff; }

        .notif-dot {
            position: absolute; top: 8px; right: 8px;
            width: 7px; height: 7px; border-radius: 50%;
            background: #EF4444; border: 1.5px solid var(--cream);
        }

        .user-pill {
            display: flex; align-items: center; gap: 0.55rem;
            background: var(--warm-white); border: 1px solid var(--sand);
            border-radius: 10px; padding: 0.3rem 0.65rem 0.3rem 0.35rem;
            cursor: pointer; transition: border-color 0.15s;
        }
        .user-pill:hover { border-color: var(--maroon); }

        .avatar {
            width: 30px; height: 30px; border-radius: 8px;
            background: var(--maroon); color: #fff;
            font-size: 0.75rem; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
        }
        .user-name { font-size: 0.78rem; font-weight: 600; color: var(--text-dark); }
        .user-role { font-size: 0.67rem; color: var(--text-muted); }

        /* ══════ CONTENT ══════ */
        .content {
            margin-left: var(--sidebar-w);
            margin-top: var(--topbar-h);
            min-height: calc(100vh - var(--topbar-h));
            padding: 1.5rem;
            transition: margin-left 0.28s cubic-bezier(.4,0,.2,1);
        }
        .content.expanded { margin-left: 64px; }

        /* ══════ SHARED ══════ */
        .card {
            background: var(--warm-white);
            border: 1px solid var(--sand);
            border-radius: var(--radius);
        }
        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(212,185,154,0.5);
            padding: 0.9rem 1.2rem;
        }

        .table th {
            font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.5px; color: var(--text-muted);
            padding: 0.7rem 1rem; background: var(--cream-dark);
            border-bottom: 1px solid var(--sand) !important; white-space: nowrap;
        }
        .table td {
            font-size: 0.81rem; padding: 0.7rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid rgba(212,185,154,0.3);
            color: var(--text-body);
        }
        .table tbody tr:last-child td { border-bottom: none; }
        .table tbody tr { transition: background 0.12s; }
        .table-hover tbody tr:hover { background: var(--cream); }

        .form-label { font-size: 0.77rem; font-weight: 600; color: var(--text-dark); margin-bottom: 0.3rem; }
        .form-control, .form-select {
            font-size: 0.82rem; border: 1px solid var(--sand);
            border-radius: var(--radius-sm); font-family: inherit;
            background: var(--warm-white); color: var(--text-body);
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--maroon);
            box-shadow: 0 0 0 3px rgba(123,31,31,0.12);
            background: var(--warm-white);
        }

        .btn-primary { background: var(--maroon); border-color: var(--maroon); color: #fff; }
        .btn-primary:hover { background: var(--maroon-light); border-color: var(--maroon-light); color: #fff; }
        .btn-outline-primary { color: var(--maroon); border-color: var(--maroon); }
        .btn-outline-primary:hover { background: var(--maroon); border-color: var(--maroon); color: #fff; }

        .badge { font-weight: 600; font-size: 0.68rem; }
        .alert { border-radius: var(--radius-sm); font-size: 0.82rem; }

        .page-header { margin-bottom: 1.4rem; }
        .page-header h1 { font-size: 1.25rem; font-weight: 700; color: var(--text-dark); margin-bottom: 0.15rem; }
        .page-header p { color: var(--text-muted); margin: 0; font-size: 0.79rem; }

        footer { font-size: 0.72rem; color: var(--text-muted); text-align: center; padding: 1.5rem 0 0.5rem; }

        .overlay { display: none; position: fixed; inset: 0; background: rgba(44,24,16,0.45); z-index: 999; }
        .overlay.show { display: block; }

        .dropdown-menu {
            border: 1px solid var(--sand); border-radius: var(--radius);
            box-shadow: 0 8px 24px rgba(44,24,16,0.12); font-size: 0.82rem;
        }
        .dropdown-item:hover { background: var(--cream); color: var(--maroon); }

        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.28s cubic-bezier(.4,0,.2,1), width 0.28s; }
            .sidebar.mobile-open { transform: translateX(0); }
            .topbar { left: 0 !important; }
            .content { margin-left: 0 !important; }
            .topbar-search { display: none !important; }
        }
        @media (max-width: 576px) { .content { padding: 1rem; } }
    </style>

    @stack('styles')
</head>
<body>

<div id="overlay" class="overlay"></div>

{{-- SIDEBAR --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">🍜</div>
        <div class="brand-info">
            <div class="brand-name">Seoullicious</div>
            <div class="brand-sub">Admin Panel</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">Utama</div>
        <a href="{{ route('admin.dashboard') }}"
           class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="ti ti-layout-dashboard"></i>
            <span class="nav-label">Dashboard</span>
        </a>

        <div class="nav-section">Katalog</div>

        <a href="{{ route('admin.kategori.index') }}"
        class="nav-item {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">

            <i class="ti ti-tag"></i>

            <span class="nav-label">
                Kategori
            </span>
        </a>

        <a href="{{ route('admin.produk.index') }}"
        class="nav-item {{ request()->routeIs('admin.produk.*') ? 'active' : '' }}">

            <i class="ti ti-bowl-chopsticks"></i>

            <span class="nav-label">
                Produk
            </span>
        </a>

        <a href="{{ route('admin.promo.index') }}"
        class="nav-item {{ request()->routeIs('admin.promo.*') ? 'active' : '' }}">

            <i class="ti ti-discount-2"></i>

            <span class="nav-label">
                Promo
            </span>
        </a>

        <a href="{{ route('admin.trash') }}"
        class="nav-item {{ request()->routeIs('admin.trash') ? 'active' : '' }}">

            <i class="ti ti-trash"></i>

            <span class="nav-label">
                Trash
            </span>

            <span class="badge bg-danger ms-auto">
                {{
                    \App\Models\Produk::onlyTrashed()->count()
                    +
                    \App\Models\Kategori::onlyTrashed()->count()
                    +
                    \App\Models\Promo::onlyTrashed()->count()
                }}
            </span>
        </a>

        <div class="nav-section">
            Transaksi
        </div>

        <a href="{{ route('admin.transaksi.index') }}"
        class="nav-item {{ request()->routeIs('admin.transaksi.*') ? 'active' : '' }}">

            <i class="ti ti-receipt"></i>

            <span class="nav-label">
                Data Transaksi
            </span>
        </a>

        <a href="{{ route('admin.pelanggan.index') }}"
        class="nav-item {{ request()->routeIs('admin.pelanggan.*') ? 'active' : '' }}">

            <i class="ti ti-users"></i>

            <span class="nav-label">
                Pelanggan
            </span>
        </a>

        <div class="nav-section">Akun</div>
        <a href="{{ route('logout') }}" class="nav-item danger"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="ti ti-logout"></i>
            <span class="nav-label">Keluar</span>
        </a>
    </nav>
</aside>

{{-- TOPBAR --}}
<header class="topbar" id="topbar">
    <div class="d-flex align-items-center">
        {{-- Toggle Desktop — ada di topbar, tidak ikut collapse --}}
        <button class="topbar-toggle d-none d-lg-flex" id="toggleBtn" title="Toggle sidebar">
            <i class="ti ti-menu-2" id="toggleIcon"></i>
        </button>
        {{-- Toggle Mobile --}}
        <button class="topbar-toggle d-lg-none" id="mobileBtn">
            <i class="ti ti-menu-2"></i>
        </button>

        <div class="topbar-search d-none d-md-flex ms-1">
            <i class="ti ti-search"></i>
            <input type="text" placeholder="Cari produk, transaksi...">
        </div>
    </div>

    <div class="topbar-right">
    {{-- NOTIFIKASI DINAMIS --}}
    <div class="dropdown">
        <button class="btn-icon" data-bs-toggle="dropdown" id="notifBtn">
            <i class="ti ti-bell"></i>
            <span class="notif-dot" id="notifDot" style="display: none;"></span>
            <span class="notif-count" id="notifCount">0</span>
        </button>
        <div class="dropdown-menu dropdown-menu-end p-0" style="min-width:320px; max-height:450px; overflow-y:auto;">
            <div class="px-3 py-2 border-bottom d-flex align-items-center justify-content-between"
                 style="background:var(--cream-dark);border-radius:var(--radius) var(--radius) 0 0;">
                <span style="font-weight:700;font-size:0.82rem;color:var(--text-dark);">Notifikasi</span>
                <div>
                    <button onclick="markAllRead()" style="font-size:0.7rem;background:none;border:none;color:var(--maroon);margin-right:10px;">
                        Tandai semua
                    </button>
                    <span class="notif-badge" id="notifBadge" style="background:var(--maroon);color:#fff;font-size:0.63rem;font-weight:700;padding:2px 8px;border-radius:20px;">0 baru</span>
                </div>
            </div>
            <div id="notificationList">
                <div class="text-center py-3 text-muted" id="loadingNotif">
                    <small>Loading...</small>
                </div>
            </div>
            <div class="text-center py-2 border-top">
                <a href="{{ route('admin.notifications.index') }}" style="font-size:0.75rem;color:var(--maroon);font-weight:600;">Lihat semua →</a>
            </div>
        </div>
    </div>

    {{-- USER DINAMIS --}}
    <div class="dropdown">
        <div class="user-pill" data-bs-toggle="dropdown" style="cursor:pointer;">
            <div class="avatar">
                @if(auth()->user()->photo)
                    <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                @else
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                @endif
            </div>
            <div class="d-none d-sm-block">
                <div class="user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div class="user-role">{{ auth()->user()->role == 'admin' ? 'Administrator' : 'User' }}</div>
            </div>
            <i class="ti ti-chevron-down" style="font-size:0.72rem;color:var(--text-muted);"></i>
        </div>
        <div class="dropdown-menu dropdown-menu-end p-0" style="min-width:220px;">
            <div class="px-3 py-2 border-bottom" style="background:var(--cream-dark);border-radius:var(--radius) var(--radius) 0 0;">
                <div style="font-weight:700;font-size:0.82rem;color:var(--text-dark);">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div style="font-size:0.7rem;color:var(--text-muted);">{{ auth()->user()->email ?? '' }}</div>
            </div>
            <div class="p-1">
                <a href="{{ route('home') }}" class="dropdown-item rounded-2 py-2">
                    <i class="ti ti-home me-2"></i> Website
                </a>
                <a href="{{ route('admin.notifications.index') }}" class="dropdown-item rounded-2 py-2">
                    <i class="ti ti-bell me-2"></i> Notifikasi
                    <span class="badge bg-danger ms-1" id="sidebarNotifCount">0</span>
                </a>
                <a href="{{ route('profile.show') }}" class="dropdown-item rounded-2 py-2">
                    <i class="ti ti-user me-2"></i> Profil Saya
                </a>
                <hr class="my-1">
                <a href="{{ route('logout') }}" class="dropdown-item rounded-2 py-2 text-danger"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="ti ti-logout me-2"></i> Keluar
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.notif-dot {
    position: absolute;
    top: -2px;
    right: -2px;
    width: 10px;
    height: 10px;
    background: #dc3545;
    border-radius: 50%;
    border: 2px solid white;
}
.notif-count {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #dc3545;
    color: white;
    font-size: 9px;
    font-weight: bold;
    padding: 2px 5px;
    border-radius: 50%;
    min-width: 16px;
    text-align: center;
}
.notification-item {
    transition: background 0.2s;
}
.notification-item:hover {
    background: #f8f9fa;
}
.notification-item.unread {
    background: #FFF3E0;
    border-left: 3px solid var(--maroon);
}
</style>

<script>
// LOAD NOTIFICATIONS
function loadNotifications() {
    fetch('{{ route("admin.notifications.unread") }}')
        .then(response => response.json())
        .then(data => {
            const count = data.count;
            const notifList = document.getElementById('notificationList');
            const notifBadge = document.getElementById('notifBadge');
            const notifCount = document.getElementById('notifCount');
            const notifDot = document.getElementById('notifDot');
            const sidebarNotif = document.getElementById('sidebarNotifCount');
            
            // Update counter
            if (notifBadge) notifBadge.textContent = count + ' baru';
            if (notifCount) {
                notifCount.textContent = count;
                notifCount.style.display = count > 0 ? 'block' : 'none';
            }
            if (notifDot) notifDot.style.display = count > 0 ? 'block' : 'none';
            if (sidebarNotif) {
                sidebarNotif.textContent = count;
                sidebarNotif.style.display = count > 0 ? 'inline-block' : 'none';
            }
            
            // Render list
            if (data.notifications.length === 0) {
                notifList.innerHTML = '<div class="text-center py-4 text-muted"><i class="ti ti-bell-off" style="font-size:30px;"></i><p class="mt-2 mb-0 small">Tidak ada notifikasi baru</p></div>';
                return;
            }
            
            let html = '';
            data.notifications.forEach(notif => {
                const isUnread = !notif.is_read;
                html += `
                    <div class="notification-item ${isUnread ? 'unread' : ''} px-3 py-2 border-bottom" data-id="${notif.id}" onclick="markAsRead(${notif.id}, '${notif.link || '#'}')" style="cursor:pointer;">
                        <div style="font-weight:600;font-size:0.79rem;">${notif.title}</div>
                        <div style="font-size:0.7rem;color:var(--text-muted);">${notif.message}</div>
                        <div style="font-size:0.65rem;color:#aaa;margin-top:4px;">${formatTime(notif.created_at)}</div>
                    </div>
                `;
            });
            notifList.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('notificationList').innerHTML = '<div class="text-center py-3 text-danger"><small>Gagal memuat notifikasi</small></div>';
        });
}

// MARK AS READ
function markAsRead(id, link) {
    fetch('/admin/notifications/' + id + '/read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    }).then(() => {
        if (link && link !== '#') {
            window.location.href = link;
        } else {
            loadNotifications();
        }
    });
}

// MARK ALL READ
function markAllRead() {
    fetch('{{ route("admin.notifications.mark-all-read") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }).then(() => {
        loadNotifications();
    });
}

// FORMAT TIME
function formatTime(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diff = Math.floor((now - date) / 1000); // seconds
    
    if (diff < 60) return 'Baru saja';
    if (diff < 3600) return Math.floor(diff / 60) + ' menit lalu';
    if (diff < 86400) return Math.floor(diff / 3600) + ' jam lalu';
    if (diff < 604800) return Math.floor(diff / 86400) + ' hari lalu';
    return date.toLocaleDateString('id-ID');
}

// Auto refresh notification every 30 seconds
setInterval(loadNotifications, 30000);

// Load on page load
document.addEventListener('DOMContentLoaded', loadNotifications);
</script>
</header>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

{{-- CONTENT --}}
<main class="content" id="content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="ti ti-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')

    <footer>
        <p>© {{ date('Y') }} <strong>Seoullicious</strong> — Powered by Laravel</p>
    </footer>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    const sidebar    = document.getElementById('sidebar');
    const content    = document.getElementById('content');
    const topbar     = document.getElementById('topbar');
    const toggleBtn  = document.getElementById('toggleBtn');
    const toggleIcon = document.getElementById('toggleIcon');
    const mobileBtn  = document.getElementById('mobileBtn');
    const overlay    = document.getElementById('overlay');

    // Simpan & restore state
    let collapsed = localStorage.getItem('sl_sidebar') === '1';

    function applyState(animate) {
        if (!animate) {
            sidebar.style.transition = 'none';
            content.style.transition = 'none';
            topbar.style.transition  = 'none';
        }
        sidebar.classList.toggle('collapsed', collapsed);
        content.classList.toggle('expanded', collapsed);
        topbar.classList.toggle('expanded', collapsed);
        if (toggleIcon) {
            toggleIcon.className = collapsed
                ? 'ti ti-layout-sidebar-left-expand'
                : 'ti ti-layout-sidebar-left-collapse';
        }
        if (!animate) {
            // re-enable transition next frame
            requestAnimationFrame(() => {
                sidebar.style.transition = '';
                content.style.transition = '';
                topbar.style.transition  = '';
            });
        }
    }

    applyState(false); // apply without animation on load

    // Desktop toggle
    toggleBtn?.addEventListener('click', () => {
        collapsed = !collapsed;
        localStorage.setItem('sl_sidebar', collapsed ? '1' : '0');
        applyState(true);
    });

    // Mobile toggle
    mobileBtn?.addEventListener('click', () => {
        sidebar.classList.add('mobile-open');
        overlay.classList.add('show');
    });

    overlay?.addEventListener('click', () => {
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('show');
    });
})();
</script>

@stack('scripts')
</body>
</html>