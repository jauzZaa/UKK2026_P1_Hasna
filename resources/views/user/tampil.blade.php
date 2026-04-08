@extends('layouts.app')

@section('title', 'Tampil Data')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Crud Dasar</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Tampil | Crud Dasar</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title mb-0">Tampil Data</h4>
                <div>
                    <a href="#" class="btn btn-sm btn-secondary-subtle">
                        Print <i class="mdi mdi-printer align-middle"></i>
                    </a>
                    <a href="{{ route('user.tambah') }}" class="btn btn-sm btn-primary ms-2">
                        Tambah <i class="mdi mdi-plus align-middle"></i>
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role ?? '-' }}</td>
                            <td>
                                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                    <i class="mdi mdi-pencil"></i> Edit
                                </a>
                                <a href="{{ route('user.destroy', $user->id) }}" class="btn btn-sm btn-danger ms-1">
                                    <i class="mdi mdi-trash-can"></i> Hapus
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                Tidak ada data
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