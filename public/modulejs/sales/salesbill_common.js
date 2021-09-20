$("#refname").typeahead({

    source: function(request, process) {
       $.ajax({
           url: "refname_search",
           dataType: "json",
           data: {
                search_val: $("#refname").val(),
                term: request.term
            },
           success: function (data) {$("#refname").val()
                    process(data);

                
           }
     });
    },
    
    minLength: 1,
    autoselect:false,
 
     
});
$("#creditnoteno").typeahead({

    source: function(request, process) {
       $.ajax({
           url: "creditnote_numbersearch",
           dataType: "json",
           data: {
                search_val: $("#creditnoteno").val(),
                term: request.term
            },
           success: function (data) {$("#creditnoteno").val()
                    process(data);

                
           }
     });
    },
    
    minLength: 1,
    autoselect:false,

    afterSelect: function (item) {
        var value = item;
        
         getcreditnote_detail(item);   
    }
 
     
});

$("#duedate").datepicker({
    format:'dd-mm-yyyy',
    startDate: '+1d',
    autoclose: true,
    todayHighlight:false,
}).on('changeDate',function(ev){
    var date_get = new Date();
    var date = $("#duedate").val();
    var supplier_date = date.split('-');

    var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
    var firstDate = new Date(supplier_date[2],supplier_date[1],supplier_date[0]);
    var secondDate = new Date(date_get.getFullYear(),(date_get.getMonth()+1),date_get.getDate());

    var diffDays = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime())/(oneDay)));

    if(diffDays != '' && diffDays != 0 ) {
        $("#duedays").val(diffDays);
    }else
    {
        $("#duedays").val('');
        $("#duedate").val('');
    }

});

// $("#duedate").datepicker({
//     format:'dd-mm-yyyy',
//     startDate: '+1d',
//     autoclose: true

// });
$('#duedays').keyup(function(e){

  var pduedays   =  $('#duedays').val();

      if(pduedays!='' && pduedays!=0)
          {
              $("#duedays").val(pduedays);
              var futDate  = DateHelper.format(DateHelper.addDays(new Date(), Number(pduedays)));
              $('#duedate').val(futDate);
          }
          else
          {
            $("#duedays").val('');
            $("#duedate").val('');
          }   

});
  

function addcharges(obj)
{
    var id                        =     $(obj).attr('id');
    var charges_id                =     $(obj).attr('id').split('chargesamt_')[1];

    var chargesamt                =     $('#chargesamt_'+charges_id).val();
    var cmaxgst                   =     $('#csprodgstper_'+charges_id).val();


    var maxgst = 0;
    $('.sprodgstper').each(function() {
      var value = parseFloat($(this).html());
      maxgst = (value > maxgst) ? value : maxgst;
     
    });

    if(Number(cmaxgst)!='' && Number(cmaxgst)!=0)
    {
        maxgst    =   cmaxgst;
    }
    else
    {
       maxgst    =   maxgst;
    }
    
    $('#csprodgstper_'+charges_id).val(maxgst);
    $('#cprodgstper_'+charges_id).html(maxgst);

    var cprodgstamt     =    Number(chargesamt)   * Number(maxgst) / 100;

    $('#csprodgstamt_'+charges_id).html(Number(cprodgstamt).toFixed(2));
    $('#cprodgstamt_'+charges_id).html(Number(cprodgstamt).toFixed(4));

    var ctotalamt      =  Number(chargesamt) + Number(cprodgstamt);
    $('#ctotalamount_'+charges_id).html(Number(ctotalamt).toFixed(4));
    $('#cstotalamount_'+charges_id).val(Number(ctotalamt).toFixed(2));
    totalcharges();

}
function taddcharges(obj)
{
    var id                        =     $(obj).attr('id');
    var charges_id                =     $(obj).attr('id').split('cstotalamount_')[1];

    var tchargesamt                =     $('#cstotalamount_'+charges_id).val();
    var cmaxgst                   =      $('#csprodgstper_'+charges_id).val();


    var maxgst = 0;
    $('.sprodgstper').each(function() {
      var value = parseFloat($(this).html());
      maxgst = (value > maxgst) ? value : maxgst;
     
    });

    if(Number(cmaxgst)!='' && Number(cmaxgst)!=0)
    {
        maxgst    =   cmaxgst;
    }
    else
    {
       maxgst    =   maxgst;
    }
    
    $('#csprodgstper_'+charges_id).val(maxgst);
    $('#cprodgstper_'+charges_id).html(maxgst);

    var cprodgstamt     =   (Number(tchargesamt)/(Number(maxgst)+100))   * Number(maxgst);
    var chargesamt      =    Number(tchargesamt) - Number(cprodgstamt);

    $('#csprodgstamt_'+charges_id).html(Number(cprodgstamt).toFixed(2));
    $('#cprodgstamt_'+charges_id).html(Number(cprodgstamt).toFixed(4));

    $('#ctotalamount_'+charges_id).html(Number(tchargesamt).toFixed(4));
    $('#chargesamt_'+charges_id).val(Number(chargesamt).toFixed(4));
    totalcharges();

}
function chargesgst(obj)
{
    var id                        =     $(obj).attr('id');
    var charges_id                =     $(obj).attr('id').split('csprodgstper_')[1];

    var chargesamt                =     $('#chargesamt_'+charges_id).val();
    var maxgst                    =     $('#csprodgstper_'+charges_id).val();
   
    $('#cprodgstper_'+charges_id).html(maxgst);
    console.log(charges_id);

    var cprodgstamt     =    Number(chargesamt)   * Number(maxgst) / 100;

    $('#csprodgstamt_'+charges_id).html(Number(cprodgstamt).toFixed(2));
    $('#cprodgstamt_'+charges_id).html(Number(cprodgstamt).toFixed(4));

    var ctotalamt      =  Number(chargesamt) + Number(cprodgstamt);
    $('#ctotalamount_'+charges_id).html(Number(ctotalamt).toFixed(4));
    $('#cstotalamount_'+charges_id).val(Number(ctotalamt).toFixed(2));
    totalcharges();

}
function calculatetotalcharges()
{
     $("#charges_record").each(function (index,e)
      {
           
             $(this).find('tr').each(function ()
             {
                if($(this).attr('id') != undefined)
                {
                    rcolumn = $(this).attr('id').split('charges_')[1];
                    
                 }    
                  var maxgst = 0;
                  $('.sprodgstper').each(function() {
                    var value = parseFloat($(this).html());
                    maxgst = (value > maxgst) ? value : maxgst;
                   
                  });

                  if(($("#chargesname_"+rcolumn).html())!='')
                  {
                     
                      var chargesamt                =     $('#chargesamt_'+rcolumn).val();
                      var cmaxgst                   =     $('#csprodgstper_'+rcolumn).val();

                      if(Number(cmaxgst)!='' && Number(cmaxgst)!=0)
                      {
                          maxgst    =   cmaxgst;
                      }
                      else
                      {
                         maxgst    =   maxgst;
                      }
                      console.log(maxgst);
                      alert()

                       $("#csprodgstper_"+rcolumn).val(maxgst);
                       $("#cprodgstper_"+rcolumn).html(maxgst);



                      var cprodgstamt     =    Number(chargesamt)   * Number(maxgst) / 100;

                      $('#csprodgstamt_'+rcolumn).html(Number(cprodgstamt).toFixed(2));
                      $('#cprodgstamt_'+rcolumn).html(Number(cprodgstamt).toFixed(4));

                      var ctotalamt      =  Number(chargesamt) + Number(cprodgstamt);
                      $('#ctotalamount_'+rcolumn).html(Number(ctotalamt).toFixed(4));
                      $('#cstotalamount_'+rcolumn).html(Number(ctotalamt).toFixed(2));
                      totalcharges();
                  

                  }
                


             });

          


        });
}
function productdetailpopup(obj)
{

  var id                        =     $(obj).attr('id');
  var product_id                =     $(obj).attr('id').split('popupid_')[1];
   var url                       =     'product_popup_values';
        $.ajax({
            url:url,

            data: {
               
                productid:product_id,
                
            },
            success:function(data)
            {
                $('.productpopup_values').html('');
                $('.productpopup_values').html(data);
                $("#productdetailpopup").modal('show');  
            }
        })
   
}

function totalcharges()
{
    var totcharges = 0;
   
    
    $('.ctotalamount').each(function (index,e){
      if($(this).html()!="")
      totcharges   +=   parseFloat($(this).html());
     
    });

    $('#scharges_total').val(Number(totcharges).toFixed(2));
    $('#charges_total').val(Number(totcharges).toFixed(4));

     var grand_total     =   $('#grand_total').val();
     var totgrand_total  =   Number(grand_total) + Number(totcharges);
     $('#ggrand_total').val(Number(totgrand_total).toFixed(4));
    $('#sggrand_total').val(Number(totgrand_total).toFixed(decimal_points));
    paymentmode();

}

///overall calculation
function totalcalculation()
{
    var sales_total = 0;
    var totalgst=0;
    var saleswithoutgst = 0;
    var saleswithoutdiscount =0;
    var salesdiscount =0;
    var roomwisediscount =0;
    var saleswithgst =0;
    var prodwisediscount = 0;
    var totalqty = 0;
    var cotcharges = 0;


    
    $('.totalsellinggst').each(function (index,e){
      if($(this).html()!="")
      saleswithgst   +=   parseFloat($(this).html());
     
     
    });
     $('.pproddiscamt').each(function (index,e){
      if($(this).val()!="")
      prodwisediscount   +=   parseFloat($(this).val());
     
     
    });
    

     $('.totalsellingwgst').each(function (index,e){
      if($(this).html()!="")
      saleswithoutgst   +=   parseFloat($(this).html());
     
     
    });
    $('.overallpproddiscamt').each(function (index,e){
      if($(this).val()!="")
      roomwisediscount   +=   parseFloat($(this).val());
     
     
    });


    $('.tsellingaftergst').each(function (index,e){
      if($(this).html()!="")
      sales_total   +=   parseFloat($(this).html());
     
     
    });
    $('.totalgstamt').each(function (index,e){
      if($(this).html()!="")
      totalgst   +=   parseFloat($(this).html());
      
    
    });

    $('.totqty').each(function (index,e){
      if($(this).val()!="")
      totalqty   +=   parseFloat($(this).val());
      
    
    });
    
    totalcharges();

       cotcharges     =    $('#charges_total').val();
    var cgrandtotal   =    Number(cotcharges)  + Number(sales_total);
   
    var partialgst          =   Number(totalgst)/2;

    $('#overallqty').val(totalqty);    
    $('#prodwise_discountamt').val(prodwisediscount.toFixed(2));
    $('#totalwithout_gst').val(saleswithoutgst.toFixed(4));
    $('#roomwisediscount_amount').val(roomwisediscount.toFixed(4));
    $('#total_cgst').val(partialgst.toFixed(4));
    $('#total_sgst').val(partialgst.toFixed(4));
    $('#total_igst').val(totalgst.toFixed(4));
    $('#sales_total').val(saleswithgst.toFixed(4));
    
    $('#grand_total').val(sales_total.toFixed(4));

    $('#showtotalwithout_gst').val(saleswithoutgst.toFixed(2));
    $('#showtotal_cgst').val(partialgst.toFixed(2));
    $('#showtotal_sgst').val(partialgst.toFixed(2));
    $('#showsales_total').val(saleswithgst.toFixed(2));
    
    $('#showgrand_total').val(sales_total.toFixed(2));
    $('#ggrand_total').val(Number(cgrandtotal).toFixed(4));
    $('#sggrand_total').val(Number(cgrandtotal).toFixed(decimal_points));
   
    //console.log(decimal_points);

        sales_total   =  sales_total.toFixed(decimal_points);


            var creditaccountid             =     $('#creditaccountid').val();
            var creditbalcheck              =     $('#creditbalcheck').val();
            var creditbalance               =     $('#creditbalance').val();

            if(creditaccountid !='')
            {
                if(Number(creditbalcheck)==0)
                {
                    var cash                        =     $('#cash').val();
                    var card                        =     $('#card').val();
                    var cheque                      =     $('#cheque').val();
                    var net_banking                 =     $('#net_banking').val();
                    var wallet                      =     $('#wallet').val();
                    var outstanding_amount          =     $('#outstanding_amount').val();
                    var credit_note                 =     $('#credit_note').val();
                    var grand_total                 =     $('#sggrand_total').val();
                    var cash_balance    =      0;
                    
                
                      cash_balance    =     Number(grand_total)-Number(card)-Number(cheque)-Number(net_banking)-Number(wallet)-Number(outstanding_amount)-Number(credit_note);
                      
                      if(Number(cash_balance)<0)
                      {

                        //toastr.error("Amout cannot be greater than Total Sales_amount "+grand_total);

                        $('#card').val(0);
                        $('#cheque').val(0);
                        $('#net_banking').val(0);
                        $('#wallet').val(0);
                        $('#outstanding_amount').val(0);

                        cash_balance    =     Number(grand_total);
                        $('#cash').val(cash_balance.toFixed(decimal_points));
                       
                      }
                      else
                      {
                        
                          $('#cash').val(cash_balance.toFixed(decimal_points));
                         
                      } 
                }
                else
                {
                        var cash                        =     $('#cash').val();
                        var card                        =     $('#card').val();
                        var cheque                      =     $('#cheque').val();
                        var net_banking                 =     $('#net_banking').val();
                        var wallet                      =     $('#wallet').val();
                        var outstanding_amount          =     $('#outstanding_amount').val();
                        var credit_note                 =     $('#credit_note').val();
                        var grand_total                 =     $('#sggrand_total').val();
                        var cash_balance    =      0;

                
                      cash_balance    =     Number(grand_total)-Number(card)-Number(cheque)-Number(net_banking)-Number(wallet)-Number(outstanding_amount)-Number(credit_note);
                     

                      if(Number(cash_balance)<0)
                      {
                        
                        $('#outstanding_amount').val(0);
                        cash_balance    =     Number(grand_total)-Number(cheque)-Number(net_banking)-Number(card)-Number(wallet)-Number(outstanding_amount)-Number(credit_note);
                        
                          $('#cash').val(cash_balance.toFixed(decimal_points));
                         
                        toastr.error("Outstanding Amount has already been received so cannot modify this amount");
                      }
                      else
                      {
                          
                          $('#cash').val(cash_balance.toFixed(decimal_points));
                         
                      }
                    
                    $('#outstanding_amount').val(creditbalance);
                    return false;
                }
            }
            else
            {

                    var cash                        =     $('#cash').val();
                    var card                        =     $('#card').val();
                    var cheque                      =     $('#cheque').val();
                    var net_banking                 =     $('#net_banking').val();
                    var wallet                      =     $('#wallet').val();
                    var outstanding_amount          =     $('#outstanding_amount').val();
                    var credit_note                 =     $('#credit_note').val();
                    var grand_total                 =     $('#sggrand_total').val();
                    var cash_balance    =      0;
                    
                
                      cash_balance    =     Number(grand_total)-Number(card)-Number(cheque)-Number(net_banking)-Number(wallet)-Number(outstanding_amount)-Number(credit_note);
                      
                      if(Number(cash_balance)<0)
                      {

                        toastr.error("Amout cannot be greater than Total Sales_amount "+grand_total);

                        $('#card').val(0);
                        $('#cheque').val(0);
                        $('#net_banking').val(0);
                        $('#wallet').val(0);
                        $('#outstanding_amount').val(0);

                        cash_balance    =     Number(grand_total);
                        $('#cash').val(cash_balance.toFixed(decimal_points));
                        
                      }
                      else
                      {
                        $('#cash').val(cash_balance.toFixed(decimal_points));
                         
                      } 


            }

           
    
   
}
$('#cheque').on("input", function() {
  
    // if(($('#cheque').val())>0)
    // {
        if(($('#chequeno').val())=='' || ($('#bankname').val())=='')
        {       
           toastr.error("First enter Cheque no and Bank details")
           $('#cheque').val('');
           $('.chequedetails').show();
           $('.netbankingdetails').hide();
           $('.outstandingdetails').hide();
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
            var credit_note                 =     $('#credit_note').val();
            var grand_total                 =     $('#sggrand_total').val();
            var cash_balance    =      0;

        
              cash_balance    =     Number(grand_total)-Number(card)-Number(cheque)-Number(net_banking)-Number(wallet)-Number(outstanding_amount)-Number(credit_note);
              
              if(Number(cash_balance)<0)
              {
                toastr.error("Amout cannot be greater than Total Sales_amount "+grand_total);
                
                $('#cheque').val(0);
                cash_balance    =     Number(grand_total)-Number(net_banking)-Number(wallet)-Number(card)-Number(outstanding_amount)-Number(credit_note);
                $('#cash').val(cash_balance.toFixed(decimal_points));
               
               
              }
              else
              {
                $('#cash').val(cash_balance.toFixed(decimal_points));
                 
              }
        }
    // }
    
});
$('#creditnoteclose').click(function(e){
      if(creditnoteno!='')
      {
          $('#savecredit').trigger('click');
      }
});
$('#credit_note').on("input", function() {

  var editcreditnotepaymentid   =  $('#editcreditnotepaymentid').val();
  var creditnoteno   =  $('#creditnoteno').val();

  if(Number(editcreditnotepaymentid)!='')
    {
            if(creditnoteno!='')
            {
              $("#addcreditpopup").modal('show'); 
              toastr.error("Kindly empty Credit Note No field! If you do not want to use Credit Note on this bill !");
              $('#creditnoteclose').click(function(e){
                  if(creditnoteno!='')
                  {
                      $('#savecredit').trigger('click');
                  }
              });
             
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
                    var credit_note                 =     $('#credit_note').val();
                    var grand_total                 =     $('#sggrand_total').val();
                    var cash_balance    =      0;

            
                   cash_balance    =     Number(grand_total)-Number(card)-Number(cheque)-Number(net_banking)-Number(wallet)-Number(outstanding_amount)-Number(credit_note);
                  

                 $('#cash').val(cash_balance.toFixed(decimal_points));
                  
           
            }
      }
    
    if(($('#credit_note').val())>0)
    {
     
          $('#credit_note').val('');
          $('.netbankingdetails').hide();
          $('.chequedetails').hide();
          $('.outstandingdetails').hide();
          $("#addcreditpopup").modal('show'); 

       
    }
    else
    {
          $('#creditnote_amount').val(''); 
          $('#issue_amount').val('');  
          $('#creditnoteno').val(''); 
          $('#creditnote_id').val('');
          paymentmode();
    }

});
$('#net_banking').on("input", function() {
    
    // if(($('#net_banking').val())>0)
    // {
        if(($('#netbankname').val())=='')
        {       
            toastr.error("First enter Bank details");
            
           $('#net_banking').val('');
            $('.netbankingdetails').show();
            $('.chequedetails').hide();
            $('.outstandingdetails').hide();
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
            var credit_note                 =     $('#credit_note').val();
            var grand_total                 =     $('#sggrand_total').val();
            var cash_balance    =      0;
            
        
              cash_balance    =     Number(grand_total)-Number(card)-Number(cheque)-Number(net_banking)-Number(wallet)-Number(outstanding_amount)-Number(credit_note);
              
              if(Number(cash_balance)<0)
              {

                toastr.error("Amout cannot be greater than Total Sales_amount "+grand_total);
                
                $('#net_banking').val(0);
                cash_balance    =     Number(grand_total)-Number(cheque)-Number(wallet)-Number(card)-Number(outstanding_amount)-Number(credit_note);
                $('#cash').val(cash_balance.toFixed(decimal_points));
                
              }
              else
              {
                
                $('#cash').val(cash_balance.toFixed(decimal_points));
                
              }
        }
      
    // }

});
$('#card').keyup(function(e){


            $('.netbankingdetails').hide();
            $('.chequedetails').hide();
            $('.outstandingdetails').hide();
            var cash                        =     $('#cash').val();
            var card                        =     $('#card').val();
            var cheque                      =     $('#cheque').val();
            var net_banking                 =     $('#net_banking').val();
            var wallet                      =     $('#wallet').val();
            var outstanding_amount          =     $('#outstanding_amount').val();
            var credit_note                 =     $('#credit_note').val();
            var grand_total                 =     $('#sggrand_total').val();
            var cash_balance    =      0;

          cash_balance    =     Number(grand_total)-Number(card)-Number(cheque)-Number(net_banking)-Number(wallet)-Number(outstanding_amount)-Number(credit_note);
          

          if(Number(cash_balance)<0)
          {
            toastr.error("Amout cannot be greater than Total Sales_amount "+grand_total);
           
            $('#card').val(0);
             cash_balance    =     Number(grand_total)-Number(cheque)-Number(net_banking)-Number(wallet)-Number(outstanding_amount)-Number(credit_note);
              $('#cash').val(cash_balance.toFixed(decimal_points));
           
            
          }
          else
          {
            
             $('#cash').val(cash_balance.toFixed(decimal_points));
               
          }
      

     

});
$('#wallet').keyup(function(e){


            $('.netbankingdetails').hide();
            $('.chequedetails').hide();
            $('.outstandingdetails').hide();
            var cash                        =     $('#cash').val();
            var card                        =     $('#card').val();
            var cheque                      =     $('#cheque').val();
            var net_banking                 =     $('#net_banking').val();
            var wallet                      =     $('#wallet').val();
            var outstanding_amount          =     $('#outstanding_amount').val();
            var credit_note                 =     $('#credit_note').val();
            var grand_total                 =     $('#sggrand_total').val();
            var cash_balance    =      0;

    
          cash_balance    =     Number(grand_total)-Number(card)-Number(cheque)-Number(net_banking)-Number(wallet)-Number(outstanding_amount)-Number(credit_note);
          

          if(Number(cash_balance)<0)
          {
            toastr.error("Amout cannot be greater than Total Sales_amount "+grand_total);
           
            $('#wallet').val(0);
            cash_balance    =     Number(grand_total)-Number(cheque)-Number(net_banking)-Number(card)-Number(outstanding_amount)-Number(credit_note);
              $('#cash').val(cash_balance.toFixed(decimal_points));
               
          }
          else
          {
            
            $('#cash').val(cash_balance.toFixed(decimal_points));
            
          }
      

     

}); 
$('#outstanding_amount').on("input", function() {


            $('.netbankingdetails').hide();
            $('.chequedetails').hide();
            //$('.outstandingdetails').show();
            
            var creditaccountid             =     $('#creditaccountid').val();
            var creditbalcheck              =     $('#creditbalcheck').val();
            var creditbalance               =     $('#creditbalance').val();

            if(creditaccountid !='')
            {
                if(Number(creditbalcheck)==0)
                { 

                   
                            var cash                        =     $('#cash').val();
                            var card                        =     $('#card').val();
                            var cheque                      =     $('#cheque').val();
                            var net_banking                 =     $('#net_banking').val();
                            var wallet                      =     $('#wallet').val();
                            var outstanding_amount          =     $('#outstanding_amount').val();
                            var credit_note                 =     $('#credit_note').val();
                            var grand_total                 =     $('#sggrand_total').val();
                            var cash_balance    =      0;

                    
                          cash_balance    =     Number(grand_total)-Number(card)-Number(cheque)-Number(net_banking)-Number(wallet)-Number(outstanding_amount)-Number(credit_note);
                          

                          if(Number(cash_balance)<0)
                          {
                            toastr.error("Amout cannot be greater than Total Sales_amount "+grand_total);
                           
                            $('#outstanding_amount').val(0);
                            cash_balance    =     Number(grand_total)-Number(cheque)-Number(net_banking)-Number(card)-Number(wallet)-Number(credit_note);
                            $('#cash').val(cash_balance.toFixed(decimal_points));
                           
                          }
                          else
                          {
                            
                            $('#cash').val(cash_balance.toFixed(decimal_points));
                             
                          }
                   
                }
                else
                {

                    toastr.error("Outstanding Amount has already been received so cannot modify this amount");
                    $('#outstanding_amount').val(creditbalance);
                    return false;
                }
            }
            else
            {

              
                      // if(($('#duedays').val())=='' || ($('#duedays').val())=='')
                      // {       
                      //    toastr.error("First Enter Due Days for Outstanding Amount.")
                      //    $('#outstanding_amount').val('');
                      //    $('.chequedetails').hide();
                      //    $('.netbankingdetails').hide();
                      //    $('.outstandingdetails').show();
                      //    return false;
                      // } 
                      // else
                      // {
                        var cash                        =     $('#cash').val();
                        var card                        =     $('#card').val();
                        var cheque                      =     $('#cheque').val();
                        var net_banking                 =     $('#net_banking').val();
                        var wallet                      =     $('#wallet').val();
                        var outstanding_amount          =     $('#outstanding_amount').val();
                        var credit_note                 =     $('#credit_note').val();
                        var grand_total                 =     $('#sggrand_total').val();
                        var cash_balance    =      0;

                
                      cash_balance    =     Number(grand_total)-Number(card)-Number(cheque)-Number(net_banking)-Number(wallet)-Number(outstanding_amount)-Number(credit_note);

                      if(Number(cash_balance)<0)
                      {
                        toastr.error("Amout cannot be greater than Total Sales_amount "+grand_total);
                       
                        $('#outstanding_amount').val(0);
                        cash_balance    =     Number(grand_total)-Number(cheque)-Number(net_banking)-Number(card)-Number(wallet)-Number(credit_note);
                         $('#cash').val(cash_balance.toFixed(decimal_points));
                         
                      }
                      else
                      {
                        
                        $('#cash').val(cash_balance.toFixed(decimal_points));
                        
                      }
                    // }
                 

            }

            
      

     

});
$('#cash').keyup(function(e){


            $('.netbankingdetails').hide();
            $('.chequedetails').hide();
            $('.outstandingdetails').hide();
            var cash                        =     $('#cash').val();
            var card                        =     $('#card').val();
            var cheque                      =     $('#cheque').val();
            var net_banking                 =     $('#net_banking').val();
            var wallet                      =     $('#wallet').val();
            var outstanding_amount          =     $('#outstanding_amount').val();
            var credit_note                 =     $('#credit_note').val();
            var grand_total                 =     $('#sggrand_total').val();
            var cash_balance    =      0;

    
          cash_balance    =     Number(grand_total)-Number(card)-Number(cheque)-Number(net_banking)-Number(wallet)-Number(outstanding_amount)-Number(credit_note);
              $('#cash').val(cash_balance.toFixed(decimal_points));
              

          if(Number(cash_balance)<0)
          {
            toastr.error("Amout cannot be greater than Total Sales_amount "+grand_total);
            
            $('#cash').val(0);
            cash_balance    =     Number(grand_total)-Number(cheque)-Number(net_banking)-Number(wallet)-Number(card)-Number(outstanding_amount)-Number(credit_note);
              $('#cash').val(cash_balance.toFixed(decimal_points));
                
          }
      

     

});
function getcreditnote_detail(creditnote_no)
{

     var creditnoteno   =   $('#creditnoteno').val();
     var customer_id    =   $('#ccustomer_id').val();
     var type = "POST";
     var url = 'creditnote_details';
     var data = {
         "creditnoteno" : creditnoteno,
     }
   callroute(url,type,data,function(data)
   {
        
        var creditnote_data = JSON.parse(data,true);


        if(creditnote_data['Success'] == "True")
        {
            
            var product_html = '';
            var creditnote_details  = creditnote_data['Data'][0];

           if(creditnote_data['Data']=='')
           {
                 toastr.error("You entered wrong Credit Note Details");
                 $('#creditnoteno').val('');
                 $('#creditnote_amount').val('');
                 $('#creditnote_id').val('');
                 $('#issue_amount').val('');
                 paymentmode();
                 return false;
           }
           else
           {
                  var cashamount          =   $('#cash').val();
                  var issue_amount       =   creditnote_details['balance_amount'];

                  $('#creditnote_amount').val(creditnote_details['balance_amount']);
                  $('#creditnote_id').val(creditnote_details['customer_creditnote_id']);

                if(Number(issue_amount) >  Number(cashamount))
                {
                   //toastr.error("Entered Amount is Greater than Bill Amount.");
                   $('#issue_amount').val(cashamount);

                }
                else
                {
                   $('#issue_amount').val(creditnote_details['balance_amount']);
                }
           }

            

            
        }
    });

}
$('#issue_amount').keyup(function(e){

      var cashamount        =   $('#cash').val();
      var credit_amount     =   $('#creditnote_amount').val(); 
      var issue_amount      =   $('#issue_amount').val();  

      if(($('#creditnote_no').val())!='')
      {
          if(Number(issue_amount) >  Number(cash))
          {
             var issueamt  =  0;
             if(Number(credit_amount) > Number(issue_amount))
             {
                issueamt     =   cashamount;
             }
             else
             {
               issueamt     =   issue_amount;
             }
             $('#issue_amount').val(issueamt);  
             toastr.error("Entered Amount is Greater than Bill Amount.");
             return false;

          }
          else if(Number(issue_amount) >  Number(credit_amount))
          {
             var issueamt  =  0;

             if(Number(credit_amount) > Number(cashamount))
             {
                issueamt     =   cashamount;
             }
             else
             {
               issueamt     =   credit_amount;
             }
             
             $('#issue_amount').val(issueamt);  
             toastr.error("Entered Amount is Greater than Credit Note Amount.");
             return false;

          }
          else
          {
              $('#issue_amount').val(issue_amount);
          }
      } 
      else
      {
          toastr.error("Please Enter Credit Note No.");
      }

});
$('#savecredit').click(function(e){

            var issue_amount      =   $('#issue_amount').val();

            var cash                        =     $('#cash').val();
            var card                        =     $('#card').val();
            var cheque                      =     $('#cheque').val();
            var net_banking                 =     $('#net_banking').val();
            var wallet                      =     $('#wallet').val();
            var outstanding_amount          =     $('#outstanding_amount').val();
            var grand_total                 =     $('#sggrand_total').val();
            var cash_balance    =      0;

    
          cash_balance    =     Number(grand_total)-Number(card)-Number(cheque)-Number(net_banking)-Number(wallet)-Number(outstanding_amount)-Number(issue_amount);
          

          if(Number(cash_balance)<0)
          {
            toastr.error("Amout cannot be greater than Total Sales_amount "+grand_total);
           
            cash_balance    =     Number(grand_total)-Number(cheque)-Number(net_banking)-Number(card)-Number(outstanding_amount)-Number(wallet);
               $('#cash').val(cash_balance.toFixed(decimal_points));
               
              $('#creditnote_amount').val(''); 
              $('#issue_amount').val('');  
              $('#creditnoteno').val(''); 
              $('#creditnote_id').val('');

          }
          else
          {
              $('#cash').val(cash_balance.toFixed(decimal_points));
               
              $('#credit_note').val(issue_amount);
          }

     
     $("#addcreditpopup").modal('hide'); 


});

function paymentmode()
{

            var cash                        =     $('#cash').val();
            var card                        =     $('#card').val();
            var cheque                      =     $('#cheque').val();
            var net_banking                 =     $('#net_banking').val();
            var wallet                      =     $('#wallet').val();
            var outstanding_amount          =     $('#outstanding_amount').val();
            var credit_note                 =     $('#credit_note').val();
            var grand_total                 =     $('#sggrand_total').val();
            var cash_balance    =      0;

    
          cash_balance    =     Number(grand_total)-Number(card)-Number(cheque)-Number(net_banking)-Number(wallet)-Number(outstanding_amount)-Number(credit_note);
  
            $('#cash').val(cash_balance.toFixed(decimal_points));
              
      
}

//search customer details
$("#searchcustomer").keyup(function ()
{
    
    jQuery.noConflict();
    if($("#searchcustomer").val().length >= 1) {

        $("#searchcustomer").autocomplete({
            autoFocus: true,
            minLength: 1,
            source: function (request, response) {
                var url = "customer_search";
                var type = "POST";
                var data = {
                    'search_val': $("#searchcustomer").val()
                };
                callroute(url, type, data, function (data) {


                    var searchdata = JSON.parse(data, true);
                    var html = '';
                    if (searchdata['Success'] == "True") {

                        var result = [];
                        searchdata['Data'].forEach(function (value) {
                            

                                if(value.customer_mobile!='' && value.customer_mobile!=null)
                                {
                                  result.push({
                                  label: value.customer_name + '_' + value.customer_mobile,
                                  value: value.customer_name + '_' + value.customer_mobile,
                                  id: value.customer_id
                                  });
                                 
                                }
                                else
                                {
                                  result.push({
                                   label: value.customer_name,
                                   value: value.customer_name,
                                   id: value.customer_id
                                   });
                                }
                                
                            
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
                $('#customer_id').val(id);
                getcustomerdetail(id);
                $('.customerdata').show();


            }
        });
    }
    else
    {
            $("#searchcustomer").empty();
    }

});
var DateHelper = {
  addDays : function(aDate, numberOfDays) {
        aDate.setDate(aDate.getDate() + numberOfDays); // Add numberOfDays
        return aDate;                                  // Return the date
    },
    format : function format(date) {
        return [
           ("0" + date.getDate()).slice(-2),           // Get day and pad it with zeroes
           ("0" + (date.getMonth()+1)).slice(-2),      // Get month and pad it with zeroes
           date.getFullYear()                          // Get full year
        ].join('-');                                   // Glue the pieces together
    }
}

 

function getcustomerdetail(customerid)
{
  
  
   var type = "POST";
   var url = 'customer_detail';
  
   var data = {
       "customer_id" : customerid
       
   }

   callroute(url,type,data,function(data)
   {
        var customer_data = JSON.parse(data,true);

        
        if(customer_data['Success'] == "True")
        {
            var customer_html = '';
            var customer_data  = customer_data['Data'][0];
            $('.opencustomerpopup').show();

             $("#ccustomer_id").val(customer_data['customer_id']);
             $("#customer_name").val(customer_data['customer_name']);
             $("#customer_mobile").val(customer_data['customer_mobile']);
             $("#customer_email").val(customer_data['customer_email']);
          if(customer_data['customer_address_detail']!= null && customer_data['customer_address_detail']!= '' && customer_data['customer_address_detail']['customer_gstin']!= null && customer_data['customer_address_detail']['customer_gstin']!= undefined)
            {
                  $("#customer_gstin").val(customer_data['customer_address_detail']['customer_gstin']);
            }
            if(customer_data['customer_address_detail']!= null && customer_data['customer_address_detail']!= '' && customer_data['customer_address_detail']['customer_address']!= null && customer_data['customer_address_detail']['customer_address']!= undefined)
            {
              $("#customer_address").val(customer_data['customer_address_detail']['customer_address']);
            }
            if(customer_data['customer_address_detail']!= null && customer_data['customer_address_detail']!= '' && customer_data['customer_address_detail']['state_id']!= null && customer_data['customer_address_detail']['state_id']!= undefined)
            {
                $("#customer_state_id").val(customer_data['customer_address_detail']['state_id']);
            }

             if(customer_data['outstanding_duedays']!='' && customer_data['outstanding_duedays']!=0 && customer_data['outstanding_duedays']!=null)
             {
               $("#duedays").val(customer_data['outstanding_duedays']);
                var futDate  = DateHelper.format(DateHelper.addDays(new Date(), customer_data['outstanding_duedays']));
                $('#duedate').val(futDate);
            }
            // else
            // {
              
            // }

             if(customer_data['totalcuscreditbalance']!='' && customer_data['totalcuscreditbalance']!=null)
             {
                $('.tcusbalance').html(customer_data['totalcuscreditbalance']);
                $('.showtbalance').show();
                $('.showtbalance').show();
             }
             else
             {
                $('.tcusbalance').html(0);
                $('.showtbalance').hide();
             }
             
             
        }

        $("#searchcustomer").val('');
        $(".odd").hide();
        
   });
}
$("#customer_gstin").keyup(function ()
{
    var gst_state_code = $("#customer_gstin").val().substr(0,2);

    if(gst_state_code.length != 0)
    {
        //$("#pstate_id").attr('disabled',true);
        $("#pstate_id").css('color','black');
        if(gst_state_code.startsWith('0'))
        {
            gst_state_code = gst_state_code.substring(1);
        }
        $("#pstate_id").val(gst_state_code);
    }
    else
    {
       // $("#pstate_id").removeAttr('disabled',false);
        $("#pstate_id").val('0');
    }

});
$("#pcustomer_gstin").keyup(function ()
{
    var gst_state_code = $("#pcustomer_gstin").val().substr(0,2);

    if(gst_state_code.length != 0)
    {
        //$("#pstate_id").attr('disabled',true);
        $("#pstate_id").css('color','black');
        if(gst_state_code.startsWith('0'))
        {
            gst_state_code = gst_state_code.substring(1);
        }
        $("#pstate_id").val(gst_state_code);
    }
    else
    {
        //$("#pstate_id").removeAttr('disabled',false);
        $("#pstate_id").val('0');
    }

});





$('#customer_mobile,#customer_email,#customer_address,#customer_gstin').change(function(e){
    toastr.success("Kindly Save your Customer Details!");
    $("#addcustomerpopup").modal('show');   
      var cusname       =     $('#customer_name').val();
      var cusmobile     =     $('#customer_mobile').val();
      var cusemail      =     $('#customer_email').val();
      var cusaddress    =     $('#customer_address').val();
      var cusgstin      =     $('#customer_gstin').val();
      var customerid    =     $('#ccustomer_id').val();
      var gst_state_code =     $("#customer_gstin").val().substr(0,2);

    if(gst_state_code.length != 0)
    {
        $("#pstate_id").attr('disabled',true);
        $("#pstate_id").css('color','black');
        if(gst_state_code.startsWith('0'))
        {
            gst_state_code = gst_state_code.substring(1);
        }
        $("#pstate_id").val(gst_state_code);
    }
    else
    {
        $("#pstate_id").removeAttr('disabled',false);
        $("#pstate_id").val('0');
    }

      $('#pcustomer_name').val(cusname);
      $('#pcustomer_mobile').val(cusmobile);
      $('#pcustomer_email').val(cusemail);
      $('#pcustomer_address').val(cusaddress);
      $('#pcustomer_gstin').val(cusgstin);
      $('#pcustomer_id').val(customerid);

      $('#pcustomer_name').focus();   
});

$(".opencustomerpopup").click(function () {
      $("#addcustomerpopup").modal('show'); 
      var cusname       =     $('#customer_name').val();
      var cusmobile     =     $('#customer_mobile').val();
      var cusemail      =     $('#customer_email').val();
      var cusaddress    =     $('#customer_address').val();
      var cusgstin      =     $('#customer_gstin').val();
      var customerid    =     $('#ccustomer_id').val();
      var gst_state_code =     $("#customer_gstin").val().substr(0,2);

    if(gst_state_code.length != 0)
    {
        $("#pstate_id").attr('disabled',true);
        $("#pstate_id").css('color','black');
        if(gst_state_code.startsWith('0'))
        {
            gst_state_code = gst_state_code.substring(1);
        }
        $("#pstate_id").val(gst_state_code);
    }
    else
    {
        $("#pstate_id").removeAttr('disabled',false);
        $("#pstate_id").val('0');
    }

      $('#pcustomer_name').val(cusname);
      $('#pcustomer_mobile').val(cusmobile);
      $('#pcustomer_email').val(cusemail);
      $('#pcustomer_address').val(cusaddress);
      $('#pcustomer_gstin').val(cusgstin);
      $('#pcustomer_id').val(customerid);

      $('#pcustomer_name').focus(); 
});

$("#addcustomer").click(function () {
      $("#addcustomerpopup").modal('show');   
      var cusname       =     $('#customer_name').val();
      var cusmobile     =     $('#customer_mobile').val();
      var cusemail      =     $('#customer_email').val();
      var cusaddress    =     $('#customer_address').val();
      var cusgstin      =     $('#customer_gstin').val();
      var customerid    =     $('#ccustomer_id').val();
      var gst_state_code =     $("#customer_gstin").val().substr(0,2);

    if(gst_state_code.length != 0)
    {
        $("#pstate_id").attr('disabled',true);
        $("#pstate_id").css('color','black');
        if(gst_state_code.startsWith('0'))
        {
            gst_state_code = gst_state_code.substring(1);
        }
        $("#pstate_id").val(gst_state_code);
    }
    else
    {
        $("#pstate_id").removeAttr('disabled',false);
        $("#pstate_id").val('0');
    }

      $('#pcustomer_name').val(cusname);
      $('#pcustomer_mobile').val(cusmobile);
      $('#pcustomer_email').val(cusemail);
      $('#pcustomer_address').val(cusaddress);
      $('#pcustomer_gstin').val(cusgstin);
      $('#pcustomer_id').val(customerid);

      $('#pcustomer_name').focus();
});
   


function validate_customerform(frmid)
{
    var error = 0;


   
    if($("#pcustomer_name").val() == '')
    {
        error = 1;
        toastr.error("Enter Customer Name!");
        return false;
    }
    
   if($("#pcustomer_mobile").val() == '')
    {
        error = 1;
        toastr.error("Enter Customer mobile No.!");
        return false;
    }
    if($("#pcustomer_email").val() != '')
    {
        var emailid = $("#pcustomer_email").val();
        if(validateEmail(emailid) == 0)
        {
            error = 1;
            toastr.error("Enter proper Customer Email id!");
            return false;
        }
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

$("#savecustomer").click(function ()
{

    if(validate_customerform('customerform'))
    {
        
        $(this).prop('disabled', true);
        var dialcode = $(".selected-dial-code").html();
        $("#customer_mobile_dial_code").val(dialcode);
        
        var type = "POST";
        var url = 'customer_create';
        var data = {
            "formdata": $("#customerform").serialize()

        };
      callroute(url,type,data,function (data)
        {
            $(this).prop('disabled', false);
            var dta = JSON.parse(data);
            if(dta['Success'] == "True")
            {
                toastr.success(dta['Message']);
                var cus_name      =     $('#pcustomer_name').val();
                var cus_mobile    =     $('#pcustomer_mobile').val();
                var cus_email     =     $('#pcustomer_email').val();
                var cus_address   =     $('#pcustomer_address').val();
                var cus_gstin     =     $('#pcustomer_gstin').val();
                var cus_state     =     $('#pstate_id').val();
                var pduedays      =     $('#poutstanding_duedays').val();

                $('.customerdata').show();                
                $("#addcustomerpopup").modal('hide');
                $('#customer_name').val(cus_name);
                $('#customer_mobile').val(cus_mobile);
                $('#customer_email').val(cus_email);
                $('#customer_address').val(cus_address);
                $('#customer_gstin').val(cus_gstin);
                $('#customer_state_id').val(cus_state);
                $('#ccustomer_id').val(dta['customer_id']);
                $('#savecustomer').prop('disabled', false);

                if(pduedays!='' && pduedays!=0)
                {
                    $("#duedays").val(pduedays);
                    var futDate  = DateHelper.format(DateHelper.addDays(new Date(), Number(pduedays)));
                    $('#duedate').val(futDate);
                }
                else
                {
                  $("#duedays").val('');
                  $("#duedate").val('');
                }
                

            }
            else
            {
                $('#savecustomer').prop('disabled', false);
                if(dta['status_code'] == 409)
               {
                  $.each(dta['Message'],function (errkey,errval)
                  {
                      var errmessage = errval;
                      toastr.error(errmessage);

                  });

              }
              else
              {
                  toastr.error(dta['Message']);
              }
            }
        });
    }
    else
    {
        return false;
    }

});


$("#addbilling").click(function (e) {

     $(this).prop('disabled', true);

  if(validate_billing('billingform'))
  {
      $("#addbilling").prop('disabled', true);



      var array = [];



      $('#sproduct_detail_record tr').has('td').each(function()
      {
          var arrayItem = {};
          $('td', $(this)).each(function(index, item)
          {
              var inputname = $(item).attr('id');

                if(inputname != undefined && inputname != '')
                {
                    var wihoutidname = inputname.split('_');
                    var nameforarray = wihoutidname[0];

                   

                        if(nameforarray == 'roomnoval')
                        {
                           
                            arrayItem['sales_product_id'] =$(this).find("#sales_product_id_"+wihoutidname[1]).val();
                            arrayItem['productid'] =$(this).find("#productid_"+wihoutidname[1]).val();
                            arrayItem['barcodesel'] =$(this).find("#barcodesel_"+wihoutidname[1]).val();
                            
                        }
                        else if(nameforarray == 'sellingmrp')
                        {
                            arrayItem['mrp'] =$(this).find("#mrp_"+wihoutidname[1]+" :selected").html();
                            arrayItem['price_master_id'] =$(this).find("#mrp_"+wihoutidname[1]+" :selected").val();
                            arrayItem['oldprice_master_id'] =$(this).find("#oldpricemasterid_"+wihoutidname[1]).val();
                            arrayItem['inwardids'] =$(this).find("#inwardids"+wihoutidname[1]).val();
                            arrayItem['inwardqtys'] =$(this).find("#inwardqtys"+wihoutidname[1]).val();
                            
                        }
                        else if(nameforarray == 'sellingqty')
                        {
                            arrayItem['qty'] =$(this).find("#qty_"+wihoutidname[1]).val();
                            arrayItem['oldqty'] =$(this).find("#oldqty_"+wihoutidname[1]).val();
                            

                        }
                        else if(nameforarray == 'sellingpricewgst')
                        {
                            arrayItem['sellingprice_before_discount'] =$(this).find("#sellingwithoutgst_"+wihoutidname[1]).val();

                        }
                        else if(nameforarray == 'sellingdiscountper')
                        {
                            arrayItem['discount_percent'] =$(this).find("#proddiscper_"+wihoutidname[1]).val();
                            arrayItem['overalldiscount_percent'] =$(this).find("#overalldiscper_"+wihoutidname[1]).val();


                        }
                        else if(nameforarray == 'sellingdiscountamt')
                        {
                            arrayItem['mrpdiscount_amount'] =$(this).find("#mrpproddiscamt_"+wihoutidname[1]).val();
                            arrayItem['discount_amount'] =$(this).find("#proddiscamt_"+wihoutidname[1]).val();
                            arrayItem['overalldiscount_amount'] =$(this).find("#overalldiscamt_"+wihoutidname[1]).val();
                            arrayItem['overallmrpdiscount_amount'] =$(this).find("#overallmrpdiscamt_"+wihoutidname[1]).val();

                        }
                        
                       
                        else
                        {
                            arrayItem[nameforarray] = $(item).html();
                        }


                }

          });
          array.push(arrayItem);
      });

      var arraydetail = [];
      arraydetail.push(array);


      var customerdetail = {};
      var paymentdetail = {};

      customerdetail['sales_bill_id'] = $("#sales_bill_id").val();
      customerdetail['customer_id'] = $("#ccustomer_id").val();
      customerdetail['customer_name'] = $("#customer_name").val();
      customerdetail['customer_mobile'] = $("#customer_mobile").val();
      customerdetail['customer_gstin'] = $("#customer_gstin").val();
      customerdetail['customer_state_id'] = $("#customer_state_id").val();
      customerdetail['customer_address'] = $("#customer_address").val();
      customerdetail['invoice_no'] = $("#invoice_no").val();
      customerdetail['invoice_date'] = $("#invoice_date").val();
      customerdetail['refname'] = $("#refname").val();
      customerdetail['chequeno'] = $("#chequeno").val();
      customerdetail['bankname'] = $("#bankname").val();
      customerdetail['netbankname'] = $("#netbankname").val();
      customerdetail['duedate'] = $("#duedate").val();
      customerdetail['duedays'] = $("#duedays").val();
      customerdetail['creditaccountid'] = $("#creditaccountid").val();
      customerdetail['creditbalcheck'] = $("#creditbalcheck").val();
      customerdetail['creditbalance'] = $("#creditbalance").val();
      customerdetail['creditnoteid'] = $("#creditnote_id").val();
      customerdetail['creditnoteamount'] = $("#creditnote_amount").val();
      customerdetail['issueamount'] = $("#issue_amount").val();
      customerdetail['editcreditnoteid'] = $("#editcreditnoteid").val();
      customerdetail['editcreditnotepaymentid'] = $("#editcreditnotepaymentid").val();
      customerdetail['editcreditnoteamount'] = $("#editcreditnoteamount").val();
      customerdetail['official_note'] = $("#official_note").val();
      customerdetail['print_note'] = $("#print_note").val();

      


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
         $(this).find('.newRow').each(function (index,item)
         {
             var paymentmethod = ($(this).find('input').attr('id'));

             // alert($("#"+paymentmethod).val())
             if($("#"+paymentmethod).val() != '' && $("#"+paymentmethod).val() != 0)
             {
                var paymentid = $("#"+paymentmethod).data("id");
                // console.log(paymentid);
                 parr.push({
                     id: paymentid,
                     value: $("#"+paymentmethod).val(),
                     sales_payment_id: $("#sales_payment_detail"+paymentid).val()
                 });
             }
         });

      });

      arraydetail.push(parr);

       var carray = [];

      $('#charges_record tr').has('td').each(function()
      {
          var carrayItem = {};
          $('td', $(this)).each(function(index, item)
          {
              var inputname = $(item).attr('id');

                if(inputname != undefined && inputname != '')
                {
                    var wihoutidname = inputname.split('_');
                    var nameforarray = wihoutidname[0];

                   

                        if(nameforarray == 'chargesname')
                        {
                            carrayItem['csales_product_id'] =$(this).find("#csales_product_id_"+wihoutidname[1]).val();
                            carrayItem['cproductid'] =$(this).find("#cproductid_"+wihoutidname[1]).val();
                            
                        }
                        else if(nameforarray == 'chargesamtdetails')
                        {
                            carrayItem['chargesamt'] =$(this).find("#chargesamt_"+wihoutidname[1]).val();
                            carrayItem['cqty'] =$(this).find("#cqty_"+wihoutidname[1]).val();
                            
                        }
                        else if(nameforarray == 'csprodgstperdetails')
                        {
                            carrayItem['csprodgstper'] =$(this).find("#csprodgstper_"+wihoutidname[1]).val();
                        }
                        else
                        {
                            carrayItem[nameforarray] = $(item).html();
                        }


                }

          });
          carray.push(carrayItem);
      });

     arraydetail.push(carray);
       console.log(arraydetail);
// return false;
   
      var data = arraydetail;

      var  url = "billing_create";
      var type = "POST";
      callroute(url,type,data,function (data)
      {
          $("#addbilling").prop('disabled', true);
          var dta = JSON.parse(data);

          if(dta['Success'] == "True")
          {
            
               toastr.success(dta['Message']);
               window.location = dta['url'];
              $("#billingform").trigger('reset');
              $("#sproduct_detail_record").empty('');

              
          }
          else
          {
            $("#addbilling").prop('disabled', true);
               toastr.error(dta['Message']);
               

          }
      })

  }
   else
    {
        $("#addbilling").prop('disabled', false);
        return false;
    }
});

$("#addbillingprint").click(function (e) {
    $(this).prop('disabled', true);
  if(validate_billing('billingform'))
  {
      $(this).prop('disabled', true);



      var array = [];



      $('#sproduct_detail_record tr').has('td').each(function()
      {
          var arrayItem = {};
          $('td', $(this)).each(function(index, item)
          {
              var inputname = $(item).attr('id');

                if(inputname != undefined && inputname != '')
                {
                    var wihoutidname = inputname.split('_');
                    var nameforarray = wihoutidname[0];

                   

                        if(nameforarray == 'roomnoval')
                        {
                           
                            arrayItem['sales_product_id'] =$(this).find("#sales_product_id_"+wihoutidname[1]).val();
                            arrayItem['productid'] =$(this).find("#productid_"+wihoutidname[1]).val();
                            arrayItem['barcodesel'] =$(this).find("#barcodesel_"+wihoutidname[1]).val();
                            
                        }
                        else if(nameforarray == 'sellingmrp')
                        {
                            arrayItem['mrp'] =$(this).find("#mrp_"+wihoutidname[1]+" :selected").html();
                            arrayItem['price_master_id'] =$(this).find("#mrp_"+wihoutidname[1]+" :selected").val();
                            arrayItem['oldprice_master_id'] =$(this).find("#oldpricemasterid_"+wihoutidname[1]).val();
                            arrayItem['inwardids'] =$(this).find("#inwardids"+wihoutidname[1]).val();
                            arrayItem['inwardqtys'] =$(this).find("#inwardqtys"+wihoutidname[1]).val();
                            
                        }
                        else if(nameforarray == 'sellingqty')
                        {
                            arrayItem['qty'] =$(this).find("#qty_"+wihoutidname[1]).val();
                            arrayItem['oldqty'] =$(this).find("#oldqty_"+wihoutidname[1]).val();
                            

                        }
                        else if(nameforarray == 'sellingpricewgst')
                        {
                            arrayItem['sellingprice_before_discount'] =$(this).find("#sellingwithoutgst_"+wihoutidname[1]).val();

                        }
                        else if(nameforarray == 'sellingdiscountper')
                        {
                            arrayItem['discount_percent'] =$(this).find("#proddiscper_"+wihoutidname[1]).val();
                            arrayItem['overalldiscount_percent'] =$(this).find("#overalldiscper_"+wihoutidname[1]).val();

                        }
                        else if(nameforarray == 'sellingdiscountamt')
                        {
                            arrayItem['mrpdiscount_amount'] =$(this).find("#mrpproddiscamt_"+wihoutidname[1]).val();
                            arrayItem['discount_amount'] =$(this).find("#proddiscamt_"+wihoutidname[1]).val();
                            arrayItem['overalldiscount_amount'] =$(this).find("#overalldiscamt_"+wihoutidname[1]).val();
                            arrayItem['overallmrpdiscount_amount'] =$(this).find("#overallmrpdiscamt_"+wihoutidname[1]).val();

                        }
                        
                       
                        else
                        {
                            arrayItem[nameforarray] = $(item).html();
                        }


                }

          });
          array.push(arrayItem);
      });

      var arraydetail = [];
      arraydetail.push(array);


      var customerdetail = {};
      var paymentdetail = {};

      customerdetail['sales_bill_id'] = $("#sales_bill_id").val();
      customerdetail['customer_id'] = $("#ccustomer_id").val();
      customerdetail['customer_name'] = $("#customer_name").val();
      customerdetail['customer_mobile'] = $("#customer_mobile").val();
      customerdetail['customer_gstin'] = $("#customer_gstin").val();
      customerdetail['customer_state_id'] = $("#customer_state_id").val();
      customerdetail['customer_address'] = $("#customer_address").val();
      customerdetail['invoice_no'] = $("#invoice_no").val();
      customerdetail['invoice_date'] = $("#invoice_date").val();
      customerdetail['refname'] = $("#refname").val();
      customerdetail['chequeno'] = $("#chequeno").val();
      customerdetail['bankname'] = $("#bankname").val();
      customerdetail['netbankname'] = $("#netbankname").val();
      customerdetail['duedate'] = $("#duedate").val();
      customerdetail['duedays'] = $("#duedays").val();
      customerdetail['creditaccountid'] = $("#creditaccountid").val();
      customerdetail['creditbalcheck'] = $("#creditbalcheck").val();
      customerdetail['creditbalance'] = $("#creditbalance").val();
      customerdetail['creditnoteid'] = $("#creditnote_id").val();
      customerdetail['creditnoteamount'] = $("#creditnote_amount").val();
      customerdetail['issueamount'] = $("#issue_amount").val();
      customerdetail['editcreditnoteid'] = $("#editcreditnoteid").val();
      customerdetail['editcreditnotepaymentid'] = $("#editcreditnotepaymentid").val();
      customerdetail['editcreditnoteamount'] = $("#editcreditnoteamount").val();
      customerdetail['official_note'] = $("#official_note").val();
      customerdetail['print_note'] = $("#print_note").val();

      


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
         $(this).find('.newRow').each(function (index,item)
         {
             var paymentmethod = ($(this).find('input').attr('id'));

             // alert($("#"+paymentmethod).val())
             if($("#"+paymentmethod).val() != '' && $("#"+paymentmethod).val() != 0)
             {
                var paymentid = $("#"+paymentmethod).data("id");
                // console.log(paymentid);
                 parr.push({
                     id: paymentid,
                     value: $("#"+paymentmethod).val(),
                     sales_payment_id: $("#sales_payment_detail"+paymentid).val()
                 });
             }
         });

      });

      arraydetail.push(parr);

       var carray = [];

      $('#charges_record tr').has('td').each(function()
      {
          var carrayItem = {};
          $('td', $(this)).each(function(index, item)
          {
              var inputname = $(item).attr('id');

                if(inputname != undefined && inputname != '')
                {
                    var wihoutidname = inputname.split('_');
                    var nameforarray = wihoutidname[0];

                   

                        if(nameforarray == 'chargesname')
                        {
                            carrayItem['csales_product_id'] =$(this).find("#csales_product_id_"+wihoutidname[1]).val();
                            carrayItem['cproductid'] =$(this).find("#cproductid_"+wihoutidname[1]).val();
                            
                        }
                        else if(nameforarray == 'chargesamtdetails')
                        {
                            carrayItem['chargesamt'] =$(this).find("#chargesamt_"+wihoutidname[1]).val();
                            carrayItem['cqty'] =$(this).find("#cqty_"+wihoutidname[1]).val();
                            
                        }
                        else if(nameforarray == 'csprodgstperdetails')
                        {
                            carrayItem['csprodgstper'] =$(this).find("#csprodgstper_"+wihoutidname[1]).val();
                        }
                        else
                        {
                            carrayItem[nameforarray] = $(item).html();
                        }



                }

          });
          carray.push(carrayItem);
      });

     arraydetail.push(carray);
       console.log(arraydetail);
    
      //return false;
      var data = arraydetail;

      var  url = "billingprint_create";
      var type = "POST";
      callroute(url,type,data,function (data)
      {

          $("#addbillingprint").prop('disabled', true);
          var dta = JSON.parse(data);

          if(dta['Success'] == "True")
          {
            
               toastr.success(dta['Message']);
               window.location.href = dta['burl'];
               window.open(dta['url'],'_blank');
               
               
              $("#billingform").trigger('reset');
              $("#sproduct_detail_record").empty('');
              
          }
          else
          {
            $("#addbillingprint").prop('disabled', true);
               toastr.error(dta['Message']);
          }
      })

  }
    else
    {
        $("#addbillingprint").prop('disabled', false);
        return false;
    }
});
function validate_billing(frmid)
{
    var error = 0;

    
    if($('#outstanding_amount').val() != 0 || $('#outstanding_amount').val() != '')
    {
        if($("#customer_name").val() =='' )
        {
             error = 1;
             toastr.error("Enter Customer Details");
             return false;
        }
        else if($('#duedays').val()=='')
        {
          toastr.error("First Enter Due Days for Outstanding Amount.")         
          $('.chequedetails').hide();
          $('.netbankingdetails').hide();
          $('.outstandingdetails').show();
          return false;
        }

    }
    if($("#grand_total").val() ==0)
    {
        error = 1;
        toastr.error("Enter product Details");
        return false;
    }
   
   var tariffcount  = 0;
    $('.ttariffwithoutgst').each(function(e){

        if($(this).val()>0)
        {
            tariffcount++;
        }
    });
    var roomcount  = 0;
    $('.allroom').each(function(e){

        if($(this).val()!='')
        {
            roomcount++;
        }
    });
   
   var paymentmodevalue  = 0;
     $("#paymentmethoddiv").each(function()
      {
          var paymentarr = {};
         $(this).find('.newRow').each(function (index,item)
         {
             var paymentmethod = ($(this).find('input').attr('id'));

             // alert($("#"+paymentmethod).val())
             if($("#"+paymentmethod).val() != '' && $("#"+paymentmethod).val() != 0)
             {
              
                    paymentmodevalue  +=  parseFloat($("#"+paymentmethod).val());
             }
         });

      });

   var paymentvalue   =  Number(paymentmodevalue).toFixed(decimal_points);
   console.log(paymentvalue);
   console.log($('#sggrand_total').val());
    if(Number(paymentvalue) != $('#sggrand_total').val())
    {
        error = 1;
        toastr.error("Please Receive full Bill Amount in payment Mode");
        return false;

    }

    if(Number(tariffcount) != Number(roomcount))
    {
        error = 1;
        toastr.error("Please Enter all room no. details");
        return false;

    }

    if(error == 1)
    {
        return false;
    }
    else
    {
        $('#addbilling').prop('disabled', true);
        $('#addbillingprint').prop('disabled', true);
        $("#billing_error").html('');
        return true;
    }
}

$("#pcustomer_date_of_birth").datepicker({
    format: 'dd-mm-yyyy',
    orientation: "bottom"

});
$("#invoice_date").datepicker({
    format: 'dd-mm-yyyy',
    orientation: "bottom",
    autoclose: true

});
function resetbill()
{
    $("#billingform").trigger('reset');
}

// function testCharacter(event) {
//     if ((event.keyCode >= 48 && event.keyCode <= 57) || event.keyCode === 13 ||event.keyCode === 9) {
//         addroom(this);
//         return true;
//     } else {
//         return false;
//     }

// }

// $("#discount_percent").on("keydown",function search(e) {
//     if(e.keyCode == 13) {
//         alert($(this).val());
//     }
// });
// function overalldiscountpercent(event) {
  
//     if (event.keyCode === 13) {

//               console.log('Enter');
//               var discount_percent        =   $('#discount_percent').val();
//               var rcolumn       = '';

//               var sales_total           =           $('#sales_total').val();
//               var discount_amount       =           (Number(sales_total) * Number(discount_percent)) / 100;
//               $('#discount_amount').val(discount_amount.toFixed(4));

//               $("#sproduct_detail_record").each(function (index,e)
//               {
                 
//                    $(this).find('tr').each(function ()
//                    {
//                       if($(this).attr('id') != undefined)
//                       {
//                           rcolumn = $(this).attr('id').split('product_')[1];
                          
//                        }    
                        

//                         if(($("#productsearch_"+rcolumn).val())!='')
//                         {
                           

//                            $("#overalldiscper_"+rcolumn).val(discount_percent);
//                             var qty                         =     $("#qty_"+rcolumn).val();
                          
//                             var totalsellingwgst             =     $("#totalsellingwgst_"+rcolumn).html();
                            
//                             var gst_percent                  =     $("#prodgstper_"+rcolumn).html();
//                             var proddiscountamt              =     ((Number(totalsellingwgst) * Number(discount_percent)) / 100).toFixed(4);
//                             var totalproddiscountamt         =     Number(proddiscountamt)

//                             var sellingafterdiscount          =     Number(totalsellingwgst) - Number(proddiscountamt);

                             
                              
//                               var gst_amount                   =     ((Number(sellingafterdiscount) * Number(gst_percent)) / 100).toFixed(4);
                              
//                               var halfgstamount                =     Number(gst_amount)/2;
//                               var sgstamount                   =     ((Number(sellingafterdiscount) * Number(gst_percent)) / 100).toFixed(2);
//                               var total_amount                 =     Number(sellingafterdiscount) + Number(gst_amount);
                             

//                                $("#overalldiscamt_"+rcolumn).val(totalproddiscountamt.toFixed(4));
//                                $("#tsellingaftergst_"+rcolumn).html(total_amount.toFixed(4));
//                                //$("#prodgstper_"+rcolumn).html(gst_percent);
//                                $("#prodgstamt_"+rcolumn).html(gst_amount);
//                                $("#totalamount_"+rcolumn).html(total_amount.toFixed(4));
//                               //$("#sprodgstper_"+rcolumn).html(Number(gst_percent).toFixed(2));
//                                $("#sprodgstamt_"+rcolumn).html(sgstamount);
//                                $("#stotalamount_"+rcolumn).html(total_amount.toFixed(2));
                             
//                               totalcalculation();
                                    
                        

//                         }
                      


//                    });

                


//               });


//         var sales_total           =           $('#sales_total').val();
        
//         var discount_amount       =           (Number(sales_total) * Number(discount_percent)) / 100;
//         $('#discount_amount').val(discount_amount.toFixed(4));
//        // return true;
//     }
//      else {
//         return false;
//     }

// }

// var input = document.getElementById("discount_percent");
// input.addEventListener("keyup", function(event) {
//   if (event.keyCode === 13) {
//    event.preventDefault();
//    document.getElementById("myBtn").click();
//   }
//});

$(document).ready(function () {
    localStorage.removeItem('edit_bill_record');
})

 var Typeahead = function (element, options) {

        this.autoSelect = typeof this.options.autoSelect == 'boolean' ? this.options.autoSelect : true;
 };