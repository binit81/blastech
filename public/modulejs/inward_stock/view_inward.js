

function viewinward(inwardid)
{

    $("#inward_popup_record").trigger('reset');
    $("#payment_div").empty();
    $("#view_inward_record").html('');
    $("#inward_payment_details").empty();
    $('#vieewpop tfoot').html('');
    $("#edit_inword_stock_in_popup").attr('onClick','');
    var  url = "view_inward_detail";
    var type = "POST";

    var data = {
        'inward_stock_id' : inwardid,
    };

    callroute(url,type,data,function (data)
    {
        var dta = JSON.parse(data);

        if(dta['Success'] == "True")
        {
            $("#view_inward_record").html('');
            $('#vieewpop tfoot').html('');
            $('#inward_payment_details').html('');
            var final_data = JSON.parse(dta['Data']);

            var dataval = final_data;

            $("#viewinwardpopup").modal('show');

            $("#edit_inword_stock_in_popup").attr("onClick","edit_inwardstock('"+inwardid+"','"+dataval['inward_type']+"')");


            if(typeof dataval['supplier_gstdetail'] != 'undefined' && dataval['supplier_gstdetail'] != '') {
                if (dataval['supplier_gstdetail']['supplier_gstin'] != '' && dataval['supplier_gstdetail']['supplier_gstin'] != undefined) {
                    $(".supplier_gstin").html(dataval['supplier_gstdetail']['supplier_gstin']);
                }

                var supplier_company_info = dataval['supplier_gstdetail']['supplier_company_info'];

                if (supplier_company_info != '' && supplier_company_info != undefined) {
                    var supplier_first_name = supplier_company_info['supplier_first_name'];
                    var supplier_last_name = '';

                    if (supplier_company_info['supplier_last_name'] != '' && supplier_company_info['supplier_last_name'] != null) {
                        supplier_last_name = supplier_company_info['supplier_last_name'];
                    }
                    $(".supplier_name").html(supplier_first_name + '' + supplier_last_name);
                }
            }

            $(".invoice_no_popup").html(dataval['invoice_no']);
            $(".inward_date_popup").html(dataval['inward_date']);

            if(typeof dataval['supplier_payment_details'] != "undefined" && dataval['supplier_payment_details'] != '')
            {

                var payment_html = '';
                var currency_symbol = '&#x20b9';
                if(tax_type == 1)
                {
                    currency_symbol = currency_title;
                }
                $.each(dataval['supplier_payment_details'],function(paymentkey,paymentvalue)
                {
                    var payment_method_name = '';

                    if(typeof paymentvalue != 'undefined' && paymentvalue['payment_method '] != '')
                    {
                         payment_method_name = paymentvalue['payment_method'][0]['payment_method_name'];
                    }

                    var payment_amt = Number(paymentvalue['amount']).toFixed(2);
                    payment_html += '<tr>' +
                        '<td style="text-align:right !important;font-size:14px !important;" class="text-dark font-weight-600">'+payment_method_name+'</td>' +
                        '<td class="font-weight-600">&nbsp;:&nbsp;</td>' +
                        '<td style="text-align:right !important;font-size:14px !important;" class="text-dark font-weight-600">'+currency_symbol+' '+payment_amt+'</td>' +
                        '</tr>';

                });

                $("#inward_payment_details").append(payment_html);
            }

            if(dataval['total_qty'] != '')
            {
                $("#total_qty").html(dataval['total_qty']);
            }
            if(dataval['total_gross'] != '')
            {
                $("#total_gross").html(dataval['total_gross']);
            }
            if(dataval['total_grand_amount'] != '')
            {
                $("#total_grand_amount").html(dataval['total_grand_amount']);
            }
            if(dataval['total_grand_amount'] != '')
            {
                $("#total_grand_amount").html(dataval['total_grand_amount']);
            }


            $(".invoiceno").html(dataval['invoice_no']);



            if(typeof dataval['inward_product_detail'] != 'undefined' && dataval['inward_product_detail'] != '' )
            {
                var product_detail_record = dataval['inward_product_detail'];
                var product_html = '';


                var base_price_total = 0;
                var igst_total = 0;
                var cgst_total = 0;
                var sgst_total = 0;
                var profit_percent_total = 0;
                var profit_amt_total = 0;
                var selling_price_total = 0;
                var offer_price_total = 0;
                var mrp_price_total = 0;
                var qty_total = 0;
                var cost_total = 0;

                var total_base_discount_percent =0;
                var total_base_discount_amount =0;
                var total_scheme_discount_percent =0;
                var total_scheme_discount_amount =0;
                var total_free_discount_percent =0;
                var total_free_discount_amount =0;
                var total_cost_rate =0;
                var free_qty_total =0;


                var colspan = 3;
                if(dataval['inward_type'] == 2)
                {
                    colspan = 2;

                }

                $.each(product_detail_record,function (key,value)
                {
                    var product_detail = '';
                    if(value['product_detail'] != '' && value['product_detail'] != 'undefined')
                    {
                         product_detail = value['product_detail'];
                    }
                    var product_system_barcode = '';
                    var product_name = '';
                    var hsn_sac_code = '';
                    if(product_detail != '' && product_detail['product_system_barcode'] != '' && product_detail['product_system_barcode'] != null)
                    {
                         product_system_barcode = product_detail['product_system_barcode'];
                    }
                    if(product_detail != '' && product_detail['product_name'] != '' && product_detail['product_name'] != null)
                    {
                        product_name = product_detail['product_name'];
                    } if(product_detail != '' && product_detail['hsn_sac_code'] != '' && product_detail['hsn_sac_code'] != null)
                    {
                        hsn_sac_code = product_detail['hsn_sac_code'];
                    }


                    var barcode = '';
                    if(product_detail != '' && product_detail['supplier_barcode'] != " " && product_detail['supplier_barcode'] != null)
                    {
                        barcode = product_detail['supplier_barcode'];
                    }
                    else {

                        barcode = product_detail['product_system_barcode'];
                    }

                    var batch_no = '';
                    var base_price = 0;
                    var base_discount_percent = 0;
                    var base_discount_amount = 0;
                    var scheme_discount_percent = 0;
                    var scheme_discount_amount = 0;
                    var free_discount_percent = 0;
                    var free_discount_amount = 0;
                    var cost_rate = 0;
                    var profit_percent = 0;
                    var profit_amount = 0;
                    var offer_price = 0;
                    var product_mrp = 0;
                    var product_qty = 0;
                    var free_qty = 0;
                    var mfg_date = '';
                    var expiry_date = '';
                    var total_gross = 0;
                    var cost_igst_amount = 0;
                    var cost_cgst_amount = 0;
                    var cost_sgst_amount = 0;
                    var sell_price =0;
                    var cost_last =0;


                  if(value['batch_no'] != '' && value['batch_no'] != null)
                  {
                      batch_no = value['batch_no'];
                  }
                  if(value['base_price'] != '')
                  {
                      base_price = value['base_price'];
                  }
                  if(value['base_discount_percent'] != '')
                  {
                      base_discount_percent = value['base_discount_percent'];
                  }
                  if(value['base_discount_amount'] != '')
                  {
                      base_discount_amount = value['base_discount_amount'];
                  }
                  if(value['scheme_discount_percent'] != '')
                  {
                      scheme_discount_percent = value['scheme_discount_percent'];
                  }
                  if(value['scheme_discount_amount'] != '')
                  {
                      scheme_discount_amount = value['scheme_discount_amount'];
                  }if(value['free_discount_percent'] != '')
                  {
                      free_discount_percent = value['free_discount_percent'];

                  }
                  if(value['free_discount_amount'] != '')
                  {
                      free_discount_amount = Number(value['free_discount_amount']);
                  }
                  if(value['cost_rate'] != '')
                  {
                      cost_rate = value['cost_rate'];
                  }
                  if(value['profit_percent'] != '')
                  {
                      profit_percent = value['profit_percent'];
                  }
                  if(value['profit_amount'] != '')
                  {
                      profit_amount = value['profit_amount'];

                  }if(value['offer_price'] != '')
                  {
                      offer_price = value['offer_price'];
                  }if(value['product_mrp'] != '')
                  {
                      product_mrp = value['product_mrp'];
                  }if(value['product_qty'] != '')
                  {
                      product_qty = value['product_qty'];
                  }if(value['free_qty'] != '')
                  {
                      free_qty = value['free_qty'];
                  }if(value['mfg_date'] != '' && value['mfg_date'] != null)
                  {
                      mfg_date = value['mfg_date'];
                  }if(value['expiry_date'] != '' && value['expiry_date'] != null)
                  {
                      expiry_date = value['expiry_date'];
                  }if(value['total_gross'] != '')
                  {
                      total_gross = value['total_gross'];
                  }if(value['cost_igst_amount'] != '')
                  {
                      cost_igst_amount = value['cost_igst_amount'];
                  }if(value['cost_cgst_amount'] != '')
                  {
                      cost_cgst_amount = value['cost_cgst_amount'];
                  }if(value['cost_sgst_amount'] != '')
                  {
                      cost_sgst_amount = value['cost_sgst_amount'];
                  }if(value['sell_price'] != '')
                  {
                      sell_price = value['sell_price'];
                  }if(value['total_cost'] != '')
                  {
                      cost_last = value['total_cost'];
                  }

                    base_price_total += base_price;
                    igst_total += cost_igst_amount;
                    cgst_total += cost_cgst_amount;
                    sgst_total += cost_sgst_amount;
                    profit_percent_total += profit_percent;
                    profit_amt_total += profit_amount;
                    selling_price_total += sell_price;
                    offer_price_total += offer_price;
                    mrp_price_total += product_mrp;
                    qty_total += product_qty;
                    cost_total += cost_last;
                    total_base_discount_percent += base_discount_percent;
                    total_base_discount_amount += base_discount_amount;
                    total_scheme_discount_percent += scheme_discount_percent;
                    total_scheme_discount_amount += scheme_discount_amount;
                    total_free_discount_percent += free_discount_percent;
                    total_free_discount_amount += free_discount_amount;
                    total_cost_rate += cost_last;
                    free_qty_total += free_qty;

                  var total_qty = ((Number(product_qty)) + (Number(free_qty)));
                  var total_cost = ((Number(value['cost_price'])) * (Number(total_qty)));

                    product_html += '<tr id="'+value['product_id']+'"> ';
                    product_html += '<td class="leftAlign text-dark font-weight-600">'+barcode+'</td>' ;
                    product_html += '<td class="leftAlign text-dark font-weight-600">'+product_name+'</td>';
                    product_html += '<td class="leftAlign text-dark font-weight-600">'+hsn_sac_code+'</td>';
                    product_html += '<td class="leftAlign text-dark font-weight-600 garment_case_hide">'+batch_no+'</td>';
                    product_html += '<td class="rightAlign text-dark font-weight-600">'+base_price+'</td>';
                    product_html += '<td class="rightAlign text-dark font-weight-600 garment_case_hide">'+base_discount_percent+'</td>';
                    product_html += '<td class="rightAlign text-dark font-weight-600 garment_case_hide">'+base_discount_amount+'</td>';
                    product_html += '<td class="rightAlign text-dark font-weight-600 garment_case_hide">'+scheme_discount_percent+'</td>';
                    product_html += '<td class="rightAlign text-dark font-weight-600 garment_case_hide">'+scheme_discount_amount+'</td>';
                    product_html += '<td class="rightAlign text-dark font-weight-600 garment_case_hide">'+free_discount_percent+'</td>';
                    product_html += '<td class="rightAlign text-dark font-weight-600 garment_case_hide">'+free_discount_amount+'</td>';
                    product_html += '<td class="rightAlign text-dark font-weight-600 garment_case_hide">'+cost_rate+'</td>';

                        if(tax_type == 1){
                            product_html +=   '<td class="rightAlign text-dark font-weight-600">' + cost_igst_amount + '</td>';
                        }else {
                            product_html +=   '<td class="rightAlign text-dark font-weight-600">' + cost_igst_amount + '</td>';
                            product_html +=  '<td class="rightAlign text-dark font-weight-600">' + cost_cgst_amount + '</td>';
                            product_html += '<td class="rightAlign text-dark font-weight-600">' + cost_sgst_amount + '</td>';
                        }
                    product_html +='<td class="rightAlign text-dark font-weight-600">'+profit_percent+'</td>' ;
                        product_html +='<td class="rightAlign text-dark font-weight-600">'+profit_amount+'</td>' ;
                        product_html +='<td class="rightAlign text-dark font-weight-600">'+sell_price+'</td>' ;
                        product_html +='<td class="rightAlign text-dark font-weight-600">'+offer_price+'</td>' ;
                        product_html +='<td class="rightAlign text-dark font-weight-600">'+product_mrp+'</td>' ;
                        product_html +='<td class="rightAlign text-dark font-weight-600">'+product_qty+'</td>' ;
                        product_html +='<td class="rightAlign text-dark font-weight-600 garment_case_hide">'+free_qty+'</td>' ;
                        product_html +='<td class="leftAlign text-dark font-weight-600 garment_case_hide">'+mfg_date+'</td>' ;
                        product_html +='<td class="leftAlign text-dark font-weight-600 garment_case_hide">'+expiry_date+'</td> ';
                        product_html +='<td class="rightAlign text-dark font-weight-600">'+cost_last+'</td>' ;
                        product_html +='</tr>';
                });
                total_base_discount_percent.toFixed(2);
                total_base_discount_amount.toFixed(2);
                total_scheme_discount_percent.toFixed(2);
                total_scheme_discount_amount.toFixed(2);
                total_free_discount_percent.toFixed(2);
                total_free_discount_amount.toFixed(2);
                igst_total.toFixed(2);
                total_cost_rate.toFixed(2);
                igst_total.toFixed(2);
                cgst_total.toFixed(2);
                sgst_total.toFixed(2);
                profit_percent_total.toFixed(2);
                profit_amt_total.toFixed(2);
                selling_price_total.toFixed(2);
                offer_price_total.toFixed(2);
                mrp_price_total.toFixed(2);

                if(total_base_discount_percent == 0)
                {
                    total_base_discount_percent = '';
                }if(total_base_discount_amount == 0)
                {
                    total_base_discount_amount = '';
                }if(total_scheme_discount_percent == 0)
                {
                    total_scheme_discount_percent = '';
                }if(total_scheme_discount_amount == 0)
                {
                    total_scheme_discount_amount = '';
                }if(total_free_discount_percent == 0)
                {
                    total_free_discount_percent = '';
                }if(total_free_discount_amount == 0)
                {
                    total_free_discount_amount = '';
                }if(total_cost_rate == 0)
                {
                    total_cost_rate = '';
                }if(igst_total == 0)
                {
                    igst_total = '';
                }if(cgst_total == 0)
                {
                    cgst_total = '';
                }if(sgst_total == 0)
                {
                    sgst_total = '';
                }if(profit_percent_total == 0)
                {
                    profit_percent_total = '';
                }if(profit_amt_total == 0)
                {
                    profit_amt_total = '';
                }if(selling_price_total == 0)
                {
                    selling_price_total = '';
                }if(offer_price_total == 0)
                {
                    offer_price_total = '';
                }if(mrp_price_total == 0)
                {
                    mrp_price_total = '';
                }

                var footer_html = '<tfoot id="footer_view_inward" style="border-bottom:1px solid #C0C0C0 !important;border-top:1px solid #C0C0C0 !important;">\n';
                footer_html += '<tr>' ;
                    footer_html +='<th colspan="'+colspan+'" class="text-dark font-14 font-weight-600"></th>' ;
                    footer_html +='<th class="text-left text-dark font-14 font-weight-600">Total</th>' ;
                    footer_html +='<th class="text-right text-dark font-14 font-weight-600">'+base_price_total+'</th>' ;
                    footer_html +='<th class="text-right text-dark font-14 font-weight-600 garment_case_hide">'+total_base_discount_percent+'</th>' ;
                    footer_html +='<th class="text-right text-dark font-14 font-weight-600 garment_case_hide">'+total_base_discount_amount+'</th>' ;
                    footer_html +='<th class="text-right text-dark font-14 font-weight-600 garment_case_hide">'+total_scheme_discount_percent+'</th>' ;
                    footer_html +='<th class="text-right text-dark font-14 font-weight-600 garment_case_hide">'+total_scheme_discount_amount+'</th>' ;
                    footer_html +='<th class="text-right text-dark font-14 font-weight-600 garment_case_hide">'+total_free_discount_percent+'</th>' ;
                    footer_html +='<th class="text-right text-dark font-14 font-weight-600 garment_case_hide">'+total_free_discount_amount+'</th>' ;
                    footer_html +='<th class="text-right text-dark font-14 font-weight-600 garment_case_hide">'+total_cost_rate+'</th>' ;
                    if(tax_type == 1)
                    {
                        footer_html +='<th class="text-right text-dark font-14 font-weight-600">' + igst_total + '</th>';
                    }
                    else {
                        footer_html +='<th class="text-right text-dark font-14 font-weight-600">' + igst_total + '</th>';
                        footer_html +='<th class="text-right text-dark font-14 font-weight-600">' + cgst_total + '</th>';
                        footer_html +='<th class="text-right text-dark font-14 font-weight-600">' + sgst_total + '</th>';
                    }
                    footer_html +='<th class="text-right text-dark font-14 font-weight-600">'+profit_percent_total+'</th>' ;
                    footer_html +='<th class="text-right text-dark font-14 font-weight-600">'+profit_amt_total+'</th>' ;
                    footer_html +='<th class="text-right text-dark font-14 font-weight-600">'+selling_price_total+'</th>' ;
                    footer_html +='<th class="text-right text-dark font-14 font-weight-600">'+offer_price_total+'</th>' ;
                    footer_html +='<th class="text-right text-dark font-14 font-weight-600">'+mrp_price_total+'</th>' ;
                    footer_html +='<th class="text-right text-dark font-14 font-weight-600">'+qty_total+'</th>' ;
                    footer_html +='<th class="text-right text-dark font-14 font-weight-600 garment_case_hide">'+free_qty_total+'</th>' ;
                    footer_html +='<th class="text-right text-dark font-14 font-weight-600 garment_case_hide"></th>' ;
                    footer_html +='<th class="text-right text-dark font-14 font-weight-600 garment_case_hide"></th>' ;
                    footer_html +='<th class="text-right text-dark font-18 font-weight-600">'+currency_symbol+' <span id="grandtotal">'+total_cost_rate.toFixed(2)+'</span></th>' ;
                    footer_html +='</tr>';
                    footer_html +='</tfoot>';
                $("#view_inward_record").append(product_html);
                $("#view_inward_record").after(footer_html);
            }

            if(dataval['inward_type'] == 1)
            {
                $(".garment_case_hide").show();
            }
            else
            {
                $(".garment_case_hide").hide();
            }
        }
        else
        {

        }
    })
}

function edit_inwardstock(stockid,inward_type)
{
    var  url = "edit_inward_stock";
    var type = "POST";
    var data = {
        'inward_stock_id' : stockid,
        'inward_type' : inward_type,
    };
    callroute(url,type,data,function (data) {
        var dta = JSON.parse(data);

        if (dta['Success'] == "True")
        {
            var url = '';
            if(dta['url'] != '' && dta['url'] != 'undefined')
            {
                 url = dta['url'];
            }
           localStorage.setItem('edit_inward_stock_record',JSON.stringify(dta['Data']));

            window.location.href = url;
        }
    });
}

function delete_inwardstock(stock_id)
{
    if(confirm("Are You Sure want to delete this inward?")) {
        var url = "delete_inward_stock";
        var type = "POST";
        var data = {
            'inward_stock_id': stock_id,
        };
        callroute(url, type, data, function (data) {
            var dta = JSON.parse(data);

            console.log(dta);
            if (dta['Success'] == "True")
            {
                toastr.success(dta['Message']);
                resettable('inward_fetch_data','viewinwardrecord');

            } else {
                toastr.error(dta['Message']);
            }
        });
    }else
    {
        return false;
    }
}

// $("#filer_from_to").daterangepicker().val('');

function resetinwardfilterdata()
{
    $("#filer_from_to").val('');
    $("#invoice_no_filter").val('');
    $("#supplier_name").val('');
    $("#supplier_id").val('');
    resettable('inward_fetch_data','viewinwardrecord');
}

$(document).on('click', '#inward_stock_export', function(){

    var filter_date = $('#filer_from_to').val();

    var from_date = '';
    var to_date = '';

    var separate_date = filter_date.split(' - ');
    if(separate_date[0] != undefined)
    {
        from_date = separate_date[0];
    }

    if(separate_date[1] != undefined)
    {
        to_date = separate_date[1];
    }
    var query = {
        from_date: from_date,
        to_date : to_date,
        invoice_no : $("#invoice_no_filter").val(),
        supplier_name : $("#supplier_id").val()
    };


    var url = "inward_stock_export?" + $.param(query)
    window.open(url,'_blank');


});



$("#supplier_name").keyup(function ()
{
    jQuery.noConflict();
    $(this).autocomplete({
        autoFocus: true,
        minLength: 1,
        source: function (request, response)
        {
            var url = "supplier_search";
            var type = "POST";
            var data = {
                'search_val': $("#supplier_name").val()
            };
            callroute(url, type, data, function (data)
            {
                var searchsupplier = JSON.parse(data, true);

                if (searchsupplier['Success'] == "True")
                {
                    var supplier_detail = searchsupplier['Data'];

                    if (supplier_detail.length > 0)
                    {
                        var resultsupplier = [];

                        supplier_detail.forEach(function (value)
                        {
                            if(value.supplier_gst.length > 0)
                            {
                                $.each(value.supplier_gst,function (supplierkey,suppliervalue)
                                {
                                    var last_name = '';
                                    if(value.supplier_last_name != '' && value.supplier_last_name != null)
                                    {
                                        last_name = value.supplier_last_name;
                                    }
                                    else
                                    {
                                        last_name = '';
                                    }
                                    resultsupplier.push({
                                        label: value.supplier_first_name + ' ' + last_name + '_' + suppliervalue.supplier_gstin,
                                        value: value.supplier_first_name + ' ' + last_name + '_' + suppliervalue.supplier_gstin,
                                        supplier_gst_id: suppliervalue.supplier_gst_id,
                                    });
                                });
                            }
                            else
                            {
                                resultsupplier.push({
                                    label: value.supplier_first_name + ' ' + last_name ,
                                    value: value.supplier_first_name + ' ' + last_name ,
                                    supplier_gst_id: '',
                                });
                            }

                        });
                        //push data into result array.and this array used for display suggetion
                        response(resultsupplier);
                    }
                }
            });
        }, //this help to call a function when select search suggetion
        select: function (event, ui)
        {
            var gst_id = ui.item.supplier_gst_id;

            $("#supplier_id").val(gst_id);
            $(".ui-helper-hidden-accessible").css('display','none');
            //call a function to perform action on select of supplier
        }
    })
});


$('#previousinvoiceno').click(function(e){


    var billno                   =     $('#fetchedbillno').val();
    var maxid                    =     $('#maxid').val();
    var minid                    =     $('#minid').val();

    if(Number(billno) == Number(minid))
    {
        $('#previousinvoice').prop('disabled', true);
        return false;
    }
    else
    {
        $('#nextinvoice').prop('disabled', false);
        $('#previousinvoice').prop('disabled', false);
        var url                       =     'previous_invoice';

        $.ajax({
            url:url,
            data: {
                billno:billno,
            },
            success:function(data)
            {
                $('.popup_values').html('');
                $('.popup_values').html(data);
            }
        })
    }
});

$('#nextinvoiceno').click(function(e){

    var billno                   =     $('#fetchedbillno').val();
    var maxid                    =     $('#maxid').val();
    var minid                    =     $('#minid').val();

    if(Number(billno) == Number(maxid))
    {
        $('#nextinvoice').prop('disabled', true);
        return false;
    }
    else
    {
        $('#nextinvoice').prop('disabled', false);
        $('#previousinvoice').prop('disabled', false);
        var url                       =     'next_invoice';

        $.ajax({
            url:url,

            data: {

                billno:billno,

            },
            success:function(data)
            {

                $('.popup_values').html('');
                $('.popup_values').html(data);


            }
        })
    }

});


$('#searchCollapse').click(function (e) {
    $('#searchBox').slideToggle();
    resetinwardfilterdata();
});