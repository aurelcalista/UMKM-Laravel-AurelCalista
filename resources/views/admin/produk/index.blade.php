@extends('layouts.admin')

@section('title', 'Data Produk')
@section('page-title', 'Kelola Produk')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .bulk-bar {
        display: none;
        align-items: center;
        gap: .75rem;
        padding: .65rem 1rem;
        background: var(--primary-light);
        border: 1px solid rgba(230,98,57,.3);
        border-radius: 10px;
        margin-bottom: .75rem;
        animation: slideDown .2s ease;
    }
    .bulk-bar.show { display: flex; }
    @keyframes slideDown { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:translateY(0); } }

    .form-check-input:checked { background-color: var(--primary); border-color: var(--primary); }
    .form-check-input:focus { box-shadow: 0 0 0 .2rem rgba(230,98,57,.2); border-color: var(--primary); }

    tr.selected-row { background: rgba(230,98,57,.04) !important; }

    .form-label { font-weight: 500; font-size: .8rem; color: #555; margin-bottom: .35rem; }
    .form-control, .form-select {
        font-size: .85rem;
        border-color: var(--gray-200);
        border-radius: 8px;
        transition: border-color .15s, box-shadow .15s;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 .2rem rgba(230,98,57,.15);
    }
    .input-group-text {
        background: var(--gray-50);
        border-color: var(--gray-200);
        font-size: .82rem;
        color: #888;
    }
    .preview-box {
        width: 100%; height: 120px;
        border: 2px dashed var(--gray-200);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        overflow: hidden; background: var(--gray-50);
        transition: border-color .15s;
        cursor: pointer;
    }
    .preview-box:hover { border-color: var(--primary); }
    .preview-box img { width: 100%; height: 100%; object-fit: cover; }
    .preview-box .placeholder { font-size: .78rem; color: #bbb; text-align: center; }

    .prod-thumb { width: 40px; height: 40px; object-fit: cover; border-radius: 8px; }
    .prod-placeholder {
        width: 40px; height: 40px; border-radius: 8px;
        background: var(--primary-light);
        display: flex; align-items: center; justify-content: center;
        color: var(--primary); font-size: .9rem; flex-shrink: 0;
    }

    .btn-act {
        width: 32px; height: 32px;
        border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
        border: 1px solid; font-size: .85rem;
        transition: background .15s, color .15s;
        cursor: pointer;
        background: transparent;
        padding: 0;
    }
    .btn-act.edit { border-color: #ffc107; color: #e0a800; }
    .btn-act.edit:hover { background: #ffc107; color: #fff; }
    .btn-act.del { border-color: #dc3545; color: #dc3545; }
    .btn-act.del:hover { background: #dc3545; color: #fff; }

    .form-sticky { position: sticky; top: 72px; }

    .filter-pill {
        padding: .3rem .8rem;
        border-radius: 20px;
        font-size: .75rem;
        font-weight: 500;
        border: 1px solid var(--gray-200);
        background: #fff;
        color: #666;
        cursor: pointer;
        transition: all .15s;
        text-decoration: none;
    }
    .filter-pill.active, .filter-pill:hover {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
    }

    .custom-scroll{
    overflow-x:auto;
    overflow-y:hidden;
    }

    .custom-scroll table{
        min-width:1200px;
    }
</style>
@endpush

@section('content')

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', timer: 2500, showConfirmButton: false, toast: true, position: 'top-end' });
});
</script>
@endif
@if(session('error'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({ icon: 'error', title: 'Oops!', text: '{{ session("error") }}', timer: 3000, showConfirmButton: false, toast: true, position: 'top-end' });
});
</script>
@endif

<div class="page-header d-flex justify-content-between align-items-start">
    <div>
        <h1>Kelola Produk</h1>
        <p>Tambah dan kelola semua produk Seoullicious</p>
    </div>
</div>

<div class="row g-4">

    {{-- ===== FORM TAMBAH ===== --}}
<div class="col-lg-4">
    <div class="card form-sticky">
        <div class="card-header bg-white border-0 py-3 px-4">
            <h6 class="fw-semibold mb-0 d-flex align-items-center gap-2">
                <i class="ti ti-plus text-primary"></i> Tambah Produk Baru
            </h6>
        </div>

        <div class="card-body px-4 pb-4">

            <form method="POST"
                  action="{{ route('admin.produk.store') }}"
                  enctype="multipart/form-data"
                  id="formTambah">

                @csrf

                {{-- Preview foto --}}
                <div class="mb-3">
                    <label class="form-label">Foto Produk</label>

                    <div class="preview-box"
                         onclick="document.getElementById('fotoInput').click()">

                        <img id="previewImg"
                             src=""
                             class="d-none"
                             alt="">

                        <div class="placeholder" id="previewPlaceholder">
                            <i class="ti ti-photo-up fs-3 d-block mb-1 text-muted"></i>
                            Klik untuk pilih foto
                        </div>
                    </div>

                    <input type="file"
                           name="poto"
                           id="fotoInput"
                           accept="image/*"
                           class="d-none @error('poto') is-invalid @enderror"
                           onchange="handlePreview(this)">

                    @error('poto')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                {{-- Nama --}}
                <div class="mb-3">
                    <label class="form-label">
                        Nama Produk <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           name="nama"
                           class="form-control @error('nama') is-invalid @enderror"
                           placeholder="Mis. Tteokbokki Original"
                           value="{{ old('nama') }}"
                           required>

                    @error('nama')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                {{-- Harga & Stok --}}
                <div class="row g-2 mb-3">

                    <div class="col-6">
                        <label class="form-label">
                            Harga <span class="text-danger">*</span>
                        </label>

                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Rp</span>

                            <input type="number"
                                   name="harga"
                                   class="form-control @error('harga') is-invalid @enderror"
                                   placeholder="0"
                                   min="0"
                                   value="{{ old('harga') }}"
                                   required>
                        </div>

                        @error('harga')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="col-6">
                        <label class="form-label">
                            Stok <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                               name="stok"
                               class="form-control @error('stok') is-invalid @enderror"
                               placeholder="0"
                               min="0"
                               value="{{ old('stok') }}"
                               required>

                        @error('stok')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                {{-- KATEGORI --}}
                <div class="mb-3">

                    <label class="form-label">
                        Kategori <span class="text-danger">*</span>
                    </label>

                    @if($kategorisObj->isEmpty())

                        <div class="alert alert-warning py-2 px-3 mb-2"
                             style="font-size:.78rem;border-radius:8px;">

                            <i class="ti ti-alert-triangle me-1"></i>

                            Belum ada kategori.

                            <a href="{{ route('admin.kategori.index') }}"
                               class="fw-semibold alert-link">
                                Tambah kategori dulu →
                            </a>
                        </div>

                        <select class="form-select" disabled>
                            <option>— Belum ada kategori —</option>
                        </select>

                    @else

                        <select name="kategori"
                                class="form-select @error('kategori') is-invalid @enderror"
                                required>

                            <option value="">— Pilih Kategori —</option>

                            @foreach($kategorisObj as $kat)
                            <option value="{{ $kat->nama_kategori }}"
                                {{ old('kategori') == $kat->nama_kategori ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                            @endforeach

                        </select>

                        @error('kategori')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror

                    @endif
                </div>
                <div class="mb-3">

                    <label class="form-label">
                        Promo
                    </label>

                    <select name="promo_id"
                            class="form-select">

                        <option value="">
                            -- Tanpa Promo --
                        </option>

                        @foreach($promos as $promo)

                        <option value="{{ $promo->id }}">

                            {{ $promo->nama_promo }}
                            ({{ $promo->diskon }}%)

                        </option>

                        @endforeach

                    </select>

                </div>

                {{-- Deskripsi --}}
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>

                    <textarea name="deskripsi"
                              class="form-control"
                              rows="3"
                              placeholder="Deskripsi singkat produk...">{{ old('deskripsi') }}</textarea>
                </div>

                {{-- DETAIL MENU DINAMIS --}}
                <div class="row g-2 mb-3">

                    <div class="col-6">
                        <label class="form-label">Waktu Masak</label>

                        <input type="text"
                               name="waktu_masak"
                               class="form-control"
                               placeholder="15 menit"
                               value="{{ old('waktu_masak') }}">
                    </div>

                    <div class="col-6">
                        <label class="form-label">Level Pedas</label>

                        <input type="text"
                               name="level_pedas"
                               class="form-control"
                               placeholder="Tidak pedas"
                               value="{{ old('level_pedas') }}">
                    </div>

                    <div class="col-6">
                        <label class="form-label">Bahan Utama</label>

                        <input type="text"
                               name="bahan_utama"
                               class="form-control"
                               placeholder="Daging sapi"
                               value="{{ old('bahan_utama') }}">
                    </div>

                    <div class="col-6">
                        <label class="form-label">Porsi</label>

                        <input type="text"
                               name="porsi"
                               class="form-control"
                               placeholder="1 porsi"
                               value="{{ old('porsi') }}">
                    </div>

                </div>

                <button type="submit"
                        class="btn btn-primary w-100 fw-500"
                        {{ $kategorisObj->isEmpty() ? 'disabled' : '' }}>

                    <i class="ti ti-plus me-2"></i>
                    Tambah Produk

                </button>

            </form>
        </div>
    </div>
</div>
{{-- ===== TABEL PRODUK ===== --}}
<div class="col-lg-8">

    <div class="bulk-bar" id="bulkBar">
        <span class="fw-500" style="font-size:.82rem" id="bulkCount">
            0 dipilih
        </span>

        <button class="btn btn-danger btn-sm ms-auto" id="btnBulkDelete">
            <i class="ti ti-trash me-1"></i>
            Hapus Terpilih
        </button>

        <button class="btn btn-outline-secondary btn-sm" id="btnClearSelect">
            <i class="ti ti-x me-1"></i>
            Batal
        </button>
    </div>

    <form id="bulkDeleteForm"
          method="POST"
          action="{{ route('admin.produk.bulkDestroy') }}"
          class="d-none">

        @csrf
        @method('DELETE')

        <div id="bulkIds"></div>
    </form>

    <div class="card">

        <div class="card-header bg-white border-0 py-3 px-4 d-flex flex-wrap gap-2 justify-content-between align-items-center">
            <h6 class="fw-semibold mb-0">
                Daftar Produk ({{ $produks->count() }})
            </h6>
        </div>

        <div class="card-body p-0">

        <div class="table-responsive custom-scroll">

                <table class="table table-hover mb-0">

                    <thead class="table-light">
                        <tr>
                            <th width="40">
                                <input type="checkbox"
                                       class="form-check-input"
                                       id="checkAll"
                                       title="Pilih semua">
                            </th>
                            <th width="70"></th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Promo</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Waktu</th>
                            <th>Pedas</th>
                            <th>Bahan</th>
                            <th>Porsi</th>
                            <th class="text-center" width="90">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produks as $produk)
                        <tr data-id="{{ $produk->id }}">
                            {{-- CHECKBOX --}}
                            <td>
                                <input type="checkbox"
                                       class="form-check-input row-check"
                                       value="{{ $produk->id }}">
                            </td>

                            {{-- FOTO --}}
                            <td>
                                @if($produk->poto)
                                    <img src="{{ asset('storage/' . $produk->poto) }}"
                                         alt="{{ $produk->nama }}"
                                         class="prod-thumb">
                                @else
                                    <div class="prod-placeholder">
                                        <i class="ti ti-bowl-chopsticks"></i>
                                    </div>
                                @endif
                            </td>
                            {{-- NAMA --}}
                            <td>
                                <div class="fw-500" style="font-size:.84rem">
                                    {{ $produk->nama }}
                                </div>

                                @if($produk->deskripsi)

                                <div class="text-muted"
                                     style="font-size:.7rem">

                                    {{ Str::limit($produk->deskripsi, 40) }}

                                </div>

                                @endif

                                {{-- DETAIL DINAMIS --}}
                                <div class="d-flex gap-1 mt-2 flex-nowrap">

                                    @if($produk->waktu_masak)
                                    <span class="badge bg-light text-dark">
                                        ⏱ {{ $produk->waktu_masak }}
                                    </span>
                                    @endif

                                    @if($produk->level_pedas)
                                    <span class="badge bg-light text-dark">
                                        🌶 {{ $produk->level_pedas }}
                                    </span>
                                    @endif

                                    @if($produk->bahan_utama)
                                    <span class="badge bg-light text-dark">
                                        🍖 {{ $produk->bahan_utama }}
                                    </span>
                                    @endif

                                    @if($produk->porsi)
                                    <span class="badge bg-light text-dark">
                                        🍽 {{ $produk->porsi }}
                                    </span>
                                    @endif

                                </div>

                            </td>

                            {{-- KATEGORI --}}
                            <td>

                                <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-2">

                                    {{ optional($produk->kategoriObj)->nama_kategori
                                        ?? $produk->kategori
                                        ?? '-' }}

                                </span>

                            </td>
                            <td>
                            @if($produk->promo)
                            <span class="badge bg-danger">
                                🎁 {{ $produk->promo->nama_promo }}
                            </span>
                            @else
                            <span class="text-muted">
                                -
                            </span>
                            @endif
                            </td>

                            {{-- HARGA --}}
                            <td class="fw-500" style="font-size:.83rem">
                               @if($produk->promo)
                                <div>
                                    <div style="font-size:.7rem;
                                                text-decoration:line-through;
                                                color:#999;">

                                        Rp {{ number_format($produk->harga, 0, ',', '.') }}
                                    </div>
                                    <div class="fw-bold text-danger">

                                        Rp {{ number_format($produk->harga_final, 0, ',', '.') }}

                                    </div>
                                    <span class="badge bg-danger mt-1">
                                        🔥 {{ $produk->promo->diskon }}% OFF
                                    </span>
                                </div>
                                @else
                                <div class="fw-500" style="font-size:.83rem">

                                    Rp {{ number_format($produk->harga, 0, ',', '.') }}

                                </div>
                                @endif

                            </td>

                            {{-- STOK --}}
                            <td>

                                <span class="badge
                                    {{ $produk->stok > 10
                                        ? 'bg-success'
                                        : ($produk->stok > 0
                                            ? 'bg-warning text-dark'
                                            : 'bg-danger') }}">

                                    {{ $produk->stok }}

                                </span>

                            </td>

                            {{-- WAKTU --}}
                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ $produk->waktu_masak ?? '-' }}
                                </span>
                            </td>

                            {{-- PEDAS --}}
                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ $produk->level_pedas ?? '-' }}
                                </span>
                            </td>

                            {{-- BAHAN --}}
                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ $produk->bahan_utama ?? '-' }}
                                </span>
                            </td>

                            {{-- PORSI --}}
                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ $produk->porsi ?? '-' }}
                                </span>
                            </td>

                            {{-- AKSI --}}
                            <td class="text-center">

                                <div class="d-flex gap-1 justify-content-center">

                                    <a href="{{ route('admin.produk.edit', $produk->id) }}"
                                       class="btn-act edit"
                                       title="Edit">

                                        <i class="ti ti-edit"></i>

                                    </a>

                                    <button type="button"
                                            class="btn-act del btn-delete-single"
                                            data-id="{{ $produk->id }}"
                                            data-name="{{ $produk->nama }}"
                                            title="Hapus">

                                        <i class="ti ti-trash"></i>

                                    </button>

                                    <form id="del-{{ $produk->id }}"
                                          method="POST"
                                          action="{{ route('admin.produk.destroy', $produk->id) }}"
                                          class="d-none">

                                        @csrf
                                        @method('DELETE')

                                    </form>

                                </div>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="11"
                                class="text-center text-muted py-5">

                                <i class="ti ti-package-off fs-2 d-block mb-2 opacity-30"></i>

                                Belum ada produk.

                                <a href="{{ route('admin.produk.create') }}"
                                   class="text-primary">

                                    Tambah sekarang

                                </a>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>
        </div>

        @if(isset($produks)
            && method_exists($produks, 'hasPages')
            && $produks->hasPages())

        <div class="card-footer d-flex justify-content-end py-2">

            {{ $produks->links('pagination::bootstrap-5') }}

        </div>

        @endif

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
function handlePreview(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('previewImg');
            img.src = e.target.result;
            img.classList.remove('d-none');
            document.getElementById('previewPlaceholder').classList.add('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

const checkAll = document.getElementById('checkAll');
const rowChecks = document.querySelectorAll('.row-check');
const bulkBar = document.getElementById('bulkBar');
const bulkCount = document.getElementById('bulkCount');

function updateBulkBar() {
    const checked = document.querySelectorAll('.row-check:checked');
    bulkBar.classList.toggle('show', checked.length > 0);
    bulkCount.textContent = checked.length + ' item dipilih';
    rowChecks.forEach(cb => cb.closest('tr').classList.toggle('selected-row', cb.checked));
}

checkAll.addEventListener('change', function() {
    rowChecks.forEach(cb => cb.checked = this.checked);
    updateBulkBar();
});

rowChecks.forEach(cb => cb.addEventListener('change', function() {
    checkAll.checked = [...rowChecks].every(c => c.checked);
    checkAll.indeterminate = [...rowChecks].some(c => c.checked) && !checkAll.checked;
    updateBulkBar();
}));

document.getElementById('btnClearSelect').addEventListener('click', function() {
    checkAll.checked = false;
    checkAll.indeterminate = false;
    rowChecks.forEach(cb => cb.checked = false);
    updateBulkBar();
});

document.getElementById('btnBulkDelete').addEventListener('click', function() {
    const checked = [...document.querySelectorAll('.row-check:checked')];
    if (!checked.length) return;
    const ids = checked.map(cb => cb.value);
    Swal.fire({
        title: 'Hapus ' + ids.length + ' produk?',
        text: 'Data yang dihapus tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#E66239',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="ti ti-trash me-1"></i> Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then(result => {
        if (result.isConfirmed) {
            const container = document.getElementById('bulkIds');
            container.innerHTML = '';
            ids.forEach(id => {
                const inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = 'ids[]';
                inp.value = id;
                container.appendChild(inp);
            });
            document.getElementById('bulkDeleteForm').submit();
        }
    });
});

document.querySelectorAll('.btn-delete-single').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const name = this.dataset.name;
        Swal.fire({
            title: 'Hapus produk?',
            html: 'Produk <strong>' + name + '</strong> akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#E66239',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="ti ti-trash me-1"></i> Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then(result => {
            if (result.isConfirmed) {
                document.getElementById('del-' + id).submit();
            }
        });
    });
});
</script>
@endpush