<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otcmedicine extends Model
{
    protected $fillable = [
       'otc_id',
        'name',
        'breadcrumbs',
        'manufacturers',
        'type',
        'packaging',
        'package',
        'qty',
        'product_form',
        'product_highlights',
        'information',
        'key_ingredients',
        'key_benefits',
        'directions_for_use',
        'safety_information',
        'manufacturer_address',
        'country_of_origin',
        'manufacturer_details',
        'marketer_details',
        'image_url',
        'category'
    ];
}
