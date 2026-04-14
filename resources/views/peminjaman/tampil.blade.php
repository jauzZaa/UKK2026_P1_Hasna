@extends('layouts.app')

@section('title', auth()->user()->role === 'User' ? 'Riwayat Peminjaman' : 'Kelola Pengajuan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Peminjaman</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">
                        {{ auth()->user()->role === 'User' ? 'Riwayat Peminjaman' : 'Kelola Pengajuan' }}
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title mb-0">
                    {{ auth()->user()->role === 'User' ? 'Riwayat Peminjaman Saya' : 'Daftar Pengajuan Peminjaman' }}
                </h4>
                @if (auth()->user()->role === 'User')
                <a href="{{ route('peminjaman.tambah') }}" class="btn btn-sm btn-primary">
                    <i class="mdi mdi-plus"></i> Ajukan Peminjaman
                </a>
                @endif
            </div>

            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="mdi mdi-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- FILTER TAB (khusus petugas) --}}
            @if (auth()->user()->role !== 'User')
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

                .tab-pending {
                    background: #FFF8E1;
                    color: #F59E0B;
                    border: 1.5px solid #F59E0B50;
                }

                .tab-pending.active {
                    background: #F59E0B;
                    color: #fff;
                    border-color: #F59E0B;
                    box-shadow: 0 2px 8px #F59E0B55;
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
                    box-shadow: 0 2px 8px #10B98155;
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
                    box-shadow: 0 2px 8px #EF444455;
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
                    box-shadow: 0 2px 8px #6B728055;
                }

                .tab-all .tab-count,
                .tab-all.active .tab-count {
                    background: #6366F1;
                }

                .tab-all.active .tab-count {
                    background: rgba(255, 255, 255, 0.25);
                }

                .tab-pending .tab-count {
                    background: #F59E0B;
                }

                .tab-pending.active .tab-count {
                    background: rgba(255, 255, 255, 0.25);
                }

                .tab-active .tab-count {
                    background: #10B981;
                }

                .tab-active.active .tab-count {
                    background: rgba(255, 255, 255, 0.25);
                }

                .tab-rejected .tab-count {
                    background: #EF4444;
                }

                .tab-rejected.active .tab-count {
                    background: rgba(255, 255, 255, 0.25);
                }

                .tab-closed .tab-count {
                    background: #6B7280;
                }

                .tab-closed.active .tab-count {
                    background: rgba(255, 255, 255, 0.25);
                }
            </style>
            <div class="d-flex gap-2 mb-3 flex-wrap">
                @php
                $tabs = ['all' => 'Semua', 'pending' => 'Menunggu', 'active' => 'Disetujui', 'rejected' => 'Ditolak', 'closed' => 'Selesai'];
                $activeTab = request('status', 'all');
                @endphp
                @foreach ($tabs as $key => $label)
                @php
                $count = $key !== 'all' ? $data->where('status', $key)->count() : $data->count();
                $isActive = $activeTab === $key ? 'active' : '';
                @endphp
                <a href="{{ request()->fullUrlWithQuery(['status' => $key]) }}"
                    class="filter-tab tab-{{ $key }} {{ $isActive }}">
                    {{ $label }}
                    <span class="tab-count">{{ $count }}</span>
                </a>
                @endforeach
            </div>
            @endif

            {{-- TABEL --}}
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            @if (auth()->user()->role !== 'User')
                            <th>Peminjam</th>
                            @endif
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
                        $isLate = $p->status === 'active'
                        && \Carbon\Carbon::today()->gt(\Carbon\Carbon::parse($p->due_date));
                        @endphp
                        <tr class="{{ $isLate ? 'table-danger' : '' }}">

                            <td>{{ $index + 1 }}</td>

                            @if (auth()->user()->role !== 'User')
                            <td>
                                <div class="fw-semibold">{{ $p->user->detail->name ?? '-' }}</div>
                                <small class="text-muted">{{ $p->user->email }}</small>
                            </td>
                            @endif

                            <td>
                                <div class="fw-semibold">{{ $p->alat->name ?? $p->tool->name ?? '-' }}</div>
                                <small>
                                    @php $type = $p->alat->item_type ?? $p->tool->item_type ?? ''; @endphp
                                    @if ($type === 'bundle')
                                    <span class="badge bg-info text-dark">Bundle</span>
                                    @else
                                    <span class="badge bg-primary">Single</span>
                                    @endif
                                </small>
                            </td>

                            <td><code>{{ $p->unit_code }}</code></td>

                            <td>
                                @php
                                $statusConfig = [
                                'pending' => ['bg-warning text-dark', 'mdi-clock-outline', 'Menunggu'],
                                'active' => ['bg-success', 'mdi-check', 'Disetujui'],
                                'rejected' => ['bg-danger', 'mdi-close', 'Ditolak'],
                                'closed' => ['bg-secondary', 'mdi-archive', 'Selesai'],
                                ];
                                [$badgeClass, $icon, $statusLabel] = $statusConfig[$p->status] ?? ['bg-light text-dark', 'mdi-help', $p->status];
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    <i class="mdi {{ $icon }}"></i> {{ $statusLabel }}
                                </span>
                            </td>

                            <td>{{ \Carbon\Carbon::parse($p->loan_date)->format('d M Y') }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($p->due_date)->format('d M Y') }}
                                @if ($isLate)
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

                            {{-- KOLOM AKSI --}}
                            <td>
                                {{-- User: tombol Kembalikan jika active --}}
                                @if (auth()->user()->role === 'User')
                                @if ($p->status === 'active')
                                <button type="button" class="btn btn-sm btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalKembali{{ $p->id }}">
                                    <i class="mdi mdi-keyboard-return"></i> Kembalikan
                                </button>
                                @else
                                <span class="text-muted small">—</span>
                                @endif

                                {{-- Petugas: tombol Setujui/Tolak jika pending --}}
                                @else
                                @if ($p->status === 'pending')
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalApprove{{ $p->id }}">
                                        <i class="mdi mdi-check"></i> Setujui
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalReject{{ $p->id }}">
                                        <i class="mdi mdi-close"></i> Tolak
                                    </button>
                                </div>
                                @else
                                <span class="text-muted small">—</span>
                                @endif
                                @endif
                            </td>

                        </tr>

                        {{-- MODAL KEMBALIKAN (User) --}}
                        @if (auth()->user()->role === 'User' && $p->status === 'active')
                        <div class="modal fade" id="modalKembali{{ $p->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                {{-- Pastikan menambahkan enctype="multipart/form-data" untuk upload file --}}
                                <form action="#" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="loan_id" value="{{ $p->id }}">

                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">
                                                <i class="mdi mdi-keyboard-return"></i> Kembalikan Alat
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <p>Kembalikan <strong>{{ $p->alat->name ?? '-' }}</strong>
                                                (Unit: <code>{{ $p->unit_code }}</code>)?</p>

                                            {{-- INPUT FOTO --}}
                                            <div class="mb-3">
                                                <label class="form-label">Bukti Foto Alat <span class="text-danger">*</span></label>
                                                <input type="file" name="photo" class="form-control" accept="image/*" required>
                                                <small class="text-muted">Unggah foto kondisi alat saat ini sebagai bukti pengembalian.</small>
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

                        {{-- MODAL APPROVE (Petugas) --}}
                        @if (auth()->user()->role !== 'User' && $p->status === 'pending')
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
                                            <p>Setujui peminjaman <strong>{{ $p->alat->name ?? $p->tool->name }}</strong>
                                                oleh <strong>{{ $p->user->detail->name ?? $p->user->email }}</strong>?</p>
                                            <div class="mb-3">
                                                <label class="form-label">Catatan <span class="text-muted">(opsional)</span></label>
                                                <textarea name="notes" class="form-control" rows="2"
                                                    placeholder="Misal: Pastikan dikembalikan tepat waktu..."></textarea>
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

                        {{-- MODAL REJECT (Petugas) --}}
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
                                            <p>Tolak peminjaman <strong>{{ $p->alat->name ?? $p->tool->name }}</strong>
                                                oleh <strong>{{ $p->user->detail->name ?? $p->user->email }}</strong>?</p>
                                            <div class="mb-3">
                                                <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                                <textarea name="notes" class="form-control" rows="2"
                                                    placeholder="Jelaskan alasan penolakan..." required></textarea>
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

                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                <i class="mdi mdi-inbox-outline fs-4 d-block mb-1"></i>
                                {{ auth()->user()->role === 'User' ? 'Belum ada riwayat peminjaman' : 'Tidak ada pengajuan' }}
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