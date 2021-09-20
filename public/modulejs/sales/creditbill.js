var sel ='';
 var balance=0;

$('.chkbox12').click(function(e) {
            var overall =   0;
            var overdetail ='';
             sel = $('input[class=chkbox12]:checked').map(function(_, el) {
            return $(el).val();

        }).get();

        $('.mCamera').val(sel);
        var arr = $('.mCamera').val().split(',');

        $.each(arr,function(i){
        overall     +=      parseFloat($('.overallbal_'+ arr[i]+'').val());

        }); 

        $('#grandoverall').html(overall.toFixed(2));
        $('#total_amount').val(overall.toFixed(2));
        $('#cash').val(overall.toFixed(2));



});
$('#total_amount').keyup(function(e){

    var total_amount  = $('#total_amount').val();
    var grandoverall  = $('#grandoverall').html();

    if(Number(total_amount)>Number(grandoverall))
    {
       toastr.error("Amount cannot be greater than the selected invoices");
       $('#total_amount').val(grandoverall);
       return false;
    }

});

function resetcreditnotefilterdata(){
    $("#fromtodate").val('');
    $("#searchcustomerdata").val('');
    $("#cbillno").val('');
    var data = {};
    var page = 1;
    var sort_type = $("#hidden_sort_type").val();
    var sort_by = $("#hidden_column_name").val();
    fetch_data('datewise_cuscreditnotedetail',page,sort_type,sort_by,data,'view_creditnote_record');
}
function resetcreditbalfilterdata(){
    $("#fromtodate").val('');
    $("#searchcustomerdata").val('');
    $("#cbillno").val('');
    var data = {};
    var page = 1;
    var sort_type = $("#hidden_sort_type").val();
    var sort_by = $("#hidden_column_name").val();
    fetch_data('datewise_cuscreditdetail',page,sort_type,sort_by,data,'view_creditreceipt_record');
}
$('#makepayment').click(function(e) {
    var grandoverall        =       $('#grandoverall').html();

    if(Number(grandoverall)==0)
    {
        toastr.error("Select Invoices for payment");
    }
    else
    {
            $("#addcuspaymentpopup").modal('show');

    }
});

$("#invoice_date").datepicker({
    format: 'dd-mm-yyyy',
    orientation: "bottom"

});

$('#cheque').keyup(function(e){
    
    if(($('#cheque').val())>0)
    {
        if(($('#remarks').val())=='')
        {       
           toastr.error("First enter Cheque no and Bank details")
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
            var grand_total                 =     $('#total_amount').val();
            var cash_balance    =      0;

        
              cash_balance    =     Number(grand_total)-Number(card)-Number(cheque)-Number(net_banking)-Number(wallet);
              
              if(Number(cash_balance)<0)
              {
                toastr.error("Amout cannot be greater than Total Sales_amount "+grand_total);
                
                $('#cheque').val(0);
                cash_balance    =     Number(grand_total)-Number(net_banking)-Number(wallet)-Number(card);
               
                $('#cash').val(cash_balance.toFixed(0));
              }
              else
              {
               
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
            var grand_total                 =     $('#total_amount').val();
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
            var grand_total                 =     $('#total_amount').val();
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
            var grand_total                 =     $('#total_amount').val();
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
            var grand_total                 =     $('#total_amount').val();
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
$('#total_amount').keyup(function(e){

       
            var grand_total                 =     $('#total_amount').val();
            $('#card').val('');
            $('#cheque').val('');
            $('#net_banking').val('');
            $('#wallet').val('');
           
            $('#cash').val(grand_total);
           

});
$("#addpayment").click(function (e) {
    $(this).prop('disabled', true);
  


      var array = [];
$('#view_bill_record tr').has('td').each(function()
      {
          var arrayItem = {};
          $('td', $(this)).each(function(index, item)
          {
              var inputname = $(item).attr('id');

                if(inputname != undefined && inputname != '')
                {
                    var wihoutidname = inputname.split('_');
                    var nameforarray = wihoutidname[0];

                    
                      if(nameforarray == 'creditaccountid')
                      {
                              var creditid   =  $('input[id="check_'+wihoutidname[1]+'"]:checked').val();
                              if(creditid != undefined)
                              {
                              arrayItem['customer_creditaccount_id'] =$(this).find('input[id="check_'+wihoutidname[1]+'"]:checked').val();
                              arrayItem['credit_amount'] =$(this).find("#balanceamount_"+wihoutidname[1]).val();
                              arrayItem['customer_creditreceipt_detail_id'] =$(this).find("#customercreditreceiptdetailid_"+wihoutidname[1]).val();
                              array.push(arrayItem);
                              }
                              

                          
                      }
                      

                }

          });
      
          
      });
      


      var arraydetail = [];
      arraydetail.push(array);

     

      var customerdetail = {};
      var paymentdetail = {};

      customerdetail['mCamera'] = $("#mCamera").val();
      customerdetail['amounttocheck'] = $("#grandoverall").html();
      customerdetail['invoice_no'] = $("#receipt_no").val();
      customerdetail['invoice_date'] = $("#invoice_date").val();
      customerdetail['total_amount'] = $("#total_amount").val();
      customerdetail['remarks'] = $("#remarks").val();
      customerdetail['customer_id'] = $("#customerid").val();
      customerdetail['customer_creditreceipt_id'] = $("#customer_creditreceipt_id").val();

      $("#totalamtdiv").each(function(){
         $(this).find('.row').each(function ()
         {
             var fieldname = ($(this).find('input').attr('id'));
             customerdetail[fieldname] = $("#"+fieldname).val();
         });

      });
      arraydetail.push(customerdetail);

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

                 parr.push({
                     id: paymentid,
                     value: $("#"+paymentmethod).val(),
                     sales_payment_id: $("#sales_payment_detail"+paymentid).val()
                 });
             }
         });

      });

      arraydetail.push(parr);

     console.log(arraydetail);
     //return false;
      
      var data = arraydetail;

      var  url = "save_customer_creditdetails";
      var type = "POST";
      callroute(url,type,data,function (data)
      {

          $("#addpayment").prop('disabled', true);
          var dta = JSON.parse(data);

          if(dta['Success'] == "True")
          {
            
               toastr.success(dta['Message']);
               window.location.href = dta['url'];
               
            
              //$("#sproduct_detail_record").empty('');
              
          }
          else
          {
            $("#addpayment").prop('disabled', true);
               toastr.error(dta['Message']);
          }
      })

  
});
$("#searchcustomerdata").typeahead({

    source: function(request, process) {
       $.ajax({
           url: "viewbillcustomer_search",
           dataType: "json",
           data: {
                search_val: $("#searchcustomerdata").val(),
                term: request.term
            },
           success: function (data) {$("#searchcustomerdata").val()
                    process(data);
           }
     });
    },
    hint: true,
    highlight: true,
    minLength: 1,
    autoSelect:false,
   
     
});

$("#cbillno").typeahead({

    source: function(request, process) {
       $.ajax({
           url: "cbillno_search",
           dataType: "json",
           data: {
                search_val: $("#cbillno").val(),
                term: request.term
            },
           success: function (data) {$("#cbillno").val()
                    process(data);

                
           }
     });
    },
    
    minLength: 1,
    autoselect:false,
 
     
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
function nodeletereceipt(obj)
{
    
    toastr.error("This receipt is automatically generated through return bill so Cannot be deleted");
    return false;

        
}

function viewcreditnote(obj){



    var id                        =     $(obj).attr('id');
    var bill_no                   =     $(obj).attr('id').split('viewreceipt_')[1]; 
   
      
        var url                       =     'view_creditnote_popup';
        $.ajax({
            url:url,

            data: {
               
                billno:bill_no,
                
            },
            success:function(data)
            {
                $('.popup_values').html('');
                $('.popup_values').html(data);

                $("#creditnotepopup").modal('show');
            }
        })
    
}
