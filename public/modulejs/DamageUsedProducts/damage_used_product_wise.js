function resetdamageproductfilterdata(){
    $("#fromtodate").val('');
    $("#damageproductsearch").val('');
    $("#DamageIds").val('');

    $("input[name=DamageType]").prop('checked',false);

    var data = {};
    var page = 1;
    var sort_type = $("#hidden_sort_type").val();
    var sort_by = $("#hidden_column_name").val();
    fetch_data('searchDamageProductReport',page,sort_type,sort_by,data,'searchDamageproductReportData');
}

$("#damageproductsearch").typeahead({
    source: function(request, process) {
        $.ajax({
            url: "damage_product_search",
            dataType: "json",
            data: {
                search_val: $("#damageproductsearch").val(),
                term: request.term
            },
            success: function (data)
            {
                $("#damageproductsearch").val();
                objects = [];
                map = {};
                if($("#damageproductsearch").val()!='')
                {
                    $.each(data, function(i, object)
                    {
                        map[object.label] = object;
                        objects.push(object.label);
                    });
                    process(objects);
                }
                else
                {
                    $(".dropdown-menu").hide();
                }
            }
        });
    },
    minLength: 1,
    afterSelect: function (item)
    {
        $("#damage_product_search_id").val(map[item]['product_id']);
    }
});



$(document).on('click', '#exportDamageProductdata', function(){

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
        from_date:from_date,
        to_date : to_date,
        damageproductsearch: $('#damage_product_search_id').val(),
        DamageType : $("input[name='DamageType']:checked").val()
    };

    var url = "exportdamageproduct_details?" + $.param(query)
    window.open(url,'_blank');


});



$("input[name='DamageType']").click(function(event){

    var searchIDs = $("input[name='DamageType']:checked").map(function(){
        return $(this).val();
    }).get(); // <----
    $('#DamageIds').val(searchIDs);
});
