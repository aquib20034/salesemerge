<x-vouchers.head_row/>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Cash receiving</h4>
                </div>
            </div>
            <!--begin::Form-->
                {!! Form::open(array('route' => 'accounts.store','method'=>'POST','id'=>'form','enctype'=>'multipart/form-data')) !!}

                    {{  Form::hidden('created_by', Auth::user()->id ) }}
                    {{  Form::hidden('company_id', Auth::user()->company_id ) }}
                    {{  Form::hidden('branch_id', Auth::user()->branch_id ) }}
                    {{  Form::hidden('action', "store" ) }}

                    <div class="card-body">
                        

                        <div class="row">
                                <div class="col">
                                    <table class="table" id="sub_cat_table">
                                        <thead>
                                            <tr>
                                                <th width="25%">Account</th>
                                                <th width="60%">Details</th>
                                                <th width="15%">Amount</th>
                                                <th width="10%"><a class="text-light btn btn-primary btn-xs add_faq_row" id="">+</a></th>
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
                                                        {{ Form::number("amount[]", null, array("placeholder" => "price","class" => "form-control","min"=>0, "step"=>"any")) }}
                                                    </td>

                                                    <td>
                                                        <a class="text-light btn btn-danger btn-xs del_faq_row">-</a>
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

<script>
        $(document).ready(function () {  

            $('.select2').select2();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
         
        });
    </script>