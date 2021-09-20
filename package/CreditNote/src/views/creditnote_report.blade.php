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
<script src="{{URL::to('/')}}/public/template/jquery/dist/jquery.min.js"></script>
<div class="container ml-10">
  <span class="commonbreadcrumbtn badge badge-danger badge-pill mr-10"  id="searchCollapse"><i class="glyphicon glyphicon-search"></i>&nbsp;Search</span>
<div class="row">
    <div class="col-xl-12">
<form id="viewbillform" name="viewbillform">

    <section class="hk-sec-wrapper collapse" id="searchbox" style="padding: 0.8rem 1.5rem 0 1.5rem !important;">
        <!-- <center><h4 class="hk-sec-title"><b>Credit Note Report</b></h4></center> -->
        <h5 class="hk-sec-title">Filter</h5>
       
            <div class="row ma-0">
                <div class="col-sm">
                    <div class="row common-search">
                        <div class="col-md-3 ">                           
                             <div class="form-group">
                              <input type="text" name-attr="from_to_date" name="fromtodate" id="fromtodate" class="daterange form-control form-inputtext"  placeholder="Select Date"/>
                            
                            </div>
                        </div>
                        <div class="col-md-3 ">                            
                             <div class="form-group">
                              <input type="text" name-attr="customerid" name="searchcustomerdata" id="searchcustomerdata" class="form-control form-inputtext typeahead" placeholder="By Customer Name / Mobile"  data-provide="typeahead" data-items="10" data-source=""/>
                              
                            </div>
                        </div>
                        
                        <div class="col-md-3 ">
                            <div class="form-group">
                              <input type="text" name-attr="billno" name="cbillno" id="cbillno" class="form-control form-inputtext" placeholder="CreditNote No."/>
                            </div>
                        </div>
                        <div class="col-md-3">
                         <button type="button" class="btn btn-info searchBtn search_data"><i class="fa fa-search"></i>Search</button>
                         <button type="button" name="resetfilter" onclick="resetcreditnotefilterdata();"
                                    class="btn resetbtn" id="resetfilter">Reset</button> 
                         <!--<button type="button" class="btn btn-success" id="billingexport" style="float:right;">Export To Excel</button>-->
                         
                    </div>
                    </div>
                </div>
               
            </div>
       
  {{--<div class="row">
                        <div class="col-md-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-5">
                                        <div>
                                            <span class="d-block font-14 text-dark font-weight-500 greencolor">Report from <span class="fromdate">{{date("d-m-Y")}}</span> to <span class="todate">{{date("d-m-Y")}}</span> </span>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="d-block display-4 text-dark mb-5"><span
                                                    class="totalinvoice"></span> Invoices </span>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="card-group hk-dash-type-3 ">
                                 <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-5">
                                            <div>
                                                <span class="d-block font-15 text-dark font-weight-500 greencolor">Taxable Amount</span>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="d-block display-4 text-dark mb-5"><span class="taxabletariff"></span></span>
                                        </div>
                                    </div>
                                </div>
                                 <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-5">
                                            <div>
                                                <span class="d-block font-15 text-dark font-weight-500 greencolor">CGST Amount</span>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="d-block display-4 text-dark mb-5"><span class="overallcgst"></span></span>
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
                                            <span class="d-block display-4 text-dark mb-5"><span class="overallsgst"></span></span>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-5">
                                            <div>
                                                <span class="d-block font-15 text-dark font-weight-500 greencolor">Grand Total</span>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="d-block display-4 text-dark mb-5"><span
                                                        class="overallgrand"></span></span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>--}}
                
    </section>


    <div class="card">
            <div class="card-body pr-0 pl-0">
                <div class="row ma-0">
                    <div class="col-sm-12 pa-0">
                        <div class="table-wrap">
                           <div class="table-responsive" id="view_creditnote_record">
                            @include('creditnote::creditnote_reportdata')
                          </div>
                        </div><!--table-wrap-->
                    </div>
                </div>
            </div><!--card-body-->
        </div>

  
   </form>  

   </div>
</div>
</div>

   <div class="modal fade" id="creditnotepopup" style="border:1px solid !important;">
        <div class="modal-dialog" style="max-width:80% !important;">
         <form id="returnbillsdetails">
            <div class="modal-content" style="height:auto;">                
               
               <div class="modal-header" style="Padding: 0.50rem 0.25rem 0 0.25rem !important;">
                <div class="row ma-0">
                <div class="col-sm">
                    <div class="row">
                        <div class="col-md-4 ">                            
                             <div class="form-group">
                            </div>
                        </div>
                          <div class="col-md-4 "> 
                                <center><h5 class="modal-title">Bill Details : <span class="invoiceno"></span></h5></center>
                        </div>
                        <div class="col-md-4 ">
                             <div class="form-group"  style="float:right;">
                          
                        </div>
                        </div>
                       
                    </div>
                </div>
               
            </div>
                
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
               </div>
               <br>
               <div class="popup_values" style="width:100%;margin:0 auto !important;">
                 @include('creditnote::creditnote_popup')
               </div>

        </div>
        </form>  
          
          </div>
        </form>
        </div>
    </div>    
 

    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>
    <script src="{{URL::to('/')}}/public/dist/js/moment.min.js"></script>
    <script src="{{URL::to('/')}}/public/dist/js/daterangepicker.js"></script>
    <script type="text/javascript">
    
           $('.daterange').daterangepicker().val('');
    
    </script>
    
        <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/popper.js/js/popper.min.js"></script>
        <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>
       
       <script src="{{URL::to('/')}}/public/modulejs/common.js"></script>
       <script src="{{URL::to('/')}}/public/dist/js/bootstrap-typeahead.js"></script>
       <script src="{{URL::to('/')}}/public/modulejs/sales/viewbill.js"></script>
        <script src="{{URL::to('/')}}/public/modulejs/sales/creditbill.js"></script>

        <script type="text/javascript">
    $(document).ready(function(e){
        $('#searchCollapse').click(function(e){
            $('#searchbox').slideToggle();
        })
    })
    </script>

@endsection
</html>