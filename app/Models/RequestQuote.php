<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestQuote extends Model
{
    protected $fillable = [
        'customer_id',
        'pharmacy_id'
    ];
}
