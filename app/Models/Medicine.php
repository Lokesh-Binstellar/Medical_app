<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'product_id',
        'product_name',
        'marketer',
        'salt_composition',
        'medicine_type',
        'introduction',
        'benefits',
        'description',
        'how_to_use',
        'safety_advise',
        'if_miss',
        'packaging_detail',
        'package',
        'qty',
        'product_form',
        'prescription_required',
        'fact_box',
        'primary_use',
        'storage',
        'use_of',
        'common_side_effect',
        'alcohol_interaction',
        'pregnancy_interaction',
        'lactation_interaction',
        'driving_interaction',
        'kidney_interaction',
        'liver_interaction',
        'manufacturer_address',
        'country_of_origin',
        'q_a',
        'how_it_works',
        'interaction',
        'manufacturer_details',
        'marketer_details',
    ];
}
