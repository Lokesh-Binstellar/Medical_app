<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnPolicy extends Model
{
   use HasFactory;

    protected $table = 'return_policies';

    protected $fillable = ['description'];
}
