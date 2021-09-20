

// //daterange picker for filter date
// $("#filer_from_to").daterangepicker().val('');

function resetproductfilterdata(){
    $("#filer_from_to").val('');
    $("#product_name_filter").val('');
    $("#barcode_filter").val('');
    $("#batch_no_filter").val('');
    $("#invoice_no_filter").val('');


    var data = {};
    var page = 1;
    var sort_type = $("#hidden_sort_type").val();
    var sort_by = $("#hidden_column_name").val();
    fetch_data('product_wise_record',page,sort_type,sort_by,data,'product_wise_report_record');
}



$(document).on('click', '#product_wise_report_export', function(){

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
        barcode : $("#barcode_filter").val(),
        product_name : $("#product_name_filter").val(),
        batch_no : $("#batch_no_filter").val(),
        invoice_no : $("#invoice_no_filter").val()
    };
    var url = "product_wise_report_export?" + $.param(query)
    window.open(url,'_blank');
});

