<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Unit;
use DB;
use DataTables;

class UnitController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:unit-list', ['only' => ['index','show']]);
         $this->middleware('permission:unit-create', ['only' => ['create','store']]);
         $this->middleware('permission:unit-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:unit-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        return view('units.index');
    }

    public function list()
    {
        $data = DB::table('units')
                ->orderBy('units.created_at','DESC')
                ->select('units.*')
                ->get();
        return 
            DataTables::of($data)
                ->addColumn('action',function($data){
                    return '
                    <div class="btn-group btn-group">
                        <a class="btn btn-info btn-sm" href="units/'.$data->id.'">
                            <i class="fa fa-eye"></i>
                        </a>
                       ';
                })
                ->addColumn('srno','')
                ->rawColumns(['srno','','action'])
                ->make(true);
    }
            // <a class="btn btn-info btn-sm" href="units/'.$data->id.'/edit" id="'.$data->id.'">
            //     <i class="fas fa-pencil-alt"></i>
            // </a>

            // <button
            //     class="btn btn-danger btn-sm delete_all"
            //     data-url="'. url('unitDelete') .'" data-id="'.$data->id.'">
            //     <i class="fas fa-trash-alt"></i>
            // </button>
            // </div>
    public function create()
    {
        return view('units.create');
    }

    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|min:3|unique:units,name',
        ]);
        
        $data = unit::create($request->all());
      
        return redirect()
                ->route('units.index')
                ->with('success','unit '.$request['name'] .' added successfully.');
    }

     public function show($id)
    {
        $data = DB::table('units')
                    ->orderBy('units.created_at','DESC')
                    ->select('units.*'
                            )
                    ->where('units.id', $id)
                    ->first();

        return view('units.show',compact('data'));
    }


    public function edit($id)
    {
        $data= DB::table('units')
                    ->where('units.id', $id)
                    ->first();

        return view('units.edit',compact('data'));
    }


    public function update(Request $request, $id)
    {
        $data = unit::findOrFail($id);
        $this->validate($request,[
            'name' => 'required|min:3|unique:units,name,'. $id,
            
        ]);

        $upd = $data->update($request->all());

        return redirect()
                ->route('units.index')
                ->with('success','unit '.$request['name'] .' updated successfully.');
    }

    public function destroy(Request $request)
    {
        $ids = $request->ids;
        $data = DB::table("units")->whereIn('id',explode(",",$ids))->delete();
        return response()->json(['success'=>"deleted successfully."]);
    }




}
