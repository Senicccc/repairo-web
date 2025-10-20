<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    use HasFactory;

protected $fillable = [
    'phone',
    'device_type',
    'brand',
    'model',
    'damage_description',
    'status',
    'diagnosis',
    'tracking_id',
    'technician',
    'cost',
];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($repair) {
            $last = self::latest('id')->first();
            $next = $last ? intval(substr($last->tracking_id, -4)) + 1 : 1;
            $repair->tracking_id = 'SRV' . date('Ymd') . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
        });
    }
}