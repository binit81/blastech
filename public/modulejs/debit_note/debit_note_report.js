function reset_debit_note_filterdata(){
    $("#filer_from_to").val('');
    $("#debit_no_filter").val('');
    var data = {};
    var page = 1;
    var sort_type = $("#hidden_sort_type").val();
    var sort_by = $("#hidden_column_name").val();
    fetch_data('debit_no_wise_search_record',page,sort_type,sort_by,data,'debit_note_record');
}

$(document).on('click', '#debit_note_report_export', function(){

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
        debit_no : $("#debit_no_filter").val()
    };
    var url = "debitnote_report_export?" + $.param(query)
    window.open(url,'_blank');


});



