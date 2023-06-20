@extends('layouts.main')
@section('title','Transactions')
@section('content')
<style>
    .cls_form{
        display:none;
    }
    .cls_label{
        font-weight:900;

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
                                <span class="cls_label">Select transaction voucher</span>
                                {!! Form::select('transaction_type_id', [0=>"---select---"]+$transaction_types,null, array('class' => 'form-control cls_transaction_type','id'=>'transaction_type_id')) !!}
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

            $(document).on('change','.cls_transaction_type', function(){
                $('.cls_form').hide();

                var id = ($(this).val());
                $('.form_'+id).show();
                
                $('.select2').focus();


                // console.log("id" + id);
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
