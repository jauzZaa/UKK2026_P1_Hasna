<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToolUnit extends Model
{
    protected $table = 'tool_units';
    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'code',
        'tool_id',
        'status',
        'notes',
        'created_at',
    ];

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'tool_id');
    }

    public function latestCondition()
    {
        return $this->hasOne(UnitCondition::class, 'unit_code', 'code')
            ->latestOfMany('recorded_at');
    }
}
