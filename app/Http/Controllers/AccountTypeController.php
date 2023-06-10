<?php
namespace App\Http\Controllers;
use DB;
use Auth;
use Gate;
use DataTables;
use App\Models\AccountType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountTypeRequest;
use Symfony\Component\HttpFoundation\Response;

class AccountTypeController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:account_type-list', ['only' => ['index','show']]);
         $this->middleware('permission:account_type-create', ['only' => ['create','store']]);
         $this->middleware('permission:account_type-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:account_type-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $query      = AccountType::where('company_id',$company_id)->get();
            $table      = DataTables::of($query);

            $table->addColumn('srno', '');
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('parent_id', function ($row) {
                if(isset($row->parent_id)){
                    return $row->getAllParentTypes();
                }
                return "";
            });

            $table->editColumn('actions', function ($row) {
                $viewGate       = 'account_type-list';
                $editGate       = 'account_type-edit';
                $deleteGate     = 'account_type-delete';
                $crudRoutePart  = 'account_types';

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
        return view('account_types.index');
    }

    public function create()
    {
        $company_id     = Auth::user()->company_id;
        $account_types     = AccountType::where('company_id',$company_id)->pluck('name','id')->all();
        return view('account_types.create',compact('account_types'));
    }

    public function store(AccountTypeRequest $request)
    {
        // Retrieve the validated input data...
        $validated      = $request->validated();
        $data           = AccountType::create($request->all());
      
        return redirect()
                ->route('account_types.index')
                ->with('success','Record added successfully.');
    }

     public function show($id)
    {
        $company_id     = Auth::user()->company_id;
        $data           = AccountType::where('company_id',$company_id)->findOrFail($id);
        return view('account_types.show',compact('data'));
    }

    public function edit($id)
    {
        $company_id     = Auth::user()->company_id;
        $account_types  = AccountType::where('company_id',$company_id)->where('id','!=',$id)->pluck('name','id')->all();
        $data           = AccountType::where('company_id',$company_id)->findOrFail($id);
        return view('account_types.edit',compact('data','account_types'));
    }

    public function update(AccountTypeRequest $request, $id)
    {
        // validated input data...
        $validated  = $request->validated();
        $company_id = Auth::user()->company_id;
        $data       = AccountType::where('company_id',$company_id)->findOrFail($id);
        $input      = $request->all();

        // if active is not set, make it in-active
        if(!(isset($input['active']))){
            $input['active'] = 0;
        }

        $upd        = $data->update($input);
        return redirect()
                ->route('account_types.index')
                ->with('success','Record updated successfully.');
    }

    public function destroy(AccountType $account_type)
    {
        $data       = AccountType::where('parent_id',$account_type->id)->first();
        if(isset($data->id)){
            return back()->with('permission','Delete Failed! Parent Id of other account type.');
        }else{
            abort_if(Gate::denies('account_type-delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $account_type->delete();
            return back()->with('success','Record deleted successfully.');
        }
    }
}
