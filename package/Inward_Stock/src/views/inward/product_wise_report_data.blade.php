<?php
/**
 * Created by PhpStorm.
 * User: Hemaxi
 * Date: 26/3/19
 * Time: 10:18 AM
 */



 $tax_currency = '&#8377;';

    if($nav_type[0]['tax_type'] == 1)
    {
        $tax_currency = $nav_type[0]['currency_title'];
    }

?>
    <table id="productwisereport" class="table tablesaw table-bordered table-hover table-striped  mb-0" data-tablesaw-mode="swipe"  data-tablesaw-sortable data-tablesaw-minimap data-tablesaw-mode-switch>
        <thead>
        <?php if($inward_type == 2) { ?>
    <tr class="header">
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">Barcode</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Invoice No.</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Inward Date</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Product Name</th>

        <?php if($nav_type[0]['tax_type'] == 1) { ?>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">Cost Rate <?php echo $nav_type[0]['currency_title'] ?>(Without <?php echo $nav_type[0]['tax_title']?>)<span id="base_price_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">Cost <?php echo $nav_type[0]['tax_title'].' '.$nav_type[0]['currency_title']?><span id="cost_igst_amount_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="9">Cost Price <?php echo $nav_type[0]['currency_title'] ?>(With <?php echo $nav_type[0]['tax_title']?>)<span id="cost_price_icon"></span></th>

    <?php } else { ?>


        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">Cost Rate &#8377;(Without GST)<span id="base_price_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">Cost IGST &#8377;<span id="cost_igst_amount_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="7">Cost CGST &#8377;<span id="cost_cgst_amount_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">Cost SGST &#8377;<span id="cost_sgst_amount_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="9">Cost Price &#8377;(With GST)<span id="cost_price_icon"></span></th>

    <?php } ?>

        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="10">Qty<span id="product_qty_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="11">Pending Return Qty<span id="pending_return_qty_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="12">Total Cost Rate</th>
        <?php if($nav_type[0]['tax_type'] == 1) { ?>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="13">Total <?php echo $nav_type[0]['tax_title']?> %</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="14">Total <?php echo $nav_type[0]['tax_title'].' '.$nav_type[0]['currency_title']?>  </th>
        <?php } else { ?>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="13">Total CGST %</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="14">Total CGST &#8377; </th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="15">Total SGST %</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="16">Total SGST &#8377;</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="17">Total IGST %</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="18">Total IGST &#8377;</th>
        <?php } ?>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="19">Total Cost Price <?php echo $tax_currency?><span id="total_cost_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="20">Profit <?php echo $tax_currency?><span id="profit_amount_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="21">Sell Price <?php echo $tax_currency?><span id="sell_price_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="22">Selling GST <?php echo $tax_currency?><span id="selling_gst_amount_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="23">Offer Price <?php echo $tax_currency?><span id="offer_price_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="24">Product MRP <?php echo $tax_currency?><span id="product_mrp_icon"></span></th>
    </tr>
        <?php } else { ?>
        <tr class="header">
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">Barcode</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Invoice No.</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Batch No.<span id="batch_no_icon"></span></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Inward Date</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">Product Name</th>
            <?php if($nav_type[0]['tax_type'] == 1) { ?>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">Cost Rate <?php echo $nav_type[0]['currency_title'] ?>(Without <?php echo $nav_type[0]['tax_title']?>)<span id="base_price_icon"></span></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">Cost <?php echo $nav_type[0]['tax_title'].' '.$nav_type[0]['currency_title']?><span id="cost_igst_amount_icon"></span></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="9">Cost Price <?php echo $nav_type[0]['currency_title'] ?>(With <?php echo $nav_type[0]['tax_title']?>)<span id="cost_price_icon"></span></th>
        <?php } else { ?>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">Cost Rate &#8377;(Without GST)<span id="base_price_icon"></span></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="7">Cost IGST &#8377;<span id="cost_igst_amount_icon"></span></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">Cost CGST &#8377;<span id="cost_cgst_amount_icon"></span></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="9">Cost SGST &#8377;<span id="cost_sgst_amount_icon"></span></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="10">Cost Price &#8377;(With GST)<span id="cost_price_icon"></span></th>
            <?php } ?>

            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="11">Qty<span id="product_qty_icon"></span></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="12">Free Qty<span id="free_qty_icon"></span></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="13">Pending Return Qty<span id="pending_return_qty_icon"></span></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="14">Total Cost Rate</th>

            <?php if($nav_type[0]['tax_type'] == 1) { ?>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="13">Total <?php echo $nav_type[0]['tax_title']?> %</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="14">Total <?php echo $nav_type[0]['tax_title'].' '.$nav_type[0]['currency_title']?>  </th>
            <?php } else { ?>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="15">Total CGST %</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="16">Total CGST &#8377; </th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="17">Total SGST %</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="18">Total SGST &#8377;</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="19">Total IGST %</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="20">Total IGST &#8377;</th>
            <?php } ?>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="21">Total Cost Price <?php echo $tax_currency?><span id="total_cost_icon"></span></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="22">Profit <?php echo $tax_currency?><span id="profit_amount_icon"></span></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="23">Sell Price <?php echo $tax_currency?><span id="sell_price_icon"></span></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="24">Selling GST <?php echo $tax_currency?><span id="selling_gst_amount_icon"></span></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="25">Offer Price <?php echo $tax_currency?><span id="offer_price_icon"></span></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="26">Product MRP <?php echo $tax_currency?><span id="product_mrp_icon"></span></th>
        </tr>

        <?php } ?>
    </thead>
    <tbody>

@foreach($product_wise_report AS $key=>$value)
    <?php if ($key % 2 == 0)
    {
        $tblclass = 'even';
    }
    else
    {
        $tblclass = 'odd';
    }
    $barcode = '';
    if($value['product_detail']['supplier_barcode'] != '')
    {
        $barcode =  $value['product_detail']['supplier_barcode'];
    }
    else
    {
        $barcode = $value['product_detail']['product_system_barcode'];
    }
    ?>
    <?php if($inward_type == 2) { ?>
     <tr id="" class="<?php echo $tblclass ?>">
          <td class="leftAlign"><?php echo $barcode ?></td>
          <td class="leftAlign"><?php echo $value['inward_stock']['invoice_no']?></td>
          <td class="leftAlign"><?php echo $value['inward_stock']['inward_date']?></td>
          <td class="leftAlign">{{$value->product_detail->product_name}}</td>
          <td class="rightAlign">{{$value->cost_rate}}</td>

         <?php if($nav_type[0]['tax_type'] == 1) { ?>
         <td class="rightAlign">{{$value->cost_igst_amount}}</td>
         <?php } else { ?>
          <td class="rightAlign">{{$value->cost_igst_amount}}</td>
          <td class="rightAlign">{{$value->cost_cgst_amount}}</td>
          <td class="rightAlign">{{$value->cost_sgst_amount}}</td>
        <?php } ?>

          <td class="rightAlign">{{$value->cost_price}}</td>
          <td class="rightAlign">{{$value->product_qty}}</td>
          <td class="rightAlign">{{$value->pending_return_qty}}</td>
          <td class="rightAlign">{{$value->total_cost_rate_with_qty}}</td>

         <?php if($nav_type[0]['tax_type'] == 1) { ?>
         <td class="rightAlign">{{$value['cost_igst_percent']}}</td>
         <td class="rightAlign">{{$value['total_igst_amount_with_qty']}}</td>
         <?php } else { ?>
          <td class="rightAlign">{{$value['cost_cgst_percent']}}</td>
          <td class="rightAlign">{{$value['total_cgst_amount_with_qty']}}</td>
          <td class="rightAlign">{{$value['cost_sgst_percent']}}</td>
          <td class="rightAlign">{{$value['total_sgst_amount_with_qty']}}</td>
          <td class="rightAlign">{{$value['cost_igst_percent']}}</td>
          <td class="rightAlign">{{$value['total_igst_amount_with_qty']}}</td>
         <?php } ?>

          <td class="rightAlign">{{$value->total_cost}}</td>
          <td class="rightAlign">{{$value->profit_amount}}</td>
          <td class="rightAlign">{{$value->sell_price}}</td>
          <td class="rightAlign">{{$value->selling_gst_amount}}</td>
          <td class="rightAlign">{{$value->offer_price}}</td>
          <td class="rightAlign">{{$value->product_mrp}}</td>
     </tr>
    <?php } else { ?>

    <tr id="" class="<?php echo $tblclass ?>">
        <td class="leftAlign"><?php echo $barcode ?></td>
        <td class="leftAlign"><?php echo $value['inward_stock']['invoice_no']?></td>
        <td class="leftAlign">{{$value->batch_no}}</td>
        <td class="leftAlign"><?php echo $value['inward_stock']['inward_date']?></td>
        <td class="leftAlign">{{$value->product_detail->product_name}}</td>
        <td class="rightAlign">{{$value->cost_rate}}</td>

        <?php if($nav_type[0]['tax_type'] == 1) { ?>
        <td class="rightAlign">{{$value->cost_igst_amount}}</td>
        <?php } else { ?>
        <td class="rightAlign">{{$value->cost_igst_amount}}</td>
        <td class="rightAlign">{{$value->cost_cgst_amount}}</td>
        <td class="rightAlign">{{$value->cost_sgst_amount}}</td>
        <?php } ?>


        <td class="rightAlign">{{$value->cost_price}}</td>
        <td class="rightAlign">{{$value->product_qty}}</td>
        <td class="rightAlign">{{$value->free_qty}}</td>
        <td class="rightAlign">{{$value->pending_return_qty}}</td>
        <td class="rightAlign">{{$value->total_cost_rate_with_qty}}</td>

        <?php if($nav_type[0]['tax_type'] == 1) { ?>
        <td class="rightAlign">{{$value['cost_igst_percent']}}</td>
        <td class="rightAlign">{{$value['total_igst_amount_with_qty']}}</td>
        <?php } else { ?>
        <td class="rightAlign">{{$value['cost_cgst_percent']}}</td>
        <td class="rightAlign">{{$value['total_cgst_amount_with_qty']}}</td>
        <td class="rightAlign">{{$value['cost_sgst_percent']}}</td>
        <td class="rightAlign">{{$value['total_sgst_amount_with_qty']}}</td>
        <td class="rightAlign">{{$value['cost_igst_percent']}}</td>
        <td class="rightAlign">{{$value['total_igst_amount_with_qty']}}</td>
        <?php } ?>
        <td class="rightAlign">{{$value->total_cost}}</td>
        <td class="rightAlign">{{$value->profit_amount}}</td>
        <td class="rightAlign">{{$value->sell_price}}</td>
        <td class="rightAlign">{{$value->selling_gst_amount}}</td>
        <td class="rightAlign">{{$value->offer_price}}</td>
        <td class="rightAlign">{{$value->product_mrp}}</td>

    <?php } ?>
@endforeach
<script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>

<script type="text/javascript">
    $(".costrate").html('<?php echo $total_cost_rate?>');
    $(".totaligst").html('<?php if(isset($igst_qty) && $igst_qty != '') echo round($igst_qty,4); else echo 0?>');
    $(".totalothertax").html('<?php if(isset($igst_qty) && $igst_qty != '') echo round($igst_qty,4); else echo 0?>');
    $(".totalcgst").html('<?php if(isset($cgst_qty) &&  $cgst_qty != '') echo $cgst_qty ;else echo 0?>');
    $(".totalsgst").html('<?php if(isset($sgst_qty) && $sgst_qty != '')echo $sgst_qty;else echo 0?>');
    $(".grandtotal").html('<?php echo round($total_total_cost,4)?>');

</script>

<tr>
        <?php if($inward_type == 2) { ?>
    <td colspan="24" class="paginateui">
        {!! $product_wise_report->links() !!}
    </td>
       <?php } else {?>
            <td colspan="26" class="paginateui">
                {!! $product_wise_report->links() !!}
            </td>
            <?php } ?>
</tr>

</tbody>
</table>
<input type="hidden" name="hidden_page" id="hidden_page" value="1"/>
<input type="hidden" name="hidden_column_name" id="hidden_column_name" value="inward_product_detail_id"/>
<input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="DESC"/>
<input type="hidden" name="fetch_data_url" id="fetch_data_url" value="product_wise_record"/>


<script src="{{URL::to('/')}}/public/dist/js/tablesaw-data.js"></script>

<script type="text/javascript">
    $(".PagecountResult").html(' ({{$product_wise_report->total()}})');

    <?php if($inward_type == 2) { ?>



    //$(".garment_case_hide").hide();
    <?php } else { ?>
    $(".garment_case_hide").show();
    <?php } ?>


</script>

