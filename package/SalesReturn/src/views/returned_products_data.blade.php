<?php
/**
 * Created by PhpStorm.
 * User: Hemaxi
 * Date: 26/3/19
 * Time: 10:18 AM
 */
?>
@foreach($returnproducts AS $returnkey=>$return_value)

<?php

 if ($returnkey % 2 == 0) {
    $tblclass = 'even';
} else {
    $tblclass = 'odd';
}

?>

<tr id="viewbill_{{$return_value['returnbill_product_id']}}" class="<?php echo $tblclass ?>">
    <td class="leftAlign"></td>
    <td class="leftAlign">{{$return_value['return_product_detail']['return_bill']['sales_bill']['bill_no']}}</td>
    <td class="leftAlign">{{$return_value['return_date']}}</td>
    <td class="leftAlign">{{$return_value['product']['product_name']}}</td>
    <td class="leftAlign">{{$return_value['product']['product_system_barcode']}}</td>
    <td class="leftAlign">{{$return_value['product']['sku_code']}}</td>
    <td class="leftAlign">{{$return_value['product']['category']['category_name']}}</td>
    <td style="text-align:right !important;">{{round($return_value['return_product_detail']['mrp'],2)}}</td>
    <td style="text-align:right !important;">{{round($return_value['return_product_detail']['sellingprice_before_discount'],2)}}</td>
    <td style="text-align:right !important;" id="returnqty_{{$return_value['returnbill_product_id']}}">{{$return_value['qty']}}</td>
    <td class="leftAlign" style="text-align:center !important;">
        <input type="hidden" id="inwardids_{{$return_value['returnbill_product_id']}}" value="{{$return_value['inwardids']}}">
        <input type="hidden" id="inwardqtys_{{$return_value['returnbill_product_id']}}" value="{{$return_value['inwardqtys']}}">
        <input type="text" id="restock_{{$return_value['returnbill_product_id']}}" onkeyup="return restockqty(this);" class="form-control mt-15" style="width:49% !important;margin:0 3px 0 0;">
        <input type="text" id="damage_{{$return_value['returnbill_product_id']}}" onkeyup="return damageqty(this);" class="form-control mt-15" style="width:49%  !important;">
        <input type="hidden" id="pricemasterid_{{$return_value['returnbill_product_id']}}" class="form-control mt-15" style="width:49%  !important;" value="{{$return_value['price_master_id']}}">
        <input type="hidden" id="productid_{{$return_value['returnbill_product_id']}}" class="form-control mt-15" style="width:49%  !important;" value="{{$return_value['product_id']}}">
        <input type="hidden" id="salesproductid_{{$return_value['returnbill_product_id']}}" class="form-control mt-15" style="width:49% !important;" value="{{$return_value['sales_products_detail_id']}}"></td>
    <td class="leftAlign" style="text-align:center !important;">
        <textarea id="remarks_{{$return_value['returnbill_product_id']}}" rows="2" style="width:100%;border-radius:5px;border:1px solid #ced4da;"></textarea>
    </td>
    <td class="leftAlign" style="text-align:center !important;"><button type="button" class="btn btn-info" name="addbilling" id="addreturnproducts_{{$return_value['returnbill_product_id']}}" onclick="return savereturn(this);" style="padding: 0rem .5rem !important;">Update</button></td>
   

</tr>


@endforeach
<tr>
    <td colspan="12" align="center">
        {!! $returnproducts->links() !!}
    </td>
</tr>


