<?php

namespace Database\Seeders;
use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'LCDs',
            'Monitor',
            'Laptop'
         ];
         foreach ($data as $val) {
            Group::create([
                            'name' => $val,
                            'company_id' => 1,
                            'branch_id' => 1,
                        ]);
         }
     }
}
