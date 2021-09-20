@include('pagetitle')
@extends('master')

@section('main-hk-pg-wrapper')
<style type="text/css">
.display-4{
    font-size:1.5rem !important;
}

.pa-0
{
    padding:2px !important;
}
.tablesaw-sortable-switch, .tablesaw-modeswitch{
    display: none;
}
.table thead tr.header th {
   
    font-size: 0.95rem !important;
}
.table tbody tr td {
   
    font-size: 0.92rem !important;
}
.tablesaw tr td{
    white-space: nowrap;
}
.table td{
  padding: .5rem !important;
}

</style>

<div class="container ml-10">

    <span class="badge exportBtn badge-pill mr-10" style="float:right; margin-top:-45px; cursor:pointer;" id="exportstockdata"><i class="ion ion-md-download"></i>&nbsp;Download Excel</span>

    <span class="badge badge-danger badge-pill" style="float:right; margin-right:150px; margin-top:-45px; cursor:pointer; padding:7px;" id="searchCollapse"><i class="glyphicon glyphicon-search"></i>&nbsp;Search</span>
<div class="row">
    <div class="col-xl-12"> 
    <section class="hk-sec-wrapper collapse" id="searchbox" style="padding: 0.8rem 1.5rem 0 1.5rem !important;">
        <!-- <center><h4 class="hk-sec-title"><b>Stock Report</b></h4></center> -->
        <!-- <h5 class="hk-sec-title">Stock Filter</h5> -->
       <form>
            <div class="row ma-0 common-search">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-sm-3 ">                           
                             <div class="form-group">
                              <input type="text" name-attr="from_to_date" name="fromtodate" id="fromtodate" class="daterange form-control form-inputtext" placeholder="Select Date"/>
                                                   
                                      
                            </div>
                        </div>
                        <div class="col-sm-2 ">                            
                             <div class="form-group">
                              <input type="text" name-attr="productsearch" name="productsearch" id="productsearch" class="form-control form-inputtext" placeholder="By Barcode / Product Name" data-provide="typeahead" data-items="10" data-source="">
                              
                            </div>
                        </div>
                        
                        <div class="col-sm-2 ">
                            <div class="form-group">
                              <input type="text" name-attr="categoryname" name="categoryname" id="categoryname" class="form-control form-inputtext" placeholder="By Category"/>
                            </div>
                        </div>
                        <div class="col-sm-2 ">
                            <div class="form-group">
                              <input type="text" name-attr="brandname" name="brandname" id="brandname" class="form-control form-inputtext" placeholder="By Brand"/>
                            </div>
                        </div>
                        <div class="col-sm-3 ">
                            <button type="button" class="btn btn-info searchBtn search_data"><i class="fa fa-search"></i>Search</button>
                        
                         <!-- <button type="button" class="btn btn-success exportBtn" id="exportstockdata" style="float:right;">Export To Excel</button> -->
                         
                    </div>
                    </div>
                </div>
               
            </div>
       
                    
                </form>
    </section>


    
                       
                        <div class="col-xl-12">
                            <div class="card-group hk-dash-type-3 pa-0">
                                 <div class="card card-sm">
                                    <div class="card-body pa-0">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <span class="d-block font-15 text-dark font-weight-500 greencolor">Total Products</span>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="d-block display-4 text-dark mb-5"><span class="totalproducts">{{$count}}</span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-sm">
                                    <div class="card-body pa-0">
                                        <div class="d-flex justify-content-between mb-5">
                                            <div>
                                                <span class="d-block font-15 text-dark font-weight-500 greencolor">Opening</span>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="d-block display-4 text-dark mb-5"><span class="opening">{{$totopening}}</span></span>
                                        </div>
                                    </div>
                                </div>
                                 
                                <div class="card card-sm">
                                    <div class="card-body pa-0">
                                        <div class="d-flex justify-content-between mb-5">
                                            <div>
                                                <span class="d-block font-15 text-dark font-weight-500 greencolor">Total Inward</span>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="d-block display-4 text-dark mb-5"><span class="totalinwardqty">{{$currinward}}</span></span>
                                        </div>
                                    </div>
                                </div>
                                 <div class="card card-sm">
                                    <div class="card-body pa-0">
                                        <div class="d-flex justify-content-between mb-5">
                                            <div>
                                                <span class="d-block font-15 text-dark font-weight-500 greencolor">Total Sold</span>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="d-block display-4 text-dark mb-5"><span class="totalsoldqty">{{$currsold}}</span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-sm">
                                    <div class="card-body pa-0">
                                        <div class="d-flex justify-content-between mb-5">
                                            <div>
                                                <span class="d-block font-15 text-dark font-weight-500 greencolor">Total Restock</span>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="d-block display-4 text-dark mb-5"><span class="totalrestockqty">{{$currrestock}}</span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-sm">
                                    <div class="card-body pa-0">
                                        <div class="d-flex justify-content-between mb-5">
                                            <div>
                                                <span class="d-block font-15 text-dark font-weight-500 greencolor">Total Damage</span>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="d-block display-4 text-dark mb-5"><span class="totaldamageqty">{{$ttotaldamage}}</span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-sm">
                                    <div class="card-body pa-0">
                                        <div class="d-flex justify-content-between mb-5">
                                            <div>
                                                <span class="d-block font-15 text-dark font-weight-500 greencolor">Total Used</span>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="d-block display-4 text-dark mb-5"><span class="totalusedqty">{{$currusedqty}}</span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-sm">
                                    <div class="card-body pa-0">
                                        <div class="d-flex justify-content-between mb-5">
                                            <div>
                                                <span class="d-block font-15 text-dark font-weight-500 greencolor">Total Supp-Return</span>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="d-block display-4 text-dark mb-5"><span class="totalsupprqty">{{$currsupprqty}}</span></span>
                                        </div>
                                    </div>
                                </div>
                                 <div class="card card-sm">
                                    <div class="card-body pa-0">
                                        <div class="d-flex justify-content-between mb-5">
                                            <div>
                                                <span class="d-block font-15 text-dark font-weight-500 greencolor">In Stock</span>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="d-block display-4 text-dark mb-5"><span class="totalinstock">{{$totstock}}</span></span>
                                        </div>
                                    </div>
                                </div>
                                
                           

                            </div>
                        </div>

                    


    <div class="card">
            <div class="card-body pr-0 pl-0">
                <div class="row ma-0">
                    <div class="col-sm-12 pa-0">
                        <div class="table-wrap">
                            <div class="table-responsive" id="view_stock_record">
                             @include('salesreport::view_stockreport_data')
                           </div>
                        </div><!--table-wrap-->
                    </div>
                </div>
            </div><!--card-body-->
        </div>
</div>
</div>
</div>
 

    <script type="text/javascript">
    $(document).ready(function(e){


            $(document).on('click', '#exportstockdata', function(){

                  var inoutdate         =     $("#fromtodate").val();
                  var totalnights       =     inoutdate.split(' - ');
                  var from_date         =     totalnights[0];
                  var to_date           =     totalnights[1];

                var query = {
                    from_date: from_date,
                    to_date : to_date,
                    productsearch: $('#productsearch').val(),
                    categoryname : $('#categoryname').val(),
                    brandname:$('#brandname').val()
                }


                var url = "{{URL::to('export_stockreport_details')}}?" + $.param(query)
                window.open(url,'_blank');


            });
    });

    </script>

    <script type="text/javascript">
    $(document).ready(function(e){

        $('#searchCollapse').click(function(e){
            $('#searchbox').slideToggle();
        })
    })
    </script>
   
    
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/popper.js/js/popper.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>

    <script src="{{URL::to('/')}}/public/dist/js/bootstrap-typeahead.js"></script>    
    <script src="{{URL::to('/')}}/public/modulejs/stock/viewstock.js"></script>
   

@endsection
