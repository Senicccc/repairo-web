<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    protected $fillable = [
        'name',
        'category',
        'price',
        'stock',
        'description',
    ];
}