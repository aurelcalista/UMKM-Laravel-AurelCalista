@extends('layouts.admin')

@section('title', 'Detail Transaksi #' . $transaksi->id)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Detail Pesanan #{{ $transaksi->id }}</h3>
                </div>
                <div class="card-body">
                    <h5>Status: 
                        @if($transaksi->approval_status == 'pending')
                            <span class="badge bg-warning">⏳ Menunggu Approval</span>
                        @elseif($transaksi->approval_status == 'approved')
                            <span class="badge bg-success">✅ Disetujui</span>
                        @elseif($transaksi->approval_status == 'rejected')
                            <span class="badge bg-danger">❌ Ditolak</span>
                        @elseif($transaksi->approval_status == 'completed')
                            <span class="badge bg-info">🎉 Selesai</span>
                        @endif
                    </h5>
                    
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Gambar</th>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach($transaksi->details as $detail)
                            @php 
                                $subtotal = $detail->harga * $detail->qty;
                                $total += $subtotal;
                            @endphp
                            <tr>
                                <td style="width:80px;">
                                    @if($detail->produk && $detail->produk->poto)
                                        <img src="{{ asset('storage/' . $detail->produk->poto) }}" 
                                            style="width:60px; height:60px; object-fit:cover; border-radius:12px;">
                                    @else
                                        <div style="width:60px; height:60px; background:#f1f1f1; display:flex; align-items:center; justify-content:center; border-radius:12px;">
                                            🍜
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $detail->produk->nama ?? 'Produk tidak tersedia' }}</td>
                                <td>{{ $detail->qty }} x</td>
                                <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Total:</th>
                                <th>Rp {{ number_format($total, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <div class="mt-3">
                        <p><strong>Metode Bayar:</strong> {{ $transaksi->metode_bayar }}</p>
                        <p><strong>Metode Kirim:</strong> {{ $transaksi->metode_kirim }}</p>
                        <p><strong>Alamat:</strong> {{ $transaksi->alamat }}</p>
                        <p><strong>Catatan:</strong> {{ $transaksi->catatan ?? '-' }}</p>
                        <p><strong>Tanggal Transaksi:</strong> {{ $transaksi->created_at ? \Carbon\Carbon::parse($transaksi->created_at)->format('d/m/Y H:i') : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>Approval</h4>
                </div>
                <div class="card-body">
                    @if($transaksi->approval_status == 'pending')
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success w-100 mb-2" onclick="confirmApprove({{ $transaksi->id }})">
                                ✅ Approve Pesanan
                            </button>
                            <button type="button" class="btn btn-danger w-100" onclick="confirmReject({{ $transaksi->id }})">
                                ❌ Tolak Pesanan
                            </button>
                        </div>
                    @elseif($transaksi->approval_status == 'approved')
                        <div class="alert alert-success">
                            ✅ Pesanan sudah disetujui oleh {{ $transaksi->approver->name ?? 'Admin' }}
                            <br><small>{{ $transaksi->approved_at ? \Carbon\Carbon::parse($transaksi->approved_at)->format('d/m/Y H:i') : '-' }}</small>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-info w-100" onclick="confirmComplete({{ $transaksi->id }})">
                                🎉 Tandai Selesai
                            </button>
                        </div>
                    @elseif($transaksi->approval_status == 'rejected')
                        <div class="alert alert-danger">
                            ❌ Pesanan ditolak
                            @if($transaksi->approved_at)
                            <br><small>{{ \Carbon\Carbon::parse($transaksi->approved_at)->format('d/m/Y H:i') }}</small>
                            @endif
                        </div>
                    @elseif($transaksi->approval_status == 'completed')
                        <div class="alert alert-secondary">
                            🎉 Pesanan telah selesai
                            @if($transaksi->approved_at)
                            <br><small>Selesai: {{ \Carbon\Carbon::parse($transaksi->approved_at)->format('d/m/Y H:i') }}</small>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmApprove(id) {
    Swal.fire({
        title: 'Approve Pesanan?',
        text: "Pesanan akan disetujui dan stok akan berkurang!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Approve!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/approvals/' + id + '/approve';
            form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function confirmReject(id) {
    Swal.fire({
        title: 'Reject Pesanan?',
        text: "Pesanan akan ditolak dan tidak dapat dibatalkan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Reject!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/approvals/' + id + '/reject';
            form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function confirmComplete(id) {
    Swal.fire({
        title: 'Selesaikan Pesanan?',
        text: "Tandai pesanan ini sebagai selesai!",
        icon: 'success',
        showCancelButton: true,
        confirmButtonColor: '#17a2b8',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Selesaikan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/approvals/' + id + '/complete';
            form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

@endsection