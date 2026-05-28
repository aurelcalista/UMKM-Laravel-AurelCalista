@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Permintaan Reset Password</h3>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table">
                <thead>
                    <tr><th>ID</th><th>Email</th><th>Tanggal Request</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                    <tr>
                        <td>{{ $req->id }}</td>
                        <td>{{ $req->email }}</td>
                        <td>{{ $req->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.password-resets.approve', $req->id) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success">Setujui & Buat Password</button>
                            </form>
                            <form method="POST" action="{{ route('admin.password-resets.reject', $req->id) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Tolak?')">Tolak</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4">Tidak ada permintaan</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $requests->links() }}
        </div>
    </div>
</div>
@endsection