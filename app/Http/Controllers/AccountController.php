<?php
namespace App\Http\Controllers;
use DB;
use Auth;
use Gate;
use DataTables;
use App\Models\Account;
use App\Models\Company;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Requests\AccountRequest;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    function __construct()
    {
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

            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);
        }
        return view('accounts.index');
    }

    public function create()
    {
        $heads          = array(1=>'Assets', 2=>'Liabilities',3=>'Equity',4=>'Revenue',5=>'Expense');
        $sub_heads      = array(1=>'Current Assets',2=>'Fixed Assets');
        $child_heads    = array(1=>'Cash in hand');
        $group_heads    = array(1=>'Group 1');

        return view('accounts.create',compact('heads','sub_heads','child_heads','group_heads'));
    }

    public function store(AccountRequest $request)
    {
        // Retrieve the validated input data...
        $validated      = $request->validated();
        $data           = Account::create($request->all());

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
        $company_id = Auth::user()->company_id;
        $data       = Account::where('company_id',$company_id)->findOrFail($id);

        $companies  = Company::where('id',$company_id)->pluck('name','id')->all();
        $branches   = Branch::where('company_id',$company_id)->pluck('name','id')->all();
        return view('accounts.edit',compact('data','companies','branches'));
    }


    public function update(AccountRequest $request, $id)
    {
        // validated input data...
        $validated  = $request->validated();
        $data       = Account::findOrFail($id);
        $input      = $request->all();

        // if active is not set, make it in-active
        if(!(isset($input['active']))){
            $input['active'] = 0;
        }

        $upd        = $data->update($input);
        return redirect()
                ->route('accounts.index')
                ->with('success','Record updated successfully.');
    }

    public function destroy(Account $account)
    {
        abort_if(Gate::denies('account-delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $account->delete();
        return back()->with('success','Record deleted successfully.');
    }

}
