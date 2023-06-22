
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    
                    <div class="col col_head">
                        {!! Html::decode(Form::label('transaction_id','Transaction ID')) !!} </br>
                        <span class="cls_label class_transaction_id">{{hp_next_transaction_id()}}</span>
                        {{ Form::hidden('transaction_id', hp_next_transaction_id(), array('id'=>'transaction_id','class' => 'form-control','readonly' => ''  )) }}
                    </div>
                    
                    <div class="col col_head">
                        {!! Html::decode(Form::label('transaction_date','Transaction date')) !!}</br>
                        <span class="cls_label cls_date">{{hp_today()}}</span>
                        {!! Form::hidden('transaction_date', hp_today(), array('id' => 'transaction_date','class' => 'form-control','readonly' => '' )) !!}
                    </div> 

                    <div class="col col_head">
                        {!! Html::decode(Form::label('account_name','User Login Branch CIH')) !!}</br>
                        <span class="cls_label cls_account_name">{{ (hp_cash_in_hand()->name) ?? ""}}</span>
                        {!! Form::hidden('account_name', (hp_cash_in_hand()->name) ?? "", array('id' => 'account_name','class' => 'form-control','readonly' => '' )) !!}
                    </div> 


                    <div class="col col_head">
                        {!! Html::decode(Form::label('account_balance','Account balance')) !!}</br>
                        <span class="cls_label cls_current_balance">33333</span>
                        {!! Form::hidden('account_balance', (hp_cash_in_hand()->current_balance) ?? "", array('id' => 'account_balance','class' => 'form-control','readonly' => '' )) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Cash receiving</h4>
                </div>
            </div>
            <!--begin::Form-->
                {!! Form::open(array('id'=>'bnk_dpst_form','enctype'=>'multipart/form-data')) !!}

                    {{  Form::hidden('created_by', Auth::user()->id ) }}
                    {{  Form::hidden('company_id', Auth::user()->company_id ) }}
                    {{  Form::hidden('branch_id', Auth::user()->branch_id ) }}
                    {{  Form::hidden('action', "store" ) }}
                    {{  Form::hidden('transaction_type_id', 0 , array("class" => "cls_transaction_type")) }}

                    <div class="card-body">
                        

                        <div class="row">
                                <div class="col">
                                    <table class="table" id="tbl_bnk_dpst">
                                        <thead>
                                            <tr>
                                                <th width="25%">Account</th>
                                                <th width="60%">Detail</th>
                                                <th width="15%">Amount</th>
                                                <th width="10%"><a class="text-light btn btn-primary btn-xs add_bnk_dpst" id="">+</a></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                <tr>
                                                    <td>
                                                        {!! Form::select("account_ids[]", ["Please select"]+hp_accounts() ,[], array("class" => "form-control select2")) !!}
                                                    </td>
                                                    <td>
                                                        {{ Form::text("details[]", null, array("placeholder" => "Enter details","class" => "form-control")) }}
                                                    </td>
                                                    <td>
                                                        {{ Form::number("amounts[]", null, array("placeholder" => "amounts","class" => "form-control cls_bnk_dpst_amnt","min"=>0, "step"=>"any")) }}
                                                    </td>
                                                    <td>
                                                        <a class="text-light btn btn-danger btn-xs del_bnk_dpst">-</a>
                                                    </td>
                                                </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="submit" class="btn btn-primary btn-xs mr-2" id="btn_bnk_dpst">Save</button>
                                <button type="reset" class="btn btn-danger btn-xs">Cancel</button>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}
            <!--end::Form-->
        </div>
    </div>
</div>

<script>
        $(document).ready(function () {  
           

            $(document).on('change', '.cls_bnk_dpst_amnt', function() {
                var inputs = $(".cls_bnk_dpst_amnt");
                var amount = 0;
                var amnt   = 0;

                for (var i = 0; i < inputs.length; i++) {
                    amnt = parseFloat($(inputs[i]).val());

                    if (isNaN(amnt)) { // Check if the value is not set or NaN
                        amnt = 0; // Set account_balance to 0 if it's not set
                    }
                    amount +=amnt;
                }
                
                amount = amount.toFixed(2); // Fix the decimal places after the sum
                var account_balance = parseFloat($("#account_balance").val()); // Convert the balance to a float
                
                if (isNaN(account_balance)) { // Check if the value is not set or NaN
                    account_balance = 0; // Set account_balance to 0 if it's not set
                }



                var balance = account_balance + parseFloat(amount); // Convert amount to a number and add it to account_balance

                $(".cls_current_balance").html(balance);

                // console.log("account_balance: ", account_balance);
                // console.log("Input Amount: ", amount);
                // console.log("balance: ", balance);
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


            $('#bnk_dpst_form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('transactions.store') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend:function(){
                        $("#spinner-div").show();
                        $("#btn_bnk_dpst").prop("disabled", true);
                    },
                    success: (data) => {
                        if(data.msg){
                            // this.reset();
                            toastr.success(data.msg);
                             $("#spinner-div").hide();
                            setTimeout(location.reload.bind(location), 2000);
                        }
                    },
                    error: function(data) {
                         $("#btn_bnk_dpst").prop("disabled", false);
                        handle_error(data);
                    }
                });
            });


             $(document).on('click','.add_bnk_dpst', function(){
                $('#tbl_bnk_dpst tbody tr:last').after(
                                                '<tr>'+
                                                    '<td>'+
                                                        '{!! Form::select("account_ids[]", ["Please select"]+hp_accounts() ,[], array("class" => "form-control select2")) !!}' +
                                                    '</td>'+
                                                    '<td>'+
                                                        '{{ Form::text("details[]", null, array("placeholder" => "Enter details","class" => "form-control")) }}' +
                                                    '</td>'+
                                                    '<td>'+
                                                        '{{ Form::number("amounts[]", null, array("placeholder" => "amounts","class" => "form-control cls_bnk_dpst_amnt","min"=>0, "step"=>"any")) }}' +
                                                    '</td>'+
                                                    '<td>'+
                                                        '<a class="text-light btn btn-danger btn-xs del_bnk_dpst">-</a>'+
                                                    '</td>'+
                                                '</tr>'
                    );
                    $('.select2').select2();
               
            });
            $(document).on('click','.del_bnk_dpst', function(){

                var rowCount = $('#tbl_bnk_dpst tr').length;
                if(rowCount > 2){
                    $(this).closest('tr').remove();
                }else{
                    toastr.error("All rows can not be deleted");
                }
            });
         
        });
    </script>