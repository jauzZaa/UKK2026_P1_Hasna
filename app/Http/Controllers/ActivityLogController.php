<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        abort_if(!auth()->check() || auth()->user()->role !== 'Admin', 403);

        $logs = ActivityLog::with('user.detail')
            ->when(request('role'), function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('role', request('role'));
                });
            })
            ->latest()
            ->paginate(20);

        return view('LogAktifitas.tampil', compact('logs'));
    }
}
