<?php
/**
 * Created by PhpStorm.
 * User: Hemaxi
 * Date: 26/3/19
 * Time: 10:18 AM
 */

 if(sizeof($returnsales) != 0) 
 {


 if($returnsales[0]['customer']!= null && $returnsales[0]['customer']!= '' && $returnsales[0]['customer']['customer_name']!= null)
  {
            $customer_name  =  $returnsales[0]['customer']['customer_name'];
  }
   else
  {
            $customer_name = '';
  }
   if($returnsales[0]['customer']!= null && $returnsales[0]['customer']!= '' && $returnsales[0]['customer']['customer_mobile']!= null)
  {
           $customer_mobile  =  $returnsales[0]['customer']['customer_mobile'];
  }
   else
  {
            $customer_mobile = '';
  }

    $currtitle       = $nav_type[0]['currency_title'];
    if($nav_type[0]['tax_type']==1)
    {
        $currency_title  = $currtitle==''||$currtitle==NULL?'INR':$currtitle;
    }
    else
    {
        $currency_title  = 'INR';
    }
  
?>

<div class="billdata">
<div class="invoice-to-wrap pb-20">
     <div class="row">
    <div class="col-xl-12">
        
            <div class="invoice-from-wrap">
               
                <div class="row" style="margin:20px 0 0 0;">
                
                  
                </div>
            </div>
         
            <div class="invoice-to-wrap pb-20">
                <div class="row">
                    <div class="col-md-7 mb-30" style="width:50% !important;border:0px solid !important;font-size:16px;"> 
                       <input type="hidden" id="rmaxid" value="{{$rmaxsales_id}}">
                              <input hidden="text" id="rminid" value="{{$rminsales_id}}">
                              <input hidden="text" id="rfetchedbillno" value="{{$returnsales[0]['return_bill_id']}}">
                        <table style="float:left;">
                            
                            <tr>
                             
                            <td class="d-block text-dark font-14">Customer</td>
                            <td class="font-weight-600">&nbsp;:&nbsp;</td>
                            <td class="text-dark font-14 font-weight-600"><span class="customer_name">{{$customer_name}}</span></td>
                            </tr>
                           
                            <tr>
                            <td class="d-block text-dark font-14">Mobile</td>
                            <td class="font-weight-600">&nbsp;:&nbsp;</td>
                            <td class="text-dark font-14 font-weight-600"><span class="customer_mobile">{{$customer_mobile}}</span></td>
                            </tr>
                            <?php
                           
  if($returnsales[0]['customer_address_detail']!= null && $returnsales[0]['customer_address_detail']!= '' && $returnsales[0]['customer_address_detail']['customer_address']!= null)
  {
            $customer_address  =  $returnsales[0]['customer_address_detail']['customer_address'];
            ?>
            <tr>
            <td class="d-block text-dark font-14">Address</td>
            <td class="font-weight-600">&nbsp;:&nbsp;</td>
            <td class="text-dark font-14 font-weight-600"><span class="customer_address">{{$customer_address}}</span></td>
            </tr>
            <?php
  }
  else
  {
            $customer_address = '';
  }
  if($returnsales[0]['customer_address_detail']!= null && $returnsales[0]['customer_address_detail']!= '' && $returnsales[0]['customer_address_detail']['customer_gstin']!= null)
  {
            $customer_gstin  =  $returnsales[0]['customer_address_detail']['customer_gstin'];
            ?>
             <tr>
            <td class="d-block text-dark font-14">GSTIN</td>
            <td class="font-weight-600">&nbsp;:&nbsp;</td>
            <td class="text-dark font-14 font-weight-600"><span class="customer_gstin">{{$customer_gstin}}</span></td>
            </tr>
            <?php
  }
   else
  {
            $customer_gstin = '';
  }
 
  ?>
                            

                           
                            
                        </table>                                      
                        
           
                          
                    </div>
                    <div class="col-md-5 mb-30" style="width:50% !important;border:0px solid !important;font-size:16px;">
                        
                       <table style="float:right;">
                            <tr>
                            <td class="d-block text-dark font-14">Invoice No.</td>
                            <td class="font-weight-600">&nbsp;:&nbsp;</td>
                            <td class="text-dark font-14 font-weight-600 text-right"><span class="tinvoiceno">{{$returnsales[0]['sales_bill']['bill_no']}}</span></td>
                            </tr>
                            <tr>
                            <td class="d-block text-dark font-14">Invoice Date</td>
                            <td class="font-weight-600">&nbsp;:&nbsp;</td>
                            <td class="text-dark font-14 font-weight-600 text-right"><span class="invoicedate">{{$returnsales[0]['bill_date']}}</span></td>
                            </tr>
                            <tr>
                            
                            </tr>
                  
                        </table>   
                       
                    </div>
                </div>
            </div>                          
        
                       <tr style="background:#88c241;border-bottom:1px #999 solid;border:1px #f3f3f3 solid;">
                        <table  class="table tablesaw table-bordered table-hover table-striped mb-0" width="100%" cellpadding="6" border="0" frame="box"  style="border:1px solid #C0C0C0 !important;">
                            <thead>
                                <tr style="background:#88c241;border-bottom:1px #f3f3f3 solid;border-top:1px #f3f3f3 solid;">
                                    <th class="text-dark font-14 font-weight-600" style="width:3% !important;color:#fff !important;">Sr.No.</th>
                                    <th class="text-dark font-14 font-weight-600" style="width:9% !important;color:#fff !important;">Item Description</th>
                                    <th class="text-dark font-14 font-weight-600" style="width:9% !important;color:#fff !important;">Barcode</th>
                                    <th class="text-right text-dark font-14 font-weight-600" style="width:4% !important;color:#fff !important;">Qty</th>
                                    <th class="text-right text-dark font-14 font-weight-600" style="width:8% !important;color:#fff !important;">SellingPrice</th>
                                      <th class="text-right text-dark font-14 font-weight-600" style="width:7% !important;color:#fff !important;">Disc. Amt.</th>
                                      <th class="text-right text-dark font-14 font-weight-600" style="width:10% !important;color:#fff !important;">Taxable Amt.</th>
                                      <?php
                                      if($tax_type==1)
                                      {
                                        ?>
                                          <th class="text-right text-dark font-14 font-weight-600" style="width:5% !important;color:#fff !important;">{{$taxname}}%</th>
                                          <th class="text-right text-dark font-14 font-weight-600" style="width:7% !important;color:#fff !important;">{{$taxname}} Amt.</th>
                                        <?php
                                      }
                                      else
                                      {
                                        ?>
                                         <th class="text-right text-dark font-14 font-weight-600" style="width:5% !important;color:#fff !important;">CGST%</th>
                                          <th class="text-right text-dark font-14 font-weight-600" style="width:7% !important;color:#fff !important;">CGST Amt.</th>
                                           <th class="text-right text-dark font-14 font-weight-600" style="width:5% !important;color:#fff !important;">SGST%</th>
                                          <th class="text-right text-dark font-14 font-weight-600" style="width:7% !important;color:#fff !important;">SGST Amt.</th>
                                        <?php
                                      }
                                      ?>
                                   
                                    <th class="text-right text-dark font-14 font-weight-600" style="width:11% !important;color:#fff !important;">Total Amt.</th>
                                </tr>
                                </thead>
                                <tbody id="productdetails">
                                  
                                  <?php
                                  $totsellingafterdiscount = 0;
                                  $totcgstamount = 0;
                                  $totsgstamount=0;
                                  $totigstamount=0;
                                    if($returnsales[0]['return_product_detail']!='')
                                    {
                                 
                                 foreach($returnsales[0]['return_product_detail'] as $saleskey=>$sales_value)
                                 {
                                    if($sales_value['sellingprice_before_discount']!=0)
                                    {
                                    if ($saleskey % 2 == 0) {
                                            $tblclass = 'even';
                                        } else {
                                            $tblclass = 'odd';
                                        }
                                        $sr   =  $saleskey + 1;
                                        $totaldiscount   =   $sales_value['discount_amount'] + $sales_value['overalldiscount_amount'];
                                        $totsellingafterdiscount  +=  $sales_value['sellingprice_after_discount'];
                                        $totcgstamount            +=  $sales_value['cgst_amount'];
                                        $totsgstamount            +=  $sales_value['sgst_amount'];
                                        $totigstamount            +=  $sales_value['igst_amount'];
                                        if($sales_value['product']['supplier_barcode']!='' || $sales_value['product']['supplier_barcode']!=NULL)
                                        {
                                           $barcode = $sales_value['product']['supplier_barcode'];
                                        }
                                        else
                                        {
                                          $barcode = $sales_value['product']['product_system_barcode'];
                                        }
                                    ?>
                                    <tr class="<?php echo $tblclass ?>" style="border-bottom:1px solid #C0C0C0 !important;">

                                      <td style="font-size:14px !important;">{{$sr}}</td>
                                      <td style="font-size:14px !important;">{{$sales_value['product']['product_name']}}</td>
                                      <td style="font-size:14px !important;">{{$barcode}}</td>
                                      <td style="text-align:right !important;font-size:14px !important;">{{$sales_value['qty']}}</td>
                                      <td style="text-align:right !important;font-size:14px !important;">{{number_format($sales_value['sellingprice_before_discount'],2)}}</td>
                                      <td style="text-align:right !important;font-size:14px !important;">{{number_format($totaldiscount,2)}}</td>
                                      <td style="text-align:right !important;font-size:14px !important;">{{number_format($sales_value['sellingprice_after_discount'],2)}}</td>
                                      <?php
                                      if($tax_type==1)
                                      {
                                        ?>

                                        <td style="text-align:right !important;font-size:14px !important;">{{$sales_value['igst_percent']}}</td>
                                        <td style="text-align:right !important;font-size:14px !important;">{{number_format($sales_value['igst_amount'],2)}}</td>
                                        <?php
                                      }
                                      else
                                      {
                                        ?>
                                         <td style="text-align:right !important;font-size:14px !important;">{{$sales_value['cgst_percent']}}</td>
                                        <td style="text-align:right !important;font-size:14px !important;">{{number_format($sales_value['cgst_amount'],2)}}</td>
                                        <td style="text-align:right !important;font-size:14px !important;">{{$sales_value['sgst_percent']}}</td>
                                        <td style="text-align:right !important;font-size:14px !important;">{{number_format($sales_value['sgst_amount'],2)}}</td>
                                        <?php
                                      }
                                      ?>
                                     
                                      <td style="text-align:right !important;font-size:14px !important;">{{number_format($sales_value['total_amount'],$nav_type[0]['decimal_points'])}}</td>
                                    </tr>

                              
                                <?php
                              }
                              }
                              }
                                ?>

                                </tbody>
                                <tfoot style="border-bottom:1px solid #C0C0C0 !important;border-top:1px solid #C0C0C0 !important;">
                                 <tr>
                                   
                                    <th colspan="3" class="text-right text-dark font-14 font-weight-600">Total</th>
                                    <th class="text-right text-dark font-14 font-weight-600">{{number_format($returnsales[0]['total_qty'],2)}}</th>
                                    <th class="text-right text-dark font-14 font-weight-600"></th>
                                    <th class="text-right text-dark font-14 font-weight-600"></th>
                                     <th class="text-right text-dark font-14 font-weight-600">{{number_format($totsellingafterdiscount,2)}}</th>
                                      <?php
                                      if($tax_type==1)
                                      {
                                        ?>
                                         
                                          <th class="text-right font-weight-600"></th>
                                          <th class="text-right text-dark font-14 font-weight-600">{{number_format($totigstamount,2)}}</th>
                                        <?php
                                      }
                                      else
                                      {
                                          ?>
                                           <th class="text-right font-weight-600"></th>
                                           <th class="text-right text-dark font-14 font-weight-600">{{number_format($totcgstamount,2)}}</th>
                                           <th class="text-right font-weight-600"></th>
                                           <th class="text-right text-dark font-14 font-weight-600">{{number_format($totsgstamount,2)}}</th>
                                          <?php
                                      }   
                                      ?>                                    
                                     
                                    <th class="text-right text-dark font-18 font-weight-600"><?php if($currency_title=='INR')
                                              {
                                                ?>&#x20b9<?php
                                              } else {
                                                echo $currency_title;
                                              }  ?> <span id="grandtotal">{{number_format($returnsales[0]['total_bill_amount'],$nav_type[0]['decimal_points'])}}</span></th>
                                    
                                  
                                </tr>
                            </tfoot>
                        </table>


            <div class="invoice-from-wrap" style="margin:45px 0 0 0;">
                <div class="row">
                    <div class="col-md-8 mb-20" style="width:60% !important;border:0px solid !important;">

                       
                    </div>
                    
                    <div class="col-md-4 mb-20" style="width:40% !important;border:0px solid !important;font-size:16px;">
                        <table style="float:right;font-size:16px;">
                            <tr>
                            <td colspan="3" class="text-right text-dark" style="font-size:14px;">Payment Methods</td>                                      
                            </tr>
                            <tbody id="paymentdetails">
                              <?php
                                if($returnsales[0]['return_bill_payment']!='')
                                {
                              ?>
                              @foreach($returnsales[0]['return_bill_payment'] AS $salespayment_key=>$salespayment_value)
                                <tr>
                                <td style="text-align:right !important;font-size:14px !important;" class="text-dark">{{$salespayment_value['payment_method'][0]['payment_method_name']}} </td>
                                <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                <td style="text-align:right !important;font-size:14px !important;" class="text-dark font-weight-600"><?php if($currency_title=='INR')
                                              {
                                                ?>&#x20b9<?php
                                              } else {
                                                echo $currency_title;
                                              }  ?> {{number_format($salespayment_value->total_bill_amount,$nav_type[0]['decimal_points'])}}</td>
                                </tr>    
                                <tr>
                                <td style="text-align:right !important;font-size:14px !important;" class="text-dark">Credit Note No. </td>
                                <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                <td style="text-align:right !important;font-size:14px !important;" class="text-dark font-weight-600"> {{$salespayment_value['customer_creditnote']['creditnote_no']}}</td>
                                </tr>                                       
                                @endforeach                                         
                             <?php
                           }
                             ?>
                           

                           </tbody>
                  
                        </table> 
                        
                    </div>
                    
                </div>
                 
            <br>
            <br>
            
            </div>
            
                
           
     
    </div>
</div>
</div>

<script type="text/javascript">
$(document).ready(function(e){

    $('.invoiceno').html(<?php echo json_encode($returnsales[0]['sales_bill']['bill_no']);?>);
    var rbill_no                   =     $('#rfetchedbillno').val();  
    var rmaxid                    =     $('#rmaxid').val();   
    var rminid                    =     $('#rminid').val();  

    if(Number(rbill_no) == Number(rminid))
    {
            $('#rpreviousinvoice').prop('disabled', true);
            $('#rnextinvoice').prop('disabled', false);
            return false;
    }
    if(Number(rbill_no) == Number(rmaxid))
    {
            $('#rpreviousinvoice').prop('disabled', false);
             $('#rnextinvoice').prop('disabled', true);
            return false;
    }


});
</script>
<?php
}
?>