@extends('layouts.app')

@section('title', 'Keranjang Belanja — Seoullicious')

@section('content')

<style>
/* ======================================================
   CART PAGE — Seoullicious (responsive fix + auto promo)
====================================================== */

/* ── CSS VARIABLES FALLBACK ── */
:root {
  --cream:    #FDF6EC;
  --soft:     #F7EFE3;
  --red:      #C0392B;
  --dark-red: #8B1A1A;
  --gold:     #D4A853;
  --dark:     #1A1010;
  --text:     #3D2B2B;
}

/* ── HERO ── */
.cart-hero {
  min-height: 260px;
  display: flex;
  align-items: flex-end;
  padding: 100px 60px 48px;
  background: var(--cream);
  position: relative;
  overflow: hidden;
}
.cart-hero::before {
  content: '';
  position: absolute; inset: 0;
  background-image:
    radial-gradient(circle at 80% 20%, rgba(192,57,43,0.06) 0%, transparent 50%),
    radial-gradient(circle at 10% 80%, rgba(212,168,83,0.08) 0%, transparent 40%);
  z-index: 0;
}
.cart-hero-deco {
  position: absolute;
  font-family: 'Noto Serif KR', serif;
  font-size: 16rem;
  color: rgba(139,26,26,0.04);
  top: 50%; right: -40px;
  transform: translateY(-50%);
  line-height: 1;
  user-select: none; pointer-events: none; z-index: 0;
}
.cart-hero-inner {
  position: relative; z-index: 1;
  display: flex; align-items: flex-end;
  justify-content: space-between;
  width: 100%; gap: 20px; flex-wrap: wrap;
}
.cart-hero-breadcrumb {
  display: inline-flex; align-items: center; gap: 8px;
  background: var(--soft); border: 1px solid rgba(139,26,26,0.15);
  border-radius: 50px; padding: 6px 18px;
  font-size: 0.76rem; letter-spacing: 2px; text-transform: uppercase;
  color: var(--dark-red); margin-bottom: 18px; text-decoration: none;
  transition: background 0.2s;
}
.cart-hero-breadcrumb:hover { background: rgba(139,26,26,0.08); }
.cart-hero-breadcrumb .dot { width: 6px; height: 6px; background: var(--gold); border-radius: 50%; flex-shrink: 0; }
.cart-hero-title {
  font-family: 'Playfair Display', serif;
  font-size: clamp(2rem, 5vw, 3.8rem);
  font-weight: 900; color: var(--dark); line-height: 1.08; margin-bottom: 12px;
}
.cart-hero-title em { color: var(--red); font-style: italic; }
.cart-hero-sub { font-size: 0.95rem; color: #7A5050; line-height: 1.7; }
.cart-hero-stats { display: flex; gap: 28px; flex-wrap: wrap; }
.cart-hstat-num {
  font-family: 'Playfair Display', serif;
  font-size: 1.8rem; font-weight: 700; color: var(--dark-red);
}
.cart-hstat-label { font-size: 0.75rem; color: #9A7070; }

/* ── MAIN LAYOUT ── */
.cart-page-wrap {
  padding: 32px 60px 96px;
  display: grid;
  grid-template-columns: 1fr 380px;
  gap: 28px; align-items: start;
}

/* ── SECTION LABEL ── */
.cart-slabel {
  display: inline-flex; align-items: center; gap: 8px;
  font-size: 0.73rem; letter-spacing: 3px; text-transform: uppercase;
  color: var(--red); font-weight: 600; margin-bottom: 14px;
}
.cart-slabel::before { content:''; width:24px; height:2px; background:var(--gold); display:inline-block; }

/* ── PROMO BANNER (auto, dari DB) ── */
.cart-promo-banner {
  margin-bottom: 20px;
  border-radius: 16px;
  background: linear-gradient(135deg, #27AE60 0%, #1E8449 100%);
  padding: 16px 22px;
  display: flex; align-items: center; gap: 14px;
  position: relative; overflow: hidden;
}
.cart-promo-banner::before {
  content: '할인';
  position: absolute; right: -8px; bottom: -12px;
  font-family: 'Noto Serif KR', serif; font-size: 6rem;
  color: rgba(255,255,255,0.06); line-height: 1; pointer-events: none;
}
.promo-banner-icon { font-size: 2rem; flex-shrink: 0; }
.promo-banner-text { flex: 1; }
.promo-banner-label {
  display: inline-flex; background: rgba(255,255,255,0.2); color: #fff;
  padding: 2px 10px; border-radius: 50px;
  font-size: 0.68rem; letter-spacing: 2px; text-transform: uppercase;
  font-weight: 700; margin-bottom: 4px;
}
.promo-banner-title { font-family: 'Playfair Display', serif; font-size: 1rem; color: #fff; font-weight: 900; margin-bottom: 2px; }
.promo-banner-sub { font-size: 0.78rem; color: rgba(255,255,255,0.75); }
.promo-banner-amount {
  font-family: 'Playfair Display', serif;
  font-size: 1.4rem; color: #fff; font-weight: 900;
  white-space: nowrap; flex-shrink: 0;
}

/* ── ITEMS CARD ── */
.cart-items-card {
  background: var(--soft);
  border-radius: 20px; overflow: hidden;
  box-shadow: 0 4px 24px rgba(139,26,26,0.06);
}
.cart-items-head {
  padding: 20px 24px 18px;
  border-bottom: 1.5px solid rgba(139,26,26,0.08);
  display: flex; align-items: center; justify-content: space-between;
  flex-wrap: wrap; gap: 10px;
}
.cart-items-head-title {
  font-family: 'Playfair Display', serif;
  font-size: 1.15rem; font-weight: 700; color: var(--dark);
}
.cart-select-all {
  display: flex; align-items: center; gap: 8px;
  font-size: 0.82rem; color: var(--text); cursor: pointer; user-select: none;
}
.cart-select-all input[type=checkbox] { width: 15px; height: 15px; accent-color: var(--red); }

/* ── ITEM ROW ── */
.cart-item-row {
  display: grid;
  grid-template-columns: 22px 80px 1fr auto auto;
  align-items: center; gap: 16px;
  padding: 18px 24px;
  border-bottom: 1.5px solid rgba(139,26,26,0.06);
  background: var(--soft);
  opacity: 0; transform: translateY(16px);
  transition: opacity 0.4s ease, transform 0.4s ease, background 0.2s;
}
.cart-item-row.visible { opacity: 1; transform: translateY(0); }
.cart-item-row:last-of-type { border-bottom: none; }
.cart-item-row:hover { background: rgba(255,255,255,0.65); }
.cart-item-row input[type=checkbox] { width: 15px; height: 15px; accent-color: var(--red); cursor: pointer; }

.cart-item-img {
  width: 80px; height: 80px; border-radius: 14px;
  background: linear-gradient(135deg, var(--red) 0%, var(--gold) 100%);
  display: flex; align-items: center; justify-content: center;
  font-size: 2.1rem; flex-shrink: 0; overflow: hidden;
}
.cart-item-img img { width: 100%; height: 100%; object-fit: cover; }

.cart-item-info { min-width: 0; }
.cart-item-name {
  font-family: 'Playfair Display', serif;
  font-size: 1rem; font-weight: 700; color: var(--dark); margin-bottom: 4px;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.cart-item-cat {
  display: inline-block;
  background: rgba(139,26,26,0.08); color: var(--dark-red);
  border-radius: 50px; padding: 2px 10px;
  font-size: 0.7rem; letter-spacing: 1px; text-transform: uppercase;
  font-weight: 600; margin-bottom: 8px;
}
.cart-item-unit-price { font-size: 0.8rem; color: #9A7070; }

/* Qty Control */
.qty-control {
  display: flex; align-items: center;
  background: white; border-radius: 50px;
  overflow: hidden; border: 1.5px solid rgba(139,26,26,0.15);
  width: fit-content;
}
.qty-btn-c {
  width: 34px; height: 34px; border: none; background: transparent;
  cursor: pointer; font-size: 1.1rem; font-weight: 700; color: var(--dark-red);
  display: flex; align-items: center; justify-content: center; transition: background 0.2s;
  flex-shrink: 0;
}
.qty-btn-c:hover { background: rgba(139,26,26,0.08); }
.qty-num-c {
  width: 36px; text-align: center;
  font-size: 0.92rem; font-weight: 700; color: var(--dark);
  font-family: 'Playfair Display', serif;
  border: none; background: transparent;
  -moz-appearance: textfield;
}
.qty-num-c::-webkit-outer-spin-button,
.qty-num-c::-webkit-inner-spin-button { -webkit-appearance: none; }

.cart-item-actions { display: flex; flex-direction: column; align-items: flex-end; gap: 10px; flex-shrink: 0; }
.cart-item-line-price {
  font-family: 'Playfair Display', serif;
  font-size: 1.05rem; font-weight: 700; color: var(--red); white-space: nowrap;
}
.cart-remove-btn {
  background: none; border: none; cursor: pointer;
  color: rgba(139,26,26,0.3); font-size: 0.78rem;
  display: flex; align-items: center; gap: 4px;
  transition: color 0.2s; font-family: 'DM Sans', sans-serif;
  white-space: nowrap;
}
.cart-remove-btn:hover { color: var(--red); }

/* Cart footer */
.cart-items-footer {
  padding: 14px 24px;
  display: flex; align-items: center; justify-content: space-between;
  border-top: 1.5px solid rgba(139,26,26,0.08);
  background: rgba(255,255,255,0.5);
  flex-wrap: wrap; gap: 10px;
}
.cart-add-more {
  font-size: 0.82rem; color: var(--dark-red); font-weight: 600;
  text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
  transition: gap 0.2s;
}
.cart-add-more:hover { gap: 10px; color: var(--red); }
.cart-del-sel {
  font-size: 0.8rem; color: #9A7070; background: none;
  border: none; cursor: pointer; transition: color 0.2s;
}
.cart-del-sel:hover { color: var(--red); }

/* ── EMPTY STATE ── */
.cart-empty-state { padding: 72px 24px; text-align: center; }
.cart-empty-state .empty-icon { font-size: 4.5rem; margin-bottom: 18px; }
.cart-empty-state h3 {
  font-family: 'Playfair Display', serif;
  font-size: 1.5rem; color: var(--dark); margin-bottom: 8px;
}
.cart-empty-state p { font-size: 0.88rem; color: #9A7070; margin-bottom: 28px; }

/* ── SIDEBAR ── */
.cart-sidebar { position: sticky; top: 88px; display: flex; flex-direction: column; gap: 18px; }

.sbar-card {
  background: white; border-radius: 22px;
  box-shadow: 0 20px 60px rgba(139,26,26,0.07); overflow: hidden;
}
.sbar-card-head {
  padding: 18px 22px 16px;
  border-bottom: 1.5px solid rgba(139,26,26,0.07);
}
.sbar-card-body { padding: 18px 22px; }

.sbar-line {
  display: flex; justify-content: space-between;
  align-items: center;
  font-size: 0.83rem; margin-bottom: 9px; gap: 8px;
}
.sbar-line .lbl { color: #9A7070; flex: 1; }
.sbar-line .val { font-weight: 600; color: var(--text); text-align: right; }
.sbar-line .free { color: #27AE60; font-weight: 700; }
.sbar-line .disc { color: #27AE60; font-weight: 700; }
.sbar-hr { border: none; border-top: 1px dashed rgba(139,26,26,0.12); margin: 12px 0; }
.sbar-total {
  display: flex; justify-content: space-between; align-items: center;
}
.sbar-total .lbl {
  font-family: 'Playfair Display', serif;
  font-size: 1rem; font-weight: 700; color: var(--dark);
}
.sbar-total .val {
  font-family: 'Playfair Display', serif;
  font-size: 1.3rem; color: var(--red); font-weight: 900;
}

/* Form fields */
.sbar-fg { margin-bottom: 14px; }
.sbar-fg label {
  display: block; font-size: 0.74rem; letter-spacing: 1px;
  text-transform: uppercase; color: #9A7070; margin-bottom: 6px; font-weight: 600;
}
.sbar-fg select,
.sbar-fg input,
.sbar-textarea {
  width: 100%; padding: 11px 14px;
  border: 1.5px solid rgba(139,26,26,0.1); border-radius: 11px;
  font-size: 0.88rem; font-family: 'DM Sans', sans-serif;
  color: var(--text); background: var(--cream); outline: none;
  transition: border-color 0.25s;
  box-sizing: border-box;
}
.sbar-textarea {
  resize: vertical; min-height: 80px;
  -webkit-appearance: none; appearance: none;
}
.sbar-fg select {
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%239A7070' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
  background-repeat: no-repeat; background-position: right 14px center;
  background-color: var(--cream);
  padding-right: 36px;
  -webkit-appearance: none; appearance: none;
}
.sbar-fg select:focus,
.sbar-fg input:focus,
.sbar-textarea:focus { border-color: var(--red); background: white; }

/* CTA */
.cart-checkout-btn {
  width: 100%; padding: 15px;
  background: var(--dark-red); color: var(--cream);
  border: none; border-radius: 50px;
  font-size: 0.92rem; font-weight: 600;
  font-family: 'DM Sans', sans-serif; cursor: pointer;
  transition: background 0.3s, transform 0.2s, box-shadow 0.3s;
  margin-top: 4px; box-sizing: border-box;
}
.cart-checkout-btn:hover {
  background: var(--red); transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(139,26,26,0.35);
}
.cart-checkout-btn:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }
.cart-checkout-btn .cta-sub {
  display: block; font-size: 0.76rem; font-weight: 400; opacity: .7; margin-top: 2px;
}

/* Payment boxes */
.payment-info-box {
  background: #fff;
  border: 1.5px solid rgba(139,26,26,0.1);
  border-radius: 18px; padding: 18px;
  margin-bottom: 18px;
}

/* ── RESPONSIVE ── */
@media (max-width: 1080px) {
  .cart-hero { padding: 100px 36px 44px; }
  .cart-page-wrap { padding: 28px 36px 80px; grid-template-columns: 1fr 330px; }
}

@media (max-width: 860px) {
  .cart-hero { padding: 90px 18px 36px; min-height: 220px; }
  .cart-hero-deco { font-size: 10rem; }
  .cart-hero-stats { display: none; }

  .cart-page-wrap {
    grid-template-columns: 1fr;
    padding: 20px 16px 60px;
    gap: 20px;
  }
  .cart-sidebar { position: static; }

  /* Item row: 3 kolom + baris kedua untuk qty & harga */
  .cart-item-row {
    grid-template-columns: 20px 68px 1fr;
    grid-template-rows: auto auto;
    gap: 10px 12px;
    padding: 14px 16px;
  }
  .cart-item-row input[type=checkbox] { align-self: start; margin-top: 4px; }
  .cart-item-img { width: 68px; height: 68px; border-radius: 12px; }
  .qty-ctrl-wrap {
    grid-column: 2 / -1;
    display: flex; align-items: center; justify-content: space-between; gap: 12px;
  }
  .cart-item-actions {
    flex-direction: row; align-items: center;
    justify-content: flex-end; gap: 12px;
  }
}

@media (max-width: 480px) {
  .cart-items-head { padding: 16px; }
  .cart-items-footer { padding: 12px 16px; }
  .sbar-card-body { padding: 16px; }
  .sbar-card-head { padding: 14px 16px; }
  .cart-item-name { font-size: 0.92rem; }
  .cart-checkout-btn { padding: 13px; font-size: 0.88rem; }
  .cart-promo-banner { flex-direction: column; align-items: flex-start; gap: 8px; }
  .promo-banner-amount { font-size: 1.2rem; }
}
</style>

{{-- ── HERO ── --}}
<div class="cart-hero">
  <div class="cart-hero-deco">장바구니</div>
  <div class="cart-hero-inner">
    <div>
      <a href="{{route('home')}}" class="cart-hero-breadcrumb">
        <span class="dot"></span> ← Kembali 
      </a>
      <h1 class="cart-hero-title">Keranjang <em>Belanja</em>mu</h1>
      <p class="cart-hero-sub">{{ count($cart) }} item sudah menunggu untuk dipesan.</p>
    </div>
    <div class="cart-hero-stats">
      <div>
        <div class="cart-hstat-num">{{ count($cart) }}</div>
        <div class="cart-hstat-label">Item</div>
      </div>
      <div>
        <div class="cart-hstat-num">Rp {{ number_format($total, 0, ',', '.') }}</div>
        <div class="cart-hstat-label">Total Belanja</div>
      </div>
    </div>
  </div>
</div>

{{-- MARQUEE --}}
<div class="marquee-section">
  <div class="marquee-track">
    <div class="marquee-item">Bibimbap <span class="marquee-dot">✦</span></div>
    <div class="marquee-item">Tteokbokki <span class="marquee-dot">✦</span></div>
    <div class="marquee-item">Korean BBQ <span class="marquee-dot">✦</span></div>
    <div class="marquee-item">Kimchi Jjigae <span class="marquee-dot">✦</span></div>
    <div class="marquee-item">Japchae <span class="marquee-dot">✦</span></div>
    <div class="marquee-item">Sundubu Jjigae <span class="marquee-dot">✦</span></div>
    <div class="marquee-item">Kimbap <span class="marquee-dot">✦</span></div>
    <div class="marquee-item">Dakgalbi <span class="marquee-dot">✦</span></div>
    <div class="marquee-item">Bibimbap <span class="marquee-dot">✦</span></div>
    <div class="marquee-item">Tteokbokki <span class="marquee-dot">✦</span></div>
    <div class="marquee-item">Korean BBQ <span class="marquee-dot">✦</span></div>
    <div class="marquee-item">Kimchi Jjigae <span class="marquee-dot">✦</span></div>
  </div>
</div>

{{-- ── MAIN ── --}}
<div class="cart-page-wrap">

  {{-- LEFT --}}
  <div>
    <div class="cart-slabel">Produk Dipilih</div>

    {{-- BANNER PROMO OTOMATIS dari DB --}}
    @if(!empty($cart) && $promo && $diskon > 0)
    <div class="cart-promo-banner">
      <div class="promo-banner-icon">🎉</div>
      <div class="promo-banner-text">
        <div class="promo-banner-label">Promo Aktif</div>
        <div class="promo-banner-title">{{ $promo->nama_promo }}</div>
        <div class="promo-banner-sub">
          Diskon {{ $promo->diskon }}% 
          @if($promo->deskripsi) · {{ $promo->deskripsi }} @endif
        </div>
      </div>
      <div class="promo-banner-amount">−Rp {{ number_format($diskon, 0, ',', '.') }}</div>
    </div>
    @endif

    <div class="cart-items-card">

      @if(empty($cart))
      <div class="cart-empty-state">
        <div class="empty-icon">🛒</div>
        <h3>Keranjangmu masih kosong</h3>
        <p>Yuk, tambahkan menu lezat dari dapur Seoul kami!</p>
        <a href="{{ route('home') }}" class="btn-primary">← Jelajahi Menu</a>
      </div>

      @else

      <div class="cart-items-head">
        <div class="cart-items-head-title">
          Pesanan Kamu
          <span style="font-size:.78rem;font-family:'DM Sans',sans-serif;font-weight:400;color:#9A7070;margin-left:6px">({{ count($cart) }} item)</span>
        </div>
        <label class="cart-select-all">
          <input type="checkbox" id="checkAll" onchange="toggleAll(this)" checked> Pilih Semua
        </label>
      </div>

      @foreach($cart as $key => $item)
      @php $lineTotal = $item['harga'] * $item['jumlah']; @endphp
      <div class="cart-item-row" id="row-{{ $key }}" data-reveal>

        <input type="checkbox" class="item-check"
               data-key="{{ $key }}" data-harga="{{ $item['harga'] }}"
               checked onchange="recalcSummary()">

        <div class="cart-item-img">
          @if(!empty($item['poto']))
            <img src="{{ Storage::url($item['poto']) }}" alt="{{ $item['nama'] }}"
                 onerror="this.parentNode.innerHTML='<span style=font-size:2rem>🍱</span>'">
          @else
            🍱
          @endif
        </div>

        <div class="cart-item-info">
          <div class="cart-item-name">{{ $item['nama'] }}</div>
          <div class="cart-item-cat">{{ ucfirst($item['kategori'] ?? 'menu') }}</div>
          <div class="cart-item-unit-price">Rp {{ number_format($item['harga'], 0, ',', '.') }} / porsi</div>
        </div>

        <div class="qty-ctrl-wrap">
          <form method="POST" action="{{ route('cart.update') }}" id="form-{{ $key }}">
            @csrf
            <input type="hidden" name="produk_id" value="{{ $key }}">
            <div class="qty-control">
              <button type="button" class="qty-btn-c" onclick="changeQty('{{ $key }}', -1)">−</button>
              <input type="number" name="jumlah" class="qty-num-c"
                     id="qty-{{ $key }}"
                     value="{{ $item['jumlah'] }}" min="1" max="{{ $item['stok'] ?? 999 }}"
                     onchange="submitQty('{{ $key }}')">
              <button type="button" class="qty-btn-c" onclick="changeQty('{{ $key }}', 1)">+</button>
            </div>
          </form>
        </div>

        <div class="cart-item-actions">
          <div class="cart-item-line-price" id="price-{{ $key }}">
            Rp {{ number_format($lineTotal, 0, ',', '.') }}
          </div>
          <form method="POST" action="{{ route('cart.remove', $key) }}">
            @csrf @method('DELETE')
            <button type="submit" class="cart-remove-btn">🗑 Hapus</button>
          </form>
        </div>

      </div>
      @endforeach

      <div class="cart-items-footer">
        <a href="{{ route('home') }}" class="cart-add-more">+ Tambah Produk Lain →</a>
        <button class="cart-del-sel" onclick="deleteSelected()">Hapus yang dipilih</button>
      </div>

      @endif
    </div>
  </div>

  {{-- SIDEBAR --}}
  @if(!empty($cart))
  <div class="cart-sidebar">

    {{-- Ringkasan Pesanan --}}
    <div class="sbar-card">
      <div class="sbar-card-head">
        <div class="cart-slabel" style="margin-bottom:0">Ringkasan Pesanan</div>
      </div>
      <div class="sbar-card-body">
        @foreach($cart as $item)
        <div class="sbar-line">
          <span class="lbl">{{ \Str::limit($item['nama'], 22) }} ×{{ $item['jumlah'] }}</span>
          <span class="val">Rp {{ number_format($item['harga'] * $item['jumlah'], 0, ',', '.') }}</span>
        </div>
        @endforeach
        <hr class="sbar-hr">
        <div class="sbar-line">
          <span class="lbl">Subtotal</span>
          <span class="val" id="sbarSubtotal">Rp {{ number_format($total, 0, ',', '.') }}</span>
        </div>
        <div class="sbar-line">
          <span class="lbl">Ongkos Kirim</span>
          <span class="val free">Gratis 🎉</span>
        </div>
        {{-- Diskon promo otomatis --}}
        <div class="sbar-line" id="sbarPromoRow" style="{{ $diskon > 0 ? '' : 'display:none' }}">
          <span class="lbl">
            Diskon Promo
            @if($promo) <span style="font-size:.7rem;background:rgba(39,174,96,.12);color:#27AE60;padding:1px 7px;border-radius:50px;font-weight:700">{{ $promo->diskon }}%</span> @endif
          </span>
          <span class="val disc" id="sbarDiscount">−Rp {{ number_format($diskon, 0, ',', '.') }}</span>
        </div>
        <hr class="sbar-hr">
        <div class="sbar-total">
          <span class="lbl">Total Bayar</span>
          <span class="val" id="sbarTotal">Rp {{ number_format($totalAfter, 0, ',', '.') }}</span>
        </div>
      </div>
    </div>

    {{-- Detail Pembelian --}}
    <div class="sbar-card">
      <div class="sbar-card-head">
        <div class="cart-slabel" style="margin-bottom:0">Detail Pembelian</div>
      </div>
      <div class="sbar-card-body">
        <form method="POST"
              action="{{ route('cart.checkout.process') }}"
              id="checkoutForm"
              enctype="multipart/form-data">
          @csrf

          {{-- Hidden: kirim info diskon ke backend --}}
          <input type="hidden" name="promo_id"    value="{{ $promo->id ?? '' }}">
          <input type="hidden" name="diskon_rp"   value="{{ $diskon }}">
          <input type="hidden" name="total_bayar" value="{{ $totalAfter }}" id="hiddenTotal">

          <div class="sbar-fg">
            <label>Nama Lengkap <span style="color:var(--red)">*</span></label>
            <input type="text" name="nama" value="{{ auth()->user()->name ?? '' }}" required>
          </div>

          <div class="sbar-fg">
            <label>Nomor Telepon <span style="color:var(--red)">*</span></label>
            <input type="text" name="telepon" required placeholder="08xx-xxxx-xxxx">
          </div>

          <div class="sbar-fg">
            <label>Alamat Lengkap</label>
            <textarea name="alamat" rows="3" class="sbar-textarea"
                      placeholder="Jl. Contoh No.123, RT/RW, Kecamatan, Kota"></textarea>
            <small style="display:block;margin-top:6px;color:#999;">Kosongkan jika ambil sendiri</small>
          </div>

          <div class="sbar-fg">
            <label>Metode Pengiriman</label>
            <select name="metode_kirim" id="metodeKirim" required>
              <option value="Ambil Sendiri">🚶 Ambil Sendiri (Gratis)</option>
              <option value="Diantar Kurir">🛵 Diantar Kurir</option>
            </select>
          </div>

          <div class="sbar-fg">
            <label>Metode Pembayaran</label>
            <select name="metode_bayar" id="metodeBayar" onchange="togglePaymentInfo()" required>
              <option value="Transfer Bank">🏦 Transfer Bank</option>
              <option value="QRIS">📱 QRIS</option>
              <option value="COD">💵 COD</option>
            </select>
          </div>

          {{-- QRIS --}}
          <div id="qrisBox" class="payment-info-box" style="display:none;text-align:center;">
            <div style="font-weight:700;margin-bottom:12px;">Scan QRIS</div>
            <img src="{{ asset('storage/images/qris.jpeg') }}"
                 style="width:220px;max-width:100%;border-radius:14px;box-shadow:0 10px 25px rgba(0,0,0,.08);">
            <div style="margin-top:10px;font-size:.82rem;color:#777;">
              GoPay • OVO • DANA • ShopeePay • M-Banking
            </div>
          </div>

          {{-- Transfer --}}
          <div id="bankBox" class="payment-info-box" style="display:none;">
            <div style="font-weight:700;margin-bottom:12px;">Transfer Bank</div>
            <div style="font-size:.9rem;line-height:1.9;">
              <b>BCA</b> : 1234567890<br>a/n Seoullicious
            </div>
          </div>

          {{-- Upload bukti --}}
          <div id="buktiBox" style="display:none;">
            <div class="sbar-fg">
              <label>Upload Bukti Pembayaran</label>
              <input type="file" name="bukti_bayar" accept="image/*"
                     style="padding:8px;font-size:.82rem;">
            </div>
          </div>

          <div class="sbar-fg">
            <label>Catatan (opsional)</label>
            <input type="text" name="catatan" placeholder="Pedas, tanpa bawang, dll">
          </div>

          <button type="submit" class="cart-checkout-btn" id="checkoutBtn">
            Pesan Sekarang 🎉
            <span class="cta-sub" id="ctaPrice">
              Rp {{ number_format($totalAfter, 0, ',', '.') }}
            </span>
          </button>
        </form>
      </div>
    </div>

  </div>
  @endif

</div>

<script>
/* ── Data dari PHP ── */
const itemHarga = {
  @foreach($cart as $key => $item)
  '{{ $key }}': {{ $item['harga'] }},
  @endforeach
};

const promoRate   = {{ $promo ? $promo->diskon / 100 : 0 }};
const hasPromo    = {{ $promo && $diskon > 0 ? 'true' : 'false' }};

/* ── Format Rupiah ── */
const fmt = n => 'Rp ' + Math.round(n).toLocaleString('id-ID');

/* ── Qty ── */
function changeQty(key, delta) {
  const el  = document.getElementById('qty-' + key);
  if (!el) return;
  const max = parseInt(el.max) || 999;
  let v = parseInt(el.value) + delta;
  if (v < 1) v = 1;
  if (v > max) v = max;
  el.value = v;

  const pr = document.getElementById('price-' + key);
  if (pr) pr.textContent = fmt(itemHarga[key] * v);
  recalcSummary();

  clearTimeout(el._t);
  el._t = setTimeout(() => updateQtyAjax(key, v), 600);
}

function submitQty(key) {
  const el = document.getElementById('qty-' + key);
  if (!el) return;
  let v = parseInt(el.value);
  if (isNaN(v) || v < 1) v = 1;
  if (v > parseInt(el.max || 999)) v = parseInt(el.max);
  el.value = v;

  const pr = document.getElementById('price-' + key);
  if (pr) pr.textContent = fmt(itemHarga[key] * v);
  recalcSummary();
  updateQtyAjax(key, v);
}

function updateQtyAjax(key, qty) {
  const token = document.querySelector('meta[name="csrf-token"]')?.content
             || document.querySelector('input[name="_token"]')?.value;

  fetch('{{ route("cart.update") }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': token,
      'Accept': 'application/json',
    },
    body: JSON.stringify({ produk_id: key, jumlah: qty })
  })
  .then(r => { if (!r.ok) console.error('Update qty gagal', r.status); })
  .catch(e => console.error('Fetch error:', e));
}

/* ── Select All ── */
function toggleAll(master) {
  document.querySelectorAll('.item-check').forEach(c => c.checked = master.checked);
  recalcSummary();
}

/* ── Recalc (dengan promo otomatis) ── */
function recalcSummary() {
  let sub = 0;
  document.querySelectorAll('.item-check').forEach(c => {
    if (!c.checked) return;
    const key = c.dataset.key;
    const qty = parseInt(document.getElementById('qty-' + key)?.value || 1);
    sub += (itemHarga[key] || 0) * qty;
  });

  const diskon = hasPromo ? Math.round(sub * promoRate) : 0;
  const total  = Math.max(0, sub - diskon);

  document.getElementById('sbarSubtotal').textContent = fmt(sub);

  const promoRow = document.getElementById('sbarPromoRow');
  const discEl   = document.getElementById('sbarDiscount');
  if (promoRow && discEl) {
    if (diskon > 0) {
      promoRow.style.display = '';
      discEl.textContent = '−' + fmt(diskon);
    } else {
      promoRow.style.display = 'none';
    }
  }

  document.getElementById('sbarTotal').textContent  = fmt(total);
  document.getElementById('ctaPrice').textContent   = fmt(total);

  const hiddenTotal = document.getElementById('hiddenTotal');
  if (hiddenTotal) hiddenTotal.value = total;
}

/* ── Delete selected ── */
function deleteSelected() {
  const checked = [...document.querySelectorAll('.item-check:checked')];
  if (!checked.length) { alert('Pilih item dulu ya!'); return; }
  if (!confirm('Hapus ' + checked.length + ' item terpilih?')) return;
  checked.forEach(c => {
    const row = document.getElementById('row-' + c.dataset.key);
    const del = row?.querySelector('form [name=_method]')?.closest('form');
    if (del) del.submit();
  });
}

/* ── Payment toggle ── */
function togglePaymentInfo() {
  const metode  = document.getElementById('metodeBayar').value;
  document.getElementById('qrisBox').style.display  = metode === 'QRIS'          ? 'block' : 'none';
  document.getElementById('bankBox').style.display  = metode === 'Transfer Bank' ? 'block' : 'none';
  document.getElementById('buktiBox').style.display = (metode === 'QRIS' || metode === 'Transfer Bank') ? 'block' : 'none';
}
togglePaymentInfo();

/* ── Checkout loading ── */
document.getElementById('checkoutForm')?.addEventListener('submit', function () {
  const btn = document.getElementById('checkoutBtn');
  btn.innerHTML = 'Memproses... ⏳';
  btn.disabled = true;
});

/* ── Scroll reveal ── */
const obs = new IntersectionObserver(entries => {
  entries.forEach(e => {
    if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); }
  });
}, { threshold: 0.08 });
document.querySelectorAll('[data-reveal]').forEach(el => obs.observe(el));

/* ── Init recalc ── */
recalcSummary();
</script>

@endsection