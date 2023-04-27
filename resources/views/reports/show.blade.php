@extends('layouts.main')
@section('title','Report')
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


<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title" style="color:black !important;">@yield('title')</h4>
    </div>
    <div class="row" id="main">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header ">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title" style="color:black !important;">
                        <?php $title = "";
                            if($entity == "customer")
                                $title = "All Customer";
                            elseif($entity == "company")
                                $title = "All Company";
                            elseif($entity == "company_single")
                                $title = "Company";
                            elseif($entity == "customer_single")
                                $title = "Customer";

                        ?>
                         
                        <?php if($entity == "company_single" || $entity == "customer_single"){ ?>
                            {{$title}}: <b> {{$rec[0]->name}} - ({{ $date}})</b> 
                        <?php }elseif($entity == "company" || $entity == "customer"){  ?>
                            {{ $title }} @yield('title') of <b>{{ $date}}</b>
                        <?php }elseif($entity == "daily_report"){  ?>
                            Daily Report of <b>{{$date}}</b>
                        <?php }?>
                         </h4>

                        <div class="btn-group btn-group ml-auto d-print-none">
                            <a  href="{{ route('reports.index') }}" class="btn btn-primary btn-sm ">
                            <i class="fas fa-arrow-left"></i></a>
                            <button  class="btn btn-info btn-sm "  onclick="printDiv('main')">
                            <i class="fa fa-print"></i></button>
                        </div>
                      
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="table-responsive">
                                <?php if ($entity == "company" || $entity == "customer"){?>
                                    <table class="table dt-responsive">
                                        <thead>
                                            <tr>
                                                <th style="color:black !important;">Sr#</th>
                                                <th style="color:black !important;">{{ $entity }}</th>
                                                <th style="color:black !important;">Previous Amount</th>
                                                <th style="color:black !important;">Invoice Amount</th>
                                                <th style="color:black !important;">Pay Amount</th>
                                                <th style="color:black !important;">Remaining Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($rec as $key => $value)
                                                <tr>
                                                    <td style="color:black !important;">{{ $key+1 }}</td>
                                                    <td style="color:black !important;">{{ $value->name }}</td>
                                                    <td style="color:black !important;">{{ $value->pbalance}}</td>
                                                    <td style="color:black !important;">{{ $value->debit }}</td>
                                                    <td style="color:black !important;">{{ $value->credit }}</td>
                                                    <td style="color:black !important;">
                                                        <?php 
                                                            if(((($value->pbalance)+($value->debit))-($value->credit)) > 0)
                                                                {
                                                                    echo ((($value->pbalance)+($value->debit))-($value->credit)) . " CR";
                                                                }else{
                                                                    echo (-1)*((($value->pbalance)+($value->debit))-($value->credit)) . " DR";
                                                                }
                                                        ?>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table><br><br>
                                <?php }elseif ($entity == "company_single" || $entity == "customer_single"){?>
                                 
                                    <table class="table dt-responsive">
                                        <thead>
                                            <tr>
                                                <th width="5%" style="color:black !important;">Sr#</th>
                                                <th width="10%" style="color:black !important;">Date</th>
                                                <th width="25%" style="color:black !important;">detail</th>
                                                <!-- <th width="15%">Previous Amount</th> -->
                                                <!-- <th style="color:black !important;">Ref</th> -->
                                                <th width="20%" style="color:black !important;">Credit Amount</th>
                                                <th width="20%" style="color:black !important;">Debit Amount</th>
                                                <th width="20%" style="color:black !important;">Remaining Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody> <?php $pBal = 0?>
                                            @foreach($rec as $key => $value)
                                                <tr>
                                                    <td style="color:black !important;">{{ $key+1 }}</td>
                                                    <td style="color:black !important;">{{date('Y-m-d',  strtotime($value->date))}}</td>
                                                    <th style="color:black !important;">
                                                        <?php
                                                            if( $value->payment_detail != NULL){?>
                                                                {{ $value->payment_detail}}
                                                        <?php } ?>
                                                        <?php 
                                                            if(($value->payment_detail != NULL) && ($value->bill_id !=NULL)){
                                                                echo ", ";
                                                            }
                                                        ?>
                                                        <?php
                                                            if( $value->bill_id != NULL){?>
                                                                Bill No: {{ $value->bill_id}}
                                                        <?php } ?>

                                                        <?php 
                                                            if(($value->bill_id != NULL) && ($value->order_no !=NULL)){
                                                                echo ", ";
                                                            }
                                                        ?>

                                                        <?php
                                                            if( $value->order_no !=NULL){?>
                                                                Inv No: {{ $value->order_no}}
                                                        <?php } ?>
                                                       </th> 
                                                    <!-- <td style="color:black !important;">
                                                        <?php 
                                                            if($pBal == ""){
                                                                $pBal=0;
                                                            }
                                                        ?>
                                                       
                                                        {{ $pBal }}
                                                    </td> -->
                                                      
                                                    <!-- <th style="color:black !important;">Ref</th> -->
                                                    <td style="color:black !important;">
                                                        <?php 
                                                            if($value->credit == ""){
                                                                $value->credit=0;
                                                            }
                                                        ?>
                                                        {{ $value->credit }}
                                                        </td>
                                                    <td style="color:black !important;">
                                                        <?php 
                                                            if($value->debit == ""){
                                                                $value->debit=0;
                                                            }
                                                        ?>
                                                        {{ ($value->debit) * (-1) }}
                                                    </td>
                                                    <?php 
                                                        $bal = (($pBal + $value->credit)-($value->debit));
                                                    ?>
                                                    <td style="color:black !important;">
                                                        <?php 
                                                            if($bal>=0)
                                                                echo $bal . " CR";
                                                            else
                                                                echo (-1)*($bal) . " DR"

                                                        ?>
                                                    </td>
                                                    <?php $pBal += (($value->credit)-($value->debit)); ?>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                <?php }elseif ($entity == "daily_report"){?>
                                    <table class="table dt-responsive">
                                        <!-- <tr>
                                            <th style="color:black !important;">Old Purchasing Amount</th>
                                            <th style="color:black !important;">{{ $rec['oPurchase'] }}</th>
                                        </tr>
                                        <tr>
                                            <th style="color:black !important;">Old Selling Amount</th>
                                            <th style="color:black !important;">{{ $rec['oSell'] }}</th>
                                        </tr>
                                        
                                        <tr><td colspan=2></td></tr> -->

                                        <tr>
                                            <th width="50%" style="color:black !important;">Opening Amount</th>
                                            <?php $oBal =  $rec['oSell'] - $rec['oPurchase']; ?>
                                            <th style="color:black !important;">{{ $oBal }}</th>
                                        </tr>

                                        
                                        <tr>
                                            <th style="color:black !important;">Purchasing Amount</th>
                                            <th style="color:black !important;">{{ $rec['cPurchase'] }}</th>
                                        </tr>
                                        <tr>
                                            <th style="color:black !important;">Selling Amount</th>
                                            <th style="color:black !important;">{{ $rec['cSell'] }}</th>
                                        </tr>
                                        <tr><td colspan=2></td></tr>
                                        <tr>
                                            <th style="color:black !important;">Cash in hand</th>
                                            <th style="color:black !important;">{{(($oBal +$rec['cSell']) - $rec['cPurchase'])}}</th>
                                        </tr>
                                    </table>
                                <?php } ?>
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
