<?php

$Total_cost_rate = (($total_pending_qty_total_cost + $opening_qty_total_cost) )/($total_instock);
$Total_cost_rate = '';

$ttoal_cost_rate = $Total_cost_rate * $total_instock;
$ttoal_cost_rate = '';
$total_instock = $total_instock + $total_opening;

?>

<table  id="view_stock_recorddata" class="table tablesaw table-bordered table-hover table-striped mb-0" data-tablesaw-mode="swipe" data-tablesaw-sortable data-tablesaw-minimap data-tablesaw-mode-switch>
    <thead>
    <tr class="header">
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Barcode</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Product Name</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Batch No.</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Cost Rate</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">MRP</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">Opening</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="7">Inward[+]</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">Sold[-]</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="9">Return</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="10">Restock[+]</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="11">Damage[-]</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="12">Used[-]</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="13">Supp. Return[-]</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="14">InStock</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="15">Total Cost Rate</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="16">Total MRP</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="17">Expiry Date</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="18">Expiry Days</th>
    </tr>
    </thead>
    <tbody>
    @foreach($inward_product AS $key => $value)

        <?php
        if ($key % 2 == 0)
        {
            $tblclass = 'even';
        }
        else
        {
            $tblclass = 'odd';
        }

        $barcode = '';

        /*echo "<pre>";
        print_r($value['total_qty']);
        echo "</pre>";
       exit;*/

        if($value->product->supplier_barcode != '')
        {
            $barcode =  $value->product->supplier_barcode;
        }
        else
        {
            $barcode = $value->product->product_system_barcode;
        }

        //total inward qty
        $inward_qty = $value->total_qty;
        //$total_inward_qty += $inward_qty;


        //product opening qty
        $value->opening = isset($value->opening) && $value->opening != '' ? $value->opening : 0;
        //$total_opening += $value->opening;

        //product wise sold qty
        $value->sold = isset($value->sold) && $value->sold != '' ?$value->sold : 0;
        //$total_sold_qty += $value->sold;

        //product wise return qty
        $value->return = isset($value->return) && $value->return != '' ?$value->return : 0;

        //product wise restock qty
        $value->restock = isset($value->restock) && $value->restock != '' ?$value->restock : 0;
        //$total_restock_qty += $value->restock;

        //product wise damage qty
        $value->damage = isset($value->damage) && $value->damage != '' ?$value->damage : 0;
        //$total_damage_qty += $value->damage;

        //product wise damage_used qty
        $value->damage_used = isset($value->damage_used) && $value->damage_used != '' ?$value->damage_used : 0;
        //$total_damage_used_qty += $value->damage_used;

        //product wise return_to_supplier qty
        $value->return_to_supplier = isset($value->return_to_supplier) && $value->return_to_supplier != '' ?$value->return_to_supplier : 0;
        //$total_return_to_supplier_qty += $value->return_to_supplier;

        //product wise instock qty
        $value->product_instock = isset($value->product_instock) && $value->product_instock != '' ?$value->product_instock : 0;
        $product_instock = $value->product_instock + $value->opening;
        //$total_instock += $value->product_instock + $value->opening;



        $total_cost_rate = (($value->pending_qty_total_cost + $value->opening_qty_total_cost) )/($product_instock);

        $costrate = $total_cost_rate * $product_instock;
        $mrptotal = $value->product_mrp * $product_instock;

        $diff = '';
        if($value->expiry_date != null)
        {
            $now = strtotime(date('d-m-Y')); //CURRENT DATE
            $expiry_date = strtotime($value->expiry_date);
            $datediff = $expiry_date-$now;
            $diff =  round($datediff / (60 * 60 * 24));
        }

        ?>

        <tr>
            <td>{{$barcode}}</td>
            <td>{{$value->product->product_name}}</td>
            <td>{{$value->batch_no}}</td>
            <td>{{$total_cost_rate}}</td>
            <td>{{$value->product_mrp}}</td>
            <td class="rightAlign bold">{{$value->opening}}</td>
            <td class="rightAlign">{{$inward_qty}}</td>
            <td class="rightAlign">{{$value->sold}}</td>
            <td class="rightAlign">{{$value->return}}</td>
            <td class="rightAlign">{{$value->restock}}</td>
            <td class="rightAlign">{{$value->damage}}</td>
            <td class="rightAlign">{{$value->damage_used}}</td>
            <td class="rightAlign">{{$value->return_to_supplier}}</td>
            <td class="rightAlign bold">{{$product_instock}}</td>
            <td class="rightAlign bold">{{$costrate}}</td>
            <td class="rightAlign bold">{{$mrptotal}}</td>
            <td class="rightAlign bold">{{$value->expiry_date}}</td>
            <td class="rightAlign bold">{{$diff}}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="18" align="center" class="paginateui">
            {!! $inward_product->links() !!}
        </td>
    </tr>

    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function(e){
        $(document).ready(function(e){
            $('.totalproducts').html('{{$inward_product->total()}}');
            $('.opening').html({{$total_opening}});
            $('.totalinwardqty').html({{$total_inward_qty}});
            $('.totalsoldqty').html({{$total_sold_qty}});
            $('.totalrestockqty').html({{$total_restock_qty}});
            $('.totaldamageqty').html({{$total_damage_qty}});
            $('.totalusedqty').html({{$total_damage_used_qty}});
            $('.totalsupprqty').html({{$total_return_to_supplier_qty}});
            $('.totalinstock').html({{$total_instock}});
            $('.totalinstock_cost').html({{$ttoal_cost_rate}});
        });
    });
</script>
<script src="{{URL::to('/')}}/public/dist/js/tablesaw-data.js"></script>
<input type="hidden" name="hidden_page" id="hidden_page" value="1" />
<input type="hidden" name="hidden_column_name" id="hidden_column_name" value="inward_product_detail_id" />
<input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="DESC" />
<input type="hidden" name="fetch_data_url" id="fetch_data_url" value="batch_no_wise_record" />
