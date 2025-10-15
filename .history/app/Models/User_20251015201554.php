<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
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

    /**
     * Mark the user's email as verified.
     */
    public function markEmailAsVerified(): bool
    {
        if ($this->hasVerifiedEmail()) {
            return false;
        }
        $this->email_verified_at = now();
        return $this->save();
    }

    /**
     * Check if the user's email is verified.
     */
    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified_at);
    }
}