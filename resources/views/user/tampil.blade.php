@extends('layouts.app')

@section('title', 'Tampil Data')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Tampil | Crud User</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@php $userTampilUrl = route('user.tampil'); @endphp

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title mb-0">Data User</h4>
                <div class="d-flex gap-2 align-items-center">
                    <select id="filterRole" class="form-select form-select-sm" style="width: 130px;"
                        onchange="window.location.href='{{ $userTampilUrl }}?role='+this.value">
                        <option value="">Role</option>
                        <option value="Admin" {{ request('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                        <option value="Employee" {{ request('role') == 'Employee' ? 'selected' : '' }}>Employee</option>
                        <option value="User" {{ request('role') == 'User' ? 'selected' : '' }}>User</option>
                    </select>
                    <a href="#" class="btn btn-sm btn-secondary-subtle">
                        Print <i class="mdi mdi-printer align-middle"></i>
                    </a>
                    <a href="{{ route('user.tambah') }}" class="btn btn-sm btn-primary">
                        Tambah <i class="mdi mdi-plus align-middle"></i>
                    </a>
                </div>
            </div>

            @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->detail->name ?? '-' }}</td>
                            <td>{{ $user->detail->nik ?? '-' }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if ($user->role == 'Admin')
                                <span class="badge bg-danger">Admin</span>
                                @elseif ($user->role == 'Employee')
                                <span class="badge bg-warning text-dark">Employee</span>
                                @elseif ($user->role == 'User')
                                <span class="badge bg-success">User</span>
                                @else
                                <span class="badge bg-light text-dark">{{ $user->role ?? '-' }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                    <i class="mdi mdi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin hapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger ms-1">
                                        <i class="mdi mdi-trash-can"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection