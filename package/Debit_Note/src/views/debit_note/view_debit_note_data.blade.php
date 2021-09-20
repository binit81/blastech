<?php
/**
 *
 * Created by PhpStorm.
 * User: Hemaxi
 * Date: 07/05/19
 * Time: 11:18 PM
 */
?>
 <table id="view_inward_table_data" class="table tablesaw table-bordered table-hover  mb-0" data-tablesaw-mode="swipe"  data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-minimap data-tablesaw-mode-switch>
        <div class="row">
            <div class="col-md-9">
                <a id="deletedebitnote" name="deletedebitnote">
                    <i class="fa fa-trash" style="font-size: 20px;color: red;margin-left: 20px;"></i></a>
            </div>
        </div>

        <thead>
        <tr class="header">
            <th scope="col" class="tablesaw-swipe-cellpersist" data-tablesaw-priority="persist" style="width:1%">
                <div class="custom-control custom-checkbox checkbox-primary">
                    <input type="checkbox" class="custom-control-input" id="checkalldebit" name="checkalldebit">
                    <label class="custom-control-label" for="checkalldebit"></label>
                </div>
            </th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist"></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">Debit No.<span id="debit_no_icon"></span></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Debit Date<span id="debit_date_icon"></span></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3" >Invoice No</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Supplier Name<span id="supplier_gst_id_icon"></span></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">Qty Returned<span id="total_qty"></span></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">Debit Amt<span id="total_qty"></span></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="7">Used Amt<span id="total_qty"></span></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">Balance Amt<span id="total_qty"></span></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="9">Note</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="10">Print</th>
        </tr>
        </thead>
        <tbody>

@foreach($debit_note AS $key=>$value)
    <?php if($key % 2 == 0)
    {
        $tblclass = 'even';
    }
    else
    {
        $tblclass = 'odd';
    }
    ?>
    <tr id="{{$value->debit_note_id}}" class="<?php echo $tblclass ?>">
        <td>
            <input type="checkbox" style="width:30px;margin-left:-17px;height: 17px;" name="delete_debit[]" value="{{$value->debit_note_id }}" id="delete_debit{{$value->debit_note_id }}" data-id="{{$value->inward_stock_id}}" data-attr="{{$value->supplier_gst_id}}"></td>
        <td class="leftAlign">
            <a ondblclick="view_debit_detail('{{encrypt($value->debit_note_id)}}');">
            <i class="fa fa-eye" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;font-weight:bold;"  ></i>
            </a>

            <a id="edit_debit_note" title="Edit" onclick="return edit_debitnote('{{encrypt($value->debit_note_id)}}');">
                <i class="fa fa-edit"></i>
            </a>
        </td>
        <td class="leftAlign">{{$value->debit_no}}</td>
        <td class="leftAlign">{{$value->debit_date}}</td>
        <td class="leftAlign">{{$value->inward_stock->invoice_no}}</td>
        <td class="leftAlign">{{$value->supplier_gstdetail->supplier_company_info->supplier_first_name}} {{$value->supplier_gstdetail->supplier_company_info->supplier_last_name}}</td>
        <td class="rightAlign">{{$value->total_qty}}</td>
        <td class="rightAlign">{{$value->total_cost_price}}</td>
        <td class="rightAlign">{{$value->used_amount}}</td>
        <td class="rightAlign">{{($value->total_cost_price-$value->used_amount)}}</td>
        <td>{{$value->note}}</td>
        <td><a href="{{URL::to('print_debit_note')}}?id={{encrypt($value->debit_note_id)}}" style="text-decoration:none !important;" target="_blank" title="Print"><i class="fa fa-print" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;"></i></a></td>
    </tr>
@endforeach

<tr>
    <td colspan="12" class="paginateui">
        {!! $debit_note->links() !!}
    </td>
</tr>
 </tbody>
 </table>


<input type="hidden" name="hidden_page" id="hidden_page" value="1"/>
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="debit_note_id"/>
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="desc"/>
    <input type="hidden" name="fetch_data_url" id="fetch_data_url" value="debit_note_fetch_data"/>
<script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>


<script src="{{URL::to('/')}}/public/dist/js/tablesaw-data.js"></script>
<script type="text/javascript">
    $(".PagecountResult").html(' ({{$debit_note->total()}})');
</script>
