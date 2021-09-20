<?php
 $tax_label = 'GST';


    if($nav_type[0]['tax_type'] == 1)
    {
        $tax_label = $nav_type[0]['tax_title'];
    }
?>

<table id="inwardtable" class="table  table-bordered table-hover  mb-0" data-tablesaw-mode="swipe"  data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-minimap data-tablesaw-mode-switch>
    <thead>
    <tr class="header">
        <th style="width: 3%;"><i class="fa fa-remove"></i></th>
        <th style="width: 3%;">Barcode</th>
        <th style="width: 3%;">Prod Name</th>
        <th style="width: 3%;">HSN</th>
        <th class="garment_case_hide" style="width: 3%;">Batch No.</th>
        <th style="width: 3%;">Base Price</th>
        <th class="garment_case_hide" colspan="2" style="width: 3%;">Disc % & Amt</th>
        <th class="garment_case_hide" colspan="2" style="width: 3%;">Scheme % & Amt</th>
        <th class="garment_case_hide" colspan="2" style="width: 3%;">Free % & Amt</th>
        <th style="width: 3%;">Cost Price</th>
        <th colspan="2" style="width: 3%;"><?php echo $tax_label?> % & Amt</th>
        <th style="width: 3%;">Extra Charge</th>
        <th colspan="2" style="width: 3%;">Profit % & Amt</th>
        <th style="width: 3%;">SellPrice</th>
        <th colspan="2" style="width: 3%;"><?php echo $tax_label?> % & Amt</th>
        <th style="width: 3%;">Offer Price</th>
        <th style="width: 3%;">MRP</th>
        <th id="po_pending_show" style="width: 3%;">Pending Qty for this PO</th>
        <th style="width: 3%;">Add Qty</th>
        <th class="garment_case_hide" style="width: 3%;">Free Qty</th>
        <th class="garment_case_hide" style="width: 3%;">Mfg Date</th>
        <th class="garment_case_hide" style="width: 3%;">Exp Date</th>
        <th style="width: 3%;">Total Cost</th>
        <th id="pending_qty_return" style="width: 3%;">InStock Qty for this Inward</th>
    </tr>
    </thead>
    <tbody id="product_detail_record">
    </tbody>
</table>