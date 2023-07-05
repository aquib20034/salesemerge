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
        font-size:14px;
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
        font-size: 11px;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ebedf2 !important;
        border-radius: 0.25rem;
        transition: border-color 0.15s ease-in-out,box-shadow 0.15s ease-in-out;
    }
    .cls_heading_3, .cls_table_heading_3{
        font-weight:900;
        /* text-align: center; */
        font-size:15px;
    }

    .card{
        margin-bottom: 5px !important;
    }

    .card-body {
        padding: 0.2rem 1.25rem !important;
    }

    .cls_transaction_date{
        padding: 0.4rem !important;

    }
    .row{
        align-items: center!important;
    }
</style>
    @include( '../sweet_script')
    <div class="page-inner">
      <!--   <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                                
                        <div class="row">
                            <div class="d-flex align-items-center">
                                <div class="col col_head">
                                    <h4 class="page-title">Existing vouchers</h4>
                                </div>
                                
                            </div>  

                            <div class="col col_head">
                                {!! Html::decode(Form::label('transaction_id','From date')) !!} </br>
                                {{  Form::date('account_id', null, array('id' => 'account_id')) }}
                            </div>
                                
                            <div class="col col_head">
                                {!! Html::decode(Form::label('transaction_date','To date')) !!}</br>
                                {{  Form::date('account_id', null, array('id' => 'account_id')) }}
                            </div> 
                            
                            <div class="col col_head">
                                <span class="">Select transactions</span>
                                {!! Form::select('trnx_type_id', [0=>"---select---"]+hp_transaction_types(TRUE),null, array('class' => 'form-control cls_transaction_type','id'=>'trnx_type_id')) !!}
                            </div>  
                        </div>  
                    </div>
                </div>
            </div>
        </div>-->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                <!-- style="background-color: #d1cdcd38;" -->
                    <div class="card-header">
                        <!-- <div class="d-flex align-items-center"> -->
                             <!-- <h4 class="page-title">@yield('title') vouchers</h4> -->
                            <!-- <div  class="ml-auto"> -->
                                
                        <div class="row">

                            <div class="col-2" style="text-align: left;">
                                <div class="cls_heading_3">New vouchers</div>
                            </div>

                            <div class="col-2">
                                <div class="row">
                                    <div class="col-6">
                                        {!! Html::decode(Form::label('transaction_id','Trnx ID')) !!} </br>

                                    </div>
                                    <div class="col-6">
                                        <span class="cls_label class_transaction_type">-</span>
                                        <span class="cls_label class_transaction_id">-</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-2">
                                <div class="row">
                                    <div class="col-5">
                                        {!! Html::decode(Form::label('transaction_date','Trnx date')) !!}</br>
                                    </div>
                                    <div class="col-7">
                                        <span class="cls_label cls_date">{{hp_today()}}</span>
                                    </div>
                                </div>
                            </div> 

                            <div class="col-4">
                                <div class="row">
                                    <div class="col-4">
                                        {!! Html::decode(Form::label('trnx_type_id', 'Trnx type')) !!}
                                    </div>
                                    <div class="col-8">
                                        {!! Form::select('trnx_type_id', [0=>"---Select transactions---"]+hp_transaction_types(TRUE),null, array('class' => 'cls_transaction_type form-control','id'=>'trnx_type_id')) !!}
                                    </div>
                                </div>
                            </div> 

                            <div class="col-2" style="text-align: right;">
                                <a  href="{{ route('transactions.index') }}" class="btn btn-primary btn-xs ml-auto">
                                <i class="fas fa-search"></i>

                                    Find old transactions
                                </a>
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


            function getFirstLetters(str) {
                var words = str.split(" ");
                var firstLetters = words.map(function(word) {
                    return word.charAt(0);
                });
                return firstLetters.join("");
            }


            $(document).on('change','.cls_transaction_type', async function() {
                $('.cls_form').hide();
                $(".cls_selected_bank_balance").html(0);
                $(".cls_selected_account_balance").html(0);
                $(".cls_cih_balance").html($("#cih_balance").val());

                var trnx_type_id = ($(this).val());
                
                $('.form_'+trnx_type_id).show();
                $('.cls_transaction_type').val(trnx_type_id);

                var selectedOptionText = $('.cls_transaction_type').find('option:selected').text();
                if(selectedOptionText == "---Select transactions---"){
                    $(".cls_heading_3").html("New vouchers");
                    $(".class_transaction_id").html('-');
                    $(".class_transaction_type").html('-');

                }else{
                    $(".cls_heading_3").html(selectedOptionText);
                    await get_last_trnx_id(trnx_type_id) // get last transaction id of this type
                   
                    var rCount      = parseInt($(".class_transaction_id").html());
                    var rowCount    = $('.table_' + trnx_type_id + ' tbody tr').length;
                    var totalCount  = rCount + (rowCount-1);
                    $(".class_transaction_id").html(totalCount);
                    $(".class_transaction_type").html(getFirstLetters(selectedOptionText));

                    // console.log("rowCount", rowCount);
                    // console.log("totalCount", totalCount);
                    // console.log("cls_transaction_type: ", selectedOptionText);
                }
            })


            async function get_last_trnx_id(trnx_type_id) {
                try {
                    const response = await $.ajax({
                        url: "{{ url('get_last_trnx_id') }}/" + trnx_type_id,
                        method: 'GET'
                    });
                    // alert("result: success:: ", response);
                    $(".class_transaction_id").html(response);
                } catch (error) {
                    console.log(error.responseText);
                }
            }


            $(document).on('click','.btn_add', function(){
                let trnx_id  = $(".class_transaction_id").html();
                $(".class_transaction_id").html(++trnx_id);
            });

          
        });
	</script>


@endsection
