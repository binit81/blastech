<?php

/**
 *
 * Created by PhpStorm.
 * User: Hemaxi
 * Date: 24/5/19
 * Time: 5:47 PM
 */

?>
<table id="batchnorecordtable" class="table table-bordered table-hover  mb-0" data-tablesaw-mode="swipe"  data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-minimap data-tablesaw-mode-switch>
    <thead>
    <tr class="header">
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">Debit Receipt No.</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Product Name</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Supplier Name</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Return Qty</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">Debit Amt</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="9">Remarks</th>
    </tr>
    </thead>
    <tbody>
    @foreach($debit_product_detail AS $report_key=>$report_value)
        <?php if($report_key % 2 == 0)
        {
            $tblclass = 'even';
        }
        else
        {
            $tblclass = 'odd';
        }
        ?>
        <tr id="{{$report_value->debit_product_detail_id}}" class="<?php echo $tblclass ?>">
            <td>{{$report_value->debit_note->debit_no}}</td>
            <td>{{$report_value->product->product_name}}</td>
            <td>{{$report_value->debit_note->supplier_gstdetail->supplier_company_info->supplier_first_name}}</td>
            <td>{{$report_value->return_qty}}</td>
            <td>{{$report_value->total_cost_price}}</td>
            <td>{{$report_value->remarks}}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="14" class="paginateui">
            {!! $debit_product_detail->links() !!}
        </td>
    </tr>
    </tbody>
</table>
<input type="hidden" name="hidden_page" id="hidden_page" value="1" />
<input type="hidden" name="hidden_column_name" id="hidden_column_name" value="debit_product_detail_id" />
<input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="DESC" />
<input type="hidden" name="fetch_data_url" id="fetch_data_url" value="debit_no_wise_search_record" />

<script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>
<script src="{{URL::to('/')}}/public/dist/js/tablesaw-data.js"></script>
<script type="text/javascript">
    $(".PagecountResult").html('({{$debit_product_detail->total()}})');
</script>

