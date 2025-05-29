<?php

namespace Database\Seeders;

use App\Models\LabTest;
use App\Models\PackageCategory;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Laboratories;
use Illuminate\Support\Facades\Hash;

class LaboratoriesSeeder extends Seeder
{
    public function run()
    {
        $roleId = \App\Models\Role::where('name', 'laboratory')->value('id') ?? 2;

        // first lab
        $user1 = User::create([
            'name' => 'labuser1',
            'email' => 'lab@gmail.com',
            'password' => Hash::make('admin@1234'),
            'role_id' => $roleId,
        ]);


        Laboratories::create([
            'user_id' => $user1->id,
            'lab_name' => 'Sun Pathology Laboratory',
            'owner_name' => 'Dr. Raj Sharma',
            'email' => $user1->email,
            'phone' => '98765432101',
            'city' =>'Ahmedabad',
            'state' => 'Gujarat',
            'pincode' => '380060',
            'address' => '1stFF, Saptak House, Science City Rd, Science City, Sola',
            'latitude' => '23.072206649802762',
            'longitude' => '72.51558375299624',
            'image' => 'sun-pathology.avif',
            'username' => $user1->name,
            'password' => 'admin@1234',
            'license' => 'LIC1234567',
            'nabl_iso_certified' =>true,
            'pickup' => true,
            'gstno' => '27AAEPM1234F1Z5',
            
        ]);

       
    }
}
