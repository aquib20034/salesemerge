<?php
namespace App\Http\Controllers;
use DB;
use Auth;
use Gate;
use App\Models\Branch;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Models\Company_has_transaction;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;


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
        return back()->with('permission','Invalid route');
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $query      = Company::where('id',$company_id) // get logged in user company only.
                            ->orderBy('companies.created_at','DESC')
                            ->get();

            $table      = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate       = 'company-list';
                $editGate       = 'company-edit';
                $deleteGate     = 'company-delete';
                $crudRoutePart  = 'companies';

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
        return view('companies.index');
    }

    public function create()
    {
        return back()->with('permission','Invalid route');
        return view('companies.create');
    }

    public function store(CompanyRequest $request)
    {
        return back()->with('permission','Invalid route');
        $data       = company::create($request->all());
        return redirect()
                    ->route('companies.edit', Auth::user()->company_id)
                    ->with('success','Record added successfully.');
    }

    public function show($id)
    {
        return back()->with('permission','Invalid route');
        $company_id     = Auth::user()->company_id;
        $data           = Company::where('id',$company_id)->findOrFail($id);
        return view('companies.show',compact('data'));
    }

    public function edit($id)
    {
        $company_id     = Auth::user()->company_id;
        $data           = Company::where('id',$company_id)->findOrFail($id); // no one can edit another company
        return view('companies.edit',compact('data'));
    }

    public function update(CompanyRequest $request, $id)
    {
        $data           = Company::findOrFail($id);
                          $data->update($request->all());
        return redirect()
                    ->route('companies.edit', Auth::user()->company_id)
                    ->with('success','Record updated successfully.');
    }

    public function destroy(Company $company)
    {
        abort_if(Gate::denies('company-delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $company->delete();
        return back()->with('success','Record deleted successfully.');
    }

    public function massDestroy(MassDestroyMemberRequest $request)
    {
        Company::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
