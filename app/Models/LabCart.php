<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabCart extends Model
{
     protected $fillable = [
        'prescription_id',
        'customer_id',
        'test_details'
     ];
}
