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

class AccountController extends Controller
{
    var $account_opening_text;
    function __construct()
    {
        $this->account_opening_text = "Account opening";
        $this->middleware('permission:account-list', ['only' => ['index','show']]);
        $this->middleware('permission:account-create', ['only' => ['create','store']]);
        $this->middleware('permission:account-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:account-delete', ['only' => ['destroy']]);
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

            $table->editColumn('actions', function ($row) {
                $viewGate       = 'account-list';
                $editGate       = 'account-edit';
                $deleteGate     = '';
                $crudRoutePart  = 'accounts';

                return view('partials.datatableActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->rawColumns(['actions', 'amount_type','amount','placeholder']);
            return $table->make(true);
        }
        
        return view('accounts.index');
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


    public function get_children($parent_id, $type)
    {
        $company_id     = hp_company_id();
        $records        = AccountType::where('company_id',$company_id)
                            ->where('parent_id', $parent_id)
                            ->pluck('name','id')
                            ->all();

        if(isset($type)){
            switch($type){
                case 'group':
                    $records        = view('accounts.ajax_group',compact('records'))->render();
                break;
                case 'child':
                    $records        = view('accounts.ajax_child',compact('records'))->render();
                break;
            }
        }
        return response()->json(['data'=>$records]);
    }

    public function create()
    {
        $cities             = hp_cities();
        // $amount_types       = hp_amount_types();
        // $transaction_types  = hp_transaction_types(false);  // false: get only Account opening voucher
        $account_types      = AccountType::where('company_id',hp_company_id())
                                ->whereNull('parent_id')
                                ->pluck('name','id')
                                ->all();

        return view('accounts.create',compact(
                                                'cities',
                                                'account_types',
                                                // 'amount_types',
                                                // 'transaction_types'
                                            ));
    }


    public function store(AccountRequest $request)
    {
         // Validate the request data
         $validated = $request->validated();

         // Create a new account
         $account = Account::create($request->all());

         return redirect()
            // ->route('accounts.index')
            ->back()
            ->with('success', 'Record added successfully.');

    }


    public function show($id)
    {
        $company_id = hp_company_id();
        $data       = Account::where('company_id',$company_id)->findOrFail($id);

        return view('accounts.show',compact('data'));
    }


    public function edit($id)
    {
        $company_id         = hp_company_id();
        $data               = Account::where('company_id',$company_id)->findOrFail($id);

        $cities             = hp_cities();
        $branches           = hp_branches($company_id);
        $companies          = hp_companies($company_id);

        $account_types      = AccountType::where('company_id',$company_id)
                                ->whereNull('parent_id')
                                ->pluck('name','id')
                                ->all();

        $group_heads        = AccountType::where('company_id',$company_id)
                                ->where('parent_id',$data->account_type_id)
                                ->pluck('name','id')
                                ->all();


        $child_heads        = AccountType::where('company_id',$company_id)
                                ->where('parent_id',$data->group_head_id)
                                ->pluck('name','id')
                                ->all();

        return view('accounts.edit',compact(
                                                'data',
                                                'cities',
                                                'branches',
                                                'companies',
                                                'account_types',
                                                'group_heads',
                                                'child_heads'
                                            ));
    }


    public function update(AccountRequest $request, $id)
    {
        // validated input data...
        $validated          = $request->validated();
        $data               = Account::findOrFail($id);
        $input              = $request->all();

        // if active is not set, make it in-active
        $input['active']    = ((isset($input['active'])) && ($input['active'] == 1 )) ?  1 : 0;
        $upd                = $data->update($input);


        return redirect()
                ->route('accounts.index')
                ->with('success','Record updated successfully.');
    }

    public function destroy(Account $account)
    {
        abort_if(Gate::denies('account-delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        // reterive transaction
        $transactions       = Transaction::where('account_id',$account->id)->get();

        // reterive ledger
        $ledgers            = Ledger::where('account_id',$account->id)->get();

        // Delete transactions
        $transactions->each->delete();

        // Delete ledgers
        $ledgers->each->delete();

        // delete account
        $account->delete();
        return back()->with('success','Record deleted successfully.');
    }

}
