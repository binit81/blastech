<table id="view_creditreceipt_recordtable" class="table tablesaw table-bordered table-hover table-striped mb-0" data-tablesaw-mode="swipe" data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-minimap data-tablesaw-mode-switch>

<thead>
<tr class="header">
  
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Receipt No.</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Receipt Date</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Customer</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Remarks</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">Total Amount</th>
    
     @foreach($payment_methods AS $payment_methods_key=>$payment_methods_value)
        <?php
        if($payment_methods_value->payment_method_id!=6)
        {
        ?>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">{{$payment_methods_value->payment_method_name}}</th>
        <?php
        }
        ?>
    @endforeach
   
   <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">Action</th>
</tr>
</thead>
<tbody>


@foreach($receipts AS $receiptkey=>$receipt_value)
<?php if ($receiptkey % 2 == 0) {
    $tblclass = 'even';
} else {
    $tblclass = 'odd';
}
$remarks  =  $receipt_value->remarks!=''?$receipt_value->remarks:'- - - - -';

if($receipt_value['return_bill_id']!= null && $receipt_value['return_bill_id']!= '')
    {
       
       $returnid  =   $receipt_value['return_bill_id'];
        
    }
    else
    {
        $returnid = '';
    }
?>

<tr id="viewreceipt_{{$receipt_value->customer_creditreceipt_id}}" class="<?php echo $tblclass ?>">

    
    <td>{{$receipt_value->receipt_no}}</td>
    <td>{{$receipt_value->receipt_date}}</td>
    <td>{{$receipt_value['customer']['customer_name']}}</td>
    <td>{{$remarks}}</td>
    <td class="rightAlign">{{round($receipt_value->total_amount,2)}}</td>
    <?php
    foreach($payment_methods AS $payment_methods_key=>$payment_methods_value)
    {
        if($payment_methods_value->payment_method_id!=6)
        {
        $count  = 0;
        foreach($receipt_value->customer_crerecp_payment as $payment_detail) { 

                if($payment_methods_value->payment_method_id == $payment_detail->payment_method_id){

                   
                    $count++;
    ?>
                <td class="rightAlign">{{$payment_detail['total_bill_amount']}}</td>
               <?php
                }
                
            }
            if($count == 0)
                {
                    ?>
                    <td class="rightAlign">0</td>
                    <?php

                }
        }
    }


   ?>


<?php
        if($returnid == '')
        {
            ?>
             <td><a href="{{URL::to('print_creditreceipt')}}?id={{encrypt($receipt_value->customer_creditreceipt_id)}}" style="text-decoration:none !important;" target="_blank" title="Print"><i class="fa fa-print" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;"></i></a><a id="deletereceipt_{{$receipt_value->customer_creditreceipt_id}}" onclick="return deletereceipt(this);" style="text-decoration:none !important;" target="_blank" title="Delete"><i class="fa fa-trash" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;"></i></a></td>
          
            <?php
        }
        else
        {   

            ?>
             <td><a href="{{URL::to('print_creditreceipt')}}?id={{encrypt($receipt_value->customer_creditreceipt_id)}}" style="text-decoration:none !important;" target="_blank" title="Print"><i class="fa fa-print" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;"></i></a><a id="deletereceipt_{{$receipt_value->customer_creditreceipt_id}}" onclick="return nodeletereceipt(this);" style="text-decoration:none !important;" target="_blank" title="Delete"><i class="fa fa-trash" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;"></i></a></td>
               
            <?php

        }
 ?>

</tr>

@endforeach
<tr>
    <td colspan="17" align="center">
        {!! $receipts->links() !!}
    </td>
</tr>
</tbody>
</table>
<input type="hidden" name="hidden_page" id="hidden_page" value="1" />
<input type="hidden" name="hidden_column_name" id="hidden_column_name" value="customer_creditreceipt_id" />
<input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="DESC" />
<input type="hidden" name="fetch_data_url" id="fetch_data_url" value="datewise_cuscreditdetail" />
<script type="text/javascript">
$(document).ready(function(e){

   
    
    

});
</script>
<script src="{{URL::to('/')}}/public/dist/js/tablesaw-data.js"></script>
