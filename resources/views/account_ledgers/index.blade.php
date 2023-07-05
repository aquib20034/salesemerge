@extends('layouts.main')
@section('title','Account ledger')
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
                          
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="myTable" class="table table-hover" style="width: 100%;" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Account title</th>
                                        <th>Trnx date</th>
                                        <th>Type</th>
                                        <th>Trnx Id</th>  <!-- custom id -->
                                        <th>Detail</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Created by</th>
                                        <th>Action</th>
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
            @can('account_ledger-delete')
                deleteButton = DeleteButtonCall("{{ route('account_ledgers.massDestroy') }}")
            @endcan
            dtButtons.push(deleteButton)
            let data = [
                    { data: 'account_id', name: 'account_id' },
                    { data: 'transaction_date', name: 'transaction_date' },
                    { data: 'transaction_type_id', name: 'transaction_type_id',orderable:false,searchable:false  },
                    { data: 'custom_id', name: 'custom_id' ,orderable:false,searchable:false },
                    { data: 'detail', name: 'detail' ,orderable:false,searchable:false },
                    { data: 'debit', name: 'debit' ,orderable:false,searchable:false },
                    { data: 'credit', name: 'credit' ,orderable:false,searchable:false },
                    { data: 'created_by', name: 'created_by' },
                    { data: 'actions', name: '{{ trans('global.actions') }}',orderable:false,searchable:false }
                ]
            DataTableCall('#myTable', "{{ route('account_ledgers.index') }}", dtButtons, data)
        });
    </script>
@endsection
