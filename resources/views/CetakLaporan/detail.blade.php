@extends('layouts.app')
@section('title', 'Detail Peminjaman')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Laporan</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">Cetak Laporan</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">

            <h5 class="mb-3">Detail Peminjaman - {{ $user->detail->name ?? $user->email }}</h5>
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Nama:</strong> {{ $user->detail->name ?? '-' }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>No. HP:</strong> {{ $user->detail->no_hp ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Alamat:</strong> {{ $user->detail->address ?? '-' }}</p>
                    <p>
                        <strong>Total Peminjaman:</strong>
                        <span class="badge bg-primary fs-6">{{ $user->peminjaman->count() }}</span>
                    </p>
                </div>
            </div>

            <a href="{{ route('laporan.tampil') }}" class="btn btn-secondary mb-3">
                <i class="mdi mdi-arrow-left"></i> Kembali
            </a>

            <hr>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title mb-0">Riwayat Peminjaman</h4>
                <a href="{{ route('laporan.detail', $user->id) }}" class="btn btn-sm btn-secondary-subtle">
                    Print <i class="mdi mdi-printer align-middle"></i>
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Alat</th>
                            <th>Tanggal Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Tujuan</th>
                            <th>Status</th>
                            <th>Petugas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->peminjaman as $index => $p)
                        @php
                        $statusConfig = [
                        'pending' => ['bg-warning text-dark', 'Menunggu'],
                        'active' => ['bg-success', 'Disetujui'],
                        'returning' => ['bg-primary', 'Dikembalikan'],
                        'rejected' => ['bg-danger', 'Ditolak'],
                        'closed' => ['bg-secondary', 'Selesai'],
                        'fined' => ['bg-danger', 'Denda'],
                        'fine_pending' => ['bg-warning text-dark', 'Menunggu Bayar'],
                        ];
                        [$badgeClass, $statusLabel] = $statusConfig[$p->status] ?? ['bg-light text-dark', $p->status];
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $p->alat->name ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->loan_date)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->due_date)->format('d-m-Y') }}</td>
                            <td>{{ $p->purpose }}</td>
                            <td>
                                <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td>{{ $p->petugas->detail->name ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="mdi mdi-inbox-outline fs-4 d-block mb-1"></i>
                                Belum ada riwayat peminjaman
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection