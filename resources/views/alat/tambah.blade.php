@extends('layouts.app')

@section('title', 'Tambah Alat')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Crud Alat</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('alat.tampil') }}">Tampil</a></li>
                    <li class="breadcrumb-item active">Tambah Alat</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title mb-0">Tambah Alat</h4>
                <a href="{{ route('alat.tampil') }}" class="btn btn-sm btn-secondary-subtle">
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

            <form action="{{ route('alat.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Kode Alat</label>
                    <input type="text" name="code_slug" class="form-control @error('code_slug') is-invalid @enderror"
                        value="{{ old('code_slug') }}" placeholder="Masukkan kode alat">
                    @error('code_slug')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Alat</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" placeholder="Masukkan nama alat">
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                        <option value="">--- Pilih Kategori ---</option>
                        @foreach ($kategori as $k)
                        <option value="{{ $k->id }}" {{ old('category_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipe Item</label>
                    <select name="item_type" class="form-control @error('item_type') is-invalid @enderror" required>
                        <option value="">--- Pilih Tipe ---</option>
                        <option value="single" {{ old('item_type') == 'single'      ? 'selected' : '' }}>Single</option>
                        <option value="bundle" {{ old('item_type') == 'bundle'      ? 'selected' : '' }}>Bundle</option>
                        <option value="bundle_tool" {{ old('item_type') == 'bundle_tool' ? 'selected' : '' }}>Bundle Tool</option>
                    </select>
                    @error('item_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Lokasi</label>
                    <select name="location_code" class="form-control @error('location_code') is-invalid @enderror">
                        <option value="">--- Pilih Lokasi ---</option>
                        @foreach ($lokasi as $l)
                        <option value="{{ $l->location_code }}" {{ old('location_code') == $l->location_code ? 'selected' : '' }}>
                            {{ $l->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('location_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                        rows="3" placeholder="Masukkan deskripsi alat">{{ old('description') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Foto Alat</label>
                    <input type="file" name="photo_path" class="form-control @error('photo_path') is-invalid @enderror"
                        accept="image/*">
                    @error('photo_path')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> Simpan
                    </button>
                    <a href="{{ route('alat.tampil') }}" class="btn btn-secondary">
                        <i class="mdi mdi-close"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection