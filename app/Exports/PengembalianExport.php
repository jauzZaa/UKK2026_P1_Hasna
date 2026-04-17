<?php

namespace App\Exports;

use App\Models\Pengembalian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PengembalianExport implements FromCollection, WithHeadings
{
    
    public function collection()
    {
        
        return Pengembalian::with([
            'peminjaman.user.detail',
            'peminjaman.alat',
            'peminjaman.unit',
            'unitCondition'
        ])->get()->map(function ($item, $i) {
            $user = $item->peminjaman->user ?? null;

            return [
                $i + 1,
                ($user->detail->name ?? '-') . "\n" . ($user->email ?? '-'),
                $item->peminjaman->alat->name ?? '-',
                "'" . ($item->peminjaman->unit->code ?? '-'),
                $item->unitCondition->conditions ?? '-', // ✅ FIX DI SINI
                \Carbon\Carbon::parse($item->peminjaman->loan_date)->format('d M Y'),
                \Carbon\Carbon::parse($item->return_date)->format('d M Y'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Peminjam',
            'Alat',
            'Unit',
            'Kondisi',
            'Tgl Pinjam',
            'Tgl Kembali'
        ];
    }
}