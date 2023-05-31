@extends('layouts.main')
@section('title','Item')
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
                            <a  href="{{ route('items.index') }}" class="btn btn-primary btn-xs ml-auto">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            
                        </div>
                    </div>
                    <!--begin::Form-->
                        {!! Form::open(array('route' => 'items.store','method'=>'POST','id'=>'form','enctype'=>'multipart/form-data')) !!}
                            {{  Form::hidden('created_by', Auth::user()->id ) }}
                            {{  Form::hidden('company_id', Auth::user()->company_id ) }}
                            {{  Form::hidden('branch_id', Auth::user()->branch_id ) }}
                            {{  Form::hidden('action', "store" ) }}

                            <div class="card-body">
                            <div class=" row">
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('name','Item name <span class="text-danger">*</span>')) !!}
                                        {{ Form::text('name', null, array('placeholder' => 'Enter item name','class' => 'form-control','autofocus' => '','required'=>'true'  )) }}
                                        @if ($errors->has('name'))  
                                            {!! "<span class='span_danger'>". $errors->first('name')."</span>"!!} 
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('manufacturer_id','Manufacturer / Company <span class="text-danger">*</span>')) !!}
                                        {!! Form::select('manufacturer_id', $manufacturers,[], array('class' => 'form-control')) !!}
                                        @if ($errors->has('manufacturer_id'))  
                                            {!! "<span class='span_danger'>". $errors->first('manufacturer_id')."</span>"!!} 
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('category_id','Category <span class="text-danger">*</span>')) !!}
                                        {!! Form::select('category_id', $categories,[], array('class' => 'form-control')) !!}
                                        @if ($errors->has('category_id'))  
                                            {!! "<span class='span_danger'>". $errors->first('category_id')."</span>"!!} 
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('group_id','Group <span class="text-danger">*</span>')) !!}
                                        {!! Form::select('group_id', $groups,[], array('class' => 'form-control')) !!}
                                        @if ($errors->has('group_id'))  
                                            {!! "<span class='span_danger'>". $errors->first('group_id')."</span>"!!} 
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('tot_piece','Total Piece <span class="text-danger">*</span>')) !!}
                                        {!! Form::number('tot_piece', 0, array('placeholder' => 'Enter total piece','class' => 'form-control')) !!}
                                        @if ($errors->has('tot_piece'))  
                                            {!! "<span class='span_danger'>". $errors->first('tot_piece')."</span>"!!} 
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('free_piece','Free Piece')) !!}
                                        {!! Form::number('free_piece', 0, array('placeholder' => 'Enter free piece','class' => 'form-control')) !!}
                                        @if ($errors->has('free_piece'))  
                                            {!! "<span class='span_danger'>". $errors->first('free_piece')."</span>"!!} 
                                        @endif
                                    </div>
                                </div>
                   
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('purchase_price','Purchase Price <span class="text-danger">*</span>')) !!}
                                        {!! Form::number('purchase_price', 0, array('placeholder' => 'Enter purchase price','class' => 'form-control')) !!}
                                        @if ($errors->has('purchase_price'))  
                                            {!! "<span class='span_danger'>". $errors->first('purchase_price')."</span>"!!} 
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('sell_price','Sell Price <span class="text-danger">*</span>')) !!}
                                        {!! Form::number('sell_price', 0, array('placeholder' => 'Enter sell price','class' => 'form-control')) !!}
                                        @if ($errors->has('sell_price'))  
                                            {!! "<span class='span_danger'>". $errors->first('sell_price')."</span>"!!} 
                                        @endif
                                        
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('unit_id','Unit')) !!}
                                        {!! Form::select('unit_id', $units,null, array('class' => 'form-control')) !!}
                                        @if ($errors->has('unit_id'))  
                                            {!! "<span class='span_danger'>". $errors->first('unit_id')."</span>"!!} 
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('company_percentage','Company Percentage')) !!}
                                        {!! Form::number('company_percentage', 0, array('placeholder' => 'Enter company percentage','class' => 'form-control')) !!}
                                        @if ($errors->has('company_percentage'))  
                                            {!! "<span class='span_danger'>". $errors->first('company_percentage')."</span>"!!} 
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('to_percentage','T.O Scheme by cartoon')) !!}
                                        {!! Form::number('to_percentage', 0, array('placeholder' => 'Enter T.O scheme by cartoon','class' => 'form-control')) !!}
                                        @if ($errors->has('to_percentage'))  
                                            {!! "<span class='span_danger'>". $errors->first('to_percentage')."</span>"!!} 
                                        @endif
                                        
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
    {!! JsValidator::formRequest('App\Http\Requests\ItemRequest', '#form'); !!}

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
