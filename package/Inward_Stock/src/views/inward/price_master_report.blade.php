@include('pagetitle')
@extends('master')

@section('main-hk-pg-wrapper')

    <div class="container">

        <span class="commonbreadcrumbtn badge exportBtn badge-pill mr-10"  id="price_master_report_export"><i class="ion ion-md-download"></i>&nbsp;Downlaod Price Master Excel</span>

        <span class=" commonbreadcrumbtn badge badge-danger badge-pill"  id="searchCollapse"><i class="glyphicon glyphicon-search"></i>&nbsp;Search</span>
    <div class="col-xl-12 collapse" id="searchbox">
        <div class="hk-row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
        <!-- <h5 class="hk-sec-title">Price Master Filter</h5> -->


                <div class="row common-search">
                    <div class="col-md-3">
                        <div class="form-group">
                            <input name-attr="barcode" type="text"  maxlength="50" autocomplete="off" name="barcode_filter" id="barcode_filter" value="" class="form-control form-inputtext" placeholder="Barcode">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input name-attr="product_name" type="text"  maxlength="50" autocomplete="off" name="product_name_filter" id="product_name_filter" value="" class="form-control form-inputtext" placeholder="Product Name">
                        </div>
                    </div>

                    <div class="col-md-5">
                        <button type="button" class="btn searchBtn search_data" id="search_price_master"><i class="fa fa-search"></i>Search</button>
                        <button type="button" name="resetfilter" onclick="resetpricedata();" class="btn resetbtn" id="resetfilter" data-container="body" data-toggle="popover" data-placement="bottom" data-content="" data-original-title="" title="">Reset</button>
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
                        <!-- <h5 class="hk-sec-title">Price Master List</h5> -->

                        <div class="table-wrap">
                            <div class="table-responsive" id="price_master">
                                @include('inward_stock::inward/price_master_report_data')
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
    
    <script src="{{URL::to('/')}}/public/modulejs/inward_stock/price_master_report.js"></script>

    <script type="text/javascript">
    $(document).ready(function(e){
        $('#searchCollapse').click(function(e){
            $('#searchbox').slideToggle();
        })
    })
    </script>


@endsection
