<?php
namespace App\Http\Controllers;

use DB;
use Hash;
use Auth;
use DataTables;
use App\Models\User;
use App\Models\Company;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    var $folder_path;
    function __construct()
    {
        $this->folder_path = "uploads/users/";
         $this->middleware('permission:user-list', ['only' => ['index','show']]);
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit|user-profileEdit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
         // $this->middleware('permission:user-profileEdit', ['only' => ['profileedit','update']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query =  User::select('users.id','users.name','users.email','roles.name as rolename','users.mobile_no','users.active')
                ->leftjoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->leftjoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->orderBy('Users.created_at','DESC')
                ->get();
            $table = DataTables::of($query);

            $table->addColumn('srno', '');
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate       = 'user-list';
                $editGate       = 'user-edit';
                $deleteGate     = 'user-delete';
                $crudRoutePart  = 'users';

                return view('partials.datatableActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        return view('users.index');

    }

    public function create()
    {
        // $roles  = Role::where('id','!=',1)->pluck('name','name')->all();
        $company_id = Auth::user()->company_id;

        $roles      = Role::pluck('name','name')->all();
        $companies  = Company::where('id',$company_id)->pluck('name','id')->all();
        $branches   = Branch::where('company_id',$company_id)->pluck('name','id')->all();

        return view('users.create',compact('roles','companies','branches'));
    }

    public function store(UserRequest $request)
    {
        // Retrieve the validated input data...
        $validated    = $request->validated();

        // get all request
        $input       = $request->all();

        // uploading image
        if((!empty($input['profile_pic'])) ){
            $input['profile_pic']   =  ($this->uploadNewImage($request->file('profile_pic')));
        }

        // creating encrypted password
        $input['password']          = Hash::make($input['password']);

        // store new entity
        $data                       = User::create($input);
        
        // assign role 
        $data->assignRole($request->input('roles'));


        return redirect()->route('users.index')
                        ->with('success','User '.$request['name']. ' added successfully.');

        // return response()->json(['success'=>$request['name']. ' added successfully.']);
    }

    public function show($id)
    {
        $data           = DB::table('users')
                            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                            ->select('users.*','roles.name as rn')
                            ->where('users.id', $id)
                            ->first();

        $data           = User::findOrFail($id);
      
        return view('users.show',compact('data'));
    }

    public function profileedit($id)
    {
        $user           = User::find($id);
        $roles          = Role::pluck('name','name')->all();
        $userRole       = $user->roles->pluck('name','name')->all();
        $designations   = Designation::pluck('name','id')->all();

        return view('profile.edit',compact('user','roles','userRole','designations'));
    }

    public function profileShow($id)
    {
        $user           = DB::table('users')
                            ->join('designations', 'designations.id', '=', 'users.designation_id')
                            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                            ->select('users.id','users.name as un','users.username','users.email','users.proImage','designations.name as dn','roles.name as rn','users.created_at','users.updated_at')
                            ->where('users.id', $id)
                            ->first();
        return view('profile.show',compact('user'));
    }

    public function edit($id)
    {
        $data           = User::findorFail($id);
        $roles          = array();
   
        if($id == 1){       // user id = 1 ==> Super Admin
            $roles      = Role::pluck('name','name')->all();
        }else{
            $roles      = Role::where('id','!=',1)->pluck('name','name')->all();
            $userRoleId = $data->roles->pluck('id')->first();

            if($userRoleId !=2){  // role id = 2 ==> Admin
                $roles      = Role::where('id',$userRoleId)->pluck('name','name')->all();
            }

        }

        $company_id = Auth::user()->company_id;

        $roles      = Role::pluck('name','name')->all();
        $companies  = Company::where('id',$company_id)->pluck('name','id')->all();
        $branches   = Branch::where('company_id',$company_id)->pluck('name','id')->all();
        
   
        $userRole        = $data->roles->pluck('name','name')->all();

        return view('users.edit',compact('data','roles','userRole','companies','branches'));
    }


    public function update(UserRequest $request, $id)
    {
        // Retrieve the validated input data...
        $validated  = $request->validated();

        // get all request
        $data       = User::findOrFail($id);
        $input      = $request->all();

        // dd($input);

        // if active is not set, make it in-active
        if(!(isset($input['active']))){
            $input['active'] = 0;
        }

        // password 
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input['password'] = $data['password'];
        }

        // image
        if(!empty($input['profile_pic'])){
            $this->deleteExistingImage((basename($data['profile_pic'])));
            $input['profile_pic']   =  ($this->uploadNewImage($request->file('profile_pic')));
        }

        // update the entity
        $data->update($input);

        // delete previous roles
        DB::table('model_has_roles')->where('model_id',$id)->delete();

        // assign new roles
        $data->assignRole($request->input('roles'));


        
        return redirect()->route('users.index')
                        ->with('success','User '.$request['name']. ' updated successfully.');
        // return response()->json(['success'=>$input['name']. ' updated successfully.']);
    }


    public function destroy(Request $request)
    { 
        if($request->ids == 1){
            return response()->json(['error'=> 'This is Super-Admin and cannot be deleted']);
        }

        if($request->ids == 2){
            return response()->json(['error'=> 'This is Admin and cannot be deleted']);
        }


        $ids        = $request->ids;
        $checkId    = Auth::user()->id;

        if($checkId == $ids){
            return response()->json(['error'=> 'This is logged in user, cannot be deleted']);
        }else{
            $record     = User::findorFail($request->ids);
                          $this->deleteExistingImage((basename($record['profile_pic'])));
            $data       = $record->delete();
            return response()->json(['success'=>$data." User deleted successfully."]);
        }
    }

    private function uploadNewImage($new_image)
    {
        $new_name               = rand().'.'.$new_image->getClientOriginalExtension();
                                  $new_image->move(public_path($this->folder_path),$new_name);
        return $new_name;
    }

    private function deleteExistingImage($existing_image){
        if( ($existing_image != "") && ($existing_image != "no_image.png") ){
            if(file_exists( public_path($this->folder_path.$existing_image))){
                unlink(public_path($this->folder_path.$existing_image));
            }
        }
        return;
    }
}
