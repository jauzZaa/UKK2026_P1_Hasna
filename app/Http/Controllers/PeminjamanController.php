<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\ToolUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function tampil()
    {
        $status = request('status', 'all');
        $data = Peminjaman::with(['alat', 'unit', 'user.detail'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('peminjaman.tampil', compact('data'));
    }

    public function tambah()
    {
        $alat = Alat::whereIn('item_type', ['single', 'bundle'])
            ->whereHas('units', function ($q) {
                $q->where('status', 'available');
            })
            ->get();

        return view('peminjaman.tambah', compact('alat'));
    }

    public function getUnits($toolId)
    {
        $units = ToolUnit::where('tool_id', $toolId)
            ->where('status', 'available')
            ->get(['code', 'notes']);

        return response()->json($units);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tool_id'    => 'required|exists:tools,id',
            'unit_code'  => 'required|exists:tool_units,code',
            'loan_date'  => 'required|date|after_or_equal:today',
            'due_date'   => 'required|date|after:loan_date',
            'purpose'    => 'required|string|max:500',
            'user_notes' => 'nullable|string|max:1000',
        ]);

        Peminjaman::create([
            'user_id'    => Auth::id(),
            'tool_id'    => $request->tool_id,
            'unit_code'  => $request->unit_code,
            'status'     => 'pending',
            'loan_date'  => $request->loan_date,
            'due_date'   => $request->due_date,
            'purpose'    => $request->purpose,
            'user_notes' => $request->user_notes,
            'created_at' => now(),
        ]);

        return redirect()->route('peminjaman.tampil')
            ->with('success', 'Pengajuan peminjaman berhasil dikirim, menunggu persetujuan.');
    }

    public function approve(Request $request, Peminjaman $peminjaman)
    {
        abort_if($peminjaman->status !== 'pending', 403, 'Pengajuan ini tidak bisa disetujui.');

        $peminjaman->update([
            'status'      => 'active',
            'employee_id' => Auth::id(),
            'notes'       => $request->notes,
        ]);

        ToolUnit::where('code', $peminjaman->unit_code)
            ->update(['status' => 'lent']);

        return redirect()->route('peminjaman.tampil')
            ->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject(Request $request, Peminjaman $peminjaman)
    {
        abort_if($peminjaman->status !== 'pending', 403, 'Pengajuan ini tidak bisa ditolak.');

        $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        $peminjaman->update([
            'status'      => 'rejected',
            'employee_id' => Auth::id(),
            'notes'       => $request->notes,
        ]);

        return redirect()->route('peminjaman.tampil')
            ->with('success', 'Pengajuan berhasil ditolak.');
    }

    public function riwayat()
    {
        $data = Peminjaman::with(['alat', 'unit'])
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('peminjaman.riwayat', compact('data'));
    }
}
