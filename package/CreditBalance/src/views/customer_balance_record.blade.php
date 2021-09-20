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
  padding: .5rem !important;
}
#exTab1 .tab-content {
  color : white;
  /*background-color: #428bca;*/
  padding : 5px 15px;
}

#exTab2 h3 {
  color : black;
  /*background-color: #428bca;*/
  padding : 5px 15px;
}


</style>


<script src="{{URL::to('/')}}/public/template/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>
<div class="container">
    <span class="badge badge-danger badge-pill mr-10" style="float:right; margin-top:-45px; cursor:pointer;" id="searchCollapse"><i class="glyphicon glyphicon-search"></i>&nbsp;Search</span>
    <span class="badge badge-primary badge-pill mr-90" style="float:right; margin-top:-45px; padding:7px 10px; cursor:pointer;" id="customercredit_receipt"><i class="ion ion-md-apps"></i>&nbsp;View Customer Receipts</span>

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

                <div class="container"></div>

                <div id="exTab2" class="container"> 
                    <ul class="nav nav-tabs">
                        <li class="active">
                             <a  href="#customercreditsummary" data-toggle="tab">Customer Wise Credit Summary</a>
                        </li>
                        <li>
                            <a href="#billwisecreditsummary" data-toggle="tab">Bill Wise Credit Summary</a>
                        </li>
                        <li>
                            <a href="#creditreceipts" data-toggle="tab">Customer Credit Receipts</a>
                        </li>
                    </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="customercreditsummary">
                        <div class="col-sm-12 pa-0">
                        <div class="table-wrap">
                            <div class="table-responsive" id="view_creditbal_record">
                                   @include('creditbalance::customer_credit_summarydata')
                            </div>
                        </div><!--table-wrap-->
                    </div>
                    </div>
                    <div class="tab-pane" id="billwisecreditsummary">
                        <div class="col-sm-12 pa-0">
                        <div class="table-wrap">
                            <div class="table-responsive" id="view_creditbillbal_record">
                                   @include('creditbalance::customer_billcredit_summarydata')
                            </div>
                        </div><!--table-wrap-->
                    </div>
                        
                    </div>
                    <div class="tab-pane" id="creditreceipts">
                        <div class="col-sm-12 pa-0">
                        <div class="table-wrap">
                            <div class="table-responsive" id="view_creditreceipt_record">
                                   @include('creditbalance::view_customer_creditreceiptdata')
                            </div>
                        </div><!--table-wrap-->
                    </div>
                    </div>
                </div>
             </div>


            </div><!--card-body-->
        </div>

    </div>
</div>
</div>
   </form>         
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