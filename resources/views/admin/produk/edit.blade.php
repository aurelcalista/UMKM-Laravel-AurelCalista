@extends('layouts.admin')

@section('title', 'Edit Produk')

@push('styles')
<style>
    .edit-layout { max-width: 680px; margin: 0 auto; }

    .edit-card {
        background: #fff;
        border: 1px solid rgba(0,0,0,0.08);
        border-radius: 14px;
        overflow: hidden;
    }

    .edit-card-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid rgba(0,0,0,0.07);
        display: flex; align-items: center; gap: 0.75rem;
    }

    .back-btn {
        width: 34px; height: 34px; border-radius: 9px;
        border: 1px solid rgba(0,0,0,0.1);
        background: #FAFAF9; color: #444;
        display: flex; align-items: center; justify-content: center;
        text-decoration: none; font-size: 0.95rem;
        transition: all 0.15s; flex-shrink: 0;
    }

    .back-btn:hover { background: rgba(229,90,43,0.08); border-color: rgba(229,90,43,0.2); color: #E55A2B; }

    .edit-body { padding: 1.5rem; }

    .field-group { margin-bottom: 1.1rem; }
    .field-label { font-size: 0.75rem; font-weight: 600; color: #555; margin-bottom: 0.35rem; display: block; }
    .field-label .req { color: #dc3545; }

    .field-input {
        width: 100%; padding: 0.55rem 0.8rem;
        border: 1px solid rgba(0,0,0,0.1); border-radius: 9px;
        font-size: 0.83rem; font-family: inherit; color: #1A1A1A; background: #fff;
        transition: border-color 0.15s, box-shadow 0.15s; outline: none;
    }

    .field-input:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 3px rgba(229,90,43,0.12);
    }

    .field-input.is-invalid { border-color: #EF4444; }
    .field-error { font-size: 0.7rem; color: #EF4444; margin-top: 0.25rem; }

    .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media (max-width: 540px) { .two-col { grid-template-columns: 1fr; } }

    .divider { height: 1px; background: rgba(0,0,0,0.06); margin: 1.25rem 0; }

    .section-tag {
        font-size: 0.65rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.6px; color: #999; margin-bottom: 0.85rem;
        display: flex; align-items: center; gap: 0.4rem;
    }
    .section-tag::after { content: ''; flex: 1; height: 1px; background: rgba(0,0,0,0.07); }

    /* Current photo */
    .current-photo {
        display: flex; align-items: center; gap: 0.9rem;
        padding: 0.75rem;
        background: #FAFAF9;
        border: 1px solid rgba(0,0,0,0.08);
        border-radius: 10px;
        margin-bottom: 0.75rem;
    }

    .current-photo img {
        width: 56px; height: 56px; border-radius: 10px; object-fit: cover; flex-shrink: 0;
    }

    .current-photo-info { font-size: 0.75rem; }
    .current-photo-label { font-weight: 600; color: #444; margin-bottom: 2px; }
    .current-photo-hint { color: #aaa; }

    /* Action buttons */
    .action-row { display: flex; gap: 0.75rem; margin-top: 0.25rem; }

    .btn-save {
        flex: 1; padding: 0.6rem;
        background: #E55A2B; color: #fff; border: none;
        border-radius: 10px; font-size: 0.82rem; font-weight: 700;
        font-family: inherit; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 0.4rem;
        transition: background 0.15s, transform 0.1s;
    }

    .btn-save:hover { background: #cc4f23; }
    .btn-save:active { transform: scale(0.99); }

    .btn-cancel {
        padding: 0.6rem 1.25rem;
        background: transparent; color: #666;
        border: 1px solid rgba(0,0,0,0.12);
        border-radius: 10px; font-size: 0.82rem; font-weight: 600;
        font-family: inherit; cursor: pointer; text-decoration: none;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.15s;
    }

    .btn-cancel:hover { background: #F5F4F2; color: #333; }
</style>
@endpush

@section('content')

<div class="page-header">
    <h1>Edit Produk</h1>
    <p>Perbarui informasi produk di bawah ini</p>
</div>

<div class="edit-layout">
    <div class="edit-card">

        {{-- Header --}}
        <div class="edit-card-header">
            <a href="{{ route('admin.produk.index') }}" class="back-btn">
                <i class="ti ti-arrow-left"></i>
            </a>
            <div>
                <div style="font-size:0.85rem;font-weight:700;">{{ $produk->nama }}</div>
                <div style="font-size:0.7rem;color:#aaa;">Edit informasi produk</div>
            </div>
        </div>

        {{-- Form --}}
        <div class="edit-body">
            <form method="POST"
                  action="{{ route('admin.produk.update', $produk->id) }}"
                  enctype="multipart/form-data">
                @csrf @method('PUT')

                {{-- Nama --}}
                <div class="field-group">
                    <label class="field-label">Nama Produk <span class="req">*</span></label>
                    <input type="text" name="nama"
                           class="field-input @error('nama') is-invalid @enderror"
                           value="{{ old('nama', $produk->nama) }}" required>
                    @error('nama')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                {{-- Harga & Stok --}}
                <div class="two-col">
                    <div class="field-group">
                        <label class="field-label">Harga (Rp) <span class="req">*</span></label>
                        <input type="number" name="harga"
                               class="field-input @error('harga') is-invalid @enderror"
                               value="{{ old('harga', $produk->harga) }}" min="0" required>
                        @error('harga')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field-group">
                        <label class="field-label">Stok <span class="req">*</span></label>
                        <input type="number" name="stok"
                               class="field-input @error('stok') is-invalid @enderror"
                               value="{{ old('stok', $produk->stok) }}" min="0" required>
                        @error('stok')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Kategori --}}
                <div class="field-group">
                    <label class="field-label">Kategori <span class="req">*</span></label>
                    <select name="kategori"
                            class="field-input @error('kategori') is-invalid @enderror" required>

                        <option value="">-- Pilih Kategori --</option>

                        @foreach($kategorisObj as $kategori)

                        <option value="{{ $kategori->nama_kategori }}"
                            {{ old('kategori', $produk->kategori) == $kategori->nama_kategori ? 'selected' : '' }}>

                            {{ $kategori->nama_kategori }}

                        </option>

                        @endforeach

                    </select>
                    @error('kategori')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="field-group">

                    <label class="field-label">
                        Promo
                    </label>

                    <select name="promo_id"
                            class="field-input">

                        <option value="">
                            -- Tanpa Promo --
                        </option>

                        @foreach($promos as $promo)

                        <option value="{{ $promo->id }}"
                            {{ old('promo_id', $produk->promo_id) == $promo->id ? 'selected' : '' }}>

                            {{ $promo->nama_promo }}
                            ({{ $promo->diskon }}%)

                        </option>

                        @endforeach

                    </select>

                </div>

                {{-- Deskripsi --}}
                <div class="field-group">
                    <label class="field-label">Deskripsi</label>
                    <textarea name="deskripsi" class="field-input" rows="3">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                </div>

                <div class="divider"></div>
                <div class="section-tag">Detail Menu</div>

                <div class="two-col">
                    <div class="field-group">
                        <label class="field-label">⏱ Waktu Masak</label>
                        <input type="text" name="waktu_masak" class="field-input"
                               value="{{ old('waktu_masak', $produk->waktu_masak) }}" placeholder="15 menit">
                    </div>
                    <div class="field-group">
                        <label class="field-label">🌶 Level Pedas</label>
                        <input type="text" name="level_pedas" class="field-input"
                               value="{{ old('level_pedas', $produk->level_pedas) }}" placeholder="Tidak pedas">
                    </div>
                    <div class="field-group">
                        <label class="field-label">🍖 Bahan Utama</label>
                        <input type="text" name="bahan_utama" class="field-input"
                               value="{{ old('bahan_utama', $produk->bahan_utama) }}" placeholder="Daging sapi">
                    </div>
                    <div class="field-group">
                        <label class="field-label">🍽 Porsi</label>
                        <input type="text" name="porsi" class="field-input"
                               value="{{ old('porsi', $produk->porsi) }}" placeholder="1 porsi">
                    </div>
                </div>

                <div class="divider"></div>
                <div class="section-tag">Foto Produk</div>

                {{-- Current Photo --}}
                @if($produk->poto)
                <div class="current-photo">
                    <img src="{{ asset('storage/' . $produk->poto) }}" alt="{{ $produk->nama }}">
                    <div class="current-photo-info">
                        <div class="current-photo-label">Foto saat ini</div>
                        <div class="current-photo-hint">Upload baru untuk mengganti foto</div>
                    </div>
                </div>
                @endif

                <div class="field-group">
                    <label class="field-label">{{ $produk->poto ? 'Ganti Foto' : 'Upload Foto' }}</label>
                    <input type="file" name="poto"
                           class="field-input @error('poto') is-invalid @enderror"
                           accept="image/*">
                    @error('poto')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                {{-- Actions --}}
                <div class="action-row">
                    <button type="submit" class="btn-save">
                        <i class="ti ti-device-floppy"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.produk.index') }}" class="btn-cancel">Batal</a>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection