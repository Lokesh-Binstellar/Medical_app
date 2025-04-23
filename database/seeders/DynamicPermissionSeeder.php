<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class DynamicPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    }
    public function createPermissionsFor($name)
    {
        $actions = ['create', 'read', 'update', 'delete'];

        foreach ($actions as $action) {
            $permission = $action . ' ' . $name;
          Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
