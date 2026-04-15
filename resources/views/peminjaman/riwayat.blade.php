@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Riwayat Peminjaman</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Riwayat Peminjaman</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title mb-0">Riwayat Peminjaman Saya</h4>
            </div>

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
                            <th>Alat</th>
                            <th>Unit</th>
                            <th>Status</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Tujuan</th>
                            <th>Catatan Petugas</th>
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

                            {{-- Alat --}}
                            <td>
                                <div class="fw-semibold">{{ $p->alat->name ?? '-' }}</div>
                                <small>
                                    @if (($p->alat->item_type ?? '') === 'bundle')
                                    <span class="badge bg-info text-dark">Bundle</span>
                                    @else
                                    <span class="badge bg-primary">Single</span>
                                    @endif
                                </small>
                            </td>

                            {{-- Unit --}}
                            <td><code>{{ $p->unit_code }}</code></td>

                            {{-- Status --}}
                            <td>
                                @php
                                $statusConfig = [
                                'pending' => ['#FFF8E1', '#F59E0B', 'mdi-clock-outline', 'Menunggu'],
                                'active' => ['#ECFDF5', '#10B981', 'mdi-check-circle', 'Disetujui'],
                                'rejected' => ['#FEF2F2', '#EF4444', 'mdi-close-circle', 'Ditolak'],
                                'closed' => ['#F3F4F6', '#6B7280', 'mdi-archive-outline', 'Selesai'],
                                ];
                                [$bg, $color, $icon, $statusLabel] = $statusConfig[$p->status] ?? ['#F3F4F6', '#6B7280', 'mdi-help', $p->status];
                                @endphp
                                <span style="display:inline-flex; align-items:center; gap:5px;
                                    background-color:{{ $bg }}; color:{{ $color }};
                                    border:1.5px solid {{ $color }}30; padding:4px 10px;
                                    border-radius:20px; font-size:0.78rem; font-weight:600;">
                                    <i class="mdi {{ $icon }}" style="font-size:0.9rem;"></i>
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            {{-- Tgl Pinjam --}}
                            <td>{{ \Carbon\Carbon::parse($p->loan_date)->format('d M Y') }}</td>

                            {{-- Tgl Kembali --}}
                            <td>
                                {{ \Carbon\Carbon::parse($p->due_date)->format('d M Y') }}
                                @if ($isLate)
                                <br><span class="badge bg-danger">
                                    Terlambat {{ \Carbon\Carbon::parse($p->due_date)->diffInDays(\Carbon\Carbon::today()) }} hari
                                </span>
                                @endif
                            </td>

                            {{-- Tujuan --}}
                            <td style="max-width:160px;">
                                <span title="{{ $p->purpose }}">
                                    {{ \Illuminate\Support\Str::limit($p->purpose, 50) }}
                                </span>
                            </td>

                            {{-- Catatan Petugas --}}
                            <td style="max-width:160px;">
                                @if ($p->notes)
                                <span title="{{ $p->notes }}" class="text-muted fst-italic">
                                    {{ \Illuminate\Support\Str::limit($p->notes, 50) }}
                                </span>
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="mdi mdi-inbox-outline fs-4 d-block mb-1"></i>
                                Belum ada riwayat peminjaman
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