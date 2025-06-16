<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteAcceptLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'pharmacy_id',
        'requested_at',
        'accepted_at',
    ];
}
