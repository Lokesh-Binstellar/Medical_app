<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryPerson extends Model
{

     protected $table = 'delivery_person';
   protected $fillable = [
        'delivery_person_name',
        'user_id',
        'email',
        'phone',
        'city',
        'state',
        'pincode',
        'address',
        // 'latitude',
        // 'longitude',
        'username',
        'password',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ratings()
    {
        return $this->morphMany(Rating::class, 'rateable');
    }
}
