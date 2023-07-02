<?php
namespace App\Http\Controllers;
use DB;
use Auth;
use Gate;
use DataTables;
use App\Models\City;
use App\Models\Unit;
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

class AccountLedgerController extends Controller
{
    var $account_opening_text;
    function __construct()
    {
        $this->account_opening_text = "Account opening";
        $this->middleware('permission:account_ledger-list', ['only' => ['index','show']]);
        $this->middleware('permission:account_ledger-create', ['only' => ['create','store']]);
        $this->middleware('permission:account_ledger-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:account_ledger-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $company_id = Auth::user()->company_id;
            $query      = Transaction::where('company_id',$company_id)
                            // ->where('account_id',1)
                            ->orderBy('id','ASC')
                            ->get();
            $table      = DataTables::of($query);

            $table->addColumn('srno', '');
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('transaction_type_id', function ($row) {
                if(isset($row->transaction_type_id)){
                    if(isset($row->transactionType->name)){
                        return $row->transactionType->name;
                    }
                }
                return "";
            });

            $table->editColumn('account_id', function ($row) {
                if(isset($row->account_id)){
                    if(isset($row->account->name)){
                        return $row->account->name;
                    }
                }
                return "";
            });

            $table->editColumn('created_by', function ($row) {
                if(isset($row->created_by)){
                    if(isset($row->createdBy->name)){
                        return $row->createdBy->name;
                    }
                }
                return "";
            });
            
            $table->addColumn('debit', function ($row) {
                if((isset($row->ledger->amount_type)) && (($row->ledger->amount_type) == 'D')){
                    return $row->ledger->amount;
                }
                return "";
            });

            
            $table->addColumn('credit', function ($row) {
                if((isset($row->ledger->amount_type)) && (($row->ledger->amount_type) == 'C')){
                    return $row->ledger->amount;
                }
                return "";
            });

            $table->addColumn('balance', function ($row) {
                $balance = 0;
                if (isset($row->ledger->amount_type) && $row->ledger->amount_type == 'D') {
                    $balance += $row->ledger->amount;
                } elseif (isset($row->ledger->amount_type) && $row->ledger->amount_type == 'C') {
                    $balance -= $row->ledger->amount;
                }
                return $balance;
            });

            $table->editColumn('actions', function ($row) {
                $viewGate       = 'account_ledger-list';
                $editGate       = 'account_ledger-edit';
                $deleteGate     = 'account_ledger-delete';
                $crudRoutePart  = 'account_ledgers';

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
        return view('account_ledgers.index');
    }

    public function get_ledger( $transaction_type_id, $from_date, $to_date, Request $request)
    {

        $company_id = Auth::user()->company_id;
        $query      = Transaction::where('company_id',$company_id)
                        ->where('transaction_type_id',$transaction_type_id)
                        ->whereDate('updated_at','>=',$from_date)
                        ->whereDate('updated_at','<=',$to_date)
                        // ->where('account_id',1)
                        ->orderBy('id','ASC')
                        ->get();
        $table      = DataTables::of($query);

        $table->addColumn('srno', '');
        $table->addColumn('placeholder', '&nbsp;');
        $table->addColumn('actions', '&nbsp;');

        $table->editColumn('transaction_type_id', function ($row) {
            if(isset($row->transaction_type_id)){
                if(isset($row->transactionType->name)){
                    return $row->transactionType->name;
                }
            }
            return "";
        });

        $table->editColumn('account_id', function ($row) {
            if(isset($row->account_id)){
                if(isset($row->account->name)){
                    return $row->account->name;
                }
            }
            return "";
        });

        $table->editColumn('created_by', function ($row) {
            if(isset($row->created_by)){
                if(isset($row->createdBy->name)){
                    return $row->createdBy->name;
                }
            }
            return "";
        });
        
        $table->addColumn('debit', function ($row) {
            if((isset($row->ledger->amount_type)) && (($row->ledger->amount_type) == 'D')){
                return $row->ledger->amount;
            }
            return "";
        });

        
        $table->addColumn('credit', function ($row) {
            if((isset($row->ledger->amount_type)) && (($row->ledger->amount_type) == 'C')){
                return $row->ledger->amount;
            }
            return "";
        });

        

        $table->editColumn('actions', function ($row) {
            $viewGate       = 'account_ledger-list';
            $editGate       = 'account_ledger-edit';
            $deleteGate     = 'account_ledger-delete';
            $crudRoutePart  = 'account_ledgers';

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


    public function create()
    {
        $company_id = Auth::user()->company_id;
        $companies  = Company::where('id',$company_id)->pluck('name','id')->all();
        $branches   = Branch::where('company_id',$company_id)->pluck('name','id')->all();
        return view('account_ledgers.create',compact('companies','branches'));
    }

    public function store(UnitRequest $request)
    {
        // Retrieve the validated input data...
        $validated      = $request->validated();
        $data           = Unit::create($request->all());

        return redirect()
                ->route('account_ledgers.index')
                ->with('success','Record added successfully.');
    }

     public function show($id)
    {
        $company_id = Auth::user()->company_id;
        $data       = Unit::where('company_id',$company_id)->findOrFail($id);
        return view('account_ledgers.show',compact('data'));
    }


    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $data       = Unit::where('company_id',$company_id)->findOrFail($id);

        $companies  = Company::where('id',$company_id)->pluck('name','id')->all();
        $branches   = Branch::where('company_id',$company_id)->pluck('name','id')->all();
        return view('account_ledgers.edit',compact('data','companies','branches'));
    }


    public function update(UnitRequest $request, $id)
    {
        // validated input data...
        $validated  = $request->validated();
        $data       = Unit::findOrFail($id);
        $input      = $request->all();

        // if active is not set, make it in-active
        if(!(isset($input['active']))){
            $input['active'] = 0;
        }

        $upd        = $data->update($input);
        return redirect()
                ->route('account_ledgers.index')
                ->with('success','Record updated successfully.');
    }

    public function destroy(Unit $unit)
    {
        abort_if(Gate::denies('account_ledger-delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $unit->delete();
        return back()->with('success','Record deleted successfully.');
    }

}
