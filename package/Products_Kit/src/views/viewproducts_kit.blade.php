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

.modal-dialog {
    max-width: 80% !important;
}


</style>

    <div class="container">
        <span class="badge exportBtn badge-pill mr-0" style="float:right; margin-top:-45px; cursor:pointer;" id="product_export"><i class="ion ion-md-download"></i>&nbsp;Download Excel</span>

        <a href="{{URL::to('/')}}/addproducts_kit"><span class="badge badge-primary badge-pill" style="float:right; margin-right:205px; margin-top:-45px; padding:8px; cursor:pointer;"><i class="fa fa-plus"></i>&nbsp;Add New Kit</span></a>

        <span class="badge badge-danger badge-pill" style="float:right; margin-right:130px; margin-top:-45px; cursor:pointer; padding:7px;" id="searchCollapse"><i class="glyphicon glyphicon-search"></i>&nbsp;Search</span>

    <section class="hk-sec-wrapper collapse" id="filterarea_block">
        <div id="">
            <div class="hk-row common-search">
                <div class="col-md-2 pb-10">
                    <div class="form-group">
                        <input type="text" name-attr="product_name" maxlength="50" autocomplete="off" name="product_name_filter" id="product_name_filter" value="" class="form-control form-inputtext" placeholder="Product Name">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="text" name-attr="barcode" maxlength="50" autocomplete="off" name="barcode_filter" id="barcode_filter" value="" class="form-control form-inputtext" placeholder="Barcode">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select name-attr="brand_id" class="form-control form-inputtext" name="brand_id_filter" id="brand_id_filter"></select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select name-attr="category_id" class="form-control form-inputtext" onchange="getsubcategory_filter()" name="category_id_filter" id="category_id_filter"></select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select name-attr="subcategory_id" class="form-control form-inputtext" name="subcategory_id_filter" id="subcategory_id_filter"></select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select name-attr="colour_id" class="form-control form-inputtext" name="colour_id_filter" id="colour_id_filter"></select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select name-attr="size_id" class="form-control form-inputtext" name="size_id_filter" id="size_id_filter"></select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select name-attr="uqc_id" class="form-control form-inputtext" name="uqc_id_filter" id="uqc_id_filter"></select>
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-info searchBtn search_data"  id="search_product"><i class="fa fa-search"></i>Search</button>
                    <button type="button" name="resetfilter" onclick="resetproductfilterdata();" class="btn btn-info resetbtn" id="resetfilter" data-container="body" data-toggle="popover" data-placement="bottom" data-content="" data-original-title="" title="">Reset</button>
                </div>
            </div>
        </div>
        
    </section>

    <section class="hk-sec-wrapper" id="productmaintable">

        <div class="hk-row">
            <div class="col-md-2">
                <a id="deleteproduct" name="deleteproduct"><i class="fa fa-trash" style="font-size: 20px;color: red;margin-left: 20px"></i></a>
            </div>
        </div>


        <div class="table-wrap">
            <div class="table-responsive" id="productrecord">
                @include('products_kit::viewproducts_kitdata')
            </div>
        </div><!--table-wrap-->

    </section>
        <div id="styleSelector">
    </div>

<!-- Show Resume -->
<div class="modal fade" id="showResume" tabindex="-1" role="dialog" aria-labelledby="showResume" aria-hidden="true">
    
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                
                <h5 class="modal-title">
                    <span id="productkitFullname" class="uppercase">Product Kit Details</span>
                    <span class="badge badge-primary mt-15 mr-0" id="productkitBadge"></span>
                </h5>

                <div class="ShowActionButtons" style="margin-left:550px;"></div>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="popup_values">
                @include('products_kit::view_kit_popup')
                </div>
            </div>
            
        </div>
        
    </div>
</div>



    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/popper.js/js/popper.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>
    <script src="{{URL::to('/')}}/public/dist/js/bootstrap-typeahead.js"></script>
    <script src="{{URL::to('/')}}/public/modulejs/product/productkit.js"></script>
    <script src="{{URL::to('/')}}/public/modulejs/product/product.js"></script>
    <script src="{{URL::to('/')}}/public/modulejs/common.js"></script>
    
@endsection

