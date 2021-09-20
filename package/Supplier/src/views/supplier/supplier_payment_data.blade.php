<?php
/**
 * Created by PhpStorm.
 * User: Hemaxi
 * Date: 26/4/19
 * Time: 17:37 AM
 */

$total_paid_amt = 0;


?>
@if(isset($outstanding_payment) && $outstanding_payment != '')

@foreach($outstanding_payment AS $key=>$value)
    <?php


    if($key % 2 == 0)
    {
        $tblclass = 'even';
    }
    else
    {
        $tblclass = 'odd';
    }


    $gst_id = $value[0]['inward_stock']['supplier_gst_id'];
    $company_name =  $value[0]['inward_stock']['supplier_gstdetail']['supplier_company_info']['supplier_company_name'];
    $first_name =  $value[0]['inward_stock']['supplier_gstdetail']['supplier_company_info']['supplier_first_name'];
    $last_name = $value[0]['inward_stock']['supplier_gstdetail']['supplier_company_info']['supplier_last_name'];

    $mobile_no = '';

    if($value[0]['inward_stock']['supplier_gstdetail']['supplier_company_info']['supplier_company_mobile_no'] != '')
        {
            $searchString = ',';

            if( strpos($value[0]['inward_stock']['supplier_gstdetail']['supplier_company_info']['supplier_company_mobile_no'],$searchString) !== false )
            {
                $mobile = explode($value[0]['inward_stock']['supplier_gstdetail']['supplier_company_info']['supplier_company_mobile_no'],',');
                $dial_code = explode($value[0]['inward_stock']['supplier_gstdetail']['supplier_company_info']['supplier_company_dial_code'],',');

                foreach ($mobile AS $mobile_key=>$mobile_value)
                    {
                            $mobile_no += $dial_code[$mobile_key] .' '.$mobile_value;
                    }
            }
            else
                {
                    $mobile_no = $value[0]['inward_stock']['supplier_gstdetail']['supplier_company_info']['supplier_company_dial_code'] .' '.$value[0]['inward_stock']['supplier_gstdetail']['supplier_company_info']['supplier_company_mobile_no'];
                }
        }






    $total_outstanding_amt = 0;
    $total_paid_amt = 0;

    /*if(isset($value['supplier_payment_details']) && $value['supplier_payment_details'] != '')
    {
          foreach($value['supplier_payment_details'] AS $payment_key=>$payment_value)
          {*/
             if($value[0]['outstanding_payment'] != '' && $value[0]['outstanding_payment'] != NULL)
             {
                $search_string = ',';
                if(strpos($value[0]['outstanding_payment'],$search_string) !== false)
                    {

                        $outstanding_amount = explode(',',$value[0]['outstanding_payment']);

                        $amount = explode(',',$value[0]['amount']);


                        foreach($amount AS $key=>$value)
                        {
                            $total_outstanding_amt += $value;
                            $total_paid_amt += ($value - $outstanding_amount[$key]);
                        }
                    }
                else
                    {
                        $total_outstanding_amt += $value[0]['amount'];
                        $total_paid_amt += ($value[0]['amount'] - $value[0]['outstanding_payment']);

                    }

             }
          /*}
     }*/

    $amount_to_pay = 0;
    $amount_to_pay = ($total_outstanding_amt - $total_paid_amt);


    ?>
    <tr id="<?php echo $gst_id?>" class="<?php echo $tblclass ?>">
        <td style="width: 10%;"><?php echo $company_name?></td>
        <td style="width: 10%;"><?php echo $first_name?>
            <?php echo $last_name?>  </td>
        <td style="width: 10%;"><?php echo $mobile_no?></td>
        <td style="text-align: right;width: 10%;"><?php echo $total_outstanding_amt ?></td>
        <td style="text-align: right;width: 10%;"><?php echo $total_paid_amt ?></td>
        <td style="text-align: right;width: 10%;"><?php echo $amount_to_pay?></td>
        <td  style="text-align:right !important;">
            <a id="view_outstanding" href="{{URL::to('list_outstanding_payment')}}?supplier_gst_id='<?php echo encrypt($gst_id)?>'"  style="text-decoration:none !important;" >
                <i class="fa fa-eye" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;font-weight:bold;">View Detail</i>
            </a></td>

    </tr>
@endforeach
@endif
<tr>
    <td colspan="7" class="paginateui">
        {{--{!! $outstanding_payment->links() !!}--}}
    </td>
</tr>


