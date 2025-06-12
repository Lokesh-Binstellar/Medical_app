<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
   protected $table = 'lab_slot_bookings';
    
      protected $fillable = [
        'customer_id',
        'lab_slot_id',
        'status',
    ];



    public function customer()
{
    return $this->belongsTo(Customers::class,'customer_id');
}

public function labSlot()
{
    return $this->belongsTo(LabSlot::class);
}
}
