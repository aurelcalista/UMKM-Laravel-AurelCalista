@extends('layouts.admin')

@section('title', 'Approval Transaksi')

@section('content')
<div class="container">
    <h1>Approval Transaksi</h1>
    
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Metode Bayar</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksis as $transaksi)
            <tr>
                <td>#{{ $transaksi->id }}</td>
                <td>{{ $transaksi->user->name }}</td>
                <td>Rp {{ number_format($transaksi->total_harga) }}</td>
                <td>{{ $transaksi->metode_bayar }}</td>
                <td>
                    @if($transaksi->approval_status == 'pending')
                        <span class="badge bg-warning">⏳ Pending</span>
                    @elseif($transaksi->approval_status == 'approved')
                        <span class="badge bg-success">✅ Approved</span>
                    @elseif($transaksi->approval_status == 'rejected')
                        <span class="badge bg-danger">❌ Rejected</span>
                    @elseif($transaksi->approval_status == 'completed')
                        <span class="badge bg-info">🎉 Completed</span>
                    @endif
                </td>
                <td>{{ $transaksi->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('admin.approvals.show', $transaksi->id) }}" class="btn btn-sm btn-primary">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    {{ $transaksis->links() }}
</div>
@endsection