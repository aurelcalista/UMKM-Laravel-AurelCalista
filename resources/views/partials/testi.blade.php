<!-- ======================================================
     REVIEW MODAL
====================================================== -->

<div class="auth-modal-backdrop"
    id="reviewModalBackdrop"
    onclick="closeReviewModal(event)">

    <div class="auth-modal"
        id="reviewModal"
        style="max-width:500px">

        <div class="auth-header"
            style="background:linear-gradient(135deg,#1A0A0A 0%,#3D1010 100%)">

            <button class="auth-modal-close"
                onclick="closeReviewModal(null,true)">
                ✕
            </button>

            <div class="auth-header-logo">
                Seoul<span>licious</span>
            </div>

            <h3>Bagikan Ulasanmu</h3>

            <p>Ceritakan pengalamanmu makan di Seoullicious</p>
        </div>

        <div class="auth-body">

            <form action="{{ route('review.store') }}"
                method="POST">

                @csrf

                <!-- Rating -->
                <div style="margin-bottom:20px">

                    <label style="display:block;font-size:0.72rem;letter-spacing:1px;text-transform:uppercase;color:#9A7070;margin-bottom:10px;font-weight:600">
                        Rating
                    </label>

                    <div class="review-stars-input"
                        id="reviewStarsInput">

                        <button type="button"
                            class="star-btn"
                            data-val="1"
                            onclick="setRating(1)">
                            ★
                        </button>

                        <button type="button"
                            class="star-btn"
                            data-val="2"
                            onclick="setRating(2)">
                            ★
                        </button>

                        <button type="button"
                            class="star-btn"
                            data-val="3"
                            onclick="setRating(3)">
                            ★
                        </button>

                        <button type="button"
                            class="star-btn"
                            data-val="4"
                            onclick="setRating(4)">
                            ★
                        </button>

                        <button type="button"
                            class="star-btn"
                            data-val="5"
                            onclick="setRating(5)">
                            ★
                        </button>
                    </div>

                    <div class="review-rating-label"
                        id="reviewRatingLabel">

                        Tap bintang untuk memberi rating
                    </div>

                    <input type="hidden"
                        name="rating"
                        id="ratingInput">
                </div>

                <!-- Nama -->
                <div class="auth-form-group">
                    <label>Nama Kamu</label>

                    <input type="text"
                        name="nama"
                        id="reviewName"
                        value="{{ Auth::user()->name ?? '' }}"
                        placeholder="Nama lengkap atau nama panggilan">
                </div>

                <!-- Kota -->
                <div class="auth-form-group">
                    <label>Kota</label>

                    <input type="text"
                        name="kota"
                        id="reviewCity"
                        placeholder="Contoh: Jakarta, Bandung, Surabaya">
                </div>

                <!-- Menu -->
                <div class="auth-form-group">

                    <label>Menu yang Dipesan</label>

                    <select name="menu"
                        id="reviewMenu">

                        <option value="">
                            -- Pilih menu favorit --
                        </option>

                        <option>Bibimbap</option>
                        <option>Bulgogi BBQ</option>
                        <option>Tteokbokki</option>
                        <option>Kimbap</option>
                        <option>Kimchi Jjigae</option>
                        <option>Sundubu Jjigae</option>
                        <option>Japchae</option>
                        <option>Dakgalbi</option>
                    </select>
                </div>

                <!-- Review -->
                <div class="auth-form-group">

                    <label>
                        Ulasan

                        <span id="reviewCharCount"
                            style="color:#B09090;font-size:0.7rem;font-weight:400;letter-spacing:0">

                            (0/300)
                        </span>
                    </label>

                    <textarea
                        name="ulasan"
                        id="reviewText"
                        maxlength="300"
                        placeholder="Ceritakan pengalamanmu — rasa makanan, pelayanan, suasana, dll."

                        style="width:100%;padding:13px 15px;border:1.5px solid rgba(139,26,26,0.12);border-radius:12px;font-size:1rem;font-family:'DM Sans',sans-serif;color:var(--text);background:var(--cream);outline:none;resize:none;height:110px;transition:border-color 0.25s;-webkit-appearance:none"

                        oninput="updateCharCount(this)"

                        onfocus="this.style.borderColor='var(--red)';this.style.background='white'"

                        onblur="this.style.borderColor='rgba(139,26,26,0.12)';this.style.background='var(--cream)'"></textarea>
                </div>

                <button type="submit"
                    class="auth-submit">

                    Kirim Ulasan ✨
                </button>

            </form>

        </div>
    </div>
</div>