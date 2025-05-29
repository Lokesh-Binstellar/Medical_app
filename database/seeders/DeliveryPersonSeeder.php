<?php

namespace Database\Seeders;

use App\Models\DeliveryPerson;
use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliveryPersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          $roleId = \App\Models\Role::where('name', 'delivery_person')->value('id');
           $user = User::create([
            'name' => 'Ravi Kumar',
            'email' => 'delivery@gmail.com',
            'password' => Hash::make('admin@1234'),
            'role_id' => $roleId,
        ]);

    $delivery = [
               'user_id'=>$user->id,
                'delivery_person_name' => 'Ravi Kumar',
                'email' => $user->email,
                'phone' => '9876543210',
                'city' => 'Lucknow',
                'state' => 'Uttar Pradesh',
                'pincode' => '226001',
                'address' => '22, Hazratganj Road',
                'username' => $user->name,
                'password' => $user->password,
          
    ];

  DeliveryPerson::create( $delivery);




    }
}
