<nav id="navbar">
          <circle cx="11" cy="11" r="8"/>
          <path d="m21 21-4.35-4.35"/>
        </svg>

        <input type="text" id="searchInput" placeholder="Cari menu..." autocomplete="off">
      </div>

      <div class="search-results" id="searchResults"></div>
    </div>
  </div>

  <div class="nav-icons">

    <button class="nav-icon-btn" id="favBtn" title="Favorit" onclick="openFav()">
      <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
      </svg>

      <span class="badge" id="favBadge" style="display:none">0</span>
    </button>

    @auth

    <button class="nav-icon-btn" id="profileBtn" title="Akun" onclick="openProfile()">
      <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
        <circle cx="12" cy="7" r="4"/>
      </svg>
    </button>

    @endauth

    <button class="nav-icon-btn" id="cartBtn" title="Keranjang" onclick="openCart()">
      <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
        <line x1="3" y1="6" x2="21" y2="6"/>
        <path d="M16 10a4 4 0 0 1-8 0"/>
      </svg>

      <span class="badge" id="cartBadge" style="display:none">0</span>
    </button>

    @guest
    <a href="{{ route('login') }}" class="nav-cta">
      Masuk
    </a>
    @endguest

  </div>

  <button class="nav-hamburger" id="hamburger" onclick="toggleMobileMenu()" aria-label="Menu">
    <span></span><span></span><span></span>
  </button>
</nav>