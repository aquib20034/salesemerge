<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            PermissionSeeder::class,
            UserSeeder::class,
            UnitSeeder::class,
            StatusSeeder::class,
            GroupSeeder::class,
            CategorySeeder::class,
            ManufacturerSeeder::class,
            ItemSeeder::class,
            Customer_typeSeeder::class
            
        ]);
    }
}
