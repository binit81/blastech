<table class="table tablesaw table-bordered table-hover  mb-0" data-tablesaw-mode="swipe" data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-minimap data-tablesaw-mode-switch>
<thead>
    <tr class="header">
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Sr. No.</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Date</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Barcode</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Product Name</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">Batch no</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">invoice No.</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Cost Rate</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Qty</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">Total Cost Rate</th>
        <?php if($nav_type[0]['tax_type'] == 1) { ?>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6"><?php echo $nav_type[0]['tax_title'] ?> %</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="7"><?php echo $nav_type[0]['tax_title'].' '.$nav_type[0]['currency_title']?></th>
        <?php } else {?>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">CGST % & Amt.</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="7">SGST % & Amt.</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">IGST % & Amt.</th>
        <?php } ?>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="9">Total Cost Price</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="10">MRP</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="11">Notes</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="12">Status</th>
    </tr>
</thead>
<tbody>
<?php
if(sizeof($result)!=0)
{
?>
@foreach($result AS $resultkey=>$result_value)
            <?php
        $barcode = '';
        if($result_value['product']['supplier_barcode'] != '')
        {
            $barcode =  $result_value['product']['supplier_barcode'];
        }
        else
        {
            $barcode = $result_value['product']['product_system_barcode'];
        }
        ?>
        <tr>
            <td>{{$resultkey+1}}</td>
            <td>{{$result_value->created_at->format('d-m-Y')}}</td>
            <td>{{$barcode}}</td>
            <td>{{$result_value->product->product_name}}</td>
            <td>{{$result_value->inward_product_detail->batch_no}}</td>
            <td>{{$result_value->inward_product_detail->inward_stock->invoice_no}}</td>
            <td class="rightAlign">{{$result_value->product_cost_rate}}</td>
            <td class="rightAlign">{{$result_value->product_damage_qty}}<input type="hidden" value="1" class="qtyall"></td>
            <td class="rightAlign">{{$result_value->product_total_cost_rate}}</td>

            <?php if($nav_type[0]['tax_type'] == 1) { ?>
            <td class="rightAlign"><?php echo $result_value->product_cost_igst_percent?>% <?php echo ($result_value->product_cost_igst_amount_with_qty)?></td>
            <?php } else {?>
            <td class="rightAlign"><?php echo $result_value->product_cost_cgst_percent?>% <?php echo ($result_value->product_cost_cgst_amount_with_qty)?></td>
            <td class="rightAlign"><?php echo $result_value->product_cost_sgst_percent?>% <?php echo ($result_value->product_cost_sgst_amount_with_qty)?></td>
            <td class="rightAlign"><?php echo $result_value->product_cost_igst_percent?>% <?php echo ($result_value->product_cost_igst_amount_with_qty)?></td>
            <?php } ?>

            <td class="rightAlign">{{$result_value->product_total_cost_price}}</td>
            <td class="rightAlign">{{$result_value->product_mrp}}</td>
            <td>{{$result_value->product_notes}}</td>
            <td>{{$result_value->damage_product->damage_types->damage_type}}</td>
        </tr>
        @endforeach

<tr>
    <td colspan="21" class="paginateui">
        {!! $result->links() !!}
    </td>
</tr>
<?php
}
?>
</tbody>
</table>

<input type="hidden" name="hidden_page" id="hidden_page" value="1" />
<input type="hidden" name="hidden_column_name" id="hidden_column_name" value="damage_product_detail_id" />
<input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
<input type="hidden" name="fetch_data_url" id="fetch_data_url" value="searchDamageProductReport" />
<script src="{{URL::to('/')}}/public/dist/js/tablesaw-data.js"></script>
<script type="text/javascript">
    $(".PagecountResult").html(' ({{$result->total()}})');
</script>