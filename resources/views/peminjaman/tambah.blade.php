@extends('layouts.app')

@section('title', 'Ajukan Peminjaman')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Peminjaman</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('peminjaman.tampil') }}">Riwayat</a></li>
                    <li class="breadcrumb-item active">Ajukan Peminjaman</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title mb-0">Ajukan Peminjaman</h4>
                <a href="{{ route('peminjaman.tampil') }}" class="btn btn-sm btn-secondary-subtle">
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

            <form action="{{ route('peminjaman.store') }}" method="POST">
                @csrf

                <div class="row g-3">

                    {{-- KOLOM KIRI --}}
                    <div class="col-md-6">

                        <div class="mb-3">
                            <label class="form-label">Alat <span class="text-danger">*</span></label>
                            <select name="tool_id" id="tool_id"
                                class="form-control @error('tool_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Alat --</option>
                                @foreach ($alat as $a)
                                <option value="{{ $a->id }}" {{ old('tool_id') == $a->id ? 'selected' : '' }}>
                                    {{ $a->name }} ({{ $a->item_type == 'bundle' ? 'Bundle' : 'Single' }})
                                </option>
                                @endforeach
                            </select>
                            @error('tool_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Unit <span class="text-danger">*</span></label>
                            <select name="unit_code" id="unit_code"
                                class="form-control @error('unit_code') is-invalid @enderror" required>
                                <option value="">-- Pilih Unit --</option>
                            </select>
                            <small class="text-muted" id="unitInfo">Pilih alat terlebih dahulu</small>
                            @error('unit_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Pinjam <span class="text-danger">*</span></label>
                            <input type="date" name="loan_date"
                                class="form-control @error('loan_date') is-invalid @enderror"
                                value="{{ old('loan_date') }}"
                                min="{{ date('Y-m-d') }}">
                            @error('loan_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Wajib Kembali <span class="text-danger">*</span></label>
                            <input type="date" name="due_date"
                                class="form-control @error('due_date') is-invalid @enderror"
                                value="{{ old('due_date') }}">
                            @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tujuan --}}
                        <div class="mb-3">
                            <label class="form-label">Tujuan Peminjaman <span class="text-danger">*</span></label>
                            <textarea name="purpose"
                                class="form-control @error('purpose') is-invalid @enderror"
                                rows="3"
                                placeholder="Jelaskan tujuan/keperluan peminjaman alat ini...">{{ old('purpose') }}</textarea>
                            @error('purpose')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    {{-- KOLOM KANAN --}}
                    <div class="col-md-6">

                        {{-- Info Alat --}}
                        <div id="infoAlat" class="mb-3 d-none">
                            <label class="form-label">Info Alat</label>
                            <div class="card border bg-light">
                                <div class="card-body py-2 px-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <img id="fotoAlat" src="#" alt="Foto"
                                            class="rounded" style="width:70px; height:70px; object-fit:cover;">
                                        <div>
                                            <div class="fw-semibold" id="namaAlat">-</div>
                                            <small class="text-muted" id="kategoriAlat">-</small><br>
                                            <small class="text-muted" id="lokasiAlat">-</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan <span class="text-muted"></span></label>
                            <textarea name="user_notes"
                                class="form-control @error('user_notes') is-invalid @enderror"
                                rows="4"
                                placeholder="Tambahkan catatan tambahan jika diperlukan...">{{ old('user_notes') }}</textarea>
                            @error('user_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                <div class="d-flex gap-2 mt-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-send"></i> Ajukan
                    </button>
                    <a href="{{ route('peminjaman.tampil') }}" class="btn btn-secondary">
                        <i class="mdi mdi-close"></i> Batal
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    const alatData = @json($alat -> load('category', 'lokasi') -> keyBy('id'));

    const toolSelect = document.getElementById('tool_id');
    const unitSelect = document.getElementById('unit_code');
    const unitInfo = document.getElementById('unitInfo');
    const infoAlat = document.getElementById('infoAlat');
    const fotoAlat = document.getElementById('fotoAlat');
    const namaAlat = document.getElementById('namaAlat');
    const kategoriAlat = document.getElementById('kategoriAlat');
    const lokasiAlat = document.getElementById('lokasiAlat');

    toolSelect.addEventListener('change', function() {
        const toolId = this.value;
        unitSelect.innerHTML = '<option value="">-- Memuat... --</option>';
        infoAlat.classList.add('d-none');

        if (!toolId) {
            unitSelect.innerHTML = '<option value="">-- Pilih Unit --</option>';
            unitInfo.textContent = 'Pilih alat terlebih dahulu';
            return;
        }

        const a = alatData[toolId];
        if (a) {
            namaAlat.textContent = a.name;
            kategoriAlat.textContent = 'Kategori: ' + (a.category?.name ?? '-');
            lokasiAlat.textContent = 'Lokasi: ' + (a.lokasi?.name ?? '-');
            fotoAlat.src = a.photo_path ? '/storage/' + a.photo_path : '';
            infoAlat.classList.remove('d-none');
        }

        fetch(`/peminjaman/units/${toolId}`)
            .then(res => res.json())
            .then(units => {
                unitSelect.innerHTML = '<option value="">-- Pilih Unit --</option>';
                if (units.length === 0) {
                    unitSelect.innerHTML = '<option value="">Tidak ada unit tersedia</option>';
                    unitInfo.textContent = 'Semua unit sedang dipinjam';
                } else {
                    units.forEach(u => {
                        const opt = document.createElement('option');
                        opt.value = u.code;
                        opt.textContent = u.code + (u.notes ? ' — ' + u.notes : '');
                        unitSelect.appendChild(opt);
                    });
                    unitInfo.textContent = units.length + ' unit tersedia';
                }
            });
    });

    document.querySelector('[name="loan_date"]').addEventListener('change', function() {
        const due = document.querySelector('[name="due_date"]');
        due.min = this.value;
        if (due.value && due.value <= this.value) due.value = '';
    });
</script>
@endsection