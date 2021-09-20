@include('pagetitle')
@extends('master')

@section('main-hk-pg-wrapper')

    <div class="container">

        <span class="commonbreadcrumbtn badge exportBtn badge-pill mr-10" id="supplier_wise_report_export"><i class="ion ion-md-download"></i>&nbsp;Download Supplier Wise Inward Excel</span>

        <span class="commonbreadcrumbtn badge badge-danger badge-pill"  id="searchCollapse"><i class="glyphicon glyphicon-search"></i>&nbsp;Search</span>
    <div class="col-xl-12 collapse" id="searchbox">
        <div class="hk-row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <!-- <h5>Filteration</h5> -->

                        <div class="row common-search">
                            <div class="col-md-3">
                                <label class="form-label">From  To Date</label>
                                <input type="text" name-attr="from_to_date"  maxlength="50" autocomplete="off" name="filer_from_to" id="filer_from_to" value="" class="daterange form-control form-inputtext" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Supplier Name</label>
                                <input type="text" name="supplier_name" id="supplier_name" value="" maxlength="" class="form-control form-inputtext">
                                <input type="hidden" name-attr="supplier_name" name="supplier_id" id="supplier_id" value="">
                            </div>

                            <div class="col-md-5">
                                <label class="form-label"></label>
                                <button type="button" name="searchsupplierwise" class="btn addbutton searchBtn search_data" id="searchsupplierwise" data-container="body" data-toggle="popover" data-placement="bottom" data-content="" data-original-title="" title=""><i class="fa fa-search"></i>Search</button>
                                <button type="button" name="resetfilter" onclick="resetfilterdata();" class="btn resetbtn" id="resetfilter" data-container="body" data-toggle="popover" data-placement="bottom" data-content="" data-original-title="" title="">Reset</button>
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
                        <!-- <h5 class="hk-sec-title">Supplier wise Inward List</h5> -->

                        <div class="table-wrap">
                            <div class="table-responsive" id="supplierrec">
                                @include('inward_stock::inward/supplier_wise_report_data')
                            </div>
                        </div><!--table-wrap-->
                    </div>
                </div>
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
    <script src="{{URL::to('/')}}/public/modulejs/inward_stock/supplier_wise_report.js"></script>

    <script type="text/javascript">
    $(document).ready(function(e){
        $('#searchCollapse').click(function(e){
            $('#searchbox').slideToggle();
        })
    })
    </script>



@endsection
