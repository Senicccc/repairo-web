<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    use HasFactory;

    // TAMBAHKAN CONSTANTS UNTUK STATUS
    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_DIAGNOSED = 'diagnosed';
    const STATUS_WAITING_PARTS = 'waiting_parts';
    const STATUS_FINISHED = 'finished';
    const STATUS_CANCELLED = 'cancelled';

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

    // TAMBAHKAN METHOD UNTUK STATUS
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_DIAGNOSED => 'Diagnosed',
            self::STATUS_WAITING_PARTS => 'Waiting Parts',
            self::STATUS_FINISHED => 'Finished',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    public static function getStatusColor($status)
    {
        return match($status) {
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_IN_PROGRESS => 'bg-blue-100 text-blue-800',
            self::STATUS_DIAGNOSED => 'bg-purple-100 text-purple-800',
            self::STATUS_WAITING_PARTS => 'bg-orange-100 text-orange-800',
            self::STATUS_FINISHED => 'bg-green-100 text-green-800',
            self::STATUS_CANCELLED => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isInProgress()
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    public function isDiagnosed()
    {
        return $this->status === self::STATUS_DIAGNOSED;
    }

    public function isWaitingParts()
    {
        return $this->status === self::STATUS_WAITING_PARTS;
    }

    public function isFinished()
    {
        return $this->status === self::STATUS_FINISHED;
    }

    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    // Relationships (yang sudah ada)
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

    public function repairSpareparts()
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