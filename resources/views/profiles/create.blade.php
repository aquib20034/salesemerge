@extends('layouts.main')
@section('title','Profile')
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
                            <a  href="{{ route('profiles.index') }}" class="btn btn-primary btn-xs ml-auto">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            
                        </div>
                    </div>
                    <!--begin::Form-->
                        {!! Form::open(array('route' => 'profiles.store','method'=>'POST','id'=>'form','enctype'=>'multipart/form-data')) !!}
                            {{  Form::hidden('created_by', Auth::user()->id ) }}
                            {{  Form::hidden('company_id', Auth::user()->company_id ) }}
                            {{  Form::hidden('branch_id', Auth::user()->branch_id ) }}
                            {{  Form::hidden('action', "store" ) }}

                            <div class="card-body">
                                <div class="row">

                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            {!! Html::decode(Form::label('old_password','Old password <span class="text-danger">*</span>')) !!}
                                            {!! Form::password('old_password', array('placeholder' => 'Enter old password','class' => 'form-control')) !!}
                                            @if ($errors->has('old_password'))  
                                                {!! "<span class='span_danger'>". $errors->first('old_password')."</span>"!!} 
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            {!! Html::decode(Form::label('password','New password <span class="text-danger">*</span>')) !!}
                                            {!! Form::password('password', array('placeholder' => 'Enter new password','class' => 'form-control')) !!}
                                            @if ($errors->has('password'))  
                                                {!! "<span class='span_danger'>". $errors->first('password')."</span>"!!} 
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            {!! Html::decode(Form::label('password_confirmation','Re-type password <span class="text-danger">*</span>')) !!}
                                            {!! Form::password('password_confirmation', array('placeholder' => 'Re-type new password','class' => 'form-control')) !!}
                                            @error('password-confirm')
                                                {!! "<span class='span_danger'>". $errors->first('password-confirm')."</span>"!!} 
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-12 text-right">
                                        <button type="submit" class="btn btn-primary btn-xs mr-2">Save</button>
                                        <button type="reset" class="btn btn-danger btn-xs">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    <!--end::Form-->
                </div>
            </div>
        </div>
    </div>
    {!! JsValidator::formRequest('App\Http\Requests\ProfileRequest', '#form'); !!}
@endsection
