<!--begin::Form-->
{!! Form::open(array('id'=>'bnk_pymnt_form','enctype'=>'multipart/form-data')) !!}
    {{  Form::hidden('created_by', Auth::user()->id ) }}
    {{  Form::hidden('company_id', Auth::user()->company_id ) }}
    {{  Form::hidden('branch_id', Auth::user()->branch_id ) }}
    {{  Form::hidden('action', "store" ) }}
    {{  Form::hidden('transaction_type_id', 0 , array("class" => "cls_transaction_type")) }}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <!-- <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Bank payment Voucher</h4>
                    </div>
                </div> -->
                <div class="card-header">
                    <div class="row">
                        <div class="col-3 col_head">
                            {!! Html::decode(Form::label('bank_ids','Bank Account Name')) !!} </br>
                            {!! Form::select("bank_id", ["Please select"]+hp_banks() ,[], array("class" => "form-control select2 cls_bank_id")) !!}
                        </div>
            
                        <div class="col-3 col_head">
                            {!! Html::decode(Form::label('method','Cheque#')) !!} </br>
                            {!! Form::text('cheque_no',  null, array('placeholder' => 'Enter cheque#', 'id' => 'cheque_no','class' => 'form-control' )) !!}

                        </div> 

                        <div class="col-2 col_head">
                            {!! Html::decode(Form::label('transaction_date','Transaction date')) !!}</br>
                            {!! Form::date('transaction_date', hp_today(), array('id' => 'transaction_date','class' => 'form-control cls_transaction_date' )) !!}
                        </div> 

                        
                        <div class="col-2 col_head">
                            {!! Html::decode(Form::label('selected_bank_balance','Selected Bank Balance')) !!}</br>
                            <span class="cls_label cls_selected_bank_balance">0</span>
                            {!! Form::hidden('selected_bank_balance', (hp_cash_in_hand()->current_balance) ?? "", array('id' => 'selected_bank_balance','class' => 'form-control','readonly' => '' )) !!}
                        </div>

                        <div class="col-2 col_head">
                            {!! Html::decode(Form::label('selected_account_balance','Account balance')) !!}</br>
                            <span class="cls_label cls_selected_account_balance"></span>
                            {!! Form::hidden('selected_account_balance', 0, array('id' => 'selected_account_balance','class' => 'form-control','readonly' => '' )) !!}
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <table class="table table_5" id="tbl_bnk_pymnt">
                                <thead>
                                    <tr>
                                        <th width="25%">Account</th>
                                        <th width="60%">Detail</th>
                                        <th width="15%">Amount</th>
                                        <th width="10%"><a class="text-light btn btn-primary btn-xs add_bnk_pymnt btn_add" id="">+</a></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            {!! Form::select("account_ids[]", ["Please select"]+hp_accounts() ,[], array("class" => "form-control select2 cls_bnk_pymnt_account_ids")) !!}
                                        </td>
                                        <td>
                                            {{ Form::text("details[]", null, array("placeholder" => "Enter details","class" => "form-control")) }}
                                        </td>
                                        <td>
                                            {{ Form::number("amounts[]", null, array("placeholder" => "amounts","class" => "form-control cls_bnk_pymnt_amnt","min"=>0, "step"=>"any")) }}
                                        </td>
                                        <td>
                                            <a class="text-light btn btn-danger btn-xs del_bnk_pymnt btn_del">-</a>
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
                            <button type="submit" class="btn btn-primary btn-xs mr-2" id="btn_bnk_pymnt">Save</button>
                            <button type="reset" class="btn btn-danger btn-xs">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{!! Form::close() !!}
<!--end::Form-->
<script>
        $(document).ready(function () {  

            async function getAccountCurrentBalance(account_id, place) {
                // console.log("sending request");
                try {
                    const response = await $.ajax({
                        url: "{{ url('get-current-balance') }}/" + account_id,
                        method: 'GET'
                    });

                    // console.log("result: success:: ", response);

                    $("#"+place).val(response);
                    $(".cls_"+place).html(response);
                } catch (error) {
                    console.log(error.responseText);
                }
            }

            function setInputs(entrd_amnt){
                if (isNaN(entrd_amnt)) {
                    entrd_amnt = 0;
                }
                var sltd_acnt_bal = parseFloat($("#selected_account_balance").val());
                var calc_amnt = sltd_acnt_bal + entrd_amnt;
                // console.log("calc_amnt: ", calc_amnt);

                $(".cls_selected_account_balance").html(calc_amnt);
                // console.log("focus finished");
            }

            // 
            $(document).on('click', '.cls_bnk_pymnt_amnt', async function() {
                // Get the current value of cls_bnk_pymnt_amnt
                var currentValue = $(this).val();

                // Find the sibling element with class cls_bnk_pymnt_account_ids
                var siblingAccount = $(this).closest('tr').find('.cls_bnk_pymnt_account_ids');

                // Get the current balance from the sibling element
                var account_id = siblingAccount.val();

                await getAccountCurrentBalance(account_id, 'selected_account_balance');

                // console.log("account_id: ", account_id);
                var entrd_amnt = parseFloat($(this).val());
                setInputs(entrd_amnt)
            });

            // Fetch Account Balance & set inputs
            $(document).on('change', '.cls_bnk_pymnt_account_ids', async function() {
                var account_id = $(this).val(); 
                // console.log("change");
               await getAccountCurrentBalance(account_id,'selected_account_balance');
            });
            

            // Fetch Bank Balance & set inputs
            $(document).on('change', '.cls_bank_id', async function() {
                var account_id = $(this).val(); 
                // console.log("change");
                await getAccountCurrentBalance(account_id,'selected_bank_balance');
            });
            



            
            $(document).on('change', '.cls_bnk_pymnt_amnt', function() {
                var inputs      = $(".cls_bnk_pymnt_amnt");
                var amount      = 0;
                var amnt        = 0;

                var entrd_amnt  = parseFloat($(this).val());
                setInputs(entrd_amnt);

                for (var i = 0; i < inputs.length; i++) {
                    amnt = parseFloat($(inputs[i]).val());

                    if (isNaN(amnt)) { // Check if the value is not set or NaN
                        amnt = 0; // Set cih_balance to 0 if it's not set
                    }
                    amount +=amnt;
                }
                
                amount = amount.toFixed(2); // Fix the decimal places after the sum
                var selected_bank_balance = parseFloat($("#selected_bank_balance").val()); // Convert the balance to a float
                
                if (isNaN(selected_bank_balance)) { // Check if the value is not set or NaN
                    selected_bank_balance = 0; // Set selected_bank_balance to 0 if it's not set
                }

                var balance = selected_bank_balance - parseFloat(amount); // Convert amount to a number and add it to selected_bank_balance

                $(".cls_selected_bank_balance").html(balance);

                // console.log("cih_balance: ", cih_balance);
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


            $('#bnk_pymnt_form').submit(function(e) {
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
                        $("#btn_bnk_pymnt").prop("disabled", true);
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
                         $("#btn_bnk_pymnt").prop("disabled", false);
                        handle_error(data);
                    }
                });
            });


             $(document).on('click','.add_bnk_pymnt', function(){
                $('#tbl_bnk_pymnt tbody tr:last').after(
                                                '<tr>'+
                                                    '<td>'+
                                                        '{!! Form::select("account_ids[]", ["Please select"]+hp_accounts() ,[], array("class" => "form-control select2 cls_bnk_pymnt_account_ids")) !!}' +
                                                    '</td>'+
                                                    '<td>'+
                                                        '{{ Form::text("details[]", null, array("placeholder" => "Enter details","class" => "form-control")) }}' +
                                                    '</td>'+
                                                    '<td>'+
                                                        '{{ Form::number("amounts[]", null, array("placeholder" => "amounts","class" => "form-control cls_bnk_pymnt_amnt","min"=>0, "step"=>"any")) }}' +
                                                    '</td>'+
                                                    '<td>'+
                                                        '<a class="text-light btn btn-danger btn-xs del_bnk_pymnt btn_del">-</a>'+
                                                    '</td>'+
                                                '</tr>'
                    );
                    $('.select2').select2();
               
            });
            $(document).on('click','.del_bnk_pymnt', function(){

                var rowCount = $('#tbl_bnk_pymnt tr').length;
                if(rowCount > 2){
                    $(this).closest('tr').remove();
                    let trnx_id  = $(".class_transaction_id").html();
                    $(".class_transaction_id").html(--trnx_id);
                }else{
                    toastr.error("All rows can not be deleted");
                }
            });
         
        });
    </script>