<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Additionalcharges extends Model
{
    protected $table = 'additional_charges';
    protected $fillable = ['platfrom_fee'];
}
