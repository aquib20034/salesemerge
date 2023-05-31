<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [

            'company-list',
            'company-create',
            'company-edit',
            'company-delete',

            'branch-list',
            'branch-create',
            'branch-edit',
            'branch-delete',
            
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

            'unit-list',
            'unit-create',
            'unit-edit',
            'unit-delete',

            'item-list',
            'item-create',
            'item-edit',
            'item-delete',

            'manufacturer-list',
            'manufacturer-create',
            'manufacturer-edit',
            'manufacturer-delete',

            'category-list',
            'category-create',
            'category-edit',
            'category-delete',

            'group-list',
            'group-create',
            'group-edit',
            'group-delete',

            'city-list',
            'city-create',
            'city-edit',
            'city-delete',

            'customer-list',
            'customer-create',
            'customer-edit',
            'customer-delete',

            'customer_type-list',
            'customer_type-create',
            'customer_type-edit',
            'customer_type-delete',

            'amount_type-list',
            'amount_type-create',
            'amount_type-edit',
            'amount_type-delete',

            'payment_method-list',
            'payment_method-create',
            'payment_method-edit',
            'payment_method-delete',

            'payment_type-list',
            'payment_type-create',
            'payment_type-edit',
            'payment_type-delete',

            'purchase-list',
            'purchase-create',
            'purchase-edit',
            'purchase-delete',

            'sell-list',
            'sell-create',
            'sell-edit',
            'sell-delete',

            'report-list',
            'report-create',
            'report-edit',
            'report-delete',

            'voucher-list',
            'voucher-create',
            'voucher-edit',
            'voucher-delete',

            'stock-list',
            'stock-create',
            'stock-edit',
            'stock-delete'
            
         ];
         foreach ($permissions as $permission) {
              Permission::create(['name' => $permission]);
         }
     }
}
