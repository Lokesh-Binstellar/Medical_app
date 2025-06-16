<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Janaushadhi extends Model
{
    use HasFactory;

    protected $fillable = [
        'drug_code',
        'generic_name',
        'unit_size',
        'mrp',
        'group_name',
    ];
}
