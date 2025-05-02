<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $fillable = [
        'customer_id',
        'name',
        'mobile_no',
        'address_line',
        'city',
        'state',
        'postal_code',
    ];
    public function customer()
    {
        return $this->belongsTo(Customers::class);
    }

}
