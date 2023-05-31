<?php
namespace App\Http\Controllers;
use DB;
use Auth;
use Gate;
use DataTables;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\GroupRequest;
use Symfony\Component\HttpFoundation\Response;

class GroupController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:group-list', ['only' => ['index','show']]);
         $this->middleware('permission:group-create', ['only' => ['create','store']]);
         $this->middleware('permission:group-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:group-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $query      = Group::where('company_id',$company_id)->orderBy('groups.name','ASC')->get();
            $table      = DataTables::of($query);

            $table->addColumn('srno', '');
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            // $table->editColumn('parent_id', function ($row) {
            //     if(isset($row->parent_id)){
            //         if(isset($row->parent->name)){
            //             return $row->parent->name;
            //         }
            //     }
            //     return "";
            // });


            $table->editColumn('parent_id', function ($row) {
                if(isset($row->parent_id)){
                    return $row->getAllParentGroups();
                }
                return "";
            });

            $table->editColumn('actions', function ($row) {
                $viewGate       = 'group-list';
                $editGate       = 'group-edit';
                $deleteGate     = 'group-delete';
                $crudRoutePart  = 'groups';

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
        return view('groups.index');
    }

    public function create()
    {
        $company_id     = Auth::user()->company_id;
        $groups         = Group::where('company_id',$company_id)->pluck('name','id')->all();
        return view('groups.create',compact('groups'));
    }

    public function store(GroupRequest $request)
    {
        // Retrieve the validated input data...
        $validated      = $request->validated();
        $data           = Group::create($request->all());
      
        return redirect()
                ->route('groups.index')
                ->with('success','Record added successfully.');
    }

     public function show($id)
    {
        $company_id     = Auth::user()->company_id;
        $data           = Group::where('company_id',$company_id)->findOrFail($id);
        return view('groups.show',compact('data'));
    }

    public function edit($id)
    {
        $company_id  = Auth::user()->company_id;
        $groups      = Group::where('company_id',$company_id)->where('id','!=',$id)->pluck('name','id')->all();
        $data        = Group::where('company_id',$company_id)->findOrFail($id);
        return view('groups.edit',compact('data','groups'));
    }

    public function update(GroupRequest $request, $id)
    {
        // validated input data...
        $validated  = $request->validated();
        $company_id = Auth::user()->company_id;
        $data       = Group::where('company_id',$company_id)->findOrFail($id);
        $input      = $request->all();

        // if active is not set, make it in-active
        if(!(isset($input['active']))){
            $input['active'] = 0;
        }

        $upd        = $data->update($input);
        return redirect()
                ->route('groups.index')
                ->with('success','Record updated successfully.');
    }

    public function destroy(Category $category)
    {
        $data       = Group::where('parent_id',$group->id)->first();
        if(isset($data->id)){
            return back()->with('permission','Delete Failed! Parent Id of other category.');
        }else{
            abort_if(Gate::denies('group-delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $group->delete();
            return back()->with('success','Record deleted successfully.');
        }
    }
}
