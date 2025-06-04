<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Customers extends Model
{
    use Notifiable;
    protected $fillable = [
        'mobile_no',
        'otp_code',
        'otp_expires_at',
        'firstName',
        'lastName',
        'email',
        'mobile_no_verified_at',
    ];

    protected $hidden = [
        'otp_code',
    ];

    protected $casts = [
        'otp_expires_at' => 'datetime',
        'mobile_no_verified_at' => 'datetime',
    ];

    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class,'customer_id');
    }
    public function prescription()
    {
        return $this->hasMany(Prescription::class);
    }
    public function orders()
{
    return $this->hasMany(Order::class, 'user_id');
}

    public function appRatings()
{
    return $this->hasMany(AppRating::class);
}


}


