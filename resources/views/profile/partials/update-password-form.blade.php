<div class="ep-card">

    <div class="ep-card-header">
        <div>
            <div class="ep-card-title">
                Ganti Password
            </div>

            <div class="ep-card-desc">
                Gunakan password yang kuat dan unik untuk keamanan akunmu
            </div>
        </div>
    </div>

    <div class="ep-card-body">

        @if(session('status') === 'password-updated')

            <div class="ep-alert ep-alert-success">
                ✅ Password berhasil diperbarui!
            </div>

        @endif

        <form method="POST"
              action="{{ route('profile.updatePassword') }}">

            @csrf
            @method('PUT')

            <div class="ep-form-row single">

                <div class="ep-form-field">

                    <label class="ep-form-label">
                        Password Saat Ini
                        <span class="req">*</span>
                    </label>

                    <div class="ep-input-pw">

                        <input type="password"
                               name="current_password"
                               id="epPwCur"
                               class="ep-form-input {{ $errors->has('current_password') ? 'is-error' : '' }}"
                               placeholder="Masukkan password saat ini">

                        <button type="button"
                                class="ep-toggle-pw"
                                onclick="epTogglePw('epPwCur')">

                            <svg viewBox="0 0 24 24">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                            </svg>

                        </button>

                    </div>

                    @error('current_password')
                        <span class="ep-input-error">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

            </div>


            <div class="ep-form-row">

                <div class="ep-form-field">

                    <label class="ep-form-label">
                        Password Baru
                        <span class="req">*</span>
                    </label>

                    <div class="ep-input-pw">

                        <input type="password"
                               name="password"
                               id="epPwNew"
                               class="ep-form-input {{ $errors->has('password') ? 'is-error' : '' }}"
                               placeholder="Min. 8 karakter"
                               oninput="epCheckStrength(this.value); epCheckMatch()">

                        <button type="button"
                                class="ep-toggle-pw"
                                onclick="epTogglePw('epPwNew')">

                            <svg viewBox="0 0 24 24">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                            </svg>

                        </button>

                    </div>

                    <div class="ep-strength-wrap">
                        <div class="ep-strength-bar" id="epStrBar"></div>
                    </div>

                    <span class="ep-strength-label"
                          id="epStrLabel"></span>

                </div>


                <div class="ep-form-field">

                    <label class="ep-form-label">
                        Konfirmasi Password
                        <span class="req">*</span>
                    </label>

                    <div class="ep-input-pw">

                        <input type="password"
                               name="password_confirmation"
                               id="epPwConf"
                               class="ep-form-input"
                               placeholder="Ulangi password baru"
                               oninput="epCheckMatch()">

                        <button type="button"
                                class="ep-toggle-pw"
                                onclick="epTogglePw('epPwConf')">

                            <svg viewBox="0 0 24 24">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                            </svg>

                        </button>

                    </div>

                    <span class="ep-match-label"
                          id="epMatchLabel"></span>

                </div>

            </div>


            <button type="submit" class="ep-btn-save">

                <svg viewBox="0 0 24 24">
                    <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                </svg>

                Simpan Password

            </button>

        </form>

    </div>

</div>