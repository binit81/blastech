<table id="view_billproduct_recordtable" class="table tablesaw table-bordered table-hover table-striped mb-0" data-tablesaw-mode="swipe" data-tablesaw-sortable data-tablesaw-minimap data-tablesaw-mode-switch>

<thead>
<tr class="header">

    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Bill No.</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Bill Date</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Customer</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Product Name</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">Barcode</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">SellingPrice</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="7">Qty</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">Disc.%</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="9">Disc. Amount</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="10">Overall Discount</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="11">Taxable Amount</th>
    <?php
    if($tax_type==1)
    {
        ?>
        
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="16">{{$taxname}}%</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="17">{{$taxname}} Amount</th>
        <?php
    }
    else
    {
        ?>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="12">CGST%</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="13">CGST Amount</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="14">SGST%</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="15">SGST Amount</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="16">IGST%</th>
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="17">IGST Amount</th>
        <?php
    }
    ?>
    
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="18">Total Amount</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="18">Reference</th>

</tr>
</thead>
<tbody>




<?php

 if(sizeof($sales_room_details) != 0) 
 {

?>
@foreach($sales_room_details AS $saleskey=>$sales_value)
<?php if ($saleskey % 2 == 0) {
    $tblclass = 'even';
} else {
    $tblclass = 'odd';
}
if($sales_value['product']['supplier_barcode']!='' || $sales_value['product']['supplier_barcode']!=NULL)
{
   $barcode = $sales_value['product']['supplier_barcode'];
}
else
{
  $barcode = $sales_value['product']['product_system_barcode'];
}
?>

<tr id="">
    <td class="leftAlign">{{$sales_value->sales_bill->bill_no}}</td>
    <td class="leftAlign">{{$sales_value->sales_bill->bill_date}}</td>
    <td class="leftAlign">{{$sales_value->sales_bill['customer']['customer_name']}}</td>
    <td class="leftAlign">{{$sales_value['product']['product_name']}}</td>
    <td class="leftAlign">{{$barcode}}</td>
    <td class="rightAlign">{{number_format($sales_value->sellingprice_before_discount,2)}}</td>
    <td class="rightAlign">{{$sales_value->qty}}</td>
    <td class="rightAlign">{{$sales_value->discount_percent}}</td>
    <td class="rightAlign">{{number_format($sales_value->discount_amount,2)}}</td>
    <td class="rightAlign">{{number_format($sales_value->overalldiscount_amount,2)}}</td>
    <td class="rightAlign">{{number_format($sales_value->sellingprice_afteroverall_discount,2)}}</td>
    <?php
    if($tax_type==1)
    {
            ?>
             <td class="rightAlign">{{$sales_value->igst_percent}}</td>
             <td class="rightAlign">{{number_format($sales_value->igst_amount,2)}}</td>
            <?php
    }
    else
    {
            if($sales_value['sales_bill']['state_id'] == $company_state)
            {
                ?>
                        <td class="rightAlign">{{$sales_value->cgst_percent}}</td>
                        <td class="rightAlign">{{number_format($sales_value->cgst_amount,2)}}</td>
                        <td class="rightAlign">{{$sales_value->sgst_percent}}</td>
                        <td class="rightAlign">{{number_format($sales_value->sgst_amount,2)}}</td>
                        <td class="rightAlign">0.00</td>
                        <td class="rightAlign">0.00</td>
                <?php
            }
            else
            {
                ?>
                        <td class="rightAlign">0.00</td>
                        <td class="rightAlign">0.00</td>
                        <td class="rightAlign">0.00</td>
                        <td class="rightAlign">0.00</td>
                        <td class="rightAlign">{{$sales_value->igst_percent}}</td>
                        <td class="rightAlign">{{number_format($sales_value->igst_amount,2)}}</td>
                <?php
            }
    }
    
    ?>

   
    <td class="rightAlign bold">{{number_format($sales_value->total_amount,$nav_type[0]['decimal_points'])}}</td>
    <td class="rightAlign">{{$sales_value['sales_bill']['reference']['reference_name']}}</td>
</tr>

 


@endforeach

@foreach($return_room_details AS $returnkey=>$return_value)
<?php
if($return_value['product']['supplier_barcode']!='' || $return_value['product']['supplier_barcode']!=NULL)
{
   $barcode = $return_value['product']['supplier_barcode'];
}
else
{
  $barcode = $return_value['product']['product_system_barcode'];
}
?>
<tr id="" style="background:#b10911 !important;">
    <td class="color leftAlign">{{$return_value['return_bill']['sales_bill']['bill_no']}}</td>
    <td class="color leftAlign">{{$return_value['return_bill']['bill_date']}}</td>
    <td class="color leftAlign">{{$return_value['customer']['customer_name']}}</td>
    <td class="color leftAlign">{{$return_value['product']['product_name']}}</td>
    <td class="color leftAlign">{{$barcode}}</td>
    <td class="rightAlign color">{{number_format($return_value->sellingprice_before_discount,2)}}</td>
    <td class="rightAlign color">{{$return_value->qty}}</td>
    <td class="rightAlign color">{{$return_value->discount_percent}}</td>
    <td class="rightAlign color">{{number_format($return_value->discount_amount,2)}}</td>
    <td class="rightAlign color">{{number_format($return_value->overalldiscount_amount,2)}}</td>
    <td class="rightAlign color">{{number_format($return_value->sellingprice_afteroverall_discount,2)}}</td>
    <?php
    if($tax_type==1)
    {
            ?>
            <td class="rightAlign color">{{$return_value->igst_percent}}</td>
            <td class="rightAlign color">{{number_format($return_value->igst_amount,2)}}</td>
            <?php
    }
    else
    {
            if($return_value['return_bill']['state_id'] == $company_state)
            {
                ?>
                        <td class="rightAlign color">{{$return_value->cgst_percent}}</td>
                        <td class="rightAlign color">{{number_format($return_value->cgst_amount,2)}}</td>
                        <td class="rightAlign color">{{$return_value->sgst_percent}}</td>
                        <td class="rightAlign color">{{number_format($return_value->sgst_amount,2)}}</td>
                        <td class="rightAlign color">0.00</td>
                        <td class="rightAlign color">0.00</td>
                <?php
            }
            else
            {
                ?>
                        <td class="rightAlign color">0.00</td>
                        <td class="rightAlign color">0.00</td>
                        <td class="rightAlign color">0.00</td>
                        <td class="rightAlign color">0.00</td>
                        <td class="rightAlign color">{{$return_value->igst_percent}}</td>
                        <td class="rightAlign color">{{number_format($return_value->igst_amount,2)}}</td>
                <?php
            }
    }
    
    ?>

   
    <td class="rightAlign bold color">{{number_format($return_value->total_amount,$nav_type[0]['decimal_points'])}}</td>
    <td class="rightAlign color">{{$return_value['return_bill']['reference']['reference_name']}}</td>
 
</tr>

 


@endforeach

<tr>

 <td colspan="18" align="center">
       {!! $sales_room_details->links() !!}
    </td>
</tr>

<script src="{{URL::to('/')}}/public/dist/js/tablesaw-data.js"></script>
<?php
}

else
{
        ?>
            <tr>
            <td colspan="18" class="leftAlign">
            <b style="font-size:16px;">No Records Found!</b>
            </td>
            </tr>
        <?php
}
?>
</tbody>
</table>
<input type="hidden" name="hidden_page" id="hidden_page" value="1" />
<input type="hidden" name="hidden_column_name" id="hidden_column_name" value="sales_bill_id" />
<input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="DESC" />
<input type="hidden" name="fetch_data_url" id="fetch_data_url" value="datewise_product_billdetail" />
<script type="text/javascript">
$(document).ready(function(e){

   
    $('.taxabletariff').html({{round($todaytaxable,$nav_type[0]['decimal_points'])}});
    $('.totalcgst').html({{round($todaycgst,$nav_type[0]['decimal_points'])}});
    $('.totalsgst').html({{round($todaysgst,$nav_type[0]['decimal_points'])}});
    $('.totaligst').html({{round($todayigst,$nav_type[0]['decimal_points'])}});
    $('.grandtotal').html({{round($todaygrand,$nav_type[0]['decimal_points'])}});
    

});
</script>