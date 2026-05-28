@extends('layouts.admin')

@section('title', 'Data Pelanggan')

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

    .form-control, .form-select {
        font-size: .85rem; border-color: var(--gray-200); border-radius: 8px;
        transition: border-color .15s, box-shadow .15s;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--primary); box-shadow: 0 0 0 .2rem rgba(230,98,57,.15);
    }

    .avatar-initial {
        width: 36px; height: 36px; border-radius: 50%;
        background: var(--primary-light); color: var(--primary);
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: .85rem; flex-shrink: 0;
    }

    .btn-act {
        width: 32px; height: 32px; border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
        border: 1px solid; font-size: 1rem;
        transition: background .15s, color .15s;
        cursor: pointer; background: transparent; padding: 0;
        text-decoration: none;
    }
    .btn-act.warn { border-color: #ffc107; color: #e0a800; }
    .btn-act.warn:hover { background: #ffc107; color: #fff; }
    .btn-act.del { border-color: #dc3545; color: #dc3545; }
    .btn-act.del:hover { background: #dc3545; color: #fff; }
    .btn-act.info { border-color: #0dcaf0; color: #0ca5c9; }
    .btn-act.info:hover { background: #0dcaf0; color: #fff; }
    
    .temp-password-box {
        background: #FEF3C7;
        padding: 16px;
        border-radius: 12px;
        margin-top: 16px;
        text-align: center;
        display: none;
    }
    .temp-password-box.show { display: block; }
    .temp-password-text {
        font-family: 'Courier New', monospace;
        font-size: 20px;
        font-weight: bold;
        letter-spacing: 1px;
        background: white;
        display: inline-block;
        padding: 8px 16px;
        border-radius: 8px;
        margin-top: 8px;
        color: #8B1A1A;
    }

    /* Badge transaksi */
    .badge-transaksi {
        display: inline-block;
        min-width: 105px;
        padding: 6px 12px;
        font-size: 0.7rem;
        font-weight: 600;
        text-align: center;
        border-radius: 20px;
    }
    .badge-transaksi.zero {
        background: #F3F4F6;
        color: #6B7280;
        border: 1px solid #E5E7EB;
    }
    .badge-transaksi.nonzero {
        background: #ECFDF5;
        color: #065F46;
        border: 1px solid #A7F3D0;
    }
    .badge-total {
        background: #D1FAE5 !important;
        color: #065F46 !important;
        padding: 5px 12px;
        border-radius: 30px;
        font-weight: 600;
    }

    /* Tab styling */
    .nav-tabs-custom {
        border-bottom: 1px solid var(--sand);
        margin-bottom: 1.5rem;
    }
    .nav-tabs-custom .nav-link {
        border: none;
        padding: 0.75rem 1.25rem;
        font-weight: 600;
        color: var(--text-muted);
        background: transparent;
        border-radius: 0;
        position: relative;
    }
    .nav-tabs-custom .nav-link i {
        margin-right: 8px;
    }
    .nav-tabs-custom .nav-link.active {
        color: var(--maroon);
        background: transparent;
        border-bottom: 2px solid var(--maroon);
    }
    .nav-tabs-custom .nav-link:hover:not(.active) {
        color: var(--maroon-light);
        background: rgba(123,31,31,0.05);
    }
    .tab-pane {
        animation: fadeIn 0.2s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
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
        <h1>Manajemen Pelanggan</h1>
        <p>Kelola data pelanggan dan permintaan reset password</p>
    </div>
</div>

{{-- Tabs --}}
<ul class="nav nav-tabs-custom" id="pelangganTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="data-pelanggan-tab" data-bs-toggle="tab" data-bs-target="#dataPelanggan" type="button" role="tab">
            <i class="ti ti-users"></i> Data Pelanggan
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="reset-requests-tab" data-bs-toggle="tab" data-bs-target="#resetRequests" type="button" role="tab">
            <i class="ti ti-refresh"></i> Permintaan Reset Password
            @if(isset($resetRequests) && $resetRequests->total() > 0)
                <span class="badge bg-danger ms-1">{{ $resetRequests->total() }}</span>
            @endif
        </button>
    </li>
</ul>

<div class="tab-content">
    {{-- ==================== TAB 1: DATA PELANGGAN ==================== --}}
    <div class="tab-pane fade show active" id="dataPelanggan" role="tabpanel">

        {{-- Search --}}
        <div class="card mb-4">
            <div class="card-body py-3 px-4">
                <form method="GET" action="{{ route('admin.pelanggan.index') }}" class="row g-2 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label mb-1" style="font-size:.75rem;font-weight:500;color:#555">Cari Pelanggan</label>
                        <input type="text" name="search" class="form-control form-control-sm"
                               placeholder="Nama / email / username..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="ti ti-search me-1"></i> Cari
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.pelanggan.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                            <i class="ti ti-refresh me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

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

        {{-- Bulk delete form --}}
        <form id="bulkDeleteForm" method="POST" action="{{ route('admin.pelanggan.bulkDestroy') }}" class="d-none">
            @csrf @method('DELETE')
            <div id="bulkIds"></div>
        </form>

        {{-- Tabel Pelanggan --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center px-4 py-3">
                <h3 class="h6 mb-0 fw-600">👥 Daftar Pelanggan</h3>
                <span class="badge-total">
                    <i class="ti ti-users me-1"></i>
                    {{ $users->total() ?? 0 }} pelanggan
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="40">
                                <input type="checkbox" class="form-check-input" id="checkAll" title="Pilih semua">
                            </th>
                            <th width="40">#</th>
                            <th>Pelanggan</th>
                            <th>Username</th>
                            <th>No. HP</th>
                            <th>Alamat</th>
                            <th class="text-center">Transaksi</th>
                            <th class="text-center" width="140">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $i => $u)
                        <tr data-id="{{ $u->id }}">
                            <td>
                                <input type="checkbox" class="form-check-input row-check" value="{{ $u->id }}">
                            </td>
                            <td class="text-muted" style="font-size:.82rem">
                                {{ $users->firstItem() + $i }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-initial">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-500" style="font-size:.85rem">{{ $u->name }}</div>
                                        <div class="text-muted" style="font-size:.72rem">{{ $u->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:.82rem">{{ $u->username ?? '-' }}</td>
                            <td style="font-size:.82rem">{{ $u->hp ?? '-' }}</td>
                            <td style="font-size:.8rem;max-width:150px">
                                <span class="text-truncate d-inline-block" style="max-width:140px" title="{{ $u->alamat ?? '' }}">
                                    {{ $u->alamat ?? '-' }}
                                </span>
                            </td>
                            <td class="text-center">
                                @php $transCount = $u->transaksis_count ?? 0; @endphp
                                <span class="badge-transaksi {{ $transCount == 0 ? 'zero' : 'nonzero' }}">
                                    <i class="ti {{ $transCount == 0 ? 'ti-receipt' : 'ti-receipt-tax' }} me-1"></i>
                                    {{ $transCount }} transaksi
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <button type="button" class="btn-act warn btn-reset-password"
                                            data-id="{{ $u->id }}"
                                            data-name="{{ $u->name }}"
                                            title="Reset Password (kirim ke admin)">
                                        <i class="ti ti-mail-forward"></i>
                                    </button>
                                    <button type="button" class="btn-act info btn-reset-fast"
                                            data-id="{{ $u->id }}"
                                            data-name="{{ $u->name }}"
                                            title="Reset Password Langsung (generate random)">
                                        <i class="ti ti-lock-open"></i>
                                    </button>
                                    <button type="button" class="btn-act del btn-delete-single"
                                            data-id="{{ $u->id }}"
                                            data-name="{{ $u->name }}"
                                            title="Hapus Pelanggan">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                    <form id="del-{{ $u->id }}" method="POST"
                                        action="{{ route('admin.pelanggan.destroy', $u->id) }}" class="d-none">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="ti ti-users-off fs-2 d-block mb-2 opacity-30"></i>
                                @if(request('search'))
                                    Tidak ada pelanggan dengan kata kunci "<strong>{{ request('search') }}</strong>".
                                @else
                                    Belum ada pelanggan terdaftar.
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(isset($users) && method_exists($users, 'hasPages') && $users->hasPages())
            <div class="card-footer d-flex justify-content-end py-2">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>

    {{-- ==================== TAB 2: PERMINTAAN RESET PASSWORD ==================== --}}
    <div class="tab-pane fade" id="resetRequests" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <h3 class="h6 mb-0">📋 Permintaan Reset Password</h3>
            </div>
            <div class="card-body">
                @if(session('reset_success'))
                    <div class="alert alert-success">{{ session('reset_success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Email</th>
                                <th>Tanggal Request</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($resetRequests ?? [] as $req)
                            <tr>
                                <td>{{ $req->id }}</td>
                                <td>{{ $req->email }}</td>
                                <td>{{ $req->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('admin.password-resets.approve', $req->id) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="ti ti-check"></i> Setujui
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.password-resets.reject', $req->id) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tolak permintaan ini?')">
                                            <i class="ti ti-x"></i> Tolak
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="ti ti-inbox fs-2 d-block mb-2"></i>
                                    Tidak ada permintaan reset password
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(isset($resetRequests) && method_exists($resetRequests, 'hasPages') && $resetRequests->hasPages())
                <div class="d-flex justify-content-end mt-3">
                    {{ $resetRequests->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal Reset Password (via admin approval) --}}
<div class="modal fade" id="modalResetPassword" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-600">🔒 Reset Password Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formResetPassword" method="POST">
                @csrf
                <div class="modal-body pt-2">
                    <p class="text-muted mb-3" id="resetPasswordInfo" style="font-size:.85rem"></p>
                    <div class="alert alert-info py-2 px-3 mb-3" style="font-size:.8rem;">
                        <i class="ti ti-info-circle me-1"></i> 
                        Pelanggan akan mendapatkan password sementara dan harus mengganti password saat login.
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-weight:500;font-size:.82rem">Password Baru (Opsional)</label>
                        <input type="text" name="password" class="form-control" id="manualPassword"
                               placeholder="Kosongkan untuk generate random">
                        <div class="form-text" style="font-size:.72rem">Biarkan kosong untuk generate password random otomatis.</div>
                    </div>
                    <div id="generatedPasswordBox" class="temp-password-box">
                        <i class="ti ti-key"></i> Password Sementara:
                        <div class="temp-password-text" id="generatedPasswordText"></div>
                        <small class="text-muted d-block mt-2">Password ini akan muncul sekali. Simpan dan berikan ke pelanggan.</small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning" id="submitResetBtn">
                        <i class="ti ti-lock-open me-1"></i> Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Reset Password Langsung (Fast Reset) --}}
<div class="modal fade" id="modalFastReset" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-600">⚡ Reset Password Langsung</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formFastReset" method="POST">
                @csrf
                <div class="modal-body pt-2">
                    <p class="text-muted mb-3" id="fastResetInfo" style="font-size:.85rem"></p>
                    <div class="alert alert-warning py-2 px-3 mb-3" style="font-size:.8rem;">
                        <i class="ti ti-alert-triangle me-1"></i> 
                        Password akan langsung direset dan pelanggan bisa login dengan password baru.
                    </div>
                    <div id="fastPasswordBox" class="temp-password-box">
                        <i class="ti ti-key"></i> Password Baru:
                        <div class="temp-password-text" id="fastPasswordText"></div>
                        <small class="text-muted d-block mt-2">Password ini akan muncul sekali. Simpan dan berikan ke pelanggan.</small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-info" id="submitFastResetBtn">
                        <i class="ti ti-lock-open me-1"></i> Ya, Reset Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
// Reset password via admin approval
document.getElementById('modalResetPassword').addEventListener('show.bs.modal', function(event) {
    const btn = event.relatedTarget;
    const id = btn.getAttribute('data-id');
    const name = btn.getAttribute('data-name');
    document.getElementById('resetPasswordInfo').textContent = 'Reset password untuk pelanggan: ' + name;
    document.getElementById('formResetPassword').action = '/admin/pelanggan/' + id + '/reset-password';
    document.getElementById('generatedPasswordBox').classList.remove('show');
    document.getElementById('manualPassword').value = '';
    document.getElementById('generatedPasswordText').textContent = '';
});

document.getElementById('manualPassword').addEventListener('input', function() {
    if (this.value) {
        document.getElementById('generatedPasswordBox').classList.remove('show');
    } else {
        document.getElementById('generatedPasswordBox').classList.add('show');
        document.getElementById('generatedPasswordText').textContent = 'SL-' + Math.random().toString(36).substring(2, 10).toUpperCase();
    }
});

// Fast reset langsung
document.querySelectorAll('.btn-reset-fast').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const name = this.dataset.name;
        document.getElementById('fastResetInfo').textContent = 'Reset password untuk: ' + name;
        document.getElementById('formFastReset').action = '/admin/pelanggan/' + id + '/reset-password-fast';
        document.getElementById('fastPasswordBox').classList.remove('show');
        
        const randomPass = 'SL-' + Math.random().toString(36).substring(2, 10).toUpperCase();
        document.getElementById('fastPasswordText').textContent = randomPass;
        document.getElementById('fastPasswordBox').classList.add('show');
        
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'password';
        hiddenInput.value = randomPass;
        const oldInput = document.querySelector('#formFastReset input[name="password"]');
        if (oldInput) oldInput.remove();
        document.getElementById('formFastReset').appendChild(hiddenInput);
        
        new bootstrap.Modal(document.getElementById('modalFastReset')).show();
    });
});

// Submit fast reset
document.getElementById('formFastReset').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('submitFastResetBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="ti ti-loader me-1"></i> Memproses...';
    
    fetch(this.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            password: this.querySelector('input[name="password"]').value
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Password Berhasil Direset!',
                html: '<strong style="font-size:20px;">' + data.password + '</strong><br><small>Password sementara untuk pelanggan.</small>',
                confirmButtonColor: '#E66239'
            });
            bootstrap.Modal.getInstance(document.getElementById('modalFastReset')).hide();
        } else {
            Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message });
        }
    })
    .catch(error => {
        Swal.fire({ icon: 'error', title: 'Error!', text: 'Terjadi kesalahan server.' });
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="ti ti-lock-open me-1"></i> Ya, Reset Sekarang';
    });
});

// Checkbox logic
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

if (checkAll) {
    checkAll.addEventListener('change', function() {
        rowChecks.forEach(cb => cb.checked = this.checked);
        updateBulkBar();
    });
}

rowChecks.forEach(cb => cb.addEventListener('change', function() {
    if (checkAll) {
        checkAll.checked = [...rowChecks].every(c => c.checked);
        checkAll.indeterminate = [...rowChecks].some(c => c.checked) && !checkAll.checked;
    }
    updateBulkBar();
}));

document.getElementById('btnClearSelect')?.addEventListener('click', function() {
    if (checkAll) checkAll.checked = false;
    if (checkAll) checkAll.indeterminate = false;
    rowChecks.forEach(cb => cb.checked = false);
    updateBulkBar();
});

document.getElementById('btnBulkDelete')?.addEventListener('click', function() {
    const checked = [...document.querySelectorAll('.row-check:checked')];
    if (!checked.length) return;
    const ids = checked.map(cb => cb.value);
    Swal.fire({
        title: 'Hapus ' + ids.length + ' pelanggan?',
        text: 'Data pelanggan yang dihapus tidak dapat dikembalikan!',
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
            title: 'Hapus pelanggan?',
            html: 'Pelanggan <strong>' + name + '</strong> akan dihapus permanen.',
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
@endpush