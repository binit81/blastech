<?php
/**
 * Created by PhpStorm.
 * User: Hemaxi
 * Date: 26/3/19
 * Time: 10:18 AM
 */

if(sizeof($sales) != 0)
{
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

<form id="popupbills">
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
      <input type="hidden" id="maxid" value="{{$maxsales_id}}">
      <input type="hidden" id="minid" value="{{$minsales_id}}">
      <input type="hidden" id="fetchedbillno" value="{{$sales[0]['sales_bill_id']}}">
      <textarea id="encryptbillid" style="display:none;">{{encrypt($sales[0]['sales_bill_id'])}}</textarea>
      <input type="hidden" id="returnid" value="{{$sales[0]['return_bill']['sales_bill_id']}}">

      <table style="float:left;">
      <?php
      if($sales[0]['customer']!= null && $sales[0]['customer']!= '' && $sales[0]['customer']['customer_name']!= null)
      {
      $customer_name  =  $sales[0]['customer']['customer_name'];
      ?>
      <tr>
      <td class="d-block text-dark font-14">Customer</td>
      <td class="font-weight-600">&nbsp;:&nbsp;</td>
      <td class="text-dark font-14 font-weight-600"><span class="customer_name">{{$customer_name}}</span></td>
      </tr>
      <?php
      }
      if($sales[0]['customer']!= null && $sales[0]['customer']!= '' && $sales[0]['customer']['customer_mobile']!= null)
      {
      $customer_mobile  =  $sales[0]['customer']['customer_mobile'];
      ?>
      <tr>
      <td class="d-block text-dark font-14">Mobile</td>
      <td class="font-weight-600">&nbsp;:&nbsp;</td>
      <td class="text-dark font-14 font-weight-600"><span class="customer_mobile">{{$customer_mobile}}</span></td>
      </tr>
      <?php
      }
      ?>



      <?php

      if($sales[0]['customer_address_detail']!= null && $sales[0]['customer_address_detail']!= '' && $sales[0]['customer_address_detail']['customer_address']!= null)
      {
      $customer_address  =  $sales[0]['customer_address_detail']['customer_address'];
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
      if($sales[0]['customer_address_detail']!= null && $sales[0]['customer_address_detail']!= '' && $sales[0]['customer_address_detail']['customer_gstin']!= null)
      {
      $customer_gstin  =  $sales[0]['customer_address_detail']['customer_gstin'];
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
      <td class="text-dark font-14 font-weight-600 text-right"><span class="tinvoiceno">{{$sales[0]['bill_no']}}</span></td>
      </tr>
      <tr>
      <td class="d-block text-dark font-14">Invoice Date</td>
      <td class="font-weight-600">&nbsp;:&nbsp;</td>
      <td class="text-dark font-14 font-weight-600 text-right"><span class="invoicedate">{{$sales[0]['bill_date']}}</span></td>
      </tr>
      <tr>

      </tr>

      </table>   

      </div>
      </div>
      </div>                          

      <!--  <tr style="background:#88c241;border-bottom:1px #999 solid;border:1px #f3f3f3 solid;"> -->
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
      if($sales[0]['sales_product_detail']!='')
      {

      foreach($sales[0]['sales_product_detail'] as $saleskey=>$sales_value)
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
      $totigstamount          +=  $sales_value['igst_amount'];
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

      <td style="text-align:right !important;font-size:14px !important;">{{number_format($sales_value['total_amount'],2)}}</td>
      </tr>


      <?php
      }
      }
      ?>

      </tbody>
      <tfoot style="border-bottom:1px solid #C0C0C0 !important;border-top:1px solid #C0C0C0 !important;">
      <tr>

      <th colspan="3" class="text-right text-dark font-14 font-weight-600">Total</th>
      <th class="text-right text-dark font-14 font-weight-600">{{round($sales[0]['total_qty'],2)}}</th>
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
                                              }  ?>
                                               <span id="grandtotal">{{number_format($sales[0]['total_bill_amount'],$nav_type[0]['decimal_points'])}}</span></th>


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
      if($sales[0]['sales_bill_payment_detail']!='')
      {
      ?>
      @foreach($sales[0]['sales_bill_payment_detail'] AS $salespayment_key=>$salespayment_value)
      <tr>
      <td style="text-align:right !important;font-size:14px !important;" class="text-dark">{{$salespayment_value['payment_method'][0]['payment_method_name']}} </td>
      <td class="font-weight-600">&nbsp;:&nbsp;</td>
      <td style="text-align:right !important;font-size:14px !important;" class="text-dark font-weight-600"><?php if($currency_title=='INR')
                                              {
                                                ?>&#x20b9<?php
                                              } else {
                                                echo $currency_title;
                                              }  ?>
                                              {{number_format($salespayment_value->total_bill_amount,$nav_type[0]['decimal_points'])}}</td>
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


    $('.invoiceno').html(<?php echo json_encode($sales[0]['bill_no']);?>);
    var bill_no                 =     $('#fetchedbillno').val();  
    var maxid                   =     $('#maxid').val();   
    var minid                   =     $('#minid').val(); 
    var encryptbillid           =     $('#encryptbillid').val();
    encryptbillid               =     "'"+encryptbillid+"'";
    


    if(Number(bill_no) == Number(minid))
    {
            $('#previousinvoice').prop('disabled', true);
            $('#nextinvoice').prop('disabled', false);
            // return false;
    }
    if(Number(bill_no) == Number(maxid))
    {
            $('#previousinvoice').prop('disabled', false);
             $('#nextinvoice').prop('disabled', true);
            // return false;
    }
   
    var printhtml = '';
    if(billprint_type==1)
    {
          printhtml = '<a href="{{URL::to('print_bill')}}?id={{encrypt($sales[0]['sales_bill_id'])}}" style="text-decoration:none !important;" target="_blank" title="A4/A5 Print"><i class="fa fa-print" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;"></i></a>';
    }
    else
    { 
        printhtml = '<a href="{{URL::to("thermalprint_bill")}}?id={{encrypt($sales[0]['sales_bill_id'])}}" style="text-decoration:none !important;" target="_blank" title="Thermal Print"><i class="fa fa-print" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;"></i></a>';

    }
    
   $('.editdeleteIcons').html('<a class="edit_bill" title="Edit"><i class="fa fa-edit" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;"></i></a>'+printhtml+'<a id="deletebill_{{$sales[0]['sales_bill_id']}}" onclick="return deletebill(this);" style="text-decoration:none !important;" target="_blank" title="Delete"><i class="fa fa-trash" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;"></i></a>');

   $('.edit_bill').click(function(e){
  
    var encryptbillid           =     $('#encryptbillid').val();
    var returnid                =     $('#returnid').val();
    encryptbillid               =     "'"+encryptbillid+"'";
    if(returnid == '' || returnid==null)
    {
        edit_hotelbill(encryptbillid);
    }
    else
    {
        notedit_hotelbill(encryptbillid);
        
    }
});


});
</script>
</form>
<?php
}
?>