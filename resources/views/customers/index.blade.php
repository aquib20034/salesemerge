@extends('layouts.main')
@section('title','Customers')
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
                        @can('customer-create')
                            <a  href="{{ route('customers.create') }}" class="btn btn-primary btn-round ml-auto">
                            <i class="fa fa-plus"></i> Add new</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless table-striped table-hover ajaxTable datatable datatable-Customer">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th> Full Name</th>
                                    <th> Contact#</th>
{{--                                    <th> Type</th>--}}
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
@endsection
@section('scripts')
    @parent
    <script>
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('customer-delete')
                deleteButton = DeleteButtonCall("{{ route('customers.massDestroy') }}")
            @endcan
            dtButtons.push(deleteButton)
            let data = [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'name', name: 'name' },
                { data: 'contact_no', name: 'contact_no' },
                { data: 'actions', name: '{{ trans('global.actions') }}' }
            ]
            DataTableCall('.datatable-Customer', "{{ route('customers.index') }}", dtButtons, data)
        });
    </script>
@endsection
