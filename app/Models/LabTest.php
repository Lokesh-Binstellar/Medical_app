<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabTest extends Model
{
    protected $fillable = [
        'name',
        'description',
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
}
