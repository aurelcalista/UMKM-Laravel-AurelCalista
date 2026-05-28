@extends('layouts.admin')

@section('title', 'Promo')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div class="page-header mb-0">
        <h1>Promo</h1>
        <p>Kelola promo dan diskon produk</p>
    </div>

    <button class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#addPromoModal">

        <i class="ti ti-plus"></i>
        Tambah Promo
    </button>

</div>

{{-- ALERT PROMO EXPIRED --}}
@foreach($promos as $promo)

    @if(!$promo->status)

    <div class="alert alert-danger">
        ❌ Promo <strong>{{ $promo->nama_promo }}</strong>
        sudah kadaluarsa
    </div>

    @endif

@endforeach

<div class="card border-0 shadow-sm">

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>Banner</th>
                        <th>Promo</th>
                        <th>Diskon</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($promos as $promo)

                    <tr>

                        <td>

                            @if($promo->banner)

                                <img src="{{ asset('storage/' . $promo->banner) }}"
                                     width="70"
                                     height="50"
                                     style="object-fit:cover;border-radius:10px;">

                            @else

                                <div class="bg-light d-flex align-items-center justify-content-center"
                                     style="width:70px;height:50px;border-radius:10px;">

                                    <i class="ti ti-photo text-muted"></i>

                                </div>

                            @endif

                        </td>

                        <td>

                            <div class="fw-semibold">
                                {{ $promo->nama_promo }}
                            </div>

                            <div class="small text-muted">
                                {{ $promo->deskripsi }}
                            </div>

                        </td>

                        <td>

                            <span class="badge bg-danger">
                                {{ $promo->diskon }}% OFF
                            </span>

                        </td>

                        <td>

                            <div>
                                {{ \Carbon\Carbon::parse($promo->tanggal_mulai)->format('d M Y') }}
                            </div>

                            <small class="text-muted">
                                sampai
                                {{ \Carbon\Carbon::parse($promo->tanggal_selesai)->format('d M Y') }}
                            </small>

                        </td>

                        <td>

                            @if($promo->status)

                                <span class="badge bg-success">
                                    Aktif
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Expired
                                </span>

                            @endif

                        </td>

                        <td>

                            <div class="d-flex gap-2">

                                {{-- EDIT --}}
                                <button class="btn btn-warning btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editPromo{{ $promo->id }}">

                                    <i class="ti ti-edit"></i>
                                </button>

                                {{-- DELETE --}}
                                <button type="button"
                                        class="btn btn-danger btn-sm btn-delete-promo"
                                        data-id="{{ $promo->id }}"
                                        data-name="{{ $promo->nama_promo }}">

                                    <i class="ti ti-trash"></i>

                                </button>

                                <form id="delete-promo-{{ $promo->id }}"
                                    action="{{ route('admin.promo.destroy', $promo->id) }}"
                                    method="POST"
                                    style="display:none;">

                                    @csrf
                                    @method('DELETE')

                                </form>

                            </div>

                        </td>

                    </tr>

                    {{-- MODAL EDIT --}}
                    <div class="modal fade"
                         id="editPromo{{ $promo->id }}"
                         tabindex="-1">

                        <div class="modal-dialog modal-lg">

                            <form action="{{ route('admin.promo.update', $promo->id) }}"
                                  method="POST"
                                  enctype="multipart/form-data">

                                @csrf
                                @method('PUT')

                                <div class="modal-content">

                                    <div class="modal-header">

                                        <h5 class="modal-title">
                                            Edit Promo
                                        </h5>

                                        <button type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">

                                        <div class="mb-3">
                                            <label class="form-label">
                                                Nama Promo
                                            </label>

                                            <input type="text"
                                                   name="nama_promo"
                                                   class="form-control"
                                                   value="{{ $promo->nama_promo }}">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">
                                                Diskon (%)
                                            </label>

                                            <input type="number"
                                                   name="diskon"
                                                   class="form-control"
                                                   value="{{ $promo->diskon }}">
                                        </div>

                                        <div class="row">

                                            <div class="col-md-6">

                                                <div class="mb-3">
                                                    <label class="form-label">
                                                        Tanggal Mulai
                                                    </label>

                                                    <input type="date"
                                                           name="tanggal_mulai"
                                                           class="form-control"
                                                           value="{{ $promo->tanggal_mulai }}">
                                                </div>

                                            </div>

                                            <div class="col-md-6">

                                                <div class="mb-3">
                                                    <label class="form-label">
                                                        Tanggal Selesai
                                                    </label>

                                                    <input type="date"
                                                           name="tanggal_selesai"
                                                           class="form-control"
                                                           value="{{ $promo->tanggal_selesai }}">
                                                </div>

                                            </div>

                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">
                                                Deskripsi
                                            </label>

                                            <textarea name="deskripsi"
                                                      class="form-control"
                                                      rows="3">{{ $promo->deskripsi }}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">
                                                Banner
                                            </label>

                                            <input type="file"
                                                   name="banner"
                                                   class="form-control">
                                        </div>

                                    </div>

                                    <div class="modal-footer">

                                        <button class="btn btn-primary">
                                            Update Promo
                                        </button>

                                    </div>

                                </div>

                            </form>

                        </div>

                    </div>

                    @empty

                    <tr>
                        <td colspan="6" class="text-center py-5">

                            <i class="ti ti-discount-off"
                               style="font-size:2rem;opacity:.3;"></i>

                            <div class="mt-2">
                                Belum ada promo
                            </div>

                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade"
     id="addPromoModal"
     tabindex="-1">

    <div class="modal-dialog modal-lg">

        <form action="{{ route('admin.promo.store') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Tambah Promo
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">
                            Nama Promo
                        </label>

                        <input type="text"
                               name="nama_promo"
                               class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Diskon (%)
                        </label>

                        <input type="number"
                               name="diskon"
                               class="form-control">
                    </div>

                    <div class="row">

                        <div class="col-md-6">

                            <div class="mb-3">
                                <label class="form-label">
                                    Tanggal Mulai
                                </label>

                                <input type="date"
                                       name="tanggal_mulai"
                                       class="form-control">
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="mb-3">
                                <label class="form-label">
                                    Tanggal Selesai
                                </label>

                                <input type="date"
                                       name="tanggal_selesai"
                                       class="form-control">
                            </div>

                        </div>

                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Deskripsi
                        </label>

                        <textarea name="deskripsi"
                                  class="form-control"
                                  rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Banner Promo
                        </label>

                        <input type="file"
                               name="banner"
                               class="form-control">
                    </div>

                </div>

                <div class="modal-footer">

                    <button class="btn btn-primary">

                        <i class="ti ti-device-floppy"></i>
                        Simpan Promo

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>
<script>

document.querySelectorAll('.btn-delete-promo').forEach(button => {

    button.addEventListener('click', function () {

        let id   = this.dataset.id;
        let name = this.dataset.name;

        Swal.fire({
            title: 'Pindahkan ke trash?',
            html: `Promo <b>${name}</b> akan dipindahkan ke trash.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {

            if (result.isConfirmed) {
                document.getElementById('delete-promo-' + id).submit();
            }

        });

    });

});

</script>
@endsection