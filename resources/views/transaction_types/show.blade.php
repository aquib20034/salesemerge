@extends('layouts.main')
@section('title',isset($title) ? $title : "")
@section('content')
@include( '../sweet_script')
    <div class="page-inner">
        <h4 class="page-title">@yield('title')</h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">@yield('title')</h4>
                            <a  href="{{ route($page.'.index') }}" class="btn btn-primary btn-xs ml-auto">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="table-responsive">
                                    <table class="table dt-responsive">
                                        <tr>
                                            <th width="50%">Transaction type name</th>
                                            <td>{{isset($data->name) ? $data->name : ""}}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th>Company</th>
                                            <td>{{isset($data->company->name) ? $data->company->name : ""}}</td>
                                        </tr>
                                        <tr>
                                            <th>Branch</th>
                                            <td>{{isset($data->branch->name) ? $data->branch->name : ""}}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                @if((isset($data->active)) && ( ($data->active == 1) || ($data->active == "Active") ) )
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <br>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
  

@endsection
