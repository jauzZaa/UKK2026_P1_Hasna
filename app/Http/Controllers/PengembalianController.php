<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\ToolUnit;
use App\Models\UnitCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PengembalianController extends Controller
{
    // Petugas - lihat semua data pengembalian yang perlu dikonfirmasi
    public function index()
    {
        $data = Peminjaman::with(['alat', 'unit', 'user.detail'])
            ->where('status', 'returning') 
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('petugas.pengembalian', compact('data'));
    }
    // User - ajukan pengembalian (tombol Kembalikan)
    public function ajukan(Request $request, Peminjaman $peminjaman)
    {
        abort_if($peminjaman->user_id !== Auth::id(), 403);
        abort_if($peminjaman->status !== 'active', 403);

        // VALIDASI FOTO
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // SIMPAN FOTO
        $path = $request->file('photo')->store('pengembalian', 'public');

        // UPDATE STATUS + SIMPAN FOTO
        $peminjaman->update([
            'status' => 'returning',
            'return_photo' => $path // pastikan kolom ini ada
        ]);

        return redirect()->route('peminjaman.tampil')
            ->with('success', 'Pengembalian diajukan, menunggu konfirmasi petugas');
    }

    // Petugas - konfirmasi pengembalian
    public function konfirmasi(Request $request, Peminjaman $peminjaman)
    {
        abort_if($peminjaman->status !== 'returning', 403);

        $request->validate([
            'conditions' => 'required|in:good,broken,maintenance',
            'notes'      => 'nullable|string|max:1000',
        ]);

        // Buat record kondisi unit
        $conditionId = (string) Str::uuid();
        UnitCondition::create([
            'id'          => $conditionId,
            'unit_code'   => $peminjaman->unit_code,
            'return_id'   => null, // diupdate setelah return dibuat
            'conditions'  => $request->conditions,
            'notes'       => $request->notes ?? 'Pengembalian alat',
            'recorded_at' => now(),
        ]);

        // Buat record pengembalian
        $pengembalian = Pengembalian::create([
            'loan_id'      => $peminjaman->id,
            'employee_id'  => Auth::id(),
            'condition_id' => $conditionId,
            'return_date'  => now()->toDateString(),
            'notes'        => $request->notes,
            'created_at'   => now(),
        ]);

        // Update return_id di unit_condition
        UnitCondition::where('id', $conditionId)
            ->update(['return_id' => $pengembalian->id]);

        // Update status loan jadi closed
        $peminjaman->update(['status' => 'closed']);

        // Update status unit
        $newStatus = $request->conditions === 'good' ? 'available' : 'nonactive';
        ToolUnit::where('code', $peminjaman->unit_code)
            ->update(['status' => $newStatus]);

        return redirect()->route('petugas.pengembalian')
            ->with('success', 'Pengembalian berhasil dikonfirmasi.');
    }

    public function riwayat()
    {
        $data = Peminjaman::with([
            'alat',
            'unit',
            'user.detail',
            'pengembalian.unitCondition'
        ])
            ->where('status', 'closed')
            ->paginate(15);

        return view('petugas.riwayat', compact('data'));
    }

   
}
