@extends('layouts.main')
@section('title','Roles')
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
                        @can('role-create')
                            <a  href="{{ route('roles.create') }}" class="btn btn-primary btn-xs ml-auto">
                            <i class="fa fa-plus"></i> Add new</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless table-striped table-hover ajaxTable datatable datatable-Role">
                            <thead>
                            <tr>
                                <th> Name</th>
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
    @endsection
    @section('scripts')
        @parent
        <script>
            $(function () {
                let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
                @can('role-delete')
                    deleteButton = DeleteButtonCall("{{ route('roles.massDestroy') }}")
                dtButtons.push(deleteButton)
                @endcan
                let data = [
                    { data: 'name', name: 'name' },
                    { data: 'actions', name: '{{ trans('global.actions') }}',orderable:false,searchable:false }
                ]
                DataTableCall('.datatable-Role', "{{ route('roles.index') }}", dtButtons, data)
            });
        </script>
@endsection



