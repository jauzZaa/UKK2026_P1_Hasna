<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $table = 'returns';
    public $timestamps = false;

    protected $fillable = [
        'loan_id',
        'employee_id',
        'condition_id',
        'return_date',
        'notes',
        'created_at',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'loan_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function kondisi()
    {
        return $this->belongsTo(UnitCondition::class, 'condition_id', 'id');
    }

    public function unitCondition()
    {
        return $this->belongsTo(UnitCondition::class, 'condition_id');
    }
}
