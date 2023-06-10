@extends('layouts.main')
@section('title','Branch')
@section('content')
@include( '../sweet_script')

<style>
        #loaderDiv{
            width:100%;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            background: rgba(0,0,0,0.2);
            z-index:9999;
            display:none;
        }
    </style>
<div class="page-inner">
    <div id= "loaderDiv">
        <i class="fas fa-spinner fa-spin" style="position:absolute; left:50%; top:50%;font-size:80px; color:#3a7ae0">
        </i>
    </div>
    <div class="page-header">
        <h4 class="page-title">@yield('title')</h4>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">@yield('title')</h4>
                        <a  href="{{ route('companies.index') }}" class="btn btn-primary btn-xs ml-auto">
                            <i class="fas fa-arrow-left"></i>
                        </a>

                    </div>
                </div>

                <!--begin::Form-->
                    {!! Form::model($data, ['method' => 'PATCH','id'=>'form','enctype'=>'multipart/form-data','route' => ['branches.update', $data->id]]) !!}
                        {{  Form::hidden('update_by', Auth::user()->id ) }}
                        <div class="card-body">
                            <div class=" row">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('name','Branch name <span class="text-danger">*</span>')) !!}
                                        {{ Form::text('name', null, array('placeholder' => 'Enter full branch name','class' => 'form-control', 'required'=>'true')) }}
                                        @if ($errors->has('name'))
                                            {!! "<span class='span_danger'>". $errors->first('name')."</span>"!!}
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('mobile_no','Mobile#')) !!}
                                        {!! Form::text('mobile_no', null, array('placeholder' => 'Enter Mobile#','class' => 'form-control')) !!}
                                        @if ($errors->has('mobile_no'))
                                            {!! "<span class='span_danger'>". $errors->first('mobile_no')."</span>"!!}
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('phone_no','Phone#')) !!}
                                        {!! Form::text('phone_no', null, array('placeholder' => 'Enter Phone#','class' => 'form-control')) !!}
                                        @if ($errors->has('phone_no'))
                                            {!! "<span class='span_danger'>". $errors->first('phone_no')."</span>"!!}
                                        @endif
                                    </div>
                                </div>

                            </div>

                            <div class=" row">

                            </div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('address','Address ')) !!}
                                        {!! Form::textarea('address', null, array('placeholder' => 'Address','rows'=>1, 'class' => 'form-control')) !!}
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

</div>


@endsection
