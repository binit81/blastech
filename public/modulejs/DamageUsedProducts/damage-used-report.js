function editDamage(value)
{
    var damage_product_id = value;

    var url = "editDamage";
    var type = "POST";
    var data = {
        'damage_product_id': damage_product_id
    };
    callroute(url, type, data, function (Data) {


        /*var pushdata = JSON.parse(Data, true);
        localStorage.setItem('editDamage-record',JSON.stringify(pushdata));
        window.location = pushdata['url'];*/

        var dta = JSON.parse(Data);

        if (dta['Success'] == "True")
        {
            var url = '';
            if(dta['url'] != '' && dta['url'] != 'undefined')
            {
                url = dta['url'];
            }

            localStorage.setItem('edit_damage_record',JSON.stringify(dta['Data']));

            window.location.href = url;
        }

    });
}


function delDamage(value)
{
    if (confirm('Are you sure you want to delete this damage record?'))
    {
        var damage_product_id     =   value;

        var url = "delDamage";
        var type = "POST";
        var data = {
            'damage_product_id': damage_product_id
        };
        callroute(url, type, data, function (Data)
        {
            var dta = JSON.parse(Data);


            if (dta['Success'] == "True")
            {
                toastr.success(dta['Message']);

                var data = {};
                var page = 1;
                var sort_type = $("#hidden_sort_type").val();
                var sort_by = $("#hidden_column_name").val();
                fetch_data('searchDamageProductReportGroup',page,sort_type,sort_by,data,'damagereportrecord');
            } else {
                toastr.error(dta['Message']);
            }

        });
    }
    return false;
}


$("input[name='DamageType']").click(function(event){

    var searchIDs = $("input[name='DamageType']:checked").map(function(){
        return $(this).val();
    }).get(); // <----
    $('#DamageIds').val(searchIDs);
});





function resetdamagefilterdata(){
    $("#fromtodate").val('');
    $("#damage_no_search").val('');

    $("input[name=DamageType]").prop('checked',false);

    var data = {};
    var page = 1;
    var sort_type = $("#hidden_sort_type").val();
    var sort_by = $("#hidden_column_name").val();
    fetch_data('searchDamageProductReportGroup',page,sort_type,sort_by,data,'damagereportrecord');
}

$(document).on('click', '#exportDamagedata', function()
{
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
        damage_no_search: $('#damage_no_search').val(),
        DamageType : $("input[name='DamageType']:checked").val()
    }
    var url = "exportdamage_details?" + $.param(query)
    window.open(url,'_blank');
});

$("#damage_no_search").typeahead({
    source: function(request, process) {
        $.ajax({
            url: "damage_no_search",
            dataType: "json",
            data: {
                search_val: $("#damage_no_search").val(),
                term: request.term
            },
            success: function (data) {
                $("#damage_no_search").val();

                if($("#damage_no_search").val()!='')
                {
                    process(data);

                }
                else
                {
                    $(".dropdown-menu").hide();
                }
            }
        });
    },

    minLength: 1,
    afterSelect: function (item) {

    }

});

