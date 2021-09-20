$("#gstplus").click(function ()
{
    $("#gstplus").prop('disabled',true);
    var id = $(this).attr('data-id');
     id++;
    $(this).attr('data-id',id);


    var html = '';
    var state_block = '';
    var tratment_block = '';

    $.each(state,function (key,value)
    {
        state_block += '<option value="'+value['state_id']+'">'+value['state_name']+'</option>';
    });

    $.each(supplier_treatments,function(treatment_key,treatment_value)
    {
        tratment_block += '<option value="'+treatment_value['supplier_treatment_id']+'">'+treatment_value['supplier_treatment_name']+'</option>';
    });

    html += '<div class="row"  id="new_gst_'+id+'">' +
        '<div class="col-md-1">' +
        '<label class="form-label">Treatment</label>' +

        '<select id="supplier_treatment_id" name="supplier_treatment_id" class="form-control form-inputtext" >'+tratment_block+'</select>' +
        '</div>' +

        '<div class="col-md-2 suppliergst"> <label class="form-label">GSTIN</label>' +
        '<input type="text" maxlength="100" onkeyup="getstate(this);" name="supplier_gstin" id="supplier_gstin" value="" class="form-control form-inputtext invalid" placeholder="">' +
        '</div>' +

        '<div class="col-md-1"> <label class="form-label">State</label>' +
        '<select name="state_id" id="state_id" class="form-control form-inputtext"> <option value="">Select State</option>'+state_block+'</select>' +
        '</div>' +
        '<div class="col-md-2"> ' +
        '<label class="form-label">Address</label> ' +
        '<input type="text" maxlength="20" name="supplier_address" id="supplier_address" value="" class="form-control form-inputtext" placeholder=""> </div>' +
        '<div class="col-md-2"> <label class="form-label">Area</label> <input type="text" maxlength="20" name="supplier_area" id="supplier_area" value="" class="form-control form-inputtext" placeholder=""> </div>' +
        '<div class="col-md-1"> <label class="form-label">Zipcode</label>' +
        '<input type="text" maxlength="20" name="supplier_gst_zipcode" id="supplier_gst_zipcode" value="" class="form-control form-inputtext" placeholder="">' +
        '</div>' +
        '<div class="col-md-2"> <label class="form-label">City</label>' +
        '<input type="text" maxlength="20" name="supplier_gst_city" id="supplier_gst_city" value="" class="form-control form-inputtext" placeholder="">' +
        ' </div><div class="col-md-1"><label></label><a id="remove_gst_'+id+'" onclick="remove_gst_row('+id+');" data-id='+id+'>' +
        '<i class="fa fa-remove"></i> </a></div> ' +
        '</div>';

    $("#repleat_gst").append(html);
    $("#gstplus").prop('disabled',false);
});

function remove_gst_row(removeid)
{
   $("#new_gst_"+removeid).remove();
}


$("#bankplus").click(function ()
{
    $("#bankplus").prop('disabled',true);
    var id = $(this).attr('data-id');
    id++;
    $(this).attr('data-id',id);

    var html_bank = '';

    html_bank += '<div class="row" id="new_bank_'+id+'">' +
        '<div class="col-md-3"><label class="form-label">Bank Name</label>' +
        '<input type="text" maxlength="100" name="supplier_bank_name" id="supplier_bank_name" value="" class="form-control form-inputtext invalid" placeholder=""> </div>' +
        '<div class="col-md-3"> ' +
        '<label class="form-label">Bank Account Name</label>' +
        ' <input type="text" maxlength="100" name="supplier_bank_account_name" id="supplier_bank_account_name" value="" class="form-control form-inputtext invalid" placeholder="">' +
        '</div>' +
        '<div class="col-md-3"> <label class="form-label">Bank Account No.</label> ' +
        '<input type="text" maxlength="20" name="supplier_bank_account_no" id="supplier_bank_account_no" value="" class="form-control form-inputtext invalid" placeholder="">' +
        '</div>' +
        '<div class="col-md-2"> <label class="form-label">Bank IFSC Code</label>' +
        '<input type="text" maxlength="100" name="supplier_bank_ifsc_code" id="supplier_bank_ifsc_code" value="" class="form-control form-inputtext" placeholder=""> </div>' +
        '<div class="col-md-1"><label></label><a id="remove_bank_'+id+'" onclick="remove_bank_row('+id+');" data-id='+id+'>' +
        '<i class="fa fa-remove"></i> </a></div> ' +
        '</div>';

    $("#repeat_bank").append(html_bank);
    $("#bankplus").prop('disabled',false);
});

function remove_bank_row(removeid)
{
    $("#new_bank_"+removeid).remove();
}


$("#companymobileplus").click(function ()
{
    $("#companymobileplus").prop('disabled',true);
    var id = $(this).attr('data-id');
    id++;
    $(this).attr('data-id',id);

    var html_contactplus = '';

    html_contactplus += '<div class="col-md-11" id="mobile_'+id+'"> ' +
        '<label class="form-label">Mobile No.</label> ' +
        '<input style="width: 251px;" type="tel" autocomplete="off" name="supplier_company_mobile_no" id="supplier_company_mobile_no" value="" maxlength="15" class="form-control form-inputtext mobileregax number" placeholder="">' +
        ' <input type="hidden" name="supplier_company_dial_code" id="supplier_company_dial_code" value=""> ' +
        '<a id="remove_bank_'+id+'" onclick="remove_companymobile_row('+id+');" data-id='+id+'>' +
        '<i class="fa fa-remove"></i> ' +
        '</div></div>';

    $("#repeat_companymobile").append(html_contactplus);

    $("#mobile_"+id).find('.mobileregax').each(function ()
    {
        var idofinput = $(this).attr('id');

        var input = document.querySelector('#mobile_'+id+' #'+idofinput);

        window.intlTelInput(input, {
            initialCountry: "in",
            separateDialCode: true,
            autoPlaceholder: false,
            utilsScript: "{{URL::to('/')}}/public/build/js/utils.js",
        });
    });



    $("#companymobileplus").prop('disabled',false);
});

function remove_companymobile_row(removeid)
{
    $("#mobile_"+removeid).remove();
}


$("#contactplus").click(function ()
{
    $("#contactplus").prop('disabled',true);
    var id = $(this).attr('data-id');
    id++;
    $(this).attr('data-id',id);


    var html_contact = '';
    var saluation_block = '';


    $.each(salutation,function(saluation_key,saluation_value)
    {
        saluation_block += '<option value="'+saluation_value['salutation_id']+'">'+saluation_value['salutation_prefix']+'</option>';
    });

    html_contact += ' <div class="row" id="new_contact_'+id+'"> ' +
        '<div class="col-md-0"> <label class="form-label"></label> ' +
        '   <select id="salutation_id" name="salutation_id" class="form-control form-inputtext" >'+saluation_block+'</select> ' +
        '</div><div class="col-md-2"> <label class="form-label">First Name</label> ' +
        '<input type="text" maxlength="100" name="supplier_contact_firstname" id="supplier_contact_firstname" value="" class="form-control form-inputtext invalid" placeholder=""> </div>' +
        '<div class="col-md-1"> <label class="form-label">Last Name</label>' +
        ' <input type="text" maxlength="100" name="supplier_contact_lastname" id="supplier_contact_lastname" value="" class="form-control form-inputtext" placeholder=""> </div>' +
        '<div class="col-md-1"> <label class="form-label">Designation</label>' +
        ' <input type="text" maxlength="100" name="supplier_contact_designation" id="supplier_contact_designation" value="" class="form-control form-inputtext" placeholder=""> </div>' +
        '<div class="col-md-2"> <label class="form-label">Email Id</label> ' +
        '<input type="text" maxlength="100" name="supplier_contact_email_id" id="supplier_contact_email_id" value="" class="form-control form-inputtext invalid" placeholder=""> </div>' +
        ' <div class="col-md-1"> <label class="form-label">Date of Birth</label> <input type="text" maxlength="100" name="supplier_date_of_birth" id="supplier_date_of_birth" value="" class="form-control form-inputtext" placeholder=""> </div><div class="col-md-2"> ' +
        '<label class="form-label">Mobile No.</label>' +
        ' <input style="width: 251px;" type="text" maxlength="100" name="supplier_contact_mobile_no" id="supplier_contact_mobile_no" value="" class="form-control form-inputtext mobileregax invalid" placeholder=""> <input type="hidden" name="supplier_contact_dial_code" id="supplier_contact_dial_code" value=""> </div>' +
        '<div class="col-md-2"> <label class="form-label">Whatsapp No.</label> ' +
        '<input style="width: 251px;" type="text" maxlength="100" name="supplier_whatsapp_no" id="supplier_whatsapp_no" value="" class="form-control form-inputtext mobileregax" placeholder="">' +
        '<input type="hidden" name="supplier_whatsapp_dial_code" id="supplier_whatsapp_dial_code" value=""></div>' +
        '<input type="hidden" name="supplier_contact_details_id" id="supplier_contact_details_id" value="">'+
        '<div class="col-md-0"><label></label>' +
        '<a id="remove_contact_'+id+'" onclick="remove_contact_row('+id+');" data-id='+id+'><i class="fa fa-remove"></i></a></div> ' +
        '</div></div>';




    $("#repeat_contact").append(html_contact);

    $("#new_contact_"+id).find('.mobileregax').each(function ()
    {
        var idofinput = $(this).attr('id');

        var input = document.querySelector('#new_contact_'+id+' #'+idofinput);

        window.intlTelInput(input, {
            initialCountry: "in",
            separateDialCode: true,
            autoPlaceholder: false,
            utilsScript: "{{URL::to('/')}}/public/build/js/utils.js",
        });
    });
    $("#supplier_date_of_birth").datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        orientation: "bottom"
    });

    $("#contactplus").prop('disabled',false);
});

function remove_contact_row(removeid)
{
    $("#new_contact_"+removeid).remove();
}

$("#supplier_date_of_birth").datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    orientation: "bottom"
});


$("#addsupplier").click(function ()
{
    $("#addsupplier").prop('disabled',true);
   if(validate_supplier('supplier_form'))
   {
       //var supplier_company_info = [];
       var supplier_gst_info = [];
       var supplier_bank_info = [];
       var supplier_contact_info = [];
       var supplier_contact_dial_code = '';
       var supplier_company_mobile_no = '';
       $("#repeat_companymobile").find('input').each(function ()
       {
           var id = $(this).attr('id');
           var contact_dial_code = '';
           if(id == 'supplier_company_mobile_no' && $("#"+id).val() != '')
           {
               if($(this).val() != '')
               {
                   contact_dial_code = $(this).siblings('.flag-container').find('.selected-dial-code').html();
                   if (supplier_contact_dial_code == '')
                   {
                       supplier_contact_dial_code = contact_dial_code;
                   } else {
                       supplier_contact_dial_code = supplier_contact_dial_code + ',' + contact_dial_code;
                   }
                   if (supplier_company_mobile_no == '')
                   {
                       supplier_company_mobile_no = $(this).val();
                   }
                   else
                   {
                       supplier_company_mobile_no = supplier_company_mobile_no + ',' + $(this).val();
                   }
               }
           }
       });

       var supplier_company_info =
       {
       'supplier_company_name' :  $("#supplier_company_name").val(),
       'supplier_first_name': $("#supplier_first_name").val(),
       'supplier_last_name': $("#supplier_last_name").val(),
       'note': $("#supplier_note").val(),
       'supplier_company_address': $("#supplier_company_address").val(),
       'supplier_company_area': $("#supplier_company_area").val(),
       'supplier_company_zipcode': $("#supplier_company_zipcode").val(),
       'supplier_company_city': $("#supplier_company_city").val(),
       'state_id': $("#state_id").val(),
       'country_id': $("#country_id").val(),
       'supplier_pan_no': $("#supplier_pan_no").val(),
       'supplier_company_info_id': $("#supplier_company_info_id").val(),
       'supplier_company_dial_code': supplier_contact_dial_code,
       'supplier_company_mobile_no': supplier_company_mobile_no,
       'supplier_payment_due_days': $("#supplier_due_days").val(),
       'supplier_payment_due_date': $("#supplier_due_date").val(),
       };

        //supplier_company_info.push(supplier_company);


        //get repeated gst value
       $("#repleat_gst .row").each(function ()
       {
            var row_id = $(this).attr('id');
           var supplier_gst = {};
           $(this).find('input,select,hidden').each(function ()
           {
               var id = $(this).attr('id');
               var value_gst = '';
               if(id == 'state_id')
               {
                    value_gst = $('#'+row_id+' #state_id :selected').val();

                   supplier_gst[id] = value_gst;
               }
               else
               {
                    value_gst = $("#"+row_id+" #"+id).val();
                   supplier_gst[id] = value_gst;
               }
           });
           supplier_gst_info.push(supplier_gst);
           
       });

       //get repeated bank value
       $("#repeat_bank .row").each(function ()
       {
           var row_id = $(this).attr('id');
           var supplier_bank = {};
           $(this).find('input,select,hidden').each(function ()
           {
               var id = $(this).attr('id');
               var value_bank = $("#"+row_id+" #"+id).val();
               supplier_bank[id] = value_bank;
           });
           supplier_bank_info.push(supplier_bank);
       });

       //get repeated Contact value
       $("#repeat_contact .row").each(function ()
       {
           var row_id = $(this).attr('id');

           var supplier_contact = {};
           var contact_dial_code = '';
           var supplier_whatsapp_dial_code = '';
           $(this).find('input,select,hidden').each(function ()
           {
               var id = $(this).attr('id');
               if(id == 'supplier_contact_mobile_no')
               {
                    contact_dial_code = $("#"+row_id).find('#supplier_contact_mobile_no').siblings('.flag-container').find('.selected-dial-code').html();


                   supplier_contact['supplier_contact_dial_code'] = contact_dial_code;
               }
               if(id == 'supplier_whatsapp_no')
               {
                    supplier_whatsapp_dial_code = $("#"+row_id).find('#supplier_whatsapp_no').siblings('.flag-container').find('.selected-dial-code').html();
                  supplier_contact['supplier_whatsapp_dial_code'] = supplier_whatsapp_dial_code;
               }

               if(id != 'supplier_contact_dial_code' && id != 'supplier_whatsapp_dial_code')
               {
                   var value_contact = $("#"+row_id+" #"+id).val();
                   supplier_contact[id] = value_contact;
               }

           });
           supplier_contact_info.push(supplier_contact);
       });

       var  url = "add_supplier";
       var type = "POST";

       var data = {
           'supplier_company_info' : supplier_company_info,
           'supplier_gst_info' : supplier_gst_info,
           'supplier_bank_info' : supplier_bank_info,
           'supplier_contact_info' : supplier_contact_info,
       };

       callroute(url,type,data,function (data)
       {
           $("#addsupplier").prop('disabled', false);
           var dta = JSON.parse(data);

           if(dta['Success'] == "True")
           {
               resetsupplierdata();
               toastr.success(dta['Message']);
               $("#addsupplierpopup").modal('hide');
               resettable('supplier_data','supplierrecord');
                location.reload();
           }
           else
           {
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
       })

  }
   else
   {
       $("#addsupplier").prop('disabled',false);
   }
});

function validate_supplier(frmid)
{
    var error = 0;

    if($("#supplier_company_name").val() == '')
    {
        error = 1;
        toastr.error("Enter Company Name!");
        return false;
    }
    if($("#supplier_first_name").val() == '')
    {
        error = 1;
        toastr.error("Enter First Name!");
        return false;
    }
   /* if($("#supplier_pan_no").val() == '')
    {
        error = 1;
        toastr.error("Enter PAN No.!");
        return false;
    }*/
    if($("#supplier_pan_no").val() != '')
    {
        var pan_no = $("#supplier_pan_no").val();
        if(pan_card_validate(pan_no) == 0)
        {
            error = 1;
            toastr.error("Enter proper PAN No.!");
            return false;
        }
    }


    $("#repeat_bank .row").each(function(){

        var row_id = $(this).attr('id').split('new_bank_')[1];
        $(this).find('input').each(function ()
        {
            if($(this).hasClass('invalid') && $(this).val() == '')
            {
                var id = $(this).attr('id');
                var label = $("#"+id).siblings('label').html();

                error = 1;
                toastr.error(label +" can not be empty!");

            }

        });
    });

    $("#repleat_gst .row").each(function(){

        var row_id = $(this).attr('id').split('new_gst_')[1];
        $(this).find('input').each(function ()
        {
            if($(this).hasClass('invalid') && $(this).val() == '')
            {
                var id = $(this).attr('id');
                var label = $("#"+id).siblings('label').html();
                error = 1;
                toastr.error(label +" can not be empty!");
                return false;
            }

        });
    });


    $("#repeat_contact .row").each(function(){

        var row_id = $(this).attr('id').split('new_contact_')[1];
        $(this).find('input').each(function ()
        {
            var label = '';
            if($(this).hasClass('invalid') && $(this).val() == '')
            {
                var id = $(this).attr('id');
                if(id == 'supplier_contact_mobile_no')
                {
                     label = 'Mobile No';
                }
                else
                {
                     label = $("#"+id).siblings('label').html();
                }

                error = 1;
                toastr.error(label +" can not be empty!");
                return false;
            }
            if($(this).attr('id') == 'supplier_contact_email_id' && $(this).val() != '')
            {
                var email = $(this).val();
                if(validateEmail(email) == 0)
                {
                    error = 1;
                    toastr.error("Enter proper email id!");
                    return false;
                }
            }

        });
    });




    if(error == 1)
    {
        return false;
    }
    else
    {
        return true;
    }
}


//for tab
$('#nxt').on('click', function () {
    moveTab("Next");
});
$('#prv').on('click', function () {
    moveTab("Previous");
});


function moveTab(nextOrPrev) {

    if ($("div.tab-pane").hasClass('active'))
    {
       $("div.tab-pane").removeClass('active');
    }
    $("#"+nextOrPrev).addClass('active');
    //$("#"+nextOrPrev).addClass('active').parent().siblings('.tab-pane').removeClass('active');
    //$(this).closest('.nav').find('.active').removeClass('active').addClass('active');
   // $(this).addClass('active').removeClass('active');
    /* $('.nav-tabs li').each(function () {
         if ($(this).hasClass('active')) {
             currentTab = $(this);

         }
     });

     if (nextOrPrev == "Next")
     {
         console.log(currentTab.next().length);
         if (currentTab.next().length)
         {
             currentTab.removeClass('active');
             currentTab.next().addClass('active');}
         else {} // do nothing for now

     } else {


         if (currentTab.prev().length)
         {
             // currentTab.removeClass('active');
             currentTab.removeClass('active');
             currentTab.prev().addClass('active');
         }
         else {} //do nothing for now

     }*/
}
//end of tab



//based on gst select state
function getstate(obj)
{
    var divid = $(obj).parent().closest('div .row').attr('id');


    var gst_state_code = $("#"+divid).find('#supplier_gstin').val().substr(0,2);


    if(gst_state_code.length != 0)
    {

        if(gst_state_code.startsWith('0'))
        {
            gst_state_code = gst_state_code.substring(1);
        }
        $("#"+divid).find('#state_id').val(gst_state_code);
       // $("#"+divid).find('#state_id option[value='+gst_state_code+']').prop("selected", true);
        $('#'+divid+' #state_id option[value="'+gst_state_code+'"]').attr("selected", "selected");

        //$("#"+divid).find('#state_id').attr('disabled',true);
       // $("#"+divid).find('#state_id').css('color','black');


    }
    else
    {
        $("#"+divid).find('#state_id').removeAttr('disabled',false);
        $("#"+divid).find('#state_id').val('0');
    }
}

function editsupplier(supplier_company_id)
{
    $("#addsupplier").prop('disable',true);
    var url = "supplier_edit";
    var type = "POST";
    var data = {
        "supplier_company_info_id": supplier_company_id
    };
    callroute(url, type, data, function (data)
    {
        $("#addsupplier").prop('disable', false);
        var supplier_response = JSON.parse(data);

        if (supplier_response['Success'] == "True")
        {
            $("#addsupplierpopup").modal('show');
            var data = supplier_response['Data'];

            $("#supplier_company_info_id").val(data['supplier_company_info_id']);
            $("#supplier_company_name").val(data['supplier_company_name']);
            $("#supplier_first_name").val(data['supplier_first_name']);
            $("#supplier_last_name").val(data['supplier_last_name']);
            $("#supplier_pan_no").val(data['supplier_pan_no']);
            $("#supplier_company_address").val(data['supplier_company_address']);
            $("#supplier_company_area").val(data['supplier_company_area']);
            $("#supplier_company_zipcode").val(data['supplier_company_zipcode']);
            $("#supplier_company_city").val(data['supplier_company_city']);
            $("#state_id").val(data['state_id']);
            $("#country_id").val(data['country_id']);
            $("#supplier_note").val(data['note']);
            $("#supplier_due_days").val(data['supplier_payment_due_days']);
            $("#supplier_due_date").val(data['supplier_payment_due_date']);

            if(data['supplier_company_mobile_no'] != null)
            {
                if (data['supplier_company_mobile_no'].indexOf(',') != -1)
                {
                    var company_mob = data['supplier_company_mobile_no'].split(',');
                    var company_mob_dial_code = data['supplier_company_dial_code'].split(',');

                    $.each(company_mob, function (key, value)
                    {

                        if (key > 2) {
                            document.getElementById("companymobileplus").click(); //append multiple company mobile
                        }

                        key++;
                        $("#mobile_" + key + " #supplier_company_mobile_no").val(value);
                    });
                    var i = 1;
                    $.each(company_mob_dial_code, function (key, value)
                    {
                        $("#mobile_" + i).find(".flag-container").children('.selected-flag').children('.selected-dial-code').html(value);
                        i++;
                    });
                } else {
                    $("#supplier_company_mobile_no").val(data['supplier_company_mobile_no']);
                    if (data['supplier_company_dial_code'] != '') {
                        $("#supplier_company_mobile_no .selected-dial-code").html(data['supplier_company_dial_code']);
                    }
                }
            }

            //bank data
            if(data['supplier_bank'] != '' && data['supplier_bank'] != 'undefined')
            {
                var bank_data = data['supplier_bank'];

                $.each(bank_data,function (bank_key,bank_value)
                {
                    if(bank_key != 0)
                    {
                        document.getElementById("bankplus").click(); //append multiple bank
                    }
                     bank_key++;

                    $("#new_bank_"+bank_key+" #supplier_bank_name").val(bank_value['supplier_bank_name']);
                    $("#new_bank_"+bank_key+" #supplier_bank_account_name").val(bank_value['supplier_bank_account_name']);
                    $("#new_bank_"+bank_key+" #supplier_bank_account_no").val(bank_value['supplier_bank_account_no']);
                    $("#new_bank_"+bank_key+" #supplier_bank_ifsc_code").val(bank_value['supplier_bank_ifsc_code']);
                    $("#new_bank_"+bank_key+" #supplier_bank_id").val(bank_value['supplier_bank_id']);
                })
            }

            //GST Data

            if(data['supplier_gst'] != '' && data['supplier_gst'] != 'undefined')
            {
                var supplier_gst = data['supplier_gst'];

                $.each(supplier_gst,function (gst_key,gst_value)
                {
                    if(gst_key != 0)
                    {
                        document.getElementById("gstplus").click(); //append multiple gst
                    }
                    gst_key++;


                    $("#new_gst_"+gst_key+" #supplier_treatment_id").val(gst_value['supplier_treatment_id']);
                    $("#new_gst_"+gst_key+" #supplier_gstin").val(gst_value['supplier_gstin']);
                    $("#new_gst_"+gst_key+" #state_id").val(gst_value['state_id']);
                    $("#new_gst_"+gst_key+" #supplier_address").val(gst_value['supplier_address']);
                    $("#new_gst_"+gst_key+" #supplier_area").val(gst_value['supplier_area']);
                    $("#new_gst_"+gst_key+" #supplier_gst_zipcode").val(gst_value['supplier_gst_zipcode']);
                    $("#new_gst_"+gst_key+" #supplier_gst_city").val(gst_value['supplier_gst_city']);
                    $("#new_gst_"+gst_key+" #supplier_gst_id").val(gst_value['supplier_gst_id']);
                })
            }

            //supplier contact detail

            if(data['supplier_contact_detail'] != '' && data['supplier_contact_detail'] != 'undefined')
            {
                var supplier_contact_detail = data['supplier_contact_detail'];

                $.each(supplier_contact_detail,function(contact_key,contact_value){
                    if(contact_key != 0)
                    {
                        document.getElementById("contactplus").click(); //append multiple contact
                    }
                    contact_key++;
                    $("#new_contact_"+contact_key+" #supplier_contact_details_id").val(contact_value['supplier_contact_details_id']);
                    $("#new_contact_"+contact_key+" #salutation_id").val(contact_value['salutation_id']);
                    $("#new_contact_"+contact_key+" #supplier_contact_firstname").val(contact_value['supplier_contact_firstname']);
                    $("#new_contact_"+contact_key+" #supplier_contact_lastname").val(contact_value['supplier_contact_lastname']);
                    $("#new_contact_"+contact_key+" #supplier_contact_designation").val(contact_value['supplier_contact_designation']);
                    $("#new_contact_"+contact_key+" #supplier_contact_email_id").val(contact_value['supplier_contact_email_id']);

                    if(contact_value['supplier_contact_dial_code'] != '')
                    {
                        $("#supplier_contact_mobile_no .selected-dial-code").html(contact_value['supplier_contact_dial_code']);
                    }
                    $("#new_contact_"+contact_key+" #supplier_contact_mobile_no").val(contact_value['supplier_contact_mobile_no']);
                    if(contact_value['supplier_whatsapp_dial_code'] != '')
                    {
                        $("#supplier_whatsapp_no .selected-dial-code").html(contact_value['supplier_whatsapp_dial_code']);
                    }
                    $("#new_contact_"+contact_key+" #supplier_whatsapp_no").val(contact_value['supplier_whatsapp_no']);

                    if(contact_value['supplier_date_of_birth'] != '' )
                    {
                        $("#supplier_date_of_birth").val(contact_value['supplier_date_of_birth']);


                    }

                });

            }
        }
    });
}

$('#checkallsupplier').change(function()
{
    if($(this).is(":checked")) {
        $("#supplierrecord tr").each(function()
        {
            var id = $(this).attr('id');

            $(this).find('td').each(function ()
            {
                $("#delete_supplier"+id).prop('checked',true);
            });
        })
    }
    else
    {
        $("#supplierrecord tr").each(function(){
            var id = $(this).attr('id');
            $(this).find('td').each(function ()
            {
                $("#delete_supplier"+id).prop('checked',false);
            });

        })
    }
});


$("#deletesupplier").click(function ()
{
    if(confirm("Are You Sure want to delete this supplier?")) {

        var ids = [];

        $('input[name="delete_supplier[]"]:checked').each(function()
        {
            ids.push($(this).val());
        });

        if(ids.length > 0)
        {
            var data = {
                "deleted_id": ids
            };
            var url = "supplier_delete";
            var type = "POST";
            callroute(url, type, data, function (data)
            {
                var dta = JSON.parse(data);

                if (dta['Success'] == "True")
                {
                    toastr.success(dta['Message']);
                    resettable('supplier_data','supplierrecord');
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

function resetsupplierdata()
{
    $("#supplier_form").trigger('reset');
    $("#supplier_form").find('input[type=hidden]').val('');
    $('#repeat_contact').children(':not(#new_contact_1)').remove();
    $('#repleat_gst').children(':not(#new_gst_1)').remove();
    $('#repeat_bank').children(':not(#new_bank_1)').remove();
    $('#repeat_companymobile').children(':not(#mobile_1)').remove();

}

$("#supplierpopup").click(function () {
    $("#supplier_form").trigger('reset');
    $("#supplier_form").find('input[type=hidden]').val('');
    $('#repeat_contact').children(':not(#new_contact_1)').remove();
    $('#repleat_gst').children(':not(#new_gst_1)').remove();
    $('#repeat_bank').children(':not(#new_bank_1)').remove();
    $('#repeat_companymobile').children(':not(#mobile_1)').remove();
    $("#addsupplierpopup").modal('show');
});


$("#supplier_treatment_id").change(function () {
  var id = $(this).val();

 if(id == 2)
 {
     $("#supplier_gstin").removeClass('invalid');
 }
});


$("#supplier_due_date").datepicker({
    format:'dd-mm-yyyy',
    startDate: new Date()+1,
}).on('changeDate',function(ev){
    var date_get = new Date();
    var date = $("#supplier_due_date").val();
    var supplier_date = date.split('-');

    var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
    var firstDate = new Date(supplier_date[2],supplier_date[1],supplier_date[0]);
    var secondDate = new Date(date_get.getFullYear(),(date_get.getMonth()+1),date_get.getDate());

    var diffDays = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime())/(oneDay)));

    if(diffDays != '' && diffDays != 0 ) {
        $("#supplier_due_days").val(diffDays);
    }else
    {
        $("#supplier_due_days").val('');
        $("#supplier_due_date").val('');
    }

});



$('#supplier_due_days').keyup(function(e){

    var due_days   =  $('#supplier_due_days').val();

    if(due_days!='' && due_days!=0)
    {
        var fut_Date  = DateHelper.format(DateHelper.addDays(new Date(), Number(due_days)));
        $('#supplier_due_date').val(fut_Date);
    }
    else
    {
        $("#supplier_due_days").val('');
        $("#supplier_due_date").val('');
    }

});


