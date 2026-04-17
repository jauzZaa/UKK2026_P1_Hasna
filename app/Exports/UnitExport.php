<?php

namespace App\Exports;

use App\Models\ToolUnit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UnitExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return ToolUnit::all()->map(function ($unit, $i) {
            return [
                $i + 1,
                "'" . $unit->code,   
                $unit->status,
                $unit->notes ?? '-',
                $unit->status, 
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Kode Unit', 'Status', 'Catatan', 'Kondisi'];
    }
}
