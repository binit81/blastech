<table  id="view_billgst_recorddata" class="table tablesaw table-bordered table-hover table-striped mb-0" data-tablesaw-mode="swipe" data-tablesaw-sortable data-tablesaw-minimap data-tablesaw-mode-switch>

<thead>
<tr class="header">

    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Bill No.</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Bill Date</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Customer</th>
    @foreach($gst_slabs AS $gstkey=>$gst_value)
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">{{$gst_value['igst_percent']}}%<br>Taxable</th>
    <?php
    if($tax_type==1)
    {
            ?>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">{{$gst_value['igst_percent']}}%<br>{{$taxname}}</th>
            <?php
    }
    else
    {
        ?>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">{{$gst_value['cgst_percent']}}%<br>CGST</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="7">{{$gst_value['sgst_percent']}}%<br>SGST</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">{{$gst_value['igst_percent']}}%<br>IGST</th>
        <?php
    }
    ?>
    
    @endforeach
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">Total Taxable</th>
     <?php
    if($tax_type==1)
    {
            ?>
             <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">Total {{$taxname}}</th>
            <?php
    }
    else
    {
        ?>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">Total CGST</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">Total SGST</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">Total IGST</th>
        <?php
    }
    ?>
    
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">Total Amount</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">Reference</th>

</tr>
</thead>
<tbody>


@foreach($sales AS $saleskey=>$sales_value)


<tr id="">
    <td class="leftAlign">{{$sales_value->bill_no}}</td>
    <td class="leftAlign">{{$sales_value->bill_date}}</td>
    <td class="leftAlign">{{$sales_value['customer']['customer_name']}}</td>
    <?php
    foreach($gst_slabs AS $gstkey=>$gst_value)
    {
        
         $count  = 0;
         $billtariff = 0;
         $billcgst = 0;
         $billsgst = 0;
         $billigst = 0;

        foreach($sales_value->sales_product_detail AS $salesroom_key=>$salesroom_value)
        {
            if($gst_value->igst_percent == $salesroom_value->igst_percent){
                
                $billtariff  +=  $salesroom_value->sellingprice_afteroverall_discount;
                $billcgst    +=  $salesroom_value->cgst_amount;
                $billsgst    +=  $salesroom_value->sgst_amount;
                $billigst    +=  $salesroom_value->igst_amount;
                $count++;
             }

         }

         if($tax_type==1)
         {
             if($count == 0)
                 {
                    ?>

                    <td style="text-align:right !important;">0</td>
                    <td style="text-align:right !important;">0</td>
                    <?php


                 }
                 else
                 {
                    ?>
                    <td style="text-align:right !important;">{{number_format($billtariff,2)}}</td>
                    <td style="text-align:right !important;">{{number_format($billigst,2)}}</td>
                    <?php

                 }
         }
         else
         {  
                 if($count == 0)
                 {
                    ?>

                    <td style="text-align:right !important;">0</td>
                    <td style="text-align:right !important;">0</td>
                    <td style="text-align:right !important;">0</td>
                    <td style="text-align:right !important;">0</td>
                    <?php


                 }
                 else
                 {
                    ?>
                    <td style="text-align:right !important;">{{number_format($billtariff,2)}}</td>
                    <?php
                    if($sales_value['state_id']==$company_state)
                    {
                        ?>
                         <td style="text-align:right !important;">{{number_format($billcgst,2)}}</td>
                         <td style="text-align:right !important;">{{number_format($billsgst,2)}}</td>
                         <td style="text-align:right !important;">0.00</td>
                        <?php
                    } 
                    else
                    {
                        ?>
                         <td style="text-align:right !important;">0.00</td>
                         <td style="text-align:right !important;">0.00</td>
                         <td style="text-align:right !important;">{{number_format($billigst,2)}}</td>
                        <?php
                    }  
                   

                 }

         }
         
        

    }
    ?>
    
    <td style="text-align:right !important;" >{{number_format($sales_value->sellingprice_after_discount,2)}}</td>
    <?php
        if($tax_type==1)
        {
            ?>
            <td style="text-align:right !important;" >{{number_format($sales_value->total_igst_amount,2)}}</td>
            <?php
        }
        else
        {
                if($sales_value['state_id']==$company_state)
                {
                    ?>
                        <td style="text-align:right !important;" >{{number_format($sales_value->total_cgst_amount,2)}}</td>
                        <td style="text-align:right !important;" >{{number_format($sales_value->total_sgst_amount,2)}}</td>
                        <td style="text-align:right !important;" >0.00</td>
                    <?php
                }
                else
                {
                    ?>
                        <td style="text-align:right !important;" >0.00</td>
                        <td style="text-align:right !important;" >0.00</td>
                        <td style="text-align:right !important;" >{{number_format($sales_value->total_igst_amount,2)}}</td>
                    <?php
                }
        }
            
    ?>
    
    <td style="text-align:right !important;" class="bold">{{number_format($sales_value->total_bill_amount,$nav_type[0]['decimal_points'])}}</td>
     <td style="text-align:right !important;">{{$sales_value['reference']['reference_name']}}</td>
 
</tr>
   
  
    
@endforeach

<?php
if(sizeof($returnbill)!=0)
{
?>
@foreach($returnbill AS $returnkey=>$return_value)


<tr style="background:#b10911 !important;">
    <td class="color leftAlign">{{$return_value['sales_bill']['bill_no']}}</td>
    <td class="color leftAlign">{{$return_value->bill_date}}</td>
    <td class="color leftAlign">{{$return_value['customer']['customer_name']}}</td>
    <?php
    foreach($gst_slabs AS $gstkey=>$gst_value)
    {
         $count  = 0;
         $billtariff = 0;
         $billcgst = 0;
         $billsgst = 0;
         $billigst = 0;

        foreach($return_value->return_product_detail AS $returnroom_key=>$returnroom_value)
        {
            if($gst_value->igst_percent == $returnroom_value->igst_percent){
                
                $billtariff  +=  $returnroom_value->sellingprice_afteroverall_discount;
                $billcgst    +=  $returnroom_value->cgst_amount;
                $billsgst    +=  $returnroom_value->sgst_amount;
                $billigst    +=  $returnroom_value->igst_amount;
                $count++;
             }

         }
         if($tax_type==1)
         {
                if($count == 0)
                 {
                    ?>
                    <td class="rightAlign color">0</td>
                    <td class="rightAlign color">0</td>
                    <?php


                 }
                 else
                 {
                    ?>
                    <td class="rightAlign color">{{number_format($billtariff,2)}}</td>
                    <td class="rightAlign color">{{number_format($billigst,2)}}</td>
                        <?php
                   
                 }
         }
         else
         {
                if($count == 0)
                 {
                    ?>
                    <td class="rightAlign color">0</td>
                    <td class="rightAlign color">0</td>
                    <td class="rightAlign color">0</td>
                    <td class="rightAlign color">0</td>
                    <?php


                 }
                 else
                 {
                    ?>
                    <td class="rightAlign color">{{number_format($billtariff,2)}}</td>
                    <?php
                    if($returnroom_value['state_id']==$company_state)
                    {
                        ?>
                         <td class="rightAlign color">{{number_format($billcgst,2)}}</td>
                         <td class="rightAlign color">{{number_format($billsgst,2)}}</td>
                         <td class="rightAlign color">0.00</td>
                        <?php
                    } 
                    else
                    {
                        ?>
                         <td class="rightAlign color">0.00</td>
                         <td class="rightAlign color">0.00</td>
                         <td class="rightAlign color">{{number_format($billigst,2)}}</td>
                        <?php
                    }  
                   

                 }
         }
         
        

    }
    ?>
    
    <td class="rightAlign color">{{number_format($returnroom_value->sellingprice_after_discount,2)}}</td>
    <?php
        if($tax_type==1)
        {
                  ?>
                  <td class="rightAlign color">{{number_format($return_value->total_igst_amount,2)}}</td>
                <?php
            
        }
        else
        {
            if($return_value['state_id']==$company_state)
            {
                ?>
                    <td class="rightAlign color">{{number_format($return_value->total_cgst_amount,2)}}</td>
                    <td class="rightAlign color">{{number_format($return_value->total_sgst_amount,2)}}</td>
                    <td class="rightAlign color">0.00</td>
                <?php
            }
            else
            {
                ?>
                    <td class="rightAlign color">0.00</td>
                    <td class="rightAlign color">0.00</td>
                    <td class="rightAlign color">{{number_format($return_value->total_igst_amount,2)}}</td>
                <?php
            }
        }
            
    ?>
    
    <td class="rightAlign color bold">{{number_format($return_value->total_bill_amount,$nav_type[0]['decimal_points'])}}</td>
    <td style="text-align:right !important;" class="color">{{$return_value['reference']['reference_name']}}</td>
 
</tr>
   
  
    
 @endforeach
 <?php
}
 ?>

<tr>
    <td colspan="16" align="center">
        {!! $sales->links() !!}
    </td>
</tr>

</tbody>
</table>
<input type="hidden" name="hidden_page" id="hidden_page" value="1" />
<input type="hidden" name="hidden_column_name" id="hidden_column_name" value="sales_bill_id" />
<input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="DESC" />
<input type="hidden" name="fetch_data_url" id="fetch_data_url" value="gstwise_billdetail" />

<script src="{{URL::to('/')}}/public/dist/js/tablesaw-data.js"></script>