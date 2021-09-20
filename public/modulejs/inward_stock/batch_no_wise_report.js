

// //daterange picker for filter date
// $("#filer_from_to").daterangepicker().val('');

function resetfilterdata(){
    $("#filer_from_to").val('');
    $("#batch_no_filter").val('');
    var data = {};
    var page = 1;
    var sort_type = $("#hidden_sort_type").val();
    var sort_by = $("#hidden_column_name").val();
    fetch_data('batch_no_wise_record',page,sort_type,sort_by,data,'batchnorecord');
}



$(document).on('click', '#batch_no_report_export', function(){

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
        batch_no : $("#batch_no").val()
    };
    var url = "inward_batch_report_export?" + $.param(query)
    window.open(url,'_blank');


});



