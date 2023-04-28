@extends('layouts.main')
@section('content')
@include( '../sweet_script')

<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">{{trans('module.units')}}</h4>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">{{trans('global.manage')}} {{trans('module.units')}}</h4>
                        @can('unit-create')
                            <a  href="{{ route('units.create') }}" class="btn btn-primary btn-xs ml-auto">
                            <i class="fa fa-plus"></i> {{trans('global.add_new')}}</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="myTable" class="table table-hover" style="width: 100%;" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th> {{trans('module.unit_name')}}</th>
                                    <th width="10%" >{{trans('global.action')}}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {  
        $('#myTable').DataTable({
            "aaSorting" : [],
            "processing": true,
            "serverSide": true,
            "select"    :true,
            "ajax"      : "{{ url('units_list') }}",
            "method"    : "GET",
            "columns"   : [
                {"data": "DT_RowIndex"},
                {"data": "name"},
                {"data": "action",orderable:false,searchable:false}
            ]
        });
    });
</script>
@endsection
