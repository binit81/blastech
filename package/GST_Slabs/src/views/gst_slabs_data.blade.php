<?php
/**
 * Created by PhpStorm.
 * User: retailcore
 * Date: 18/3/19
 * Time: 5:15 PM
 */
?>

<table id="customerrecordtable" class="table tablesaw table-bordered table-hover  mb-0" data-tablesaw-mode="swipe"  data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-minimap data-tablesaw-mode-switch>
        <thead>
        <tr class="header">
            <th scope="col" class="tablesaw-swipe-cellpersist" data-tablesaw-sortable-col data-tablesaw-priority="persist">
                <div class="custom-control custom-checkbox checkbox-primary">
                    <input type="checkbox" class="custom-control-input" id="checkall" name="checkall" >
                    <label class="custom-control-label" for="checkall"></label>
                </div>
            </th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Price From &#x20b9;<span id="selling_price_from_icon"></span></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Price To &#x20b9;<span id="selling_price_to_icon"></span></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">GST %<span id="percentage_icon"></span></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Note</th>
        </tr>
        </thead>
        <tbody id="gstslabrecord">


@foreach($gst_slabs AS $gstslab_key=>$gstslab_value)
    <?php if($gstslab_key % 2 == 0)
    {
        $tblclass = 'even';
    }
    else
    {
        $tblclass = 'odd';
    }
    ?>
    <tr id="{{$gstslab_value->gst_slabs_master_id}}" class="<?php echo $tblclass ?>">
        <td>
            <input type="checkbox" name="delete_gstslabs[]" value="{{$gstslab_value->gst_slabs_master_id }}" id="delete_gstslabs{{$gstslab_value->gst_slabs_master_id }}">

            <a onclick="return editgstslabs('{{encrypt($gstslab_value->gst_slabs_master_id)}}');">
            <i class="fa fa-edit" title="Edit GST Slab"></i></a>

        </td>
        <td class="rightAlign" >{{number_format($gstslab_value->selling_price_from)}}</td>
        <td class="rightAlign">{{number_format($gstslab_value->selling_price_to)}}</td>
        <td class="rightAlign">{{number_format($gstslab_value->percentage)}}</td>
        <td class="rightAlign">{{$gstslab_value->note}}</td>
    </tr>
@endforeach
<tr>
    <td colspan="5" class="paginateui">
       {!! $gst_slabs->links() !!}
    </td>
</tr>
        </tbody>
    </table>
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="gst_slabs_master_id" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
    <input type="hidden" name="fetch_data_url" id="fetch_data_url" value="gst_slabs_fetch_data" />
<script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>


<script src="{{URL::to('/')}}/public/dist/js/tablesaw-data.js"></script>
<script type="text/javascript">
    $(".PagecountResult").html(' ({{$gst_slabs->total()}})');
</script>