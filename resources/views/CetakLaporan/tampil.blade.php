@extends('layouts.app')
@section('title', 'Cetak Laporan')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Laporan</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Cetak Laporan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title mb-0">Laporan Data Peminjam</h4>
                <form method="GET" action="{{ route('laporan.tampil') }}" class="ms-auto me-2">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control"
                            placeholder="Cari peminjam..."
                            value="{{ request('search') }}"
                            style="width: 200px;">
                        <button type="submit" class="btn btn-secondary-subtle">
                            <i class="mdi mdi-magnify"></i>
                        </button>
                        @if(request('search'))
                        <a href="{{ route('laporan.tampil') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-close"></i>
                        </a>
                        @endif
                    </div>
                </form>
                <a href="{{ route('laporan.export') }}" class="btn btn-sm btn-secondary-subtle">
                    Print <i class="mdi mdi-printer align-middle"></i>
                </a>
            </div>

            @if(session('success'))
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
                            <th>Nama Peminjam</th>
                            <th>Email</th>
                            <th>No. HP</th>
                            <th>Jumlah Peminjaman</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->detail->name ?? '-' }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->detail->no_hp ?? '-' }}</td>
                            <td>
                                <span class="badge bg-primary fs-6">
                                    {{ $user->jumlah_peminjaman }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('laporan.detail', $user->id) }}"
                                        class="btn btn-sm btn-info text-white">
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="mdi mdi-inbox-outline fs-4 d-block mb-1"></i>
                                Tidak ada data peminjam
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