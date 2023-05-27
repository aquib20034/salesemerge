    <button type="button" class="btn btn-primary btn-xs " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-ellipsis-v"></i>
    </button>
    <div class="dropdown-menu">
        <div class="arrow"></div>
        @can($viewGate)
            <a data-toggle="tooltip" title="" class="dropdown-item" data-original-title="View"  href="{{ route($crudRoutePart . '.show', $row->id) }}" >
                <i class="fa fa-eye"></i> View
            </a>
        @endcan

        @can($editGate)
            <a data-toggle="tooltip" title="" class="dropdown-item" data-original-title="Edit Task" href="{{ route( $crudRoutePart . '.edit', $row->id) }}" >
                <i class="fa fa-edit"></i> Edit
            </a>
        @endcan
        
        @can($deleteGate)
            <form action="{{ route( $crudRoutePart . '.destroy', $row->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="dropdown-item" title="Delete"><i class="fas fa-trash-alt"></i> Delete</button>
            </form>
        @endcan
    </div>
