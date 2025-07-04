<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabSlot extends Model
{
    use HasFactory;

    protected $fillable = ['eventStartDate', 'eventEndDate', 'is_active', 'laboratory_id'];

    protected $casts = [
        'eventStartDate' => 'datetime',
        'eventEndDate' => 'datetime',
    ];

    public function laboratory()
    {
        return $this->belongsTo(User::class, 'laboratory_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class,'lab_slot_id');
    }
}
