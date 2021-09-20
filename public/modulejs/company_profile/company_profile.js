



$("#addcompanyprofile").click(function ()
{
   if(validate_company_profile('company_profile_form'))
   {
      $("#addcompanyprofile").prop('disabled', true);
      var pi = $('#pi .selected-dial-code').html();
      var ci = $('#ci .selected-dial-code').html();
      var sm = $('#sm .selected-dial-code').html();

      var pi_country = $('#pi .selected-dial-code').siblings('div').attr('class').split('iti-flag ')[1];
      var ci_country = $('#ci .selected-dial-code').siblings('div').attr('class').split('iti-flag ')[1];
      var sm_country = $('#sm .selected-dial-code').siblings('div').attr('class').split('iti-flag ')[1];

      $("#state_id").removeAttr('disabled',true);
      var state_id = $('#state_id :selected').val();

      $("#state_id").val(state_id);


      $("#personal_mobile_dial_code").val(pi +','+ pi_country);
      $("#company_mobile_dial_code").val(ci +','+ ci_country);
      $("#whatsapp_mobile_dial_code").val(sm +','+ sm_country);


      var comapny_address = CKEDITOR.instances.company_address.getData();
      $("#company_address").val(comapny_address);

      var terms_and_condition = CKEDITOR.instances.terms_and_condition.getData();
      $("#terms_and_condition").val(terms_and_condition);

      var additional_message = CKEDITOR.instances.additional_message.getData();
      $("#additional_message").val(additional_message);

      var po_terms_and_condition = CKEDITOR.instances.po_terms_and_condition.getData();
      $("#po_terms_and_condition").val(po_terms_and_condition);


      var data = {
         "formdata": $("#company_profile_form").serialize(),
      };

      var  url = "company_profile_create";
      var type = "POST";
      callroute(url,type,data,function (data)
      {
         $("#addcompanyprofile").prop('disabled', false);
         var dta = JSON.parse(data);

         if(dta['Success'] == "True")
         {
            toastr.success(dta['Message']);
            $("#company_profile_id").val(dta['company_profile_id']);
            if(dta['url']!='')
            {
               window.location = dta['url'];
            }
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
   else {
      $("#addcompanyprofile").prop('disabled', false);
      return false;
   }
});


//function for validate company profile form
function validate_company_profile(frmid)
{
   var error = 0;

   if($("#full_name").val() == '')
   {
       error = 1;
      toastr.error("Enter Full Name!");
      return false;
   }

   if($("#personal_mobile_no").val() == '')
   {
       error = 1;
      toastr.error("Enter Personal Mobile Number!");
      return false;
   }

   if($("#personal_email").val() == '')
   {
       error = 1;
      toastr.error("Enter Personal Email!");
      return false;
   }
   else
   {
      var email = $("#personal_email").val().split(',');
      $.each(email,function (emailkey,emailvalue) {
         if(!validateEmail(emailvalue))
         {
            error = 1;
            toastr.error("Enter Proper Personal EmailId!");
            return false;
         }
      });
   }


   if($("#company_email").val() != '')
   {
      var cemail = $("#company_email").val().split(',');
      $.each(cemail,function (cemailkey,cemailvalue) {
         if(!validateEmail(cemailvalue))
         {
            error = 1;
            toastr.error("Enter Proper Company EmailId!");
            return false;
         }
      });
   }

   if($("#company_name").val() == '')
   {
      error = 1;
      toastr.error("Enter Company Name!");
      return false;
   }


   if($("#state_id").val() == 0)
   {
      error  =1;
      toastr.error("Please Select State!");
      return false;

   }



    var address = CKEDITOR.instances.company_address.getData();
   if(address == '')
   {
      error = 1;
      toastr.error("Enter Company Address!");
      return false;
   }

   if($("#company_area").val() == '')
   {
      error = 1;
      toastr.error("Enter Company Area!");
      return false;
   }

   if($("#company_city").val() == '')
   {
      error = 1;
      toastr.error("Enter Company City!");
      return false;
   }
   if($("#company_pincode").val() == '')
      {
         error = 1;
         toastr.error("Enter Company Pincode!");
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
//end of function for validate company profile form








//authorized signatory for => hide show
$("#authorized_signatory").change(function(){

   if($(this).prop('checked'))
   {
      $("#authority_for").css('display','block');
      $("#authorized_signatory_for").val($("#company_name").val());
   }
   else
   {
      $("#authority_for").css('display','none');
      $("#authorized_signatory_for").val('');
   }

});
//end of authority signatory for =>hide/show

//if authority signatory is checked then its value = company name
$("#company_name").keyup(function ()
{
   if($("#authorized_signatory").prop('checked'))
   {
      $("#authorized_signatory_for").val($(this).val());

   }
});
//end of companyname = authority signatory for

//based on gst select state
$("#gstin").keyup(function ()
{
   var gst_state_code = $("#gstin").val().substr(0,2);

   if(gst_state_code.length != 0)
   {
      $('#state_id').attr("style", "pointer-events: none;");
      $("#state_id").css('color','black');
      if(gst_state_code.startsWith('0'))
      {
          gst_state_code = gst_state_code.substring(1);
      }
      $("#state_id").val(gst_state_code);
      $('#state_id option[value="'+gst_state_code+'"]').attr("selected", "selected");

   }
   else
   {
      $('#state_id').attr("style", "pointer-events: all;");
      $("#state_id").css('color','');
      $("#state_id").val('');
   }
});

//end of select state based on gst

//function for reset company profile
function resetcompany_profiledata()
{

$("#pi").each(function () {
   $(this).find("input[type=text],input[type=tel], textarea").val('');
});

   $("#ci").each(function () {
      $(this).find("input[type=text],input[type=tel], textarea,select").val('');
   });
   $("#sm").each(function () {
      $(this).find("input[type=text],input[type=tel], textarea,select").val('');
   });

   $("#ad").each(function () {
      $(this).find("input[type=text],input[type=tel], textarea,select").val('');
   });

   $("#ad").each(function () {
      $(this).find("input[type=text],input[type=tel], textarea,select").val('');
   });

   $("#bf").each(function () {
      $(this).find("input[type=text],input[type=tel], textarea,select").val('');
   });

   $("#bd").each(function () {
      $(this).find("input[type=text]").val('');
   });
   $("#authorized_signatory").prop('checked',false);
   $("#authority_for").css('display','none');


  CKEDITOR.instances.company_address.setData('');
  CKEDITOR.instances.terms_and_condition.setData('');
  CKEDITOR.instances.additional_message.setData('');
}
//end of function for company profile




//for check software configuration is set or not
$("document").ready(function () {

   if(typeof tax_type == 'undefined' )
   {
     // toastr.error("Please Contact Software Technical Person First!");
      toastr.options = {
         debug: false,
         positionClass: "toast-top-full-width",
         timeOut: 0,
         tapToDismiss :false,
         onclick: null,
         extendedTimeOut: 0
      }
      toastr.error("Please Contact Software Technical Team First!");
      $("#company_profile_form :input").prop("disabled", true);

   }

});

//end of checking configuration set up



