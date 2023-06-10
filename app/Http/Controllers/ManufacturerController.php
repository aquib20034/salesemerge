<?php
namespace App\Http\Controllers;
use DB;
use Auth;
use Gate;
use DataTables;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManufacturerRequest;
use Symfony\Component\HttpFoundation\Response;

class ManufacturerController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:manufacturer-list', ['only' => ['index','show']]);
         $this->middleware('permission:manufacturer-create', ['only' => ['create','store']]);
         $this->middleware('permission:manufacturer-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:manufacturer-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $query      = Manufacturer::where('company_id',$company_id)->orderBy('manufacturers.name','ASC')->get();
            $table      = DataTables::of($query);

            $table->addColumn('srno', '');
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate       = 'manufacturer-list';
                $editGate       = 'manufacturer-edit';
                $deleteGate     = 'manufacturer-delete';
                $crudRoutePart  = 'manufacturers';

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
        return view('manufacturers.index');
    }

    public function create()
    {
        return view('manufacturers.create');
    }

    public function store(ManufacturerRequest $request)
    {
        // Retrieve the validated input data...
        $validated      = $request->validated();
        $data           = Manufacturer::create($request->all());
      
        return redirect()
                ->route('manufacturers.index')
                ->with('success','Record added successfully.');
    }

     public function show($id)
    {
        $company_id     = Auth::user()->company_id;
        $data           = Manufacturer::where('company_id',$company_id)->findOrFail($id);
        return view('manufacturers.show',compact('data'));
    }

    public function edit($id)
    {
        $company_id    = Auth::user()->company_id;
        $data          = Manufacturer::where('company_id',$company_id)->findOrFail($id);
        return view('manufacturers.edit',compact('data'));
    }

    public function update(ManufacturerRequest $request, $id)
    {
        // validated input data...
        $validated  = $request->validated();
        $company_id = Auth::user()->company_id;
        $data       = Manufacturer::where('company_id',$company_id)->findOrFail($id);
        $input      = $request->all();

        // if active is not set, make it in-active
        if(!(isset($input['active']))){
            $input['active'] = 0;
        }

        $upd        = $data->update($input);
        return redirect()
                ->route('manufacturers.index')
                ->with('success','Record updated successfully.');
    }

    public function destroy(Manufacturer $manufacturer)
    {
        abort_if(Gate::denies('manufacturer-delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $manufacturer->delete();
        return back()->with('success','Record deleted successfully.');
    }
}
