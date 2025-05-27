<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laboratories extends Model
{
    protected $fillable = [
        'lab_name',
        'owner_name',
        'user_id',
        'email',
        'phone',
        'city',
        'state',
        'pincode',
        'address',
        'latitude',
        'longitude',
        'image',
        'username',
        'password',
        'license',
        'gstno',
        'nabl_iso_certified',
        'test',
        'package_details',
        'pickup',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function labPackageAssignments()
    {
        return $this->hasMany(LabPackages::class, 'lab_id');
    }
    public function ratings()
    {
        return $this->morphMany(Rating::class, 'rateable');
    }


    //     public function labtests()
    // {
    //     // Assuming a laboratory has many tests (many-to-many relationship)
    //     return $this->belongsToMany(LabTest::class, 'lab_tests', 'id', 'id');
    // }

}
