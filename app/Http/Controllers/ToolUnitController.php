<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ToolUnit;
use App\Models\UnitCondition;
use App\Models\Alat;
use Illuminate\Http\Request;
use App\Exports\UnitExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class ToolUnitController extends Controller
{

    public function exportExcel()
    {
        return Excel::download(new UnitExport, 'data_unit.xlsx');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tool_id' => 'required|exists:tools,id',
            'status'  => 'required|in:available,nonactive,lent',
            'notes'   => 'nullable|string',
        ]);

        $tool = Alat::findOrFail($request->tool_id);
        $prefix = $tool->code_slug;

        $lastUnit = ToolUnit::where('tool_id', $tool->id)->orderBy('code', 'desc')->first();
        $newNumber = $lastUnit ? ((int) substr($lastUnit->code, -3)) + 1 : 1;
        $code = $prefix . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        ToolUnit::create([
            'code'       => $code,
            'tool_id'    => $request->tool_id,
            'status'     => $request->status,
            'notes'      => $request->notes,
            'created_at' => now(),
        ]);

        UnitCondition::create([
            'id'          => (string) Str::uuid(),
            'unit_code'   => $code,
            'conditions'  => 'good',
            'notes'       => 'Kondisi awal unit',
            'recorded_at' => now(),
        ]);

        // ← LOG
        ActivityLog::log('create', 'tool_unit', "Menambah unit: {$code} untuk alat: {$tool->name}");

        return redirect()->route('alat.detail', $request->tool_id)->with('success', 'Unit berhasil ditambahkan!');
    }

    public function update(Request $request, $code)
    {
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

       
        ActivityLog::log('update', 'tool_unit', "Mengubah unit: {$code}, status: {$request->status}");

        return redirect()->route('alat.detail', $unit->tool_id)->with('success', 'Unit berhasil diupdate!');
    }

    public function destroy($code)
{
    $unit = ToolUnit::where('code', $code)->firstOrFail();

    if ($unit->status === 'lent') {
        return redirect()->back()
            ->with('error', 'Unit tidak bisa dihapus karena sedang dipinjam.');
    }

    $activeLoan = \App\Models\Peminjaman::where('unit_code', $code)
        ->whereNotIn('status', ['closed', 'rejected'])
        ->exists();

    if ($activeLoan) {
        return redirect()->back()
            ->with('error', 'Unit tidak bisa dihapus karena masih dalam proses peminjaman.');
    }

    UnitCondition::where('unit_code', $code)->delete();

    ActivityLog::log('delete', 'tool_unit', "Menghapus unit: {$code}");

    $toolId = $unit->tool_id;
    $unit->delete();

    return redirect()->route('alat.detail', $toolId)
        ->with('success', 'Unit berhasil dihapus.');
}
}
