<?php
/**
 * Created by PhpStorm.
 * User: Hemaxi
 * Date: 26/3/19
 * Time: 10:18 AM
 */
$ledgerbal  =  0;
foreach($cuscreditdata AS $cuskey=>$customer_value)
{
	
	if ($cuskey % 2 == 0) {
	    $tblclass = 'even';
	} else {
	    $tblclass = 'odd';
	}
	$receivedamt     =    $customer_value->recdamt !='' ?$customer_value->recdamt :0;
	$balnce_amount   =    $customer_value->credit_amount  -  $receivedamt;

	

	if($balnce_amount > 0)
	{

	?>
	<tr id="creditvalue_{{$customer_value['customer_creditaccount_id']}}" class="<?php echo $tblclass ?>">
	<td id="creditaccountid_{{$customer_value['customer_creditaccount_id']}}"><input type="checkbox" class="chkbox12" style="width:30px;margin-left:-17px;height: 17px;" value="{{$customer_value['customer_creditaccount_id']}}" id="check_{{$customer_value['customer_creditaccount_id']}}">
	<input type="hidden" value="{{$customer_value['balance_amount']}}" id="balanceamount_{{$customer_value['customer_creditaccount_id']}}" class="overallbal_{{$customer_value['customer_creditaccount_id']}}">
	<input type="hidden" value="" id="customercreditreceiptdetailid_{{$customer_value['customer_creditaccount_id']}}"></td>
	<td style="text-align:left !important;">{{$customer_value['sales_bill']['bill_no']}}</td>
	<td style="text-align:left !important;">{{$customer_value['customer']['customer_name']}}</td>
	<td style="text-align:left !important;">{{$customer_value['bill_date']}}</td>
	<td style="text-align:right !important;">{{$customer_value['credit_amount']}}</td>
	<td style="text-align:right !important;">{{$receivedamt}}</td>
	<td style="text-align:right !important;">{{$balnce_amount}}</td>
	</tr>
<?php

	}
	$ledgerbal       =   $ledgerbal + $balnce_amount;

}
?>
<script type="text/javascript">
$('.ledgerbalance').html({{$ledgerbal}});
</script>

