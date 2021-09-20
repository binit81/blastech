$('#checkalldebitreceipt').change(function()
{
    if($(this).is(":checked")) {
        $("#debit_receipt_table tr").each(function()
        {
            var id = $(this).attr('id');

            $(this).find('td').each(function ()
            {
                $("#delete_debit_receipt"+id).prop('checked',true);
            });
        })
    }
    else
    {
        $("#debit_receipt_table tr").each(function(){
            var id = $(this).attr('id');
            $(this).find('td').each(function ()
            {
                $("#delete_debit_receipt"+id).prop('checked',false);
            });

        })
    }
});


$("#delete_supplier_payment").click(function ()
{
    if(confirm("Are You Sure want to delete this debit note?")) {

        var ids = [];

        $('input[name="delete_debit_receipt[]"]:checked').each(function()
        {
            ids.push($(this).val());
        });


        if(ids.length > 0)
        {
            var data = {
                "deleted_id": ids
            };
            var url = "supplier_payment_delete";
            var type = "POST";
            callroute(url, type, data, function (data)
            {
                var dta = JSON.parse(data);

                if (dta['Success'] == "True")
                {
                    toastr.success(dta['Message']);
                    resettable('supplier_debit_receipt_data');
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
