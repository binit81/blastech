<?php
/**
 * Created by PhpStorm.
 * User: Hemaxi
 * Date: 27/4/19
 * Time: 16:12 AM
 */
$ledger_bal = 0;

?>
@foreach($outstanding_detail AS $key=>$value)

    <?php
    if ($key % 2 == 0) {
        $tblclass = 'even';
    } else {
        $tblclass = 'odd';
    }
    $amount_to_pay = 0;
    $mobile_no = '';
    if ($value['inward_stock']['supplier_gstdetail']['supplier_company_info']['supplier_company_mobile_no'] != '') {
        $searchString = ',';

        if (strpos($value['inward_stock']['supplier_gstdetail']['supplier_company_info']['supplier_company_mobile_no'], $searchString) !== false) {
            $mobile = explode($value['inward_stock']['supplier_gstdetail']['supplier_company_info']['supplier_company_mobile_no'], ',');
            $dial_code = explode($value['inward_stock']['supplier_gstdetail']['supplier_company_info']['supplier_company_dial_code'], ',');

            foreach ($mobile AS $mobile_key => $mobile_value) {
                $mobile_no += $dial_code[$mobile_key] . ' ' . $mobile_value;
            }
        } else {
            $mobile_no = $value['inward_stock']['supplier_gstdetail']['supplier_company_info']['supplier_company_dial_code'] . ' ' . $value['inward_stock']['supplier_gstdetail']['supplier_company_info']['supplier_company_mobile_no'];
        }

    }
    $supplier_gst_id = $value['inward_stock']['supplier_gst_id'];
    $total_outstanding_amt = 0;
    $total_paid_amt = 0;

    if ($value['outstanding_payment'] != '' && $value['outstanding_payment'] != NULL) {

        $total_outstanding_amt += $value['amount'];
        $total_paid_amt += ($value['amount'] - $value['outstanding_payment']);
    }
    $amount_to_pay = 0;
    $amount_to_pay = ($total_outstanding_amt - $total_paid_amt);
    $ledger_bal = ($ledger_bal + $amount_to_pay);

    ?>
    <input type="hidden" name="supplier_gst_id" id="supplier_gst_id" value="<?php echo $supplier_gst_id ?>">
    <tr id="debitvalue" data-id="{{$value->supplier_payment_detail_id}}" class="<?php echo $tblclass ?>">
        <td id="debitaccountid_'{{$value->supplier_payment_detail_id}}'" style="width:5px;">
            <input type="checkbox" class="debit_chck" style="width:30px;margin-left:-17px;height: 17px;" data-id="{{$value->supplier_payment_detail_id}}" id="check_{{$value['supplier_payment_detail_id']}}">
        </td>
        <td style="width: 10%;text-align:left !important;">{{$value->inward_stock->supplier_gstdetail->supplier_company_info->supplier_company_name}}</td>
        <td style="width: 10%;text-align:left !important;">{{$value->inward_stock->invoice_no}}</td>

        <td style="width: 10%;text-align:left !important;">{{$value->inward_stock->supplier_gstdetail->supplier_company_info->supplier_company_name}}
            _{{$value->inward_stock->supplier_gstdetail->supplier_gstin}}</td>
        <td style="width: 10%;"><?php echo $mobile_no?></td>
        <td style="width: 10%;text-align:left !important;">{{$value->inward_stock->inward_date}}</td>

        <input type="hidden" value="{{$value['outstanding_payment']}}" id="outstanding_amount_{{$value['supplier_payment_detail_id']}}">
        <input type="hidden" name="inward_stock_id_{{$value->supplier_payment_detail_id}}" id="inward_stock_id_{{$value->supplier_payment_detail_id}}" value="{{$value->inward_stock_id}}">


        <td style="width:15%;text-align:right !important;">{{$value->amount}}</td>
        <td style="width:15%;text-align:right !important;"><?php echo $total_paid_amt?></td>
        <td style="width:15%;text-align:right !important;"><?php echo $amount_to_pay?></td>
    </tr>

@endforeach

<tr>
    <td colspan="9" class="paginateui"></td>
</tr>
<script type="text/javascript">
    $(".ledgerbalance").html('<?php echo $ledger_bal?>')
</script>








