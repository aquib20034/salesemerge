@extends('layouts.main')
@section('title','Sells')
@section('content')
@include( '../sweet_script')
<style>
    @media print
    {    
        .no-print, .no-print *
        {
            display: none !important;
        }
    }
    
</style>

<div class="page-inner" id="main">
    <div class="page-header">
        <h4 class="page-title" style="color:black !important;">@yield('title')</h4>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-print-none">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title" style="color:black !important;">Show @yield('title')</h4>
                        <div class="btn-group btn-group ml-auto ">
                            <a  href="{{ route('sells.index') }}" class="btn btn-primary btn-sm ">
                            <i class="fas fa-arrow-left"></i></a>
                            <button    class="btn btn-info btn-sm "  onclick="printDiv('main')">
                            <i class="fa fa-print"></i></button>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="table-responsive">
                                <table class="table dt-responsive">
                                    <tr>
                                        <th style="color:black !important;">Customer Name</th>
                                        <td style="color:black !important;">{{$data->customer_name}}</td>
                                        <th style="color:black !important;">Bill No</th>
                                        <td style="color:black !important;">{{$data->id}}</td>
                                        
                                    </tr>
                                    <tr>
                                        <th style="color:black !important;">Invoice Date </th>
                                        <td style="color:black !important;">{{$data->invoice_date}}</td>
                                        <!-- <th style="color:black !important;">Order No </th> -->
                                        <th style="color:black !important;">Invoice/ Bilty No </th>
                                        <td style="color:black !important;">{{$data->order_no}}</td>
                                    </tr>
                                 
                                    
                                  
                                    <tr class="d-print-none">
                                        <th style="color:black !important;">Contact No</th>
                                        <td style="color:black !important;">{{$data->contact_no}}</td>
                                        <th style="color:black !important;">Address </th>
                                        <td style="color:black !important;">{{$data->address}}</td>
                                    </tr>
                                    
                                    <tr class="d-print-none">
                                        <th style="color:black !important;">Total Amount </th>
                                        <td style="color:black !important;">{{$data->total_amount}}</td>
                                        <th style="color:black !important;">Payment Method </th>
                                        <td style="color:black !important;">{{$data->payment_method_name}}</td>
                                        
                                    </tr>

                                    <tr>
                                        <th style="color:black !important;">City</th>
                                        <td style="color:black !important;">{{$data->city_name}}</td>
                                        <th style="color:black !important;">Payment </th>
                                        <td style="color:black !important;">{{$data->credit}}</td>
                                      
                                    </tr>
                                    <tr class="d-print-none">
                                        <th style="color:black !important;">Payment Detail </th>
                                        <td style="color:black !important;">{{$data->payment_detail}}</td>
                                        <td style="color:black !important;"></td>
                                        <td style="color:black !important;"></td>
                                    </tr>

                                   
                                </table>
                            </div>
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
                        <h4 class="card-title" style="color:black !important;">Item Details</h4>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="table-responsive">
                                <table class="table dt-responsive">
                                    <thead>
                                        <tr>
                                            <th width = "5%"  style="color:black !important;">Sr#</th>
                                            <th width = "35%" style="color:black !important;">Item Name</th>
                                            <th width = "15%" style="color:black !important;">Unit</th>
                                            <th width = "15%" style="color:black !important;">Qty  </th>
                                            <!-- <th width = "15%">Piece</th> -->
                                            <th width = "15%" style="color:black !important;">Sell Price</th>
                                            <th width = "15%" style="color:black !important;">Sell Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $tot_amount = 0;?>
                                        @foreach($selected_items as $key =>$value)
                                        <?php $tot_amount += ( $value->sell_qty *$value->sell_price);?>
                                            <tr>
                                                <td style="color:black !important;">{{ $key+1 }}</td>
                                                <th style="color:black !important;">{{$value->item_name}}</th>
                                                <td style="color:black !important;">{{$value->unit_name}}</td>
                                                <td style="color:black !important;">{{$value->sell_qty}}</td>
                                                <!-- <td style="color:black !important;">{{$value->unit_piece}}</td> -->
                                                <td style="color:black !important;">{{$value->sell_price}}</td>
                                              
                                                <td style="color:black !important;">{{$value->sell_price * $value->sell_qty }}</td>
                                            </tr>
                                        @endforeach
                                    <tbody>

                                </table>
                                <br>
                                <table id="summaryTable" class="table" style="margin-left: 70%; max-width: 30%">
                                   <tbody>
                                       <tr>
                                           <th style="width: 15%;color:black !important;">Total Amount</th>
                                           <th style="width: 15%;color:black !important;">{{ $tot_amount }}</th>
                                       <tr>
                                       <tr>
                                           <th style="width: 15%;color:black !important;">Total Payment </th>
                                           <th style="width: 15%;color:black !important;">{{ $data->credit }}</th>
                                       <tr>

                                       <tr>
                                           <th style="width: 20%;color:black !important;"> Net-Amount </th>
                                           <th style="width: 20%;color:black !important;">{{ ($data->credit  - $tot_amount) }} </th>
                                       <tr>
                                   <tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function printDiv(divName){
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>


@endsection
