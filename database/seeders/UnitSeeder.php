<?php

namespace Database\Seeders;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'Kg',
            'Items',
            'Piece'
         ];
         foreach ($data as $val) {
            Unit::create([
                            'name' => $val,
                            'company_id' => 1,
                            'branch_id' => 1,
                        ]);
         }
     }
}
