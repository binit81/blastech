$("#searchcustomerdata").keyup(function ()
{
    
    jQuery.noConflict();
    if($("#searchcustomerdata").val().length >= 1) {

        $("#searchcustomerdata").autocomplete({
            autoFocus: true,
            minLength: 1,
            source: function (request, response) {
                var url = "customer_search";
                var type = "POST";
                var data = {
                    'search_val': $("#searchcustomerdata").val()
                };
                callroute(url, type, data, function (data) {


                    var searchdata = JSON.parse(data, true);
                    var html = '';
                    if (searchdata['Success'] == "True") {

                        var result = [];
                        searchdata['Data'].forEach(function (value) {
                            result.push({
                                label: value.customer_name + '_' + value.customer_mobile,
                                value: value.customer_name + '_' + value.customer_mobile,
                                id: value.customer_id
                            });
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
                
               
            }
        });
    }
    else
    {
            $("#searchcustomerdata").empty();
    }

});


$('#fromtodate').change(function(e){


   
    var inoutdate         =     $("#fromtodate").val();
    


    var totalnights       =     inoutdate.split(' - ');

    $("#from_date").val(totalnights[0]);
    $("#to_date").val(totalnights[1]); 

});


$("#productsearch").typeahead({

    source: function(request, process) {
       $.ajax({
           url: "sproduct_search",
           dataType: "json",
           data: {
                search_val: $("#productsearch").val(),
                term: request.term
            },
           success: function (data) {$("#productsearch").val()
                   
                 objects = [];
                 map = {};

                if($("#productsearch").val()!='')
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
    autoSelect:false
    
     
});

$('#categoryname').keyup(function(e){
     
    jQuery.noConflict();

    $(this).autocomplete({
        

            autoFocus: true,
            minLength: 1,
            source: function (request, response)
            {
                var url = "category_search";
                var type = "POST";
                var data = {
                    'search_val' : $("#categoryname").val()

                };

                callroute(url,type,data,function (data)
                {

                   
                    var searchdata = JSON.parse(data,true);
                    var html = '';
                    if(searchdata['Success'] == "True")
                    {

                        var result = [];
                        searchdata['Data'].forEach(function (value)
                        {
                            result.push({label:value.category_name, value:value.category_name,id:value.category_id });
                        });

                        //push data into result array.and this array used for display suggetion
                        response(result);

                    }
                });
            },

});
         
});
$('#brandname').keyup(function(e){

    jQuery.noConflict();

    $('#brandname').autocomplete({        

            autoFocus: true,
            minLength: 1,
            source: function (request, response)
            {
                var url = "brand_search";
                var type = "POST";
                    var data = {
                    'search_val' : $("#brandname").val()

                };

                callroute(url,type,data,function (data)
                {

                   
                    var searchdata = JSON.parse(data,true);
                    var html = '';
                    if(searchdata['Success'] == "True")
                    {

                        var result = [];
                        searchdata['Data'].forEach(function (value)
                        {
                            result.push({label:value.brand_type, value:value.brand_type });
                        });

                        //push data into result array.and this array used for display suggetion
                        response(result);

                    }
                });
            },

});
         
});


function resetbatchdata()
{
    $("#fromtodate").val('');
    $("#productsearch").val('');

    var data = {
        'from_date' : '',
        'to_date' : '',
        'barcode' : ''
    };
    var page = 1;
    var sort_type = $("#hidden_sort_type").val();
    var sort_by = $("#hidden_column_name").val();
    fetch_data('batch_no_wise_record',page,sort_type,sort_by,data,'batch_no_report_record');
}


$(document).on('click', '#exportbatchnodata', function(){

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

        var query =
        {
            from_date: from_date,
            to_date : to_date,
            productsearch: $("#productsearch"),
        };
        var url = "export_batchno_details?" + $.param(query)
        window.open(url,'_blank');
    });

