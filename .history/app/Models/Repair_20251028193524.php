<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_name',
        'phone',
        'phone_brand',
        'phone_model',
        'imei',
        'complaint',
        'status',
        'diagnosis',
        'sparepart',
        'tracking_id',
        'technician',
        'technician_id',
        'cost',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];



    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function technicianUser()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

        public function spareparts()
    {
        return $this->hasMany(RepairSparepart::class);
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