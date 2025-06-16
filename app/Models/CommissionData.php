<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionData extends Model
{
    protected $fillable = [
        'commonAmount',
        'gstRate',
        'commissionBelowAmount',
        'commissionAboveAmount',
    ];
}
