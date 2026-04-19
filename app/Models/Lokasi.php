<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    protected $table = 'locations';
    protected $primaryKey = 'location_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'location_code',
        'name',
        'detail',
    ];

    public function alat()
    {
        return $this->hasMany(Alat::class, 'location_code', 'location_code');
    }
}
