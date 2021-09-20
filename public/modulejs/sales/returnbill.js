//inward datepicker
$("#inward_date").datepicker({
    format: 'dd/mm/yy'
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
    autoselect:false,
 
     
});
$("#bill_no").typeahead({

    source: function(request, process) {
       $.ajax({
           url: "billno_search",
           dataType: "json",
           data: {
                search_val: $("#bill_no").val(),
                term: request.term
            },
           success: function (data) {$("#bill_no").val()
                    process(data);

                
           }
     });
    },
    
    minLength: 1,
    autoselect:false,
 
     
});

function removerow(productid)
{
    $("#product_"+productid).remove();
    var discount_percent        =     $("#discount_percent").val(); 
             if(Number(discount_percent)==0 || discount_percent == '')
             {
                  totalcalculation();
             }
             else
             {
                totalcalculation();
                totalcharges();
                var sales_total           =           $('#sales_total').val();
                var discount_amount       =           (Number(sales_total) * Number(discount_percent)) / 100;
                $("#discount_amount").val(discount_amount);
                // var cotcharges            =           $('#charges_total').val();
                // var cgrandtotal           =           Number(cotcharges)  + Number(sales_total);

                // var saleswithgst = 0;
                // $('.totalsellinggst').each(function (index,e){
                //       if($(this).html()!="")
                //       saleswithgst   +=   parseFloat($(this).html());                                                 
                     
                // });
                // $("#sales_total").val(saleswithgst);
                // $("#discount_percent").val(discount_percent);
                //  overalldiscountpercent();
             }   

          var srr = 0;
       $('.totqty').each(function(e){
            if($(this).val()!='')
            {
                srr++;
            }
       });
       $('.showtitems').show();
       $('.titems').html(srr);   

}


function calqty(obj)
{
 
    var id                        =     $(obj).attr('id');
    var product_id                =     $(obj).attr('id').split('qty_')[1];
    var qty                       =     $('#qty_'+product_id).val();
    var oldqty                    =     $('#oldqty_'+product_id).val();
    var stock                     =     $('#stock_'+product_id).html();

   

    if(Number(qty)>Number(oldqty))
    {
        toastr.error("Qty cannot be greater than Purchased Qty");
        $('#qty_'+product_id).val(oldqty);
        var sellingprice              =     $("#sellingwithoutgst_"+product_id).val();
        
        var gst_per                   =     $("#sprodgstper_"+product_id).html();
        var discount_percent          =     $("#proddiscper_"+product_id).val();
        var mrp                       =     $("#mrp_"+product_id).val();

          
        var totalmrpdiscount             =     (Number(mrp) * Number(oldqty)) * Number(discount_percent) / 100;

          
     
        var totalsellingwgst             =     Number(sellingprice) * Number(oldqty);
        var sellingdiscount              =     (Number(sellingprice) * Number(discount_percent) / 100).toFixed(4);
        var gst_amount                   =     (Number(sellingprice-sellingdiscount) * Number(gst_per) / 100).toFixed(4);

        var totaldiscount                =      Number(sellingdiscount) * Number(oldqty);

        var discountedamt                =      Number(totalsellingwgst) - Number(totaldiscount);


        var mrp                          =     Number(totaldiscount) + Number(gst_amount);
        
        var totalgst                     =      Number(gst_amount) * Number(oldqty);


        var sellingwithgst                =      Number(discountedamt) + Number(totalgst);


        var total_amount                 =     Number(discountedamt) + Number(totalgst);
       
         
         $("#totalsellingwgst_"+product_id).html(discountedamt.toFixed(4));
         
         $("#totalsellinggst_"+product_id).html(sellingwithgst.toFixed(4));
         $("#mrpproddiscamt_"+product_id).val(totalmrpdiscount.toFixed(4));
         $("#proddiscamt_"+product_id).val(totaldiscount.toFixed(4));
         $("#prodgstamt_"+product_id).html(totalgst.toFixed(4));
         $("#totalamount_"+product_id).html(total_amount.toFixed(4));

         $("#sprodgstamt_"+product_id).html(totalgst.toFixed(2));
         $("#stotalamount_"+product_id).html(total_amount.toFixed(2));

           var discount_percent        =     $("#discount_percent").val(); 
             if(Number(discount_percent)==0 || discount_percent == '')
             {
                  totalcalculation();
             }
             else
             {

                var saleswithgst = 0;
                  $('.totalsellinggst').each(function (index,e){
                      if($(this).html()!="")
                      saleswithgst   +=   parseFloat($(this).html());                                                 
                     
                    });
                $("#sales_total").val(saleswithgst);
                $("#discount_percent").val(discount_percent);
                 overalldiscountpercent();
             }   

    }
    else
    {
        var sellingprice              =     $("#sellingwithoutgst_"+product_id).val();
        var qty                       =     $("#qty_"+product_id).val();
        var gst_per                   =     $("#sprodgstper_"+product_id).html();
        var discount_percent          =     $("#proddiscper_"+product_id).val();
        var mrp                       =     $("#mrp_"+product_id).val();

          
        var totalmrpdiscount             =     (Number(mrp) * Number(qty)) * Number(discount_percent) / 100;
         
     
        var totalsellingwgst             =     Number(sellingprice) * Number(qty);
        var sellingdiscount              =     (Number(sellingprice) * Number(discount_percent) / 100).toFixed(4);
        var gst_amount                   =     (Number(sellingprice-sellingdiscount) * Number(gst_per) / 100).toFixed(4);

        var totaldiscount                =      Number(sellingdiscount) * Number(qty);

        var discountedamt                =      Number(totalsellingwgst) - Number(totaldiscount);

        var mrp                          =     Number(totaldiscount) + Number(gst_amount);
        
        var totalgst                     =      Number(gst_amount) * Number(qty);


        var sellingwithgst                =      Number(discountedamt) + Number(totalgst);


        var total_amount                 =     Number(discountedamt) + Number(totalgst);
        //console.log(discountedamt);

        
         $("#totalsellingwgst_"+product_id).html(discountedamt.toFixed(4));
         
         $("#totalsellinggst_"+product_id).html(sellingwithgst.toFixed(4));
         $("#mrpproddiscamt_"+product_id).val(totalmrpdiscount.toFixed(4));
         $("#proddiscamt_"+product_id).val(totaldiscount.toFixed(4));
         $("#prodgstamt_"+product_id).html(totalgst.toFixed(4));
         $("#totalamount_"+product_id).html(total_amount.toFixed(4));

         $("#sprodgstamt_"+product_id).html(totalgst.toFixed(2));
         $("#stotalamount_"+product_id).html(total_amount.toFixed(2));

          var discount_percent        =     $("#discount_percent").val(); 
             if(Number(discount_percent)==0 || discount_percent == '')
             {
                  totalcalculation();
             }
             else
             {

                var saleswithgst = 0;
                  $('.totalsellinggst').each(function (index,e){
                      if($(this).html()!="")
                      saleswithgst   +=   parseFloat($(this).html());                                                 
                     
                    });
                $("#sales_total").val(saleswithgst);
                $("#discount_percent").val(discount_percent);
                 overalldiscountpercent();
             }  
     }
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

             $("#ccustomer_id").val(customer_data['customer_id']);
             $("#customer_name").val(customer_data['customer_name']);
             $("#customer_mobile").val(customer_data['customer_mobile']);
             $("#customer_email").val(customer_data['customer_email']);
             $("#customer_gstin").val(customer_data['customer_address_detail']['customer_gstin']);
             $("#customer_address").val(customer_data['customer_address_detail']['customer_address']);
             $("#customer_state_id").val(customer_data['customer_address_detail']['state_id']);
             
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

});
$("#pcustomer_gstin").keyup(function ()
{
    var gst_state_code = $("#pcustomer_gstin").val().substr(0,2);

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

});

function overalldiscountpercent()
{
    var discount_percent        =   $('#discount_percent').val();
    var rcolumn       = '';

     var sales_total           =           $('#sales_total').val();
      var discount_amount       =           (Number(sales_total) * Number(discount_percent)) / 100;
      $('#discount_amount').val(discount_amount.toFixed(4));

      $("#sproduct_detail_record").each(function (index,e)
        {
           
             $(this).find('tr').each(function ()
             {
                if($(this).attr('id') != undefined)
                {
                    rcolumn = $(this).attr('id').split('product_')[1];
                    
                 }    
                  

                  if(($("#productsearch_"+rcolumn).val())!='')
                  {
                     

                     $("#overalldiscper_"+rcolumn).val(discount_percent);
                      var qty                         =     $("#qty_"+rcolumn).val();
                    
                      var totalsellingwgst             =     $("#totalsellingwgst_"+rcolumn).html();
                      var totalmrpgst                  =     $("#totalsellinggst_"+rcolumn).html();
                      
                      var gst_percent                  =     $("#prodgstper_"+rcolumn).html();
                      var prodmrpdiscountamt           =     ((Number(totalmrpgst) * Number(discount_percent)) / 100).toFixed(4);
                      var proddiscountamt              =     ((Number(totalsellingwgst) * Number(discount_percent)) / 100).toFixed(4);
                      var totalproddiscountamt         =     Number(proddiscountamt)

                      var sellingafterdiscount          =     Number(totalsellingwgst) - Number(proddiscountamt);

                       console.log(prodmrpdiscountamt);
                        
                        var gst_amount                   =     ((Number(sellingafterdiscount) * Number(gst_percent)) / 100).toFixed(4);
                        
                        var halfgstamount                =     Number(gst_amount)/2;
                        var sgstamount                   =     ((Number(sellingafterdiscount) * Number(gst_percent)) / 100).toFixed(2);
                        var total_amount                 =     Number(sellingafterdiscount) + Number(gst_amount);
                       

                         $("#overallmrpdiscamt_"+rcolumn).val(prodmrpdiscountamt);
                         $("#overalldiscamt_"+rcolumn).val(totalproddiscountamt.toFixed(4));
                         $("#tsellingaftergst_"+rcolumn).html(total_amount.toFixed(4));
                         //$("#prodgstper_"+rcolumn).html(gst_percent);
                         $("#prodgstamt_"+rcolumn).html(gst_amount);
                         $("#totalamount_"+rcolumn).html(total_amount.toFixed(4));
                        //$("#sprodgstper_"+rcolumn).html(Number(gst_percent).toFixed(2));
                         $("#sprodgstamt_"+rcolumn).html(sgstamount);
                         $("#stotalamount_"+rcolumn).html(total_amount.toFixed(2));
                       
                        totalcalculation();
                              
                  

                  }



             });

          


        });


                      var sales_total           =           $('#sales_total').val();
                      
                      var discount_amount       =           (Number(sales_total) * Number(discount_percent)) / 100;
                      $('#discount_amount').val(discount_amount.toFixed(4));

}
function overalldiscountamount()
{
    var discount_amount        =   $('#discount_amount').val();
    var rcolumn       = '';

      $("#sproduct_detail_record").each(function (index,e)
        {
           
             $(this).find('tr').each(function ()
             {
                if($(this).attr('id') != undefined)
                {
                    rcolumn = $(this).attr('id').split('product_')[1];
                    
                }
                
                var sales_total           =           $('#sales_total').val();
                var discount_percent      =           ((Number(discount_amount) / Number(sales_total)) * 100);
                
                $('#discount_percent').val(discount_percent.toFixed(4));

                if(($("#productsearch_"+rcolumn).val())!='')
                  {
                      

                     $("#overalldiscper_"+rcolumn).val(discount_percent);
                      var qty                         =     $("#qty_"+rcolumn).val();
                    
                      var totalsellingwgst             =     $("#totalsellingwgst_"+rcolumn).html();
                      var totalmrpgst                  =     $("#totalsellinggst_"+rcolumn).html();

                      var prodmrpdiscountamt           =     ((Number(totalmrpgst) * Number(discount_percent)) / 100).toFixed(4);
                      
                      var gst_percent                  =     $("#prodgstper_"+rcolumn).html();
                      var proddiscountamt              =     ((Number(totalsellingwgst) * Number(discount_percent)) / 100).toFixed(4);
                      var totalproddiscountamt         =     Number(proddiscountamt)

                      var sellingafterdiscount          =     Number(totalsellingwgst) - Number(proddiscountamt);

                       
                        
                        var gst_amount                   =     ((Number(sellingafterdiscount) * Number(gst_percent)) / 100).toFixed(4);
                        
                        var halfgstamount                =     Number(gst_amount)/2;
                        var sgstamount                   =     ((Number(sellingafterdiscount) * Number(gst_percent)) / 100).toFixed(2);
                        var total_amount                 =     Number(sellingafterdiscount) + Number(gst_amount);
                       

                         $("#overallmrpdiscamt_"+rcolumn).val(prodmrpdiscountamt);
                         $("#overalldiscamt_"+rcolumn).val(totalproddiscountamt.toFixed(4));
                         $("#tsellingaftergst_"+rcolumn).html(total_amount.toFixed(4));
                         //$("#prodgstper_"+rcolumn).html(gst_percent);
                         $("#prodgstamt_"+rcolumn).html(gst_amount);
                         $("#totalamount_"+rcolumn).html(total_amount.toFixed(4));
                        //$("#sprodgstper_"+rcolumn).html(Number(gst_percent).toFixed(2));
                         $("#sprodgstamt_"+rcolumn).html(sgstamount);
                         $("#stotalamount_"+rcolumn).html(total_amount.toFixed(2));
                       
                        totalcalculation();
                              
                  

                  }

             });

          


        });

}



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
    $('#credit_note').val(Number(cgrandtotal).toFixed(decimal_points));
    sales_total   =  sales_total.toFixed(decimal_points);

    
   
}

function taddcharges(obj)
{
    var id                        =     $(obj).attr('id');
    var charges_id                =     $(obj).attr('id').split('creturntotalamount_')[1];

    var tchargesamt                =     $('#creturntotalamount_'+charges_id).val();
    var oldcharges                 =     $('#oldchargesamt_'+charges_id).val();
    var maxgst                     =      $('#csprodgstper_'+charges_id).html();

    if(Number(tchargesamt)>Number(oldcharges))
    {
        toastr.error("Return Charges Amt. Cannot be Greater than the Previous Charges Amt.");
        $('#creturntotalamount_'+charges_id).val(Number(oldcharges));
            var cprodgstamt     =   (Number(oldcharges)/(Number(maxgst)+100))   * Number(maxgst);
            var chargesamt      =    Number(oldcharges) - Number(cprodgstamt);

            $('#csprodgstamt_'+charges_id).html(Number(cprodgstamt).toFixed(2));
            $('#cprodgstamt_'+charges_id).html(Number(cprodgstamt).toFixed(4));

            $('#ctotalamount_'+charges_id).html(Number(oldcharges).toFixed(4));
            $('#cstotalamount_'+charges_id).html(Number(oldcharges).toFixed(2));
            $('#chargesamt_'+charges_id).val(Number(chargesamt).toFixed(4));
            totalcharges();
    }
    else
    {
        $('#creturntotalamount_'+charges_id).val(Number(tchargesamt));
         var cprodgstamt     =   (Number(tchargesamt)/(Number(maxgst)+100))   * Number(maxgst);
        var chargesamt      =    Number(tchargesamt) - Number(cprodgstamt);

        $('#csprodgstamt_'+charges_id).html(Number(cprodgstamt).toFixed(2));
        $('#cprodgstamt_'+charges_id).html(Number(cprodgstamt).toFixed(4));

        $('#ctotalamount_'+charges_id).html(Number(tchargesamt).toFixed(4));
        $('#cstotalamount_'+charges_id).html(Number(tchargesamt).toFixed(2));
        $('#chargesamt_'+charges_id).val(Number(chargesamt).toFixed(4));
        totalcharges();
    }
   
   

}


function totalcharges()
{
    var totcharges = 0;
   
    
    $('.chargesamt').each(function (index,e){
      if($(this).val()!="")
      totcharges   +=   parseFloat($(this).val());
     
    });

    $('#scharges_total').val(Number(totcharges).toFixed(2));
    $('#charges_total').val(Number(totcharges).toFixed(4));

     var grand_total     =   $('#grand_total').val();
     var totgrand_total  =   Number(grand_total) + Number(totcharges);
     $('#ggrand_total').val(Number(totgrand_total).toFixed(4));
    $('#sggrand_total').val(Number(totgrand_total).toFixed(decimal_points));
    

}

$('#customer_name,#customer_mobile,#customer_email').change(function(e){
    toastr.success("Kindly Save your Customer Details!");
    $("#addcustomerpopup").modal('show');   
      var cusname       =     $('#customer_name').val();
      var cusmobile     =     $('#customer_mobile').val();
      var cusemail      =     $('#customer_email').val();
      var customerid    =     $('#ccustomer_id').val();

   

      $('#pcustomer_name').val(cusname);
      $('#pcustomer_mobile').val(cusmobile);
      $('#pcustomer_email').val(cusemail);
      
      $('#pcustomer_id').val(customerid);

      $('#pcustomer_name').focus();   
});

$("#addcustomer").click(function () {
      $("#addcustomerpopup").modal('show');   
      var cusname       =     $('#customer_name').val();
      var cusmobile     =     $('#customer_mobile').val();
      var cusemail      =     $('#customer_email').val();
      
      var customerid    =     $('#ccustomer_id').val();
     
    

      $('#pcustomer_name').val(cusname);
      $('#pcustomer_mobile').val(cusmobile);
      $('#pcustomer_email').val(cusemail);
     
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

                $('.customerdata').show();
                
                $("#addcustomerpopup").modal('hide');
                $('#customer_name').val(cus_name);
                $('#customer_mobile').val(cus_mobile);
                $('#customer_email').val(cus_email);
               
                $('#ccustomer_id').val(dta['customer_id']);
                $('#savecustomer').prop('disabled', false);

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
                            arrayItem['return_product_id'] =$(this).find("#return_product_id_"+wihoutidname[1]).val();
                            arrayItem['sales_product_id'] =$(this).find("#sales_product_id_"+wihoutidname[1]).val();
                            arrayItem['productid'] =$(this).find("#productid_"+wihoutidname[1]).val();
                            
                        }
                       else if(nameforarray == 'sellingmrp')
                        {
                            arrayItem['mrp'] =$(this).find("#mrp_"+wihoutidname[1]).val();
                            arrayItem['price_master_id'] =$(this).find("#pricemasterid_"+wihoutidname[1]).val();
                            arrayItem['inwardids'] =$(this).find("#inwardids_"+wihoutidname[1]).val();
                            arrayItem['inwardqtys'] =$(this).find("#inwardqtys_"+wihoutidname[1]).val();
                            
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
      customerdetail['return_bill_id'] = $("#return_bill_id").val();
      customerdetail['customer_creditnote_id'] = $("#customer_creditnote_id").val();
      customerdetail['customer_id'] = $("#ccustomer_id").val();
      customerdetail['customer_name'] = $("#customer_name").val();
      customerdetail['customer_mobile'] = $("#customer_mobile").val();
      customerdetail['invoice_no'] = $("#invoice_no").val();
      customerdetail['invoice_date'] = $("#invoice_date").val();
      customerdetail['chequeno'] = $("#chequeno").val();
      customerdetail['bankname'] = $("#bankname").val();
      customerdetail['netbankname'] = $("#netbankname").val();
      customerdetail['creditaccountid'] = $("#creditaccountid").val();
      customerdetail['totalcreditamount'] = $("#totalcreditamount").val();
      customerdetail['totalcreditbalance'] = $("#totalcreditbalance").val();
      customerdetail['refname'] = $("#refname").val();



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
                            carrayItem['creturn_product_id'] =$(this).find("#creturn_product_id_"+wihoutidname[1]).val();
                            carrayItem['cproductid'] =$(this).find("#cproductid_"+wihoutidname[1]).val();
                            carrayItem['csales_product_id'] =$(this).find("#csales_product_id_"+wihoutidname[1]).val();
                            
                        }
                        else if(nameforarray == 'chargesamtdetails')
                        {
                            carrayItem['chargesamt'] =$(this).find("#chargesamt_"+wihoutidname[1]).val();
                            carrayItem['cqty'] =$(this).find("#cqty_"+wihoutidname[1]).val();
                            
                        }
                        else if(nameforarray == 'cstotalamountdetails')
                        {
                            carrayItem['returnchargesamt'] =$(this).find("#creturntotalamount_"+wihoutidname[1]).val();                        
                            
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

      var  url = "returnbilling_create";
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
      $("#addbillingprint").prop('disabled', true);



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
                            arrayItem['return_product_id'] =$(this).find("#return_product_id_"+wihoutidname[1]).val();
                            arrayItem['sales_product_id'] =$(this).find("#sales_product_id_"+wihoutidname[1]).val();
                            arrayItem['productid'] =$(this).find("#productid_"+wihoutidname[1]).val();
                            
                        }
                       else if(nameforarray == 'sellingmrp')
                        {
                            arrayItem['mrp'] =$(this).find("#mrp_"+wihoutidname[1]).val();
                            arrayItem['price_master_id'] =$(this).find("#pricemasterid_"+wihoutidname[1]).val();
                            arrayItem['inwardids'] =$(this).find("#inwardids_"+wihoutidname[1]).val();
                            arrayItem['inwardqtys'] =$(this).find("#inwardqtys_"+wihoutidname[1]).val();
                            
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
      customerdetail['return_bill_id'] = $("#return_bill_id").val();
      customerdetail['customer_creditnote_id'] = $("#customer_creditnote_id").val();
      customerdetail['customer_id'] = $("#ccustomer_id").val();
      customerdetail['customer_name'] = $("#customer_name").val();
      customerdetail['customer_mobile'] = $("#customer_mobile").val();
      customerdetail['invoice_no'] = $("#invoice_no").val();
      customerdetail['invoice_date'] = $("#invoice_date").val();
      customerdetail['chequeno'] = $("#chequeno").val();
      customerdetail['bankname'] = $("#bankname").val();
      customerdetail['netbankname'] = $("#netbankname").val();
      customerdetail['creditaccountid'] = $("#creditaccountid").val();
      customerdetail['totalcreditamount'] = $("#totalcreditamount").val();
      customerdetail['totalcreditbalance'] = $("#totalcreditbalance").val();
      customerdetail['refname'] = $("#refname").val();



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
                            carrayItem['creturn_product_id'] =$(this).find("#creturn_product_id_"+wihoutidname[1]).val();
                            carrayItem['cproductid'] =$(this).find("#cproductid_"+wihoutidname[1]).val();
                            carrayItem['csales_product_id'] =$(this).find("#csales_product_id_"+wihoutidname[1]).val();
                            
                        }
                        else if(nameforarray == 'chargesamtdetails')
                        {
                            carrayItem['chargesamt'] =$(this).find("#chargesamt_"+wihoutidname[1]).val();
                            carrayItem['cqty'] =$(this).find("#cqty_"+wihoutidname[1]).val();
                            
                        }
                        else if(nameforarray == 'cstotalamountdetails')
                        {
                            carrayItem['returnchargesamt'] =$(this).find("#creturntotalamount_"+wihoutidname[1]).val();                        
                            
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

      var  url = "returnbillingprint_create";
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

    if($("#customer_name").val() =='')
    {
        error = 1;
        toastr.error("Customer details are necessary to create Credit Note");
        return false;
    }
   
    if($("#grand_total").val() ==0)
    {
        error = 1;
        toastr.error("No Product to return please select products");
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
// $("#invoice_date").datepicker({
//     format: 'dd-mm-yyyy',
//     orientation: "bottom"

// });
function resetbill()
{
    $("#billingform").trigger('reset');
}

function testCharacter(event) {
    if ((event.keyCode >= 48 && event.keyCode <= 57) || event.keyCode === 13 ||event.keyCode === 9) {
        addroom(this);
        return true;
    } else {
        return false;
    }

}
function resetreturnfilterdata()
{
    $("#fromtodate").val('');
    $("#bill_no").val('');
    $("#productsearch").val('');
    $("#billingform").trigger('reset');
    $("#sproduct_detail_record").html('');
    $('#searchbilldata').prop('disabled',false);
    $("#returnbillsdetails").trigger('reset');
    $("#customerform").trigger('reset');
    $("#productdetails").html('');
    $("#charges_record").html('');
    
    
   
    
}


$('#searchbilldata').click(function(e){

   var bill_no        =  $("#bill_no").val();
   var productsearch  =  $('#productsearch').val();

   if(bill_no=='' && productsearch=='')
   {
            toastr.error("Please Enter Filter details to return Products");
            return false;
    }
    else
    {        
           if(bill_no !='')
           {
               $('.loaderContainer').show();
               $('#fromtodate').val('');
               $('#productsearch').val('');


               var type = "POST";
               var url = 'returnbill_data';
               var data = {
                   "bill_no" : bill_no,
               }
           callroute(url,type,data,function(data)
           {

                var bill_data = JSON.parse(data,true);


                if(bill_data['Success'] == "True")
                {

                    $('#searchbilldata').prop('disabled', true);
                    var bill_detail         = bill_data['Data'];
                    var bill_productdetail  = bill_data['ProductData'];
                   

                    var customer_name = '';
                    var customer_mobile='';
                    var customer_email='';
                    var cusreference ='';
                    
                      if(bill_detail['customer']!= null && bill_detail['customer']!= '' && bill_detail['customer']['customer_name']!= null && bill_detail['customer']['customer_name']!= undefined)
                      {
                                customer_name  =  bill_detail['customer']['customer_name'];
                                $('.customerdata').show();
                      }
                      
                      if(bill_detail['customer']!= null && bill_detail['customer']!= '' && bill_detail['customer']['customer_mobile']!= null && bill_detail['customer']['customer_mobile']!= undefined)
                      {
                                customer_mobile  =  bill_detail['customer']['customer_mobile'];
                      }
                      
                      if(bill_detail['customer']!= null && bill_detail['customer']!= '' && bill_detail['customer']['customer_email']!= null && bill_detail['customer']['customer_email']!= undefined)
                      {
                                customer_email  =  bill_detail['customer']['customer_email'];
                      }
                     if(bill_detail['reference']!= null && bill_detail['reference']!= '' && bill_detail['reference']['reference_name']!= null && bill_detail['reference']['reference_name']!= undefined)
                      {
                                cusreference  =  bill_detail['reference']['reference_name'];
                      }

                       $('#ccustomer_id').val(bill_detail['customer_id']);
                       $("#sales_bill_id").val(bill_detail['sales_bill_id']);
                       $("#invoice_date").val(bill_detail['bill_date']);
                       $("#invoice_no").val(bill_detail['bill_no']);
                       $("#discount_percent").val(bill_detail['discount_percent']);
                       $("#discount_amount").val(bill_detail['discount_amount']);
                       $("#roomwisediscount_amount").val(bill_detail['productwise_discounttotal']);

                        $("#customer_name").val(customer_name);
                        $("#customer_mobile").val(customer_mobile);
                        $("#customer_email").val(customer_email);
                        $("#refname").val(cusreference);

                  
                    if(bill_detail['customer_creditaccount'] != 'undefined' && bill_detail['customer_creditaccount'] != '' && bill_detail['customer_creditaccount'] != null)
                    {
                             
                            $("#creditaccountid").val(bill_detail['customer_creditaccount']['customer_creditaccount_id']);
                            $("#totalcreditamount").val(bill_detail['customer_creditaccount']['credit_amount']);  
                            $("#totalcreditbalance").val(bill_detail['customer_creditaccount']['balance_amount']);   
                    }
                    

          
                var productcount  = 0;
                var chargecount  = 0;

                 //console.log(bill_productdetail);
                   
                if(bill_productdetail != 'undefined' && bill_productdetail != '')
               {

                   var product_html = '';   
                    var pcount    = 0;
                    var sellingprice  = 0;
                    var stock = 0;
                    var pricehtml = '';  
                    var chargeshtml = ''; 
                      
               $.each(bill_productdetail,function (billkey,billvalue)
               {
                        
                       if(billvalue['product_type']==1)
                       {

                                var rinwardids      =     (billvalue['inwardids'].slice(0,-1)).split(',');
                                var rinwardqtys     =     (billvalue['inwardqtys'].slice(0,-1)).split(',');
                                var tinwardids      =      '';
                                var tinwardqtys     =      '';
                                var totiqty         =      '';
                                var totalreturnqty  =      '';

                                //console.log(billvalue['return_product_detail']);

                 if(billvalue['return_product_detail'] != 'undefined' && billvalue['return_product_detail'] != '')
                {               

                    $.each(rinwardids,function (ridkey,ridvalue)
                    {
                        
                            var totiqty         =      '';
                              $.each(billvalue['return_product_detail'],function (rbillkey,rbillvalue)
                              {
                                 
                                    
                                    var iids             =     (rbillvalue['inwardids'].slice(0,-1)).split(',');
                                    var iqtys            =     (rbillvalue['inwardqtys'].slice(0,-1)).split(',');
                                       
                                         
                                              
                                            $.each(iids,function (iidkey,iidvalue)
                                            {
                                                if(ridvalue == iidvalue)
                                                {
                                                    totiqty  =  Number(totiqty) + Number(iqtys[iidkey]);

                                                }
                                                
                                            })
                                            
                                           

                                           
                                })


                                  if(totiqty!='')
                                  {

                                      if(Number(rinwardqtys[ridkey]) != Number(totiqty))
                                      {
                                          
                                           tinwardids     +=     ridvalue+',';
                                           tinwardqtys    +=     (Number(rinwardqtys[ridkey])-Number(totiqty))+',';
                                      }
                                  }
                                  else
                                  {
                                           tinwardids      +=     ridvalue+',';
                                           tinwardqtys    +=     (Number(rinwardqtys[ridkey]))+',';
                                  }
                            
                           
                            
                       })
                 }

                else
                {
                     tinwardids     +=     billvalue['inwardids'];
                     tinwardqtys    +=     billvalue['inwardqtys'];
                 }

                             // console.log(tinwardids);
                             //  console.log(tinwardqtys);
                            //console.log(totalreturnqty);
                               if(billvalue['qty']==billvalue['totalreturnqty'])
                               {

                               }
                               else
                               {
                               
                                        productcount =  1;
                                        var qty                         =   billvalue['qty'] - billvalue['totalreturnqty'];

                                        var product_id                  =   billvalue['price_master_id'];

                                        var sellingprice                =   billvalue['sellingprice_before_discount'];
                                        
                                        var discount_percent            =   billvalue['discount_percent'];
                                        var overalldiscount_percent     =   billvalue['overalldiscount_percent'];
                                        var gst_percent                 =   billvalue['igst_percent'];
                                        var totalmrpdiscount            =   (Number(billvalue['mrp']) * Number(qty)) * Number(discount_percent) / 100;

                                        var totalsellingwgst             =     Number(sellingprice) * Number(qty);
                                        var sellingdiscount              =     (Number(sellingprice) * Number(discount_percent) / 100).toFixed(4);
                                        var gst_amount                   =     (Number(sellingprice-sellingdiscount) * Number(gst_percent) / 100).toFixed(4);

                                        var totaldiscount                =      Number(sellingdiscount) * Number(qty);

                                        var discountedamt                =      Number(totalsellingwgst) - Number(totaldiscount);

                                        var mrp                          =     Number(totaldiscount) + Number(gst_amount);
                                        
                                        var totalgst                     =      Number(gst_amount) * Number(qty);


                                        var sellingwithgst               =      Number(discountedamt) + Number(totalgst);



                                      var totalsellingwgst               =     discountedamt;
                                  
                                      var mrpproddiscountamt              =     ((Number(sellingwithgst) * Number(overalldiscount_percent)) / 100).toFixed(4);
                                      var proddiscountamt              =     ((Number(totalsellingwgst) * Number(overalldiscount_percent)) / 100).toFixed(4);

                                      var totalproddiscountamt         =     Number(proddiscountamt)

                                      var sellingafterdiscount          =     Number(totalsellingwgst) - Number(proddiscountamt);

                                       
                                        
                                        var gst_amount                   =     ((Number(sellingafterdiscount) * Number(gst_percent)) / 100).toFixed(4);
                                        
                                       
                                        var sgstamount                   =     ((Number(sellingafterdiscount) * Number(gst_percent)) / 100).toFixed(2);
                                        var total_amount                 =     Number(sellingafterdiscount) + Number(gst_amount);




                                        var showsellingprice              =   Number(billvalue['sellingprice_before_discount']).toFixed(2);
                                        var showigst_amount               =   Number(billvalue['igst_amount']).toFixed(2);
                                        var mrp                           =    Number(billvalue['mrp']).toFixed(2);
                                        var total_amount                  =    Number(total_amount).toFixed(4);
                                        var stotal_amount                  =    Number(total_amount).toFixed(2);

                    console.log(bill_data['Billtype']);
                          if(bill_data['Billtype']==3)
                          {
                            var batchhtml   =   '<td id="batchno_'+product_id+'">'+billvalue['batchprice_master']['batch_no']+'</td>';
                          } 
                          else
                          {
                            var batchhtml  = '';
                          }             
                        
                        if(billvalue['product']['supplier_barcode']!='' && billvalue['product']['supplier_barcode']!=null)
                        {
                          var barcode     =     billvalue['product']['supplier_barcode'];
                        }
                        else
                        {
                          var barcode     =     billvalue['product']['product_system_barcode'];
                        }

                           product_html += '<tr id="product_' + product_id + '">' +
                                    '<td id="barcodesel_'+product_id+'" name="barcode_sel[]">'+barcode+'</td>'+
                                    '<td id="roomnoval_'+product_id+'" style="display:none;">'+
                                    '<input value="'+billvalue['sales_products_detail_id']+'" type="hidden" id="sales_product_id_'+product_id+'" name="sales_product_id[]" class="" >'+
                                    '<input value="" type="hidden" id="return_product_id_'+product_id+'" name="return_product_id_[]" class="" >'+
                                    '<input value="'+billvalue['product_id']+'" type="hidden" id="productid_'+product_id+'" name="productid[]" class="allbarcode" >'+

                                    '</td>'+
                                    '<td id="product_name_'+product_id+'" name="product_name[]">'+billvalue['product']['product_name']+'</td>'+ batchhtml+'<td id="sellingmrp_'+product_id+'">'+'<input type="text" tabindex="-1" id="mrp_'+product_id+'" class="floating-input tarifform-control number" value="'+mrp+'" readonly>'+
                                    '<input type="hidden" id="pricemasterid_'+product_id+'" tabindex="-1" name="pricemasterid[]"  value="'+billvalue['price_master_id']+'" >'+
                                    '<input type="hidden" id="inwardids_'+product_id+'" name="inwardids[]"  value="'+tinwardids+'" >'+
                                    '<input type="hidden" id="inwardqtys_'+product_id+'" name="inwardqtys[]"  value="'+tinwardqtys+'" >'+
                                    '</td>'+
                                    '</td>'+
                                    '<td id="sellingpricewgst_'+product_id+'">'+'<input type="text" id="showsellingwithoutgst_'+product_id+'" class="floating-input tarifform-control number" tabindex="-1" value="'+showsellingprice+'" readonly>'+
                                    '<input type="hidden" id="sellingwithoutgst_'+product_id+'" class="floating-input form-control number tsellingwithoutgst" name="tsellingwithoutgst[]" tabindex="-1"  value="'+sellingprice+'" >'+
                                    '</td>'+                  
                                    '<td id="sellingqty_'+product_id+'">'+
                                    '<input type="text" id="qty_'+product_id+'" class="floating-input tarifform-control number totqty" value="'+qty+'" name="qty[]" onkeyup="return calqty(this);" >'+
                                    '<input type="hidden" id="oldqty_'+product_id+'" class="floating-input tarifform-control number" value="'+qty+'" name="oldqty[]">'+
                                    '</td>'+       
                                    '<td id="sellingdiscountper_'+product_id+'">'+'<input type="text" id="proddiscper_'+product_id+'" class="floating-input tarifform-control number" tabindex="-1" value="'+billvalue['discount_percent']+'" name="proddiscper[]" onkeyup="return caldiscountper(this);" readonly>'+
                                    '<input type="text" id="overalldiscper_'+product_id+'" class="floating-input tarifform-control number" value="'+billvalue['overalldiscount_percent']+'" name="proddiscper[]" tabindex="-1" style="display:none;">'+'</td>'+
                                    '<td id="sellingdiscountamt_'+product_id+'">'+'<input type="text" id="mrpproddiscamt_'+product_id+'" class="floating-input tarifform-control number" tabindex="-1" value="'+totalmrpdiscount+'"  readonly>'+'<input type="text" id="proddiscamt_'+product_id+'" class="floating-input tarifform-control number pproddiscamt" tabindex="-1" value="'+totaldiscount+'" name="proddiscamt[]" onkeyup="return caldiscountamt(this);" readonly style="display:none;">'+
                                    '<input type="text" id="overalldiscamt_'+product_id+'" class="floating-input tarifform-control number overallpproddiscamt" value="'+proddiscountamt+'" tabindex="-1" name="proddiscamt[]" style="display:none;">'+'<input type="text" id="overallmrpdiscamt_'+product_id+'" class="floating-input tarifform-control number" value="'+mrpproddiscountamt+'" tabindex="-1" name="overallmrpdiscamt[]" style="display:none;">'+'</td>'+

                                    '<td style="display:none;" id="totalsellingwgst_'+product_id+'" class="totalsellingwgst" name="totalsellingwgst[]">'+discountedamt+'</td>'+
                                    '<td style="display:none;" id="totalsellinggst_'+product_id+'" class="totalsellinggst">'+sellingwithgst+'</td>'+
                                    '<td id="sprodgstper_'+product_id+'" style="text-align:right !important;">'+billvalue['igst_percent']+'</td>'+
                                    '<td id="sprodgstamt_'+product_id+'" style="text-align:right !important;">'+sgstamount+'</td>'+
                                    '<td id="prodgstper_'+product_id+'" style="display:none;" name="prodgstper[]">'+billvalue['igst_percent']+'</td>'+
                                    '<td id="prodgstamt_'+product_id+'" style="display:none;" class="totalgstamt" name="prodgstamt[]">'+gst_amount+'</td>'+

                                    '<td id="totalamount_'+product_id+'" style="font-weight:bold;display:none;" class="tsellingaftergst" name="totalamount[]">'+total_amount+'</td>'+
                                    '<td id="stotalamount_'+product_id+'" style="font-weight:bold;text-align:right !important;">'+stotal_amount+'</td>'+
                                    '<td onclick="removerow(' + product_id + ');"><i class="fa fa-close"></i></td>' +
                                    '</tr>';
                                }
                            }
                             if(productcount == 1)
                             {
                                    if(billvalue['product_type']==2)
                                    {

                                            chargecount   = 1;
                                            var cproduct_id              =   billvalue['product_id'];
                                            var chargesamt               =   billvalue['mrp']- billvalue['totalreccharges'];
                                            var maxgst                   =   billvalue['igst_percent'];
                                            

                                            var cprodgstamt               =    Number(chargesamt)   * Number(maxgst) / 100;
                                            var ctotalamt                 =    Number(chargesamt) + Number(cprodgstamt);

                                            var cshowigst_amount          =   Number(cprodgstamt).toFixed(2);
                                            var ctotal_amount             =    Number(ctotalamt).toFixed(2);

                                     chargeshtml +=   '<tr id="charges_'+cproduct_id+'">'+
                                        '<td id="chargesname_'+cproduct_id+'" style="text-align:left !important;">'+
                                        '<input value="" type="hidden" id="creturn_product_id_'+cproduct_id+'" name="creturn_product_id_[]" class="" >'+
                                        '<input value="'+billvalue['sales_products_detail_id']+'" type="hidden" id="csales_product_id_'+cproduct_id+'" name="csales_product_id_[]" class="" >'+
                                        '<input value="'+cproduct_id+'" type="hidden" id="cproductid_'+cproduct_id+'" name="cproductid[]" class="">'+billvalue['product']['product_name']+'</td>'+                                                
                                        '<td class="bold"  id="chargesamtdetails_'+cproduct_id+'">'+
                                            '<input type="text" id="chargesamt_'+cproduct_id+'" onkeyup="return addcharges(this);" class="floating-input tarifform-control number" name="chargesamt[]" value="'+chargesamt+'" readonly style="background:#f3f9ec !important;" tabindex="-1">'+
                                            '<input type="hidden" id="ochargesamt_'+cproduct_id+'" name="ochargesamt[]" value="'+chargesamt+'">'+
                                            '<input type="hidden" id="cqty_'+cproduct_id+'" class="floating-input tarifform-control number" name="cqty[]" value="'+billvalue['qty']+'">'+
                                        '</td>'+                                              
                                        '<td id="csprodgstper_'+cproduct_id+'" style="text-align:right !important;">'+billvalue['igst_percent']+'</td>'+
                                        '<td id="csprodgstamt_'+cproduct_id+'" style="text-align:right !important;">'+cshowigst_amount+'</td>'+
                                        '<td id="cprodgstper_'+cproduct_id+'" style="display:none;" name="prodgstper[]">'+billvalue['igst_percent']+'</td>'+
                                        '<td id="cprodgstamt_'+cproduct_id+'" style="display:none;" name="prodgstamt[]">'+cprodgstamt+'</td>'+
                                        '<td id="ctotalamount_'+cproduct_id+'" style="font-weight:bold;display:none;" class="ctotalamount" name="ctotalamount[]">'+ctotalamt+'</td>'+
                                        '<td id="cstotalamount_'+cproduct_id+'" style="font-weight:bold;text-align:right !important;">'+ctotal_amount+'</td>'+
                                        '<td id="cstotalamountdetails_'+cproduct_id+'" style="font-weight:bold;text-align:right !important;">'+'<input type="text" id="oldchargesamt_'+cproduct_id+'" value="'+ctotal_amount+'" class="floating-input tarifform-control number " style="display:none;">'+'<input type="text" id="creturntotalamount_'+cproduct_id+'" value="0" class="floating-input tarifform-control number chargesamt" onkeyup="return taddcharges(this);" style="width:50%;font-weight:bold;">'+'</td>'+
                                    '</tr>';
                                                   
                                    }
                                }
                                                                


                       });
                        

                       if(productcount == 0)
                       {
                           product_html += '<tr>' +
                                    '<td colspan="11" style="text-align:left !important;"><b style="color:#000;">No Products to return</b></td>'+
                                    '</tr>';
                             chargeshtml += '<tr>' +
                            '<td colspan="5" style="text-align:left !important;"><b style="color:#000;">No Charges Amount to return</b></td>'+
                            '</tr>';
                       }
                       if(chargecount == 0 && productcount == 1)
                       {
                          
                             chargeshtml += '<tr>' +
                            '<td colspan="5" style="text-align:left !important;"><b style="color:#000;">No Charges Amount to return</b></td>'+
                            '</tr>';
                       }



                       
                       $(".odd").hide();
                       $("#sproduct_detail_record").append(product_html);
                        var srr = 0;
                       $('.totqty').each(function(e){
                            if($(this).val()!='')
                            {
                                srr++;
                            }
                       });
                       $('.showtitems').show();
                       $('.titems').html(srr);
                       $('.loaderContainer').hide();
                       $("#charges_record").append(chargeshtml);
                   }
                   //end of fillup product detail row
                 
                              totalcalculation();
                   
                    
                
                }
                else if(bill_data['Success'] == "Null")
                {
                        $("#searchbilldata").prop('disabled', false);
                        $('.loaderContainer').hide();
                        toastr.error(bill_data['Message']);
                }
               else
                {
                        $("#searchbilldata").prop('disabled', false);
                        $('.loaderContainer').hide();
                        toastr.error(bill_data['Message']);
                }

              
           });
         }
         else
         {

              
               var type = "POST";
               var url = 'returnbillsecond_data';
               var data = {
                   "productsearch":productsearch
               }
             callroute(url,type,data,function(data)
            {

                var bill_data = JSON.parse(data,true);


                if(bill_data['Success'] == "True")
                {
                   
                    $('#searchbilldata').prop('disabled', true);
                    $("#addreturnpopup").modal('show');

                    var bill_details    =   bill_data['Data'];
                    
                    var bill_html = '';
                    var tblclass = '';

                    $.each(bill_details,function (billkey,billvalue)
                     {
                         if(billvalue['customer']!= null && billvalue['customer']!= '' && billvalue['customer']['customer_name']!= null && billvalue['customer']['customer_name']!= undefined)
                          {
                                    customer_name  =  billvalue['customer']['customer_name'];
                          }
                          else
                          {
                                customer_name  =  '';
                          }
                          var taxablevalue   =   billvalue['total_bill_amount'] - billvalue['total_cgst_amount'] -billvalue['total_sgst_amount'];
                          taxablevalue       =   Number(taxablevalue).toFixed(2);
                          var totalcgst      =   Number(billvalue['total_cgst_amount']).toFixed(2);
                          var totalsgst      =   Number(billvalue['total_sgst_amount']).toFixed(2);
                          var totaligst      =   Number(billvalue['total_igst_amount']).toFixed(2);
                          var billamount     =   Number(billvalue['total_bill_amount']).toFixed(0);

                         
                        var sr  =  billkey +1;
                        var taxhtml = '';

                        if(Number(tax_type)==1)
                        {
                             taxhtml = '<td style="font-size:13px !important;text-align:right !important;color:#000 !important;">'+totaligst+'</td>';
                            
                        }
                        else
                        {
                           taxhtml  =  '<td style="font-size:13px !important;text-align:right !important;color:#000 !important;">'+totalcgst+'</td>'+
                                  '<td style="font-size:13px !important;text-align:right !important;color:#000 !important;">'+totalsgst+'</td>';
                        }
                       

                        bill_html +=  '<tr class="'+tblclass+'" style="border-top:1px solid #C0C0C0 !important;">'+
                                  '<td style="font-size:13px !important;" class="leftAlign"><button id="returnbillno_'+billvalue['sales_bill_id']+'" onclick="return popreturndata(this);" class="btn btn-primary" style="color:#fff;padding: .15rem .35rem;line-height:1.3 !important;"><i class="fa fa-check" style="margin:0 !important;"></i> Select</button>'+
                                  '<input type="hidden" value="'+billvalue['bill_no']+'" id="rbillno_'+billvalue['sales_bill_id']+'">'+'</td>'+                                 
                                  '<td style="font-size:13px !important;font-weight:bold !important;" class="leftAlign"><span style="cursor:pointer;">'+billvalue['bill_no']+'</span></td>'+
                                  '<td style="font-size:13px !important;" class="leftAlign">'+billvalue['bill_date']+'</td>'+
                                  '<td style="font-size:13px !important;" class="leftAlign">'+customer_name+'</td>'+
                                  '<td style="font-size:13px !important;text-align:right !important;color:#000 !important;">'+billvalue['total_qty']+'</td>'+
                                  '<td style="font-size:13px !important;text-align:right !important;color:#000 !important;">'+taxablevalue+'</td>'+taxhtml+                                  
                                  '<td style="font-size:13px !important;text-align:right !important;color:#000 !important;">'+billamount+'</td>'+
                                  '<td style="text-align:center !important;"><span id="down_'+billvalue['sales_bill_id']+'" onclick="return showdetails(this);" style="font-weight:bold;font-size:14px;color:#28a745 !important;">Show</span><span id="up_'+billvalue['sales_bill_id']+'" onclick="return hidedetails(this);" style="font-weight:bold;font-size:14px;color:#f00 !important;display:none;">Hide</span></td>'+
                                '</tr>';

                                var headinghtml = '';

                                if(Number(tax_type)==1)
                                {
                                     headinghtml = '<th scope="col" style="width:12%;cursor: pointer;text-align:right !important;"><b>'+tax_title+' Amt.</b></th>';
                                }
                                else
                                {
                                    headinghtml  =  '<th scope="col" style="width:12%;cursor: pointer;text-align:right !important;"><b>CGST Amt.</b></th>'+
                                                '<th scope="col" style="width:12%;cursor: pointer;text-align:right !important;"><b>SGST Amt.</b></th>';
                                   
                                }

                          bill_html +=  '<tr id="show_'+billvalue['sales_bill_id']+'" style="display:none;">'+
                                '<td colspan="10">'+
                                    '<table class="table table-striped mb-0" style="width:100%;">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th scope="col" style="width:12%;cursor: pointer;"><b>Barcode</b></th>'+
                                                '<th scope="col" style="width:12%;cursor: pointer;"><b>Product Name</b></th>'+
                                                '<th scope="col" style="width:12%;cursor: pointer;"><b>Qty</b></th>'+
                                                '<th scope="col" style="width:12%;cursor: pointer;text-align:right !important;"><b>Taxable Value</b></th>'+headinghtml+   
                                                '<th scope="col" style="width:12%;cursor: pointer;text-align:right !important;"><b>Bill Amount</b></th>'+
                                            '</tr>'+
                                            '</thead>'+
                                            '<tbody id="showproductdetails">'
                                            
                                            $.each(billvalue['sales_product_detail'],function (billpkey,billpvalue)
                                            {
                                                  var ptax    =    Number(billpvalue['sellingprice_afteroverall_discount']).toFixed(2);
                                                  var pcgst   =    Number(billpvalue['cgst_amount']).toFixed(2);
                                                  var psgst   =    Number(billpvalue['sgst_amount']).toFixed(2);
                                                  var pigst   =    Number(billpvalue['igst_amount']).toFixed(2);
                                                  var pamount =    Number(billpvalue['total_amount']).toFixed(2);

                                                if (billpkey % 2 == 0) {
                                                    tblclass = 'even';
                                                } else {
                                                    tblclass = 'odd';
                                                }
                                                var ptaxhtml = '';

                                                if(Number(tax_type)==1)
                                                {
                                                     ptaxhtml = '<td style="font-size:13px !important;text-align:right !important;color:#000 !important;">'+pigst+'</td>';
                                                }
                                                else
                                                {
                                                    ptaxhtml  =  '<td style="font-size:13px !important;text-align:right !important;color:#000 !important;">'+pcgst+'</td>'+
                                                  '<td style="font-size:13px !important;text-align:right !important;color:#000 !important;">'+psgst+'</td>';
                                                   
                                                }

                                                bill_html +=  '<tr style="border-bottom:0px solid #C0C0C0 !important;">'+
                                                  '<td style="font-size:13px !important;" class="leftAlign">'+billpvalue['product']['product_system_barcode']+'</td>'+
                                                  '<td style="font-size:13px !important;font-weight:bold !important;" class="leftAlign">'+billpvalue['product']['product_name']+'</td>'+
                                                  '<td style="font-size:13px !important;text-align:right !important;color:#000 !important;">'+billpvalue['qty']+'</td>'+
                                                  '<td style="font-size:13px !important;text-align:right !important;color:#000 !important;">'+ptax+'</td>'+ptaxhtml+
                                                  '<td style="font-size:13px !important;text-align:right !important;color:#000 !important;">'+pamount+'</td>'+
                                                '</tr>';
                                            });


                                            bill_html +=  '</tbody>'+
                                            '</table>'+
                                        '</tr>';                                        
                    });

                   

                }

                 else
                    {
                            $("#searchbilldata").prop('disabled', false);
                            $('.loaderContainer').hide();
                            toastr.error(bill_data['Message']);

                    }
                    $("#productdetails").prepend(bill_html);
                 
           });

         }
   }

});
 
function showdetails(obj)
{
    var id                       =     $(obj).attr('id');
    var salesid                  =     $(obj).attr('id').split('down_')[1];
    $('#show_'+salesid).toggle();
    $('#down_'+salesid).hide();
    $('#up_'+salesid).show();
}
function hidedetails(obj)
{
    var id                       =     $(obj).attr('id');
    var salesid                  =     $(obj).attr('id').split('up_')[1];
    $('#show_'+salesid).toggle();
    $('#down_'+salesid).show();
    $('#up_'+salesid).hide();
}
function popreturndata(obj)
{

    var id                       =     $(obj).attr('id');
    var salesid                  =     $(obj).attr('id').split('returnbillno_')[1];
    var billno                   =     $('#rbillno_'+salesid).val();

    $('#returnbillno_'+salesid).attr("disabled", "disabled");
   
            $('.loaderContainer').show();
               var type = "POST";
               var url = 'returnbill_data';
               var data = {
                   "bill_no" : billno,
               }
           callroute(url,type,data,function(data)
           {

                var bill_data = JSON.parse(data,true);
                 $("#addreturnpopup").modal('hide');

                if(bill_data['Success'] == "True")
                {
                    $('#searchbilldata').prop('disabled', true);
                    var bill_detail         = bill_data['Data'];
                    var bill_productdetail  = bill_data['ProductData'];

                    var customer_name = '';
                    var customer_mobile ='';
                    var customer_email='';
                    var cusreference='';
                    
                      if(bill_detail['customer']!= null && bill_detail['customer']!= '' && bill_detail['customer']['customer_name']!= null && bill_detail['customer']['customer_name']!= undefined)
                      {
                                customer_name  =  bill_detail['customer']['customer_name'];
                      }
                      
                      if(bill_detail['customer']!= null && bill_detail['customer']!= '' && bill_detail['customer']['customer_mobile']!= null && bill_detail['customer']['customer_mobile']!= undefined)
                      {
                                customer_mobile  =  bill_detail['customer']['customer_mobile'];
                      }
                     
                     if(bill_detail['customer']!= null && bill_detail['customer']!= '' && bill_detail['customer']['customer_email']!= null && bill_detail['customer']['customer_email']!= undefined)
                      {
                                customer_email  =  bill_detail['customer']['customer_email'];
                      }
                     
                      if(bill_detail['reference']!= null && bill_detail['reference']!= '' && bill_detail['reference']['reference_name']!= null && bill_detail['reference']['reference_name']!= undefined)
                      {
                                cusreference  =  bill_detail['reference']['reference_name'];
                      }


                       $('#ccustomer_id').val(bill_detail['customer_id']);
                       $("#sales_bill_id").val(bill_detail['sales_bill_id']);
                       $("#invoice_date").val(bill_detail['bill_date']);
                       $("#invoice_no").val(bill_detail['bill_no']);
                       $("#discount_percent").val(bill_detail['discount_percent']);
                       $("#discount_amount").val(bill_detail['discount_amount']);
                       $("#roomwisediscount_amount").val(bill_detail['productwise_discounttotal']);

                        $("#customer_name").val(customer_name);
                        $("#customer_mobile").val(customer_mobile);
                        $("#customer_email").val(customer_email);
                        $("#refname").val(cusreference);


                   //console.log(bill_detail['customer_creditaccount']);
                    if(bill_detail['customer_creditaccount'] != 'undefined' && bill_detail['customer_creditaccount'] != '' && bill_detail['customer_creditaccount'] != null)
                    {
                             
                            $("#creditaccountid").val(bill_detail['customer_creditaccount']['customer_creditaccount_id']);
                            $("#totalcreditamount").val(bill_detail['customer_creditaccount']['credit_amount']);  
                            $("#totalcreditbalance").val(bill_detail['customer_creditaccount']['balance_amount']);   
                    }
                    

          
                var productcount  = 0;
                var chargecount  = 0;

                 console.log(bill_productdetail);
                   
                if(bill_productdetail != 'undefined' && bill_productdetail != '')
               {

                   var product_html = '';   
                    var pcount    = 0;
                    var sellingprice  = 0;
                    var stock = 0;
                    var pricehtml = '';  
                    var chargeshtml = ''; 
                      
                   $.each(bill_productdetail,function (billkey,billvalue)
                   {
                        
                       if(billvalue['product_type']==1)
                       {

                                var rinwardids      =     (billvalue['inwardids'].slice(0,-1)).split(',');
                                var rinwardqtys     =     (billvalue['inwardqtys'].slice(0,-1)).split(',');
                                var tinwardids      =      '';
                                var tinwardqtys     =      '';
                                var totiqty         =      '';
                                var totalreturnqty  =      '';

                                console.log(billvalue['return_product_detail']);

                 if(billvalue['return_product_detail'] != 'undefined' && billvalue['return_product_detail'] != '')
                {               

                    $.each(rinwardids,function (ridkey,ridvalue)
                    {
                        
                            var totiqty         =      '';
                              $.each(billvalue['return_product_detail'],function (rbillkey,rbillvalue)
                              {
                                 
                                    
                                    var iids             =     (rbillvalue['inwardids'].slice(0,-1)).split(',');
                                    var iqtys            =     (rbillvalue['inwardqtys'].slice(0,-1)).split(',');
                                       
                                            //console.log(ridvalue);
                                              
                                            $.each(iids,function (iidkey,iidvalue)
                                            {
                                                if(ridvalue == iidvalue)
                                                {
                                                    totiqty  =  Number(totiqty) + Number(iqtys[iidkey]);
                                                }
                                                
                                            })
                                            //console.log(totiqty);
                                           

                                           
                                })
                                            if(totiqty!='')
                                            {
                                                if(rinwardqtys[ridkey] != totiqty)
                                                {
                                                    // console.log('aaa');
                                                     tinwardids     +=     ridvalue+',';
                                                     tinwardqtys    +=     (rinwardqtys[ridkey]-totiqty)+',';
                                                }
                                            }
                            
                           
                            
                       })
                 }

                else
                {
                     tinwardids     +=     billvalue['inwardids'];
                     tinwardqtys    +=     billvalue['inwardqtys'];
                 }

                               if(billvalue['qty']==billvalue['totalreturnqty'])
                               {

                               }
                               else
                               {
                                        productcount =  1;
                                        var qty                         =   billvalue['qty'] - billvalue['totalreturnqty'];

                                        var product_id                  =   billvalue['price_master_id'];

                                        var sellingprice                =   billvalue['sellingprice_before_discount'];
                                        
                                        var discount_percent            =   billvalue['discount_percent'];
                                        var overalldiscount_percent     =   billvalue['overalldiscount_percent'];
                                        var gst_percent                 =   billvalue['igst_percent'];

                                        var totalmrpdiscount            =   (Number(billvalue['mrp']) * Number(qty)) * Number(discount_percent) / 100;

                                        var totalsellingwgst             =     Number(sellingprice) * Number(qty);
                                        var sellingdiscount              =     (Number(sellingprice) * Number(discount_percent) / 100).toFixed(4);
                                        var gst_amount                   =     (Number(sellingprice-sellingdiscount) * Number(gst_percent) / 100).toFixed(4);

                                        var totaldiscount                =      Number(sellingdiscount) * Number(qty);

                                        var discountedamt                =      Number(totalsellingwgst) - Number(totaldiscount);

                                        var mrp                          =     Number(totaldiscount) + Number(gst_amount);
                                        
                                        var totalgst                     =      Number(gst_amount) * Number(qty);


                                        var sellingwithgst                =      Number(discountedamt) + Number(totalgst);



                                      var totalsellingwgst             =     discountedamt;


                                      var mrpproddiscountamt              =     ((Number(sellingwithgst) * Number(overalldiscount_percent)) / 100).toFixed(4);
                                  
                                      
                                      var proddiscountamt              =     ((Number(totalsellingwgst) * Number(overalldiscount_percent)) / 100).toFixed(4);
                                      var totalproddiscountamt         =     Number(proddiscountamt)

                                      var sellingafterdiscount          =     Number(totalsellingwgst) - Number(proddiscountamt);

                                       
                                        
                                        var gst_amount                   =     ((Number(sellingafterdiscount) * Number(gst_percent)) / 100).toFixed(4);
                                        
                                       
                                        var sgstamount                   =     ((Number(sellingafterdiscount) * Number(gst_percent)) / 100).toFixed(2);
                                        var total_amount                 =     Number(sellingafterdiscount) + Number(gst_amount);




                                        var showsellingprice              =   Number(billvalue['sellingprice_before_discount']).toFixed(2);
                                        var showigst_amount               =   Number(billvalue['igst_amount']).toFixed(2);
                                        var mrp                           =    Number(billvalue['mrp']).toFixed(2);
                                        var total_amount                  =    Number(total_amount).toFixed(4);
                                        var stotal_amount                 =    Number(total_amount).toFixed(2);

                         if(bill_data['Billtype']==3)
                          {
                            var batchhtml   =   '<td id="batchno_'+product_id+'">'+billvalue['batchprice_master']['batch_no']+'</td>';
                          } 
                          else
                          {
                            var batchhtml  = '';
                          }    
                          if(billvalue['product']['supplier_barcode']!='' && billvalue['product']['supplier_barcode']!=null)
                            {
                              var barcode     =     billvalue['product']['supplier_barcode'];
                            }
                            else
                            {
                              var barcode     =     billvalue['product']['product_system_barcode'];
                            } 
                        
                           product_html += '<tr id="product_' + product_id + '">' +
                                    '<td id="barcodesel_'+product_id+'" name="barcode_sel[]">'+barcode+'</td>'+
                                    '<td id="roomnoval_'+product_id+'" style="display:none;">'+
                                    '<input value="'+billvalue['sales_products_detail_id']+'" type="hidden" id="sales_product_id_'+product_id+'" name="sales_product_id[]" class="" >'+
                                    '<input value="" type="hidden" id="return_product_id_'+product_id+'" name="return_product_id_[]" class="" >'+
                                    '<input value="'+billvalue['product_id']+'" type="hidden" id="productid_'+product_id+'" name="productid[]" class="allbarcode" >'+

                                    '</td>'+
                                    '<td id="product_name_'+product_id+'" name="product_name[]">'+billvalue['product']['product_name']+'</td>'+ batchhtml+'<td id="sellingmrp_'+product_id+'">'+'<input type="text" id="mrp_'+product_id+'" class="floating-input tarifform-control number" value="'+mrp+'" readonly tabindex="-1">'+
                                    '<input type="hidden" id="pricemasterid_'+product_id+'" name="pricemasterid[]"  value="'+billvalue['price_master_id']+'" >'+
                                     '<input type="hidden" id="inwardids_'+product_id+'" name="inwardids[]"  value="'+tinwardids+'" >'+
                                    '<input type="hidden" id="inwardqtys_'+product_id+'" name="inwardqtys[]"  value="'+tinwardqtys+'" >'+
                                    '</td>'+
                                    '<td id="sellingpricewgst_'+product_id+'">'+
                                    '<input type="text" id="showsellingwithoutgst_'+product_id+'" class="floating-input tarifform-control number" value="'+showsellingprice+'" readonly tabindex="-1">'+
                                    '<input type="hidden" id="sellingwithoutgst_'+product_id+'" class="floating-input form-control number tsellingwithoutgst" name="tsellingwithoutgst[]"  value="'+sellingprice+'" >'+
                                    '</td>'+                  
                                    '<td id="sellingqty_'+product_id+'">'+
                                    '<input type="text" id="qty_'+product_id+'" class="floating-input tarifform-control number totqty" value="'+qty+'" name="qty[]" onkeyup="return calqty(this);">'+
                                    '<input type="hidden" id="oldqty_'+product_id+'" class="floating-input tarifform-control number" value="'+qty+'" name="oldqty[]">'+
                                    '</td>'+       
                                    '<td id="sellingdiscountper_'+product_id+'">'+'<input type="text" id="proddiscper_'+product_id+'" class="floating-input tarifform-control number" value="'+billvalue['discount_percent']+'" name="proddiscper[]" onkeyup="return caldiscountper(this);" readonly tabindex="-1">'+
                                    '<input type="text" id="overalldiscper_'+product_id+'" class="floating-input tarifform-control number" value="'+billvalue['overalldiscount_percent']+'" name="proddiscper[]" style="display:none;">'+'</td>'+
                                    '<td id="sellingdiscountamt_'+product_id+'">'+'<input type="text" id="mrpproddiscamt_'+product_id+'" class="floating-input tarifform-control number" value="'+totalmrpdiscount+'" readonly tabindex="-1">'+'<input type="text" id="proddiscamt_'+product_id+'" class="floating-input tarifform-control number pproddiscamt" value="'+totaldiscount+'" name="proddiscamt[]" onkeyup="return caldiscountamt(this);" readonly tabindex="-1" style="display:none;">'+
                                    '<input type="text" id="overalldiscamt_'+product_id+'" class="floating-input tarifform-control number overallpproddiscamt" value="'+proddiscountamt+'" name="proddiscamt[]" style="display:none;">'+'<input type="text" id="overallmrpdiscamt_'+product_id+'" class="floating-input tarifform-control number" value="'+mrpproddiscountamt+'" name="overallmrpdiscamt[]" style="display:none;">'+'</td>'+

                                    '<td style="display:none;" id="totalsellingwgst_'+product_id+'" class="totalsellingwgst" name="totalsellingwgst[]">'+discountedamt+'</td>'+
                                    '<td style="display:none;" id="totalsellinggst_'+product_id+'" class="totalsellinggst">'+sellingwithgst+'</td>'+
                                    '<td id="sprodgstper_'+product_id+'" style="text-align:right !important;">'+billvalue['igst_percent']+'</td>'+
                                    '<td id="sprodgstamt_'+product_id+'" style="text-align:right !important;">'+sgstamount+'</td>'+
                                    '<td id="prodgstper_'+product_id+'" style="display:none;" name="prodgstper[]">'+billvalue['igst_percent']+'</td>'+
                                    '<td id="prodgstamt_'+product_id+'" style="display:none;" class="totalgstamt" name="prodgstamt[]">'+gst_amount+'</td>'+

                                    '<td id="totalamount_'+product_id+'" style="font-weight:bold;display:none;" class="tsellingaftergst" name="totalamount[]">'+total_amount+'</td>'+
                                    '<td id="stotalamount_'+product_id+'" style="font-weight:bold;text-align:right !important;">'+stotal_amount+'</td>'+
                                    '<td onclick="removerow(' + product_id + ');"><i class="fa fa-close"></i></td>' +
                                    '</tr>';
                                }
                            }
                             if(productcount == 1)
                             {
                                    if(billvalue['product_type']==2)
                                    {

                                            chargecount   = 1;
                                            var cproduct_id              =   billvalue['product_id'];
                                            var chargesamt               =   billvalue['mrp']- billvalue['totalreccharges'];
                                            var maxgst                   =   billvalue['igst_percent'];
                                            

                                            var cprodgstamt               =    Number(chargesamt)   * Number(maxgst) / 100;
                                            var ctotalamt                 =    Number(chargesamt) + Number(cprodgstamt);

                                            var cshowigst_amount          =   Number(cprodgstamt).toFixed(2);
                                            var ctotal_amount             =    Number(ctotalamt).toFixed(2);

                                            chargeshtml +=   '<tr id="charges_'+cproduct_id+'">'+
                                        '<td id="chargesname_'+cproduct_id+'" style="text-align:left !important;">'+
                                        '<input value="" type="hidden" id="creturn_product_id_'+cproduct_id+'" name="creturn_product_id_[]" class="" >'+
                                        '<input value="'+billvalue['sales_products_detail_id']+'" type="hidden" id="csales_product_id_'+cproduct_id+'" name="csales_product_id_[]" class="" >'+
                                        '<input value="'+cproduct_id+'" type="hidden" id="cproductid_'+cproduct_id+'" name="cproductid[]" class="">'+billvalue['product']['product_name']+'</td>'+                                                
                                        '<td class="bold"  id="chargesamtdetails_'+cproduct_id+'">'+
                                            '<input type="text" id="chargesamt_'+cproduct_id+'" onkeyup="return addcharges(this);" class="floating-input tarifform-control number" name="chargesamt[]" value="'+chargesamt+'" readonly style="background:#f3f9ec !important;" tabindex="-1">'+
                                            '<input type="hidden" id="ochargesamt_'+cproduct_id+'" name="ochargesamt[]" value="'+chargesamt+'">'+
                                            '<input type="hidden" id="cqty_'+cproduct_id+'" class="floating-input tarifform-control number" name="cqty[]" value="'+billvalue['qty']+'">'+
                                        '</td>'+                                              
                                        '<td id="csprodgstper_'+cproduct_id+'" style="text-align:right !important;">'+billvalue['igst_percent']+'</td>'+
                                        '<td id="csprodgstamt_'+cproduct_id+'" style="text-align:right !important;">'+cshowigst_amount+'</td>'+
                                        '<td id="cprodgstper_'+cproduct_id+'" style="display:none;" name="prodgstper[]">'+billvalue['igst_percent']+'</td>'+
                                        '<td id="cprodgstamt_'+cproduct_id+'" style="display:none;" name="prodgstamt[]">'+cprodgstamt+'</td>'+
                                        '<td id="ctotalamount_'+cproduct_id+'" style="font-weight:bold;display:none;" class="ctotalamount" name="ctotalamount[]">'+ctotalamt+'</td>'+
                                        '<td id="cstotalamount_'+cproduct_id+'" style="font-weight:bold;text-align:right !important;">'+ctotal_amount+'</td>'+
                                        '<td id="cstotalamountdetails_'+cproduct_id+'" style="font-weight:bold;text-align:right !important;">'+'<input type="text" id="oldchargesamt_'+cproduct_id+'" value="'+ctotal_amount+'" class="floating-input tarifform-control number " style="display:none;">'+'<input type="text" id="creturntotalamount_'+cproduct_id+'" value="0" class="floating-input tarifform-control number chargesamt" onkeyup="return taddcharges(this);" style="width:50%;font-weight:bold;">'+'</td>'+
                                    '</tr>';

                                    
                                                   
                                    }
                                }
                                                                


                       });
                        

                       if(productcount == 0)
                       {
                           product_html += '<tr>' +
                                    '<td colspan="11" style="text-align:left !important;"><b style="color:#000;">No Products to return</b></td>'+
                                    '</tr>';
                             chargeshtml += '<tr>' +
                            '<td colspan="5" style="text-align:left !important;"><b style="color:#000;">No Charges Amount to return</b></td>'+
                            '</tr>';
                       }
                       if(chargecount == 0 && productcount == 1)
                       {
                          
                             chargeshtml += '<tr>' +
                            '<td colspan="5" style="text-align:left !important;"><b style="color:#000;">No Charges Amount to return</b></td>'+
                            '</tr>';
                       }
                       
                       $(".odd").hide();
                       $("#sproduct_detail_record").append(product_html);
                       var srr = 0;
                       $('.totqty').each(function(e){
                            if($(this).val()!='')
                            {
                                srr++;
                            }
                       });
                       $('.showtitems').show();
                       $('.titems').html(srr);
                       $("#charges_record").append(chargeshtml);
                       $('.loaderContainer').hide();
                   }
                   //end of fillup product detail row
                 
                              totalcalculation();
                   
                    
                
                }
                else if(bill_data['Success'] == "Null")
                {
                        $("#searchbilldata").prop('disabled', false);
                        $('.loaderContainer').hide();
                        toastr.error(bill_data['Message']);
                }
               else
                {
                        $("#searchbilldata").prop('disabled', false);
                        $('.loaderContainer').hide();
                        toastr.error(bill_data['Message']);
                }

              
           });
    
}
                         

function restockqty(obj)
{
    var id                        =     $(obj).attr('id');
    var returnid                  =     $(obj).attr('id').split('restock_')[1];
    var restock                   =     $('#restock_'+returnid).val();
    var damage                    =     $('#damage_'+returnid).val();
    var totalreturn               =     $('#returnqty_'+returnid).html();

    

    var damage  =    Number(totalreturn)  -   Number(restock);

    if(Number(damage)<0)
    {
        $('#damage_'+returnid).val(0);
        $('#restock_'+returnid).val(totalreturn);
    }
    else
    {
         $('#damage_'+returnid).val(damage);
    }
    

}
function damageqty(obj)
{
    var id                        =     $(obj).attr('id');
    var returnid                  =     $(obj).attr('id').split('damage_')[1];
    var restock                   =     $('#restock_'+returnid).val();
    var damage                    =     $('#damage_'+returnid).val();
    var totalreturn               =     $('#returnqty_'+returnid).html();

    var restock  =    Number(totalreturn)  -   Number(damage);     
        
   
   if(Number(restock)<0)
    {
        $('#damage_'+returnid).val(totalreturn);
        $('#restock_'+returnid).val(0);
    }
    else
    {
         $('#restock_'+returnid).val(restock);
    }

}

function savereturn(obj)
{
 
    var id                        =     $(obj).attr('id');
    var returnid                  =     $(obj).attr('id').split('addreturnproducts_')[1];
    var arrayValue                =     [];
    var return_values             =     {};

    return_values['returnbill_product_id']    =   returnid;
    return_values['returnqty']                =   $('#returnqty_'+returnid).html();
    return_values['restockqty']               =   $('#restock_'+returnid).val();
    return_values['damageqty']                =   $('#damage_'+returnid).val();
    return_values['price_master_id']          =   $('#pricemasterid_'+returnid).val();
    return_values['product_id']               =   $('#productid_'+returnid).val();
    return_values['sales_products_detail_id'] =   $('#salesproductid_'+returnid).val();
    return_values['inwardids']                =   $('#inwardids_'+returnid).val();
    return_values['inwardqtys']               =   $('#inwardqtys_'+returnid).val();
    return_values['remarks']                  =   $('#remarks_'+returnid).val();
   

    if(return_values['restockqty']=='' && return_values['restockqty']=='')
    {
        toastr.error('Please Enter Qty to ReStock and Damage');
        $('#restock_'+returnid).focus();
        return false;
    }
    else if(return_values['damageqty']>0 && return_values['remarks']=='')
    {  
            toastr.error('Please mention Remarks for Damage Product');
            $('#remarks_'+returnid).focus();
            return false;
       
    }
    else
    {
        if(confirm("Are You Sure to Restock or add products to Damage List ?")) {

                arrayValue.push(return_values);

                var data = arrayValue;

                //console.log(data);
                //return false;

                var  url = "restock_products";
                var type = "POST";
                callroute(url,type,data,function (data)
                {
                     var dta = JSON.parse(data);

                        if (dta['Success'] == "True")
                        {
                            toastr.success(dta['Message']);
                            $("#viewbillform").trigger('reset');
                            resettable('viewreturn_data','view_bill_record');

                        } else {
                            toastr.error(dta['Message']);
                        }
                    
                });
            }
            else
            {
                 return false;
            }

        
    }

}



/*$(document).ready(function () {
    localStorage.removeItem('edit_bill_record');
})*/