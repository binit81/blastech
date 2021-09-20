$("#productsearch").typeahead({
  
    source: function(request, process) {
       jQuery.noConflict();
       $.ajax({
           url: "bsproduct_search",
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
              

                   // if(data.length === 1) {
                   //     $(".dropdown-menu .active").trigger("click");
                   //     $("#productsearch").val('');  
                   //  }
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
        var batch_no = map[item]['batch_no'];
         bsgetproductdetail(barcode,product_name,batch_no);
          $("#productsearch").val('');
            
    }
     
});


function bsgetproductdetail(barcode,product_name,batch_no)
{

  jQuery.noConflict();
   var columnid   =   columnid;
   var type = "POST";
   var url = 'bsproduct_detail';
   var data = {
       "barcode" : barcode,
       "product_name":product_name,
       "batch_no":batch_no
   }
   callroute(url,type,data,function(data)
   {
        
        var product_data = JSON.parse(data,true);


        if(product_data['Success'] == "True")
        {
            
            var product_html = '';
            var product_detail  = product_data['Data'][0];

            var skucode = '';
            var pricehtml = '';
            var pcount    = 0;
            var sellingprice  = 0;
            var stock = 0;
            var gst_per = 0;
            var sr  = 1;

            //console.log(product_detail);
          if(product_detail ==  undefined)
          {
                toastr.error("Stock not avaiable!");
                $('#productsearch').val('');
                $('.loaderContainer').hide();
                return false;

          }
          else
          {
                $('.loaderContainer').hide();
            var product_id = product_detail['price_master_id'];

            if(product_detail['product']['sku_code'] != null || product_detail['product']['sku_code'] != undefined)
            {
                skucode = product_detail['product']['sku_code'];
            }
           
             

            // $.each(product_detail,function (key,value)
            // {
            //     if(pcount == 0)
            //     {
                    if(product_detail['selling_gst_percent'] != null || product_detail['selling_gst_percent'] != undefined)
                    {
                        gst_per        =   product_detail['selling_gst_percent'];
                    }
                    else
                    {
                        gst_per        =   0;
                    }
                    sellingprice   =   product_detail['sell_price'];
                    stock          =   product_detail['product_qty'];
                    
                // }
                pricehtml += '<option value='+product_detail['price_master_id']+'>'+product_detail['offer_price']+'</option>';
                // pcount++;
            // });
            
           
            
            var samerow = 0;
            $("#sproduct_detail_record tr").each(function()
            {
               var row_product_id = $(this).attr('id').split('_')[1];
               if(row_product_id == product_id)
               {
                   var qty = $("#qty_"+product_id).val();
                   var product_qty = ((Number(qty)) + (Number(1)));
                   $("#qty_"+product_id).val(product_qty);
                   samerow = 1;
                    calqty($('#qty_'+product_id));
                   return false;
               }
            });

            if(samerow == 0)
            {
                var srrno  = 0;
                $('.totqty').each(function(e){
                    var ssrno  = 0;
                    if($(this).val()!='')
                    {
                        srrno++;
                    }
                });
                sr  =  sr + srrno;

                if(Number(sr)==1)
                {
                  $('.plural').html('Item');
                }
                else if(Number(sr)>1)
                {
                  $('.plural').html('Items');
                }

                $('.titems').html(sr);

                if (product_html == '') {


                    var showsellingwithoutgst        =     Number(sellingprice).toFixed(2);
                    var qty =  1;
                    var totalsellingwgst             =     Number(sellingprice) * Number(qty);
                    var gst_amount                   =     (Number(sellingprice) * Number(gst_per) / 100).toFixed(4);
                    var mrp                          =     Number(sellingprice) + Number(gst_amount);
                    var totalgst                     =      Number(gst_amount) * Number(qty);
                   
                    var sellingwithgst                =      Number(sellingprice) + Number(totalgst);
                    var tsellingwithgst               =      Number(mrp) * Number(qty);
                    
                   
                    var total_amount                 =     Number(totalsellingwgst) + Number(totalgst);
                    

                     
                     var stotalgst         =     totalgst.toFixed(2);
                     var stotal_amount     =     total_amount.toFixed(2);
                     var total_amount      =     total_amount.toFixed(4);

                   if(product_detail['product']['supplier_barcode']!='' && product_detail['product']['supplier_barcode']!=null)
                    {
                      var barcode     =     product_detail['product']['supplier_barcode'];
                    }
                    else
                    {
                      var barcode     =     product_detail['product']['product_system_barcode'];
                    }

                    if(product_detail['product']['colour_id']!=null)
                      {
                        colour_name   = product_detail['product']['colour']['colour_name'];
                      }
                      else
                      {
                        colour_name   = '';
                      }

                      if(product_detail['product']['size_id']!=null)
                      {
                        size_name   = product_detail['product']['size']['size_name'];
                      }
                      else
                      {
                        size_name   = '';
                      }

                      if(product_detail['product']['uqc']!=null)
                      {
                        uqc_name   = product_detail['product']['uqc']['uqc_name'];
                      }
                      else
                      {
                        uqc_name   = '';
                      }
                    


                    product_html += '<tr id="product_' + product_id + '">' +
                        '<td class="pt-15 pb-15" id="product_name_'+product_id+'" name="product_name[]"><a id="popupid_'+product_detail['product_id']+'" onclick="return productdetailpopup(this);"><span class="informative">'+product_detail['product']['product_name']+'</span></a></td>'+ 
                        '<td class="leftAlign"><a id="popupid_'+product_detail['product_id']+'" onclick="return productdetailpopup(this);">'+barcode+'</a></td>'+
                        '<td class="leftAlign"><a id="popupid_'+product_detail['product_id']+'" onclick="return productdetailpopup(this);">'+colour_name+'</a></td>'+
                        '<td class="leftAlign"><a id="popupid_'+product_detail['product_id']+'" onclick="return productdetailpopup(this);">'+size_name+'</a></td>'+
                        '<td class="leftAlign"><a id="popupid_'+product_detail['product_id']+'" onclick="return productdetailpopup(this);">'+uqc_name+'</a></td>'+
                        '<td id="roomnoval_'+product_id+'" style="display:none;">'+
                        '<input value="'+product_detail['product']['product_system_barcode']+'" type="hidden" id="barcodesel_'+product_id+'" name="barcode_sel[]">'+
                        '<input value="" type="hidden" id="sales_product_id_'+product_id+'" name="sales_product_id[]" class="" >'+
                        '<input value="'+product_detail['product_id']+'" type="hidden" id="productid_'+product_id+'" name="productid[]" class="allbarcode" >'+

                        '</td>'+
                        '<td id="batchno_'+product_id+'" class="center">'+product_detail['batch_no']+'</td>'+
                        '<td id="stock_'+product_id+'" name="stock[]">'+stock+'</td>'+ 
                        '<td id="sellingmrp_'+product_id+'">'+'<select name="mrp[]" id="mrp_'+product_id+'" style="border-radius:0.2rem;border-color:#ced4da;width:100%;" onchange="return filterprice_detail(this);"  tabindex="-1">'+pricehtml+'</select>'+
                        '<input type="hidden" id="oldpricemasterid_'+product_id+'" name="oldpricemasterid[]"  value="" >'+
                        '<input type="hidden" id="inwardids'+product_id+'" name="inwardids[]"  value="" >'+
                        '<input type="hidden" id="inwardqtys'+product_id+'" name="inwardqtys[]"  value="" >'+
                        '</td>'+
                        '<td id="sellingpricewgst_'+product_id+'">'+
                        '<input type="text" id="showsellingwithoutgst_'+product_id+'" class="floating-input tarifform-control number" value="'+showsellingwithoutgst+'" readonly tabindex="-1">'+
                        '<input type="hidden" id="sellingwithoutgst_'+product_id+'" class="floating-input form-control number tsellingwithoutgst" name="tsellingwithoutgst[]"  value="'+sellingprice+'" >'+
                        '</td>'+                  
                        '<td id="sellingqty_'+product_id+'">'+
                        '<input type="text" id="qty_'+product_id+'" class="floating-input tarifform-control nnumber totqty" value="1" name="qty[]" onkeyup="return calqty(this);">'+
                        '<input type="hidden" id="oldqty_'+product_id+'" class="floating-input tarifform-control number" value="0" name="oldqty[]">'+
                        '</td>'+       
                        '<td id="sellingdiscountper_'+product_id+'">'+'<input type="text" id="proddiscper_'+product_id+'" class="floating-input tarifform-control number" value="0" name="proddiscper[]" onkeyup="return caldiscountper(this);" >'+
                        '<input type="text" id="overalldiscper_'+product_id+'" class="floating-input tarifform-control number" value="0" name="proddiscper[]" style="display:none;">'+'</td>'+
                        '<td id="sellingdiscountamt_'+product_id+'">'+'<input type="text" id="mrpproddiscamt_'+product_id+'" class="floating-input tarifform-control number mrppproddiscamt" value="0" onchange="return mrpcaldiscountamt(this);">'+'<input type="text" id="proddiscamt_'+product_id+'" class="floating-input tarifform-control number pproddiscamt" value="0" name="proddiscamt[]" onkeyup="return caldiscountamt(this);" readonly tabindex="-1" style="display:none;">'+
                        '<input type="text" id="overalldiscamt_'+product_id+'" class="floating-input tarifform-control number overallpproddiscamt" value="0" name="proddiscamt[]" style="display:none;">'+'<input type="text" id="overallmrpdiscamt_'+product_id+'" class="floating-input tarifform-control number" value="0" name="overallmrpdiscamt[]" style="display:none;">'+'</td>'+

                        '<td style="display:none;" id="totalsellingwgst_'+product_id+'" class="totalsellingwgst" name="totalsellingwgst[]">'+totalsellingwgst+'</td>'+
                        '<td style="display:none;" id="totalsellinggst_'+product_id+'" class="totalsellinggst">'+tsellingwithgst+'</td>'+
                        '<td id="sprodgstper_'+product_id+'" style="text-align:right !important; display:none;" class="sprodgstper">'+gst_per+'</td>'+
                        '<td id="sprodgstamt_'+product_id+'" style="text-align:right !important; display:none;">'+stotalgst+'</td>'+
                        '<td id="prodgstper_'+product_id+'" style="display:none;" name="prodgstper[]">'+gst_per+'</td>'+
                        '<td id="prodgstamt_'+product_id+'" style="display:none;" class="totalgstamt" name="prodgstamt[]">'+totalgst+'</td>'+

                        '<td id="totalamount_'+product_id+'" style="font-weight:bold;display:none;" class="tsellingaftergst" name="totalamount[]">'+total_amount+'</td>'+
                        '<td id="stotalamount_'+product_id+'" style="font-weight:bold;text-align:right !important;">'+stotal_amount+'</td>'+
                        '<td onclick="removerow(' + product_detail['price_master_id'] + ');"><i class="fa fa-close"></i></td>' +
                        '</tr>'; 



                } else {


                    var showsellingwithoutgst        =    sellingprice.toFixed(2);
                    var qty =  1;
                    var totalsellingwgst             =     Number(sellingprice) * Number(qty);
                    var gst_amount                   =     (Number(sellingprice) * Number(gst_per) / 100).toFixed(4);
                    var mrp                          =     Number(sellingprice) + Number(gst_amount);
                    var totalgst                     =      Number(gst_amount) * Number(qty);
                   
                    var sellingwithgst                =      Number(sellingprice) + Number(totalgst);
                    var tsellingwithgst               =      Number(mrp) * Number(qty);
                    
                   
                    var total_amount                 =     Number(totalsellingwgst) + Number(totalgst);
                    

                     
                     var stotalgst         =     totalgst.toFixed(2);
                     var stotal_amount     =     total_amount.toFixed(2);
                     var total_amount      =     total_amount.toFixed(4);

                 


                    product_html += product_html + '<tr id="product_' + product_id + '">' +
                        '<td class="pt-15 pb-15" id="product_name_'+product_id+'" name="product_name[]"><a id="popupid_'+product_detail['product_id']+'" onclick="return productdetailpopup(this);"><span class="informative">'+product_detail['product']['product_name']+'</span></a></td>'+ 
                        '<td class="leftAlign"><a id="popupid_'+product_detail['product_id']+'" onclick="return productdetailpopup(this);">'+barcode+'</a></td>'+
                        '<td class="leftAlign"><a id="popupid_'+product_detail['product_id']+'" onclick="return productdetailpopup(this);">'+colour_name+'</a></td>'+
                        '<td class="leftAlign"><a id="popupid_'+product_detail['product_id']+'" onclick="return productdetailpopup(this);">'+size_name+'</a></td>'+
                        '<td class="leftAlign"><a id="popupid_'+product_detail['product_id']+'" onclick="return productdetailpopup(this);">'+uqc_name+'</a></td>'+
                        '<td id="roomnoval_'+product_id+'" style="display:none;">'+
                        '<input value="'+product_detail['product']['product_system_barcode']+'" type="hidden" id="barcodesel_'+product_id+'" name="barcode_sel[]">'+
                        '<input value="" type="hidden" id="sales_product_id_'+product_id+'" name="sales_product_id[]" class="" >'+
                        '<input value="'+product_detail['product_id']+'" type="hidden" id="productid_'+product_id+'" name="productid[]" class="allbarcode" >'+

                        '</td>'+
                        '<td id="batchno_'+product_id+'" class="center">'+product_detail['batch_no']+'</td>'+
                        '<td id="stock_'+product_id+'" name="stock[]">'+stock+'</td>'+ 
                        '<td id="sellingmrp_'+product_id+'">'+'<select name="mrp[]" id="mrp_'+product_id+'" style="border-radius:0.2rem;border-color:#ced4da;width:100%;" onchange="return filterprice_detail(this);"  tabindex="-1">'+pricehtml+'</select>'+
                        '<input type="hidden" id="oldpricemasterid_'+product_id+'" name="oldpricemasterid[]"  value="" >'+
                        '<input type="hidden" id="inwardids'+product_id+'" name="inwardids[]"  value="" >'+
                        '<input type="hidden" id="inwardqtys'+product_id+'" name="inwardqtys[]"  value="" >'+
                        '</td>'+
                        '<td id="sellingpricewgst_'+product_id+'">'+
                        '<input type="text" id="showsellingwithoutgst_'+product_id+'" class="floating-input tarifform-control number" value="'+showsellingwithoutgst+'" readonly tabindex="-1">'+
                        '<input type="hidden" id="sellingwithoutgst_'+product_id+'" class="floating-input form-control number tsellingwithoutgst" name="tsellingwithoutgst[]"  value="'+sellingprice+'" >'+
                        '</td>'+                  
                        '<td id="sellingqty_'+product_id+'">'+
                        '<input type="text" id="qty_'+product_id+'" class="floating-input tarifform-control nnumber totqty" value="1" name="qty[]" onkeyup="return calqty(this);">'+
                        '<input type="hidden" id="oldqty_'+product_id+'" class="floating-input tarifform-control number" value="0" name="oldqty[]">'+
                        '</td>'+       
                        '<td id="sellingdiscountper_'+product_id+'">'+'<input type="text" id="proddiscper_'+product_id+'" class="floating-input tarifform-control number" value="0" name="proddiscper[]" onkeyup="return caldiscountper(this);" >'+
                        '<input type="text" id="overalldiscper_'+product_id+'" class="floating-input tarifform-control number" value="0" name="proddiscper[]" style="display:none;">'+'</td>'+
                        '<td id="sellingdiscountamt_'+product_id+'">'+'<input type="text" id="mrpproddiscamt_'+product_id+'" class="floating-input tarifform-control number mrppproddiscamt" value="0" onchange="return mrpcaldiscountamt(this);">'+'<input type="text" id="proddiscamt_'+product_id+'" class="floating-input tarifform-control number pproddiscamt" value="0" name="proddiscamt[]" onkeyup="return caldiscountamt(this);" readonly tabindex="-1" style="display:none;">'+
                        '<input type="text" id="overalldiscamt_'+product_id+'" class="floating-input tarifform-control number overallpproddiscamt" value="0" name="proddiscamt[]" style="display:none;">'+'<input type="text" id="overallmrpdiscamt_'+product_id+'" class="floating-input tarifform-control number" value="0" name="overallmrpdiscamt[]" style="display:none;">'+'</td>'+

                        '<td style="display:none;" id="totalsellingwgst_'+product_id+'" class="totalsellingwgst" name="totalsellingwgst[]">'+totalsellingwgst+'</td>'+
                        '<td style="display:none;" id="totalsellinggst_'+product_id+'" class="totalsellinggst">'+tsellingwithgst+'</td>'+
                        '<td id="sprodgstper_'+product_id+'" style="text-align:right !important; display:none;" class="sprodgstper">'+gst_per+'</td>'+
                        '<td id="sprodgstamt_'+product_id+'" style="text-align:right !important; display:none;">'+stotalgst+'</td>'+
                        '<td id="prodgstper_'+product_id+'" style="display:none;" name="prodgstper[]">'+gst_per+'</td>'+
                        '<td id="prodgstamt_'+product_id+'" style="display:none;" class="totalgstamt" name="prodgstamt[]">'+totalgst+'</td>'+

                        '<td id="totalamount_'+product_id+'" style="font-weight:bold;display:none;" class="tsellingaftergst" name="totalamount[]">'+total_amount+'</td>'+
                        '<td id="stotalamount_'+product_id+'" style="font-weight:bold;text-align:right !important;">'+stotal_amount+'</td>'+
                        '<td onclick="removerow(' + product_detail['price_master_id'] + ');"><i class="fa fa-close"></i></td>' +
                        '</tr>';
                }
            }
        }
    }

        $("#productsearch").val('');
        $(".odd").hide();
        $("#sproduct_detail_record").prepend(product_html);
        totalcalculation();

        var charges_total  =  $('#scharges_total').val();
        if(Number(charges_total)!='' && Number(charges_total)!=0)
        {
           calculatetotalcharges();
        }
      
   });
}
function removerow(productid)
{
    $("#product_"+productid).remove();
    totalcalculation();
    var srrno  = 0;
    $('.totqty').each(function(e){
        var ssrno  = 0;
        if($(this).val()!='')
        {
            srrno++;
        }
    });

    if(Number(srrno)==1)
    {
      $('.plural').html('Item');
    }
    else if(Number(srrno)>1)
    {
      $('.plural').html('Items');
    }
    
    $('.titems').html(srrno);
}
function editremoverow(productid)
{
    $("#showsellingwithoutgst_"+productid).val(0);
    $("#sellingwithoutgst_"+productid).val(0);
    $("#qty_"+productid).val(0);
    $("#overalldiscper_"+productid).val(0);
    $("#overalldiscamt_"+productid).val(0);
    calqty($('#qty_'+productid));
}
function filterprice_detail(obj)
{

    var id                        =     $(obj).attr('id');
    var product_id                =     $(obj).attr('id').split('mrp_')[1];
    var priceid                   =     $("#mrp_"+product_id+" :selected").val();

    var url     =   "search_pricedetail";
    var type    =   "POST";
    var data    =   {
        "price_id":priceid
    };

    callroute(url,type,data,function (data)
    {
        var price_data = JSON.parse(data,true);
        
        if(price_data['Success'] == "True")
        {
            var price_detail  = price_data['Data'][0];
            if(price_data['Data'].length > 0)
            {

                var stock = 0;

                if(price_detail['selling_gst_percent'] != null || price_detail['selling_gst_percent'] != undefined)
                    {
                        gst_per        =   price_detail['selling_gst_percent'];
                    }
                    else
                    {
                        gst_per        =   0;
                    }




                $("#showsellingwithoutgst_"+product_id).val(price_detail['sell_price'].toFixed(2));
                $("#sellingwithoutgst_"+product_id).val(price_detail['sell_price']);
                
                $("#sprodgstper_"+product_id).html(gst_per);
                $("#prodgstper_"+product_id).html(gst_per);

                var sellingprice                =   price_detail['sell_price'];
                var gst_per                     =   price_detail['selling_gst_percent'];



                    var oldprice_master_id    =   $("#oldpricemasterid_"+product_id).val();
                    var oldqty                =   $("#oldqty_"+product_id).val();
                    if(oldprice_master_id!= '')
                    {

                     if(price_detail['price_master_id']!= oldprice_master_id)
                        {
                                stock   =   price_detail['product_qty'] - oldqty;
                                $("#stock_"+product_id).html(stock);
                                var oldqty    =    $("#oldqty_"+product_id).val();
                                $("#qty_"+product_id).val(oldqty);
                                calqty($('#qty_'+product_id));
                        }
                        else
                        {
                               stock  =  price_detail['product_qty'];
                               $("#stock_"+product_id).html(stock);
                               var oldqty    =    $("#oldqty_"+product_id).val();
                               $("#qty_"+product_id).val(oldqty);
                               calqty($('#qty_'+product_id));
                        }

                    }
                    else
                    {
                       
                        $("#stock_"+product_id).html(price_detail['product_qty']);
                        calqty($('#qty_'+product_id));
                    }
                    


                /*var qty                          =    $("#qty_"+product_id).val();

                var totalsellingwgst             =     Number(sellingprice) * Number(qty);
                var gst_amount                   =     (Number(sellingprice) * Number(gst_per) / 100).toFixed(4);
               
                var mrp                          =     Number(sellingprice) + Number(gst_amount);
                var totalgst                     =      Number(gst_amount) * Number(qty);
               
                var sellingwithgst                =      Number(sellingprice) + Number(totalgst);
                var tsellingwithgst               =      Number(mrp) * Number(qty);
                
               
                var total_amount                 =     Number(totalsellingwgst) + Number(totalgst);
                

                 
                 $("#totalsellingwgst_"+product_id).html(totalsellingwgst.toFixed(4));
                 
                 $("#totalsellinggst_"+product_id).html(tsellingwithgst.toFixed(4));
                
                 $("#prodgstamt_"+product_id).html(totalgst.toFixed(4));
                 $("#totalamount_"+product_id).html(total_amount.toFixed(4));
                
                 $("#sprodgstamt_"+product_id).html(totalgst.toFixed(2));
                 $("#stotalamount_"+product_id).html(total_amount.toFixed(2));
                  totalcalculation();  */ 
                   
            }
            else {
                     

                   
                }
        }

          
        });
}
function caldiscountper(obj)
{
   if(obj.value != '' && obj.value != undefined)
    {
        obj.value = obj.value.replace(/[^\d.]/g, '');
    }
    var id                        =     $(obj).attr('id');
    var product_id                =     $(obj).attr('id').split('proddiscper_')[1];
    var discount_percent          =     $('#proddiscper_'+product_id).val();
    var sellingprice              =     $("#sellingwithoutgst_"+product_id).val();
    var qty                       =     $("#qty_"+product_id).val();
    var gst_per                   =     $("#sprodgstper_"+product_id).html();
    var mrp                       =     $("#mrp_"+product_id+" :selected").html();
    console.log(Number(discount_percent));

          
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
       
         
         $("#totalsellingwgst_"+product_id).html(discountedamt.toFixed(4));
         
         $("#totalsellinggst_"+product_id).html(sellingwithgst.toFixed(4));
         $("#mrpproddiscamt_"+product_id).val(totalmrpdiscount.toFixed(4));
         $("#proddiscamt_"+product_id).val(totaldiscount.toFixed(4));
         $("#prodgstamt_"+product_id).html(totalgst.toFixed(4));
         $("#totalamount_"+product_id).html(total_amount.toFixed(4));

         $("#sprodgstamt_"+product_id).html(totalgst.toFixed(2));
         $("#stotalamount_"+product_id).html(total_amount.toFixed(2));

           var discount_percent        =     $("#discount_percent").val(); 
             if(Number(discount_percent)==0 && discount_percent == '')
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
function mrpcaldiscountamt(obj)
{
    var id                        =     $(obj).attr('id');
    var product_id                =     $(obj).attr('id').split('mrpproddiscamt_')[1];
    var mrpdiscount_amount        =     $('#mrpproddiscamt_'+product_id).val();
    var sellingprice              =     $("#sellingwithoutgst_"+product_id).val();
    var qty                       =     $("#qty_"+product_id).val();
    var mrp                       =     $("#mrp_"+product_id+" :selected").html();

    var discount_percent          =     (Number(mrpdiscount_amount) * 100) / (Number(mrp)* Number(qty));
    $('#proddiscper_'+product_id).val(Number(discount_percent).toFixed(4));

    caldiscountper($('#proddiscper_'+product_id));

          
     
     
}

function calqty(obj)
{
    if(obj.value != '' && obj.value != undefined)
    {
        obj.value = obj.value.replace(/[^\d.]/g, '');

    }
    var id                        =     $(obj).attr('id');
    var product_id                =     $(obj).attr('id').split('qty_')[1];
    var qty                       =     $('#qty_'+product_id).val();
    var oldqty                    =     $('#oldqty_'+product_id).val();
    var stock                     =     $('#stock_'+product_id).html();


    var totalstock   =   Number(stock) + Number(oldqty);

    if(Number(qty)>Number(totalstock))
    {
        toastr.error("Entered Qty is greater than Stock");
        $('#qty_'+product_id).val(totalstock);
        var sellingprice              =     $("#sellingwithoutgst_"+product_id).val();
        
        var gst_per                   =     $("#sprodgstper_"+product_id).html();
        var discount_percent          =     $("#proddiscper_"+product_id).val();
        var mrp                       =     $("#mrp_"+product_id+" :selected").html();

          
        var totalmrpdiscount             =     (Number(mrp) * Number(totalstock)) * Number(discount_percent) / 100;
        var totalsellingwgst             =     Number(sellingprice) * Number(totalstock);
        var sellingdiscount              =     (Number(sellingprice) * Number(discount_percent) / 100).toFixed(4);
        var gst_amount                   =     (Number(sellingprice-sellingdiscount) * Number(gst_per) / 100).toFixed(4);

        var totaldiscount                =      Number(sellingdiscount) * Number(totalstock);

        var discountedamt                =      Number(totalsellingwgst) - Number(totaldiscount);

        var mrp                          =     Number(totaldiscount) + Number(gst_amount);
        
        var totalgst                     =      Number(gst_amount) * Number(totalstock);


        var sellingwithgst                =      Number(discountedamt) + Number(totalgst);


        var total_amount                 =     Number(discountedamt) + Number(totalgst);
       
         
         $("#totalsellingwgst_"+product_id).html(discountedamt.toFixed(4));
         
         $("#totalsellinggst_"+product_id).html(sellingwithgst.toFixed(4));
         $("#proddiscamt_"+product_id).val(totaldiscount.toFixed(4));
         $("#mrpproddiscamt_"+product_id).val(totalmrpdiscount.toFixed(4));
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
        var mrp                       =     $("#mrp_"+product_id+" :selected").html();

          
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
       
        
         $("#totalsellingwgst_"+product_id).html(discountedamt.toFixed(4));
         
         $("#totalsellinggst_"+product_id).html(sellingwithgst.toFixed(4));
         $("#proddiscamt_"+product_id).val(totaldiscount.toFixed(4));
         $("#mrpproddiscamt_"+product_id).val(totalmrpdiscount.toFixed(4));
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
                  

                  if(($("#barcodesel_"+rcolumn).val())!='')
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

                if(($("#barcodesel_"+rcolumn).val())!='')
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





