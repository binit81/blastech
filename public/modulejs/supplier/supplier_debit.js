var payment_detail_id ='';
var balance=0;
$('.debit_chck').click(function(e)
{
    var arr = [];

    var overall =   0;
    var overdetail ='';
    $('input[class=debit_chck]:checked').each(function (index)
    {
        arr.push($(this).data('id'));
    });



    $.each(arr,function(i,val)
    {
        overall += parseFloat($('#outstanding_amount_'+val+'').val());

    });


    $('#grandoverall').html(overall.toFixed(2));
    $('#total_amount_pay').val(overall.toFixed(2));
    $('#cash').val(overall.toFixed(2));



});
$('#total_amount_pay').keyup(function(e){

    var total_amount_pay  = $('#total_amount_pay').val();
    var grandoverall  = $('#grandoverall').html();

    if(Number(total_amount_pay)>Number(grandoverall))
    {
        toastr.error("Amount cannot be greater than the payment_detail_idected invoices");
        $('#total_amount_pay').val(grandoverall);
        return false;
    }

});

$('#makepayment').click(function(e) {
    var grandoverall =  $('#grandoverall').html();

    if(Number(grandoverall)==0)
    {
        toastr.error("Select Supplier for payment");
    }
    else
    {
        $("#supplier_debit_popup").modal('show');

    }
});

$("#debit_date").datepicker({
    format: 'dd-mm-yyyy',
    orientation: "bottom"

});

$('#cheque').keyup(function(e){

    if(($('#cheque').val())>0)
    {
        if(($('#remarks').val())=='')
        {
            toastr.error("First enter Cheque no and Bank details");
            $('#cheque').val('');
            $('#remarks').focus();
            return false;
        }
        else
        {
            var cash                        =     $('#cash').val();
            var card                        =     $('#card').val();
            var cheque                      =     $('#cheque').val();
            var net_banking                 =     $('#net_banking').val();
            var wallet                      =     $('#wallet').val();
            var outstanding_amount          =     $('#outstanding_amount').val();
            var grand_total                 =     $('#total_amount_pay').val();
            var cash_balance    =      0;


            cash_balance    =     Number(grand_total)-Number(card)-Number(cheque)-Number(net_banking)-Number(wallet);

            if(Number(cash_balance)<0)
            {
                toastr.error("Amout cannot be greater than Total Sales_amount "+grand_total);

                $('#cheque').val(0);
                cash_balance    =     Number(grand_total)-Number(net_banking)-Number(wallet)-Number(card);
                alert(cash_balance)
                $('#cash').val(cash_balance.toFixed(0));
            }
            else
            {
                alert(cash_balance)
                $('#cash').val(cash_balance);
            }
        }
    }

});
$('#net_banking').keyup(function(e){

    if(($('#net_banking').val())>0)
    {
        if(($('#remarks').val())=='')
        {
            toastr.error("First enter Bank details");
            $('#remarks').focus();
            $('#net_banking').val('');

            return false;
        }
        else
        {
            var cash                        =     $('#cash').val();
            var card                        =     $('#card').val();
            var cheque                      =     $('#cheque').val();
            var net_banking                 =     $('#net_banking').val();
            var wallet                      =     $('#wallet').val();
            var outstanding_amount          =     $('#outstanding_amount').val();
            var grand_total                 =     $('#total_amount_pay').val();
            var cash_balance    =      0;


            cash_balance    =     Number(grand_total)-Number(card)-Number(cheque)-Number(net_banking)-Number(wallet);

            if(Number(cash_balance)<0)
            {

                toastr.error("Amout cannot be greater than Total Sales_amount "+grand_total);

                $('#net_banking').val(0);
                cash_balance    =     Number(grand_total)-Number(cheque)-Number(wallet)-Number(card);
                $('#cash').val(cash_balance);
            }
            else
            {

                $('#cash').val(cash_balance);
            }
        }

    }

});
$('#card').keyup(function(e){

    var cash                        =     $('#cash').val();
    var card                        =     $('#card').val();
    var cheque                      =     $('#cheque').val();
    var net_banking                 =     $('#net_banking').val();
    var wallet                      =     $('#wallet').val();
    var outstanding_amount          =     $('#outstanding_amount').val();
    var grand_total                 =     $('#total_amount_pay').val();
    var cash_balance    =      0;

    cash_balance    =     Number(grand_total)-Number(card)-Number(cheque)-Number(net_banking)-Number(wallet);


    if(Number(cash_balance)<0)
    {
        toastr.error("Amout cannot be greater than Total Sales_amount "+grand_total);

        $('#card').val(0);
        cash_balance    =     Number(grand_total)-Number(cheque)-Number(net_banking)-Number(wallet);
        $('#cash').val(cash_balance);

    }
    else
    {

        $('#cash').val(cash_balance);
    }




});
$('#wallet').keyup(function(e){

    var cash                        =     $('#cash').val();
    var card                        =     $('#card').val();
    var cheque                      =     $('#cheque').val();
    var net_banking                 =     $('#net_banking').val();
    var wallet                      =     $('#wallet').val();
    var outstanding_amount          =     $('#outstanding_amount').val();
    var grand_total                 =     $('#total_amount_pay').val();
    var cash_balance    =      0;


    cash_balance    =     Number(grand_total)-Number(card)-Number(cheque)-Number(net_banking)-Number(wallet);


    if(Number(cash_balance)<0)
    {
        toastr.error("Amout cannot be greater than Total Sales_amount "+grand_total);

        $('#wallet').val(0);
        cash_balance    =     Number(grand_total)-Number(cheque)-Number(net_banking)-Number(card);
        $('#cash').val(cash_balance);
    }
    else
    {

        $('#cash').val(cash_balance);
    }




});
$('#cash').keyup(function(e){

    var cash                        =     $('#cash').val();
    var card                        =     $('#card').val();
    var cheque                      =     $('#cheque').val();
    var net_banking                 =     $('#net_banking').val();
    var wallet                      =     $('#wallet').val();
    var outstanding_amount          =     $('#outstanding_amount').val();
    var grand_total                 =     $('#total_amount_pay').val();
    var cash_balance    =      0;


    cash_balance    =     Number(grand_total)-Number(card)-Number(cheque)-Number(net_banking)-Number(wallet);
    $('#cash').val(cash_balance);

    if(Number(cash_balance)<0)
    {
        toastr.error("Amout cannot be greater than Total Sales_amount "+grand_total);

        $('#cash').val(0);
        cash_balance    =     Number(grand_total)-Number(cheque)-Number(net_banking)-Number(wallet)-Number(card);
        $('#cash').val(cash_balance);
    }




});
$('#total_amount_pay').keyup(function(e){
    var grand_total   =  $('#total_amount_pay').val();
    $('#card').val('');
    $('#cheque').val('');
    $('#net_banking').val('');
    $('#wallet').val('');
    $('#debit_note').val('');
    $('#cash').val(grand_total);
});


$("#add_supplier_payment").click(function (e)
{
    var arraydetail = [];
    $(this).prop('disabled', true);
    var array = [];
    $('#outstanding_detail tr').each(function()
    {
       var row_id = $(this).data('id');
        if($(this).find('#check_'+row_id).is(':checked'))
        {
            if(row_id != undefined && row_id != '')
            {
                var arrayItem = {};
                arrayItem['supplier_payment_detail_id'] = row_id;
                arrayItem['inward_stock_id'] = $(this).find("#inward_stock_id_"+row_id).val();
                array.push(arrayItem);
            }
        }
    });

    arraydetail['debit_detail'] = array;

    var supplier_receipt = [];
    var paymentdetail = {};

    supplier_receipt.push({
        total_amount_pay : $("#total_amount_pay").val(),
        debit_date : $("#debit_date").val(),
        remarks : $("#remarks").val(),
        receipt_no : $("#receipt_no").val(),
        supplier_gst_id : $("#supplier_gst_id").val(),
        total : $(".ledgerbalance").html(),
    });


    var parr =[];
    $("#paymentmethoddiv").each(function()
    {
        var paymentarr = {};
        $(this).find('.row').each(function (index,item)
        {
            var paymentmethod = ($(this).find('input').attr('id'));

            if($("#"+paymentmethod).val() != '' && $("#"+paymentmethod).val() != 0)
            {
                var paymentid = $("#"+paymentmethod).data("id");
                   var supplier_debit_note_id = '';
                if(paymentid == 9)
                {
                    supplier_debit_note_id = $("#supplier_debit_note_id").val()
                }
                parr.push({
                    id: paymentid,
                    value: $("#"+paymentmethod).val(),
                    supplier_debit_note_id : supplier_debit_note_id
                });
            }
        });
    });

    arraydetail['payment_detail'] = parr;

    var data={
        'debit_detail' : array,
        'payment_detail' : parr,
        'supplier_receipt' : supplier_receipt
    };
    var  url = "save_supplier_debitdetail";
    var type = "POST";
    callroute(url,type,data,function (data)
    {
        $("#add_supplier_payment").prop('disabled', true);
        var dta = JSON.parse(data);

        if(dta['Success'] == "True")
        {

            toastr.success(dta['Message']);
            window.location.href = dta['url'];


            //$("#sproduct_detail_record").empty('');

        }
        else
        {
            $("#add_supplier_payment").prop('disabled', true);
            toastr.error(dta['Message']);
        }
    })


});

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
            //this help to call a function when payment_detail_idect search suggetion
            payment_detail_idect: function (event, ui) {
                var id = ui.item.id;
                //call a getproductdetail function for getting product detail based on payment_detail_idected product from suggetion


            }
        });
    }
    else
    {
        $("#searchcustomerdata").empty();
    }

});
function deletereceipt(obj)
{
    if(confirm("Are You Sure want to delete this Credit Receipt?")) {

        var id                        =     $(obj).attr('id');
        var billid                    =     $(obj).attr('id').split('deletereceipt_')[1];


        if(billid.length > 0)
        {
            var data = {
                "deleted_id": billid
            };
            var url = "receipt_delete";
            var type = "POST";
            callroute(url, type, data, function (data) {

                var dta = JSON.parse(data);

                if (dta['Success'] == "True")
                {
                    toastr.success(dta['Message']);
                    $("#viewbillform").trigger('reset');
                    resettable('viewreceipt_data');

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
}



//DEBIT NOTE

$("#debit_note").keypress(function () {
    return false;
});
$("#debit_note").focus(function ()
{
    var cash = $("#cash").val();

    if(cash == 0 || cash == '')
    {
        toastr.error("Add some amount in default payment method cash amount!");
        return false;
    }
    else
    {
        $("#supplierdebitnotepopup").modal('show');
    }
});


$("#supplier_debit_note").focusout(function(){
    var type = "POST";
    var url = "get_debit_note_amount";

    var data={
        'debit_note_no' : $("#supplier_debit_note").val()
    }

    callroute(url,type,data,function (data) {

        var dta = JSON.parse(data);

        if(dta['Success']=="True")
        {
            var amount_detail = dta['Data'];

            if(amount_detail != null && amount_detail['total_cost_price'] != undefined && amount_detail['used_amount'] != undefined)
            {
                var edit_time_debit_add = $("#debit_note").val();
                var debit_amount = (((Number(amount_detail['total_cost_price']))-(Number(amount_detail['used_amount'])) + Number(edit_time_debit_add)));

                if(debit_amount == 0)
                {
                    toastr.error("This Debit note all amount was used!Select some other Debit Note!");
                    return false;
                }
                $("#supplier_debit_note_amount").val(debit_amount);
                $("#supplier_debit_note_amount_for_minus").val(debit_amount);
                $("#supplier_debit_note_id").val(amount_detail['debit_note_id']);
            }
            else{
                $("#debit_note_no").val('');
                toastr.error("Debit Note No. is invalid!");
                return false;
            }
        }
    });
});

$("#supplier_debit_note_issue_amount").keyup(function ()
{
    var total_amount = $("#supplier_debit_note_amount").val();

    var issue_amount = $("#supplier_debit_note_issue_amount").val();
    var minus_from = $("#supplier_debit_note_amount_for_minus").val();

    var with_minus_value = ((Number(minus_from))-(Number(issue_amount)));
    $("#supplier_debit_note_amount").val(with_minus_value);

    if(Number(issue_amount)>Number(minus_from))
    {
        toastr.error("Issue Amount can not be greater than "+ total_amount);
        $("#supplier_debit_note_issue_amount").val(0);
        $("#supplier_debit_note_amount").val(total_amount);
    }
    var inward_total_amt = $("#grand_total_disp").val();

    if(Number(issue_amount)>Number(inward_total_amt))
    {
        toastr.error("Issue Amount can not be greater than total amount "+ inward_total_amt);
        $("#supplier_debit_note_issue_amount").val(0);
        $("#supplier_debit_note_amount").val(total_amount);
    }
    var outstandingamt = $("#outstanding_amount").val();
    if(Number(issue_amount) > outstandingamt)
    {
        toastr.error("Issue Amount can not be greater than unpaid amount "+ outstandingamt);
        $("#supplier_debit_note_issue_amount").val(0);
        $("#supplier_debit_note_amount").val(minus_from);
    }


});

$("#supplier_save_debit_note").click(function ()
{
    var debit_note_issue_amt = $("#supplier_debit_note_issue_amount").val();

    if(debit_note_issue_amt != '')
    {
        $("#debit_note").val(debit_note_issue_amt);

        var outstanding_amount = ((Number($("#cash").val())) - (Number(debit_note_issue_amt)));

        if(outstanding_amount != '' || outstanding_amount == 0)
        {
            if (debit_note_issue_amt == 0)
            {
                var outstanding = ((Number($("#cash").val())) - Number(outstanding_amount));
                outstanding_amount = ((Number($("#cash").val())) + Number(outstanding));
            }

            $("#cash").val(outstanding_amount);
        } else {
            $("#supplier_debit_note").val(0);
            toastr.error("Add some amount in default payment method unpaid amount!");
        }

        $("#supplierdebitnotepopup").modal('hide');
    }
    else
    {
        toastr.error("Fill up proper debit note detail and amount!");
    }
});
//END OF DEBIT NOTE