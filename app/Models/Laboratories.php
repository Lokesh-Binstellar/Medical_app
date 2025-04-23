<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laboratories extends Model
{
    protected $fillable = [
        'lab_name',
        'owner_name',
        'user_id',
        'email',
        'phone',
        'city',
        'state',
        'pincode',
        'address',
        'latitude',
        'longitude',
        'image',
        'username',
        'password',
        'license',
        'pickup',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
