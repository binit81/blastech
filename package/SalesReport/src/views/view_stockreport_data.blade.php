<table  id="view_stock_recorddata" class="table tablesaw table-bordered table-hover table-striped mb-0" data-tablesaw-mode="swipe" data-tablesaw-sortable data-tablesaw-minimap data-tablesaw-mode-switch>

<thead>
<tr class="header">                                                
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Barcode</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Product Name</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">MRP</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Opening</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">Inward(+)</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">Sold(-)</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="7">Return</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">Restock(+)</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="9">Damage(-)</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="10">Used(-)</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="11">Supp. Return(-)</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="12">InStock</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="13">Total MRP</th>
    

</tr>
</thead>
<tbody>


@foreach($product AS $productkey=>$product_value)
<?php 
if ($productkey % 2 == 0) {
    $tblclass = 'even';
} else {
    $tblclass = 'odd';
}
    $sr   = $productkey + 1;

    if(isset($product_value->category_id) && $product_value->category_id!=null || $product_value->category_id!='') 
    {
        $category_name   =   $product_value->category->category_name;   
    }
    else
    {
        $category_name  = '- - - -';
    }
    if(isset($product_value->brand_id) && $product_value->brand_id!=null || $product_value->brand_id!='') 
    {
        $brand_name   =   $product_value->brand->brand_type;   
    }
    else
    {
        $brand_name  = '- - - -';
    }

    if(isset($product_value->sku_code) && $product_value->sku_code!=null || $product_value->sku_code!='') 
    {
        $sku_code   =   $product_value->sku_code;   
    }
    else
    {
        $sku_code  = '- - - -';
    }

    
    $opening   =   $product_value->totalinwardqty - $product_value->totalsoldqty + $product_value['totalrestock'] - $product_value['totalused'] - $product_value['totalddamage'] - $product_value['totalsuppreturn'];
    $stock     =   $opening +$product_value->currentinward -$product_value->currentsold + $product_value['currentrestock']-$product_value['currentused'] - $product_value['currentddamage']  - $product_value['currentsuppreturn'];
    $todayinward  = $product_value->currentinward != '' ?$product_value->currentinward : 0;
    $todaysold    = $product_value->currentsold != '' ?$product_value->currentsold : 0;

    $totaldamage  =  $product_value['currentdamage'] +  $product_value['currentddamage'];

    if($product_value->averagemrp !='')
    {
        $averagemrp   = $product_value->averagemrp;
    }
    else
    {
        $averagemrp    =  $product_value->offer_price != '' ?$product_value->offer_price : 0;
    }
    
    $totalmrpvalue  =  $averagemrp * $stock;

    if($product_value->supplier_barcode!='' && $product_value->supplier_barcode!=NULL)
    {
        $barcode  =   $product_value->supplier_barcode;
       
    }
    else
    {
         $barcode  =   $product_value->product_system_barcode;
    }

 ?>
 <tr>
    <td>{{$barcode}}</td>
    <td>{{$product_value->product_name}}</td>
    <td>{{number_format($averagemrp,$nav_type[0]['decimal_points'])}}</td>
    <td class="rightAlign bold">{{$opening}}</td>
    <td class="rightAlign">{{$todayinward}}</td>
    <td class="rightAlign">{{$todaysold}}</td>
    <td class="rightAlign">{{$product_value['currentreturn']!=''?$product_value['currentreturn']:0}}</td>
    <td class="rightAlign">{{$product_value['currentrestock']!=''?$product_value['currentrestock']:0}}</td>
    <td class="rightAlign">{{$totaldamage}}</td>
    <td class="rightAlign">{{$product_value['currentused']!=''?$product_value['currentused']:0}}</td>
    <td class="rightAlign">{{$product_value['currentsuppreturn']!=''?$product_value['currentsuppreturn']:0}}</td>
    <td class="rightAlign bold">{{$stock}}</td>
    <td class="rightAlign bold">{{number_format($totalmrpvalue,$nav_type[0]['decimal_points'])}}</td>
</tr>
@endforeach
<tr>
 <td colspan="16" align="center">
       {!! $product->links() !!}
    </td>
</tr>

</tbody>
</table>


<script type="text/javascript">
$(document).ready(function(e){

 
    $('.totalproducts').html({{$count}});
    $('.opening').html({{$totopening}});
    $('.totalinwardqty').html({{$currinward}});
    $('.totalsoldqty').html({{$currsold}});
    $('.totalinstock').html({{$totstock}});
    $('.totalrestockqty').html({{$currrestock}});
    $('.totaldamageqty').html({{$ttotaldamage}});
    $('.totalusedqty').html({{$currusedqty}});
    $('.totalsupprqty').html({{$currsupprqty}});
   
});
</script>

<script src="{{URL::to('/')}}/public/dist/js/tablesaw-data.js"></script>
<input type="hidden" name="hidden_page" id="hidden_page" value="1" />
<input type="hidden" name="hidden_column_name" id="hidden_column_name" value="product_id" />
<input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="DESC" />
<input type="hidden" name="fetch_data_url" id="fetch_data_url" value="datewise_stock_detail" />
