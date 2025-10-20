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
        'payment_method',
        'status',
        'invoice_number',
        'payment_date',
    ];

    public function repair()
    {
        return $this->belongsTo(Repair::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->invoice_number)) {
                $last = self::latest('id')->first();
                $next = $last ? $last->id + 1 : 1;
                $payment->invoice_number = 'INV' . date('Ymd') . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
            }
            if (empty($payment->payment_date)) {
                $payment->payment_date = now();
            }
        });
    }

}