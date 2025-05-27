<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'pharmacy_id',
        'product_details',
        'items_price',
        'platform_fees',
        'total_price',
        'delivery_charges',
        'delivery_address',
        'delivery_options',
        'add_patient',
        'payment_option',
        'status',
        'selected_pharmacy_address',
        'selected_pharmacy_latlong',
        'delivery_person_id'
    ];

    protected $casts = [
        'product_details' => 'array', // Automatically cast JSON to array
    ];

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'user_id', 'id');
    }

}
