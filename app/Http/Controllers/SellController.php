<?php


namespace App\Http\Controllers;

use DB;
use DataTables;
use App\Models\Sell;
use App\Models\Sell_has_item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer_has_transaction;


class SellController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:sell-list', ['only' => ['index','show']]);
         $this->middleware('permission:sell-create', ['only' => ['create','store']]);
         $this->middleware('permission:sell-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:sell-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        return view('sells.index');
    }

    public function list()
    {
        $data = DB::table('sells')
                ->orderBy('sells.created_at','DESC')
                ->leftjoin('customers', 'customers.id', '=', 'sells.customer_id')
                ->select('sells.*',
                        'customers.name as customer_name'
                        )
                ->get();

        return 
            DataTables::of($data)
                ->addColumn('action',function($data){
                    return '
                    <div class="btn-group btn-group">
                        <a class="btn btn-info btn-sm" href="sells/'.$data->id.'">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a class="btn btn-info btn-sm" href="sells/'.$data->id.'/edit" id="'.$data->id.'">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                     
                        <button
                            class="btn btn-danger btn-sm delete_all"
                            data-url="'. url('sellDelete') .'" data-id="'.$data->id.'">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>';
                })
                ->addColumn('srno','')
                ->rawColumns(['srno','','action'])
                ->make(true);
    }

    public function fetch_item_unit_detail(Request $request)
    {
        if($request->ajax()){

            $item_id        = $request->item;
            $unit_id        = $request->unit;

           
            $item           = DB::table('items')
                                ->select('items.*')
                                ->where('items.id',$item_id)
                                // ->pluck("name","id")
                                ->first();

            $unit           = DB::table('units')
                                ->select('id','name')
                                ->where('units.id',$unit_id)
                                // ->pluck("name","id")
                                ->first();
            // $unit_name      = $unit->name;
                                
            return response()->json(['data'=>$item,'unit'=>$unit]);
        }

    }

    public function create()
    {


        // $items              = DB::table('items')
        //                         ->select('items.name','items.id')
        //                         ->pluck('name','id')
        //                         ->all();


        $items       = DB::table('items')
                                ->leftjoin('companies', 'companies.id', '=', 'items.company_id')
                                ->select(
                                        //  'items.name',
                                         'items.id',
                                          DB::raw('CONCAT(items.name, "  -  ", companies.name) as name')
                                         )
                                ->pluck('name','id')
                                ->all();


        $customers          = DB::table('customers')
                                ->orderBy('customers.id')
                                // ->select('customers.id',
                                //           DB::raw('CONCAT(customers.name, "  -  ",customers.address) as name'))
                                ->select('customers.id','name')
                                ->pluck('name','id')
                                ->all();

        $units              = DB::table('units')
                                ->pluck('name','id')
                                ->all();
            //    dd($customers);
        $payment_methods    = DB::table('payment_methods')
                                ->select('payment_methods.name','payment_methods.id')
                                ->pluck('name','id')
                                ->all();

        return view('sells.create',
                        compact(
                            'units',
                            'items',
                            'customers',
                            'payment_methods',
                        )
                    );
    }

    public function store(Request $request)
    {
        $this->validate($request,
            [
                'item_id'               => 'required',
                // 'order_no'          => 'required',
                'customer_id'           => 'required',
                'payment_method_id'     => 'required',
                'total_amount'          => 'required',
            ],
            [
                'total_amount.required' => 'Please press calculate button befor submit!',
            ]
        );


        $inputs                     = $request->all();
        $item                       = $inputs['item_id'];
        $unit                       = $inputs['unit_id'];
        $sell_qty                   = $inputs['sell_qty'];
        $unit_piece                 = $inputs['unit_piece'];
        $sell_price                 = $inputs['sell_price'];
        


        $data                       = Sell::create($inputs);
        $sell_id                    = $data['id'];
      
        if($data){
            if($item){
                foreach($item as $item_key => $item_value){
                $var                 = new Sell_has_item();
                $var->sell_id        = $sell_id;
                $var->item_id        = $item_value;
                $var->unit_id        = $unit[$item_key];
                $var->sell_qty       = $sell_qty[$item_key];
                $var->unit_piece     = $unit_piece[$item_key];
                $var->tot_piece      = (($sell_qty[$item_key]) * ($unit_piece[$item_key]));
                $var->sell_price     = $sell_price[$item_key]; // sell price of that particular unit
                $var->tot_price      = (($sell_qty[$item_key]) * ($sell_price[$item_key]));
                $var->save();
                }
            }

            $val                    = new Customer_has_transaction();
            $val->sell_id           = $data['id'];
            $val->customer_id       = $inputs['customer_id'];
            $val->payment_method_id = $inputs['payment_method_id'];
            $val->payment_detail    = $inputs['payment_detail'];
            $val->credit            = $inputs['pay_amount'];
            $val->debit             = $inputs['total_amount'];
            $val->save();

        }
        if($request['direction']==1){
            return redirect("sells/$sell_id")
                    ->with('success','Sell added successfully.');
              
        }else{
            return redirect()
                    ->route('sells.index')
                    ->with('success','Order added successfully.');
        }
    }

     public function show($id)
    {
        $data               = DB::table('sells')
                                ->orderBy('sells.created_at','DESC')
                                ->leftjoin('customers', 'customers.id', '=', 'sells.customer_id')
                                ->leftjoin('cities', 'cities.id', '=', 'customers.city_id')
                                ->leftjoin('customer_has_transactions', 'customer_has_transactions.sell_id', '=', 'sells.id')
                                ->leftjoin('payment_methods', 'payment_methods.id', '=', 'customer_has_transactions.payment_method_id')
                                ->select('sells.*',
                                        'customers.name as customer_name',
                                        'customers.contact_no',
                                        'customers.address',
                                        'cities.name as city_name',
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

    
        // $items              = DB::table('items')
        //                         ->select('items.name','items.id')
        //                         ->pluck('name','id')
        //                         ->all();

        $items       = DB::table('items')
                        ->leftjoin('companies', 'companies.id', '=', 'items.company_id')
                        ->select(
                                //  'items.name',
                                'items.id',
                                DB::raw('CONCAT(items.name, "  -  ", companies.name) as name')
                                )
                        ->pluck('name','id')
                        ->all();


                              
        $customers          = DB::table('customers')
                                ->orderBy('customers.id')
                                // ->select('customers.id',
                                //           DB::raw('CONCAT(customers.name, "  -  ",customers.address) as name'))
                                ->select('customers.id','name')
                                ->pluck('name','id')
                                ->all();

        $payment_methods    = DB::table('payment_methods')
                                ->select('payment_methods.name','payment_methods.id')
                                ->pluck('name','id')
                                ->all();

        $units              = DB::table('units')
                                ->pluck('name','id')
                                ->all();

        return view('sells.edit',
                    compact(
                        'data',
                        'units',
                        'items',
                        'customers',
                        'selected_items',
                        'payment_methods',
                        )
                    );
    }


    public function update(Request $request, $id)
    {
        $this->validate($request,
            [
                'item_id'               => 'required',
                // 'order_no'           => 'required',
                'customer_id'           => 'required',
                'payment_method_id'     => 'required',
                'total_amount'          => 'required',
            ],
            [
                'total_amount.required' => 'Please press calculate button befor submit!',
            ]
        );
        $data                       = sell::findOrFail($id);

        $inputs                     = $request->all();
        $item                       = $inputs['item_id'];
        $unit                       = $inputs['unit_id'];
        $sell_qty                   = $inputs['sell_qty'];
        $unit_piece                 = $inputs['unit_piece'];
        $sell_price                 = $inputs['sell_price'];

        $upd                        = $data->update($inputs);
        $sell_id                    =  $id;
        DB::table("customer_has_transactions")->where('sell_id', '=', $sell_id)->delete();
        DB::table("sell_has_items")->where('sell_id', '=', $sell_id)->delete();

        if($upd){
            if($item){
                foreach($item as $item_key => $item_value){
                $var                 = new Sell_has_item();
                $var->sell_id        = $sell_id;
                $var->item_id        = $item_value;
                $var->unit_id        = $unit[$item_key];
                $var->sell_qty       = $sell_qty[$item_key];
                $var->unit_piece     = $unit_piece[$item_key];
                $var->tot_piece      = (($sell_qty[$item_key]) * ($unit_piece[$item_key]));
                $var->sell_price     = $sell_price[$item_key]; // sell price of that particular unit
                $var->tot_price      = (($sell_qty[$item_key]) * ($sell_price[$item_key]));
                $var->save();
                }
            }

            $val                    = new Customer_has_transaction();
            $val->sell_id           = $data['id'];
            $val->customer_id       = $inputs['customer_id'];
            $val->payment_method_id = $inputs['payment_method_id'];
            $val->payment_detail    = $inputs['payment_detail'];
            $val->credit            = $inputs['pay_amount'];
            $val->debit             = $inputs['total_amount'];
            $val->save();

        }


        if($request['direction']==1){
            return redirect("sells/$sell_id")
                    ->with('success','Sell updated successfully.');
              
        }else{
            return redirect()
                    ->route('sells.index')
                    ->with('success','Sell updated successfully.');
        }
    }

    public function destroy(Request $request)
    {
        $ids = $request->ids;
        DB::table("company_has_transactions")->whereIn('sell_id',explode(",",$ids))->delete();
        DB::table("sell_has_items")->whereIn('sell_id',explode(",",$ids))->delete();
        DB::table("sells")->whereIn('id',explode(",",$ids))->delete();
        return response()->json(['success'=>"deleted successfully."]);
    }




}
