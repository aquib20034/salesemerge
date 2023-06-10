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
                        <h4 class="card-title">Show @yield('title')</h4>
                        <a  href="{{ route('companies.index') }}" class="btn btn-primary btn-round ml-auto">
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
                                        <td>Company Name</td>
                                        <td>{{$data->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Owner Name</td>
                                        <td>{{$data->owner_name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Contact No</td>
                                        <td>{{$data->contact_no}}</td>
                                    </tr>
                                    <tr>
                                        <td>Previous Amount</td>
                                        <td>{{$data->previous_amount}}</td>
                                    </tr>
                                    <tr>
                                        <td>Address </td>
                                        <td>{{$data->address}}</td>
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
