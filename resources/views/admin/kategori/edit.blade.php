@extends('layouts.admin')

@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')

@section('content')
<div class="row justify-content-center">

    <div class="col-lg-7">

        <div class="card">

            <div class="card-header bg-white border-0 py-3 d-flex align-items-center gap-3">

                <a href="{{ route('admin.kategori.index') }}"
                   class="btn btn-sm btn-light">
                    <i class="ti ti-arrow-left"></i>
                </a>

                <h6 class="fw-semibold mb-0">
                    Edit Kategori: {{ $kategori->nama_kategori }}
                </h6>

            </div>

            <div class="card-body p-4">

                <form method="POST"
                      action="{{ route('admin.kategori.update', $kategori->id) }}">

                    @csrf
                    @method('PUT')

                    <div class="mb-4">

                        <label class="form-label fw-medium">
                            Nama Kategori <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="nama_kategori"
                               class="form-control @error('nama_kategori') is-invalid @enderror"
                               value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                               required>

                        @error('nama_kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="d-flex gap-2">

                        <button type="submit"
                                class="btn btn-primary flex-grow-1">

                            <i class="ti ti-device-floppy me-2"></i>
                            Simpan Perubahan

                        </button>

                        <a href="{{ route('admin.kategori.index') }}"
                           class="btn btn-outline-secondary">
                            Batal
                        </a>

                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection