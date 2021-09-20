@include('pagetitle')

@extends('master')

@section('main-hk-pg-wrapper')


    <div class="container">



        <div id="modelData" style=""></div>

        <div class="modal fade bs-example-modal-lg" id="ModalCarousel34" tabindex="-1" role="dialog" aria-labelledby="ModalCarousel34" style="padding-right: 17px;">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body pa-0">
                        <div id="demo" class="carousel slide" data-ride="carousel">

                            <ul class="carousel-indicators">

                            </ul>

                            <div class="carousel-inner"></div>

                            <a class="carousel-control-prev" href="#demo" data-slide="prev">
                                <span class="carousel-control-prev-icon" style="color: black"></span>
                            </a>
                            <a class="carousel-control-next" href="#demo" data-slide="next">
                                <span class="carousel-control-next-icon" style="color: black"></span>
                            </a>
                        </div>

                        </div>
                    </div>
                </div>
            </div>








        <span class="commonbreadcrumbtn badge exportBtn badge-pill mr-0"  id="product_export"><i class="ion ion-md-download"></i>&nbsp;Download Products Data</span>

        <span class="commonbreadcrumbtn badge badge-primary badge-pill"  id="addnewcollapse"><i class="fa fa-plus"></i>&nbsp;Add New Product</span>

         <span class="commonbreadcrumbtn badge badge-danger badge-pill"  id="searchCollapse"><i class="glyphicon glyphicon-search"></i>&nbsp;Search</span>

        <form name="productform" id="productform"  method="post" enctype="multipart/form-data">
            <meta name="csrf-token" content="{{ csrf_token() }}" />
        <section id="product_block" class="collapse">
         <div class="row ml-0">
            
                <input type="hidden" name="product_id" id="product_id" value="">
                <input type="hidden" name="type" id="type" value="">
                <input type="hidden" name="inward_type" id="inward_type" value="{{$inward_type}}">

                <div class="col-sm-12">
                    <div class="hk-row">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="form-label">Product Name</label>
                                            <input class="form-control form-inputtext invalid" value="" name="product_name" id="product_name" type="text" placeholder=" ">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Note</label>
                                            <textarea name="product_note" id="product_note" class="form-control"></textarea>
                                        </div>

                                        {{-- <div class="col-md-12">
                                            <label class="form-label">Product Type</label>
                                            <input type="radio" name="product_type" checked id="fmcgproduct" value="1">
                                            <span style="font-size: 16px;color: black">FMCG</span>
                                            <input type="radio" name="product_type" id="garmentproduct" value="2">
                                            <span style="font-size: 16px;color: black">GARMENT</span>
                                        </div>--}}
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
                                        <?php if($inward_type == 1) {?>
                                        <div class="col-md-3">
                                            <label class="form-label">Alert Before Product Expiry(Days)</label>
                                            <input class="form-control form-inputtext number" value="" maxlength="10"
                                                   autocomplete="off" type="text" name="days_before_product_expiry"
                                                   id="days_before_product_expiry" placeholder=" ">
                                        </div>
                                        <?php } ?>
                                        <div class="col-md-3">
                                            <label class="form-label">Product System Barcode</label>
                                            <input class="form-control form-inputtext notallowinput"
                                                   value="{{$system_barcode_final}}" maxlength="10" autocomplete="off"
                                                   type="text" name="product_system_barcode" id="product_system_barcode"
                                                   placeholder=" ">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Supplier Barcode</label>
                                            <input class="form-control form-inputtext" value=" " autocomplete="off"
                                                   type="text" name="supplier_barcode" id="supplier_barcode"
                                                   placeholder=" ">
                                        </div>
                                       {{-- <div class="col-md-2 rightAlign">
                                            <label class="form-label rightAlign">Is EAN?</label>

                                            <input type="radio" name="is_ean" checked id="iseanyes" value="1"> <span style="font-size: 16px;color: black"><small>Yes</small></span>

                                            <input type="radio" name="is_ean" id="iseanno" value="0">
                                            <span style="font-size: 16px;color: black"><small>No</small></span>
                                        </div>--}}



                                        {{--<div class="col-md-2">
                                            <label class="form-label">Product EAN Barcode</label>
                                            <input class="form-control form-inputtext" value=" " maxlength="10" autocomplete="off" type="text" name="product_ean_barcode" id="product_ean_barcode" placeholder=" ">
                                        </div>--}}
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="hk-row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <input type="hidden" name="type" id="type" value="1" />
                                <button type="submit" name="addproduct" class="btn btn-info saveBtn" id="addproduct" data-container="body" data-toggle="popover" data-placement="bottom" data-content="">Add Product</button>
                                <button type="button" name="resetproduct" onclick="resetproductdata();" class="btn btn-info resetbtn" id="resetproduct" data-container="body" data-toggle="popover" data-placement="bottom" data-content="">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </form>

    <section class="hk-sec-wrapper collapse" id="filterarea_block">
        <div id="">
            <div class="hk-row common-search">
                <div class="col-md-2 pb-10">
                    <div class="form-group">
                        <input type="text" name-attr="product_name" maxlength="50" autocomplete="off" name="product_name_filter" id="product_name_filter" value="" class="form-control form-inputtext" placeholder="Product Name">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="text" name-attr="barcode" maxlength="50" autocomplete="off" name="barcode_filter" id="barcode_filter" value="" class="form-control form-inputtext" placeholder="Barcode">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select name-attr="brand_id" class="form-control form-inputtext" name="brand_id_filter" id="brand_id_filter"></select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select name-attr="category_id" class="form-control form-inputtext" onchange="getsubcategory_filter()" name="category_id_filter" id="category_id_filter"></select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select name-attr="subcategory_id" class="form-control form-inputtext" name="subcategory_id_filter" id="subcategory_id_filter"></select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select name-attr="colour_id" class="form-control form-inputtext" name="colour_id_filter" id="colour_id_filter"></select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select name-attr="size_id" class="form-control form-inputtext" name="size_id_filter" id="size_id_filter"></select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select name-attr="uqc_id" class="form-control form-inputtext" name="uqc_id_filter" id="uqc_id_filter"></select>
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-info searchBtn search_data"  id="search_product"><i class="fa fa-search"></i>Search</button>
                    <button type="button" name="resetfilter" onclick="resetproductfilterdata();" class="btn btn-info resetbtn" id="resetfilter" data-container="body" data-toggle="popover" data-placement="bottom" data-content="" data-original-title="" title="">Reset</button>
                </div>
            </div>
        </div>
        
    </section>

    <section class="hk-sec-wrapper" id="productmaintable">

        <div class="hk-row">
            <div class="col-md-2">
                <a id="deleteproduct" name="deleteproduct"><i class="fa fa-trash cursor" style="font-size: 20px;color: red;margin-left: 20px"></i></a>
            </div>
        </div>


        <div class="table-wrap">
            <div class="table-responsive" id="productrecord">
                @include('products::product/product_data')
            </div>
        </div><!--table-wrap-->

    </section>
        <div id="styleSelector">
    </div>


    <div class="modal fade" id="addbrandpopup">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Brand</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="container"></div>
                <form id="brandform">
                    <input type="hidden" name="brand_id" value="" id="brand_id">
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





    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/popper.js/js/popper.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>


    <script src="{{URL::to('/')}}/public/modulejs/product/product.js"></script>
    <script src="{{URL::to('/')}}/public/modulejs/product/productproperties.js"></script>
@endsection

