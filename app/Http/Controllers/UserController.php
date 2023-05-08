<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;

use DB;
use Hash;
use Auth;
use DataTables;

class UserController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:user-list', ['only' => ['index','show']]);
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit|user-profileEdit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
         // $this->middleware('permission:user-profileEdit', ['only' => ['profileedit','update']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query =  User::select('users.id','users.name','users.email','roles.name as rolename')
                ->leftjoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->leftjoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->orderBy('Users.created_at','DESC')
                ->get();
            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'user-list';
                $editGate = 'user-edit';
                $deleteGate = 'user-delete';
                $crudRoutePart = 'users';

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
        $roles = Role::pluck('name','name')->all();
        return view('users.create',compact('roles'));
    }

    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'roles' => 'required'
        ]);

        if($request['image']){
            $this->validate($request,[
                'image'=>'required|image|mimes:jpeg,png,jpg,gif|max:2048']);
            $image = $request->file('image');
            $new_name=rand().'.'.$image->getClientOriginalExtension();
            $image->move(public_path("uploads/users"),$new_name);
            $input = $request->all();
            $input['image'] = $new_name;
            $input['password'] = Hash::make($input['password']);
            $user = User::create($input);
        }else{
            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            $user = User::create($input);
        }

        $user->assignRole($request->input('roles'));
        return redirect()->route('users.index')
                        ->with('success','User '.$request['name']. ' added successfully.');
    }

    public function show($id)
    {
        $user = DB::table('users')
        ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
        ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
        ->select('users.*','roles.name as rn')
        ->where('users.id', $id)
        ->first();

        return view('users.show',compact('user'));
    }
    public function profileedit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        $designations = Designation::pluck('name','id')->all();
        return view('profile.edit',compact('user','roles','userRole','designations'));
    }
    public function profileShow($id)
    {
        $user = DB::table('users')
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
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        return view('users.edit',compact('user','roles','userRole'));
    }


    public function update(Request $request, $id)
    {
        request()->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'roles' => 'required'
        ]);

        $user = User::find($id);
        $input = $request->all();

        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input['password'] = $user['password'];
        }

        if(!empty($input['image'])){
            $this->validate($request,[
                'image'=>'required|image|mimes:jpeg,png,jpg,gif|max:2048']);

            if($user['image']!=""){
                unlink(public_path('uploads/users/'.$user['image']));
            }

            $image = $request->file('image');
            $new_name=rand().'.'.$image->getClientOriginalExtension();
            $image->move(public_path("uploads/users"),$new_name);
            $input['image'] = $new_name;
            $user->update($input);
        }else{
             $input['image'] = $user['image'];
             $user->update($input);
        }

        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success','User '.$request['name']. ' updated successfully');
    }


    public function destroy(Request $request)
    {
        $ids = $request->ids;
        $checkId = Auth::user()->id;

        if($checkId==$ids){
            return response()->json(['error'=> 'This is logged in user, cannot be deleted']);
        }else{
            $user = User::find($ids);
             if($user['image']!=""){
                unlink(public_path('uploads/users/'.$user['image']));
            }
            $data = DB::table("users")->whereIn('id',explode(",",$ids))->delete();
            return response()->json(['success'=>$data." User deleted successfully."]);
        }
    }




}
