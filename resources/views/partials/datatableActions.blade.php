    <button type="button" class="btn btn-primary btn-xs " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-ellipsis-v"></i>
    </button>
    <div class="dropdown-menu">
        <div class="arrow"></div>
        @can($viewGate)
            @if(($action ?? '') == 'branch_update')
                <a  title="" class="dropdown-item" data-original-title="View" href="javascript:void(0);"  onclick="ViewBranch({{$row->id}});" data-toggle="modal" data-target="#BranchView">
                    <i class="fa fa-eye"></i> View
                </a>
            @else
            <a data-toggle="tooltip" title="" class="dropdown-item" data-original-title="View"  href="{{ route($crudRoutePart . '.show', $row->id) }}" >
                <i class="fa fa-eye"></i> View
            </a>
            @endif
        @endcan

        @can($editGate)
            @if(($action ?? '') == 'branch_update')
                <a  title="" class="dropdown-item" data-original-title="Edit Task" href="javascript:void(0);"  onclick="GetBranch({{$row->id}});"  data-toggle="modal" data-target="#BranchUpdate">
                    <i class="fa fa-edit"></i> Edit
                </a>
            @else
                <a data-toggle="tooltip" title="" class="dropdown-item" data-original-title="Edit Task" href="{{ route( $crudRoutePart . '.edit', $row->id) }}"  onclick="">
                    <i class="fa fa-edit"></i> Edit
                </a>
            @endif
        @endcan

        @can($deleteGate)
            <form action="{{ route( $crudRoutePart . '.destroy', $row->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="dropdown-item" title="Delete"><i class="fas fa-trash-alt"></i> Delete</button>
            </form>
        @endcan
    </div>
