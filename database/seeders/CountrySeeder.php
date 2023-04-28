<?php

namespace Database\Seeders;
use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run()
    {
        $data = [
            'Pakistan',
            'Bangladesh',
            'India'
         ];
         foreach ($data as $val) {
            Country::create(['name' => $val]);
         }
     }
}
