<?php
namespace App\Http\Controllers;
use DB;
use Auth;
use Gate;
use DataTables;
use App\Models\Company;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Models\TransactionType;
use App\Http\Requests\TransactionTypeRequest as CustomRequest;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class TransactionTypeController extends Controller
{
    public $data =  array();

    function __construct()
    {
        $this->data['title']                 = "Transaction types";
        $this->data['page']                  = "transaction_types";
        
        $this->data['permissions']['list']   = "transaction_type-list";
        $this->data['permissions']['create'] = "transaction_type-create";
        $this->data['permissions']['edit']   = "transaction_type-edit";
        $this->data['permissions']['delete'] = "transaction_type-delete";

        $this->middleware('permission:'.$this->data['permissions']['list'], ['only' => ['index','show']]);
        $this->middleware('permission:'.$this->data['permissions']['create'], ['only' => ['create','store']]);
        $this->middleware('permission:'.$this->data['permissions']['edit'], ['only' => ['edit','update']]);
        $this->middleware('permission:'.$this->data['permissions']['delete'], ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $company_id = Auth::user()->company_id;
            $query      = TransactionType::orderBy('transaction_types.name','ASC')->get();
            $table      = DataTables::of($query);

            $table->addColumn('srno', '');
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate       = $this->data['permissions']['list'];
                $editGate       = $this->data['permissions']['edit'];
                $deleteGate     = $this->data['permissions']['delete'];
                $crudRoutePart  = $this->data['page'];

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
        return view($this->data['page'] .'.index')->with($this->data);
    }

    public function create()
    {
        $company_id = Auth::user()->company_id;
        $companies  = Company::where('id',$company_id)->pluck('name','id')->all();
        $branches   = Branch::where('company_id',$company_id)->pluck('name','id')->all();
        return view($this->data['page'] .'.create',compact('companies','branches'))->with($this->data);
    }

    public function store(CustomRequest $request)
    {
        // Retrieve the validated input data...
        $validated      = $request->validated();
        $data           = TransactionType::create($request->all());

        return redirect()
                ->route($this->data['page'] .'.index')
                ->with('success','Record added successfully.')->with($this->data);
    }

     public function show($id)
    {
        $company_id = Auth::user()->company_id;
        $data       = TransactionType::where('company_id',$company_id)->findOrFail($id);
        return view($this->data['page'] .'.show',compact('data'))->with($this->data);
    }


    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $data       = TransactionType::where('company_id',$company_id)->findOrFail($id);

        $companies  = Company::where('id',$company_id)->pluck('name','id')->all();
        $branches   = Branch::where('company_id',$company_id)->pluck('name','id')->all();
        return view($this->data['page'] .'.edit',compact('data','companies','branches'))->with($this->data);
    }


    public function update(CustomRequest $request, $id)
    {
        // validated input data...
        $validated  = $request->validated();
        $data       = TransactionType::findOrFail($id);
        $input      = $request->all();

        // if active is not set, make it in-active
        if(!(isset($input['active']))){
            $input['active'] = 0;
        }

        $upd        = $data->update($input);
        return redirect()
                ->route($this->data['page'] .'.index')
                ->with('success','Record updated successfully.')->with($this->data);
    }

    public function destroy(TransactionType $transaction_type)
    {
        abort_if(Gate::denies('transaction_type-delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $transaction_type->delete();
        return back()->with('success','Record deleted successfully.')->with($this->data);
    }

}
