<?php
/**
 *
 * Created by PhpStorm.
 * User: Hemaxi
 * Date: 28/3/19
 * Time: 5:47 PM
 */
$tax_name = 'GSTIN';
 if($nav_type[0]['tax_type'] == 1)
 {
     $tax_name = $nav_type[0]['tax_title'];
     }
?>
<table  class="table tablesaw table-bordered table-hover  mb-0" data-tablesaw-mode="swipe"  data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-minimap data-tablesaw-mode-switch>
<thead>
    <tr class="header">
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">Invoice No.<span id="invoice_no_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Po No.<span id="po_no_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Inward date<span id="inward_date_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Invoice date<span id="invoice_date_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">Supplier Name</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">Supplier <?php echo $tax_name?></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="7">Total Cost Rate<span id="cost_rate_icon"></span></th>
        <?php if($nav_type[0]['tax_type'] == 1){ ?>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="10">Total Cost <?php echo $nav_type[0]['tax_title'].' '.$nav_type[0]['currency_title']?><span id="total_cost_igst_amount_icon"></span></th>
        <?php } else { ?>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">Total Cost CGST &#8377;<span id="total_cost_cgst_amount_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="9">Total Cost SGST &#8377;<span id="total_cost_sgst_amount_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="10">Total Cost IGST &#8377;<span id="total_cost_igst_amount_icon"></span></th>
        <?php } ?>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="11">Total Qty<span id="total_qty_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="12">Total Cost &#8377;<span id="total_grand_amount_icon"></span></th>
    </tr>
    </thead>
    <tbody>

@foreach($supplier_wise_report AS $report_key=>$report_value)
    <?php if($report_key % 2 == 0)
    {
        $tblclass = 'even';
    }
    else
    {
        $tblclass = 'odd';
    }

    if($report_value['inward_product_detail'] != ''){
        $cost_rate = 0;
        foreach ($report_value['inward_product_detail'] AS $key=>$value)
            {
               // print_r($value['cost_rate']);
                $cost_rate += $value['cost_rate'] * ($value['product_qty']+ $value['free_qty']);
            }
    }
    ?>
    <tr id="{{$report_value->inward_stock_id}}" class="<?php echo $tblclass ?>">
        <td class="leftAlign" ondblclick="viewinward('{{encrypt($report_value->inward_stock_id)}}');" >{{$report_value->invoice_no}}</td>
        <td class="leftAlign">{{$report_value->po_no}}</td>
        <td class="leftAlign">{{$report_value->inward_date}}</td>
        <td class="leftAlign">{{$report_value->invoice_date}}</td>
        <td class="leftAlign">{{$report_value->supplier_gstdetail->supplier_company_info->supplier_first_name }}</td>
        <td class="leftAlign">{{$report_value->supplier_gstdetail->supplier_gstin }}</td>
        <td class="rightAlign"><?php echo $cost_rate?></td>

        <?php if($nav_type[0]['tax_type'] == 1) { ?>
        <td class="rightAlign">{{$report_value->total_cost_igst_amount}}</td>
        <?php } else { ?>
        <td class="rightAlign">{{$report_value->total_cost_cgst_amount}}</td>
        <td class="rightAlign">{{$report_value->total_cost_sgst_amount}}</td>
        <td class="rightAlign">{{$report_value->total_cost_igst_amount}}</td>
        <?php } ?>

        <td class="rightAlign">{{$report_value->total_qty}}</td>
        <td class="rightAlign">{{$report_value->total_grand_amount}}</td>
    </tr>
@endforeach

<tr>
    <td colspan="12" class="paginateui">
        {!! $supplier_wise_report->links() !!}
    </td>
</tr>
    </tbody>
</table>
<input type="hidden" name="hidden_page" id="hidden_page" value="1" />
<input type="hidden" name="hidden_column_name" id="hidden_column_name" value="inward_stock_id" />
<input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="desc" />
<input type="hidden" name="fetch_data_url" id="fetch_data_url" value="supplier_wise_record" />

<script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>

<script src="{{URL::to('/')}}/public/dist/js/tablesaw-data.js"></script>
<script type="text/javascript">
    $(".PagecountResult").html(' ({{$supplier_wise_report->total()}})');
</script>
