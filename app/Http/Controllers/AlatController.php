<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Lokasi;
use App\Models\BundleTool;
use App\Models\UnitCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlatController extends Controller
{
    public function tampil()
    {
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
                    'price'         => $request->bundle_tool_price[$i] ?? 0,
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

        // ← LOG
        ActivityLog::log('create', 'alat', "Menambah alat: {$alat->name} (Tipe: {$alat->item_type})");

        return redirect()->route('alat.tampil')->with('success', 'Alat berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $alat     = Alat::with('bundleItems')->findOrFail($id);
        $kategori = Category::all();
        $lokasi   = Lokasi::all();
        return view('alat.edit', compact('alat', 'kategori', 'lokasi'));
    }

    public function update(Request $request, $id)
    {
        $alat = Alat::findOrFail($id);

        $alat->update([
            'category_id'   => $request->category_id,
            'location_code' => $request->location_code,
            'name'          => $request->name,
            'price'         => $request->price,
            'description'   => $request->description,
            'item_type'     => $request->item_type,
        ]);

        if ($request->hasFile('photo_path')) {
            if ($alat->photo_path) Storage::disk('public')->delete($alat->photo_path);
            $alat->photo_path = $request->file('photo_path')->store('alat', 'public');
            $alat->save();
        }

        if ($request->item_type == 'bundle' && $request->has('bundle_names')) {
            BundleTool::where('bundle_id', $alat->id)->delete();
            foreach ($request->bundle_names as $key => $name) {
                if (!$name) continue;
                $subSlug = $alat->code_slug . '-sub-' . ($key + 1);
                $subAlat = Alat::where('code_slug', $subSlug)->first();
                if ($subAlat) {
                    $subAlat->update([
                        'name'          => $name,
                        'price'         => $request->bundle_prices[$key] ?? 0,
                        'category_id'   => $alat->category_id,
                        'location_code' => $alat->location_code,
                        'photo_path'    => $alat->photo_path,
                        'description'   => '-',
                    ]);
                } else {
                    $subAlat = Alat::create([
                        'name'          => $name,
                        'item_type'     => 'bundle_tool',
                        'category_id'   => $alat->category_id,
                        'location_code' => $alat->location_code,
                        'price'         => $request->bundle_prices[$key] ?? 0,
                        'description'   => '-',
                        'code_slug'     => $subSlug,
                        'photo_path'    => $alat->photo_path,
                        'created_at'    => now(),
                    ]);
                }
                BundleTool::create([
                    'bundle_id' => $alat->id,
                    'tool_id'   => $subAlat->id,
                    'qty'       => $request->bundle_qtys[$key] ?? 1,
                ]);
            }
        }

        // ← LOG
        ActivityLog::log('update', 'alat', "Mengubah data alat: {$alat->name}");

        return redirect()->route('alat.tampil')->with('success', 'Data berhasil diperbarui!');
    }

    private function hapusUnits(Alat $alat)
    {
        $alat->load('units');
        foreach ($alat->units as $unit) {
            UnitCondition::where('unit_code', $unit->code)->delete();
        }
        $alat->units()->delete();
    }

    public function destroy($id)
    {
        $alat = Alat::with('bundleItems')->findOrFail($id);

        BundleTool::where('bundle_id', $id)->delete();

        if ($alat->item_type === 'bundle') {
            foreach ($alat->bundleItems as $subAlat) {
                BundleTool::where('bundle_id', $subAlat->id)->orWhere('tool_id', $subAlat->id)->delete();
                $this->hapusUnits($subAlat);
                $subAlat->delete();
            }
        }

        BundleTool::where('tool_id', $id)->delete();
        $this->hapusUnits($alat);

        if ($alat->photo_path && Storage::disk('public')->exists($alat->photo_path)) {
            Storage::disk('public')->delete($alat->photo_path);
        }

        
        ActivityLog::log('delete', 'alat', "Menghapus alat: {$alat->name}");

        $alat->delete();

        return redirect()->route('alat.tampil')->with('success', 'Alat berhasil dihapus!');
    }

    public function detail($id)
    {
        $alat = Alat::with('category', 'lokasi', 'units.latestCondition', 'bundleTools.tool')->findOrFail($id);
        return view('alat.detail', compact('alat'));
    }
}
