<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class BranchController extends Controller
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
            $query = Branch::query()
                    ->orderBy('branches.created_at','DESC')
                ->select('Branch.*')
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

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        return view('branches.index');
    }

    public function create()
    {
        return view('branches.create');
    }

    public function store(Request $request)
    {
        request()->validate([
            'name'          => 'required|min:3|unique:companies,name',
        ]);

        $data                   = Branch::create($request->all());
//        return redirect()
//                ->route('companies.index')
//                ->with('success','Company '.$request['name'] .' added successfully.');
        return response()->json(['status' => 200, 'data' => array(), 'msg' => "Branch added Successfully", 'alert' => "success"]);

    }

    public function show($id)
    {
        $data = Branch::query()
            ->select('branches.*')
            ->where('branches.id', $id)
            ->first();

        return view('branches.show',compact('data'));
    }

    public function edit($id)
    {
        $data = Branch::query()
            ->select('branches.*')
            ->where('branches.id', $id)
            ->first();

        return view('branches.edit',compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = Branch::findOrFail($id);
        $this->validate($request,[
            'name'          => 'required|min:3|unique:companies,name,'. $id
        ]);
        return redirect()
            ->route('companies.index')
            ->with('success','Company '.$request['name'] .' updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        abort_if(Gate::denies('company-delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $branch->delete();
        return back();
    }

    public function massDestroy(MassDestroyMemberRequest $request)
    {
        Branch::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}
