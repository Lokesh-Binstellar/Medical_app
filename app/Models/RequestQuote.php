<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestQuote extends Model
{
    protected $fillable = [
        'customer_id',
        'pharmacy_id',
        'customer_address',
        'pharmacy_address_status',
        'prescription_id',
        'products_details',
    ];
}
