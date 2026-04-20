<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DetailPeminjamanExport implements FromCollection, WithHeadings
{
    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function collection()
    {
        return Peminjaman::with(['alat', 'petugas'])
            ->where('user_id', $this->userId)
            ->get()
            ->map(function ($item, $i) {

                return [
                    $i + 1,
                    $item->alat->name ?? '-',
                    \Carbon\Carbon::parse($item->loan_date)->format('d-m-Y'),
                    \Carbon\Carbon::parse($item->due_date)->format('d-m-Y'),
                    $item->purpose ?? '-',
                    $item->status ?? '-',
                    $item->petugas->detail->name ?? '-', 
                ];
            });
    }

    public function headings(): array
    {
        return [
            'No',
            'Alat',
            'Tanggal Pinjam',
            'Jatuh Tempo',
            'Tujuan',
            'Status',
            'Petugas'
        ];
    }
}
