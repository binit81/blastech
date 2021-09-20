@include('pagetitle')
@extends('master')

@section('main-hk-pg-wrapper')


    <div class="container">
        
        <a href="{{URL::to('debit_note')}}"><span class="commonbreadcrumbtn badge badge-primary badge-pill mr-80"  ><i class="fa fa-plus"></i>&nbsp;Make Debit Note</span></a>
        <span class="commonbreadcrumbtn badge badge-danger badge-pill" id="searchCollapse"><i class="glyphicon glyphicon-search"></i>&nbsp;Search</span>
        
    <section class="hk-sec-wrapper mr-20 collapse" id="searchbox" style="padding: 0.8rem 1.5rem 0 1.5rem;">
        
           <div class="row ma-0">
            <div class="col-sm">
                <div class="row">
                    <div class="col-md-3 pb-10">
                        <div class="form-group">
                            <input type="text"  maxlength="50" autocomplete="off" name="debit_no" id="debit_no" value="" class="form-control form-inputtext" placeholder="Debit No.">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text"  maxlength="50" autocomplete="off" name="supplier_name" id="supplier_name" value="" class="form-control form-inputtext" placeholder="Supplier Name">
                            <input type="hidden" name="supplier_gst_id" id="supplier_gst_id" value="">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <button type="button" class="btn btn-info searchBtn" id="search_view_debit"><i class="fa fa-search"></i>Search</button>
                        <button type="button" name="resetfilter" onclick="resetdebitfilterdata();" class="btn btn-info resetbtn" id="resetfilter" data-container="body" data-toggle="popover" data-placement="bottom" data-content="" data-original-title="" title="">Reset</button>
                        {{--<button type="button" class="btn btn-info exportBtn" id="po_record_export" style="float:right;">Export To Excel</button>--}}
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
                            <div class="table-responsive" id="debitnoterecord">
                            @include('debit_note::debit_note/view_debit_note_data')
                            </div>
                        </div><!--table-wrap-->
                    </div>
                </div>
            </div>
        </div>
    



    <div class="modal fade" id="viewdebitpopup">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="margin-left: -456px;width:1755px">
                <form method="post" id="debit_popup_record">
                    <div class="modal-header" style="Padding: 0.50rem 0.25rem 0 0.25rem !important;">
                        <div class="row ma-0">
                            <div class="col-sm">
                                <div class="row">
                                    <div class="col-md-4">                            
                                         <div class="form-group">
                                         <button class="btn btn-primary" id="previousinvoice" style="color:#fff !important;cursor:pointer;" type="button">Previous</button>
                                           <button class="btn btn-primary" id="nextinvoice" style="color:#fff !important;cursor:pointer;" type="button">Next</button>
                                        </div>
                                    </div>
                                      <div class="col-md-4">                           
                                          <center><h5 class="modal-title">Debit Details : <span class="invoiceno"></span></h5></center>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group"  style="float:right;">
                                        <span style="width:150px;float:right;border:1xpx solid;">Action : 
                                            <a title="Edit"><i class="fa fa-edit" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;"></i></a>

                                            <a id="" onclick="" style="text-decoration:none !important;" target="_blank" title="Delete"><i class="fa fa-trash" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;"></i></a>

                                            <a id="" href="" style="text-decoration:none !important;" target="_blank" title="Print"><i class="fa fa-print" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;"></i></a>
                                        </span>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-30" style="width:50% !important;border:0px solid !important;font-size:16px;">
                          <table style="float:right;">
                              <tr>
                                  <td class="d-block text-dark font-14">Invoice No.</td>
                                  <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                  <td class="text-dark font-14 font-weight-600 text-right"><span class="tinvoiceno"></span></td>
                              </tr>
                              <tr>
                                  <td class="d-block text-dark font-14">Invoice Date</td>
                                  <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                  <td class="text-dark font-14 font-weight-600 text-right"><span class="invoicedate"></span></td>
                              </tr>
                              <tr>

                              </tr>
                          </table>   
                        </div>
                    </div>
                    <!-- <div class="col-md-2">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-5">
                                    <div>
                                        <span class="d-block font-15 text-dark font-weight-500 greencolor">Total Qty</span>
                                    </div>
                                </div>
                                <div>
                                    <span class="d-block display-4 text-dark mb-5"><span id="debit_total_qty"></span> </span>
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

                                                <table class="table tablesaw view-bill-screen table-hover w-100 display pb-30 dataTable dtr-inline tablesaw-sortable tablesaw-swipe"
                                                       data-tablesaw-minimap data-tablesaw-mode-switch role="grid"
                                                       aria-describedby="datable_1_info" id="viewinward">
                                                    <thead>
                                                    <tr class="header">
                                                        <th style="width: 3%;">Barcode</th>
                                                        <th style="width: 3%;">Prod Name</th>
                                                        <th style="width: 3%;">Cost Rate</th>
                                                        <th colspan="2" style="width: 3%;">GST % & Amt</th>
                                                        <th style="width: 3%;">qty</th>
                                                        <th style="width: 3%;">total cost without GST</th>
                                                        <th style="width: 3%;">total gst</th>
                                                        <th style="width: 3%;">total cost with GST</th>
                                                        <th style="width: 3%;">Remarks</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="view_debit_record">

                                                    </tbody>

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

    <script src="{{URL::to('/')}}/public/dist/js/moment.min.js"></script>
    <script src="{{URL::to('/')}}/public/dist/js/daterangepicker.js"></script>

    <script src="{{URL::to('/')}}/public/modulejs/common.js"></script>
    <script src="{{URL::to('/')}}/public/modulejs/debit_note/view_debit_note.js"></script>


        <script type="text/javascript">
            $(document).ready(function(e){
                $('#searchCollapse').click(function(e){
                    $('#searchbox').slideToggle();
                })

                $('#addnewcollapse').click(function(e){
                    $('#addnewbox').slideToggle();
                })
            })

    </script>


@endsection