<?php
/**
 *
 * Created by PhpStorm.
 * User: Hemaxi
 * Date: 07/05/19
 * Time: 11:18 PM
 */


?>

    <table id="view_inward_table_data" class="table tablesaw table-bordered table-hover table-striped mb-0" data-tablesaw-mode="swipe"  data-tablesaw-sortable data-tablesaw-minimap data-tablesaw-mode-switch>
        <thead>
    <tr class="header">
        {{--<th scope="col" class="tablesaw-swipe-cellpersist" data-tablesaw-sortable-col data-tablesaw-priority="">
            <div class="custom-control custom-checkbox checkbox-primary">
                <input type="checkbox" class="custom-control-input" id="checkallpo" name="checkallpo">
                <label class="custom-control-label" for="checkallpo"></label>
            </div>
        </th>--}}
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1" >Action</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">PO No.<span id="po_no_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">PO Date<span id="po_date_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Supplier Name<span id="supplier_gst_id_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">Delivery Date<span id="delivery_date_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">Delivery To<span id="delivery_to_icon"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="7">Qty Required<span id="total_qty"></span></th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">Qty Received</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="9">Qty Pending</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="10">Take Inward</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="11">Note</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="12">Print</th>
    </tr>
    </thead>
    <tbody>

@foreach($purchase_order AS $key=>$value)
    <?php if($key % 2 == 0)
    {
        $tblclass = 'even';
    }
    else
    {
        $tblclass = 'odd';
    }
    ?>
    <tr id="{{$value->purchase_order_id}}" class="<?php echo $tblclass ?>">
       {{-- <td>
        <input type="checkbox" style="width:30px;margin-left:-17px;height: 17px;" name="delete_po[]" value="{{$value->purchase_order_id }}" id="delete_po{{$value->purchase_order_id }}">
        </td>--}}
        <td class="leftAlign">
            <a title="View PO" ondblclick="view_po_detail('{{encrypt($value->purchase_order_id)}}');">
            <i class="fa fa-eye" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;font-weight:bold;"  ></i>
            </a>

        @if($value['received_qty'] > 0)
                <a title="No Edit">---</a>
        @else
            <a id="edit_purchase_order" title="Edit" onclick="return edit_po('{{encrypt($value->purchase_order_id)}}','1');"><i class="fa fa-edit"></i></a>
            @endif
        </td>
        <td class="leftAlign">{{$value->po_no}}</td>
        <td class="leftAlign">{{$value->po_date}}</td>
        <td class="leftAlign">{{$value->supplier_gstdetail->supplier_company_info->supplier_first_name}} {{$value->supplier_gstdetail->supplier_company_info->supplier_last_name}}</td>
        <td class="leftAlign">{{$value->delivery_date}}</td>
        <td class="leftAlign">{{$value->delivery_to}}</td>
        <td class="rightAlign">{{$value->total_qty}}</td>
        <td class="rightAlign">{{$value->received_qty}}</td>
        <td class="rightAlign">{{$value->pending_qty}}</td>



        @if($value['pending_qty'] != 0)
        <td>
            <button class="">
            <a  id="take_inward_data" title="Take Inward" onclick="return edit_po('{{encrypt($value->purchase_order_id)}}','2');">Take Inward</a>
            </button>
        </td>
            @else
            <td>
                <a title="No Inward">---</a>
            </td>
        @endif

        <td>{{$value->note}}</td>

        <td>
            <a href="{{URL::to('print_po')}}?id={{encrypt($value->purchase_order_id)}}&print_type={{encrypt('1')}}" style="text-decoration:none !important;" target="_blank" title="Print">
                <i class="fa fa-print" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;"></i>
            </a>
        </td>
    </tr>
@endforeach

<tr>
    <td colspan="13" class="paginateui">
        {!! $purchase_order->links() !!}
    </td>
</tr>
    </tbody>
</table>
<input type="hidden" name="hidden_page" id="hidden_page" value="1"/>
<input type="hidden" name="hidden_column_name" id="hidden_column_name" value="purchase_order_id"/>
<input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="desc"/>
<input type="hidden" name="fetch_data_url" id="fetch_data_url" value="purchase_order_fetch_data"/>


<script src="{{URL::to('/')}}/public/dist/js/tablesaw-data.js"></script>
<script type="text/javascript">
    $(".PagecountResult").html(' ({{$purchase_order->total()}})');
</script>