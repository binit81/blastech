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
/*.uploadBtn
{
  padding:3px 9px !important;
  font-size:12px !important;
  border-radius:50px !important;
}*/

</style>

<script src="{{URL::to('/')}}/public/template/jquery/dist/jquery.min.js"></script>
<form id="viewbillform" name="viewbillform">
<div class="container ml-10">
<div class="row">
    <div class="col-xl-12">

        <span class="commonbreadcrumbtn badge exportBtn badge-pill "  id="billingexport"><i class="ion ion-md-download"></i>&nbsp;&nbsp;Download Bill Data </span>

       <span class="commonbreadcrumbtn badge  uploadBtn badge-pill "  id="collapseuploadBtn" >&nbsp<i class="ion ion-md-cloud-upload"></i>&nbsp;&nbsp;Upload Excel</span>
       
       <span class="commonbreadcrumbtn badge badge-danger badge-pill"  id="collapseBtn"><i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;Search</span>

    <section class="hk-sec-wrapper collapse" id="collapseDiv" style="padding: 0.8rem 1.5rem 0 1.5rem !important;margin-top:5px !important;margin-bottom:5px !important;">
     
       
            <div class="row ma-0">
                <div class="col-sm">
                    <div class="row common-search">
                        <div class="col-md-3  pb-20">                           
                             <div class="form-group">
                              <input type="text" name-attr="from_to_date" name="fromtodate" id="fromtodate" class="daterange form-control form-inputtext"  placeholder="Select Date"/>   
                                             
                                      
                            </div>
                        </div>
                        <div class="col-md-2 ">                            
                             <div class="form-group">
                              <input type="text" name-attr="customerid" name="searchcustomerdata" id="searchcustomerdata" class="form-control form-inputtext" placeholder="By Customer Name / Mobile"/>
                              
                            </div>
                        </div>
                        
                        <div class="col-md-2 ">
                            <div class="form-group">
                              <input type="text" name-attr="billno" name="billno" id="billno" class="form-control form-inputtext" placeholder="Bill No." data-provide="typeahead" data-items="10" data-source=""/>
                            </div>
                        </div>
                         <div class="col-md-2 ">
                            <div class="form-group">
                              <input type="text" name-attr="reference_name" name="reference_name" id="reference_name" class="form-control form-inputtext" placeholder="Reference" data-provide="typeahead" data-items="10" data-source=""/>
                            </div>
                        </div>
                        <div class="col-md-3">
                         <button type="button" class="btn btn-info searchBtn search_data"><i class="fa fa-search"></i>Search</button>
                         <button type="button" name="resetfilter" onclick="resetfilterdata();"
                                    class="btn resetbtn" id="resetfilter">Reset</button>
                    </div>
                    </div>
                </div>
               
            </div>
                
    </section>
     <section class="hk-sec-wrapper collapse" id="collapseuploadDiv" style="padding: 0.8rem 1.5rem 0 1.5rem !important;margin-top:5px !important;margin-bottom:5px !important;">
     
       
            <div class="row ma-0">
                <div class="col-sm">
                    <div class="row common-search">
                        <div class="col-md-3">                           
                             <div class="form-group">
                              <input type="file" class="" id="salesfileUpload"  accept=".xlsx, .xls" /> 
                            </div>
                        </div>
                        <div class="col-md-2 ">                            
                             <div class="form-group">
                              <button type="button"  class="btn btn-info btn-block" name="upload" id="uploadsales"><i class="ion ion-md-cloud-upload"></i>&nbsp;Upload</button>
                              
                            </div>
                        </div>
                        

                        <div class="col-md-3 ">
                            <div class="form-group">
                             
                            </div>
                        </div>
                        <div class="col-md-3">
                        
                    </div>
                    </div>
                </div>
               
            </div>
                
    </section>

    <div class="row ma-0 mt-10">
                        <div class="col-md-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-5">
                                        <div>
                                          <span class="d-block font-14 text-dark font-weight-500 greencolor">
                                              Report from <span class="fromdate">{{date("d-m-Y")}}</span> to <span class="todate">{{date("d-m-Y")}}</span> 
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="d-block display-4 text-dark mb-5"><span
                                                    class="totalinvoice">{{$count}}</span> <span class="invoiceLabel">Invoices</span> </span>


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
                                            <span class="d-block display-4 text-dark mb-5"><span class="taxabletariff">{{$todaytaxable}}</span></span>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                if($tax_type==1)
                                {
                                  ?>
                                    
                                <div class="card card-sm">
                                      <div class="card-body">
                                          <div class="d-flex justify-content-between mb-5">
                                              <div>
                                                  <span class="d-block font-15 text-dark font-weight-500 greencolor">{{$taxname}} Amount</span>
                                              </div>
                                          </div>
                                          <div>
                                              <span class="d-block display-4 text-dark mb-5"><span class="overalligst">{{$todayigst}}</span></span>
                                          </div>
                                      </div>
                                  </div>
                                  <?php
                                }
                                else
                                {
                                  ?>

                                   <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-5">
                                            <div>
                                                <span class="d-block font-15 text-dark font-weight-500 greencolor">CGST Amount</span>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="d-block display-4 text-dark mb-5"><span class="overallcgst">{{$todaycgst}}</span></span>
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
                                            <span class="d-block display-4 text-dark mb-5"><span class="overallsgst">{{$todaysgst}}</span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-5">
                                            <div>
                                                <span class="d-block font-15 text-dark font-weight-500 greencolor">IGST Amount</span>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="d-block display-4 text-dark mb-5"><span class="overalligst">{{$todayigst}}</span></span>
                                        </div>
                                    </div>
                                </div>
                                  <?php
                                }
                                ?>
                                
                                
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-5">
                                            <div>
                                                <span class="d-block font-15 text-dark font-weight-500 greencolor">Grand Total</span>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="d-block display-4 text-dark mb-5"><span
                                                        class="overallgrand">{{$todaygrand}}</span></span>
                                        </div>
                                    </div>
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
                          <div class="table-responsive" id="viewbillrecord">
                           @include('salesreport::view_bill_data')
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

<div class="modal fade" id="addcustomerpopup" style="border:1px solid !important;">
        <div class="modal-dialog" style="max-width:90% !important;">
         <form id="customerform">
            <div class="modal-content">                
               
               <div class="modal-header" style="Padding: 0.50rem 0.25rem 0 0.25rem !important;">
                <div class="row ma-0">
                <div class="col-sm">
                    <div class="row">
                      
                        <div class="col-md-4">                            
                             <div class="form-group">
                             <button class="btn btn-primary" id="previousinvoice" style="color:#fff !important;cursor:pointer;" type="button">Previous</button>
                               <button class="btn btn-primary" id="nextinvoice" style="color:#fff !important;cursor:pointer;" type="button">Next</button>
                            </div>
                        </div>
                          <div class="col-md-4">                           
                       
                                <center><h5 class="modal-title">Bill Details : <span class="invoiceno"></span></h5></center>


                            
                        </div>
                        <div class="col-md-4">
                             <div class="form-group"  style="float:right;">
                             <span style="width:150px;float:right;border:1xpx solid;">Action : <span class="editdeleteIcons"></span> </span>
                        </div>
                        </div>
                       
                    </div>
                </div>
               
            </div>
                
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
               </div>
               <br>
               <div class="popup_values">
               @include('salesreport::view_bill_popup')
               </div>

        </div>
        </form>  
          
          </div>
        </form>
        </div>
    </div>  
       <div class="modal fade" id="addreturnpopup" style="border:1px solid !important;">
        <div class="modal-dialog" style="max-width:90% !important;">
         <form id="customerform">
            <div class="modal-content">                
               
               <div class="modal-header" style="Padding: 0.50rem 0.25rem 0 0.25rem !important;">
                <div class="row ma-0">
                <div class="col-sm">
                    <div class="row">
                      
                        <div class="col-md-4 ">                            
                             <div class="form-group">
                             <button class="btn btn-primary" id="rpreviousinvoice" style="color:#fff !important;cursor:pointer;" type="button">Previous</button>
                               <button class="btn btn-primary" id="rnextinvoice" style="color:#fff !important;cursor:pointer;" type="button">Next</button>
                            </div>
                        </div>
                          <div class="col-md-4 ">                           
                       
                                <center><h5 class="modal-title">Return Bill Details : <span class="invoiceno"></span></h5></center>
                            
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
               <div class="rpopup_values">
               @include('salesreport::view_returnbill_popup')
               </div>

        </div>
        </form>  
          
          </div>
        </form>
        </div>
    </div>       
     


    <script type="text/javascript">
    $(document).ready(function(e){

            $('#collapseBtn').click(function(e){
              $('#collapseDiv').slideToggle();
            });
             $('#collapseuploadBtn').click(function(e){
              $('#collapseuploadDiv').slideToggle();
            });
    });

    </script>
      <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
      <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/popper.js/js/popper.min.js"></script>
      <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>

      <script src="{{URL::to('/')}}/public/dist/js/bootstrap-typeahead.js"></script>

      <script src="{{URL::to('/')}}/public/modulejs/sales/viewbill.js"></script>
      <script type="text/javascript" src="{{URL::to('/')}}/public/dist/js/xlsx.full.min.js"></script>


@endsection
</html>