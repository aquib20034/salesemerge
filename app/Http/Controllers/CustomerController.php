<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\customer;
use App\Models\Customer_has_transaction;
use DB;
use DataTables;

class CustomerController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:customer-list', ['only' => ['index','show']]);
         $this->middleware('permission:customer-create', ['only' => ['create','store']]);
         $this->middleware('permission:customer-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:customer-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Customer::query()
                ->orderBy('customers.created_at','DESC')
                ->leftjoin('customer_types', 'customer_types.id', '=', 'customers.customer_type_id')
                ->select('customers.*',
                    'customer_types.name as customer_type_name')
                ->get();
            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'customer-list';
                $editGate = 'customer-edit';
                $deleteGate = 'customer-delete';
                $crudRoutePart = 'customers';

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
        return view('customers.index');
    }

    public function create()
    {
        $customer_types = DB::table('customer_types')
                            ->select('customer_types.name','customer_types.id')
                            ->pluck('name','id')->all();

        $cities = DB::table('cities')
                            ->select('cities.name','cities.id')
                            ->pluck('name','id')->all();

        return view('customers.create',compact('customer_types','cities'));
    }

    public function store(Request $request)
    {

        request()->validate([
            'name' => 'required|min:3|unique:customers,name',
        ]);

        $data = customer::create($request->all());

        $val                    = new Customer_has_transaction();
        $val->customer_id       = $data['id'];
        $val->payment_detail    = "Account Opening";
        $val->payment_method_id = 1;

        if($request['previous_amount']>=0){
            $val->credit            = $request['previous_amount'];
        }else{
            $val->debit             = (-1)*($request['previous_amount']);
        }
        $val->save();

        return redirect()
                ->route('customers.index')
                ->with('success','customer '.$request['name'] .' added successfully.');
    }

    public function show($id)
    {
        $data   = DB::table('customers')
                    ->leftjoin('customer_types', 'customer_types.id', '=', 'customers.customer_type_id')
                    ->leftjoin('cities', 'cities.id', '=', 'customers.city_id')
                    ->select('customers.*',
                             'customer_types.name as customer_type_name',
                             'cities.name as city_name',
                    DB::raw('(CASE
                    WHEN customer_has_transactions.credit >=0  THEN customer_has_transactions.credit
                    ELSE customer_has_transactions.debit
                    END) AS previous_amount')
                    )
                    ->leftjoin('customer_has_transactions', 'customer_has_transactions.customer_id', '=', 'customers.id')
                    ->where('customer_has_transactions.payment_detail','It is first entry amount')
                    ->where('customers.id', $id)
                    ->first();

        return view('customers.show',compact('data'));
    }


    public function edit($id)
    {
        $data   = DB::table('customers')
                    ->select('customers.*',
                    DB::raw('(CASE
                    WHEN customer_has_transactions.credit >=0  THEN customer_has_transactions.credit
                    ELSE ((-1)*(customer_has_transactions.debit))
                    END) AS previous_amount')
                    )
                    ->leftjoin('customer_has_transactions', 'customer_has_transactions.customer_id', '=', 'customers.id')
                    ->where('customer_has_transactions.payment_detail','It is first entry amount')
                    ->where('customers.id', $id)
                    ->first();

                    // dd($data);

        $customer_types = DB::table('customer_types')
                            ->select('customer_types.name','customer_types.id')
                            ->pluck('name','id')->all();

        $cities = DB::table('cities')
                            ->select('cities.name','cities.id')
                            ->pluck('name','id')->all();


        return view('customers.edit',compact('data','customer_types','cities'));
    }


    public function update(Request $request, $id)
    {
        $data = customer::findOrFail($id);

        $this->validate($request,[
            'name' => 'required|min:3|unique:customers,name,'. $id,

        ]);

        $input['customer_id']     =  $id;

        if($request['previous_amount']>=0){
            $input['credit']     = $request['previous_amount'];
            $input['debit']      = null;
        }else{

            $input['debit']      = (-1)*($request['previous_amount']);
            $input['credit']     = null;
        }

        $transaction             = Customer_has_transaction::where('customer_id', '=', $id)
                                        ->where('payment_detail','Account Opening')
                                        ->first();
        $data->update($request->all());
        $transaction->update($input);

        return redirect()
                ->route('customers.index')
                ->with('success','customer '.$request['name'] .' updated successfully.');
    }

    public function destroy(Request $request)
    {
        $ids = $request->ids;
        $data = DB::table("customers")->whereIn('id',explode(",",$ids))->delete();
        return response()->json(['success'=>"deleted successfully."]);
    }




}
