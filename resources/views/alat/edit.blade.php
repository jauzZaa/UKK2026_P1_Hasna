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
                            <select name="item_type" id="item_type"
                                class="form-control @error('item_type') is-invalid @enderror" required>
                                <option value="single" {{ old('item_type', $alat->item_type) == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="bundle" {{ old('item_type', $alat->item_type) == 'bundle' ? 'selected' : '' }}>Bundle</option>
                            </select>
                            @error('item_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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

                {{-- BUNDLE SECTION (Read Only dengan Warna Default) --}}
                @if($alat->item_type == 'bundle')
                <hr class="my-4">
                <div id="bundleSection">
                    <div class="alert alert-light border d-flex align-items-center shadow-sm mb-4">
                        <i class="mdi mdi-information-outline text-info me-2 fs-4"></i>
                        <div class="text-muted">Data isi bundle bersifat permanen dan tidak dapat diubah di sini.</div>
                    </div>

                    <h5 class="mb-3">Isi Bundle Alat</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            {{-- Header Default (Abu-abu sangat muda khas Bootstrap) --}}
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3">Nama Sub-Alat</th>
                                    <th class="py-3" style="width: 100px;">Qty</th>
                                    <th class="py-3" style="width: 200px;">Harga Satuan</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alat->bundleItems as $item)
                                <tr>
                                    <td class="fw-semibold">{{ $item->name }}</td>
                                    <td>{{ $item->pivot->qty }}</td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>

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
    // Format harga utama
    const priceDisplay = document.getElementById('priceDisplay');
    const priceValue = document.getElementById('priceValue');
    priceDisplay.addEventListener('input', function() {
        let raw = this.value.replace(/\D/g, '');
        priceValue.value = raw || 0;
        this.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
    });

    // Preview foto baru
    document.getElementById('inputFoto').addEventListener('change', function() {
        const preview = document.getElementById('previewFoto');
        if (this.files && this.files[0]) {
            preview.src = URL.createObjectURL(this.files[0]);
            preview.classList.remove('d-none');
        }
    });
</script>
@endsection