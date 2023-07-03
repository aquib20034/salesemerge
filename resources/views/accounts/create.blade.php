@extends('layouts.main')
@section('title','Account')
@section('content')
<style>
    .cls_form{
        display:none;
    }
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
                            <h4 class="card-title">Add @yield('title')</h4>
                            <a  href="{{ route('accounts.index') }}" class="btn btn-primary btn-xs ml-auto">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    <!--begin::Form-->
                        {!! Form::open(array('route' => 'accounts.store','method'=>'POST','id'=>'form','enctype'=>'multipart/form-data')) !!}

                            {{  Form::hidden('created_by', Auth::user()->id ) }}
                            {{  Form::hidden('company_id', Auth::user()->company_id ) }}
                            {{  Form::hidden('action', "store" ) }}

                            <div class="card-body">
                                <div class="row">

                                    <!-- Head of Account SelectBox -->
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 cls_head_div">
                                        <div class="form-group">
                                            {!! Html::decode(Form::label('account_type_id','Head of accounts / Account type<span class="text-danger">*</span>')) !!}
                                            {!! Form::select('account_type_id', ['0'=>'--select--']+$account_types,[], array('class' => 'form-control cls_head')) !!}
                                            @if ($errors->has('account_type_id'))  
                                                {!! "<span class='span_danger'>". $errors->first('account_type_id')."</span>"!!} 
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Group Head SelectBox -->
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 cls_group_div"></div>

                                    <!-- Child Head SelectBox -->
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 cls_child_div"></div>

                                    
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
                                                {!! Html::decode(Form::label('branch_id','Branch <span class="text-danger">*</span>')) !!}
                                                {!! Form::select('branch_id', $branches,[], array('class' => 'form-control')) !!}
                                                @if ($errors->has('branch_id'))  
                                                    {!! "<span class='span_danger'>". $errors->first('branch_id')."</span>"!!} 
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                {!! Html::decode(Form::label('city_id','City ')) !!}
                                                {!! Form::select('city_id', $cities,null, array('class' => 'form-control','id'=>'city_id')) !!}
                                                @if ($errors->has('city_id'))  
                                                    {!! "<span class='span_danger'>". $errors->first('city_id')."</span>"!!} 
                                                @endif
                                            </div>
                                        </div>

                                        
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                {!! Html::decode(Form::label('account_limit','Account Limit')) !!}
                                                {!! Form::number('account_limit', null, array('placeholder' => 'Enter account limit','class' => 'form-control')) !!}
                                                @if ($errors->has('account_limit'))  
                                                    {!! "<span class='span_danger'>". $errors->first('account_limit')."</span>"!!} 
                                                @endif
                                            </div>
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
