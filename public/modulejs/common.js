$("input").attr("autocomplete", "off");


function validateNumber(evt) {
    var e = evt || window.event;
    var key = e.keyCode || e.which;

    if (!e.shiftKey && !e.altKey && !e.ctrlKey &&
        // numbers
        key >= 48 && key <= 57 ||
        // Numeric keypad
        key >= 96 && key <= 105 ||
        // Backspace and Tab and Enter
        key == 8 || key == 9 || key == 13 ||
        // Home and End
        key == 35 || key == 36 ||
        // left and right arrows
        key == 37 || key == 39 ||
        // Del and Ins
        key == 46 || key == 45) {
        // input is VALID
    }
    else {
        // input is INVALID
        e.returnValue = false;
        if (e.preventDefault) e.preventDefault();
    }
}

$("input[class='employee_login_']").click(function(e)
{
    if($("input[class='employee_login_']").prop('checked') == true)
    {
        $('.loginYesNo').show();
        $('.loginData').show();
    }
    else
    {
        $('.loginYesNo').hide();
        $('.loginData').hide();
    }
});

function removePicture_(user_id)
{
    var fetch_data_url  =   'removePicture';
    $('.loaderContainer').show();
    $.ajax({
        url:fetch_data_url,
        data: {
            user_id:user_id,
        },
        success:function(data)
        {
            var searchdata = JSON.parse(data, true);
            $('.loaderContainer').hide();
            // console.log(searchdata);

            toastr.success(searchdata['Message']);

            if(searchdata['picture']=='empty')
            {
                $('.employeePicture').html('Add Profile Picture<br><input type="file" class="" id="employee_picture" name="employee_picture" accept=".jpeg, .jpg, .png, .gif" autocomplete="off"><small>Max Image Size: <b>2mb.</b><br>Accepted Formats: <b>.jpeg, .jpg, .png, .gif</b></small><input type="hidden" name="chkPicture" id="chkPicture" value="" />');
            }
            
        }
    })
}


$('#my_employee_form').on('submit', function(event)
{
    event.preventDefault();
  
    var error = 0;
    if($('#employee_firstname').val()=='')
    {
        error = 1;
        toastr.error("employee first name is required");
        $('#employee_firstname').focus();
        return false;
    }
    
    if($('#employee_lastname').val()=='')
    {
        error = 1;
        toastr.error("employee last name is required");
        $('#employee_lastname').focus();
        return false;
    }
    
    if($('#email').val()=='')
    {
        error = 1;
        toastr.error("email address is required");
        $('#email').focus();
        return false;
    }
    else
    {
        if(!validateEmail($('#email').val()))
        {
            error = 1;
            toastr.error("invalid email address");
            return false;
        }
    }
    
    if($('#employee_mobileno').val()=='')
    {
        error = 1;
        toastr.error("Mobile No. is required");
        $('#employee_mobileno').focus();
        return false;
    }

    if($('#employee_joiningdate').val()=='')
    {
        error = 1;
        toastr.error("joining date is required");
        $('#employee_joiningdate').focus();
        return false;
    }
    else
    {
        if(!validate_date_format($('#employee_joiningdate').val()))
        {
            error = 1;
            toastr.error("invalid date format");
            return false;
        }
    }
    
    if($('#user_id_').val()=='')
    {
        if($("input[name='employee_login']").prop('checked') == true)
        {
            if($('#password').val()=='')
            {
                error = 1;
                toastr.error("password is required");
                $('#password').focus();
                return false;
            }   

            if($('#encrypt_password').val()=='')
            {
                error = 1;
                toastr.error("confirm password is required");
                $('#encrypt_password').focus();
                return false;
            }

            if($('#password').val()!=$('#encrypt_password').val())
            {
                error = 1;
                toastr.error("password not matched");
                $('#password').focus();
                return false;
            }

            if($('#employee_role_id_').val()=='')
            {
                error = 1;
                toastr.error("Permission is required");
                $('#password').focus();
                return false;
            }
        }
    }
    else if($('#user_id_').val()!='')
    {
       if($("input[name='employee_login_']").prop('checked') == true)
        {
            if($('#password').val()=='')
            {
                error = 1;
                toastr.error("password is required");
                $('#password').focus();
                return false;
            }   

            if($('#encrypt_password').val()=='')
            {
                error = 1;
                toastr.error("confirm password is required");
                $('#encrypt_password').focus();
                return false;
            }

            if($('#password').val()!=$('#encrypt_password').val())
            {
                error = 1;
                toastr.error("password not matched");
                $('#password').focus();
                return false;
            }
        } 
    }

    if(error == 1)
    {
        return false;
    }
    else
    {
            var mobno               =   $('.mobno .selected-dial-code').html();
            var altmobno            =   $('.altmobno .selected-dial-code').html();
            var fammobno            =   $('.fammobno .selected-dial-code').html();

            var mobno_country       =   $('.mobno .selected-dial-code').siblings('div').attr('class').split('iti-flag ')[1];
            var altmobno_country    =   $('.altmobno .selected-dial-code').siblings('div').attr('class').split('iti-flag ')[1];
            var fammobno_country    =   $('.fammobno .selected-dial-code').siblings('div').attr('class').split('iti-flag ')[1];

            $("#employee_mobileno_dial_code").val(mobno +','+ mobno_country);
            $("#employee_alternate_mobile_dial_code").val(altmobno +','+ altmobno_country);
            $("#employee_family_member_mobile_dial_code").val(fammobno +','+ fammobno_country);

            $.ajaxSetup({
                headers : { "X-CSRF-TOKEN" :jQuery(`meta[name="csrf-token"]`). attr("content")}
            });

            $.ajax({
                url: "employee_form_create",
                method: "POST",
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data)
                {
          
                     if(data['Success'] == "True")
                     {
                        toastr.success(data['Message']);
                        $("#company_profile_id").val(data['company_profile_id']);
                        if(data['url']!='')
                        {
                           window.location = 'my_profile';
                        }

                     }
                     else
                     {
                        if(data['status_code'] == 409)
                        {
                           $.each(data['Message'],function (errkey,errval)
                           {
                              var errmessage = errval;
                              toastr.error(errmessage);
                           });
                        }
                        else
                        {
                           toastr.error(data['Message']);
                        }
                     }
                }

            });
    }

});

$('.eyePassword').click( function(e){

    // password field
    var x = document.getElementById("password");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }

    // confirm password field
    var y = document.getElementById("encrypt_password");
    if (y.type === "password") {
        y.type = "text";
    } else {
        y.type = "password";
    }

    $('.eyePassword').css('opacity','1');   // change opacity

});

$('.close').click(function(e)
{
    var alertStatus     =   $('.alertStatus').val();
    if(Number(alertStatus)==1)
    {
        jQuery.toast({
            heading: '<b>Unsaved Data!</b>',
            text: '<p>It looks like you have been editing something - if you leave before saving, then your changes will be lost.<br><br><b>Do you want to close?</b></p><button class="btn btn-secondary btn-sm mt-10 mr-10" onclick="ignoreSaving()" data-dismiss="modal" aria-hidden="true">Yes</button><button class="btn btn-secondary btn-sm mt-10 mr-10" onclick="closeAlert()" data-dismiss="modal" aria-hidden="true">No</button>',
            position: 'top-right',
            loaderBg:'#7a5449',
            class: 'jq-toast-danger',
            hideAfter: 11155500, 
            stack: 6,
            showHideTransition: 'fade'
        });
        return false;
    }
});

function closeAlert()
{
   $('.jq-toast-wrap').remove(); 
}

function ignoreSaving()
{
    $('.alertStatus').val('0');
    $('.jq-toast-wrap').remove();
    $('.close').click();
}

$('form input').keyup(function() { 
    $('.alertStatus').val('1');
});

$('.number').keypress(function (event)
{
    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
});

$(".onlyinteger").keypress(function (evt)
{
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
});

//allow only integer and one point in td editable
function testCharacter(event) {

    if ((event.which != 46 || $(event.target).text().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }

}
//pan card validation regax

function pan_card_validate(panVal)
{
    var regpan = /^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/;
    if(!regpan.test(panVal)){
       return 0;
    } else {
        return 1;
    }
}


function callroute(url,type,data,callback)
{
    var csrf_token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        type: type,
        url: url,
        processData: false,
        data: JSON.stringify(data),
        contentType: 'application/json; charset=UTF8',
        beforeSend: function(xhr)
        {
            xhr.setRequestHeader("X-CSRF-TOKEN",csrf_token);
        },
        success: function(server_response)
        {
            callback(server_response);
        },
        error: function(server_error)
        {

            callback(server_error);
        }
    });
}


function validateEmail(emailField){
    var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

    if (reg.test(emailField) == false)
    {
        return 0;
    }

    return 1;

}
function validate_date_format(dateval)
{

    var dateReg = /^\d{2}[./-]\d{2}[./-]\d{4}$/;


    if(dateReg.test(dateval) == false)
    {
        return 0;
    }
    return 1;
}

//CHECK SEPRATE DAY,MONTH,YEAR VALIDATION

function separtate_date_format_validation(d,m,y,datetype)
{
    //datetype = mfg and expiry for display message

    //current year
    var currentYear = (new Date).getFullYear();

    var dateerror = 0;
    if(d == null || d == "" || !$.isNumeric(d))
    {
        toastr.error(datetype +" days must be numeric! ");
        dateerror = 1;
    }
    if(m == null || m == "" || !$.isNumeric(m))
    {
        toastr.error(datetype +" month must be numeric! ");
        dateerror = 1;
    }
    if(y == null || y == "" || !$.isNumeric(y))
    {
        toastr.error(datetype +" year must be numeric! ");
        dateerror = 1;
    }

    if(d > 31)
    {
        toastr.error(datetype +" day can not be greater than 31.");
        dateerror = 1;
    }

    if(m > 12)
    {
        toastr.error(datetype +" month can not be greater than 12.");
        dateerror = 1;
    }

    if(y.length < 4 || y.length > 4)
    {
        toastr.error(datetype +" year must be in 4 digit.");
        dateerror = 1;
    }
   /* if(y > currentYear)
    {
        toastr.error(datetype +" year can not be greater than "+currentYear+".");
        dateerror = 1;
    }*/

    if ((m == 4 || m == 6 || m == 9 || m == 11) && d == 31)
    {
        toastr.error(datetype +" selected month contains only 30 days.");
        dateerror = 1;
    }
    if (m == 2 && d > 29 && (y % 4 == 0))
    {
        toastr.error(datetype +" selected month contains only 29 days.");
        dateerror = 1;
    }

    if ((m == 2) && d > 28)
    {
        toastr.error(datetype +" selected month contains only 28 days.");
        dateerror = 1;
    }

    if(dateerror == 1)
    {
        return 0;
    }else
    {
        return 1;
    }
}


$(".mobileregax").keydown(function(event) {
    k = event.which;

    if ((k >= 96 && k <= 105) || (k == 8) || (k == 9) || (k >= 48 && k <= 57))
    {
        if ($(this).val().length == 12)
            {
            if (k == 8 || k== 9)
            {
                return true;
            } else {
                event.preventDefault();
                return false;

            }
        }
    } else {
        event.preventDefault();
        return false;
    }

});


//pujita mam code

$( document ).ready(function()
{
    var $table = $('.scrollable_table'),
        $bodyCells = $table.find('tbody tr:first').children(),
        colWidth;

    // Get the tbody columns width array
    colWidth = $bodyCells.map(function() {
        return $(this).width();
    }).get();

    // Set the width of thead columns
    $table.find('thead tr').children().each(function(i, v) {
        $(v).width(colWidth[i]);
    });



});
$(window).resize(function() {
    var $table = $('.scrollable_table'),
        $bodyCells = $table.find('tbody tr:first').children(),
        colWidth;

    // Get the tbody columns width array
    colWidth = $bodyCells.map(function() {
        return $(this).width();
    }).get();

    // Set the width of thead columns
    $table.find('thead tr').children().each(function(i, v) {
        $(v).width(colWidth[i]);
    });
}).resize();
/*$('.view-bill-screen').DataTable( {
    paging: true,
    searching:false
} );*/
//end of pujita mam code


//for reset table after insert/update/delete activity
function resettable(url,id='')
{
    $.ajax({
        url:url,
        success:function(data)
        {
            //$('tbody').html('');
            $("#"+id).html(data);
        }
    })
}
//end of reset table

//code for sorting and search value in table
function clear_icon()
{
    $('#id_icon').html('');
    $('#post_title_icon').html('');
}
function fetch_data(fetch_data_url,page, sort_type, sort_by, query,id='')
{
    $.ajax({
        url:fetch_data_url,

        data: {
            page: page,
            sortby: sort_by,
            sorttype: sort_type,
            query: query
        },
        success:function(data)
        {
           // $('tbody').html('');
            $('.loaderContainer').hide();
            if(id != '') {
                $("#"+id).html(data);
                $('.loaderContainer').hide();
            }else
            {
                $('tbody').html();
            }

        }
    })
}


$('.search_data').click(function(e){
    e.preventDefault();
    var page            =     $('#hidden_page').val();
    var column_name     =     $('#hidden_column_name').val();
    var sort_type       =     $('#hidden_sort_type').val();


    var query = {};
    $(".common-search").find('input,select,hidden').each(function ()
    {
       if($(this).attr('name-attr') != undefined)
       {
           var name_attr = $(this).attr('name-attr');

           if(name_attr == "from_to_date")
           {
               query['from_date'] = '';
               query['to_date'] = '';
               var separate_date = $(this).val().split(' - ');

               if(separate_date[0] != undefined)
               {
                   query['from_date'] = separate_date[0];
               }

               if(separate_date[1] != undefined)
               {
                   query['to_date'] = separate_date[1];
               }
           }
           else
           {

               query[name_attr] = $(this).val();
           }

         }
    });

    var fetch_data_url = $('#fetch_data_url').val();

    $('li').removeClass('active');
    // $(this).parent().addClass('active');    
    var tbodyid = $('html').find('.table-responsive').attr('id');

    $('.loaderContainer').show();
    fetch_data(fetch_data_url,page, sort_type, column_name, query,tbodyid);

});
$(document).on('keyup', '#serach', function()
{
    var query = {};
    query['serach'] = $('#serach').val();
    var column_name = $('#hidden_column_name').val();
    var sort_type = $('#hidden_sort_type').val();
    var page = $('#hidden_page').val();
    var fetch_data_url = $('#fetch_data_url').val();
    var tbodyid = $(".table-responsive").attr('id');

    fetch_data(fetch_data_url,page, sort_type, column_name, query,tbodyid);
});

$(document).on('click', '.sorting', function(){
    var column_name = $(this).data('column_name');
    var order_type = $(this).data('sorting_type');
    var reverse_order = '';
    if(order_type == 'asc')
    {
        $(this).data('sorting_type', 'desc');
        reverse_order = 'desc';
       // clear_icon();
        $('#'+column_name+'_icon').html('<span class="glyphicon glyphicon-triangle-bottom"></span>');
    }
    if(order_type == 'desc')
    {
        $(this).data('sorting_type', 'asc');
        reverse_order = 'asc';
      //  clear_icon
        $('#'+column_name+'_icon').html('<span class="glyphicon glyphicon-triangle-top"></span>');
    }
    $('#hidden_column_name').val(column_name);
    $('#hidden_sort_type').val(reverse_order);
    var page = $('#hidden_page').val();
    var query = $('#serach').val();
    var fetch_data_url = $('#fetch_data_url').val();
    fetch_data(fetch_data_url,page, reverse_order, column_name, query);
});

$(document).on('click', '.pagination a', function(event)
{
    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    $('#hidden_page').val(page);
    var column_name = $('#hidden_column_name').val();
    var sort_type = $('#hidden_sort_type').val();

    var query = {};
    $(".common-search").find('input,select,hidden').each(function ()
    {
       if($(this).attr('name-attr') != undefined)
       {
           var name_attr = $(this).attr('name-attr');

           if(name_attr == "from_to_date")
           {
               query['from_date'] = '';
               query['to_date'] = '';
               var separate_date = $(this).val().split(' - ');

               if(separate_date[0] != undefined)
               {
                   query['from_date'] = separate_date[0];
               }

               if(separate_date[1] != undefined)
               {
                   query['to_date'] = separate_date[1];
               }
           }
           else
           {
               query[name_attr] = $(this).val();
           }
       }
    });



    var fetch_data_url = $('#fetch_data_url').val();

    $('li').removeClass('active');
    $(this).parent().addClass('active');
    var tbodyid = $(this).closest('table').parent('.table-responsive').attr('id');
    $('.loaderContainer').show();
    fetch_data(fetch_data_url,page, sort_type, column_name, query,tbodyid);
});
//end of code for sorting and search value in table




//for open product form as popup
function open_product_popup()
{
    var html = '<section id="product_block" style="display: block;"> <h5 class="hk-sec-title">Add New Product</h5> <div class="row" > <form name="productform" id="productform"> <input type="hidden" name="product_id" id="product_id" value=""> <input type="hidden" name="type" id="type" value=""> <div class="col-sm-12"> <div class="hk-row"> <div class="col-md-3"> <div class="card"> <div class="card-body greybg"> <div class="row"> <div class="col-md-12"> <label class="form-label">Product Name</label> <input class="form-control form-inputtext" value="" name="product_name" id="product_name" type="text" placeholder=" "> </div></div></div></div></div><div class="col-md-9"> @include(\'product/product_calculation\') </div></div></div><div class="col-sm-12"> <div class="hk-row"> <div class="col-md-12"> <div class="card"> <div class="card-body greybg"> <div class="card-header"> <h5>Optional</h5> </div><div class="row"> <div class="col-md-3"> <label class="form-label">SKU Code</label> <input class="form-control form-inputtext" value="" maxlength="" autocomplete="off" type="text" name="sku_code" id="sku_code" placeholder=" "> </div><div class="col-md-3"> <label class="form-label">Product Code</label> <input class="form-control form-inputtext" value="" maxlength="" autocomplete="off" type="text" name="product_code" id="product_code" placeholder=" "> </div><div class="col-md-3"> <label class="form-label">Product Description</label> <input class="form-control form-inputtext" value="" maxlength="" autocomplete="off" type="text" name="product_description" id="product_description" placeholder=" "> </div><div class="col-md-3"> <label class="form-label">HSN</label> <input class="form-control form-inputtext number" value="" maxlength="" autocomplete="off" type="text" name="hsn_sac_code" id="hsn_sac_code" placeholder=" "> </div><div class="col-md-2"> <label class="form-label">Brand</label> <select class="form-control form-inputtext" name="brand_id" id="brand_id"> </select> </div><div class="col-md-1"> <button type="button" class="btn btn-info addmoreoption" id="addbrand"><i class="fa fa-plus"></i></button> </div><div class="col-md-2"> <label class="form-label">Category</label> <select class="form-control form-inputtext" onchange="getsubcategory()" name="category_id" id="category_id"> </select> </div><div class="col-md-1"> <button type="button" class="btn btn-info addmoreoption" id="addcategory" name="addcategory"><i class="fa fa-plus"></i></button> </div><div class="col-md-2"> <label class="form-label">Sub Category</label> <select class="form-control form-inputtext" name="subcategory_id" id="subcategory_id"> </select> </div><div class="col-md-1"> <button type="button" class="btn btn-info addmoreoption" id="addsubcategory" name="addsubcategory"><i class="fa fa-plus"></i></button> </div><div class="col-md-2"> <label class="form-label">Colour</label> <select class="form-control form-inputtext" name="colour_id" id="colour_id"> </select> </div><div class="col-md-1"> <button type="button" class="btn btn-info addmoreoption" id="addcolour" name="addcolour"><i class="fa fa-plus"></i></button> </div><div class="col-md-2"> <label class="form-label">Size</label> <select class="form-control form-inputtext" name="size_id" id="size_id"> </select> </div><div class="col-md-1"> <button type="button" class="btn btn-info addmoreoption" id="addsize" name="addsize"><i class="fa fa-plus"></i></button> </div><div class="col-md-2"> <label class="form-label">UQC</label> <select class="form-control form-inputtext" name="uqc_id" id="uqc_id"> </select> </div><div class="col-md-1"> <button type="button" class="btn btn-info addmoreoption" id="adduqc" name="adduqc"><i class="fa fa-plus"></i></button> </div><div class="col-md-3"> <label class="form-label">Material</label> <input class="form-control form-inputtext" value="" maxlength="10" autocomplete="off" type="text" name="material_id" id="material_id" placeholder=" "> </div><div class="col-md-3"> <label class="form-label">Days Before Product Expiry</label> <input class="form-control form-inputtext number" value="" maxlength="10" autocomplete="off" type="text" name="days_before_product_expiry" id="days_before_product_expiry" placeholder=" "> </div><div class="col-md-3"> <label class="form-label">Product System Barcode</label> <input class="form-control form-inputtext notallowinput" value="{{$system_barcode_final}}" maxlength="10" autocomplete="off" type="text" name="product_system_barcode" id="product_system_barcode" placeholder=" "> </div><div class="col-md-3"> <label class="form-label">Supplier Barcode</label> <input class="form-control form-inputtext" value=" " autocomplete="off" type="text" name="supplier_barcode" id="supplier_barcode" placeholder=" "> </div><div class="col-md-3"> <label class="form-label">Is Ean?</label> <label class="form-label"> <input type="radio" name="is_ean" checked id="iseanyes" value="1">Yes</label> <label class="form-label"><input type="radio" name="is_ean" id="iseanno" value="0">No</label> </div><div class="col-md-3"> <label class="form-label">Product Ean Barcode</label> <input class="form-control form-inputtext" value=" " maxlength="10" autocomplete="off" type="text" name="product_ean_barcode" id="product_ean_barcode" placeholder=" "> </div><div class="col-md-12"> <div class="row" id="imageblock"> <div class="col-md-3" id="block_1"> <div class="form-group"> <input type="file" onchange="previewandvalidation(this);" data-counter="1" accept=".png, .jpg, .jpeg" name="product_image_1" id="product_image_1" class="form-control form-inputtext productimage"> <div id="preview_1" style="display: none"> <a onclick="removeimgsrc(\'1\');" class="displayright"><i class="fa fa-remove" style="font-size: 20px;"></i></a> <img src="" id="product_preview_1" width="100%" height="200px"> </div></div></div><button type="button" style="height: 38px;" class="btn btn-info" id="addmoreimg" name="addmoreimg"><i class="fa fa-plus"></i></button> </div></div></div></div></div></div></div></div></form> </div><div class="row"> <div class="col-sm-12"> <div class="hk-row"> <div class="col-md-3"> <div class="card"> <div class="card-body greybg"> <button type="button" name="addproduct" class="btn btn-info" id="addproduct" data-container="body" data-toggle="popover" data-placement="bottom" data-content="">Add Product</button> <button type="button" name="resetproduct" onclick="resetproductdata();" class="btn btn-light resetbtn" id="resetproduct" data-container="body" data-toggle="popover" data-placement="bottom" data-content="">Reset</button> </div></div></div></div></div></div></section>';
}


//this is used for display batch no suggestion
$("#batch_no_filter").keyup(function ()
{
    jQuery.noConflict();
    $("#batch_no_filter").autocomplete({
        autoFocus: true,
        minLength: 1,
        source: function (request, response)
        {
            var url = "batch_no_search";
            var type = "POST";
            var data = {
                'search_val': $("#batch_no_filter").val()
            };
            callroute(url, type, data, function (data)
            {
                var searchbatchno = JSON.parse(data, true);

                if (searchbatchno['Success'] == "True")
                {
                    var batch_no = searchbatchno['Data'];

                    if (batch_no.length > 0)
                    {
                        var resultbatch_no = [];

                        batch_no.forEach(function (value)
                        {

                            resultbatch_no.push({
                                label: value.batch_no,
                                value: value.batch_no
                            });
                        });
                        //push data into result array.and this array used for display suggetion
                        response(resultbatch_no);
                    }
                }
            });
        }, //this help to call a function when select search suggetion
        select: function (event, ui)
        {
            $(".ui-helper-hidden-accessible").css('display','none');
            //call a function to perform action on select of supplier
        }
    })
});

//this is used for display product name suggestion
$("#product_name_filter").keyup(function ()
{
    jQuery.noConflict();
    $("#product_name_filter").autocomplete({
        autoFocus: true,
        minLength: 1,
        source: function (request, response)
        {
            var url = "product_name_search";
            var type = "POST";
            var data = {
                'search_val': $("#product_name_filter").val()
            };
            callroute(url,type,data,function (data)
            {
                var search_product_name = JSON.parse(data, true);
                if (search_product_name['Success'] == "True")
                {
                    var product_name = search_product_name['Data'];
                    if (product_name.length > 0)
                    {
                        var resultproduct_name = [];
                        product_name.forEach(function (value)
                        {
                            resultproduct_name.push({
                                label: value.product_name,
                                value: value.product_name
                            });
                        });
                        //push data into result array.and this array used for display suggetion
                        response(resultproduct_name);
                    }
                }
            });
        }, //this help to call a function when select search suggetion
        select: function (event, ui)
        {
            $(".ui-helper-hidden-accessible").css('display','none');
            //call a function to perform action on select of supplier
        }
    })
});



$("#filterarea").change(function () {
    if($(this).is(':checked'))
    {
       $("#filterarea_block").show();
    }
    else
    {
        $("#filterarea_block").hide();
    }
});



//This is used for display BARCODE suggestion
$("#barcode_filter").keyup(function ()
{
    jQuery.noConflict();
    $(this).autocomplete({
        autoFocus: true,
        minLength: 1,
        source: function (request, response)
        {
            var url = "product_barcode_search";
            var type = "POST";
            var data = {
                'search_val': $("#barcode_filter").val()
            };
            callroute(url, type, data, function (data)
            {
                var search_barcode = JSON.parse(data, true);

                if (search_barcode['Success'] == "True")
                {
                    var barcode = search_barcode['Data'];

                    if (barcode.length > 0)
                    {
                        var resultbarcode = [];

                        barcode.forEach(function (value)
                        {

                            var barcode = '';
                            if(value['supplier_barcode'] != '' && value['supplier_barcode'] != null)
                            {
                                barcode = value['supplier_barcode'];
                            }
                            else
                            {
                                barcode = value['product_system_barcode'];
                            }

                            resultbarcode.push({
                                label: barcode,
                                value: barcode
                            });
                        });
                        //push data into result array.and this array used for display suggetion
                        response(resultbarcode);
                    }
                }
            });
        }, //this help to call a function when select search suggetion
        select: function (event, ui)
        {
            $(".ui-helper-hidden-accessible").css('display','none');
            //call a function to perform action on select of supplier
        }
    })
});

//This is used for display Invoice No suggestion

$("#invoice_no_filter").keyup(function ()
{
    jQuery.noConflict();

    $(this).autocomplete({
        autoFocus: true,
        minLength: 1,
        source: function (request, response)
        {
            var url = "invoice_number_search";
            var type = "POST";
            var data = {
                'search_val' : $("#invoice_no_filter").val()
            };
            callroute(url,type,data,function (data)
            {
                var searchdata = JSON.parse(data,true);

                if(searchdata['Success'] == "True")
                {
                    var result = [];
                    searchdata['Data'].forEach(function (value)
                    {
                        result.push({
                            label: value.invoice_no,
                            value: value.invoice_no
                        });
                    });
                    response(result);
                }
            });
        },
        //this help to call a function when select search suggetion
        select: function(event,ui)
        {
            $(".ui-helper-hidden-accessible").css('display','none');
        }
    });
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



//FOR SOFTWARE CONFIGURATION MODEL

$(document).keydown(function(e)
{
    if(e.key == "Y" && e.ctrlKey)
    {

        $(".verify_form").show();
        $("#configuration_password").val('');
        $("#software_configuration_form").hide();

        $("#software_configuration_popup").modal('show');


        //verify password of software configuration
        $("#verify_password").click(function ()
        {
           if(verify_password_configuration("software_verification_form"))
           {
               $("#verify_password").prop('disabled',true);
               var data = {
                   "formdata": $("#software_verification_form").serialize(),
               };
               var url = "valid_technical_team";
               var type = "POST";
               callroute(url, type, data, function (data)
               {
                   $("#verify_password").prop('disabled',false);
                   var dta = JSON.parse(data);
                   if(dta['Success'] == "False")
                   {
                       toastr.success(dta['Message']);
                       $("#software_configuration_popup").modal('hide');
                   }
                   else
                   {
                       $(".verify_form").hide();
                       $("#software_configuration_form").show();
                   }
               })
           }
        });

        //function for validation of password
        function verify_password_configuration(frmid)
        {
            var error = 0;

            if($("#configuration_password").val() == '')
            {
                error = 1;
                toastr.error("Please Enter Password!");

            }

            if(error == 1)
            {
                return  false;
            }else{
                return true;
            }
        }


        //check decimal points validation
        $("#decimal_points").keyup(function () {
            if($(this).val() > 4)
            {
                toastr.error("Decimal points can not be greater than 4");
                $(this).val(0);
                return false;
            }
        })

        //To manage billing js file according to Tax type selected.
        $("input[name='tax_type']").change(function ()
        {
            if($(this).val()==1)
            {
                $("#billtype_with_gst"). prop('disabled', true);
                $("#billtype_batch_no"). prop('disabled', true);
                $('#billtype_without_gst').prop('checked',true);
                $('.vatdetails').show();
                $('.vatdetails').show();
            }
            else
            {
                $("#billtype_with_gst"). prop('disabled', false);
                $("#billtype_batch_no"). prop('disabled', false);
                $('#billtype_without_gst').prop('checked',true);
                $('.vatdetails').hide();
                $('.vatdetails').hide();
            }
        })

        $("#add_software_configuration").click(function ()
        {
            if(validate_software_configuration('software_configuration_form'))
            {
                $("#add_software_configuration").prop('disabled',true);
                var data = {
                    "formdata": $("#software_configuration_form").serialize(),
                };

                var url = "software_configuration_create";
                var type = "POST";
                callroute(url, type, data, function (data)
                {
                    console.log("HERE");
                    $("#add_software_configuration").prop('disabled',false);
                    $("#addcompanyprofile").prop('disabled', false);
                    var dta = JSON.parse(data);
                    if (dta['Success'] == "True")
                    {
                        toastr.success(dta['Message']);
                        $("#company_profile_id").val(dta['company_profile_id']);
                        $("#software_configuration_popup").modal('hide');
                        $("#company_profile_form :input").prop("disabled", false);
                        if(dta['url'] != '' && dta['url'] != 'undefined')
                        {
                            window.location.href = dta['url'];
                        }
                        location.reload();

                    } else {
                        if (dta['status_code'] == 409) {
                            $.each(dta['Message'], function (errkey, errval) {
                                var errmessage = errval;
                                toastr.error(errmessage);
                            });
                        } else {
                            toastr.error(dta['Message']);
                        }
                    }
                })
            }else
            {
                $("#add_software_configuration").prop('disabled',false);
                return false;
            }
        });


        function validate_software_configuration(frmid)
        {
            var error = 0;

            var tax_type = $("input[name='tax_type']:checked"). val();

            if(Number(tax_type)==1)
            {
                if($("#tax_title").val() == '')
                {
                    error = 1;
                    toastr.error("Enter Tax Title!");
                    $('#tax_title').focus();
                    return false;
                }
                else if($("#currency_title").val() == '')
                {
                    error = 1;
                    toastr.error("Enter Currency Title!");
                    $('#currency_title').focus();
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
    }
});


//end of software configuration model
//custom daterangePicker
//console.log($('body').find('.daterange').length);
if($('body').find('.daterange').length!=0)
{
 function updateLabel(start, end, label) {
        if (label === 'Custom Range') {
            $(".daterange").html(start.format('dd-mm-yyyy') + ' - ' + end.format('dd-mm-yyyy'));
        } else {
            $(".daterange").html(label);
        }
    }

    $(".daterange").daterangepicker({
        startDate: moment().startOf('day'),
        endDate: moment().endOf('day'),
        opens: "right",
        drops: "down",
        ranges: {
            'Today': [moment().startOf('day'), moment().endOf('day')],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, updateLabel);

    //Set the default value
    var datepicker = $(".daterange").data('daterangepicker');
    //console.log(datepicker);
    var initialSel = 'Today';   // Or something else
    if (initialSel && datepicker.ranges && datepicker.ranges[initialSel]) {
        var range = datepicker.ranges[initialSel];
        datepicker.chosenLabel = initialSel;
        datepicker.setStartDate(range[0]);
        datepicker.setEndDate(range[1]);
        updateLabel(datepicker.startDate, datepicker.endDate, datepicker.chosenLabel);
    } else {
        datepicker.chosenLabel = 'Today';
        updateLabel(datepicker.startDate, datepicker.endDate, datepicker.chosenLabel);
    }

    $(".daterange").val('');
}


//FOR DISPLAY DEPENDENT RECORD
/*$(".dependent_record").click(function () {*/
function dependent_record(obj)
{
//Sr No.   //IP   //Module Name  //Detail //Created time  //Updated Time
    var id = $(obj).attr('data-id');
    var url = $(obj).attr('data-url');
    var type = "POST";
    var data = {
        'id' : id,
    };
    callroute(url,type,data,function (data)
    {
            var dta = JSON.parse(data);

            if(dta['Success'] == "True")
            {
                $("#view_dependent_record").html('');
                var dependent_record = dta['Data'];
                var dependent_html = '';

                if(dependent_record.length > 0) {
                    $.each(dependent_record, function (key, value) {
                        var tblclass = 'odd';
                        if (key % 2 == 0) {
                            tblclass = 'even';
                        }

                        key++;

                        var value_detail = '';
                        $.each(value['detail'], function (k, v) {
                            value_detail += "" + k + "" + " :: " + v + "<br/>"
                        });

                        dependent_html += '<tr class=' + tblclass + ' style="border-bottom:1px solid #C0C0C0 !important;">' +
                            '<td style="font-size:14px !important;text-align: left">' + key + '</td>' +
                            '<td style="font-size:14px !important;text-align: left">' + value['Module_Name'] + '</td>' +
                            '<td style="font-size:14px !important;text-align: left">' + value_detail + '</td>' +
                            /*'<td style="font-size:14px !important;text-align: left">'+value['created_at']+'<br/>'+value['updated_at']+'</td>' +*/
                            '</tr>';
                    });
                }else
                {
                    dependent_html = '<tr><td colspan="3">no dependent record found...</td></tr>';
                }

                $("#view_dependent_record").append(dependent_html);

                $("#dependency_popup").modal('show');
            }
            else
            {
                return false;
            }
    });

}


//END OF DISPLAY DEPENDENT RECORD