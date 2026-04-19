<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\ActivityLog;
use App\Models\Peminjaman;
use App\Models\ToolUnit;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function tampil()
    {
        $status = request('status', 'all');

        $query = Peminjaman::with(['alat', 'unit', 'user.detail'])
            ->orderByDesc('created_at');

        $query->whereNotIn('status', ['closed']);

        // Filter per status tab (kecuali 'all')
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if (Auth::user()->role === 'User') {
            $query->where('user_id', Auth::id());
        }

        $data = $query->paginate(15);
        $alat = Alat::orderBy('name')->get();

        return view('peminjaman.tampil', compact('data', 'alat'));
    }

    public function tambah()
    {
        $dendaAktif = Peminjaman::where('user_id', Auth::id())
            ->whereIn('status', ['fined', 'fine_pending'])
            ->exists();

        if ($dendaAktif) {
            return redirect()->route('peminjaman.tampil')
                ->with('error', 'Anda masih memiliki denda yang belum diselesaikan.');
        }

        $masihAktif = Peminjaman::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'active'])
            ->exists();

        if ($masihAktif) {
            return redirect()->route('peminjaman.tampil')
                ->with('error', 'Kamu masih memiliki peminjaman yang belum selesai. Kembalikan alat terlebih dahulu.');
        }

        $alat = Alat::whereIn('item_type', ['single', 'bundle'])
            ->whereHas('units', function ($q) {
                $q->where('status', 'available');
            })->get();

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

        $masihAktif = Peminjaman::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'active'])
            ->exists();

        if ($masihAktif) {
            return redirect()->route('peminjaman.tampil')
                ->with('error', 'Kamu masih memiliki peminjaman yang belum selesai. Kembalikan alat terlebih dahulu.');
        }

        $request->validate([
            'tool_id'   => 'required|exists:tools,id',
            'unit_code' => 'required|exists:tool_units,code',
            'loan_date' => 'required|date|after_or_equal:today',
            'due_date'  => 'required|date|after:loan_date',
            'purpose'   => 'required|string|max:500',
            'notes'     => 'nullable|string|max:1000',
        ]);

        $peminjaman = Peminjaman::create([
            'user_id'    => Auth::id(),
            'tool_id'    => $request->tool_id,
            'unit_code'  => $request->unit_code,
            'status'     => 'pending',
            'loan_date'  => $request->loan_date,
            'due_date'   => $request->due_date,
            'purpose'    => $request->purpose,
            'notes'      => $request->notes,
            'created_at' => now(),
        ]);

        ActivityLog::log('create', 'peminjaman', "Mengajukan peminjaman alat: {$peminjaman->alat->name} (Unit: {$peminjaman->unit_code})");

        return redirect()->route('peminjaman.tampil')
            ->with('success', 'Pengajuan peminjaman berhasil dikirim.');
    }

    public function approve(Request $request, Peminjaman $peminjaman)
    {
        abort_if($peminjaman->status !== 'pending', 403, 'Pengajuan ini tidak bisa disetujui.');

        $peminjaman->update([
            'status'      => 'active',
            'employee_id' => Auth::id(),
            'notes'       => $request->notes,
        ]);

        ToolUnit::where('code', $peminjaman->unit_code)->update(['status' => 'lent']);

        ActivityLog::log('approve', 'peminjaman', "Menyetujui peminjaman ID: {$peminjaman->id} (Unit: {$peminjaman->unit_code})");

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

        ActivityLog::log('reject', 'peminjaman', "Menolak peminjaman ID: {$peminjaman->id} (Unit: {$peminjaman->unit_code})");

        return redirect()->route('peminjaman.tampil')
            ->with('success', 'Pengajuan berhasil ditolak.');
    }

    public function riwayat()
    {
        // Riwayat = hanya status closed milik user yang login
        $data = Peminjaman::with(['alat', 'unit'])
            ->where('user_id', Auth::id())
            ->where('status', 'closed')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('peminjaman.riwayat', compact('data'));
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        abort_if(Auth::user()->role !== 'Admin', 403);

        $request->validate([
            'tool_id'   => 'required|exists:tools,id',
            'unit_code' => 'required|exists:tool_units,code',
            'loan_date' => 'required|date',
            'due_date'  => 'required|date|after:loan_date',
            'purpose'   => 'required|string|max:500',
            'status'    => 'required|in:pending,active,rejected,closed',
            'notes'     => 'nullable|string|max:1000',
        ]);

        $peminjaman->update($request->only([
            'tool_id',
            'unit_code',
            'loan_date',
            'due_date',
            'purpose',
            'status',
            'notes',
        ]));

        ActivityLog::log('update', 'peminjaman', "Mengubah data peminjaman ID: {$peminjaman->id}");

        return redirect()->route('peminjaman.tampil')
            ->with('success', 'Data peminjaman berhasil diperbarui.');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        abort_if(Auth::user()->role !== 'Admin', 403);

        if ($peminjaman->status === 'active') {
            ToolUnit::where('code', $peminjaman->unit_code)->update(['status' => 'available']);
        }

        ActivityLog::log('delete', 'peminjaman', "Menghapus peminjaman ID: {$peminjaman->id} (Unit: {$peminjaman->unit_code})");

        $peminjaman->delete();

        return redirect()->route('peminjaman.tampil')
            ->with('success', 'Data peminjaman berhasil dihapus.');
    }
}
