<?php
namespace App\Http\Controllers;
use DB;
use Auth;
use DataTables;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:role-list', ['only' => ['index','store']]);
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {

            $company_id = Auth::user()->company_id;
            $query      = Role::where('company_id',$company_id)->orderBy('roles.name','ASC')->get();
            $table      = DataTables::of($query);

            $table->addColumn('srno', '');
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate       = 'role-list';
                $editGate       = 'role-edit';
                $deleteGate     = 'role-delete';
                $crudRoutePart  = 'roles';

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
        return view('roles.index');
    }

    public function create()
    {
        $permission = Permission::get();
        return view('roles.create',compact('permission'));
    }


    public function store(RoleRequest $request)
    {
        $validated          = $request->validated();
        $company_id         = Auth::user()->company_id;
        $company_role_name  = $request->input('name')." - ". (isset(Auth::user()->company->name) ? (Auth::user()->company->name) : "");


        $flag               = Role::where('name',$company_role_name)->where('company_id',$company_id)->first();

        if(isset($flag->id)){
            return back()->with('permission','Role already exists')->withInput($request->input());
        }

        
        $role               = Role::create([
                                'name'          => $company_role_name, 
                                'company_id'    => $company_id
                            ]);
        $role->syncPermissions($request->input('permission'));

        return redirect()->route('roles.index')
                        ->with('success','Role '.$request['name']. ' added successfully.');
    }

    public function show($id)
    {
        $company_id         = Auth::user()->company_id;

        $role               = Role::where('company_id',$company_id)->findOrFail($id);
        $rolePermissions    = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
                                ->where("role_has_permissions.role_id",$id)
                                ->get();

        return view('roles.show',compact('role','rolePermissions'));
    }


    public function edit($id)
    {
        $company_id         = Auth::user()->company_id;
        $role               = Role::where('company_id',$company_id)->findOrFail($id);
        $permission         = Permission::get();
        $rolePermissions    = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
                                ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                                ->all();

        return view('roles.edit',compact('role','permission','rolePermissions'));
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'          => 'required|unique:roles,name,'. $id,
            'permission'    => 'required',
        ]);

        $company_input      = $request->input('name');
        $company_id         = Auth::user()->company_id;
        $role               = Role::where('company_id',$company_id)->findOrFail($id);
        $company_name       = (isset(Auth::user()->company->name) ? Auth::user()->company->name : "");
        $company_role_name  = $request->input('name')." - ". $company_name;

        if (str_contains($company_input, $company_name)) { // if company_input has already company name -- so need to add company name again 
            $company_role_name  = $request->input('name');
        }

        $role->name = $company_role_name;
        $role->save();

        $role->syncPermissions($request->input('permission'));
        return redirect()->route('roles.index')
                        ->with('success','Role '.$request['name']. ' updated successfully');
    }

    public function destroy(Request $request)
    {
        $ids = $request->ids;
        if($ids==1){
            return response()->json(['error'=> 'This is logged in user role, cannot be deleted']);
        }else{
            $data = DB::table("roles")->whereIn('id',explode(",",$ids))->delete();
            return response()->json(['success'=>$data." Roles deleted successfully."]);
        }
    }


}
