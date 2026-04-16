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
            <h4 class="card-title mb-3">Data Pengembalian Saya</h4>

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
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Keterangan Denda</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $p)
                        <tr>
                            <td>{{ $index + 1 }}</td>
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
                            <td>{{ \Carbon\Carbon::parse($p->due_date)->format('d M Y') }}</td>
                            <td>
                                @if ($p->pengembalian && $p->pengembalian->notes)
                                <div class="alert alert-danger py-2 mb-0">
                                    <i class="mdi mdi-alert me-1"></i>
                                    {{ str_replace('[DENDA] ', '', $p->pengembalian->notes) }}
                                </div>
                                @else
                                <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                @if ($p->status === 'fined')
                                <span style="display:inline-flex; align-items:center; gap:5px;
                                    background-color:#FEF2F2; color:#EF4444;
                                    border:1.5px solid #EF444430; padding:4px 10px;
                                    border-radius:20px; font-size:0.78rem; font-weight:600;">
                                    <i class="mdi mdi-alert-circle" style="font-size:0.9rem;"></i>
                                    Kena Denda
                                </span>
                                @elseif ($p->status === 'fine_pending')
                                <span style="display:inline-flex; align-items:center; gap:5px;
                                    background-color:#FFF8E1; color:#F59E0B;
                                    border:1.5px solid #F59E0B30; padding:4px 10px;
                                    border-radius:20px; font-size:0.78rem; font-weight:600;">
                                    <i class="mdi mdi-clock-outline" style="font-size:0.9rem;"></i>
                                    Menunggu Konfirmasi
                                </span>
                                @endif
                            </td>
                            <td>
                                @if ($p->status === 'fined')
                                <button type="button" class="btn btn-sm btn-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalLapor{{ $p->id }}">
                                    <i class="mdi mdi-cash-check"></i> Lapor Bayar
                                </button>
                                @elseif ($p->status === 'fine_pending')
                                <span class="text-muted small">Menunggu konfirmasi petugas</span>
                                @endif
                            </td>
                        </tr>

                        {{-- MODAL LAPOR BAYAR --}}
                        @if ($p->status === 'fined')
                        <div class="modal fade" id="modalLapor{{ $p->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('pengembalian.lapor-bayar', $p->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">
                                                <i class="mdi mdi-cash-check"></i> Lapor Pembayaran Denda
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Lapor bahwa kamu sudah membayar denda untuk peminjaman
                                                <strong>{{ $p->alat->name ?? '-' }}</strong>
                                                (Unit: <code>{{ $p->unit_code }}</code>)?
                                            </p>
                                            @if ($p->pengembalian && $p->pengembalian->notes)
                                            <div class="alert alert-warning py-2">
                                                <i class="mdi mdi-information me-1"></i>
                                                {{ str_replace('[DENDA] ', '', $p->pengembalian->notes) }}
                                            </div>
                                            @endif
                                            <p class="text-muted small mb-0">
                                                <i class="mdi mdi-information-outline"></i>
                                                Petugas akan mengkonfirmasi pembayaran kamu.
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">
                                                <i class="mdi mdi-check"></i> Ya, Sudah Bayar
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
                                <i class="mdi mdi-check-circle-outline fs-4 d-block mb-1"></i>
                                Tidak ada denda aktif
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