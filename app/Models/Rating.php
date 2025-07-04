<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
     protected $fillable = ['customer_id', 'rating',  'comment','rateable_id', 'rateable_type'];

    public function rateable()
    {
        return $this->morphTo();
    }
}
