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
   
    <!-- Custom CSS
    <link href="{{URL::to('/')}}/public/dist/css/style.css" rel="stylesheet" type="text/css">    
    <link href="{{URL::to('/')}}/public/template/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"> -->
   
    <link href="{{URL::to('/')}}/public/dist/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <script type="text/javascript">window.print();</script>

<style>
ul
{
    list-style:square !important;
     margin : 0 0 0 8px !important;
    padding: 0 0 0 8px !important;
}
ul li
{
    margin : 0 0 0 0 !important;
    padding: 0 0 0 0 !important;
}
.hk-invoice-wrap .invoice-from-wrap > .row div:last-child, .hk-invoice-wrap .invoice-to-wrap > .row div:last-child {
    text-align: left !important;
}
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

</style>

</head>


             {{--<div class="row">
                    <div class="col-xl-12" >
                        <section class="hk-sec-wrapper hk-invoice-wrap" style="margin:-15px 0 0 0 !important;font-family:Tahoma, Geneva, sans-serif !important;font-size:13px !important;border:0px !important;">--}}
                            <div style="width:100%;border:0px solid;font-family:Tahoma, Geneva, sans-serif !important;font-size:13px !important">
                                <span class="mb-35 font-weight-600" style="font-size:34px;"><center>TAX INVOICE</center></span>
                                 
                                <div style="margin:20px 0 0 0;width:100%;border:0px solid;float:left;">
                                    <div style="border:0px solid !important;font-size:16px;float:left !important;">
                                        <span class="d-block font-weight-600" style="font-size:24px;"><span class="text-dark">{{$billingdata->company->company_name}}</span></span><br>
                                        <span class="d-block font-weight-600"><span class="text-dark">{{strip_tags($billingdata->company->company_address)}}</span></span><br>
                                        <span class="d-block font-weight-600"><span class="text-dark">{{strip_tags ($billingdata->company->company_area)}} {{$billingdata->company->company_city}} - {{$billingdata->company->company_pincode}}</span></span><br>
                                        <?php
                                        $company_mobile_code =  explode(',',$billingdata->company->company_mobile_dial_code);

                                        ?>
                                         <span class="d-block font-weight-600"><span class="text-dark">({{$company_mobile_code[0]}}){{$billingdata->company->company_mobile}}</span></span><br>
                                          <span class="d-block font-weight-600"><span class="text-dark">{{$billingdata->company->company_email}}</span></span>

                                     
                                    </div>
                                    <?php
                                        $whtapp_mobile_code =  explode(',',$billingdata->company->whatsapp_mobile_dial_code);
                                    ?>
                                    
                               
                            </div>
                           <?php
                           $customer_country   =  $billingdata['customer_address_detail']['country_name']['country_name'];
                                    if(isset($billingdata) && isset($billingdata['customer_address_detail'])
                                        && $billingdata['customer_address_detail']['customer_address'] != NULL)
                                    {
                                        
                                        $customer_address = $billingdata['customer_address_detail']['customer_address'].' ,'.$customer_country; 
                                    } 
                                    else {
                                        $customer_address = $customer_country; 

                                    }
                                    ?>  
                         <?php
                         if(isset($billingdata) && $billingdata['customer']['customer_id']!=NULL)   
                         {
                            ?>
                            <div style="margin:10px 0 0 0px !important;width:100%;border:0px solid;float:left;">
                                
                                <div class="row">
                                    <div class="col-md-12 mb-30" style="border:0px solid !important;font-size:16px;"> 
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
                                            ?>
                                            <tr>
                                            <td class="d-block font-weight-600" valign="top">Address</td>
                                            <td class="font-weight-600" valign="top">&nbsp;:&nbsp;</td>
                                            <td class="font-weight-600">{{$customer_address}}</td>
                                            </tr>

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
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <div style="margin:10px 0 0 0px !important;width:100%;border:0px solid;float:left;">
                                
                                
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
                                       <table style="float:left;">
                                            <tr>
                                            <td colspan="3"><span class="d-block font-weight-600" style="font-size:20px;text-align:left;">Invoice Details</span></td>    
                                            </tr>
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
                      
                    
                           
                                 <table width="100%" cellpadding="6" cellspacing="0" frame="box" style="margin:20px 0 10px 0 !important;float:left;border:1px solid !important;">
                                            <thead>                                                
                                                <tr class="background">
                                                   
                                                    <th class="text-dark font-12 font-weight-600 bottomdarkline" style="width:40% !important;text-align:left;">Particulars</th>                                                    
                                                    <th class="text-right text-dark font-12 font-weight-600 bottomdarkline " style="width:20% !important;">MRP</th>
                                                    <th class="text-right text-dark font-12 font-weight-600 bottomdarkline " style="width:10% !important;">Qty</th>
                                                    <th class="text-right text-dark font-12 font-weight-600 bottomdarkline " style="width:10% !important;">Disc. Amt.</th>                                                   
                                                    <th class="text-right text-dark font-12 font-weight-600 bottomdarkline " style="width:20% !important;">Total Amount</th>
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


                                                $totaldiscount   =   $billingproduct_value->mrpdiscount_amount + $billingproduct_value->overallmrpdiscount_amount;

                                                $sno  =   $billingproduct_key + 1;
                                                $ttaldiscount  +=  $totaldiscount;
                                                $totalcgst  +=  $billingproduct_value->cgst_amount;
                                                $totalsgst  +=  $billingproduct_value->sgst_amount;
                                                $totaligst  +=  $billingproduct_value->igst_amount;
                                                $tottaxable +=  $billingproduct_value->sellingprice_afteroverall_discount;
                                                ?>
                                                <tr class="underline" style="border-bottom:1px solid #C0C0C0 !important;">
                                                    <td class="text-dark font-weight-600 underline"><?php echo html_entity_decode($billingproduct_value->product->product_name)?></td>
                                                    <td class="text-right text-dark font-weight-600 underline">{{round($billingproduct_value->mrp,2)}}</td>
                                                    <td class="text-right text-dark font-weight-600 underline">{{round($billingproduct_value->qty)}}</td>
                                                    <td class="text-right text-dark font-weight-600 underline">{{round($totaldiscount,2)}}</td>
                                                    <td class="text-right text-dark font-weight-600 underline tottotamt">{{round($billingproduct_value->total_amount,2)}}</td>
                                                    
                                                </tr>

                                               
                                                <?php
                                            }
                                                
                                          
                                            ?>
                                              
                                                </tbody>
                                            <tfoot style="border-bottom:1px solid #999 !important;border-top:1px solid #999 !important;">
                                                <tr>
                                                    
                                                     <th colspan="2" class="text-right font-14 font-weight-600 upperdarkline">Total</th>
                                                      <th class="text-right text-dark font-14 font-weight-600 upperdarkline">{{round($billingdata->total_qty)}}</th>
                                                     <th class="text-right text-dark font-14 font-weight-600 upperdarkline">{{round($ttaldiscount,2)}}</th>
                                                    <th class="text-right text-dark font-18 font-weight-600 upperdarkline">
                                                        <?php
                                                        if($currency_title=='INR')
                                                          {
                                                            ?>&#x20b9<?php
                                                          }
                                                          else
                                                          {
                                                            echo $currency_title;
                                                          }
                                                          ?> {{round($billingdata->total_bill_amount,0)}}</th>
                                                    
                                                  
                                                </tr>
                                            </tfoot>
                           
                                      </table>
                                      <br/>
                                      <br>

        <?php
        if($tax_type!=1)
        {
            ?>
<span class="mb-35 font-weight-600" style="font-size:16px;margin:10px 0 0 0;"><center><--------GST Breakup Details--------></center></span>

                                      <table width="100%" cellpadding="6" cellspacing="0" frame="box" style="margin:20px 0 0 0 !important;float:left;border:1px solid !important;">
                                            <thead>
                                                <tr style="border-bottom:1px #999 solid;border-top:1px #999 solid;">
                                                   
                                                    <th class="text-dark font-12 font-weight-600 bottomdarkline" style="width:8% !important;text-align:left;">GST%</th>
                                                    <th class="text-right text-dark font-12 font-weight-600 bottomdarkline" style="width:16% !important;">Taxable Amt.</th>
                                                     <?php
                                                    if($billingdata['company']['state_id']==$billingdata['state_id'])
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
                                                    <td class="text-dark font-weight-600">{{round($gst_value->igst_percent,2)}}%</td>
                                                    <td class="text-right text-dark font-weight-600">{{round($gst_value->tottaxablevalue,2)}}</td>
                                                    <?php
                                                    if($billingdata['company']['state_id']==$billingdata['state_id'])
                                                    {
                                                        ?>
                                                        <td class="text-right text-dark font-weight-600">{{round($gst_value->cgst_percent,2)}}%<br>{{round($gst_value->totcgstamount,2)}}</td>
                                                        <td class="text-right text-dark font-weight-600">{{round($gst_value->sgst_percent,2)}}%<br>{{round($gst_value->totsgstamount,2)}}</td>
                                                       
                                                        <?php


                                                    }
                                                    else
                                                    {
                                                        ?>
                                                           
                                                            <td class="text-right text-dark font-weight-600">{{round($gst_value->igst_percent,2)}}%<br>{{round($gst_value->totigstamount,2)}}</td>
                                                           
                                                    <?php
                                                    }
                                                    ?>
                                                     <td class="text-right text-dark font-weight-600 tottotamt">{{round($gst_value->totgrand,2)}}</td>  
                                                    
                                                </tr>

                                               
                                                <?php
                                            }
                                                
                                          
                                            ?>
                                              
  
                                                </tbody>
                                            <tfoot style="border-bottom:1px solid #999 !important;border-top:1px solid #999 !important;">
                                                <tr>
                                                    
                                                     <th class="text-right font-14 font-weight-600 upperdarkline">Total</th>
                                                     <th class="text-right text-dark font-14 font-weight-600 upperdarkline">{{round($billingdata->sellingprice_after_discount)}}</th>
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
                                              ?> {{round($billingdata->total_bill_amount,0)}}</th>
                                                    
                                                  
                                                </tr>
                                            </tfoot>
                           
                                      </table>

                    <?php
                    }
                    ?>                  
                        

                            <div style="margin:35px 0 0 12px;width:100%;border:0px solid;float:left;">
                              
                        
                                        <table style="float:left;font-size:16px;">
                                            <tr>
                                            <td colspan="3" class="font-weight-600" style="font-size:14px;">PAYMENT METHODS</td>                                      
                                            </tr>
                                            @foreach($billingdata->sales_bill_payment_detail AS $salespayment_key=>$salespayment_value)
                                            <tr>
                                            <td class="d-block font-weight-600">{{$salespayment_value['payment_method'][0]['payment_method_name']}} </td>
                                            <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                            <td class="font-weight-600"><?php
                                              if($currency_title=='INR')
                                              {
                                                ?>&#x20b9<?php
                                              }
                                              else
                                              {
                                                echo $currency_title;
                                              }
                                              ?> {{round($salespayment_value->total_bill_amount,2)}}</td>
                                            </tr>                                          
                                            @endforeach
                                           
                                  
                                        </table> 
                                        
                                   
                                    
                                </div>
                                  <div style="margin:25px 0 0 12px;width:100%;border:0px solid;float:left;">
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
                           
                            <div style="margin:25px 0 0 12px;width:100%;border:0px solid;float:left;">
                               <center><span class="d-block font-weight-600" style="font-size:18px;"><?php echo html_entity_decode($billingdata->company->additional_message) ?></span></center>
                           </div>
                            </div>
                            
                                
                           
                        {{--}} </section>
                    </div>
                </div>--}}
             
    