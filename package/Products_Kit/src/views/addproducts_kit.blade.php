@include('pagetitle')
@extends('master')

@section('main-hk-pg-wrapper')
<style type="text/css">
.col-md-7,.col-md-5{
    padding-left:0 !important;
    padding-right:0 !important;
}
.table thead tr.header th {
   
    /*font-size: 0.95rem !important;*/
}
.table tbody tr td {
   
    /*font-size: 0.95rem !important;*/
}


.active {
    display: block;
    background:#D8D8D8;
    border:1px solid #CFCFCF;

}
.modal-content .form-inputtext {
    height: calc(2rem + 1px) !important;
    font-size: 1rem !important;
    margin-bottom: 0.50rem !important;
    width:80% !important;
}
.modal-content .form-control[readonly] {
    border: 1px solid #ced4da !important;
    background: transparent;
    color: #000 !important;
    font-size: 0.8rem;
    font-weight: bold;
}
#paymentmethoddiv .form-control[readonly] {
    border: 1px solid #ced4da !important;
    background: transparent;
    color: #000 !important;
    font-size: 0.8rem;
    font-weight: bold;
}
#charges_record .tarifform-control {
    border: 1px solid #ced4da !important;
    background: transparent;
    font-size: 0.9rem;
    color:#000;
}
.form-control, label {
    height: calc(1.95rem) !important;
    
}

</style>

<link rel="stylesheet" href="{{URL::to('/')}}/public/bower_components/bootstrap-datepicker/css/bootstrap-datepicker.css">
<div class="container">
<span class="badge badge-primary badge-pill" style="float:right; margin-right:205px; margin-top:-45px; padding:8px; cursor:pointer;" id="addnewkitcollapse"><i class="fa fa-plus"></i>&nbsp;Add Kit Details</span>

 <!---Start Product Kit enter section-->
<form name="productkitform" id="productkitform" method="POST">

<section id="product_block" class="collapse">
                <input type="hidden" name="product_id" id="product_id" value="">
                <input type="hidden" name="type" id="type" value="">
                <input type="hidden" name="inward_type" id="inward_type" value="">

                <div class="col-sm-12">
                    <div class="hk-row">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                       <div class="col-md-5 pa-0">
                                        <label class="form-label leftAlign">Product Name</label>
                                        </div>
                                        <div class="col-md-7 pa-0">
                                         <input class="form-control form-inputtext" value="" name="product_name" id="product_name" type="text" placeholder=" ">
                                        </div>
                                        <div class="col-md-5 pa-0">
                                        <label class="form-label leftAlign">Note</label>
                                        </div>
                                        <div class="col-md-7 pa-0">
                                        <textarea name="product_note" id="product_note" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            @include('products::product/product_calculation')
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="hk-row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Optional</h5>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">SKU</label>
                                            <input class="form-control form-inputtext" value="" maxlength="" autocomplete="off" type="text" name="sku_code" id="sku_code" placeholder=" ">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Product Code</label>
                                            <input class="form-control form-inputtext" value="" maxlength="" autocomplete="off" type="text" name="product_code" id="product_code" placeholder=" ">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Product Description</label>
                                            <input class="form-control form-inputtext" value="" maxlength="" autocomplete="off" type="text" name="product_description" id="product_description" placeholder=" ">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">HSN</label>
                                            <input class="form-control form-inputtext number" value="" maxlength="" autocomplete="off" type="text" name="hsn_sac_code" id="hsn_sac_code" placeholder=" ">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Brand</label>
                                            <select class="form-control form-inputtext" name="brand_id" id="brand_id">
                                            </select>
                                        </div>
                                        <div class="col-md-1 mt-25">
                                            <button type="button" class="btn btn-info addmoreoption" id="addbrand" style="height:40px"><i class="fa fa-plus"></i></button>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Category</label>
                                            <select class="form-control form-inputtext" onchange="getsubcategory('')" name="category_id" id="category_id">
                                            </select>
                                        </div>
                                        <div class="col-md-1 mt-25">
                                            <button type="button" class="btn btn-info addmoreoption" id="addcategory"
                                                    name="addcategory" style="height:40px"><i class="fa fa-plus"></i></button>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Sub Category</label>
                                            <select class="form-control form-inputtext" name="subcategory_id" id="subcategory_id">
                                            </select>
                                        </div>
                                        <div class="col-md-1 mt-25">
                                            <button type="button" class="btn btn-info addmoreoption" id="addsubcategory" name="addsubcategory" style="height:40px"><i class="fa fa-plus"></i></button>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Colour</label>
                                            <select class="form-control form-inputtext" name="colour_id" id="colour_id">
                                            </select>
                                        </div>
                                        <div class="col-md-1 mt-25">
                                            <button type="button" class="btn btn-info addmoreoption" id="addcolour" name="addcolour" style="height:40px"><i class="fa fa-plus"></i></button>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Size</label>
                                            <select class="form-control form-inputtext" name="size_id" id="size_id">
                                            </select>
                                        </div>
                                        <div class="col-md-1 mt-25">
                                            <button type="button" class="btn btn-info addmoreoption" id="addsize" name="addsize" style="height:40px"><i class="fa fa-plus"></i></button>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">UQC</label>
                                            <select class="form-control form-inputtext" name="uqc_id" id="uqc_id">
                                            </select>
                                        </div>
                                        <div class="col-md-1 mt-25">
                                            {{--<button type="button" class="btn btn-info addmoreoption" id="adduqc" name="adduqc"><i class="fa fa-plus"></i></button>--}}
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Material</label>
                                            <input class="form-control form-inputtext" value="" maxlength="10"
                                                   autocomplete="off" type="text" name="material_id" id="material_id"
                                                   placeholder=" ">
                                        </div>
                                        <?php //if($inward_type == 1) {?>
                                        <div class="col-md-3">
                                            <label class="form-label">Alert Before Product Expiry(Days)</label>
                                            <input class="form-control form-inputtext number" value="" maxlength="10"
                                                   autocomplete="off" type="text" name="days_before_product_expiry"
                                                   id="days_before_product_expiry" placeholder=" ">
                                        </div>
                                        <?php //} ?>
                                        <div class="col-md-3">
                                            <label class="form-label">Product System Barcode</label>
                                            <input class="form-control form-inputtext notallowinput"
                                                   value="{{$system_barcode_final}}" maxlength="10" autocomplete="off"
                                                   type="text" name="product_system_barcode" id="product_system_barcode"
                                                   placeholder=" ">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Kit Barcode</label>
                                            <input class="form-control form-inputtext notallowinput" value="{{$supplier_barcode}}" autocomplete="off" type="text" name="supplier_barcode" id="supplier_barcode" placeholder=" ">
                                        </div>
                                       
                                        <div class="col-md-3">
                                            <label class="form-label">Low Stock Alert</label>
                                            <input type="text" maxlength="10" class="form-control form-inputtext number" name="alert_product_qty" id="alert_product_qty" value="">
                                        </div>

                                       <div class="col-md-12">
                                            <div class="row" id="imageblock">
                                                <div class="col-md-2 block_1" class="previews">
                                                    <label class="form-label">Product Image Caption</label>
                                                    <input type="text" name="imageCaption[]" id="imageCaption_1" placeholder="" /></div>
                                                <div class="col-md-2 block_1">
                                                    <div class="form-group">
                                                        
                                                        <label class="form-label">Product Image</label>
                                                        <input type="file" onchange="previewandvalidation(this);" data-counter="1" accept=".png, .jpg, .jpeg" name="product_image[]" id="product_image_1" class="form-control form-inputtext productimage" value="">
                                                        <div id="preview_1" class="previews" style="display: none">
                                                            <a onclick="removeimgsrc('1');" class="displayright"><i class="fa fa-remove" style="font-size: 20px;"></i></a>
                                                            <img src="" id="product_preview_1" name="product_preview_1" width="" height="150px">
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-info mt-25" style="height:40px" id="addmoreimg" name="addmoreimg"><i class="fa fa-plus"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row pa-0" id="EditImagesBlock" style="display:none;"></div>
                                        <div class="col-md-12 mt-25 rightAlign">
                                             <button type="submit" name="addproductkit" class="btn btn-info saveBtn" id="addproductkit" data-container="body" data-toggle="popover" data-placement="bottom" data-content="">Add Product Kit</button>
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

</section>
</form>
<!---End Product Kit enter section-->
<form name="billingform" id="billingform" method="POST">
   
    <div class="row ma-0">
        <div class="col-sm-9">
        <div class="hk-row ma-0">
                <div class="col-md-12">
                    
        <!-----------------------productsdetail-->

                     <div class="card pa-0 ma-0">
                        <b style="margin:5px 0 0 10px;">Add Items in Kit</b>
                        <div class="card-body pa-10">
                            <div class="table-wrap">
                                <div class="row pa-0">
                                 <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-prepend"><label class="input-group-text" style="height: 40px;"><i class="fa fa-search"></i></label></span>
                                                <input class="form-control form-inputtext typeahead" value="" maxlength="" type="text" name="productsearch" id="productsearch" placeholder="Enter Barcode/Product Code/Product Name" data-provide="typeahead" data-items="10" data-source="">
                                             </div>
                                    </div>
                                   
                                    <div class="col-md-2 rightAlign"><h5 class="hk-sec-title showtbalance" style="margin-right:-30px; margin-top:-10px;"><small  class="badge badge-soft-success  ma-0">Total Cost Price <br><b><span class="ttotalcostprice">0</span></b></small></h5></div>
                                    <div class="col-md-2 rightAlign"><h5 class="hk-sec-title showtbalance" style="margin-right:-30px; margin-top:-10px;"><small  class="badge badge-soft-success  ma-0">Total Qty <br><b><span class="ttotalqty">0</span></b></small></h5></div>
                                    <div class="col-md-2 rightAlign"><h5 class="hk-sec-title showtbalance" style="margin-right:-30px; margin-top:-10px;"><small  class="badge badge-soft-success  ma-0">Total MRP <br><b><span class="ttotalmrp">0</span></b></small></h5></div>
                                    

                                </div>
                                        <div class="table-responsive pa-0 ma-0">
                                           
                                            <table width="100%" border="0">
                                                
                                          
                                                <thead>
                                                <tr class="blue_Head">
                                                    <th class="pa-10 leftAlign">Item (<span class="titems">0</span>)</th>
                                                    <th>Barcode</th>
                                                    <th>Size</th>
                                                    <th>Colour</th>
                                                    <th>UQC</th>
                                                    <th>InStock</th>
                                                    <th class="rightAlign" style="width:10%">Cost Price</th>
                                                    <th class="rightAlign" style="width:10%">MRP</th>
                                                    <th class="rightAlign" style="width:10%">Rate</th>
                                                    <th class="rightAlign" style="width:6%">Qty</th>
                                                    <th class="rightAlign" style="width:10%">Total</th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                                </thead>
                                                
                                                 
                                               <tbody id="sproduct_detail_record">
                                               </tbody>
                                            </table>
                                        </div><!--table-wrap-->
                                    </div><!--table-responsive-->
                                </div>
                            </div>
                        </div>
                    </div>
        
  <!--hk-row-->
   <div class="hk-row">  
            
     <div class="col-md-12">
       
            </div>
            </div>
       
     </div>

      <input type="hidden" name="sales_bill_id" id="sales_bill_id">
      <input type="hidden" name="customer_id" id="ccustomer_id">
        <!--col-xl-9-->
        <div class="col-sm-3 pa-0">
           
            <div class="hk-row">
                <div class="col-sm-12 pa-0">
                    <div class="card pa-10" id="productdetailsDiv">
                        <div class="row pl-0 ma-0">
                            <div class="row pl-0 ma-0">
                            <div class="col-md-12 pa-0 pb-3" style="display:none;">
                            <input type="text" id="pproduct_id">                          
                            </div>
                            <div class="col-md-5 pa-0 pb-3">
                            <label class="form-label leftAlign">Kit Selling Price</label>
                            </div>
                            <div class="col-md-7 pa-0">
                            <label class="showselling_price leftAlign bold"></label>
                            </div>
                            <div class="col-md-5 pa-0 pb-3">
                            <label class="form-label leftAlign">MRP</label>
                            </div>
                            <div class="col-md-7 pa-0">
                            <label class="showmrp leftAlign bold"></label>
                            </div>
                            <div class="col-md-5 pa-0 pb-3">
                            <label class="form-label leftAlign">Product Name</label>
                            </div>
                            <div class="col-md-7 pa-0">
                            <label class="showproduct_name leftAlign bold"></label>
                            </div>
                            <div class="col-md-5 pa-0 pb-3">
                            <label class="form-label leftAlign">Note</label>
                            </div>
                            <div class="col-md-7 pa-0">
                            <label class="showproduct_note leftAlign bold"></label>
                            </div>
                            <div class="col-md-5 pa-0 pb-3">
                            <label class="form-label leftAlign">SKU</label>
                            </div>
                            <div class="col-md-7 pa-0">
                            <label class="showsku_code leftAlign bold"></label>
                            </div>
                            <div class="col-md-5 pa-0 pb-3">
                            <label class="form-label leftAlign">Product Code</label>
                            </div>
                            <div class="col-md-7 pa-0">
                            <label class="showproduct_code leftAlign bold"></label>
                            </div>
                            <div class="col-md-5 pa-0 pb-3">
                            <label class="form-label leftAlign">Product Description</label>
                            </div>
                            <div class="col-md-7 pa-0">
                            <label class="showproduct_description leftAlign bold"></label>
                            </div>
                            <div class="col-md-5 pa-0 pb-3">
                            <label class="form-label leftAlign">HSN</label>
                            </div>
                            <div class="col-md-7 pa-0">
                            <label class="showhsn_sac_code leftAlign bold"></label>
                            </div>
                            <div class="col-md-5 pa-0 pb-3">
                            <label class="form-label leftAlign">Brand</label>
                            </div>
                            <div class="col-md-7 pa-0">
                            <label class="showbrand_id leftAlign bold"></label>
                            </div>
                            <div class="col-md-5 pa-0 pb-3">
                            <label class="form-label leftAlign">Category</label>
                            </div>
                            <div class="col-md-7 pa-0">
                            <label class="showcategory_id leftAlign bold"></label>
                            </div>
                            <div class="col-md-5 pa-0 pb-5">
                            <label class="form-label leftAlign">Sub Category</label>
                            </div>
                            <div class="col-md-7 pa-0">
                           <label class="showsubcategory_id leftAlign bold"></label>
                            </div>
                            <div class="col-md-5 pa-0 pb-3">
                            <label class="form-label leftAlign">Colour</label>
                            </div>
                            <div class="col-md-7 pa-0">
                            <label class="showcolour_id leftAlign bold"></label>
                            </div>
                            <div class="col-md-5 pa-0 pb-3">
                            <label class="form-label leftAlign">Size</label>
                            </div>
                            <div class="col-md-7 pa-0">
                            <label class="showsize_id leftAlign bold"></label>
                            </div>
                            <div class="col-md-5 pa-0 pb-3">
                            <label class="form-label leftAlign">UQC</label>
                            </div>
                            <div class="col-md-7 pa-0">
                            <label class="showuqc_id leftAlign bold"></label>
                            </div>
                            <div class="col-md-5 pa-0 pb-3">
                            <label class="form-label leftAlign">Material</label>
                            </div>
                            <div class="col-md-7 pa-0">
                            <label class="showmaterial_id leftAlign bold"></label>
                            </div>
                            <div class="col-md-5 pa-0 pb-3">
                            <label class="form-label leftAlign">System Barcode</label>
                            </div>
                            <div class="col-md-7 pa-0">
                            <label class="showproduct_system_barcode leftAlign bold"></label>
                            </div>
                            <div class="col-md-5 pa-0 pb-3">
                            <label class="form-label leftAlign">Supplier Barcode</label>
                            </div>
                            <div class="col-md-7 pa-0">
                            <label class="showsupplier_barcode leftAlign bold"></label>
                            </div>
                            <div class="col-md-5 pa-0 pb-3">
                            <label class="form-label leftAlign">Low Stock Alert</label>
                            </div>
                            <div class="col-md-7 pa-0">
                            <label class="showalert_product_qty leftAlign bold"></label>
                            </div>
                        
                        </div>
                        </div>    
                    </div>
                    
                    </div>
                </div>
        
                <div class="col-sm-12 pa-0">
                    <div class="pa-0">
                      <div class="row pa-0 ma-0">
                          <div class="col-sm-3 pa-0 pr-5">
                              
                          </div> 
                          <div class="col-sm-6 pa-0 pl-5">
                              <button type="button" class="btn btn-info savenewBtn btn-block" name="addbilling" id="addbilling"><i class="fa fa-save"></i>Save & New</button>
                          </div>
                           <div class="col-sm-3 pa-0 pr-5">
                              
                          </div> 
                      </div>
                   </div>
            </div><!--hk-row-->
        </div><!--col-xl-3-->

    </div><!--row-->
    </div>
    </div>


</form>
<div class="modal fade" id="addbrandpopup">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Brand</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="container"></div>
                <form id="brandform">
                    <input type="hidden" name="pbrand_id" value="" id="pbrand_id">
                    <div class="modal-body">

                        <label class="form-label">Brand Name</label>
                        <input class="form-control form-inputtext" autocomplete="off" name="brand_type" id="brand_type"
                               maxlength="100" type="text" placeholder=" ">
                    </div>

                    <div class="modal-footer">
                        <a href="#" data-dismiss="modal" class="btn">Close</a>
                        <button type="submit" id="savebrand" class="btn btn-info">Save Brand</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addcategorypopup">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Category</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="container"></div>
                <form id="categoryform">


                    <input type="hidden" name="category_id" onchange="getsubcategory('');" value="" id="category_id">
                    <div class="modal-body">

                        <label class="form-label">Category Name</label>
                        <input class="form-control form-inputtext" autocomplete="off" name="category_name"
                               id="category_name" maxlength="100" type="text" placeholder=" ">


                    </div>

                    <div class="modal-footer"><a href="#" data-dismiss="modal" class="btn">Close</a>
                        <button type="submit" id="savecategory" class="btn btn-info">Save Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addsubcategorypopup">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Subcategory</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="container"></div>
                <form id="subcategoryform">


                    <input type="hidden" name="subcategory_id" value="" id="subcategory_id">
                    <div class="modal-body">


                        <select class="form-control form-inputtext" name="popcategory_id" id="popcategory_id">
                        </select>


                        <label class="form-label">Subcategory Name</label>
                        <input class="form-control form-inputtext" autocomplete="off" name="subcategory_name"
                               id="subcategory_name" maxlength="100" type="text" placeholder=" ">


                    </div>

                    <div class="modal-footer"><a href="#" data-dismiss="modal" class="btn">Close</a>
                        <button type="submit" id="savesubcategory" class="btn btn-info">Save Subcategory</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addcolourpopup">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Colour</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="container"></div>
                <form id="colourform">


                    <input type="hidden" name="colour_id" value="" id="colour_id">
                    <div class="modal-body">
                        <label class="form-label">Colour Name</label>
                        <input class="form-control form-inputtext" autocomplete="off" name="colour_name"
                               id="colour_name" maxlength="100" type="text" placeholder=" ">


                    </div>

                    <div class="modal-footer"><a href="#" data-dismiss="modal" class="btn">Close</a>
                        <button type="submit" id="savecolour" class="btn btn-info">Save Colour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addsizepopup">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Size</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="container"></div>
                <form id="sizeform">


                    <input type="hidden" name="size_id" value="" id="size_id">
                    <div class="modal-body">
                        <div class="input-group input-group-default floating-label">
                            <label class="form-label"> Size Name</label>
                            <input class="form-control form-inputtext" autocomplete="off" name="size_name"
                                   id="size_name" maxlength="100" type="text" placeholder=" ">

                        </div>

                        <span id="sizeerr" style="color: red;font-size: 15px"></span>
                    </div>

                    <div class="modal-footer"><a href="#" data-dismiss="modal" class="btn">Close</a>
                        <button type="submit" id="savesize" class="btn btn-primary">Save Size</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="adduqcpopup">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add UQC</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="container"></div>
                <form id="uqcform">
                    <input type="hidden" name="uqc_id" value="" id="uqc_id">
                    <div class="modal-body">
                        <div class="input-group input-group-default floating-label">
                            <input class="form-control form-inputtext" autocomplete="off" name="uqc_name" id="uqc_name" maxlength="100" type="text" placeholder=" ">
                            <label class="form-label">UQC Name</label>
                        </div>
                        <div class="input-group input-group-default floating-label">
                            <input class="form-control form-inputtext" autocomplete="off" name="uqc_type" id="uqc_type" maxlength="100" type="text" placeholder=" ">
                            <label class="form-label">UQC Type</label>
                        </div>

                        <div class="input-group input-group-default floating-label">
                            <input class="form-control form-inputtext" autocomplete="off" name="uqc_shortname" id="uqc_shortname" maxlength="100" type="text" placeholder=" ">
                            <label class="form-label">UQC Name</label>
                        </div>

                        <span id="uqcerr" style="color: red;font-size: 15px"></span>
                    </div>

                    <div class="modal-footer"><a href="#" data-dismiss="modal" class="btn">Close</a>
                        <button type="submit" id="saveuqc" class="btn btn-primary">Save UQC</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<link rel="stylesheet" href="{{URL::to('/')}}/public/build/css/intlTelInput.css">


    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>
   
    <script type="text/javascript">
    $(document).ready(function () {
        $('#productsearch').focus();
        
    });
    </script>



    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/popper.js/js/popper.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/dist/js/datepicker.js"></script>-
    

    

    <script src="{{URL::to('/')}}/public/dist/js/bootstrap-typeahead.js"></script>
    <script src="{{URL::to('/')}}/public/modulejs/product/product.js"></script>
    <script src="{{URL::to('/')}}/public/modulejs/product/productkit.js"></script>
    <script src="{{URL::to('/')}}/public/modulejs/product/productproperties.js"></script>
    <script src="{{URL::to('/')}}/public/modulejs/common.js"></script>
   
    <script type="text/javascript" src="{{URL::to('/')}}/public/build/js/intlTelInput.js"></script>

   
@endsection
