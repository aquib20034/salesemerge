<?php

namespace Database\Seeders;
use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run()
    {
        $countries = [
            'Pakistan',
            'Bangladesh',
            'India'
         ];

         $cities = [
            'Mirpurkhas',
            'Hyderabad',
            'Karachi'
         ];
         foreach ($countries as $key => $country) {
            City::create([
                            'name'          => $country,
                            'country_id'    => $cities[$key],
                        ]);
         }
     }
}
