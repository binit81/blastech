<?php
/**
 *
 * Created by PhpStorm.
 * User: Hemaxi
 * Date: 28/3/19
 * Time: 5:47 PM
 */

?>

<table id="view_inward_table_data" class="table tablesaw table-bordered table-hover table-striped mb-0" data-tablesaw-mode="swipe" data-tablesaw-sortable data-tablesaw-minimap data-tablesaw-mode-switch>

    <thead>
    <tr class="header">
        <th style="width: 3%;" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Action</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">Invoice No.<span id="invoice_no_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Po No.<span id="po_no_icon"></span></th>
        <th scope="col"  data-tablesaw-sortable-col data-tablesaw-priority="3">Inward date <span id="inward_date_icon" class="p" ></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Supplier Name</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">Total Cost Rate<span id="cost_rate_icon"></span></th>
        <?php if($nav_type[0]['tax_type'] == 1){ ?>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">Total Cost <?php echo $nav_type[0]['tax_title'].' '.$nav_type[0]['currency_tilte']?><span id="total_cost_igst_amount_icon"></span></th>
        <?php } else { ?>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">Total Cost CGST &#8377;<span id="total_cost_cgst_amount_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="7">Total Cost SGST &#8377;<span id="total_cost_sgst_amount_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">Total Cost IGST &#8377;<span id="total_cost_igst_amount_icon"></span></th>
        <?php } ?>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="9">Total Qty<span id="total_qty_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="10">Grand Total &#8377;<span id="total_grand_amount_icon"></span></th>
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

    ?>
    <tr id="{{$inward_value->inward_stock_id}}" class="<?php echo $tblclass ?>">
        <td class="leftAlign" >
            <a id="popupforview" ondblclick="return false;" onclick="viewinward('{{encrypt($inward_value->inward_stock_id)}}');"><i class="fa fa-eye" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;font-weight:bold;" title="View Inward Stock" ></i></a>

            <?php if($nav_type[0]['inward_type'] == $inward_value['inward_type']) { ?>
            <a id="edit_inword_stock" onclick="return edit_inwardstock('{{encrypt($inward_value->inward_stock_id)}}','{{$inward_value->inward_type}}');">
            <i class="fa fa-edit" title="Edit Inward Stock"></i></a>

            <?php if($inward_value->total_qty == $inward_value->totalpendingqty) {?>
            <a id="delete_inword_stock" onclick="return delete_inwardstock('{{encrypt($inward_value->inward_stock_id)}}');">
            <i class="fa fa-trash" title="Delete Inward Stock"></i></a>
            <?php } else { ?>
            <i title="This inward some qty is used.so this inward can not be delete!" class="fa fa-info"></i>
            <?php  } ?>

            <?php } else { ?>
                <i class="fa fa-info" title="Change your inward type for edit or delete this inward!"></i>
            <?php } ?>
        </td>
        <td class="leftAlign">{{$inward_value->invoice_no}}</td>
        <td class="leftAlign">{{$inward_value->po_no}}</td>
        <td class="leftAlign">{{$inward_value->inward_date}}</td>
        <td class="leftAlign">{{$inward_value->supplier_gstdetail->supplier_company_info->supplier_first_name}} {{$inward_value->supplier_gstdetail->supplier_company_info->supplier_last_name}}</td>
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

<script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>
<script src="{{URL::to('/')}}/public/dist/js/tablesaw-data.js"></script>
<script>
    $(".PagecountResult").html(' ({{$inward_stock->total()}})');
</script>