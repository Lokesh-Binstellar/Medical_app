<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'customer_id',
        'prescription_file',
        'prescription_status',
    ];
    public function customers()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }
}
