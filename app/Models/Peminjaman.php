<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'loans';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'tool_id',
        'unit_code',
        'employee_id',
        'status',
        'loan_date',
        'due_date',
        'purpose',
        'user_notes', // tambah ini (setelah bikin migration-nya)
        'notes',
        'created_at',
    ];

    // Relasi ke user peminjam (untuk blade: $p->user->detail)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Alias user (nama lama, bisa tetap dipakai)
    public function peminjam()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'tool_id');
    }

    public function unit()
    {
        return $this->belongsTo(ToolUnit::class, 'unit_code', 'code');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function pengembalian()
    {
         return $this->hasOne(Pengembalian::class, 'loan_id');
     }
}
