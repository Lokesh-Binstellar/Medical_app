<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class MobileUser extends Authenticatable
{
    use HasApiTokens, HasFactory;
    protected $fillable = [
        'phone',
        'otp_code',
        'otp_expires_at',
        'phone_verified_at',
    ];
    protected $hidden = [
        'otp_code',   
        'remember_token',
    ];
    protected $casts = [
        'otp_expires_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];
}
