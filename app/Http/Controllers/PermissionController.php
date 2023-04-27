<?php


namespace App\Http\Controllers;
use App\Models\Permission;
use Illuminate\Http\Request;
use DB;
use DataTables;

class PermissionController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:permission-list', ['only' => ['index','show']]);
         $this->middleware('permission:permission-create', ['only' => ['create','store']]);
         $this->middleware('permission:permission-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('permissions.index');
    }

    public function list()
    {
        $data = DB::table('permissions')
                    ->orderBy('permissions.name')
                    ->select('permissions.id','permissions.name')
                    ->get();
                    
        return DataTables::of($data)
                ->addColumn('action',function($data){
                 return 
                        '<div class="btn-group btn-group">
                          
                           
                         
                        </div>';
                    })
                ->addColumn('srno','')
                ->rawColumns(['srno','','action'])
                ->make(true);

    }
        // <a class="btn btn-info btn-sm" href="permissions/'.$data->id.'/edit" id="'.$data->id.'">
        //     <i class="fas fa-pencil-alt"></i>
        // </a>

    public function create()
    {
        return view('permissions.create');
    }


    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|unique:permissions,name',
        ]);
        Permission::create($request->all());
        return redirect()->route('permissions.create')
                        ->with('success','Permission '.$request['name']. ' added successfully.');
    }

     public function show(Permission $permission)
    {
        return view('permissions.show',compact('permission'));
    }


    public function edit(Permission $permission)
    {
        return view('permissions.edit',compact('permission'));
    }


    public function update(Request $request,$id)
    {
        $permission = Permission::findOrFail($id);
        request()->validate([
            'name' => 'required|unique:permissions,name,'. $id,
        ]);

        $permission->update($request->all());
        return redirect()->route('permissions.index')
                        ->with('success','Permission '.$request['name']. ' updated successfully');
    }

    public function destroy(Permission $permission)
    {
        $ids = $request->ids;
        $data = DB::table("permissions")->whereIn('id',explode(",",$ids))->delete();
        return response()->json(['success'=>$data." Permission deleted successfully."]);
    }

   
}
