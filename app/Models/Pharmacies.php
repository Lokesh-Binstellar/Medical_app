<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Phar;

class Pharmacies extends Model
{
   protected $fillable = [
    'pharmacy_name',
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
    'status',
   
    ];





    public function user()
{
    return $this->belongsTo(User::class);
}
public function medicines()
{
    return $this->hasMany(Phrmacymedicine::class, 'phrmacy_id'); // Make sure the foreign key matches
}
}
