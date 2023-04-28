@extends('layouts.main')
@section('title','Units')
@section('content')
@include( '../sweet_script')


<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">{{trans("module.units")}}</h4>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">{{trans("global.edit")}} {{trans("module.unit")}}</h4>
                        <a  href="{{ route('units.index') }}" class="btn btn-primary btn-xs ml-auto">
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
                                        <td width="50%">{{trans("module.unit_name")}}</td>
                                        <td>{{$data->name}}</td>
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
