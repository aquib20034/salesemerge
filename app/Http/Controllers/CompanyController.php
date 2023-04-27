<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Company_has_transaction;
use DB;
use DataTables;

class companyController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:company-list', ['only' => ['index','show']]);
         $this->middleware('permission:company-create', ['only' => ['create','store']]);
         $this->middleware('permission:company-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:company-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        return view('companies.index');
    }

    public function list()
    {
        $data = DB::table('companies')
                ->orderBy('companies.created_at','DESC')
                ->select('companies.*')
                ->get();
        return 
            DataTables::of($data)
                ->addColumn('action',function($data){
                    return '
                    <div class="btn-group btn-group">
                        <a class="btn btn-info btn-sm" href="companies/'.$data->id.'">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a class="btn btn-info btn-sm" href="companies/'.$data->id.'/edit" id="'.$data->id.'">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                     
                        <button
                            class="btn btn-danger btn-sm delete_all"
                            data-url="'. url('companyDelete') .'" data-id="'.$data->id.'">
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
        $amount_types =DB::table('amount_types')
                            ->select('amount_types.name','amount_types.id')
                            ->pluck('name','id')->all();

        return view('companies.create',compact('amount_types'));
    }

    public function store(Request $request)
    {
        request()->validate([
            'name'          => 'required|min:3|unique:companies,name',
            'owner_name'    => 'required|min:3',
        ]);

        $data                   = company::create($request->all());
        $company_id             =  $data['id'];

        $val                    =  new Company_has_transaction();
        $val->company_id        = $company_id;
        $val->payment_method_id = 1;
        $val->payment_detail    = "Account Opening";

        if($request['previous_amount']>=0){
            $val->credit            = $request['previous_amount'];
        }else{
            $val->debit             = ((-1)* ($request['previous_amount']));
        }
        $val->save();
      
        return redirect()
                ->route('companies.index')
                ->with('success','Company '.$request['name'] .' added successfully.');
    }

    public function show($id)
    {
        $data   = DB::table('companies')
                    ->select('companies.*',
                        DB::raw('(CASE 
                        WHEN company_has_transactions.credit >=0  THEN company_has_transactions.credit
                        ELSE company_has_transactions.debit
                        END) AS previous_amount')
                    )
                    ->leftjoin('company_has_transactions', 'company_has_transactions.company_id', '=', 'companies.id')
                    ->where('company_has_transactions.payment_detail','It is first entry amount')
                    ->where('companies.id', $id)
                    ->first();

        return view('companies.show',compact('data'));
    }


    public function edit($id)
    {
        $data= DB::table('companies')
                    ->select('companies.*',
                    DB::raw('(CASE 
                    WHEN company_has_transactions.credit >=0  THEN company_has_transactions.credit
                    ELSE ((-1)*(company_has_transactions.debit))
                    END) AS previous_amount')
                    )
                    ->leftjoin('company_has_transactions', 'company_has_transactions.company_id', '=', 'companies.id')
                    ->where('company_has_transactions.payment_detail','It is first entry amount')
                    ->where('companies.id', $id)
                    ->first();

        return view('companies.edit',compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = company::findOrFail($id);
        $this->validate($request,[
            'name'          => 'required|min:3|unique:companies,name,'. $id,
            'owner_name'    => 'required|min:3',
        ]);

        $input['customer_id']     =  $id;

        if($request['previous_amount']>=0){
            $input['credit']     = $request['previous_amount'];
            $input['debit']      = null;
        }else{
            $input['debit']      = ((-1)* ($request['previous_amount']));
            $input['credit']     = null;
        }
       
        $transaction             = Company_has_transaction::where('company_id', '=', $id)
                                        ->where('payment_detail','Account Opening')
                                        ->first();
        $data->update($request->all());
        $transaction->update($input);

        return redirect()
                ->route('companies.index')
                ->with('success','Company '.$request['name'] .' updated successfully.');
    }

    public function destroy(Request $request)
    {
        $ids = $request->ids;
        $data = DB::table("companies")->whereIn('id',explode(",",$ids))->delete();
        return response()->json(['success'=>"deleted successfully."]);
    }




}
