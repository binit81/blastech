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
.tablesaw-sortable-switch, .tablesaw-modeswitch{
    display: none;
}

.tablesaw tr td{
    white-space: nowrap;
}
.table td, .table th{
  padding: .3rem !important;
}
</style>
<script src="{{URL::to('/')}}/public/template/jquery/dist/jquery.min.js"></script>

<div class="container ml-20">
<div class="row">
    <div class="col-xl-12">
    <span class="badge exportBtn badge-pill mr-10" style="float:right;margin-right:120px; margin-top:-45px; cursor:pointer;" id="exportcreditdata"><i class="ion ion-md-download"></i>&nbsp;Download Excel</span>   
    <span class="badge badge-primary badge-pill mr-90" style="float:right; margin-top:-45px;margin-right:130px !important; padding:7px 10px; cursor:pointer;" id="customercredit_receipt"><i class="ion ion-md-apps"></i>&nbsp;View Customer Receipts</span>
     <span class="badge badge-danger badge-pill mr-10" style="float:right; margin-top:-45px; cursor:pointer;margin-right:300px !important;" id="searchCollapse"><i class="glyphicon glyphicon-search"></i>&nbsp;Search</span>

    <form id="viewbillform" name="viewbillform">
    <section class="hk-sec-wrapper collapse" id="searchbox" style="padding: 0.8rem 1.5rem 0 1.5rem !important;">
       
       
        
            
       
            <div class="row ma-0 common-search">
                <div class="col-sm">
                    <div class="row">
                      
                        <div class="col-md-3 ">                            
                             <div class="form-group">
                             <input type="text" name-attr="customerid" name="searchcustomerdata" id="searchcustomerdata" class="form-control form-inputtext" placeholder="By Customer Name / Mobile"/>
                              
                            </div>
                        </div>
                          <div class="col-md-3 ">                           
                        <div class="form-group">
                               <button type="button" class="btn btn-info search_data searchBtn"><i class="fa fa-search"></i>Search</button>
                            </div>
                        </div>
                        <div class="col-md-3 ">
                           
                        </div>
                        <div class="col-md-3">
                         
                         
                    </div>
                    </div>
                </div>
               
            </div>
       

                
    </section>


    <div class="card">
            <div class="card-body pr-0 pl-0">
                <div class="row ma-0">
                    <div class="col-sm-12 pa-0">
                        <div class="table-wrap">
                            <div class="table-responsive" id="view_creditbal_record">
                                   @include('creditbalance::customer_credit_summarydata')
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

   <div class="modal fade" id="addcreditreceiptpopup" style="border:1px solid !important;">
        <div class="modal-dialog" style="max-width:90% !important;">
         <form id="customerform">
            <div class="modal-content">                
               
               <div class="modal-header" style="Padding: 0.50rem 0.25rem 0 0.25rem !important;">
                <div class="row ma-0">
                <div class="col-sm">
                    <div class="row">
                      
                        <div class="col-md-4">                            
                             <div class="form-group">                            
                            </div>
                        </div>
                          <div class="col-md-4">                           
                       
                                <center><h5 class="modal-title">Credit Payment Details : <span class="invoiceno"></span></h5></center>


                            
                        </div>
                        <div class="col-md-4">
                             <div class="form-group"  style="float:right;">
                             
                        </div>
                        </div>
                       
                    </div>
                </div>
               
            </div>
                
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
               </div>
               <br>
               <div class="creditpopup_values">
               @include('creditbalance::view_creditreceipt_popup')
               </div>

        </div>
        </form>  
          
          </div>
        </form>
        </div>
    </div>    
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
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>
    <script src="{{URL::to('/')}}/public/dist/js/moment.min.js"></script>
    <script src="{{URL::to('/')}}/public/dist/js/daterangepicker.js"></script>


        <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/popper.js/js/popper.min.js"></script>
        <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>
        <script src="{{URL::to('/')}}/public/dist/js/bootstrap-typeahead.js"></script>
       <script src="{{URL::to('/')}}/public/modulejs/common.js"></script>
       <script src="{{URL::to('/')}}/public/modulejs/sales/viewbill.js"></script>

       <script type="text/javascript">
    $(document).ready(function(e){
        $('#searchCollapse').click(function(e){
            $('#searchbox').slideToggle();
        })
    })
    </script>

@endsection
</html>