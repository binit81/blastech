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
        .table td, .table th{
            padding: .5rem !important;
        }
        .typeahead {
            width: 300px;
            margin-top: 3px;
            padding: 8px 0;
            background-color: #fff;
            border: 1px solid #ccc;
            border: 1px solid rgba(0, 0, 0, 0.2);
            -webkit-border-radius: 8px;
            -moz-border-radius: 8px;
            border-radius: 8px;
            -webkit-box-shadow: 0 5px 10px rgba(0,0,0,.2);
            -moz-box-shadow: 0 5px 10px rgba(0,0,0,.2);
            box-shadow: 0 5px 10px rgba(0,0,0,.2);
        }
        .color{
            color:#fff !important;
        }

        .active {
            display: block;
            background:#D8D8D8;
            border:1px solid #CFCFCF;

        }
        .tablesaw-sortable-switch, .tablesaw-modeswitch{
            display: none;
        }

        .tablesaw tr td{
            white-space: nowrap;
        }
        </style>

    <script>
        var id = '';
    </script>

    <div class="container">
       
    
    
     <a href="{{URL::to('issue_po')}}"><span class="commonbreadcrumbtn badge badge-primary badge-pill mr-80"  id="addnewcollapse"><i class="fa fa-plus"></i>&nbsp;Make PO</span></a>
     <span class="commonbreadcrumbtn badge badge-danger badge-pill" id="searchCollapse"><i class="glyphicon glyphicon-search"></i>&nbsp;Search</span>
    <section class="hk-sec-wrapper collapse" id="searchbox" style="padding: 0.8rem 1.5rem 0 1.5rem;">
        <div class="row ma-0">
            <div class="col-sm">
                <div class="row common-search">
                    <div class="col-md-3 pb-10">
                        <div class="form-group">
                            <input type="text" name-attr="from_to_date"  maxlength="50" autocomplete="off" name="filer_from_to" id="filer_from_to" value="" class="daterange form-control form-inputtext" placeholder="Select PO Date">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="text" name-attr="po_no"  maxlength="50" autocomplete="off" name="po_no" id="po_no" value="" class="form-control form-inputtext" placeholder="PO No.">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text"  maxlength="50" autocomplete="off" name="supplier_name" id="supplier_name" value="" class="form-control form-inputtext" placeholder="Supplier Name">
                            <input type="hidden" name-attr="supplier_name" name="supplier_id" id="supplier_id" value="">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <button type="button" class="btn btn-info searchBtn search_data" id="search_view_po"><i class="fa fa-search"></i>Search</button>
                        <button type="button" name="resetfilter" onclick="resetpofilterdata();" class="btn btn-info resetbtn" id="resetfilter" data-container="body" data-toggle="popover" data-placement="bottom" data-content="" data-original-title="" title="">Reset</button>
                        {{--<button type="button" class="btn btn-info exportBtn" id="po_record_export" style="float:right;"><i class="ion ion-md-download"></i>&nbsp;Export To Excel</button>--}}
                    </div>
                </div>
            </div>
        </div>

    </section>
    
        <div class="hk-row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                       

                        <div class="table-wrap">
                            <div class="table-responsive" id="porecord">
                                @include('PO::purchase_order/view_purchase_order_data')
                            </div>
                        </div><!--table-wrap-->
                    </div>
                </div>
            </div>
        </div>


    <div class="modal fade" id="viewpopopup">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="margin-left: -456px;width:1755px">
                <form method="post" id="po_popup_record">
                    <div class="modal-header" style="Padding: 0.50rem 0.25rem 0 0.25rem !important;">
                        <div class="row ma-0">
                            <div class="col-sm">
                                <div class="row">
                                    <div class="col-md-4">                            
                                         <div class="form-group">
                                         <button class="btn btn-primary" id="" style="color:#fff !important;cursor:pointer;" type="button">Previous</button>
                                           <button class="btn btn-primary" id="" style="color:#fff !important;cursor:pointer;" type="button">Next</button>
                                        </div>
                                    </div>
                                      <div class="col-md-4">                           
                                          <center><h5 class="modal-title">PO Details : <span class="invoiceno"></span></h5></center>
                                    </div>
                                    <div class="col-md-4">
                                         <div class="form-group"  style="float:right;">
                                         <span style="width:250px;float:right;border:1xpx solid;">Action : 
                                            <span>
                                                <a class="edit_bill" title="Edit"><i class="fa fa-edit" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;"></i></a>

                                                <a id="" onclick="" style="text-decoration:none !important;" target="_blank" title="Delete"><i class="fa fa-trash" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;"></i></a>

                                                <a id="print_detail_po" href="{{URL::to('print_po')}}?id=param&print_type={{encrypt('2')}}" style="text-decoration:none !important;" target="_blank" title="Print"><i class="fa fa-print" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;"></i></a>
                                                <a id="take_inward_data" title="Take Inward"><button class="btn btn-success btn-sm">Take Inward</button></a>
                                            </span>
                                        </span>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                   </div>
                  

                    <!-- <div class="modal-header">
                        <span class="d-block display-4" style="margin-left: 1520px">
                            <a id="print_detail_po" href="{{URL::to('print_po')}}?id=param&print_type={{encrypt('2')}}" style="text-decoration:none !important;" target="_blank" title="Print"><i class="fa fa-print" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;"></i></a>
                        </span>
                        <span class="show_takeinward" style="margin-left: 20px;display: none;">
                            <button><a id="take_inward_data" title="Take Inward" >Take Inward</a></button>
                        </span>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div> -->

                    <!-- <div class="col-md-2">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-5">
                                        <div>
                                            <span class="d-block font-15 text-dark font-weight-500 greencolor">Total Qty</span>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="d-block display-4 text-dark mb-5"><span id="po_total_qty"></span> </span>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                    <div class="col-xl-12">
                        <div class="hk-row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body greybg">
                                        <div class="table-wrap">
                                            <div class="table-responsive">
                                                <table  class="table tablesaw table-bordered table-hover table-striped mb-0 view-bill-screen w-100 display pb-30 dtr-inline tablesaw-sortable tablesaw-swipe" width="100%" cellpadding="6" border="0" frame="box"  style="border:1px solid #C0C0C0 !important;" tablesaw-minimap data-tablesaw-mode-switch role="grid" aria-describedby="datable_1_info" id="viewinward">
                                                <!-- <table class="table tablesaw table-bordered view-bill-screen table-hover w-100 display pb-30 dataTable dtr-inline tablesaw-sortable tablesaw-swipe" data-tablesaw-minimap data-tablesaw-mode-switch role="grid" aria-describedby="datable_1_info" id="viewinward"> -->
                                                    <thead>
                                                    <?php
                                                    $tax_lable = "GST";
                                                    if($nav_type[0]['tax_type']== 1) {
                                                        $tax_lable = $nav_type[0]['tax_title'];
                                                    }
                                                    ?>
                                                    <tr style="background:#88c241;border-bottom:1px #f3f3f3 solid;border-top:1px #f3f3f3 solid;">
                                                        <th class="text-dark font-14 font-weight-600" style="width:3% !important;color:#fff !important;">Barcode</th>
                                                        <th class="text-dark font-14 font-weight-600" style="width:3% !important;color:#fff !important;">Prod Name</th>
                                                        <th class="text-dark font-14 font-weight-600" style="width:3% !important;color:#fff !important;">Size / UQC</th>
                                                        <th class="text-dark font-14 font-weight-600" style="width:3% !important;color:#fff !important;">Cost Rate</th>
                                                        <th colspan="2" class="text-dark font-14 font-weight-600" style="width:3% !important;color:#fff !important;"><?php echo $tax_lable?> % & Amt</th>
                                                        <th class="text-dark font-14 font-weight-600" style="width:3% !important;color:#fff !important;">Qty</th>
                                                        <th class="text-dark font-14 font-weight-600" style="width:3% !important;color:#fff !important;">Total Cost Without <?php echo $tax_lable?></th>
                                                        <th class="text-dark font-14 font-weight-600" style="width:3% !important;color:#fff !important;">Total <?php echo $tax_lable?></th>
                                                        <th class="text-dark font-14 font-weight-600" style="width:3% !important;color:#fff !important;">Total Cost With <?php echo $tax_lable?></th>
                                                        <th class="text-dark font-14 font-weight-600" style="width:3% !important;color:#fff !important;">Received Qty</th>
                                                        <th class="text-dark font-14 font-weight-600" style="width:3% !important;color:#fff !important;">Pending Qty</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody id="view_po_record">

                                                    </tbody>

                                                    <tfoot style="border-bottom:1px solid #C0C0C0 !important;border-top:1px solid #C0C0C0 !important;">
                                                    <tr>
                                                        <th colspan="6" class="text-right text-dark font-14 font-weight-600">Total</th>
                                                        <th class="text-right text-dark font-14 font-weight-600"><span id="po_total_qty"></th>
                                                        <th class="text-right text-dark font-14 font-weight-600"></th>
                                                        <th class="text-right text-dark font-14 font-weight-600"></th>
                                                        <th class="text-right text-dark font-14 font-weight-600"></th>
                                                        <th class="text-right text-dark font-14 font-weight-600"></th>
                                                        <th class="text-right text-dark font-14 font-weight-600"></th>
                                                    </tr>
                                                  </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/popper.js/js/popper.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>

    <script src="{{URL::to('/')}}/public/modulejs/purchase_order/view_po_detail.js"></script>

    <script type="text/javascript">
    $(document).ready(function(e){
        $('#searchCollapse').click(function(e){
            $('#searchbox').slideToggle();
        })
    })
    </script>

@endsection
