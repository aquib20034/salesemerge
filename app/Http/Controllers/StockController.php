<?php


namespace App\Http\Controllers;

use DB;
use DataTables;
use App\Models\Sell;
use App\Models\Sell_has_item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer_has_transaction;
use Illuminate\Database\Eloquent\Collection;

class StockController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:stock-list', ['only' => ['index','show']]);
         $this->middleware('permission:stock-create', ['only' => ['create','store']]);
         $this->middleware('permission:stock-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:stock-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        return view('stocks.index');
    }

    public function calc_purchase_items($item_id){
        $data       = DB::table('purchase_has_items')
                        ->select(DB::raw('SUM(purchase_has_items.item_piece * purchase_has_items.purchase_qty ) as purchase_qty'))
                        ->where('purchase_has_items.item_id',$item_id)
                        ->first(); 
        if($data->purchase_qty!=null ){
            return  $data->purchase_qty;
        }else{
            return 0;
        }
    }


    public function calc_sell_items($item_id){
        $data       = DB::table('sell_has_items')
                        ->select(DB::raw('SUM(sell_has_items.tot_piece) as sell_qty'))
                        ->where('sell_has_items.item_id', $item_id)
                        ->first(); 

        if($data->sell_qty!=null ){
            return $data->sell_qty;
        }else{
            return 0;
        }
    }

    public function list()
    {
        $record       = DB::table('items')
                        ->orderBy('items.created_at','ASC')
                        ->leftjoin('companies', 'companies.id', '=', 'items.company_id')
                        // ->leftjoin('units', 'units.id', '=', 'items.unit_id')
                        ->select('items.id',
                                 'items.name as item_name',
                                 'companies.name as company_name',
                                 'items.tot_piece',
                                 'items.unit_id',
                                //  'units.name as unit_name'
                                )
                        ->get();

        // dd($record);
        $rec                 = array();
        foreach ($record as $key => $value) {
            if($value->unit_id==1){
                $unit = "Ctn";
            }else{
                $unit = "Bora";
            }

            // cal and sum the sold items
            $sell_qty       = $this->calc_sell_items($value->id);

            // cal and sum the purchased items
            $purchase_qty   = $this->calc_purchase_items($value->id);

            // set the stock according to ctn and piece 
            $stock_qty_int      = ($purchase_qty-$sell_qty) / $value->tot_piece;
            $stock_qty_dec      = ($purchase_qty-$sell_qty) % $value->tot_piece;
            $stock_qty_int      = ceil( $stock_qty_int ); 
            $stock              =  $this->setValue($stock_qty_int, $stock_qty_dec,$unit);

            // set the purchase qty according to ctn and piece 
            $purchase_qty_int   = ($purchase_qty/ $value->tot_piece);
            $purchase_qty_dec   = $purchase_qty % $value->tot_piece;
            $purchase_qty       =  $this->setValue($purchase_qty_int, $purchase_qty_dec,$unit);

            // set the sell qty according to ctn and piece 
            $sell_qty_int       = ($sell_qty/ $value->tot_piece);
            $sell_qty_dec       = $sell_qty % $value->tot_piece;
            $sell_qty_int       = floor( $sell_qty_int );  
            $sell_qty           =  $this->setValue($sell_qty_int, $sell_qty_dec,$unit);

            // set the tot piece 
            $tot_piece          = "1 ".$unit." has ".$value->tot_piece;

         
            // set all the columns for datatables
            $rec[$key] = json_decode(json_encode(array(
                        'id'            => $value->id,
                        'item_name'     => $value->item_name,
                        'company_name'  => $value->company_name,
                        'tot_piece'     => $tot_piece,
                        'sell_qty'      => $sell_qty,
                        'purchase_qty'  => $purchase_qty,
                        'stock'         => $stock
                    )), false);
      
        }

        $data = new Collection($rec);
       
        return 
            DataTables::of($data)
                // ->addColumn('action',function($data){
                //     return '
                //     <div class="btn-group btn-group">
                //         <a class="btn btn-info btn-sm" href="sells/'.$data->id.'">
                //             <i class="fa fa-eye"></i>
                //         </a>
                      
                //     </div>';
                // })
                ->addColumn('srno','')
                // ->rawColumns(['srno','','action'])
                ->rawColumns(['srno',''])
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

    public function setValue($int_part, $dec_part,$unit){
       
        if ($dec_part==0){
            return ($int_part." ". $unit);
        }else{
            return ($int_part." ". $unit.", ". $dec_part." Piece");
        }
    }

    public function create()
    {

        $items              = DB::table('items')
                                ->select('items.name','items.id')
                                ->pluck('name','id')
                                ->all();

        $customers          = DB::table('customers')
                                ->orderBy('customers.id')
                                ->select('customers.id',
                                          DB::raw('CONCAT(customers.name, "  -  ",customers.address) as name'))
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
        // dd($request);
        $this->validate($request,
            [
                'item_id'           => 'required',
                'order_no'          => 'required',
                'customer_id'        => 'required',
                'payment_method_id' => 'required',
                'total_amount'        => 'required',
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
        
        return redirect()
                ->route('sells.index')
                ->with('success','Order added successfully.');
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

    
        $items              = DB::table('items')
                                ->select('items.name','items.id')
                                ->pluck('name','id')
                                ->all();


                              
        $customers          = DB::table('customers')
                                ->orderBy('customers.id')
                                ->select('customers.id',
                                          DB::raw('CONCAT(customers.name, "  -  ",customers.address) as name'))
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
                'item_id'           => 'required',
                'order_no'          => 'required',
                'customer_id'        => 'required',
                'payment_method_id' => 'required',
                'total_amount'        => 'required',
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



        return redirect()
                ->route('sells.index')
                ->with('success','Sell updated successfully.');
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
