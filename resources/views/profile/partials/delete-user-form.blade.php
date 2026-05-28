<div class="ep-card" style="border-color:#FEB2B2;">

    <div class="ep-card-header">

        <div>
            <div class="ep-card-title" style="color:#C53030;">
                Zona Bahaya
            </div>

            <div class="ep-card-desc">
                Tindakan ini tidak dapat dibatalkan
            </div>
        </div>

    </div>

    <div class="ep-card-body">

        <div class="ep-danger-zone">

            <div class="ep-danger-title">

                <svg viewBox="0 0 24 24">
                    <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
                </svg>

                Hapus Akun Permanen

            </div>

            <p class="ep-danger-desc">
                Setelah akun dihapus, semua data termasuk pesanan
                dan riwayat transaksi akan hilang permanen.
            </p>

            <button onclick="document.getElementById('epDeleteModal').classList.add('open')"
                    class="ep-btn-danger">

                🗑 Hapus Akun Saya

            </button>

        </div>

    </div>

</div>


<div id="epDeleteModal" class="ep-modal-overlay">

    <div class="ep-modal">

        <div class="ep-modal-icon">⚠️</div>

        <div class="ep-modal-title">
            Hapus Akun?
        </div>

        <div class="ep-modal-desc">
            Semua data akan hilang permanen.
            Masukkan password untuk konfirmasi.
        </div>

        <form method="POST"
              action="{{ route('profile.destroy') }}"
              style="width:100%;">

            @csrf
            @method('DELETE')

            <div class="ep-form-field"
                 style="margin-bottom:16px;text-align:left;">

                <label class="ep-form-label">
                    Password
                    <span class="req">*</span>
                </label>

                <div class="ep-input-pw">

                    <input type="password"
                           name="password"
                           id="epDelPw"
                           class="ep-form-input {{ $errors->userDeletion->has('password') ? 'is-error' : '' }}"
                           placeholder="Masukkan passwordmu">

                    <button type="button"
                            class="ep-toggle-pw"
                            onclick="epTogglePw('epDelPw')">

                        <svg viewBox="0 0 24 24">
                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                        </svg>

                    </button>

                </div>

            </div>

            <div class="ep-modal-actions">

                <button type="button"
                        class="ep-btn-cancel"
                        onclick="document.getElementById('epDeleteModal').classList.remove('open')">

                    Batal

                </button>

                <button type="submit"
                        class="ep-btn-danger"
                        style="flex:1;justify-content:center;">

                    🗑 Ya, Hapus

                </button>

            </div>

        </form>

    </div>

</div>