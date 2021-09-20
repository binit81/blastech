
$("#addcustomer").click(function (e) {
  if(validate_customer('customerform'))
  {
      var dialcode = $(".selected-dial-code").html();
      $("#customer_mobile_dial_code").val(dialcode);

      var data = {
          "formdata": $("#customerform").serialize(),
      };
      var  url = "customer_create";
      var type = "POST";

      callroute(url,type,data,function (data)
      {
          $("#addcustomer").prop('disabled', false);
          var dta = JSON.parse(data);

          if(dta['Success'] == "True")
          {
              toastr.success(dta['Message']);
              $("#customerform").trigger('reset');
              $("#customer_id").val('');
              $('#addnewbox').slideToggle();

              resettable('customer_data','customertablerecord');
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
    $("#addcustomer").prop('disabled', false);
  e.preventDefault();
});

function validate_customer(frmid)
{
    var error = 0;

    if($("#customer_name").val() == '')
    {
        error = 1;
        toastr.error("Enter Customer Name!");
        return false;
    }


    if($("#customer_mobile").val() == " ")
    {
        error = 1;
        toastr.error("Enter Customer Mobile No!");
        return false;
    }

    if($("#customer_email").val() != '')
    {
        var emailid = $("#customer_email").val();
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


function editcustomer(customer_id)
{
    $("#addcustomer").prop('disable',true);
    $('#addnewbox').slideToggle();
    $("html").scrollTop(0);
    var url = "customer_edit";
    var type = "POST";
    var data = {
        "customer_id": customer_id
    }
    callroute(url, type, data, function (data)
    {
        $("#addcustomer").prop('disable', false);
        var customer_response = JSON.parse(data);

        if (customer_response['Success'] == "True")
        {

            var customer_data = customer_response['Data'];
            $("#customer_id").val(customer_data['customer_id']);
            $("#customer_name").val(customer_data['customer_name']);
            $('input[name=gender][value='+customer_data['customer_gender']+']').prop('checked',true);
            $("#customer_mobile").val(customer_data['customer_mobile']);
            $("#customer_email").val(customer_data['customer_email']);
            $("#customer_date_of_birth").val(customer_data['customer_date_of_birth']);
            $("#outstanding_duedays").val(customer_data['outstanding_duedays']);
            $("#source").val(customer_data['customer_source_id']);
            $("#customer_note").val(customer_data['note']);

            if(customer_data['customer_mobile_dial_code'] != '')
            {
               $(".selected-dial-code").html(customer_data['customer_mobile_dial_code']);
            }

            if(typeof customer_data['customer_address_detail'] != '' && customer_data['customer_address_detail'] != 'undefined' && customer_data['customer_address_detail'] != null)
            {
                var address_detail = customer_data['customer_address_detail'];

                $("#customer_address_detail_id").val(address_detail['customer_address_detail_id']);
                $("#customer_gstin").val(address_detail['customer_gstin']);
                $("#customer_address").val(address_detail['customer_address']);
                $("#customer_area").val(address_detail['customer_area']);
                $("#customer_city").val(address_detail['customer_city']);
                $("#customer_pincode").val(address_detail['customer_pincode']);

                if(address_detail['customer_gstin'] != '' && address_detail['customer_gstin'] != null)
                {
                    $('#state_id').attr("style", "pointer-events: none;");
                }
                else
                {
                    $('#state_id').attr("style", "pointer-events: all;");
                }

                if(address_detail['state_id'] == null)
                {
                    $("#state_id").val('0');
                }
                else
                {
                    $("#state_id").val(address_detail['state_id']);
                }

                $("#country_id").val(address_detail['country_id']);
            }
        }
    });
}


$('#checkallcustomer').change(function()
{
    if($(this).is(":checked")) {
        $("#customerrecord tr").each(function()
        {
            var id = $(this).attr('id');

            $(this).find('td').each(function ()
            {
                $("#delete_customer"+id).prop('checked',true);
            });
        })
    }
    else
    {
        $("#customerrecord tr").each(function(){
            var id = $(this).attr('id');
            $(this).find('td').each(function ()
            {
                $("#delete_customer"+id).prop('checked',false);
            });

        })
    }
});


$("#deletecustomer").click(function ()
{
    if(confirm("Are You Sure want to delete this customer?")) {

        var ids = [];

        $('input[name="delete_customer[]"]:checked').each(function()
        {
            ids.push($(this).val());
        });


        if(ids.length > 0)
        {
            var data = {
                "deleted_id": ids
            };
            var url = "customer_delete";
            var type = "POST";
            callroute(url, type, data, function (data)
            {
                var dta = JSON.parse(data);

                if (dta['Success'] == "True")
                {
                    toastr.success(dta['Message']);
                    resettable('customer_data','customertablerecord');

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


function resetcustomerdata()
{
    $("#customerform").trigger('reset');
    $("#customer_id").val('');
    $("#customer_address_detail_id").val('');
}


$("#customer_date_of_birth").datepicker({
    format: 'dd-mm-yyyy',
    orientation: "bottom"
});


//based on gst select state
$("#customer_gstin").keyup(function ()
{
    var gst_state_code = $("#customer_gstin").val().substr(0,2);

    if(gst_state_code.length != 0)
    {
        $('#state_id').attr("style", "pointer-events: none;");

        $("#state_id").css('color','black');
        if(gst_state_code.startsWith('0'))
        {
            gst_state_code = gst_state_code.substring(1);
        }
        $("#state_id").val(gst_state_code);
    }
    else
    {
        $('#state_id').attr("style", "pointer-events: all;");

        $("#state_id").val('0');
    }


});
//end of select state based on gst

function resetcustomerfilterdata()
{
    $("#filter_customer_name").val('');
    $("#filter_mobile_no").val('');
    $("#filter_email_id").val('');
    $("#filter_gst_in").val('');
    $("#filter_date_of_birth").val('');
    $("#filter_area").val('');
    $("#filter_city").val('');
    $("#filer_pincode").val('');
    $("#filter_state_id").val(0);
    $("#filter_country_id").val(0);

    $("#hidden_page").val(1);

    resettable('customer_data','customertablerecord');
}


$(document).on('click','#download_customer_tmpate',function ()
{
    var url = "customer_template?";
    window.open(url, '_blank');
});


$("body").on("click", "#uploadcustomer", function ()
{
    $("#uploadcustomer").attr('disabled',true);

    $(".loaderContainer").show();

    //Reference the FileUpload element.
    var fileUpload = $("#customerfileUpload")[0];

    var ext = fileUpload.value.split('.').pop();

    //Validate whether File is valid Excel file.
    var validImageTypes = ['xls', 'xlsx'];

    // var regex = /^([a-zA-Z 0-9\s_\\.\-:])+(.xls|.xlsx)$/;
    // if (regex.test(fileUpload.value.toLowerCase())) {
    if (validImageTypes.includes(ext))
    {
        if (typeof (FileReader) != "undefined")
        {
            var reader = new FileReader();

            //For Browsers other than IE.
            if (reader.readAsBinaryString)
            {
                reader.onload = function (e)
                {
                    ProcessExcel(e.target.result);
                };
                reader.readAsBinaryString(fileUpload.files[0]);
            } else {
                //For IE Browser.
                reader.onload = function (e)
                {
                    var data = "";
                    var bytes = new Uint8Array(e.target.result);
                    for (var i = 0; i < bytes.byteLength; i++) {
                        data += String.fromCharCode(bytes[i]);
                    }
                    ProcessExcel(data);
                };
                reader.readAsArrayBuffer(fileUpload.files[0]);
            }
        } else {
            $("#uploadcustomer").attr('disabled',false);
            $(".loaderContainer").hide();
            alert("This browser does not support HTML5.");
        }
    } else {
        $("#uploadcustomer").attr('disabled',false);
        $(".loaderContainer").hide();
        alert("Please upload a valid Excel file.");
    }
});
function ProcessExcel(data)
{
    //Read the Excel File data.

    var result;

    var workbook = XLSX.read(data, {
        type: 'binary'
    });

    //Fetch the name of First Sheet.
    var firstSheet = workbook.SheetNames[0];
    //Read all rows from First Sheet into an JSON array.
    var excelRows = XLSX.utils.sheet_to_json(workbook.Sheets[firstSheet],{ defval: ''});

    var final_customerarr = [];
    $.each(excelRows,function (key,value)
    {
        var final_data = {};
        $.each(value,function (arrkwy,arrval)
        {
            if (!arrkwy.match("^__EMPTY"))
            {
                final_data[arrkwy] = arrval;
            }
        });
        final_customerarr.push(final_data);
    });

    var error = 0;

   $.each(final_customerarr,function (validate_ckey,validate_cvalue)
    {
        if(validate_cvalue['Customer Name'] == '')
        {
            error = 1;
            toastr.error("Customer name can not be empty!");
        }

        if(validate_cvalue['Gender'] != "" && validate_cvalue['Gender'] != "male" && validate_cvalue['Gender'] != "Male" && validate_cvalue['Gender'] != "MALE"  && validate_cvalue['Gender'] != "female" && validate_cvalue['Gender'] != "Female" && validate_cvalue['Gender'] != "FEMALE" && validate_cvalue['Gender'] != "transgender" && validate_cvalue['Gender'] != "Transgender" && validate_cvalue['Gender'] != "TRANSGENDER")
        {
            error = 1;
            toastr.error("Customer Gender must 'male','Male','MALE','female','Female','FEMALE','transgender','Transgender','TRANSGENDER'!");
        }

        if(validate_cvalue['Customer Mobile Country Code'] != "" && !$.isNumeric(validate_cvalue['Customer Mobile Country Code']))
        {
            error = 1;
            toastr.error("Customer Mobile Country Code must be numeric!");
        }

        if(validate_cvalue['Mobile No.'] != "" && !$.isNumeric(validate_cvalue['Mobile No.']))
        {
            error = 1;
            toastr.error("Customer Mobile No. must be numeric!");
        }

        if(validate_cvalue['Email'] != '')
        {
            var emailid = validate_cvalue['Email'];
            if(validateEmail(emailid) == 0)
            {
                error = 1;
                toastr.error("Enter proper Customer Email id!");
                return false;
            }
        }

        //CHECKING MFG DATE,MONTH AND YEAR DIGIT AND PROPER DATE FORMAT VALIDATION
        if(validate_cvalue['Day of Birth(DD)'] != '' || validate_cvalue['Month of Birth(MM)'] != '' || validate_cvalue['Year of Birth(YYYY)'] != '')
        {
            if(separtate_date_format_validation(validate_cvalue['Day of Birth(DD)'],validate_cvalue['Month of Birth(MM)'],validate_cvalue['Year of Birth(YYYY)'],"Date of birth") == 0)
            {
                error  = 1;
            }
        }
        //END OF CHECKING MFG DATE FORMAT VALIDATION


        if(validate_cvalue['Credit Period(days)'] != "" && !$.isNumeric(validate_cvalue['Credit Period(days)']))
        {
            error = 1;
            toastr.error("Customer Credit Period(days) must be numeric!");
        }

        if(error == 1)
        {
            $("#uploadcustomer").attr('disabled',false);
            $(".loaderContainer").hide();
            return false;
        }
    });


    if(error == 0)
    {
        checkcustomer(final_customerarr);
    }

}


function checkcustomer(customerarr)
{
    var  url = "customer_check";
    var type = "POST";

    callroute(url,type,customerarr,function (data)
    {
        var responce = JSON.parse(data);
        if(responce['Success'] == "True")
        {
            $(".loaderContainer").hide();
            toastr.success(responce[['Message']]);
            $("#uploadcustomer").attr('disabled',false);
            $("#upload_customer_popup").modal('hide');
            $("#customerfileUpload").val('');
            resetcustomerfilterdata();
        }
        else
        {
            toastr.error(responce[['Message']]);
            $(".loaderContainer").hide();
            $("#uploadcustomer").attr('disabled',false);
        }
    })
}


$("#upload_customer_tmpate").click(function ()
{
   jQuery.noConflict();
$("#upload_customer_popup").modal('show');
});