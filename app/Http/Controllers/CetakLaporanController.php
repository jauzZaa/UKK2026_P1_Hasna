<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Exports\LaporanPeminjamanExport;
use App\Exports\DetailPeminjamanExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class CetakLaporanController extends Controller
{


    public function exportLaporan()
    {
        return Excel::download(new LaporanPeminjamanExport, 'laporan_peminjaman.xlsx');
    }

    public function exportDetail($id)
    {
        return Excel::download(new DetailPeminjamanExport($id), 'detail_peminjaman.xlsx');
    }

    public function tampil(Request $request)
    {
        abort_if(auth()->user()->role !== 'Admin', 403);

        $search = $request->search;

        $data = User::with(['detail', 'peminjaman'])
            ->where('role', 'User')
            ->when($search, function ($q) use ($search) {
                $q->whereHas('detail', fn($q) => $q->where('name', 'like', "%$search%"))
                    ->orWhere('email', 'like', "%$search%");
            })
            ->get()
            ->map(function ($user) {
                $user->jumlah_peminjaman = $user->peminjaman->count();
                return $user;
            });

        return view('CetakLaporan.tampil', compact('data'));
    }

    public function detail($id)
    {
        abort_if(auth()->user()->role !== 'Admin', 403);

        $user = User::with(['detail', 'peminjaman.alat', 'peminjaman.petugas.detail'])
            ->findOrFail($id);

        return view('CetakLaporan.detail', compact('user'));
    }


}
