<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Category;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlatController extends Controller
{
    public function tampil()
    {
        $data = Alat::with('category', 'lokasi')->get();
        return view('alat.tampil', compact('data'));
    }

    public function tambah()
    {
        $kategori = Category::all();
        $lokasi   = Lokasi::all();
        return view('alat.tambah', compact('kategori', 'lokasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'   => 'required|exists:categories,id',
            'location_code' => 'nullable|exists:locations,location_code',
            'name'          => 'required|string|max:255',
            'item_type'     => 'required|in:single,bundle,bundle_tool',
            'description'   => 'nullable|string',
            'code_slug'     => 'nullable|string',
            'photo_path'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['category_id', 'location_code', 'name', 'item_type', 'description', 'code_slug']);

        $data['created_at'] = now();

        if ($request->hasFile('photo_path')) {
            $data['photo_path'] = $request->file('photo_path')->store('alat', 'public');
        }

        Alat::create($data);

        return redirect()->route('alat.tampil')->with('success', 'Alat berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $alat     = Alat::findOrFail($id);
        $kategori = Category::all();
        $lokasi   = Lokasi::all();
        return view('alat.edit', compact('alat', 'kategori', 'lokasi'));
    }

    public function update(Request $request, $id)
    {
        $alat = Alat::findOrFail($id);

        $request->validate([
            'category_id'   => 'required|exists:categories,id',
            'location_code' => 'nullable|exists:locations,location_code',
            'name'          => 'required|string|max:255',
            'item_type'     => 'required|in:single,bundle,bundle_tool',
            'description'   => 'nullable|string',
            'code_slug'     => 'nullable|string',
            'photo_path'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['category_id', 'location_code', 'name', 'item_type', 'description', 'code_slug']);

        if ($request->hasFile('photo_path')) {
            if ($alat->photo_path && Storage::disk('public')->exists($alat->photo_path)) {
                Storage::disk('public')->delete($alat->photo_path);
            }

            $data['photo_path'] = $request->file('photo_path')->store('alat', 'public');
        }

        $alat->update($data);

        return redirect()->route('alat.tampil')->with('success', 'Alat berhasil diupdate!');
    }

    public function destroy($id)
    {
        $alat = Alat::findOrFail($id);

        // Hapus foto dari storage
        if ($alat->photo_path) {
            Storage::disk('public')->delete($alat->photo_path);
        }

        $alat->delete();
        return redirect()->route('alat.tampil')->with('success', 'Alat berhasil dihapus!');
    }
    public function detail($id)
    {
        $alat = Alat::with('category', 'lokasi', 'units.latestCondition')->findOrFail($id);
        return view('alat.detail', compact('alat'));
    }
}
