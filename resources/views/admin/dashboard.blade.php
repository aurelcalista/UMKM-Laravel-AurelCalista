@extends('layouts.admin')

@section('title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .hero-banner {
        background: linear-gradient(135deg, #E55A2B 0%, #FF8A5C 50%, #FFBA9A 100%);
        border-radius: 16px;
        padding: 1.75rem 2rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .hero-banner::before {
        content: '';
        position: absolute; right: -60px; top: -60px;
        width: 220px; height: 220px;
        border-radius: 50%;
        background: rgba(255,255,255,0.1);
    }
    .hero-banner::after {
        content: '';
        position: absolute; right: 20px; bottom: -40px;
        width: 140px; height: 140px;
        border-radius: 50%;
        background: rgba(255,255,255,0.07);
    }
    .hero-banner .emoji {
        position: absolute; right: 2rem; top: 50%;
        transform: translateY(-50%);
        font-size: 4rem;
        opacity: 0.18;
        z-index: 0;
    }
    .hero-banner h2 { font-size: 1.2rem; font-weight: 700; margin-bottom: 0.3rem; }
    .hero-banner p { font-size: 0.82rem; opacity: 0.85; margin: 0; }
    .hero-badge {
        display: inline-block;
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 20px;
        font-size: 0.7rem;
        padding: 0.2rem 0.7rem;
        margin-bottom: 0.6rem;
        backdrop-filter: blur(4px);
    }

    /* ===== STAT CARDS ===== */
    .stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
    }
    @media (max-width: 900px) { .stat-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px) { .stat-grid { grid-template-columns: 1fr; } }

    .stat-card {
        background: #fff;
        border: 1px solid rgba(0,0,0,0.08);
        border-radius: 14px;
        padding: 1.25rem;
        transition: transform 0.18s, box-shadow 0.18s;
        cursor: default;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }

    .stat-icon {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; margin-bottom: 1rem; flex-shrink: 0;
    }
    .stat-icon.orange { background: rgba(229,90,43,0.12); color: #E55A2B; }
    .stat-icon.green  { background: rgba(34,197,94,0.12);  color: #16A34A; }
    .stat-icon.blue   { background: rgba(14,165,233,0.12); color: #0284C7; }
    .stat-icon.amber  { background: rgba(245,158,11,0.12); color: #D97706; }

    .stat-label { font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #999; margin-bottom: 0.3rem; }
    .stat-value { font-size: 1.6rem; font-weight: 700; color: #1A1A1A; line-height: 1; margin-bottom: 0.4rem; }
    .stat-value.sm { font-size: 1.15rem; }
    .stat-trend { font-size: 0.72rem; display: flex; align-items: center; gap: 0.25rem; }
    .trend-up { color: #16A34A; }
    .trend-down { color: #DC2626; }
    .trend-neutral { color: #999; }

    /* ===== MINI SPARKLINE ===== */
    .sparkline { height: 36px; width: 100%; }

    /* ===== CHART SECTION ===== */
    .chart-card { background: #fff; border: 1px solid rgba(0,0,0,0.08); border-radius: 14px; }
    .chart-title { font-size: 0.85rem; font-weight: 700; }
    .chart-sub { font-size: 0.72rem; color: #999; }

    .bar-chart-wrap { display: flex; align-items: flex-end; gap: 6px; height: 100px; padding: 0 4px; }
    .bar {
        flex: 1; border-radius: 5px 5px 0 0;
        position: relative; cursor: pointer;
        transition: all 0.2s;
        min-width: 0;
    }
    .bar:hover { filter: brightness(1.1); }
    .bar-label { font-size: 0.6rem; color: #aaa; text-align: center; margin-top: 5px; }
    .bar-labels { display: flex; gap: 6px; padding: 0 4px; }
    .bar-labels span { flex: 1; font-size: 0.6rem; color: #bbb; text-align: center; }

    /* ===== ACTIVITY FEED ===== */
    .activity-item {
        display: flex; align-items: flex-start; gap: 0.75rem;
        padding: 0.65rem 0;
        border-bottom: 1px solid rgba(0,0,0,0.06);
    }
    .activity-item:last-child { border-bottom: none; }
    .activity-dot {
        width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; margin-top: 5px;
    }

    /* ===== TOP PRODUCTS ===== */
    .top-prod {
        display: flex; align-items: center; gap: 0.75rem;
        padding: 0.7rem 1.25rem;
        border-bottom: 1px solid rgba(0,0,0,0.06);
        transition: background 0.12s;
    }
    .top-prod:last-child { border-bottom: none; }
    .top-prod:hover { background: #FAFAF9; }
    .rank { width: 24px; height: 24px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700; flex-shrink: 0; }
    .rank-1 { background: rgba(234,179,8,0.15); color: #B45309; }
    .rank-2 { background: rgba(156,163,175,0.15); color: #4B5563; }
    .rank-3 { background: rgba(180,83,9,0.12); color: #92400E; }
    .rank-n { background: rgba(0,0,0,0.06); color: #888; }
    .prod-thumb { width: 38px; height: 38px; border-radius: 10px; object-fit: cover; flex-shrink: 0; }
    .prod-placeholder { width: 38px; height: 38px; border-radius: 10px; background: rgba(229,90,43,0.1); display: flex; align-items: center; justify-content: center; color: #E55A2B; font-size: 1rem; flex-shrink: 0; }
    .prod-name { font-size: 0.8rem; font-weight: 600; }
    .prod-cat { font-size: 0.68rem; color: #aaa; }
    .prod-price { font-size: 0.78rem; font-weight: 700; color: #E55A2B; white-space: nowrap; }

    /* ===== PELANGGAN ===== */
    .cust-item {
        display: flex; align-items: center; gap: 0.75rem;
        padding: 0.65rem 1.25rem;
        border-bottom: 1px solid rgba(0,0,0,0.06);
        transition: background 0.12s;
    }
    .cust-item:last-child { border-bottom: none; }
    .cust-item:hover { background: #FAFAF9; }
    .cust-avatar {
        width: 36px; height: 36px; border-radius: 10px;
        background: rgba(229,90,43,0.1); color: #E55A2B;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.8rem; flex-shrink: 0;
    }

    /* ===== QUICK ACTIONS ===== */
    .qa-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.75rem; margin-bottom: 1.5rem; }
    @media (max-width: 700px) { .qa-grid { grid-template-columns: repeat(2, 1fr); } }

    .qa-card {
        background: #fff;
        border: 1px solid rgba(0,0,0,0.08);
        border-radius: 12px;
        padding: 0.9rem 1rem;
        display: flex; align-items: center; gap: 0.75rem;
        font-size: 0.78rem; font-weight: 600; color: #333;
        transition: all 0.15s; cursor: pointer;
        text-decoration: none;
    }
    .qa-card:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-light); transform: translateY(-1px); }
    .qa-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }

    /* ===== STATUS BADGES ===== */
    .badge-success { background: rgba(34,197,94,0.12); color: #15803D; font-size: 0.68rem; font-weight: 600; padding: 0.25rem 0.6rem; border-radius: 20px; }
    .badge-warning { background: rgba(245,158,11,0.12); color: #B45309; font-size: 0.68rem; font-weight: 600; padding: 0.25rem 0.6rem; border-radius: 20px; }
    .badge-danger  { background: rgba(239,68,68,0.12);  color: #B91C1C; font-size: 0.68rem; font-weight: 600; padding: 0.25rem 0.6rem; border-radius: 20px; }
    .badge-orange  { background: rgba(229,90,43,0.12);  color: #C2410C; font-size: 0.68rem; font-weight: 600; padding: 0.25rem 0.6rem; border-radius: 20px; }

    /* ===== SECTION HEADER ===== */
    .sec-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
    .sec-title { font-size: 0.88rem; font-weight: 700; }
    .sec-link { font-size: 0.72rem; color: #E55A2B; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 2px; }
    .sec-link:hover { text-decoration: underline; }

    /* Donut chart */
    .donut-wrap { display: flex; align-items: center; gap: 1.5rem; }
    .donut-legend { font-size: 0.75rem; }
    .legend-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; margin-right: 5px; }
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-1">
    <div>
        <h1 style="font-size:1.3rem;font-weight:700;margin-bottom:0.15rem;">Dashboard</h1>
        <p style="color:#999;font-size:0.78rem;margin:0;">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</p>
    </div>
</div>

{{-- Hero Banner --}}
<div class="hero-banner">
    <span class="hero-badge">🇰🇷 Seoullicious Admin</span>
    <h2>Annyeonghaseyo, {{ auth()->user()->nama ?? 'Admin' }}! 👋</h2>
    <p>Berikut ringkasan performa toko makanan Korea kamu hari ini.</p>
    <div class="emoji">🍜</div>
</div>

@if($promoEndingSoon->count())

<div class="alert alert-warning border-0 shadow-sm mb-3"
     style="border-radius:14px;">

    <div class="d-flex align-items-center gap-2">

        <i class="ti ti-alert-triangle"
           style="font-size:1.2rem;"></i>

        <div>

            <div class="fw-bold">
                Promo Akan Berakhir
            </div>

            <div style="font-size:.85rem;">

                @foreach($promoEndingSoon as $promo)

                    <div>
                        🎁 {{ $promo->nama_promo }}
                        berakhir pada
                        {{ \Carbon\Carbon::parse($promo->tanggal_selesai)->format('d M Y') }}
                    </div>

                @endforeach

            </div>

        </div>

    </div>
</div>

@endif
{{-- Stat Cards --}}
<div class="stat-grid" style="grid-template-columns: repeat(7, 1fr);">
    <div class="stat-card">
        <div class="stat-icon orange"><i class="ti ti-bowl-chopsticks"></i></div>
        <div class="stat-label">Total Produk</div>
        <div class="stat-value">{{ $totalProduk ?? 0 }}</div>
        <div class="stat-trend trend-neutral"><i class="ti ti-point-filled"></i> Menu tersedia</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green"><i class="ti ti-users"></i></div>
        <div class="stat-label">Total Pelanggan</div>
        <div class="stat-value">{{ $totalPelanggan ?? 0 }}</div>
        <div class="stat-trend trend-up"><i class="ti ti-trending-up"></i> Akun terdaftar</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue"><i class="ti ti-receipt"></i></div>
        <div class="stat-label">Total Transaksi</div>
        <div class="stat-value">{{ $totalTransaksi ?? 0 }}</div>
        <div class="stat-trend trend-neutral"><i class="ti ti-point-filled"></i> Semua waktu</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon amber"><i class="ti ti-cash"></i></div>
        <div class="stat-label">Total Pendapatan</div>
        <div class="stat-value sm">Rp{{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</div>
        <div class="stat-trend trend-up"><i class="ti ti-trending-up"></i> Akumulasi</div>
    </div>

    <div class="stat-card">
    <div class="stat-icon green">
        <i class="ti ti-chart-line"></i>
    </div>

    <div class="stat-label">Total Profit</div>

    <div class="stat-value sm">
        Rp{{ number_format($totalProfit ?? 0, 0, ',', '.') }}
    </div>

    <div class="stat-trend trend-up">
        <i class="ti ti-trending-up"></i>
        Keuntungan bersih
    </div>
</div>

<div class="stat-card">
    <div class="stat-icon blue">
        <i class="ti ti-calendar-week"></i>
    </div>

    <div class="stat-label">Minggu Ini</div>

    <div class="stat-value sm">
        Rp{{ number_format($weeklyIncome ?? 0, 0, ',', '.') }}
    </div>

    <div class="stat-trend trend-up">
        <i class="ti ti-arrow-up"></i>
        Pendapatan mingguan
    </div>
</div>

<div class="stat-card">
    <div class="stat-icon orange">
        <i class="ti ti-calendar-month"></i>
    </div>

    <div class="stat-label">Bulan Ini</div>

    <div class="stat-value sm">
        Rp{{ number_format($monthlyIncome ?? 0, 0, ',', '.') }}
    </div>

    <div class="stat-trend trend-up">
        <i class="ti ti-chart-bar"></i>
        Pendapatan bulanan
    </div>
</div>
</div>

{{-- Quick Actions --}}
<div class="qa-grid">
    <a href="{{ route('admin.produk.create') }}" class="qa-card">
        <div class="qa-icon" style="background:rgba(229,90,43,0.1);color:#E55A2B;"><i class="ti ti-plus"></i></div>
        Tambah Produk
    </a>
    <a href="{{ route('admin.kategori.index') }}" class="qa-card">
        <div class="qa-icon" style="background:rgba(34,197,94,0.1);color:#16A34A;"><i class="ti ti-tag"></i></div>
        Kategori
    </a>
    <a href="{{ route('admin.transaksi.index') }}" class="qa-card">
        <div class="qa-icon" style="background:rgba(14,165,233,0.1);color:#0284C7;"><i class="ti ti-receipt"></i></div>
        Transaksi
    </a>
    <a href="{{ route('admin.pelanggan.index') }}" class="qa-card">
        <div class="qa-icon" style="background:rgba(245,158,11,0.1);color:#D97706;"><i class="ti ti-users"></i></div>
        Pelanggan
    </a>
    <!-- <a href="{{ route('admin.trash') }}" class="qa-card">
        <div class="qa-icon" style="background:rgba(239,68,68,0.1);color:#EF4444;">
            <i class="ti ti-trash"></i>
        </div>
        Trash
    </a> -->
</div>

{{-- Main Grid --}}
<div class="row g-3 mb-3">

    {{-- Transaksi Chart --}}
    <div class="col-12 col-lg-7">
        <div class="chart-card h-100">
            <div class="d-flex align-items-center justify-content-between px-4 py-3" style="border-bottom:1px solid rgba(0,0,0,0.07);">
                <div>
                    <div class="chart-title">Transaksi per Bulan</div>
                    <div class="chart-sub">Performa 6 bulan terakhir</div>
                </div>
                <span class="badge-orange">2025</span>
            </div>
            <div class="px-4 py-3">
                <div class="bar-chart-wrap" id="monthChart">
                    {{-- Bars filled by JS --}}
                </div>
                <div class="bar-labels" id="monthLabels"></div>
            </div>
        </div>
    </div>

    {{-- Stok Overview --}}
    <div class="col-12 col-lg-5">
        <div class="chart-card h-100">
            <div class="px-4 py-3" style="border-bottom:1px solid rgba(0,0,0,0.07);">
                <div class="chart-title">Ringkasan Stok</div>
                <div class="chart-sub">Status ketersediaan produk</div>
            </div>
            <div class="px-4 py-4">
                @php
                    $produkAll = \App\Models\Produk::all();
                    $stokAman  = $produkAll->where('stok', '>', 10)->count();
                    $stokSedikit = $produkAll->where('stok', '>', 0)->where('stok', '<=', 10)->count();
                    $stokHabis   = $produkAll->where('stok', 0)->count();
                    $total = max($produkAll->count(), 1);
                @endphp
                <div class="d-flex align-items-center gap-3 mb-3">
                    <svg width="90" height="90" viewBox="0 0 90 90">
                        <circle cx="45" cy="45" r="35" fill="none" stroke="#F0F0F0" stroke-width="12"/>
                        @php
                            $pct1 = ($stokAman/$total)*219.9;
                            $pct2 = ($stokSedikit/$total)*219.9;
                            $pct3 = ($stokHabis/$total)*219.9;
                        @endphp
                        <circle cx="45" cy="45" r="35" fill="none" stroke="#22C55E" stroke-width="12"
                            stroke-dasharray="{{ $pct1 }} 219.9"
                            stroke-dashoffset="0"
                            transform="rotate(-90 45 45)"/>
                        <circle cx="45" cy="45" r="35" fill="none" stroke="#F59E0B" stroke-width="12"
                            stroke-dasharray="{{ $pct2 }} 219.9"
                            stroke-dashoffset="{{ -$pct1 }}"
                            transform="rotate(-90 45 45)"/>
                        <circle cx="45" cy="45" r="35" fill="none" stroke="#EF4444" stroke-width="12"
                            stroke-dasharray="{{ $pct3 }} 219.9"
                            stroke-dashoffset="{{ -($pct1+$pct2) }}"
                            transform="rotate(-90 45 45)"/>
                        <text x="45" y="49" text-anchor="middle" font-size="14" font-weight="700" fill="#1A1A1A">{{ $total }}</text>
                    </svg>
                    <div style="flex:1;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span style="font-size:0.78rem;"><span class="legend-dot" style="background:#22C55E;"></span>Stok Aman</span>
                            <span style="font-size:0.78rem;font-weight:700;">{{ $stokAman }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span style="font-size:0.78rem;"><span class="legend-dot" style="background:#F59E0B;"></span>Hampir Habis</span>
                            <span style="font-size:0.78rem;font-weight:700;">{{ $stokSedikit }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span style="font-size:0.78rem;"><span class="legend-dot" style="background:#EF4444;"></span>Habis</span>
                            <span style="font-size:0.78rem;font-weight:700;">{{ $stokHabis }}</span>
                        </div>
                    </div>
                </div>
                @if($stokHabis > 0)
                <div class="d-flex align-items-center gap-2 p-2" style="background:rgba(239,68,68,0.07);border-radius:8px;border:1px solid rgba(239,68,68,0.15);">
                    <i class="ti ti-alert-triangle" style="color:#EF4444;font-size:0.9rem;"></i>
                    <span style="font-size:0.72rem;color:#B91C1C;">{{ $stokHabis }} produk kehabisan stok</span>
                    <a href="{{ route('admin.produk.index') }}" style="font-size:0.72rem;color:#E55A2B;font-weight:600;margin-left:auto;">Cek →</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Bottom Grid --}}
<div class="row g-3">

    {{-- Transaksi Terbaru --}}
    <div class="col-12 col-lg-7">
        <div class="chart-card">
            <div class="d-flex align-items-center justify-content-between px-4 py-3" style="border-bottom:1px solid rgba(0,0,0,0.07);">
                <div class="chart-title">🧾 Transaksi Terbaru</div>
                <a href="{{ route('admin.transaksi.index') }}" class="sec-link">Semua <i class="ti ti-arrow-right"></i></a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>No. Transaksi</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksiTerbaru ?? [] as $t)
                        <tr>
                            <td>
                            <a href="{{ route('admin.transaksi.show', $t->id) }}"
                            style="color:#E55A2B;font-weight:600;font-size:0.78rem;">
                                #TRX-{{ str_pad($t->id, 4, '0', STR_PAD_LEFT) }}
                            </a>
                        </td>
                        <td>{{ $t->user->name ?? '-' }}</td>
                        <td style="font-weight:600;">Rp{{ number_format($t->total_harga, 0, ',', '.') }}</td>
                        <td>
                            @if($t->approval_status == 'pending')
                                <span class="badge-warning">⏳ Pending</span>
                            @elseif($t->approval_status == 'approved')
                                <span class="badge-success">✅ Approved</span>
                            @elseif($t->approval_status == 'rejected')
                                <span class="badge-danger">❌ Rejected</span>
                            @elseif($t->approval_status == 'completed')
                                <span class="badge-info">🎉 Completed</span>
                            @else
                                <span class="badge-secondary">{{ $t->status }}</span>
                            @endif
                        </td>
                        <td style="color:#999;">{{ \Carbon\Carbon::parse($t->created_at)->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5" style="color:#ccc;">
                                <i class="ti ti-receipt-off" style="font-size:2rem;display:block;margin-bottom:0.5rem;opacity:0.3;"></i>
                                Belum ada transaksi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Right Column --}}
    <div class="col-12 col-lg-5 d-flex flex-column gap-3">

        {{-- Top Produk --}}
        <div class="chart-card">
            <div class="d-flex align-items-center justify-content-between px-4 py-3" style="border-bottom:1px solid rgba(0,0,0,0.07);">
                <div class="chart-title">🏆 Produk Terlaris</div>
                <a href="{{ route('admin.produk.index') }}" class="sec-link">Semua <i class="ti ti-arrow-right"></i></a>
            </div>
            @forelse($topProduk ?? [] as $i => $p)
            <div class="top-prod">
                <div class="rank {{ ['rank-1','rank-2','rank-3'][$i] ?? 'rank-n' }}">{{ $i+1 }}</div>
                @if(isset($p->poto) && $p->poto)
                    <img src="{{ asset('storage/'.$p->poto) }}" class="prod-thumb" alt="">
                @else
                    <div class="prod-placeholder"><i class="ti ti-bowl-chopsticks"></i></div>
                @endif
                <div style="flex:1;min-width:0;">
                    <div class="prod-name text-truncate">{{ $p->nama ?? '-' }}</div>
                    <div class="prod-cat">{{ $p->kategori ?? 'Produk' }}</div>
                </div>
                <div class="prod-price">Rp{{ number_format($p->harga ?? 0, 0, ',', '.') }}</div>
            </div>
            @empty
            <div class="text-center py-4" style="color:#ccc;font-size:0.82rem;">
                <i class="ti ti-package-off" style="font-size:1.8rem;display:block;margin-bottom:0.4rem;opacity:0.3;"></i>
                Belum ada data produk
            </div>
            @endforelse
        </div>

        {{-- Pelanggan Terbaru --}}
        <div class="chart-card">
            <div class="d-flex align-items-center justify-content-between px-4 py-3" style="border-bottom:1px solid rgba(0,0,0,0.07);">
                <div class="chart-title">👥 Pelanggan Terbaru</div>
                <a href="{{ route('admin.pelanggan.index') }}" class="sec-link">Semua <i class="ti ti-arrow-right"></i></a>
            </div>
            @forelse($pelangganTerbaru ?? [] as $u)
            <div class="cust-item">
                <div class="cust-avatar">{{ strtoupper(substr($u->nama, 0, 1)) }}</div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:0.8rem;font-weight:600;" class="text-truncate">{{ $u->nama }}</div>
                    <div style="font-size:0.7rem;color:#aaa;" class="text-truncate">{{ $u->email }}</div>
                </div>
                <div style="font-size:0.68rem;color:#bbb;white-space:nowrap;">
                    {{ \Carbon\Carbon::parse($u->created_at)->diffForHumans() }}
                </div>
            </div>
            @empty
            <div class="text-center py-4" style="color:#ccc;font-size:0.82rem;">
                <i class="ti ti-users-off" style="font-size:1.8rem;display:block;margin-bottom:0.4rem;opacity:0.3;"></i>
                Belum ada pelanggan
            </div>
            @endforelse
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
// Bar chart transaksi per bulan (dummy data — ganti dengan data real dari controller)
const months = ['Jan','Feb','Mar','Apr','Mei','Jun'];
const values = {!! json_encode($monthlyData ?? [12, 19, 8, 24, 17, 30]) !!};
const max = Math.max(...values);
const colors = ['#FDDCCC','#FBBFA3','#F9A17A','#F68252','#F4622A','#E55A2B'];

const chartEl = document.getElementById('monthChart');
const labelsEl = document.getElementById('monthLabels');

values.forEach((v, i) => {
    const pct = (v / max) * 100;
    const bar = document.createElement('div');
    bar.className = 'bar';
    bar.style.height = pct + '%';
    bar.style.background = colors[i];
    bar.title = months[i] + ': ' + v + ' transaksi';
    chartEl.appendChild(bar);

    const lbl = document.createElement('span');
    lbl.className = 'bar-label';
    lbl.textContent = months[i];
    labelsEl.appendChild(lbl);
});
</script>
@endpush