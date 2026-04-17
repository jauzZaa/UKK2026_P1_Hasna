@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

@php
use App\Models\User;
use App\Models\Alat;
use App\Models\Category;
use App\Models\Lokasi;
use App\Models\Peminjaman;

$totalUser = User::count();
$totalKategori = Category::count();
$totalLokasi = Lokasi::count();
$totalAlat = Alat::whereIn('item_type', ['single', 'bundle'])->count();
$totalPending = Peminjaman::where('status', 'pending')->count();
$totalActive = Peminjaman::where('status', 'active')->count();
$totalReturning = Peminjaman::where('status', 'returning')->count();
$totalDenda = Peminjaman::whereIn('status', ['fined', 'fine_pending'])->count();
$totalSelesai = Peminjaman::where('status', 'closed')->count();
@endphp

<style>
    /* Mengatur kelengkungan semua card putih */
    .card {
        border-radius: 20px !important;
        border: none !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03) !important;
        overflow: hidden;
        /* Agar isi card tidak keluar dari lengkungan */
        margin-bottom: 1.5rem;
    }

    /* Efek hover untuk Master Data */
    .card-animate {
        transition: all 0.3s ease-in-out;
    }

    .card-animate:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08) !important;
    }

    /* Styling Ikon Master Data */
    .avatar-stat {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 15px;
        /* Lengkungan ikon */
    }

    /* Styling Ikon Status Transaksi */
    .icon-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Header Tabel */
    .table-card thead th {
        background-color: #fcfcfd;
        text-transform: uppercase;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.8px;
        padding: 15px;
        border-bottom: 1px solid #f1f1f1;
    }

    .table-nowrap td {
        padding: 15px;
    }

    /* Badge Custom */
    .badge-soft {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
    }
</style>

<div class="row mb-4">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 fw-bold" style="letter-spacing: -0.5px;">Sales Analytics</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item text-muted">Dashonic</li>
                    <li class="breadcrumb-item active fw-bold text-primary">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>


<div class="row">
    @php
    $stats = [
    ['label' => 'Total User', 'value' => $totalUser, 'sub' => 'Terdaftar di sistem', 'icon' => 'mdi-account-group', 'color' => 'primary', 'route' => 'user.tampil'],
    ['label' => 'Total Kategori', 'value' => $totalKategori, 'sub' => 'Kategori alat', 'icon' => 'mdi-tag-multiple', 'color' => 'info', 'route' => 'category.tampil'],
    ['label' => 'Total Lokasi', 'value' => $totalLokasi, 'sub' => 'Lokasi penyimpanan', 'icon' => 'mdi-map-marker', 'color' => 'warning', 'route' => 'lokasi.tampil'],
    ['label' => 'Total Alat', 'value' => $totalAlat, 'sub' => 'Jenis alat terdaftar', 'icon' => 'mdi-package-variant', 'color' => 'success', 'route' => 'alat.tampil'],
    ];
    @endphp

    @foreach($stats as $s)
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-bold mb-1 text-uppercase font-size-11">{{ $s['label'] }}</p>
                        <h3 class="mb-1 fw-black">{{ $s['value'] }}</h3>
                        <div class="text-muted font-size-13">{{ $s['sub'] }}</div>
                    </div>
                    <div class="avatar-stat bg-{{ $s['color'] }}-subtle">
                        <i class="mdi {{ $s['icon'] }} font-size-24 text-{{ $s['color'] }}"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                    <a href="{{ route($s['route']) }}" class="text-{{ $s['color'] }} fw-bold font-size-13 text-decoration-none">
                        Lihat Detail
                    </a>
                    <i class="mdi mdi-chevron-right text-{{ $s['color'] }}"></i>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row mt-2">
    <div class="col-12 mb-3 d-flex align-items-center">
        <div class="bg-primary rounded-pill me-2" style="width: 4px; height: 18px;"></div>
        <h5 class="fw-bold mb-0">Status Transaksi</h5>
    </div>

    @php
    $loans = [
    ['label' => 'Pending', 'value' => $totalPending, 'icon' => 'mdi-clock-outline', 'color' => 'warning'],
    ['label' => 'Aktif', 'value' => $totalActive, 'icon' => 'mdi-play-circle-outline', 'color' => 'success'],
    ['label' => 'Proses Kembali', 'value' => $totalReturning, 'icon' => 'mdi-restore', 'color' => 'info'],
    ['label' => 'Denda Aktif', 'value' => $totalDenda, 'icon' => 'mdi-alert-circle-outline', 'color' => 'danger'],
    ];
    @endphp

    @foreach($loans as $l)
    <div class="col-xl-3 col-md-6">
        <div class="card border-1 shadow-none" style="border: 1px solid #eee !important;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon-circle bg-{{ $l['color'] }} me-3">
                        <i class="mdi {{ $l['icon'] }} text-white font-size-20"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 font-size-13 fw-medium">{{ $l['label'] }}</p>
                        <h4 class="mb-0 fw-bold">{{ $l['value'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>


<div class="row mt-2">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0 fw-bold">Peminjaman Terbaru</h5>
                <a href="{{ route('peminjaman.tampil') }}" class="btn btn-sm btn-light rounded-pill px-3 fw-bold">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-card">
                        <thead>
                            <tr>
                                <th class="ps-4" width="60">No</th>
                                <th>Peminjam</th>
                                <th>Alat</th>
                                <th>Unit Code</th>
                                <th>Tgl Pinjam</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $recent = Peminjaman::with(['peminjam.detail', 'alat'])
                            ->orderByDesc('created_at')->limit(5)->get();

                            $badges = [
                            'pending' => 'bg-warning-subtle text-warning',
                            'active' => 'bg-success-subtle text-success',
                            'returning' => 'bg-info-subtle text-info',
                            'closed' => 'bg-light text-muted',
                            'fined' => 'bg-danger-subtle text-danger',
                            ];
                            @endphp

                            @forelse ($recent as $index => $p)
                            <tr>
                                <td class="ps-4 fw-medium text-muted">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center me-2" style="width:32px; height:32px;">
                                            <span class="text-primary fw-bold font-size-10">{{ substr($p->peminjam->detail->name ?? 'U', 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="fw-bold font-size-14">{{ $p->peminjam->detail->name ?? 'User' }}</div>
                                            <small class="text-muted">{{ $p->peminjam->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="fw-medium">{{ $p->alat->name ?? '-' }}</td>
                                <td><code class="text-primary fw-bold">{{ $p->unit_code }}</code></td>
                                <td class="text-muted">{{ \Carbon\Carbon::parse($p->loan_date)->format('d M, Y') }}</td>
                                <td class="text-center">
                                    <span class="badge badge-soft {{ $badges[$p->status] ?? 'bg-secondary' }}">
                                        {{ ucfirst(str_replace('_', ' ', $p->status)) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted italic">Tidak ada data peminjaman terbaru.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection