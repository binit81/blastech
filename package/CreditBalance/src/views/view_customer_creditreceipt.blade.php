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
.tablesaw-sortable-switch, .tablesaw-modeswitch{
    display: none;
}

.tablesaw tr td{
    white-space: nowrap;
}
</style>
<script src="{{URL::to('/')}}/public/template/jquery/dist/jquery.min.js"></script>
<div class="container ml-10">
    <span class="badge badge-danger badge-pill mr-10" style="float:right; margin-top:-45px; cursor:pointer;" id="searchCollapse"><i class="glyphicon glyphicon-search"></i>&nbsp;Search</span>
    <span class="badge badge-primary badge-pill mr-90" style="float:right; margin-top:-45px; padding:7px 10px; cursor:pointer;" id="customercredit_summary"><i class="ion ion-md-apps"></i>&nbsp;View Customer Receipts</span>
<form id="viewbillform" name="viewbillform">
<div class="row">
    <div class="col-xl-12">
    <section class="hk-sec-wrapper collapse" id="searchbox" style="padding: 0.8rem 1.5rem 0 1.5rem !important;margin-top:5px !important;margin-bottom:5px !important;">
       

            <div class="row ma-0">
                <div class="col-sm">
                    <div class="row common-search">
                        <div class="col-md-3 ">                           
                             <div class="form-group">
                              <input type="text" name-attr="from_to_date" name="fromtodate" id="fromtodate" class="daterange form-control form-inputtext"  placeholder="Select Date"/>
                              <input type="hidden" name="from_date" id="from_date"> 
                              <input type="hidden" name="to_date" id="to_date">                               
                                      
                            </div>
                        </div>
                        <div class="col-md-3 ">                            
                             <div class="form-group">
                              <input type="text" name-attr="customerid" name="searchcustomerdata" id="searchcustomerdata" class="form-control form-inputtext" placeholder="By Customer Name / Mobile"/>
                              
                            </div>
                        </div>
                        
                        <div class="col-md-3 ">
                            <div class="form-group">
                              <input type="text" name-attr="billno" name="cbillno" id="cbillno" class="form-control form-inputtext" placeholder="Receipt No."/>
                            </div>
                        </div>
                        <div class="col-md-3">
                         <button type="button" class="btn btn-info searchBtn search_data"><i class="fa fa-search"></i>Search</button>
                         <button type="button" name="resetfilter" onclick="resetcreditbalfilterdata();"
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
                            <div class="table-responsive" id="view_creditreceipt_record">
                                   @include('creditbalance::view_customer_creditreceiptdata')
                            </div>
                        </div><!--table-wrap-->
                    </div>
                </div>
            </div><!--card-body-->
        </div>

  </div>
</div>
</div>
</form>  
 

    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>
    <script src="{{URL::to('/')}}/public/dist/js/moment.min.js"></script>
    <script src="{{URL::to('/')}}/public/dist/js/daterangepicker.js"></script>
    <script type="text/javascript">
$(document).ready(function(e){

    $('#customercredit_receipt').click(function(e){
        window.location = 'view_customer_creditreceipt';

    });
    $('#customercredit_summary').click(function(e){
        window.location = 'customer_credit_summary';

    });
});

</script>
    <script type="text/javascript">
    
           $('.daterange').daterangepicker().val('');
    
    </script>
    <script type="text/javascript">
    $(document).ready(function(e){

            $(document).on('click', '#billingexport', function(){

                var query = {
                    from_date: $('#from_date').val(),
                    to_date : $('#to_date').val(),
                    bill_no: $('#billno').val(),
                    customerid : $('#searchcustomerdata').val()
                }


                var url = "{{URL::to('exportbill_details')}}?" + $.param(query)
                window.open(url,'_blank');


            });
    });

    </script>
        <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/popper.js/js/popper.min.js"></script>
        <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>
        <script src="{{URL::to('/')}}/public/dist/js/bootstrap-typeahead.js"></script>
       <script src="{{URL::to('/')}}/public/modulejs/common.js"></script>
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