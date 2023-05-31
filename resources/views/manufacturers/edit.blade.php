@extends('layouts.main')
@section('title','Manufacturer')
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
                            <h4 class="card-title">Edit @yield('title')</h4>
                            <a  href="{{ route('manufacturers.index') }}" class="btn btn-primary btn-xs ml-auto">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>

                    <!--begin::Form-->
                        {!! Form::model($data, ['method' => 'PATCH','id'=>'form','enctype'=>'multipart/form-data','route' => ['manufacturers.update', $data->id]]) !!}
                            {{  Form::hidden('updated_by', Auth::user()->id ) }}
                            {{  Form::hidden('action', "update" ) }}
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                {!! Html::decode(Form::label('name','Manufacturer name <span class="text-danger">*</span>')) !!}
                                                {{ Form::text('name', null, array('placeholder' => 'Enter manufacturer name','class' => 'form-control','autofocus' => ''  )) }}
                                                @if ($errors->has('name'))  
                                                    {!! "<span class='span_danger'>". $errors->first('name')."</span>"!!} 
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                {!! Html::decode(Form::label('active','Active<span class="text-danger">*</span>')) !!}<br>
                                                <span class="switch switch-sm switch-icon switch-success">
                                                <?php $actv = (isset($data->active) && ($data->active == "Active") || ($data->active == 1)) ? 1 : 0; ?>
                                                    <label>
                                                        {!! Form::checkbox('active',1,$actv,  array('class' => 'form-control', 'data-toggle'=>'toggle', 'data-onstyle'=>'success', 'data-style' => 'btn-round')) !!}
                                                        <span></span>
                                                    </label>
                                                </span>
                                            
                                                @if ($errors->has('active'))  
                                                    {!! "<span class='span_danger'>". $errors->first('active')."</span>"!!} 
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-12 text-right">
                                        <button type="submit" class="btn btn-primary btn-sm mr-2">Save</button>
                                    </div>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    <!--end::Form-->
                </div>
            </div>
        </div>
    </div>
    {!! JsValidator::formRequest('App\Http\Requests\ManufacturerRequest', '#form'); !!}
@endsection
