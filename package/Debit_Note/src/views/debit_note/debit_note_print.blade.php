<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Print Debit Note</title>
    <meta name="description" content="A responsive bootstrap 4 admin dashboard template by hencework" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- vector map CSS -->

    <!-- Custom CSS -->
    <link href="{{URL::to('/')}}/public/dist/css/style.css" rel="stylesheet" type="text/css">
    <link href="{{URL::to('/')}}/public/template/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">

    <link href="{{URL::to('/')}}/public/dist/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <script type="text/javascript">window.print();</script>

    <style>
        ul
        {
            list-style:square !important;
            margin : 0 0 0 8px !important;
            padding: 0 0 0 8px !important;
        }
        ul li
        {
            margin : 0 0 0 0 !important;
            padding: 0 0 0 0 !important;
        }
    </style>
</head>

<div class="row">
    <div class="col-xl-12" >
        <section class="hk-sec-wrapper hk-invoice-wrap" style="margin:-15px 0 0 0 !important;font-family:Tahoma, Geneva, sans-serif !important;font-size:13px !important;border:0px !important;">
            <div class="invoice-from-wrap">
                <span class="mb-35 font-weight-600" style="font-size:34px;"><center>DEBIT NOTE</center></span>


                <div class="row" style="margin:20px 0 0 0;">
                    <div class="col-md-8 mb-20" style="width:60% !important;border:0px solid !important;font-size:16px;">
                        <img class="img-fluid invoice-brand-img d-block mb-20 pull-left" src="{{URL::to('/')}}/public/dist/img/rcslogo1.png" width="150" alt="logo" style="margin:0 5px 0 0;"/>
                        <span class="d-block font-weight-600" style="font-size:24px;"><span class="pl-10 text-dark">{{$debit_note[0]->company->company_name}}</span></span>
                        <span class="d-block font-weight-600"><span class="pl-10 text-dark">{{strip_tags($debit_note[0]->company->company_address)}}</span></span>
                        <span class="d-block font-weight-600"><span class="pl-10 text-dark">{{strip_tags ($debit_note[0]->company->company_area)}} {{$debit_note[0]->company->company_city}} - {{$debit_note[0]->company->company_pincode}}</span></span>
                        <?php
                        $company_mobile_code =  explode(',',$debit_note[0]->company->company_mobile_dial_code);
                        if($debit_note[0]->company->company_mobile!='' || $debit_note[0]->company->company_mobile!=null)
                        {
                        ?>
                        <span class="d-block font-weight-600"><span class="pl-10 text-dark">({{$company_mobile_code[0]}}){{$debit_note[0]->company->company_mobile}}</span></span>
                        <?php
                        }
                        ?>
                        <span class="d-block font-weight-600"><span class="pl-10 text-dark">{{$debit_note[0]->company->company_email}}</span></span>


                    </div>
                    <?php
                    $whtapp_mobile_code =  explode(',',$debit_note[0]->company->whatsapp_mobile_dial_code);
                    ?>
                    <div class="col-md-4 mb-20" style="text-align:right !important;width:40% !important;border:0px solid !important;font-size:16px;">
                        <table style="float:right;">
                            <tr>
                                <td colspan="3" class="font-weight-600">{{strip_tags ($debit_note[0]->company->website)}}</td>

                            </tr>
                            <?php
                            if(isset($debit_note) && isset($debit_note[0]['company'])
                            && $debit_note[0]['company']['whatsapp_mobile_number']!=NULL)
                            {
                            ?>
                            <tr>
                                <td class="d-block font-weight-600"><a class="fa fa-whatsapp"></a></td>
                                <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                <td class="font-weight-600">({{$whtapp_mobile_code[0]}}){{$debit_note[0]->company->whatsapp_mobile_number}}</td>
                            </tr>
                            <?php

                            }

                            if(isset($debit_note) && isset($debit_note[0]['company'])
                            && $debit_note[0]['company']['facebook']!=NULL)
                            {
                            ?>
                            <tr>
                                <td class="d-block font-weight-600"><a class="fa fa-facebook"></a></td>
                                <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                <td class="font-weight-600">{{$debit_note[0]->company->facebook}}</td>
                            </tr><?php


                            }

                            if(isset($debit_note) && isset($debit_note[0]['company'])
                            && $debit_note[0]['company']['instagram']!=NULL)
                            {
                            ?>
                            <tr>
                                <td class="d-block font-weight-600"><a class="fa fa-instagram"></a></td>
                                <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                <td class="font-weight-600">{{$debit_note[0]->company->instagram}}</td>
                            </tr>
                            <?php


                            }
                            if(isset($debit_note) && isset($debit_note[0]['company'])
                            && $debit_note[0]['company']['pinterest']!=NULL)
                            {
                            ?>
                            <tr>
                                <td class="d-block font-weight-600"><a class="fa fa-pinterest"></a></td>
                                <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                <td class="font-weight-600">{{$debit_note[0]->company->pinterest}}</td>
                            </tr>
                            <?php


                            }
                            ?>
                        </table>


                    </div>
                </div>
            </div>
            <?php
            $tax_currency = '&#8377;';
            $tax_title = 'GST';
            $tax = 'GSTIN';

            if($nav_type[0]['tax_type'] == 1)
            {
             $tax = $nav_type[0]['tax_title'];
            $tax_title = $nav_type[0]['tax_title'];
            $tax_currency = $nav_type[0]['currency_title'];
            }
            ?>

            <div class="invoice-to-wrap pb-20">

                <div class="row">

                    <div class="col-md-7 mb-30" style="width:50% !important;border:0px solid !important;font-size:16px;">
                        <table style="float:left;">

                            <tr>
                                <?php
                                $suppplier_address = isset($debit_note[0]['supplier_gstdetail']['supplier_address']) ? $debit_note[0]['supplier_gstdetail']['supplier_address'] : '';
                                $suppplier_area = isset($debit_note[0]['supplier_gstdetail']['supplier_area']) ? $debit_note[0]['supplier_gstdetail']['supplier_area'] : '';
                                $suppplier_zip = isset($debit_note[0]['supplier_gstdetail']['supplier_gst_zipcode']) ? $debit_note[0]['supplier_gstdetail']['supplier_gst_zipcode'] : '';
                                $suppplier_city = isset($debit_note[0]['supplier_gstdetail']['supplier_gst_city']) ? $debit_note[0]['supplier_gstdetail']['supplier_gst_city'] : '';
                                $address = $suppplier_address.$suppplier_area.$suppplier_zip.$suppplier_city;
                                ?>
                                <td class="d-block font-weight-600">Address</td>
                                <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                <td class="font-weight-600"><?php echo $address?></td>
                            </tr>

                            <tr>
                                <td class="d-block font-weight-600"><?php echo $tax?></td>
                                <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                <td class="font-weight-600">{{$debit_note[0]['supplier_gstdetail']['supplier_gstin']}}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-5 mb-30" style="width:50% !important;border:0px solid !important;font-size:16px;">

                        <table style="float:right;">
                            <tr>
                                <td class="d-block font-weight-600">Debit Note Number</td>
                                <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                <td class="font-weight-600 text-right"><?php echo $debit_note[0]['debit_no'] ?></td>
                            </tr>
                            <tr>
                                <td class="d-block font-weight-600">Debit Note Date</td>
                                <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                <td class="font-weight-600 text-right"><?php echo $debit_note[0]['debit_date'] ?></td>
                            </tr>
                            <tr>
                                <td class="d-block font-weight-600"><?php echo $tax?></td>
                                <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                <td class="font-weight-600 text-right"><?php echo $debit_note[0]['company']['gstin']?></td>
                            </tr>

                            <tr>
                                <td class="d-block font-weight-600">Place</td>
                                <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                <td class="font-weight-600 text-right"><?php echo $debit_note[0]['company']['state_name']['state_name']?></td>
                            </tr>

                            <tr>
                                <td class="d-block font-weight-600">Invoice Number</td>
                                <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                <td class="font-weight-600 text-right"><?php echo $debit_note[0]['inward_stock']['invoice_no']?></td>
                            </tr>

                            <tr>
                                <td class="d-block font-weight-600">Invoice Date</td>
                                <td class="font-weight-600">&nbsp;:&nbsp;</td>
                                <td class="font-weight-600 text-right"><?php echo $debit_note[0]['inward_stock']['invoice_date']?></td>
                            </tr>

                        </table>

                    </div>
                </div>
            </div>


            <table width="100%" cellpadding="6" border="0" frame="box">
                <thead>
                <tr style="background:#999;border-bottom:1px #999 solid;border-top:1px #999 solid;">
                    <th class="text-left text-dark font-12 font-weight-600" style="width:5% !important;">Sr.<br>No.</th>
                    <th class="text-left text-dark font-12 font-weight-600" style="width:10% !important;">Barcode</th>
                    <th class="text-left text-dark font-12 font-weight-600" style="width:10% !important;">Product Name</th>
                    <th class="text-right text-dark font-12 font-weight-600" style="width:10% !important;">Cost Rate</th>
                    <th class="text-right text-dark font-12 font-weight-600" style="width:5% !important;">Qty</th>
                    <th class="text-right text-dark font-12 font-weight-600" style="width:10% !important;">Taxable Amount</th>
                    <?php if($nav_type[0]['tax_type'] == 1) { ?>
                        <th class="text-right text-dark font-12 font-weight-600 interstate" style="width:20% !important;"><?php echo $tax_title?>%</th>
                    <th class="text-right text-dark font-12 font-weight-600 interstate" style="width:20% !important;"><?php echo $tax_title?> Amt.</th>

                    <?php } else {
                        if($debit_gst_breakup[0]['cost_igst_percent'] == 0 || $debit_gst_breakup[0]['cost_igst_percent'] == '') { ?>
                    <th class="text-right text-dark font-12 font-weight-600 intrastate" style="width:9% !important;">CGST%</th>
                    <th class="text-right text-dark font-12 font-weight-600 intrastate" style="width:9% !important;">CGST Amt.</th>
                    <th class="text-right text-dark font-12 font-weight-600 intrastate" style="width:9% !important;">SGST%</th>
                    <th class="text-right text-dark font-12 font-weight-600 intrastate" style="width:9% !important;">SGST Amt.</th>
                    <?php } else { ?>
                    <th class="text-right text-dark font-12 font-weight-600 interstate" style="width:20% !important;">IGST%</th>
                    <th class="text-right text-dark font-12 font-weight-600 interstate" style="width:20% !important;">IGST Amt.</th>
                    <?php } }?>
                    <th class="text-right text-dark font-12 font-weight-600" style="width:14% !important;">Total Amount</th>
                </tr>
                </thead>
                <tbody>

                <?php
                $total_cost = 0;
                $total_cost_gst_amount = 0;
                $total_igst_amount = 0;
                $total_cgst_amount = 0;
                $total_sgst_amount = 0;
                ?>
                @foreach($debit_note[0]['debit_product_details'] AS $key=>$value)
                    <?php
                    $barcode = '';
                    if ($value != '' && $value['product']['supplier_barcode'] != " " && $value['product']['supplier_barcode'] != null)
                    {
                        $barcode = $value['product']['supplier_barcode'];
                    } else {

                        $barcode = $value['product']['product_system_barcode'];
                    }

                    $total_cost += $value['cost_rate'];
                    $total_cost_gst_amount += $value['cost_gst_amount'];


                    $total_igst_amount += $value['total_igst_amount_with_qty'];
                    $total_cgst_amount += $value['total_cgst_amount_with_qty'];
                    $total_sgst_amount += $value['total_sgst_amount_with_qty'];

                    $key++;

                    ?>

                    <tr style="border-bottom:1px solid #C0C0C0 !important;">
                        <td class="font-weight-600 text-dark"><?php echo $key?></td>
                        <td class="font-weight-600 text-dark"><?php echo $barcode ?></td>
                        <td class="font-weight-600 text-dark">{{$value->product->product_name}}</td>
                        <td class="text-right font-weight-600 text-dark">{{$value->cost_rate}}</td>
                        <td class="text-right font-weight-600 text-dark">{{$value->return_qty}}</td>
                        <td class="text-right font-weight-600 text-dark">{{$value->total_cost_rate}}</td>
                        <?php if($debit_gst_breakup[0]['cost_igst_percent'] == 0 || $debit_gst_breakup[0]['cost_igst_percent'] == '') { ?>
                        <td class="text-right font-weight-600 text-dark intrastate">{{$value->cost_cgst_percent}}</td>
                        <td class="text-right font-weight-600 text-dark intrastate">{{$value->total_cgst_amount_with_qty}}</td>
                        <td class="text-right font-weight-600 text-dark intrastate">{{$value->cost_sgst_percent}}</td>
                        <td class="text-right font-weight-600 text-dark intrastate">{{$value->total_sgst_amount_with_qty}}</td>
                        <?php } else { ?>
                        <td class="text-right font-weight-600 text-dark interstate">{{$value->cost_igst_percent}}</td>
                        <td class="text-right font-weight-600 text-dark interstate">{{$value->total_igst_amount_with_qty}}</td>
                        <?php } ?>
                        <td class="text-right font-weight-600 text-dark">{{$value->total_cost_price}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="text-dark" style="height:50px !important;">&nbsp;</td>
                    <td class="text-dark"></td>
                    <td class="text-dark"></td>
                    <td class="text-dark"></td>
                    <td class="text-right text-dark"></td>
                    <td class="text-right text-dark"></td>
                    <?php if($debit_gst_breakup[0]['cost_igst_percent'] == 0 || $debit_gst_breakup[0]['cost_igst_percent'] == '') { ?>
                    <td class="text-right text-dark intrastate"></td>
                    <td class="text-right text-dark intrastate"></td>
                    <td class="text-right text-dark intrastate"></td>
                    <td class="text-right text-dark intrastate"></td>
                    <?php } else { ?>
                    <td class="text-right text-dark interstate"></td>
                    <td class="text-right text-dark interstate"></td>
                    <?php } ?>
                    <td class="text-right text-dark"></td>
                </tr>
                </tbody>

                <tfoot style="border-bottom:1px solid #999 !important;border-top:1px solid #999 !important;">
                <tr>
                    <th colspan="2" class="font-14 font-weight-600"></th>
                    <th  class="font-14 font-weight-600 text-right">Total</th>
                    <th class="text-right font-weight-600"></th>
                    <th class="text-right font-weight-600">{{$debit_note[0]['total_qty']}}</th>
                    <th class="text-right font-weight-600">{{$debit_note[0]['total_cost_rate']}}</th>
                    <?php if($debit_gst_breakup[0]['cost_igst_percent'] == 0 || $debit_gst_breakup[0]['cost_igst_percent'] == '') { ?>
                    <th class="text-right font-weight-600 intrastate"></th>
                    <th class="text-right font-weight-600 intrastate"><?php echo $total_cgst_amount?></th>
                    <th class="text-right font-weight-600 intrastate"></th>
                    <th class="text-right font-weight-600 intrastate"><?php echo $total_sgst_amount?></th>
                    <?php }  else {?>
                    <th class="text-right font-weight-600 interstate"></th>
                    <th class="text-right font-weight-600 interstate"><?php echo $total_igst_amount?></th>
                    <?php }?>
                    <th class="text-right text-dark font-18 font-weight-600"><?php echo $tax_currency?> {{$debit_note[0]['total_cost_price']}}</th>
                </tr>
                </tfoot>
            </table>

            <div class="invoice-from-wrap" style="margin:45px 0 0 0;">
                <div class="row">
                    <div class="col-md-8 mb-20" style="width:60% !important;border:0px solid !important;"></div>
                    <div class="col-md-4 mb-20" style="width:40% !important;border:0px solid !important;font-size:16px;"></div>
                </div>
                <div class="row">

                </div>
                <br>
                <br>
                <center><span class="d-block font-weight-600" style="font-size:18px;">{{strip_tags($debit_note[0]->company->additional_message)}}</span></center>
            </div>

            <div class="invoice-from-wrap" style="margin:25px 0 0 0;">
                <div class="row">
                    <div class="col-md-8 mb-20" style="width:60% !important;border:0px solid !important;">
                        <span class="mb-35 font-weight-600" style="font-size:16px;"><center><--------GST Breakup Details--------></center></span>
                        <table width="100%" cellpadding="6" cellspacing="0" frame="box" style="float:left;border:1px solid !important;">
                            <thead>
                            <tr style="border-bottom:1px #999 solid;border-top:1px #999 solid;">
                                <th class="text-dark font-12 font-weight-600 bottomdarkline" style="width:8% !important;text-align:left;"><?php echo  $tax_title ?>%</th>
                                <th class="text-right text-dark font-12 font-weight-600 bottomdarkline" style="width:16% !important;">Taxable Amt.</th>
                                <?php if($nav_type[0]['tax_type'] == 1) { ?>
                                <th class="text-right text-dark font-12 font-weight-600 bottomdarkline interstate" style="width:16% !important;"><?php echo $tax_title ?><br><small>Rate & Amt.</small></th>
                                <?php } else {if($debit_gst_breakup[0]['cost_igst_percent'] == 0 || $debit_gst_breakup[0]['cost_igst_percent'] == '') { ?>
                                <th class="text-right text-dark font-12 font-weight-600 bottomdarkline intrastate" style="width:16% !important;">CGST<br><small>Rate & Amt.</small></th>
                                <th class="text-right text-dark font-12 font-weight-600 bottomdarkline intrastate" style="width:16% !important;">SGST<br><small>Rate & Amt.</small></th>
                                <?php } else { ?>
                                <th class="text-right text-dark font-12 font-weight-600 bottomdarkline interstate" style="width:16% !important;">IGST<br><small>Rate & Amt.</small></th>
                                <?php } }?>
                                <th class="text-right text-dark font-12 font-weight-600 bottomdarkline" style="width:26% !important;">Total Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $final_total_taxable_value = 0;
                            $final_total_cgst_value = 0;
                            $final_total_sgst_value = 0;
                            $final_total_igst_value = 0;
                            $final_total_grand = 0;
                            foreach($debit_gst_breakup AS $gst_key=>$gst_value)
                            {
                            $final_total_taxable_value += $gst_value['total_taxable_value'];
                            $final_total_cgst_value += $gst_value['total_cgst_amount_with_qty'];
                            $final_total_sgst_value += $gst_value['total_sgst_amount_with_qty'];
                            $final_total_igst_value += $gst_value['total_igst_amount_with_qty'];
                            $final_total_grand += $gst_value['total_grand'];
                            ?>
                            <tr style="border-bottom:1px solid #C0C0C0 !important;">
                                <td class="text-dark font-weight-600">{{round($gst_value->cost_gst_percent,2)}}%</td>
                                <td class="text-right text-dark font-weight-600">{{round($gst_value->total_taxable_value,2)}}</td>
                                <?php if($debit_gst_breakup[0]['cost_igst_percent'] == 0 || $debit_gst_breakup[0]['cost_igst_percent'] == '') { ?>
                                <td class="text-right text-dark font-weight-600 intrastate">{{round($gst_value->cost_cgst_percent,2)}}%<br>{{round($gst_value->total_cgst_amount_with_qty,2)}}</td>
                                <td class="text-right text-dark font-weight-600 intrastate">{{round($gst_value->cost_sgst_percent  ,2)}}%<br>{{round($gst_value->total_sgst_amount_with_qty,2)}}</td>
                                <?php } else { ?>
                                <td class="text-right text-dark font-weight-600 interstate">{{round($gst_value->cost_igst_percent,2)}}%<br>{{round($gst_value->total_igst_amount_with_qty,2)}}</td>
                                <?php } ?>
                                <td class="text-right text-dark font-weight-600 tottotamt">{{round($gst_value->total_grand,2)}}</td>
                            </tr>
                            </tbody>
                            <?php } ?>
                            <tfoot style="border-bottom:1px solid #999 !important;border-top:1px solid #999 !important;">
                            <tr>
                                <th class="text-right font-14 font-weight-600 upperdarkline">Total</th>
                                <th class="text-right text-dark font-14 font-weight-600 upperdarkline"><?php echo $final_total_taxable_value?></th>
                                <?php if($debit_gst_breakup[0]['cost_igst_percent'] == 0 || $debit_gst_breakup[0]['cost_igst_percent'] == '') { ?>
                                <th class="text-right text-dark font-14 font-weight-600 upperdarkline intrastate"><?php echo $final_total_cgst_value?></th>
                                <th class="text-right text-dark font-14 font-weight-600 upperdarkline intrastate"><?php echo $final_total_sgst_value?></th>
                                <?php } else { ?>
                                <th class="text-right text-dark font-14 font-weight-600 upperdarkline interstate"><?php echo $final_total_igst_value?></th>
                                <?php } ?>
                                <th class="text-right text-dark font-18 font-weight-600 upperdarkline"><?php echo $tax_currency?> <?php echo $final_total_grand?></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="col-md-8 mb-20" style="width:70% !important;border:0px solid !important;margin:20px 0 0 0;">
                        <?php
                        if(isset($debit_note) && isset($debit_note[0]['company'])&& $debit_note[0]['company']['terms_and_condition']!=NULL)
                        {
                        ?>
                        <table style="float:left;font-size:12px;">
                            <tr>
                                <td colspan="3" class="font-weight-600" style="font-size:14px;">TERMS & CONDITIONS</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="font-weight-600"><?php echo html_entity_decode($debit_note[0]->company->terms_and_condition); ?></td>
                            </tr>

                        </table>
                        <?php
                        }
                        ?>
                    </div>

                    <div class="col-md-4 mb-20 text-right" style="width:30% !important;border:0px solid !important;">
                        <?php
                        if(isset($debit_note[0]->company->authorized_signatory_for) && $debit_note[0]->company->authorized_signatory_for!='')
                        {
                        ?>
                        <span class="d-block font-weight-600" style="font-size:16px;">For {{$debit_note[0]->company->authorized_signatory_for}}</span><br><br>
                        <span class="d-block font-weight-600" style="font-size:16px;">Authorized Signatory</span>
                        <?php
                        }
                        ?>
                    </div>
        </section>
    </div>
</div>


