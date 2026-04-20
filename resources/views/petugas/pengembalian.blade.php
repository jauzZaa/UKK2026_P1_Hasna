@extends('layouts.app')

@section('title', 'Data Pengembalian')

@section('content')

@php $role = strtolower(auth()->user()->role); @endphp

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

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="mdi mdi-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title mb-0">Daftar Pengembalian</h4>
                <form method="GET" action="{{ route('petugas.pengembalian') }}">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control"
                            placeholder="Cari peminjam / alat..."
                            value="{{ request('search') }}"
                            style="width: 200px;">
                        <button type="submit" class="btn btn-secondary">
                            <i class="mdi mdi-magnify"></i>
                        </button>
                        @if(request('search'))
                        <a href="{{ route('petugas.pengembalian') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-close"></i>
                        </a>
                        @endif
                    </div>
                </form>
            </div>


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
                            @if($role != 'user')
                            <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataReturning as $index => $p)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-semibold">{{ $p->peminjam->detail->name ?? '-' }}</div>
                                <small class="text-muted">{{ $p->peminjam->email ?? '' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $p->alat->name ?? '-' }}</div>
                                <small>
                                    <span class="badge bg-{{ ($p->alat->item_type ?? '') === 'bundle' ? 'info text-dark' : 'primary' }}">
                                        {{ ($p->alat->item_type ?? '') === 'bundle' ? 'Bundle' : 'Single' }}
                                    </span>
                                </small>
                            </td>
                            <td><code>{{ $p->unit_code }}</code></td>
                            <td>{{ \Carbon\Carbon::parse($p->loan_date)->format('d M Y') }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($p->due_date)->format('d M Y') }}
                                @if(\Carbon\Carbon::today()->gt(\Carbon\Carbon::parse($p->due_date)))
                                <br><span class="badge bg-danger">Terlambat {{ \Carbon\Carbon::parse($p->due_date)->diffInDays(\Carbon\Carbon::today()) }} hari</span>
                                @endif
                            </td>
                            <td><span class="badge bg-warning text-dark">Menunggu Konfirmasi</span></td>
                            @if($role != 'user')
                            <td>
                                <button type="button" class="btn btn-sm btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalKonfirmasi{{ $p->id }}">
                                    <i class="mdi mdi-check-circle"></i> Konfirmasi
                                </button>
                            </td>
                            @endif
                        </tr>

                        {{-- MODAL KONFIRMASI --}}
                        @if($role != 'user')
                        <div class="modal fade" id="modalKonfirmasi{{ $p->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('pengembalian.konfirmasi', $p->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title"><i class="mdi mdi-check-circle"></i> Konfirmasi Pengembalian</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Konfirmasi pengembalian <strong>{{ $p->alat->name ?? '-' }}</strong> dari <strong>{{ $p->peminjam->detail->name ?? $p->peminjam->email }}</strong>?</p>
                                            <div class="mb-3">
                                                <label class="form-label">Kondisi Alat <span class="text-danger">*</span></label>
                                                <select name="conditions" class="form-control" required>
                                                    <option value="">--- Pilih Kondisi ---</option>
                                                    <option value="good">Baik</option>
                                                    <option value="broken">Rusak</option>
                                                    <option value="maintenance">Maintenance</option>
                                                </select>
                                            </div>
                                            <div class="mb-3" id="dendaInfo{{ $p->id }}" style="display:none;">
                                                <label class="form-label">Keterangan Denda <span class="text-danger">*</span></label>
                                                <textarea name="denda_info" class="form-control" rows="2" placeholder="Jelaskan kerusakan dan nominal denda..."></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Catatan</label>
                                                <textarea name="notes" class="form-control" rows="2" placeholder="Catatan tambahan..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-check"></i> Konfirmasi</button>
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
        </div>
    </div>

    {{-- TABEL DENDA --}}
    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-3">Daftar Pengembalian Denda</h4>
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-danger">
                        <tr>
                            <th>No</th>
                            @if($role != 'user')<th>Peminjam</th>@endif
                            <th>Alat</th>
                            <th>Unit</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Keterangan Denda</th>
                            <th>Status</th>
                            @if($role != 'user')<th>Aksi</th>@endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataDenda as $index => $p)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            @if($role != 'user')
                            <td>
                                <div class="fw-semibold">{{ $p->peminjam->detail->name ?? '-' }}</div>
                                <small class="text-muted">{{ $p->peminjam->email ?? '' }}</small>
                            </td>
                            @endif
                            <td>
                                <div class="fw-semibold">{{ $p->alat->name ?? '-' }}</div>
                                <small>
                                    <span class="badge bg-{{ ($p->alat->item_type ?? '') === 'bundle' ? 'info text-dark' : 'primary' }}">
                                        {{ ($p->alat->item_type ?? '') === 'bundle' ? 'Bundle' : 'Single' }}
                                    </span>
                                </small>
                            </td>
                            <td><code>{{ $p->unit_code }}</code></td>
                            <td>{{ \Carbon\Carbon::parse($p->loan_date)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->due_date)->format('d M Y') }}</td>
                            <td>
                                @if($p->pengembalian && $p->pengembalian->notes)
                                <div class="alert alert-danger py-2 mb-0">
                                    <i class="mdi mdi-alert me-1"></i>
                                    {{ str_replace('[DENDA] ', '', $p->pengembalian->notes) }}
                                </div>
                                @else
                                <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                @if($p->status === 'fined')
                                <span class="badge bg-danger">Kena Denda</span>
                                @elseif($p->status === 'fine_pending')
                                <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                                @endif
                            </td>
                            @if($role != 'user')
                            <td>
                                @if($p->status === 'fine_pending')
                                <form action="{{ route('pengembalian.konfirmasi-bayar', $p->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Konfirmasi pembayaran denda ini?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="mdi mdi-check"></i> Konfirmasi Bayar
                                    </button>
                                </form>
                                @else
                                <span class="text-muted small">Menunggu user lapor</span>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="mdi mdi-check-circle-outline fs-4 d-block mb-1"></i>
                                Tidak ada denda aktif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // Tampil field denda saat kondisi = broken
    document.querySelectorAll('select[name="conditions"]').forEach(function(select) {
        select.addEventListener('change', function() {
            const modalId = this.closest('.modal').id;
            const loanId = modalId.replace('modalKonfirmasi', '');
            const dendaDiv = document.getElementById('dendaInfo' + loanId);
            if (dendaDiv) {
                dendaDiv.style.display = this.value === 'broken' ? 'block' : 'none';
            }
        });
    });
</script>

@endsection