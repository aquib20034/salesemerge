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
                            <h4 class="card-title">Update @yield('title')</h4>
                        </div>
                    </div>

                    <!--begin::Form-->
                        {!! Form::model($data, ['method' => 'PATCH','id'=>'form','enctype'=>'multipart/form-data','route' => ['profiles.update', $data->id]]) !!}
                            {{  Form::hidden('updated_by', Auth::user()->id ) }}
                            {{  Form::hidden('action', "update" ) }}
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

                                        <div class="col-lg-2" >
                                        {!! Html::decode(Form::label('profile_pic','Profile picture')) !!}

                                            <div class="avatar avatar-xl add_image" id="kt_profile_avatar">
                                                <img id="blah" src="{{ $data->profile_pic }}" class="avatar-img rounded-circle" alt="your image"/>
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
                                        <button type="submit" class="btn btn-primary btn-xs mr-2">Save</button>
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

    <script>
        $(document).ready(function () {  
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

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
