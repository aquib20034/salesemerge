<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Item;
use DB;
use DataTables;

class ItemController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:item-list', ['only' => ['index','show']]);
         $this->middleware('permission:item-create', ['only' => ['create','store']]);
         $this->middleware('permission:item-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:item-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        return view('items.index');
    }

    public function list()
    {
        $data = DB::table('items')
                ->orderBy('items.created_at','DESC')
                ->leftjoin('companies', 'companies.id', '=', 'items.company_id')
                ->leftjoin('units', 'units.id', '=', 'items.unit_id')
                ->select('items.*',
                        'companies.name as company_name',
                        'units.name as unit_name'
                        )
                ->get();

        return 
            DataTables::of($data)
                ->addColumn('action',function($data){
                    return '
                    <div class="btn-group btn-group">
                        <a class="btn btn-info btn-sm" href="items/'.$data->id.'">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a class="btn btn-info btn-sm" href="items/'.$data->id.'/edit" id="'.$data->id.'">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                     
                        <button
                            class="btn btn-danger btn-sm delete_all"
                            data-url="'. url('itemDelete') .'" data-id="'.$data->id.'">
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
        $companies = DB::table('companies')
                            ->select('companies.name','companies.id')
                            ->pluck('name','id')->all();

        $units = DB::table('units')
                            ->select('units.name','units.id')
                            ->pluck('name','id')->all();


        return view('items.create',compact('companies','units'));
    }

    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|min:3|unique:items,name',
            'purchase_price' => 'required',
            'sell_price' => 'required',
            'tot_piece' => 'required|min:1'
        ]);
        
        $input = $request->all();

        $input['unit_sell_price'] =  ( $request['sell_price'] /  $request['tot_piece']);
        $data = item::create($input);
      
        return redirect()
                ->route('items.index')
                ->with('success','item '.$request['name'] .' added successfully.');
    }

     public function show($id)
    {
        $data = DB::table('items')
                    ->orderBy('items.created_at','DESC')
                    ->leftjoin('companies', 'companies.id', '=', 'items.company_id')
                    ->leftjoin('units', 'units.id', '=', 'items.unit_id')
                    ->select('items.*',
                            'companies.name as company_name',
                            'units.name as unit_name'
                            )
                    ->where('items.id', $id)
                    ->first();

        return view('items.show',compact('data'));
    }


    public function edit($id)
    {
        $data= DB::table('items')
                    ->where('items.id', $id)
                    ->first();

        $companies = DB::table('companies')
                    ->select('companies.name','companies.id')
                    ->pluck('name','id')->all();

        $units = DB::table('units')
                    ->select('units.name','units.id')
                    ->pluck('name','id')->all();


        return view('items.edit',compact('data','companies','units'));
    }


    public function update(Request $request, $id)
    {
        $data = item::findOrFail($id);
        $this->validate($request,[
            'name' => 'required|min:3|unique:items,name,'. $id,
            'purchase_price' => 'required',
            'sell_price' => 'required',
            'tot_piece' => 'required|min:1'
            
        ]);

        $input = $request->all();
        $input['unit_sell_price'] =  ( $request['sell_price'] /  $request['tot_piece']);


        $upd = $data->update($input);

        return redirect()
                ->route('items.index')
                ->with('success','item '.$request['name'] .' updated successfully.');
    }

    public function destroy(Request $request)
    {
        $ids = $request->ids;
        $data = DB::table("items")->whereIn('id',explode(",",$ids))->delete();
        return response()->json(['success'=>"deleted successfully."]);
    }




}
