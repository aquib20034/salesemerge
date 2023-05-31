<?php
namespace App\Http\Controllers;
use DB;
use Auth;
use Gate;
use DataTables;
use App\Models\Unit;
use App\Models\Item;
use App\Models\Company;
use App\Models\Branch;
use App\Models\Group;
use App\Models\Category;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use App\Http\Requests\ItemRequest;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class ItemController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:item-list', ['only' => ['index','show']]);
         $this->middleware('permission:item-create', ['only' => ['create','store']]);
         $this->middleware('permission:item-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:item-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $company_id     = Auth::user()->company_id;
            $query          = Item::where('company_id',$company_id)->orderBy('items.name','ASC')->get();
            $table          = DataTables::of($query);

            $table->addColumn('srno', '');
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('manufacturer_id', function ($row) {
                if(isset($row->manufacturer_id)){
                    if(isset($row->manufacturer->name)){
                        return $row->manufacturer->name;
                    }
                }
                return "";
            });

            $table->editColumn('category_id', function ($row) {
                if(isset($row->category_id)){
                    if(isset($row->category->name)){
                        return $row->category->name;
                    }
                }
                return "";
            });

            $table->editColumn('group_id', function ($row) {
                if(isset($row->group_id)){
                    if(isset($row->group->name)){
                        return $row->group->name;
                    }
                }
                return "";
            });

            $table->editColumn('unit_id', function ($row) {
                if(isset($row->unit_id)){
                    if(isset($row->unit->name)){
                        return $row->unit->name;
                    }
                }
                return "";
            });

            $table->editColumn('actions', function ($row) {
                $viewGate       = 'item-list';
                $editGate       = 'item-edit';
                $deleteGate     = 'item-delete';
                $crudRoutePart  = 'items';

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
        return view('items.index');
    }

    public function create()
    {
        $company_id     = Auth::user()->company_id;

        $units          = Unit::where('company_id',$company_id)->pluck('name','id')->all();
        $groups         = Group::where('company_id',$company_id)->pluck('name','id')->all();
        $branches       = Branch::where('company_id',$company_id)->pluck('name','id')->all();
        $companies      = Company::where('id',$company_id)->pluck('name','id')->all();
        $categories     = Category::where('company_id',$company_id)->pluck('name','id')->all();
        $manufacturers  = Manufacturer::where('company_id',$company_id)->pluck('name','id')->all();
        return view('items.create',compact('units','groups','branches','companies','categories','manufacturers'));
    }

    public function store(ItemRequest $request)
    {
        // Retrieve the validated input data...
        $validated      = $request->validated();
        $data           = Item::create($request->all());
      
        return redirect()
                ->route('items.index')
                ->with('success','Record added successfully.');
    }

     public function show($id)
    {
        $company_id = Auth::user()->company_id;
        $data       = Item::where('company_id',$company_id)->findOrFail($id);
        return view('items.show',compact('data'));
    }


    public function edit($id)
    {
        $company_id     = Auth::user()->company_id;
        $data           = Item::where('company_id',$company_id)->findOrFail($id);
        $units          = Unit::where('company_id',$company_id)->pluck('name','id')->all();
        $groups         = Group::where('company_id',$company_id)->pluck('name','id')->all();
        $branches       = Branch::where('company_id',$company_id)->pluck('name','id')->all();
        $companies      = Company::where('id',$company_id)->pluck('name','id')->all();
        $categories     = Category::where('company_id',$company_id)->pluck('name','id')->all();
        $manufacturers  = Manufacturer::where('company_id',$company_id)->pluck('name','id')->all();
        return view('items.edit',compact('data','units','groups','branches','companies','categories','manufacturers'));

    }


    public function update(ItemRequest $request, $id)
    {
        // validated input data...
        $validated  = $request->validated();
        $company_id = Auth::user()->company_id;
        $data       = Item::where('company_id',$company_id)->findOrFail($id);
        $input      = $request->all();

        // if active is not set, make it in-active
        if(!(isset($input['active']))){
            $input['active'] = 0;
        }

        $upd        = $data->update($input);
        return redirect()
                ->route('items.index')
                ->with('success','Record updated successfully.');
    }

    public function destroy(Item $item)
    {
        abort_if(Gate::denies('item-delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $item->delete();
        return back()->with('success','Record deleted successfully.');
    }

}
