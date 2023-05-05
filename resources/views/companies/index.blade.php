@extends('layouts.main')
@section('title','Companies')
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
                        @can('company-create')
                            <a  href="{{ route('companies.create') }}" class="btn btn-primary btn-round ml-auto">
                            <i class="fa fa-plus"></i> Add new</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable-Company" class="display table table-striped table-hover" style="width: 100%;" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th> Company Name</th>
                                    <th> Contact#</th>
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
            "ajax": "{{ url('companyList') }}",
            "method": "GET",
            "columns": [
                {"data": "srno"},
                {"data": "name"},
                {"data": "contact_no"},
                {"data": "action",orderable:false,searchable:false}

            ]
        });
     t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
    });




    $(function () {
        debugger;
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        @can('earning_delete')
        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('companies.massDestroy') }}",
            className: 'btn-danger',
            action: function (e, dt, node, config) {
                var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
                    return entry.id
                });

                if (ids.length === 0) {
                    alert('{{ trans('global.datatables.zero_selected') }}')

                    return
                }

                if (confirm('{{ trans('global.areYouSure') }}')) {
                    $.ajax({
                        headers: {'x-csrf-token': _token},
                        method: 'POST',
                        url: config.url,
                        data: { ids: ids, _method: 'DELETE' }})
                        .done(function () { location.reload() })
                }
            }
        }
        dtButtons.push(deleteButton)
        @endcan

        let dtOverrideGlobals = {
            buttons: dtButtons,
            processing: true,
            serverSide: true,
            retrieve: true,
            aaSorting: [],
            ajax: "{{ route('companies.index') }}",
            columns: [
                { data: 'srno', name: 'srno' },
                { data: 'name', name: 'name' },
                { data: 'contact_no', name: 'contact_no' },
                // { data: 'geteway', name: 'earnings.geteway' },
                { data: 'actions', name: '{{ trans('global.actions') }}' }
            ],
            orderCellsTop: true,
            order: [[ 1, 'desc' ]],
            pageLength: 100,
        };
        let table = $('.datatable-Company').DataTable(dtOverrideGlobals);
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });

    });


</script>
@endsection
