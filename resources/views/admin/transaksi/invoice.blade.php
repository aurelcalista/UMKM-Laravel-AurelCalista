<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #INV-{{ str_pad($transaksi->id, 4, '0', STR_PAD_LEFT) }} | Seoullicious</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            background: #f5f5f5;
            padding: 40px 20px;
        }

        /* CONTAINER */
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        /* ACTION BUTTONS */
        .action-buttons {
            background: white;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: inherit;
            margin: 0 6px;
        }
        .btn-primary {
            background: #8B1A1A;
            color: white;
        }
        .btn-primary:hover {
            background: #6b1414;
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        /* INVOICE CONTENT */
        .invoice-content {
            padding: 40px;
        }

        /* HEADER */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #8B1A1A;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .brand-icon {
            font-size: 48px;
        }
        .brand-info h1 {
            font-size: 28px;
            color: #8B1A1A;
            margin-bottom: 4px;
        }
        .brand-info p {
            font-size: 12px;
            color: #999;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h2 {
            font-size: 32px;
            color: #8B1A1A;
            letter-spacing: 2px;
            margin-bottom: 8px;
        }
        .invoice-title .invoice-number {
            font-size: 16px;
            font-weight: 600;
            color: #555;
            background: #f0f0f0;
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
        }

        /* INFO GRID */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
            padding: 20px;
            background: #fafafa;
            border-radius: 12px;
        }
        .info-box h4 {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #999;
            margin-bottom: 8px;
        }
        .info-box p {
            font-size: 14px;
            color: #333;
            line-height: 1.6;
        }
        .info-box .name {
            font-size: 16px;
            font-weight: 700;
            color: #8B1A1A;
        }

        /* STATUS BADGE */
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            margin-top: 8px;
        }
        .status-pending { background: #ffc107; color: #856404; }
        .status-approved { background: #28a745; color: white; }
        .status-completed { background: #17a2b8; color: white; }
        .status-rejected { background: #dc3545; color: white; }

        /* TABLE */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            text-align: left;
            padding: 12px;
            background: #8B1A1A;
            color: white;
            font-size: 13px;
            font-weight: 600;
        }
        .items-table th:last-child {
            text-align: right;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }
        .items-table td:last-child {
            text-align: right;
            font-weight: 600;
        }
        .items-table tr:last-child td {
            border-bottom: none;
        }

        /* TOTAL SECTION */
        .total-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }
        .total-box {
            width: 280px;
            background: #fafafa;
            border-radius: 12px;
            padding: 20px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 13px;
        }
        .total-row.grand-total {
            border-top: 2px solid #8B1A1A;
            margin-top: 8px;
            padding-top: 12px;
            font-weight: 800;
            font-size: 16px;
            color: #8B1A1A;
        }

        /* PAYMENT INFO */
        .payment-info {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
        }
        .payment-info h4 {
            font-size: 12px;
            text-transform: uppercase;
            color: #999;
            margin-bottom: 12px;
        }
        .payment-methods {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .payment-method {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }

        /* FOOTER */
        .invoice-footer {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid #eee;
            margin-top: 20px;
        }
        .invoice-footer .thanks {
            font-size: 16px;
            font-weight: 600;
            color: #8B1A1A;
            margin-bottom: 8px;
        }
        .invoice-footer p {
            font-size: 11px;
            color: #999;
        }

        /* PRINT STYLES */
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .action-buttons {
                display: none;
            }
            .invoice-container {
                box-shadow: none;
                border-radius: 0;
            }
            .btn {
                display: none;
            }
        }
        @media print {

        .no-print{
            display:none !important;
        }

    }
    </style>
</head>
<body>

<div class="invoice-container">
    {{-- ACTION BUTTONS --}}
    <div class="action-buttons">
        <button class="btn btn-primary" onclick="window.print()">
            🖨️ Cetak Invoice
        </button>
        <button class="btn btn-secondary" onclick="window.location.href='{{ route('admin.transaksi.index') }}'">
        ← Kembali ke Daftar Transaksi
    </button>
    </div>

    {{-- INVOICE CONTENT --}}
    <div class="invoice-content">
        
        {{-- HEADER --}}
        <div class="invoice-header">
            <div class="brand">
                <div class="brand-icon">🍜</div>
                <div class="brand-info">
                    <h1>Seoullicious</h1>
                    <p>Authentic Korean Restaurant & Delivery</p>
                    <p>📍 Jakarta, Indonesia</p>
                </div>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <div class="invoice-number">
                    #INV-{{ str_pad($transaksi->id, 4, '0', STR_PAD_LEFT) }}
                </div>
            </div>
        </div>

        {{-- INFO GRID --}}
        <div class="info-grid">
            <div class="info-box">
                <h4>📋 INFORMASI PELANGGAN</h4>
                <p class="name">{{ $transaksi->user->name ?? 'Pelanggan' }}</p>
                <p>{{ $transaksi->user->email ?? '-' }}</p>
                <p>{{ $transaksi->user->hp ?? '-' }}</p>
                <p>{{ $transaksi->alamat ?? '-' }}</p>
            </div>
            <div class="info-box">
                <h4>📅 DETAIL TRANSAKSI</h4>
                <p><strong>Tanggal:</strong> {{ $transaksi->created_at ? $transaksi->created_at->format('d/m/Y H:i') : '-' }}</p>
                <p><strong>Metode Bayar:</strong> {{ $transaksi->metode_bayar ?? '-' }}</p>
                <p><strong>Metode Kirim:</strong> {{ $transaksi->metode_kirim ?? '-' }}</p>
                <p><strong>Status:</strong> 
                    @if($transaksi->approval_status == 'pending')
                        <span class="status-badge status-pending">⏳ Pending</span>
                    @elseif($transaksi->approval_status == 'approved')
                        <span class="status-badge status-approved">✅ Approved</span>
                    @elseif($transaksi->approval_status == 'completed')
                        <span class="status-badge status-completed">🎉 Completed</span>
                    @elseif($transaksi->approval_status == 'rejected')
                        <span class="status-badge status-rejected">❌ Rejected</span>
                    @endif
                </p>
            </div>
        </div>

        {{-- ITEMS TABLE --}}
<table class="items-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Foto</th>
            <th>Produk</th>
            <th style="text-align:center">Qty</th>
            <th>Harga</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @forelse($transaksi->details as $index => $detail)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td style="width: 70px;">
                @php
                    $fotoPath = null;
                    if($detail->produk && $detail->produk->poto) {
                        $fotoPath = asset('storage/' . $detail->produk->poto);
                    } elseif($detail->foto) {
                        $fotoPath = asset('storage/' . $detail->foto);
                    }
                @endphp
                
                @if($fotoPath)
                    <img src="{{ $fotoPath }}" 
                         alt="{{ $detail->produk->nama ?? 'Produk' }}"
                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                @else
                    <div style="width: 60px; height: 60px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                        🍜
                    </div>
                @endif
            </td>
            <td>
                <strong>{{ $detail->produk->nama ?? 'Produk tidak tersedia' }}</strong>
                @if($detail->produk && $detail->produk->kategori)
                    <br><small style="color: #999;">{{ $detail->produk->kategori }}</small>
                @endif
            </td>
            <td style="text-align:center">{{ $detail->qty }} x</td>
            <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($detail->qty * $detail->harga, 0, ',', '.') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align:center; padding: 40px;">
                Tidak ada data detail pesanan
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

        {{-- TOTAL SECTION --}}
        <div class="total-section">
            <div class="total-box">
                <div class="total-row">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="total-row">
                    <span>Biaya Pengiriman</span>
                    <span>Gratis 🎉</span>
                </div>
                <div class="total-row grand-total">
                    <span>TOTAL</span>
                    <span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- PAYMENT INFO --}}
        @if($transaksi->metode_bayar && $transaksi->approval_status == 'pending')
        <div class="payment-info">
            <h4>💳 INFORMASI PEMBAYARAN</h4>
            <div class="payment-methods">
                <div class="payment-method">🏦 Transfer Bank: BCA / Mandiri / BRI</div>
                <div class="payment-method">📱 QRIS: Scan via GoPay, OVO, DANA</div>
                <div class="payment-method">💚 E-Wallet: GoPay / OVO / DANA</div>
            </div>
            <p style="font-size: 11px; margin-top: 12px; color: #999;">
                * Konfirmasi pembayaran setelah melakukan transfer ke nomor rekening yang tersedia.
            </p>
        </div>
        @endif

        {{-- FOOTER --}}
        <div class="invoice-footer">
            <div class="thanks">감사합니다 — Terima kasih telah berbelanja di Seoullicious! 🙏</div>
            <p>Invoice ini diterbitkan secara otomatis oleh sistem Seoullicious.</p>
            <p>Untuk pertanyaan atau bantuan, hubungi WhatsApp: +62 812 3456 7890</p>
            <p style="margin-top: 8px;">✨ Nikmati cita rasa Korea autentik di Seoullicious! ✨</p>
        </div>

    </div>
</div>

<script>
    // Auto print jika parameter print=1
    if (window.location.search.includes('print=1')) {
        window.onload = function() {
            setTimeout(() => {
                window.print();
            }, 500);
        }
    }
</script>

</body>
</html>