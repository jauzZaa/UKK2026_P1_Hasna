<?php

namespace App\Exports;

use App\Models\Alat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AlatExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Alat::with(['category', 'lokasi'])->get()
            ->map(function ($alat, $i) {
                return [
                    $i + 1,
                    $alat->code_slug,
                    $alat->name,
                    $alat->item_type,
                    $alat->category->name ?? '-',
                $alat->lokasi->name ?? '-',
                    $alat->price,
                    $alat->description ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Alat',
            'Nama Alat',
            'Tipe Item',
            'Kategori',
            'Lokasi',
            'Price',
            'Deskripsi'
        ];
    }
}
