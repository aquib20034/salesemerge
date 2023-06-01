<?php
namespace App\Http\Controllers;

use DB;
use Hash;
use Auth;
use Gate;
use DataTables;
use App\Models\User;
use App\Models\Company;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;


class ProfileController extends Controller
{
    var $folder_path;
    function __construct()
    {
        $this->folder_path = "uploads/users/";
        $this->middleware('permission:profile-list', ['only' => ['index','show']]);
        $this->middleware('permission:profile-create', ['only' => ['create','store']]);
        $this->middleware('permission:profile-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:profile-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        return back()->with('permission','Invalid route');
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
                $viewGate       = 'profile-list';
                $editGate       = 'profile-edit';
                $deleteGate     = 'profile-delete';
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
        return back()->with('permission','Invalid route');
        // $roles  = Role::where('id','!=',1)->pluck('name','name')->all();
        $company_id = Auth::user()->company_id;

        $roles      = Role::pluck('name','name')->all();
        $companies  = Company::where('id',$company_id)->pluck('name','id')->all();
        $branches   = Branch::where('company_id',$company_id)->pluck('name','id')->all();

        return view('users.create',compact('roles','companies','branches'));
    }

    public function store(ProfileRequest $request)
    {
        return back()->with('permission','Invalid route');
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
        return back()->with('permission','Invalid route');

        $data           = DB::table('users')
                            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                            ->select('users.*','roles.name as rn')
                            ->where('users.id', $id)
                            ->first();

        $data           = User::findOrFail($id);
      
        return view('users.show',compact('data'));
    }
    public function edit($id)
    {
        $user_id    = Auth::user()->id;
        $company_id = Auth::user()->company_id;

        if($user_id!=$id){
            return back()->with('permission','Invalid route');
        }

        $data           = User::where('company_id',$company_id)->findorFail($id);
        return view('profiles.edit',compact('data'));
    }


    public function update(ProfileRequest $request, $id)
    {
        // Retrieve the validated input data...
        $validated  = $request->validated();

        $user_id    = Auth::user()->id;
        $company_id = Auth::user()->company_id;

        if($user_id != $id){
            return back()->with('permission','Id not matched');
        }

        // get all request
        $data       = User::where('company_id',$company_id)->findOrFail($id);
        $input      = $request->all();
        

        if(!empty($input['old_password'])){
            if(!(Hash::check($request->old_password, $data->password))) {
                return back()->withErrors(['old_password' => 'Old password is incorrect'])->with('permission','Old password is incorrect.');
            }
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

        
        return back()->with('success','Password changed successfully.');
        // return response()->json(['success'=>$input['name']. ' updated successfully.']);
    }


    public function destroy(Request $request)
    { 
        return back()->with('permission','Invalid route');
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
