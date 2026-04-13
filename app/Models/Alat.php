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
        'price',
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

    public function bundleTools()
    {
        return $this->hasMany(BundleTool::class, 'bundle_id');
    }

    public function bundleItems()
    {
        return $this->belongsToMany(
            Alat::class,
            'bundle_tools',
            'bundle_id',
            'tool_id'
        )->withPivot('qty');
    }

}
