@extends('layouts.main')
@section('title','Accounts')
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
                            @can('account-create')
                                <a  href="{{ route('accounts.create') }}" class="btn btn-primary btn-xs ml-auto">
                                <i class="fa fa-plus"></i> Create a new account</a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="myTable" class="table table-hover" style="width: 100%;" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th width="20%">Name</th>
                                        <th>Type</th>
                                        <th>Limit</th>
                                        <th>City</th>
                                        <th>Branch</th>
                                        <th width="8%">Active</th>
                                        <th width="5%">Action</th>
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
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('account-delete')
                deleteButton = DeleteButtonCall("{{ route('accounts.massDestroy') }}")
            @endcan
            dtButtons.push(deleteButton)
            let data = [
                { data: 'name', name: 'name' },
                { data: 'account_type', name: 'account_type' },
                { data: 'account_limit', name: 'account_limit' },
                { data: 'city', name: 'city' },
                { data: 'branch', name: 'branch' },
                { data: 'active', name: 'active' },
                { data: 'actions', name: '{{ trans('global.actions') }}',orderable:false,searchable:false }
            ]
            DataTableCall('#myTable', "{{ route('accounts.index') }}", dtButtons, data)
        });
    </script>
@endsection
