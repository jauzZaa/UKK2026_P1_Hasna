<?php

namespace App\Exports;

use App\Models\Lokasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LokasiExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Lokasi::all()->map(function ($item, $i) {
            return [
                $i + 1,
                "'" . $item->location_code, // 👈 biar ga jadi angka aneh di Excel
                $item->name,
                $item->description,
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Kode Lokasi', 'Nama Lokasi', 'Deskripsi'];
    }
}
