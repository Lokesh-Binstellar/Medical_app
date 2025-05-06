<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Phrmacymedicine extends Model
{
    protected $fillable = [
        'medicine','total_amount','commission_amount', 'phrmacy_id','mrp_amount'
    ];

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }

   
    
}
