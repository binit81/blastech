function view_debit_detail(debitid)
{
    $("#debit_popup_record").trigger('reset');

    $("#view_debit_record").empty();
    var  url = "view_debit_detail";
    var type = "POST";

    var data = {
        'debit_note_id' : debitid,
    };

    callroute(url,type,data,function (data)
    {
        var dta = JSON.parse(data);

        if(dta['Success'] == "True")
        {
            var final_data = JSON.parse(dta['Data']);

            var dataval = final_data;

            $("#debit_total_qty").html(dataval[0]['total_qty']);


            $("#viewdebitpopup").modal('show');

            var debit_detail = '';

            if(dataval[0]['debit_product_details'] != '' && dataval[0]['debit_product_details'] != null && dataval[0]['debit_product_details'] != undefined) {
                $.each(dataval[0]['debit_product_details'], function (key, value)
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

                    var cost_rate = '';
                    var cost_gst_percent = '';
                    var cost_gst_amount = '';
                    var qty = '';
                    var total_cost_rate = '';
                    var total_gst = '';
                    var total_cost_price = '';
                    var remarks = '';



                    if (value['cost_rate'] != '') {
                        cost_rate = value['cost_rate'];
                    }
                    if (value['cost_gst_percent'] != '') {
                        cost_gst_percent = value['cost_gst_percent'];
                    }
                    if (value['cost_gst_amount'] != '') {
                        cost_gst_amount = value['cost_gst_amount'];

                    }
                    if (value['return_qty'] != '') {
                        qty = value['return_qty'];
                    }

                    if (value['total_cost_rate'] != '') {
                        total_cost_rate = value['total_cost_rate'];
                    }

                    if (value['total_gst'] != '') {
                        total_gst = value['total_gst'];
                    }

                    if (value['total_cost_price'] != '') {
                        total_cost_price = value['total_cost_price'];
                    }
                    if (value['remarks'] != '' && value['remarks'] != null) {
                             remarks = value['remarks'];
                     }


                    debit_detail += '<tr id="' + value['product_id'] + '"> ' +
                        '<td>' + barcode + '</td>' +
                        '<td>' + product_name + '</td>' +
                        '<td>' + cost_rate + '</td>' +
                        '<td>' + cost_gst_percent + '</td>' +
                        '<td>' + cost_gst_amount + '</td>' +
                        '<td>' + qty + '</td>' +
                        '<td>' + total_cost_rate + '</td>' +
                        '<td>' + total_gst + '</td>' +
                        '<td>' + total_cost_price + '</td>' +
                        '<td>' + remarks + '</td>' +

                        '</tr>';
                });
            }
            $("#view_debit_record").append(debit_detail);
        }
        else
        {

        }
    })
}




function edit_debitnote(debit_id)
{
    var  url = "edit_debit_note";
    var type = "POST";

    var data = {
        'debit_note_id' : debit_id,

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


            localStorage.setItem('edit_debit_note', JSON.stringify(dta['Data']));


            window.location.href = url;
        }
    });
}


$('#checkalldebit').change(function()
{
    if($(this).is(":checked")) {
        $("#debitrecord tr").each(function()
        {
            var id = $(this).attr('id');

            $(this).find('td').each(function ()
            {
                $("#delete_debit"+id).prop('checked',true);
            });
        })
    }
    else
    {
        $("#debitrecord tr").each(function(){
            var id = $(this).attr('id');
            $(this).find('td').each(function ()
            {
                $("#delete_debit"+id).prop('checked',false);
            });

        })
    }
});


$("#deletedebitnote").click(function ()
{
    if(confirm("Are You Sure want to delete this debit note?")) {

        var ids = [];


        $('input[name="delete_debit[]"]:checked').each(function()
        {
            idss = {};
            idss['debit_note_id'] = $(this).val();
            idss['inward_stock_id'] = $(this).data('id');
            idss['supplier_gst_id'] =$(this).data('attr');

            ids.push(idss)
        });

        if(ids.length > 0)
        {
            var data = {
                "deleted_id": ids,
            };
            var url = "debit_note_delete";
            var type = "POST";
            callroute(url, type, data, function (data)
            {
                var dta = JSON.parse(data);

                if (dta['Success'] == "True")
                {
                    toastr.success(dta['Message']);
                    resettable('debit_note_data','debitnoterecord');
                } else {
                    toastr.error(dta['Message']);
                }
            })
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
});


//this is used for display supplier suggestion
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
                                        label: value.supplier_company_name + ' '+ value.supplier_first_name + ' ' + last_name + '_' + suppliervalue.supplier_gstin,
                                        value: value.supplier_company_name + ' '+ value.supplier_first_name + ' ' + last_name + '_' + suppliervalue.supplier_gstin,
                                        supplier_gst_id: suppliervalue.supplier_gst_id
                                    });
                                });
                            }
                            else
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
                                    label: value.supplier_company_name + ' '+ value.supplier_first_name + ' ' + last_name ,
                                    value: value.supplier_company_name + ' '+ value.supplier_first_name + ' ' + last_name ,
                                    supplier_gst_id: ''

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

            $("#supplier_gst_id").val(gst_id);

            $(".ui-helper-hidden-accessible").css('display','none');
            //call a function to perform action on select of supplier
        }
    })
});
$("#search_view_debit").click(function () {
    debit_filter();
});

function debit_filter()
{

    var debit_no = $('#debit_no').val();
    var data = {

        debit_no : debit_no,
        supplier_gst_id : $("#supplier_gst_id").val()
    };

    var page = $("#hidden_page").val();
    var sort_type = $("#hidden_sort_type").val();
    var sort_by = $("#hidden_column_name").val();
    fetch_data('debit_note_fetch_data',page,sort_type,sort_by,data,'debitrecord');
}
function resetdebitfilterdata()
{
    $("#debit_no").val('');
    $("#supplier_gst_id").val('');

    debit_filter();
}
