@include('pagetitle')
@extends('master')

@section('main-hk-pg-wrapper')
<style type="text/css">
.hk-sec-wrapper .form-control{
	height: auto !important;
	line-height: 1 !important;	
}
.table td, .table th{
	padding: .5rem !important;
}
.form-control[readonly]{
	border-color:#ced4da !important;
	background:#fff !important;
	color:#324148 !important;
	width:50px !important;
}
.ui-helper-hidden-accessible{
    display: none !important;
}
.barcode_color{
    color:#dd3f08;
}

.modal-lg {
    max-width: 70%;
}

#cke_1_contents{
    height:150px !important;
}

.htmltojpg_data{
    border:#ddd 1px solid;
    padding:10px;
    border-radius: 5px;
}
.htmltojpg_data tr td{
    font-size: 12px;
}

.tablesaw-sortable-switch, .tablesaw-modeswitch{
    display: none;
}

.tablesaw th, .tablesaw tr td{
    white-space: nowrap;
}

</style>


<div class="container ml-10">
    <span class="commonbreadcrumbtn badge exportBtn badge-pill mr-10"  id="exportDamageProductdata"><i class="ion ion-md-download"></i>&nbsp;Download Damage/Used Productwise Excel</span>
    
    <span class="commonbreadcrumbtn badge badge-danger badge-pill"  id="searchCollapse"><i class="glyphicon glyphicon-search"></i>&nbsp;Search</span>
<div class="row">
    <div class="col-xl-12"> 
        <section class="hk-sec-wrapper collapse" id="searchbox">
            <div class="hk-row common-search">
                <div class="col-sm-2">                           
                     <div class="form-group">
                      <input type="text" name-attr="from_to_date" name="fromtodate" id="fromtodate" class="daterange form-control form-inputtext" placeholder="Select Date"/>

                    </div>
                </div>

                <div class="col-sm-3">                            
                     <div class="form-group">
                      <input type="text" name-attr="damageproductsearch" name="damageproductsearch" id="damageproductsearch" class="form-control form-inputtext" placeholder="By Barcode / Product Name" autocomplete="off">
                         <input type="hidden" name-attr="damage_product_search_id" value="" id="damage_product_search_id">
                    </div>
                </div>

                <div class="col-sm-3">                            
                     <div class="form-group">
                        <?php
                            if(sizeof($damage_types)!=0)
                            {
                        ?>
                            @foreach($damage_types as $i=> $value)
                            <input type="checkbox" name="DamageType" id="<?php echo $value['damage_type_id']?>" value="<?php echo $value['damage_type_id']?>" />&nbsp;<?php echo $value['damage_type']?>&nbsp;
                            @endforeach
                        <?php
                            }
                            else
                            {
                                echo 'no data found...';
                            }
                        ?>

                        <input type="hidden" name-attr="DamageIds" name="DamageIds" id="DamageIds" value="" />
                    </div>
                </div>
                <div class="col-sm-4">
                    <button type="button" class="btn searchBtn search_data" id="SearchDamageData"><i class="fa fa-search"></i>Search</button>
                    <button type="button" name="resetdamageproductfilter" onclick="resetdamageproductfilterdata();" class="btn resetbtn" id="resetdamageproductfilter" data-container="body" data-toggle="popover" data-placement="bottom" data-content="" data-original-title="" title="">Reset</button>
                </div>
            </div>         
        </section>
    </div> 





</div>
<div class="row">
                <div class="col-xl-12">
                    <section class="hk-sec-wrapper">
                        <div class="row">
                            <div class="col-sm">
                                <!-- <small class="badge badge-soft-success mt-15 mr-10"><b>Total Damage QTY:</b> <span class="totalDamageQty">0</span></small>   -->
                                <div class="table-wrap">
                                    <div class="table-responsive" id="searchDamageproductReportData">
                                        @include('damageproducts::damageproducts/view_damagereport_data')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
  
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/popper.js/js/popper.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>
  
    <script src="{{URL::to('/')}}/public/dist/js/bootstrap-typeahead.js"></script>
    <script src="{{URL::to('/')}}/public/modulejs/DamageUsedProducts/damage_used_product_wise.js"></script>

<script type="text/javascript">
    $(document).ready(function(e){
        $('#searchCollapse').click(function(e){
            $('#searchbox').slideToggle();
        })
    })
    </script>

@endsection

