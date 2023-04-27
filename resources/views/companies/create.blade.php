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
                        <h4 class="card-title">Add @yield('title')</h4>
                        <a  href="{{ route('companies.index') }}" class="btn btn-primary btn-round ml-auto">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        
                    </div>
                </div>

                    <!--begin::Form-->
                    {!! Form::open(array('route' => 'companies.store','method'=>'POST','id'=>'form','enctype'=>'multipart/form-data')) !!}
                        {{  Form::hidden('created_by', Auth::user()->id ) }}

                        <div class="card-body">
                            <div class=" row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('name','Company Name <span class="text-danger">*</span>')) !!}
                                        {{ Form::text('name', null, array('placeholder' => 'Enter full company name','class' => 'form-control','autofocus' => ''  )) }}
                                        @if ($errors->has('name'))  
                                            {!! "<span class='span_danger'>". $errors->first('name')."</span>"!!} 
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('owner_name','Owner Name  <span class="text-danger">*</span>')) !!}
                                        {{ Form::text('owner_name', null, array('placeholder' => 'Enter owner name','class' => 'form-control')) }}
                                        @if ($errors->has('owner_name'))  
                                            {!! "<span class='span_danger'>". $errors->first('owner_name')."</span>"!!} 
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('contact_no','Contact No')) !!}
                                        {!! Form::text('contact_no', null, array('placeholder' => 'Enter contact no','class' => 'form-control')) !!}
                                        @if ($errors->has('contact_no'))  
                                            {!! "<span class='span_danger'>". $errors->first('contact_no')."</span>"!!} 
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('previous_amount','Previous Amount')) !!}
                                        {!! Form::number('previous_amount', 0, array('placeholder' => 'Enter previous amount','class' => 'form-control')) !!}
                                        @if ($errors->has('previous_amount'))  
                                            {!! "<span class='span_danger'>". $errors->first('previous_amount')."</span>"!!} 
                                        @endif
                                    </div>
                                </div>
                            </div>
                    

                           

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('address','Address ')) !!}
                                        {!! Form::textarea('address', null, array('placeholder' => 'Address','rows'=>5, 'class' => 'form-control')) !!}
                                        @if ($errors->has('address'))  
                                            {!! "<span class='span_danger'>". $errors->first('address')."</span>"!!} 
                                        @endif
                                    </div>
                                </div>
                            </div>

                            
                        </div>

                        

                        <div class="card-footer">
                            <div class="row">
                                
                                <div class="col-lg-12 text-right">
                                    <button type="submit" class="btn btn-primary mr-2">Save</button>
                                    <button type="reset" class="btn btn-danger">Cancel</button>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                    <!--end::Form-->
                </div>
            </div>
        </div>
    </div>
</div>
  

@endsection
