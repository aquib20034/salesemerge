@extends('layouts.main')
@section('title','Users')
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
                            <a  href="{{ route('users.create') }}" class="btn btn-primary btn-xs ml-auto">
                            <i class="fa fa-plus"></i> Add new</a>
                        @endcan
                        
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped dt-responsive  datatable-users" >
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th> Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th width="5%" >Action</th>
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
        @can('company-delete')
            deleteButton = DeleteButtonCall("{{ route('users.massDestroy') }}")
        @endcan
        dtButtons.push(deleteButton)
        let data = [
            { data: 'placeholder', name: 'placeholder',orderable:false,searchable:false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'rolename', name: 'rolename' },
            { data: 'actions', name: '{{ trans('global.actions') }}',orderable:false,searchable:false }
        ]
        DataTableCall('.datatable-users', "{{ route('users.index') }}", dtButtons, data)
    });
</script>

@endsection
