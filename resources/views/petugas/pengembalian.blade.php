@extends('layouts.app')

@section('title', 'Data Pengembalian')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Pengembalian</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Data Pengembalian</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-3">Daftar Pengembalian</h4>

            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="mdi mdi-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Peminjam</th>
                            <th>Alat</th>
                            <th>Unit</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Wajib Kembali</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $p)
                        @php
                        $isLate = \Carbon\Carbon::today()->gt(\Carbon\Carbon::parse($p->due_date));
                        @endphp
                        <tr class="{{ $isLate ? 'table-warning' : '' }}">
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-semibold">{{ $p->user->detail->name ?? '-' }}</div>
                                <small class="text-muted">{{ $p->user->email }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $p->alat->name ?? '-' }}</div>
                                <small>
                                    <span class="badge bg-{{ $p->alat->item_type === 'bundle' ? 'info text-dark' : 'primary' }}">
                                        {{ $p->alat->item_type === 'bundle' ? 'Bundle' : 'Single' }}
                                    </span>
                                </small>
                            </td>
                            <td><code>{{ $p->unit_code }}</code></td>
                            <td>{{ \Carbon\Carbon::parse($p->loan_date)->format('d M Y') }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($p->due_date)->format('d M Y') }}
                                @if ($isLate)
                                <br><span class="badge bg-danger">
                                    Terlambat {{ \Carbon\Carbon::parse($p->due_date)->diffInDays(\Carbon\Carbon::today()) }} hari
                                </span>
                                @endif
                            </td>
                            <td>
                                @if ($p->status === 'returning')
                                <span style="display:inline-flex; align-items:center; gap:5px;
                                    background-color:#EFF6FF; color:#3B82F6;
                                    border:1.5px solid #3B82F630; padding:4px 10px;
                                    border-radius:20px; font-size:0.78rem; font-weight:600;">
                                    <i class="mdi mdi-arrow-left-circle" style="font-size:0.9rem;"></i>
                                    Menunggu Konfirmasi
                                </span>
                                @else
                                <span style="display:inline-flex; align-items:center; gap:5px;
                                    background-color:#ECFDF5; color:#10B981;
                                    border:1.5px solid #10B98130; padding:4px 10px;
                                    border-radius:20px; font-size:0.78rem; font-weight:600;">
                                    <i class="mdi mdi-check-circle" style="font-size:0.9rem;"></i>
                                    Dipinjam
                                </span>
                                @endif
                            </td>
                            <td>
                                @if ($p->status === 'returning')
                                <button type="button" class="btn btn-sm btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalKonfirmasi{{ $p->id }}">
                                    <i class="mdi mdi-check-circle"></i> Konfirmasi
                                </button>
                                @else
                                <span class="text-muted small">Belum dikembalikan</span>
                                @endif
                            </td>
                        </tr>

                        {{-- MODAL KONFIRMASI --}}
                        @if ($p->status === 'returning')
                        <div class="modal fade" id="modalKonfirmasi{{ $p->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('pengembalian.konfirmasi', $p->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">
                                                <i class="mdi mdi-check-circle"></i> Konfirmasi Pengembalian
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Konfirmasi pengembalian <strong>{{ $p->alat->name ?? '-' }}</strong>
                                                (Unit: <code>{{ $p->unit_code }}</code>) dari
                                                <strong>{{ $p->user->detail->name ?? $p->user->email }}</strong>?
                                            </p>

                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Kondisi Alat <span class="text-danger">*</span></label>
                                                <select name="conditions" class="form-control" id="kondisi{{ $p->id }}" required
                                                    onchange="toggleBrokenForm({{ $p->id }}, this.value)">
                                                    <option value="">-- Pilih Kondisi --</option>
                                                    <option value="good">Baik</option>
                                                    <option value="broken">Rusak</option>
                                                    <option value="maintenance">Perlu Maintenance</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Catatan <span class="text-muted">(opsional)</span></label>
                                                <textarea name="notes" class="form-control" rows="2"
                                                    placeholder="Catatan kondisi alat..."></textarea>
                                            </div>

                                            {{-- Form tambahan jika broken --}}
                                            <div id="brokenForm{{ $p->id }}" class="d-none">
                                                <div class="alert alert-danger py-2">
                                                    <i class="mdi mdi-alert"></i>
                                                    <strong>Alat rusak!</strong> Unit akan otomatis dinonaktifkan dan tidak bisa dipinjam.
                                                </div>
                                            </div>

                                            {{-- Info jika maintenance --}}
                                            <div id="maintenanceForm{{ $p->id }}" class="d-none">
                                                <div class="alert alert-warning py-2">
                                                    <i class="mdi mdi-wrench"></i>
                                                    <strong>Perlu maintenance!</strong> Unit akan dinonaktifkan sementara.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="mdi mdi-check"></i> Ya, Konfirmasi
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif

                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="mdi mdi-inbox-outline fs-4 d-block mb-1"></i>
                                Tidak ada data pengembalian
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $data->links() }}
        </div>
    </div>
</div>

<script>
    function toggleBrokenForm(id, value) {
        document.getElementById('brokenForm' + id).classList.add('d-none');
        document.getElementById('maintenanceForm' + id).classList.add('d-none');

        if (value === 'broken') {
            document.getElementById('brokenForm' + id).classList.remove('d-none');
        } else if (value === 'maintenance') {
            document.getElementById('maintenanceForm' + id).classList.remove('d-none');
        }
    }
</script>
@endsection