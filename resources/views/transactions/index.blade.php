@extends('layouts.main')
@section('title','Vouchers')
@section('content')
    @include( '../sweet_script')

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

   
    .row{
        align-items: center!important;
    }
    @media only screen and (min-width: 480px) {
        .col-xs-12 {
            margin-top: 0;
        }
    }
</style>
    <div class="page-inner">
        <!-- <div class="page-header">
            <h4 class="page-title">@yield('title')</h4>
        </div> -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <!--  style="background-color: #d1cdcd38;" -->
                        <!-- <div class="d-flex align-items-center"> -->
                                <!-- <h4 class="page-title">@yield('title') vouchers</h4> -->
                            <!-- <div  class="ml-auto"> -->
                                
                        <div class="row">

                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" style="text-align: left;">
                                <div class="cls_table_heading_3">New vouchers</div>
                            </div>
                            
                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-3">
                                        {!! Html::decode(Form::label('from_date', 'From')) !!}
                                    </div>
                                    <div class="col-9">
                                        {!! Form::date('from_date', hp_today(), array('id' => 'from_date', 'class' => 'form-control cls_transaction_date')) !!}
                                    </div>
                                </div>
                            </div>
                                
                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-3">
                                        {!! Html::decode(Form::label('to_date', 'To')) !!}
                                    </div>
                                    <div class="col-9">
                                        {!! Form::date('to_date', hp_today(), array('id' => 'to_date', 'class' => 'form-control cls_transaction_date')) !!}
                                    </div>
                                </div>
                            </div> 


                                            
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-3">
                                        {!! Html::decode(Form::label('tt_id', 'Type')) !!}
                                    </div>
                                    <div class="col-9">
                                        {!! Form::select('tt_id', hp_transaction_types(TRUE),null, array('class' => 'cls_tt form-control','id'=>'tt_id')) !!}
                                    </div>
                                </div>
                            </div> 
                        
                            

                            <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12" style="text-align: center;">
                                <button type="" class="btn btn-success btn-xs" id="btn_table" style="margin-top: 10px;">
                                    <i class="fas fa-sync-alt"></i>
                                    Load vouchers
                                </button>
                            </div>  
                            
                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" style="text-align: center;">
                                <a  href="{{ route('transactions.create') }}" class="btn btn-primary btn-xs ml-auto" style="margin-top: 10px;">
                                    <i class="fa fa-plus"></i> 
                                    Add voucher
                                </a>
                            </div> 
                            
                        </div>  
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="myTable" class="table table-hover" style="width: 100%;" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Account title</th>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Id</th>  <!-- custom id -->
                                        <th>Detail</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Created by</th>
                                        <th>Action</th>
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

    
    <script>

        function printReceipt(receiptId) {
            // Retrieve the receipt details using an API call or any other method
            // Replace this AJAX call with your own logic to retrieve the receipt data
            $.ajax({
                url: '/account_ledgers/print/' + receiptId,
                type: 'GET',
                success: function (response) {
                    // On success, call the print function
                    printContent(response);
                }
            });
        }

        function printContent(content) {
            var printWindow = window.open('', '', 'width=800,height=600');
            printWindow.document.write(content);
            printWindow.document.close();
            
            // Wait for the content to load before triggering the print dialog
            printWindow.addEventListener('load', function () {
                printWindow.print();
                printWindow.close();
            });
        }
                
        
        $(document).ready(async function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });



           
            await load_table();



            $(document).on('change','.cls_tt', function(){
                rename_heading();
            })

            function handle_error(data){
                $("#spinner-div").hide();
                var txt   = '';
                for (var key in data.responseJSON.errors) {
                    txt += data.responseJSON.errors[key];
                    txt +='<br>';
                }
                toastr.error(txt);
            }


            $(document).on('click', '#btn_table', async function() {

                await load_table();

            });

            function rename_heading(){
                $(".cls_table_heading_3").html( $('.cls_tt').find('option:selected').text());
            }

            async function load_table(){
                  // Destroy the DataTable instance if it exists
                if ($.fn.DataTable.isDataTable('#myTable')) {
                    $('#myTable').DataTable().destroy();
                }

                let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
                @can('account_ledger-delete')
                    deleteButton = DeleteButtonCall("{{ route('account_ledgers.massDestroy') }}")
                @endcan
                dtButtons.push(deleteButton)

                let data = [
                    { data: 'account_id', name: 'account_id' },
                    { data: 'transaction_date', name: 'transaction_date' },
                    { data: 'transaction_type_id', name: 'transaction_type_id',orderable:false,searchable:false  },
                    { data: 'custom_id', name: 'custom_id' ,orderable:false,searchable:false },
                    { data: 'detail', name: 'detail' ,orderable:false,searchable:false },
                    { data: 'debit', name: 'debit' ,orderable:false,searchable:false },
                    { data: 'credit', name: 'credit' ,orderable:false,searchable:false },
                    { data: 'created_by', name: 'created_by' },
                    { data: 'actions', name: '{{ trans('global.actions') }}',orderable:false,searchable:false }
                ]

                let transaction_type_id =$('.cls_tt').val();  // transactoin type id
                let from_date = $('#from_date').val();
                let to_date = $('#to_date').val();
                let path  = "{{ url('get_ledger') }}/" + transaction_type_id + "/" + from_date + "/" + to_date;

                // console.log("transaction_type_id: ", transaction_type_id);
                // console.log("from_date: ", from_date);
                // console.log("to_date: ", to_date);
                // console.log("path: ", path);

                DataTableCall('#myTable', path, dtButtons, data)
                rename_heading();
            }
        })
    </script>
@endsection
