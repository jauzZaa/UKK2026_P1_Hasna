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

                <div class="row g-3">
                    {{-- KOLOM KIRI --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Kode Alat <span class="text-danger">*</span></label>
                            <input type="text" name="code_slug"
                                class="form-control @error('code_slug') is-invalid @enderror"
                                value="{{ old('code_slug') }}" placeholder="Contoh: OBG-001">
                            @error('code_slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Alat <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="Masukkan nama alat">
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipe Item <span class="text-danger">*</span></label>
                            <select name="item_type" id="item_type"
                                class="form-control @error('item_type') is-invalid @enderror" required>
                                <option value="">--- Pilih Tipe ---</option>
                                <option value="single" {{ old('item_type') == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="bundle" {{ old('item_type') == 'bundle' ? 'selected' : '' }}>Bundle</option>
                            </select>
                            @error('item_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="category_id"
                                class="form-control @error('category_id') is-invalid @enderror" required>
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
                    </div>

                    {{-- KOLOM KANAN --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Lokasi</label>
                            <select name="location_code"
                                class="form-control @error('location_code') is-invalid @enderror">
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
                            <label class="form-label">Harga Beli <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" id="priceDisplay"
                                    class="form-control @error('price') is-invalid @enderror"
                                    placeholder="0"
                                    value="{{ old('price') ? number_format(old('price'), 0, ',', '.') : '' }}"
                                    inputmode="numeric">
                                <input type="hidden" name="price" id="priceValue" value="{{ old('price', 0) }}">
                                @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto Alat <span class="text-danger">*</span></label>
                            <input type="file" name="photo_path"
                                class="form-control @error('photo_path') is-invalid @enderror"
                                accept="image/*" id="inputFoto">
                            @error('photo_path')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-2">
                                <img id="previewFoto" src="#" alt="Preview"
                                    class="img-thumbnail d-none" style="max-height: 160px;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                            <textarea name="description"
                                class="form-control @error('description') is-invalid @enderror"
                                rows="4" placeholder="Masukkan deskripsi alat">{{ old('description') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- BUNDLE SECTION --}}
                @php $showBundle = old('item_type') == 'bundle'; @endphp
                <div id="bundleSection" class="mt-3" @if(!$showBundle) style="display:none;" @endif>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0">Bundle Tools</h5>
                        <button type="button" id="addBundleItem" class="btn btn-primary btn-sm">
                            <i class="mdi mdi-plus"></i> Add Bundle
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="bundleTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th style="width: 130px;">Qty</th>
                                    <th style="width: 200px;">Harga (Rp)</th>
                                    <th style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody id="bundleItems">
                                {{-- rows ditambah via JS --}}
                            </tbody>
                        </table>
                    </div>
                    <p id="emptyBundle" class="text-center text-muted py-2">Belum ada bundle item yang ditambahkan</p>
                </div>

                <div class="d-flex gap-2 mt-3">
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

<script>
    // Format harga utama
    const priceDisplay = document.getElementById('priceDisplay');
    const priceValue = document.getElementById('priceValue');
    priceDisplay.addEventListener('input', function() {
        let raw = this.value.replace(/\D/g, '');
        priceValue.value = raw || 0;
        this.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
    });

    // Tampil/sembunyikan section bundle
    document.getElementById('item_type').addEventListener('change', function() {
        document.getElementById('bundleSection').style.display =
            this.value === 'bundle' ? 'block' : 'none';
    });

    // Preview foto
    document.getElementById('inputFoto').addEventListener('change', function() {
        const preview = document.getElementById('previewFoto');
        if (this.files && this.files[0]) {
            preview.src = URL.createObjectURL(this.files[0]);
            preview.classList.remove('d-none');
        }
    });

    // Hitung total bundle
    function hitungTotal() {
        let total = 0;
        document.querySelectorAll('.bundle-price-value').forEach(function(el) {
            total += parseInt(el.value) || 0;
        });
    }

    // Cek empty
    function cekEmpty() {
        const rows = document.querySelectorAll('#bundleItems tr');
        document.getElementById('emptyBundle').style.display = rows.length === 0 ? 'block' : 'none';
        document.getElementById('bundleTable').style.display = rows.length === 0 ? 'none' : 'table';
    }

    // Buat baris bundle baru
    function newBundleRow() {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <input type="text" name="bundle_tool_name[]" class="form-control form-control-sm"
                    placeholder="Nama sub-alat">
            </td>
            <td>
                <input type="number" name="bundle_qty[]" class="form-control form-control-sm"
                    value="1" min="1">
            </td>
            <td>
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Rp</span>
                    <input type="text" class="form-control bundle-price-display" placeholder="0" inputmode="numeric">
                    <input type="hidden" name="bundle_tool_price[]" class="bundle-price-value" value="0">
                </div>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm btn-remove-bundle">
                    <i class="mdi mdi-delete"></i>
                </button>
            </td>
        `;
        return tr;
    }

    // Tambah baris bundle
    document.getElementById('addBundleItem').addEventListener('click', function() {
        document.getElementById('bundleItems').appendChild(newBundleRow());
        cekEmpty();
        hitungTotal();
    });

    // Hapus baris bundle
    document.getElementById('bundleItems').addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-remove-bundle');
        if (btn) {
            btn.closest('tr').remove();
            cekEmpty();
            hitungTotal();
        }
    });

    // Format harga bundle
    document.getElementById('bundleItems').addEventListener('input', function(e) {
        if (e.target.classList.contains('bundle-price-display')) {
            let raw = e.target.value.replace(/\D/g, '');
            const hidden = e.target.nextElementSibling;
            hidden.value = raw || 0;
            e.target.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
            hitungTotal();
        }
    });

    // Init
    cekEmpty();
</script>
@endsection