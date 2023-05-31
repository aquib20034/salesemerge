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
                        <h4 class="card-title">Show @yield('title')</h4>
                        <a  href="{{ route('items.index') }}" class="btn btn-primary btn-round ml-auto">
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
                                        <td width="50%">Item Name</td>
                                        <td>{{$data->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Company Name</td>
                                        <td>{{$data->company_name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Unit Name</td>
                                        <td>{{$data->unit_name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Total Piece</td>
                                        <td>{{$data->tot_piece}}</td>
                                    </tr>
                                    <tr>
                                        <td>Free Piece </td>
                                        <td>{{$data->free_piece}}</td>
                                    </tr>
                                    <tr>
                                        <td>Purchase Price</td>
                                        <td>{{$data->purchase_price}}</td>
                                    </tr>
                                    <tr>
                                        <td>Selling Price</td>
                                        <td>{{$data->sell_price}}</td>
                                    </tr>
                                    <tr>
                                        <td>Company Percentage</td>
                                        <td>{{$data->company_percentage}}</td>
                                    </tr>
                                    <tr>
                                        <td>T.O Scheme by cartoon</td>
                                        <td>{{$data->to_percentage}}</td>
                                    </tr>
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
