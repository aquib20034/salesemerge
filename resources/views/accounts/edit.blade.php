@extends('layouts.main')
@section('title','Account')
@section('content')
<style>
    /* .cls_form{
        display:none;
    } */
</style>
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
                            <a  href="{{ route('accounts.index') }}" class="btn btn-primary btn-xs ml-auto">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    <!--begin::Form-->
                        {!! Form::model($data, ['method' => 'PATCH','id'=>'form','enctype'=>'multipart/form-data','route' => ['accounts.update', $data->id]]) !!}

                            {{  Form::hidden('updated_by', Auth::user()->id ) }}
                            {{  Form::hidden('company_id', Auth::user()->company_id ) }}
                            {{  Form::hidden('branch_id', Auth::user()->branch_id ) }}
                            {{  Form::hidden('action', "update" ) }}

                            <div class="card-body">
                                <div class="row">

                                    <!-- Head of Account SelectBox -->
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 cls_head_div">
                                        <div class="form-group">
                                            {!! Html::decode(Form::label('account_type_id','Head of accounts / Account type<span class="text-danger">*</span>')) !!}
                                            {!! Form::select('account_type_id', ['0'=>'--select--']+$account_types,$data->account_type_id, array('class' => 'form-control cls_head')) !!}
                                            @if ($errors->has('account_type_id'))  
                                                {!! "<span class='span_danger'>". $errors->first('account_type_id')."</span>"!!} 
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Group Head SelectBox -->
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 cls_group_div">
                                        <div class="form-group">
                                            {!! Html::decode(Form::label('group_head_id','Group head<span class="text-danger">*</span>')) !!}
                                            {!! Form::select('group_head_id', ['0'=>"--select--"]+$group_heads,$data->group_head_id, array('class' => 'form-control cls_group')) !!}
                                            @if ($errors->has('group_head_id'))  
                                                {!! "<span class='span_danger'>". $errors->first('group_head_id')."</span>"!!} 
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Child Head SelectBox -->
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 cls_child_div">
                                        <div class="form-group">
                                            {!! Html::decode(Form::label('child_head_id','Child head<span class="text-danger">*</span>')) !!}
                                            {!! Form::select('child_head_id', ['0'=>"--select--"]+$child_heads,$data->child_head_id, array('class' => 'form-control cls_child')) !!}
                                            @if ($errors->has('child_head_id'))  
                                                {!! "<span class='span_danger'>". $errors->first('child_head_id')."</span>"!!} 
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            {!! Html::decode(Form::label('active','Active<span class="text-danger">*</span>')) !!}<br>
                                            <span class="switch switch-sm switch-icon switch-success">
                                            <?php $actv = (isset($data->active) && ($data->active == "Active") || ($data->active == 1)) ? 1 : 0; ?>
                                                <label>
                                                    {!! Form::checkbox('active',1,$actv,  array('class' => 'form-control', 'data-toggle'=>'toggle', 'data-onstyle'=>'success', 'data-style' => 'btn-round')) !!}
                                                    <span></span>
                                                </label>
                                            </span>
                                        
                                            @if ($errors->has('active'))  
                                                {!! "<span class='span_danger'>". $errors->first('active')."</span>"!!} 
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Form to create accounts -->
                                <div class="cls_form">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                {!! Html::decode(Form::label('name','Account name <span class="text-danger">*</span>')) !!}
                                                {{ Form::text('name', null, array('id'=>'name','placeholder' => 'Enter account name','class' => 'form-control','autofocus' => ''  )) }}
                                                @if ($errors->has('name'))  
                                                    {!! "<span class='span_danger'>". $errors->first('name')."</span>"!!} 
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                {!! Html::decode(Form::label('transaction_type','Transaction type <span class="text-danger">*</span>')) !!}
                                                {!! Form::select('transaction_type', $transaction_types,$ledger->transaction_type, array('class' => 'form-control')) !!}
                                                @if ($errors->has('transaction_type'))  
                                                    {!! "<span class='span_danger'>". $errors->first('transaction_type')."</span>"!!} 
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                {!! Html::decode(Form::label('amount','Amount')) !!}
                                                {!! Form::number('amount', $ledger->amount, array('placeholder' => 'Enter amount','class' => 'form-control')) !!}
                                                @if ($errors->has('amount'))  
                                                    {!! "<span class='span_danger'>". $errors->first('amount')."</span>"!!} 
                                                @endif
                                            </div>
                                        </div> 

                                        <!-- <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                {!! Html::decode(Form::label('contact_no','Contact No')) !!}
                                                {!! Form::number('contact_no', null, array('placeholder' => 'Enter contact no','class' => 'form-control')) !!}
                                                @if ($errors->has('contact_no'))  
                                                    {!! "<span class='span_danger'>". $errors->first('contact_no')."</span>"!!} 
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                {!! Html::decode(Form::label('city_id','City ')) !!}
                                                {!! Form::select('city_id', $cities,null, array('class' => 'form-control')) !!}
                                                @if ($errors->has('city_id'))  
                                                    {!! "<span class='span_danger'>". $errors->first('city_id')."</span>"!!} 
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                {!! Html::decode(Form::label('previous_amount','Previous Amount')) !!}
                                                {!! Form::number('previous_amount', 0, array('placeholder' => 'Enter previous amount','class' => 'form-control')) !!}
                                                @if ($errors->has('previous_amount'))  
                                                    {!! "<span class='span_danger'>". $errors->first('previous_amount')."</span>"!!} 
                                                @endif
                                            </div>
                                        </div> -->
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
    {!! JsValidator::formRequest('App\Http\Requests\AccountRequest', '#form'); !!}


    <script type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function handle_error(data){
                $("#spinner-div").hide();
                var txt   = '';
                for (var key in data.responseJSON.errors) {
                    txt += data.responseJSON.errors[key];
                    txt +='<br>';
                }
                toastr.error(txt);
            }

            function ajax_call(parent_id, flag){

                $.ajax({
                    url: "{{ url('get_children') }}/"+parent_id+"/"+flag,
                    type: 'GET',
                    // dataType: 'json',
                    beforeSend:function(){
                        $("#spinner-div").show();
                    },
                    success: function(data) {
                        var where_to_append = ".cls_"+flag+"_div";
                        $(where_to_append).html(data.data);
                        $("#spinner-div").hide();
                    },
                    error: function(data) {
                        handle_error(data);
                    }
                });
            }

            $(document).on('change','.cls_head', function(){
                ajax_call(($(this).val()), 'group')
                $('.cls_child_div').html("");
                $('.cls_form').hide();
            })


            $(document).on('change','.cls_group', function(){
                ajax_call(($(this).val()), 'child')
                $('.cls_form').hide();

            })

            $(document).on('change','.cls_child', function(){
                $('.cls_form').show();
                $('#name').focus();

                
            })

        });

	</script>


@endsection
