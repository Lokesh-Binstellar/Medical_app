<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestQuote extends Model
{
    protected $fillable = [
        'user_id',
        'pharmacy_id'
    ];
}
