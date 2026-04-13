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
        // 1. PERBAIKAN VALIDASI: Samakan 'in:...' dengan yang ada di view (Modal Tambah)
        $request->validate([
            'tool_id' => 'required|exists:tools,id',
            'status'  => 'required|in:available,nonactive,lent', // Sesuaikan pilihan di modal
            'notes'   => 'nullable|string'
        ]);

        $tool = Alat::findOrFail($request->tool_id);
        $prefix = $tool->code_slug;

        // 2. LOGIKA GENERATE KODE: Ambil nomor terakhir
        $lastUnit = ToolUnit::where('tool_id', $tool->id)
            ->orderBy('code', 'desc')
            ->first();

        if ($lastUnit) {
            // Mengambil 3 angka terakhir dari string kode (contoh: ALT-001 -> ambil 001)
            $lastNumber = (int) substr($lastUnit->code, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $number = str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        $code = $prefix . '-' . $number;

        // 3. SIMPAN KE DATABASE
        ToolUnit::create([
            'code'       => $code,
            'tool_id'    => $request->tool_id,
            'status'     => $request->status,
            'notes'      => $request->notes,
            'created_at' => now(), // Karena di model timestamps = false, kita isi manual
        ]);

        // 4. SIMPAN KONDISI AWAL
        UnitCondition::create([
            'id'          => (string) Str::uuid(),
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
        // Gunakan find karena primary key adalah string 'code'
        $unit = ToolUnit::where('code', $code)->firstOrFail();

        $request->validate([
            'status' => 'required|in:available,nonactive,lent',
            'notes'  => 'nullable|string',
        ]);

        $unit->update([
            'status' => $request->status,
            'notes'  => $request->notes,
        ]);

        if ($request->filled('conditions')) {
            UnitCondition::create([
                'id'          => (string) Str::uuid(),
                'unit_code'   => $unit->code,
                'conditions'  => $request->conditions,
                'notes'       => $request->condition_notes,
                'recorded_at' => now(),
            ]);
        }

        return redirect()->route('alat.detail', $unit->tool_id)
            ->with('success', 'Unit berhasil diupdate!');
    }
}
