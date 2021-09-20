@include('pagetitle')
@extends('master')

@section('main-hk-pg-wrapper')


    <div class="container">

        <span class="commonbreadcrumbtn badge exportBtn badge-pill mr-10" 
              id="inward_stock_export"><i class="ion ion-md-download"></i>&nbsp;Download Inward Stock Excel</span>

              

        <?php $inward_url_show = ''; if($nav_type[0]['inward_type'] == 1) { $inward_url_show = 'inward_stock'; }
                 if($nav_type[0]['inward_type'] == 2) { $inward_url_show = 'inward_stock_show'; }
        ?>
        <?php if($inward_url_show != '') {?>
        <a href="{{URL::to($inward_url_show)}}"><span class="commonbreadcrumbtn badge badge-primary badge-pill" id="addnewcollapse"><i class="fa fa-plus"></i>&nbsp;Take Inward</span></a>
        <?php } ?>
        <span class="commonbreadcrumbtn badge badge-danger badge-pill"
              
              id="searchCollapse"><i class="glyphicon glyphicon-search"></i>&nbsp;Search</span>

        

        <section class="hk-sec-wrapper collapse" id="searchBox">
            <div class="row">
                <div class="col-md-11">
                </div>
            <!--  <div class="col-md-1">
            <a href="{{URL::to('inward_stock')}}">
                <button type="button"  class="btn btn-info" name="view_inward" id="view_inward">Take Inward</button>
            </a>
            </div> -->
            </div>
            <h5 class="hk-sec-title">Inward Details Filter</h5>

            <div class="row ma-0">
                <div class="col-sm">
                    <div class="row common-search">
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name-attr="from_to_date" maxlength="50" autocomplete="off"
                                       name="filer_from_to" id="filer_from_to" value=""
                                       class="daterange form-control form-inputtext" placeholder="Select Date">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" name-attr="invoice_no" maxlength="50" autocomplete="off"
                                       name="invoice_no_filter" id="invoice_no_filter" value="" class="form-control form-inputtext"
                                       placeholder="Invoice No.">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" maxlength="50" autocomplete="off" name="supplier_name"
                                       id="supplier_name" value="" class="form-control form-inputtext"
                                       placeholder="Supplier Name">
                                <input type="hidden" name-attr="supplier_name" name="supplier_id" id="supplier_id"
                                       value="">
                            </div>
                        </div>

                        <div class="col-md-5">
                            <button type="button" class="btn searchBtn search_data" id="search_view_inward"><i
                                        class="fa fa-search"></i>Search
                            </button>
                            <button type="button" name="resetfilter" onclick="resetinwardfilterdata();"
                                    class="btn resetbtn" id="resetfilter" data-container="body" data-toggle="popover"
                                    data-placement="bottom" data-content="" data-original-title="" title="">Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <div class="hk-row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="pull-right">
                            <label class="form-label" style="font-size: 20px;color: red"
                                   id="totalinward_record_with_pagination"></label>
                        </div>
                        <div class="table-wrap">
                            <div class="table-responsive" id="viewinwardrecord">
                                @include('inward_stock::inward/inward_stock_data')
                            </div>
                        </div><!--table-wrap-->
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="viewinwardpopup" style="border:1px solid !important;">
            <div class="modal-dialog" style="max-width:90% !important;">
                <form method="post" id="inward_popup_record">
                    <div class="modal-content">
                        <div class="modal-header" style="Padding: 0.50rem 0.25rem 0 0.25rem !important;">
                            <div class="row ma-0">
                                <div class="col-sm">
                                    <div class="row">
                                        <div class="col-md-4">
                                        </div>
                                        <div class="col-md-4 ">
                                            <center><h5 class="modal-title">Invoice No. : <span class="invoiceno"></span></h5></center>
                                        </div>
                                        <div class="col-md-4 ">
                                            <div class="form-group" style="float:right;">
                                                <label>Edit Inward
                                                <a id="edit_inword_stock_in_popup">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>


                        <div class="invoice-to-wrap pb-20">
                            <div class="row">
                                <div class="col-md-7 mb-30" style="width:50% !important;border:0px solid !important;font-size:16px;">
                                    <table style="float:left;">
                                        <tr>
                                            <td class="d-block text-dark font-14 font-weight-600">Supplier</td>
                                            <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                            <td class="text-dark font-14 font-weight-600"><span class="supplier_name"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="d-block text-dark font-14 font-weight-600">GSTIN</td>
                                            <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                            <td class="text-dark font-14 font-weight-600"><span class="supplier_gstin"></span></td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="col-md-5 mb-30" style="width:50% !important;border:0px solid !important;font-size:16px;">
                                    <table style="float:right;">
                                        <tr>
                                            <td class="d-block text-dark font-14 font-weight-600">Invoice No.</td>
                                            <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                            <td class="text-dark font-14 font-weight-600 text-right"><span class="invoice_no_popup"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="d-block text-dark font-14 font-weight-600">Inward Date</td>
                                            <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                            <td class="text-dark font-14 font-weight-600 text-right"><span class="inward_date_popup"></span></td>
                                        </tr>
                                        <tr></tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <section class="hk-sec-wrapper">
                            <div class="table-wrap">
                                <div class="table-responsive">
                                    <table width="100%" cellpadding="6" border="0" frame="box" id="vieewpop" style="border:1px solid #C0C0C0 !important;">
                                        <thead>
                                        <tr style="background:#88c241;border-bottom:1px #f3f3f3 solid;border-top:1px #f3f3f3 solid;">
                                            <th class="text-dark font-14 font-weight-600" style="width:4% !important;color:#fff !important;">Barcode</th>
                                            <th class="text-dark font-14 font-weight-600" style="width:4% !important;color:#fff !important;">Prod Name</th>
                                            <th class="text-dark font-14 font-weight-600" style="width:4% !important;color:#fff !important;">HSN</th>
                                            <th class="text-dark font-14 font-weight-600 garment_case_hide" style="width:4% !important;color:#fff !important;">Batch No.</th>
                                            <th class="text-dark font-14 font-weight-600" style="width:5% !important;color:#fff !important;">Base Price</th>
                                            <th colspan="2" class="text-dark font-14 font-weight-600 garment_case_hide" style="width:5% !important;color:#fff !important;">Disc % & Amt</th>
                                            <th colspan="2" class="text-dark font-14 font-weight-600 garment_case_hide" style="width:5% !important;color:#fff !important;">Scheme % & Amt</th>
                                            <th colspan="2" class="text-dark font-14 font-weight-600 garment_case_hide" style="width:5% !important;color:#fff !important;">Free % & Amt</th>
                                            <th class="text-dark font-14 font-weight-600 garment_case_hide" style="width:5% !important;color:#fff !important;">Cost Rate</th>


                                            <?php if($nav_type[0]['tax_type'] == 1) { ?>
                                            <th class="text-dark font-14 font-weight-600" style="width:5% !important;color:#fff !important;"><?php echo $nav_type[0]['tax_title'].' '.$nav_type[0]['currency_title']?></th>
                                             <?php } else { ?>
                                            <th class="text-dark font-14 font-weight-600" style="width:5% !important;color:#fff !important;">IGST &#8377;</th>
                                            <th class="text-dark font-14 font-weight-600" style="width:5% !important;color:#fff !important;">CGST &#8377;</th>
                                            <th class="text-dark font-14 font-weight-600" style="width:5% !important;color:#fff !important;">SGST &#8377;</th>
                                            <?php } ?>
                                            <?php if($nav_type[0]['tax_type'] == 1) { ?>
                                            <th colspan="2" class="text-dark font-14 font-weight-600" style="width:5% !important;color:#fff !important;">Profit % & <?php echo $nav_type[0]['currency_title']?></th>
                                            <?php } else { ?>
                                            <th colspan="2" class="text-dark font-14 font-weight-600" style="width:5% !important;color:#fff !important;">Profit % & &#8377;</th>
                                            <?php } ?>
                                            <th class="text-dark font-14 font-weight-600" style="width:5% !important;color:#fff !important;">Selling Price</th>
                                            <th class="text-dark font-14 font-weight-600" style="width:5% !important;color:#fff !important;">Offer Price</th>
                                            <th class="text-dark font-14 font-weight-600" style="width:5% !important;color:#fff !important;">MRP</th>
                                            <th class="text-dark font-14 font-weight-600" style="width:4% !important;color:#fff !important;">Add Qty</th>
                                            <th class="text-dark font-14 font-weight-600 garment_case_hide" style="width:5% !important;color:#fff !important;">Free Qty</th>
                                            <th class="text-dark font-14 font-weight-600 garment_case_hide" style="width:5% !important;color:#fff !important;">Mfg Date</th>
                                            <th class="text-dark font-14 font-weight-600 garment_case_hide" style="width:5% !important;color:#fff !important;">Exp Date</th>
                                            <th class="text-dark font-14 font-weight-600" style="width:5% !important;color:#fff !important;">Total Cost</th>
                                        </tr>
                                        </thead>
                                        <tbody id="view_inward_record" style="height: 50px;overflow-y: auto">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section>

                        <div class="invoice-from-wrap" style="margin:45px 0 0 0;">
                            <div class="row">
                                <div class="col-md-8 mb-20" style="width:60% !important;border:0px solid !important;">
                                </div>

                                <div class="col-md-4 mb-20" style="width:40% !important;border:0px solid !important;font-size:16px;">
                                    <table style="float:right;font-size:16px;">
                                        <tr>
                                            <td colspan="3" class="text-right text-dark font-weight-600" style="font-size:14px;">Payment Methods</td>
                                        </tr>
                                        <tbody id="inward_payment_details">

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                            <br>
                            <br>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>
        <script type="text/javascript"
                src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
        <script type="text/javascript"
                src="{{URL::to('/')}}/public/bower_components/popper.js/js/popper.min.js"></script>
        <script type="text/javascript"
                src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>
        <script src="{{URL::to('/')}}/public/modulejs/inward_stock/view_inward.js"></script>





@endsection
