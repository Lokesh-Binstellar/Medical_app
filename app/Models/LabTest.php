<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabTest extends Model
{
    protected $fillable = [
        'name',
        'description',
        'organ',
        'price',
        'home_price',
        'contains',
        'gender',
        'reports_in',
        'sample_required',
        'preparation',
        'how_does_it_work',
        'sub_reports',
        'sub_report_details',
        'faq',
        'references',
    ];

//     public function laboratories()
// {
//     // A test can belong to many laboratories (many-to-many relationship)
//     return $this->belongsToMany(Laboratories::class, 'laboratory_test', 'id', 'id');
// }

}
