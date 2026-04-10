<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    protected $table = 'tools';
    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'location_code',
        'name',
        'item_type',
        'description',
        'code_slug',
        'photo_path',
        'created_at',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'location_code', 'location_code');
    }

    public function units() 
    {
        return $this->hasMany(ToolUnit::class, 'tool_id');
    }

}
