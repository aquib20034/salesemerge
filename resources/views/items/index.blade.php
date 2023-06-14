@extends('layouts.main')
@section('title','Items')
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
                            @can('item-create')
                                <a  href="{{ route('items.create') }}" class="btn btn-primary btn-xs ml-auto">
                                <i class="fa fa-plus"></i> New</a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="myTable" class="table table-hover" style="width: 100%;" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Manufacturer</th>
                                        <th>Category</th>
                                        <th>Group</th>
                                        <th>Unit</th>
                                        <th>Total piece</th>
                                        <th>Free piece</th>
                                        <th>Purchase price</th>
                                        <th>Sell price</th>
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
            @can('item-delete')
                deleteButton = DeleteButtonCall("{{ route('items.massDestroy') }}")
            dtButtons.push(deleteButton)
            @endcan
            let data = [
                { data: 'name', name: 'name' },
                { data: 'manufacturer_id', name: 'manufacturer_id' },
                { data: 'category_id', name: 'category_id' },
                { data: 'group_id', name: 'group_id' },
                { data: 'unit_id', name: 'unit_id' },
                { data: 'tot_piece', name: 'tot_piece' },
                { data: 'free_piece', name: 'free_piece' },
                { data: 'purchase_price', name: 'purchase_price' },
                { data: 'sell_price', name: 'sell_price' },
                { data: 'active', name: 'active' },
                { data: 'actions', name: '{{ trans('global.actions') }}',orderable:false,searchable:false }
            ]
            DataTableCall('#myTable', "{{ route('items.index') }}", dtButtons, data)
        });
    </script>
@endsection
