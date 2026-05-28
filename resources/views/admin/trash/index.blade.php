@extends('layouts.admin')

@section('title','Trash')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="fw-bold mb-1">
            🗑️ Trash
        </h1>

        <p class="text-muted mb-0">
            Semua data yang dihapus sementara
        </p>
    </div>

</div>

{{-- =========================
   PRODUK
========================= --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">
            Produk
        </h5>

        <span class="badge bg-danger">
            {{ $produks->count() }}
        </span>
    </div>

    <div class="table-responsive">

        <table class="table align-middle mb-0">

            <thead class="table-light">
                <tr>
                    <th width="90">Foto</th>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Dihapus</th>
                    <th width="230">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($produks as $p)

                <tr>

                    <td>
                        <img src="{{ asset('storage/'.$p->gambar) }}"
                             width="65"
                             height="65"
                             style="object-fit:cover;border-radius:12px;">
                    </td>

                    <td>

                        <div class="fw-semibold">
                            {{ $p->nama }}
                        </div>

                        <small class="text-muted">
                            {{ $p->kategori->nama_kategori ?? '-' }}
                        </small>

                    </td>

                    <td>
                        <span class="fw-semibold text-success">
                            Rp{{ number_format($p->harga,0,',','.') }}
                        </span>
                    </td>

                    <td>
                        {{ $p->deleted_at->diffForHumans() }}
                    </td>

                    <td>

                        <div class="d-flex gap-2">

                            {{-- RESTORE --}}
                            <form action="{{ route('admin.produk.restore',$p->id) }}"
                                  method="POST">

                                @csrf

                                <button class="btn btn-success btn-sm">
                                    <i class="ti ti-restore"></i>
                                    Restore
                                </button>

                            </form>

                            {{-- DELETE --}}
                            <form action="{{ route('admin.produk.forceDelete',$p->id) }}"
                                  method="POST">

                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger btn-sm">
                                    <i class="ti ti-trash"></i>
                                    Hapus
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="5" class="text-center py-5">

                        <i class="ti ti-trash-off"
                           style="font-size:2rem;opacity:.3;"></i>

                        <div class="mt-2">
                            Trash produk kosong
                        </div>

                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

{{-- =========================
   KATEGORI
========================= --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white d-flex justify-content-between align-items-center">

        <h5 class="fw-bold mb-0">
            Kategori
        </h5>

        <span class="badge bg-warning text-dark">
            {{ $kategoris->count() }}
        </span>

    </div>

    <div class="table-responsive">

        <table class="table align-middle mb-0">

            <thead class="table-light">
                <tr>
                    <th>Nama Kategori</th>
                    <th>Dihapus</th>
                    <th width="230">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($kategoris as $k)

                <tr>

                    <td>

                        <div class="fw-semibold">
                            {{ $k->nama_kategori }}
                        </div>

                    </td>

                    <td>
                        {{ $k->deleted_at->diffForHumans() }}
                    </td>

                    <td>

                        <div class="d-flex gap-2">

                            {{-- RESTORE --}}
                            <form action="{{ route('admin.kategori.restore',$k->id) }}"
                                  method="POST">

                                @csrf

                                <button class="btn btn-success btn-sm">
                                    <i class="ti ti-restore"></i>
                                    Restore
                                </button>

                            </form>

                            {{-- DELETE --}}
                            <form action="{{ route('admin.kategori.forceDelete',$k->id) }}"
                                  method="POST">

                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger btn-sm">
                                    <i class="ti ti-trash"></i>
                                    Hapus
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="3" class="text-center py-5">

                        <i class="ti ti-trash-off"
                           style="font-size:2rem;opacity:.3;"></i>

                        <div class="mt-2">
                            Trash kategori kosong
                        </div>

                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

{{-- =========================
   PROMO
========================= --}}
<div class="card border-0 shadow-sm">

    <div class="card-header bg-white d-flex justify-content-between align-items-center">

        <h5 class="fw-bold mb-0">
            Promo
        </h5>

        <span class="badge bg-primary">
            {{ $promos->count() }}
        </span>

    </div>

    <div class="table-responsive">

        <table class="table align-middle mb-0">

            <thead class="table-light">
                <tr>
                    <th width="90">Banner</th>
                    <th>Promo</th>
                    <th>Diskon</th>
                    <th>Periode</th>
                    <th>Dihapus</th>
                    <th width="230">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($promos as $promo)

                <tr>

                    <td>

                        @if($promo->banner)

                        <img src="{{ asset('storage/'.$promo->banner) }}"
                             width="70"
                             height="50"
                             style="object-fit:cover;border-radius:10px;">

                        @else

                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                             style="width:70px;height:50px;">

                            <i class="ti ti-photo text-muted"></i>

                        </div>

                        @endif

                    </td>

                    <td>

                        <div class="fw-semibold">
                            {{ $promo->nama_promo }}
                        </div>

                        <small class="text-muted">
                            {{ $promo->deskripsi }}
                        </small>

                    </td>

                    <td>

                        <span class="badge bg-danger">
                            {{ $promo->diskon }}%
                        </span>

                    </td>

                    <td>

                        <small>
                            {{ \Carbon\Carbon::parse($promo->tanggal_mulai)->format('d M Y') }}
                            -
                            {{ \Carbon\Carbon::parse($promo->tanggal_selesai)->format('d M Y') }}
                        </small>

                    </td>

                    <td>
                        {{ $promo->deleted_at->diffForHumans() }}
                    </td>

                    <td>

                        <div class="d-flex gap-2">

                            {{-- RESTORE --}}
                            <form action="{{ route('admin.promo.restore',$promo->id) }}"
                                  method="POST">

                                @csrf

                                <button class="btn btn-success btn-sm">
                                    <i class="ti ti-restore"></i>
                                    Restore
                                </button>

                            </form>

                            {{-- DELETE --}}
                            <form action="{{ route('admin.promo.forceDelete',$promo->id) }}"
                                  method="POST">

                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger btn-sm">
                                    <i class="ti ti-trash"></i>
                                    Hapus
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="6" class="text-center py-5">

                        <i class="ti ti-trash-off"
                           style="font-size:2rem;opacity:.3;"></i>

                        <div class="mt-2">
                            Trash promo kosong
                        </div>

                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection