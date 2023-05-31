@extends('layouts.main')
@section('title','Items')
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
                        <a  href="{{ route('items.index') }}" class="btn btn-primary btn-round ml-auto">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        
                    </div>
                </div>

                    <!--begin::Form-->
                    {!! Form::model($data, ['method' => 'PATCH','id'=>'form','enctype'=>'multipart/form-data','route' => ['items.update', $data->id]]) !!}
                        {{  Form::hidden('created_by', Auth::user()->id ) }}

                        <div class="card-body">
                            <div class=" row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('name','Item Name <span class="text-danger">*</span>')) !!}
                                        {{ Form::text('name', null, array('placeholder' => 'Enter Item name','class' => 'form-control','autofocus' => ''  )) }}
                                        @if ($errors->has('name'))  
                                            {!! "<span class='span_danger'>". $errors->first('name')."</span>"!!} 
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('company_id','Company Name ')) !!}
                                        {!! Form::select('company_id', $companies,null, array('class' => 'form-control')) !!}
                                        @if ($errors->has('company_id'))  
                                            {!! "<span class='span_danger'>". $errors->first('company_id')."</span>"!!} 
                                        @endif
                                        
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('unit_id','Unit ')) !!}
                                        {!! Form::select('unit_id', $units,null, array('class' => 'form-control')) !!}
                                        @if ($errors->has('unit_id'))  
                                            {!! "<span class='span_danger'>". $errors->first('unit_id')."</span>"!!} 
                                        @endif
                                    </div>
                                </div>
                            </div>
                    

                            <div class="row">
                               
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('tot_piece','Total Piece <span class="text-danger">*</span>')) !!}
                                        {!! Form::number('tot_piece', null, array('placeholder' => 'Enter total piece','class' => 'form-control')) !!}
                                        @if ($errors->has('tot_piece'))  
                                            {!! "<span class='span_danger'>". $errors->first('tot_piece')."</span>"!!} 
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('free_piece','Free Piece')) !!}
                                        {!! Form::number('free_piece', null, array('placeholder' => 'Enter free piece','class' => 'form-control')) !!}
                                        @if ($errors->has('free_piece'))  
                                            {!! "<span class='span_danger'>". $errors->first('free_piece')."</span>"!!} 
                                        @endif
                                        
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('purchase_price','Purchase Price')) !!}
                                        {!! Form::number('purchase_price', null, array('placeholder' => 'Enter purchase price','class' => 'form-control')) !!}
                                        @if ($errors->has('purchase_price'))  
                                            {!! "<span class='span_danger'>". $errors->first('purchase_price')."</span>"!!} 
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('sell_price','Selling Price')) !!}
                                        {!! Form::number('sell_price', null, array('placeholder' => 'Enter sell price','class' => 'form-control')) !!}
                                        @if ($errors->has('sell_price'))  
                                            {!! "<span class='span_danger'>". $errors->first('sell_price')."</span>"!!} 
                                        @endif
                                        
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('company_percentage','Company Percentage')) !!}
                                        {!! Form::number('company_percentage', null, array('placeholder' => 'Enter company percentage','class' => 'form-control')) !!}
                                        @if ($errors->has('company_percentage'))  
                                            {!! "<span class='span_danger'>". $errors->first('company_percentage')."</span>"!!} 
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('to_percentage','T.O Scheme by cartoon')) !!}
                                        {!! Form::number('to_percentage', null, array('placeholder' => 'Enter T.O scheme by cartoon','class' => 'form-control')) !!}
                                        @if ($errors->has('to_percentage'))  
                                            {!! "<span class='span_danger'>". $errors->first('to_percentage')."</span>"!!} 
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
