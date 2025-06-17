<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'user_id', 'pharmacy_id', 'product_details', 'returned_items', 'items_price', 'platform_fees', 'total_price', 'delivery_charges', 'delivery_address', 'delivery_options', 'add_patient', 'payment_option', 'status', 'selected_pharmacy_address', 'selected_pharmacy_latlong', 'delivery_person_id','cancel_by','commission','quote_diff_minutes'];

    protected $casts = [
        'product_details' => 'array', // Automatically cast JSON to array
    ];

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'user_id', 'id');
    }
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacies::class, 'pharmacy_id', 'user_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'add_patient','id');
    }

    public function deliveryPerson()
    {
        return $this->belongsTo(User::class, 'delivery_person_id');
    }


}
