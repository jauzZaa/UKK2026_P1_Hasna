@extends('layouts.app')

@section('title', 'Riwayat Pengembalian')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Riwayat Pengembalian</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Riwayat Pengembalian</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title mb-0">Riwayat Semua Pengembalian</h4>
                <div>
                    <a href="{{ route('pengembalian.export') }}" class="btn btn-sm btn-secondary-subtle">
                        Print <i class="mdi mdi-printer align-middle"></i>
                    </a>
                </div>
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
                            <th>Peminjam</th>
                            <th>Alat</th>
                            <th>Unit</th>
                            <th>Kondisi</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $p)
                        <tr>

                            <td>{{ $index + 1 }}</td>

                            {{-- Peminjam --}}
                            <td>
                                <div class="fw-semibold">{{ $p->user->detail->name ?? '-' }}</div>
                                <small class="text-muted">{{ $p->user->email }}</small>
                            </td>

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

                            {{-- Kondisi --}}
                            <td>
                                @php
                                $kondisi = $p->pengembalian?->unitCondition->conditions ?? null;
                                @endphp

                                @if ($kondisi === 'good')
                                <span class="badge bg-success">Baik</span>
                                @elseif ($kondisi === 'broken')
                                <span class="badge bg-danger">Rusak</span>
                                @elseif ($kondisi === 'maintenance')
                                <span class="badge bg-warning text-dark">Maintenance</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- Tgl Pinjam --}}
                            <td>{{ \Carbon\Carbon::parse($p->loan_date)->format('d M Y') }}</td>

                            {{-- Tgl Kembali --}}
                            <td>
                                {{ $p->pengembalian?->return_date
                                    ? \Carbon\Carbon::parse($p->pengembalian->return_date)->format('d M Y')
                                    : '-' }}
                            </td>


                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="mdi mdi-inbox-outline fs-4 d-block mb-1"></i>
                                Belum ada riwayat pengembalian
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