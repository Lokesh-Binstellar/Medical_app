<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phlebotomist extends Model
{
   protected $table = 'phlebotomists';

    protected $fillable = [
        'laboratory_id',
        'phlebotomists_name',
        'contact_number',
        'email',
        'city',
        'state',
        'pincode',
        'address',
        'username',
        'password',
    ];

    protected $hidden = ['password'];
}
