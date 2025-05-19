<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
class Patient extends Model
{
    protected $fillable = ['customer_id', 'name', 'birth_date', 'gender'];
    protected $hidden = ['created_at', 'updated_at'];
    public function customer()
    {
        return $this->belongsTo(Customers::class);
    }
    protected function birthDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => \Carbon\Carbon::parse($value)->format('d/m/Y'), // Example: 19-05-2025
        );
    }
}
