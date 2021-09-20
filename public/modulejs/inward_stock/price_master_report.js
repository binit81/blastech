function resetpricedata()
{
    $("#barcode_filter").val('');
    $("#product_name_filter").val('');

    var data = {};

    var page = 1;
    var sort_type = $("#hidden_sort_type").val();
    var sort_by = $("#hidden_column_name").val();
    fetch_data('price_master_record',page,sort_type,sort_by,data,'price_master');
}



$(document).on('click', '#price_master_report_export', function(){

    var barcode = $('#barcode_filter').val();
    var product_name = $('#product_name_filter').val();


    var query = {
        barcode: barcode,
        product_name : product_name
    };
    var url = "inward_pricemaster_report_export?" + $.param(query)
    window.open(url,'_blank');


});