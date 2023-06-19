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

class TransactionController extends Controller
{
    var $account_opening_text;
    function __construct()
    {
        $this->account_opening_text = "Account opening";
        $this->middleware('permission:transaction-list', ['only' => ['index','show']]);
        $this->middleware('permission:transaction-create', ['only' => ['create','store']]);
        $this->middleware('permission:transaction-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:transaction-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
       
        return view('transactions.index');
    }

    public function create()
    {
        $cities             = hp_cities();
        // $amount_types       = hp_amount_types();
        $transaction_types  = hp_transaction_types(true);  // false: get all except Account opening voucher
        $account_types      = AccountType::where('company_id',hp_company_id())
                                ->whereNull('parent_id')
                                ->pluck('name','id')
                                ->all();

        return view('transactions.create',compact(
                                                'cities',
                                                'account_types',
                                                // 'amount_types',
                                                'transaction_types'
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
