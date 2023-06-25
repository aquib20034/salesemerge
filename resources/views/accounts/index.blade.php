@extends('layouts.main')
@section('title','Accounts')
@section('content')
    @include( '../sweet_script')
    <style>
        .cls_badge_green{
            background-color: #35cd3a;
            margin-left: auto;
            line-height: 1;
            padding: 2px 5px;
            vertical-align: middle;
            font-weight: 900;
            font-size: 11px;
            color:white;
        }

        .cls_badge_blue{
            background-color: blue;
            margin-left: auto;
            line-height: 1;
            padding: 2px 5px;
            vertical-align: middle;
            font-weight: 900;
            font-size: 11px;
            color:white;
        }

        .cls_badge_balance{
            background-color: grey;
            margin-left: auto;
            line-height: 1;
            padding: 2px 5px;
            vertical-align: middle;
            font-weight: 900;
            font-size: 11px;
            color:white;
        }

        .cls_badge_blue:hover,
        .cls_badge_green:hover,
        .cls_badge_balance:hover {
            cursor: pointer;
        }

        
    </style>
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">@yield('title')</h4>
        </div>
        <!-- <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                <label for="filter_account_type">Filter by Account Type:</label>
                <select id="filter_account_type" class="form-control">
                    <option value="">All</option>
                    <option value="1">Assets</option>
                </select>
                </div>
            </div>
        </div> -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Manage @yield('title')</h4>
                            @can('account-create')
                                <a  href="{{ route('accounts.create') }}" class="btn btn-primary btn-xs ml-auto">
                                <i class="fa fa-plus"></i> Create new account</a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="myTable" class="table table-hover" style="width: 100%;" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Opening balance</th>
                                        <th width="5%">Action</th>
                                    </tr>
                                </thead>
                            
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addOpeningBalanceModal" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Opening balance form </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    
                {!! Form::open(array('id'=>'modalForm','enctype'=>'multipart/form-data')) !!}
                    {{  Form::hidden('action', "store",array('id' => 'action')) }}
                    {{  Form::hidden('account_id', null, array('id' => 'account_id')) }}
                    {{  Form::hidden('created_by', Auth::user()->id ) }}
                    {{  Form::hidden('company_id', Auth::user()->company_id ) }}
                    {{  Form::hidden('branch_id', Auth::user()->branch_id ) }}
                    {{  Form::hidden('transaction_type_id',1, array('id' => 'transaction_type_id')) }}
                    {{  Form::hidden('transaction_id',0, array('id' => 'transaction_id')) }}
                    
                    <div class=" row">
                        <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <div class="form-group">
                                {!! Html::decode(Form::label('amount_type','Amount type')) !!}
                                {!! Form::select('amount_type', hp_amount_types(),null, array('class' => 'form-control','id'=>'amount_type')) !!}
                                @if ($errors->has('amount_type'))  
                                    {!! "<span class='span_danger'>". $errors->first('amount_type')."</span>"!!} 
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <div class="form-group">
                                {!! Html::decode(Form::label('amount','Opening balance <span class="text-danger">*</span>')) !!}
                                {{ Form::number('amount', null, array('placeholder' => 'Enter opening balance','class' => 'form-control','id' => 'amount'  )) }}
                                @if ($errors->has('amount'))
                                    {!! "<span class='span_danger'>". $errors->first('amount')."</span>"!!}
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-xs" id="btn_submit">Save</button>
                </div>
                {!! Form::close() !!}
                <!--end::Form-->
            </div>
        </div>
    </div>

    <script>
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('account-delete')
                deleteButton = DeleteButtonCall("{{ route('accounts.massDestroy') }}")
            @endcan
            dtButtons.push(deleteButton)
            let data = [
                { data: 'name', name: 'name' },
                { data: 'account_type', name: 'account_type' },
                { data: 'amount', name: 'amount' },
                { data: 'actions', name: '{{ trans('global.actions') }}',orderable:false,searchable:false }
            ]
            let table = DataTableCall('#myTable', "{{ route('accounts.index') }}", dtButtons, data)

            // Add account type filter event
            // $('#filter_account_type').on('change', function () {
            //     let accountType = $(this).val();
            //     console.log(accountType)
            //     if (table) {
            //         // table.column(1).search(accountType).draw();
            //         // $('#myTable').DataTable().column(1).search(accountType).draw();
            //         table.column(1).search(accountType);
            //       table.draw();
            //     }
            // });
        });

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

            $('#modalForm').submit(function(e) {
                e.preventDefault();
                console.log("test")
                var formData = new FormData(this);

                $.ajax({
                    type: 'POST',
                    url: "{{ route('add_upd_opening_balance') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend:function(){
                        $("#btn_submit").prop("disabled", true);
                        $("#spinner-div").show();
                    },
                    success: function(data) {
                        $("#spinner-div").hide();
                        AlertCall(data, $('#myTable').DataTable().ajax.reload());
                        $("#modalForm")[0].reset();
                        $("#addOpeningBalanceModal").modal("hide"); 
                        $("#btn_submit").prop("disabled", false);


                    },
                    error: function(data) {
                        handle_error(data);
                        $("#btn_submit").prop("disabled", false);

                    }
                });
            });


         

            $(document).on('click','.cls_amount', function(){

                // get attributes
                let account_id      = $(this).attr('data-account_id');
                let action          = $(this).attr('data-action');
                let amount          = $(this).attr('data-amount');
                let amount_type     = $(this).attr('data-amount_type');
                let transaction_id  = $(this).attr('data-transaction_id');

                // set attributes
                $('#account_id').val(account_id);
                $('#action').val(action);
                $('#amount').val(amount);
                $('#amount_type').val(amount_type);
                $('#transaction_id').val(transaction_id);
               
                $('#amount').focus();
            })

        });

    </script>
@endsection
