    <button type="button" class="btn btn-primary btn-xs " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-ellipsis-v"></i>
    </button>
    <div class="dropdown-menu">
        <div class="arrow"></div>
        @can($viewGate)
            <a data-toggle="tooltip" title="" class="dropdown-item" data-original-title="Print" href="javascript:void(0);"  onclick="printReceipt({{$row->id}});" >
                <i class="fa fa-print"></i> print
            </a>
        @endcan
    </div>
