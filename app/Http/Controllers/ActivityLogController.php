<?php
namespace App\Http\Controllers;


use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        abort_if(!auth()->check() || auth()->user()->role !== 'Admin', 403);

        $logs = ActivityLog::with('user.detail')
            ->latest()
            ->paginate(20);

        return view('LogAktifitas.tampil', compact('logs'));
    }
}