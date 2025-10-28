<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairSparepart extends Model
{
    use HasFactory;

    protected $fillable = [
        'repair_id',
        
        'sparepart_id',
        'quantity',
        'source',
        'price',
    ];

    public function repair()
    {
        return $this->belongsTo(Repair::class);
    }

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }
}