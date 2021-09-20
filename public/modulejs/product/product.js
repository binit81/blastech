$(document).ready(function () {


    getbrand();
    getCategory('1','');
    getColour();
    getSize();
    getUqc();

});

function productImages(product_id)
{
    $.ajax({
        url:'get_productImages',

        data: {
            product_id:product_id
        },
        success:function(data)
        {
            var dta = JSON.parse(data);
            // console.log(dta['Data']);

            if(dta['Data']!='')
            {
                var product_name                =   dta['Data'][0]['products']['product_name'];
                var product_system_barcode      =   dta['Data'][0]['products']['product_system_barcode'];
                $('#ModalCarousel34 .modal-title').html(product_name+' ('+product_system_barcode+')');

                $(".carousel-indicators").html('');
                $(".carousel-inner").html('');

                var img_div = '';
                var slide_div = '';

                $.each(dta['Data'],function (key,value)
                {
                    var clas    =   '';
                    if(Number(key)==0)
                    {
                         clas    =   'active';
                    }

                    slide_div += '<li data-target="#demo" data-slide-to="'+key+'" class="'+clas+'"></li>';

                    img_div += '<div class="carousel-item '+clas+' center"> ' +
                        '<img src="'+product_image_url+value['product_image']+'" width="100%" alt="'+value['caption']+'">'+value['caption']+'</div>';
                });
                
                $(".carousel-indicators").append(slide_div);
                $(".carousel-inner").append(img_div);
                $("#ModalCarousel34").modal('show');
            }
        }
    })
}

$('#addnewcollapse').click(function(e)
{
    $("#product_name_filter").val('');
    $("#barcode_filter").val('');
    $("#brand_id_filter").val(0);
    $("#category_id_filter").val(0);
    $("#subcategory_id_filter").val(0);
    $("#colour_id_filter").val(0);
    $("#size_id_filter").val(0);
    $("#uqc_id_filter").val(0);
    $("#productform").trigger('reset');

    $("#hidden_page").val(1);
    $('#product_block').slideToggle();

    $('#EditImagesBlock').html('');



    resettable('product_data','productrecord');


});
$('#addnewkitcollapse').click(function(e)
{
    
    $('#product_block').slideToggle();

});


$('#searchCollapse').click(function(e){
    $('#filterarea_block').slideToggle();
});


function validate_productform(frmid)
{
    var error = 0;

    if($("#product_name").val() == '')
    {
         error = 1;
        toastr.error("Please Enter Product Name!");
        return false;
    }


    if(error == 1)
    {
        $("#formerr").show();
        return false;
    }
    else
    {
        $("#formerr").hide();
        return true;
    }
}


function getbrand(brandid) {
    var url = "get_brand";
    var type = "GET";
    var data = {};

    callroute(url,type,data,function (data)
    {
        if(data['Success'] == "True")
            {
                var brandhtml = '';
                $("#brand_id").html('');
                $("#brand_id_filter").html('');
                if(data['Data'].length > 0)
                {
                    brandhtml = "<option value='0'>Brand</option>";
                    $.each(data['Data'],function (key,value)
                    {
                        brandhtml += '<option value='+value['brand_id']+'>'+value['brand_type']+'</option>';
                    });
                    $("#brand_id,#brand_id_filter").append(brandhtml);
                }
                else
                {
                    brandhtml = "<option value='0'>Brand</option>";

                    $('#brand_id,#brand_id_filter').append(brandhtml);

                }

            }
        if(brandid != '' && brandid != null && brandid != 0)
        {
            $("#brand_id").val(brandid);
        }
     });
}

function getCategory(fillcattype,category_id)
{

    var url = "get_category";
    var type = "GET";
    var data = {};

    callroute(url,type,data,function (data)
    {
        if (data['Success'] == "True")
        {
            var categoryhtml = '';
            var catid = $("#category_id").val();


            if (data['Data'].length > 0)
            {
                $("#category_id").html('');
                categoryhtml = "<option value='0'>Category</option>";

                $.each(data['Data'], function (key, value)
                {
                    categoryhtml += '<option value=' + value['category_id'] + '>' + value['category_name'] + '</option>';
                });
                $("#category_id,#category_id_filter").append(categoryhtml);
            } else
                {
                     categoryhtml = "<option value='0'>Category</option>";
                    $("#category_id,#category_id_filter").append(categoryhtml);
                }

            if(category_id != '' && category_id != null && category_id != 0)
            {
                $("#category_id").val(category_id);
            }

          }

        if(fillcattype == '2')
        {
            $("#popcategory_id").append(categoryhtml);

            if(catid != '' && catid != null && catid != 0)
            {
                $("#popcategory_id").val(catid);
            }
        }

        //$("#subcategory_id").html('');
       // $("#popcategory_id").val();
        //$("#subcategory_id").append('<option value=0>Sub Category</option>');
        $("#subcategory_id_filter").append('<option value=0>Sub Category</option>');




    });
}
function getsubcategory(subcatid)
{
    var category_ID = $('#category_id :selected').val();
    var url = "get_subcategory";
    var type = "POST";
    var data = {
        "category_ID":category_ID
    };

    callroute(url,type,data,function (data)
    {
        if(data['Success'] == "True")
        {

            var subcategoryhtml = '';
            $("#subcategory_id").html('');
            if(data['Data'].length > 0)
            {
                subcategoryhtml += "<option value='0'>Sub Category</option>";

                $.each(data['Data'],function (key,value)
                {
                    subcategoryhtml += '<option value='+value['subcategory_id']+'>'+value['subcategory_name']+'</option>';
                });
                $("#subcategory_id").append(subcategoryhtml);
            }
            else {
                     //subcategoryhtml += "";
                    $("#subcategory_id").append("<option value='0'>Sub Category</option>");
                }
            }


            if(subcatid != '')
            {
                $("#subcategory_id").val(subcatid);
            }
           // $("#subcategory_id").val();
        });
}
function editgetsubcategory(catid,subcatid)
{
    var category_ID = catid;
    var url = "get_subcategory";
    var type = "POST";
    var data = {
        "category_ID":category_ID
    };

    callroute(url,type,data,function (data)
    {
        if(data['Success'] == "True")
        {

            var subcategoryhtml = '';
            $("#subcategory_id").html('');
            if(data['Data'].length > 0)
            {
                subcategoryhtml += "<option value='0'>Sub Category</option>";

                $.each(data['Data'],function (key,value)
                {
                    subcategoryhtml += '<option value='+value['subcategory_id']+'>'+value['subcategory_name']+'</option>';
                });
                $("#subcategory_id").append(subcategoryhtml);
            }
            else {
                     //subcategoryhtml += "";
                    $("#subcategory_id").append("<option value='0'>Sub Category</option>");
                }
            }
         
            if(subcatid != '')
            {
                $("#subcategory_id").val(subcatid);
            }
           // $("#subcategory_id").val();
        });
}

function getsubcategory_filter(subcatid)
{

    var category_ID = $('#category_id_filter :selected').val();

    var url = "get_subcategory";
    var type = "POST";
    var data = {
        "category_ID":category_ID
    };

    callroute(url,type,data,function (data)
    {
        if(data['Success'] == "True")
        {

            var subcategoryhtml = '';
            $("#subcategory_id_filter").html('');
            if(data['Data'].length > 0)
            {
                subcategoryhtml += "<option value='0'>Sub Category</option>";

                $.each(data['Data'],function (key,value)
                {
                    subcategoryhtml += '<option value='+value['subcategory_id']+'>'+value['subcategory_name']+'</option>';
                });
                $("#subcategory_id_filter").append(subcategoryhtml);
            }
            else {
                     //subcategoryhtml += "";
                    $("#subcategory_id_filter").append("<option value='0'>Sub Category</option>");
                }
            }

        });
}

function getColour(colour_id) {

    var url = "get_colour";
    var type = "GET";
    var data = {};

    callroute(url,type,data,function (data)
    {
        if (data['Success'] == "True") {
            var colourhtml = '';
            $("#colour_id").html('');
            if (data['Data'].length > 0)
            {
                colourhtml = "<option value='0'>Colour</option>";
                $.each(data['Data'], function (key, value) {
                    colourhtml += '<option value=' + value['colour_id'] + '>' + value['colour_name'] + '</option>';
                });
                $("#colour_id,#colour_id_filter").append(colourhtml);
                    }
            else
                {
                    colourhtml = "<option value='0'>Colour</option>";
                    $("#colour_id,#colour_id_filter").append(colourhtml);
                }
            }

        if(colour_id != '' && colour_id != null && colour_id != 0)
        {
            $("#colour_id").val(colour_id);
        }
        });
}

function getSize(size_id)
{
    var url = "get_size";
    var type = "GET";
    var data = {};

    callroute(url,type,data,function (data)
    {
        if (data['Success'] == "True")
        {
            var sizehtml = '';
            $("#size_id").html('');
            if (data['Data'].length > 0)
            {
                sizehtml = "<option value='0'>Size</option>";
                $.each(data['Data'], function (key, value) {
                    sizehtml += '<option value=' + value['size_id'] + '>' + value['size_name'] + '</option>';
                });
                $("#size_id,#size_id_filter").append(sizehtml);
            } else {
                 sizehtml = "<option value='0'>Size</option>";
                 $("#size_id,#size_id_filter").append(sizehtml);
                    }
        }
        if(size_id != '' && size_id != null && size_id != 0)
        {
            $("#size_id").val(size_id);
        }
        });
}

function getUqc(uqc_id) {

    var url = "get_uqc";
    var type = "GET";
    var data = {};

    callroute(url,type,data,function (data)
    {
        if (data['Success'] == "True")
        {
            var uqchtml = '';
            $("#uqc_id").html('');
            if (data['Data'].length > 0)
            {
                uqchtml = "<option value='0'>UQC</option>";
                $.each(data['Data'], function (key, value)
                {
                    uqchtml += '<option value=' + value['uqc_id'] + '>' + value['uqc_shortname'] + '</option>';
                });
                $("#uqc_id,#uqc_id_filter").append(uqchtml);
            } else
                {
                    uqchtml = "<option value='0'>UQC</option>";
                    $("#uqc_id,#uqc_id_filter").append(uqchtml);
                }
                if(uqc_id != '' && uqc_id != null && uqc_id != 0)
                {
                    $("#uqc_id").val(uqc_id);
                }
         }
        });
}

var count = 1;
$("#addmoreimg").click(function()
{
//
    $(this).prop('disabled', true);
    count++;
   // $("#image1").clone().attr('id', 'product_image_'+count).insertAfter("#product_image_1");
    $('#addmoreimg').before('<div class="col-md-2 block_'+count+'" class="previews"><label class="form-label">Product Image Caption</label><input type="text" name="imageCaption[]" id="imageCaption_'+count+'" placeholder="" /><button type="button" class="btn btn-danger mt-10" onclick="removefun('+count+')"><i class="fa fa-minus"></i></button></div><div class="col-md-2 block_'+count+'">'+
        '<div class="form-group">' +
        '<label class="form-label">Product Image</label><input onchange="previewandvalidation(this);" accept=".png, .jpg, .jpeg" type="file" name="product_image[]" id="product_image_'+count+'" data-counter="'+count+'" class="form-control form-inputtext productimage">' +
        '<div style="display: none" id="preview_'+count+'" class="previews">' +
        '<a  onclick="removeimgsrc('+count+');" class="displayright"><i class="fa fa-remove" style="font-size: 20px;"></i></a>' +
        '<img src="" id="product_preview_'+count+'" width="" height="150px"></div></div></div>');

    $(this).prop('disabled', false);

});

function removefun(cnt)
{
    $(".block_"+cnt).remove();
}
function removeimgsrc(cntid)
{

    $('#product_preview_'+cntid).attr('src', '');
    $('#product_image_'+cntid).val('');
    $("#preview_"+cntid).hide();
}

$("#addservice").click(function (e)
{
    if(validate_serviceform('serviceform'))
    {
        $("#type").val('2');
        $("#addservice").prop('disabled', true);

        var data = {
            "formdata": $("#serviceform").serialize(),
            "type" : '2'
        };

        var  url = "product_create";
        var type = "POST";
        callroute(url,type,data,function (data)
        {
            $("#addservice").prop('disabled', false);
            var dta = JSON.parse(data);

            if(dta['Success'] == "True")
            {
                var message = dta['Message'].replace("Product","Room");
                toastr.success(message);

                $("#serviceform").trigger('reset');
                $("#product_id").val('');
                resettable('service_data');
            }
            else
            {
                if(dta['status_code'] == 409)
                {
                    $.each(dta['Message'],function (errkey,errval)
                    {
                        var errmessage = errval[0].replace("supplier barcode","Room No");
                        toastr.error(errmessage);
                      //  alert(errmessage);
                    });
                }
                else
                {
                    toastr.error(dta['Message']);
                }


            }
        })


    }
    e.preventDefault();
});


function validate_serviceform(frmid)
{
    var error = 0;

    if($("#supplier_barcode").val() == '')
    {
        error = 1;
        toastr.error("Please Enter Room No!");
        return false;
    }

    if($("#selling_price").val() == '')
    {
        error = 1;
        toastr.error("Please Enter Tarrif!");
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


$('#productform').on('submit', function(event)
{

    if(validate_productform('productform'))
    {
        event.preventDefault();
        $("#addproduct").prop('disabled', false);
        $("#addproduct").text('Add Product');
        
        $.ajaxSetup({
            headers : { "X-CSRF-TOKEN" :jQuery(`meta[name="csrf-token"]`). attr("content")}
        });

        $.ajax({
            url: "product_create",
            method: "POST",
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data)
            {
                console.log(data); //return false;
                if(data['Success'] == "True")
                {
                    toastr.success(data['Message']);
                    $("#productform").trigger('reset');
                    $('.previews').html('');
                    $("#product_id").val('');
                    $('input[name=product_type][value=1]').prop('checked',true);
                    resettable('product_data','productrecord');
                    $('#product_block').slideToggle();
                }
                else
                {
                    if(data['status_code'] == 409)
                    {
                        $.each(data['Message'],function (errkey,errval)
                        {
                            var errmessage = errval[0];

                            if(errmessage == "The supplier barcode has already been taken.")
                            {
                                var supplier_barcode = $("#supplier_barcode").val();
                                //toastr.error('Product with this Supplier Barcode : '+supplier_barcode+' already exist.<a style="display: inline-block;">Click here</a> to view the existing product.');
                                toastr.error("<br /><button>View this product</button>",'Product with this Supplier Barcode : '+supplier_barcode+' already exist.',
                                    {
                                        allowHtml: true,
                                        showCloseButton: true,
                                        onclick: function ()
                                        {
                                            view_existing_product(supplier_barcode);
                                            toastr.clear()
                                        }

                                    })

                            }
                            else
                            {
                                toastr.error(errmessage);
                                resettable('product_data','productrecord');

                            }
                        });

                    }
                    else
                    {
                        toastr.error(data['Message']);
                    }
                }
            }
        });

        // $("#type").val('1');
        // $("#addproduct").prop('disabled', true);
        // var data = {
        //     "formdata": $("#productform").serialize(),
        //     "type" : '1'
        // };
        // var  url = "product_create";
        // var type = "POST";
        // callroute(url,type,data,function (data)
        // {
        //     $("#addproduct").prop('disabled', false);
        //     $("#addproduct").text('Add Product');
        //     var dta = JSON.parse(data);

        //     if(dta['Success'] == "True")
        //     {
        //         toastr.success(dta['Message']);
        //         $("#productform").trigger('reset');
        //         $("#product_id").val('');
        //         $('input[name=product_type][value=1]').prop('checked',true);
        //         resettable('product_data','productrecord');
        //         $('#product_block').slideToggle();
        //     }
        //     else
        //     {
        //         if(dta['status_code'] == 409)
        //         {
        //             $.each(dta['Message'],function (errkey,errval)
        //             {
        //                 var errmessage = errval[0];

        //                 if(errmessage == "The supplier barcode has already been taken.")
        //                 {
        //                     var supplier_barcode = $("#supplier_barcode").val();
        //                     //toastr.error('Product with this Supplier Barcode : '+supplier_barcode+' already exist.<a style="display: inline-block;">Click here</a> to view the existing product.');
        //                     toastr.error("<br /><button>View this product</button>",'Product with this Supplier Barcode : '+supplier_barcode+' already exist.',
        //                         {
        //                             allowHtml: true,
        //                             showCloseButton: true,
        //                             onclick: function ()
        //                             {
        //                               view_existing_product(supplier_barcode);
        //                                 toastr.clear()
        //                             }

        //                         })

        //                 }
        //                 else
        //                 {
        //                     toastr.error(errmessage);
        //                     resettable('product_data','productrecord');

        //                 }
        //             });

        //         }
        //         else
        //         {
        //             toastr.error(dta['Message']);
        //         }
        //     }
        // })

    }
    event.preventDefault();
});

$("#deleteproduct").click(function ()
{
    if(confirm("Are You Sure want to delete this product?")) {

        var ids = [];

        $('input[name="delete_product[]"]:checked').each(function()
        {
            ids.push($(this).val());
        });

        if(ids.length > 0)
        {
        var data = {
            "deleted_id": ids
        };
        var url = "product_delete";
        var type = "POST";
        callroute(url, type, data, function (data) {

            var dta = JSON.parse(data);

            if (dta['Success'] == "True")
            {
                toastr.success(dta['Message']);
                $("#productform").trigger('reset');
                $("#product_id").val('');

                resettable('product_data','productrecord');

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




$("#delete_service").click(function ()
{
    if(confirm("Are You Sure want to delete this room?")) {

        var ids = [];

        $('input[name="delete_room[]"]:checked').each(function()
        {
            ids.push($(this).val());
        });

        if(ids.length > 0)
        {
        var data = {
            "deleted_id": ids
        };
        var url = "product_delete";
        var type = "POST";
        callroute(url, type, data, function (data) {

            var dta = JSON.parse(data);

            if (dta['Success'] == "True")
            {
                toastr.success(dta['Message'].replace('Product','Room'));

                $("#serviceform").trigger('reset');
                $("#product_id").val('');
                resettable('service_data');
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


function previewandvalidation(input)
{
    var imageid = $(input).attr('id');
    var counterval = $(input).data('counter');


    var validExtensions = ['png','jpg','jpeg']; //array of valid extensions
    var fileName = input.files[0].name;
    var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1);
    if ($.inArray(fileNameExt, validExtensions) == -1) {
        input.type = '';
        input.type = 'file';
        $('#product_preview_'+counterval).attr('src',"");
        alert("Only these file types are accepted : "+validExtensions.join(', '));
    }
    else
    {
        if (input.files && input.files[0]) {
            var filerdr = new FileReader();
            filerdr.onload = function (e)
            {
                $("#preview_"+counterval).show();
                $('#product_preview_'+counterval).attr('src', e.target.result);
            };
            filerdr.readAsDataURL(input.files[0]);
        }
    }
}


//for add category,size,sub category colour size uqc id in popup

$("#addbrand").click(function () {
   $("#addbrandpopup").modal('show');
});

$("#addcategory").click(function () {
    $("#addcategorypopup").modal('show');
});

$("#addsubcategory").click(function ()
{
    getCategory('2','');
    $("#subcategory_name").val('');
    $("#addsubcategorypopup").modal('show');
});

$("#addcolour").click(function () {
   $("#addcolourpopup").modal('show');
});

$("#addsize").click(function () {
   $("#addsizepopup").modal('show');

   
});

$("#adduqc").click(function () {
   $("#adduqcpopup").modal('show');
});

//end of display popup of category,sub category,colur,uqc

//edit service
function editservice(productid)
{

    $(this).prop('disable',true);
    var url = "product_edit";
    var type = "POST";
    var data = {
        "product_id": productid
    }
    callroute(url, type, data, function (data)
    {
        $(this).prop('disable', false);
        var product_response = JSON.parse(data);

        if (product_response['Success'] == "True")
        {

            var product_data = product_response['Data'];


            $("#serviceform #product_id").val(product_data['product_id']);

            $("#selling_price").val(product_data['selling_price']);
            $("#sell_gst_percent").val(product_data['sell_gst_percent']);
            $("#sell_gst_amount").val(product_data['sell_gst_amount']);
            $("#product_mrp").val(product_data['product_mrp']);
            $("#product_description").val(product_data['product_description']);
            $("#hsn_sac_code").val(product_data['hsn_sac_code']);


            $("#supplier_barcode").val(product_data['supplier_barcode']);


        }
    });
}

//edn of edit service

//edit product

function editproduct(productid)
{
    $(this).prop('disable',true);
    $("html").scrollTop(0);
    $("#product_block").slideToggle();
    $("#addproduct").text('Update Product');
    var url = "product_edit";
    var type = "POST";
    var data = {
        "product_id": productid
    }
    callroute(url, type, data, function (data) {
        $(this).prop('disable', false);
        var product_response = JSON.parse(data);

        console.log(product_response);

        if (product_response['Success'] == "True")
        {
            var brandval = '';
            var categoryval = '';
            var subcategoryval = '';
            var colourval = '';
            var sizeval = '';
            var uqcval = '';
            var product_data = product_response['Data'];


           // $('#productform input[name=product_type][value='+product_data['product_type']+']').attr('checked', 'checked');



            $("#productform #product_id").val(product_data['product_id']);
            $("#productform #product_name").val(product_data['product_name']);
            $("#productform #product_note").val(product_data['note']);
            $("#productform #cost_rate").val(product_data['cost_rate']);
            $("#productform #cost_gst_percent").val(product_data['cost_gst_percent']);
            $("#productform #cost_gst_amount").val(product_data['cost_gst_amount']);
            $("#productform #extra_charge").val(product_data['extra_charge']);
            $("#productform #cost_price").val(product_data['cost_price']);
            $("#productform #profit_percent").val(product_data['profit_percent']);
            $("#productform #profit_amount").val(product_data['profit_amount']);
            $("#productform #selling_price").val(product_data['selling_price']);
            $("#productform #sell_gst_percent").val(product_data['sell_gst_percent']);
            $("#productform #sell_gst_amount").val(product_data['sell_gst_amount']);
            $("#productform #product_mrp").val(product_data['product_mrp']);
            $("#productform #offer_price").val(product_data['offer_price']);
            $("#productform #wholesale_price").val(product_data['wholesale_price']);
            $("#productform #sku_code").val(product_data['sku_code']);
            $("#productform #product_code").val(product_data['product_code']);
            $("#productform #product_description").val(product_data['product_description']);
            $("#productform #hsn_sac_code").val(product_data['hsn_sac_code']);
            if (product_data['brand_id'] == null) {
                brandval = '0';
            } else {
                brandval = product_data['brand_id'];
            }
            $("#productform #brand_id").val(brandval);
            if (product_data['category_id'] == null) {
                categoryval = '0';
            } else {
                categoryval = product_data['category_id'];
            }
            $("#productform #category_id").val(categoryval);
            if (product_data['subcategory_id'] == null) {
                subcategoryval = '0';
            } else {
                subcategoryval = product_data['subcategory_id'];
            }
            getsubcategory(subcategoryval);


            if (product_data['colour_id'] == null) {
                colourval = '0';
            } else {
                colourval = product_data['colour_id'];
            }
            $("#productform #colour_id").val(colourval);


            if (product_data['size_id'] == null) {
                sizeval = '0';
            } else {
                sizeval = product_data['size_id'];
            }
            $("#productform #size_id").val(sizeval);


            if (product_data['uqc_id'] == null) {
                uqcval = '0';
            } else {
                uqcval = product_data['uqc_id'];
            }
            $("#productform #uqc_id").val(uqcval);

            if(product_data['profit_percent'] == '' || product_data['profit_percent'] == null || product_data['profit_percent'] <= 0)
            {
                $("#profit_percent").css('color','red');
            }

            if(product_data['profit_amount'] == '' || product_data['profit_amount'] == null || product_data['profit_amount'] <= 0)
            {
                $("#profit_amount").css('color','red');
            }


            $("#productform #product_system_barcode").val(product_data['product_system_barcode']);
            $("#productform #supplier_barcode").val(product_data['supplier_barcode']);
            $("#productform #product_ean_barcode").val(product_data['product_ean_barcode']);
            $("#productform #alert_product_qty").val(product_data['alert_product_qty']);
            $("#productform #days_before_product_expiry").val(product_data['days_before_product_expiry']);

            $('#EditImagesBlock').html('');
            $('#EditImagesBlock').show();
            $('.previews').html('');

            $.each(product_data['product_images'],function (key,value)
            {
                $('#EditImagesBlock').prepend('<div class="col-md-3 center" id="picture_'+value['product_image_id']+'"><img src="'+product_image_url+value['product_image']+'" id="product_preview_1" name="product_preview_1" class="pb-10" width="" height="150px"><br><b>'+value['caption']+'</b><br><a class="displayright pt-10" onclick="removePicture('+value['product_image_id']+')"><i class="fa fa-remove cursor" style="font-size: 20px;"></i></a></div>');
            });
        }
    });
}

function removePicture(product_image_id)
{
    var fetch_data_url  =   'ProductremovePicture';
    $('.loaderContainer').show();
    $.ajax({
        url:fetch_data_url,
        data: {
            product_image_id:product_image_id,
        },
        success:function(data)
        {
            var searchdata = JSON.parse(data, true);
            $('.loaderContainer').hide();
            // console.log(searchdata);

            $('#picture_'+product_image_id).remove();

            toastr.success(searchdata['Message']);
        }
    })
}


//12 march 2019...added by hemaxi.....for type(service and product)

$("input[name='formtype']").on("click",function ()
{
   var id = $(this).attr('value');

   if(id == 1)
   {
       $("#productform").trigger('reset');
       $("#product_block").css('display','block');
       $("#productmaintable").css('display','block');
       $("#service_block").css('display','none');
       $("#servicemaintable").css('display','none');

   }
   else
   {
       $("#serviceform").trigger('reset');
       $("#product_block").css('display','none');
       $("#productmaintable").css('display','none');
       $("#service_block").css('display','block');
       $("#servicemaintable").css('display','block');
   }
});


$(".servicesellingprice").keyup(function () {

    var service_selling_price = $(this).val();

    if(service_selling_price == '')
    {
        $("#serviceform #sell_gst_percent").val('');
        $("#serviceform #sell_gst_amount").val('');
        $("#serviceform #product_mrp").val('');
        return false;
    }

    var type = "POST";
    var url = 'gstrange_detail';

    var data = {
        "sellingprice" : service_selling_price
    };

    callroute(url,type,data,function(data)
    {
        var gst_data = JSON.parse(data,true);

        if(gst_data['Success'] == "True")
        {
            var gst_detail  = gst_data['Data'][0];

            var percentage = gst_detail['percentage'];

            $("#serviceform #sell_gst_percent").val(Number(percentage).toFixed(4));

            var sellgstamount = ((Number(gst_detail['percentage'])) * (Number(service_selling_price)) / (Number(100)));
            $("#serviceform #sell_gst_amount").val(sellgstamount.toFixed(4));
            var tarrifwithgst = ((Number(sellgstamount)) + (Number(service_selling_price)));
            $("#serviceform #product_mrp").val(tarrifwithgst.toFixed(4));
        }
    });
});


$('#checkallproduct').change(function()
{
    if($(this).is(":checked"))
    {
        $("#productsalldata tr").each(function()
        {
            var id = $(this).attr('id');

            $(this).find('td').each(function ()
            {
                $("#delete_product_"+id).prop('checked',true);
            });

        })
    }
    else
    {
        $("#productsalldata tr").each(function()
        {
            var id = $(this).attr('id');
            $(this).find('td').each(function ()
            {
                $("#delete_product_"+id).prop('checked',false);
            });
        })
    }
});

$('#checlallservice').change(function()
{
    if($(this).is(":checked")) {
        $("#servicerecord tr").each(function()
        {
            var id = $(this).attr('id');

            $(this).find('td').each(function ()
            {
                $("#delete_room_"+id).prop('checked',true);
            });

        })
    }
    else
    {
        $("#servicerecord tr").each(function(){
            var id = $(this).attr('id');
            $(this).find('td').each(function ()
            {
                $("#delete_room_"+id).prop('checked',false);
            });

        })
    }
});
function resetservicedata()
{
    $("#serviceform").trigger('reset');
    $("#product_id").val('');
}

function resetproductfilterdata()
{
    $("#product_name_filter").val('');
    $("#barcode_filter").val('');
    $("#brand_id_filter").val(0);
    $("#category_id_filter").val(0);
    $("#subcategory_id_filter").val(0);
    $("#colour_id_filter").val(0);
    $("#size_id_filter").val(0);
    $("#uqc_id_filter").val(0);

    $("#hidden_page").val(1);

    resettable('product_data','productrecord');
}
function resetproductdata()
{
    $("#productform").trigger('reset');
    $("#product_id").val('');

    $("#addproduct").text('Add Product');
    resettable('product_data','productrecord');
}
function view_existing_product(barcode)
{
    $("#barcode_filter").val(barcode);
    product_filter();

}
$(document).on('click', '#product_export', function(){

    var product_name = $('#product_name_filter').val();
    var barcode = $('#barcode_filter').val();
    var brand_id = $('#brand_id_filter').val();
    var category_id = $('#category_id_filter').val();
    var subcategory_id = $('#subcategory_id_filter').val();
    var colour_id = $('#colour_id_filter').val();
    var size_id = $('#size_id_filter').val();
    var uqc_id = $('#uqc_id_filter').val();

    var query = {
        product_name :product_name,
        barcode : barcode,
        brand_id : brand_id,
        category_id : category_id,
        subcategory_id : subcategory_id,
        colour_id : colour_id,
        size_id : size_id,
        uqc_id : uqc_id,
    };
    var url = "product_export?" + $.param(query)
    window.open(url,'_blank');
});


