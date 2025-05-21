<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phlebotomist extends Model
{
    protected $fillable = ['name', 'contact_number', 'laboratory_id'];
}
