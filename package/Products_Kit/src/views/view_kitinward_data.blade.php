<table id="view_kitinward_table_data" class="table table-bordered table-hover table-striped mb-0" data-tablesaw-mode="swipe" data-tablesaw-sortable data-tablesaw-mode-switch>


    <thead>
    <tr class="header">
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Action</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Kit Name</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Inward No</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Inward date</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">Total Cost Rate</th>
        <?php if($nav_type[0]['tax_type'] == 1){ ?>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">Cost <?php echo $nav_type[0]['tax_title'].' '.$nav_type[0]['currency_tilte']?></th>
        <?php } else { ?>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="7">Cost CGST &#8377;</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">Cost SGST &#8377;</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="9">Cost IGST &#8377;</th>
        <?php } ?>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="10">Total Qty</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="11">Grand Total &#8377;</th>
    </tr>
    </thead>
    <tbody>

@foreach($inward_stock AS $inward_key=>$inward_value)
    <?php if($inward_key % 2 == 0)
    {
        $tblclass = 'even';
    }
    else
    {
        $tblclass = 'odd';
    }

    $delete_option = '';
    if($inward_value->total_qty != $inward_value->totalpendingqty)
    {
        $delete_option = 'style=display:none';
    }
    ?>
    <tr id="{{$inward_value->inward_stock_id}}" class="<?php echo $tblclass ?>">
        <td>
        <a id="edit_bill" onclick="return edit_kitinward('{{encrypt($inward_value->inward_stock_id)}}');" title="Edit Kit Inward"><i class="fa fa-edit" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;"></i></a>
        </td>
        <td class="leftAlign">{{$inward_value['kitinward_product_detail']['product']['product_name']}}</td>
        <td class="leftAlign">{{$inward_value->invoice_no}}</td>
        <td class="leftAlign">{{$inward_value->inward_date}}</td>
        <td class="rightAlign">{{number_format($inward_value->cost_rate,$nav_type[0]['decimal_points'])}}</td>

        <?php if($nav_type[0]['tax_type']==1) {?>
        <td class="rightAlign">{{number_format($inward_value->total_cost_igst_amount,$nav_type[0]['decimal_points'])}}</td>
        <?php } else { ?>
        <td class="rightAlign">{{number_format($inward_value->total_cost_cgst_amount,$nav_type[0]['decimal_points'])}}</td>
        <td class="rightAlign">{{number_format($inward_value->total_cost_sgst_amount,$nav_type[0]['decimal_points'])}}</td>
        <td class="rightAlign">{{number_format($inward_value->total_cost_igst_amount,$nav_type[0]['decimal_points'])}}</td>
        <?php } ?>

        <td class="rightAlign">{{$inward_value->total_qty}}</td>
        <td class="rightAlign">{{number_format($inward_value->total_grand_amount,$nav_type[0]['decimal_points'])}}</td>
    </tr>
  
@endforeach

<tr>
    <td colspan="13" class="paginateui">
        {!! $inward_stock->links() !!}
    </td>
</tr>
    </tbody>
</table>

<input type="hidden" name="hidden_page" id="hidden_page" value="1"/>
<input type="hidden" name="hidden_column_name" id="hidden_column_name" value="inward_stock_id"/>
<input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="desc"/>
<input type="hidden" name="fetch_data_url" id="fetch_data_url" value="inward_fetch_data"/>

<script>
    $(".PagecountResult").html(' ({{$inward_stock->total()}})');
</script>