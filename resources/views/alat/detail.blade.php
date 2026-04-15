@extends('layouts.app')

@section('title', 'Detail Alat')

@section('content')

@php $role = strtolower(auth()->user()->role); @endphp

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Detail Alat</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('alat.tampil') }}">Tampil</a></li>
                    <li class="breadcrumb-item active">Detail Alat</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12">

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Card Info Alat -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex align-items-start gap-4">
                <div style="width: 120px; height: 120px; flex-shrink: 0;">
                    @if ($alat->photo_path)
                    <img src="{{ asset('storage/' . $alat->photo_path) }}"
                        style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                    @else
                    <div class="bg-light d-flex align-items-center justify-content-center rounded"
                        style="width: 120px; height: 120px;">
                        <i class="mdi mdi-image-off text-muted" style="font-size: 40px;"></i>
                    </div>
                    @endif
                </div>

                <div class="flex-grow-1">
                    <h4 class="mb-1">{{ $alat->name }}</h4>
                    <p class="text-muted mb-3">ID: #TL-{{ str_pad($alat->id, 3, '0', STR_PAD_LEFT) }}</p>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Kategori</small>
                            <span class="badge bg-primary-subtle text-primary">
                                {{ $alat->category->name ?? '-' }}
                            </span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Tipe Alat</small>
                            @if ($alat->item_type == 'single')
                            <span class="badge bg-info-subtle text-info">Single</span>
                            @elseif ($alat->item_type == 'bundle')
                            <span class="badge bg-warning-subtle text-warning">Bundle</span>
                            @elseif ($alat->item_type == 'bundle_tool')
                            <span class="badge bg-secondary-subtle text-secondary">Bundle Tool</span>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Lokasi</small>
                            <span>{{ $alat->lokasi->name ?? '-' }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Kode</small>
                            <span>{{ $alat->code_slug ?? '-' }}</span>
                        </div>
                        <div class="col-12 mb-3">
                            <small class="text-muted d-block">Deskripsi</small>
                            <span>{{ $alat->description ?? '-' }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Tanggal Dibuat</small>
                            <span>{{ \Carbon\Carbon::parse($alat->created_at)->format('d M Y') }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Total Unit</small>
                            <span>{{ $alat->units->count() }} Unit</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Daftar Unit -->
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title mb-0">Daftar Unit Alat</h4>
                @if($role == 'admin')
                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahUnit">
                    <i class="mdi mdi-plus"></i> Tambah Unit
                </button>
                @endif
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode Unit</th>
                            <th>Status</th>
                            <th>Kondisi</th>
                            <th>Catatan</th>
                            @if($role == 'admin')
                            <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($alat->units as $index => $unit)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $unit->code }}</td>
                            <td>
                                @if ($unit->status == 'available')
                                <span class="badge bg-success">Tersedia</span>
                                @elseif ($unit->status == 'lent')
                                <span class="badge bg-warning text-dark">Dipinjam</span>
                                @elseif ($unit->status == 'nonactive')
                                <span class="badge bg-danger">Nonaktif</span>
                                @else
                                <span class="badge bg-light text-dark">-</span>
                                @endif
                            </td>
                            <td>{{ $unit->latestCondition->conditions ?? '-' }}</td>
                            <td>{{ $unit->notes ?? '-' }}</td>
                            @if($role == 'admin')
                            <td>
                                <button type="button" class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditUnit"
                                    data-code="{{ $unit->code }}"
                                    data-status="{{ $unit->status }}"
                                    data-notes="{{ $unit->notes }}"
                                    data-conditions="{{ $unit->latestCondition->conditions ?? '' }}"
                                    data-condition-notes="{{ $unit->latestCondition->notes ?? '' }}">
                                    <i class="mdi mdi-pencil"></i> Edit
                                </button>
                                <form action="{{ route('unit.destroy', $unit->code) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin hapus unit ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger ms-1">
                                        <i class="mdi mdi-trash-can"></i> Hapus
                                    </button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $role == 'admin' ? 6 : 5 }}" class="text-center text-muted py-3">
                                Belum ada unit untuk alat ini
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <a href="{{ route('alat.tampil') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

@if($role == 'admin')
<!-- Modal Tambah Unit -->
<div class="modal fade" id="modalTambahUnit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Unit Alat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('unit.store') }}" method="POST">
                @csrf
                <input type="hidden" name="tool_id" value="{{ $alat->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Unit</label>
                        <input type="text" name="code" class="form-control bg-light"
                            value="{{ $alat->code_slug }}-{{ str_pad($alat->units->count() + 1, 3, '0', STR_PAD_LEFT) }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="">--- Pilih Status ---</option>
                            <option value="available">Tersedia</option>
                            <option value="nonactive">Nonaktif</option>
                            <option value="lent">Dipinjam</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Catatan tambahan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="mdi mdi-content-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Unit -->
<div class="modal fade" id="modalEditUnit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Unit Alat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditUnit" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Unit</label>
                        <input type="text" id="editCode" class="form-control" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="editStatus" class="form-control" required>
                            <option value="">--- Pilih Status ---</option>
                            <option value="available">Tersedia</option>
                            <option value="nonactive">Nonaktif</option>
                            <option value="lent">Dipinjam</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" id="editNotes" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kondisi Baru <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                        <select name="conditions" id="editConditions" class="form-control">
                            <option value="">--- Pilih Kondisi ---</option>
                            <option value="good">Baik</option>
                            <option value="broken">Rusak</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan Kondisi</label>
                        <textarea name="condition_notes" id="editConditionNotes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="mdi mdi-content-save-edit"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('modalEditUnit').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const code = button.getAttribute('data-code');
        const status = button.getAttribute('data-status');
        const notes = button.getAttribute('data-notes');
        const conditions = button.getAttribute('data-conditions');
        const conditionNotes = button.getAttribute('data-condition-notes');

        document.getElementById('editCode').value = code;
        document.getElementById('editStatus').value = status;
        document.getElementById('editNotes').value = notes;
        document.getElementById('editConditions').value = conditions;
        document.getElementById('editConditionNotes').value = conditionNotes;

        document.getElementById('formEditUnit').action = '/unit/update/' + code;
    });
</script>
@endif

@endsection