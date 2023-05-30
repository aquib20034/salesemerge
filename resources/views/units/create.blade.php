@extends('layouts.main')
@section('title','Unit')
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
                            <a  href="{{ route('units.index') }}" class="btn btn-primary btn-xs ml-auto">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            
                        </div>
                    </div>
                    <!--begin::Form-->
                        {!! Form::open(array('route' => 'units.store','method'=>'POST','id'=>'form','enctype'=>'multipart/form-data')) !!}
                            {{  Form::hidden('created_by', Auth::user()->id ) }}
                            {{  Form::hidden('action', "store" ) }}

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            {!! Html::decode(Form::label('name','Unit Name <span class="text-danger">*</span>')) !!}
                                            {{ Form::text('name', null, array('placeholder' => 'Enter unit name','class' => 'form-control','autofocus' => ''  )) }}
                                            @if ($errors->has('name'))  
                                                {!! "<span class='span_danger'>". $errors->first('name')."</span>"!!} 
                                            @endif
                                        </div>
                                    </div>
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
    {!! JsValidator::formRequest('App\Http\Requests\UnitRequest', '#form'); !!}

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
