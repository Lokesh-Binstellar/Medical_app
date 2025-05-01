<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabPackages extends Model
{
    use HasFactory;

    protected $fillable = [
        'lab_id',
        'package_category_id',
        'package_name',
        'home_price',
        'price',
        'description',
    ];

    // Lab relationship (Assuming Lab model exists)
  // Relationship with Laboratories
public function laboratory()
{
    return $this->belongsTo(Laboratories::class, 'lab_id');
}

// Relationship with Package Category
public function packageCategory()
{
    return $this->belongsTo(PackageCategory::class, 'package_category_id');
}

}
