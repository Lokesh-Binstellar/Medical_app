<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageCategory extends Model
{
    protected $fillable = [

        'name',
        'package_image'
    ];

    public function labPackageAssignments()
    {
        return $this->hasMany(LabPackages::class, 'package_category_id');
    }
    
}
