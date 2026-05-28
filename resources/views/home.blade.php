@extends('layouts.app')

@auth
    @if(isset($pendingOrdersCount) && $pendingOrdersCount > 0)

    <div id="pendingToast" style="
        position: fixed;
        bottom: 28px;
        right: 28px;
        z-index: 9999;
        max-width: 340px;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.06);
        border-left: 4px solid #f59e0b;
        padding: 16px 18px 16px 16px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        animation: toastIn 0.4s cubic-bezier(0.22,1,0.36,1);
        font-family: system-ui, sans-serif;
    ">
        <div style="
            width: 38px; height: 38px; border-radius: 10px;
            background: #fef3c7;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0;
        ">⏳</div>
        <div style="flex: 1; min-width: 0;">
            <div style="font-size: 13px; font-weight: 600; color: #111; margin-bottom: 3px;">
                {{ $pendingOrdersCount }} pesanan menunggu konfirmasi
            </div>
            <div style="font-size: 12px; color: #888; line-height: 1.5;">
                Pesanan akan diproses setelah admin menyetujui.
            </div>
        </div>
        <button onclick="document.getElementById('pendingToast').style.display='none'"
                style="background: none; border: none; cursor: pointer; color: #bbb; font-size: 16px; line-height: 1; padding: 0; flex-shrink: 0; margin-top: -2px; transition: color 0.15s;"
                onmouseover="this.style.color='#666'"
                onmouseout="this.style.color='#bbb'"
                aria-label="Tutup">✕</button>
    </div>

    <style>
    @keyframes toastIn {
        from { opacity: 0; transform: translateY(16px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    </style>

    <script>
    setTimeout(function() {
        var t = document.getElementById('pendingToast');
        if (t) {
            t.style.transition = 'opacity 0.4s, transform 0.4s';
            t.style.opacity = '0';
            t.style.transform = 'translateY(12px)';
            setTimeout(function(){ t.style.display = 'none'; }, 400);
        }
    }, 8000);
    </script>

    @endif
@endauth

@section('is-home')@endsection

@section('content')

<!-- ======================================================
     HERO
====================================================== -->
<section id="hero">
  <div class="hero-bg"></div>
  <div class="hero-deco">맛</div>
  <div class="hero-left">
    <div class="hero-tag">
      <span class="dot"></span>
      Authentic Korean Home Food
    </div>
    <h1 class="hero-title">
      Masakan Korea <em>Rumahan</em><br>
      yang Lezat & Terjangkau
    </h1>
    <p class="hero-sub">
      Rasakan cita rasa otentik Korea Selatan —
      dari bibimbap yang hangat hingga tteokbokki
      yang pedas menggoda, semua hadir untuk
      memanjakan lidahmu.
    </p>
    <div class="hero-btns">
      <a href="#menu" class="btn-primary">Lihat Menu</a>
      <a href="#reservation" class="btn-outline">Pesan Sekarang</a>
    </div>
    <div class="hero-stats">
      <div class="stat">
        <div class="stat-num">12K+</div>
        <div class="stat-label">Pelanggan Puas</div>
      </div>
      <div class="stat">
        <div class="stat-num">3+</div>
        <div class="stat-label">Tahun Berdiri</div>
      </div>
      <div class="stat">
        <div class="stat-num">100%</div>
        <div class="stat-label">Bahan Fresh</div>
      </div>
    </div>
  </div>
  <div class="hero-right">
    <div class="hero-img-wrap">
      <div class="hero-img-main">
        <img src="{{ asset('storage/home/rumahan3.jpg') }}" alt="Masakan Korea">
      </div>
      <div class="hero-float-card float-1">
        <div class="float-card-title">Rating Kami</div>
        <div class="float-stars">★★★★★</div>
        <div class="float-card-val">4.9 / 5.0</div>
      </div>
      <div class="hero-float-card float-2">
        <div class="float-card-title">Menu Favorit</div>
        <div class="float-card-val">🌶️ Tteokbokki</div>
      </div>
    </div>
  </div>
</section>

<!-- MARQUEE -->
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

<!-- ======================================================
     MENU
====================================================== -->
<section id="menu">
  <div class="menu-header">
    <div>
      <div class="section-label" data-reveal>Menu Kami</div>
      <h2 class="section-title" data-reveal>Sajian <em>terbaik</em><br>dari dapur Seoul</h2>
    </div>
    <div class="menu-tabs">
      <button class="tab active" onclick="filterMenu('all',this)">Semua</button>
      <button class="tab" onclick="filterMenu('main',this)">Main Course</button>
      <button class="tab" onclick="filterMenu('snack',this)">Snack</button>
      <button class="tab" onclick="filterMenu('soup',this)">Soup</button>
    </div>
  </div>
  <div class="menu-grid" id="menuGrid"></div>
</section>

<!-- PROMO -->
@if(isset($promoAktif) && $promoAktif)

<section id="promo">

  <div class="promo-left" data-reveal>

    <div class="promo-tag">✨ Promo Spesial</div>

    <h2 class="promo-title">
      {{ $promoAktif->nama_promo }}
    </h2>

    <p class="promo-sub">
      {{ $promoAktif->deskripsi ?? 'Nikmati promo eksklusif dari kami. Jangan sampai terlewat!' }}
      <br>

      <!-- <small style="opacity:0.75;font-size:0.82rem">
        📅 Berlaku sampai
        {{ \Carbon\Carbon::parse($promoAktif->tanggal_selesai)->translatedFormat('d F Y') }}
      </small> -->
    </p>

    <a href="#menu"
       class="btn-primary"
       style="background:var(--gold);color:var(--dark);">
      Pesan Sekarang
    </a>

  </div>

  <div class="promo-right" data-reveal">

    @if($promoAktif->banner)

      <div class="promo-dish" style="font-size:1rem;padding:0;overflow:hidden;border-radius:50%;">

        <img src="{{ asset('storage/' . $promoAktif->banner) }}"
             alt="{{ $promoAktif->nama_promo }}"
             style="width:240px;height:240px;object-fit:cover;border-radius:50%;
                    box-shadow:0 20px 60px rgba(0,0,0,0.4);">

      </div>

    @else

      <div class="promo-dish">🥗</div>

    @endif

    <div class="promo-badge">

      <div class="promo-badge-num">
        {{ $promoAktif->diskon }}%
      </div>

      <div class="promo-badge-label">
        📅 Berlaku sampai
        {{ \Carbon\Carbon::parse($promoAktif->tanggal_selesai)->translatedFormat('d F Y') }}
      </div>

    </div>

  </div>

</section>

@endif
<!-- ======================================================
     ABOUT
====================================================== -->
<section id="about">
  <div class="about-img-group">
    <div class="about-img-1" id="aboutImg1">
      <img src="{{ asset('storage/home/rumahan1.jpg') }}" alt="Masakan Rumahan">
    </div>
    <div class="about-img-2" id="aboutImg2">
      <img src="{{ asset('storage/home/rumahan2.jpg') }}" alt="Masakan Rumahan">
    </div>
    <div class="about-badge-float" id="aboutBadge">
      <div class="about-badge-icon">🏡</div>
      <div class="about-badge-text">Masakan Rumahan<br>Hangat & Fresh</div>
    </div>
  </div>
  <div class="about-right" id="aboutRight">
    <div class="section-label">Tentang Kami</div>
    <h2 class="section-title">
      Kehangatan <em>masakan rumahan</em><br>
      dalam setiap hidangan
    </h2>
    <p style="margin-top:18px;color:#7A5050;line-height:1.8;font-size:0.95rem">
      Seoullicious hadir untuk membawa rasa nyaman lewat masakan rumahan yang dibuat dengan resep spesial keluarga.
      Kami menggunakan bahan segar berkualitas dan memasak setiap hidangan dengan penuh perhatian agar terasa hangat,
      lezat, dan seperti masakan rumah sendiri.
    </p>
    <div class="about-values">
      <div class="value-item">
        <div class="value-icon">🥬</div>
        <div>
          <div class="value-title">Bahan Segar Pilihan</div>
          <div class="value-desc">Menggunakan bahan berkualitas agar rasa tetap fresh dan nikmat setiap hari</div>
        </div>
      </div>
      <div class="value-item">
        <div class="value-icon">🍳</div>
        <div>
          <div class="value-title">Resep Rumahan</div>
          <div class="value-desc">Dimasak dengan cita rasa khas rumahan yang sederhana namun bikin rindu</div>
        </div>
      </div>
      <div class="value-item">
        <div class="value-icon">❤️</div>
        <div>
          <div class="value-title">Dibuat dengan Penuh Kehangatan</div>
          <div class="value-desc">Setiap menu dibuat dengan perhatian agar pelanggan merasa nyaman seperti di rumah</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ======================================================
     TESTIMONIALS
====================================================== -->
<section id="testimonials">
  <div style="text-align:center;position:relative;z-index:1">
    <div class="section-label" data-reveal style="justify-content:center">Ulasan Pelanggan</div>
    <h2 class="section-title" data-reveal style="color:var(--cream)">Kata mereka tentang<br><em style="color:var(--gold)">Seoullicious</em></h2>
    <p data-reveal style="color:rgba(253,246,236,0.45);margin-top:12px;font-size:0.9rem">Sudah pernah makan di sini? Bagikan pengalamanmu!</p>
    <button data-reveal onclick="openReviewModal()" class="testi-write-btn" style="margin-top:20px">✍️ Tulis Ulasan</button>
  </div>
  <div class="testi-slider-wrap" style="position:relative;z-index:1">
    <div class="testi-track-outer" id="testiOuter">
     <div class="testi-track" id="testiTrack">

    {{-- Ulasan dari DB --}}
    @if(isset($reviews) && $reviews->count() > 0)
        @foreach($reviews as $r)
        <div class="testi-card">
            <div class="testi-stars">{{ str_repeat('★', $r->rating) }}{{ str_repeat('☆', 5 - $r->rating) }}</div>
            <div class="testi-text">"{{ $r->ulasan }}"</div>
            <div class="testi-author">
                <div class="testi-avatar">👤</div>
                <div>
                    <div class="testi-name">{{ $r->nama }}</div>
                    <div class="testi-loc">{{ $r->kota ?? 'Indonesia' }}</div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        {{-- Fallback statis kalau belum ada ulasan --}}
        <div class="testi-card"><div class="testi-stars">★★★★★</div><div class="testi-text">"Tteokbokkinya juara banget! Pedasnya pas, kenyal, dan sausnya kaya rasa."</div><div class="testi-author"><div class="testi-avatar">👩</div><div><div class="testi-name">Rizki Amelia</div><div class="testi-loc">Jakarta Selatan</div></div></div></div>
        <div class="testi-card"><div class="testi-stars">★★★★★</div><div class="testi-text">"Bulgogi BBQ-nya autentik banget. Bumbunya meresap sempurna, dagingnya empuk."</div><div class="testi-author"><div class="testi-avatar">👨</div><div><div class="testi-name">Bagas Pratama</div><div class="testi-loc">Bandung</div></div></div></div>
        <div class="testi-card"><div class="testi-stars">★★★★★</div><div class="testi-text">"Suasananya bikin betah, staffnya ramah, dan makanannya konsisten enak setiap kunjungan."</div><div class="testi-author"><div class="testi-avatar">👩</div><div><div class="testi-name">Nadia Kusuma</div><div class="testi-loc">Surabaya</div></div></div></div>
        <div class="testi-card"><div class="testi-stars">★★★★★</div><div class="testi-text">"Sundubu Jjigae-nya maknyus! Kuahnya gurih, tahu sutranya lembut banget."</div><div class="testi-author"><div class="testi-avatar">👨</div><div><div class="testi-name">Dimas Aryo</div><div class="testi-loc">Yogyakarta</div></div></div></div>
        <div class="testi-card"><div class="testi-stars">★★★★★</div><div class="testi-text">"Udah 5x balik ke sini dan gak pernah kecewa. Worth every rupiah!"</div><div class="testi-author"><div class="testi-avatar">👩</div><div><div class="testi-name">Siska Wulandari</div><div class="testi-loc">Depok</div></div></div></div>
        <div class="testi-card"><div class="testi-stars">★★★★☆</div><div class="testi-text">"Kimbapnya enak dan fresh. Suka banget sama dekorasi restonya!"</div><div class="testi-author"><div class="testi-avatar">👨</div><div><div class="testi-name">Farhan Maulana</div><div class="testi-loc">Bekasi</div></div></div></div>
        <div class="testi-card"><div class="testi-stars">★★★★★</div><div class="testi-text">"Dakgalbinya wow banget, ayamnya tender dan saus gochujangnya nendang!"</div><div class="testi-author"><div class="testi-avatar">👩</div><div><div class="testi-name">Ayu Ramadhani</div><div class="testi-loc">Tangerang</div></div></div></div>
        <div class="testi-card"><div class="testi-stars">★★★★★</div><div class="testi-text">"Japchae-nya luar biasa! Mie kacanya kenyal dengan sayuran yang masih segar."</div><div class="testi-author"><div class="testi-avatar">👨</div><div><div class="testi-name">Kevin Santoso</div><div class="testi-loc">Jakarta Utara</div></div></div></div>
    @endif

</div>
    </div>
    <div class="testi-nav">
      <div class="testi-dots" id="testiDots"></div>
      <div class="testi-arrows">
        <button class="testi-arrow" id="testiPrev" onclick="testiSlide(-1)" aria-label="Sebelumnya">&#8592;</button>
        <button class="testi-arrow" id="testiNext" onclick="testiSlide(1)" aria-label="Berikutnya">&#8594;</button>
      </div>
    </div>
  </div>
</section>

<!-- ======================================================
     WHY US
====================================================== -->
<section id="why-us">
  <div style="text-align:center;margin-bottom:44px">
    <div class="section-label" data-reveal style="justify-content:center">Keunggulan Kami</div>
    <h2 class="section-title" data-reveal>Mengapa memilih <em>Seoullicious?</em></h2>
  </div>
  <div class="why-grid">
    <div class="why-box-main" data-reveal>
      <div class="why-box-tag">HOME COOKED</div>
      <h3>Masakan rumahan yang hangat dan penuh rasa</h3>
      <p>Seoullicious hadir untuk menghadirkan kenyamanan lewat hidangan rumahan yang dibuat setiap hari menggunakan bahan segar pilihan. Kami percaya bahwa makanan terbaik adalah makanan yang dimasak dengan perhatian, kehangatan, dan rasa seperti di rumah sendiri.</p>
      <a href="#menu" class="btn-primary" style="margin-top:22px;display:inline-block;background:var(--gold);color:var(--dark)">Lihat Menu</a>
    </div>
    <div class="why-features">
      <div class="why-feature-card" data-reveal>
        <div class="why-feature-icon">🥬</div>
        <div>
          <h4>Bahan Segar Setiap Hari</h4>
          <p>Kami menggunakan bahan berkualitas dan segar agar setiap masakan terasa lebih nikmat dan sehat untuk dinikmati bersama keluarga.</p>
        </div>
      </div>
      <div class="why-feature-card" data-reveal>
        <div class="why-feature-icon">🍳</div>
        <div>
          <h4>Resep Rumahan Favorit</h4>
          <p>Semua menu dibuat dengan resep khas rumahan yang sederhana, hangat, dan memiliki cita rasa yang selalu bikin rindu.</p>
        </div>
      </div>
      <div class="why-feature-card" data-reveal>
        <div class="why-feature-icon">❤️</div>
        <div>
          <h4>Dimasak dengan Penuh Perhatian</h4>
          <p>Kami memasak setiap hidangan dengan penuh perhatian agar pelanggan dapat merasakan kenyamanan dan kehangatan di setiap suapan.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ======================================================
     CARA PESAN
====================================================== -->
<section id="cara-pesan" style="background: linear-gradient(135deg, var(--dark-red) 0%, #4A0E0E 100%); position: relative; overflow: hidden; width: 100%;">
    <div style="position: absolute; inset: 0; opacity: 0.04; background-image: radial-gradient(circle, #fff 1.5px, transparent 1.5px); background-size: 28px 28px;"></div>
    <div style="position: absolute; font-family: 'Noto Serif KR', serif; font-size: 12rem; color: rgba(255,255,255,0.03); bottom: -30px; right: -30px; line-height: 1; pointer-events: none;">주문</div>
    <div style="position: relative; z-index: 1; max-width: 1200px; margin: 0 auto; padding: 60px 20px;">
        <div style="text-align: center; margin-bottom: 48px;">
            <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(212,168,83,0.15); color: var(--gold); font-size: 0.73rem; letter-spacing: 3px; text-transform: uppercase; font-weight: 600; padding: 6px 16px; border-radius: 50px; margin-bottom: 16px;">
                <span style="width: 20px; height: 2px; background: var(--gold); display: inline-block;"></span>
                Panduan Pemesanan
            </div>
            <h2 style="color: white; font-family: 'Playfair Display', serif; font-size: clamp(1.8rem, 4vw, 2.5rem); font-weight: 700; margin: 0 0 12px 0;">
                Gampang Banget!<br>Pesan dalam <em style="color: var(--gold); font-style: italic;">4 Langkah</em>
            </h2>
            <p style="color: rgba(255,255,255,0.7); margin: 0; font-size: 0.9rem;">Dari dapur ke rumahmu, semudah itu!</p>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 28px; max-width: 1100px; margin: 0 auto;">
            <div style="background: rgba(255,255,255,0.08); backdrop-filter: blur(2px); border-radius: 24px; padding: 28px 20px; text-align: center; transition: all 0.3s ease; border: 1px solid rgba(255,255,255,0.1);">
                <div style="font-size: 48px; margin-bottom: 16px;">🔍</div>
                <div style="display: inline-block; background: var(--gold); color: var(--dark); font-size: 0.7rem; font-weight: 700; padding: 4px 12px; border-radius: 50px; margin-bottom: 16px;">Langkah 01</div>
                <h4 style="color: white; font-family: 'Playfair Display', serif; font-size: 1.2rem; margin: 0 0 10px 0;">Pilih Menu</h4>
                <p style="color: rgba(255,255,255,0.7); font-size: 0.85rem; line-height: 1.6; margin: 0;">Jelajahi menu favoritmu dari berbagai pilihan masakan Korea autentik.</p>
            </div>
            <div style="background: rgba(255,255,255,0.08); backdrop-filter: blur(2px); border-radius: 24px; padding: 28px 20px; text-align: center; transition: all 0.3s ease; border: 1px solid rgba(255,255,255,0.1);">
                <div style="font-size: 48px; margin-bottom: 16px;">🛒</div>
                <div style="display: inline-block; background: var(--gold); color: var(--dark); font-size: 0.7rem; font-weight: 700; padding: 4px 12px; border-radius: 50px; margin-bottom: 16px;">Langkah 02</div>
                <h4 style="color: white; font-family: 'Playfair Display', serif; font-size: 1.2rem; margin: 0 0 10px 0;">Masukkan ke Keranjang</h4>
                <p style="color: rgba(255,255,255,0.7); font-size: 0.85rem; line-height: 1.6; margin: 0;">Tambah menu pilihanmu ke keranjang, atur jumlah pesanan sesuai keinginan.</p>
            </div>
            <div style="background: rgba(255,255,255,0.08); backdrop-filter: blur(2px); border-radius: 24px; padding: 28px 20px; text-align: center; transition: all 0.3s ease; border: 1px solid rgba(255,255,255,0.1);">
                <div style="font-size: 48px; margin-bottom: 16px;">📝</div>
                <div style="display: inline-block; background: var(--gold); color: var(--dark); font-size: 0.7rem; font-weight: 700; padding: 4px 12px; border-radius: 50px; margin-bottom: 16px;">Langkah 03</div>
                <h4 style="color: white; font-family: 'Playfair Display', serif; font-size: 1.2rem; margin: 0 0 10px 0;">Isi Data & Checkout</h4>
                <p style="color: rgba(255,255,255,0.7); font-size: 0.85rem; line-height: 1.6; margin: 0;">Lengkapi data diri, pilih metode pembayaran, dan konfirmasi pesanan.</p>
            </div>
            <div style="background: rgba(255,255,255,0.08); backdrop-filter: blur(2px); border-radius: 24px; padding: 28px 20px; text-align: center; transition: all 0.3s ease; border: 1px solid rgba(255,255,255,0.1);">
                <div style="font-size: 48px; margin-bottom: 16px;">🍜</div>
                <div style="display: inline-block; background: var(--gold); color: var(--dark); font-size: 0.7rem; font-weight: 700; padding: 4px 12px; border-radius: 50px; margin-bottom: 16px;">Langkah 04</div>
                <h4 style="color: white; font-family: 'Playfair Display', serif; font-size: 1.2rem; margin: 0 0 10px 0;">Pesanan Diproses</h4>
                <p style="color: rgba(255,255,255,0.7); font-size: 0.85rem; line-height: 1.6; margin: 0;">Kami masak fresh & kirim ke rumahmu. Tinggal santai dan nikmati!</p>
            </div>
        </div>
        <!-- <div style="text-align: center; margin-top: 48px;">
            <a href="https://wa.me/6281234567890" target="_blank" style="display: inline-flex; align-items: center; gap: 10px; background: #25D366; color: white; padding: 12px 28px; border-radius: 50px; text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                💬 Pesan Langsung via WhatsApp
            </a>
            <p style="color: rgba(255,255,255,0.5); font-size: 0.7rem; margin-top: 12px;">Atau hubungi kami untuk pertanyaan seputar menu & pemesanan</p>
        </div> -->
    </div>
</section>

<!-- ======================================================
     FAQ
====================================================== -->
<section id="faq" style="padding-top:90px;">
  <div style="text-align:center;margin-bottom:45px">
    <div class="section-label" data-reveal style="justify-content:center">FAQ</div>
    <h2 class="section-title" data-reveal style="max-width:700px;margin:0 auto;">Pertanyaan yang <em>Sering</em> Ditanyakan</h2>
  </div>
  <div class="faq-container">
    <div class="faq-item active">
      <button class="faq-question">
        <span>🍜 Apakah makanan dibuat fresh setiap hari?</span>
        <span class="faq-icon">+</span>
      </button>
      <div class="faq-answer">Ya, semua menu dimasak fresh setiap hari menggunakan bahan berkualitas agar rasa tetap hangat dan lezat.</div>
    </div>
    <div class="faq-item">
      <button class="faq-question">
        <span>🌶️ Bisa request level pedas?</span>
        <span class="faq-icon">+</span>
      </button>
      <div class="faq-answer">Tentu! Kamu bisa memilih tingkat kepedasan sesuai selera.</div>
    </div>
    <div class="faq-item">
      <button class="faq-question">
        <span>🛵 Apakah tersedia delivery?</span>
        <span class="faq-icon">+</span>
      </button>
      <div class="faq-answer">Bisa, kami melayani delivery via WhatsApp dan aplikasi online.</div>
    </div>
  </div>
</section>

<!-- ======================================================
     LOCATIONS
====================================================== -->
<section id="locations">
  <div style="text-align:center">
    <div class="section-label" data-reveal style="justify-content:center">Temukan Kami</div>
    <h2 class="section-title" data-reveal>Lokasi <em>Seoullicious</em></h2>
  </div>
  <div class="locations-grid single-location">
    <div class="location-map-card" data-reveal>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15850.251805296242!2d108.5399188698182!3d-6.700923255587328!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6ee25b4ccabfdd%3A0x6fde6a4285ea1cdf!2sGg.%20Mulya%20I%20No.16%2C%20Kesenden%2C%20Kec.%20Kejaksan%2C%20Kota%20Cirebon%2C%20Jawa%20Barat%2045121!5e0!3m2!1sen!2sid!4v1778994746559!5m2!1sen!2sid"
          width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>
    <div class="location-info-card" data-reveal>
      <h3>Seoullicious Cirebon</h3>
      <div class="location-info-item">
        <div class="info-icon">📍</div>
        <div>
          <div class="info-title">Alamat</div>
          <div class="info-desc">Jl. Saleh Gg Mulya 1 No.16,<br>Kota Cirebon, Jawa Barat</div>
        </div>
      </div>
      <div class="location-info-item">
        <div class="info-icon">📞</div>
        <div>
          <div class="info-title">Telepon</div>
          <div class="info-desc">+62 813-8900-7152</div>
        </div>
      </div>
      <div class="location-info-item">
        <div class="info-icon">⏰</div>
        <div>
          <div class="info-title">Jam Operasional</div>
          <div class="info-desc">Senin – Minggu<br>09.00 – 21.00</div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ─── DATA MENU DARI DATABASE ─────────────────────────────────────────────── --}}
@php
$menuData = [];
if (isset($produks)) {
    foreach ($produks as $p) {
        $menuData[] = [
            'id'       => $p->id,
            'name'     => $p->nama,
            'cat'      => strtolower($p->kategori ?? 'main'),
            'emoji'    => '🍱',
            'img'      => $p->poto ? asset('storage/' . $p->poto) : '',
            'price'    => (int) $p->harga,
            'stock'    => (int) $p->stok,
            'cookTime' => $p->waktu_masak  ?? '15 menit',
            'spicy'    => $p->level_pedas  ?? 'Tidak pedas',
            'calori'   => $p->bahan_utama  ?? 'Daging sapi',
            'portion'  => $p->porsi        ?? '1 porsi',
            'desc'     => $p->deskripsi    ?? '',
        ];
    }
}
@endphp

<script>
// Cukup assign saja, JANGAN panggil renderMenu() di sini
// renderMenu('all') sudah ada di DOMContentLoaded di layouts/app.blade.php
window.menuData = {!! json_encode($menuData) !!};

// FAQ accordion
document.querySelectorAll('.faq-question').forEach(function(button) {
    button.addEventListener('click', function() {
        var faqItem = button.parentElement;
        document.querySelectorAll('.faq-item').forEach(function(item) {
            if (item !== faqItem) item.classList.remove('active');
        });
        faqItem.classList.toggle('active');
    });
});
</script>
@endsection