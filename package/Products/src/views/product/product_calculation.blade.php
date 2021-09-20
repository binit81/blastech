<?php
/**
 * Created by PhpStorm.
 * User: Hemaxi
 * Date: 9/3/19
 * Time: 2:45 PM
 */

 $tax_label = 'GST';

    if($nav_type[0]['tax_type'] == 1)
    {
        $tax_label = $nav_type[0]['tax_title'];
    }

?>

<div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 ">
                    <label class="form-label">Cost Rate</label>
                    <input class="form-control form-inputtext number" value="0" maxlength="10" autocomplete="off" name="cost_rate" id="cost_rate" type="text" placeholder=" ">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Cost <?php echo $tax_label?> %</label>
                    <input class="form-control form-inputtext number" value="0" maxlength="2" autocomplete="off" type="text" name="cost_gst_percent" id="cost_gst_percent" placeholder=" ">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Cost <?php echo $tax_label?> &#8377;</label>
                    <input class="form-control form-inputtext number notallowinput" tabindex="-1" style="color: black;font-size: 2rem;" value="0" maxlength="10"  autocomplete="off" type="text" name="cost_gst_amount" id="cost_gst_amount" placeholder=" ">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Cost Price</label>
                    <input class="form-control form-inputtext number notallowinput" tabindex="-1" style="color: black;font-size: 2rem;" value="0" maxlength="10" autocomplete="off" type="text" name="cost_price" id="cost_price" placeholder=" ">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Extra Charge</label>
                    <input class="form-control form-inputtext number"   value="0" maxlength="10" autocomplete="off" type="text" name="extra_charge" id="extra_charge" placeholder=" ">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Profit %</label>
                    <input class="form-control form-inputtext number" value="0" maxlength="10" autocomplete="off" type="text" name="profit_percent" id="profit_percent" placeholder=" ">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Profit &#8377;</label>
                    <input class="form-control form-inputtext number notallowinput" tabindex="-1" style="color: black;font-size: 2rem;" value="0" maxlength="10"  autocomplete="off" type="text" name="profit_amount" id="profit_amount" placeholder=" ">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Selling Rate</label>
                    <input  class="form-control form-inputtext number notallowinput" tabindex="-1" style="color: black;font-size: 2rem;" value="0" maxlength="10" autocomplete="off" type="text" name="selling_price" id="selling_price" placeholder=" ">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sell <?php echo $tax_label?> %</label>
                    <input class="form-control form-inputtext number" value="0" maxlength="2" autocomplete="off" type="text" name="sell_gst_percent" id="sell_gst_percent" placeholder=" ">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sell <?php echo $tax_label?> &#8377;</label>
                    <input class="form-control form-inputtext number notallowinput" tabindex="-1" style="color: black;font-size: 2rem;" value="0" maxlength="10"  autocomplete="off" type="text" name="sell_gst_amount" id="sell_gst_amount" placeholder=" ">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Offer Price</label>
                    <input class="form-control form-inputtext number" value="0" maxlength="10" autocomplete="off" type="text" name="offer_price" id="offer_price" placeholder=" ">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Product MRP</label>
                    <input class="form-control form-inputtext number notallowinput" tabindex="-1" style="color: black;font-size: 2rem;" value="0"  maxlength="10" autocomplete="off" type="text" name="product_mrp" id="product_mrp" placeholder=" ">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Wholesale Price</label>
                    <input class="form-control form-inputtext number" value="0" maxlength="10" autocomplete="off" type="text" name="wholesale_price" id="wholesale_price" placeholder=" ">
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>
<script src="{{URL::to('/')}}/public/modulejs/product/product_calculation.js"></script>
{{--<script src="{{URL::to('/')}}/public/modulejs/product/product_roundoff_calculation.js"></script>--}}
