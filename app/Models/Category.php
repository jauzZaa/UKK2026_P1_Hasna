<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description', 
    ];

    public function alat()
    {
        return $this->hasMany(Alat::class, 'category_id');
    }
}
