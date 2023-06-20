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
</style>
    @include( '../sweet_script')
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                             <h4 class="page-title">@yield('title') vouchers</h4>
                            <div  class="ml-auto">
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
                $(".cls_current_balance").html($("#account_balance").val());
                $('.cls_form').hide();
                var id = ($(this).val());
                $('.form_'+id).show();
                $('.select2').focus();
                $('.cls_transaction_type').val(id);
            })
        });
	</script>


@endsection
