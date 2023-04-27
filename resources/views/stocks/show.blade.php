@extends('layouts.main')
@section('title','Purchases')
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
                        <h4 class="card-title">Show @yield('title')</h4>
                        <a  href="{{ route('purchases.index') }}" class="btn btn-primary btn-round ml-auto">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="table-responsive">
                                <table class="table dt-responsive">
                                    <tr>
                                        <td>Customer Name</td>
                                        <td>{{$data->customer_name}}</td>
                                    </tr>
                                 
                                    <tr>
                                        <td>Contact No</td>
                                        <td>{{$data->contact_no}}</td>
                                    </tr>
                                 
                                  
                                    <tr>
                                        <td>Address </td>
                                        <td>{{$data->address}}</td>
                                    </tr>

                                    <tr>
                                        <td>Payment Method </td>
                                        <td>{{$data->payment_method_name}}</td>
                                    </tr>

                                    <tr>
                                        <td>Payment Detail </td>
                                        <td>{{$data->payment_detail}}</td>
                                    </tr>

                                    <tr>
                                        <td>Total </td>
                                        <td>{{$data->total_amount}}</td>
                                    </tr>


                                 
                                    <tr>
                                        <td>Payment </td>
                                        <td>{{$data->credit}}</td>
                                    </tr>
                                </table><br><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Item Details</h4>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="table-responsive">
                                <table class="table dt-responsive">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Piece</th>
                                            <th>Qty in Ctn/ Bora# </th>
                                            <th>Sell Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($selected_items as $key =>$value)
                                            <tr>
                                                <td>{{$value->item_name}} - {{$value->unit_name}}</td>
                                                <td>{{$value->unit_piece}}</td>
                                                <td>{{$value->sell_price}}</td>
                                                <td>{{$value->sell_qty}}</td>
                                            </tr>
                                        @endforeach
                                    <tbody>

                                </table><br><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
  

@endsection
