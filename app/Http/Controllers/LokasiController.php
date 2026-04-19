<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Lokasi;
use App\Exports\LokasiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class LokasiController extends Controller
{

    public function exportExcel()
    {
        return Excel::download(new LokasiExport, 'data_lokasi.xlsx');
    }

    public function tampil()
    {
        $data = Lokasi::all();
        return view('lokasi.tampil', compact('data'));
    }

    public function tambah()
    {
        return view('lokasi.tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'location_code' => 'required|string|unique:locations,location_code',
            'name'          => 'nullable|string|max:100',
            'detail'        => 'nullable|string|max:100',
        ]);

        $lokasi = Lokasi::create([
            'location_code' => $request->location_code,
            'name'          => $request->name,
            'detail'        => $request->detail,
        ]);

        // ← LOG
        ActivityLog::log('create', 'lokasi', "Menambah lokasi: {$lokasi->name} ({$lokasi->location_code})");

        return redirect()->route('lokasi.tampil')->with('success', 'Lokasi berhasil ditambahkan!');
    }

    public function edit($location_code)
    {
        $lokasi = Lokasi::findOrFail($location_code);
        return view('lokasi.edit', compact('lokasi'));
    }

    public function update(Request $request, $location_code)
    {
        $lokasi = Lokasi::findOrFail($location_code);

        $request->validate([
            'name'   => 'nullable|string|max:100',
            'detail' => 'nullable|string|max:100',
        ]);

        $lokasi->update([
            'name'   => $request->name,
            'detail' => $request->detail,
        ]);

        
        ActivityLog::log('update', 'lokasi', "Mengubah lokasi: {$lokasi->name} ({$lokasi->location_code})");

        return redirect()->route('lokasi.tampil')->with('success', 'Lokasi berhasil diupdate!');
    }

    public function destroy($location_code)
    {
        $lokasi = Lokasi::findOrFail($location_code);

        if ($lokasi->alat()->count() > 0) {
            return redirect()->route('lokasi.tampil')
                ->with('error', 'Lokasi tidak bisa dihapus karena sudah digunakan oleh ' . $lokasi->alat()->count() . ' alat.');
        }

        ActivityLog::log('delete', 'lokasi', "Menghapus lokasi: {$lokasi->name} ({$lokasi->location_code})");

        $lokasi->delete();
        return redirect()->route('lokasi.tampil')->with('success', 'Lokasi berhasil dihapus!');
    }
}
