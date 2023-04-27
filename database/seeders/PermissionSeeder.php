<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            
            'permission-list',
            'permission-create',
            'permission-edit',
            'permission-delete',

            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            'user-list',
            'user-create',
            'user-edit',
            'user-delete',

            'vehicle_type-list',
            'vehicle_type-create',
            'vehicle_type-edit',
            'vehicle_type-delete',

            'time_slot-list',
            'time_slot-create',
            'time_slot-edit',
            'time_slot-delete',

            'item-list',
            'item-create',
            'item-edit',
            'item-delete',

            'addon-list',
            'addon-create',
            'addon-edit',
            'addon-delete',

            'rate_list-list',
            'rate_list-create',
            'rate_list-edit',
            'rate_list-delete',

            'service-list',
            'service-create',
            'service-edit',
            'service-delete',

            'customer-list',
            'customer-create',
            'customer-edit',
            'customer-delete',

            'customer_type-list',
            'customer_type-create',
            'customer_type-edit',
            'customer_type-delete',

            'wash_house-list',
            'wash_house-create',
            'wash_house-edit',
            'wash_house-delete',

            'distribution_hub-list',
            'distribution_hub-create',
            'distribution_hub-edit',
            'distribution_hub-delete',

            'complaint-list',
            'complaint-create',
            'complaint-edit',
            'complaint-delete',

            'area-list',
            'area-create',
            'area-edit',
            'area-delete',

            'zone-list',
            'zone-create',
            'zone-edit',
            'zone-delete',

            'rider-list',
            'rider-create',
            'rider-edit',
            'rider-delete',

            'order-list',
            'order-create',
            'order-edit',
            'order-delete',

            'order_detail-list',
            'order_detail-create',
            'order_detail-edit',
            'order_detail-delete',

            'order_verify-list',
            'order_verify-create',
            'order_verify-edit',
            'order_verify-delete',

            'order_inspect-list',
            'order_inspect-create',
            'order_inspect-edit',
            'order_inspect-delete',

            'Wash_house_order-list',
            'Wash_house_order-create',
            'Wash_house_order-edit',
            'Wash_house_order-delete',

            'Wash_house_summary-list',
            'Wash_house_summary-create',
            'Wash_house_summary-edit',
            'Wash_house_summary-delete',

         ];
         foreach ($permissions as $permission) {
              Permission::create(['name' => $permission]);
         }
     }
}
