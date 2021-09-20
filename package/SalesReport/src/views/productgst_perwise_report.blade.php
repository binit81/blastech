@include('pagetitle')
@extends('master')

@section('main-hk-pg-wrapper')
<style type="text/css">
.display-4{
    font-size:1.5rem !important;
}
.tablesaw-sortable-switch, .tablesaw-modeswitch{
    display: none;
}

.tablesaw tr td{
    white-space: nowrap;
}
.table td{
  padding: .5rem !important;
}
.table thead tr.header th {
   
    font-size: 0.95rem !important;
}
.table tbody tr td {
   
    font-size: 0.92rem !important;
}
.color{
    color:#fff !important;
}

</style>
<script src="{{URL::to('/')}}/public/template/jquery/dist/jquery.min.js"></script>
<div class="container ml-10">

    <span class="commonbreadcrumbtn badge exportBtn badge-pill mr-10"  id="gstwiseBillingexport"><i class="ion ion-md-download"></i>&nbsp;Download GST % Wise Bill Excel </span>

    <span class="commonbreadcrumbtn badge badge-danger badge-pill"  id="searchCollapse"><i class="glyphicon glyphicon-search"></i>&nbsp;Search</span>
<div class="row">
    <div class="col-xl-12">
    <section class="hk-sec-wrapper collapse" id="searchbox" style="padding: 0.8rem 1.5rem 0 1.5rem !important;">
       <!--  <center><h4 class="hk-sec-title"><b>Product GST% Wise Report</b></h4></center> -->
        <!-- <h5 class="hk-sec-title">Bill Details Filter</h5> -->
       <form>
            <div class="row common-search">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-md-3 ">                           
                             <div class="form-group">
                              <input type="text"  name-attr="from_to_date" name="fromtodate" id="fromtodate" class="daterange form-control form-inputtext"  placeholder="Select Date"/>
                                                            
                                      
                            </div>
                        </div>
                        <div class="col-md-2 ">                            
                             <div class="form-group">
                              <input type="text"  name-attr="customerid" name="searchcustomerdata" id="searchcustomerdata" class="form-control form-inputtext" placeholder="By Customer Name / Mobile"/>
                              
                            </div>
                        </div>
                        
                        <div class="col-md-2 ">
                            <div class="form-group">
                              <input type="text"  name-attr="billno" name="billno" id="billno" class="form-control form-inputtext" placeholder="Bill No."/>
                            </div>
                        </div>
                         <div class="col-md-2 ">
                            <div class="form-group">
                              <input type="text" name-attr="reference_name" name="reference_name" id="reference_name" class="form-control form-inputtext" placeholder="Reference" data-provide="typeahead" data-items="10" data-source=""/>
                            </div>
                        </div>
                        <div class="col-md-3 ">
                            <button type="button" class="btn btn-info searchBtn search_data"><i class="fa fa-search"></i>Search</button> 
                            <button type="button" name="resetfilter" onclick="resetbillgstfilterdata();"
                                    class="btn resetbtn" id="resetfilter">Reset</button>                          
                        
                        </div>
                        
                        <!-- <button type="button" class="btn btn-success exportBtn " id="gstwiseBillingexport">Export To Excel</button> -->
                        </div>
                    </div>
                </div>
               
            </div>
  
                </form>
    </section>


    <div class="card">
            <div class="card-body pr-0 pl-0">
                <div class="row ma-0">
                    <div class="col-sm-12 pa-0">
                        <div class="table-wrap">
                            <div class="table-responsive" id="view_billgst_record">
                             @include('salesreport::productgst_perwise_reportdata')
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
        $('#searchCollapse').click(function(e){
            $('#searchbox').slideToggle();
        })
    })
    </script>
    

       <script src="{{URL::to('/')}}/public/dist/js/bootstrap-typeahead.js"></script>
        <script src="{{URL::to('/')}}/public/modulejs/sales/viewbill.js"></script>

@endsection
