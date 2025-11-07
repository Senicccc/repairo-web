<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    protected $fillable = [
        'brand',
        'model', 
        'name',
        'category',
        'price',
        'stock',
        'compatible_models'
    ];

    public function spareparts()
{
    return $this->hasMany(RepairSparepart::class);
}

}