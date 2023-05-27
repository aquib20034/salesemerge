<?php


namespace App\Http\Controllers;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Branch;
use App\Models\Company_has_transaction;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Auth;
use DataTables;


class CompanyController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:company-list', ['only' => ['index','show']]);
         $this->middleware('permission:company-create', ['only' => ['create','store']]);
         $this->middleware('permission:company-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:company-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Company::query()
                ->orderBy('companies.created_at','DESC')
                ->select('companies.*')
                ->get();
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'company-list';
                $editGate = 'company-edit';
                $deleteGate = 'company-delete';
                $crudRoutePart = 'companies';

                return view('partials.datatableActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });
//            $table->editColumn('sno', function ($row) {
//                return $row->id ? $row->id : 0 ;
//            });

//            If any modififcation require before present in the grid, use editColumn
//            $table->editColumn('owner_name', function ($row) {
//                return $row->owner_name ? $row->owner_name : '';
//            });
//
//            $table->editColumn('contact_no', function ($row) {
//                return $row->contact_no ? $row->contact_no : '';
//            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        return view('companies.index');
    }

    public function create()
    {
        $amount_types =DB::table('amount_types')
                            ->select('amount_types.name','amount_types.id')
                            ->pluck('name','id')->all();

        return view('companies.create',compact('amount_types'));
    }

    public function store(Request $request)
    {
        request()->validate([
            'name'          => 'required|min:3|unique:companies,name',
            'owner_name'    => 'required|min:3',
        ]);

        $data                   = company::create($request->all());
        $company_id             =  $data['id'];

        $val                    =  new Company_has_transaction();
        $val->company_id        = $company_id;
        $val->payment_method_id = 1;
        $val->payment_detail    = "Account Opening";

        if($request['previous_amount'] >= 0){
            $val->credit            = $request['previous_amount'];
        }else{
            $val->debit             = ((-1)* ($request['previous_amount']));
        }
        $val->save();

        return redirect()
                ->route('companies.index')
                ->with('success','Company '.$request['name'] .' added successfully.');
    }

    public function show($id)
    {
        $data = Company::query()
                    ->select('companies.*')
                    ->where('companies.id', $id)
                    ->first();

        return view('companies.show',compact('data'));
    }


    public function edit($id)
    {
        $data = Company::query()
                    ->select('companies.*')
                    ->where('companies.id', $id)
                    ->first();

        return view('companies.edit',compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = company::findOrFail($id);
        $this->validate($request,[
            'name'          => 'required|min:3|unique:companies,name,'. $id,
            'owner_name'    => 'required|min:3',
        ]);

        $input['customer_id']     =  $id;

        if($request['previous_amount']>=0){
            $input['credit']     = $request['previous_amount'];
            $input['debit']      = null;
        }else{
            $input['debit']      = ((-1)* ($request['previous_amount']));
            $input['credit']     = null;
        }

        $transaction             = Company_has_transaction::where('company_id', '=', $id)
                                        ->where('payment_detail','Account Opening')
                                        ->first();
        $data->update($request->all());
        $transaction->update($input);

        return redirect()
                ->route('companies.index')
                ->with('success','Company '.$request['name'] .' updated successfully.');
    }

    public function destroy(Company $company)
    {
        abort_if(Gate::denies('company-delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $company->delete();
        return back();
    }

//    public function destroy2(Request $request)
//    {
//        $ids = $request->ids;
//        $data = Company::query()->whereIn('id',explode(",",$ids))->delete();
//        return response()->json(['success'=>"deleted successfully."]);
//    }

    public function massDestroy(MassDestroyMemberRequest $request)
    {
        Company::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }




}
