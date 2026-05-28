@extends('layouts.app')

@section('body-class', 'profile-page')

@section('content')

@php
  $totalPengeluaran = $transaksis->where('approval_status', 'completed')->sum('total_harga');
@endphp

<div class="sp-wrap">
<div class="sp-container">

  {{-- ===== FLASH MESSAGES ===== --}}
  @if(session('success'))
    <div class="sp-alert sp-alert-success" style="margin-bottom:20px;">
      <svg viewBox="0 0 24 24" width="18" height="18" fill="#276749"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
      {{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div class="sp-alert sp-alert-error" style="margin-bottom:20px;">
      <svg viewBox="0 0 24 24" width="18" height="18" fill="#C53030"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
      {{ session('error') }}
    </div>
  @endif

  {{-- ===== HERO HEADER ===== --}}
  <div class="sp-hero">
    <div class="sp-avatar-ring" onclick="openPhotoModal()">
      <div class="sp-avatar" id="sp-main-avatar">
        @if(Auth::user()->foto)
          <img src="{{ Storage::url(Auth::user()->foto) }}" alt="Foto Profil" id="sp-avatar-img">
        @else
          <div class="sp-avatar-initials" id="sp-avatar-initials">
            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
          </div>
        @endif
      </div>
      <div class="sp-avatar-edit-badge">
        <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
      </div>
    </div>

    <div class="sp-hero-info">
      <div class="sp-hero-tag">
        <span class="dot"></span>
        Pelanggan Seoullicious
      </div>
      <h1 class="sp-hero-name">{{ Auth::user()->name }}</h1>
      <p class="sp-hero-username">
        @if(Auth::user()->username)
          {{ '@' . Auth::user()->username }}
        @else
          <span class="text-muted">@belumdiatur</span>
        @endif
      </p>
      <div class="sp-hero-meta">
        <div class="sp-hero-meta-item">
          <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
          {{ Auth::user()->email }}
        </div>
        @if(Auth::user()->hp)
        <div class="sp-hero-meta-item">
          <svg viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
          {{ Auth::user()->hp }}
        </div>
        @endif
        <div class="sp-hero-meta-item">
          <svg viewBox="0 0 24 24"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/></svg>
          Bergabung {{ date('M Y', strtotime(Auth::user()->created_at ?? now())) }}
        </div>
      </div>
    </div>

  </div>

  {{-- ===== STAT MINI CARDS ===== --}}
  <div class="sp-stats-row">
    <div class="sp-mini-stat">
      <div class="sp-mini-stat-icon" style="background:#FCE8E8;">
        <svg viewBox="0 0 24 24" width="20" height="20" fill="#9E2020"><path d="M7 4V2H5v2H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2h-1V2h-2v2H7zm0 2h10v2H7V6zM4 20V10h16v10H4z"/></svg>
      </div>
      <div class="sp-mini-stat-num">{{ $totalPesanan }}</div>
      <div class="sp-mini-stat-label">Total Pesanan</div>
    </div>
    <div class="sp-mini-stat">
      <div class="sp-mini-stat-icon" style="background:#FEF3C7;">
        <svg viewBox="0 0 24 24" width="20" height="20" fill="#92400E"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
      </div>
      <div class="sp-mini-stat-num">{{ $pendingTransaksis->count() }}</div>
      <div class="sp-mini-stat-label">Pesanan Aktif</div>
    </div>
    <div class="sp-mini-stat">
      <div class="sp-mini-stat-icon" style="background:#DBEAFE;">
        <svg viewBox="0 0 24 24" width="20" height="20" fill="#1E40AF"><path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/></svg>
      </div>
      <div class="sp-mini-stat-num">{{ $riwayatTransaksis->count() }}</div>
      <div class="sp-mini-stat-label">Riwayat Pesanan</div>
    </div>
    <div class="sp-mini-stat">
      <div class="sp-mini-stat-icon" style="background:#D1FAE5;">
        <svg viewBox="0 0 24 24" width="20" height="20" fill="#065F46"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
      </div>
      <div class="sp-mini-stat-num">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
      <div class="sp-mini-stat-label">Total Pengeluaran</div>
    </div>
  </div>

  {{-- ===== BODY === --}}
  <div class="sp-body">

    {{-- === SIDEBAR NAV === --}}
    <nav class="sp-sidebar">
      <div class="sp-nav-group">
        <div class="sp-nav-group-label">Akun Saya</div>
        <button class="sp-nav-item active" onclick="spSwitch('biodata', this)">
          <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
          <span>Biodata</span>
        </button>
        <button class="sp-nav-item" onclick="spSwitch('pesanan', this)">
          <svg viewBox="0 0 24 24"><path d="M7 4V2H5v2H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2h-1V2h-2v2H7zm0 2h10v2H7V6zM4 20V10h16v10H4z"/></svg>
          <span>Pesanan Aktif</span>
          @if($pendingTransaksis->count() > 0)
            <span class="sp-nav-badge">{{ $pendingTransaksis->count() }}</span>
          @endif
        </button>
        <button class="sp-nav-item" onclick="spSwitch('riwayat', this)">
          <svg viewBox="0 0 24 24"><path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/></svg>
          <span>Riwayat</span>
          @if($riwayatTransaksis->count() > 0)
            <span class="sp-nav-badge gold">{{ $riwayatTransaksis->count() }}</span>
          @endif
        </button>
        <button class="sp-nav-item" onclick="spSwitch('favorit', this)">
          <svg viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
          <span>Favorit</span>
        </button>

        <div class="sp-nav-divider"></div>
        <div class="sp-nav-group-label">Pengaturan</div>
        <a href="{{ route('profile.edit') }}" class="sp-nav-item">
          <svg viewBox="0 0 24 24">
            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
          </svg>
          <span>Pengaturan</span>
        </a>

        <div class="sp-nav-divider"></div>
        <a href="{{ route('home') }}" class="sp-nav-item">
          <svg viewBox="0 0 24 24">
            <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
          </svg>
          <span>Kembali</span>
        </a>

        <div class="sp-nav-divider"></div>
        <button class="sp-nav-item danger" onclick="openLogoutConfirm()">
          <svg viewBox="0 0 24 24"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></svg>
          <span>Logout</span>
        </button>
      </div>
    </nav>

    {{-- === PANELS === --}}
    <div class="sp-panels">

      {{-- ===== PANEL: BIODATA ===== --}}
      <div class="sp-panel active" id="panel-biodata">
        <div class="sp-card">
          <div class="sp-card-header">
            <h2 class="sp-card-title">Biodata Saya</h2>
            <a href="{{ route('profile.edit') }}" class="sp-btn-edit">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor">
                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                </svg>
                Edit Profil
            </a>
          </div>
          <div class="sp-bio-grid">
            <div class="sp-bio-field">
              <div class="sp-bio-label">Nama Lengkap</div>
              <div class="sp-bio-val">{{ Auth::user()->name }}</div>
            </div>
            <div class="sp-bio-field">
              <div class="sp-bio-label">Username</div>
              <div class="sp-bio-val">
                @if(Auth::user()->username)
                  {{ '@' . Auth::user()->username }}
                @else
                  <span class="empty">@belumdiatur</span>
                @endif
              </div>
            </div>
            <div class="sp-bio-field">
              <div class="sp-bio-label">Email</div>
              <div class="sp-bio-val">{{ Auth::user()->email }}</div>
            </div>
            <div class="sp-bio-field">
              <div class="sp-bio-label">No. Telepon</div>
              <div class="sp-bio-val {{ !Auth::user()->hp ? 'empty' : '' }}">{{ Auth::user()->hp ?? 'Belum diisi' }}</div>
            </div>
            <div class="sp-bio-field full">
              <div class="sp-bio-label">Alamat</div>
              <div class="sp-bio-val {{ !Auth::user()->alamat ? 'empty' : '' }}">{{ Auth::user()->alamat ?? 'Belum diisi' }}</div>
            </div>
            <div class="sp-bio-field">
              <div class="sp-bio-label">Status Akun</div>
              <div class="sp-bio-val">
                <span class="sp-badge sp-badge-completed">✦ Pelanggan Aktif</span>
              </div>
            </div>
            <div class="sp-bio-field">
              <div class="sp-bio-label">Bergabung Sejak</div>
              <div class="sp-bio-val">{{ date('d F Y', strtotime(Auth::user()->created_at ?? now())) }}</div>
            </div>
          </div>
        </div>
      </div>

      {{-- ===== PANEL: PESANAN AKTIF ===== --}}
      <div class="sp-panel" id="panel-pesanan">
        <div class="sp-card">
          <div class="sp-card-header">
            <h2 class="sp-card-title">Pesanan Aktif</h2>
            <span class="sp-badge sp-badge-info">{{ $pendingTransaksis->count() }} pesanan</span>
          </div>
          <div class="sp-card-body">
            @forelse($pendingTransaksis as $item)
            <div class="sp-order-card">
              @php $firstDetail = $item->details->first(); $produk = $firstDetail ? $firstDetail->produk : null; @endphp
              <div class="sp-order-thumb">
                @if($produk && $produk->poto)
                  <img src="{{ asset('storage/' . $produk->poto) }}" alt="{{ $produk->nama }}" style="width:100%;height:100%;object-fit:cover;border-radius:18px;">
                @else
                  <div style="width:100%;height:100%;background:linear-gradient(135deg,#C0392B,#D4A853);display:flex;align-items:center;justify-content:center;font-size:2rem;color:white;border-radius:18px;">🍜</div>
                @endif
              </div>
              <div class="sp-order-body">
                <div class="sp-order-invoice">Invoice <span>#{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</span></div>
                <div class="sp-order-items">
                  @foreach($item->details as $detail)
                    <div>{{ $detail->produk->nama ?? 'Produk tidak tersedia' }} ×{{ $detail->qty }} <span style="color:#8B1A1A;">(Rp {{ number_format($detail->harga, 0, ',', '.') }})</span></div>
                  @endforeach
                </div>
                <div class="sp-order-meta">
                  @if($item->approval_status === 'pending')
                    <span class="sp-badge sp-badge-pending">⏳ Menunggu Konfirmasi</span>
                  @else
                    <span class="sp-badge sp-badge-approved">⚙️ Diproses</span>
                  @endif
                  <span class="sp-badge sp-badge-info">💳 {{ ucfirst($item->metode_bayar ?? 'Transfer') }}</span>
                  @if($item->metode_kirim)
                    <span class="sp-badge sp-badge-info">🚚 {{ ucfirst($item->metode_kirim) }}</span>
                  @endif
                </div>
              </div>
              <div class="sp-order-right">
                <div class="sp-order-price">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</div>
                <div class="sp-order-date">{{ $item->created_at->format('d M Y') }}<br>{{ $item->created_at->format('H:i') }}</div>
              </div>
            </div>
            @empty
              <div class="sp-empty">
                <div class="sp-empty-icon">🍜</div>
                <div class="sp-empty-title">Tidak ada pesanan aktif</div>
                <div class="sp-empty-sub">Saat ini belum ada pesanan yang sedang diproses.</div>
              </div>
            @endforelse
          </div>
        </div>
      </div>

      {{-- ===== PANEL: RIWAYAT PESANAN ===== --}}
      <div class="sp-panel" id="panel-riwayat">
        <div class="sp-card">
          <div class="sp-card-header">
            <h2 class="sp-card-title">Riwayat Pesanan</h2>
            <span class="sp-badge sp-badge-info">{{ $riwayatTransaksis->count() }} pesanan</span>
          </div>
          <div class="sp-card-body">
            @forelse($riwayatTransaksis as $item)
              @php $first = $item->details->first(); $isDone = $item->approval_status === 'completed'; @endphp
              <div class="sp-order-card">
                <div class="sp-order-thumb">
                  @if($first && $first->produk && $first->produk->poto)
                    <img src="{{ asset('storage/' . $first->produk->poto) }}" alt="{{ $first->produk->nama }}" style="width:100%;height:100%;object-fit:cover;">
                  @else
                    <div style="width:100%;height:100%;background:#FCE8E8;display:flex;align-items:center;justify-content:center;font-size:2rem;">🍜</div>
                  @endif
                </div>
                <div class="sp-order-body">
                  <div class="sp-order-invoice">Invoice <span>#{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</span></div>
                  <div class="sp-order-items">
                    @foreach($item->details->take(2) as $detail)
                      {{ $detail->produk->nama ?? 'Produk' }} ×{{ $detail->qty }}<br>
                    @endforeach
                    @if($item->details->count() > 2)
                      <span class="more">+{{ $item->details->count() - 2 }} item lainnya</span>
                    @endif
                  </div>
                  <div class="sp-order-meta">
                    @if($isDone)
                      <span class="sp-badge sp-badge-completed">✅ Selesai</span>
                    @else
                      <span class="sp-badge sp-badge-rejected">❌ Ditolak</span>
                    @endif
                    <span class="sp-badge sp-badge-info">💳 {{ ucfirst($item->metode_bayar ?? 'Transfer') }}</span>
                    @if($item->metode_kirim)
                      <span class="sp-badge sp-badge-info">🚚 {{ ucfirst($item->metode_kirim) }}</span>
                    @endif
                  </div>
                  @if($isDone)
                    <div style="margin-top:10px; display:flex; gap:10px; flex-wrap:wrap;">
                      <a href="{{ url('/') }}#menu" class="sp-btn-reorder">🔄 Pesan Lagi</a>
                      <a href="{{ route('invoice.index', $item->id) }}" class="sp-btn-reorder" style="background:#fff; color:#8B1A1A; border:1px solid #8B1A1A;">👁️ Lihat Invoice</a>
                    </div>
                  @endif
                </div>
                <div class="sp-order-right">
                  <div class="sp-order-price">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</div>
                  <div class="sp-order-date">{{ $item->created_at->format('d M Y') }}<br>{{ $item->created_at->format('H:i') }}</div>
                </div>
              </div>
            @empty
              <div class="sp-empty">
                <div class="sp-empty-icon"><svg viewBox="0 0 24 24"><path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/></svg></div>
                <div class="sp-empty-title">Belum ada riwayat pesanan</div>
                <div class="sp-empty-sub">Pesananmu yang sudah selesai atau ditolak akan muncul di sini.</div>
              </div>
            @endforelse
          </div>
        </div>
      </div>

      {{-- ===== PANEL: FAVORIT ===== --}}
      <div class="sp-panel" id="panel-favorit">
        <div class="sp-card">
          <div class="sp-card-header">
            <h2 class="sp-card-title">Favorit Saya</h2>
          </div>
          <div class="sp-card-body">
            @php $favs = []; @endphp
            @if(count($favs) > 0)
              <div class="sp-fav-grid">
                @foreach($favs as $fav)
                  <div class="sp-fav-card">
                    <div class="sp-fav-img">
                      @if($fav->produk && $fav->produk->poto)
                        <img src="{{ asset('storage/' . $fav->produk->poto) }}" alt="{{ $fav->produk->nama }}">
                      @else 🍜 @endif
                    </div>
                    <div class="sp-fav-body">
                      <div class="sp-fav-name">{{ $fav->produk->nama }}</div>
                      <div class="sp-fav-price">Rp {{ number_format($fav->produk->harga, 0, ',', '.') }}</div>
                      <button class="sp-fav-add">+ Tambah ke Keranjang</button>
                    </div>
                  </div>
                @endforeach
              </div>
            @else
              <div class="sp-empty">
                <div class="sp-empty-icon"><svg viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg></div>
                <div class="sp-empty-title">Belum ada favorit</div>
                <div class="sp-empty-sub">Tambahkan menu favoritmu dengan menekan ikon ❤️ di halaman menu.</div>
                <a href="{{ url('/') }}#menu" class="sp-btn-edit" style="margin-top:4px;">Jelajahi Menu</a>
              </div>
            @endif
          </div>
        </div>
      </div>

    </div>{{-- end panels --}}
  </div>{{-- end sp-body --}}
</div>{{-- end container --}}
</div>{{-- end sp-wrap --}}

{{-- ===== PHOTO MODAL ===== --}}
<div id="spPhotoModal" class="sp-photo-modal-overlay">
  <div class="sp-photo-modal">
    <div class="sp-photo-modal-header">
      <span class="sp-photo-modal-title">Ubah Foto Profil</span>
      <button class="sp-photo-modal-close" onclick="closePhotoModal()">✕</button>
    </div>
    <div class="sp-photo-preview">
      @if(Auth::user()->foto)
        <img src="{{ Storage::url(Auth::user()->foto) }}" id="spModalPreviewImg" class="sp-photo-preview-img">
        <div id="spModalPreviewInitials" class="sp-photo-preview-initials" style="display:none;">{{ strtoupper(substr(Auth::user()->name,0,2)) }}</div>
      @else
        <img id="spModalPreviewImg" class="sp-photo-preview-img" style="display:none;">
        <div id="spModalPreviewInitials" class="sp-photo-preview-initials">{{ strtoupper(substr(Auth::user()->name,0,2)) }}</div>
      @endif
    </div>
    <div class="sp-photo-actions">
      <button class="sp-photo-btn" onclick="openCamera()"><svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M9 3L7.17 5H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2h-3.17L15 3H9zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/></svg>Ambil dari Kamera</button>
      <button class="sp-photo-btn" onclick="document.getElementById('spFotoInput').click()"><svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>Pilih dari Galeri</button>
      <button id="spBtnDeletePhoto" class="sp-photo-btn danger" onclick="spConfirmDeletePhoto()" style="{{ Auth::user()->foto ? '' : 'display:none;' }}"><svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>Hapus Foto</button>
      <input type="file" id="spFotoInput" accept="image/*" style="display:none;" onchange="spPreviewPhoto(event)">
    </div>
    <div id="spCameraArea" style="display:none;padding:0 24px 20px;">
      <video id="spCamVideo" autoplay playsinline style="width:100%;border-radius:12px;background:#000;display:block;"></video>
      <div class="sp-camera-controls">
        <button class="sp-photo-btn primary" onclick="spCapturePhoto()" style="flex:1;">📸 Ambil Foto</button>
        <button class="sp-photo-btn" onclick="spStopCamera()" style="flex:0 0 auto;">Batal</button>
      </div>
      <canvas id="spCamCanvas" style="display:none;"></canvas>
    </div>
    <div id="spSaveArea" style="display:none;padding:0 24px 20px;">
      <form id="spUploadForm" action="{{ route('profile.updatePhoto') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" id="spUploadInput" name="foto" style="display:none;">
        <input type="hidden" id="spBase64Input" name="foto_base64">
        <button type="submit" class="sp-photo-btn primary" style="width:100%;"><svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.11 0 2-.89 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>💾 Simpan Foto</button>
      </form>
    </div>
    <form id="spDeletePhotoForm" action="{{ route('profile.deletePhoto') }}" method="POST" style="display:none;">@csrf @method('DELETE')</form>
  </div>
</div>

{{-- ===== LOGOUT CONFIRM ===== --}}
<div id="spLogoutConfirm" class="sp-confirm-modal-overlay">
  <div class="sp-confirm-modal">
    <div class="sp-confirm-icon">👋</div>
    <div class="sp-confirm-title">Yakin ingin logout?</div>
    <div class="sp-confirm-desc">Kamu akan keluar dari akun Seoullicious. Sampai jumpa lagi!</div>
    <div class="sp-confirm-actions">
      <button class="sp-btn-cancel" onclick="closeLogoutConfirm()">Batal</button>
      <button class="sp-btn-save" onclick="document.getElementById('spLogoutForm').submit()">Logout</button>
    </div>
  </div>
</div>

{{-- ===== DELETE CONFIRM ===== --}}
<div id="spDeleteConfirm" class="sp-confirm-modal-overlay">
  <div class="sp-confirm-modal">
    <div class="sp-confirm-icon">⚠️</div>
    <div class="sp-confirm-title" style="color:#C53030;">Hapus Akun?</div>
    <div class="sp-confirm-desc">Tindakan ini tidak dapat dibatalkan. Semua data akan hilang permanen. Masukkan passwordmu untuk konfirmasi.</div>
    <form method="POST" action="{{ route('profile.destroy') }}" style="width:100%;margin-bottom:16px;">
      @csrf
      @method('DELETE')
      <div class="sp-input-pw" style="margin-bottom:0;">
        <input type="password" name="password" id="spDeletePw" class="sp-form-input" placeholder="Masukkan password" style="margin-bottom:0;">
        <button type="button" class="sp-toggle-pw" onclick="spTogglePw('spDeletePw',this)"><svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg></button>
      </div>
      <div class="sp-confirm-actions" style="margin-top:16px;">
        <button type="button" class="sp-btn-cancel" onclick="closeDeleteConfirm()">Batal</button>
        <button type="submit" class="sp-btn-danger" style="flex:1;">🗑 Hapus Akun</button>
      </div>
    </form>
  </div>
</div>

<form id="spLogoutForm" method="POST" action="{{ route('logout') }}" style="display:none;">@csrf</form>
<div id="spToast" class="sp-toast"></div>

@push('scripts')
<script>
// ===== PANEL SWITCHING =====
function spSwitch(name, el) {
  document.querySelectorAll('.sp-panel').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.sp-nav-item').forEach(n => n.classList.remove('active'));
  const panel = document.getElementById('panel-' + name);
  if (panel) panel.classList.add('active');
  if (el) el.classList.add('active');
  window.scrollTo({ top: document.querySelector('.sp-body')?.offsetTop - 80 || 0, behavior: 'smooth' });
}

// ===== TOAST =====
function spShowToast(msg, duration = 3000) {
  const t = document.getElementById('spToast');
  if (!t) return;
  t.textContent = msg;
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), duration);
}

// ===== PASSWORD TOGGLE =====
function spTogglePw(id, btn) {
  const inp = document.getElementById(id);
  if (!inp) return;
  if (inp.type === 'password') {
    inp.type = 'text';
    btn.style.color = 'var(--maroon)';
  } else {
    inp.type = 'password';
    btn.style.color = '';
  }
}

// ===== PASSWORD STRENGTH =====
function spCheckStrength(v) {
  const bar = document.getElementById('spStrBar');
  const lbl = document.getElementById('spStrLabel');
  if (!bar || !lbl) return;
  let score = 0;
  if (v.length >= 6) score++;
  if (v.length >= 10) score++;
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

function spCheckMatch() {
  const a = document.getElementById('spPwNew');
  const b = document.getElementById('spPwConf');
  const lbl = document.getElementById('spMatchLabel');
  if (!a || !b || !lbl) return;
  if (!b.value) { lbl.textContent = ''; return; }
  if (a.value === b.value) {
    lbl.textContent = '✓ Password cocok';
    lbl.style.color = '#276749';
  } else {
    lbl.textContent = '✗ Password tidak cocok';
    lbl.style.color = '#E53E3E';
  }
}

// ===== PHOTO MODAL =====
let spSelFile = null, spCapBlob = null, spMediaStream = null;

function openPhotoModal() {
  document.getElementById('spPhotoModal').classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closePhotoModal() {
  document.getElementById('spPhotoModal').classList.remove('open');
  document.body.style.overflow = '';
  spStopCamera();
  spSelFile = null; spCapBlob = null;
  document.getElementById('spSaveArea').style.display = 'none';
}

function spPreviewPhoto(e) {
  const file = e.target.files[0];
  if (!file) return;
  spSelFile = file;
  const reader = new FileReader();
  reader.onload = ev => {
    const img = document.getElementById('spModalPreviewImg');
    const ini = document.getElementById('spModalPreviewInitials');
    if (img) { img.src = ev.target.result; img.style.display = 'block'; }
    if (ini) ini.style.display = 'none';
    const uInp = document.getElementById('spUploadInput');
    if (uInp) {
      const dt = new DataTransfer();
      dt.items.add(file);
      uInp.files = dt.files;
    }
    document.getElementById('spSaveArea').style.display = 'block';
  };
  reader.readAsDataURL(file);
}

async function openCamera() {
  try {
    spMediaStream = await navigator.mediaDevices.getUserMedia({ video: true });
    const vid = document.getElementById('spCamVideo');
    if (vid) { vid.srcObject = spMediaStream; }
    document.getElementById('spCameraArea').style.display = 'block';
  } catch {
    spShowToast('⚠️ Kamera tidak dapat diakses');
  }
}
function spStopCamera() {
  if (spMediaStream) { spMediaStream.getTracks().forEach(t => t.stop()); spMediaStream = null; }
  const area = document.getElementById('spCameraArea');
  if (area) area.style.display = 'none';
}
function spCapturePhoto() {
  const vid = document.getElementById('spCamVideo');
  const can = document.getElementById('spCamCanvas');
  if (!vid || !can) return;
  can.width = vid.videoWidth;
  can.height = vid.videoHeight;
  can.getContext('2d').drawImage(vid, 0, 0);
  const dataUrl = can.toDataURL('image/jpeg', 0.85);
  spCapBlob = dataUrl;
  const img = document.getElementById('spModalPreviewImg');
  const ini = document.getElementById('spModalPreviewInitials');
  if (img) { img.src = dataUrl; img.style.display = 'block'; }
  if (ini) ini.style.display = 'none';
  const b64inp = document.getElementById('spBase64Input');
  if (b64inp) b64inp.value = dataUrl;
  spStopCamera();
  document.getElementById('spSaveArea').style.display = 'block';
}
function spConfirmDeletePhoto() {
  if (confirm('Hapus foto profil?')) {
    document.getElementById('spDeletePhotoForm').submit();
  }
}

// Upload form submit
const spUpForm = document.getElementById('spUploadForm');
if (spUpForm) {
  spUpForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const fd = new FormData();
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    fd.append('_token', csrf || '');
    if (spSelFile) {
      fd.append('foto', spSelFile, spSelFile.name);
    } else if (spCapBlob) {
      fd.append('foto_base64', spCapBlob);
    } else {
      spShowToast('Pilih foto terlebih dahulu');
      return;
    }
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.textContent = 'Menyimpan...';
    fetch(this.action, {
      method: 'POST', body: fd,
      headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        spUpdateAvatars(data.url);
        closePhotoModal();
        spShowToast('✅ Foto profil berhasil diperbarui!');
      } else {
        spShowToast('❌ ' + (data.message || 'Gagal mengunggah foto'));
      }
    })
    .catch(() => spShowToast('❌ Terjadi kesalahan koneksi'))
    .finally(() => { btn.disabled = false; btn.textContent = '💾 Simpan Foto'; });
  });
}

function spUpdateAvatars(url) {
  document.querySelectorAll('#sp-avatar-img, #spModalPreviewImg').forEach(el => {
    if (el) { el.src = url; el.style.display = 'block'; }
  });
  document.querySelectorAll('#sp-avatar-initials, #spModalPreviewInitials').forEach(el => {
    if (el) el.style.display = 'none';
  });
  document.getElementById('spBtnDeletePhoto').style.display = 'flex';
}

// ===== LOGOUT / DELETE CONFIRM =====
function openLogoutConfirm() { document.getElementById('spLogoutConfirm').classList.add('open'); }
function closeLogoutConfirm() { document.getElementById('spLogoutConfirm').classList.remove('open'); }
function openDeleteConfirm() { document.getElementById('spDeleteConfirm').classList.add('open'); }
function closeDeleteConfirm() { document.getElementById('spDeleteConfirm').classList.remove('open'); }

// Close on backdrop click
['spPhotoModal','spLogoutConfirm','spDeleteConfirm'].forEach(id => {
  const el = document.getElementById(id);
  if (el) el.addEventListener('click', function(e) {
    if (e.target === this) {
      this.classList.remove('open');
      document.body.style.overflow = '';
    }
  });
});

// ===== AUTO-OPEN PANEL FROM SESSION =====
@if(session('open_panel'))
  spSwitch('{{ session('open_panel') }}', document.querySelector('[onclick*="{{ session('open_panel') }}"]'));
@endif
</script>
@endpush
@endsection