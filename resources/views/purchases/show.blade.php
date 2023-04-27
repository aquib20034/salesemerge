@extends('layouts.main')
@section('title','Purchases')
@section('content')
@include( '../sweet_script')
<style>
    @media print
    {    
        .no-print, .no-print *
        {
            display: none !important;
        }
        table th,td{
            color:black !important;
        }
    }
    /* .trColor{
      color:black !important;
    } */
    /* table th,td{
        color:black !important;
    } */
    
</style>

<div class="page-inner" id="main">
    <div class="page-header">
        <h4 class="page-title" style="color:black !important;">@yield('title')</h4>
    </div>
    <div class="row" >
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-print-none">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title" style="color:black !important;" >Show @yield('title')</h4>
                   
                        <div class="btn-group btn-group ml-auto ">
                            <a  href="{{ route('purchases.index') }}" class="btn btn-primary btn-sm ">
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
                                        <th style="color:black !important;">Company Name</th>
                                        <td style="color:black !important;" colspan=3>{{$data->company_name}}</td>
                                        
                                    </tr>

                                    <tr>
                                        <th style="color:black !important;" width = "15%">Owner Name</th>
                                        <td style="color:black !important;" width = "35%">{{$data->owner_name}}</td>
                                        <th style="color:black !important;" width = "15%">Bill No: </th>
                                        <td style="color:black !important;" width = "35%">{{$data->id}}</td>
                                    </tr>

                                    <tr>
                                        <th style="color:black !important;">Invoice Date </th>
                                        <td style="color:black !important;">{{$data->invoice_date}}</td>
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
                                        <th style="color:black !important;">Payment Method </th>
                                        <td style="color:black !important;">{{$data->payment_method_name}}</td>
                                        <th style="color:black !important;">Total Amount </th>
                                        <td style="color:black !important;">{{$data->total_amount}}</td>
                                    </tr>


                                    <tr>
                                        <th style="color:black !important;">Payment Detail </th>
                                        <td style="color:black !important;">{{$data->payment_detail}}</td>
                                        <th style="color:black !important;">Bilty (Karaya) </th>
                                        <td style="color:black !important;">{{$data->bilty_amount}}</td>
                                    </tr>
                                    <tr>
                                        <th style="color:black !important;">Net Amount </th>
                                        <td style="color:black !important;">{{$data->net_amount}}</td>
                                        <th style="color:black !important;">Payment </th>
                                        <td style="color:black !important;">{{$data->credit}}</td>
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
            <div class="card" >
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
                                        <tr >
                                            <th width = "5%"  style="color:black !important;">Sr#</th>
                                            <th width = "20%" style="color:black !important;">Item Name</th>
                                            <th width = "15%" style="color:black !important;">Piece</th>
                                            <th width = "20%" style="color:black !important;">Qty in Ctn/ Bora# </th>
                                            <th width = "20%" style="color:black !important;">Purchase Price</th>
                                            <th width = "20%" style="color:black !important;">Purchase Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $tot_amount = 0;?>
                                        @foreach($selected_items as $key =>$value)
                                            <?php $tot_amount += ( $value->purchase_qty *$value->purchase_price);?>
                                            <tr>
                                                <td style="color:black !important;">{{ $key+1 }}</td>
                                                <th style="color:black !important;">{{$value->item_name}}</th>
                                                <td style="color:black !important;">{{$value->item_piece}}</td>
                                                <td style="color:black !important;">{{$value->purchase_qty}}</td>
                                                <td style="color:black !important;">{{$value->purchase_price}}</td>
                                                <td style="color:black !important;">{{$value->purchase_price * $value->purchase_qty }}</td>
                                            </tr>
                                        @endforeach
                                    <tbody>
                                </table>
                                   <br>
                               <table id="summaryTable" class="table dt-responsive" style="margin-left: 60%; max-width: 40%">
                                   <tbody>
                                       <tr>
                                           <th style="color:black !important; width: 20%">Total Amount</th>
                                           <th style="color:black !important; width: 20%">{{ $tot_amount }}</th>
                                       </tr>

                                       <tr>
                                           <th style="color:black !important; width: 20%">Bilty (Karaya) </th>
                                           <th style="color:black !important; width: 20%">{{ $data->bilty_amount }}</th>
                                       </tr>
                                       <tr>
                                           <th style="color:black !important; width: 20%"> Payment </th>
                                           <th style="color:black !important; width: 20%">{{ $data->credit }}</th>
                                       </tr>
                                       <tr>
                                           <th style="color:black !important; width: 20%"> Net Amount </th>
                                           <th style="color:black !important; width: 20%">{{ $data->net_amount }}</th>
                                       </tr>
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
        // var div = document.getElementById('btns');
        //     div.remove();
        // $(".btns").remove();
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        
        // console.log( printContents.children(".btns")) ;
        // console.log(printContents)
        document.body.innerHTML = printContents;
        
        window.print();
        // $(".btns").append();
        document.body.innerHTML = originalContents;

    }
</script>

@endsection
