<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'phone_brand',
    'phone_model',
    'imei',
    'complaint',
    'status',
    'technician',
    'cost'
];


    public function repair()
    {
        return $this->belongsTo(Repair::class);
    }
}