<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'eventStartDate', 
        'eventEndDate', 
        'is_active',
        'laboratory_id'
    ];

  
    public function laboratory()
    {
        return $this->belongsTo(User::class, 'laboratory_id');
    }
}
