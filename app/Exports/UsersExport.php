<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::with('detail')->get()->map(function ($user, $i) {
            return [
                $i + 1,
                $user->detail->name ?? '-',
                "'" . ($user->detail->nik ?? '-'),
                $user->email,
                $user->role,
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Nama', 'NIK', 'Email', 'Role'];
    }
}
