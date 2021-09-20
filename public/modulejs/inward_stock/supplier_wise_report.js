

// //daterange picker for filter date
// $("#filer_from_to").daterangepicker().val('');

function resetfilterdata(){
    $("#filer_from_to").val('');
    $("#supplier_name").val('');
    $("#supplier_id").val('');

    var data = {};
    var page = 1;
    var sort_type = $("#hidden_sort_type").val();
    var sort_by = $("#hidden_column_name").val();
    fetch_data('supplier_wise_record',page,sort_type,sort_by,data,'supplierrec');

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
        }
    })
});



$(document).on('click', '#supplier_wise_report_export', function(){

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
        supplier_name : $("#supplier_id").val()
    };


    var url = "supplier_wise_report_export?" + $.param(query)
    window.open(url,'_blank');


});
