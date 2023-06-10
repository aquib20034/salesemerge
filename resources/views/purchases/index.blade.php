@extends('layouts.main')
@section('title','Purchase')
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
                        @can('purchase-create')
                            <a  href="{{ route('purchases.create') }}" class="btn btn-primary btn-round ml-auto">
                            <i class="fa fa-plus"></i> Add new</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless table-striped table-hover ajaxTable datatable datatable-Purchase">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th> Order#</th>
                                    <th> Company Name</th>
                                    <th> Invoice Date</th>
                                    <th> Bilty</th>
                                    <th> Net Amount</th>
                                    <!-- <th> Payment</th> -->
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
{{--            @can('purchase-delete')--}}
{{--                deleteButton = DeleteButtonCall("{{ route('purchases.massDestroy') }}")--}}
{{--            @endcan--}}
            // dtButtons.push(deleteButton)
            let data = [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'order_no', name: 'order_no' },
                { data: 'company_name', name: 'company_name' },
                { data: 'invoice_date', name: 'invoice_date' },
                { data: 'bilty_amount', name: 'bilty_amount' },
                { data: 'net_amount', name: 'net_amount' },
                { data: 'actions', name: '{{ trans('global.actions') }}' }
            ]
            DataTableCall('.datatable-Purchase', "{{ route('purchases.index') }}", dtButtons, data)
        });
    </script>
@endsection
