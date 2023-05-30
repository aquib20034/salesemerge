@extends('layouts.main')
@section('content')
@section('title','unit')
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

                <!--begin::Form-->
                {!! Form::model($data, ['method' => 'PATCH','id'=>'form','enctype'=>'multipart/form-data','route' => ['units.update', $data->id]]) !!}
                    {{  Form::hidden('update_by', Auth::user()->id ) }}

                    <div class="card-body">
                        <div class=" row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('name',trans("module.unit_name").'<span class="text-danger">*</span>')) !!}
                                    {{ Form::text('name', null, array('placeholder' => trans("global.enter")." ".trans("module.unit_name"),'class' => 'form-control','autofocus' => '','required'  )) }}
                                    @if ($errors->has('name'))  
                                        {!! "<span class='span_danger'>". $errors->first('name')."</span>"!!} 
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @component('components.web.form.footer.style1')
                    @endcomponent
                    
                {!! Form::close() !!}
                <!--end::Form-->
            </div>
        </div>
    </div>
</div>
  

@endsection
