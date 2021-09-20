    <!DOCTYPE html>
<!-- 
Template Name: Mintos - Responsive Bootstrap 4 Admin Dashboard Template
Author: Hencework
Contact: https://hencework.ticksy.com/

License: You must have a valid license purchased only from templatemonster to legally use the template for your project.
-->
<html lang="en">


<!-- Mirrored from hencework.com/theme/mintos/dashboard1.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 05 Mar 2019 07:02:35 GMT -->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<title>Print Bill</title>
<meta name="description" content="A responsive bootstrap 4 admin dashboard template by hencework" />
<meta name="csrf-token" content="{{ csrf_token() }}" />

<!-- Favicon -->
<link rel="shortcut icon" href="favicon.ico">
<link rel="icon" href="favicon.ico" type="image/x-icon">

<!-- vector map CSS -->

<!-- Custom CSS -->
<!-- <link href="{{URL::to('/')}}/public/dist/css/style.css" rel="stylesheet" type="text/css">  -->   
<!--<link href="{{URL::to('/')}}/public/template/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"> -->

<link href="{{URL::to('/')}}/public/dist/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript">window.print();</script>
<style type="text/css">
.text-right
{
    text-align:right;
}
.font-weight-600
{
    font-weight:600;
}
.underline{
    border-bottom:1px solid #C0C0C0;
}
.upperline{
    border-top:1px solid #C0C0C0;
}
.bottomdarkline{
    border-bottom:1px solid;
}
.upperdarkline
{
  border-top:1px solid;
}
.page-header, .page-header-space {
  height: 170px;
}

.page-footer, .page-footer-space {
  height: 130px;

}

.page-footer {
  position: fixed;
  bottom: 0;
  width: 100%;
  border-top: 1px solid white; /* for demo */
  /*background: yellow; /* for demo */*/
}

.page-header {
  position: fixed;
  top: 0mm;
  width: 100%;
  border-bottom: 1px solid white; /* for demo */
  /*background: yellow; /* for demo */*/
}

/*.page {
  page-break-after: always;
}*/

@page {
  margin: 20mm
}

@media print {
   thead {display: table-header-group;} 
   tfoot {display: table-footer-group;}
   
   button {display: none;}
   
   body {margin: 0;}
}
</style>


<body>

  <div class="page-header" style="text-align: center">
    <div style="width:100%;float:left;border:0px solid;">
                    <span style="font-size:24px;"><center>TAX INVOICE</center></span>
                               
                                    <div style="width:65% !important;border:0 solid !important;font-size:16px;float:left;">
                                        <div style="width:38% !important;border:0px solid !important;font-size:16px;float:left;">
                                        <img class="img-fluid invoice-brand-img d-block mb-20 pull-left" src="{{URL::to('/')}}/public/dist/img/rcslogo1.png" width="100%" alt="logo" style="margin:0 5px 0 0;border:0px solid;"/>
                                    </div>
                                    <div style="width:60% !important;border:0px solid !important;font-size:16px;float:left;text-align:left;margin:0 0 0 5px;">
                                        <span class="d-block font-weight-600" style="font-size:24px;"><span class="pl-10 text-dark">{{$billingdata->company->company_name}}</span></span><br>
                                        <span class="d-block font-weight-600"><span class="pl-10 text-dark">{{strip_tags($billingdata->company->company_address)}}</span></span><br>
                                        <span class="d-block font-weight-600"><span class="pl-10 text-dark">{{strip_tags ($billingdata->company->company_area)}} {{$billingdata->company->company_city}} - {{$billingdata->company->company_pincode}}</span></span><br>
                                        <?php
                                        $company_mobile_code =  explode(',',$billingdata->company->company_mobile_dial_code);
                                        if($billingdata->company->company_mobile!='' || $billingdata->company->company_mobile!=null)
                                        {
                                        ?>
                                         <span class="d-block font-weight-600"><span class="pl-10 text-dark">({{$company_mobile_code[0]}}){{$billingdata->company->company_mobile}}</span></span><br>
                                         <?php
                                          }
                                         ?>
                                          <span class="font-weight-600"><span class="pl-10 text-dark">{{$billingdata->company->company_email}}</span></span><br>

                                     
                                    </div>
                                </div>
                                    <?php
                                        $whtapp_mobile_code =  explode(',',$billingdata->company->whatsapp_mobile_dial_code);
                                    ?>
                                    <div style="text-align:right !important;width:34% !important;border:0px solid !important;font-size:16px;float:left;">
                                        <table>
                                            <tr>
                                            <td colspan="3" class="font-weight-600">{{strip_tags ($billingdata->company->website)}}</td>
                                            
                                            </tr>
                                             <?php 
                                             if(isset($billingdata) && isset($billingdata['company'])
                                                 && $billingdata['company']['whatsapp_mobile_number']!=NULL)   
                                             {
                                                ?>
                                            <tr>
                                            <td class="d-block font-weight-600"><a class="fa fa-whatsapp"></a></td>
                                            <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                            <td class="font-weight-600">({{$whtapp_mobile_code[0]}}){{$billingdata->company->whatsapp_mobile_number}}</td>
                                            </tr>
                                            <?php                                            
                                            
                                            } 
                                             
                                           if(isset($billingdata) && isset($billingdata['company'])
                                                 && $billingdata['company']['facebook']!=NULL)   
                                             {
                                                ?>
                                                <tr>
                                                <td class="d-block font-weight-600"><a class="fa fa-facebook"></a></td>
                                                <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                                <td class="font-weight-600">{{$billingdata->company->facebook}}</td>
                                                </tr><?php
                                              
                                            
                                            } 
                                             
                                            if(isset($billingdata) && isset($billingdata['company'])
                                                 && $billingdata['company']['instagram']!=NULL)   
                                              {
                                                ?>
                                              <tr>                                          
                                                <td class="d-block font-weight-600"><a class="fa fa-instagram"></a></td>
                                                <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                                <td class="font-weight-600">{{$billingdata->company->instagram}}</td>                                              
                                               </tr>
                                               <?php
                                              
                                            
                                            } 
                                             if(isset($billingdata) && isset($billingdata['company'])
                                                 && $billingdata['company']['pinterest']!=NULL)   
                                              {
                                                ?>
                                             <tr>
                                                <td class="d-block font-weight-600"><a class="fa fa-pinterest"></a></td>
                                                <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                                <td class="font-weight-600">{{$billingdata->company->pinterest}}</td>                                             
                                            </tr>
                                             <?php
                                              
                                            
                                            } 
                                            ?>
                                        </table>
                                        
                                       
                                    </div>
                                
                            </div>
                           <?php
                           $customer_country   =  $billingdata['customer_address_detail']['country_name']['country_name'];
                                    
                                    
                                    ?>  
                                </div>
    
  </div>

  <div class="page-footer">
    <div style="width:100% !important;border:0px solid !important;margin:0px 0 0 0;float:left;">
                <div style="width:70% !important;border:0px solid !important;margin:0px 0 0 0;float:left;">
                                        <?php
                                        if(isset($billingdata) && isset($billingdata['company'])&& $billingdata['company']['terms_and_condition']!=NULL)
                                        {
                                        ?>
                                        <table style="float:left;font-size:12px;">
                                            <tr>
                                            <td colspan="3" class="font-weight-600" style="font-size:14px;">TERMS & CONDITIONS</td>
                                           </tr>
                                            <tr>
                                            <td colspan="3" class="font-weight-600"><?php echo html_entity_decode($billingdata->company->terms_and_condition); ?></td>
                                           </tr>                                          
                                  
                                        </table> 
                                      <?php
                                        }
                                      ?>
                                     
                                    </div>
                                   
                                    <div style="width:29% !important;border:0px solid !important;float:right;text-align:right;">
                                        <?php
                                        if(isset($billingdata->company->authorized_signatory_for) && $billingdata->company->authorized_signatory_for!='')
                                        {
                                            ?>
                                             <span class="d-block font-weight-600" style="font-size:16px;">For {{$billingdata->company->authorized_signatory_for}}</span><br><br>
                                             <span class="d-block font-weight-600" style="font-size:16px;">Authorized Signatory</span>
                                            <?php
                                        }
                                        ?>
                                    
                                    </div>
                       <br clear="all">
                               <center><span class="d-block font-weight-600" style="font-size:18px;">{{strip_tags($billingdata->company->additional_message)}}</span></center>
                      <!--       </div>
                        </div> -->
                    </div>
   </div>
  </div>

   <table width="100%">

    <thead>
      <tr>
        <td>
          <!--place holder for the fixed-position header-->
          <div class="page-header-space"></div>
        </td>
      </tr>
    </thead>

    <tbody>
      <tr>
        <td>
          <!--*** CONTENT GOES HERE ***-->
          <!-- <div class="page">PAGE 1</div>
          <div class="page">PAGE 2</div> -->
          <div class="page">
            <!--*** CONTENT GOES HERE ***-->

                <div style="width:100%;border:0px solid;float:left;margin:5px 0 0 0;">
                                    <div style="width:50% !important;border:0px solid !important;font-size:16px;float:left;"> 
                                        <table style="float:left;">
                                            <?php
                                             if(isset($billingdata) && isset($billingdata->customer) && $billingdata['customer']['customer_name']!=NULL)   
                                             {
                                                ?>
                                            <tr>
                                            <td colspan="3"><span class="d-block font-weight-600" style="font-size:20px;text-align:left;">Customer Details</span></td>    
                                            </tr>
                                            <tr>
                                            <td class="d-block font-weight-600">Customer</td>
                                            <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                            <td class="font-weight-600">{{$billingdata->customer->customer_name}}</td>
                                            </tr>
                                            <?php
                                            }

                                             if(isset($billingdata) && isset($billingdata->customer) && $billingdata['customer']['customer_mobile']!=NULL)   
                                             {
                                            ?>
                                            <tr>
                                            <td class="d-block font-weight-600">Mobile</td>
                                            <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                            <td class="font-weight-600">{{$billingdata->customer->customer_mobile}}</td>
                                            </tr>
                                            <?php
                                            }
                                            if(isset($billingdata) && isset($billingdata['customer_address_detail'])
                                                && $billingdata['customer_address_detail']['customer_address'] != NULL)
                                            {
                                                
                                                $customer_address = $billingdata['customer_address_detail']['customer_address'].' ,'.$customer_country; 
                                                ?>
                                                 <tr>
                                                <td class="d-block font-weight-600">Address</td>
                                                <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                                <td class="font-weight-600">{{$customer_address}}</td>
                                                </tr>
                                                <?php
                                            } 
                                            ?>
                                            

                                            <?php
                                           
                                            if(isset($billingdata) && isset($billingdata['customer_address_detail'])
                                            && $billingdata['customer_address_detail']['customer_gstin']!=NULL)
                                            {
                                            ?>
                                            
                                            <tr>
                                            <td class="d-block font-weight-600">GSTIN</td>
                                            <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                            <td class="font-weight-600">{{$billingdata['customer_address_detail']['customer_gstin']}}</td>
                                            </tr>
                                            <?php
                                            }
                                            ?>
                                  
                                        </table>                                      
                                        
                           
                                          
                                    </div>
                                    <div style="width:49% !important;border:0px solid !important;font-size:16px;float:right;">
                                        <?php

                                        
                                        $invoicedate        =         date("d-m-Y",strtotime($billingdata->bill_date));

                                        if(isset($billingdata) && isset($billingdata['customer_address_detail'])
                                            && isset($billingdata['customer_address_detail']['customer_gstin']))
                                        {
                                            $customer_gstin = $billingdata['customer_address_detail']['customer_gstin']; 
                                        } else {
                                            $customer_gstin = '';
                                        }
                                        ?>
                                       <table style="float:right;">
                                            <tr>
                                            <td class="d-block font-weight-600">Invoice No.</td>
                                            <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                            <td class="font-weight-600 text-right">{{$billingdata->bill_no}}</td>
                                            </tr>
                                            <tr>
                                            <td class="d-block font-weight-600">Invoice Date</td>
                                            <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                            <td class="font-weight-600 text-right">{{$invoicedate}}</td>
                                            </tr>
                                            <tr>
                                            <td class="d-block font-weight-600">GSTIN</td>
                                            <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                            <td class="font-weight-600 text-right">{{$billingdata->company->gstin}}</td>
                                            </tr>
                                           
                                            <tr>
                                            <td class="d-block font-weight-600">Place</td>
                                            <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                            <td class="font-weight-600 text-right">{{$billingdata->company->state_name->state_name}}</td>
                                            </tr>
                                             <?php
                                             if(isset($billingdata) && isset($billingdata['reference'])
                                            && isset($billingdata['reference']['reference_name']))
                                             {
                                            ?>
                                            <tr>
                                            <td class="d-block font-weight-600">Reference</td>
                                            <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                            <td class="font-weight-600 text-right">{{$billingdata['reference']['reference_name']}}</td>
                                            </tr>
                                            <?php
                                             }
                                            ?>
                                        </table>   
                                       
                                    </div>
                               
                            </div>                          
                          
         <?php
        if($tax_type==1)
        {
            ?>
            <table width="100%" cellpadding="6" frame="box" border="1" style="border-collapse:collapse;float:left;">
                                    <thead>
                                        <tr style="background:#999;border-bottom:1px #999 solid;border-top:1px #999 solid;">
                                           
                                            <?php
                                             if($billingdata['overalldiscount']!=0 && $billingdata['overalldiscount']!='' && $billingdata['overalldiscount']!=null)
                                             {
                                                 // <th class="text-right text-dark font-12 font-weight-600" style="width:6% !important;">MRP</th>
                                                    echo '<th class="text-dark font-12" style="width:8% !important;">Sr No.</th>
                                                    <th class="text-dark font-12" style="width:16% !important;">Item Description</th>
                                                    <th class="text-dark font-12" style="width:8% !important;">Barcode</th>
                                                   
                                                    <th class="text-right text-dark font-12" style="width:8% !important;">SellingPrice</th>
                                                    <th class="text-right text-dark font-12" style="width:6% !important;">Qty</th>
                                                    <th class="text-right text-dark font-12" style="width:7% !important;">Disc. Amt.</th>
                                                    <th class="text-right text-dark font-12" style="width:10% !important;">Taxable<br>Amount</th>
                                                     <th class="text-right text-dark font-12" style="width:7% !important;">'.$taxname.'%</th>
                                                    <th class="text-right text-dark font-12" style="width:10% !important;">'.$taxname.' Amt.</th>
                                                    <th class="text-right text-dark font-12" style="width:17% !important;">Total Amount</th>';
                                                }
                                                else
                                                {
                                                    // <th class="text-right text-dark font-12 font-weight-600" style="width:6% !important;">MRP</th>

                                                    echo '<th class="text-dark font-12" style="width:8% !important;">Sr No.</th>
                                                    <th class="text-dark font-12" style="width:16% !important;">Item Description</th>
                                                    <th class="text-dark font-12" style="width:10% !important;">Barcode</th>
                                                    <th class="text-right text-dark font-12" style="width:13% !important;">SellingPrice</th>
                                                    <th class="text-right text-dark font-12" style="width:6% !important;">Qty</th>
                                                    <th class="text-right text-dark font-12" style="width:13% !important;">Taxable<br>Amount</th>
                                                     <th class="text-right text-dark font-12" style="width:7% !important;">'.$taxname.'%</th>
                                                    <th class="text-right text-dark font-12" style="width:10% !important;">'.$taxname.' Amt.</th>
                                                    <th class="text-right text-dark font-12" style="width:17% !important;">Total Amount</th>';
                                                }
                                                ?>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $ttaldiscount = 0;
                                                $totalcgst = 0;
                                                $totalsgst = 0;
                                                $totaligst = 0;
                                                $tottaxable = 0;
                                                foreach($billingproductdata AS $billingproduct_key=>$billingproduct_value)
                                                {
                                               
                                                    if ($billingproduct_key % 2 == 0) {
                                                            $tblclass = '#fff;';
                                                        } else {
                                                            $tblclass = '#f3f3f3;';
                                                        } 


                                                $totaldiscount   =   $billingproduct_value->discount_amount + $billingproduct_value->overalldiscount_amount;

                                                $sno  =   $billingproduct_key + 1;
                                                $ttaldiscount  +=  $totaldiscount;
                                                
                                                $totaligst  +=  $billingproduct_value->igst_amount;
                                                $tottaxable +=  $billingproduct_value->sellingprice_afteroverall_discount;
                                                if($billingproduct_value['product']['supplier_barcode']!='' && $billingproduct_value['product']['supplier_barcode']!=null)
                                                {
                                                  $barcode     =     $billingproduct_value['product']['supplier_barcode'];
                                                }
                                                else
                                                {
                                                  $barcode     =     $billingproduct_value['product']['product_system_barcode'];
                                                }
                                                ?>
                                                <tr style="border-bottom:1px solid #C0C0C0 !important;">
                                                    <td style="height:50px !important;">{{$sno}}</td>
                                                    <td><?php echo html_entity_decode($billingproduct_value->product->product_name)?></td>
                                                    <td>{{$barcode}}</td>
                                                    <!-- <td class="text-right">{{round($billingproduct_value->mrp)}}</td> -->
                                                    <td class="text-right">{{round($billingproduct_value->sellingprice_before_discount,2)}}</td>
                                                    
                                                    <td class="text-right">{{round($billingproduct_value->qty)}}</td>
                                                    <?php
                                                     if($billingdata['overalldiscount']!=0 && $billingdata['overalldiscount']!='' && $billingdata['overalldiscount']!=null)
                                                     {
                                                        ?>
                                                        <td class="text-right">{{round($totaldiscount,2)}}</td>
                                                       
                                                        <?php
                                                     }
                                                    ?>
                                                    <td class="text-right totcgstamt">{{round($billingproduct_value->sellingprice_afteroverall_discount,2)}}</td>
                                                    <td class="text-right">{{$billingproduct_value->igst_percent}}</td>
                                                    <td class="text-right totsgstamt">{{round($billingproduct_value->igst_amount,2)}}</td>
                                                    <td class="text-right tottotamt">{{round($billingproduct_value->total_amount,2)}}</td>
                                                    
                                                </tr>

                                               
                                                <?php
                                            }
                                                
                                                $rest   =   6 - $productcount;
                                                for($x=1; $x<=$rest; $x++)
                                                {
                                                    ?>
                                                <tr>
                                                    <td class="text-dark" style="height:50px !important;">&nbsp;</td>
                                                    <td class="text-dark"></td>
                                                    <td class="text-dark"></td>
                                                    <!-- <td class="text-dark"></td> -->
                                                    <td class="text-right text-dark"></td>
                                                    <?php
                                                     if($billingdata['overalldiscount']!=0 && $billingdata['overalldiscount']!='' && $billingdata['overalldiscount']!=null)
                                                     {
                                                            echo '<td class="text-right text-dark"></td>';
                                                     }
                                                     ?>
                                                    
                                                    <td class="text-right text-dark"></td>
                                                    <td class="text-right text-dark totcgstamt"></td>
                                                    <td class="text-right text-dark"></td>
                                                    <td class="text-right text-dark totsgstamt"></td>
                                                    <td class="text-right text-dark tottotamt"></td>
                                                    
                                                </tr>


                                                <?php
                                                 }



                                                    ?>              
                                               
                                           <!--  <tfoot style="border-bottom:1px solid #999 !important;border-top:1px solid #999 !important;"> -->
                                                <tr>
                                                    
                                                     <th colspan="4" class="text-right font-14 font-weight-600">Total</th>
                                                      <th class="text-right text-dark font-14 font-weight-600">{{round($billingdata->total_qty)}}</th>
                                                      <?php
                                                     if($billingdata['overalldiscount']!=0 && $billingdata['overalldiscount']!='' && $billingdata['overalldiscount']!=null)
                                                     {
                                                        ?>
                                                         <th class="text-right text-dark font-14 font-weight-600">{{round($ttaldiscount,2)}}</th>
                                                        <?php
                                                     }

                                                    ?>
                                                    
                                                     <th class="text-right text-dark font-14 font-weight-600">{{round($tottaxable,2)}}</th>
                                                    <th></th>
                                                    <th class="text-right text-dark font-14 font-weight-600">{{round($totaligst,2)}}</th>
                                                    <th class="text-right text-dark font-18 font-weight-600"><?php
                                              if($currency_title=='INR')
                                              {
                                                ?>&#x20b9<?php
                                              }
                                              else
                                              {
                                                echo $currency_title;
                                              }
                                              ?> {{round($billingdata->total_bill_amount,$nav_type[0]['decimal_points'])}}</th>
                                                    
                                                  
                                                </tr>
                                         <!--    </tfoot> -->
                                          </tbody>
                                      </table>
            <?php
        }
        else
        {

       

                            if(($billingdata->state_id)==($billingdata->company->state_id))
                            {

                          ?>
                                          <table width="100%" cellpadding="6" frame="box" border="1" style="border-collapse:collapse;float:left;">
                                            <thead>
                                                    <tr style="background:#999;border-bottom:1px #999 solid;border-top:1px #999 solid;">
                                                    
                                                     <?php
                                                     if($billingdata['overalldiscount']!=0 && $billingdata['overalldiscount']!='' && $billingdata['overalldiscount']!=null)
                                                     {
                                                        echo '
                                                        <th class="text-dark font-12 font-weight-600" style="width:5% !important;">Sr.<br>No.</th>
                                                        <th class="text-dark font-12 font-weight-600" style="width:9% !important;">Item Description</th>
                                                        <th class="text-dark font-12 font-weight-600" style="width:9% !important;">Barcode</th>
                                                        <th class="text-right text-dark font-12 font-weight-600" style="width:6% !important;">MRP</th>
                                                        <th class="text-right text-dark font-12 font-weight-600" style="width:10% !important;">SellingPrice</th>
                                                        <th class="text-right text-dark font-12 font-weight-600" style="width:4% !important;">Qty</th>
                                                        <th class="text-right text-dark font-12 font-weight-600" style="width:7% !important;">Disc. Amt.</th>
                                                        <th class="text-right text-dark font-12 font-weight-600" style="width:7% !important;">Taxable Amount</th>
                                                        <th class="text-right text-dark font-12 font-weight-600" style="width:5% !important;">CGST%</th>
                                                        <th class="text-right text-dark font-12 font-weight-600" style="width:7% !important;">CGST Amt.</th>
                                                        <th class="text-right text-dark font-12 font-weight-600" style="width:5% !important;">SGST%</th>
                                                        <th class="text-right text-dark font-12 font-weight-600" style="width:7% !important;">SGST Amt.</th>
                                                        <th class="text-right text-dark font-12 font-weight-600" style="width:11% !important;">Total Amount</th>';
                                                     }
                                                     else
                                                     {
                                                        echo '
                                                        <th class="text-dark font-12 font-weight-600" style="width:5% !important;">Sr.<br>No.</th>
                                                        <th class="text-dark font-12 font-weight-600" style="width:15% !important;">Item Description</th>
                                                        <th class="text-dark font-12 font-weight-600" style="width:9% !important;">Barcode</th>
                                                        <th class="text-right text-dark font-12 font-weight-600" style="width:6% !important;">MRP</th>
                                                        <th class="text-right text-dark font-12 font-weight-600" style="width:10% !important;">SellingPrice</th>
                                                        <th class="text-right text-dark font-12 font-weight-600" style="width:4% !important;">Qty</th>
                                                        <th class="text-right text-dark font-12 font-weight-600" style="width:7% !important;">Taxable Amount</th>
                                                        <th class="text-right text-dark font-12 font-weight-600" style="width:5% !important;">CGST%</th>
                                                        <th class="text-right text-dark font-12 font-weight-600" style="width:7% !important;">CGST Amt.</th>
                                                        <th class="text-right text-dark font-12 font-weight-600" style="width:5% !important;">SGST%</th>
                                                        <th class="text-right text-dark font-12 font-weight-600" style="width:7% !important;">SGST Amt.</th>
                                                        <th class="text-right text-dark font-12 font-weight-600" style="width:11% !important;">Total Amount</th>';
                                                     }
                                                     ?>
                                                      
                                                      
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $ttaldiscount = 0;
                                                $totalcgst = 0;
                                                $totalsgst = 0;
                                                $totaligst = 0;
                                                $tottaxable = 0;
                                                foreach($billingproductdata AS $billingproduct_key=>$billingproduct_value)
                                                {
                                                
                                                  if ($billingproduct_key % 2 == 0) {
                                                            $tblclass = '#fff;';
                                                        } else {
                                                            $tblclass = '#f3f3f3;';
                                                        }  
                                                $totaldiscount   =   $billingproduct_value->discount_amount + $billingproduct_value->overalldiscount_amount;
                                                $sno  =   $billingproduct_key + 1;

                                                $totalcgst  +=  $billingproduct_value->cgst_amount;
                                                $totalsgst  +=  $billingproduct_value->sgst_amount;
                                                $tottaxable +=  $billingproduct_value->sellingprice_afteroverall_discount;

                                                $ttaldiscount  +=  $totaldiscount;

                                                if($billingproduct_value['product']['supplier_barcode']!='' && $billingproduct_value['product']['supplier_barcode']!=null)
                                                {
                                                  $barcode     =     $billingproduct_value['product']['supplier_barcode'];
                                                }
                                                else
                                                {
                                                  $barcode     =     $billingproduct_value['product']['product_system_barcode'];
                                                }
                                                
                                                ?>
                                                <tr style="border-bottom:1px solid #C0C0C0 !important;">
                                                    <td style="height:50px !important;">{{$sno}}</td>
                                                    <td><?php echo html_entity_decode($billingproduct_value->product->product_name)?></td>
                                                    <td>{{$barcode}}</td>
                                                    <td class="text-right">{{round($billingproduct_value->mrp,2)}}</td>
                                                    <td class="text-right">{{round($billingproduct_value->sellingprice_before_discount,2)}}</td>
                                                     <td class="text-right">{{round($billingproduct_value->qty)}}</td>
                                                     <?php
                                                     if($billingdata['overalldiscount']!=0 && $billingdata['overalldiscount']!='' && $billingdata['overalldiscount']!=null)
                                                     {
                                                        ?>
                                                        <td class="text-right">{{round($totaldiscount,2)}}</td>
                                                        <?php
                                                     }
                                                     ?>
                                                    
                                                      <td class="text-right">{{round($billingproduct_value->sellingprice_afteroverall_discount,2)}}</td>                                                      
                                                    <td class="text-right">{{$billingproduct_value->cgst_percent}}</td>
                                                    <td class="text-right totcgstamt">{{round($billingproduct_value->cgst_amount,2)}}</td>
                                                    <td class="text-right">{{$billingproduct_value->sgst_percent}}</td>
                                                    <td class="text-right totsgstamt">{{round($billingproduct_value->sgst_amount,2)}}</td>
                                                    <td class="text-right tottotamt">{{round($billingproduct_value->total_amount,2)}}</td>
                                                    
                                                </tr>
                                            <?php
                                               }
                                                
                                                
                                                $rest   =   7 - $productcount;
                                                for($x=1; $x<=$rest; $x++)
                                                {
                                                    ?>
                                                <tr>
                                                    <td class="text-dark" style="height:50px !important;">&nbsp;</td>
                                                    <td class="text-dark"></td>
                                                    <td class="text-dark"></td>
                                                    <td class="text-dark"></td>
                                                    <td class="text-right text-dark"></td>
                                                    <td class="text-right text-dark"></td>
                                                   <td class="text-right text-dark"></td>
                                                   <?php
                                                   if($billingdata['overalldiscount']!=0 && $billingdata['overalldiscount']!='' && $billingdata['overalldiscount']!=null)
                                                     {
                                                        echo '<td class="text-right text-dark"></td>';
                                                     }
                                                   ?>
                                                  
                                                  <td class="text-right text-dark tottariff"></td>
                                                        
                                                    <td class="text-right text-dark"></td>
                                                    <td class="text-right text-dark totcgstamt"></td>
                                                    <td class="text-right text-dark"></td>
                                                    <td class="text-right text-dark totsgstamt"></td>
                                                    
                                                    
                                                </tr>


                                                <?php
                                                 }



                                                    ?>              
                                               
                                            <!-- <tfoot style="border-bottom:1px solid #999 !important;border-top:1px solid #999 !important;"> -->
                                                <tr>
                                                   
                                                    <th colspan="5" class="text-right font-14 font-weight-600">Total</th>
                                                    <th class="text-right font-weight-600">{{round($billingdata->total_qty)}}</th>
                                                    <?php
                                                    if($billingdata['overalldiscount']!=0 && $billingdata['overalldiscount']!='' && $billingdata['overalldiscount']!=null)
                                                    {
                                                        ?>
                                                            <th class="text-right font-weight-600">{{round($ttaldiscount,2)}}</th>
                                                        <?php
                                                    }
                                                    ?>
                                                    
                                                     <th class="text-right font-weight-600">{{round($tottaxable,2)}}</th>
                                                     <th></th>
                                                      <th class="text-right text-dark font-14 font-weight-600">{{round($totalcgst,2)}}</th>
                                                    <th class="text-right font-weight-600"></th>
                                                    <th class="text-right text-dark font-14 font-weight-600">{{round($totalsgst,2)}}</th>
                                                    <th class="text-right text-dark font-18 font-weight-600"><?php
                                              if($currency_title=='INR')
                                              {
                                                ?>&#x20b9<?php
                                              }
                                              else
                                              {
                                                echo $currency_title;
                                              }
                                              ?> {{round($billingdata->total_bill_amount,$nav_type[0]['decimal_points'])}}</th>
                                                    
                                                  
                                                </tr>
                                            <!-- </tfoot> -->
                                             </tbody>
                                        </table>
                             <?php
                             }
                             else
                             {
                                ?>
                                  <table width="100%" cellpadding="6" frame="box" border="1" style="border-collapse:collapse;float:left;">
                                    <thead>
                                        <tr style="background:#999;border-bottom:1px #999 solid;border-top:1px #999 solid;">
                                           
                                            <?php
                                             if($billingdata['overalldiscount']!=0 && $billingdata['overalldiscount']!='' && $billingdata['overalldiscount']!=null)
                                             {
                                                    echo '<th class="text-dark font-12 font-weight-600" style="width:8% !important;">Sr No.</th>
                                                    <th class="text-dark font-12 font-weight-600" style="width:16% !important;">Item Description</th>
                                                    <th class="text-dark font-12 font-weight-600" style="width:10% !important;">Barcode</th>
                                                    <th class="text-right text-dark font-12 font-weight-600" style="width:6% !important;">MRP</th>
                                                    <th class="text-right text-dark font-12 font-weight-600" style="width:10% !important;">SellingPrice</th>
                                                    <th class="text-right text-dark font-12 font-weight-600" style="width:6% !important;">Qty</th>
                                                    <th class="text-right text-dark font-12 font-weight-600" style="width:7% !important;">Disc. Amt.</th>
                                                    <th class="text-right text-dark font-12 font-weight-600" style="width:10% !important;">Taxable<br>Amount</th>
                                                     <th class="text-right text-dark font-12 font-weight-600" style="width:7% !important;">IGST%</th>
                                                    <th class="text-right text-dark font-12 font-weight-600" style="width:10% !important;">IGST Amt.</th>
                                                    <th class="text-right text-dark font-12 font-weight-600" style="width:13% !important;">Total Amount</th>';
                                                }
                                                else
                                                {
                                                    echo '<th class="text-dark font-12 font-weight-600" style="width:8% !important;">Sr No.</th>
                                                    <th class="text-dark font-12 font-weight-600" style="width:20% !important;">Item Description</th>
                                                    <th class="text-dark font-12 font-weight-600" style="width:10% !important;">Barcode</th>
                                                    <th class="text-right text-dark font-12 font-weight-600" style="width:6% !important;">MRP</th>
                                                    <th class="text-right text-dark font-12 font-weight-600" style="width:13% !important;">SellingPrice</th>
                                                    <th class="text-right text-dark font-12 font-weight-600" style="width:6% !important;">Qty</th>
                                                    <th class="text-right text-dark font-12 font-weight-600" style="width:13% !important;">Taxable<br>Amount</th>
                                                     <th class="text-right text-dark font-12 font-weight-600" style="width:7% !important;">IGST%</th>
                                                    <th class="text-right text-dark font-12 font-weight-600" style="width:10% !important;">IGST Amt.</th>
                                                    <th class="text-right text-dark font-12 font-weight-600" style="width:13% !important;">Total Amount</th>';
                                                }
                                                ?>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $ttaldiscount = 0;
                                                $totalcgst = 0;
                                                $totalsgst = 0;
                                                $totaligst = 0;
                                                $tottaxable = 0;
                                                foreach($billingproductdata AS $billingproduct_key=>$billingproduct_value)
                                                {
                                               
                                                    if ($billingproduct_key % 2 == 0) {
                                                            $tblclass = '#fff;';
                                                        } else {
                                                            $tblclass = '#f3f3f3;';
                                                        } 


                                                $totaldiscount   =   $billingproduct_value->discount_amount + $billingproduct_value->overalldiscount_amount;

                                                $sno  =   $billingproduct_key + 1;
                                                $ttaldiscount  +=  $totaldiscount;
                                                
                                                $totaligst  +=  $billingproduct_value->igst_amount;
                                                $tottaxable +=  $billingproduct_value->sellingprice_afteroverall_discount;
                                                if($billingproduct_value['product']['supplier_barcode']!='' && $billingproduct_value['product']['supplier_barcode']!=null)
                                                {
                                                  $barcode     =     $billingproduct_value['product']['supplier_barcode'];
                                                }
                                                else
                                                {
                                                  $barcode     =     $billingproduct_value['product']['product_system_barcode'];
                                                }
                                                ?>
                                                <tr style="border-bottom:1px solid #C0C0C0 !important;">
                                                    <td style="height:50px !important;">{{$sno}}</td>
                                                    <td><?php echo html_entity_decode($billingproduct_value->product->product_name)?></td>
                                                    <td>{{$barcode}}</td>
                                                    <td class="text-right">{{round($billingproduct_value->mrp)}}</td>
                                                    <td class="text-right">{{round($billingproduct_value->sellingprice_before_discount,2)}}</td>
                                                    
                                                    <td class="text-right">{{round($billingproduct_value->qty)}}</td>
                                                    <?php
                                                     if($billingdata['overalldiscount']!=0 && $billingdata['overalldiscount']!='' && $billingdata['overalldiscount']!=null)
                                                     {
                                                        ?>
                                                        <td class="text-right">{{round($totaldiscount,2)}}</td>
                                                       
                                                        <?php
                                                     }
                                                    ?>
                                                    <td class="text-right totcgstamt">{{round($billingproduct_value->sellingprice_afteroverall_discount,2)}}</td>
                                                    <td class="text-right">{{$billingproduct_value->igst_percent}}</td>
                                                    <td class="text-right totsgstamt">{{round($billingproduct_value->igst_amount,2)}}</td>
                                                    <td class="text-right tottotamt">{{round($billingproduct_value->total_amount,2)}}</td>
                                                    
                                                </tr>

                                               
                                                <?php
                                            }
                                                
                                                $rest   =  8 - $productcount;
                                                for($x=1; $x<=$rest; $x++)
                                                {
                                                    ?>
                                                <tr>
                                                    <td class="text-dark" style="height:50px !important;">&nbsp;</td>
                                                    <td class="text-dark"></td>
                                                    <td class="text-dark"></td>
                                                    <td class="text-dark"></td>
                                                    <td class="text-right text-dark"></td>
                                                    <?php
                                                     if($billingdata['overalldiscount']!=0 && $billingdata['overalldiscount']!='' && $billingdata['overalldiscount']!=null)
                                                     {
                                                            echo '<td class="text-right text-dark"></td>';
                                                     }
                                                     ?>
                                                    
                                                    <td class="text-right text-dark"></td>
                                                    <td class="text-right text-dark totcgstamt"></td>
                                                    <td class="text-right text-dark"></td>
                                                    <td class="text-right text-dark totsgstamt"></td>
                                                    <td class="text-right text-dark tottotamt"></td>
                                                    
                                                </tr>


                                                <?php
                                                 }



                                                    ?>              
                                                
                                            <!-- <tfoot style="border-bottom:1px solid #999 !important;border-top:1px solid #999 !important;"> -->
                                                <tr>
                                                    
                                                     <th colspan="5" class="text-right font-14 font-weight-600">Total</th>
                                                      <th class="text-right text-dark font-14 font-weight-600">{{round($billingdata->total_qty)}}</th>
                                                      <?php
                                                     if($billingdata['overalldiscount']!=0 && $billingdata['overalldiscount']!='' && $billingdata['overalldiscount']!=null)
                                                     {
                                                        ?>
                                                         <th class="text-right text-dark font-14 font-weight-600">{{round($ttaldiscount,2)}}</th>
                                                        <?php
                                                     }

                                                    ?>
                                                    
                                                     <th class="text-right text-dark font-14 font-weight-600">{{round($tottaxable,2)}}</th>
                                                    <th></th>
                                                    <th class="text-right text-dark font-14 font-weight-600">{{round($totaligst,2)}}</th>
                                                    <th class="text-right text-dark font-18 font-weight-600"><?php
                                              if($currency_title=='INR')
                                              {
                                                ?>&#x20b9<?php
                                              }
                                              else
                                              {
                                                echo $currency_title;
                                              }
                                              ?> {{round($billingdata->total_bill_amount,$nav_type[0]['decimal_points'])}}</th>
                                                    
                                                  
                                                </tr>
                                            <!-- </tfoot> -->
                                            </tbody>
                                      </table>
                                       <?php 
                                     }

                   }   
            ?>     

                            <div style="width:100%;border:0px solid;margin:20px 0 0 0;float:left;">
                               
                              <div style="width:70% !important;border:0px solid !important;float:left;">
                            <?php
                            if($tax_type!=1)
                            {
                            ?>
                                <span class="font-weight-600" style="font-size:16px;"><center><--------GST Breakup Details--------></center></span>

                                      <table width="100%" cellpadding="6" frame="box" border="1" style="border-collapse:collapse;">
                                            <thead>
                                                <tr style="border-bottom:1px #999 solid;border-top:1px #999 solid;">
                                                   
                                                    <th class="text-dark font-12 font-weight-600 bottomdarkline" style="width:8% !important;text-align:left;">GST%</th>
                                                    <th class="text-right text-dark font-12 font-weight-600 bottomdarkline" style="width:16% !important;">Taxable Amt.</th>
                                                    <?php
                                                    if(($billingdata->state_id)==($billingdata->company->state_id))
                                                    {
                                                    ?>
                                                    <th class="text-right text-dark font-12 font-weight-600 bottomdarkline" style="width:16% !important;">CGST<br><small>Rate & Amt.</small></th>
                                                    <th class="text-right text-dark font-12 font-weight-600 bottomdarkline" style="width:16% !important;">SGST<br><small>Rate & Amt.</small></th>
                                                    <?php
                                                    }
                                                    else
                                                    {

                                                    ?>
                                                    <th class="text-right text-dark font-12 font-weight-600 bottomdarkline" style="width:16% !important;">IGST<br><small>Rate & Amt.</small></th>
                                                    <?php
                                                    }
                                                    ?>
                                                    <th class="text-right text-dark font-12 font-weight-600 bottomdarkline" style="width:26% !important;">Total Amount</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $ttaldiscount = 0;
                                                foreach($gstdata AS $gst_key=>$gst_value)
                                                {
                                               
                                                    if ($billingproduct_key % 2 == 0) {
                                                            $tblclass = '#fff;';
                                                        } else {
                                                            $tblclass = '#f3f3f3;';
                                                        } 

                                                ?>

                                                <tr style="border-bottom:1px solid #C0C0C0 !important;">
                                                    <td>{{round($gst_value->igst_percent,2)}}%</td>
                                                    <td class="text-right">{{round($gst_value->tottaxablevalue,2)}}</td>
                                                    <?php
                                                    if($billingdata['company']['state_id']==$billingdata['state_id'])
                                                    {
                                                        ?>
                                                        <td class="text-right">{{round($gst_value->cgst_percent,2)}}%<br>{{round($gst_value->totcgstamount,2)}}</td>
                                                        <td class="text-right">{{round($gst_value->sgst_percent,2)}}%<br>{{round($gst_value->totsgstamount,2)}}</td>
                                                        
                                                        <?php


                                                    }
                                                    else
                                                    {
                                                        ?>
                                                           
                                                            <td class="text-right">{{round($gst_value->igst_percent,2)}}%<br>{{round($gst_value->totigstamount,2)}}</td>
                                                           
                                                    <?php
                                                    }
                                                    ?>
                                                     <td class="text-right tottotamt">{{round($gst_value->totgrand,2)}}</td>  
                                                    
                                                </tr>

                                               
                                                <?php
                                           }
                                                
                                          
                                            ?>
                                              
  
                                                
                                            <!-- tfoot style="border-bottom:1px solid #999 !important;border-top:1px solid #999 !important;"> -->
                                                <tr>
                                                    
                                                     <th class="text-right font-14 font-weight-600 upperdarkline">Total</th>
                                                     <th class="text-right text-dark font-14 font-weight-600 upperdarkline">{{round($tottaxable,2)}}</th>
                                                      <?php
                                                   if($billingdata['company']['state_id']==$billingdata['state_id'])
                                                    {
                                                        ?>
                                                          <th class="text-right text-dark font-14 font-weight-600 upperdarkline">{{round($totalcgst,2)}}</th>
                                                          <th class="text-right text-dark font-14 font-weight-600 upperdarkline">{{round($totalsgst,2)}}</th>
                                                        
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                      
                                                             <th class="text-right text-dark font-14 font-weight-600 upperdarkline">{{round($totaligst,2)}}</th>
                                                        <?php
                                                    }
                                                    ?>
                                                    
                                     
                                                    
                                                    <th class="text-right text-dark font-18 font-weight-600 upperdarkline"><?php
                                                  if($currency_title=='INR')
                                                  {
                                                    ?>&#x20b9<?php
                                                  }
                                                  else
                                                  {
                                                    echo $currency_title;
                                                  }
                                                  ?> {{round($billingdata->total_bill_amount,$nav_type[0]['decimal_points'])}}</th>
                                                    
                                                  
                                                </tr>
                                           <!--  </tfoot> -->
                                           </tbody>
                           
                                      </table> 
                                 <?php
                                  }
                                  ?>
                                       
                                    </div>
                                    
                                    <div style="width:29% !important;border:0px solid !important;font-size:16px;float:right;">
                                        <table style="float:right;font-size:16px;">
                                            <tr>
                                            <td colspan="3" class="text-right font-weight-600" style="font-size:14px;">PAYMENT METHODS</td>                                      
                                            </tr>
                                            @foreach($billingdata->sales_bill_payment_detail AS $salespayment_key=>$salespayment_value)
                                            <tr>
                                            <td class="d-block font-weight-600">{{$salespayment_value['payment_method'][0]['payment_method_name']}} </td>
                                            <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                            <td class="font-weight-600">
                                              <?php
                                              if($currency_title=='INR')
                                              {
                                                ?>&#x20b9<?php
                                              }
                                              else
                                              {
                                                echo $currency_title;
                                              }
                                              ?>
                                             {{$salespayment_value->total_bill_amount}}</td>
                                            </tr>                                          
                                            @endforeach
                                           
                                  
                                        </table> 
                                        
                                    </div>
                                    
                               
                               
                               <div style="width:60% !important;border:0px solid !important;float:left;">
                                <?php
                                if($billingdata['company']['account_holder_name']!=NULL ||$billingdata['company']['account_number']!=NULL ||$billingdata['company']['ifsc_code']!=NULL||$billingdata['company']['branch']!=NULL)
                                {
                                ?>
                               
                                        <table style="float:left;font-size:16px;">
                                            <tr>
                                            <td colspan="3" class="d-block font-weight-600" style="font-size:14px;">BANK DETAILS</td>
                                            
                                            </tr>
                                            <?php 
                                             if(isset($billingdata) && isset($billingdata['company'])
                                                 && $billingdata['company']['account_holder_name']!=NULL)   
                                             {
                                                ?>
                                            <tr>
                                            <td class="d-block font-weight-600">AC HOLDER NAME</td>
                                            <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                            <td class="font-weight-600">{{$billingdata->company-> account_holder_name}}</td>
                                            </tr>
                                            <?php
                                            }

                                             if(isset($billingdata) && isset($billingdata['company'])
                                                 && $billingdata['company']['account_number']!=NULL)   
                                             {
                                                ?>
                                            <tr>
                                            <td class="d-block font-weight-600">ACCOUNT NO.</td>
                                            <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                            <td class="font-weight-600">{{$billingdata->company->account_number}}</td>
                                            </tr>
                                             <?php
                                             }
                                             if(isset($billingdata) && isset($billingdata['company'])
                                                 && $billingdata['company']['ifsc_code']!=NULL)   
                                             {
                                                ?>
                                            <tr>
                                            <td class="d-block font-weight-600">IFSC CODE</td>
                                            <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                            <td class="font-weight-600">{{$billingdata->company->ifsc_code}}</td>
                                            </tr>
                                             <?php
                                             }
                                             if(isset($billingdata) && isset($billingdata['company'])
                                                 && $billingdata['company']['branch']!=NULL)   
                                             {
                                                ?>
                                            <tr>
                                            <td class="d-block font-weight-600">BRANCH</td>
                                            <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                            <td class="font-weight-600">{{$billingdata->company->branch}}</td>
                                            </tr>
                                             <?php
                                             } 
                                             ?>
                                        </table> 
                                        <?php
                                      }
                                        ?>  
                                       
                              </div>
                                    
                                <div style="width:39% !important;border:0px solid !important;font-size:16px;float:left;">
                                    
                                    
                                </div>
                           </div> 

            <!--*** CONTENT GOES HERE ***-->
          </div>
        </td>
      </tr>
    </tbody>

    <tfoot>
      <tr>
        <td>
          <!--place holder for the fixed-position footer-->
          <div class="page-footer-space"></div>
        </td>
      </tr>
    </tfoot>

  </table>

</body>

</html>