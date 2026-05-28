@extends('layouts.admin')

@section('title', 'Detail Transaksi #' . $transaksi->id)

@push('styles')
<style>
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }
    .status-pending {
        background: #ffc107;
        color: #856404;
    }
    .status-approved {
        background: #28a745;
        color: white;
    }
    .status-rejected {
        background: #dc3545;
        color: white;
    }
    .status-completed {
        background: #17a2b8;
        color: white;
    }
    .info-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 20px;
    }
    .info-label {
        font-size: 0.7rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 4px;
    }
    .info-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: #333;
    }
    .btn-approve {
        background: #28a745;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
    }
    .btn-reject {
        background: #dc3545;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
    }
    .btn-complete {
        background: #17a2b8;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
    }
    .btn-approve:hover, .btn-reject:hover, .btn-complete:hover {
        opacity: 0.9;
    }
    
    /* Style untuk bukti bayar */
    .bukti-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 20px;
        text-align: center;
    }
    .bukti-image {
        max-width: 100%;
        max-height: 300px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        cursor: pointer;
        transition: transform 0.2s;
    }
    .bukti-image:hover {
        transform: scale(1.02);
    }
    .bukti-empty {
        color: #999;
        font-style: italic;
        padding: 20px;
        text-align: center;
    }
    .modal-bukti {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.9);
        cursor: pointer;
    }
    .modal-bukti-content {
        margin: auto;
        display: block;
        max-width: 90%;
        max-height: 90%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    .modal-bukti-close {
        position: absolute;
        top: 20px;
        right: 35px;
        color: #fff;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
    }
</style>
@endpush

@section('content')

<div class="page-header d-flex justify-content-between align-items-start">
    <div>
        <h1>Detail Transaksi</h1>
        <p>#TRX-{{ str_pad($transaksi->id, 4, '0', STR_PAD_LEFT) }}</p>
    </div>
    <a href="{{ route('admin.transaksi.index') }}" class="btn btn-outline-secondary">
        ← Kembali
    </a>
</div>

<div class="row">
    {{-- LEFT COLUMN - Info Transaksi --}}
    <div class="col-md-8">
        {{-- Status Card --}}
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="h6 mb-0">Status Pesanan</h3>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span>Status Saat Ini:</span>
                    @if($transaksi->approval_status == 'pending')
                        <span class="status-badge status-pending">⏳ Pending</span>
                    @elseif($transaksi->approval_status == 'approved')
                        <span class="status-badge status-approved">✅ Approved</span>
                    @elseif($transaksi->approval_status == 'rejected')
                        <span class="status-badge status-rejected">❌ Rejected</span>
                    @elseif($transaksi->approval_status == 'completed')
                        <span class="status-badge status-completed">🎉 Completed</span>
                    @endif
                </div>

                @if($transaksi->approval_status == 'pending')
                <div class="alert alert-info">
                    <strong>⏳ Menunggu Persetujuan</strong><br>
                    Silakan approve atau reject pesanan ini.
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn-approve" onclick="confirmApprove({{ $transaksi->id }})">
                        ✅ Approve Pesanan
                    </button>
                    <button type="button" class="btn-reject" onclick="confirmReject({{ $transaksi->id }})">
                        ❌ Reject Pesanan
                    </button>
                </div>
                @elseif($transaksi->approval_status == 'approved')
                <div class="alert alert-success">
                    <strong>✅ Pesanan Disetujui</strong><br>
                    Pesanan sudah disetujui oleh admin. Stok sudah berkurang.
                </div>
                <div class="mt-3">
                    <button type="button" class="btn-complete" onclick="confirmComplete({{ $transaksi->id }})">
                        🎉 Tandai Selesai
                    </button>
                </div>
                @elseif($transaksi->approval_status == 'rejected')
                <div class="alert alert-danger">
                    <strong>❌ Pesanan Ditolak</strong><br>
                    Pesanan ini telah ditolak oleh admin.
                </div>
                @elseif($transaksi->approval_status == 'completed')
                <div class="alert alert-secondary">
                    <strong>🎉 Pesanan Selesai</strong><br>
                    Pesanan sudah selesai diproses.
                </div>
                @endif
            </div>
        </div>

        {{-- BUKTI PEMBAYARAN --}}
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="h6 mb-0">📸 Bukti Pembayaran</h3>
            </div>
            <div class="card-body">
                @if($transaksi->bukti_bayar)
                    <div class="bukti-card">
                        <img src="{{ Storage::url($transaksi->bukti_bayar) }}" 
                             alt="Bukti Pembayaran"
                             class="bukti-image"
                             onclick="showBuktiModal(this.src)">
                        <div style="margin-top: 12px;">
                            <a href="{{ Storage::url($transaksi->bukti_bayar) }}" 
                               target="_blank" 
                               class="btn btn-sm btn-primary">
                                📥 Buka Gambar Baru
                            </a>
                            <a href="{{ Storage::url($transaksi->bukti_bayar) }}" 
                               download
                               class="btn btn-sm btn-secondary">
                                💾 Download Bukti
                            </a>
                        </div>
                    </div>
                @else
                    <div class="bukti-empty">
                        <span style="font-size: 2rem;">📷</span>
                        <p>Belum ada bukti pembayaran yang diupload.</p>
                        @if($transaksi->metode_bayar == 'COD')
                            <small class="text-muted">(Metode pembayaran COD, bukti tidak diperlukan)</small>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Customer Info --}}
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="h6 mb-0">Informasi Pelanggan</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-label">Nama Lengkap</div>
                        <div class="info-value">{{ $transaksi->user->name ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $transaksi->user->email ?? '-' }}</div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <div class="info-label">No. Telepon</div>
                        <div class="info-value">{{ $transaksi->user->hp ?? '-' }}</div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="info-label">Alamat</div>
                        <div class="info-value">{{ $transaksi->alamat ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment & Delivery --}}
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="h6 mb-0">Metode Pembayaran & Pengiriman</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-label">Metode Pembayaran</div>
                        <div class="info-value">
                            @if($transaksi->metode_bayar == 'Transfer Bank')
                                🏦 Transfer Bank
                            @elseif($transaksi->metode_bayar == 'QRIS')
                                📱 QRIS
                            @elseif($transaksi->metode_bayar == 'COD')
                                💵 Cash on Delivery (COD)
                            @else
                                {{ $transaksi->metode_bayar ?? '-' }}
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Metode Pengiriman</div>
                        <div class="info-value">
                            @if($transaksi->metode_kirim == 'Ambil Sendiri')
                                🚶 Ambil Sendiri
                            @elseif($transaksi->metode_kirim == 'Diantar Kurir')
                                🛵 Diantar Kurir
                            @else
                                {{ $transaksi->metode_kirim ?? '-' }}
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="info-label">Catatan</div>
                        <div class="info-value">{{ $transaksi->catatan ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Products --}}
        <div class="card">
            <div class="card-header">
                <h3 class="h6 mb-0">Detail Pesanan</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Gambar</th>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach($transaksi->details as $detail)
                        @php $subtotal = $detail->harga * $detail->qty; $total += $subtotal; @endphp
                        <tr>

                            <td style="width:80px;">
                                @if($detail->produk && $detail->produk->poto)
                                    <img src="{{ Storage::url($detail->produk->poto) }}"
                                        alt="{{ $detail->produk->nama }}"
                                        style="width:60px;height:60px;object-fit:cover;border-radius:12px;">
                                @else
                                    <div style="width:60px;height:60px;border-radius:12px;background:#f1f1f1;display:flex;align-items:center;justify-content:center;font-size:1.5rem;">
                                        🍜
                                    </div>
                                @endif
                            </td>

                            <td>
                                {{ $detail->produk->nama ?? 'Produk tidak tersedia' }}
                                @if(!$detail->produk)
                                    <small class="text-danger">(produk telah dihapus)</small>
                                @endif
                            </td>

                            <td>
                                Rp {{ number_format($detail->harga, 0, ',', '.') }}
                            </td>

                            <td>
                                {{ $detail->qty }} x
                            </td>

                            <td class="fw-600">
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="3" class="text-end">Total:</th>
                            <th class="fw-600">Rp {{ number_format($total, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- RIGHT COLUMN - Summary --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="h6 mb-0">Ringkasan Pesanan</h3>
            </div>
            <div class="card-body">
                <div class="info-card">
                    <div class="info-label">Tanggal Transaksi</div>
                    <div class="info-value">{{ $transaksi->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="info-card">
                    <div class="info-label">Total Pembayaran</div>
                    <div class="info-value" style="font-size: 1.3rem; color: #8B1A1A;">
                        Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                    </div>
                </div>
                
                @if($transaksi->approved_at)
                <div class="info-card">
                    <div class="info-label">Disetujui Pada</div>
                    <div class="info-value">{{ $transaksi->approved_at->format('d/m/Y H:i') }}</div>
                </div>
                @endif

                @if($transaksi->approved_by)
                <div class="info-card">
                    <div class="info-label">Disetujui Oleh</div>
                    <div class="info-value">{{ $transaksi->approver->name ?? '-' }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal untuk preview gambar besar --}}
<div id="buktiModal" class="modal-bukti" onclick="closeBuktiModal()">
    <span class="modal-bukti-close">&times;</span>
    <img class="modal-bukti-content" id="buktiModalImg">
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

// Fungsi untuk menampilkan modal bukti bayar
function showBuktiModal(src) {
    const modal = document.getElementById('buktiModal');
    const modalImg = document.getElementById('buktiModalImg');
    modal.style.display = 'block';
    modalImg.src = src;
}

function closeBuktiModal() {
    const modal = document.getElementById('buktiModal');
    modal.style.display = 'none';
}

// Tutup modal dengan ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeBuktiModal();
    }
});
</script>

@endsection