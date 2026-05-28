<!-- ======================================================
     NAVBAR
====================================================== -->
<nav id="navbar">
  <a href="{{ route('home') }}" class="logo">Seoul<span>licious</span></a>

  <!-- Desktop nav links + search -->
  <div class="nav-links-wrap">
    <ul class="nav-links">
      <li><a href="{{ url('/#menu') }}">Menu</a></li>
      <li><a href="{{ url('/#about') }}">Tentang</a></li>
      <li><a href="{{ url('/#testimonials') }}">Ulasan</a></li>
      <li><a href="{{ url('/#faq') }}">FAQ</a></li>
      <li><a href="{{ url('/#locations') }}">Lokasi</a></li>
    </ul>
    <div class="nav-search">
      <div class="search-wrap">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input type="text" id="searchInput" placeholder="Cari menu..." autocomplete="off">
      </div>
      <div class="search-results" id="searchResults"></div>
    </div>
  </div>

  <!-- Right icons -->
  <div class="nav-icons">
    @auth

      {{-- Tombol Dashboard — hanya untuk admin --}}
      @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.dashboard') }}"
           class="nav-cta"
           style="background:var(--gold);color:var(--dark);margin-right:4px;
                  display:inline-flex;align-items:center;gap:5px;font-size:0.78rem;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
               stroke-linecap="round" stroke-linejoin="round"
               style="width:14px;height:14px;flex-shrink:0">
            <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
            <rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>
          </svg>
          Dashboard
        </a>
      @endif

      <button class="nav-icon-btn" id="favBtn" title="Favorit" onclick="openFav()">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        <span class="badge" id="favBadge" style="display:none">0</span>
      </button>

      <button class="nav-icon-btn" id="cartBtn" title="Keranjang" onclick="openCart()">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        <span class="badge" id="cartBadge" style="display:none">{{ collect(session('cart', []))->sum('jumlah') }}</span>
      </button>

      <a href="{{ route('profile.show') }}" class="nav-icon-btn" id="profileBtn" title="Profil Saya">
        @if(auth()->user()->foto)
          <img src="{{ asset('storage/' . auth()->user()->foto) }}"
               alt="{{ auth()->user()->name }}"
               style="width:100%;height:100%;border-radius:50%;object-fit:cover;"
               onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
          <svg style="display:none" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        @else
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        @endif
      </a>

      <form method="POST" action="{{ route('logout') }}" style="display:inline;margin:0">
        @csrf
        <button type="submit" class="nav-cta"
                style="background:transparent;border:1.5px solid rgba(139,26,26,0.3);color:var(--dark-red)">
          Keluar
        </button>
      </form>

    @else
      <a href="{{ route('login') }}" class="nav-cta">Masuk</a>
      <a href="{{ route('register') }}" class="nav-cta"
         style="background:transparent;border:1.5px solid var(--dark-red);color:var(--dark-red);margin-left:4px">
        Daftar
      </a>
    @endauth
  </div>

  <!-- Hamburger -->
  <button class="nav-hamburger" id="hamburger" onclick="toggleMobileMenu()" aria-label="Menu">
    <span></span><span></span><span></span>
  </button>
</nav>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobileMenu">
  <ul class="mobile-menu-links">
    <li><a href="{{ url('/#menu') }}" onclick="closeMobileMenu()">Menu</a></li>
    <li><a href="{{ url('/#about') }}" onclick="closeMobileMenu()">Tentang</a></li>
    <li><a href="{{ url('/#testimonials') }}" onclick="closeMobileMenu()">Ulasan</a></li>
    <li><a href="{{ url('/#faq') }}" onclick="closeMobileMenu()">FAQ</a></li>
    <li><a href="{{ url('/#locations') }}" onclick="closeMobileMenu()">Lokasi</a></li>
  </ul>

  <div class="mobile-search-wrap">
    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
    <input type="text" id="mobileSearchInput" placeholder="Cari menu...">
  </div>

  @auth

    {{-- Tombol Dashboard mobile — hanya untuk admin --}}
    @if(auth()->user()->role === 'admin')
      <a href="{{ route('admin.dashboard') }}" onclick="closeMobileMenu()"
         style="display:flex;align-items:center;justify-content:center;gap:7px;
                margin-top:14px;padding:10px;border-radius:50px;
                background:var(--gold);text-align:center;text-decoration:none;
                font-family:'DM Sans',sans-serif;font-size:0.85rem;font-weight:600;
                color:var(--dark);">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round"
             style="width:15px;height:15px;flex-shrink:0">
          <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
          <rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>
        </svg>
        Dashboard Admin
      </a>
    @endif

    <div style="display:flex;gap:8px;margin-top:10px;flex-wrap:wrap;">
      <button onclick="closeMobileMenu();openCart()"
              style="flex:1;padding:10px;border-radius:50px;background:var(--soft);border:none;
                     cursor:pointer;font-family:'DM Sans',sans-serif;font-size:0.85rem;
                     font-weight:600;color:var(--dark-red)">
        🛒 Keranjang
      </button>
      <a href="{{ route('profile.show') }}" onclick="closeMobileMenu()"
         style="flex:1;padding:10px;border-radius:50px;background:var(--soft);text-align:center;
                text-decoration:none;font-family:'DM Sans',sans-serif;font-size:0.85rem;
                font-weight:600;color:var(--dark-red)">
        👤 Profil
      </a>
    </div>

    <form method="POST" action="{{ route('logout') }}" style="margin-top:8px">
      @csrf
      <button type="submit"
              style="width:100%;padding:10px;border-radius:50px;background:var(--dark-red);
                     color:white;border:none;cursor:pointer;font-family:'DM Sans',sans-serif;
                     font-size:0.85rem;font-weight:600">
        Keluar
      </button>
    </form>

  @else
    <div style="display:flex;gap:8px;margin-top:14px;">
      <a href="{{ route('login') }}"
         style="flex:1;padding:10px;border-radius:50px;background:var(--dark-red);color:white;
                text-align:center;text-decoration:none;font-family:'DM Sans',sans-serif;
                font-size:0.85rem;font-weight:600">
        Masuk
      </a>
      <a href="{{ route('register') }}"
         style="flex:1;padding:10px;border-radius:50px;background:transparent;
                border:1.5px solid var(--dark-red);color:var(--dark-red);text-align:center;
                text-decoration:none;font-family:'DM Sans',sans-serif;font-size:0.85rem;font-weight:600">
        Daftar
      </a>
    </div>
  @endauth
</div>