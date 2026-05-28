<!-- ======================================================
     MENU POPUP
====================================================== -->
<div class="overlay-backdrop"
     id="menuPopupBackdrop"
     onclick="closeMenuPopup(event)">

  <div class="menu-popup" id="menuPopup">

    <div class="popup-img" id="popupImg">

      <button class="popup-close"
              onclick="closeMenuPopup(null,true)">
        ✕
      </button>

      <button class="popup-fav"
              id="popupFavBtn"
              onclick="togglePopupFav()">
        🤍
      </button>

      <span id="popupEmoji"
            style="position:relative;z-index:1"></span>

    </div>

    <div class="popup-body">

      <div class="popup-cat-tag" id="popupCat"></div>

      <div class="popup-name" id="popupName"></div>

      <div class="popup-price" id="popupPrice"></div>

      <div class="popup-desc" id="popupDesc"></div>

      <div class="popup-info-grid">

        <div class="popup-info-item">
          <div class="popup-info-label">Waktu Masak</div>
          <div class="popup-info-val" id="popupCookTime"></div>
        </div>

        <div class="popup-info-item">
          <div class="popup-info-label">Level Pedas</div>
          <div class="popup-info-val" id="popupSpicy"></div>
        </div>

        <div class="popup-info-item">
          <div class="popup-info-label">Bahan Utama</div>
          <div class="popup-info-val" id="popupCalori"></div>
        </div>

        <div class="popup-info-item">
          <div class="popup-info-label">Porsi</div>
          <div class="popup-info-val" id="popupPortion"></div>
        </div>

      </div>

      <div class="popup-stock-bar">

        <div class="popup-stock-label">
          <span>Stok Tersedia</span>
          <span id="popupStockNum"></span>
        </div>

        <div class="stock-bar-bg">
          <div class="stock-bar-fill"
               id="stockBarFill"></div>
        </div>

      </div>

      <div class="popup-add-section">

        <!-- QTY -->
        <div class="qty-control">

          <button type="button"
                  class="qty-btn"
                  onclick="changeQty(-1)">
            −
          </button>

          <div class="qty-num" id="popupQty">1</div>

          <button type="button"
                  class="qty-btn"
                  onclick="changeQty(1)">
            +
          </button>

        </div>

        <!-- FORM -->
        <form id="addCartForm">

          @csrf

          <input type="hidden"
                 name="produk_id"
                 id="formProdukId">

          <input type="hidden"
                 name="jumlah"
                 id="formJumlah"
                 value="1">

          <button type="button"
                  class="add-cart-btn"
                  id="addCartBtn"
                  onclick="submitCartAjax()">

            🛒 Tambah ke Keranjang

          </button>

        </form>

      </div>

    </div>

  </div>
</div>


<script>

/* ======================================================
   CHANGE QTY
====================================================== */
function changeQty(val){

    popupQty += val;

    if(popupQty < 1){
        popupQty = 1;
    }

    document.getElementById('popupQty').innerText =
        popupQty;

    document.getElementById('formJumlah').value =
        popupQty;
}


/* ======================================================
   AJAX ADD TO CART
====================================================== */
async function submitCartAjax(){

    // CEK LOGIN
    if(!IS_LOGGED_IN){

        Swal.fire({
            icon: 'warning',
            title: 'Login Diperlukan',
            html: `
                Kamu harus login dulu untuk menambahkan
                menu ke keranjang 🛒<br><br>

                Belum punya akun?
                <a href="{{ route('register') }}"
                   style="
                        color:#8B1A1A;
                        font-weight:600;
                        text-decoration:none;
                   ">
                   Daftar di sini
                </a>
            `,
            confirmButtonText: 'Login',
            confirmButtonColor: '#8B1A1A'
        }).then((result) => {

            if(result.isConfirmed){

                window.location.href =
                    "{{ route('login') }}";
            }
        });

        return;
    }

    const btn = document.getElementById('addCartBtn');

    btn.disabled = true;

    btn.innerHTML = '⏳ Menambahkan...';

    try{

        const response = await fetch(
            "{{ route('cart.add') }}",
            {
                method: 'POST',

                credentials: 'same-origin',

                headers: {

                    'X-CSRF-TOKEN':
                        document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,

                    'Accept': 'application/json'
                },

                body: new FormData(
                    document.getElementById('addCartForm')
                )
            }
        );

        const data = await response.json();

        if(data.success){

            // UPDATE BADGE
            const badge =
                document.getElementById('cartBadge');

            if(badge){

                badge.style.display = 'flex';

                badge.textContent =
                    data.cartCount;
            }

            Swal.fire({
                icon:'success',
                title:'Berhasil!',
                text:data.message,
                timer:1200,
                showConfirmButton:false
            });

            closeMenuPopup(null,true);

        }else{

            Swal.fire({
                icon:'error',
                title:'Gagal',
                text:data.message || 'Terjadi kesalahan'
            });
        }

    }catch(err){

        console.log(err);

        Swal.fire({
            icon:'error',
            title:'Error',
            text:'Terjadi kesalahan server'
        });
    }

    btn.disabled = false;

    btn.innerHTML =
        '🛒 Tambah ke Keranjang';
}

</script>