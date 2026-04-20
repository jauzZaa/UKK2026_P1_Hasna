@extends('layouts.app')

@section('title', auth()->user()->role === 'User' ? 'Riwayat Peminjaman' : 'Kelola Pengajuan')

@section('content')

@php
$role = auth()->user()->role;
$allData = $data;
$activeTab = request('status', 'all');
@endphp

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Peminjaman</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">
                        {{ $role === 'User' ? 'Riwayat Peminjaman' : 'Kelola Pengajuan' }}
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">


            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title mb-0">
                    {{ $role === 'User' ? 'Riwayat Peminjaman Saya' : 'Daftar Pengajuan Peminjaman' }}
                </h4>
                <div class="d-flex gap-2 align-items-center">

                    <form method="GET" action="{{ route('peminjaman.tampil') }}" class="d-flex">
                        @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                    </form>
                    @if($role === 'User')
                    <a href="{{ route('peminjaman.tambah') }}" class="btn btn-sm btn-primary">
                        <i class="mdi mdi-plus"></i> Ajukan Peminjaman
                    </a>
                    @endif
                </div>
            </div>


            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="mdi mdi-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="mdi mdi-alert-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif


            <style>
                .filter-tab {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    padding: 7px 16px;
                    border-radius: 999px;
                    font-size: 0.82rem;
                    font-weight: 600;
                    text-decoration: none;
                    transition: all 0.2s;
                }

                .filter-tab .tab-count {
                    border-radius: 999px;
                    padding: 1px 7px;
                    font-size: 0.75rem;
                    font-weight: 700;
                    min-width: 20px;
                    text-align: center;
                    color: #fff;
                }

                .tab-all {
                    background: #EEF2FF;
                    color: #6366F1;
                    border: 1.5px solid #6366F150;
                }

                .tab-all.active {
                    background: #6366F1;
                    color: #fff;
                    border-color: #6366F1;
                    box-shadow: 0 2px 8px #6366F155;
                }

                .tab-all .tab-count {
                    background: #6366F1;
                }

                .tab-all.active .tab-count {
                    background: rgba(255, 255, 255, 0.25);
                }

                .tab-pending {
                    background: #FFF8E1;
                    color: #F59E0B;
                    border: 1.5px solid #F59E0B50;
                }

                .tab-pending.active {
                    background: #F59E0B;
                    color: #fff;
                    border-color: #F59E0B;
                }

                .tab-pending .tab-count {
                    background: #F59E0B;
                }

                .tab-pending.active .tab-count {
                    background: rgba(255, 255, 255, 0.25);
                }

                .tab-active {
                    background: #ECFDF5;
                    color: #10B981;
                    border: 1.5px solid #10B98150;
                }

                .tab-active.active {
                    background: #10B981;
                    color: #fff;
                    border-color: #10B981;
                }

                .tab-active .tab-count {
                    background: #10B981;
                }

                .tab-active.active .tab-count {
                    background: rgba(255, 255, 255, 0.25);
                }

                .tab-rejected {
                    background: #FEF2F2;
                    color: #EF4444;
                    border: 1.5px solid #EF444450;
                }

                .tab-rejected.active {
                    background: #EF4444;
                    color: #fff;
                    border-color: #EF4444;
                }

                .tab-rejected .tab-count {
                    background: #EF4444;
                }

                .tab-rejected.active .tab-count {
                    background: rgba(255, 255, 255, 0.25);
                }

                .tab-closed {
                    background: #F3F4F6;
                    color: #6B7280;
                    border: 1.5px solid #6B728050;
                }

                .tab-closed.active {
                    background: #6B7280;
                    color: #fff;
                    border-color: #6B7280;
                }

                .tab-closed .tab-count {
                    background: #6B7280;
                }

                .tab-closed.active .tab-count {
                    background: rgba(255, 255, 255, 0.25);
                }

                .tab-returning {
                    background: #EFF6FF;
                    color: #3B82F6;
                    border: 1.5px solid #3B82F650;
                }

                .tab-returning.active {
                    background: #3B82F6;
                    color: #fff;
                    border-color: #3B82F6;
                }

                .tab-returning .tab-count {
                    background: #3B82F6;
                }

                .tab-returning.active .tab-count {
                    background: rgba(255, 255, 255, 0.25);
                }
            </style>

            <div class="d-flex gap-2 mb-3 flex-wrap">
                @php
                $tabs = ['all' => 'Semua', 'pending' => 'Menunggu', 'active' => 'Disetujui', 'returning' => 'Dikembalikan', 'rejected' => 'Ditolak', 'closed' => 'Selesai'];
                @endphp
                @foreach ($tabs as $key => $label)
                @php
                $count = $key !== 'all' ? $allData->where('status', $key)->count() : $allData->count();
                $isActive = $activeTab === $key ? 'active' : '';
                @endphp
                <a href="{{ request()->fullUrlWithQuery(['status' => $key]) }}"
                    class="filter-tab tab-{{ $key }} {{ $isActive }}">
                    {{ $label }}
                    <span class="tab-count">{{ $count }}</span>
                </a>
                @endforeach

                <form method="GET" action="{{ route('peminjaman.tampil') }}" class="ms-auto">
                    @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control"
                            placeholder="Cari alat / peminjam..."
                            value="{{ request('search') }}"
                            style="width: 200px;">
                        <button type="submit" class="btn btn-secondary">
                            <i class="mdi mdi-magnify"></i>
                        </button>
                        @if(request('search'))
                        <a href="{{ route('peminjaman.tampil', ['status' => request('status')]) }}" class="btn btn-outline-secondary">
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
                            @if($role !== 'User')<th>Peminjam</th>@endif
                            <th>Alat</th>
                            <th>Unit</th>
                            <th>Status</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Tujuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $p)
                        @php
                        $isLate = $p->status === 'active' && \Carbon\Carbon::today()->gt(\Carbon\Carbon::parse($p->due_date));
                        $statusConfig = [
                        'pending' => ['bg-warning text-dark', 'mdi-clock-outline', 'Menunggu'],
                        'active' => ['bg-success', 'mdi-check', 'Disetujui'],
                        'returning' => ['bg-primary', 'mdi-arrow-left-circle', 'Dikembalikan'],
                        'rejected' => ['bg-danger', 'mdi-close', 'Ditolak'],
                        'closed' => ['bg-secondary', 'mdi-archive', 'Selesai'],
                        ];
                        [$badgeClass, $icon, $statusLabel] = $statusConfig[$p->status] ?? ['bg-light text-dark', 'mdi-help', $p->status];
                        @endphp
                        <tr class="{{ $isLate ? 'table-danger' : '' }}">
                            <td>{{ $index + 1 }}</td>

                            @if($role !== 'User')
                            <td>
                                <div class="fw-semibold">{{ $p->user->detail->name ?? '-' }}</div>
                                <small class="text-muted">{{ $p->user->email }}</small>
                            </td>
                            @endif

                            <td>
                                <div class="fw-semibold">{{ $p->alat->name ?? '-' }}</div>
                                <small>
                                    @php $type = $p->alat->item_type ?? ''; @endphp
                                    @if($type === 'bundle')
                                    <span class="badge bg-info text-dark">Bundle</span>
                                    @else
                                    <span class="badge bg-primary">Single</span>
                                    @endif
                                </small>
                            </td>

                            <td><code>{{ $p->unit_code }}</code></td>

                            <td>
                                <span class="badge {{ $badgeClass }}">
                                    <i class="mdi {{ $icon }}"></i> {{ $statusLabel }}
                                </span>
                            </td>

                            <td>{{ \Carbon\Carbon::parse($p->loan_date)->format('d M Y') }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($p->due_date)->format('d M Y') }}
                                @if($isLate)
                                <br><span class="badge bg-danger">
                                    Terlambat {{ \Carbon\Carbon::parse($p->due_date)->diffInDays(\Carbon\Carbon::today()) }} hari
                                </span>
                                @endif
                            </td>

                            <td style="max-width:180px;">
                                <span title="{{ $p->purpose }}">
                                    {{ \Illuminate\Support\Str::limit($p->purpose, 50) }}
                                </span>
                            </td>

                            <td>
                                @if($role === 'User')
                                @if($p->status === 'active')
                                <button type="button" class="btn btn-sm btn-primary"
                                    data-bs-toggle="modal" data-bs-target="#modalKembali{{ $p->id }}">
                                    <i class="mdi mdi-keyboard-return"></i> Kembalikan
                                </button>
                                @else
                                <span class="text-muted small">—</span>
                                @endif

                                @elseif($role === 'Admin')
                                <div class="d-flex gap-1 flex-wrap">
                                    @if($p->status === 'pending')
                                    <button type="button" class="btn btn-sm btn-success"
                                        data-bs-toggle="modal" data-bs-target="#modalApprove{{ $p->id }}">
                                        <i class="mdi mdi-check"></i> Setujui
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        data-bs-toggle="modal" data-bs-target="#modalReject{{ $p->id }}">
                                        <i class="mdi mdi-close"></i> Tolak
                                    </button>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-warning"
                                        data-bs-toggle="modal" data-bs-target="#modalEdit{{ $p->id }}">
                                        <i class="mdi mdi-pencil"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        data-bs-toggle="modal" data-bs-target="#modalHapus{{ $p->id }}">
                                        <i class="mdi mdi-delete"></i> Hapus
                                    </button>
                                </div>

                                @else
                                @if($p->status === 'pending')
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-success"
                                        data-bs-toggle="modal" data-bs-target="#modalApprove{{ $p->id }}">
                                        <i class="mdi mdi-check"></i> Setujui
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        data-bs-toggle="modal" data-bs-target="#modalReject{{ $p->id }}">
                                        <i class="mdi mdi-close"></i> Tolak
                                    </button>
                                </div>
                                @else
                                <span class="text-muted small">—</span>
                                @endif
                                @endif
                            </td>
                        </tr>

                        @if($role === 'User' && $p->status === 'active')
                        <div class="modal fade" id="modalKembali{{ $p->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('pengembalian.ajukan', $p->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf @method('PATCH')
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title"><i class="mdi mdi-keyboard-return"></i> Kembalikan Alat</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Kembalikan <strong>{{ $p->alat->name ?? '-' }}</strong> (Unit: <code>{{ $p->unit_code }}</code>)?</p>
                                            <div class="mb-3">
                                                <label class="form-label">Bukti Foto Alat <span class="text-danger">*</span></label>
                                                <input type="file" name="photo" class="form-control" accept="image/*" required>
                                                <small class="text-muted">Upload foto kondisi alat saat dikembalikan</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="mdi mdi-keyboard-return"></i> Kembalikan
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif

                        @if($role !== 'User' && $p->status === 'pending')
                        <div class="modal fade" id="modalApprove{{ $p->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('peminjaman.approve', $p->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <div class="modal-content">
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title"><i class="mdi mdi-check-circle"></i> Setujui Pengajuan</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Setujui peminjaman <strong>{{ $p->alat->name ?? '-' }}</strong> oleh <strong>{{ $p->user->detail->name ?? $p->user->email }}</strong>?</p>
                                            <div class="mb-3">
                                                <label class="form-label">Catatan <span class="text-muted">(opsional)</span></label>
                                                <textarea name="notes" class="form-control" rows="2" placeholder="Misal: Pastikan dikembalikan tepat waktu..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-success"><i class="mdi mdi-check"></i> Ya, Setujui</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="modal fade" id="modalReject{{ $p->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('peminjaman.reject', $p->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title"><i class="mdi mdi-close-circle"></i> Tolak Pengajuan</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Tolak peminjaman <strong>{{ $p->alat->name ?? '-' }}</strong> oleh <strong>{{ $p->user->detail->name ?? $p->user->email }}</strong>?</p>
                                            <div class="mb-3">
                                                <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                                <textarea name="notes" class="form-control" rows="2" placeholder="Jelaskan alasan penolakan..." required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger"><i class="mdi mdi-close"></i> Ya, Tolak</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif

                        @if($role === 'Admin')
                        <div class="modal fade" id="modalEdit{{ $p->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <form action="{{ route('peminjaman.update', $p->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning text-dark">
                                            <h5 class="modal-title"><i class="mdi mdi-pencil"></i> Edit Peminjaman</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Alat</label>
                                                    <select name="tool_id" class="form-select" required>
                                                        @foreach ($alat as $a)
                                                        <option value="{{ $a->id }}" {{ $p->tool_id == $a->id ? 'selected' : '' }}>
                                                            {{ $a->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Unit Code</label>
                                                    <input type="text" name="unit_code" class="form-control" value="{{ $p->unit_code }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Status</label>
                                                    <select name="status" class="form-select" required>
                                                        @foreach(['pending' => 'Menunggu', 'active' => 'Disetujui', 'rejected' => 'Ditolak', 'closed' => 'Selesai'] as $val => $lbl)
                                                        <option value="{{ $val }}" {{ $p->status === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label fw-semibold">Tgl Pinjam</label>
                                                    <input type="date" name="loan_date" class="form-control" value="{{ \Carbon\Carbon::parse($p->loan_date)->format('Y-m-d') }}" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label fw-semibold">Tgl Kembali</label>
                                                    <input type="date" name="due_date" class="form-control" value="{{ \Carbon\Carbon::parse($p->due_date)->format('Y-m-d') }}" required>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-semibold">Tujuan</label>
                                                    <textarea name="purpose" class="form-control" rows="2" required>{{ $p->purpose }}</textarea>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-semibold">Catatan</label>
                                                    <textarea name="notes" class="form-control" rows="2">{{ $p->notes }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-warning text-dark">
                                                <i class="mdi mdi-content-save"></i> Simpan Perubahan
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="modal fade" id="modalHapus{{ $p->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('peminjaman.destroy', $p->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title"><i class="mdi mdi-delete-alert"></i> Hapus Peminjaman</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Hapus data peminjaman <strong>{{ $p->alat->name ?? '-' }}</strong> oleh <strong>{{ $p->user->detail->name ?? $p->user->email }}</strong>?</p>
                                            <div class="alert alert-warning py-2 mb-0">
                                                <i class="mdi mdi-alert me-1"></i>
                                                Jika status <strong>Disetujui</strong>, unit akan otomatis dibebaskan kembali.
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">
                                                <i class="mdi mdi-delete"></i> Ya, Hapus
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif

                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                <i class="mdi mdi-inbox-outline fs-4 d-block mb-1"></i>
                                {{ $role === 'User' ? 'Belum ada riwayat peminjaman' : 'Tidak ada pengajuan' }}
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
@endsection