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
.uploadBtn
{
  padding:3px 9px !important;
  font-size:12px !important;
  border-radius:50px !important;
}
</style>

    <div class="container">

        <span class="badge exportBtn badge-pill mr-10" style="float:right; margin-top:-45px; cursor:pointer;"
              id="inward_stock_export"><i class="ion ion-md-download"></i>&nbsp;Download Excel</span>

      
        <a href="{{URL::to('inward_productskit')}}"><span class="badge badge-primary badge-pill" style="float:right; margin-right:205px; margin-top:-45px; padding:8px; cursor:pointer;" id="addnewcollapse"><i class="fa fa-plus"></i>&nbsp;Take Inward</span></a>
       
        <span class="badge badge-danger badge-pill"
              style="float:right; margin-right:130px; margin-top:-45px; cursor:pointer; padding:7px;"
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
                            <div class="table-responsive" id="viewkitinwardrecord">
                                @include('products_kit::view_kitinward_data')
                            </div>
                        </div><!--table-wrap-->
                    </div>
                </div>
            </div>
        </div>




        <script src="{{URL::to('/')}}/public/template/jquery/dist/jquery.min.js"></script>

        <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/popper.js/js/popper.min.js"></script>
        <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>
        <script src="{{URL::to('/')}}/public/dist/js/bootstrap-typeahead.js"></script>
        <script src="{{URL::to('/')}}/public/modulejs/product/productkit.js"></script>
        <script src="{{URL::to('/')}}/public/modulejs/product/inward_productkit.js"></script>





@endsection
