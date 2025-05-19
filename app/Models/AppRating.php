<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppRating extends Model
{
       protected $fillable = [
        'customer_id',
        'rating',
        'tags',
        'comment',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customers::class);
    }
}
