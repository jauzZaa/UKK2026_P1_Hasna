<?php

namespace App\Http\Controllers;

use App\Models\ToolUnit;
use App\Models\UnitCondition;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ToolUnitController extends Controller
{
    public function store(Request $request)
    {
        // 🔍 Validasi
        $request->validate([
            'tool_id' => 'required|exists:tools,id',
            'status' => 'required|in:available,borrowed,maintenance,damaged',
            'notes' => 'nullable|string'
        ]);

        // 🔹 Ambil data tool
        $tool = Alat::findOrFail($request->tool_id);

        // 🔹 Prefix dari tool
        $prefix = $tool->code_slug;

        // 🔹 Cari unit terakhir
        $lastUnit = ToolUnit::where('tool_id', $tool->id)
            ->orderBy('code', 'desc')
            ->first();

        if ($lastUnit) {

            // ambil nomor terakhir
            $lastNumber = (int) substr($lastUnit->code, -3);

            $newNumber = $lastNumber + 1;
        } else {

            $newNumber = 1;
        }

        // 🔹 Format nomor 001
        $number = str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        $code = $prefix . '-' . $number;

        ToolUnit::create([
            'code'       => $code,
            'tool_id'    => $request->tool_id,
            'status'     => $request->status,
            'notes'      => $request->notes,
            'created_at' => now(),
        ]);

        UnitCondition::create([
            'id'          => \Illuminate\Support\Str::uuid(),
            'unit_code'   => $code,
            'conditions'  => 'good',
            'notes'       => 'Kondisi awal unit',
            'recorded_at' => now(),
        ]);

        return redirect()->route('alat.detail', $request->tool_id)
            ->with('success', 'Unit berhasil ditambahkan!');
    }

    public function update(Request $request, $code)
    {
        $unit = ToolUnit::findOrFail($code);

        $request->validate([
            'status' => 'required|in:available,nonactive,lent',
            'notes'  => 'nullable|string',
        ]);

        $unit->update([
            'status' => $request->status,
            'notes'  => $request->notes,
        ]);

        // Simpan kondisi baru kalau diisi
        if ($request->filled('conditions')) {
            UnitCondition::create([
                'id'          => Str::uuid(),
                'unit_code'   => $unit->code,
                'conditions'  => $request->conditions,
                'notes'       => $request->condition_notes,
                'recorded_at' => now(),
            ]);
        }

        return redirect()->route('alat.detail', $unit->tool_id)
            ->with('success', 'Unit berhasil diupdate!');
    }

    public function destroy($code)
    {
        $unit = ToolUnit::findOrFail($code);
        $tool_id = $unit->tool_id;

        UnitCondition::where('unit_code', $code)->delete();
        $unit->delete();

        return redirect()->route('alat.detail', $tool_id)
            ->with('success', 'Unit berhasil dihapus!');
    }
}
