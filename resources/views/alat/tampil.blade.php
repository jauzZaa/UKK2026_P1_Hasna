@extends('layouts.app')

@section('title', 'Tampil Data Alat')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Tampil | Crud Alat</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@php $role = strtolower(auth()->user()->role); @endphp

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title mb-0">Tampil Data Alat</h4>
                <div class="d-flex gap-2 align-items-center">
                    <form method="GET" action="{{ route('alat.tampil') }}" class="d-flex">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control"
                                placeholder="Cari alat..."
                                value="{{ request('search') }}"
                                style="width: 200px;">
                            <button type="submit" class="btn btn-secondary-subtle">
                                <i class="mdi mdi-magnify"></i>
                            </button>
                            @if(request('search'))
                            <a href="{{ route('alat.tampil') }}" class="btn btn-outline-secondary">
                                <i class="mdi mdi-close"></i>
                            </a>
                            @endif
                        </div>
                    </form>
                    <a href="{{ route('alat.export') }}" class="btn btn-sm btn-secondary-subtle">
                        Print <i class="mdi mdi-printer align-middle"></i>
                    </a>
                    <a href="{{ route('alat.tambah') }}" class="btn btn-sm btn-primary">
                        Tambah <i class="mdi mdi-plus align-middle"></i>
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 45px;"></th>
                            <th style="width: 50px;">No</th>
                            <th>Foto</th>
                            <th>Kode</th>
                            <th>Nama Alat</th>
                            <th>Kategori</th>
                            <th>Tipe</th>
                            <th>Lokasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $alat)
                        <tr>
                            <td class="text-center">
                                @if ($alat->item_type == 'bundle' && $alat->bundleItems->count() > 0)
                                <button class="btn btn-link btn-sm p-0 text-decoration-none"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#row-bundle-{{ $alat->id }}">
                                    <i class="mdi mdi-chevron-right fw-bold text-info fs-5"></i>
                                </button>
                                @else
                                <span class="text-muted small">•</span>
                                @endif
                            </td>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @if ($alat->photo_path)
                                <img src="{{ asset('storage/' . $alat->photo_path) }}" width="60" height="60"
                                    style="object-fit: cover; border-radius: 6px;">
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $alat->code_slug ?? '-' }}</td>
                            <td class="fw-medium">{{ $alat->name }}</td>
                            <td>{{ $alat->category->name ?? '-' }}</td>
                            <td>
                                @if ($alat->item_type == 'single')
                                <span class="badge bg-primary">Single</span>
                                @elseif ($alat->item_type == 'bundle')
                                <span class="badge bg-info">Bundle</span>
                                @endif
                            </td>
                            <td>{{ $alat->lokasi->name ?? '-' }}</td>
                            <td class="text-center">
                                @if($role == 'admin')
                                <a href="{{ route('alat.edit', $alat->id) }}" class="btn btn-sm btn-warning">
                                    <i class="mdi mdi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('alat.destroy', $alat->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin hapus alat ini? Seluruh data komponen di dalamnya juga akan terhapus.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger ms-1">
                                        <i class="mdi mdi-trash-can"></i> Hapus
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('alat.detail', $alat->id) }}" class="btn btn-sm btn-info ms-1">
                                    <i class="mdi mdi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>

                        @if ($alat->item_type == 'bundle' && $alat->bundleItems->count() > 0)
                        <tr class="collapse" id="row-bundle-{{ $alat->id }}">
                            <td colspan="9" class="bg-light p-0">
                                <div class="p-4 border-start border-4 border-info ms-2">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="mdi mdi-package-variant-closed fs-4 text-info me-2"></i>
                                        <h6 class="mb-0 fw-bold text-dark">Detail Komponen Bundle: <span class="text-primary">{{ $alat->name }}</span></h6>
                                    </div>
                                    <table class="table table-sm table-hover table-bordered bg-white mb-0 shadow-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-center" style="width: 50px;">No</th>
                                                <th>Nama Komponen</th>
                                                <th>Harga Satuan</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-end pe-3">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($alat->bundleItems as $subIndex => $item)
                                            <tr>
                                                <td class="text-center text-muted">{{ $subIndex + 1 }}</td>
                                                <td>{{ $item->name ?? '-' }}</td>
                                                <td>Rp {{ number_format($item->price ?? 0, 0, ',', '.') }}</td>
                                                <td class="text-center">
                                                    <span class="badge bg-secondary">{{ $item->pivot->qty }}</span>
                                                </td>
                                                <td class="text-end pe-3">
                                                    Rp {{ number_format(($item->price ?? 0) * $item->pivot->qty, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        @endif

                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="mdi mdi-database-off fs-1 d-block mb-2"></i>
                                Tidak ada data alat yang tersedia.
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