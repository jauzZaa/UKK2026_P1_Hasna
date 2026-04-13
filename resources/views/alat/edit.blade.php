@extends('layouts.app')

@section('title', 'Edit Alat')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Crud Alat</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('alat.tampil') }}">Tampil</a></li>
                    <li class="breadcrumb-item active">Edit Alat</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title mb-0">Edit Alat: {{ $alat->name }}</h4>
                <a href="{{ route('alat.tampil') }}" class="btn btn-sm btn-secondary-subtle">
                    <i class="mdi mdi-arrow-left"></i> Kembali
                </a>
            </div>

            <form action="{{ route('alat.update', $alat->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    {{-- KOLOM KIRI --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Kode Alat <span class="text-danger">*</span></label>
                            <input type="text" name="code_slug"
                                class="form-control @error('code_slug') is-invalid @enderror"
                                value="{{ old('code_slug', $alat->code_slug) }}" placeholder="Contoh: OBG-001">
                            @error('code_slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Alat <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $alat->name) }}" placeholder="Masukkan nama alat">
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipe Item <span class="text-danger">*</span></label>
                            <select name="item_type" id="item_type" class="form-control" required>
                                <option value="single" {{ $alat->item_type == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="bundle" {{ $alat->item_type == 'bundle' ? 'selected' : '' }}>Bundle</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="category_id"
                                class="form-control @error('category_id') is-invalid @enderror" required>
                                @foreach ($kategori as $k)
                                <option value="{{ $k->id }}" {{ old('category_id', $alat->category_id) == $k->id ? 'selected' : '' }}>
                                    {{ $k->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- KOLOM KANAN --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Lokasi</label>
                            <select name="location_code"
                                class="form-control @error('location_code') is-invalid @enderror">
                                <option value="">--- Pilih Lokasi ---</option>
                                @foreach ($lokasi as $l)
                                <option value="{{ $l->location_code }}" {{ old('location_code', $alat->location_code) == $l->location_code ? 'selected' : '' }}>
                                    {{ $l->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Harga Beli <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" id="priceDisplay"
                                    class="form-control @error('price') is-invalid @enderror"
                                    value="{{ number_format(old('price', $alat->price), 0, ',', '.') }}"
                                    inputmode="numeric">
                                <input type="hidden" name="price" id="priceValue" value="{{ old('price', $alat->price) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto Alat</label>
                            @if($alat->photo_path)
                            <div class="mb-2">
                                <img id="previewFoto" src="{{ asset('storage/' . $alat->photo_path) }}"
                                    class="img-thumbnail" style="max-height: 100px;">
                            </div>
                            @endif
                            <input type="file" name="photo_path" id="inputFoto"
                                class="form-control @error('photo_path') is-invalid @enderror" accept="image/*">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                            <textarea name="description"
                                class="form-control @error('description') is-invalid @enderror"
                                rows="3">{{ old('description', $alat->description) }}</textarea>
                        </div>
                    </div>
                </div>

                @if($alat->item_type == 'bundle')
                <hr class="my-4">
                <div id="bundleSection">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Isi Bundle Alat</h5>
                        <button type="button" class="btn btn-sm btn-primary" id="addBundleItem">
                            <i class="mdi mdi-plus"></i> Tambah Baris
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="bundleTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Sub-Alat</th>
                                    <th style="width: 120px;">Qty</th>
                                    <th style="width: 200px;">Harga Satuan (Rp)</th>
                                    <th style="width: 50px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alat->bundleItems as $item)
                                <tr>
                                    <td>
                                        <input type="text" name="bundle_names[]" class="form-control" value="{{ $item->name }}" placeholder="Nama alat..." required>
                                    </td>
                                    <td>
                                        <input type="number" name="bundle_qtys[]" class="form-control" value="{{ $item->pivot->qty }}" min="1" required>
                                    </td>
                                    <td>
                                        <input type="number" name="bundle_prices[]" class="form-control" value="{{ $item->price }}" placeholder="Harga..." required>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-row"><i class="mdi mdi-trash-can"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-warning">
                        <i class="mdi mdi-content-save-edit"></i> Update Data
                    </button>
                    <a href="{{ route('alat.tampil') }}" class="btn btn-secondary">
                        <i class="mdi mdi-close"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Script untuk menambah baris bundle secara manual
    document.getElementById('addBundleItem')?.addEventListener('click', function() {
        const tableBody = document.querySelector('#bundleTable tbody');
        const newRow = document.createElement('tr');

        newRow.innerHTML = `
            <td>
                <input type="text" name="bundle_names[]" class="form-control" placeholder="Nama alat..." required>
            </td>
            <td>
                <input type="number" name="bundle_qtys[]" class="form-control" value="1" min="1" required>
            </td>
            <td>
                <input type="number" name="bundle_prices[]" class="form-control" placeholder="Harga..." required>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-row"><i class="mdi mdi-trash-can"></i></button>
            </td>
        `;
        tableBody.appendChild(newRow);
    });

    // Delegasi event untuk hapus baris
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-row')) {
            e.target.closest('tr').remove();
        }
    });
</script>
@endsection