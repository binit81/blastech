$("#inward_date").datepicker({
    format: 'dd-mm-yyyy',
    orientation: "bottom",
    autoclose: true

});
$(document).ready(function(e){

  if(localStorage.getItem('inward_kit_record'))
  {
      var edit_data  = localStorage.getItem('inward_kit_record');
      
      
      if(edit_data != '' && edit_data != undefined && edit_data != null)
      {

           var edit_kitdata = JSON.parse(edit_data);
           var barcode = edit_kitdata['product_system_barcode'];
           var product_name = edit_kitdata['product_name'];
           var showproductbarcode  =  barcode+'_'+product_name;
           $('#productkitsearch').val(showproductbarcode);
           $('#productkitsearch').prop('readonly',true);
          
           getproductkitdetail(barcode,product_name);  


      }
  }
  if(localStorage.getItem('edit_kitinward_record'))
  {
      var edit_data  = localStorage.getItem('edit_kitinward_record');
      
      
      if(edit_data != '' && edit_data != undefined && edit_data != null)
      {

           var edit_kitdata = JSON.parse(edit_data);
           //console.log(edit_kitdata[0]); 
           $('#product_id').val(edit_kitdata[0]['kitinward_product_detail']['product_id']);
           $("#inward_stock_id").val(edit_kitdata[0]['inward_stock_id']);
           $("#inward_product_detail_id").val(edit_kitdata[0]['kitinward_product_detail']['inward_product_detail_id']);

           var showproductbarcode  =  edit_kitdata[0]['kitinward_product_detail']['product']['product_system_barcode']+'_'+edit_kitdata[0]['kitinward_product_detail']['product']['product_name'];
           $('#productkitsearch').val(showproductbarcode);
           $('#productkitsearch').prop('readonly',true);
           $('#inward_qty').val(edit_kitdata[0]['total_qty']);
           $('#oldinward_qty').val(edit_kitdata[0]['total_qty']);
           $('#inward_date').val(edit_kitdata[0]['inward_date']);

           var product_html = '';

        $.each(edit_kitdata[0]['inward_kit_detail'],function (kitkey,kitvalue)
       {

              var kitproductid  =  kitvalue['kitproduct_id'];
              var product_id    =  kitvalue['product_id'];

           
               
                     var stock = 0;
                    $.each(kitvalue['price_master'],function (key,value)
                    {
                      
                            stock         +=   value['product_qty'];                     
                  
                      
                    });

                     if(kitvalue['itemproduct']['supplier_barcode']!='' && kitvalue['itemproduct']['supplier_barcode']!=null)
                      {
                        var barcode     =     kitvalue['itemproduct']['supplier_barcode'];
                      }
                      else
                      {
                        var barcode     =     kitvalue['itemproduct']['product_system_barcode'];
                      }

                        

                        var colour_name = '';
                        var size_name   = '';
                        var uqc_name    = '';

                        if(kitvalue['itemproduct']['colour_id']!=null)
                        {
                          colour_name   = kitvalue['itemproduct']['colour']['colour_name'];
                        }

                        if(kitvalue['itemproduct']['size_id']!=null)
                        {
                          size_name   = kitvalue['itemproduct']['size']['size_name'];
                        }

                        if(kitvalue['itemproduct']['uqc']!=null)
                        {
                          uqc_name   = kitvalue['itemproduct']['uqc']['uqc_name'];
                        }
                        
                        var comboqty   = Number(kitvalue['qty']) / edit_kitdata[0]['total_qty'];

                      product_html += '<tr id="product_' + product_id + '">' +
                      '<td class="pt-15 pb-15 leftAlign" id="product_name_'+product_id+'" name="product_name[]"><a id="popupid_'+product_id+'" onclick="return productdetailpopup(this);"><span class="informative">'+kitvalue['itemproduct']['product_name']+'</span></a></td>'+ 
                          '<td class="leftAlign"><a id="popupid_'+product_id+'" onclick="return productdetailpopup(this);">'+barcode+'</a></td>'+
                          '<td class="leftAlign"><a id="popupid_'+product_id+'" onclick="return productdetailpopup(this);">'+size_name+'</a></td>'+
                          '<td class="leftAlign"><a id="popupid_'+product_id+'" onclick="return productdetailpopup(this);">'+colour_name+'</a></td>'+
                          '<td class="leftAlign"><a id="popupid_'+product_id+'" onclick="return productdetailpopup(this);">'+uqc_name+'</a></td>'+
                          '<td id="roomnoval_'+product_id+'" style="display:none;">'+
                          '<input value="'+barcode+'" type="hidden" id="barcodesel_'+product_id+'" name="barcode_sel[]">'+
                          '<input type="hidden" id="inward_kit_detail_id_'+product_id+'" name="inward_kit_detail_id[]" class="" value="'+kitvalue['inward_kit_detail_id']+'">'+
                          '<input type="hidden" id="inwardids_'+product_id+'" name="inwardids[]" class=""  value="'+kitvalue['inwardids']+'">'+
                          '<input type="hidden" id="inwardqtys_'+product_id+'" name="inwardqtys[]" class=""  value="'+kitvalue['inwardqtys']+'">'+
                          '<input value="'+kitvalue['product_id']+'" type="hidden" id="itemproductid_'+product_id+'" name="itemproductid[]">'+
                          '<input value="'+kitvalue['kitproduct_id']+'" type="hidden" id="kitproductid_'+product_id+'" name="kitproductid[]">'+
                          '</td>'+
                          '<td id="showstock_'+product_id+'" class="rightAlign">'+stock+'<input value="'+stock+'" type="hidden" id="stock_'+product_id+'" name="stock[]">'+'</td>'+  
                          '<td id="showkitqty_'+product_id+'" class="rightAlign">'+comboqty+'<input value="'+comboqty+'" type="hidden" id="kitqty_'+product_id+'" name="kitqty[]">'+'</td>'+                          
                          '<td id="showtotalqty_'+product_id+'" style="font-weight:bold;" class="rightAlign"><input value="'+kitvalue['qty']+'" type="text" id="totalqty_'+product_id+'" name="totalqty[]" class="form-control mt-15 totqty" style="width:100% !important;" readonly><input value="'+kitvalue['qty']+'" type="hidden" id="oldkitqty_'+product_id+'" name="oldkitqty[]"></td>'+
                          '</tr>'; 

 


        });
        $("#kitSearchResult").prepend(product_html);
        totalcalculation();

        $('.loaderContainer').hide();
              
          var srrno  = 0;
          $('.totqty').each(function(e){
              var ssrno  = 0;
              if($(this).val()!='')
              {
                  srrno++;
              }
          });
        srrno;


      $('.titems').html(srrno);  
      }
  }


});

$("#productkitsearch").typeahead({

    source: function(request, process) {
       $.ajax({
           url: "inwardkit_search",
           dataType: "json",
           data: {
                search_val: $("#productkitsearch").val(),
                term: request.term
            },
           success: function (data) {$("#productkitsearch").val()
                   
                 objects = [];
                 map = {};

                if($("#productkitsearch").val()!='')
                  {
                    
                     $.each(data, function(i, object)
                    {
                        map[object.label] = object;
                        objects.push(object.label);
                    });
                    process(objects);

                  
                      if(objects!='')
                      {
                        if(objects.length === 1) {
                           $('.loaderContainer').show();
                             $(".dropdown-menu .active").trigger("click");
                             $("#productkitsearch").val('');  
                            }

                        }
                    
                  }
                  else
                  {
                    $(".dropdown-menu").hide(); 
                  }

                  
           }
     });
    },
    
    minLength: 1,
   // autoSelect:false,
   // typeahead-select-on-exact="true"
     afterSelect: function (item) {
      $('.loaderContainer').show();
        var value = item;
        var barcode = map[item]['barcode'];
        var product_name = map[item]['product_name'];
        var systembarcode = map[item]['systembarcode'];
         getproductkitdetail(barcode,product_name);   
    }
     
});

function getproductkitdetail(barcode,product_name)
{

    
   var columnid   =   columnid;
   var type = "POST";
   var url = 'productkit_detail';
   var data = {
       "barcode" : barcode,
       "product_name":product_name
   }
   callroute(url,type,data,function(data)
   {
        
        var product_data = JSON.parse(data,true);
        $('#kitSearchResult').html('');

        if(product_data['Success'] == "True")
        {
            var kitproductid = '';
            var product_html = '';
            var product_detail  = product_data['Data'][0];

            var skucode = '';
            var pricehtml = '';
            var pcount    = 0;
            var sellingprice  = 0;
            //var stock = 0;
            var gst_per = 0;

            $.each(product_detail['combo_products_detail'],function (combokey,combovalue)
            {

               kitproductid  =  combovalue['kitproduct_id'];
              var product_id    =  combovalue['product_id'];

             
               
                     var stock = 0;
                    $.each(combovalue['price_master'],function (key,value)
                    {
                      
                            stock         +=   value['product_qty'];                     
                  
                      
                    });

                     if(combovalue['product']['supplier_barcode']!='' && combovalue['product']['supplier_barcode']!=null)
                      {
                        var barcode     =     combovalue['product']['supplier_barcode'];
                      }
                      else
                      {
                        var barcode     =     combovalue['product']['product_system_barcode'];
                      }

                        

                        var colour_name = '';
                        var size_name   = '';
                        var uqc_name    = '';

                        if(combovalue['product']['colour_id']!=null)
                        {
                          colour_name   = combovalue['product']['colour']['colour_name'];
                        }

                        if(combovalue['product']['size_id']!=null)
                        {
                          size_name   = combovalue['product']['size']['size_name'];
                        }

                        if(combovalue['product']['uqc']!=null)
                        {
                          uqc_name   = combovalue['product']['uqc']['uqc_name'];
                        }
                       
                      product_html += '<tr id="product_' + product_id + '">' +
                      '<td class="pt-15 pb-15 leftAlign" id="product_name_'+product_id+'" name="product_name[]"><a id="popupid_'+product_id+'" onclick="return productdetailpopup(this);"><span class="informative">'+combovalue['product']['product_name']+'</span></a></td>'+ 
                          '<td class="leftAlign"><a id="popupid_'+product_id+'" onclick="return productdetailpopup(this);">'+barcode+'</a></td>'+
                          '<td class="leftAlign"><a id="popupid_'+product_id+'" onclick="return productdetailpopup(this);">'+size_name+'</a></td>'+
                          '<td class="leftAlign"><a id="popupid_'+product_id+'" onclick="return productdetailpopup(this);">'+colour_name+'</a></td>'+
                          '<td class="leftAlign"><a id="popupid_'+product_id+'" onclick="return productdetailpopup(this);">'+uqc_name+'</a></td>'+
                          '<td id="roomnoval_'+product_id+'" style="display:none;">'+
                          '<input value="'+barcode+'" type="hidden" id="barcodesel_'+product_id+'" name="barcode_sel[]">'+
                          '<input value="" type="hidden" id="inward_kit_detail_id_'+product_id+'" name="inward_kit_detail_id[]" class="" value="">'+
                          '<input value="" type="hidden" id="inwardids_'+product_id+'" name="inwardids[]" class=""  value="">'+
                          '<input value="" type="hidden" id="inwardqtys_'+product_id+'" name="inwardqtys[]" class=""  value="">'+
                          '<input value="'+combovalue['product_id']+'" type="hidden" id="itemproductid_'+product_id+'" name="itemproductid[]">'+
                          '<input value="'+combovalue['kitproduct_id']+'" type="hidden" id="kitproductid_'+product_id+'" name="kitproductid[]">'+
                          '</td>'+
                          '<td id="showstock_'+product_id+'" class="rightAlign">'+stock+'<input value="'+stock+'" type="hidden" id="stock_'+product_id+'" name="stock[]">'+'</td>'+  
                          '<td id="showkitqty_'+product_id+'" class="rightAlign">'+combovalue['qty']+'<input value="'+combovalue['qty']+'" type="hidden" id="kitqty_'+product_id+'" name="kitqty[]">'+'</td>'+                          
                          '<td id="showtotalqty_'+product_id+'" style="font-weight:bold;" class="rightAlign"><input value="0" type="text" id="totalqty_'+product_id+'" name="totalqty[]" class="form-control mt-15 totqty" style="width:100% !important;" readonly><input value="" type="hidden" id="oldkitqty_'+product_id+'" name="oldkitqty[]"></td>'+
                          '<td onclick="removerow(' + product_id + ');"><i class="fa fa-close"></i></td>' +
                          '</tr>'; 



                   });    

                 $('#product_id').val(kitproductid);    

        }

   

        $("#productsearch").val('');
       
        $(".odd").hide();
        $("#kitSearchResult").prepend(product_html);
        totalcalculation();
        $('.loaderContainer').hide();
              
          var srrno  = 0;
          $('.totqty').each(function(e){
              var ssrno  = 0;
              if($(this).val()!='')
              {
                  srrno++;
              }
          });
        srrno;


      $('.titems').html(srrno);  
      if(product_data["Success"]=="False")
      {
          toastr.error(product_data['Message']);
          $('.loaderContainer').hide();
      }
      
   });
}

function calculateqty()
{

        var inward_qty   = $('#inward_qty').val();

    $("#kitSearchResult").each(function (index,e)
    {
           
             $(this).find('tr').each(function ()
             {
                 if($(this).attr('id') != undefined)
                  {
                      rcolumn = $(this).attr('id').split('product_')[1];
                      
                  }
                
                  if(($("#itemproductid_"+rcolumn).val())!='')
                  {
                  
                     var stock       =   $('#stock_'+rcolumn).val();
                     var kitqty      =   $('#kitqty_'+rcolumn).val();
                     var oldkitqty   =   $('#oldkitqty_'+rcolumn).val();
                     var barcode     =   $('#barcodesel_'+rcolumn).val();
                     var totalstock  =   Number(stock) + Number(oldkitqty);
                     
                      var totalqty   =   Number(kitqty) * Number(inward_qty);
                      if(Number(totalqty)>Number(totalstock))
                      {
                          toastr.error("Stock for Barcode No. "+barcode+" is less than Required qty");
                          $('#totalqty_'+rcolumn).val(totalstock);
                      }
                      else
                      {
                           $('#totalqty_'+rcolumn).val(totalqty);
                      }

                  }

             });

        });

   totalcalculation();
        
  
}

function totalcalculation()
{
    var totqty  = 0;
  

          $('.totqty').each(function (index,e){
            if($(this).val()!="")
            totqty   +=   parseFloat($(this).val());
           
           
          });

          $('#totqtyData').html(Number(totqty));
          
}

$("#saveInwardProducts").click(function (e) {

     $(this).prop('disabled', true);

  if(validate_billing('inward_productskit'))
  {
      $("#saveInwardProducts").prop('disabled', true);



      var array = [];



      $('#kitSearchResult tr').has('td').each(function()
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
                            arrayItem['inward_kit_detail_id'] =$(this).find("#inward_kit_detail_id_"+wihoutidname[1]).val();
                            arrayItem['itemproductid'] =$(this).find("#itemproductid_"+wihoutidname[1]).val();
                            arrayItem['kitproductid'] =$(this).find("#kitproductid_"+wihoutidname[1]).val();
                            arrayItem['inwardids'] =$(this).find("#inwardids_"+wihoutidname[1]).val();
                            arrayItem['inwardqtys'] =$(this).find("#inwardqtys_"+wihoutidname[1]).val();
                            
                        }
                        else if(nameforarray == 'showtotalqty')
                        {
                            arrayItem['totalqty'] =$(this).find("#totalqty_"+wihoutidname[1]).val();
                            arrayItem['oldqty'] =$(this).find("#oldkitqty_"+wihoutidname[1]).val();
                            
                        }
                        


                }

          });
          array.push(arrayItem);
      });

      var arraydetail = [];
      arraydetail.push(array);


      var customerdetail = {};
      var paymentdetail = {};

      customerdetail['product_id'] = $("#product_id").val();
      customerdetail['inward_date'] = $("#inward_date").val();
      customerdetail['inward_qty'] = $("#inward_qty").val();
      customerdetail['oldinward_qty'] = $("#oldinward_qty").val();      
      customerdetail['inward_stock_id'] = $("#inward_stock_id").val();
      customerdetail['inward_product_detail_id'] = $("#inward_product_detail_id").val();
      

      arraydetail.push(customerdetail);

 

       console.log(arraydetail);
        //return false;
   
      var data = arraydetail;

      var  url = "createcombo_inward";
      var type = "POST";
      callroute(url,type,data,function (data)
      {
          $("#saveInwardProducts").prop('disabled', true);
          var dta = JSON.parse(data);

          if(dta['Success'] == "True")
          {
            
               toastr.success(dta['Message']);
               window.location = dta['url'];
              $("#inward_productskit").trigger('reset');
              $("#kitSearchResult").empty('');

              
          }
          else
          {
            $("#saveInwardProducts").prop('disabled', true);
               toastr.error(dta['Message']);
               

          }
      })

  }
   else
    {
        $("#saveInwardProducts").prop('disabled', false);
        return false;
    }
});

function validate_billing(frmid)
{
    var error = 0;    
    
    if($("#totqtyData").html() ==0)
    {
        error = 1;
        toastr.error("Please Take Inward first");
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

function edit_kitinward(inward_stock_id)
{


    var  url = "edit_kitinward";
    var type = "POST";

    var data = {
        'inward_stock_id' : inward_stock_id,
    };

    callroute(url,type,data,function (data) {
        var dta = JSON.parse(data);

        if (dta['Success'] == "True")
        {
            var url = '';
            if(dta['url'] != '' && dta['url'] != 'undefined')
            {
                 url = dta['url'];
            }
           localStorage.setItem('edit_kitinward_record',JSON.stringify(dta['Data']));

            window.location.href = url;



        }
    });
}