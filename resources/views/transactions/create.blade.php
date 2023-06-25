@extends('layouts.main')
@section('title','Transactions')
@section('content')
<style>
    .cls_form{
        display:none;
    }
    .cls_label{
        font-weight:900;
        text-align: center;
        font-size:22px;
    }

    .col_head{
        text-align: center;
    }

    .select2-container--default .select2-selection--single {
        /* background-color: #fff; */
        border: 0 solid #aaa !important;
        /* border-radius: 4px; */
    }
    .select2{
    display: block;
    width: 100% !important;
    /* padding: 0.375rem 0.75rem !important; */
    padding: 0.2rem 0.8rem !important;
    font-size: 13px;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ebedf2 !important;
    border-radius: 0.25rem;
    transition: border-color 0.15s ease-in-out,box-shadow 0.15s ease-in-out;
    }
</style>
    @include( '../sweet_script')
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <!-- <div class="d-flex align-items-center"> -->
                             <!-- <h4 class="page-title">@yield('title') vouchers</h4> -->
                            <!-- <div  class="ml-auto"> -->
                                
                        <div class="row">
                            <div class="d-flex align-items-center">
                                <div class="col col_head">
                                    <h4 class="page-title">@yield('title') vouchers</h4>
                                </div>
                                
                            </div>  

                            <div class="col col_head">
                                {!! Html::decode(Form::label('transaction_id','Transaction ID')) !!} </br>
                                <span class="cls_label class_transaction_id">{{hp_next_transaction_id()}}</span>
                            </div>
                                
                            <div class="col col_head">
                                {!! Html::decode(Form::label('transaction_date','Transaction date')) !!}</br>
                                <span class="cls_label cls_date">{{hp_today()}}</span>
                            </div> 
                            
                            <div class="col col_head">
                                <span class="">Select transaction voucher</span>
                                {!! Form::select('trnx_type_id', [0=>"---select---"]+hp_transaction_types(TRUE),null, array('class' => 'form-control cls_transaction_type','id'=>'trnx_type_id')) !!}
                            </div>  
                        </div>  
                    </div>
                </div>
            </div>
        </div>

        <div class="cls_form form_2">
            <x-vouchers.cash_receiving/>
        </div>

        <div class="cls_form form_3">
            <x-vouchers.cash_payment/>
        </div>
       
        <div class="cls_form form_4">
            <x-vouchers.bank_deposit/>
        </div>

        <div class="cls_form form_5">
            <x-vouchers.bank_payment/>
        </div>

        <div class="cls_form form_6">
            <x-vouchers.journal/>
        </div>


    </div>


    <script type="text/javascript">
        $(document).ready(function () {
             $('.select2').select2();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $(document).on('change','.cls_transaction_type', function(){
                $(".cls_cih_balance").html($("#cih_balance").val());

                $(".cls_selected_account_balance").html(0);

                $('.cls_form').hide();
                var id = ($(this).val());
                $('.form_'+id).show();
                $('.select2').focus();
                $('.cls_transaction_type').val(id);
            })
        });
	</script>


@endsection
