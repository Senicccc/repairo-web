<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'reward_type', 'reward_value', 'points_used', 'status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}