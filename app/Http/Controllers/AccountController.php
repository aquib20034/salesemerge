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
            $company_id = Auth::user()->company_id;
            $query      = Account::where('company_id',$company_id)->orderBy('accounts.name','ASC')->get();
            $table      = DataTables::of($query);

            $table->addColumn('srno', '');
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->addColumn('account_type', function ($row) {
                return $row->account_type_tree($row);
            });

            $table->editColumn('actions', function ($row) {
                $viewGate       = 'account-list';
                $editGate       = 'account-edit';
                $deleteGate     = 'account-delete';
                $crudRoutePart  = 'accounts';

                return view('partials.datatableActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->rawColumns(['actions', 'account_type','placeholder']);
            return $table->make(true);
        }
        return view('accounts.index');
    }

    public function get_children($parent_id, $type)
    {
        $company_id     = Auth::user()->company_id;
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
        $cities             = City::pluck('name','id')->all();
        $company_id         = Auth::user()->company_id;
        $transaction_types  = array('D' => 'Debit','C' =>'Credit') ;
        $account_types      = AccountType::where('company_id',$company_id)->whereNull('parent_id')->pluck('name','id')->all();
        
        return view('accounts.create',compact('cities','account_types','transaction_types'));
    }

    public function store(AccountRequest $request)
    {
        // Retrieve the validated input data...
        $validated                      = $request->validated();

        // Insert / create Account
        $account                        = Account::create($request->all());

        // Create the transaction entry
        $transaction                    = new Transaction();
        $transaction->account_id        = $account->id; 
        $transaction->detail            = $this->account_opening_text; 
        $transaction->transaction_date  = date('Y-m-d');
        $transaction->save();

        // Insert the amount into the ledger
        $ledger                         = new Ledger();
        $ledger->amount                 = isset($request->amount) ? $request->amount : 0;
        $ledger->account_id             = $account->id; 
        $ledger->transaction_id         = $transaction->id; 
        $ledger->transaction_type       = isset($request->transaction_type) ? $request->transaction_type : 'D';
        $ledger->save();


        return redirect()
                ->route('accounts.index')
                ->with('success','Record added successfully.');
    }

    public function show($id)
    {
        $company_id = Auth::user()->company_id;
        $data       = Account::where('company_id',$company_id)->findOrFail($id);

        return view('accounts.show',compact('data'));
    }


    public function edit($id)
    {
        $company_id         = Auth::user()->company_id;
        $data               = Account::where('company_id',$company_id)->findOrFail($id);

        $cities             = City::pluck('name','id')->all();
        $branches           = Branch::where('company_id',$company_id)->pluck('name','id')->all();
        $companies          = Company::where('id',$company_id)->pluck('name','id')->all();
        $transaction_types  = array('D' => 'Debit','C' =>'Credit') ;


        $transaction        = Transaction::where('detail',$this->account_opening_text)
                                ->where('account_id',$id)
                                ->first();

        $ledger             = Ledger::where('transaction_id',$transaction->id)
                                ->where('account_id',$id)
                                ->first();

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
                                                'companies',
                                                'branches',
                                                'ledger',
                                                'transaction',
                                                'transaction_types',
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


        // reterive transaction
        $transaction        = Transaction::where('detail',$this->account_opening_text)
                                ->where('account_id',$id)
                                ->first();

        // reterive ledger
        $ledger             = Ledger::where('transaction_id',$transaction->id)
                                ->where('account_id',$id)
                                ->first();

         // update the amount into the ledger
         $ledger->amount                 = isset($request->amount) ? $request->amount : 0;
         $ledger->transaction_type       = isset($request->transaction_type) ? $request->transaction_type : 'D';
         $ledger->save();

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
