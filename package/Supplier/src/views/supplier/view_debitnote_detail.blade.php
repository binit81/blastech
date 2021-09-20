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
            .modal-content .form-control[readonly] {
                border: 1px solid #ced4da !important;
                background: transparent;
                color: #000 !important;
                font-size: 0.8rem;
                font-weight: bold;
            }


        </style>
        <link rel="stylesheet" href="http://localhost/retailcore/public/bower_components/bootstrap-datepicker/css/bootstrap-datepicker.css">

        <script src="{{URL::to('/')}}/public/template/jquery/dist/jquery.min.js"></script>
        <form id="viewbillform" name="viewbillform">
            <section class="hk-sec-wrapper" style="padding: 0.8rem 1.5rem 0 1.5rem !important;margin-top:5px !important;margin-bottom:5px !important;">
                <center><h4 class="hk-sec-title"><b>Manage Supplier Debit Payments</b></h4></center>
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
                                            <th scope="col" style="width:10%;cursor: pointer;text-align:left !important;">Supplier Company Name</th>
                                            <th scope="col" style="width:10%;cursor: pointer;text-align:left !important;">Invoice No</th>
                                            <th scope="col" style="width:10%;cursor: pointer;text-align:left !important;">Supplier Name_GSTIN</th>
                                            <th scope="col" style="width:10%;cursor: pointer">Mobile No.<span id="supplier_company_mobile_no _icon"></span></th>
                                            <th scope="col" style="width:10%;cursor: pointer;text-align:left !important;">Inward Date</th>
                                            <th scope="col" style="width:15%;cursor: pointer;text-align:right !important;">Outstanding Amount</th>
                                            <th scope="col" style="width:15%;cursor: pointer;text-align:right !important;">Paid Amount</th>
                                            <th scope="col" style="width:15%;cursor: pointer;text-align:right !important;">Balance Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody id="outstanding_detail">
                                        @include('supplier::supplier/view_debitnote_detail_data')
                                        </tbody>
                                        <tr>
                                            <td colspan="7" style="text-align:right;">
                                                <button type="button" id="makepayment" name="makepayment" class="btn btn-info">Make Payment</button>
                                            </td>
                                            <td style="text-align:left;">
                                                <span id="grandoverall" style="border:none;font-size:20px;color:#DB3E2D;font-weight:bold;">0</span>
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
        </form>

        <div class="modal fade" id="supplier_debit_popup" data-backdrop="static" style="border:1px solid !important;">

            <div class="modal-dialog">
                <input type="hidden" id="supplier_gst_id" name="supplier_gst_id" value="">
                <form id="supplier_debit_form">
                    <div class="modal-content" style="width: 712px;">
                        <div class="modal-header">
                            <h4 class="modal-title">Debit Payments</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <br>
                        <div class="row ma-0">
                            <div class="col-sm-12">
                                <div class="row ma-0">
                                    <div class="col-md-6">
                                        <div class="row ma-0">
                                            <div class="col-sm-10">
                                                <label class="form-label">Receipt No. :  <b style="color:#000;font-size:18px;"><?php echo $final_receipt_no ?></b></label><b></b>&nbsp;
                                                <input type="hidden" maxlength="50" autocomplete="off" name="receipt_no" id="receipt_no" value="<?php echo $final_receipt_no ?>" class="form-control form-inputtext" placeholder="">
                                            </div>
                                            <div class="col-sm-10">
                                                <label class="form-label">Date</label>
                                                <input type="text" maxlength="50" autocomplete="off" name="debit_date" id="debit_date"  value="{{date("d-m-Y")}}" class="form-control form-inputtext" placeholder="">

                                            </div>
                                            <div class="col-sm-10">
                                                <label class="form-label">Amount to Pay</label>
                                                <input type="text" maxlength="50" autocomplete="off" name="total_amount_pay" id="total_amount_pay" value="" class="form-control form-inputtext" placeholder="" style="font-weight:bold;">

                                            </div>
                                            <div class="col-sm-10">
                                                <label class="form-label">Remarks</label>
                                                <textarea name="remarks" id="remarks" value="" rows="2" class="form-control form-inputtext" placeholder="Bank Name, Cheque No."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="border:0px solid !important;">
                                        <div class="card">
                                            <div class="card-body pr-0 pl-0" id="paymentmethoddiv">
                                                <h5 class="card-title center"><center>Payment Method</center></h5>
                                                @foreach($payment_methods AS $payment_methods_key=>$payment_methods_value)
                                                    <?php

                                                    if($payment_methods_value->payment_method_id != 6 && $payment_methods_value->payment_method_id != 8 && $payment_methods_value->payment_method_id != 4)
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
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="hidden" class="alertStatus" value="0" />

                                    <button type="button" id="add_supplier_payment" name="add_supplier_payment" class="btn btn-info addbtn">Add Payment</button>
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



        <div class="modal fade" id="supplierdebitnotepopup" style="border:1px solid !important;">
            <div class="modal-dialog" style="max-width:30% !important;">

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
                                    <input type="text" name="supplier_debit_note" id="supplier_debit_note" value=""  class="form-control form-inputtext" placeholder="Enter Debit Note No.">
                                    <input type="hidden" name="supplier_debit_note_id" id="supplier_debit_note_id" value=""  class="form-control form-inputtext">
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label" style="text-align:right !important;">Debit Amount</label>
                                </div>
                                <div class="col-md-7">
                                    <input type="text" name="supplier_debit_note_amount" id="supplier_debit_note_amount" value=""  class="form-control form-inputtext" readonly placeholder="">
                                    <input type="hidden" name="supplier_debit_note_amount_for_minus" id="supplier_debit_note_amount_for_minus" value=""  class="form-control form-inputtext" placeholder="">
                                </div>

                                <div class="col-md-5">
                                    <label class="form-label" style="text-align:right !important;">Issue Amount</label>
                                </div>

                                <div class="col-md-7">
                                    <input type="text" name="supplier_debit_note_issue_amount" id="supplier_debit_note_issue_amount" value=""  class="form-control form-inputtext number" placeholder="">
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <button type="button" id="supplier_save_debit_note" name="supplier_save_debit_note" class="btn btn-info">Add</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">&nbsp;
                            </div>
                        </div>

                    </div>

                </div>


            </div>
            </form>
        </div>
        </div>


        <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>

        <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/popper.js/js/popper.min.js"></script>
        <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>

        <script type="text/javascript" src="{{URL::to('/')}}/public/modulejs/supplier/supplier_debit.js"></script>



@endsection
</html>



