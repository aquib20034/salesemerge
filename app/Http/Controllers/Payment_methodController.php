<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Payment_method;
use DB;
use DataTables;

class Payment_methodController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:payment_method-list', ['only' => ['index','show']]);
         $this->middleware('permission:payment_method-create', ['only' => ['create','store']]);
         $this->middleware('permission:payment_method-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:payment_method-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        return view('payment_methods.index');
    }

    public function list()
    {
        $data = DB::table('payment_methods')
                ->orderBy('payment_methods.created_at','DESC')
                ->select('payment_methods.*')
                ->get();
        return 
            DataTables::of($data)
                ->addColumn('action',function($data){
                    return '
                    <div class="btn-group btn-group">
                        <a class="btn btn-info btn-sm" href="payment_methods/'.$data->id.'">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a class="btn btn-info btn-sm" href="payment_methods/'.$data->id.'/edit" id="'.$data->id.'">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                     
                        <button
                            class="btn btn-danger btn-sm delete_all"
                            data-url="'. url('payment_methodDelete') .'" data-id="'.$data->id.'">
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
        return view('payment_methods.create');
    }

    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|min:3|unique:payment_methods,name',
        ]);
        
        $data = payment_method::create($request->all());
      
        return redirect()
                ->route('payment_methods.index')
                ->with('success','payment_method '.$request['name'] .' added successfully.');
    }

     public function show($id)
    {
        $data = DB::table('payment_methods')
                    ->orderBy('payment_methods.created_at','DESC')
                    ->select('payment_methods.*'
                            )
                    ->where('payment_methods.id', $id)
                    ->first();

        return view('payment_methods.show',compact('data'));
    }


    public function edit($id)
    {
        $data= DB::table('payment_methods')
                    ->where('payment_methods.id', $id)
                    ->first();

        return view('payment_methods.edit',compact('data'));
    }


    public function update(Request $request, $id)
    {
        $data = payment_method::findOrFail($id);
        $this->validate($request,[
            'name' => 'required|min:3|unique:payment_methods,name,'. $id,
            
        ]);

        $upd = $data->update($request->all());

        return redirect()
                ->route('payment_methods.index')
                ->with('success','payment_method '.$request['name'] .' updated successfully.');
    }

    public function destroy(Request $request)
    {
        $ids = $request->ids;
        $data = DB::table("payment_methods")->whereIn('id',explode(",",$ids))->delete();
        return response()->json(['success'=>"deleted successfully."]);
    }




}
