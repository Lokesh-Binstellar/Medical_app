<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carts extends Model
{
    protected $fillable = [
        'customer_id',
        'products_details'
    ];
}
