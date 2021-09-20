@include('pagetitle')
@extends('master')

@section('main-hk-pg-wrapper')
<style type="text/css">
.col-md-7,.col-md-5{
    padding-left:0 !important;
    padding-right:0 !important;
}
.table thead tr.header th {
   
    font-size: 0.95rem !important;
}

.resetbtn{
    width:90px !important;
    padding: .175rem .35rem !important;
}
.popup_values table tbody tr td .even
{
    background : #f3f3f3 !important;
}
.popup_values table tbody tr td .even
{
    background : #ffffff !important;
}
.popup_values table tbody tr td{
    padding: 1rem 0 1rem 0 !important;
}
.tarifform-control[readonly] {
    border-color: transparent;
    background: transparent;
    color: #000;
    font-size: 1rem;
    font-weight: normal !important;
    
}
</style>
<?php
$billtype   =  $nav_type[0]['billtype'];
$tax_type   =  $nav_type[0]['tax_type'];
$taxname    =  $nav_type[0]['tax_title'];
$tax_title  =  $tax_type==1?$taxname:'IGST';

?>
<link rel="stylesheet" href="{{URL::to('/')}}/public/bower_components/bootstrap-datepicker/css/bootstrap-datepicker.css">

<form name="billingform" id="billingform" method="POST">
   
    <div class="row ma-0">
        <div class="col-sm-10">

            <div class="hk-row">
                 <div class="col-md-4">
                    <div class="card pa-10">
                        <div class="card-body fixed-height pa-0">
                            <h5 class="card-title" style="margin:5px 0 0 5px !important;">Search</h5>
                                    <div class="row">
                                        <div class="col-sm-4  no-right">
                                            <label>Product</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="productsearch" id="productsearch" class="form-control mt-15 typeahead" placeholder="Product Name / Barcode" data-provide="typeahead" data-items="10" data-source=""/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                           
                                        </div>
                                        <div class="col-sm-8">
                                            <label style="text-align:left !important;">OR</label>
                                        </div>
                                    </div>
                                     <div class="row">
                                        <div class="col-sm-4  no-right">
                                            <label>Invoice No.</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="bill_no" id="bill_no" class="form-control mt-15 typeahead" placeholder="Bill No." data-provide="typeahead" data-items="10" data-source=""/>
                                            
                                        </div>
                                    </div>
                            <div class="row">
                                <div class="col-sm-12" style="text-align:right !important;">                                  
                                    <button type="button" class="btn btn-info searchBtn" id="searchbilldata" style="padding: .175rem .75rem !important;"><i class="fa fa-search"></i>Search</button>
                                    <button type="button" name="resetfilter" onclick="resetreturnfilterdata();" class="btn btn-info resetbtn" id="resetfilter" data-container="body" data-toggle="popover" data-placement="bottom" data-content="" data-original-title="" title="" >Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card pa-10">
                        <div class="card-body fixed-height">

                            <div class="row" style="border:0px solid;">
                                <div class="col-sm-6" style="border:0px solid;">
                                    <div class="row">
                                        <div class="col-sm-12 no-right">
                                         <h5 class="card-title">Customer Details</h5>
                                        </div>
                                    </div>
                          
                                </div><!--col-md-7-->
                                <div class="col-sm-6" style="border:0px solid;text-align:right important;">
                                    <div class="row">
                                        <div class="col-sm-12">
                                        <label style="text-align:right important;"> <a id="addcustomer"  class="btn btn-primary pull-right" style="color:#fff;padding: .125rem .35rem;margin:-7px 0 0 0;"><i class="fa fa-plus"></i>Add New Customer</a></label>
                                        </div>
                                    </div><!--row-->
                                 
                                    <!--row-->
                                </div><!--col-md-5-->
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="row ma-0">
                                        <div class="col-sm-3 no-right">
                                            <label>Search</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="hidden" name="sales_bill_id" id="sales_bill_id">
                                            <input type="hidden" name="return_bill_id" id="return_bill_id">
                                            <input type="hidden" name="customer_creditnote_id" id="customer_creditnote_id">
                                            <input type="hidden" name="customer_id" id="ccustomer_id">
                                            <input type="text" name="searchcustomer" id="searchcustomer" class="floating-input form-control" value="" maxlength="" placeholder="By Customer Name / Mobile">
                                        </div>
                                    </div>
                          
                                </div><!--col-md-7-->
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-5 no-right">
                                        </div>
                                        <div class="col-sm-7 no-right">
                                           
                                           
                                        </div>
                                    </div><!--row-->
                                 
                                    <!--row-->
                                </div><!--col-md-5-->
                            </div>
                            
                            
                               <div class="row">
                                <div class="col-sm-6">
                                <div class="customerdata" style="margin:2px 0 0 0;display:none;">                                   
                                    <div class="row">
                                        <div class="col-sm-5  no-right">
                                            <label>Name</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control mt-15" placeholder="" name="customer_name" id="customer_name">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5 no-right">
                                            <label>Mobile No.</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control mt-15" placeholder="" name="customer_mobile" id="customer_mobile">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5 no-right">
                                            <label>Email</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control mt-15" placeholder="" name="customer_email" id="customer_email">
                                        </div>
                                    </div>
                                </div>
                            </div><!--col-md-7-->
                                <div class="col-sm-6">
                                    
                                    <div class="row">
                                        <div class="col-sm-5 no-right">                                       
                                            <label>Invoice</label>
                                        </div>
                                        <div class="col-sm-7">
                                             <input type="text" class="form-control mt-15" placeholder="" name="invoice_no" id="invoice_no" autocomplete="off" value="" style="color:#000;" readonly>
                                           
                                        </div>
                                    </div>
                                     <div class="row">
                                         <div class="col-sm-5 no-right">
                                            <label>Invoice Date</label>
                                        </div>
                                        <div class="col-sm-7">
                                           <input type="text" class="form-control mt-15" name="invoice_date" id="invoice_date" value="{{date("d-m-Y")}}" readonly style="color:#000;" >
                                        </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-sm-5 no-right">
                                        <label>Reference</label>
                                    </div>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control mt-15" placeholder="" name="refname" id="refname">
                                    </div>
                            </div><!--row-->
                                </div><!--col-md-5-->
                            

                            </div>
                        </div><!--card-body-->
                    </div><!--card-->
              </div><!--col-lg-4-->
               
            </div><!--hk-row-->
        
    <div class="hk-row">
            <div class="col-md-12">
                 <div class="card">
                    <div class="card-body">
                        <div class="table-wrap">
                            <div class="row">
                                 <div class="col-md-4"></div>
                                    <div class="col-md-8 rightAlign showtitems" style="display:none;"><h5 class="hk-sec-title"><small class="badge badge-soft-danger mt-15 mr-10"><b>No. of Items:</b> <span class="titems">0</span></small></h5></div>
                                    

                                </div>
                                    <div class="table-responsive">
                                   
                                        <table class="table tablesaw table-bordered table-hover table-striped mb-0" data-tablesaw-mode="swipe"  data-tablesaw-sortable data-tablesaw-minimap data-tablesaw-mode-switch >
                                        <?php 
                                            if($billtype == 1 || $billtype==2)
                                            {
                                        ?>
                                            <thead>
                                                <tr class="header">
                                                <th  scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">Barcode</th>
                                                <th  scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Product Name</th>                         
                                                <th  scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">MRP</th>
                                                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Rate</th>
                                                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">Qty</th>
                                                <th  scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">Disc.%</th>
                                                <th  scope="col" data-tablesaw-sortable-col data-tablesaw-priority="7">Disc.Amt.</th> 
                                                <th  scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">{{$tax_title}}%</th>
                                                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="9">{{$tax_title}} Amt.</th>
                                                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="10">Total Amt.</th>
                                                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="11"></th>
                                            </tr>
                                            </thead>
                                             <?php
                                                }
                                                else
                                                {
                                                    ?>
                                                <thead>
                                                    <tr class="header" >
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">Barcode</th>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Product Name</th>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">BatchNo</th>                                             
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">MRP</th>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">Rate</th>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">Qty</th>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="7">Disc.%</th>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">Disc.Amt.</th> 
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="9">{{$tax_title}}%</th>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="10">{{$tax_title}} Amt.</th>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="11">Total Amt.</th>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="12"></th>
                                                    </tr>
                                                </thead> 
                                                    <?php
                                                }
                                                ?>
                                             <input type="hidden" name="counter" id="counter" value="1">
                                           <tbody id="sproduct_detail_record" style="font-size:12px !important;">
                                          
                                   
                                          
                                            </tbody>
                                        </table>
                                    </div><!--table-wrap-->
                                </div><!--table-responsive-->
                            </div>
                        </div>
                    </div>
                </div>
      <div class="hk-row">  
            
     <div class="col-md-12">
        <?php
            if(sizeof($chargeslist) != 0) 
            {
        ?>
                 <div class="card" style="margin-bottom: 1px !important;">
                    <div class="card-body greybg">
                        <div class="table-wrap">
                                
                                    <div class="table-responsive">
                                       
                                        <table class="table mb-0" style="margin:10px 0 0 0;">
                                            <thead>
                                            <tr class="header" style="font-size:10px !important;">
                                                <th style="width:35%;text-align:left;">Charges</th>
                                                <th style="width:15%;">Amount</th>
                                                <th style="width:10%;text-align:right !important;">{{$tax_title}}%</th>
                                                <th style="width:10%;text-align:right !important;">{{$tax_title}} Amt.</th>
                                                <th style="width:15%;text-align:right !important;">Total Charges.</th>
                                                <th style="width:15%;text-align:right !important;">Return Charges</th>
                                            </tr>
                                            </thead>
                                             
                                           <tbody id="charges_record">
                                        
                                          </tbody>
                                        </table>
                                    </div><!--table-wrap-->
                        </div><!--table-responsive-->
                    </div>
                </div>
                <?php 
            }
        ?>
            </div>
    
 </div>                 
            <!--hk-row-->

        </div><!--col-xl-9-->
        <div class="col-sm-2 pa-0">
            <div class="hk-row">

                <div class="col-sm-12 pa-0">
                     <div class="col-sm-12 pa-0">
                    <div class="card" style="display:none;">
                        <div class="card-body pr-0 pl-0 greybg" id="paymentmethoddiv">
                             <h5 class="card-title center">Payment Method</h5>
                            @foreach($payment_methods AS $payment_methods_key=>$payment_methods_value)
                                <?php
                                     if($payment_methods_value->payment_method_id == 8)
                                     {
                                        $class  =   "font-weight:bold;font-size:16px;";
                                        ?>
                                         <div class="row" style="margin-right:2px !important;">
                                                    {{--order cash ,card,wallet--}}
                                                <div class="col-md-7 no-right">
                                                    <label for="card" style="{{$class}}">{{$payment_methods_value->payment_method_name}}</label>
                                                </div>
                                                <div class="col-sm-5">
                                                    <input type="text" value="" data-id="{{$payment_methods_value->payment_method_id}}" class="form-control mt-15 number" id="{{$payment_methods_value->html_id}}" name="{{$payment_methods_value->html_name}}" style="{{$class}}">
                                                    <input type="hidden" value="" class="form-control mt-15 number" id="sales_payment_detail{{$payment_methods_value->payment_method_id}}" name="{{$payment_methods_value->html_name}}" >
                                                </div>
                                            </div>

                                        <?php
                                     }
                                     
                                ?>

                                           
                                             @endforeach
                          </div>      
                                
                    </div>
                </div>
                    <div class="card">
                        <div class="card-body pr-0 pl-0 greenbg" id="totalamtdiv">
                            <h5 class="card-title center">Amounts</h5>
                             <div class="row" style="margin-right:2px !important;">
                                <div class="col-md-7 no-right">
                                    <label style="font-size:16px;font-weight: bold;margin-left:0px">Total Qty</label>
                                </div>
                                <div class="col-md-5">                                    
                                     <input type="text" style="font-size: 20px;" class="form-control mt-15" value="0" readonly="" id="overallqty" name="overallqty" tabindex="-1">
                                </div>
                            </div>
                            <div class="row" style="margin-right:2px !important;">
                                <div class="col-md-7 no-right">
                                    <label>Item Subtotal</label>
                                </div>
                                <div class="col-md-5">                                    
                                    <input type="hidden" class="form-control mt-15" value="0.00" readonly="" id="totalwithout_gst" name="totalwithout_gst">
                                    <input type="text" class="form-control mt-15" value="0.00" readonly="" id="showtotalwithout_gst" tabindex="-1">
                                </div>
                            </div><!--row-->
                            <?php
                                if($tax_type==1)
                                {
                                    $display  = "display:none;";
                                }
                                else
                                {
                                    $display ='';
                                }
                            ?>

                            <div class="row" style="margin-right:2px !important;{{$display}}">
                                <div class="col-md-7 no-right">
                                    <label>CGST</label>
                                </div>
                                <div class="col-md-5">                                    
                                    <input type="hidden" class="form-control mt-15" value="0.00" readonly="" id="total_cgst" name="total_cgst">
                                    <input type="text" class="form-control mt-15" value="0.00" readonly="" id="showtotal_cgst" tabindex="-1">
                                </div>
                            </div><!--row-->
                            <div class="row" style="margin-right:2px !important;{{$display}}">
                                <div class="col-md-7 no-right">
                                    <label>SGST</label>
                                </div>
                                <div class="col-md-5">
                                    <input type="hidden" class="form-control mt-15" value="0.00" readonly="" id="total_sgst" name="total_sgst">
                                    <input type="text" class="form-control mt-15" value="0.00" readonly="" id="showtotal_sgst" tabindex="-1">
                                    
                                </div>
                            </div><!--row-->
                            <div class="row" style="margin-right:2px !important;">
                                <div class="col-md-7 no-right">
                                    <label>Item Discount</label>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control mt-15" value="0.00" readonly="" id="prodwise_discountamt" name="prodwise_discountamt" tabindex="-1">
                                                                        
                                </div>
                            </div>
                            <div class="row" style="margin-right:2px !important;">
                                <div class="col-md-7 no-right">
                                    <label>Total {{$tax_title}}</label>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control mt-15" value="0.00" readonly="" id="total_igst" name="total_igst" tabindex="-1">
                                </div>
                            </div>

                            <div class="row" style="margin-right:2px !important;">
                                <div class="col-md-7 no-right">
                                    <label>Net Amount</label>
                                </div>
                                     <div class="col-md-5">
                                    <input type="hidden" class="form-control mt-15" value="0.00" readonly="" id="sales_total" name="sales_total">
                                    <input type="text" class="form-control mt-15" value="0.00" readonly="" id="showsales_total" tabindex="-1">
                                    
                                    
                                </div>
                            </div><!--row-->
                            <div class="row" style="margin-right:2px !important;">
                                <div class="col-md-7 no-right">
                                    <label>Overall Disc.%</label>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control mt-15 number" value=""  id="discount_percent" name="discount_percent" onkeyup="return overalldiscountpercent();" readonly="" tabindex="-1">
                                </div>
                            </div><!--row-->
                             <div class="row" style="margin-right:2px !important;">
                                <div class="col-md-7 no-right">
                                    <label>Discount Amt.</label>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control mt-15 number" value=""  id="discount_amount" name="discount_amount" onkeyup="return overalldiscountamount();" readonly="" tabindex="-1">
                                   
                                </div>
                            </div>
                            <div class="row" style="margin-right:2px !important;display:none;">
                                <div class="col-md-7 no-right">
                                    <label>Roomwise Discount Amt.</label>
                                </div>
                                <div class="col-md-5" style="margin-left: 5px;">
                                    <input type="text" class="form-control mt-15 number" value=""  id="roomwisediscount_amount" name="roomwisediscount_amount"  tabindex="-1">
                                </div>
                            </div>
                            <?php
                            if(sizeof($chargeslist) != 0) 
                            {
                                $tdisplay   = "";
                            }
                            else
                            {
                                $tdisplay   = "display:none";
                            }
                            ?>
                            <div class="row" style="margin-right:2px !important;{{$tdisplay}}">
                                <div class="col-md-7 no-right">
                                    <label>Grand Total</label>
                                </div>
                                <div class="col-md-5">
                                    <input type="hidden" class="form-control mt-15" value="0.00" readonly="" id="grand_total" name="grand_total">
                                    <input type="text"  style="font-size: 16px;" class="form-control mt-15" value="0.00" readonly="" id="showgrand_total" tabindex="-1">
                                   
                                </div>
                            </div>
                            <div class="row" style="margin-right:2px !important;{{$tdisplay}}">
                                <div class="col-md-7 no-right">
                                    <label>Additional Charges</label>
                                </div>
                                <div class="col-md-5">
                                    <input type="hidden" class="form-control mt-15" value="0.00" readonly="" id="charges_total" name="charges_total">
                                    <input type="text" style="font-size: 16px;" class="form-control mt-15" value="0.00" readonly="" id="scharges_total" tabindex="-1">
                                   
                                </div>
                            </div>
                            <hr>
                            <div class="row" style="margin-right:2px !important;">
                                <div class="col-md-7 no-right">
                                    <label style="font-size:16px;font-weight: bold;margin-left:0px">Credit Amt.</label>
                                </div>
                                <div class="col-md-5">
                                   <input type="hidden" style="font-size: 20px;" class="form-control mt-15" value="0.00" readonly="" id="ggrand_total" name="charges_total">
                                    <input type="text" style="font-size: 20px;width:200px;" class="form-control mt-15" value="0.00" readonly="" id="sggrand_total" tabindex="-1">  
                                   
                                </div>
                            </div>
                            
                             <div class="row" style="margin-right:2px !important;">
                                <div class="col-md-12">
                                    
                                    <input type="hidden" value="" id="creditaccountid" class="form-control mt-15">
                                    <input type="hidden" value="" id="totalcreditamount" class="form-control mt-15">
                                    <input type="hidden" value="" id="totalcreditbalance" class="form-control mt-15">
                                </div>
                            </div>
                          
                        </div>
                    </div>
                </div><!--col-md-12-->


               
                
             
                    
                            <button type="button" class="btn btn-success saveprintBtn btn-block" name="addbillingprint" id="addbillingprint"><i class="fa fa-save"></i>Save & Print</button>
                           
                          
                            <div class="col-md-12" style="text-align:center;margin:10px 0 0 0;">                  
                            <button type="button" class="btn btn-info savenewBtn btn-block" name="addbilling" id="addbilling"><i class="fa fa-save"></i>Save & New</button>




            </div><!--hk-row-->
        </div><!--col-xl-3-->
    </div><!--row-->
    </div>
    </div>
</form>
<link rel="stylesheet" href="{{URL::to('/')}}/public/build/css/intlTelInput.css">
<div class="modal fade" id="addcustomerpopup" style="border:1px solid !important;">
        <div class="modal-dialog">
         <form id="customerform">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Customer Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                 </div>

                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Customer Name</label>
                                    <input type="text" maxlength="50" autocomplete="off" name="customer_name" id="pcustomer_name" value="" class="form-control form-inputtext invalid" placeholder="">
                                     <input type="hidden" name="customer_id" id="pcustomer_id" value="">
                                     <input type="hidden" name="customer_address_detail_id" id="pcustomer_address_detail_id" value="">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Mobile No.</label>
                                    <input type="tel" autocomplete="off" name="customer_mobile" id="pcustomer_mobile" value="" maxlength="15" class="form-control form-inputtext invalid mobileregax" placeholder=""
                                    style="width:235px !important;">
                                    <input type="hidden" name="customer_mobile_dial_code" id="customer_mobile_dial_code" value="">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Email</label>
                                    <input type="text" autocomplete="off" maxlength="50" name="customer_email" id="pcustomer_email" value=""  class="form-control form-inputtext" placeholder="">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">GSTIN</label>
                                    <input type="text" maxlength="15" name="customer_gstin" id="pcustomer_gstin" value=""  class="form-control form-inputtext" placeholder="">
                                </div>

                                <div class="col-md-4 ">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="text" maxlength="15" name="customer_date_of_birth" id="pcustomer_date_of_birth" value=""  class="form-control form-inputtext" placeholder="">
                                </div>

                                <div class="col-md-4 ">
                                    <label class="form-label">Address</label>
                                    <input type="text" maxlength="100" name="customer_address" id="pcustomer_address" value=""  class="form-control form-inputtext" placeholder="">
                                </div>

                                <div class="col-md-4 ">
                                    <label class="form-label">Area</label>
                                    <input type="text" maxlength="100" name="customer_area" id="pcustomer_area" value=""  class="form-control form-inputtext" placeholder="">
                                </div>

                                <div class="col-md-4 ">
                                    <label class="form-label">City / Town</label>
                                    <input type="text" maxlength="100" name="customer_city" id="pcustomer_city" value=""  class="form-control form-inputtext" placeholder="">
                                </div>

                                <div class="col-md-4 ">
                                    <label class="form-label">Pin / Zip Code</label>
                                    <input type="text" maxlength="20" name="customer_pincode" id="pcustomer_pincode" value=""  class="form-control form-inputtext" placeholder="">
                                </div>

                                <div class="col-md-4 ">
                                    <label class="form-label">State / Region</label>
                                    <select name="state_id" id="pstate_id" class="form-control form-inputtext">
                                        <option value="0">Select State</option>
                                        @foreach($state AS $statekey=>$statevalue)
                                            <option value="{{$statevalue->state_id}}">{{$statevalue->state_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4 ">
                                    <label class="form-label">Country</label>
                                    <select name="country_id" id="pcountry_id" class="form-control form-inputtext">
                                        <option value="">Select Country</option>
                                        @foreach($country AS $countrykey=>$countryvalue)
                                            <option <?php if($countryvalue['country_id'] == '102') echo "selected"  ?> value="{{$countryvalue->country_id}}">{{$countryvalue->country_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4 pt-25">
                                <button type="button" id="savecustomer" name="savecustomer" class="btn btn-info saveBtn"><i class="fa fa-save"></i>Add Customer</button>
                                

                            </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">&nbsp;
                            </div>
                        </div>

                    </div>
        </div>
        </form>  
          
          </div>
        </form>
        </div>
    </div>
<div class="modal fade" id="addreturnpopup" style="border:1px solid !important;">
        <div class="modal-dialog" style="max-width:90% !important;">
         <form id="returnbillsdetails">
            <div class="modal-content" style="height:600px;overflow-y:scroll;overflow-x:none;">                
               
               <div class="modal-header" style="Padding: 0.50rem 0.25rem 0 0.25rem !important;">
                <div class="row ma-0">
                <div class="col-sm">
                    <div class="row">
                        <div class="col-md-4 ">                            
                             <div class="form-group">
                            </div>
                        </div>
                          <div class="col-md-4 "> 
                                <center><h5 class="modal-title">Bill Details : <span class="invoiceno"></span></h5></center>
                        </div>
                        <div class="col-md-4 ">
                             <div class="form-group"  style="float:right;">
                          
                        </div>
                        </div>
                       
                    </div>
                </div>
               
            </div>
                
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
               </div>
               <br>
               <div class="popup_values" style="width:95%;margin:0 auto !important;">
                 <table border="0" frame="" width="100%">
                            <thead>
                                <tr>
                                    <th scope="col" style="width:9%;cursor: pointer;">Select</th>
                                    <th scope="col" style="width:9%;cursor: pointer;">Bill No.</th>
                                    <th scope="col" style="width:9%;cursor: pointer;">Bill Date</th>
                                    <th scope="col" style="width:13%;cursor: pointer;">Customer Name</th>
                                    <th scope="col" style="width:9%;cursor: pointer;text-align:right !important;">Qty</th>
                                    <th scope="col" style="width:13%;cursor: pointer;text-align:right !important;">Taxable Value</th>
                                    <?php
                                    if($tax_type==1)
                                    {
                                        ?>
                                        <th scope="col" style="width:15%;cursor: pointer;text-align:right !important;">{{$tax_title}} Amt.</th>
                                        
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <th scope="col" style="width:9%;cursor: pointer;text-align:right !important;">CGST Amt.</th>
                                        <th scope="col" style="width:9%;cursor: pointer;text-align:right !important;">SGST Amt.</th> 
                                        <?php
                                    }
                                    ?>
                                      
                                    <th scope="col" style="width:13%;cursor: pointer;text-align:right !important;">Bill Amount</th>
                                    <th scope="col" style="width:7%;cursor: pointer;text-align:center !important;">Action</th>

                                </tr>
                                </thead>
                                <tbody id="productdetails">
                                </tbody>
                                <tfoot>
                                 <tr>
                                 <td colpsan="9">&nbsp;</td>
                                 </tr>   
                                </tfoot>
                            </table>
               </div>

        </div>
        </form>  
          
          </div>
        </form>
        </div>
    </div>    
    

    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>
    <script src="{{URL::to('/')}}/public/dist/js/moment.min.js"></script>
    <script src="{{URL::to('/')}}/public/dist/js/daterangepicker.js"></script>
    <script type="text/javascript">
   
           $('.daterange').daterangepicker({ 

                         
                autoUpdateInput: false,  
                allowEmpty: true,      
           
                },function(start_date, end_date) {

            
        $('.daterange').val(start_date.format('DD-MM-YYYY')+' - '+end_date.format('DD-MM-YYYY'));
 
        });

    </script>
    <!-- THis code create problem in in JS file thats why put in main File-->


<!--- Again same problem while daterangepicker fields loaded in edit times thats why placed code in Main file -->




    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/popper.js/js/popper.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/dist/js/datepicker.js"></script>-
  

    

    <script src="{{URL::to('/')}}/public/dist/js/bootstrap-typeahead.js"></script>
    <script src="{{URL::to('/')}}/public/modulejs/sales/returnbill.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/build/js/intlTelInput.js"></script>

    <script>
    $(document).ready(function () {

        $('#bill_no').focus();
        selectdiacode();
    });


    function selectdiacode()
    {
    var input = document.querySelector("#pcustomer_mobile");
    window.intlTelInput(input, {
    initialCountry: "in",
    separateDialCode: true,
    utilsScript: "{{URL::to('/')}}/public/build/js/utils.js",
    });
    }


</script>
@endsection
