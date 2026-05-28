@extends('layouts.admin')

@section('title', 'Data Kategori')
@section('page-title', 'Kelola Kategori')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .bulk-bar {
        display: none; align-items: center; gap: .75rem;
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
        font-size: .85rem; border-color: var(--gray-200); border-radius: 8px;
        transition: border-color .15s, box-shadow .15s;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--primary); box-shadow: 0 0 0 .2rem rgba(230,98,57,.15);
    }

    .btn-act {
        width: 32px; height: 32px; border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
        border: 1px solid; font-size: .85rem;
        transition: background .15s, color .15s;
        cursor: pointer; background: transparent; padding: 0;
    }
    .btn-act.edit { border-color: #ffc107; color: #e0a800; }
    .btn-act.edit:hover { background: #ffc107; color: #fff; }
    .btn-act.del { border-color: #dc3545; color: #dc3545; }
    .btn-act.del:hover { background: #dc3545; color: #fff; }

    .form-sticky { position: sticky; top: 72px; }

    .kat-icon {
        width: 36px; height: 36px; border-radius: 8px;
        background: var(--primary-light); color: var(--primary);
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
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
        <h1>Kelola Kategori</h1>
        <p>Tambah dan atur kategori produk Seoullicious</p>
    </div>
</div>

<div class="row g-4">

    {{-- ===== FORM TAMBAH ===== --}}
    <div class="col-lg-4">
        <div class="card form-sticky">
            <div class="card-header bg-white border-0 py-3 px-4">
                <h6 class="fw-semibold mb-0 d-flex align-items-center gap-2">
                    <i class="ti ti-tag text-primary"></i> Tambah Kategori
                </h6>
            </div>
            <div class="card-body px-4 pb-4">
                <form method="POST" action="{{ route('admin.kategori.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kategori"
                               class="form-control @error('nama_kategori') is-invalid @enderror"
                               placeholder="Mis. Makanan, Minuman, Snack..."
                               value="{{ old('nama_kategori') }}" required>
                        @error('nama_kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text mt-1" style="font-size:.73rem">
                            Nama kategori akan digunakan saat menambah produk.
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-500">
                        <i class="ti ti-plus me-2"></i>Tambah Kategori
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== TABEL KATEGORI ===== --}}
    <div class="col-lg-8">

        {{-- Bulk bar --}}
        <div class="bulk-bar" id="bulkBar">
            <span class="fw-500" style="font-size:.82rem" id="bulkCount">0 dipilih</span>
            <button class="btn btn-danger btn-sm ms-auto" id="btnBulkDelete">
                <i class="ti ti-trash me-1"></i> Hapus Terpilih
            </button>
            <button class="btn btn-outline-secondary btn-sm" id="btnClearSelect">
                <i class="ti ti-x me-1"></i> Batal
            </button>
        </div>

        {{-- Hidden bulk delete form --}}
        <form id="bulkDeleteForm" method="POST" action="{{ route('admin.kategori.bulkDestroy') }}" class="d-none">
            @csrf @method('DELETE')
            <div id="bulkIds"></div>
        </form>

        <div class="card">
            <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-semibold mb-0">Daftar Kategori
                    <span class="badge bg-primary bg-opacity-10 text-primary ms-2">{{ $kategoris->count() }}</span>
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="40">
                                    <input type="checkbox" class="form-check-input" id="checkAll" title="Pilih semua">
                                </th>
                                <th width="50">No</th>
                                <th>Nama Kategori</th>
                                <th class="text-center" width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategoris as $kategori)
                            <tr data-id="{{ $kategori->id }}">
                                <td>
                                    <input type="checkbox" class="form-check-input row-check" value="{{ $kategori->id }}">
                                </td>
                                <td class="text-muted" style="font-size:.82rem">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="kat-icon">
                                            <i class="ti ti-tag"></i>
                                        </div>
                                        <span class="fw-500" style="font-size:.85rem">{{ $kategori->nama_kategori }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('admin.kategori.edit', $kategori->id) }}"
                                           class="btn-act edit" title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <button type="button" class="btn-act del btn-delete-single"
                                                data-id="{{ $kategori->id }}"
                                                data-name="{{ $kategori->nama_kategori }}"
                                                title="Hapus">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                        {{-- DELETE --}}
                                        <button type="button"
                                                class="btn-act del btn-delete-single"
                                                data-id="{{ $kategori->id }}"
                                                data-name="{{ $kategori->nama_kategori }}"
                                                title="Hapus">

                                            <i class="ti ti-trash"></i>

                                        </button>

                                        <form id="delete-form-{{ $kategori->id }}"
                                            action="{{ route('admin.kategori.destroy', $kategori->id) }}"
                                            method="POST"
                                            style="display:none;">

                                            @csrf
                                            @method('DELETE')

                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="ti ti-category-off fs-2 d-block mb-2 opacity-30"></i>
                                    Belum ada kategori. Tambah kategori pertama kamu!
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
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
    checkAll.checked = false; checkAll.indeterminate = false;
    rowChecks.forEach(cb => cb.checked = false);
    updateBulkBar();
});

document.getElementById('btnBulkDelete').addEventListener('click', function() {
    const checked = [...document.querySelectorAll('.row-check:checked')];
    if (!checked.length) return;
    const ids = checked.map(cb => cb.value);
    Swal.fire({
        title: 'Hapus ' + ids.length + ' kategori?',
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
                inp.type = 'hidden'; inp.name = 'ids[]'; inp.value = id;
                container.appendChild(inp);
            });
            document.getElementById('bulkDeleteForm').submit();
        }
    });
});

document.querySelectorAll('.btn-delete-single').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id, name = this.dataset.name;
        Swal.fire({
            title: 'Hapus kategori?',
            html: 'Kategori <strong>' + name + '</strong> akan dipindahkan ke trash, pulihkan jika dibutuhkan kembali.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#E66239',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="ti ti-trash me-1"></i> Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then(result => {
            if (result.isConfirmed) document.getElementById('del-' + id).submit();
        });
    });
});
</script>
<script>

document.querySelectorAll('.btn-delete-single').forEach(button => {

    button.addEventListener('click', function () {

        let id   = this.dataset.id;
        let name = this.dataset.name;

        Swal.fire({
            title: 'Pindahkan ke trash?',
            html: `Kategori <b>${name}</b> akan dipindahkan ke trash.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {

            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }

        });

    });

});

</script>
@endpush

