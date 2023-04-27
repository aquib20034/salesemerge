<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\City;
use DB;
use DataTables;

class cityController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:city-list', ['only' => ['index','show']]);
         $this->middleware('permission:city-create', ['only' => ['create','store']]);
         $this->middleware('permission:city-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:city-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        return view('cities.index');
    }

    public function list()
    {
        $data = DB::table('cities')
                ->orderBy('cities.created_at','DESC')
                ->select('cities.*')
                ->get();
        return 
            DataTables::of($data)
                ->addColumn('action',function($data){
                    return '
                    <div class="btn-group btn-group">
                        <a class="btn btn-info btn-sm" href="cities/'.$data->id.'">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a class="btn btn-info btn-sm" href="cities/'.$data->id.'/edit" id="'.$data->id.'">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                     
                        <button
                            class="btn btn-danger btn-sm delete_all"
                            data-url="'. url('cityDelete') .'" data-id="'.$data->id.'">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>';
                })
                ->addColumn('srno','')
                ->rawColumns(['srno','','action'])
                ->make(true);
    }

    public function create()
    {
        return view('cities.create');
    }

    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|min:3|unique:cities,name',
        ]);
        
        $data = city::create($request->all());
      
        return redirect()
                ->route('cities.index')
                ->with('success','city '.$request['name'] .' added successfully.');
    }

     public function show($id)
    {
        $data = DB::table('cities')
                    ->orderBy('cities.created_at','DESC')
                    ->select('cities.*'
                            )
                    ->where('cities.id', $id)
                    ->first();

        return view('cities.show',compact('data'));
    }


    public function edit($id)
    {
        $data= DB::table('cities')
                    ->where('cities.id', $id)
                    ->first();

        return view('cities.edit',compact('data'));
    }


    public function update(Request $request, $id)
    {
        $data = city::findOrFail($id);
        $this->validate($request,[
            'name' => 'required|min:3|unique:cities,name,'. $id,
            
        ]);

        $upd = $data->update($request->all());

        return redirect()
                ->route('cities.index')
                ->with('success','city '.$request['name'] .' updated successfully.');
    }

    public function destroy(Request $request)
    {
        $ids = $request->ids;
        $data = DB::table("cities")->whereIn('id',explode(",",$ids))->delete();
        return response()->json(['success'=>"deleted successfully."]);
    }




}
