$("input").attr("autocomplete", "off");

$("#universalSearch").keyup(function ()
{
    jQuery.noConflict();
    if($("#universalSearch").val().length >= 1) {

        $("#universalSearch").autocomplete({
            autoFocus: true,
            minLength: 1,
            source: function (request, response) {
                var url = "universal_search";
                var type = "POST";
                var data = {
                    'search_val': $("#universalSearch").val()
                };
                callroute(url, type, data, function (data) {


                    var searchdata = JSON.parse(data, true);
                    var html = '';
                    if (searchdata['Success'] == "True") {

                        var result = [];
                        searchdata['Data'].forEach(function (value) {
                             result.push({label:value.nav_tab_display_name, value:value.product_name,id:value.nav_url });
                        });

                        //push data into result array.and this array used for display suggetion
                        response(result);

                    }
                });
            },
            //this help to call a function when select search suggetion
            select: function (event, ui) {
                var id = ui.item.id;
                //call a getproductdetail function for getting product detail based on selected product from suggetion
               
                window.location.href = id;
               
            }
        });
    }
    else
    {
            $("#universalSearch").empty();
    }

});
