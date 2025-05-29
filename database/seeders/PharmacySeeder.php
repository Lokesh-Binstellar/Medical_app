<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Pharmacies;
use App\Models\Role;

class PharmacySeeder extends Seeder
{
    public function run(): void
    {
        // Pharmacy role ID
        $roleId = Role::where('name', 'pharmacy')->value('id');

        // first lab
        $user = User::create([
            'name' => 'pharmacyuser',
            'email' => 'pharmacy@gmail.com',
            'password' => Hash::make('admin@1234'),
            'role_id' => $roleId,
        ]);

        // Sample data
        $pharmacies = [
                'user_id' => $user->id,
                'pharmacy_name' => 'Medkart Pharmacy',
                'owner_name' =>'Amit Verma',
                'email' => $user->email,
                'phone' => '9876543210',
                'city' => 'Ahmedabad',
                'state' => 'Gujarat',
                'pincode' => '380061',
                'address' => 'A, 11, Gulab Tower Rd, opposite JG International School',
                'latitude' => '23.063872657200033',
                'longitude' => '72.52617873253752',
                'license' => 'LIC12345',
                'image' => 'placeholder.jpg',
                'username' => $user->name,
                'password' => $user->password
    ];
        

      
       

            // Save pharmacy
            Pharmacies::create( $pharmacies);
        
    }
}
