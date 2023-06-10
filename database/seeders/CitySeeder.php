<?php

namespace Database\Seeders;
use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run()
    {

         $cities = [
            'Mirpurkhas',
            'Hyderabad',
            'Karachi'
         ];
         foreach ($cities as $key => $city) {
            City::create([
                            'name'          => $city,
                            'country_id'    => 1,
                        ]);
         }
     }
}
