<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Company;
use App\Models\Branch;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'company_name'          => ['required', 'string', 'min:3', 'max:255'],
            'code'                  => ['required', 'string', 'min:2', 'max:255'],
            'mobile_no'             => ['required', 'numeric', 'unique:companies'],
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'              => ['required', 'string', 'min:8', 'confirmed']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

        // BEGIN :: Create new company
            $company            = Company::create([
                                        'name'          => isset($data['company_name']) ? $data['company_name'] : "", 
                                        'code'          => isset($data['code']) ? $data['code'] : "", 
                                        'mobile_no'     => isset($data['mobile_no']) ? $data['mobile_no'] : "", 
                                        'owner_name'    => isset($data['name']) ? $data['name'] : "", 
                                    ]);
        // END :: Create new company

        // BEGIN :: Create new branch
            $branch             = Branch::create([
                                        'name'          => "Main", 
                                        'company_id'    => $company->id 
                                    ]);
        // END :: Create new branch

        // BEGIN :: Create new role
            $company_role_name  = "Admin - ". (isset($data['company_name']) ? $data['company_name'] : "");
            $role               = Role::create([
                                        'name'          => $company_role_name, 
                                        'company_id'    => $company->id
                                    ]);
        // END :: Create new role


        // BEGIN :: Create new user
            $user               = User::create([
                                        'name'          => isset($data['name']) ? $data['name'] : "", 
                                        'email'         => isset($data['email']) ? $data['email'] : "", 
                                        'password'      => isset($data['password']) ? Hash::make($data['password']) : "",
                                        'company_id'    => $company->id,
                                        'branch_id'     => $branch->id
                                    ]);
        // END :: Create new company
  

        // BEGIN :: Assign role to user
            $permissions        = Permission::pluck('id','id')->all();
            $role->syncPermissions($permissions);
            $user->assignRole([$role->id]);
        // END :: Assign role to user
  
        return $user;
    }
}
