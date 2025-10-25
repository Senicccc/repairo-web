<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function repairs()
    {
        return $this->hasMany(Repair::class);
    }
    // app/Models/User.php
public function loyaltyRewards()
{
    return $this->hasMany(\App\Models\LoyaltyReward::class);
}

    
    protected static function booted()
    {
        static::updated(function ($user) {
            if ($user->isDirty('phone')) {
                \App\Models\Repair::where('user_id', $user->id)
                    ->update(['phone' => $user->phone]);
            }
        });
    }

}