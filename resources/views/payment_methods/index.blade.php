@extends('layouts.main')
@section('title','Payment Methods')
@section('content')

@include( '../sweet_script')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">@yield('title')</h4>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Manage @yield('title')</h4>
                        @can('user-create')
                            <a  href="{{ route('payment_methods.create') }}" class="btn btn-primary btn-round ml-auto">
                            <i class="fa fa-plus"></i> Add new</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="myTable" class="display table table-striped table-hover" style="width: 100%;" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th> Payment Method Name</th>
                                    <th width="10%" >Action</th>
                                </tr>
                            </thead>
                          
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {  

    var t = $('#myTable').DataTable({
          "aaSorting": [],
            "processing": true,
            "serverSide": false,
            "select":true,
            "ajax": "{{ url('payment_methodList') }}",
            "method": "GET",
            "columns": [
                {"data": "srno"},
                {"data": "name"},
                {"data": "action",orderable:false,searchable:false}

            ]
        });
     t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();


    });
</script>
@endsection
