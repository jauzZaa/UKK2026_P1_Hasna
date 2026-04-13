<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BundleTool extends Model
{
    protected $table = 'bundle_tools';
    public $timestamps = false;

    protected $fillable = [
        'bundle_id',
        'tool_id',
        'qty',
    ];

    public function bundle()
    {
        return $this->belongsTo(Alat::class, 'bundle_id');
    }

    public function tool()
    {
        return $this->belongsTo(Alat::class, 'tool_id');
    }
}
