<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Category;
use App\Models\Lokasi;
use App\Models\BundleTool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlatController extends Controller
{
    public function tampil()
    {
        // Filter agar 'bundle_tool' tidak muncul di list utama tabel
        $data = Alat::with(['category', 'lokasi', 'bundleItems'])
            ->where('item_type', '!=', 'bundle_tool')
            ->get();

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
            'item_type'     => 'required|in:single,bundle',
            'price'         => 'required|integer|min:0',
            'description'   => 'required|string',
            'code_slug'     => 'required|string|max:15|unique:tools,code_slug',
            'photo_path'    => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoPath = $request->file('photo_path')->store('alat', 'public');

        $alat = Alat::create([
            'category_id'   => $request->category_id,
            'location_code' => $request->location_code,
            'name'          => $request->name,
            'item_type'     => $request->item_type,
            'price'         => $request->price,
            'description'   => $request->description,
            'code_slug'     => $request->code_slug,
            'photo_path'    => $fotoPath,
            'created_at'    => now(),
        ]);

        if ($request->item_type === 'bundle' && $request->bundle_tool_name) {
            foreach ($request->bundle_tool_name as $i => $nama) {
                if (!$nama) continue;

                $subAlat = Alat::create([
                    'category_id'   => $request->category_id,
                    'location_code' => $request->location_code,
                    'name'          => $nama,
                    'item_type'     => 'bundle_tool',
                    'price'         => $request->bundle_tool_price[$i] ?? 0, // ← harga dari form
                    'description'   => '-',
                    'code_slug'     => $request->code_slug . '-' . ($i + 1),
                    'photo_path'    => $fotoPath,
                    'created_at'    => now(),
                ]);

                BundleTool::create([
                    'bundle_id' => $alat->id,
                    'tool_id'   => $subAlat->id,
                    'qty'       => $request->bundle_qty[$i] ?? 1,
                ]);
            }
        }

        return redirect()->route('alat.tampil')->with('success', 'Alat berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $alat     = Alat::with('bundleTools.tool')->findOrFail($id);
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
            'price'         => 'required|integer|min:0',
            'description'   => 'nullable|string',
            'code_slug'     => 'required|string|max:15|unique:tools,code_slug,' . $id,
            'photo_path'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'category_id',
            'location_code',
            'name',
            'item_type',
            'price',
            'description',
            'code_slug',
        ]);

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

        \App\Models\BundleTool::where('bundle_id', $id)
            ->orWhere('tool_id', $id)
            ->delete();

        // 3. Hapus foto jika ada
        if ($alat->photo_path && Storage::disk('public')->exists($alat->photo_path)) {
            Storage::disk('public')->delete($alat->photo_path);
        }

        // 4. Baru hapus data alat utamanya
        $alat->delete();

        return redirect()->route('alat.tampil')->with('success', 'Alat dan relasinya berhasil dihapus!');
    }

    public function detail($id)
    {
        $alat = Alat::with('category', 'lokasi', 'units.latestCondition', 'bundleTools.tool')->findOrFail($id);
        return view('alat.detail', compact('alat'));
    }
}
