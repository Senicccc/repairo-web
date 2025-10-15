<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'repair_id',
        'amount',
        'method',
        'status',
    ];

    public function repair()
    {
        return $this->belongsTo(Repair::class);
    }
}