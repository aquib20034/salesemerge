<?php


namespace App\Http\Controllers;
use App\Http\Requests\CreateCompanyRequest;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Company_has_transaction;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Auth;


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
            //    $table->editColumn('sno', function ($row) {
            //        return $row->id ? $row->id : 0 ;
            //    });

            //    If any modififcation require before present in the grid, use editColumn
            //    $table->editColumn('owner_name', function ($row) {
            //        return $row->owner_name ? $row->owner_name : '';
            //    });

            //    $table->editColumn('contact_no', function ($row) {
            //        return $row->contact_no ? $row->contact_no : '';
            //    });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        return view('companies.index');
    }

    public function create()
    {
        $amount_types = DB::table('amount_types')
            ->select('amount_types.name','amount_types.id')
            ->pluck('name','id')->all();

        return view('companies.create');
    }

    public function store(CreateCompanyRequest $request)
    {
        $data = company::create($request->all());
        return response()->json(['status' => 200, 'data' => array(), 'msg' => "Company added Successfully", 'alert' => "success"]);
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

    public function update(CreateCompanyRequest $request, $id)
    {
        $data = company::findOrFail($id);
        $data->update($request->all());

        return redirect()
            ->route('companies.edit', Auth::user()->company_id)
            ->with('success','Company '.$request['name'] .' edit successfully.');
    }

    public function destroy(Company $company)
    {
        abort_if(Gate::denies('company-delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $company->delete();
        return back();
    }

    public function massDestroy(MassDestroyMemberRequest $request)
    {
        Company::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
