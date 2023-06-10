<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Hash;
use App\Models\User;
use App\Models\Company;
use App\Models\Branch;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    public function run()
    {
        // BEGIN :: Create new company
            $company    = Company::create([
                                'name'          => "Salesemerge", 
                                'mobile_no'     => "03139120034", 
                                'owner_name'    => "Salesemerge", 
                            ]);
        // END :: Create new company

        // BEGIN :: Create new branch
            $branch     = Branch::create([
                            'name'          => "Main", 
                            'company_id'    => $company->id 
                        ]);
        // END :: Create new branch

        // BEGIN :: Create new role
            $role       = Role::create([
                                'name'          => "Super-Admin", 
                                'company_id'    => $company->id
                            ]);
        // END :: Create new role


        // BEGIN :: Create new user
            $user       = User::create([
                                    'name'          => 'admin', 
                                    'email'         => 'admin@gmail.com',
                                    'password'      => Hash::make('rootroot'),
                                    'company_id'    => $company->id,
                                    'branch_id'     => $branch->id
                                ]);
        // END :: Create new company


        // BEGIN :: Assign role to user
            $permissions        = Permission::pluck('id','id')->all();
            $role->syncPermissions($permissions);
            $user->assignRole([$role->id]);
        // END :: Assign role to user

        
    }
}
