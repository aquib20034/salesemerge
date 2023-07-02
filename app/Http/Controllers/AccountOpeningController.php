<?php
namespace App\Http\Controllers;
use DB;
use Auth;
use Gate;
use DataTables;
use App\Models\City;
use App\Models\Branch;
use App\Models\Ledger;
use App\Models\Account;
use App\Models\Company;
use App\Models\Transaction;
use App\Models\AccountType;
use Illuminate\Http\Request;
use App\Http\Requests\AccountRequest;
use App\Http\Requests\OpeningBalanceRequest;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class AccountOpeningController extends Controller
{
    var $account_opening_text;
    function __construct()
    {
        $this->account_opening_text = "Account opening";
        $this->middleware('permission:account_opening-list', ['only' => ['index','show']]);
        $this->middleware('permission:account_opening-create', ['only' => ['create','store']]);
        $this->middleware('permission:account_opening-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:account_opening-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $company_id = hp_company_id();
            $query      = Account::where('company_id',$company_id)->get();
            $table      = DataTables::of($query);

            $table->addColumn('srno', '');
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->addColumn('account_type', function ($row) {
                // return $row->account_type_tree($row);
                return $row->account_type->name;
            });


            $table->addColumn('amount', function ($row) {
                $trnx = ($row->opening_transaction($row->id));

                $amnt = $trnx->ledger->amount ?? "<span class='cls_badge_balance'>Enter Balance</span>";
                $type = isset($trnx->ledger->amount_type) 
                            ? 
                                ($trnx->ledger->amount_type == "D") 
                                    ? "<span class='cls_badge_blue'>DR</span>"
                                    : "<span class='cls_badge_green'>CR</span>"
                                
                            : "";
                                

                return "<a class='cls_amount' 
                                data-account_id     ='".$row->id."' 
                                data-amount         ='".($trnx->ledger->amount ?? 0)."' 
                                data-amount_type    ='".($trnx->ledger->amount_type ?? 'D')."' 
                                data-transaction_id ='".(isset($trnx->id) ? $trnx->id: 0)."' 
                                data-action         ='".(isset($trnx->ledger->id) ? "update": "store")."' 
                                data-toggle='modal' 
                                data-target='#addOpeningBalanceModal'
                                >
                            ".$amnt." ".$type."</span>
                        </a>";
            });

          

            $table->rawColumns(['amount_type','amount','placeholder']);
            return $table->make(true);
        }
        
        return view('account_openings.index');
    }

    public function add_upd_opening_balance(OpeningBalanceRequest $request)
    {

        try {
            // Start a database transaction
            DB::beginTransaction();
            $msg = "Record added successfully.";

                // Validate the request data
                $validated = $request->validated();

                if($request->filled('action') && ($request->action == "store")){

                    // Create a new transaction
                    $trnx                       = new Transaction();
                    $trnx->account_id           = $request->filled('account_id') ? $request->account_id : 0; 
                    $trnx->transaction_type_id  = $request->filled('transaction_type_id') ? $request->transaction_type_id : 0;
                    $trnx->detail               = $this->account_opening_text;
                    $trnx->transaction_date     = date('Y-m-d H:i:s');
                    $trnx->company_id           = Auth::user()->company_id;
                    $trnx->branch_id            = Auth::user()->branch_id;
                    $trnx->created_by           = Auth::user()->id;

                    $trnx->save();

                    // Create a new ledger entry
                    $ledger                     = new Ledger();
                    $ledger->transaction_id     = $trnx->id;
                    $ledger->account_id         = $request->filled('account_id') ? $request->account_id : 0; 
                    $ledger->amount_type        = $request->filled('amount_type') ? $request->amount_type : null;
                    $ledger->amount             = $request->filled('amount') ? $request->amount : 0;
                    $ledger->save();

                }else if($request->filled('action') && ($request->action == "update")){
                    $msg = "Record updated successfully.";

                    // update transaction
                    $transaction_id             = isset($request->transaction_id) ? $request->transaction_id : 0;
                    $trnx                       = Transaction::where('id',$transaction_id)->first();
                    if(isset($trnx->id)){
                        $trnx->account_id           = $request->filled('account_id') ? $request->account_id : 0; 
                        $trnx->transaction_type_id  = $request->filled('transaction_type_id') ? $request->transaction_type_id : 0;
                        $trnx->detail               = $this->account_opening_text;
                        // $trnx->transaction_date     = date('Y-m-d H:i:s');
                        // $trnx->company_id           = Auth::user()->company_id;
                        // $trnx->branch_id            = Auth::user()->branch_id;
                        // $trnx->created_by           = Auth::user()->id;
                        $trnx->save();
                    }else{
                        return response()->json(array(
                            'code'      =>  401,
                            'errors'   =>  [0=>"Unable to find transaction."]
                        ), 401);
                    }

                    // update ledger entry
                    $ledger                     = Ledger::where('transaction_id', $transaction_id)->first();
                    if(isset($ledger->id)){
                        $ledger->transaction_id     = $trnx->id;
                        $ledger->account_id         = $request->filled('account_id') ? $request->account_id : 0; 
                        $ledger->amount_type        = $request->filled('amount_type') ? $request->amount_type : null;
                        $ledger->amount             = $request->filled('amount') ? $request->amount : 0;
                        $ledger->save();
                    }else{
                        return response()->json(array(
                            'code'      =>  401,
                            'errors'   =>  [0=>"Unable to find ledger."]
                        ), 401);
                    }
                    
                }

                // update current balance of account
                $account                        = Account::where('id',$request->account_id)->first();
                $account->current_balance       = hp_calc_current_balance($account->id); 
                $account->save();


                
            // Commit the transaction
            DB::commit();
            return response()->json(['status' => 200,'msg'=>$msg]);
        } catch (\Exception $e) {
            // Roll back the transaction if an exception occurs
            DB::rollBack();
            hp_send_exception($e);

            return response()->json(array(
                'code'      =>  401,
                'errors'   =>  [0=>"Something went wrong."]
            ), 401);
        }
    }

  
    public function create()
    {
        return back()->with('permission','Invalid route');
    }


    public function store(AccountRequest $request)
    {
        return back()->with('permission','Invalid route');

    }


    public function show($id)
    {
        return back()->with('permission','Invalid route');
    }


    public function edit($id)
    {
        return back()->with('permission','Invalid route');
    }


    public function update(AccountRequest $request, $id)
    {
        return back()->with('permission','Invalid route');
    }

    public function destroy(Account $account)
    {
        return back()->with('permission','Invalid route');
    }

}
