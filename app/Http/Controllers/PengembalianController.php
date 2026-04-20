<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\ToolUnit;
use App\Models\UnitCondition;
use App\Exports\PengembalianExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PengembalianController extends Controller
{

    public function exportExcel()
    {
        return Excel::download(new PengembalianExport, 'riwayat_pengembalian.xlsx');
    }

    public function index()
    {
        $search = request('search');

        $dataReturning = Peminjaman::with(['peminjam.detail', 'alat', 'unit'])
            ->where('status', 'returning')
            ->when($search, function ($q) use ($search) {
                $q->whereHas('peminjam.detail', fn($q) => $q->where('name', 'like', "%$search%"))
                    ->orWhereHas('alat', fn($q) => $q->where('name', 'like', "%$search%"))
                    ->orWhere('unit_code', 'like', "%$search%");
            })
            ->orderByDesc('created_at')
            ->get();

        $dataDenda = Peminjaman::with(['peminjam.detail', 'alat', 'unit', 'pengembalian'])
            ->whereIn('status', ['fined', 'fine_pending'])
            ->orderByDesc('created_at')
            ->get();

        return view('petugas.pengembalian', compact('dataReturning', 'dataDenda'));
    }
    public function ajukan(Request $request, Peminjaman $peminjaman)
    {
        abort_if($peminjaman->user_id !== Auth::id(), 403);
        abort_if($peminjaman->status !== 'active', 403);

        $request->validate(['photo' => 'required|image|mimes:jpg,jpeg,png|max:2048']);

        $path = $request->file('photo')->store('pengembalian', 'public');
        $peminjaman->update(['status' => 'returning', 'return_photo' => $path]);

        // ← LOG
        ActivityLog::log('update', 'pengembalian', "Mengajukan pengembalian peminjaman ID: {$peminjaman->id}");

        return redirect()->route('peminjaman.tampil')
            ->with('success', 'Pengembalian diajukan, menunggu konfirmasi petugas');
    }

    public function konfirmasi(Request $request, Peminjaman $peminjaman)
    {
        abort_if($peminjaman->status !== 'returning', 403);

        if (Pengembalian::where('loan_id', $peminjaman->id)->exists()) {
            return redirect()->route('petugas.pengembalian')
                ->with('success', 'Pengembalian sudah pernah dikonfirmasi sebelumnya.');
        }

        $request->validate([
            'conditions' => 'required|in:good,broken,maintenance',
            'notes'      => 'nullable|string|max:1000',
            'denda_info' => 'required_if:conditions,broken|nullable|string|max:500',
        ]);

        $conditionId = (string) Str::uuid();
        UnitCondition::create([
            'id'          => $conditionId,
            'unit_code'   => $peminjaman->unit_code,
            'return_id'   => null,
            'conditions'  => $request->conditions,
            'notes'       => $request->notes ?? 'Pengembalian alat',
            'recorded_at' => now(),
        ]);

        $pengembalian = Pengembalian::create([
            'loan_id'      => $peminjaman->id,
            'employee_id'  => Auth::id(),
            'condition_id' => $conditionId,
            'return_date'  => now()->toDateString(),
            'notes'        => $request->conditions === 'broken'
                ? '[DENDA] ' . ($request->denda_info ?? 'Alat dikembalikan dalam kondisi rusak.')
                : $request->notes,
            'created_at'   => now(),
        ]);

        UnitCondition::where('id', $conditionId)->update(['return_id' => $pengembalian->id]);

        if ($request->conditions === 'broken') {
            $peminjaman->update(['status' => 'fined']);
            ToolUnit::where('code', $peminjaman->unit_code)->update(['status' => 'nonactive']);
        } else {
            $peminjaman->update(['status' => 'closed']);
            $newStatus = $request->conditions === 'good' ? 'available' : 'nonactive';
            ToolUnit::where('code', $peminjaman->unit_code)->update(['status' => $newStatus]);
        }

        ActivityLog::log('update', 'pengembalian', "Konfirmasi pengembalian ID: {$peminjaman->id}, kondisi: {$request->conditions}");

        return redirect()->route('petugas.pengembalian')
            ->with('success', 'Pengembalian berhasil dikonfirmasi.');
    }

    public function laporBayar(Peminjaman $peminjaman)
    {
        abort_if($peminjaman->user_id !== Auth::id(), 403);
        abort_if($peminjaman->status !== 'fined', 403);

        $peminjaman->update(['status' => 'fine_pending']);

        
        ActivityLog::log('update', 'pengembalian', "Lapor bayar denda peminjaman ID: {$peminjaman->id}");

        return redirect()->route('peminjaman.denda')
            ->with('success', 'Laporan pembayaran denda telah dikirim ke petugas.');
    }

    public function konfirmasiBayar(Peminjaman $peminjaman)
    {
        abort_if($peminjaman->status !== 'fine_pending', 403);

        $peminjaman->update(['status' => 'closed']);

        
        ActivityLog::log('approve', 'pengembalian', "Konfirmasi bayar denda peminjaman ID: {$peminjaman->id}");

        return redirect()->route('pengembalian.denda')
            ->with('success', 'Pembayaran denda berhasil dikonfirmasi.');
    }

    public function dendaUser()
    {
        $data = Peminjaman::with(['alat', 'unit', 'pengembalian'])
            ->where('user_id', Auth::id())
            ->whereIn('status', ['fined', 'fine_pending'])
            ->orderByDesc('created_at')->paginate(15);
        return view('peminjaman.denda', compact('data'));
    }

    public function dendaPetugas()
    {
        $dataReturning = Peminjaman::with(['peminjam.detail', 'alat', 'unit'])
            ->where('status', 'returning')->orderByDesc('created_at')->get();

        $dataDenda = Peminjaman::with(['alat', 'unit', 'peminjam.detail', 'pengembalian'])
            ->whereIn('status', ['fined', 'fine_pending'])->orderByDesc('created_at')->get();

        return view('petugas.pengembalian', compact('dataReturning', 'dataDenda'));
    }

    public function riwayat()
    {
        $search = request('search');

        $data = Peminjaman::with(['alat', 'unit', 'user.detail', 'pengembalian.unitCondition'])
            ->where('status', 'closed')
            ->when($search, function ($q) use ($search) {
                $q->whereHas('alat', fn($q) => $q->where('name', 'like', "%$search%"))
                    ->orWhereHas('user.detail', fn($q) => $q->where('name', 'like', "%$search%"))
                    ->orWhere('unit_code', 'like', "%$search%");
            })
            ->paginate(15);

        return view('petugas.riwayat', compact('data'));
    }

    
}
