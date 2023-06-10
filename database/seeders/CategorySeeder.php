<?php

namespace Database\Seeders;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $data = [
            'Electronic Devices',
            'Home Appliances'
         ];
         foreach ($data as $val) {
            Category::create([
                            'name' => $val,
                            'company_id' => 1,
                            'branch_id' => 1,
                        ]);
         }
     }
}
