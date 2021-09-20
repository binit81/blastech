<table id="view_billproduct_recordtable" class="table tablesaw table-bordered table-hover table-striped mb-0" data-tablesaw-mode="swipe" data-tablesaw-sortable data-tablesaw-minimap data-tablesaw-mode-switch>

<thead>
<tr class="header">

    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Bill No.</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Bill Date</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Product Name</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Barcode</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Qty</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">SellingPrice</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">Disc. Amount</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="7">Taxable Amount</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">Average Cost</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="10">Profit/Loss</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="11">Profit/Loss%</th>
</tr>
</thead>
<tbody>




<?php

if(sizeof($productdetails) != 0) 
{

?>
@foreach($productdetails AS $saleskey=>$sales_value)
<?php
    $totalsellingprice   =   $sales_value['qty'] * $sales_value['sellingprice_before_discount'];
    $totaldiscount       =   $sales_value['discount_amount']  + $sales_value['overalldiscount_amount'];
    $total_price = 0;

    if($sales_value['inwardids'] !='' || $sales_value['inwardids'] !=null)
    {

       $inwardids  = explode(',' ,substr($sales_value['inwardids'],0,-1));
       $inwardqtys = explode(',' ,substr($sales_value['inwardqtys'],0,-1));
       

        foreach($inwardids as $inidkey=>$inids)
        {
              $cost_price = Retailcore\Inward_Stock\Models\inward\inward_product_detail::select('cost_rate')->find($inids);
               
              $total_price += $cost_price['cost_rate'] * $inwardqtys[$inidkey];            
        } 
        $averagecost      =   ($total_price / $sales_value['qty']) * $sales_value['qty'];
        $profitamt        =   $sales_value->sellingprice_afteroverall_discount  - $averagecost;
        $profitper        =   ($profitamt * 100)/$averagecost;
    }
    else
    {
        $averagecost      =   0;
        $profitamt        =   $sales_value->sellingprice_afteroverall_discount  - $averagecost;
        $profitper        =   0;
    }
      //print_r($total_price);
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
    <td class="leftAlign">{{$sales_value['sales_bill']['bill_no']}}</td>
    <td class="leftAlign">{{$sales_value['sales_bill']['bill_date']}}</td>
    <td class="leftAlign">{{$sales_value['product']['product_name']}}</td>
    <td class="leftAlign">{{$barcode}}</td>
    <td class="rightAlign">{{$sales_value['qty']}}</td>
    <td class="rightAlign">{{number_format($totalsellingprice,2)}}</td>
    <td class="rightAlign">{{number_format($totaldiscount,2)}}</td>
    <td class="rightAlign">{{number_format($sales_value->sellingprice_afteroverall_discount,2)}}</td>
    <td class="rightAlign">{{number_format($averagecost,2)}}</td>
    <td class="rightAlign">{{number_format($profitamt,2)}}</td>
    <td class="rightAlign">{{number_format($profitper,2)}}</td>
</tr>

 


@endforeach
<?php
if(sizeof($rproductdetails) != 0) 
{

?>
@foreach($rproductdetails AS $returnkey=>$return_value)
<?php
    $totalsellingprice   =   $return_value['qty'] * $return_value['sellingprice_before_discount'];
    $totaldiscount       =   $return_value['discount_amount']  + $return_value['overalldiscount_amount'];

     $inwardids  = explode(',' ,substr($return_value['inwardids'],0,-1));
     $inwardqtys = explode(',' ,substr($return_value['inwardqtys'],0,-1));
     $total_price = 0;
    if($return_value['inwardids'] !='' || $return_value['inwardids'] !=null)
    {

      foreach($inwardids as $inidkey=>$inids)
      {
            $cost_price = Retailcore\Inward_Stock\Models\inward\inward_product_detail::select('cost_rate')->find($inids);
             
            $total_price += $cost_price['cost_rate'] * $inwardqtys[$inidkey];            
      } 
      $averagecost      =   ($total_price / $return_value['qty']) * $return_value['qty'];
      $profitamt        =   $return_value->sellingprice_afteroverall_discount  - $averagecost;
      $profitper        =   ($profitamt * 100)/$averagecost;
    }
    else
    {
        $averagecost      =   0;
        $profitamt        =   $return_value->sellingprice_afteroverall_discount  - $averagecost;
        $profitper        =   0;
    }
    if($return_value['product']['supplier_barcode']!='' || $return_value['product']['supplier_barcode']!=NULL)
      {
         $barcode = $return_value['product']['supplier_barcode'];
      }
      else
      {
        $barcode = $return_value['product']['product_system_barcode'];
      }
      //print_r($total_price);
      
?>

<tr id="" style="background:#b10911 !important;">
    <td class="color leftAlign">{{$return_value['return_bill']['sales_bill']['bill_no']}}</td>
    <td class="color leftAlign">{{$return_value['return_bill']['bill_date']}}</td>
    <td class="color leftAlign">{{$return_value['product']['product_name']}}</td>
    <td class="color leftAlign">{{$barcode}}</td>
    <td class="rightAlign color">{{$return_value['qty']}}</td>
    <td class="rightAlign color">{{number_format($totalsellingprice,2)}}</td>
    <td class="rightAlign color">{{number_format($totaldiscount,2)}}</td>
    <td class="rightAlign color">{{number_format($return_value->sellingprice_afteroverall_discount,2)}}</td>
    <td class="rightAlign color">{{number_format($averagecost,2)}}</td>
    <td class="rightAlign color">{{number_format($profitamt,2)}}</td>
    <td class="rightAlign color">{{number_format($profitper,2)}}</td>
</tr>

 


@endforeach

<?php
}
?>

<tr>

 <td colspan="18" align="center">
       {!! $productdetails->links() !!}
    </td>
</tr>
<script type="text/javascript">
$(document).ready(function(e){

   
  
    

});
</script>
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
<input type="hidden" name="fetch_data_url" id="fetch_data_url" value="datewise_profitloss_detail" />
