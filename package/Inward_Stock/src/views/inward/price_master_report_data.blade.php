<?php
/**
 *
 * Created by PhpStorm.
 * User: Hemaxi
 * Date: 28/3/19
 * Time: 5:47 PM
 */

$tax_currency = '&#8377;';
$tax_title = 'GST';

if($nav_type[0]['tax_type'] == 1)
{
    $tax_title = $nav_type[0]['tax_title'];
    $tax_currency = $nav_type[0]['currency_title'];
}

?>
    <table id="pricemasterreport" class="table tablesaw table-bordered table-hover  mb-0" data-tablesaw-mode="swipe"  data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-minimap data-tablesaw-mode-switch>
    <thead>
    <tr class="header">

        <th class="garment_case_hide" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">Batch No<span id="batch_no_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Barcode</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Product Name</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Qty<span id="product_qty_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">Selling Price <?php echo $tax_currency?><span id="sell_price_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">Selling <?php echo $tax_title ?> %<span id="selling_gst_percent_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="7">Selling <?php echo $tax_title .' '.$tax_currency?><span id="selling_gst_amount_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">Offer Price <?php echo $tax_currency?><span id="offer_price_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="9">Product MRP <?php echo $tax_currency?><span id="product_mrp_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="10">Wholesaler Price <?php echo $tax_currency?><span id="wholesaler_price_icon"></span></th>
    </tr>
    </thead>
    <tbody >


@foreach($price_master AS $price_key=>$price_value)
    <?php if($price_key % 2 == 0)
    {
        $tblclass = 'even';
    }
    else
    {
        $tblclass = 'odd';
    }

    $barcode = '';

    if($price_value['product']['supplier_barcode'] != '')
    {
        $barcode =  $price_value['product']['supplier_barcode'];
    }
    else
    {
        $barcode = $price_value['product']['product_system_barcode'];
    }

    ?>
    <tr id="{{$price_value->price_master_id}}" class="<?php echo $tblclass ?>">
        <td class="leftAlign garment_case_hide">{{$price_value->batch_no}}</td>
        <td class="leftAlign"><?php echo $barcode ?></td>
        <td class="leftAlign">{{$price_value->product->product_name}}</td>
        <td class="rightAlign">{{$price_value->product_qty}}</td>
        <td class="rightAlign">{{number_format($price_value->sell_price)}}</td>
        <td class="rightAlign">{{number_format($price_value->selling_gst_percent)}}</td>
        <td class="rightAlign">{{number_format($price_value->selling_gst_amount)}}</td>
        <td class="rightAlign">{{number_format($price_value->offer_price)}}</td>
        <td class="rightAlign">{{number_format($price_value->product_mrp)}}</td>
        <td class="rightAlign">{{number_format($price_value->wholesaler_price)}}</td>
    </tr>
@endforeach
<tr>
    <td colspan="10" class="paginateui">
        {!! $price_master->links() !!}
    </td>
</tr>
    </tbody>
</table>
<input type="hidden" name="hidden_page" id="hidden_page" value="1" />
<input type="hidden" name="hidden_column_name" id="hidden_column_name" value="updated_at" />
<input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="desc" />
<input type="hidden" name="fetch_data_url" id="fetch_data_url" value="price_master_record" />

<script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>
<script src="{{URL::to('/')}}/public/dist/js/tablesaw-data.js"></script>
<script type="text/javascript">
    $(".PagecountResult").html(' ({{$price_master->total()}})');

    <?php if($inward_type == 2) { ?>
    $(".garment_case_hide").hide();
    <?php } else { ?>
    $(".garment_case_hide").show();
    <?php } ?>
</script>