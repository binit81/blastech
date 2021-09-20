function view_po_detail(poid)
{
    $("#po_popup_record").trigger('reset');

    $("#view_po_record").empty();
    var  url = "view_po_detail";
    var type = "POST";

    var data = {
        'purchase_order_id' : poid,
    };

    callroute(url,type,data,function (data)
    {
        var dta = JSON.parse(data);
        if(dta['Success'] == "True")
        {
            var final_data = JSON.parse(dta['Data']);

            var dataval = final_data;


            $("#po_total_qty").html(dataval[0]['total_qty']);

            $("#viewpopopup").modal('show');

            var oldUrl = $("#print_detail_po").attr("href"); // Get current url

            var newUrl = oldUrl.replace("param",poid);
            $("#print_detail_po").attr("href", newUrl); // Set herf value


            var po_detail = '';

            if(dataval[0]['purchase_order_detail'] != '' && dataval[0]['purchase_order_detail'] != null && dataval[0]['purchase_order_detail'] != undefined) {
                var is_takeinward_show = 0;
                $.each(dataval[0]['purchase_order_detail'], function (key, value)
                {
                    if (value['product'] != '' && value['product'] != 'undefined') {
                        product_detail = value['product'];
                    }
                    var product_name = '';

                    if (product_detail != '' && product_detail['product_name'] != '' && product_detail['product_name'] != null) {
                        product_name = product_detail['product_name'];
                    }

                    var barcode = '';
                    if (product_detail != '' && product_detail['supplier_barcode'] != " " && product_detail['supplier_barcode'] != null) {
                        barcode = product_detail['supplier_barcode'];
                    } else {

                        barcode = product_detail['product_system_barcode'];
                    }
                  var  tblclass = '';
                if(key % 2 == 0)
                {
                    tblclass = 'even';
                }
                else
                {
                    tblclass = 'odd';
                }

                    var cost_rate = '';
                    var cost_gst_percent = '';
                    var cost_gst_amount = '';
                    var qty = '';
                    var total_cost_without_gst = '';
                    var total_gst = '';
                    var total_cost_with_gst = '';
                    var pending_qty = '';
                    var received_qty = '';


                    if (value['cost_rate'] != '') {
                        cost_rate = value['cost_rate'];
                    }
                    if (value['cost_gst_percent'] != '') {
                        cost_gst_percent = value['cost_gst_percent'];
                    }
                    if (value['cost_gst_amount'] != '') {
                        cost_gst_amount = value['cost_gst_amount'];

                    }
                    if (value['qty'] != '') {
                        qty = value['qty'];
                    }

                    if (value['total_cost_without_gst'] != '') {
                        total_cost_without_gst = value['total_cost_without_gst'];
                    }

                    if (value['total_gst'] != '') {
                        total_gst = value['total_gst'];
                    }

                    if (value['total_cost_with_gst'] != '') {
                        total_cost_with_gst = value['total_cost_with_gst'];
                    }
                    if (value['pending_qty'] != '' ||value['pending_qty'] == '0') {
                        pending_qty = value['pending_qty'];
                    }
                    if (value['received_qty'] != '' ||value['received_qty'] == '0')
                    {
                        received_qty = value['received_qty'];
                    }

                    var uqc_name = '';
                    if(product_detail['uqc_id'] != '' && product_detail['uqc_id'] != null && product_detail['uqc_id'] != 0)
                    {
                        uqc_name = product_detail['uqc']['uqc_shortname'];
                    }
                    var size_name = '';
                    if(product_detail['size_id'] != '' && product_detail['size_id'] != null && product_detail['size_id'] != 0)
                    {
                        size_name = product_detail['size']['size_name'];
                    }

                    po_detail += '<tr id="' + value['product_id'] + '" class="'+tblclass+'"> ' +
                        '<td>' + barcode + '</td>' +
                        '<td>' + product_name + '</td>' +
                        '<td>' + size_name+ ' '+uqc_name+'</td>' +
                        '<td style="text-align:right !important;font-size:14px !important;">' + cost_rate + '</td>' +
                        '<td style="text-align:right !important;font-size:14px !important;">' + cost_gst_percent + '</td>' +
                        '<td style="text-align:right !important;font-size:14px !important;">' + cost_gst_amount + '</td>' +
                        '<td style="text-align:right !important;font-size:14px !important;">' + qty + '</td>' +
                        '<td style="text-align:right !important;font-size:14px !important;">' + total_cost_without_gst + '</td>' +
                        '<td style="text-align:right !important;font-size:14px !important;">' + total_gst + '</td>' +
                        '<td style="text-align:right !important;font-size:14px !important;">' + total_cost_with_gst + '</td>' +
                        '<td style="text-align:right !important;font-size:14px !important;">' + received_qty + '</td>' +
                        '<td style="text-align:right !important;font-size:14px !important;">' + pending_qty + '</td>' +
                        '</tr>';

                    is_takeinward_show += pending_qty;
                });
            }
                $("#view_po_record").append(po_detail);
                console.log(is_takeinward_show);
                if(is_takeinward_show > 0)
                {
                    $(".show_takeinward").show();
                    $(".show_takeinward").attr('onclick','edit_po("'+poid+'","2")');
                }
                else
                {
                    $(".show_takeinward").hide();
                }
        }
        else
        {

        }
    })
}

//function for edit po and take inward in inward screen
//type = 1 for edit po in issue po screen
//type = 2 for take po in inward stock

function edit_po(po_id,po_edit_type)
{
    var  url = "edit_purchase_order";
    var type = "POST";

    var data = {
        'purchase_order_id' : po_id,
        'type' : po_edit_type,
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

            if(po_edit_type == 1)
            {
                localStorage.setItem('edit_po_record', JSON.stringify(dta['Data']));
            }else
            {
                localStorage.setItem('take_po_inward_data', JSON.stringify(dta['Data']));
            }

            window.location.href = url;
        }
    });
}

// $("#filer_from_to").daterangepicker().val('');


function resetpofilterdata()
{
    $("#filer_from_to").val('');
    $("#hidden_page").val('1');
    $("#po_no").val('');
    $("#supplier_name").val('');
    $("#supplier_id").val('');
    $("#hidden_page").val(1);

    resettable('purchase_order_fetch_data','porecord');
}



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


$(document).on('click', '#po_record_export', function(){

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
        po_no : $("#po_no").val(),
        supplier_name : $("#supplier_id").val()
    };
    var url = "po_report_export?" + $.param(query)
    window.open(url,'_blank');


});