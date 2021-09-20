<style>
#OuterDivA4{
    width:23cm;
    border:0px solid;
}
body{
    font-family: arial;
}
</style>

<script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(e) {
    var data1    =   localStorage.getItem('barcode-printing-record');
    console.log(data1);
    var product_data = JSON.parse(data1,true);
    var radioValue = product_data[2]['radioValue'];
    var BarcodeTemplate = product_data[1]['BarcodeTemplate'];
 
    
    //console.log(product_data);
    //console.log(product_data[1]);

    var url1 = "fetchTemplateData";
    var type1 = "POST";
    var data1 = {
        'BarcodeTemplateId': product_data[1]['BarcodeTemplate']
    };
    callroute(url1, type1, data1, function (datax) {
        var searchdata = JSON.parse(datax, true);
        // console.log(searchdata);
        templateDataHTMLCODE1   =   '';
        if (searchdata['Success'] == "True") {
            console.log(searchdata);
            var templateData                    =   searchdata['Data1'];
            var barcode_type                    =   templateData[0]['barcode_type'];
            var templateDataHTMLCODE            =   templateData[0]['template_data'];
            var template_label_width            =   templateData[0]['template_label_width'];
            var template_label_height           =   templateData[0]['template_label_height'];
            var template_label_font_size        =   templateData[0]['template_label_font_size'];
            var template_label_margin_top       =   templateData[0]['template_label_margin_top'];
            var template_label_margin_right     =   templateData[0]['template_label_margin_right'];
            var template_label_margin_bottom    =   templateData[0]['template_label_margin_bottom'];
            var template_label_margin_left      =   templateData[0]['template_label_margin_left'];
            var template_label_size_type        =   templateData[0]['template_label_size_type'];

            var layout_width                    =   templateData[0]['barcode_sheet']['layout_width'];

            $.each(product_data[0],function (key,value){
                
                var printQty    =   value['printqty'];
                var url = "barcode_product_detail";
                var type = "POST";
                var data = {
                    "product_id": value['product_id'],
                    "inward_id": value['inward_id']
                }
                callroute(url, type, data, function (data1)
                {
                    var pushdata = JSON.parse(data1, true);
                    var CompanyName =   pushdata['CompanyName'];

                    if (pushdata['Success'] == "True")
                    {
                        console.log(pushdata);
                        var templateData1  =   pushdata['Data'];
                        var product_barcode     =   '';
                        $.each(templateData1,function (key,value1)
                        {
                            
                            var product_barcode     =   value1['product']['product_system_barcode'];
                            var supplier_barcode    =   value1['product']['supplier_barcode']==''?product_barcode:value1['product']['supplier_barcode'];
                            var product_name        =   value1['product']['product_name'];
                            var product_code        =   value1['product']['product_code'];
                            var product_desc        =   value1['product']['product_description'];
                            var product_mrp         =   value1['product_mrp'];
                            var offer_price         =   value1['offer_price'];
                            
                            if(value1['product']['brand_id']=='' || value1['product']['brand_id']==null)
                            {
                                var brand_type            =   '';
                            }
                            else
                            {
                                var brand_type            =   value1['product']['brand']['brand_type'];
                            }

                            if(value1['product']['sku_code']=='' || value1['product']['sku_code']==null)
                            {
                                var sku_code            =   '';
                            }
                            else
                            {
                                var sku_code            =   value1['product']['sku_code'];
                            }

                            if(value1['product']['size_id']=='' || value1['product']['size_id']==null)
                            {
                                var size_name           =   '';
                            }
                            else
                            {
                                var size_name           =   value1['product']['size']['size_name'];
                            }

                            if(value1['product']['colour_id']=='' || value1['product']['colour_id']==null)
                            {
                                var colour_name         =   '';
                            }
                            else
                            {
                                var colour_name         =   value1['product']['colour']['colour_name'];
                            }

                            if(value1['product']['subcategory_id']==null || value1['product']['subcategory_id']=='')
                            {
                                var subcategory         =   '';
                            }
                            else
                            {
                                var subcategory         =   value1['product']['subcategory']['subcategory_name'];
                            }

                            if(value1['product']['category_id']==null || value1['product']['category_id']=='')
                            {
                                var category         =   '';
                            }
                            else
                            {
                                var category         =   value1['product']['category']['category_name'];
                            }

                            var z;

                            

                            var url2 = "GenerateBarcode";
                            var type2 = "POST";
                            var data2 = {
                                "product_barcode": product_barcode,
                                "barcode_type": barcode_type
                            }
                            callroute(url2, type2, data2, function (datan)
                            {
                                //console.log(product_barcode);
                                var fetchGeneratedBarcode   = JSON.parse(datan, true);
                                var fetchGeneratedBarcode   =   fetchGeneratedBarcode['Data'];

                                var find                =   [
                                    '[BARCODE]',
                                    '[SUPP_BARCODE]',
                                    '[SIZE]',
                                    '[COLOUR]',
                                    '[MRP]',
                                    '[SKUCODE]',
                                    '[SUBCATEGORY]',
                                    '[PRODUCT_NAME]',
                                    '[PRODUCT_DESC]',
                                    '[CODE]',
                                    '[OFFER_PRICE]',
                                    '[COMPANY]',
                                    '[BRAND]',
                                    '[MATERIAL]',
                                    '[CATEGORY]',
                                    '<p>',
                                    '</p>'
                                ];

                                var rep                 =   [
                                    '<img src="data:image/png;base64,'+fetchGeneratedBarcode+'" alt="barcode" /><br /><font style="font-size:20px;">'+product_barcode+'</font>',
                                    '<img src="data:image/png;base64,'+fetchGeneratedBarcode+'" alt="barcode" /><br /><font style="font-size:20px;">'+supplier_barcode+'</font>',
                                    size_name,
                                    colour_name,
                                    '₹'+product_mrp+'',
                                    sku_code,
                                    subcategory,
                                    product_name,
                                    product_desc,
                                    product_code,
                                    '₹'+offer_price+'',
                                    CompanyName,
                                    brand_type,
                                    '',
                                    category,
                                    '',
                                    ''
                                ];

                                templateDataHTMLCODE1    = templateDataHTMLCODE.replace(find[0],rep[0]);
                                templateDataHTMLCODE1    = templateDataHTMLCODE1.replace(find[1],rep[1]);
                                templateDataHTMLCODE1    = templateDataHTMLCODE1.replace(find[2],rep[2]);
                                templateDataHTMLCODE1    = templateDataHTMLCODE1.replace(find[3],rep[3]);
                                templateDataHTMLCODE1    = templateDataHTMLCODE1.replace(find[4],rep[4]);
                                templateDataHTMLCODE1    = templateDataHTMLCODE1.replace(find[5],rep[5]);
                                templateDataHTMLCODE1    = templateDataHTMLCODE1.replace(find[6],rep[6]);
                                templateDataHTMLCODE1    = templateDataHTMLCODE1.replace(find[7],rep[7]);
                                templateDataHTMLCODE1    = templateDataHTMLCODE1.replace(find[8],rep[8]);
                                templateDataHTMLCODE1    = templateDataHTMLCODE1.replace(find[9],rep[9]);
                                templateDataHTMLCODE1    = templateDataHTMLCODE1.replace(find[10],rep[10]);
                                templateDataHTMLCODE1    = templateDataHTMLCODE1.replace(find[11],rep[11]);
                                templateDataHTMLCODE1    = templateDataHTMLCODE1.replace(find[12],rep[12]);
                                templateDataHTMLCODE1    = templateDataHTMLCODE1.replace(find[13],rep[14]);

                                $('table tr td').css('font-size',' '+template_label_font_size+'pt ');

                                $('#OuterDivA4').css('width',layout_width);

                                for(z=1;z<=printQty;z++)
                                {
                                    $('.printBarcodeDiv').append('<div style="width:'+template_label_width+''+template_label_size_type+'; height:'+template_label_height+''+template_label_size_type+'; margin-top:'+template_label_margin_top+'px; margin-right:'+template_label_margin_right+'px; margin-bottom:'+template_label_margin_bottom+'px; margin-left:'+template_label_margin_left+'px; display:inline-block !important;">'+templateDataHTMLCODE1+'</div>');
                                }

                            });

                        });
                    }
                });      
            });
        }   
    });

    
    

});
</script>

<meta name="csrf-token" content="{{ csrf_token() }}" />

<div id="OuterDivA4" style="border:0px solid;">

    <div class="printBarcodeDiv"></div>

</div>

<script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>
<script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/popper.js/js/popper.min.js"></script>
<script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>
<script src="{{URL::to('/')}}/public/modulejs/common.js"></script>