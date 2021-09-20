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

</style>


<form method="post" name="damage_used_product" id="damage_used_product" enctype="multipart/form-data">
<div class="container ml-20 mt-0">
<div class="row">


    <input type="hidden" id="damage_product_id" name="damage_product_id" value="" />
    <input type="hidden" class="form-control form-inputtext mt-15" placeholder="" name="damage_no" id="damage_no" value="{{$damage_no}}" readonly style="width:150px !important;">
    <input type="hidden" name="company_state" id="company_state" value="{{$company_state}}">
    <input type="hidden" name="company_bill_type" id="company_bill_type" value="{{$billtype}}">
    <input type="hidden" name="company_bill_type" id="tax_type" value="{{$tax_type}}">

    <div class="col-xl-9"> 
        <section class="hk-sec-wrapper">
            <div class="row">
                <div class="col-xl-4">
                    <label class="form-label"></label>
                <?php
                    if(sizeof($damage_types)!=0)
                    {
                ?>
                    @foreach($damage_types as $i=> $value)
                    <input type="radio" name="DamageType[]" id="<?php $value['damage_type_id'] ?>" <?php echo $value['damage_type_id']==1?'checked':'';?> value="<?php echo $value['damage_type_id']?>" />&nbsp;<?php echo $value['damage_type']?>&nbsp;
                    @endforeach
                <?php
                    }
                    else
                    {
                        echo 'no template found...';
                    }
                ?>
                </div>

                <div class="col-xl-6">
                    <div class="input-group">
                        <label class="form-label"></label>
                        <span class="input-group-prepend">
                            <label class="input-group-text" style="height: 40px;"><i class="fa fa-search"></i></label></span>
                        <input class="form-control form-inputtext typeahead" value="" maxlength="" type="text" name="damage_productsearch" id="damage_productsearch" placeholder="Barcode_ProductName_BatchNo_InvoiceNo" data-provide="typeahead" data-items="20" data-source="" style="height: 40px !important;">
                     </div>
                </div>

                <div class="col-xl-2">
                    <div class="input-group">
                    <label class="form-label">Damage Date</label>
                    <input class="form-control form-inputtext invalid" value="" autocomplete="off" type="text" name="damage_date" id="damage_date" placeholder=" ">
                    </div>
                </div>
        </div>
        </section>
    <!--  data-toggle="tooltip" data-placement="top" data-original-title="Cost Price" -->
  
        <section class="hk-sec-wrapper">
            <div class="table-wrap">
            <div class="table-responsive">

                <div class="hk-row">
                    <div class="col-md-10">

                    </div>
                    <div class="col-md-2 rightAlign">
                        <h5 class="hk-sec-title">
                            <small class="badge badge-soft-danger mt-15 mr-10"><b>No. of Items:</b>
                                <span class="damage_total_item">0</span>
                            </small>
                        </h5>
                    </div>
                </div>
                <?php
                $tax_label = 'GST';


                if($nav_type[0]['tax_type'] == 1)
                {
                    $tax_label = $nav_type[0]['tax_title'];
                }
                ?>
                <table class="table tablesaw table-bordered table-hover table-striped mb-0" data-tablesaw-mode="swipe"  data-tablesaw-sortable data-tablesaw-minimap data-tablesaw-mode-switch>
                    <thead class="thead-primary">
                        <tr class="header">
                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">Supplier Name</th>
                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Product Name</th>
                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Barcode</th>
                           <th class="batch_case_show" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Batch No.</th>
                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">invoice No.<i class="fa fa-info-circle" title="Supplier Purchase Invoice No."></i></th>
                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">Pcode</th>
                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="7">Color / Size</th>
                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">In Stock<i class="fa fa-info-circle" title="Balance stock specific to this purchase invoice. (Total Purchased Qty)"></i></th>
                            <th class="rightAlign" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="9">Cost</th>
                            <th class="rightAlign" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="10">Offer Price</th>
                            <th class="rightAlign" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="11">Qty</th>
                            <th class="rightAlign" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="12">Total Cost</th>
                            <th class="rightAlign" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="13"><?php echo $tax_label?> %</th>
                            <th class="rightAlign" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="14"><?php echo $tax_label?> Amt.</th>
                            <th class="rightAlign" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="15">Total</th>
                            <th width="100" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="16">Notes</th>      
                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="17">Action</th>
                        </tr>
                    </thead>
                    <tbody id="DamageSearchResult">
                       
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
                        <td class="rightAlign"><b>QTY:</b></td>
                        <td class="rightAlign" id="totqtyData"><b>0.00</b></td>
                    </tr>
                    <tr>
                        <td class="rightAlign"><b>Total Cost:</b></td>
                        <td class="rightAlign" id="totcostData"><b>0.00</b></td>
                    </tr>
                    <tr>
                        <td class="rightAlign"><b>Total GST Amt:</b></td>
                        <td class="rightAlign" id="totgstData"><b>0.00</b></td>
                    </tr>
                    <tr>
                        <td class="rightAlign"><b>Total Cost Price:</b></td>
                        <td class="rightAlign" id="totcostpriceData"><b>0.00</b></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <!-- Save Button -->
                            <button type="button" id="saveDamageProducts" name="saveDamageProducts" class="btn savenewBtn btn-block" style="color:#ffffff;"><i class="fa fa-save"></i>Save &amp; New</button>
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

    <script src="{{URL::to('/')}}/public/modulejs/DamageUsedProducts/damage-used.js"></script>
        
@endsection

