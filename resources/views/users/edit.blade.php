@extends('layouts.main')
@section('title','Users')
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
                        <a  href="{{ route('users.index') }}" class="btn btn-primary btn-round ml-auto">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        
                    </div>
                </div>


                    <!--begin::Form-->
                 {!! Form::model($user, ['method' => 'PATCH','id'=>'form','enctype'=>'multipart/form-data','route' => ['users.update', $user->id]]) !!}
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-12">
                              {!! Html::decode(Form::label('name','Full Name <span class="text-danger">*</span>')) !!}
                               {{ Form::text('name', null, array('placeholder' => 'Enter full name','class' => 'form-control','autofocus' => ''  )) }}
                                @if ($errors->has('name'))  
                                    {!! "<span class='span_danger'>". $errors->first('name')."</span>"!!} 
                                @endif

                            </div>
                        </div>
                  

                     <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                {!! Html::decode(Form::label('email','Email <span class="text-danger">*</span>')) !!}
                                {!! Form::text('email', null, array('placeholder' => 'Enter valid mail','class' => 'form-control')) !!}
                                @if ($errors->has('email'))  
                                    {!! "<span class='span_danger'>". $errors->first('email')."</span>"!!} 
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                {!! Html::decode(Form::label('password','Password ')) !!}
                                {!! Form::password('password', array('placeholder' => 'Enter password','class' => 'form-control')) !!}
                                @if ($errors->has('password'))  
                                    {!! "<span class='span_danger'>". $errors->first('email')."</span>"!!} 
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                {!! Html::decode(Form::label('roles[]','Roles <span class="text-danger">*</span>')) !!}
                                {!! Form::select('roles[]', $roles,$userRole, array('class' => 'form-control')) !!}
                                @if ($errors->has('roles'))  
                                    {!! "<span class='span_danger'>". $errors->first('roles')."</span>"!!} 
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                {!! Html::decode(Form::label('contact_no','Contact No ')) !!}
                                {!! Form::text('contact_no', null, array('placeholder' => 'Enter contact no','class' => 'form-control')) !!}
                                @if ($errors->has('contact_no'))  
                                    {!! "<span class='span_danger'>". $errors->first('contact_no')."</span>"!!} 
                                @endif
                            </div>
                        </div>
                    </div>

                   <div class="form-group row">
                        <div class="col-lg-10">
                            {!! Html::decode(Form::label('description','Description ')) !!}
                            {!! Form::textarea('description', null, array('placeholder' => 'Item description','rows'=>5, 'class' => 'form-control')) !!}
                            @if ($errors->has('description'))  
                                {!! "<span class='span_danger'>". $errors->first('description')."</span>"!!} 
                            @endif
                        </div>
                    
                        <div class="col-lg-2" style="margin-top: 20px">
                        <!-- <label >Avatar</label> -->
                            <div class="avatar avatar-xl" id="kt_profile_avatar">
                                 @if($user->image)
                                        <img id="blah" src="{{ asset('/uploads/users/'.$user->image) }}" class="avatar-img rounded-circle" alt="your image"/>
                                    @else
                                        <img id="blah" src="{{ asset('/uploads/users/no_image.png') }}" class="avatar-img rounded-circle" alt="your image"/>
                                    @endif
                                      @if ($errors->has('image'))  
                                            {!! "<span class='span_danger'>". $errors->first('image')."</span>"!!} 
                                        @endif
                                

                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change Image">

                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                    <!-- <input type="file" name="image" accept=".png, .jpg, .jpeg" /> -->
                                   

                                    {!! Form::file('image', array('id'=>'exampleInputFile','accept'=>'.png, .jpg, .jpeg')) !!}

                                    <!-- <input type="hidden" name="profile_avatar_remove" /> -->
                                </label>
                            </div>

                            <!-- <label class="custom-file-label" for="exampleInputFile">Choose file</label> -->
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
  
<script>
        function readURL(input) {
          if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
              $('#blah').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]); // convert to base64 string
          }
        }

        $("#exampleInputFile").change(function() {
          readURL(this);
        });
    </script>


@endsection
