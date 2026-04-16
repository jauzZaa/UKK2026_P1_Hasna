@extends('layouts.app')
@section('title', 'Log Aktifitas')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Log Aktifitas</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Log Aktifitas</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-3">Riwayat Aktifitas Sistem</h4>
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Waktu</th>
                            <th>User</th>
                            <th>Aksi</th>
                            <th>Modul</th>
                            <th>Keterangan</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $index => $log)
                        <tr>
                            <td>{{ $logs->firstItem() + $index }}</td>
                            <td>
                                <div>{{ $log->created_at->format('d M Y') }}</div>
                                <small class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $log->user->detail->name ?? '-' }}</div>
                                <small class="text-muted">{{ $log->user->email ?? '-' }}</small>
                                <br>
                                <span class="badge bg-{{ $log->user->role === 'Admin' ? 'danger' : ($log->user->role === 'Employee' ? 'warning text-dark' : 'primary') }}">
                                    {{ $log->user->role }}
                                </span>
                            </td>
                            <td>
                                @php
                                $actionConfig = [
                                    'create'  => ['bg-success', 'mdi-plus-circle'],
                                    'update'  => ['bg-warning text-dark', 'mdi-pencil'],
                                    'delete'  => ['bg-danger', 'mdi-delete'],
                                    'approve' => ['bg-success', 'mdi-check-circle'],
                                    'reject'  => ['bg-danger', 'mdi-close-circle'],
                                    'login'   => ['bg-info', 'mdi-login'],
                                    'logout'  => ['bg-secondary', 'mdi-logout'],
                                ];
                                [$badge, $icon] = $actionConfig[$log->action] ?? ['bg-light text-dark', 'mdi-help'];
                                @endphp
                                <span class="badge {{ $badge }}">
                                    <i class="mdi {{ $icon }}"></i> {{ ucfirst($log->action) }}
                                </span>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ ucfirst($log->module) }}</span></td>
                            <td>{{ $log->description }}</td>
                            <td><small class="text-muted">{{ $log->ip_address }}</small></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="mdi mdi-clipboard-text-off fs-4 d-block mb-1"></i>
                                Belum ada log aktifitas
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection