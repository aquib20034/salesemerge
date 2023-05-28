@extends('layouts.main')
@section('title','User')
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
                            <a  href="{{ route('users.index') }}" class="btn btn-primary btn-xs ml-auto">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            
                        </div>
                    </div>
                    <!--begin::Form-->
                        {!! Form::open(array('route' => 'users.store','method'=>'POST','id'=>'form','enctype'=>'multipart/form-data')) !!}
                            {{  Form::hidden('created_by', Auth::user()->id ) }}
                            {{  Form::hidden('action', "store" ) }}

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            {!! Html::decode(Form::label('name','Full Name <span class="text-danger">*</span>')) !!}
                                            {{ Form::text('name', null, array('placeholder' => 'Enter full name','class' => 'form-control','autofocus' => ''  )) }}
                                            @if ($errors->has('name'))  
                                                {!! "<span class='span_danger'>". $errors->first('name')."</span>"!!} 
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            {!! Html::decode(Form::label('email','Email <span class="text-danger">*</span>')) !!}
                                            {!! Form::text('email', null, array('placeholder' => 'Enter valid mail','class' => 'form-control')) !!}
                                            @if ($errors->has('email'))  
                                                {!! "<span class='span_danger'>". $errors->first('email')."</span>"!!} 
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            {!! Html::decode(Form::label('password','Password <span class="text-danger">*</span>')) !!}
                                            {!! Form::password('password', array('placeholder' => 'Enter password','class' => 'form-control')) !!}
                                            @if ($errors->has('password'))  
                                                {!! "<span class='span_danger'>". $errors->first('email')."</span>"!!} 
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            {!! Html::decode(Form::label('roles[]','Roles <span class="text-danger">*</span>')) !!}
                                            {!! Form::select('roles[]', $roles,[], array('class' => 'form-control')) !!}
                                            @if ($errors->has('roles'))  
                                                {!! "<span class='span_danger'>". $errors->first('roles')."</span>"!!} 
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            {!! Html::decode(Form::label('company_id','Company <span class="text-danger">*</span>')) !!}
                                            {!! Form::select('company_id', $companies,[], array('class' => 'form-control')) !!}
                                            @if ($errors->has('company_id'))  
                                                {!! "<span class='span_danger'>". $errors->first('company_id')."</span>"!!} 
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            {!! Html::decode(Form::label('branch_id','Branch <span class="text-danger">*</span>')) !!}
                                            {!! Form::select('branch_id', $branches,[], array('class' => 'form-control')) !!}
                                            @if ($errors->has('branch_id'))  
                                                {!! "<span class='span_danger'>". $errors->first('branch_id')."</span>"!!} 
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            {!! Html::decode(Form::label('phone_no','Phone#')) !!}
                                            {!! Form::text('phone_no', null, array('placeholder' => 'Enter phone#','class' => 'form-control')) !!}
                                            @if ($errors->has('phone_no'))  
                                                {!! "<span class='span_danger'>". $errors->first('phone_no')."</span>"!!} 
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            {!! Html::decode(Form::label('mobile_no','Mobile#')) !!}
                                            {!! Form::text('mobile_no', null, array('placeholder' => 'Enter mobile','class' => 'form-control')) !!}
                                            @if ($errors->has('mobile_no'))  
                                                {!! "<span class='span_danger'>". $errors->first('mobile_no')."</span>"!!} 
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                                        {!! Html::decode(Form::label('description','Description ')) !!}
                                        {!! Form::textarea('description', null, array('placeholder' => 'Description','rows'=>5, 'class' => 'form-control')) !!}
                                        @if ($errors->has('description'))  
                                            {!! "<span class='span_danger'>". $errors->first('description')."</span>"!!} 
                                        @endif
                                    </div>
           
                                    <div class="col-lg-2" style="margin-top: 20px">
                                        <div class="avatar avatar-xl add_image" id="kt_profile_avatar">
                                            <img id="blah" src="{{ asset('/uploads/no_image.png') }}" class="avatar-img rounded-circle "  alt="your image"/>
                                            <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change Image">
                                                <i class="fa fa-pen icon-sm text-muted"></i>
                                                {!! Form::file('profile_pic', array('id'=>'profile_pic','accept'=>'.png, .jpg, .jpeg')) !!}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-12 text-right">
                                        <button type="submit" class="btn btn-primary btn-sm mr-2">Save</button>
                                        <button type="reset" class="btn btn-danger btn-sm">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    <!--end::Form-->
                </div>
            </div>
        </div>
    </div>
    {!! JsValidator::formRequest('App\Http\Requests\UserRequest', '#form'); !!}

    <script>
        $(document).ready(function () {  


            // getting and viewing profile_pic
            $("#profile_pic").change(function() {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    
                    reader.onload = function(e) {
                        $('#blah').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]); // convert to base64 string
                }
            });

        });
    </script>
    

@endsection
