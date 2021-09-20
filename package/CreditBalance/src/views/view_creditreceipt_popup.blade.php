<?php
/**
 * Created by PhpStorm.
 * User: Hemaxi
 * Date: 26/3/19
 * Time: 10:18 AM
 */

if(sizeof($creditreceipt) != 0)
{
  
?>

<form id="popupbills">
    
      <table  class="table tablesaw table-bordered table-hover table-striped mb-0" width="100%" cellpadding="6" border="0" frame="box"  style="border:1px solid #C0C0C0 !important;">
      <thead>
      <tr style="background:#88c241;border-bottom:1px #f3f3f3 solid;border-top:1px #f3f3f3 solid;">
      <th class="text-dark font-14" style="width:3% !important;color:#fff !important;">Sr.No.</th>
      <th class="text-dark font-14" style="width:9% !important;color:#fff !important;">Receipt No.</th>
      <th class="text-dark font-14" style="width:9% !important;color:#fff !important;">Receipt Date</th>
      <th class="text-right text-dark font-14" style="width:4% !important;color:#fff !important;">UnPaid Amount</th>
      <th class="text-right text-dark font-14" style="width:8% !important;color:#fff !important;">Received Amount</th>
      <th class="text-right text-dark font-14" style="width:7% !important;color:#fff !important;">Balance Amount</th>
      <th class="text-right text-dark font-14" style="width:10% !important;color:#fff !important;">Remarks</th>
      <th class="text-right text-dark font-14" style="width:10% !important;color:#fff !important;">Mode Details</th>
      </tr>
      </thead>
      <tbody id="productdetails">

      <?php
     

        foreach($creditreceipt as $credit_key=>$credit_value)
        {
              $payvalue = '';
              $sr  =  $credit_key  + 1;
              

              foreach($credit_value['customer_crerecp_payment'] as $credit_paykey=>$credit_payvalue)
              {
                  $payvalue   =    '"'.$credit_payvalue['payment_method']['payment_method_name'].'"'.' : '. $credit_payvalue['total_bill_amount'];
              }
             
            ?>
            <tr height="35">
              <td style="font-size:14px !important;">{{$sr}}</td>
              <td style="font-size:14px !important;">{{$credit_value['customer_creditreceipt']['receipt_no']}}</td>
              <td style="font-size:14px !important;">{{$credit_value['customer_creditreceipt']['receipt_date']}}</td>
              <td style="font-size:14px !important;">{{$credit_value['credit_amount']}}</td>
              <td style="font-size:14px !important;">{{$credit_value['payment_amount']}}</td>
              <td style="font-size:14px !important;">{{$credit_value['balance_amount']}}</td>
              <td style="font-size:14px !important;">{{$credit_value['customer_creditreceipt']['remarks']}}</td>
              <td style="font-size:14px !important;">{{$payvalue}}</td>
              
            </tr>
            <?php
        }
    
      ?>

      </tbody>
     
      </table>
  

<script type="text/javascript">

$(document).ready(function(e){



 
});


</script>
</form>
<?php
}
else
{
  ?>
   <table  class="table tablesaw table-bordered table-hover table-striped mb-0" width="100%" cellpadding="6" border="0" frame="box"  style="border:1px solid #C0C0C0 !important;">
      <thead>
      <tr style="background:#88c241;border-bottom:1px #f3f3f3 solid;border-top:1px #f3f3f3 solid;">
      <th class="text-dark font-14" style="width:3% !important;color:#fff !important;">Sr.No.</th>
      <th class="text-dark font-14" style="width:9% !important;color:#fff !important;">Receipt No.</th>
      <th class="text-dark font-14" style="width:9% !important;color:#fff !important;">Receipt Date</th>
      <th class="text-right text-dark font-14" style="width:4% !important;color:#fff !important;">UnPaid Amount</th>
      <th class="text-right text-dark font-14" style="width:8% !important;color:#fff !important;">Received Amount</th>
      <th class="text-right text-dark font-14" style="width:7% !important;color:#fff !important;">Balance Amount</th>
      <th class="text-right text-dark font-14" style="width:10% !important;color:#fff !important;">Remarks</th>
      <th class="text-right text-dark font-14" style="width:10% !important;color:#fff !important;">Mode Details</th>
      </tr>
      </thead>
      <tbody id="productdetails">
        <tr height="35">
        <td colspan="8"><b style="color:#f00;">No Receipt found yet.............</b></td>
        </tr>
      </tbody>
      </table>
        <?php
}
?>