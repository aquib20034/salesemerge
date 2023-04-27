@extends('layouts.main')
@section('title','General Vouchers')
@section('content')

@include( '../sweet_script')


<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">@yield('title')</h4>
    </div>


   

    <!-- Single Entities reporting  -->
    <div class="row">
        <!-- Single Company reporting  -->
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Find One Company @yield('title')</h4>
                    </div>
                </div>
                <!--begin::Form-->
                {!! Form::open(array('route' => 'vouchers.store','method'=>'POST','id'=>'form','enctype'=>'multipart/form-data')) !!}
                    {{  Form::hidden('entity', 'company' ) }}

                    <div class="card-body">
                        <div class=" row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('company_id','Company Name ')) !!}
                                    {!! Form::select('company_id', $companies,null, array('class' => 'form-control')) !!}
                                    @if ($errors->has('company_id'))  
                                        {!! "<span class='span_danger'>". $errors->first('company_id')."</span>"!!} 
                                    @endif
                                    
                                </div>
                            </div>
                        </div>
                        <div class=" row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('company_payment_details','Payment Details')) !!}
                                    {!! Form::text('company_payment_details', null, array('placeholder' => 'Enter payment details ','class' => 'form-control')) !!}
                                    @if ($errors->has('company_payment_details'))  
                                        {!! "<span class='span_danger'>". $errors->first('company_payment_details')."</span>"!!} 
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class=" row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                {!! Html::decode(Form::label('company_amount','Amount')) !!}
                                {!! Form::number('company_amount', null, array('placeholder' => 'Enter Amount','class' => 'form-control','required'=>'true')) !!}
                                @if ($errors->has('company_amount'))  
                                    {!! "<span class='span_danger'>". $errors->first('company_amount')."</span>"!!} 
                                @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="submit" class="btn btn-primary mr-2">Add Payment</button>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}
                <!--end::Form-->
            </div>
        </div>

        <!-- Single Customer reporting  -->
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Customer @yield('title')</h4>
                    </div>
                </div>
                <!--begin::Form-->
                {!! Form::open(array('route' => 'vouchers.store','method'=>'POST','id'=>'form','enctype'=>'multipart/form-data')) !!}
                    {{  Form::hidden('entity', 'customers' ) }}

                    <div class="card-body">
                        <div class=" row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('customer_id','Customer Name ')) !!}
                                    {!! Form::select('customer_id', $customers,null, array('class' => 'form-control')) !!}
                                    @if ($errors->has('customer_id'))  
                                        {!! "<span class='span_danger'>". $errors->first('customer_id')."</span>"!!} 
                                    @endif
                                    
                                </div>
                            </div>
                        </div>
                        <div class=" row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('customer_payment_details','Payment Details')) !!}
                                    {!! Form::text('customer_payment_details', null, array('placeholder' => 'Enter payment details ','class' => 'form-control')) !!}
                                    @if ($errors->has('customer_payment_details'))  
                                        {!! "<span class='span_danger'>". $errors->first('customer_payment_details')."</span>"!!} 
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class=" row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                {!! Html::decode(Form::label('customer_amount','Amount')) !!}
                                {!! Form::number('customer_amount', null, array('placeholder' => 'Enter Amount','class' => 'form-control','required'=>'true')) !!}
                                @if ($errors->has('customer_amount'))  
                                    {!! "<span class='span_danger'>". $errors->first('customer_amount')."</span>"!!} 
                                @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="submit" class="btn btn-primary mr-2">Add Payment</button>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}
                <!--end::Form-->
            </div>
        </div>
    </div>


   
    

    
</div>
  

@endsection
