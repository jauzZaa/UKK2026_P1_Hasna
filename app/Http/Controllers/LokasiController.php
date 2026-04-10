<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
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

        Lokasi::create([
            'location_code' => $request->location_code,
            'name'          => $request->name,
            'detail'        => $request->detail,
        ]);

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

        return redirect()->route('lokasi.tampil')->with('success', 'Lokasi berhasil diupdate!');
    }

    public function destroy($location_code)
    {
        Lokasi::findOrFail($location_code)->delete();
        return redirect()->route('lokasi.tampil')->with('success', 'Lokasi berhasil dihapus!');
    }
}
