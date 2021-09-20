@include('pagetitle')
@extends('master')

@section('main-hk-pg-wrapper')

    <html>
    <head>
        <title>

        </title>

        <link rel="stylesheet" href="{{URL::to('/')}}/public/bower_components/bootstrap-datepicker/css/bootstrap-datepicker.css">
        <link rel="stylesheet" href="{{URL::to('/')}}/public/bower_components/sweetalert/css/sweetalert.css">

    </head>
    <body>

    <div class="container">
        <a href="{{URL::to('view_debit_note')}}"><span class="commonbreadcrumbtn badge viewBtn badge-pill"  id="searchCollapse"><i class="ion ion-md-apps"></i>&nbsp;View Debit Note</span></a>

    <form name="debit_note_form" id="debit_note_form" method="post" enctype="multipart/form-data">

        <input type="hidden" name="debit_note_id" id="debit_note_id" value="">
        <input type="hidden" name="company_state_id" id="company_state_id" value="{{$company_state_id}}">
        <input type="hidden" name="supplier_state_id" id="supplier_state_id" value="">
        <input type="hidden" name="inward_type" id="inward_type" value="">

        <div class="col-xl-12">
            <div class="hk-row">
                <div class="col-sm-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Invoice No</label>
                                    <input class="form-control form-inputtext invalid" value="" maxlength="" type="text" name="invoice_no" id="invoice_no" placeholder=" ">
                                    <input type="hidden" name="inward_stock_id" id="inward_stock_id" value="">
                                    <input type="hidden" name="gst_id" id="gst_id" value="">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    {{--<label class="form-label">Debit Receipt No.</label>--}}
                                    <input class="form-control form-inputtext invalid" value="<?php echo $debit_no?>" style="color:black;font-size: 20px" autocomplete="off" type="text" name="debit_no" id="debit_no" placeholder=" " readonly="readonly">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Debit Date</label>
                                    <input class="form-control form-inputtext invalid" value="" autocomplete="off" type="text" name="debit_date" id="debit_date" placeholder=" ">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-6">
                                    <label class="form-label">Supplier Company Name</label>
                                    <input  readonly="readonly" style="color:black;font-size: 1rem" class="form-control form-inputtext invalid" value="" autocomplete="off" type="text" name="supplier_company" id="supplier_company" placeholder=" ">
                                </div>


                                <div class="col-md-6">
                                    <label class="form-label">Supplier Name</label>
                                    <input readonly="readonly" style="color:black;font-size: 1rem"  class="form-control form-inputtext invalid" value="" autocomplete="off" type="text" name="supplier_name" id="supplier_name" placeholder=" ">
                                </div>


                                <div class="col-md-12">
                                    <label class="form-label">Note</label>
                                    <textarea  class="form-control"  name="debit_note_note" id="debit_note_note" ></textarea>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>

                <?php
                $tax_label = 'GST';


                if($nav_type[0]['tax_type'] == 1)
                {
                    $tax_label = $nav_type[0]['tax_title'];
                }
                ?>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-6">
                                    <label class="form-label">Total Qty</label>
                                    <input class="form-control form-inputtext invalid" style="color:black;font-size: 20px" value="0" autocomplete="off" type="text" name="total_qty" id="total_qty" placeholder=" " readonly="readonly">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Total Cost Rate</label>
                                    <input class="form-control form-inputtext invalid" style="color:black;font-size: 20px" value="0" autocomplete="off" type="text" name="total_cost_rate" id="total_cost_rate" placeholder=" " readonly="readonly">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Total <?php echo $tax_label?></label>
                                    <input class="form-control form-inputtext invalid" style="color:black;font-size: 20px" value="0" autocomplete="off" type="text" name="total_gst" id="total_gst" placeholder=" " readonly="readonly">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Total Cost Price</label>
                                    <input class="form-control form-inputtext invalid" style="color:black;font-size: 20px" value="0" autocomplete="off" type="text" name="total_cost_price" id="total_cost_price" placeholder=" " readonly="readonly">
                                </div>
                            </div>


                            {{--<button type="button" class="btn btn-success saveBtn btn-block" name="adddebitprint" id="adddebitprint">
                                <i class="fa fa-save"></i>Save & Print</button>

                            <div class="col-md-2 mt-2">
                                <a id="print_save_debit" href="{{URL::to('print_debit_note')}}?id=param" style="text-decoration:none !important;" target="_blank" title="Print">
                                </a>
                            </div>

                            <button type="button" class="btn btn-info savenewBtn btn-block" name="adddebit" id="adddebit">
                            <i class="fa fa-save"></i>Save & New</button>--}}


                            <div class="row pa-0 ma-0">
                                <div class="col-sm-6 pa-0 pr-5">
                                    <button type="button" class="btn btn-success saveBtn btn-block" name="adddebitprint" id="adddebitprint">
                                        <i class="fa fa-print"></i>Save & Print</button>
                                    <a id="print_save_debit" href="{{URL::to('print_debit_note')}}?id=param" style="text-decoration:none !important;" target="_blank" title="Print">
                                    </a>
                                </div>

                                <div class="col-sm-6 pa-0 pl-5">
                                    <button type="button" class="btn btn-info savenewBtn btn-block" name="adddebit" id="adddebit">
                                        <i class="fa fa-save"></i>Save & New</button>
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
                                <div class="hk-row">
                                <div class="col-md-4" style="display: none" id="product_search_block">
                                    <div class="input-group">
                                        <span class="input-group-prepend"><label class="input-group-text" style="height: 40px;padding-top: 13px"><i class="fa fa-search"></i></label></span>
                                        <input class="form-control form-inputtext" value="" maxlength="" type="text" name="debit_productsearch" id="debit_productsearch" placeholder="Barcode/Product Code/Product Name">
                                        <input type="hidden" name="invoice_product_val" id="invoice_product_val" value="">
                                    </div>
                                </div>
                                    <div class="col-md-11">
                                    </div>
                                    <div class="col-md-1 rightAlign">
                                        <h5 class="hk-sec-title">
                                            <small class="badge badge-soft-danger mt-15 mr-10"><b>No. of Items:</b>
                                                <span class="debitnotetotalitems">0</span>
                                            </small>
                                        </h5>

                                    </div>
                                </div>
                                <table class="table table-bordered tablesaw view-bill-screen table-hover w-100 display pb-30 dataTable dtr-inline tablesaw-sortable tablesaw-swipe" data-tablesaw-mode="swipe"  data-tablesaw-sortable-switch data-tablesaw-minimap data-tablesaw-mode-switch role="grid" aria-describedby="datable_1_info" id="inwardtable">

                                    <thead>
                                    <tr class="header">
                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">Barcode</th>
                                        <th readonly scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Prod Name</th>
                                        <th class="fmcg" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Batch No.</th>
                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Base Price</th>
                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">Cost Rate</th>
                                        <th colspan="2" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6"><?php echo $tax_label?> % & Amt</th>
                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="7">Offer Price</th>
                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">MRP</th>
                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="9">In Stock</th>
                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="10">Pending Return Qty</th>
                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="11">Qty</th>
                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="12">Free Qty</th>
                                        <th id="po_no_show"scope="col" data-tablesaw-sortable-col data-tablesaw-priority="13">Return  Qty</th>
                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="14">Total <?php echo $tax_label?></th>
                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="15">Total Cost Rate</th>
                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="16">Total Cost Price</th>
                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="17">Remarks</th>
                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="18"><i class="fa fa-remove"></i></th>
                                    </tr>
                                    </thead>
                                    <tbody id="debit_product_detail_record">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/popper.js/js/popper.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/dist/js/datepicker.js"></script>
    <script src="{{URL::to('/')}}/public/bower_components/sweetalert/js/sweetalert.min.js"></script>
    <script src="{{URL::to('/')}}/public/modulejs/common.js"></script>
    <script src="{{URL::to('/')}}/public/modulejs/debit_note/debit_note.js"></script>

        <script>
            var tax_type = '<?php echo $tax_type?>';
            </script>
    </body>
    </html>
@endsection






































