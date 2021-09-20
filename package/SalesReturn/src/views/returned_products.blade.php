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
</style>
<script src="{{URL::to('/')}}/public/template/jquery/dist/jquery.min.js"></script>

     
       
<form id="viewbillform" name="viewbillform">
<div class="container ml-20">
<div class="row">
    <div class="col-xl-12">
    <div class="card">
            <div class="card-body pr-0 pl-0">
                <div class="row ma-0">
                    <div class="col-sm-12 pa-0">
                        <div class="table-wrap">
                            <div class="table-responsive">
                                   <table class="table tablesaw table-bordered table-hover table-striped mb-0" data-tablesaw-mode="swipe" data-tablesaw-sortable data-tablesaw-minimap data-tablesaw-mode-switch border="1">

                                              <thead >
                                              <tr class="header">
                                                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1"></th>
                                                <th scope="col"data-tablesaw-sortable-col >Bill No.</th>
                                                <th scope="col" data-tablesaw-sortable-col>Bill Date</th>
                                                <th scope="col" data-tablesaw-sortable-col>Product Name</th>
                                                <th scope="col" data-tablesaw-sortable-col>Barcode</th>
                                                <th scope="col" data-tablesaw-sortable-col>SKU</th>
                                                <th scope="col" data-tablesaw-sortable-col>Category</th>
                                                <th scope="col" data-tablesaw-sortable-col>MRP
                                                </th>
                                                <th scope="col" data-tablesaw-sortable-col>Selling  Price</th>
                                                <th scope="col" data-tablesaw-sortable-col>Qty</th>
                                                <th scope="col"data-tablesaw-sortable-col>Restock / Damage</th>
                                                <th scope="col" data-tablesaw-sortable-col>Remarks</th>
                                                <th scope="col"data-tablesaw-sortable-col>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody id="view_bill_record">
                                           @include('salesreturn::returned_products_data')
                                            </tbody>
                                        </table>
                                        <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                                        <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="sales_bill_id" />
                                        <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="DESC" />
                                        <input type="hidden" name="fetch_data_url" id="fetch_data_url" value="datewise_product_billdetail" />
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

    <script type="text/javascript">
    $(document).ready(function(e){

            $(document).on('click', '#searchroomwisedata', function(){

                var query = {
                    from_date: $('#from_date').val(),
                    to_date : $('#to_date').val(),
                    bill_no: $('#billno').val(),
                    customerid : $('#searchcustomerdata').val(),
                    roomno:$('#roomno').val()
                }


                var url = "{{URL::to('exportproductwise_details')}}?" + $.param(query)
                window.open(url,'_blank');


            });
    });

    </script>


    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/popper.js/js/popper.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/dist/js/datepicker.js"></script>-
  

    

    <script src="{{URL::to('/')}}/public/dist/js/bootstrap-typeahead.js"></script>
    <script src="{{URL::to('/')}}/public/modulejs/sales/returnbill.js"></script>
    

@endsection
