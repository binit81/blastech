$("#employee_joiningdate").datepicker({
    format: 'dd-mm-yyyy',
    orientation: "bottom",
    autoclose: true
});

$("#employee_dob").datepicker({
    format: 'dd-mm-yyyy',
    orientation: "bottom",
    autoclose: true
});

$("#employee_resigned_date").datepicker({
    format: 'dd-mm-yyyy',
    orientation: "bottom",
    autoclose: true
});

$('#country_id').change(function(e)
{
    var country_id      =   $('#country_id').val();
    if(Number(country_id)!='102')
    {
        $('#state_id').prop('selectedIndex',0);
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

    // admin password on change password window
    var bx = document.getElementById("adminpassword");
    if (bx.type === "password") {
        bx.type = "text";
    } else {
        bx.type = "password";
    }

    // new password on change password window
    var z = document.getElementById("new_password_");
    if (z.type === "password") {
        z.type = "text";
    } else {
        z.type = "password";
    }

    // re-enter password on change password window
    var a = document.getElementById("confirm_password_");
    if (a.type === "password") {
        a.type = "text";
    } else {
        a.type = "password";
    }

    // admin password on change password window
    var b = document.getElementById("admin_password_");
    if (b.type === "password") {
        b.type = "text";
    } else {
        b.type = "password";
    }


    $('.eyePassword').css('opacity','1');   // change opacity

});

function addnewRole()
{
    $("#addnewRole").modal('show');
}

function addnewEmployee()
{
    $("#addnewEmployee").modal('show');
    $('#employee_form').trigger("reset");
    $('.loginData').html('');
    $('#user_id_').val('');
    $('.adminPassBox').hide();
    $('.PasswordLabel').html('Set Password?');
    $('.loginYesNo').hide();

    $('.employeePicture').html('Add Profile Picture<br><input type="file" class="" id="employee_picture" name="employee_picture" accept=".jpeg, .jpg, .png, .gif" autocomplete="off"><small>Max Image Size: <b>2mb.</b><br>Accepted Formats: <b>.jpeg, .jpg, .png, .gif</b></small><input type="hidden" name="chkPicture" id="chkPicture" value="" />');

}

// function addnewRole()
// {
//     $('#roleForm').trigger("reset");
//     $('.roleForm').html('');
// }


$("input[class='employee_login']").click(function(e)
{

    // if($('#user_id_').val()=='')
    // {

        if($('#user_id_').val()=='')
        {
            $('.adminPassBox').hide();
            $('.loginYesNo').addClass('col-md-3');

            if($("input[class='employee_login']").prop('checked') == true)
            {
                $('.loginYesNo').show();
                $('.loginData').show();
                var email   =   $('#email').val();  
                if(email!='')
                {
                    $('.loginData').html('<br><small style="color:#888888;"><b>' +email+ '</b> will be your <b>USERNAME</b> for software login</small>');   
                }
            }
            else
            {
                $('.loginYesNo').hide();
                $('.loginData').hide();
                $('#password').val('');
                $('#encrypt_password').val('');
                $('#adminpassword').val('');
                $('#employee_role_id_').prop('selectedIndex',0);
            }
        }
        else
        {
            
            $('.abcX').removeClass('col-md-3');
            $('.extraeye').hide();

            if($("input[class='employee_login']").prop('checked') == true)
            {
                $('.loginYesNo').show();
                $('.adminPassBox').show();
                $('.loginData').show();
                var email   =   $('#email').val();  
                if(email!='')
                {
                    $('.loginData').html('<br><small style="color:#888888;"><b>' +email+ '</b> will be your <b>USERNAME</b> for software login</small>');   
                }
            }
            else
            {
                $('.loginYesNo').hide();
                $('.loginData').hide();
                $('.adminPassBox').hide();

                $('#password').val('');
                $('#encrypt_password').val('');
                $('#adminpassword').val('');
                $('#employee_role_id_').prop('selectedIndex',0);
            }
        }
    // }
});

$("#employeeName").keyup(function ()
{
    
    jQuery.noConflict();
    if($("#employeeName").val().length >= 1) {

        $("#employeeName").autocomplete({
            autoFocus: true,
            minLength: 1,
            source: function (request, response) {
                var url = "employeeName_search";
                var type = "POST";
                var data = {
                    'search_val': $("#employeeName").val()
                };
                callroute(url, type, data, function (data) {


                    var searchdata = JSON.parse(data, true);
                    var html = '';
                    if (searchdata['Success'] == "True") {

                        var result = [];
                        searchdata['Data'].forEach(function (value) {
                            var name    =   value.employee_firstname +' '+ value.employee_middlename +' '+ value.employee_lastname;
                             result.push({label:name, value:name,id:value.user_id });
                        });
                        response(result);

                    }
                });
            },
            select: function (event, ui) {
                var id = ui.item.id;
            }
        });
    }
    else
    {
            $("#employeeName").empty();
    }

});

$("#mobileNo").keyup(function ()
{
    
    jQuery.noConflict();
    if($("#mobileNo").val().length >= 1) {

        $("#mobileNo").autocomplete({
            autoFocus: true,
            minLength: 1,
            source: function (request, response) {
                var url = "employee_mobile_search";
                var type = "POST";
                var data = {
                    'search_val': $("#mobileNo").val()
                };
                callroute(url, type, data, function (data) {


                    var searchdata = JSON.parse(data, true);
                    var html = '';
                    if (searchdata['Success'] == "True") {

                        var result = [];
                        searchdata['Data'].forEach(function (value) {
                            var datanew    =   value.employee_mobileno;
                            result.push({label:datanew, value:datanew,id:value.user_id });
                        });
                        response(result);

                    }
                });
            },
            select: function (event, ui) {
                var id = ui.item.id;
            }
        });
    }
    else
    {
            $("#mobileNo").empty();
    }

});


$("#empCode").keyup(function ()
{
    
    jQuery.noConflict();
    if($("#empCode").val().length >= 1) {

        $("#empCode").autocomplete({
            autoFocus: true,
            minLength: 1,
            source: function (request, response) {
                var url = "employee_code_search";
                var type = "POST";
                var data = {
                    'search_val': $("#empCode").val()
                };
                callroute(url, type, data, function (data) {


                    var searchdata = JSON.parse(data, true);
                    var html = '';
                    if (searchdata['Success'] == "True") {

                        var result = [];
                        searchdata['Data'].forEach(function (value) {
                            var datanew    =   value.employee_code;
                            result.push({label:datanew, value:datanew,id:value.user_id });
                        });
                        response(result);

                    }
                });
            },
            select: function (event, ui) {
                var id = ui.item.id;
            }
        });
    }
    else
    {
            $("#empCode").empty();
    }

});

$("#empDesignation").keyup(function ()
{
    
    jQuery.noConflict();
    if($("#empDesignation").val().length >= 1) {

        $("#empDesignation").autocomplete({
            autoFocus: true,
            minLength: 1,
            source: function (request, response) {
                var url = "employee_designation_search";
                var type = "POST";
                var data = {
                    'search_val': $("#empDesignation").val()
                };
                callroute(url, type, data, function (data) {


                    var searchdata = JSON.parse(data, true);
                    var html = '';
                    if (searchdata['Success'] == "True") {

                        var result = [];
                        searchdata['Data'].forEach(function (value) {
                            var datanew    =   value.employee_designation;
                            result.push({label:datanew, value:datanew,id:value.user_id });
                        });
                        response(result);

                    }
                });
            },
            select: function (event, ui) {
                var id = ui.item.id;
            }
        });
    }
    else
    {
            $("#empDesignation").empty();
    }

});


$('#SearchEmployeeData').click(function(e){

    var employeeName           =    $('#employeeName').val();
    var mobileNo               =    $('#mobileNo').val();
    var empCode                =    $('#empCode').val();
    var empDesignation         =    $('#empDesignation').val();
    var radioValue             =    $("input[name='statusType']:checked").val();

    if(radioValue==undefined)
    {
        radioValue  =   ''
    }
    else
    {
        radioValue  =   radioValue;
    }

    var fetch_data_url  =   'searchEmployeeResult';

    $('.loaderContainer').show();
    
    employee_data(fetch_data_url,employeeName,mobileNo,empCode,empDesignation,radioValue);
    

});

function employee_data(fetch_data_url,employeeName,mobileNo,empCode,empDesignation,radioValue)
{
    $.ajax({
        url:fetch_data_url,

        data: {
            employeeName:employeeName,
            mobileNo:mobileNo,
            empCode:empCode,
            empDesignation:empDesignation,
            radioValue:radioValue
        },
        success:function(data)
        {
            console.log(data);
            $('.loaderContainer').hide();
            // $('html, body').animate({ scrollTop: $("#barcodeTotalQty_text").offset().top }, 2000);
            $('tbody#employeesearchResult').html('');
            $('tbody#employeesearchResult').html(data);

        }
    })
}

// $("body").on("submit", "#employee_form", function (event)
$('#employee_form').on('submit', function(event)
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
            
            if($('#adminpassword').val()=='')
            {
                error = 1;
                toastr.error("admin password is required");
                $('#adminpassword').focus();
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
                           window.location = data['url'];
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

function changeStatus(val,status)
{
    var fetch_data_url  =   'changeStatus';
    $('.loaderContainer').show();
    $.ajax({
        url:fetch_data_url,
        data: {
            user_id:val,
            is_active: status
        },
        success:function(data)
        {
            var searchdata = JSON.parse(data, true);
            $('.loaderContainer').hide();

            if(Number(searchdata['status'])==1)
            {
                $('#status'+Number(searchdata['user_id'])).html('Active');
                $('#status'+Number(searchdata['user_id'])).removeClass('RedBackground');
                $('#status'+Number(searchdata['user_id'])).addClass(searchdata['class']);
            }
            else
            {
                $('#status'+Number(searchdata['user_id'])).html('Inactive');
                $('#status'+Number(searchdata['user_id'])).removeClass('GreenBackground');
                $('#status'+Number(searchdata['user_id'])).addClass(searchdata['class']);
            }
        }
    })
}

$(document).on('click', '#exportEmployeedata', function(){

    var query = {
        employeeName: $('#employeeName').val(),
        mobileNo : $('#mobileNo').val(),
        empCode: $('#empCode').val(),
        empDesignation: $('#empDesignation').val(),
        radioValue : $("input[name='statusType']:checked").val()
    }

    
    var url = "exportemployee_details?" + $.param(query)
    window.open(url,'_blank');

});

function formatDate(date) {
     var d = new Date(date),
         month = '' + (d.getMonth() + 1),
         day = '' + d.getDate(),
         year = d.getFullYear();

     if (month.length < 2) month = '0' + month;
     if (day.length < 2) day = '0' + day;

     return [day, month, year].join('-');
 }

function editEmployee(user_id)
{
    $("#addnewEmployee").modal('show');
    $('#user_id_').val(user_id);
    var fetch_data_url  =   'editEmployee';
    $('.loaderContainer').show();
    $('.loginYesNo').hide();
    $('.adminPassBox').hide();
    $('input[name="employee_login"]').prop('checked', false);

    $('.PasswordLabel').html('Set / Change Password?');

    $.ajax({
        url:fetch_data_url,
        data: {
            user_id:user_id,
        },
        success:function(data)
        {
            var searchdata = JSON.parse(data, true);
            $('.loaderContainer').hide();
            console.log(searchdata);
            var data  =   searchdata['Data'][0];

            $('#employee_firstname').val(data['employee_firstname']);
            $('#employee_middlename').val(data['employee_middlename']);
            $('#employee_lastname').val(data['employee_lastname']);
            $('#employee_code').val(data['employee_code']);
            $('#email').val(data['email']);
            $('#employee_mobileno').val(data['employee_mobileno']);
            $('#employee_alternate_mobile').val(data['employee_alternate_mobile']);
            $('#employee_family_member_mobile').val(data['employee_family_member_mobile']);
            $('#employee_joiningdate').val(formatDate(data['employee_joiningdate']));
            $('#employee_designation').val(data['employee_designation']);
            $('#employee_duties').val(data['employee_duties']);
            $('#employee_salary_offered').val(data['employee_salary_offered']);
            $('#employee_skills').val(data['employee_skills']);
            $('#employee_past_experience').val(data['employee_past_experience']);
            $('#employee_dob').val(data['employee_dob']!=null?formatDate(data['employee_dob']):'');
            $('#employee_address').val(data['employee_address']);
            $('#employee_area').val(data['employee_area']);
            $('#employee_city_town').val(data['employee_city_town']);
            $('#employee_zipcode').val(data['employee_zipcode']);
            $('#employee_reference').val(data['employee_reference']);
            $('#employee_resigned_date').val(data['employee_resigned_date']!=null?formatDate(data['employee_resigned_date']):'');
            $('#employee_resigned_reason').val(data['employee_resigned_reason']);
            $('#employee_remarks').val(data['employee_remarks']);
            $('#is_master').val(data['is_master']);


            $('#old_password_').val(data['encrypt_password']);
            $('#old_encrypt_password_').val(data['encrypt_password']);

            $('#state_id option:eq('+data['state_id']+')').prop('selected', true);

            if(data['employee_role_id']!=null)
            {
                $('#employee_role_id_ option:eq('+data['employee_role_id']+')').prop('selected', true);
            }
            else
            {
                $('#employee_role_id_').prop('selectedIndex',0);
            }
            
            $('#country_id option:eq('+data['country_id']+')').prop('selected', true);
            $('input[name="employee_marital_status"][value="' + data['employee_marital_status'] + '"]').prop('checked', true);

            if(data['employee_picture']!='')
            {
                $('.employeePicture').html('<div class="media-img-wrap d-flex mr-10 pull-right center"><div class="avatar"><img src="'+employee_image_url+data['employee_picture']+'" class="img-fluid img-thumbnail" alt="img"><small class="cursor" onclick="removePicture('+data['user_id']+')">remove</small><input type="hidden" name="chkPicture" id="chkPicture" value="'+data['employee_picture']+'" /></div></div>');
            }
            else
            {
                $('.employeePicture').html('Add Profile Picture<br><input type="file" class="" id="employee_picture" name="employee_picture" accept=".jpeg, .jpg, .png, .gif" autocomplete="off"><small>Max Image Size: <b>2mb.</b><br>Accepted Formats: <b>.jpeg, .jpg, .png, .gif</b></small><input type="hidden" name="chkPicture" id="chkPicture" value="" />');
            }

            $('.loginData').html('');

            // if(Number(data['employee_login'])==1)
            // {
            //     $("input[name='employee_login']").prop('checked', true);
            //     //$('.loginYesNo').show();
            //     $('.loginData').show();
            //     $('.loginData').html('<br><small style="color:#888888;"><b>' + data['email'] + '</b> will be your software login name</small>');

            // }
            // else
            // {
            //     $("input[name='employee_login']").prop('checked', false);
            //     //$('.loginYesNo').hide();
            // }


        }
    })
}

function removePicture(user_id)
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

function deleteEmp(user_id)
{
    var r = confirm("are you sure you want to delete this employee?");

    if (r == true)
    {
        var fetch_data_url  =   'deleteEmployee';
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
                console.log(searchdata);

                toastr.success(searchdata['Message']);
                if(searchdata['url']!='')
                {
                   window.location = searchdata['url'];
                }
            }
        })    
    }
    else
    {
        return false;
    }
}

function showResume(user_id)
{
    $("#showResume").modal('show');

    var permission_edit         =   $('#permission_edit').val();
    var permission_delete       =   $('#permission_delete').val();

    var fetch_data_url  =   'showResume';
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
            console.log(searchdata);

            var data  =   searchdata['Data'][0];

            $('#emp_dob').html('');
            $('#emp_code').html('');
            $('#emp_email').html('');
            $('#emp_mobile').html('');
            $('#emp_alternate').html('');
            $('#emp_family').html('');
            $('#emp_joining').html('');
            $('#emp_salary').html('');
            $('#emp_marital').html('');
            $('#emp_address').html('');
            $('#emp_area').html('');
            $('#emp_city').html('');
            $('#emp_state').html('');
            $('#emp_country').html('');
            $('#emp_postal').html('');
            $('#emp_designation').html('');
            $('#emp_duties').html('');
            $('#emp_education').html('');
            $('#emp_skills').html('');
            $('#emp_pastexp').html('');
            $('#emp_references').html('');
            $('#emp_regsineddate').html('');
            $('#emp_reason').html('');
            $('#emp_remarks').html('');

            if(data['employee_picture']!='')
            {
                $('#emp_profilepic').html('<img src="'+employee_image_url+data['employee_picture']+'" class="img-fluid img-thumbnail" width="100%"  alt="img">');
            }
            else
            {
                $('#emp_profilepic').html('<img src="dist/img/img-thumb.jpg" class="img-fluid img-thumbnail" width="100%"  alt="img">');
            }

            $('#employeeFullname').html(data['employee_firstname']+' '+data['employee_middlename']+' '+data['employee_lastname']);


            if(data['employee_role_id']!=null)
            {   
                $('#employeeRoleBadge').html(data['employee_role']['role_name']);
            }

            $('#emp_dob').html(data['employee_dob']!=null?formatDate(data['employee_dob']):'');
            $('#emp_code').html(data['employee_code']);
            $('#emp_email').html(data['email']);
            $('#emp_mobile').html(data['employee_mobileno']);
            $('#emp_alternate').html(data['employee_alternate_mobile']);
            $('#emp_family').html(data['employee_family_member_mobile']);
            $('#emp_joining').html(data['employee_joiningdate']!=null?formatDate(data['employee_joiningdate']):'');
            $('#emp_salary').html(data['employee_salary_offered']);

            var marital     =   '';
            if(Number(data['employee_marital_status'])==1)
            {
                marital     =   'Single';
            }
            else if(Number(data['employee_marital_status'])==2)
            {
                marital     =   'Married';
            }
            else if(Number(data['employee_marital_status'])==3)
            {
                marital     =   'Divorced';
            }
            else if(Number(data['employee_marital_status'])==4)
            {
                marital     =   'Widow';
            }

            $('#emp_marital').html(marital);
            $('#emp_address').html(data['employee_address']);
            $('#emp_area').html(data['employee_area']);
            $('#emp_city').html(data['employee_city_town']);

            if(data['state_id']!=null)
            {
                $('#emp_state').html(data['state']['state_name']); 
            }
            
            if(data['country_id']!=null)
            {
                $('#emp_country').html(data['country']['country_name']);
            }

            $('#emp_postal').html(data['employee_zipcode']);
            $('#emp_designation').html(data['employee_designation']);
            $('#emp_duties').html(data['employee_duties']);
            $('#emp_education').html(data['employee_education']);
            $('#emp_skills').html(data['employee_skills']);
            $('#emp_pastexp').html(data['employee_past_experience']);
            $('#emp_references').html(data['employee_reference']);
            $('#emp_regsineddate').html(data['employee_resigned_date']!=null?formatDate(data['employee_resigned_date']):'');
            $('#emp_reason').html(data['employee_resigned_reason']);
            $('#emp_remarks').html(data['employee_remarks']);

            if(Number(permission_edit)==1 || Number(permission_delete)==1)
            {
                var action  =   'Action: ';
            }
            else
            {
                var action  =   '';
            }

            if(Number(permission_edit)!=1)
            {
                var edit    =   '';
            }
            else
            {
                var edit    =   '<button class="btn btn-icon btn-icon-only ma-0 btn-secondary btn-icon-style-4" onclick="changePassword('+data['user_id']+')" title="Change Password"><i class="fa fa-lock"></i></button><button class="btn btn-icon btn-icon-only btn-secondary btn-icon-style-4" onclick="editEmployee('+data['user_id']+')" title="edit employee"><i class="fa fa-pencil"></i></button>';
            }

            if(Number(permission_delete)!=1)
            {
                var del     =   '';
            }
            else
            {
                var del     =   '<button class="btn btn-icon btn-icon-only btn-secondary btn-icon-style-4" onclick="deleteEmp('+data['user_id']+')" title="delete employee"><i class="fa fa-trash"></i></button>';
            }

            $('.ShowActionButtons').html(action+edit+del);

        }
    })
}

function validateRole()
{
    var password    =   $('#password').val();
    if(password!='')
    {
        $('#employee_role_id_').addClass('invalid');
    }
}

function changePassword(user_id)
{
    $("#newPassword").modal('show');
    $('#password_employee_id').val(user_id);
    $('#new_password_employee_id').val(user_id);

    var url = "showEmpName";
    var type = "POST";
    var data = {
        "user_id": user_id,
    }
    callroute(url,type,data,function (data)
    {
        var searchdata = JSON.parse(data, true);
        console.log(searchdata);
        var firstname   =   searchdata['Data'][0]['employee_firstname'];
        var middlename  =   searchdata['Data'][0]['employee_middlename'];
        var lastname    =   searchdata['Data'][0]['employee_lastname'];

        $('.EmpNamePassword').html(firstname +' '+ middlename +' '+ lastname);
        //.EmpNamePassword
    })
}

$('#changePasswordBtn').click(function(e)
{
    var user_id     =   $('#password_employee_id').val();
    var old_password    =   $('#old_password').val();
    var re_old_password    =   $('#re_old_password').val();
    var new_password    =   $('#new_password').val();
    var admin_password    =   $('#admin_password').val();

    if(old_password=='')
    {
        toastr.error("old password is required");
        $('#old_password').focus();
        return false;
    }
    else if(re_old_password=='')
    {
        toastr.error("re-enter old password is required");
        $('#re_old_password').focus();
        return false;
    }
    else if(old_password!=re_old_password)
    {
        toastr.error("old passwords not matched");
        $('#re_old_password').focus();
        return false;
    }
    else if(new_password=='')
    {
        toastr.error("new password is required");
        $('#new_password').focus();
        return false;
    }
    else if(admin_password=='')
    {
        toastr.error("admin password is required");
        $('#admin_password').focus();
        return false;
    }
    else
    {
        var fetch_data_url  =   'changePassword';
        // $('.loaderContainer').show();
        $.ajax({
            url:fetch_data_url,
            data: {
                user_id:user_id,
                old_password: old_password,
                re_old_password: re_old_password,
                new_password: new_password,
                admin_password: admin_password,
            },
            success:function(data)
            {
                var searchdata = JSON.parse(data, true);
                // $('.loaderContainer').hide();
                console.log(searchdata);

                if(searchdata['Success'] == "True")
                {
                    toastr.success(searchdata['Message']);
                    if(searchdata['url']!='')
                    {
                       window.location = searchdata['url'];
                    }
                }

                if(searchdata['Success'] == "False")
                {
                    toastr.error(searchdata['Message']);
                }

            }
        })
    }
});

$('#CreatePasswordBtn').click(function(e)
{

    var user_id             =   $('#new_password_employee_id').val();
    var new_password        =   $('#new_password_').val();
    var confirm_password    =   $('#confirm_password_').val();
    var admin_password      =   $('#admin_password_').val();

    if(new_password=='')
    {
        toastr.error("new password is required");
        $('#new_password_').focus();
        return false;
    }
    else if(confirm_password=='')
    {
        toastr.error("re-enter password is required");
        $('#confirm_password_').focus();
        return false;
    }
    else if(new_password!=confirm_password)
    {
        toastr.error("passwords not matched");
        $('#confirm_password_').focus();
        return false;
    }
    else if(admin_password=='')
    {
        toastr.error("admin password is required");
        $('#admin_password_').focus();
        return false;
    }
    else
    {
        var fetch_data_url  =   'createPassword';
        // $('.loaderContainer').show();
        $.ajax({
            url:fetch_data_url,
            data: {
                user_id:user_id,
                new_password: new_password,
                confirm_password: confirm_password,
                admin_password: admin_password,
            },
            success:function(data)
            {
                var searchdata = JSON.parse(data, true);
                // $('.loaderContainer').hide();
                console.log(searchdata);

                if(searchdata['Success'] == "True")
                {
                    toastr.success(searchdata['Message']);
                    if(searchdata['url']!='')
                    {
                       window.location = searchdata['url'];
                    }
                }

                if(searchdata['Success'] == "False")
                {
                    toastr.error(searchdata['Message']);
                }

            }
        })
    }
});
