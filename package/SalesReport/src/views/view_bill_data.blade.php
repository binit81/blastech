<table id="view_bill_recordtable" class="table tablesaw table-bordered table-hover table-striped mb-0" data-tablesaw-mode="swipe" data-tablesaw-sortable data-tablesaw-minimap data-tablesaw-mode-switch>


<thead>
<tr class="header">
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Action</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Bill No.</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Bill Date</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Customer</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">Total Qty</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">Selling Price</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="7">Disc. Amt.</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">Taxable Amt.</th>
    <?php
    if($tax_type==1)
    {
        ?>
           <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="11">{{$taxname}} Amt.</th> 
    <?php
    }
    else
    {
        ?>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="9">CGST Amt.</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="10">SGST Amt.</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="11">IGST Amt.</th> 
        <?php
    }
    ?>
                                             
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="12">Total Bill Amt.</th>

    @foreach($payment_methods AS $payment_methods_key=>$payment_methods_value)
        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="13">{{$payment_methods_value->payment_method_name}}</th>
    @endforeach
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="12">Reference</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="12">Note for Internal USE</th>
    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="12">Note for Customer</th>
</tr>
</thead>
<tbody>
           


<?php

 if(sizeof($sales) != 0) 
 {
    
 
foreach($sales AS $saleskey=>$sales_value)
{
if ($saleskey % 2 == 0) {
    $tblclass = 'even';
} else {
    $tblclass = 'odd';
}

 


$sellingbeforediscount = $sales_value->sellingprice_after_discount + $sales_value->totaldiscount + $sales_value->totalcharges;

    if($sales_value['return_bill']!= null && $sales_value['return_bill']!= '')
    {
       
       $returnid  =   $sales_value['return_bill']['sales_bill_id'];
        
    }
    else
    {
        $returnid = '';
    }

    $totalsellingprice_after_discount  =   $sales_value->sellingprice_after_discount + $sales_value->totalcharges;

    $halfchargesgst   =   $sales_value->chargesgst / 2;

  if($tax_type==1)
  {
     $totaligstamount   =   $sales_value->total_igst_amount + $sales_value->chargesgst;
  }
  else
  {
        if($sales_value['state_id']==$company_state)
        {
                    $totalcgstamount   =   $sales_value->total_cgst_amount + $halfchargesgst;
                    $totalsgstamount   =   $sales_value->total_sgst_amount + $halfchargesgst;
                    $totaligstamount   =   0;
        }
        else
        {
                    $totalcgstamount   =   0;
                    $totalsgstamount   =   0;
                    $totaligstamount   =   $sales_value->total_igst_amount + $sales_value->chargesgst;
        }
   }

?>

<tr id="viewbill_{{$sales_value->sales_bill_id}}" class="<?php echo $tblclass ?>" ondblclick="return viewBill(this);">
    <td class="leftAlign"><i class="fa fa-eye" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;font-weight:bold;"  id="viewbill_{{$sales_value->sales_bill_id}}" onclick="return viewBill(this);" title="View Bill Details"></i>
    <?php
        if($returnid == '')
        {
            ?>
            <a id="edit_bill" onclick="return edit_hotelbill('{{encrypt($sales_value->sales_bill_id)}}');" title="Edit"><i class="fa fa-edit" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;"></i></a><a href="{{URL::to('print_bill')}}?id={{encrypt($sales_value->sales_bill_id)}}" style="text-decoration:none !important;" target="_blank" title="A4/A5 Print"><i class="fa fa-print" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;"></i></a><a href="{{URL::to('thermalprint_bill')}}?id={{encrypt($sales_value->sales_bill_id)}}" style="text-decoration:none !important;" target="_blank" title="Thermal Print"><i class="fa fa-print" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;"></i></a><a id="deletebill_{{$sales_value->sales_bill_id}}" onclick="return deletebill(this);" style="text-decoration:none !important;" target="_blank" title="Delete"><i class="fa fa-trash" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;"></i></a>
            <?php
        }
        else
        {   

            ?>
                <a id="edit_bill" onclick="return notedit_hotelbill('{{$sales_value->sales_bill_id}}');" title="Edit"><i class="fa fa-edit" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;"></i></a><a href="{{URL::to('print_bill')}}?id={{encrypt($sales_value->sales_bill_id)}}" style="text-decoration:none !important;" target="_blank" title="A4/A5 Print"><i class="fa fa-print" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;"></i></a><a href="{{URL::to('thermalprint_bill')}}?id={{encrypt($sales_value->sales_bill_id)}}" style="text-decoration:none !important;" target="_blank" title="Thermal Print"><i class="fa fa-print" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;"></i></a><a id="deletebill_{{$sales_value->sales_bill_id}}" onclick="return deletebill(this);" style="text-decoration:none !important;" target="_blank" title="Delete"><i class="fa fa-trash" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;"></i></a>
            <?php

        }
    ?>
    </td>   
    <td class="leftAlign">{{$sales_value->bill_no}}</td>
    <td class="leftAlign">{{date("d-m-Y",strtotime($sales_value->bill_date))}}</td>
    <td class="leftAlign">{{$customername = $sales_value['customer']['customer_name']}}</td>
    <td class="rightAlign">{{$sales_value->total_qty}}</td>
    <td class="rightAlign">{{number_format($sellingbeforediscount,2)}}</td>
    <td class="rightAlign">{{number_format($sales_value->totaldiscount,2)}}</td>
    <td class="rightAlign">{{number_format($totalsellingprice_after_discount,2)}}</td>
    <?php
    if($tax_type==1)
    {
        ?>

        <td class="rightAlign">{{number_format($totaligstamount,2)}}</td>
        <?php
    }
    else
    {
        ?>
         <td class="rightAlign">{{number_format($totalcgstamount,2)}}</td>
        <td class="rightAlign">{{number_format($totalsgstamount,2)}}</td>
        <td class="rightAlign">{{number_format($totaligstamount,2)}}</td>
        <?php
    }
    ?>
   
    <td class="rightAlign bold">{{number_format($sales_value->total_bill_amount,$nav_type[0]['decimal_points'])}}</td>
    @foreach($payment_methods AS $payment_methods_key=>$payment_methods_value)
        <td class="rightAlign" id="bill{{$sales_value->sales_bill_id}}{{$payment_methods_value->payment_method_id}}">0.00</td>
    @endforeach
    <td class="leftAlign">{{$sales_value['reference']['reference_name']}}</td>
    <td class="leftAlign">{{$sales_value->official_note}}</td>
    <td class="leftAlign">{{$sales_value->print_note}}</td>

</tr>


@foreach($sales_value->sales_bill_payment_detail AS $salespayment_key=>$salespayment_value)
    <?php
    $billid = $sales_value->sales_bill_id . $salespayment_value->payment_method_id;
    ?>
    <script type="text/javascript">
        $('#bill<?php echo $billid?>').html(Number(<?php echo $salespayment_value->total_bill_amount ?>).toFixed({{$nav_type[0]['decimal_points']}}));
    </script>

@endforeach
<?php
}
?>

@foreach($returnbill AS $returnkey=>$return_value)

<?php

$rsellingbeforediscount = $return_value->sellingprice_after_discount + $return_value->totaldiscount + $return_value->totalcharges;


    $rtotalsellingprice_after_discount  =   $return_value->sellingprice_after_discount + $return_value->totalcharges;

    $rhalfchargesgst   =   $return_value->chargesgst / 2;

  if($tax_type==1)
  {
    $rtotaligstamount   =   $return_value->total_igst_amount + $return_value->chargesgst;
  }
  else
  {
        if($return_value['state_id']==$company_state)
        {
                    $rtotalcgstamount   =   $return_value->total_cgst_amount + $rhalfchargesgst;
                    $rtotalsgstamount   =   $return_value->total_sgst_amount + $rhalfchargesgst;
                    $rtotaligstamount   =   0;
        }
        else
        {
                    $rtotalcgstamount   =   0;
                    $rtotalsgstamount   =   0;
                    $rtotaligstamount   =   $return_value->total_igst_amount + $return_value->chargesgst;
        }
  }
    ?>
<tr style="background:#b10911 !important;">
    <td class="color leftAlign"><i class="fa fa-eye" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;font-weight:bold;color:#fff;"  id="viewreturnbill_{{$return_value->return_bill_id}}" onclick="return viewreturnBill(this);"></i>
     <a href="{{URL::to('print_creditnote')}}?id={{encrypt($return_value->return_bill_id)}}" style="text-decoration:none !important;" target="_blank" title="Print"><i class="fa fa-print" aria-hidden="true" style="margin:0 2px !important;cursor:pointer;color:#fff;"></i></a></td>   
    <td class="color leftAlign">{{$return_value['sales_bill']['bill_no']}}</td>
    <td class="color leftAlign">{{$return_value->created_at->format('d-m-Y')}}</td>
    <td class="color leftAlign">{{$customername = $return_value['customer']['customer_name']}}</td>
    <td class="color rightAlign">{{$return_value->total_qty}}</td>
    <td class="color rightAlign">{{round($rsellingbeforediscount,2)}}</td>
    <td class="color rightAlign">{{round($return_value->totaldiscount,2)}}</td>
    <td class="color rightAlign">{{round($rtotalsellingprice_after_discount,2)}}</td>
    <?php
    if($tax_type==1)
    {
        ?>
         <td class="color rightAlign">{{round($rtotaligstamount,2)}}</td>
        <?php
    }
    else
    {
        ?>
         <td class="color rightAlign">{{round($rtotalcgstamount,2)}}</td>
        <td class="color rightAlign">{{round($rtotalsgstamount,2)}}</td>
        <td class="color rightAlign">{{round($rtotaligstamount,2)}}</td>
        <?php
    }
    ?>
   
    <td class="color rightAlign bold" >{{number_format($return_value->total_bill_amount,$nav_type[0]['decimal_points'])}}</td>
    @foreach($payment_methods AS $payment_methods_key=>$payment_methods_value)
        <td class="color rightAlign" id="rbill{{$return_value->return_bill_id}}{{$payment_methods_value->payment_method_id}}">0.00</td>
    @endforeach
    <td class="leftAlign color">{{$return_value['reference']['reference_name']}}</td>
    <td class="leftAlign color"></td>
    <td class="leftAlign color"></td>

</tr>
@foreach($return_value->return_bill_payment AS $returnpayment_key=>$returnrpayment_value)
    <?php
    $rbillid = $return_value->return_bill_id . $returnrpayment_value->payment_method_id;
    $creditamt =  number_format($returnrpayment_value->total_bill_amount,$nav_type[0]['decimal_points']);
    $creditno =  $returnrpayment_value['customer_creditnote']['creditnote_no'];
    $creditamtno   =  $creditamt.'<br>( '.$creditno.' )';
    ?>
    <script type="text/javascript">
        $('#rbill<?php echo $rbillid?>').html('<?php echo $creditamtno; ?>');
    </script>

@endforeach

@endforeach
<tr>
    <td colspan="22" align="center">
        {!! $sales->links() !!}
    </td>
</tr>

<script src="{{URL::to('/')}}/public/dist/js/tablesaw-data.js"></script>

<?php
}

else
{
        ?>
            <tr>
            <td colspan="22" class="leftAlign">
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
<input type="hidden" name="fetch_data_url" id="fetch_data_url" value="datewise_billdetail" />
<script type="text/javascript">
$(document).ready(function(e){
   

    $('.totalinvoice').html({{$count}});
    $('.taxabletariff').html({{round($todaytaxable,$nav_type[0]['decimal_points'])}});
    $('.overallcgst').html({{round($todaycgst,$nav_type[0]['decimal_points'])}});
    $('.overallsgst').html({{round($todaysgst,$nav_type[0]['decimal_points'])}});
    $('.overalligst').html({{round($todayigst,$nav_type[0]['decimal_points'])}});
    $('.overallgrand').html({{round($todaygrand,$nav_type[0]['decimal_points'])}});
   
    $('.fromdate').html("{{$max_date}}");
    $('.todate').html("{{$min_date}}");

    if($('.totalinvoice').html()==0  || $('.totalinvoice').html()=='')
    {
        $('.invoiceLabel').html('');
    }
    else if($('.totalinvoice').html()==1)
    {
       $('.invoiceLabel').html('Invoice');
    }
    else
    {
        $('.invoiceLabel').html('Invoices');
    }

   

});
</script>