<?php

namespace Database\Seeders;
use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{

    public function run()
    {
        $data = [
            'Pending',
            'Awaiting Payment',
            'Awaiting Fulfillment',
            'Awaiting Shipment',
            'Awaiting Pickup', 
            'Completed',
            'Shipped',
            'Cancelled',
            'Declined',
            'Refunded',
            'Disputed'
         ];
         foreach ($data as $val) {
            Status::create(['name' => $val]);
         }
     }
}
