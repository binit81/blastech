$("#addgstslab").click(function (e) {
    if(validate_gstslabs('gstslabsform'))
    {
        $("#addgstslab").prop('disabled', true);

        var data = {
            "formdata": $("#gstslabsform").serialize(),
        };
        var  url = "gstslabs_create";
        var type = "POST";
        callroute(url,type,data,function (data)
        {
            var dta = JSON.parse(data);

            if(dta['Success'] == "True")
            {
                toastr.success(dta['Message']);
                $("#gstslabsform").trigger('reset');
                $("#gst_slabs_master_id").val('');
                resettable('gst_slabs_data','tablegstrecord');
            }
            else
            {
                toastr.error(dta['Message']);
            }
            $("#addgstslab").prop('disabled', false);
        })

    }
    //$(this).prop('disabled', false);
    e.preventDefault();
});

function validate_gstslabs(frmid)
{
    var error = 0;

    if($("#selling_price_from").val() == '')
    {
        error = 1;
       toastr.error("Enter From Selling Price!");
        return false;
    }
    if($("#selling_price_to").val() == '')
    {
        error = 1;
       toastr.error("Enter To Selling Price!");
        return false;
    }

    if($("#percentage").val() == '')
    {
        error = 1;
       toastr.error("Enter GST Percentage!");
        return false;
    }

    var sellingfrom = $("#selling_price_from").val();
    var sellingto = $("#selling_price_to").val();


    if(Number(sellingto) < Number(sellingfrom))
    {
        error = 1;
       toastr.error("To selling price can not be less than from selling price!");
        return false;
    }

    if(error == 1)
    {
        return false;
    }
    else
    {
        return true;
    }
}


//edit product

function editgstslabs(gstslabid)
{
    $(this).prop('disable',true);
    var url = "gstslab_edit";
    var type = "POST";
    var data = {
        "gst_slabs_master_id": gstslabid
    }
    callroute(url, type, data, function (data) {
        $(this).prop('disable', false);
        var gstslabs_response = JSON.parse(data);

        if (gstslabs_response['Success'] == "True")
        {

            var gstslabs_data = gstslabs_response['Data'];

            $("#gst_slabs_master_id").val(gstslabs_data['gst_slabs_master_id']);
            $("#selling_price_from").val(gstslabs_data['selling_price_from']);
            $("#selling_price_to").val(gstslabs_data['selling_price_to']);
            $("#percentage").val(gstslabs_data['percentage']);
            $("#gst_note").val(gstslabs_data['note']);
        }
    });
}


$("#deletegstslabs").click(function ()
{
    if(confirm("Are You Sure want to delete this GST slabs?")) {

        var ids = [];

        $('input[name="delete_gstslabs[]"]:checked').each(function()
        {
            ids.push($(this).val());
        });


        if(ids.length > 0)
        {
            var data = {
                "deleted_id": ids
            };
            var url = "gstslabs_delete";
            var type = "POST";
            callroute(url, type, data, function (data)
            {
                var dta = JSON.parse(data);

                if (dta['Success'] == "True")
                {
                    toastr.success(dta['Message']);
                    $("#gstslabsform").trigger('reset');
                    $("#gst_slabs_master_id").val('');
                    resettable('gst_slabs_data','tablegstrecord');
                } else {
                    toastr.error(dta['Message']);
                }
            })
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
});


$("#cancelgstslab").click(function () {
   $("#selling_price_from").val('');
   $("#selling_price_to").val('');
   $("#percentage").val('');
   $("#gst_slabs_master_id").val('');
});


$('#checkall').change(function()
{
    if($(this).is(":checked")) {
        $("#gstslabrecord tr").each(function()
        {
            var id = $(this).attr('id');

            $(this).find('td').each(function ()
            {
               $("#delete_gstslabs"+id).prop('checked',true);
            });

        })
    }
    else
    {
        $("#gstslabrecord tr").each(function(){
            var id = $(this).attr('id');
            $(this).find('td').each(function ()
            {
                $("#delete_gstslabs"+id).prop('checked',false);
            });

        })
    }
});