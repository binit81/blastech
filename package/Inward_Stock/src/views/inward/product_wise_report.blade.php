@include('pagetitle')

    @extends('master')

    @section('main-hk-pg-wrapper')
        <style type="text/css">
            .display-4 {
                font-size: 1.5rem !important;
            }
        </style>

        <div class="container">

            <span class="commonbreadcrumbtn badge exportBtn badge-pill mr-10"  id="product_wise_report_export"><i class="ion ion-md-download"></i>&nbsp;Download Product Wise Inward Excel </span>

            <span class="commonbreadcrumbtn badge badge-danger badge-pill"  id="searchCollapse"><i class="glyphicon glyphicon-search"></i>&nbsp;Search</span>
        <div class="col-xl-12 collapse" id="searchbox">
            <div class="hk-row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- <h4 class="hk-sec-title">View Product Wise Inward</h4> -->
                            <div class="col-xl-12">
                                <div class="row common-search">
                                    <div class="col-md-3">
                                        <label class="form-label">From To Date</label>
                                        <input type="text" name-attr="from_to_date" maxlength="50" autocomplete="off" name="filer_from_to" id="filer_from_to" value="" class="daterange form-control form-inputtext" placeholder="">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Barcode</label>
                                        <input type="text" name-attr="barcode" name="barcode_filter" id="barcode_filter" class="form-control form-inputtext" placeholder="">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Product Name</label>
                                        <input type="text" name-attr="product_name" name="product_name_filter" id="product_name_filter" class="form-control form-inputtext" placeholder="">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Batch No.</label>
                                        <input type="text" name-attr="batch_no" name="batch_no_filter" id="batch_no_filter" class="form-control form-inputtext" placeholder="">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Invoice No.</label>
                                        <input type="text" name-attr="invoice_no" name="invoice_no_filter" id="invoice_no_filter" class="form-control form-inputtext" placeholder="">
                                    </div>

                                    <div class="col-md-12 ma-0">
                                        <label class="form-label"></label>
                                        <button type="button" name="searchproductwise" class="btn addbutton searchBtn search_data" id="searchproductwise" data-container="body" data-toggle="popover" data-placement="bottom" data-content="" data-original-title="" title=""><i class="fa fa-search"></i>Search</button>
                                        <button type="button" name="resetproductfilter" onclick="resetproductfilterdata();" class="btn resetbtn" id="resetproductfilter" data-container="body" data-toggle="popover" data-placement="bottom" data-content="" data-original-title="" title="">Reset</button>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12">
            <div class="hk-row">
                <div class="col-md-12">
                    <div class="card-group hk-dash-type-3 ">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-5">
                                    <div>
                                        <span class="d-block font-15 text-dark font-weight-500 greencolor">Cost Rate</span>
                                    </div>
                                </div>
                                <div>
                                <span class="d-block display-4 text-dark mb-5">
                                    <span class="costrate"></span>
                                </span>
                                </div>
                            </div>
                        </div>


                        <?php if($nav_type[0]['tax_type'] == 1) { ?>
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-5">
                                    <div>
                                        <span class="d-block font-15 text-dark font-weight-500 greencolor"><?php echo $nav_type[0]['tax_title']?> Amount</span>
                                    </div>
                                </div>
                                <div>
                                 <span class="d-block display-4 text-dark mb-5">
                                     <span class="totalothertax"></span>
                                 </span>
                                </div>
                            </div>
                        </div>

                        <?php } else { ?>
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-5">
                                    <div>
                                        <span class="d-block font-15 text-dark font-weight-500 greencolor">CGST Amount</span>
                                    </div>
                                </div>
                                <div>
                                 <span class="d-block display-4 text-dark mb-5">
                                     <span class="totalcgst"></span>
                                 </span>
                                </div>
                            </div>
                        </div>


                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-5">
                                    <div>
                                        <span class="d-block font-15 text-dark font-weight-500 greencolor">SGST Amount</span>
                                    </div>
                                </div>
                                <div>
                                <span class="d-block display-4 text-dark mb-5">
                                     <span class="totalsgst"></span>
                                 </span>
                                </div>
                            </div>
                        </div>


                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-5">
                                    <div>
                                        <span class="d-block font-15 text-dark font-weight-500 greencolor">IGST Amount</span>
                                    </div>
                                </div>
                                <div>
                                <span class="d-block display-4 text-dark mb-5">
                                    <span class="totaligst"></span>
                                </span>
                                </div>
                            </div>
                        </div>

                        <?php } ?>

                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-5">
                                    <div>
                                        <span class="d-block font-15 text-dark font-weight-500 greencolor">Grand Total</span>
                                    </div>
                                </div>
                                <div>
                                <span class="d-block display-4 text-dark font-weight-600 mb-5">
                                    <span class="grandtotal"></span>
                                </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-12">
            <div class="hk-row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-wrap">
                                <div class="table-responsive" id="product_wise_report_record">
                                    @include('inward_stock::inward/product_wise_report_data')
                                </div>
                            </div><!--table-wrap-->
                        </div>
                    </div>
                </div>
            </div><!--card-body-->
        </div>



    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/popper.js/js/popper.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>
    
    

    

    <script src="{{URL::to('/')}}/public/dist/js/bootstrap-typeahead.js"></script>

        <script src="{{URL::to('/')}}/public/modulejs/inward_stock/product_wise_report.js"></script>

        <script type="text/javascript">
    $(document).ready(function(e){
        $('#searchCollapse').click(function(e){
            $('#searchbox').slideToggle();
        })
    })
    </script>



@endsection
