<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KategoriExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Category::all()->map(function ($item, $i) {
            return [
                $i + 1,
                $item->name,
                $item->description,
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Nama Kategori', 'Deskripsi'];
    }
}
