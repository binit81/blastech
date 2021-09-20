<table id="view_creditbillbal_recordtable" class="table tablesaw table-bordered table-hover table-striped mb-0" data-tablesaw-mode="swipe" data-tablesaw-sortable data-tablesaw-minimap data-tablesaw-mode-switch>
                                            
<thead>
<tr class="header">
  
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Sr No.</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Customer Name</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Mobile No.</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Bill No.</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Due Date</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Remaining Days</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Outstanding Amount</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Paid Amount</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Balance Amount</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Action</th>
   
</tr>
</thead>
<tbody id="view_bill_record">

@foreach($customerbillbaldata AS $customerbalkey=>$customerbal_value)
<?php if ($customerbalkey % 2 == 0) {
    $tblclass = 'even';
} else {
    $tblclass = 'odd';
}
$sr  =   $customerbalkey + 1;

$duedate   =   $customerbal_value['duedate'];
$todaydate =   date("d-m-Y");

$diff = strtotime($duedate) - strtotime($todaydate); 
      
// 1 day = 24 hours 
// 24 * 60 * 60 = 86400 seconds 
$days = abs(round($diff / 86400)); 

?>

<tr id="" class="<?php echo $tblclass ?>">

    <td>{{$sr}}</td>
    <td>{{$customerbal_value['customer']['customer_name']}}</td>
    <td>{{$customerbal_value['customer']['customer_mobile']}}</td>
    <td>{{$customerbal_value['sales_bill']['bill_no']}}</td>
    <td>{{$customerbal_value['duedate']}}</td>
    <td>{{$days}}</td>
    <td class="rightAlign">{{$customerbal_value['credit_amount']}}</td>
    <td class="rightAlign">{{$customerbal_value['totalcreditrecd']!=''?$customerbal_value['totalcreditrecd']:0}}</td>
    <td class="rightAlign">{{$customerbal_value['balance_amount']}}</td>
    <td class="rightAlign"><a href="{{URL::to('customer_credit_ac')}}?id={{encrypt($customerbal_value->customer_id)}}" style="text-decoration:none !important;" target="_blank" title="Print"><i class="fa fa-eye" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;font-weight:bold;">View Detail</i></a></td>
</tr>


@endforeach
<tr>
    <td colspan="17" align="center">
        {!! $customerbillbaldata->links() !!}
    </td>
</tr>

</tbody>
</table>
<input type="hidden" name="hidden_page" id="hidden_page" value="1" />
<input type="hidden" name="hidden_column_name" id="hidden_column_name" value="sales_bill_id" />
<input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="DESC" />
<input type="hidden" name="fetch_data_url" id="fetch_data_url" value="datewise_creditbaldetail" />
<script src="{{URL::to('/')}}/public/dist/js/tablesaw-data.js"></script>