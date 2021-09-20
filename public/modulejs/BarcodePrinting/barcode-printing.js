function templateDesigner()
{
    $("#templateDesigner").modal('show');
}

function CreateTemplateDesigner()
{
    $("#templateDesigner").modal('hide');
    $("#CreateTemplateDesigner").modal('show');
}

$("#productName").keyup(function ()
{
    
    jQuery.noConflict();
    if($("#productName").val().length >= 1) {

        $("#productName").autocomplete({
            autoFocus: true,
            minLength: 1,
            source: function (request, response) {
                var url = "bar_product_search";
                var type = "POST";
                var data = {
                    'search_val': $("#productName").val()
                };
                callroute(url, type, data, function (data) {


                    var searchdata = JSON.parse(data, true);
                    var html = '';
                    if (searchdata['Success'] == "True") {

                        var result = [];
                        searchdata['Data'].forEach(function (value) {
                             result.push({label:value.product_name, value:value.product_name,id:value.product_id });
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
            $("#productName").empty();
    }

});

$("#fBarcode").keyup(function ()
{
    
    jQuery.noConflict();
    if($("#fBarcode").val().length >= 1) {

        $("#fBarcode").autocomplete({
            autoFocus: true,
            minLength: 1,
            source: function (request, response) {
                var url = "barcode_search";
                var type = "POST";
                var data = {
                    'search_val': $("#fBarcode").val()
                };
                callroute(url, type, data, function (data) {


                    var searchdata = JSON.parse(data, true);
                    var html = '';
                    if (searchdata['Success'] == "True") {

                        var result = [];
                        searchdata['Data'].forEach(function (value) {
                              result.push({label:value.product_system_barcode, value:value.product_system_barcode,id:value.product_id });
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
            $("#fBarcode").empty();
    }

});

$("#tBarcode").keyup(function ()
{
    
    jQuery.noConflict();
    if($("#tBarcode").val().length >= 1) {

        $("#tBarcode").autocomplete({
            autoFocus: true,
            minLength: 1,
            source: function (request, response) {
                var url = "barcode_search";
                var type = "POST";
                var data = {
                    'search_val': $("#tBarcode").val()
                };
                callroute(url, type, data, function (data) {


                    var searchdata = JSON.parse(data, true);
                    var html = '';
                    if (searchdata['Success'] == "True") {

                        var result = [];
                        searchdata['Data'].forEach(function (value) {
                              result.push({label:value.product_system_barcode, value:value.product_system_barcode });
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
            $("#tBarcode").empty();
    }

});

$("#supplier_barcode").keyup(function ()
{
    
    jQuery.noConflict();
    if($("#supplier_barcode").val().length >= 1) {

        $("#supplier_barcode").autocomplete({
            autoFocus: true,
            minLength: 1,
            source: function (request, response) {
                var url = "supplier_barcode_search";
                var type = "POST";
                var data = {
                    'search_val': $("#supplier_barcode").val()
                };
                callroute(url, type, data, function (data) {


                    var searchdata = JSON.parse(data, true);
                    var html = '';
                    if (searchdata['Success'] == "True") {

                        var result = [];
                        searchdata['Data'].forEach(function (value) {
                              result.push({label:value.supplier_barcode, value:value.supplier_barcode });
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
            $("#supplier_barcode").empty();
    }

});

$("#category").keyup(function ()
{
    
    jQuery.noConflict();
    if($("#category").val().length >= 1) {

        $("#category").autocomplete({
            autoFocus: true,
            minLength: 1,
            source: function (request, response) {
                var url = "category_search";
                var type = "POST";
                var data = {
                    'search_val': $("#category").val()
                };
                callroute(url, type, data, function (data) {


                    var searchdata = JSON.parse(data, true);
                    var html = '';
                    if (searchdata['Success'] == "True") {

                        var result = [];
                        searchdata['Data'].forEach(function (value) {
                              result.push({label:value.category_name, value:value.category_name });
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
            $("#category").empty();
    }

});

$("#brandname").keyup(function ()
{
    
    jQuery.noConflict();
    if($("#brandname").val().length >= 1) {

        $("#brandname").autocomplete({
            autoFocus: true,
            minLength: 1,
            source: function (request, response) {
                var url = "brand_search";
                var type = "POST";
                var data = {
                    'search_val': $("#brandname").val()
                };
                callroute(url, type, data, function (data) {


                    var searchdata = JSON.parse(data, true);
                    var html = '';
                    if (searchdata['Success'] == "True") {

                        var result = [];
                        searchdata['Data'].forEach(function (value) {
                              result.push({label:value.brand_type, value:value.brand_type });
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
            $("#brandname").empty();
    }

});

$("#sizename").keyup(function ()
{
    
    jQuery.noConflict();
    if($("#sizename").val().length >= 1) {

        $("#sizename").autocomplete({
            autoFocus: true,
            minLength: 1,
            source: function (request, response) {
                var url = "size_search";
                var type = "POST";
                var data = {
                    'search_val': $("#sizename").val()
                };
                callroute(url, type, data, function (data) {


                    var searchdata = JSON.parse(data, true);
                    var html = '';
                    if (searchdata['Success'] == "True") {

                        var result = [];
                        searchdata['Data'].forEach(function (value) {
                              result.push({label:value.size_name, value:value.size_name });
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
            $("#sizename").empty();
    }

});

$("#colourname").keyup(function ()
{
    
    jQuery.noConflict();
    if($("#colourname").val().length >= 1) {

        $("#colourname").autocomplete({
            autoFocus: true,
            minLength: 1,
            source: function (request, response) {
                var url = "colour_search";
                var type = "POST";
                var data = {
                    'search_val': $("#colourname").val()
                };
                callroute(url, type, data, function (data) {


                    var searchdata = JSON.parse(data, true);
                    var html = '';
                    if (searchdata['Success'] == "True") {

                        var result = [];
                        searchdata['Data'].forEach(function (value) {
                              result.push({label:value.colour_name, value:value.colour_name });
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
            $("#colourname").empty();
    }

});

$("#skucode").keyup(function ()
{
    
    jQuery.noConflict();
    if($("#skucode").val().length >= 1) {

        $("#skucode").autocomplete({
            autoFocus: true,
            minLength: 1,
            source: function (request, response) {
                var url = "sku_search";
                var type = "POST";
                var data = {
                    'search_val': $("#skucode").val()
                };
                callroute(url, type, data, function (data) {


                    var searchdata = JSON.parse(data, true);
                    var html = '';
                    if (searchdata['Success'] == "True") {

                        var result = [];
                        searchdata['Data'].forEach(function (value) {
                              result.push({label:value.sku_code, value:value.sku_code });
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
            $("#skucode").empty();
    }

});

$("#productCode").keyup(function ()
{
    
    jQuery.noConflict();
    if($("#productCode").val().length >= 1) {

        $("#productCode").autocomplete({
            autoFocus: true,
            minLength: 1,
            source: function (request, response) {
                var url = "product_code";
                var type = "POST";
                var data = {
                    'search_val': $("#productCode").val()
                };
                callroute(url, type, data, function (data) {


                    var searchdata = JSON.parse(data, true);
                    var html = '';
                    if (searchdata['Success'] == "True") {

                        var result = [];
                        searchdata['Data'].forEach(function (value) {
                              result.push({label:value.product_code, value:value.product_code });
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
            $("#productCode").empty();
    }

});

$("#invoiceNo").keyup(function ()
{
    
    jQuery.noConflict();
    if($("#invoiceNo").val().length >= 1) {

        $("#invoiceNo").autocomplete({
            autoFocus: true,
            minLength: 1,
            source: function (request, response) {
                var url = "invoice_no";
                var type = "POST";
                var data = {
                    'search_val': $("#invoiceNo").val()
                };
                callroute(url, type, data, function (data) {


                    var searchdata = JSON.parse(data, true);
                    var html = '';
                    if (searchdata['Success'] == "True") {

                        var result = [];
                        searchdata['Data'].forEach(function (value) {
                              result.push({label:value.invoice_no, value:value.invoice_no });
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
            $("#invoiceNo").empty();
    }
});

$("#SearchBtn").click(function (){
	var from_date		       =	$('#from_date').val();
	var to_date			       =	$('#to_date').val();
	var productName            =	$('#productName').val();
	var fBarcode               =	$('#fBarcode').val();
	var tBarcode		       =	$('#tBarcode').val();
	var productCode		       =	$('#productCode').val();
	var invoiceNo		       =	$('#invoiceNo').val();

    var supplier_barcode       =    $('#supplier_barcode').val();
    var category               =    $('#category').val();
    var subcategory            =    $('#subcategory').val();
    var brandname              =    $('#brandname').val();
    var sizename               =    $('#sizename').val();
    var colourname             =    $('#colourname').val();
    var skucode                =    $('#skucode').val();


	var fetch_data_url	=	'searchBarcodePrintProduct';

    $('.loaderContainer').show();
	
	productfetch_data(fetch_data_url,from_date,to_date,productName,fBarcode,tBarcode,productCode,invoiceNo,supplier_barcode,category,subcategory,brandname,sizename,colourname,skucode);
	
});

function productfetch_data(fetch_data_url,from_date,to_date,productName,fBarcode,tBarcode,productCode,invoiceNo,supplier_barcode,category,subcategory,brandname,sizename,colourname,skucode)
{
    $.ajax({
        url:fetch_data_url,

        data: {
            from_date:from_date,
            to_date:to_date,
            productName:productName,
            fBarcode:fBarcode,
            tBarcode:tBarcode,
            productCode:productCode,
            invoiceNo:invoiceNo,
            supplier_barcode:supplier_barcode,
            category:category,
            subcategory:subcategory,
            brandname:brandname,
            sizename:sizename,
            colourname:colourname,
            skucode:skucode
        },
        success:function(data)
        {
            // console.log(data);
            $('.loaderContainer').hide();
            // $('html, body').animate({ scrollTop: $("#barcodeTotalQty_text").offset().top }, 2000);
            $('tbody#searchResult').html('');
            $('tbody#searchResult').html(data);

            $('.PrintBarcodes').show();

        }
    })
}

function RemovePrintTbl(obj)
{
    var removeId    =   $(obj).attr('id');
    var id          =   removeId.split('_')[1];
    $('#printTbl_'+id).remove();
    getTotalPrintQty();
}

$("#printBarcodeBtn").click(function (){
    var radioValue = $("input[name='labelType']:checked").val();

    var array = [];
    if(radioValue!='')
    {
    $('#searchResult tr').has('td').each(function()
      {
          var arrayItem = {};
          $('td', $(this)).each(function(index, item)
          {
              var inputname = $(item).attr('id');

                if(inputname != undefined && inputname != '')
                {
                    var wihoutidname = inputname.split('_');
                    var nameforarray = wihoutidname[0];

                         if(nameforarray == 'fetchval')
                        {
                            arrayItem['product_id']     =   $(this).find("#productid_"+wihoutidname[1]).val();
                            arrayItem['printqty']       =   $(this).find("#printStock_"+wihoutidname[1]).val();
                        }
                        

                }

        });
        array.push(arrayItem);
    });

        var arraydetail = [];
        arraydetail.push(array);

        var rradiovalue   =  [];

        rradiovalue['radioValue']     =   radioValue;
        alert(rradiovalue['radioValue']);

        arraydetail.push(rradiovalue);

        console.log(arraydetail);
       // return false;

        localStorage.setItem('barcode-printing-record',JSON.stringify(arraydetail));
        var url  =   'barcode-sticker';
        window.location.href = url;

    }
});

$("input[name='MasterType']").click(function (e){
    
    var url = "fetchBarcodeLabels";
    var type = "POST";
    var data = {
        'MasterType': $("input[name='MasterType']:checked").val()
    };
    callroute(url, type, data, function (data) {


        var searchdata = JSON.parse(data, true);
        var html = '';
        var dataNew =   '';
        if (searchdata['Success'] == "True") {

            var barcodedata  =   searchdata['Data'];
            
            $.each(barcodedata,function (key,value)
            {
                dataNew +=   '<div class="col-md-4 form-group"><div class="custom-control custom-radio mb-5"><input id="slabel_' + value['id'] + '" name="labelType" class="custom-control-input" value="' + value['id'] + '" type="radio"><label class="custom-control-label" for="slabel_' + value['id'] + '" style="text-align:left;line-height:1 !important;">' + value['label_name'] + '<br><small>' + value['label_tagline'] + '</small></label></div></div>';

            });

            $('.searchBarcodeData').html(dataNew);
            $('.searchBarcodeData').append('<div class="col-auto"><button type="button" id="printBarcodeBtn" name="printBarcodeBtn" class="btn btn-primary ml-0"><i class="fa fa-print"></i>Print Barcodes</button></div>');

        }
    });
         
});

$('#PopupBarcodeType').change( function(e){

    var PopupBarcodeType    =   $('#PopupBarcodeType :selected').val();

    var url = "fetchBarcodeLabels";
    var type = "POST";
    var data = {
        'MasterType': PopupBarcodeType
    };
    callroute(url, type, data, function (data) {


        var searchdata = JSON.parse(data, true);
        var html = '';
        var dataNew =   '';
        $('.GetDropBarcodeSheets').html('');
        if (searchdata['Success'] == "True") {

            var barcodetype  =   searchdata['Data'];

            dataNew     =   '<select name="PopupBarcodeSheets" id="PopupBarcodeSheets" class="form-control form-inputtext"><option value="">Barcode Sheet</option>';
            
            $.each(barcodetype,function (key,value)
            {
                dataNew +=   '<option value="'+ value['id'] +'">'+ value['label_name'] +'</option>';
            });

            dataNew     +=  '</select>';

            
            $('.GetDropBarcodeSheets').html(dataNew);

        }
    });

});


$('#SaveBarcodeTemplateBtn').click( function(e){

    var arrayValue          =   [];
    var template_values     =   {};

    template_values['barcode_template_id']  =   '';
    template_values['PrintBarcodeSheets']   =   $('#PrintBarcodeSheets').val();
    template_values['PrintBarcodeType']     =   $('#PrintBarcodeType').val();
    template_values['template_name']        =   $('#template_name').val();
    template_values['template_data']        =   CKEDITOR.instances.template_data.getData();
    template_values['label_width']          =   $('#label_width').val();
    template_values['label_height']         =   $('#label_height').val();
    template_values['label_size_type']      =   $('#label_size_type').val();
    template_values['label_font_size']      =   $('#label_font_size').val();
    template_values['label_margin_top']     =   $('#label_margin_top').val();
    template_values['label_margin_right']   =   $('#label_margin_right').val();
    template_values['label_margin_bottom']  =   $('#label_margin_bottom').val();
    template_values['label_margin_left']    =   $('#label_margin_left').val();

    if(template_values['PrintBarcodeSheets']=='')
    {
        toastr.error('please select sheet');
    }
    else if(template_values['PrintBarcodeType']=='')
    {
        toastr.error('please select barcode type');
    }
    else if(template_values['template_name']=='')
    {
        toastr.error('please enter template name');
        $('#template_name').focus();
    }
    else if(template_values['template_data']=='')
    {
        toastr.error('please enter template data');
    }
    else if(template_values['label_size_type']=='')
    {
        toastr.error('please select label size type');
    }
    else if(template_values['label_width']=='')
    {
        toastr.error('please enter label width');
        $('#label_width').focus();
    }
    else if(template_values['label_height']=='')
    {
        toastr.error('please enter label height');
        $('#label_height').focus();
    }
    else if(template_values['label_font_size']=='')
    {
        toastr.error('please enter label font size');
        $('#label_font_size').focus();
    }
    else if(template_values['label_margin_top']=='')
    {
        toastr.error('please enter label top margin, if not then enter 0');
        $('#label_margin_top').focus();
    }
    else if(template_values['label_margin_right']=='')
    {
        toastr.error('please enter label right margin, if not then enter 0');
        $('#label_margin_right').focus();
    }
    else if(template_values['label_margin_bottom']=='')
    {
        toastr.error('please enter label bottom margin, if not then enter 0');
        $('#label_margin_bottom').focus();
    }
    else if(template_values['label_margin_left']=='')
    {
        toastr.error('please enter label left margin, if not then enter 0');
        $('#label_margin_left').focus();
    }
    else
    {

        arrayValue.push(template_values);

        var data = arrayValue;

        // console.log(data);

        var  url = "template_save";
        var type = "POST";
        callroute(url,type,data,function (data)
        {
            var dta = JSON.parse(data); 
            toastr.success('template generated successfully...');
            window.location = dta['url'];
        });

        
    }

});

$('#saveBarcodeTemplateToUser').click( function(e)
{
    var barcode_template_id     =   $("input[name='barcode_template_id']:checked").val();

    var url = "saveBarcodeTemplateToUser";
    var type = "POST";
    var data = {
        'barcode_template_id':  $("input[name='barcode_template_id']:checked").val()
    };
    callroute(url, type, data, function (data)
    {
        var pushdata = JSON.parse(data, true);
        toastr.success('template selected successfully...');
        window.location = pushdata['url'];
    });
});

$('.PrintBarcodes').click( function(e){

   
    var BarcodeTemplate     =   $("input[name='labelTypeName']:checked").val();
    var labelType           =   $("input[name='barcode_sheet_id_"+BarcodeTemplate+"']").val();

    if (!$("input[name='labelTypeName']:checked").val())
    {
        toastr.error('please select print label type');
    }
    else
    {

        var array = [];
        
        $('#searchResult tr').has('td').each(function()
        {
              var arrayItem = {};
              $('td', $(this)).each(function(index, item)
              {
                    var inputname = $(item).attr('id');

                    if(inputname != undefined && inputname != '')
                    {
                        var wihoutidname = inputname.split('_');
                        var nameforarray = wihoutidname[0];

                        if(nameforarray == 'fetchval')
                        {
                            arrayItem['product_id']     =   $(this).find("#productid_"+wihoutidname[1]).val();
                            arrayItem['inward_id']     =   $(this).find("#inwardid_"+wihoutidname[1]).val();
                            arrayItem['printqty']       =   $(this).find("#printStock_"+wihoutidname[1]).val();
                        }
                    }

            });
            array.push(arrayItem);
        });

        var arraydetail = [];
        arraydetail.push(array);
        
        var rradiovalue   =  {};
        var BarcodeTemplateVal  =   {};

        BarcodeTemplateVal['BarcodeTemplate']   =   BarcodeTemplate;
        rradiovalue['radioValue']     =   labelType;
        //alert(rradiovalue['radioValue']);

        arraydetail.push(BarcodeTemplateVal);
        arraydetail.push(rradiovalue);

        // console.log(arraydetail);
       // return false;

        localStorage.setItem('barcode-printing-record',JSON.stringify(arraydetail));
        var url  =   'barcode-sticker';

        window.open(url,'_blank');
    
        
    }

});

function editTemplate(value)
{
    // $('#openTemplateEditor').click();

    var url = "editTemplate";
    var type = "POST";
    var data = {
        'barcode_template_id':  value
    };
    callroute(url, type, data, function (Data) {

        var pushdata = JSON.parse(Data, true);
        if (pushdata['Success'] == "True") {

            var datanew  =   pushdata['Data'];

            // console.log(datanew[0]);

            var barcode_sheet_id                =   datanew[0]['barcode_sheet_id'];
            var barcode_type                    =   datanew[0]['barcode_type']; 
            var template_data                   =   datanew[0]['template_data']; 
            var barcode_template_id             =   datanew[0]['barcode_template_id']; 
            var template_label_font_size        =   datanew[0]['template_label_font_size']; 
            var template_label_height           =   datanew[0]['template_label_height']; 
            var template_label_margin_bottom    =   datanew[0]['template_label_margin_bottom']; 
            var template_label_margin_left      =   datanew[0]['template_label_margin_left']; 
            var template_label_margin_right     =   datanew[0]['template_label_margin_right']; 
            var template_label_margin_top       =   datanew[0]['template_label_margin_top']; 
            var template_label_size_type        =   datanew[0]['template_label_size_type']; 
            var template_label_width            =   datanew[0]['template_label_width'];          
            var template_name                   =   datanew[0]['template_name']; 

            $('#edit_template_name').val(template_name);
            CKEDITOR.instances.edit_template_data.setData(template_data);
            $('#edit_label_width').val(template_label_width);
            $('#edit_label_height').val(template_label_height);
            $('#edit_label_font_size').val(template_label_font_size);
            $('#edit_label_margin_top').val(template_label_margin_top);
            $('#edit_label_margin_right').val(template_label_margin_right);
            $('#edit_label_margin_bottom').val(template_label_margin_bottom);
            $('#edit_label_margin_left').val(template_label_margin_left);

            $('#edit_PrintBarcodeSheets option[value="'+barcode_sheet_id+'"]').prop('selected', true);
            $('#edit_PrintBarcodeType option[value="'+barcode_type+'"]').prop('selected', true);
            $('#edit_label_size_type option[value="'+template_label_size_type+'"]').prop('selected', true);

            $('#edit_value_id').val(value);

        }
       
    });
}

$('#edit_SaveBarcodeTemplateBtn').click( function(e){

    var arrayValue          =   [];
    var template_values     =   {};

    template_values['barcode_template_id']  =   $('#edit_value_id').val();
    template_values['PrintBarcodeSheets']   =   $('#edit_PrintBarcodeSheets').val();
    template_values['PrintBarcodeType']     =   $('#edit_PrintBarcodeType').val();
    template_values['template_name']        =   $('#edit_template_name').val();
    template_values['template_data']        =   CKEDITOR.instances.edit_template_data.getData();
    template_values['label_width']          =   $('#edit_label_width').val();
    template_values['label_height']         =   $('#edit_label_height').val();
    template_values['label_size_type']      =   $('#edit_label_size_type').val();
    template_values['label_font_size']      =   $('#edit_label_font_size').val();
    template_values['label_margin_top']     =   $('#edit_label_margin_top').val();
    template_values['label_margin_right']   =   $('#edit_label_margin_right').val();
    template_values['label_margin_bottom']  =   $('#edit_label_margin_bottom').val();
    template_values['label_margin_left']    =   $('#edit_label_margin_left').val();

    if(template_values['PrintBarcodeSheets']=='')
    {
        toastr.error('please select sheet');

    }
    else if(template_values['PrintBarcodeType']=='')
    {
        toastr.error('please select barcode type');
    }
    else if(template_values['template_name']=='')
    {
        toastr.error('please enter template name');
        $('#template_name').focus();
    }
    else if(template_values['template_data']=='')
    {
        toastr.error('please enter template data');
    }
    else if(template_values['label_width']=='')
    {
        toastr.error('please enter label width');
        $('#label_width').focus();
    }
    else if(template_values['label_height']=='')
    {
        toastr.error('please enter label height');
        $('#label_height').focus();
    }
    else if(template_values['label_size_type']=='')
    {
        toastr.error('please select label size type');
    }
    else if(template_values['label_font_size']=='')
    {
        toastr.error('please enter label font size');
        $('#label_font_size').focus();
    }
    else if(template_values['label_margin_top']=='')
    {
        toastr.error('please enter label top margin, if not then enter 0');
        $('#label_margin_top').focus();
    }
    else if(template_values['label_margin_right']=='')
    {
        toastr.error('please enter label right margin, if not then enter 0');
        $('#label_margin_right').focus();
    }
    else if(template_values['label_margin_bottom']=='')
    {
        toastr.error('please enter label bottom margin, if not then enter 0');
        $('#label_margin_bottom').focus();
    }
    else if(template_values['label_margin_left']=='')
    {
        toastr.error('please enter label left margin, if not then enter 0');
        $('#label_margin_left').focus();
    }
    else
    {

        arrayValue.push(template_values);

        var data = arrayValue;

        // console.log(data);

        var  url = "edit_template_save";
        var type = "POST";
        callroute(url,type,data,function (data)
        {
            var dta = JSON.parse(data); 
            if(dta['Success']=='True')
            {
                toastr.success('template updated successfully...');
                window.location = dta['url'];
            }
        });
    }

});

function deleteTemplate(value)
{
    if (confirm('Are you sure you want to delete this template?'))
    {
        var url = "deleteTemplate";
        var type = "POST";
        var data = {
            'barcode_template_id':  value
        };
        callroute(url, type, data, function (Data) {
            var dta = JSON.parse(Data); 
            console.log(dta);
            toastr.success('template deleted successfully...');
            window.location = dta['url'];
        });
    }
    return false;
    
}

function getTotalPrintQty()
{
    var printStock  =   0;
    $('.printStock').each(function (index,e){
        printStock   +=   parseFloat($(this).val());
    });

    var totalResults  =   0;
    $('.totalResults').each(function (index,e){
        totalResults   +=   parseFloat($(this).val());
    });

    $('.totalSearchCount').html('('+totalResults+')');

    $('#barcodeTotalQty').html(printStock);
    $('#barcodeTotalQty_text').val(printStock);



    var labelTypeName           =   $("#selectedTemplateID").val();
    var labeltagline            =   $('#label_tagline_'+labelTypeName).val();
    var label                   =   labeltagline.split('x');

    var barcodeTotalQty_text    =   $('#barcodeTotalQty_text').val();
    var totalLabelsinOneSheet   =   Number(label[0]) * Number(label[1]);

    var sheetVal                =   Number(barcodeTotalQty_text) / Number(totalLabelsinOneSheet);
    
    var val                     =   Math.ceil(sheetVal);

    $('#searchBarcodeSheets').html(val);


}

// $("input[name='labelTypeName']").click( function(e){
//     var labelTypeName           =   $("input[name='labelTypeName']:checked").val();
//     var labeltagline            =   $('#chkbox_'+labelTypeName).val();
//     var label                   =   labeltagline.split('x');

//     var barcodeTotalQty_text    =   $('#barcodeTotalQty_text').val();
//     var totalLabelsinOneSheet   =   Number(label[0]) * Number(label[1]);

//     var sheetVal                =   Number(barcodeTotalQty_text) / Number(totalLabelsinOneSheet);
    
//     var val                     =   Math.ceil(sheetVal);

//     $('#searchBarcodeSheets').html(val);
    
// });

$('#resetBtn').click( function(e){

    $('#fromtodate').val('');
    $('#fBarcode').val('');
    $('#tBarcode').val('');
    $('#productName').val('');
    $('#productCode').val('');
    $('#supplier_barcode').val('');
    $('#invoiceNo').val('');
    $('#category').val('');
    $('#brandname').val('');
    $('#sizename').val('');
    $('#colourname').val('');
    $('#skucode').val('');

    $('#searchResult').html('');
    $('#searchBarcodeCount').html('0');
    $('#barcodeTotalQty').html('0');
    $('#searchBarcodeSheets').html('0');

});


function stickerClick(barcode_template_id)
{
    var labeltagline            =   $('#label_tagline_'+barcode_template_id).val();
    var label                   =   labeltagline.split('x');

    var barcodeTotalQty_text    =   $('#barcodeTotalQty_text').val();
    var totalLabelsinOneSheet   =   Number(label[0]) * Number(label[1]);

    var sheetVal                =   Number(barcodeTotalQty_text) / Number(totalLabelsinOneSheet);
    
    var val                     =   Math.ceil(sheetVal);

    $('#searchBarcodeSheets').html(val);
    $('#selectedTemplateID').val(barcode_template_id);
}