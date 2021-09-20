@include('pagetitle')
@extends('master')

@section('main-hk-pg-wrapper')  

<style type="text/css">
.display-4{
    font-size:1.5rem !important;
}
.table thead tr.header th {
   
    font-size: 0.95rem !important;
}
.table tbody tr td {
   
    font-size: 0.92rem !important;
}
.form-inputtext {
    height: calc(2.0rem + 1.5px);
    margin-bottom: 0.50rem;
}

</style>
<link rel="stylesheet" href="http://localhost/retailcore/public/bower_components/bootstrap-datepicker/css/bootstrap-datepicker.css">

<script src="{{URL::to('/')}}/public/template/jquery/dist/jquery.min.js"></script>
<form id="viewbillform" name="viewbillform">
<div class="container ml-20">
<div class="row">
    <div class="col-xl-12">  
    <section class="hk-sec-wrapper" style="padding: 0.8rem 1.5rem 0 1.5rem !important;margin-top:5px !important;margin-bottom:5px !important;">
        <center><h4 class="hk-sec-title"><b>Manage Customer Credit Payments</b></h4></center>
        <h5 class="hk-sec-title">Ledger Balance : <span class="ledgerbalance" style="font-size:20px;color:#DB3E2D;font-weight:bold;"></span></h5>
 
                
    </section>


    <div class="card">
            <div class="card-body pr-0 pl-0">
                <div class="row ma-0">
                    <div class="col-sm-12 pa-0">
                        <div class="table-wrap">
                            <div class="table-responsive">
                                   <table class="table tablesaw view-bill-screen table-hover w-100 display pb-30 dataTable dtr-inline tablesaw-swipe" data-tablesaw-sortable-switch data-tablesaw-minimap data-tablesaw-mode-switch role="grid" aria-describedby="datable_1_info" cellpadding="6">
                                          


                                            <thead>
                                               
                                            <tr class="header">                                              
                                                <th scope="col" style="width:5%;cursor: pointer;text-align:left !important;" ></th>
                                                <th scope="col" style="width:14%;cursor: pointer;text-align:left !important;">Invoice No.</th>
                                                <th scope="col" style="width:14%;cursor: pointer;text-align:left !important;">Customer Name</th>
                                                <th scope="col" style="width:14%;cursor: pointer;text-align:left !important;">Bill Date</th>
                                                <th scope="col" style="width:14%;cursor: pointer;text-align:right !important;">Outstanding Amount</th>
                                                <th scope="col" style="width:14%;cursor: pointer;text-align:right !important;">Received Amount</th>
                                                <th scope="col" style="width:14%;cursor: pointer;text-align:right !important;">Balance Amount</th>
                                            
                                            </tr>
                                            </thead>
                                            <tbody id="view_bill_record">
                                                @include('creditbalance::customer_credit_acdata')
                                               

                                           
                                            </tbody>
                                      
                                            <tr>
                                                <td colspan="6" style="text-align:right;">
                                                <button type="button" id="makepayment" name="makepayment" class="btn btn-info">Make Payment</button>
                                                </td>
                                                <td style="text-align:left;">
                                                <span id="grandoverall" style="border:none;font-size:20px;color:#DB3E2D;font-weight:bold;"></span>
                                                <input type="hidden" class="mCamera" id="mCamera"></span>
                                                </td>
                                                </tr>
                                           
                                        </table>
                                        
                            </div>
                        </div><!--table-wrap-->
                    </div>
                </div>
            </div><!--card-body-->
        </div>

      </div>
      </div>
      </div>  
   </form>  

<div class="modal fade" id="addcuspaymentpopup" style="border:1px solid !important;">
        <div class="modal-dialog">
         <form id="customerform">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Receive Credit Payments</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                 </div>
                 <br>
                    <div class="row ma-0">
                        <div class="col-sm-12">
                            <div class="row ma-0">
                               <div class="col-md-8">
                                 <div class="row ma-0">
                                <div class="col-sm-10">
                                    <label class="form-label">Receipt No. :  <b style="color:#000;font-size:18px;">{{$invoiceno}}</b></label><b></b>&nbsp;
                                    <input type="hidden" maxlength="50" autocomplete="off" name="receipt_no" id="receipt_no" value="{{$invoiceno}}" class="form-control form-inputtext" placeholder="">
                                    <input type="hidden" id="customerid" value="{{$cusid}}">
                                    <input type="hidden" id="customer_creditreceipt_id" value="">
                                     
                                </div>
                                 <div class="col-sm-10">
                                    <label class="form-label">Date</label>
                                    <input type="text" maxlength="50" autocomplete="off" name="invoice_date" id="invoice_date"  value="{{date("d-m-Y")}}" class="form-control form-inputtext" placeholder="">
                                     
                                </div>
                                <div class="col-sm-10">
                                    <label class="form-label">Amount to Receive</label>
                                    <input type="text" maxlength="50" autocomplete="off" name="total_amount" id="total_amount" value="" class="form-control form-inputtext" placeholder="" style="font-weight:bold;">
                                     
                                </div>
                                <div class="col-sm-10">
                                    <label class="form-label">Remarks</label>
                                    <textarea name="remarks" id="remarks" value="" rows="2" class="form-control form-inputtext" placeholder="Bank Name, Cheque No."></textarea>
                                     
                                </div>
                             </div>
                               </div>
                                <div class="col-md-4" style="border:0px solid !important;">
                                    <div class="card">
                                     <div class="card-body pr-0 pl-0" id="paymentmethoddiv">
                                       
                                                  <h5 class="card-title center"><center>Payment Method</center></h5>
                                             
                                    @foreach($payment_methods AS $payment_methods_key=>$payment_methods_value)
                                <?php

                                    if($payment_methods_value->payment_method_id != 6 && $payment_methods_value->payment_method_id != 8)
                                    {

                                    
                                     if($payment_methods_value->payment_method_name == 'Cash')
                                     {
                                        $class  =   "font-weight:bold;font-size:16px;";
                                     }
                                     else
                                     {
                                        $class = '';
                                     }


                                ?>

                                            <div class="row" style="margin-right:2px !important;">
                                                  
                                                <div class="col-sm-7 no-right">
                                                    <label for="card" style="{{$class}}">{{$payment_methods_value->payment_method_name}}</label>
                                                </div>
                                                <div class="col-sm-5">
                                                    <input type="text" value="" data-id="{{$payment_methods_value->payment_method_id}}" class="form-control form-inputtext number" id="{{$payment_methods_value->html_id}}" name="{{$payment_methods_value->html_name}}" style="{{$class}}">
                                                    <input type="hidden" value="" class="form-control mt-15 number" id="sales_payment_detail{{$payment_methods_value->payment_method_id}}" name="{{$payment_methods_value->html_name}}" >
                                                    
                                                </div>
                                            </div>

                                      <?php
                                      } 
                                      ?>      
                                             @endforeach

                                </div>
                            </div>
                        </div></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <button type="button" id="addpayment" name="addpayment" class="btn btn-info">Add Payment</button>
                                

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
   

    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>

        <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/popper.js/js/popper.min.js"></script>
        <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="{{URL::to('/')}}/public/dist/js/datepicker.js"></script>-
       <script src="{{URL::to('/')}}/public/dist/js/bootstrap-typeahead.js"></script>
       <script src="{{URL::to('/')}}/public/modulejs/common.js"></script>
       <script src="{{URL::to('/')}}/public/modulejs/sales/creditbill.js"></script>


@endsection
</html>



