<div class="btn-group btn-group">
    @can($viewGate)
        <a data-toggle="tooltip" title="" class="btn btn-warning btn-xs" data-original-title="View"  href="{{ route($crudRoutePart . '.show', $row->id) }}" >
            <i class="fa fa-eye"></i>
        </a>
    @endcan
    @can($editGate)
        <a data-toggle="tooltip" title="" class="btn btn-primary btn-xs" data-original-title="Edit Task" href="{{ route( $crudRoutePart . '.edit', $row->id) }}" >
            <i class="fa fa-edit"></i>
        </a>
    @endcan
    @can($deleteGate)
        <form action="{{ route( $crudRoutePart . '.destroy', $row->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit" class="btn btn-danger btn-xs" title="Delete"><i class="fas fa-trash-alt"></i></button>
        </form>
    @endcan
</div>
