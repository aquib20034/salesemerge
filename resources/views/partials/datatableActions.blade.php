@can($viewGate)
    <a class="btn btn-xs btn-primary" href="{{ route($crudRoutePart . '.show', $row->id) }}" title="View">
        <i class="fa fa-eye"></i>
    </a>
@endcan
@can($editGate)
    <a class="btn btn-xs btn-info" href="{{ route( $crudRoutePart . '.edit', $row->id) }}" title="Edit">
        <i class="fa fa-pen"></i>
    </a>
@endcan
@can($deleteGate)
    <form action="{{ route( $crudRoutePart . '.destroy', $row->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit" class="btn btn-xs btn-danger" title="Delete"><i class="fas fa-trash-alt"></i></button>
    </form>
@endcan
