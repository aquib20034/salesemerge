<?php

namespace Database\Seeders;
use App\Models\Manufacturer;
use Illuminate\Database\Seeder;

class ManufacturerSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'HP',
            'Samsung',
            'Dell'
         ];
         foreach ($data as $val) {
            Manufacturer::create([
                            'name' => $val,
                            'company_id' => 1,
                            'branch_id' => 1,
                        ]);

                     
         }
     }
}
