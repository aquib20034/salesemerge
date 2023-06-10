<?php
namespace App\Http\Controllers;
use DB;
use Auth;
use Gate;
use DataTables;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:category-list', ['only' => ['index','show']]);
         $this->middleware('permission:category-create', ['only' => ['create','store']]);
         $this->middleware('permission:category-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:category-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $query      = Category::where('company_id',$company_id)->orderBy('categories.name','ASC')->get();
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
                    return $row->getAllParentCategories();
                }
                return "";
            });

            $table->editColumn('actions', function ($row) {
                $viewGate       = 'category-list';
                $editGate       = 'category-edit';
                $deleteGate     = 'category-delete';
                $crudRoutePart  = 'categories';

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
        return view('categories.index');
    }

    public function create()
    {
        $company_id     = Auth::user()->company_id;
        $categories     = Category::where('company_id',$company_id)->pluck('name','id')->all();
        return view('categories.create',compact('categories'));
    }

    public function store(CategoryRequest $request)
    {
        // Retrieve the validated input data...
        $validated      = $request->validated();
        $data           = Category::create($request->all());
      
        return redirect()
                ->route('categories.index')
                ->with('success','Record added successfully.');
    }

     public function show($id)
    {
        $company_id     = Auth::user()->company_id;
        $data           = Category::where('company_id',$company_id)->findOrFail($id);
        return view('categories.show',compact('data'));
    }

    public function edit($id)
    {
        $company_id     = Auth::user()->company_id;
        $categories     = Category::where('company_id',$company_id)->where('id','!=',$id)->pluck('name','id')->all();
        $data           = Category::where('company_id',$company_id)->findOrFail($id);
        return view('categories.edit',compact('data','categories'));
    }

    public function update(CategoryRequest $request, $id)
    {
        // validated input data...
        $validated  = $request->validated();
        $company_id = Auth::user()->company_id;
        $data       = Category::where('company_id',$company_id)->findOrFail($id);
        $input      = $request->all();

        // if active is not set, make it in-active
        if(!(isset($input['active']))){
            $input['active'] = 0;
        }

        $upd        = $data->update($input);
        return redirect()
                ->route('categories.index')
                ->with('success','Record updated successfully.');
    }

    public function destroy(Category $category)
    {
        $data       = Category::where('parent_id',$category->id)->first();
        if(isset($data->id)){
            return back()->with('permission','Delete Failed! Parent Id of other category.');
        }else{
            abort_if(Gate::denies('category-delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $category->delete();
            return back()->with('success','Record deleted successfully.');
        }
    }
}
