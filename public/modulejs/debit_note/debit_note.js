//set datepicker to debit_date
$('#debit_date').datepicker({
    autoclose: true,
    format: "dd-mm-yyyy",
    immediateUpdates: true,
    todayBtn: true,
    orientation: "bottom",
    todayHighlight: true
}).datepicker("setDate", "0");
//end of datepicker


//this is used for display invoice no suggestion
$("#invoice_no").keyup(function ()
{
    if($(this).val() == '')
    {
        $("#inward_stock_id").val('');
        $("#gst_id").val('');
        $("#supplier_name").val('');
        $("#supplier_company").val('');
        $("#invoice_product_val").val();
        $("#product_search_block").css('display','none');
        $("#debit_product_detail_record").empty();
        $(".fmcg").show();
    }
    jQuery.noConflict();
    $(this).autocomplete({
        autoFocus: true,
        minLength: 1,
        source: function (request, response)
        {
            var url = "invoice_no_search";
            var type = "POST";
            var data = {
                'search_val': $("#invoice_no").val()
            };
            callroute(url, type, data, function (data)
            {
                var searchinvoice = JSON.parse(data, true);

                if (searchinvoice['Success'] == "True")
                {
                    var invoice_detail = searchinvoice['Data'];

                    if (invoice_detail.length > 0)
                    {
                        var resultinvoice = [];
                        var value = invoice_detail[0];
                        if(value['inward_type'] == 2)
                        {
                            $(".fmcg").hide();
                            fmcg_inward = 0;
                        }
                        else
                        {
                            $(".fmcg").show();
                            fmcg_inward = 1;
                        }


                        invoice_detail.forEach(function (invoicevalue)
                        {
                            var last_name = '';
                           if(invoicevalue.supplier_gstdetail.supplier_company_info.supplier_last_name == null)
                           {
                               last_name = '';
                           }
                           else
                           {
                               last_name = invoicevalue.supplier_gstdetail.supplier_company_info.supplier_last_name;
                           }
                           resultinvoice.push({
                                    label: invoicevalue.invoice_no+'_'+invoicevalue.supplier_gstdetail.supplier_company_info.supplier_company_name,
                                    value: invoicevalue.invoice_no,
                                    inward_stock_id: invoicevalue.inward_stock_id,
                                    supplier_gst_id: invoicevalue.supplier_gst_id,
                                    supplier_company_name: invoicevalue.supplier_gstdetail.supplier_company_info.supplier_company_name,
                                    supplier_name: invoicevalue.supplier_gstdetail.supplier_company_info.supplier_first_name +''+ last_name,
                                    invoice_product : invoicevalue.product_val,
                                    supplier_state_id : invoicevalue.supplier_gstdetail.state_id,
                                    inward_type : invoicevalue.inward_type,
                                });
                        });
                        //push data into result array.and this array used for display suggetion
                        response(resultinvoice);
                    }
                }
            });
        }, //this help to call a function when select search suggetion
        select: function (event, ui)
        {
            var gst_id = ui.item.supplier_gst_id;
            var inward_stock_id = ui.item.inward_stock_id;
            $("#gst_id").val(gst_id);
            $("#inward_stock_id").val(inward_stock_id);
            $("#supplier_name").val(ui.item.supplier_name);
            $("#supplier_company").val(ui.item.supplier_company_name);
            $("#invoice_product_val").val(ui.item.invoice_product);
            $("#supplier_state_id").val(ui.item.supplier_state_id);
            $(".ui-helper-hidden-accessible").css('display','none');
            $("#product_search_block").css('display','block');
            $("#inward_type").val(ui.item.inward_type);
            //call a function to perform action on select of supplier
        }
    })
});


$("#debit_productsearch").keyup(function ()
{
    jQuery.noConflict();
    $(this).autocomplete({
        autoFocus: true,
        minLength: 1,
        source: function (request, response)
        {
            var url = "debit_productsearch";
            var type = "POST";
            var pro = [
                $("#invoice_product_val").val()
            ];
            var data = {
                'search_val' : $("#debit_productsearch").val(),
                'invoice_product' :pro[0]
            };

            callroute(url,type,data,function (data)
            {
                var searchdata = JSON.parse(data,true);

                if(searchdata['Success'] == "True")
                {
                    var result = [];
                    searchdata['Data'].forEach(function (value)
                    {
                        var display_barcode = '';
                        if(value['price_master'] !='')
                        {
                            $.each(value['price_master'],function (key,val)
                            {

                               /* if(val['inward_stock_id'] == $("#inward_stock_id").val())
                                {*/
                                    var batch_no = '';
                                    if (val['batch_no'] != '' && val['batch_no'] != null)
                                    {
                                        batch_no = '_' + val['batch_no'];
                                    }
                                    if (value.supplier_barcode != " " && value.supplier_barcode != undefined)
                                    {
                                        display_barcode = value.supplier_barcode;
                                    }
                                    else
                                    {
                                        display_barcode = value.product_system_barcode;
                                    }

                                    if (display_barcode != undefined)
                                    {
                                        result.push({
                                            label: value.product_name + '_' + display_barcode + batch_no,
                                            value: value.product_name + '_' + display_barcode + batch_no,
                                            id: value.product_id,
                                            batch_no: batch_no,
                                        });
                                    }
                               /* }*/
                            });
                        }
                    });
                    //push data into result array.and this array used for display suggetion
                    response(result);
                }
            });
        },
        //this help to call a function when select search suggetion
        select: function(event,ui)
        {
            var id = ui.item.id;
            var batch_no = ui.item.batch_no;
            var btch_no = batch_no.replace('_','');
            //call a getproductdetail function for getting product detail based on selected product from suggetion
           getinwardproduct(id,btch_no);

            $(".ui-helper-hidden-accessible").css('display','none');
        },
    });


});


//function for getting product detail based on product suggestion
function getinwardproduct(productid,batch_no)
{
    var type = "POST";
    var url = 'inward_productdetail';
    var data = {
        "product_id" : productid,
        "inward_stock_id" : $("#inward_stock_id").val(),
        "batch_no" : batch_no
    }
    callroute(url,type,data,function(data)
    {
        var inward_product_data = JSON.parse(data,true);

        if(inward_product_data['Success'] == "True")
        {
            var product_html = '';
            var product_detail  = inward_product_data['Data'][0]['product'];
            var inward_detail  = inward_product_data['Data'][0];

            var rowCount = $('#debit_product_detail_record tr').length;
            rowCount++;

            if (product_detail['hsn_sac_code'] == '' || product_detail['hsn_sac_code'] == null)
            {
                product_detail['hsn_sac_code'] = '';
            }
            if (inward_detail['batch_no'] == '' || inward_detail['batch_no'] == null)
            {
                inward_detail['batch_no'] = '';
            }

            var barcode = '';
            if (product_detail['supplier_barcode'] != " " && product_detail['supplier_barcode'] != null)
            {
                barcode = product_detail['supplier_barcode'];
            }
            else
            {
                barcode = product_detail['product_system_barcode'];
            }

            var gst_percent = ((Number(inward_detail['cost_igst_percent'])) + (Number(inward_detail['cost_cgst_percent'])) + (Number(inward_detail['cost_sgst_percent'])));
            var gst_amount = ((Number(inward_detail['cost_igst_amount'])) + (Number(inward_detail['cost_cgst_amount'])) + (Number(inward_detail['cost_sgst_amount'])));
            var product_id = inward_detail['product_id'];
            var samerow = 0;

            $("#debit_product_detail_record tr").each(function()
            {
                var row_product_id = $(this).attr('id').split('_')[1];
                if(row_product_id == product_id)
                {
                    var samerowupdate = 0;
                    if($("#inward_type").val() == 2)
                    {
                        samerowupdate = 1;
                    }
                    else
                    {
                        if(inward_detail['batch_no'] == $("#batch_no_"+product_id).html()) {
                            samerowupdate = 1;
                        }
                    }
                        if(samerowupdate == 1){
                        var qty = $("#return_qty_" + product_id).html();
                        var return_qty = ((Number(qty)) + (Number(1)));
                        $("#return_qty_" + product_id).html(return_qty);
                        samerow = 1;

                        var costrate = $("#cost_rate_" + product_id).html();

                        var total_cost_without_gst = ((Number(costrate)) * (Number(return_qty)));

                        $("#total_cost_rate_" + product_id).html(total_cost_without_gst.toFixed(4));

                        var gst_amount = $("#cost_gst_amount_" + product_id).html();
                        var total_gst = ((Number(gst_amount)) * (Number(return_qty)));
                        $("#total_gst_" + product_id).html(total_gst.toFixed(4));

                        var total_cost_with_gst = (((Number(costrate)) + (Number(gst_amount))) * (Number(return_qty)));
                        $("#total_cost_price_" + product_id).html(total_cost_with_gst.toFixed(4));

                        gettotal();
                        return false;
                    }
                }
            });

            if(samerow == 0) {
                if (product_html == '')
                {
                    product_html += '<tr id="product_' + product_id + '"  data-id="' + rowCount + '">' +
                        '<input type="hidden" name="inward_product_detail_id_' + product_id + '" id="inward_product_detail_id_' + product_id + '" value="' + inward_detail['inward_product_detail_id'] + '">' +
                        '<input type="hidden" name="debit_product_detail_id_'+product_id+'" id="debit_product_detail_id_'+product_id + '" value="">' +
                        '<td>' + barcode + '</td>' +
                        '<td>' + product_detail['product_name'] + '</td>' +
                        '<td class="fmcg"  readonly   id="batch_no_' + product_id + '">' + inward_detail['batch_no'] + '</td>' +
                        '<td  readonly   id="base_price_' + product_id + '">' + inward_detail['base_price'] + '</td>' +
                        '<td  readonly  id="cost_rate_' + product_id + '">' + inward_detail['cost_rate'] + '</td>' +
                        '<td readonly  id="cost_gst_percent_' + product_id + '">' + gst_percent + '</td>' +
                        '<td  readonly id="cost_gst_amount_' + product_id + '">' + Number(gst_amount.toFixed(4)) + '</td>' +
                        '<td readonly  id="offer_price_' + product_id + '">' + inward_detail['offer_price'] + '</td>' +
                        '<td readonly id="product_mrp_' + product_id + '">' + product_detail['product_mrp'] + '</td>' +
                        '<td readonly class="number" id="instock_qty_' + product_id + '">' + inward_detail['in_stock'] + '</td>' +
                        '<td readonly id="pending_qty_' + product_id + '">' + inward_detail['pending_return_qty'] + '</td>' +
                        '<td id="product_qty_' + product_id + '">' + inward_detail['product_qty'] + '</td>' +
                        '<td id="free_qty_' + product_id + '">' + inward_detail['free_qty'] + '</td>' +
                        '<td  onkeydown = "return validateNumber(event);" class="number editablearea" contenteditable="true"  style="color: black;"  onkeyup="debitproductqty(this);" id="return_qty_' + product_id + '"></td><input type="hidden" name="max_qty_' + product_id + '" id="max_qty_' + product_id + '" value="' + inward_detail['pending_return_qty'] + '">' +
                        '<td readonly id="total_gst_' + product_id + '">0</td>' +
                        '<td readonly id="total_cost_rate_' + product_id + '">0</td>' +
                        '<td readonly id="total_cost_price_' + product_id + '">0</td>' +
                        '<td  id="remarksentry_' + product_id + '"><textarea name="remarks_' + product_id + '" id="remarks_' + product_id + '"></textarea></td>' +
                        '<td onclick="removedebit(' + rowCount + ');"><i class="fa fa-close"></i></td></tr>';
                }
            }


        }

        $("#debit_productsearch").val('');
        $(".odd").hide();
        $("#debit_product_detail_record").prepend(product_html);

        var totalqty = $("#debit_product_detail_record tr").length;

       $(".debitnotetotalitems").html(totalqty);

        if(fmcg_inward == 1)
        {
            $(".fmcg").show();
        }
        else
        {
            $(".fmcg").hide();
        }
    });
}


function removedebit(rwcnt)
{
    $("#debit_product_detail_record").find("tr[data-id='" + rwcnt + "']").remove();
    var totalqty = $("#debit_product_detail_record tr").length;

    $(".debitnotetotalitems").html(totalqty);
}


function debitproductqty(obj)
{
    var product_id = $(obj).attr('id').split('return_qty_')[1];

    var tbl_row = $(obj).closest('tr').data('id');
    $("#debit_product_detail_record").find("tr[data-id='" + tbl_row + "']").each(function () {
        var max_qty = parseInt($(this).find('#max_qty_' + product_id).val());

        var returnqty = parseInt($(this).find("#return_qty_" + product_id).html());
        var instock_qty = parseInt($(this).find("#instock_qty_" + product_id).html());

        if ((returnqty > max_qty) || (instock_qty < returnqty)) {
            $(this).find("#return_qty_" + product_id).html(0);
            $(this).find("#pending_qty_" + product_id).html(max_qty);
        }

        var gstamount = $(this).find("#cost_gst_amount_" + product_id).html();

        var return_qty = $(this).find("#return_qty_" + product_id).html();

        if (return_qty == '') {
            return_qty = 0;

        }

        var costrate = $(this).find("#cost_rate_" + product_id).html();


        var total_cost = (((Number(costrate)) + (Number(gstamount))) * (Number(return_qty)));

        var total_gst = ((Number(gstamount)) * (Number(return_qty)));

        var total_cost_rate = ((Number(costrate)) * (Number(return_qty)));


        if (return_qty > 0) {
            $(this).find("#total_cost_price_" + product_id).html(total_cost.toFixed(4));
            $(this).find("#total_gst_" + product_id).html(total_gst.toFixed(4));
            $(this).find("#total_cost_rate_" + product_id).html(total_cost_rate.toFixed(4));

            var pending = ((Number(max_qty) - Number(return_qty)));

            $(this).find("#pending_qty_" + product_id).html(pending);
        } else {
            $(this).find("#total_cost_price_" + product_id).html(0.0000);
            $(this).find("#total_gst_" + product_id).html(0.0000);
            $(this).find("#total_cost_rate_" + product_id).html(0.0000);
            $(this).find("#pending_qty_" + product_id).html(max_qty);
        }
        gettotal();
    });
}

function gettotal()
{

    var total_qty = 0;
    var totalcostrate = 0;
    var totalgst = 0;
    var totalcostprice = 0;
    $("#debit_product_detail_record tr").each(function (index,e)
    {
        var product_id = $(this).attr('id').split('product_')[1];

        $(this).find('td').each(function ()
        {

            if($(this).attr('id') == "return_qty_"+product_id)
            {
                var totalqty  = $(this).html();
                if(totalqty == '')
                {
                    totalqty = 0;
                }
                total_qty += (parseInt(totalqty));
            }

            if($(this).attr('id') == "total_cost_rate_"+product_id)
            {
                var costrate = $(this).html();

                if ($.isNumeric(costrate))
                {
                    totalcostrate += (Number(costrate));

                }
            }
            if($(this).attr('id') == "total_gst_"+product_id)
            {
                var gst = $(this).html();

                if ($.isNumeric(gst))
                {
                    totalgst += (Number(gst));

                }
            }
            if($(this).attr('id') == "total_cost_price_"+product_id)
            {
                var costprice = $(this).html();

                if ($.isNumeric(costprice))
                {
                    totalcostprice += (Number(costprice));

                }
            }


        });
    });
    $("#total_qty").val(total_qty);
    $("#total_cost_rate").val(totalcostrate.toFixed(decimal_points));
    $("#total_gst").val(totalgst.toFixed(decimal_points));
    $("#total_cost_price").val(totalcostprice.toFixed(decimal_points));
}


$("#adddebitprint").click(function () {
    //1 = save and print
    //0 = save and new
    adddebit('1')
});

$("#adddebit").click(function ()
{
    //1 = save and print
    //0 = save and new
   adddebit('0')
});


function adddebit(debitprinttype)
{
    //ADD PO FUNCTION
    $("#adddebit").prop('disabled', true);
    $("#adddebitprint").prop('disabled', true);
   if(validate_poform('debit_note_form'))
   {
            $("#adddebit").prop('disabled', true);
            $("#adddebitprint").prop('disabled', true);
            var debit_note_data = [];
            var debit_note_product_info = [];


            debit_note = {};
            debit_note['inward_stock_id'] = $("#inward_stock_id").val();
            debit_note['supplier_gst_id'] = $("#gst_id").val();
            debit_note['debit_no'] = $("#debit_no").val();
            debit_note['debit_date'] = $("#debit_date").val();
            debit_note['total_qty'] = $("#total_qty").val();
            debit_note['total_cost_rate'] = $("#total_cost_rate").val();
            debit_note['total_gst'] = $("#total_gst").val();
            debit_note['total_cost_price'] = $("#total_cost_price").val();
            debit_note['note'] = $("#debit_note_note").val();

            var company_state = $("#company_state_id").val();
            var supplier_state = $("#supplier_state_id").val();


            //getting product row info
            $("#debit_product_detail_record tr").each(function (index,e)
            {
                var rowcount = $(this).data('id');
                $("#debit_product_detail_record").find("tr[data-id='" + rowcount + "']").each(function (key, keyval)
                {
                    /*  $(this).find('tr').each(function (key,keyval) {*/

                    var tr = $(this).attr('id');
                    var product_id = tr.split('product_')[1];
                    var debit_product_detail = {};
                    debit_product_detail['product_id'] = product_id;
                    debit_product_detail['inward_product_detail_id'] = $(this).find('#inward_product_detail_id_'+product_id).val();
                    debit_product_detail['debit_product_detail_id'] = $(this).find('#debit_product_detail_id_'+product_id).val();
                    $(this).find('td').each(function ()
                    {
                        if ($(this).attr('id') != undefined)
                        {
                            if ($(this).attr('id') == 'cost_gst_percent_' + product_id + '')
                            {
                                var cost_rate = $("#debit_product_detail_record").find("tr[data-id='" + rowcount + "']").find("#cost_rate_"+product_id).html();

                                var cost_gst_percent = $("#debit_product_detail_record").find("tr[data-id='" + rowcount + "']").find("#cost_gst_percent_"+product_id).html();
                                var cost_gst_amount =$("#debit_product_detail_record").find("tr[data-id='" + rowcount + "']").find("#cost_gst_amount_"+product_id).html();

                                var cost_cgst_sgst_percent = 0.00;
                                var cost_cgst_sgst_amount = 0.00;
                                var return_qty = $("#debit_product_detail_record").find("tr[data-id='" + rowcount + "']").find("#return_qty_"+product_id).html();

                                debit_product_detail['cost_gst_percent'] = cost_gst_percent;
                                var gst_cal = ''; //1=igst,2=cgst,sgst
                                if(tax_type == 1)
                                {
                                    gst_cal = 1;
                                }
                                else{
                                    if(company_state == supplier_state || supplier_state == '')
                                    {
                                        gst_cal = 2;
                                    }
                                    else
                                    {
                                        gst_cal = 1;
                                    }
                                }


                                if(gst_cal == 2)
                                //if ((company_state == supplier_state || supplier_state == '') && tax_type != '2')
                                {
                                    if (cost_gst_percent != 0)
                                    {
                                        cost_cgst_sgst_percent = ((Number(cost_gst_percent)) / 2).toFixed(4);
                                        cost_cgst_sgst_amount = ((Number(cost_gst_amount)) / 2).toFixed(4);
                                    }
                                    debit_product_detail['cost_igst_percent'] = 0.00;
                                    debit_product_detail['cost_igst_amount'] = 0.00;
                                    debit_product_detail['cost_cgst_percent'] = cost_cgst_sgst_percent;
                                    debit_product_detail['cost_cgst_amount'] = cost_cgst_sgst_amount;
                                    debit_product_detail['cost_sgst_percent'] = cost_cgst_sgst_percent;
                                    debit_product_detail['cost_sgst_amount'] = cost_cgst_sgst_amount;
                                    debit_product_detail['total_igst_amount_with_qty'] = 0.00;
                                    debit_product_detail['total_cgst_amount_with_qty'] = ((Number(cost_cgst_sgst_amount)) * (Number(return_qty)));
                                    debit_product_detail['total_sgst_amount_with_qty'] = ((Number(cost_cgst_sgst_amount)) * (Number(return_qty)));
                                } else {
                                    debit_product_detail['cost_igst_percent'] = cost_gst_percent;
                                    debit_product_detail['cost_igst_amount'] = cost_gst_amount;
                                    debit_product_detail['cost_cgst_percent'] = 0.00;
                                    debit_product_detail['cost_cgst_amount'] = 0.00;
                                    debit_product_detail['cost_sgst_percent'] = 0.00;
                                    debit_product_detail['cost_sgst_amount'] = 0.00;
                                    debit_product_detail['total_igst_amount_with_qty'] = ((Number(cost_gst_amount)) * (Number(return_qty)));
                                    debit_product_detail['total_cgst_amount_with_qty'] = 0.00;
                                    debit_product_detail['total_sgst_amount_with_qty'] = 0.00;
                                }
                            }
                            else {
                                id = $(this).attr('id').split('_' + product_id)[0];

                                if (id == 'remarksentry')
                                {
                                    values = $(this).find("#remarks_" + product_id).val();
                                    debit_product_detail['remarks'] = values;
                                } else {
                                    values = $(this).html();
                                    debit_product_detail[id] = values;
                                }
                            }
                        }
                    });
                    debit_note_product_info.push(debit_product_detail);
                });
            });
            //end of getting row info

            debit_note_data.push(debit_note);


            var url_route = "add_debit_note";
            var url = url_route;
            var type = "POST";

            var data = {
                'debit_note' : debit_note_data,
                'debit_product_detail' : debit_note_product_info,
                'debit_note_id' : $("#debit_note_id").val(),
            };

            callroute(url,type,data,function (data)
            {
                $("#adddebit").prop('disabled', false);
                $("#adddebitprint").prop('disabled', false);
                var dta = JSON.parse(data);

                if(dta['Success'] == "True")
                {
                    toastr.success(dta['Message']);
                    $("#debit_note_form").trigger('reset');
                    $("#debit_product_detail_record").empty();
                    $('#debit_date').datepicker({
                        autoclose: true,
                        format: "dd-mm-yyyy",
                        immediateUpdates: true,
                        todayBtn: true,
                        orientation: "bottom",
                        todayHighlight: true
                    }).datepicker("setDate", "0");

                    $("#debit_no").val(dta['debit_no']);

                    if(debitprinttype == 1)
                    {
                        var oldUrl = $("#print_save_debit").attr("href"); // Get current url

                        var newUrl = oldUrl.replace("param",dta['debit_note_id']);
                        $("#print_save_debit").attr("href", newUrl); // Set herf value

                        document.getElementById('print_save_debit').click(); // Works!
                    }



                    if(dta['url'] != '' && dta['url'] != 'undefined')
                    {
                        /*remove localstorage value of edit Debit*/
                        localStorage.removeItem('edit_debit_record');
                        setTimeout(function(){
                            window.location.href = dta['url'];
                        }, 1000);
                    }
                }
                else
                {
                    if(dta['status_code'] == 409)
                    {
                        toastr.error(dta['Message']);
                    }
                    else
                    {
                        toastr.error(dta['Message']);
                    }
                }

                var totalqty = $("#debit_product_detail_record tr").length;

                $(".debitnotetotalitems").html(totalqty);
            })
        }
   else
   {
       $("#adddebit").prop('disabled', false);
       $("#adddebitprint").prop('disabled', false);
       return false;
   }
}




function validate_poform(frmid)
{
    var error = 0;

    var rowlength = $('#debit_product_detail_record').find('tr').length;

    if(rowlength == 0)
    {
        error = 1;
        toastr.error("Select some product to Debit!");
        return false;
    }
    else
    {

        $("#debit_product_detail_record").each(function ()
        {
            $(this).find('tr').each(function ()
            {
                var tr = $(this).attr('id');
                var product_id = tr.split('product_')[1];

                var cost_rate = $("#cost_rate_" + product_id).html();

                if (cost_rate == '' || cost_rate == 0)
                {
                    error = 1;
                    toastr.error("Cost Rate is not valid!");
                }

                var qty = $("#return_qty_" + product_id).html();

                if (qty == '' || qty == 0)
                {
                    error = 1;
                    toastr.error("Return Qty must be grather than 0!");

                }

                /*var total_gst = $("#total_gst_" + product_id).html();

                if (total_gst == '' || total_gst == 0)
                {
                    error = 1;
                    toastr.error("Total GST is not valid!");

                }*/

                var total_cost_rate = $("#total_cost_rate_" + product_id).html();

                if (total_cost_rate == '' || total_cost_rate == 0)
                {
                    error = 1;
                    toastr.error("Total Cost rate is not valid!");

                }

                var total_cost_price = $("#total_cost_price_"+product_id).html();

                if (total_cost_price == '' || total_cost_price == 0)
                {
                    error = 1;
                    toastr.error("Total Cost Price is not valid!");
                }
            });
        });

        if ($("#gst_id").val() == '')
        {
            error = 1;
            toastr.error("supplier name can not be empty!");

        }


        if ($("#total_qty").val() == '' || $("#total_qty").val() == 0)
        {
            error = 1;
            toastr.error("Total Qty can not be empty!");

        }
  if ($("#total_cost_rate").val() == '' || $("#total_cost_rate").val() == 0)
        {
            error = 1;
            toastr.error("Total cost rate can not be empty!");

        }
        if ($("#total_cost_price").val() == '' || $("#total_cost_price").val() == 0)
        {
            error = 1;
            toastr.error("Total cost price can not be empty!");

        }

    }

    if(error == 1)
    {
        return false;
    }
    else
    {
        return true;
    }
}

//for edit debit note
$(document).ready(function ()
{
    //get a value from local storage
    var edit_data  = localStorage.getItem('edit_debit_note');

    var fmcg_inward = 0;

    if(edit_data != '' && edit_data != undefined && edit_data != null)
    {
        var edit_debit_data = JSON.parse(edit_data);
        var edit_debit = JSON.parse(edit_debit_data);

        $("#invoice_no").val(edit_debit['inward_stock']['invoice_no']);
        $("#inward_stock_id").val(edit_debit['inward_stock_id']);
        $("#debit_note_id").val(edit_debit['debit_note_id']);
        $("#debit_date").val(edit_debit['debit_date']);
        $("#debit_no").val(edit_debit['debit_no']);
        $("#gst_id").val(edit_debit['supplier_gst_id']);
        $("#total_qty").val(edit_debit['total_qty']);
        $("#total_cost_rate").val(edit_debit['total_cost_rate']);
        $("#total_gst").val(edit_debit['total_gst']);
        $("#total_cost_price").val(edit_debit['total_cost_price']);
        $("#debit_note_note").val(edit_debit['note']);

        //fillup product detail row
        if(edit_debit['debit_product_details'] != 'undefined' && edit_debit['debit_product_details'] != '')
        {
            var debit_html = '';
            $.each(edit_debit['debit_product_details'],function (debit_detail_key,debit_detail_value)
            {
                var  inward_product_detail = debit_detail_value['inward_product_detail'];

                if(debit_detail_value['product']['hsn_sac_code'] =='' || debit_detail_value['product']['hsn_sac_code'] == null)
                {
                    debit_detail_value['product']['hsn_sac_code'] = '';
                }

                var barcode = '';

                if(debit_detail_value['product'] != '' && debit_detail_value['product']['supplier_barcode'] != " " && debit_detail_value['product']['supplier_barcode'] != null)
                {
                    barcode = debit_detail_value['product']['supplier_barcode'];
                }
                else {

                    barcode = debit_detail_value['product']['product_system_barcode'];
                }

                if(inward_product_detail['batch_no'] == '' || inward_product_detail['batch_no'] == null)
                {
                    inward_product_detail['batch_no'] = '';
                }

                if(debit_detail_value['remarks'] == '' || debit_detail_value['remarks'] == null)
                {
                    debit_detail_value['remarks'] = '';
                }

                var rowCount = $('#debit_product_detail_record tr').length;
                rowCount++;
                debit_detail_key++;

                var product_id = debit_detail_value['product_id'];

                var max_qty = (Number(inward_product_detail['pending_return_qty']) + (Number(debit_detail_value['return_qty'])));


                debit_html += '<tr id="product_' + product_id + '"  data-id="' + debit_detail_key + '">' +
                    '<input type="hidden" name="inward_product_detail_id_' + product_id + '" id="inward_product_detail_id_' + product_id + '" value="' + inward_product_detail['inward_product_detail_id'] + '">' +
                    '<input type="hidden" name="debit_product_detail_id_'+product_id+'" id="debit_product_detail_id_'+product_id + '" value="'+debit_detail_value['debit_product_detail_id']+'">' +
                    '<td>' + barcode + '</td>' +
                    '<td>' + debit_detail_value['product']['product_name'] + '</td>' +
                    '<td readonly id="batch_no_' + product_id + '">' + inward_product_detail['batch_no'] + '</td>' +
                    '<td readonly  id="base_price_' + product_id + '">' + debit_detail_value['base_price'] + '</td>' +
                    '<td readonly  id="cost_rate_' + product_id + '">' + debit_detail_value['cost_rate'] + '</td>' +
                    '<td readonly id="cost_gst_percent_' + product_id + '">' + debit_detail_value['cost_gst_percent'] + '</td>' +
                    '<td readonly id="cost_gst_amount_' + product_id + '">' + debit_detail_value['cost_gst_amount'] + '</td>' +
                    '<td readonly id="offer_price_' + product_id + '">' + inward_product_detail['offer_price'] + '</td>' +
                    '<td readonly id="product_mrp_' + product_id + '">' + inward_product_detail['product_mrp'] + '</td>' +
                    '<td readonly id="instock_qty_' + product_id + '">' + debit_detail_value['in_stock'] + '</td>' +
                    '<td readonly id="pending_qty_' + product_id + '">' + inward_product_detail['pending_return_qty'] + '</td>' +
                    '<td id="product_qty_' + product_id + '">' + inward_product_detail['product_qty'] + '</td>' +
                    '<td id="free_qty_' + product_id + '">' + inward_product_detail['free_qty'] + '</td>' +
                    '<td onkeypress = "return testCharacter(event);" class="number editablearea " contenteditable="true"  style="color: black;"  onkeyup="debitproductqty(this);" id="return_qty_' + product_id + '">'+debit_detail_value['return_qty']+'</td>' +
                    '<input type="hidden" name="max_qty_' + product_id + '" id="max_qty_' + product_id + '" value="' + max_qty + '">' +
                    '<td readonly id="total_gst_' + product_id + '">'+debit_detail_value['total_gst']+'</td>' +
                    '<td readonly id="total_cost_rate_' + product_id + '">'+debit_detail_value['total_cost_rate']+'</td>' +
                    '<td readonly id="total_cost_price_' + product_id + '">'+debit_detail_value['total_cost_price']+'</td>' +
                    '<td  id="remarksentry_' + product_id + '"><textarea name="remarks_' + product_id + '" id="remarks_' + product_id + '" >'+debit_detail_value['remarks']+'</textarea></td>' +
                    '<td></td>' +
                    '</tr>';
            });
            $("#productsearch").val('');
            $(".odd").hide();
            $("#debit_product_detail_record").append(debit_html);

            var totalqty = $("#debit_product_detail_record tr").length;

            $(".debitnotetotalitems").html(totalqty);
        }
        //end of fillup product detail row


        if(edit_debit['supplier_gstdetail'] != '' && edit_debit['supplier_gstdetail'] != undefined)
        {
            if(edit_debit['supplier_gstdetail']['supplier_gst'] != '')
            {
                var supplier_company_info = edit_debit['supplier_gstdetail']['supplier_company_info'];

                if(supplier_company_info['supplier_last_name'] == null)
                {
                    supplier_company_info['supplier_last_name'] = '';
                }

                $("#state_id").val(edit_debit['supplier_gstdetail']['state_id']);

                var name_supplier = supplier_company_info['supplier_first_name'] +' '+supplier_company_info['supplier_last_name'] +'_'+edit_debit['supplier_gstdetail']['supplier_gstin'];

                $("#supplier_name").val(name_supplier);
                $("#supplier_company").val(supplier_company_info['supplier_company_name']);
            }
        }
    }


});
