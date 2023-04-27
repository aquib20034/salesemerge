@extends('layouts.main')
@section('title','Purchases')
@section('content')
@include( '../sweet_script')
<script type="text/javascript">

  
    function add_item_row(){
        var check   = 0;
        var $item   = document.getElementById('item').value; 
        var $static_qty     = document.getElementById('static_qty').value; 
        var itm_id  = $("input[name='item_id\\[\\]']")
                        .map(function(){
                            return $(this).val();
                        }).get();
                        // console.log("id: " +itm_id);      
        if((itm_id.length>0)){    
            // console.log("length" + itm_id.length);
            // console.log("if");           
            itm_id.forEach(function(id) {
                // console.log("$item: " +$item); 
                // console.log("id: " +id);    
                if($item != id){
                    check = 1;
                    
                }else{
                    check = 0;
                }
            });
        }else{
            check = 1;
            console.log("else");           
        }
        if(check == 1){
            // console.log(item);
            var token = $("input[name='_token']").val();
            $.ajax({
                url: "{{ url('fetch_item_detail') }}",
                method: 'POST',
                data: {item:$item, _token:token},
                success: function(data) {
                    // console.log(data.data);
                    // console.log(data.data.tot_piece);

                    
                    var $name = data.data.name;

                    $rowno=$("#itemTable tr").length;
                    // $rowno=$rowno+1;
                    $("#itemTable tr:last").after("<tr id='row_itemTable"+$rowno+"'>"+
                            "<td> " +
                                '<input type="hidden" id="item_id['+$rowno+']" name="item_id[]" value ="'+data.data.id+'" class="form-control" readonly>'+
                                '<input type="text" id="item_name['+$rowno+']" name="item_name[]" value ="'+data.data.name+'" class="form-control" readonly>'+
                            "</td>"+
                            "<td> " +
                            '<input type="number" id="item_piece[]" name="item_piece[]" value ="'+data.data.tot_piece+'" class="form-control" readonly >'+
                            "</td>"+
                            "<td> " +
                                '<input type="number" id="purchase_qty[]" name="purchase_qty[]" value ="'+$static_qty+'" class="form-control" onchange="calc_value()" >'+
                            "</td>"+
                            "<td> " +
                                '<input type="number" id="purchase_price[]" name="purchase_price[]" value ="'+data.data.purchase_price+'" class="form-control" onchange="calc_value()">'+
                            "</td>"+
                            // "<td> " +
                                '<input type="hidden" id="sell_price['+$rowno+']" name="sell_price[]" value ="'+data.data.sell_price+'" class="form-control" >'+
                            // "</td>"+
                            "<td  width='40px'>"+
                                "<input class='btn btn-danger btn-sm' type='button' value='-' onclick=delete_item_row('row_itemTable"+$rowno+"')>"+
                            "</td>"+
                    "</tr>");
                    calc();
                    if($rowno>1){
                        $("#calc").html(
                            '<table id="calcTable" class="table">'+
                                '<tbody>'+
                                    '<tr>'+
                                        '<td colspan="4" style="text-align:right"> </td>'+
                                        '<td width="5%"><input class="btn btn-success btn-sm" type="button" onclick="calc();" value="Calculate"></td>'+
                                    '</tr>'+
                                '<tbody>'+
                            '</table>'
                        );
                    }
                }
            });
        }else{
            alert("This item is already added.");
        }
    }
    function delete_item_row(rowno){
        $('#'+rowno).remove();
        if(document.getElementById('summaryTable') ){
            calc();
        }
        
        $rowno=$("#itemTable tr").length;
        // console.log($rowno);
        if($rowno==1){
            $('#calcTable').remove(); 
            $('#summaryTable').remove(); 
        }
    }
    function calc_value(){
        if(document.getElementById('summaryTable') ){
            calc();
        }

    }
    function calc(){
        // if(document.getElementById('summaryTable') ){
            var purchase_qty        = 0;
            var item_qty            = 0;
            var net_amount          = 0;
            var bilty_amount        = 0;
            var tot_purchase_price  = 0;

            bilty_amount            = document.getElementById('bilty_amount').value;

            purchase_qty            = $("input[name='purchase_qty\\[\\]']")
                                        .map(function(){
                                            return $(this).val();
                                        }).get();

            var purchase_price      = $("input[name='purchase_price\\[\\]']")
                                        .map(function(){
                                            return $(this).val();
                                        }).get();
            
            for (x in purchase_price) {
                    var q = parseInt(purchase_qty[x]);
                    var p = parseInt(purchase_price[x]);

                item_qty            = q * p;
                tot_purchase_price += item_qty;
                }
            // net_amount              = tot_purchase_price +  parseInt(bilty_amount);
            var pay_amount          = document.getElementById('pay_amount').value; 
            net_amount              = (parseInt(pay_amount) - (tot_purchase_price +  parseInt(bilty_amount)));
            $("#summary").html(
                    '<table id="summaryTable" class="table" style="margin-left: 70%; max-width: 30%">'+
                        '<tbody>'+
                            '<tr>'+
                                '<td style="width: 15%">Total Amount</td>'+
                                '<td style="width: 15%">'+tot_purchase_price+'</td>'+
                                '<input type="hidden" name = "total_amount" value="'+tot_purchase_price+'">'+
                            '<tr>'+

                            '<tr>'+
                                '<td style="width: 15%">Bilty (Karaya) </td>'+
                                '<td style="width: 15%">'+bilty_amount+'</td>'+
                            '<tr>'+

                            '<tr>'+
                                '<th style="width: 15%"> Payment </th>'+
                                '<th style="width: 15%">'+pay_amount+'</th>'+
                                '<input type="hidden" name = "pay_amount" value="'+pay_amount+'">'+
                            '<tr>'+

                            '<tr>'+
                                '<th style="width: 15%"> Net Amount </th>'+
                                '<th style="width: 15%">'+net_amount+'</th>'+
                                '<input type="hidden" name = "net_amount" value="'+net_amount+'">'+
                            '<tr>'+
                        '<tbody>'+
                    '</table>'
                );
        }
    // }

    
</script>

<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">@yield('title')</h4>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Add @yield('title')</h4>
                        <a  href="{{ route('purchases.index') }}" class="btn btn-primary btn-round ml-auto">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        
                    </div>
                </div>

                    <!--begin::Form-->
                    
                    {!! Form::model($data, ['method' => 'PATCH','id'=>'form','enctype'=>'multipart/form-data','route' => ['purchases.update', $data->id]]) !!}
                        {{  Form::hidden('update_by', Auth::user()->id ) }}
                        {{  Form::hidden('direction', '1' ) }}
                        <div class="card-body">

                            <div class=" row">
                                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('company_id','Company Name ')) !!}
                                        {!! Form::select('company_id', $companies,null, array('class' => 'form-control','autofocus' => '')) !!}
                                        @if ($errors->has('company_id'))  
                                            {!! "<span class='span_danger'>". $errors->first('company_id')."</span>"!!} 
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('order_no',' Company Order No:')) !!}
                                        {!! Form::text('order_no', null, array('placeholder' => 'Enter company order no','class' => 'form-control')) !!}
                                        @if ($errors->has('order_no'))  
                                            {!! "<span class='span_danger'>". $errors->first('order_no')."</span>"!!} 
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class=" row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('payment_method_id','Payment Method ')) !!}
                                        {!! Form::select('payment_method_id', $payment_methods,null, array('class' => 'form-control')) !!}
                                        @if ($errors->has('payment_method_id'))  
                                            {!! "<span class='span_danger'>". $errors->first('payment_method_id')."</span>"!!} 
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('payment_detail','Transaction Id/ Receiver ')) !!}
                                        {{ Form::text('payment_detail', null, array('placeholder' => 'Enter transaction Id/ receiver','class' => 'form-control'  )) }}
                                        @if ($errors->has('payment_detail'))  
                                            {!! "<span class='span_danger'>". $errors->first('payment_detail')."</span>"!!} 
                                        @endif
                                        
                                    </div>
                                </div>
                            </div>

                            <div class=" row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                    {!! Html::decode(Form::label('pay_amount',' Payment Amount')) !!}
                                        {!! Form::number('pay_amount', null, array('placeholder' => 'Enter payment amount','class' => 'form-control', 'onchange'=>'calc_value()')) !!}
                                        @if ($errors->has('pay_amount'))  
                                            {!! "<span class='span_danger'>". $errors->first('pay_amount')."</span>"!!} 
                                        @endif
                                        
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('bilty_amount',' Bilty (Karaya)')) !!}
                                        {!! Form::number('bilty_amount', null, array('placeholder' => 'Enter bilty (Karaya)','class' => 'form-control','onchange'=>'calc_value()')) !!}
                                        @if ($errors->has('bilty_amount'))  
                                            {!! "<span class='span_danger'>". $errors->first('paying_amount')."</span>"!!} 
                                        @endif
                                    </div>
                                </div>
                              
                            </div>
                            

                            <div class=" row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('invoice_date',' Invoice Date <span class="text-danger">*</span>')) !!}
                                        {!! Form::date('invoice_date',null, array('placeholder' => 'Enter purchase','class' => 'form-control')) !!}
                                        @if ($errors->has('invoice_date'))  
                                            {!! "<span class='span_danger'>". $errors->first('invoice_date')."</span>"!!} 
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('purchase_date',' Purchase Date <span class="text-danger">*</span>')) !!}
                                        {!! Form::date('purchase_date', null, array('placeholder' => 'Enter purchase','class' => 'form-control')) !!}
                                        @if ($errors->has('purchase_date'))  
                                            {!! "<span class='span_danger'>". $errors->first('purchase_date')."</span>"!!} 
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <br>

                            <h4 class="card-title"> Select Item</h4>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <table id="" class="table">
                                        <tbody>
                                            <tr>
                                                <td width="80%" style="text-align:left"> 
                                                    {!! Form::select("item", $items,null, array("class"=> "form-control","id"=>"item")) !!}
                                                </td>
                                                <td width="15%" style="text-align:left"> 
                                                    {!! Form::number('static_qty', 1, array('class' => 'form-control',"id"=>"static_qty")) !!}
                                                </td>
                                                <td width="5%"><input class="btn btn-success btn-sm" type="button" onclick="add_item_row();" value="+"></td>
                                            </tr>
                                        <tbody>
                                    </table>
                                </div>
                            </div>


                            <h4 class="card-title">Item Details</h4>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table id="itemTable" class="table">
                                            <thead>
                                                <tr>
                                                    <th width="45%">Items </th>
                                                    <th width="12%">Piece </th>
                                                    <th width="14%">Qty in Ctn/ Bora# </th>
                                                    <th width="12%">Purchase Price </th>
                                                    <!-- <th width="12%">Sell Price </th> -->
                                                    <th width="5%"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(isset($selected_items)){
                                                    foreach($selected_items as $key => $value){ ?>
                                                    <script type="text/javascript">
                                                        $rowno=$("#itemTable tr").length;
                                                        // $rowno=$rowno+1;
                                                        $("#itemTable tr:last").after("<tr id='row_itemTable"+$rowno+"'>"+
                                                                "<td> " +
                                                                    '<input type="hidden" id="item_id['+$rowno+']" name="item_id[]" value ="{{$value->item_id}}" class="form-control" readonly>'+
                                                                    '<input type="text" id="item_name['+$rowno+']" name="item_name[]" value ="{{$value->item_name}}" class="form-control" readonly>'+
                                                                "</td>"+
                                                                "<td> " +
                                                                '<input type="number" id="item_piece[]" name="item_piece[]" value ="{{$value->item_piece}}" class="form-control" readonly >'+
                                                                "</td>"+
                                                                "<td> " +
                                                                    '<input type="number" id="purchase_qty[]" name="purchase_qty[]" value ="{{$value->purchase_qty}}" class="form-control" onchange="calc_value()" >'+
                                                                "</td>"+
                                                                "<td> " +
                                                                    '<input type="number" id="purchase_price[]" name="purchase_price[]" value ="{{$value->purchase_price}}" class="form-control" onchange="calc_value()">'+
                                                                "</td>"+
                                                                // "<td> " +
                                                                    '<input type="hidden" id="sell_price['+$rowno+']" name="sell_price[]" value ="{{$value->sell_price}}" class="form-control" >'+
                                                                // "</td>"+
                                                                "<td  width='40px'>"+
                                                                    "<input class='btn btn-danger btn-sm' type='button' value='-' onclick=delete_item_row('row_itemTable"+$rowno+"')>"+
                                                                "</td>"+
                                                        "</tr>");
                                                    </script>
                                                <?php } }?>
                                            <tbody>
                                        </table>
                                    </div>
                                    <div id="calc">
                                        <table id="calcTable" class="table">
                                            <tbody>
                                                <tr>
                                                    <td colspan="4" style="text-align:right"> </td>
                                                    <td width="5%"><input class="btn btn-success btn-sm" type="button" onclick="calc();" value="Calculate"></td>
                                                </tr>
                                            <tbody>
                                        </table>
                                    </div>
                                    <div id="summary"></div>
                                </div>
                            </div>
                        </div>
                        <script>
                            calc();
                        </script>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit" class="btn btn-primary mr-2" onclick="calc_value()">Save</button>
                                    <button type="reset" class="btn btn-danger" >Cancel</button>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                    <!--end::Form-->
                </div>
            </div>
        </div>
    </div>
</div>
  
<!-- item table -->


@endsection
