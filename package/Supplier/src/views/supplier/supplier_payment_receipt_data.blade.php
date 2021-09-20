<?php
/**
 * Created by PhpStorm.
 * User: Hemaxi
 * Date: 30/4/19
 * Time: 18:28 PM
 */

?>
@foreach($debit_receipt AS $key=>$value)

    <?php
    if ($key % 2 == 0) {
        $tblclass = 'even';
    } else {
        $tblclass = 'odd';
    }


    ?>

    <tr id=""  class="<?php echo $tblclass ?>">
        <td style="width:1%;"><input style="width: 40px;height: 20px;" type="checkbox" name="delete_debit_receipt[]" value="{{$value->supplier_debitreceipt_id}}" id="delete_debit_receipt{{$value->supplier_debitreceipt_id}}"></td>
        <td style="text-align:center !important;width:5%;">{{$value->receipt_no}}</td>
        <td style="text-align:center !important;width:7.5%;">{{$value->receipt_date}}</td>
        <td style="text-align:left !important;width:7.5%;">{{$value->supplier_gstdetail->supplier_company_info->supplier_first_name}} {{$value->supplier_gstdetail->supplier_company_info->supplier_last_name}}</td>
        <td style="text-align:center !important;width:5.5%;">{{$value->supplier_gstdetail->supplier_gstin}}</td>
        <td style="text-align:left !important;width:11.5%;">{{$value->remarks}}</td>
        <td style="text-align:left !important;width:5%;">{{date('d-m-Y  h:i:s A'),strtotime($value->created_at)}}</td>
        <td style="text-align:right !important;width:7.5%;">{{$value->total_amount}}</td>


        <?php
        foreach($payment_methods AS $payment_methods_key=>$payment_methods_value)
        {
        if($payment_methods_value->payment_method_id!=6 && $payment_methods_value->payment_method_id != 8 && $payment_methods_value->payment_method_id != 4)
        {
        $count  = 0;
        foreach($value->supplier_debitreceipt_details as $payment_detail) {

        if($payment_methods_value->payment_method_id == $payment_detail->payment_method_id)
        {
            $count++;
        ?>
        <td style="text-align:right !important;">{{$payment_detail['amount']}}</td>
        <?php
        }

        }
        if($count == 0)
        {
        ?>
        <td style="text-align:right !important;">0</td>
        <?php

        }
        }
        }
        ?>
    </tr>

@endforeach

<tr>
    <td colspan="13" class="paginateui">
    </td>
</tr>









