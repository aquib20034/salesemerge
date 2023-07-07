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
	         <th style="width: 76.7895%; text-align: center;">Amount Detail</th>
	         <th style="width: 44.2105%; text-align: center;">Debit</th>
	      </tr>
	      <tr>
	         <td>
                {{ isset($data->account_id) ? (($data->account_id)) : ""}} -
                {{ isset($data->account->name) ? (strtoupper($data->account->name)) : ""}}
            </td>
	        <td style="text-align: right; vertical-align: top;" rowspan="2"> 
                {{ isset($data->ledger->amount) ? ($data->ledger->amount) : ""}}
            </td>
	      </tr>
	      <tr>
	         <td style="text-align: right;">Cash Paid</td>
	      </tr>
	      <tr>
	        <th style="text-align: right;">Total Rs.</th>
	        <th style="text-align: right; vertical-align: middle;">
                {{ isset($data->ledger->amount) ? ($data->ledger->amount) : ""}}
            </th>
	      </tr>
	      <tr>
            <td style="text-align: left; vertical-align: middle; padding: 10px" colspan="2">
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