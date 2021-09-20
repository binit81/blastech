<?php
if(sizeof($result)!=0)
{
?>

@foreach($result AS $resultkey=>$result_value)
<?php if ($resultkey % 2 == 0) {
    $tblclass = 'even';
} else {
    $tblclass = 'odd';
}



?>

<tr class="<?php echo $tblclass ?>" id="printTbl_{{$result_value->inward_product_detail_id}}">
    <td class="leftAlign">{{$result_value->product->product_name}}</td>
    <td class="leftAlign">{{$result_value->product->product_system_barcode}}</td>
    <td class="leftAlign">{{$result_value->product->supplier_barcode}}</td>
    <td class="leftAlign">{{$result_value->product->product_code}}</td>
    <td class="leftAlign">{{$result_value->product->sku_code}}</td>
    <td class="leftAlign">{{$result_value['product']['category']['category_name']}}</td>
    <td class="leftAlign">{{$result_value['product']['brand']['brand_type']}}</td>
    <td class="leftAlign">{{$result_value['product']['colour']['colour_name']}}</td>
    <td class="leftAlign">{{$result_value['product']['size']['size_name']}}</td>
    <td><input name="" type="text" class="form-control" size="1" value="{{$result_value->product_qty}}" readonly="readonly" /></td>
    <td class="rightAlign">{{$result_value->offer_price}}</td>
    <td id="fetchval_{{$result_value->inward_product_detail_id}}"><input name="printStock[]" id="printStock_{{$result_value->inward_product_detail_id}}" type="text" class="form-control printStock" onkeyup="getTotalPrintQty()" value="1" style="width:50px;" />
        <input name="productid[]" id="productid_{{$result_value->inward_product_detail_id}}" type="hidden" class="form-control printStockId" value="{{$result_value->product->product_id}}" style="width:50px;" />
        <input name="inwardid[]" id="inwardid_{{$result_value->inward_product_detail_id}}" type="hidden" class="form-control printStockId" value="{{$result_value->inward_product_detail_id}}" style="width:50px;" /></td>
    <td class="leftAlign"><span class="fa fa-remove cursor" id="removeTbl_{{$result_value->inward_product_detail_id}}" onClick="RemovePrintTbl(this)"></span>
        <input name="" type="hidden" class="form-control totalResults" size="1" value="1" readonly="readonly" /></td>
</tr>

<script type="text/javascript">
$(document).ready(function(e){

    var printStock  =   0;
    $('.printStock').each(function (index,e){
        printStock   +=   parseFloat($(this).val());
    });

    $('#barcodeTotalQty').html(printStock);
    $('#barcodeTotalQty_text').val(printStock);
});
</script>

@endforeach

@foreach($result1 AS $resultkey1=>$result_value1)

<script type="text/javascript">
$(document).ready(function(e){
    $('.totalSearchCount').html(' (<?php echo $result_value1->totalCount?>)');
});
</script>

@endforeach

<?php } else { ?>
<tr><td colspan="13" class="leftAlign">No result found...</td></tr>
<?php }?>