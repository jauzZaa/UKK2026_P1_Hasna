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

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title mb-0">Tampil Data Alat</h4>
                <div>
                    <a href="#" class="btn btn-sm btn-secondary-subtle">
                        Print <i class="mdi mdi-printer align-middle"></i>
                    </a>
                    <a href="{{ route('alat.tambah') }}" class="btn btn-sm btn-primary ms-2">
                        Tambah <i class="mdi mdi-plus align-middle"></i>
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Foto</th>
                            <th>Kode</th>
                            <th>Nama Alat</th>
                            <th>Kategori</th>
                            <th>Tipe</th>
                            <th>Lokasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $alat)
                        <tr>
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
                            <td>{{ $alat->name }}</td>
                            <td>{{ $alat->category->name ?? '-' }}</td>
                            <td>
                                @if ($alat->item_type == 'single')
                                <span class="badge bg-primary">Single</span>
                                @elseif ($alat->item_type == 'bundle')
                                <span class="badge bg-info">Bundle</span>
                                @elseif ($alat->item_type == 'bundle_tool')
                                <span class="badge bg-secondary">Bundle Tool</span>
                                @else
                                <span class="badge bg-light text-dark">-</span>
                                @endif
                            </td>
                            <td>{{ $alat->lokasi->name ?? '-' }}</td>


                            <td>
                                <a href="{{ route('alat.edit', $alat->id) }}" class="btn btn-sm btn-warning">
                                    <i class="mdi mdi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('alat.destroy', $alat->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin hapus alat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger ms-1">
                                        <i class="mdi mdi-trash-can"></i> Hapus
                                    </button>
                                </form>
                                <a href="{{ route('alat.detail', $alat->id) }}" class="btn btn-sm btn-info ms-1">
                                    <i class="mdi mdi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-3">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection