<?php


namespace App\Http\Controllers;

use DB;
use DataTables;
use App\Models\Sell;
use App\Models\Sell_has_item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer_has_transaction;
use App\Models\Company_has_transaction;

use Illuminate\Database\Eloquent\Collection;

class VoucherController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:voucher-list', ['only' => ['index','show']]);
         $this->middleware('permission:voucher-create', ['only' => ['create','store']]);
         $this->middleware('permission:voucher-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:voucher-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $companies          = DB::table('companies')
                                ->orderBy('companies.id')
                                ->select('companies.id',
                                        DB::raw('CONCAT(companies.name, "  -  ", companies.owner_name, "  -  ",companies.address) as name'))
                                ->pluck('name','id')
                                ->all();

        $customers          = DB::table('customers')
                                ->orderBy('customers.id')
                                ->select('customers.id',
                                          DB::raw('CONCAT(customers.name, "  -  ",customers.address) as name'))
                                ->pluck('name','id')
                                ->all();

        return view('vouchers.index',
                                compact(
                                    'companies',
                                    'customers'
                                )
                            );
    }

    public function create()
    {
    }

 

    public function store(Request $request)
    {
        

        if($request['entity']=='company'){
            $this->validate($request,[
                'company_id'        => 'required',
                'company_amount'    => 'required',
            ]);
            if($request['company_amount']>=0){
                $request['credit']     = $request['company_amount'];
            }else{
                $request['debit']      = ((-1)* ($request['company_amount']));
            }
            $request['payment_detail'] = $request['company_payment_details'];

            $rec                   = Company_has_transaction::create($request->all());
            if(!($rec)){
                return redirect()
                    ->route('vouchers.index')
                    ->with('permission','No record found!.');
            }else{
                return redirect()
                    ->route('vouchers.index')
                    ->with('success','Company amount added successfully!.');
            }
         
           
        }elseif($request['entity']=='customers'){
            $this->validate($request,[
                'customer_id'        => 'required',
                'customer_amount'    => 'required',
            ]);

            if($request['customer_amount']>=0){
                $request['credit']     = $request['customer_amount'];
            }else{
                $request['debit']      = ((-1)* ($request['customer_amount']));
            }
            $request['payment_detail'] = $request['customer_payment_details'];
            $rec                   = Customer_has_transaction::create($request->all());
            if(!($rec)){
                return redirect()
                    ->route('vouchers.index')
                    ->with('permission','No record found!.');
            }else{
                return redirect()
                    ->route('vouchers.index')
                    ->with('success','Customer amount added successfully!.');
            }
           
        }
    }

    public function show($id)
    {
        $data               = DB::table('sells')
                                ->orderBy('sells.created_at','DESC')
                                ->leftjoin('customers', 'customers.id', '=', 'sells.customer_id')
                                ->leftjoin('customer_has_transactions', 'customer_has_transactions.sell_id', '=', 'sells.id')
                                ->leftjoin('payment_methods', 'payment_methods.id', '=', 'customer_has_transactions.payment_method_id')
                                ->select('sells.*',
                                        'customers.name as customer_name',
                                        'customers.contact_no',
                                        'customers.address',
                                        'customer_has_transactions.debit',
                                        'customer_has_transactions.credit',
                                        'customer_has_transactions.payment_detail',
                                        'customer_has_transactions.payment_method_id',
                                        'payment_methods.name as payment_method_name',
                                        )
                                ->where('sells.id', $id)
                                ->first();

        // dd($data);
        $selected_items     = DB::table('sell_has_items')
                                ->leftjoin('units', 'units.id', '=', 'sell_has_items.unit_id')
                                ->leftjoin('items', 'items.id', '=', 'sell_has_items.item_id')
                                ->select('sell_has_items.*',
                                         'items.name as item_name',
                                         'units.name as unit_name')
                                ->where('sell_has_items.sell_id', $id)
                                ->get()
                                ->all();

        // dd($selected_items);

        return view('sells.show',compact('data','selected_items'));
    }

    public function edit($id)
    {
    }


    public function update(Request $request, $id)
    {
    }

    public function destroy(Request $request)
    {
    }

}
