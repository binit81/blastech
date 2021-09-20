@include('pagetitle')
@extends('master')

@section('main-hk-pg-wrapper')
<style type="text/css">
.hk-sec-wrapper .form-control{
	height: auto !important;
	line-height: 1 !important;	
}
.table td, .table th{
	padding: .2rem !important;
}
.form-control[readonly]{
	border-color:#ced4da !important;
	background:#fff !important;
	color:#324148 !important;
	width:50px !important;
}


</style>


<form method="post" name="inward_productskit" id="inward_productskit" enctype="multipart/form-data">
<div class="container ml-20 mt-0">
<div class="row">


    <input type="hidden" id="product_id" name="product_id" value="" />
    <input type="hidden" id="inward_stock_id" name="inward_stock_id" value="" />
    <input type="hidden" id="inward_product_detail_id" name="inward_product_detail_id" value="" />
   
    <div class="col-xl-9"> 
        <section class="hk-sec-wrapper">
            <div class="row ma-0 pa-0 mb-0">
               
                <div class="col-sm-6">
                    <div class="input-group">                       
                        <span class="input-group-prepend">
                            <label class="input-group-text" style="height: 40px;"><i class="fa fa-search"></i></label></span>
                        <input class="form-control form-inputtext typeahead" value="" maxlength="" type="text" name="productkitsearch" id="productkitsearch" placeholder="Barcode_ProductName" data-provide="typeahead" data-items="20" data-source="" style="height: 40px !important;">
                     </div>
                </div>

                <div class="col-sm-2">
                    <div class="input-group">                  
                    <input class="form-control form-inputtext invalid number" value="" type="text" name="inward_qty" id="inward_qty" placeholder="Enter Kit Qty"  style="height: 40px !important;" onkeyup="return calculateqty();">
                    <input class="form-control form-inputtext invalid number" value="" type="hidden" name="oldinward_qty" id="oldinward_qty" style="height: 40px !important;">
                    </div>
                </div>
                <div class="col-sm-4 rightAlign">
                    <h5 class="hk-sec-title">
                            <small class="badge badge-soft-danger mt-15 mr-10"><b>No. of Items:</b>
                                <span class="titems">0</span></span>
                            </small>
                        </h5>
                </div>
        </div>
        </section>
    <!--  data-toggle="tooltip" data-placement="top" data-original-title="Cost Price" -->
  
        <section class="hk-sec-wrapper">
            <div class="table-wrap">
            <div class="table-responsive">

                  
                <table class="table table-striped mb-0">
                    <thead class="thead-primary">
                        <tr>
                            <th width="20%">Item</th>
                            <th width="14%">Barcode</th>
                            <th width="10%">Size</th>
                            <th width="12%">Colour</th>
                            <th width="10%">UQC</th>
                            <th width="10%" class="rightAlign">In Stock</th>
                            <th width="10%" class="rightAlign">Qty</th>
                            <th width="10%" class="rightAlign">Total Qty</th>
                            <th width="3%"></th>
                        </tr>
                    </thead>
                    <tbody id="kitSearchResult">
                       
                    </tbody>
                </table>
            </div>
        </div>
        </section>
    </div>

    <div class="col-xl-3">
        <section class="hk-sec-wrapper">
            <div class="table-wrap">
                <div class="table-responsive">
                   <table border="0" class="table table-striped mb-0">
                     <tr>
                        <td class="rightAlign" width="50%"><b>Inward Date:</b></td>
                        <td class="rightAlign" width="50%"><input type="text" class="form-control" id="inward_date" style="width:80%;" value="{{date("d-m-Y")}}"></td>
                    </tr>
                    <tr>
                        <td class="rightAlign"><b>Total QTY:</b></td>
                        <td class="rightAlign" id="totqtyData"><b>0.00</b></td>
                    </tr>
                    
                    <tr>
                        <td colspan="2">
                            <!-- Save Button -->
                            <button type="button" id="saveInwardProducts" name="saveInwardProducts" class="btn savenewBtn btn-block" style="color:#ffffff;"><i class="fa fa-save"></i>Save &amp; New</button>
                            <!-- Save Button -->
                        </td>
                    </tr>
                   </table>
                </div>
            </div>
        </section>
    </div>

</div>
</div>

</form>


    <script src="{{URL::to('/')}}/public/template/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/popper.js/js/popper.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>
    <script src="{{URL::to('/')}}/public/dist/js/bootstrap-typeahead.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/dist/js/datepicker.js"></script>

    <script src="{{URL::to('/')}}/public/modulejs/product/productkit.js"></script>
    <script src="{{URL::to('/')}}/public/modulejs/product/inward_productkit.js"></script>
        
@endsection

