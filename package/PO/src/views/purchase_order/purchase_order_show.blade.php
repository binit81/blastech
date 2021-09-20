@include('pagetitle')
@extends('master')

@section('main-hk-pg-wrapper')

    <html>
    <head>
        <title>

        </title>

        <link rel="stylesheet"
              href="{{URL::to('/')}}/public/bower_components/bootstrap-datepicker/css/bootstrap-datepicker.css">
        <link rel="stylesheet" href="{{URL::to('/')}}/public/bower_components/sweetalert/css/sweetalert.css">

    </head>
    <body>

    <form name="issue_po" id="issue_po" method="post" enctype="multipart/form-data">

        <?php
        $po_terms_and_condition = (isset($po_terms_condition) && $po_terms_condition != '' ? $po_terms_condition : '');
        ?>

        <input type="hidden" name="purchase_order_id" id="purchase_order_id" value="">
        <div class="container">

            <a href="{{URL::to('view_issue_po')}}">
                <span class="commonbreadcrumbtn badge viewBtn badge-pill"
                       id="searchCollapse"><i
                            class="ion ion-md-apps"></i>&nbsp;View PO</span></a>

            <div class="col-xl-12">
                <div class="hk-row">
                    <div class="col-sm-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">Supplier</label>
                                        <input class="form-control form-inputtext invalid" value="" maxlength=""
                                               type="text" name="supplier_name" id="supplier_name" placeholder=" ">
                                        <input type="hidden" name="gst_id" id="gst_id" value="">
                                        <input type="hidden" name="state_id" id="state_id" value="">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                       {{-- <label class="form-label">PO No.</label>--}}
                                        <input class="form-control form-inputtext invalid" value="<?php echo $po_no;?>"
                                               style="color:black;font-size: 20px" autocomplete="off" type="text"
                                               name="po_no" id="po_no" placeholder=" " readonly="readonly">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">PO Date</label>
                                        <input class="form-control form-inputtext invalid" value="" autocomplete="off" type="text" name="po_date" id="po_date" placeholder=" ">
                                    </div>


                                    <div class="col-md-12">
                                        <label class="form-label">Note</label>
                                        <textarea name="po_note" id="po_note" class="form-control"></textarea>
                                    </div>

                                </div>


                            </div>
                        </div>
                    </div>


                    <div class="col-md-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">Delivery Date</label>
                                        <input class="form-control form-inputtext invalid" value="" autocomplete="off"
                                               type="text" name="delivery_date" id="delivery_date" placeholder=" ">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Delivery To</label>
                                        <input class="form-control form-inputtext invalid" value="" autocomplete="off"
                                               type="text" name="delivery_to" id="delivery_to" placeholder=" ">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Address</label>
                                        <textarea class="form-control form-inputtext invalid" name="address"
                                                  id="address" rows="3"></textarea>
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
                                        <label class="form-label">Terms & Condition(will display on PO)</label>
                                        <textarea class="form-control form-inputtext" value="" name="terms_condition"
                                                  id="terms_condition" placeholder=" "
                                                  rows="3"><?php echo $po_terms_and_condition?></textarea>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Total Qty</label>
                                        <input class="form-control form-inputtext invalid"
                                               style="color:black;font-size: 20px" value="0" autocomplete="off"
                                               type="text" name="total_qty" id="total_qty" placeholder=" "
                                               readonly="readonly">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Total Cost Rate</label>
                                        <input class="form-control form-inputtext invalid"
                                               style="color:black;font-size: 20px" value="0" autocomplete="off"
                                               type="text" name="total_cost_rate" id="total_cost_rate" placeholder=" "
                                               readonly="readonly">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Total GST</label>
                                        <input class="form-control form-inputtext invalid"
                                               style="color:black;font-size: 20px" value="0" autocomplete="off"
                                               type="text" name="total_gst" id="total_gst" placeholder=" "
                                               readonly="readonly">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Total Cost Price</label>
                                        <input class="form-control form-inputtext invalid"
                                               style="color:black;font-size: 20px" value="0" autocomplete="off"
                                               type="text" name="total_cost_price" id="total_cost_price" placeholder=" "
                                               readonly="readonly">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                {{--<div class="col-md-12">

                                    <button type="button" class="btn btn-success saveBtn btn-block" name="addpoprint" id="addpoprint">
                                        <i class="fa fa-save"></i>Save & Print</button>


                                    <div class="col-md-2 mt-2">
                                    <a id="print_save_po" href="{{URL::to('print_po')}}?id=param&print_type={{encrypt('1')}}" style="text-decoration:none !important;" target="_blank" title="Print">
                                    </a>
                                    </div>


                                    <button type="button" class="btn btn-info savenewBtn btn-block" name="addpo" id="addpo">
                                    <i class="fa fa-save"></i>Save & New</button>
                                </div>
--}}

                                <div class="row pa-0 ma-0">
                                    <div class="col-sm-6 pa-0 pr-5">
                                        <button type="button" class="btn btn-success saveBtn btn-block" name="addpoprint" id="addpoprint">
                                            <i class="fa fa-print"></i>Save & Print</button>
                                        <a id="print_save_po" href="{{URL::to('print_po')}}?id=param&print_type={{encrypt('1')}}" style="text-decoration:none !important;" target="_blank" title="Print"></a>
                                    </div>

                                    <div class="col-sm-6 pa-0 pl-5">
                                        <button type="button" class="btn btn-info savenewBtn btn-block" name="addpo" id="addpo">
                                            <i class="fa fa-save"></i>Save & New</button>
                                    </div>
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
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <span class="input-group-prepend"><label class="input-group-text"
                                                                                     style="height: 40px;padding-top: 13px"><i
                                                            class="fa fa-search"></i></label></span>
                                            <input class="form-control form-inputtext" value="" maxlength="" type="text"
                                                   name="productsearch" id="productsearch"
                                                   placeholder="Barcode/Product Code/Product Name">
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                    </div>
                                    <div class="col-md-1 rightAlign">
                                        <h5 class="hk-sec-title">
                                            <small class="badge badge-soft-danger mt-15 mr-10"><b>No. of Items:</b>
                                                <span class="pototalitems">0</span>
                                            </small>
                                        </h5>
                                    </div>
                                </div>

                            </div>


                            <table class="table tablesaw view-bill-screen table-hover w-100 display pb-30 dataTable dtr-inline tablesaw-sortable tablesaw-swipe table-bordered "
                                   data-tablesaw-mode="swipe" data-tablesaw-sortable-switch data-tablesaw-minimap
                                   data-tablesaw-mode-switch role="grid" aria-describedby="datable_1_info"
                                   id="inwardtable"  >
                                <thead>
                                <tr class="header">
                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">Barcode</th>
                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2" >Prod Name</th>
                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">HSN</th>
                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Size / UQC</th>
                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">In Stock</th>
                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">Cost Rate</th>
                                    <?php if($nav_type[0]['tax_type'] == 1) {?>
                                    <th colspan="2" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="7">Cost <?php echo $nav_type[0]['tax_title']?> % & Amt</th>
                                    <?php } else { ?>
                                    <th colspan="2" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">Cost GST % & Amt</th>
                                    <?php } ?>
                                    <th scope="col" data-tablesaw-sortable-col scope="col" data-tablesaw-sortable-col data-tablesaw-priority="9">Qty</th>
                                    <th scope="col" data-tablesaw-sortable-col scope="col" data-tablesaw-sortable-col data-tablesaw-priority="10">Total Cost Rate</th>
                                    <?php if($nav_type[0]['tax_type'] == 1) {?>
                                    <th >Total <?php echo $nav_type[0]['tax_title']?></th>
                                    <?php } else { ?>
                                    <th scope="col" data-tablesaw-sortable-col scope="col" data-tablesaw-sortable-col data-tablesaw-priority="11">Total GST</th>
                                    <?php } ?>
                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-col data-tablesaw-priority="12" >Total Cost Price</th>
                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-col data-tablesaw-priority="13"><i class="fa fa-remove"></i></th>
                                </tr>
                                </thead>
                                <tbody id="po_product_detail_record">
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
   
    <script type="text/javascript" src="{{URL::to('/')}}/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script src="{{URL::to('/')}}/public/modulejs/purchase_order/issue_po.js"></script>

    <script>
        CKEDITOR.replace('terms_condition', {
            height: ['100px']
        });
    </script>

    </body>
    </html>
@endsection






































