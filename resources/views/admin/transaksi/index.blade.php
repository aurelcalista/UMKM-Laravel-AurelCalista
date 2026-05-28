@extends('layouts.admin')

@section('title', 'Data Transaksi')

@push('styles')
<style>
    .badge-warning {
        background: #ffc107;
        color: #856404;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
        display: inline-block;
    }
    .badge-success {
        background: #28a745;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
        display: inline-block;
    }
    .badge-danger {
        background: #dc3545;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
        display: inline-block;
    }
    .badge-info {
        background: #17a2b8;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
        display: inline-block;
    }
    .badge-secondary {
        background: #6c757d;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
        display: inline-block;
    }
    .btn-group {
        display: flex;
        gap: 5px;
    }
    .btn-sm {
        padding: 4px 8px;
        font-size: 0.7rem;
        border-radius: 6px;
        text-decoration: none;
        display: inline-block;
    }
    .btn-primary {
        background: #8B1A1A;
        color: white;
        border: none;
    }
    .btn-info {
        background: #17a2b8;
        color: white;
        border: none;
    }
    .btn-danger {
        background: #dc3545;
        color: white;
        border: none;
    }
</style>
@endpush

@section('content')

<div class="page-header d-flex justify-content-between align-items-start">
    <div>
        <h1>Data Transaksi</h1>
        <p>Kelola semua transaksi pembelian pelanggan Seoullicious</p>
    </div>
</div>

{{-- Filter & Search --}}
<div class="card mb-4">
    <div class="card-body py-3 px-4">
        <form method="GET" action="{{ route('admin.transaksi.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label mb-1" style="font-size:.75rem;font-weight:500">Cari Transaksi</label>
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="No. transaksi / nama pelanggan..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1" style="font-size:.75rem;font-weight:500">Tanggal</label>
                <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ request('tanggal') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    🔍 Filter
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.transaksi.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                    🔄 Reset
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Tabel Transaksi --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center px-4 py-3">
        <h3 class="h6 mb-0 fw-600">🧾 Daftar Transaksi</h3>
        <span class="badge bg-primary">{{ $transaksis->total() ?? 0 }} transaksi</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 text-nowrap">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>No. Transaksi</th>
                    <th>Pelanggan</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Metode Bayar</th>
                    <th>Tanggal</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $i => $t)
                <tr>
                    <td class="text-muted">{{ $transaksis->firstItem() + $i }}</td>
                    <td>
                        <span class="fw-600" style="color:#8B1A1A">
                            #TRX-{{ str_pad($t->id, 4, '0', STR_PAD_LEFT) }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:28px;height:28px;border-radius:50%;background:#f0e6e6;
                                        color:#8B1A1A;display:flex;align-items:center;justify-content:center;
                                        font-size:.7rem;font-weight:700;flex-shrink:0">
                                {{ strtoupper(substr($t->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-600">{{ $t->user->name ?? 'Tamu' }}</div>
                                <div class="small text-muted">{{ $t->user->email ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="fw-600">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                    <td>
                        @if($t->approval_status == 'pending')
                            <span class="badge-warning">⏳ Pending</span>
                        @elseif($t->approval_status == 'approved')
                            <span class="badge-success">✅ Approved</span>
                        @elseif($t->approval_status == 'rejected')
                            <span class="badge-danger">❌ Rejected</span>
                        @elseif($t->approval_status == 'completed')
                            <span class="badge-info">🎉 Completed</span>
                        @else
                            <span class="badge-secondary">{{ $t->status }}</span>
                        @endif
                    </td>
                    <td>{{ $t->metode_bayar ?? '-' }}</td>
                    <td>{{ $t->created_at ? $t->created_at->format('d/m/Y H:i') : '-' }}</td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('admin.transaksi.show', $t->id) }}" class="btn-sm btn-primary">Detail</a>
                            <a href="{{ route('admin.transaksi.invoice', $t->id) }}" class="btn-sm btn-info">🧾 Invoice</a>
                            <button type="button" class="btn-sm btn-danger" onclick="confirmDelete({{ $t->id }})">Hapus</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4" style="color: #999;">
                        📭 Belum ada transaksi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($transaksis->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center py-2 px-4">
        <small class="text-muted">
            Menampilkan {{ $transaksis->firstItem() }}–{{ $transaksis->lastItem() }} dari {{ $transaksis->total() }} transaksi
        </small>
        {{ $transaksis->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Hapus Transaksi?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#8B1A1A',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/transaksi/' + id;
            form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">';
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

@endsection