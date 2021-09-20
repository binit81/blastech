@include('pagetitle')
@extends('master')

@section('main-hk-pg-wrapper')

 <link rel="stylesheet" href="{{URL::to('/')}}/public/bower_components/bootstrap-datepicker/css/bootstrap-datepicker.css">
 <link rel="stylesheet" href="{{URL::to('/')}}/public/bower_components/sweetalert/css/sweetalert.css">


 <style type="text/css">
     .modal-content .form-control[readonly] {
         border: 1px solid #ced4da !important;
         background: transparent;
         color: #000 !important;
         font-size: 0.8rem;
         font-weight: bold;
     }
 </style>

 <form name="inwardstock" id="inwardstock" method="post" enctype="multipart/form-data">
            <input type="hidden" name="inward_type" id="inward_type" value="{{$inward_type}}">
            <input type="hidden" name="product_id" id="product_id" value="0">
            <input type="hidden" name="inward_stock_id" id="inward_stock_id" value="">
            <input type="hidden" name="company_state_id" id="company_state_id" value="{{$company_state_id}}">
            <input type="hidden" name="billing_type" id="billing_type" value="{{$bill_type}}">


            {{--if update_offer_price = 1 then update all this product qty with new offer price--}}
            <input type="hidden" name="update_offer_price" id="update_offer_price" value="0">
        
<div class="container">
    
    <span class="commonbreadcrumbtn badge badge-pill downloadBtn mr-0"  id="downloadtmpate"><i class="ion ion-md-download"></i>&nbsp;Download Inward Template</span></a>

  {{--  <a href="{{ URL::to('inward_template')  }}">--}}

    <a href="{{URL::to('view_inward_stock')}}">
    <span class="commonbreadcrumbtn badge viewBtn badge-pill" id="searchCollapse"><i class="ion ion-md-apps"></i>&nbsp;View Inward</span></a>

    

        <div class="col-xl-12">
                <div class="hk-row">
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Invoice Date</label>
                                    <input class="form-control form-inputtext invalid" value="" autocomplete="off" type="text" name="invoice_date" id="invoice_date" placeholder=" ">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Inward Date</label>
                                    <input class="form-control form-inputtext invalid" value="" autocomplete="off" type="text" name="inward_date" id="inward_date" placeholder=" ">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Invoice/GRN No. </label>
                                    <input class="form-control form-inputtext invalid" value="" maxlength="" autocomplete="off" type="text" name="invoice_grn_no" id="invoice_grn_no" placeholder=" ">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">PO Number</label>
                                    <input class="form-control form-inputtext number" value="" maxlength="" autocomplete="off" type="text" name="po_no" id="po_no" placeholder=" " disabled>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Supplier</label>
                                    <input class="form-control form-inputtext invalid col-md-9 mr-5" value="" maxlength="" type="text" name="supplier_name" id="supplier_name" placeholder=" ">
                                    <input type="hidden" name="gst_id" id="gst_id" value="">
                                    <input type="hidden" name="state_id" id="state_id" value="">

                                    {{--<button type="button" title="Add Supplier" class="btn btn-info" id="newsupplier" name="newsupplier"><i class="fa fa-plus"></i></button>--}}
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Note</label>
                                    <textarea class="form-control form-inputtext" value="" maxlength=""  name="note" id="note" data-id="note" data-field_name="payment_method_id" rows="3" placeholder="Cheque No.,Bank Name"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="col-sm-5">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <small class="badge badge-soft-success mt-15 mr-10"><b>Total Qty</b></small>
                                    <!-- <label class="form-label">Total Qty</label> -->
                                    <input readonly class="form-control form-inputtext" value="" style="color: black;font-size: 30px" maxlength="" autocomplete="off" type="text" name="total_qty" id="total_qty" placeholder=" ">
                                </div>
                                <div class="col-md-4">
                                    <small class="badge badge-soft-success mt-15 mr-10"><b>Gross Total</b></small>
                                    <!-- <label class="form-label">Gross Total</label> -->
                                    <input readonly class="form-control form-inputtext" style="color: black;font-size: 30px" value="" maxlength="" autocomplete="off" type="text" name="gross_total_disp" id="gross_total_disp" placeholder=" ">
                                    <input type="hidden" name="gross_total" id="gross_total" value="">
                                </div>
                                <div class="col-md-4">
                                    <small class="badge badge-soft-success mt-15 mr-10"><b>Grand Total</b></small>
                                    <!-- <label class="form-label">Grand Total </label> -->
                                    <input readonly class="form-control form-inputtext" value="" style="color: black;font-size: 30px" maxlength="" autocomplete="off" type="text" name="grand_total_disp" id="grand_total_disp" placeholder=" ">
                                    <input type="hidden" name="grand_total" id="grand_total" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12" id="paymentdiv">
                    <div class="card invalid">
                    <div class="card-body">
                        <h5>Payment Method</h5>
                        <div class="row" id="paymentmethoddiv">
                                @foreach($payment_methods AS $payment_methods_key=>$payment_methods_value)
                                    @if($payment_methods_value['payment_method_id'] != '4' && $payment_methods_value['payment_method_id'] != '8')
                                    <div class="col-md-4 paymentdiv">
                                        <label class="form-label">{{$payment_methods_value->payment_method_name}}</label>
                                        <input class="form-control form-inputtext number" value="" maxlength="" autocomplete="off" type="text" name="{{$payment_methods_value->html_id}}" id="{{$payment_methods_value->html_id}}" data-id="{{$payment_methods_value->payment_method_id}}" data-field_name="payment_method_id" placeholder=" ">
                                        <input type="hidden" name="outstanding_payment_{{$payment_methods_value->payment_method_id}}" id="outstanding_payment_{{$payment_methods_value->payment_method_id}}" value="">
                                        <input type="hidden" name="supplier_payment_detail_id_{{$payment_methods_value->payment_method_id}}" id="supplier_payment_detail_id_{{$payment_methods_value->payment_method_id}}" value="">
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                    </div>
                    </div>
                </div>
            </div>

                    <div class="col-md-3">

                        <div class="card">
                            <div class="card-body">
                                <h5>Upload Inward</h5>
                                <input type="file" class="" id="fileUpload"  accept=".xlsx, .xls" />
                                <button type="button"  class="btn btn-info btn-block mt-10 uploadBtn" name="upload" id="upload"><i class="fa fa-upload"></i>&nbsp;Upload</button>
                                      <!-- <input type="button" id="upload" class="btn btn-info addbutton btn-block mt-10" value="Upload" /> -->
                                       <input type='hidden' name='rec' id='rec' data-json=''>
                                       <input type='hidden' name='rec_product' id='rec_product' data-json=''>
                                
                            </div>
                            <div class="row">
                                <div class="col-sm-5 pa-0 pl-25 ml-10 unpaid" style="display: none">
                                    <label class="form-label">Due Days</label>
                                    <input type="text" class="form-control form-inputtext number" id="inward_unpaid_amt_due_days" name="inward_unpaid_amt_due_days" placeholder="Enter Days">
                                </div>
                                <div class="col-sm-5 pa-0 pl-5 ml-10 unpaid" style="display: none">
                                    <label class="form-label">Due Date</label>
                                    <input type="text" class="form-control form-inputtext" id="inward_unpaid_due_date" name="inward_unpaid_due_date" placeholder="Choose Date"  style="color:#000 !important;" tabindex="-1">
                                </div>

                            <?php  if($inward_calculation == 2) { ?>
                            <div class="col-sm-5 pa-0 pl-5 ml-10">
                                <button type="button" name="roundoff_offer" class="btn btn-success saveBtn btn-block mb-10 ml-20" id="roundoff_offer">
                                    <i class="fa fa-circle"></i>Round Off</button>
                            </div>
                                <?php } ?>

                            <div class="col-sm-5 pa-0 pl-5 ml-10">
                                <button type="button" <?php  if($inward_calculation == 2) echo "disabled" ?> name="addinwardstock" class="btn btn-info mb-10 ml-20 savenewBtn btn-block" id="addinwardstock"><i class="fa fa-save"></i>Save</button>
                            </div>

                        </div>

                        </div>
                    </div>
                </div>
                </div>
                </div>
        </form>
        <div class="col-xl-12">
                <div class="hk-row">
                    <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-wrap">
                            <div class="table-responsve">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="input-group">
                                        <span class="input-group-prepend"><label class="input-group-text" style="height: 40px;padding-top: 5px"><i class="fa fa-search"></i></label></span>
                                        <input class="form-control form-inputtext" value="" maxlength="" type="text" name="productsearch" id="productsearch" placeholder="Barcode/Product Code/Product Name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                    </div>

                                </div>

                                @include('inward_stock::inward/inward_stock_table')

                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>



     <div class="modal fade" id="inwarddebitnotepopup" tabindex="-1" role="dialog" aria-labelledby="inwarddebitnotepopup" aria-hidden="true" data-backdrop="static">

     <div class="modal-dialog" style="max-width:30% !important;">
        <form name="debitnoteform_inward" id="debitnoteform_inward">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title">Debit Note Details</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
             </div>
             <br>
             <div class="row">
                 <div class="col-sm">
                     <div class="row">
                         <div class="col-md-5">
                             <label class="form-label" style="text-align:right !important;">Debit Note No.</label>
                         </div>
                         <div class="col-md-7">
                             <input type="text" name="debit_note_no" id="debit_note_no" value=""  class="form-control form-inputtext" placeholder="Enter Debit Note No.">
                             <input type="hidden" name="debit_note_id" id="debit_note_id" value=""  class="form-control form-inputtext">
                         </div>
                         <div class="col-md-5">
                             <label class="form-label" style="text-align:right !important;">Debit Amount</label>
                         </div>
                         <div class="col-md-7">
                             <input type="text" name="debit_note_amount" id="debit_note_amount" value=""  class="form-control form-inputtext" readonly placeholder="">
                             <input type="hidden" name="debit_note_amount_for_minus" id="debit_note_amount_for_minus" value=""  class="form-control form-inputtext" placeholder="">
                         </div>
                         <div class="col-md-5">
                             <label class="form-label" style="text-align:right !important;">Issue Amount</label>
                         </div>
                         <div class="col-md-7">
                             <input type="text" name="debit_note_issue_amount" id="debit_note_issue_amount" value=""  class="form-control form-inputtext number" placeholder="">
                         </div>
                     </div>
                 </div>
                 <div class="row">
                     <div class="col-md-4">
                         <input type="hidden" class="alertStatus" value="0" />
                         <button type="button" id="save_debit_note" name="save_debit_note" class="btn btn-info">Add</button>
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

 </div>
    <div id="styleSelector">
    </div>

    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/popper.js/js/popper.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/dist/js/datepicker.js"></script>
    <script src="{{URL::to('/')}}/public/bower_components/sweetalert/js/sweetalert.min.js"></script>
  
    <script src="{{URL::to('/')}}/public/modulejs/inward_stock/inwardstock.js"></script>


 <?php
 if($inward_calculation == 1) { ?>
     <script src="{{URL::to('/')}}/public/modulejs/inward_stock/inwardstock_calculation.js"></script>
 <?php } else {?>
    <script src="{{URL::to('/')}}/public/modulejs/inward_stock/inwardstock_roundoff_calculation.js"></script>
 <?php } ?>
    <script src="{{URL::to('/')}}/public/modulejs/inward_stock/import_inward.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/dist/js/xlsx.full.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/dist/js/jszip.js"></script>

    <script src="{{URL::to('/')}}/public/dist/js/tablesaw-data.js"></script>

    <script>
        var inward_calculation = '<?php echo $inward_calculation?>';


       /* $(window).on("beforeunload", function() {
            return confirm("Do you really want to close?");
        })*/
    </script>

    </body>
    </html>
@endsection






































