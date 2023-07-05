<!--begin::Form-->
{!! Form::open(array('id'=>'jrnl_form','enctype'=>'multipart/form-data')) !!}
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
                        <h4 class="card-title">Journal voucher</h4>
                    </div>
                </div> -->
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col_head">
                            {!! Html::decode(Form::label('selected_crdt_acnt_bal','Selected credit account balance')) !!}</br>
                            <span class="cls_label cls_selected_crdt_acnt_bal">0</span>
                            {!! Form::hidden('selected_crdt_acnt_bal', (hp_cash_in_hand()->current_balance) ?? "", array('id' => 'selected_crdt_acnt_bal','class' => 'form-control','readonly' => '' )) !!}
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col_head">
                            {!! Html::decode(Form::label('selected_account_balance','Account balance')) !!}</br>
                            <span class="cls_label cls_selected_account_balance">0</span>
                            {!! Form::hidden('selected_account_balance', 0, array('id' => 'selected_account_balance','class' => 'form-control','readonly' => '' )) !!}
                        </div>
                    </div>
                </div>
          
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h2>Debit Accounts</h2>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="table-responsive">
                                <table class="table"  id="tbl_jrnl_dbt">
                                    <thead>
                                        <tr>
                                            <th width="25%">Debit account</th>
                                            <th width="60%">Debit detail</th>
                                            <th width="15%">Debit Amount</th>
                                            <th width="10%"><a class="text-light btn btn-primary btn-xs add_jrnl_dbt" id="">+</a></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <tr>
                                                <td>
                                                    {!! Form::select("dbt_acnt_ids[]", ["Please select"]+hp_accounts() ,[], array("class" => "form-control select2 cls_dbt_acnt_id")) !!}
                                                </td>
                                                <td>
                                                    {{ Form::text("dbt_details[]", null, array("placeholder" => "Enter detail","class" => "form-control")) }}
                                                </td>
                                                <td>
                                                    {{ Form::number("dbt_amounts[]", null, array("placeholder" => "amount","class" => "form-control cls_dbt_amount","min"=>0, "step"=>"any")) }}
                                                </td>
                                                <td>
                                                    <a class="text-light btn btn-danger btn-xs del_jrnl_dbt">-</a>
                                                </td>
                                            </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col">
                            <h2>Credit Accounts</h2>
                            <hr>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="table-responsive">
                                <table class="table table_6" id="tbl_jrnl">
                                    <thead>
                                        <tr>
                                            <th width="25%">Credit account</th>
                                            <th width="60%">Credit details</th>
                                            <th width="15%">Credit amounts</th>
                                            <th width="10%"><a class="text-light btn btn-primary btn-xs add_jrnl" id="">+</a></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <tr>
                                                <td>
                                                    {!! Form::select("account_ids[]", ["Please select"]+hp_accounts() ,[], array("class" => "form-control select2 cls_jrnl_account_ids")) !!}
                                                </td>
                                                <td>
                                                    {{ Form::text("details[]", null, array("placeholder" => "Enter details","class" => "form-control")) }}
                                                </td>
                                                <td>
                                                    {{ Form::number("amounts[]", null, array("placeholder" => "amounts","class" => "form-control cls_jrnl_amnt","min"=>0, "step"=>"any")) }}
                                                </td>
                                                <td>
                                                    <a class="text-light btn btn-danger btn-xs del_jrnl btn_del">-</a>
                                                </td>
                                            </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-lg-12 text-right">
                            <button type="submit" class="btn btn-primary btn-xs mr-2" id="btn_jrnl">Save</button>
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
            $(document).on('click', '.cls_jrnl_amnt', async function() {
                // Get the current value of cls_jrnl_amnt
                var currentValue = $(this).val();

                // Find the sibling element with class cls_jrnl_account_ids
                var siblingAccount = $(this).closest('tr').find('.cls_jrnl_account_ids');

                // Get the current balance from the sibling element
                var account_id = siblingAccount.val();

                await getAccountCurrentBalance(account_id, 'selected_account_balance');

                // console.log("account_id: ", account_id);
                var entrd_amnt = parseFloat($(this).val());
                setInputs(entrd_amnt)
            });

            // Fetch Account Balance & set inputs
            $(document).on('change', '.cls_jrnl_account_ids', async function() {
                var account_id = $(this).val(); 
                // console.log("change");
               await getAccountCurrentBalance(account_id,'selected_account_balance');
            });
            

            // Fetch Bank Balance & set inputs
            $(document).on('change', '.cls_dbt_acnt_id', async function() {
                var account_id = $(this).val(); 
                // console.log("change");
                await getAccountCurrentBalance(account_id,'selected_crdt_acnt_bal');
            });
            

            
            $(document).on('change', '.cls_jrnl_amnt', function() {
                var inputs      = $(".cls_jrnl_amnt");
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
                var selected_crdt_acnt_bal = parseFloat($("#selected_crdt_acnt_bal").val()); // Convert the balance to a float
                
                if (isNaN(selected_crdt_acnt_bal)) { // Check if the value is not set or NaN
                    selected_crdt_acnt_bal = 0; // Set selected_crdt_acnt_bal to 0 if it's not set
                }

                var balance = selected_crdt_acnt_bal - parseFloat(amount); // Convert amount to a number and add it to selected_crdt_acnt_bal

                $(".cls_selected_crdt_acnt_bal").html(balance);

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

            function compareDebitCredit(){

                // credit amount calc 
                var amnt        = 0;
                var amount      = 0;
                var inputs      = $(".cls_jrnl_amnt");
                
                for (var i = 0; i < inputs.length; i++) {
                    amnt = parseFloat($(inputs[i]).val());
                    if (isNaN(amnt)) { // Check if the value is not set or NaN
                        amnt = 0; // Set cih_balance to 0 if it's not set
                    }
                    amount +=amnt;
                }
                amount                      = amount.toFixed(2); // Fix the decimal places after the sum
                
                
                // debit amount calc  
                var dbt_amnt        = 0;
                var dbt_amount      = 0;
                var dbt_inputs      = $(".cls_dbt_amount");
                
                for (var i = 0; i < dbt_inputs.length; i++) {
                    dbt_amnt = parseFloat($(dbt_inputs[i]).val());
                    if (isNaN(dbt_amnt)) { // Check if the value is not set or NaN
                        dbt_amnt = 0; // Set cih_balance to 0 if it's not set
                    }
                    dbt_amount +=dbt_amnt;
                }
                dbt_amount                      = dbt_amount.toFixed(2); // Fix the decimal places after the sum
                
                
                
                if( parseFloat(dbt_amount) == parseFloat(amount)){
                    return true;
                }
                return false;
                
                
                
                
                
                // var selected_dbt_acnt_bal   = parseFloat($(".cls_dbt_amount").val()); // Convert the balance to a float
                // console.log("selected_dbt_acnt_bal", selected_dbt_acnt_bal);
                
                // if (isNaN(selected_dbt_acnt_bal)) { // Check if the value is not set or NaN
                //     selected_dbt_acnt_bal = 0; // Set selected_dbt_acnt_bal to 0 if it's not set
                // }

                // if(selected_dbt_acnt_bal == parseFloat(amount)){
                //     return true;
                // }
                // return false;
            }

            $('#jrnl_form').submit(function(e) {
                e.preventDefault();

                if(compareDebitCredit()){
                
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
                            $("#btn_jrnl").prop("disabled", true);
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
                            $("#btn_jrnl").prop("disabled", false);
                            handle_error(data);
                        }
                    });
                }else{
                    toastr.error("Debit and credit not matched");

                }
            });


            // BEGIN :: Credit Acount table Add and delete row functions
                $(document).on('click','.add_jrnl', function(){
                    $('#tbl_jrnl tbody tr:last').after(
                                                    '<tr>'+
                                                        '<td>'+
                                                            '{!! Form::select("account_ids[]", ["Please select"]+hp_accounts() ,[], array("class" => "form-control select2 cls_jrnl_account_ids")) !!}' +
                                                        '</td>'+
                                                        '<td>'+
                                                            '{{ Form::text("details[]", null, array("placeholder" => "Enter details","class" => "form-control")) }}' +
                                                        '</td>'+
                                                        '<td>'+
                                                            '{{ Form::number("amounts[]", null, array("placeholder" => "amounts","class" => "form-control cls_jrnl_amnt","min"=>0, "step"=>"any")) }}' +
                                                        '</td>'+
                                                        '<td>'+
                                                            '<a class="text-light btn btn-danger btn-xs del_jrnl btn_del">-</a>'+
                                                        '</td>'+
                                                    '</tr>'
                        );
                        $('.select2').select2();
                
                });
                $(document).on('click','.del_jrnl', function(){

                    var rowCount = $('#tbl_jrnl tr').length;
                    if(rowCount > 2){
                        $(this).closest('tr').remove();
                        // let trnx_id  = $(".class_transaction_id").html();
                        // $(".class_transaction_id").html(--trnx_id);
                    }else{
                        toastr.error("All rows can not be deleted");
                    }
                });
            // END :: Credit Acount table Add and delete row functions



            // BEGIN :: Debit Acount table Add and delete row functions

                $(document).on('click','.add_jrnl_dbt', function(){
                    $('#tbl_jrnl_dbt tbody tr:last').after(
                                                    '<tr>'+
                                                        '<td>'+
                                                            '{!! Form::select("dbt_acnt_ids[]", ["Please select"]+hp_accounts() ,[], array("class" => "form-control select2 cls_dbt_acnt_id cls_dbt_acnt_ids")) !!}' +
                                                        '</td>'+
                                                        '<td>'+
                                                            '{{ Form::text("dbt_details[]", null, array("placeholder" => "Enter detail","class" => "form-control")) }}' +
                                                        '</td>'+
                                                        '<td>'+
                                                            '{{ Form::number("dbt_amounts[]", null, array("placeholder" => "amount","class" => "form-control cls_dbt_amount","min"=>0, "step"=>"any")) }}' +
                                                        '</td>'+
                                                        '<td>'+
                                                            '<a class="text-light btn btn-danger btn-xs del_jrnl_dbt">-</a>'+
                                                        '</td>'+
                                                    '</tr>'
                        );
                        $('.select2').select2();
                
                });
                $(document).on('click','.del_jrnl_dbt', function(){

                    var rowCount = $('#tbl_jrnl_dbt tr').length;
                    if(rowCount > 2){
                        $(this).closest('tr').remove();
                        // let trnx_id  = $(".class_transaction_id").html();
                        // $(".class_transaction_id").html(--trnx_id);
                    }else{
                        toastr.error("All rows can not be deleted");
                    }
                });

            // END :: Debit Acount table Add and delete row functions
         
        });
    </script>