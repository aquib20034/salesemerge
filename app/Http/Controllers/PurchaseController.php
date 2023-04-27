<?php


namespace App\Http\Controllers;

use DB;
use DataTables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Purchase_has_item;
use App\Models\Company_has_transaction;


class PurchaseController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:purchase-list', ['only' => ['index','show']]);
         $this->middleware('permission:purchase-create', ['only' => ['create','store']]);
         $this->middleware('permission:purchase-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:purchase-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        return view('purchases.index');
    }

    public function list()
    {
        $data = DB::table('purchases')
                ->orderBy('purchases.created_at','DESC')
                ->leftjoin('companies', 'companies.id', '=', 'purchases.company_id')
                ->select('purchases.*',
                        'companies.name as company_name'
                        )
                ->get();

        return 
            DataTables::of($data)
                ->addColumn('action',function($data){
                    return '
                    <div class="btn-group btn-group">
                        <a class="btn btn-info btn-sm" href="purchases/'.$data->id.'">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a class="btn btn-info btn-sm" href="purchases/'.$data->id.'/edit" id="'.$data->id.'">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                     
                        <button
                            class="btn btn-danger btn-sm delete_all"
                            data-url="'. url('purchaseDelete') .'" data-id="'.$data->id.'">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>';
                })
                ->addColumn('srno','')
                ->rawColumns(['srno','','action'])
                ->make(true);
    }

    public function fetch_item_detail(Request $request)
    {
        if($request->ajax()){

            $item_id        = $request->item;

           
            $item      = DB::table('items')
                                ->select('items.*')
                                ->where('items.id',$item_id)
                                // ->pluck("name","id")
                                ->first();
                            
            return response()->json(['data'=>$item]);
        }

    }

    public function create()
    {
        $items       = DB::table('items')
                                ->leftjoin('companies', 'companies.id', '=', 'items.company_id')
                                ->select(
                                        //  'items.name',
                                         'items.id',
                                          DB::raw('CONCAT(items.name, "  -  ", companies.name) as name')
                                         )
                                ->pluck('name','id')
                                ->all();

        $companies          = DB::table('companies')
                                ->orderBy('companies.id')
                                ->select('companies.id',
                                          DB::raw('CONCAT(companies.name, "  -  ", companies.owner_name) as name'))
                                ->pluck('name','id')
                                ->all();
        
        $payment_types      = DB::table('payment_types')
                                ->orderBy('payment_types.id')
                                ->select('payment_types.name','payment_types.id')
                                ->pluck('name','id')
                                ->all();

        $payment_methods    = DB::table('payment_methods')
                                ->select('payment_methods.name','payment_methods.id')
                                ->pluck('name','id')
                                ->all();

        return view('purchases.create',
                        compact(
                            'items',
                            'companies',
                            'payment_types',
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
                // 'order_no'          => 'required',
                'company_id'        => 'required',
                'payment_method_id' => 'required',
                'net_amount'        => 'required',
            ],
            [
                'net_amount.required' => 'Please press calculate button befor submit!',
            ]
        );
        $inputs                     = $request->all();
        $item                       = $inputs['item_id'];
        $piece                      = $inputs['item_piece'];
        $qty                        = $inputs['purchase_qty'];
        $sell_price                 = $inputs['sell_price'];
        $purchase_price             = $inputs['purchase_price'];
        $data                       = Purchase::create($inputs);
        $purchase_id                =  $data['id'];
      
        if($data){
            if($item){
                foreach($item as $item_key => $item_value){
                $var                 = new Purchase_has_item();
                $var->purchase_id    = $purchase_id;
                $var->item_id        = $item_value;
                $var->item_piece     = $piece[$item_key];
                $var->purchase_qty   = $qty[$item_key];
                $var->sell_price     = $sell_price[$item_key];
                $var->purchase_price = $purchase_price[$item_key];
                $var->save();
                }
            }

            $val                    = new Company_has_transaction();
            $val->purchase_id       = $purchase_id;
            $val->company_id        = $inputs['company_id'];
            $val->payment_method_id = $inputs['payment_method_id'];
            $val->payment_detail    = $inputs['payment_detail'];
            $val->credit            = $inputs['pay_amount'];
            $val->debit             = $inputs['net_amount'];
            $val->save();

        }

        if($request['direction']==1){
           return redirect("purchases/$purchase_id")
                  ->with('success','Order added successfully.');
             
        }else{
            return redirect()
                ->route('purchases.index')
                ->with('success','Order added successfully.');
        }
        
        
    }

     public function show($id)
    {
        $data = DB::table('purchases')
                    ->orderBy('purchases.created_at','DESC')
                    ->leftjoin('companies', 'companies.id', '=', 'purchases.company_id')
                    ->leftjoin('company_has_transactions', 'company_has_transactions.purchase_id', '=', 'purchases.id')
                    ->leftjoin('payment_methods', 'payment_methods.id', '=', 'company_has_transactions.payment_method_id')
                    ->select('purchases.*',
                            'companies.name as company_name',
                            'companies.owner_name',
                            'companies.contact_no',
                            'companies.address',
                            'company_has_transactions.debit',
                            'company_has_transactions.credit',
                            'company_has_transactions.payment_detail',
                            'company_has_transactions.payment_method_id',
                            'payment_methods.name as payment_method_name',
                            )
                    ->where('purchases.id', $id)
                    ->first();

        $selected_items     = DB::table('purchase_has_items')
                                ->leftjoin('items', 'items.id', '=', 'purchase_has_items.item_id')
                                ->select('purchase_has_items.*','items.name as item_name')
                                ->where('purchase_has_items.purchase_id', $id)
                                ->get()
                                ->all();

                    // dd($selected_items);

        return view('purchases.show',compact('data','selected_items'));
    }


    public function edit($id)
    {
        $data               = DB::table('purchases')
                                ->leftjoin('company_has_transactions', 'company_has_transactions.purchase_id', '=', 'purchases.id')
                                ->leftjoin('payment_methods', 'payment_methods.id', '=', 'company_has_transactions.payment_method_id')
                                ->select('purchases.*',
                                         'company_has_transactions.payment_method_id',
                                         'company_has_transactions.payment_detail',
                                         'company_has_transactions.credit',
                                         'company_has_transactions.debit',
                                         'payment_methods.name as payment_method_name',
                                         )
                                ->where('purchases.id', $id)
                                ->first();
                               
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

        $selected_items     = DB::table('purchase_has_items')
                                ->leftjoin('items', 'items.id', '=', 'purchase_has_items.item_id')
                                ->select('purchase_has_items.*','items.name as item_name')
                                ->where('purchase_has_items.purchase_id', $id)
                                ->get()
                                ->all();

                              
        $companies          = DB::table('companies')
                                ->orderBy('companies.id')
                                ->select('companies.id',
                                          DB::raw('CONCAT(companies.name, "  -  ", companies.owner_name) as name'))
                                ->pluck('name','id')
                                ->all();

        $payment_methods    = DB::table('payment_methods')
                                ->select('payment_methods.name','payment_methods.id')
                                ->pluck('name','id')
                                ->all();

        return view('purchases.edit',
                    compact(
                        'data',
                        'items',
                        'companies',
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
                // 'order_no'          => 'required',
                'company_id'        => 'required',
                'payment_method_id' => 'required',
                'net_amount'        => 'required',
            ],
            [
                'net_amount.required' => 'Please press calculate button befor submit!',
            ]
        );
        $data                       = purchase::findOrFail($id);
        $inputs                     = $request->all();
        $item                       = $inputs['item_id'];
        $piece                      = $inputs['item_piece'];
        $qty                        = $inputs['purchase_qty'];
        $sell_price                 = $inputs['sell_price'];
        $purchase_price             = $inputs['purchase_price'];
        $upd                        = $data->update($inputs);
        $purchase_id                =  $id;
        DB::table("company_has_transactions")->where('purchase_id', '=', $purchase_id)->delete();
        DB::table("purchase_has_items")->where('purchase_id', '=', $purchase_id)->delete();

        if($data){
            if($item){
                foreach($item as $item_key => $item_value){
                $var                 =  new Purchase_has_item();
                $var->purchase_id    = $purchase_id;
                $var->item_id        = $item_value;
                $var->item_piece     = $piece[$item_key];
                $var->purchase_qty   = $qty[$item_key];
                $var->sell_price     = $sell_price[$item_key];
                $var->purchase_price = $purchase_price[$item_key];
                $var->save();
                }
            }

            $val                    =  new Company_has_transaction();
            $val->purchase_id       = $purchase_id;
            $val->company_id        = $inputs['company_id'];
            $val->payment_method_id = $inputs['payment_method_id'];
            $val->payment_detail    = $inputs['payment_detail'];
            $val->credit            = $inputs['pay_amount'];
            $val->debit             = $inputs['net_amount'];
            $val->save();

        }


        if($request['direction']==1){
            return redirect("purchases/$purchase_id")
                  ->with('success','Order updated successfully.');
         }else{
            return redirect()
                    ->route('purchases.index')
                    ->with('success','Order updated successfully.');
         }
    }

    public function destroy(Request $request)
    {
        $ids = $request->ids;
        DB::table("company_has_transactions")->whereIn('purchase_id',explode(",",$ids))->delete();
        DB::table("purchase_has_items")->whereIn('purchase_id',explode(",",$ids))->delete();
        DB::table("purchases")->whereIn('id',explode(",",$ids))->delete();
        return response()->json(['success'=>"deleted successfully."]);
    }




}
