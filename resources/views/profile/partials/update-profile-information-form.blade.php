<div class="ep-card">

    <div class="ep-card-header">
        <div>
            <div class="ep-card-title">Edit Profil</div>
            <div class="ep-card-desc">
                Perbarui informasi pribadi dan alamatmu
            </div>
        </div>
    </div>

    <div class="ep-card-body">

        @if(session('success'))
            <div class="ep-alert ep-alert-success">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if($errors->any()
            && !$errors->has('current_password')
            && !$errors->has('password')
            && !$errors->userDeletion->any())

            <div class="ep-alert ep-alert-error">
                ❌ {{ $errors->first() }}
            </div>

        @endif

        <form method="POST"
              action="{{ route('profile.update') }}">

            @csrf
            @method('PATCH')

            <div class="ep-form-row">

                <div class="ep-form-field">
                    <label class="ep-form-label">
                        Nama Lengkap
                    </label>

                    <input type="text"
                        name="name"
                        class="ep-form-input {{ $errors->has('name') ? 'is-error' : '' }}"
                        value="{{ old('name', Auth::user()->name) }}"
                        placeholder="Masukkan nama lengkap">

                    @error('name')
                        <span class="ep-input-error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="ep-form-field">
                    <label class="ep-form-label">
                        Username
                    </label>

                    <input type="text"
                        name="username"
                        class="ep-form-input {{ $errors->has('username') ? 'is-error' : '' }}"
                        value="{{ old('username', Auth::user()->username ?? '') }}"
                        placeholder="Masukkan username">

                    @error('username')
                        <span class="ep-input-error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

            </div>


            <div class="ep-form-row">

                <div class="ep-form-field">

                    <label class="ep-form-label">
                        Email
                        <span class="req">*</span>
                    </label>

                    <input type="email"
                           name="email"
                           class="ep-form-input {{ $errors->has('email') ? 'is-error' : '' }}"
                           value="{{ old('email', Auth::user()->email) }}"
                           placeholder="email@example.com"
                           required>

                    @error('email')
                        <span class="ep-input-error">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

                <div class="ep-form-field">

                    <label class="ep-form-label">
                        No. Telepon
                    </label>

                    <input type="text"
                           name="hp"
                           class="ep-form-input"
                           value="{{ old('hp', Auth::user()->hp ?? '') }}"
                           placeholder="08xxxxxxxxxx">

                </div>

            </div>


            <div class="ep-form-row single">

                <div class="ep-form-field">

                    <label class="ep-form-label">
                        Alamat
                    </label>

                    <textarea name="alamat"
                              class="ep-form-input"
                              placeholder="Masukkan alamat lengkap">{{ old('alamat', Auth::user()->alamat ?? '') }}</textarea>

                </div>

            </div>


            <button type="submit" class="ep-btn-save">

                <svg viewBox="0 0 24 24">
                    <path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.11 0 2-.89 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/>
                </svg>

                Simpan Perubahan

            </button>

        </form>

    </div>

</div>