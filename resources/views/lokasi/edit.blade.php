@extends('layouts.app')

@section('title', 'Edit Lokasi')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Crud Lokasi</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('lokasi.tampil') }}">Tampil</a></li>
                    <li class="breadcrumb-item active">Edit Lokasi</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title mb-0">Edit Lokasi</h4>
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

            <form action="{{ route('lokasi.update', $lokasi->location_code) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Kode Lokasi</label>
                    <input type="text" class="form-control" value="{{ $lokasi->location_code }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Lokasi</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $lokasi->name) }}" placeholder="Masukkan nama lokasi">
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Detail</label>
                    <input type="text" name="detail" class="form-control @error('detail') is-invalid @enderror"
                        value="{{ old('detail', $lokasi->detail) }}" placeholder="Masukkan detail lokasi">
                    @error('detail')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning">
                        <i class="mdi mdi-content-save-edit"></i> Update
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