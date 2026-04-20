<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanPeminjamanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::with(['detail', 'peminjaman'])
            ->where('role', 'User') 
            ->get()
            ->map(function ($user, $i) {
                return [
                    $i + 1,
                    $user->detail->name ?? '-',
                    $user->email,
                    "'" . ($user->detail->no_hp ?? '-'),
                    $user->peminjaman->count(), 
                ];
            });
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Peminjam',
            'Email',
            'No. HP',
            'Jumlah Peminjaman'
        ];
    }
}
