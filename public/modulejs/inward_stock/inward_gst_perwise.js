
$(document).on('click', '#gst_wise_export', function(){

    var filter_date = $('#fromtodate').val();

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
        to_date : to_date
    };
    var url = "inward_gst_wise_export_excel?" + $.param(query)
    window.open(url,'_blank');


});


function reset_inwardgst_percent_filterdata(){
    $("#fromtodate").val('');
    var data = {};
    var page = 1;
    var sort_type = $("#hidden_sort_type").val();
    var sort_by = $("#hidden_column_name").val();
    fetch_data('inward_gstperwise_search',page,sort_type,sort_by,data,'view_inwardpercent_record');
}