<!DOCTYPE html>
<html>
<head>
	<!-- <title>Test</title> -->
</head>

<style type="text/css">
	body{
	  border: 1px dotted black;
	  padding: 15px;

	}

	td, th{
		height: 20.4px;	
	}
	.tbl_first, .tbl_second, .tbl_three{
  		border-collapse: collapse;
		width:100%;
	}

	.tbl_first th, td{
	  text-align:left;
	}

	.tbl_second td,  .tbl_second th{
	  border: 1px solid black;
	  padding: 0px 10px;
	}
</style>
<body>

 	<!-- <span style="font-size:14px; font-weight: 600; text-align:right;">  07/05/2023 6:23 AM</span> -->
	<table class="tbl_first">
	   <tbody>
	      <tr>
	         <td style="text-align: center; vertical-align: middle;" colspan="4">
	         	<span style="font-size:18px; font-weight: 800"> {{ isset($data->company->name) ? (strtoupper($data->company->name)) : ""}}</span>
	         </td>
	      </tr>
	      <tr>
	         <td style="text-align: center; vertical-align: middle;" colspan="4">
	         	<span style="font-size:14px; font-weight: 500"> {{ isset($data->branch->name) ? (strtoupper($data->branch->name)) : ""}}</span>
	     	</td>
	      </tr>
	      <tr>
	         <td style="text-align: center; vertical-align: middle;" colspan="4">
	         	<span style="font-size:14px; font-weight: 500"> 
                    Code/ STN: {{ isset($data->company->code) ? (($data->company->code)) : ""}},
                    Phone#:{{ isset($data->company->phone_no) ? (($data->company->phone_no)) : ""}},
                    Mobile#:{{ isset($data->company->mobile_no) ? (($data->company->mobile_no)) : ""}},
                    Email: {{ isset($data->company->email) ? (($data->company->email)) : ""}}
                </span>
	     	</td>
	      </tr>
	      <tr>
	         <td style="text-align: center; vertical-align: middle;" colspan="4">
	         	<span style="font-size:22px; font-weight: 800"> 
                    <u>{{ isset($data->transactionType->name) ? (strtoupper($data->transactionType->name)) : ""}}</u>
                </span>
	         </td>
	      </tr>
	      <tr>
	         <th style="width: 15%;"> Voucher#</th>
	         <td style="width: 45%;">  {{ isset($data->custom_id) ? (get_first_letters($data->transactionType->name) . " - " . $data->custom_id) : ""}} </td>
	         <th style="width: 15%;"> Date</th>
	         <td style="width: 25%;">{{ isset($data->transaction_date) ? (($data->transaction_date)) : ""}}</td>
	      </tr>
	      <tr>
	         <th>Description</th>
	         <td style="overflow-wrap: anywhere;">{{ isset($data->detail) ? (($data->detail)) : ""}}</td>
	         <th> Operator</th>
	         <td>{{ isset($data->createdBy->name) ? (($data->createdBy->name)) : ""}}</td>
	      </tr>
	   </tbody>
	</table>
	<!-- DivTable.com -->
	
	<br>

	<table class="tbl_second">
	   <tbody>
	      <tr>
	         <th style="width: 60%; text-align: center;">Amount Detail</th>
	         <th style="width: 20%; text-align: center;">Debit</th>
	         <th style="width: 20%; text-align: center;">Credit</th>
	      </tr>

		  <?php 
			$debits 	= 0;
			$credits 	= 0;
		  ?>
		  @foreach($records as $key => $record)
			<tr>
				<td>
					{{ isset($record->account_id) ? (($record->account_id)) : ""}} -
					{{ isset($record->account->name) ? (strtoupper($record->account->name)) : ""}}
				</td>
				<td style="text-align: right; vertical-align: top;" rowspan="2"> 
					@if($record->ledger->amount_type == 'D')
						{{ $debits += isset($record->ledger->amount) ? ($record->ledger->amount) : 0.00}}
					@else
						0.00
					@endif
				</td>
				<td style="text-align: right; vertical-align: top;" rowspan="2"> 
					@if($record->ledger->amount_type == 'C')
						{{ $credits += isset($record->ledger->amount) ? ($record->ledger->amount) : 0.00}}
					@else
						0.00
					@endif
				</td>
			</tr>
			<tr>
				<td style="text-align: right; overflow-wrap: anywhere;">
					
					@if($record->ledger->amount_type == 'D')
						Cheque method: <span style="font-weight:800"> {{ isset($record->method) ? (($record->method)) : ""}} </span>, 
						Issue date: <span style="font-weight:800">  {{ isset($record->transaction_date) ? (($record->transaction_date)) : ""}}</span>
					@else
						{{ isset($record->detail) ? (($record->detail)) : ""}}
					@endif
				</td>

			</tr>
			
		  @endforeach
		  <tr>
				<th style="text-align: right;">Total Rs.</th>
				<th style="text-align: right; vertical-align: middle;">
					{{ $debits }}
				</th>

				<th style="text-align: right; vertical-align: middle;">
					{{ $credits }}
				</th>
			</tr>
	      <tr>
            <td style="text-align: left; vertical-align: middle; padding: 10px" colspan="3">
	         	The Sum of Rupees: 
                <span style="font-size:16px; font-weight: 800"> 
                    {{ isset($data->ledger->amount) ? numberToWords2($data->ledger->amount) : ""}}
                </span>
	         </td>
	      </tr>
	   </tbody>
	</table> 
	<br>
	<br>
	<table class="tbl_three" style="width: 100%;" border="0">
	   <tbody>
	      <tr>
	         <td style="text-align: center; width: 33%;">____________</td>
	         <td style="text-align: center; width: 33%;">____________</td>
	         <td style="text-align: center; width: 33%;">____________</td>
	      </tr>
	      <tr>
	         <th style="text-align: center;">Accountant</th>
	         <th style="text-align: center;">Cashier</th>
	         <th style="text-align: center;">Party Signature</th>
	      </tr>
	   </tbody>
	</table>
 
</body>
</html>