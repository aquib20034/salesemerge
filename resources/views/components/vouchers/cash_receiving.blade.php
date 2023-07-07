<div class="row">
    <div class="col-md-12">
        <div class="card">
            <!-- <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Cash receiving</h4>
                </div>
            </div> -->
            <x-vouchers.head_row/>
            <!--begin::Form-->
                {!! Form::open(array('id'=>'csh_rcvng_form','enctype'=>'multipart/form-data')) !!}

                    {{  Form::hidden('created_by', Auth::user()->id ) }}
                    {{  Form::hidden('company_id', Auth::user()->company_id ) }}
                    {{  Form::hidden('branch_id', Auth::user()->branch_id ) }}
                    {{  Form::hidden('action', "store" ) }}
                    {{  Form::hidden('transaction_type_id', 0 , array("class" => "cls_transaction_type")) }}

                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="table-responsive">
                                    <table class="table table_2" id="tbl_csh_rcvng">
                                        <thead>
                                            <tr>
                                                <th width="25%">Account</th>
                                                <th width="60%">Detail</th>
                                                <th width="15%">Amount</th>
                                                <th width="10%"><a class="text-light btn btn-primary btn-xs add_csh_rcvng btn_add" id="">+</a></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                <tr>
                                                    <td>
                                                        {!! Form::select("account_ids[]", ["Please select"]+hp_accounts() ,[], array("class" => "form-control select2 cls_csh_rcvng_account_ids")) !!}
                                                    </td>
                                                    <td>
                                                        {{ Form::text("details[]", null, array("placeholder" => "Enter details","class" => "form-control")) }}
                                                    </td>
                                                    <td>
                                                        {{ Form::number("amounts[]", null, array("placeholder" => "amounts","class" => "form-control cls_csh_rcvng_amnt","min"=>0, "step"=>"any")) }}
                                                    </td>
                                                    <td>
                                                        <a class="text-light btn btn-danger btn-xs del_csh_rcvng btn_del">-</a>
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
                                <x-buttons.find_old_transactions/>
                                <button type="submit" class="btn btn-primary btn-xs mr-2 btn_save" id="btn_csh_rcvng">Save</button>
                                <button type="reset" class="btn btn-danger btn-xs btn_clear">Clear</button>
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

            function setInputs(entrd_amnt){
                if (isNaN(entrd_amnt)) {
                    entrd_amnt = 0;
                }
                var sltd_acnt_bal = parseFloat($("#selected_account_balance").val());
                var calc_amnt = sltd_acnt_bal - entrd_amnt;
                // console.log("calc_amnt: ", calc_amnt);

                $(".cls_selected_account_balance").html(calc_amnt);
                // console.log("focus finished");
            }


            $(document).on('click', '.cls_csh_rcvng_amnt', async function() {
                // Get the current value of cls_csh_rcvng_amnt
                var currentValue = $(this).val();

                // Find the sibling element with class cls_csh_rcvng_account_ids
                var siblingAccount = $(this).closest('tr').find('.cls_csh_rcvng_account_ids');

                // Get the current balance from the sibling element
                var account_id = siblingAccount.val();

                await getAccountCurrentBalance(account_id);

                // console.log("account_id: ", account_id);
                var entrd_amnt = parseFloat($(this).val());
                setInputs(entrd_amnt)
            });

            $(document).on('change', '.cls_csh_rcvng_account_ids', function() {
                var account_id = $(this).val(); 
                // console.log("change");
                getAccountCurrentBalance(account_id);
            });
            
            $(document).on('change', '.cls_csh_rcvng_amnt', function() {
                var inputs      = $(".cls_csh_rcvng_amnt");
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
                var cih_balance = parseFloat($("#cih_balance").val()); // Convert the balance to a float
                
                if (isNaN(cih_balance)) { // Check if the value is not set or NaN
                    cih_balance = 0; // Set cih_balance to 0 if it's not set
                }

                var balance = cih_balance + parseFloat(amount); // Convert amount to a number and add it to cih_balance

                $(".cls_cih_balance").html(balance);

                // console.log("cih_balance: ", cih_balance);
                // console.log("Input Amount: ", amount);
                // console.log("balance: ", balance);
            });

            $('#csh_rcvng_form').submit(function(e) {
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
                        $("#btn_csh_rcvng").prop("disabled", true);
                    },
                    success: (data) => {
                        if(data.msg){
                            // this.reset();
                            toastr.success(data.msg);
                            $("#spinner-div").hide();
                            $("#btn_csh_rcvng").text("Record saved");
                            shouldPrint(data.last_id);
                            // setTimeout(location.reload.bind(location), 2000);
                        }
                    },
                    error: function(data) {
                         $("#btn_csh_rcvng").prop("disabled", false);
                        handle_error(data);
                    }
                });
            });

            $(document).on('click','.add_csh_rcvng', function(){
                $('#tbl_csh_rcvng tbody tr:last').after(
                                                '<tr>'+
                                                    '<td>'+
                                                        '{!! Form::select("account_ids[]", ["Please select"]+hp_accounts() ,[], array("class" => "form-control select2 cls_csh_rcvng_account_ids")) !!}' +
                                                    '</td>'+
                                                    '<td>'+
                                                        '{{ Form::text("details[]", null, array("placeholder" => "Enter details","class" => "form-control")) }}' +
                                                    '</td>'+
                                                    '<td>'+
                                                        '{{ Form::number("amounts[]", null, array("placeholder" => "amounts","class" => "form-control cls_csh_rcvng_amnt","min"=>0, "step"=>"any")) }}' +
                                                    '</td>'+
                                                    '<td>'+
                                                        '<a class="text-light btn btn-danger btn-xs del_csh_rcvng btn_del">-</a>'+
                                                    '</td>'+
                                                '</tr>'
                    );
                    $('.select2').select2();
               
            });

            $(document).on('click','.del_csh_rcvng', function(){

                var rowCount = $('#tbl_csh_rcvng tr').length;
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