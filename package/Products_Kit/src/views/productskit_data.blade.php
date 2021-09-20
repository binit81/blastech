    <table id="productrecordtable" class="table tablesaw table-bordered table-hover mb-0" data-tablesaw-mode="swipe" data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-minimap data-tablesaw-mode-switch>

<thead>
<tr class="header">
    <th scope="col" class="tablesaw-swipe-cellpersist"  data-tablesaw-priority="persist">
        
    </th>
     <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Kit Barcode<span id="supplier_barcode_icon"></span></th>
    <th scope="col" class="leftAlign" data-tablesaw-sortable-col data-tablesaw-priority="persist">Product Name<span id="product_name_icon"></span></th>
   
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">HSN<span id="hsn_sac_code_icon"></span></th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Category</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Sub Category</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Brand</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">Colour</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">Size</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">UQC</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="9">Cost Rate</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="10">Cost GST(%)</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="11">Cost GST(&#8377)</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="12">Cost Price</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="13">Profit(%)</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="14">Profit(&#8377)</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="15">Selling Rate</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="16">Selling GST(%)</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="17">Selling GST(&#8377)</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="18">Product MRP</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="19">Offer Price</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="20">Wholesale Price</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="21">SKU</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="22">Product Code</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="23">HSN</th>
    <?php //if($inward_type == 1) { ?>
    <!-- <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="24">Alert Before Product Expiry(Days)</th> -->
    <?php //} ?>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="25">Low Stock Alert</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="26">Note</th>
    </tr>
</thead>
<tbody id="productsalldata">

@foreach($product AS $productkey=>$product_value)
<?php if($productkey % 2 == 0)
{
    $tblclass = 'even';
}
else
{
    $tblclass = 'odd';
}
?>
<tr id="{{$product_value->product_id}}" class="<?php echo $tblclass ?>">
    <td class="leftAlign"><a id="edit_productskit" onclick="return edit_productskit('{{encrypt($product_value->product_id)}}');" title="Edit"><i class="fa fa-edit" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;"></i></a></td>
    <td class="leftAlign">{{$product_value->supplier_barcode}}</td>
    <td class="leftAlign">{{$product_value->product_name}}</td>
   
    <td class="leftAlign">{{$product_value->hsn_sac_code}}</td>
    <?php if($product_value['category_id'] != NULL) {$categoryname = $product_value->category->category_name;} else{ $categoryname = ''; }?>
    <td class="leftAlign">{{$categoryname}}</td>
    <?php if($product_value['subcategory_id'] != NULL) {$subcategoryname = $product_value->subcategory->subcategory_name;} else{ $subcategoryname = ''; }?>
    <td class="leftAlign">{{$subcategoryname}}</td>
    <?php if($product_value['brand_id'] != NULL) {$brandname = $product_value->brand->brand_type;} else{ $brandname = ''; }?>
    <td class="leftAlign">{{$brandname}}</td>
    <?php if($product_value['colour_id'] != NULL) {$colourname = $product_value->colour->colour_name;} else{ $colourname = ''; }?>
    <td class="leftAlign">{{$colourname}}</td>
    <?php if($product_value['size_id'] != NULL) {$sizename = $product_value->size->size_name;} else{ $sizename = ''; }?>
    <td class="leftAlign">{{$sizename}}</td>
    <?php if($product_value['uqc_id'] != NULL) {$uqc_shortname = $product_value->uqc->uqc_shortname;} else{ $uqc_shortname = ''; }?>
    <td class="leftAlign">{{$uqc_shortname}}</td>
    <td class="rightAlign">{{number_format($product_value->cost_rate)}}</td>
    <td class="rightAlign">{{number_format($product_value->cost_gst_percent)}}</td>
    <td class="rightAlign">{{number_format($product_value->cost_gst_amount)}}</td>
    <td class="rightAlign">{{number_format($product_value->cost_price)}}</td>
    <td class="rightAlign">{{number_format($product_value->profit_percent)}}</td>
    <td class="rightAlign">{{number_format($product_value->profit_amount)}}</td>
    <td class="rightAlign">{{number_format($product_value->selling_price)}}</td>
    <td class="rightAlign">{{number_format($product_value->sell_gst_percent)}}</td>
    <td class="rightAlign">{{number_format($product_value->sell_gst_amount)}}</td>
    <td class="rightAlign">{{number_format($product_value->product_mrp)}}</td>
    <td class="rightAlign">{{number_format($product_value->offer_price)}}</td>
    <td class="rightAlign">{{number_format($product_value->wholesale_price)}}</td>
    <td class="leftAlign">{{$product_value->sku_code}}</td>
    <td class="leftAlign">{{$product_value->product_code}}</td>
    <td class="leftAlign">{{$product_value->hsn_sac_code}}</td>
    <?php //if($inward_type == 1) { ?>
    <!-- <td class="leftAlign">{{$product_value->days_before_product_expiry}}</td> -->
    <?php //} ?>
    <td class="leftAlign">{{$product_value->alert_product_qty}}</td>
    <td class="leftAlign">{{$product_value->note}}</td>

    {{--<td><a data-product_id="{{encrypt($product_value->product_id)}}" id="editproduct"><i class="fa fa-edit"></i></a></td>--}}
</tr>
@endforeach

<tr>
    <td colspan="29" class="paginateui">
        {!! $product->links() !!}
    </td>
</tr>

</tbody>
    </table>
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="product_id" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="desc" />
    <input type="hidden" name="fetch_data_url" id="fetch_data_url" value="productskit_fetch_data" />


    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>
    <script src="{{URL::to('/')}}/public/dist/js/tablesaw-data.js"></script>
<script>
    
    $('.PagecountResult').html(' ({{$product->total()}})');
</script>
