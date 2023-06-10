<?php
namespace App\Http\Controllers;
use DB;
use Auth;
use Gate;
use DataTables;
use App\Models\Unit;
use App\Models\Company;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Requests\UnitRequest;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class UnitController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:unit-list', ['only' => ['index','show']]);
         $this->middleware('permission:unit-create', ['only' => ['create','store']]);
         $this->middleware('permission:unit-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:unit-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $company_id = Auth::user()->company_id;
            $query      = Unit::where('company_id',$company_id)->orderBy('units.name','ASC')->get();
            $table      = DataTables::of($query);

            $table->addColumn('srno', '');
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate       = 'unit-list';
                $editGate       = 'unit-edit';
                $deleteGate     = 'unit-delete';
                $crudRoutePart  = 'units';

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
        return view('units.index');
    }

    public function create()
    {
        $company_id = Auth::user()->company_id;
        $companies  = Company::where('id',$company_id)->pluck('name','id')->all();
        $branches   = Branch::where('company_id',$company_id)->pluck('name','id')->all();
        return view('units.create',compact('companies','branches'));
    }

    public function store(UnitRequest $request)
    {
        // Retrieve the validated input data...
        $validated      = $request->validated();
        $data           = Unit::create($request->all());

        return redirect()
                ->route('units.index')
                ->with('success','Record added successfully.');
    }

     public function show($id)
    {
        $company_id = Auth::user()->company_id;
        $data       = Unit::where('company_id',$company_id)->findOrFail($id);
        return view('units.show',compact('data'));
    }


    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $data       = Unit::where('company_id',$company_id)->findOrFail($id);

        $companies  = Company::where('id',$company_id)->pluck('name','id')->all();
        $branches   = Branch::where('company_id',$company_id)->pluck('name','id')->all();
        return view('units.edit',compact('data','companies','branches'));
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
                ->route('units.index')
                ->with('success','Record updated successfully.');
    }

    public function destroy(Unit $unit)
    {
        abort_if(Gate::denies('unit-delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $unit->delete();
        return back()->with('success','Record deleted successfully.');
    }

}
