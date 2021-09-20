<?php
$tax_label = 'GST';


if($nav_type[0]['tax_type'] == 1)
{
    $tax_label = $nav_type[0]['tax_title'];
}
?>
<table class="table tablesaw table-bordered table-hover  mb-0" data-tablesaw-mode="swipe" data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-minimap data-tablesaw-mode-switch>
    <thead>
    <tr class="header">
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Sr. No.</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">Date</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Damage Type</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Damage No.</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Total Damage Qty</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">Total Cost Rate</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">Total <?php echo $tax_label?> Amount</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">Total Cost Price</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">Action</th>
    </tr>
    </thead>
    <tbody id="">
@foreach($result AS $resultkey=>$result_value)
<tr>
    <td>{{$resultkey+1}}</td>
    <td>{{$result_value->created_at->format('d-m-Y')}}</td>
    <td>{{$result_value['damage_types']['damage_type']}}</td>
    <td>{{$result_value->damage_no}}</td>
    <td class="rightAlign">{{$result_value->damage_total_qty}}<input type="hidden" value="1" class="qtyall"></td>
    <td class="rightAlign">{{$result_value->damage_total_cost_rate}}</td>
    <td class="rightAlign">{{$result_value->damage_total_gst}}</td>
    <td class="rightAlign">{{$result_value->damage_total_cost_price}}</td>
    <td class="rightAlign">
    <button class="btn btn-icon btn-icon-only btn-secondary btn-icon-style-4" onclick="editDamage('{{encrypt($result_value->damage_product_id)}}')"><i class="fa fa-pencil"></i></button>
    <button class="btn btn-icon btn-icon-only btn-secondary btn-icon-style-4" onclick="delDamage('{{encrypt($result_value->damage_product_id)}}')"><i class="fa fa-trash"></i></button>
    </td>
</tr>
@endforeach
    <tr>
        <td colspan="9" class="paginateui">
            {!! $result->links() !!}
        </td>
    </tr>
    </tbody>
</table>

<input type="hidden" name="hidden_page" id="hidden_page" value="1" />
<input type="hidden" name="hidden_column_name" id="hidden_column_name" value="damage_product_id" />
<input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
<input type="hidden" name="fetch_data_url" id="fetch_data_url" value="searchDamageProductReportGroup" />

<script type="text/javascript">
    $(".PagecountResult").html(' ({{$result->total()}})');
</script>
