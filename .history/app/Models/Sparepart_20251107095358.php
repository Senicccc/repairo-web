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
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    public function spareparts()
{
    return $this->hasMany(RepairSparepart::class);
}

}