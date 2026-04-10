@extends('layouts.app')

@section('title', 'Tambah Lokasi')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Crud Lokasi</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('lokasi.tampil') }}">Tampil</a></li>
                    <li class="breadcrumb-item active">Tambah Lokasi</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title mb-0">Tambah Lokasi</h4>
                <a href="{{ route('lokasi.tampil') }}" class="btn btn-sm btn-secondary-subtle">
                    <i class="mdi mdi-arrow-left"></i> Kembali
                </a>
            </div>

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('lokasi.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Kode Lokasi</label>
                    <input type="text" name="location_code" class="form-control @error('location_code') is-invalid @enderror"
                        value="{{ old('location_code') }}" placeholder="Masukkan kode lokasi">
                    @error('location_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Lokasi</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" placeholder="Masukkan nama lokasi">
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Detail</label>
                    <input type="text" name="detail" class="form-control @error('detail') is-invalid @enderror"
                        value="{{ old('detail') }}" placeholder="Masukkan detail lokasi">
                    @error('detail')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> Simpan
                    </button>
                    <a href="{{ route('lokasi.tampil') }}" class="btn btn-secondary">
                        <i class="mdi mdi-close"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection