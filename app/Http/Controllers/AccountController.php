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

            $table->addColumn('city', function ($row) {
                // return $row->account_type_tree($row);
                return $row->city->name;
            });


            $table->addColumn('branch', function ($row) {
                // return $row->account_type_tree($row);
                return $row->branch->name;
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
