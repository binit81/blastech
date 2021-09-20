$("#roundoff_offer").click(function(){
    $("#roundoff_offer").attr('disabled',true);
    reverce_on_offer_price();
});



function baseprice(obj) {
    var product_id = $(obj).attr('id').split('base_price_')[1];
    var tbl_row = $(obj).closest('tr').data('id');

    $("#product_detail_record").find("tr[data-id='" + tbl_row + "']").each(function () {
        var baseprice = $(this).find("#base_price_" + product_id).html();

        var freeqty = $(this).find("#free_qty_" + product_id).html();
        var qty = $(this).find("#product_qty_" + product_id).html();
        var total_qty_with = ((Number(freeqty)) + (Number(qty)));

        if (qty == 0) {
            qty = 1;
        }

        var discountpercent = $(this).find("#base_discount_percent_" + product_id).html();
        var discountamount = ((Number(baseprice)) * (Number(discountpercent)) / Number(100));
        $(this).find("#base_discount_amount_" + product_id).html(discountamount.toFixed(4));


        var costprice = ((Number(baseprice) - (Number(discountamount))));

        $(this).find("#cost_rate_" + product_id).html(costprice.toFixed(4));
        var schemepercent = $(this).find("#scheme_discount_percent_" + product_id).html();
        var cost_price_discount = $(this).find("#cost_rate_" + product_id).html();
        var schemeamount = ((Number(cost_price_discount) * Number(schemepercent)) / (Number(100)));
        $(this).find("#scheme_discount_amount_" + product_id).html(schemeamount.toFixed(4));
        var cost_final = (Number(cost_price_discount) - Number(schemeamount));

        $(this).find("#cost_rate_" + product_id).html(cost_final.toFixed(4));


        //FREE PERCENT AND AMOUNT
        var cost_price_schm = $(this).find("#cost_rate_" + product_id).html();
        var freeamtbefore = 0;

        if (total_qty_with == 0)
        {
            freeamtbefore = ((Number(cost_price_schm)) * (Number(qty)) / ((Number(qty)) + (Number(freeqty))));
        } else {
            freeamtbefore = ((Number(cost_price_schm)) * (Number(qty)) / ((Number(total_qty_with))));
        }

        if (isNaN(freeamtbefore)) {
            freeamtbefore = 0;
        }
        var free_amount = ((Number(cost_price_schm)) - (Number(freeamtbefore)));
        var freepercent = (((Number(free_amount)) * (Number(100))) / (Number(cost_price_schm)));
        if (isNaN(freepercent)) {
            freepercent = 0;
        }
        $(this).find("#free_discount_percent_" + product_id).html(freepercent.toFixed(4));
        $(this).find("#free_discount_amount_" + product_id).html(free_amount.toFixed(4));

        var costpriceafterfree = ((Number(cost_price_schm)) - (Number(free_amount)));

        $(this).find("#cost_rate_" + product_id).html(costpriceafterfree.toFixed(4));


        var cost_rate = $(this).find("#cost_rate_" + product_id).html();
        var gstpercent = $(this).find("#gst_percent_" + product_id).html();

        var gstamount = ((Number(cost_rate)) * (Number(gstpercent)) / Number(100));

        $(this).find("#gst_amount_" + product_id).html(gstamount.toFixed(4));

        var gst_amt = $(this).find("#gst_amount_" + product_id).html();


        //CALCULATE PROFIT AMOUNT
        var cost_price = $(this).find("#cost_rate_" + product_id).html();
        var extracharge = $(this).find("#extra_charge_" + product_id).html();
        var cost_rate_for_profit = ((Number(cost_price)) + (Number(extracharge))).toFixed(4);
        var profit_percent = $(this).find("#profit_percent_" + product_id).html();
        var profitamt = ((Number(cost_rate_for_profit)) * (Number(profit_percent)) /(Number(100)));
        $(this).find("#profit_amount_" + product_id).html(profitamt.toFixed(4));
        var profitpercent = 0;
        if(cost_rate_for_profit == '' || cost_rate_for_profit =='0.0000')
        {
            profitpercent = 100;
           // $(this).find("#profit_percent_" + product_id).html(profitpercent.toFixed(4));
        }
        else
        {
            profitpercent = ((Number(100)) * (Number(profitamt)) / (Number(cost_rate_for_profit)));
           // $(this).find("#profit_percent_" + product_id).html(profitpercent.toFixed(4));
        }


        //END CALCULATION OF PROFIT

        //CALCULATE SELLING PRICE
        var profit = $(this).find("#profit_amount_" + product_id).html();
        var sellingprice = ((Number(cost_rate_for_profit)) + (Number(profit)));
        $(this).find("#sell_price_" + product_id).html(sellingprice.toFixed(4));
        //END OF CALCULATE SELLING PRICE


        //CALCULATE OFFER PRICE
        var sell_price_for_offer = $(this).find("#sell_price_" + product_id).html();
        var sell_percent = $(this).find("#selling_gst_percent_" + product_id).html();
        var sellgstamt = ((Number(sell_price_for_offer) * (Number(sell_percent))) / (Number(100)));
        $(this).find("#selling_gst_amount_" + product_id).html(sellgstamt.toFixed(4));


        var sellgst_for_offer = $(this).find("#selling_gst_amount_" + product_id).html();
        var offer_price = ((Number(sell_price_for_offer)) + (Number(sellgst_for_offer)));
        $(this).find("#offer_price_" + product_id).html(offer_price.toFixed(4));
        $(this).find("#product_mrp_" + product_id).html(offer_price.toFixed(4));



        //END OF CALCULATE OFFER PRICE

        var totalqty = '';

        if (total_qty_with == 0) {
            totalqty = ((Number(freeqty)) + (Number(qty)));
        } else {
            totalqty = total_qty_with;
        }

        var totalcostval = (((Number(cost_rate)) + (Number(gst_amt))) * (Number(totalqty)));

        if (total_qty_with != 0 && !isNaN(total_qty_with)) {
            $(this).find("#total_cost_" + product_id).html(totalcostval.toFixed(4));

        } else {
            $(this).find("#total_cost_" + product_id).html(0);
        }
        totalcalculation();
    });
}

function extracharge(obj) {
    var product_id = $(obj).attr('id').split('extra_charge_')[1];

    var tbl_row = $(obj).closest('tr').data('id');

    $("#product_detail_record").find("tr[data-id='" + tbl_row + "']").each(function ()
    {
        var cost_price = $(this).find("#cost_rate_" + product_id).html();
        var extracharge = $(this).find("#extra_charge_" + product_id).html();
        var costprice = ((Number(cost_price)) + (Number(extracharge))).toFixed(4);
        //var sellingprice = $(this).find("#sell_price_" + product_id).html();

        //CALCULATE PROFIT AMOUNT
        var profit_percent = $(this).find("#profit_percent_" + product_id).html();
        var profitamt = ((Number(costprice)) * (Number(profit_percent)) /(Number(100)));
        //var profitamt = ((Number(sellingprice)) - (Number(costprice)));
        $(this).find("#profit_amount_" + product_id).html(profitamt.toFixed(4));
        var profitpercent = ((Number(100)) * (Number(profitamt)) / (Number(costprice)));
       // $(this).find("#profit_percent_" + product_id).html(profitpercent.toFixed(4));
        //END CALCULATION OF PROFIT

        //CALCULATE SELLING PRICE
        var profit = $(this).find("#profit_amount_" + product_id).html();
        var sellingprice = ((Number(costprice)) + (Number(profit)));
        $(this).find("#sell_price_" + product_id).html(sellingprice.toFixed(4));
        //END OF CALCULATE SELLING PRICE

        var sell_price_for_offer = $(this).find("#sell_price_" + product_id).html();
        var sell_percent = $(this).find("#selling_gst_percent_" + product_id).html();
        var sellgstamt = ((Number(sell_price_for_offer) * (Number(sell_percent))) / (Number(100)));
        $(this).find("#selling_gst_amount_" + product_id).html(sellgstamt.toFixed(4));
        //CALCULATE OFFER PRICE

        var sellgst_for_offer = $(this).find("#selling_gst_amount_" + product_id).html();
        var offer_price = ((Number(sell_price_for_offer)) + (Number(sellgst_for_offer)));
        $(this).find("#offer_price_" + product_id).html(offer_price.toFixed(4));
        $(this).find("#product_mrp_" + product_id).html(offer_price.toFixed(4));


        //END OF CALCULATE OFFER PRICE


    });
}

function discountpercent(obj) {

    var product_id = $(obj).attr('id').split('base_discount_percent_')[1];
    var tbl_row = $(obj).closest('tr').data('id');
    $("#product_detail_record").find("tr[data-id='" + tbl_row + "']").each(function () {

        var freeqty = $(this).find("#free_qty_" + product_id).html();

        var qty = $(this).find("#product_qty_" + product_id).html();
        var total_qty_with = ((Number(freeqty)) + (Number(qty)));
        if (qty == 0) {
            qty = 1;
        }

        //BASE PRICE
        var baseprice = $(this).find("#base_price_" + product_id).html();

        //GET DISCOUNT PERCENTAGE AND CALCULATE DISCOUNT AMOUNT
        var discountpercent = $(this).find("#base_discount_percent_" + product_id).html();
        var discountamount = ((Number(baseprice) * Number(discountpercent)) / Number(100));
        $(this).find("#base_discount_amount_" + product_id).html(discountamount.toFixed(4));
        //MINUS DISCOUNT FROM BASEPRICE AND REGENERATE COST RATE
        var finalcost_rate_afterdiscount = ((Number(baseprice)) - (Number(discountamount)));

        $(this).find("#cost_rate_" + product_id).html(finalcost_rate_afterdiscount.toFixed(4));
        //END OF DISCOUNT BLOCK

        //GET SCHEME PERCENT AND CALCULATE SCHEME AMOUNT
        var cost_dis = $(this).find("#cost_rate_" + product_id).html();
        var schemepercent = $(this).find("#scheme_discount_percent_" + product_id).html();
        var schemeamt = ((Number(schemepercent)) * ((Number(cost_dis))) / Number(100));
        $(this).find("#scheme_discount_amount_" + product_id).html(schemeamt.toFixed(4));

        //MINUS SCHEME FROM COST PRICE AND REASSIGN COST PRICE
        var finalcost_rate_afterscheme = ((Number(cost_dis)) - (Number(schemeamt)));
        $(this).find("#cost_rate_" + product_id).html(finalcost_rate_afterscheme.toFixed(4));
        //END OF SCHEME BLOCK

        //FREE PERCENT AND AMOUNT
        var cost_sch = $(this).find("#cost_rate_" + product_id).html();
        var freeamtbefore = 0;
        if (total_qty_with == 0) {
            freeamtbefore = ((Number(cost_sch)) * (Number(qty)) / ((Number(qty)) + (Number(freeqty))));
        } else {
            freeamtbefore = ((Number(cost_sch)) * (Number(qty)) / ((Number(total_qty_with))));
        }

        var free_amount = ((Number(cost_sch)) - (Number(freeamtbefore)));
        var freepercent = (((Number(free_amount)) * (Number(100))) / (Number(cost_sch)));
        $(this).find("#free_discount_percent_" + product_id).html(freepercent.toFixed(4));
        $(this).find("#free_discount_amount_" + product_id).html(free_amount.toFixed(4));

        var costpriceafterfree = ((Number(cost_sch)) - (Number(free_amount)));


        $(this).find("#cost_rate_" + product_id).html(costpriceafterfree.toFixed(4));


        //CALCULATE COST GST ON COST PRICE AFTERSCHEME
        var cost = $(this).find("#cost_rate_" + product_id).html();
        var costgst = $(this).find("#gst_percent_" + product_id).html();
        var costgstamt = (Number(cost) * Number(costgst) / Number(100));
        $(this).find("#gst_amount_" + product_id).html(costgstamt.toFixed(4));
        //END OF GST COSTGST BLOCK

        //CALCULATE PROFIT AMOUNT
        var cost_price = $(this).find("#cost_rate_" + product_id).html();
        var extracharge = $(this).find("#extra_charge_" + product_id).html();
        var cost_rate = ((Number(cost_price)) + (Number(extracharge))).toFixed(4);
        //var sellingprice = $(this).find("#sell_price_" + product_id).html();
        var profit_percent = $(this).find("#profit_percent_" + product_id).html();
        var profitamt = ((Number(cost_rate)) * (Number(profit_percent)) /(Number(100)));
        //var profitamt = ((Number(sellingprice)) - (Number(cost_rate)));
        $(this).find("#profit_amount_" + product_id).html(profitamt.toFixed(4));
        var profitpercent = ((Number(100)) * (Number(profitamt)) / (Number(cost_rate)));
        //$(this).find("#profit_percent_" + product_id).html(profitpercent.toFixed(4));
        //END CALCULATION OF PROFIT


        //CALCULATE SELLING PRICE
        var profit = $(this).find("#profit_amount_" + product_id).html();
        var sellingprice = ((Number(cost_rate)) + (Number(profit)));
        $(this).find("#sell_price_" + product_id).html(sellingprice.toFixed(4));
        //END OF CALCULATE SELLING PRICE

        var sell_price_for_offer = $(this).find("#sell_price_" + product_id).html();
        var sell_percent = $(this).find("#selling_gst_percent_" + product_id).html();
        var sellgstamt = ((Number(sell_price_for_offer) * (Number(sell_percent))) / (Number(100)));
        $(this).find("#selling_gst_amount_" + product_id).html(sellgstamt.toFixed(4));

        //CALCULATE OFFER PRICE

        var sellgst_for_offer = $(this).find("#selling_gst_amount_" + product_id).html();
        var offer_price = ((Number(sell_price_for_offer)) + (Number(sellgst_for_offer)));
        $(this).find("#offer_price_" + product_id).html(offer_price.toFixed(4));
        $(this).find("#product_mrp_" + product_id).html(offer_price.toFixed(4));



        //END OF CALCULATE OFFER PRICE


        var totalqty = '';
        if (total_qty_with == 0) {
            totalqty = ((Number(freeqty)) + (Number(qty)));
        } else {
            totalqty = total_qty_with;
        }

        var cost_rate_fnl = $(this).find("#cost_rate_" + product_id).html();
        var cost_amt_gst = $(this).find("#gst_amount_" + product_id).html();
        var totalcostval = (((Number(cost_rate_fnl)) + (Number(cost_amt_gst))) * (Number(totalqty)));

        if (total_qty_with != 0 && !isNaN(total_qty_with)) {
            $(this).find("#total_cost_" + product_id).html(totalcostval.toFixed(4));

        }
        totalcalculation();
    });
}

function schemepercent(obj) {
    var tbl_row = $(obj).closest('tr').data('id');
    $("#product_detail_record").find("tr[data-id='" + tbl_row + "']").each(function () {
        var product_id = $(obj).attr('id').split('scheme_discount_percent_')[1];

        var baseprice = $(this).find("#base_price_" + product_id).html();

        var freeqty = $(this).find("#free_qty_" + product_id).html();

        var qty = $(this).find("#product_qty_" + product_id).html();

        var total_qty_with = ((Number(freeqty)) + (Number(qty)));
        if (qty == 0) {
            qty = 1;
        }

        //GET DISCOUNT PERCENTAGE AND CALCULATE DISCOUNT AMOUNT
        var discountpercent = $(this).find("#base_discount_percent_" + product_id).html();
        var discountamount = ((Number(baseprice) * Number(discountpercent)) / Number(Number(100)));
        $(this).find("#base_discount_amount_" + product_id).html(discountamount.toFixed(4));
        //MINUS DISCOUNT FROM BASEPRICE AND REGENERATE COST RATE
        var finalcost_rate_afterdiscount = ((Number(baseprice)) - (Number(discountamount)));
        $(this).find("#cost_rate_" + product_id).html(finalcost_rate_afterdiscount.toFixed(4));
        //END OF DISCOUNT BLOCK

        //SCHEME PERCENT AND AMOUNT
        var cost_rate_after_discount = $(this).find("#cost_rate_" + product_id).html();
        var schemepercent = $(this).find("#scheme_discount_percent_" + product_id).html();
        var schemeamt = ((Number(cost_rate_after_discount)) * (Number(schemepercent)) / Number(100));
        $(this).find("#scheme_discount_amount_" + product_id).html(schemeamt.toFixed(4));
        var finalcostprice = ((Number(cost_rate_after_discount)) - (Number(schemeamt)));
        $(this).find("#cost_rate_" + product_id).html(finalcostprice.toFixed(4));
        //$("#sell_price").html(finalcostprice);


        //FREE PERCENT AND AMOUNT
        var cost_rate_after_scheme = $(this).find("#cost_rate_" + product_id).html();
        var freeamtbefore = 0;

        if (total_qty_with == 0) {
            freeamtbefore = ((Number(cost_rate_after_scheme)) * (Number(qty)) / ((Number(qty)) + (Number(freeqty))));
        } else {

            freeamtbefore = ((Number(cost_rate_after_scheme)) * (Number(qty)) / ((Number(total_qty_with))));
        }

        var free_amount = ((Number(cost_rate_after_scheme)) - (Number(freeamtbefore)));
        var freepercent = (((Number(free_amount)) * (Number(100))) / (Number(cost_rate_after_scheme)));
        $(this).find("#free_discount_percent_" + product_id).html(freepercent.toFixed(4));
        $(this).find("#free_discount_amount_" + product_id).html(free_amount.toFixed(4));
        var costpriceafter_freeamt = ((Number(cost_rate_after_scheme)) - (Number(free_amount)));
        $(this).find("#cost_rate_" + product_id).html(costpriceafter_freeamt.toFixed(4));

        //CALCULATE COST GST ON COST PRICE AFTERSCHEME
        var cost_rate_fn = $(this).find("#cost_rate_" + product_id).html();
        var costgst = $(this).find("#gst_percent_" + product_id).html();
        var costgstamt = (Number(cost_rate_fn) * Number(costgst) / Number(100));
        $(this).find("#gst_amount_" + product_id).html(costgstamt.toFixed(4));
        //END OF GST COSTGST BLOCK

        //CALCULATE PROFIT AMOUNT




        //CALCULATE PROFIT AMOUNT
        var cost_price = $(this).find("#cost_rate_" + product_id).html();
        var extracharge = $(this).find("#extra_charge_" + product_id).html();
        var cost_rate = ((Number(cost_price)) + (Number(extracharge))).toFixed(4);

        var profit_percent = $(this).find("#profit_percent_" + product_id).html();
        var profitamt = ((Number(cost_rate)) * (Number(profit_percent)) /(Number(100)));

        //var profitamt = ((Number(sellingprice)) - (Number(cost_rate)));
        $(this).find("#profit_amount_" + product_id).html(profitamt.toFixed(4));
        var profitpercent = ((Number(100)) * (Number(profitamt)) / (Number(cost_rate)));
        //$(this).find("#profit_percent_" + product_id).html(profitpercent.toFixed(4));
        //END CALCULATION OF PROFIT


        //CALCULATE SELLING PRICE
        var profit = $(this).find("#profit_amount_" + product_id).html();
        var sellingprice = ((Number(cost_rate)) + (Number(profit)));
        $(this).find("#sell_price_" + product_id).html(sellingprice.toFixed(4));
        //END OF CALCULATE SELLING PRICE

        var sell_price_for_offer = $(this).find("#sell_price_" + product_id).html();
        var sell_percent = $(this).find("#selling_gst_percent_" + product_id).html();
        var sellgstamt = ((Number(sell_price_for_offer) * (Number(sell_percent))) / (Number(100)));
        $(this).find("#selling_gst_amount_" + product_id).html(sellgstamt.toFixed(4));
        //CALCULATE OFFER PRICE

        var sellgst_for_offer = $(this).find("#selling_gst_amount_" + product_id).html();
        var offer_price = ((Number(sell_price_for_offer)) + (Number(sellgst_for_offer)));
        $(this).find("#offer_price_" + product_id).html(offer_price.toFixed(4));
        $(this).find("#product_mrp_" + product_id).html(offer_price.toFixed(4));



        //END OF CALCULATE OFFER PRICE

        if (qty == 0) {
            qty = 1;
        }
        var cost_rate_fnl = $(this).find("#cost_rate_" + product_id).html();

        var totalqty = '';
        if (total_qty_with == 0) {
            totalqty = ((Number(freeqty)) + (Number(qty)));
        } else {
            totalqty = total_qty_with;
        }

        var cost_amt_gst = $(this).find("#gst_amount_" + product_id).html();
        var totalcostval = (((Number(cost_rate_fnl)) + (Number(cost_amt_gst))) * (Number(totalqty)));


        if (total_qty_with != 0 && !isNaN(total_qty_with)) {
            $(this).find("#total_cost_" + product_id).html(totalcostval.toFixed(4));

        }

        totalcalculation();
        /*//CALCULATE SELLING GST AMOUNT BASED ON SELLING PRICE AFTER CHNAGE SCHME PERCENTAGE
        var sellinggstper = $("#selling_gst_percent").html();
        var sellingamt = ((Number(sellinggstper)) * (Number(finalcostprice)) /100).toFixed(4);
        $("#selling_gst_amount").html(sellingamt);
        //END OF SELLING GST CALCULATION

        var offerprice = ((Number(finalcostprice)) + (Number(sellingamt))).toFixed(4);
        $("#offer_price").html(finalcostprice);*/
    });
}

function addproductqty(obj) {
    var po_no = $("#po_no").val();
    var product_id = $(obj).attr('id').split('product_qty_')[1];
    var tbl_row = $(obj).closest('tr').data('id');

    setTimeout(function ()
    {
        $("#product_detail_record").find("tr[data-id='" +tbl_row+ "']").each(function ()
        {
            //allow only pending issue qty.and qty not grather than this qty
            if (po_no != '' && po_no != undefined && po_no != 0)
            {
                var max_qty = parseInt($(this).find('#po_qty_' + product_id).val());
                var pending_po_qty = parseInt($(this).find('#pending_po_qty_' + product_id).val());
                var p_qty = parseInt($(this).find("#product_qty_" + product_id).html());

                //if issue po inward that time check qty cant be greater than issue po qty
                if (max_qty != '' && max_qty != 'undefined' && !isNaN(max_qty))
                {
                    if (p_qty > max_qty)
                    {
                        $(this).find("#product_qty_" + product_id).html(0);
                        $(this).find("#po_pending_show_" + product_id).html(max_qty);
                    }
                    else
                    {
                        var pending_qty = (Number(max_qty) - Number(p_qty));
                        $(this).find("#po_pending_show_" + product_id).html(pending_qty);
                    }
                }
                //else check po inward edit.qty cant be grather than pending qty
                else
                    {
                    if (p_qty > pending_po_qty)
                    {
                        $(this).find("#product_qty_" + product_id).html(0);
                        $(this).find("#po_pending_show_" + product_id).html(pending_po_qty);
                        toastr.error(pending_po_qty + " qty is pending in issue po.you can not add qty more than " + pending_po_qty);
                    }
                    else
                    {
                        var pending_issue_qty = (Number(pending_po_qty) - Number(p_qty));
                        $(this).find("#po_pending_show_" + product_id).html(pending_issue_qty);
                    }
                }
            }//end of check po qty
            var max_allow = parseInt($(this).find('#max_allow_qty_' + product_id).val());
            var freeqty = parseInt($(this).find("#free_qty_" + product_id).html());
            var qty = parseInt($(this).find("#product_qty_" + product_id).html());

            var total_qty = (Number(freeqty) + Number(qty));

            //this will check if qty sell or debit that case qty can be grather than or equal to  qty
            if (max_allow != '' && max_allow != 'undefined' && !isNaN(max_allow) || max_allow == 0)
            {
                if (total_qty < max_allow)
                {
                    $(this).find("#product_qty_" + product_id).html(0);
                    $("#addinwardstock").prop('disabled', true);
                    $(this).find("#product_qty_" + product_id).css('border-color', 'red');
                    toastr.error("Max add qty is " + max_allow);
                }
                else
                {
                    /*if(pending_po_qty == 'undefind' || pending_po_qty == '')
                    {*/
                    //pending return qty calculation
                    var edit_pending_qty = (Number(total_qty) - Number(max_allow));
                    $(this).find("#pending_qty_" + product_id).html(edit_pending_qty);
                    $(this).find("#product_qty_" + product_id).css('border-color', '');
                    $("#addinwardstock").prop('disabled', false);
                    /*}*/
                }
            }
            //else allow user to add qty as more they want
            else
            {
                $(this).find("#pending_qty_" + product_id).html(qty);
            }

            var gstamount = $(this).find("#gst_amount_" + product_id).html();

            var total_qty_with = ((Number(freeqty)) + (Number(qty)));

            if (qty == '' || qty == 0 || isNaN(qty))
            {
                qty = 1;
            }

            var totalqty = ((Number(freeqty) + Number(qty)));

            var baseprice = $(this).find("#base_price_" + product_id).html();

            //GET DISCOUNT PERCENTAGE AND CALCULATE DISCOUNT AMOUNT
            var discountpercent = $(this).find("#base_discount_percent_" + product_id).html();
            var discountamount = ((Number(baseprice) * Number(discountpercent)) / 100);
            $(this).find("#base_discount_amount_" + product_id).html(discountamount.toFixed(4));
            //MINUS DISCOUNT FROM BASEPRICE AND REGENERATE COST RATE
            var finalcost_rate_afterdiscount = ((Number(baseprice)) - (Number(discountamount)));
            //END OF DISCOUNT BLOCK

            //SCHEME PERCENT AND AMOUNT
            var schemepercent = $(this).find("#scheme_discount_percent_" + product_id).html();
            var schemeamt = ((Number(finalcost_rate_afterdiscount)) * (Number(schemepercent)) / (Number(100)));
            $(this).find("#scheme_discount_amount_" + product_id).html(schemeamt.toFixed(4));
            var finalcostpriceafterscheme = ((Number(finalcost_rate_afterdiscount)) - (Number(schemeamt)));
            $(this).find("#cost_rate_" + product_id).html(finalcostpriceafterscheme.toFixed(4));


            if (totalqty == 0)
            {
                totalqty = 1;
            }
            var finalcostpriceafter_scheme = $(this).find("#cost_rate_" + product_id).html();


            var freeamtbefore = 0;

            if (total_qty_with == 0 || isNaN(total_qty_with))
            {
                freeamtbefore = ((Number(finalcostpriceafter_scheme)) * (Number(qty)) / ((Number(qty)) + (Number(freeqty))));
            }
            else
            {
                freeamtbefore = ((Number(finalcostpriceafter_scheme)) * (Number(qty)) / ((Number(total_qty_with))));
            }
            var free_amount = ((Number(finalcostpriceafter_scheme)) - (Number(freeamtbefore)));
            if (free_amount == 0)
            {
                $(this).find("#free_discount_percent_" + product_id).html(0);
            }
            else
            {
                var freepercent = (((Number(free_amount)) * (Number(100))) / (Number(finalcostpriceafter_scheme)));
                $(this).find("#free_discount_percent_" + product_id).html(freepercent.toFixed(4));
            }

            //FREE AMOUNT
            $(this).find("#free_discount_amount_" + product_id).html(free_amount.toFixed(4));

            var costpriceafterfree = ((Number(finalcostpriceafter_scheme)) - (Number(free_amount)));

            $(this).find("#cost_rate_" + product_id).html(costpriceafterfree.toFixed(4));


            //calculate gst amount after new cost price
            var costprice_free = $(this).find("#cost_rate_" + product_id).html();
            var gst_percent = $(this).find("#gst_percent_" + product_id).html();

            var gstamt = ((Number(costprice_free)) * (Number(gst_percent)) / (Number(100)));

            $(this).find("#gst_amount_" + product_id).html(gstamt.toFixed(4));

            var gst_amt_cost = $(this).find("#gst_amount_" + product_id).html();

            var totalqty = '';

            if (total_qty_with == 0)
            {
                totalqty = ((Number(freeqty)) + (Number(qty)));
            }
            else
            {
                totalqty = total_qty_with;
            }

            var total_cost = (((Number(costprice_free)) + (Number(gst_amt_cost))) * (Number(totalqty)));


          /*  //CALCULATE PROFIT AMOUNT
            var cost_price = $(this).find("#cost_rate_" + product_id).html();
            var extracharge = $(this).find("#extra_charge_" + product_id).html();
            var cost_rate = ((Number(cost_price)) + (Number(extracharge))).toFixed(4);
            var sellingprice = $(this).find("#sell_price_" + product_id).html();
            var profitamt = ((Number(sellingprice)) - (Number(cost_rate)));
            $(this).find("#profit_amount_" + product_id).html(profitamt.toFixed(4));
            var profitpercent = ((Number(100)) * (Number(profitamt)) / (Number(cost_rate)));
            $(this).find("#profit_percent_" + product_id).html(profitpercent.toFixed(4));
            //END CALCULATION OF PROFIT*/


            //CALCULATE PROFIT AMOUNT
            var cost_price = $(this).find("#cost_rate_" + product_id).html();
            var extracharge = $(this).find("#extra_charge_" + product_id).html();
            var cost_rate = ((Number(cost_price)) + (Number(extracharge))).toFixed(4);

            var profit_percent = $(this).find("#profit_percent_" + product_id).html();
            var profitamt = ((Number(cost_rate)) * (Number(profit_percent)) /(Number(100)));

            //var profitamt = ((Number(sellingprice)) - (Number(cost_rate)));
            $(this).find("#profit_amount_" + product_id).html(profitamt.toFixed(4));
            //var profitpercent = ((Number(100)) * (Number(profitamt)) / (Number(cost_rate)));
            //$(this).find("#profit_percent_" + product_id).html(profitpercent.toFixed(4));
            //END CALCULATION OF PROFIT


            //CALCULATE SELLING PRICE
            var profit = $(this).find("#profit_amount_" + product_id).html();
            var sellingprice = ((Number(cost_rate)) + (Number(profit)));
            $(this).find("#sell_price_" + product_id).html(sellingprice.toFixed(4));



            var sell_price_for_offer = $(this).find("#sell_price_" + product_id).html();
            var sell_percent = $(this).find("#selling_gst_percent_" + product_id).html();
            var sellgstamt = ((Number(sell_price_for_offer) * (Number(sell_percent))) / (Number(100)));
            $(this).find("#selling_gst_amount_" + product_id).html(sellgstamt.toFixed(4));

            //END OF CALCULATE SELLING PRICE



            //CALCULATE OFFER PRICE

            var sellgst_for_offer = $(this).find("#selling_gst_amount_" + product_id).html();
            var offer_price = ((Number(sell_price_for_offer)) + (Number(sellgst_for_offer)));
            $(this).find("#offer_price_" + product_id).html(offer_price.toFixed(4));
            $(this).find("#product_mrp_" + product_id).html(offer_price.toFixed(4));


            //END OF CALCULATE OFFER PRICE



            if (total_qty_with != 0 && !isNaN(total_qty_with) )
            {
                $(this).find("#total_cost_" + product_id).html(total_cost.toFixed(4));
            } else {
                $(this).find("#total_cost_" + product_id).html(0.0000);
            }
            totalcalculation();
        });

    }, 1000);
}

function freeqty(obj) {
    var product_id = $(obj).attr('id').split('free_qty_')[1];
    var tbl_row = $(obj).closest('tr').data('id');
    var po_no = $("#po_no").val();
    setTimeout(function () {
        $("#product_detail_record").find("tr[data-id='" + tbl_row + "']").each(function () {

            var freeqty = $(this).find("#free_qty_" + product_id).html();
            var qty = $(this).find("#product_qty_" + product_id).html();

            var max_allow = parseInt($(this).find('#max_allow_qty_' + product_id).val());

            if (po_no == '') {

                if (max_allow != '' && max_allow != 'undefined' && !isNaN(max_allow) || max_allow == 0) {
                    var total_qty = (Number(freeqty) + Number(qty));
                    if (total_qty < max_allow) {
                        $(this).find("#free_qty_" + product_id).html(0);
                        $("#addinwardstock").prop('disabled', true);
                        $(this).find("#free_qty_" + product_id).css('border-color', 'red');
                        toastr.error(max_allow + " Qty was sell or debit.you can add qty more than or equal to " + max_allow);
                    } else {
                        var edit_pending_qty = (Number(total_qty) - Number(max_allow));
                        $(this).find("#pending_qty_" + product_id).html(edit_pending_qty);
                        $(this).find("#free_qty_" + product_id).css('border-color', '');
                        $("#addinwardstock").prop('disabled', false);
                    }
                } else {
                    $(this).find("#pending_qty_" + product_id).html(qty);
                }
            }

            var total_qty_with = ((Number(freeqty)) + (Number(qty)));
            if (qty == '' || qty == 0 || isNaN(qty)) {
                qty = 1;
            }

            var baseprice = $(this).find("#base_price_" + product_id).html();

            //GET DISCOUNT PERCENTAGE AND CALCULATE DISCOUNT AMOUNT
            var discountpercent = $(this).find("#base_discount_percent_" + product_id).html();
            var discountamount = ((Number(baseprice) * Number(discountpercent)) / 100);
            $(this).find("#base_discount_amount_" + product_id).html(discountamount.toFixed(4));
            //MINUS DISCOUNT FROM BASEPRICE AND REGENERATE COST RATE
            var cost_rate_afterdiscount = ((Number(baseprice)) - (Number(discountamount)));
            //END OF DISCOUNT BLOCK

            //SCHEME PERCENT AND AMOUNT
            var schemepercent = $(this).find("#scheme_discount_percent_" + product_id).html();
            var schemeamt = ((Number(cost_rate_afterdiscount)) * (Number(schemepercent)) / (Number(100)));
            $(this).find("#scheme_discount_amount_" + product_id).html(schemeamt.toFixed(4));
            var costpriceafterscheme = ((Number(cost_rate_afterdiscount)) - (Number(schemeamt)));
            $(this).find("#cost_rate_" + product_id).html(costpriceafterscheme.toFixed(4));

            var costpriceafter_scheme = $(this).find("#cost_rate_" + product_id).html();
            //var freeamtbefore = ((Number(costpriceafter_scheme))*(Number(qty))/((Number(qty))+(Number(freeqty))));

            var freeamtbefore = 0;
            if (total_qty_with == 0) {
                freeamtbefore = ((Number(costpriceafter_scheme)) * (Number(qty)) / ((Number(qty)) + (Number(freeqty))));
            } else {
                freeamtbefore = ((Number(costpriceafter_scheme)) * (Number(qty)) / ((Number(total_qty_with))));
            }

            var free_amount = ((Number(costpriceafter_scheme)) - (Number(freeamtbefore)));
            var freepercent = (((Number(free_amount)) * (Number(100))) / (Number(costpriceafterscheme)));
            $(this).find("#free_discount_amount_" + product_id).html(free_amount.toFixed(4));
            $(this).find("#free_discount_percent_" + product_id).html(freepercent.toFixed(4));

            var costpriceafterfree = ((Number(costpriceafter_scheme)) - (Number(free_amount)));
            $(this).find("#cost_rate_" + product_id).html(costpriceafterfree.toFixed(4));


            //calculate gst amount after new cost price
            var costpriceafter_free = $(this).find("#cost_rate_" + product_id).html();
            var gst_percent = $(this).find("#gst_percent_" + product_id).html();

            var gstamt = ((Number(costpriceafter_free)) * (Number(gst_percent)) / (Number(100)));

            $(this).find("#gst_amount_" + product_id).html(gstamt.toFixed(4));

            var gst_amt_cost = $(this).find("#gst_amount_" + product_id).html();

            var totalqty = '';
            if (total_qty_with == 0) {
                totalqty = ((Number(freeqty)) + (Number(qty)));
            } else {
                totalqty = total_qty_with;
            }

            var total_cost = (((Number(costpriceafter_free)) + (Number(gst_amt_cost))) * (Number(totalqty)));


            /*//CALCULATE PROFIT AMOUNT
            var cost_price = $(this).find("#cost_rate_" + product_id).html();
            var extracharge = $(this).find("#extra_charge_" + product_id).html();
            var cost_rate = ((Number(cost_price)) + (Number(extracharge))).toFixed(4);
            var sellingprice = $(this).find("#sell_price_" + product_id).html();
            var profitamt = ((Number(sellingprice)) - (Number(cost_rate)));
            $(this).find("#profit_amount_" + product_id).html(profitamt.toFixed(4));
            var profitpercent = ((Number(100)) * (Number(profitamt)) / (Number(cost_rate)));
            $(this).find("#profit_percent_" + product_id).html(profitpercent.toFixed(4));
            //END CALCULATION OF PROFIT*/


            //CALCULATE PROFIT AMOUNT
            var cost_price = $(this).find("#cost_rate_" + product_id).html();
            var extracharge = $(this).find("#extra_charge_" + product_id).html();
            var cost_rate = ((Number(cost_price)) + (Number(extracharge))).toFixed(4);

            var profit_percent = $(this).find("#profit_percent_" + product_id).html();
            var profitamt = ((Number(cost_rate)) * (Number(profit_percent)) /(Number(100)));



            //var profitamt = ((Number(sellingprice)) - (Number(cost_rate)));
            $(this).find("#profit_amount_" + product_id).html(profitamt.toFixed(4));
            //var profitpercent = ((Number(100)) * (Number(profitamt)) / (Number(cost_rate)));
            //$(this).find("#profit_percent_" + product_id).html(profitpercent.toFixed(4));
            //END CALCULATION OF PROFIT


            //CALCULATE SELLING PRICE
            var profit = $(this).find("#profit_amount_" + product_id).html();
            var sellingprice = ((Number(cost_rate)) + (Number(profit)));
            $(this).find("#sell_price_" + product_id).html(sellingprice.toFixed(4));
            //END OF CALCULATE SELLING PRICE


            //CALCULATE OFFER PRICE
            var sell_price_for_offer = $(this).find("#sell_price_" + product_id).html();
            var sell_percent = $(this).find("#selling_gst_percent_" + product_id).html();

            var sellgstamt = ((Number(sell_price_for_offer) * (Number(sell_percent))) / (Number(100)));
            $(this).find("#selling_gst_amount_" + product_id).html(sellgstamt.toFixed(4));

            var sellgst_for_offer = $(this).find("#selling_gst_amount_" + product_id).html();
            var offer_price = ((Number(sell_price_for_offer)) + (Number(sellgst_for_offer)));
            $(this).find("#offer_price_" + product_id).html(offer_price.toFixed(4));
            $(this).find("#product_mrp_" + product_id).html(offer_price.toFixed(4));


            //END OF CALCULATE OFFER PRICE



            if (total_qty_with != 0 && !isNaN(total_qty_with)) {
                $(this).find("#total_cost_" + product_id).html(total_cost.toFixed(4));
            }

            totalcalculation();
        });
    }, 1000);
}

function totalcalculation() {
    $("#total_qty").val('');
    $("#gross_total").val('');
    $("#grand_total").val('');

    var totalcost_payment = 0;
    var gstamount = 0;
    var totalcost = 0;
    var qty = 0;
    $("#product_detail_record tr").each(function (index, e) {

        var product_id = $(this).attr('id').split('product_')[1];
        var tbl_row = $(this).data('id');

        $(this).find('td').each(function ()
        {
            if ($(this).attr('id') == "total_cost_" + product_id)
            {
                var totalcostval = $(this).html();
                if ($.isNumeric(totalcostval))
                {
                    totalcost += (Number(totalcostval));
                    totalcost_payment += (Number(totalcostval));
                }
            }
            if ($(this).attr('id') == "product_qty_" + product_id || $(this).attr('id') == "free_qty_" + product_id)
            {
                var totalqty = $(this).html();
                if (totalqty == '')
                {
                    totalqty = 0;
                }
                qty += (parseInt(totalqty));
            }
        });
    });
    var finalcost = (totalcost.toFixed(4));
    var finalcost_payment = (totalcost_payment.toFixed(decimal_points));

    $("#total_qty").val(qty);

    $("#gross_total").val(finalcost);

    $("#gross_total_disp").val(finalcost_payment);

    $("#grand_total").val(finalcost);

    $("#grand_total_disp").val(finalcost_payment);

    $("#paymentmethoddiv").find('.paymentdiv').find('input[type=text]').each(function () {
        $(this).val('');
    });
    //var finalcost_payment = (totalcost.toFixed(0));


    var tblrow = $("#product_detail_record tr").length;

    if(tblrow > 0)
    {
        $("#paymentdiv ").find('input').attr('disabled',false);
    }
    else
    {
        $("#paymentdiv").find('input').attr('disabled',true);
    }

    //$("#cash").val(finalcost_payment);
    $("#outstanding_amount").val(finalcost_payment);
    var out_id = $("#outstanding_amount").data('id');
    $("#outstanding_payment_" + out_id).val(finalcost_payment);

    if(finalcost_payment != '' && finalcost_payment != 0)
    {
        $(".unpaid").show();
    }
    if(finalcost_payment == '' || finalcost_payment == 0)
    {
        $(".unpaid").hide();
    }
}

function offerprice(obj)
{
    var product_id = $(obj).attr('id').split('offer_price_')[1];
    var tbl_row = $(obj).closest('tr').data('id');

    $("#product_detail_record").find("tr[data-id='" + tbl_row + "']").each(function ()
    {

        var offerprice = $(this).find("#offer_price_" + product_id).html();

        var sellinggstpercent = $(this).find("#selling_gst_percent_" + product_id).html();

        //CALCULATE SELLING GST AMOUNT
        var x = ((Number(offerprice)) * (Number(sellinggstpercent)));
        var y = ((Number(100)) + Number(sellinggstpercent));
        var sellgstamt = ((Number(x)) / (Number(y)));
        $(this).find("#selling_gst_amount_" + product_id).html(sellgstamt.toFixed(4));

        //CALCULATE SELLING GST PERCENTAGE
        var sellingprice = ((Number(offerprice) - (Number(sellgstamt))));


        $(this).find("#sell_price_" + product_id).html(sellingprice.toFixed(4));
        var cost_price = $(this).find("#cost_rate_" + product_id).html();
        var extracharge = $(this).find("#extra_charge_" + product_id).html();
        var cost_rate = ((Number(cost_price)) + (Number(extracharge)));


        //CALCULATE PROFIT AMOUNT
        var selling_price = $(this).find("#sell_price_" + product_id).html();
        var profitamt = ((Number(selling_price)) - (Number(cost_rate)));
        $(this).find("#profit_amount_" + product_id).html(profitamt.toFixed(4));
        var profit_amt = $(this).find("#profit_amount_" + product_id).html();
        var profitpercent = ((Number(100)) * (Number(profit_amt)) / (Number(cost_rate)));
        $(this).find("#profit_percent_" + product_id).html(profitpercent.toFixed(4));
        //END CALCULATION OF PROFIT
    });


}

function costgstpercent(obj) {

    var product_id = $(obj).attr('id').split('gst_percent_')[1];
    var tbl_row = $(obj).closest('tr').data('id');
    $("#product_detail_record").find("tr[data-id='" + tbl_row + "']").each(function () {
        var costrate = $(this).find("#cost_rate_" + product_id).html();

        var gstpercent = $(this).find("#gst_percent_" + product_id).html();

        var gstamount = ((Number(costrate)) * (Number(gstpercent)) / Number(100));

        $(this).find("#gst_amount_" + product_id).html(gstamount.toFixed(4));

        var finaltotalcost = ((Number(costrate)) + (Number(gstamount))).toFixed(4);


        var product_qty = $(this).find("#product_qty_" + product_id).html();
        var free_qty = $(this).find("#free_qty_" + product_id).html();

        var total_qty = ((Number(product_qty)) + (Number(free_qty)));


        var totalcostval = ((Number(total_qty)) * (Number(finaltotalcost)));

        if (total_qty != 0) {
            $(this).find("#total_cost_" + product_id).html(totalcostval.toFixed(4));
        }

        totalcalculation();
    });
}

function sellinggstpercent(obj)
{
    var product_id = $(obj).attr('id').split('selling_gst_percent_')[1];
    var tbl_row = $(obj).closest('tr').data('id');
    $("#product_detail_record").find("tr[data-id='" + tbl_row + "']").each(function () {
        var sellinggstpercent = $(this).find("#selling_gst_percent_" + product_id).html();
        var offer_price = $(this).find("#offer_price_" + product_id).html();

        var sellprice = $(this).find("#sell_price_" + product_id).html();

        var sellingamiunt = (((Number(sellprice)) * (Number(sellinggstpercent))) / ((Number(100))));

        $(this).find("#selling_gst_amount_" + product_id).html(sellingamiunt.toFixed(4));

        var selling_amt = $(this).find("#selling_gst_amount_" + product_id).html();
        var offer_price_with_gst = ((Number(sellprice)) + (Number(sellingamiunt)));

        $(this).find("#offer_price_"+product_id).html(offer_price_with_gst.toFixed(4));
        $(this).find("#product_mrp_" + product_id).html(offer_price_with_gst.toFixed(4));

        var sellingprice = $(this).find("#sell_price_" + product_id).html();
        var costprice = $(this).find("#cost_rate_" + product_id).html();

        //CALCULATE PROFIT AMOUNT
        var extracharge = $(this).find("#extra_charge_" + product_id).html();
        var cost_rate = ((Number(costprice)) + (Number(extracharge))).toFixed(4);

        var profit_percent = $(this).find("#profit_percent_" + product_id).html();
        //var profitamt = ((Number(cost_rate)) * (Number(profit_percent)) /(Number(100)));
        var profitamt = ((Number(sellingprice)) - (Number(cost_rate)));
        //$(this).find("#profit_amount_" + product_id).html(profitamt.toFixed(4));
        //var profitpercent = ((Number(100)) * (Number(profitamt)) / (Number(cost_rate)));
        //$(this).find("#profit_percent_" + product_id).html(profitpercent.toFixed(4));
        //END CALCULATION OF PROFIT
    });

}

function removerow(rowcnt) {
    $("#product_detail_record").find("tr[data-id='" + rowcnt + "']").remove();
    //$("#product_"+productid).remove();
    totalcalculation();
}

/*$('#product_detail_record').on('focusout', 'td[contenteditable]', function()
{
    var tdid = $(this).attr('id');

    /!*if(tdid.startsWith('offer_price_'))
    {*!/
        var product_id = $(this).parent('tr').attr('id').split('product_')[1];
        var row_id = $(this).parent('tr').data('id');

        var offer_price = $("#product_detail_record").find('tr[data-id='+row_id+']').find('#offer_price_'+product_id).html();

        var roundval = (offer_price);

        var selling_price  = $("#product_detail_record").find('tr[data-id='+row_id+']').find('#sell_price_'+product_id).html();
        var selling_gst_amt  = $("#product_detail_record").find('tr[data-id='+row_id+']').find('#selling_gst_amount_'+product_id).html();

        var selling_mrp_price = ((Number(selling_price)) + Number(selling_gst_amt));

        var check_point_val = ((Number(roundval)) - Number(selling_mrp_price));

        if(check_point_val >0 || check_point_val < 0)
        {
            $("#product_detail_record").find('tr[data-id='+row_id+']').find('#offer_price_'+product_id).html(roundval);

            offerprice(this);
        }
  /!*  }*!/

});*/




function reverce_on_offer_price()
{

    $("#product_detail_record").find("tr").each(function ()
    {
        var rowcount = $(this).data('id');

        $("#product_detail_record").find("tr[data-id='" + rowcount + "']").each(function (key, keyval) {
            var product_id = $(this).attr('id').split('product_')[1];

            var offerprice = $(this).find("#offer_price_" + product_id).html();
            var roundval = Math.round(offerprice);
            $(this).find("#offer_price_" + product_id).html(roundval);
            $(this).find("#product_mrp_" + product_id).html(roundval);
            var sellinggstpercent = $(this).find("#selling_gst_percent_" + product_id).html();

            //CALCULATE SELLING GST AMOUNT
            var x = ((Number(roundval)) * (Number(sellinggstpercent)));
            var y = ((Number(100)) + Number(sellinggstpercent));
            var sellgstamt = ((Number(x)) / (Number(y)));
            $(this).find("#selling_gst_amount_" + product_id).html(sellgstamt.toFixed(4));


            //CALCULATE SELLING GST PERCENTAGE
            var sellingprice = ((Number(roundval) - (Number(sellgstamt))));
            $(this).find("#sell_price_" + product_id).html(sellingprice.toFixed(4));


            var cost_price = $(this).find("#cost_rate_" + product_id).html();
            var extracharge = $(this).find("#extra_charge_" + product_id).html();

            var cost_rate = ((Number(cost_price)) + (Number(extracharge))).toFixed(4);

            /* //CALCULATE PROFIT AMOUNT
             var profit_percent = $(this).find("#profit_percent_" + product_id).html();
             var profitamt = ((Number(cost_rate)) * (Number(profit_percent)) /(Number(100)));
             //var profitamt = ((Number(sellingprice)) - (Number(cost_rate)));
             $(this).find("#profit_amount_" + product_id).html(profitamt.toFixed(4));
             var profitpercent = ((Number(100)) * (Number(profitamt)) / (Number(cost_rate)));
             $(this).find("#profit_percent_" + product_id).html(profitpercent.toFixed(4));
             //END CALCULATION OF PROFIT*/

            //CALCULATE PROFIT AMOUNT
            var selling_price = $(this).find("#sell_price_" + product_id).html();
            var profitamt = ((Number(selling_price)) - (Number(cost_rate)));
            $(this).find("#profit_amount_" + product_id).html(profitamt.toFixed(4));
            var profitpercent = ((Number(100)) * (Number(profitamt)) / (Number(cost_rate)));
            $(this).find("#profit_percent_" + product_id).html(profitpercent.toFixed(4));
            //END CALCULATION OF PROFIT
        });

    });

    $("#addinwardstock").attr('disabled',false);
    toastr.success("Now you can save inward!");
}

//LOGIC OF THIS FILE
//PROFIT PERCENT STAY CONSTANT..UPDATE ROUNDOFF OFFER PRICE






