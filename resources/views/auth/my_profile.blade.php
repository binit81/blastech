@include('pagetitle')
@extends('master')

@section('main-hk-pg-wrapper')

<link rel="stylesheet" href="{{URL::to('/')}}/public/bower_components/bootstrap-datepicker/css/bootstrap-datepicker.css">

<form name="my_employee_form" id="my_employee_form" method="post" enctype="multipart/form-data">
<input type="hidden" name="user_id" id="user_id" value="<?php echo $result[0]['user_id']?>">
<meta name="csrf-token" content="{{ csrf_token() }}" />

<div class="container ml-20">
    <div class="row">
        <div class="col-md-3">
            First Name<br>
            <input type="text" name="employee_firstname" id="employee_firstname" class="form-control form-inputtext invalid" value="{{$result[0]['employee_firstname']}}"/>
        </div>
        <div class="col-md-3">
            Middle Name<br>
            <input type="text" name="employee_middlename" id="employee_middlename" class="form-control form-inputtext invalid" value="{{$result[0]['employee_middlename']}}"/>
        </div>
        <div class="col-md-3">
            Last Name<br>
            <input type="text" name="employee_lastname" id="employee_lastname" class="form-control form-inputtext invalid" value="{{$result[0]['employee_lastname']}}"/>
        </div>
        <div class="col-md-3">
            Employee Code<br>
            <input type="text" name="employee_code" id="employee_code" class="form-control form-inputtext" value="{{$result[0]['employee_code']}}"/>
        </div>
        
    </div>

    <div class="row">
        <div class="col-md-3">
            Email<br>
            <input type="text" name="email" id="email" class="form-control form-inputtext invalid" value="{{$result[0]['email']}}"/>
        </div>
        <div class="col-md-3 mobno">
            Mobile No.<br>
            <input type="tel" style="width: 100%;" name="employee_mobileno" id="employee_mobileno" maxlength="15" class="form-control form-inputtext invalid mobileregax" value="{{$result[0]['employee_mobileno']}}">
            <input type="hidden" name="employee_mobileno_dial_code" id="employee_mobileno_dial_code" value="">
        </div>
        
        <div class="col-md-3 altmobno">
            Alternate Phone No.<br>
            <input type="tel" style="width: 100%;" name="employee_alternate_mobile" id="employee_alternate_mobile" maxlength="15" class="form-control form-inputtext mobileregax" value="{{$result[0]['employee_alternate_mobile']}}">
            <input type="hidden" name="employee_alternate_mobile_dial_code" id="employee_alternate_mobile_dial_code" value="">
        </div>
        <div class="col-md-3 fammobno">
            Family Member Phone No.<br>
            <input type="tel" style="width: 100%;" name="employee_family_member_mobile" id="employee_family_member_mobile" maxlength="15" class="form-control form-inputtext mobileregax" value="{{$result[0]['employee_family_member_mobile']}}">
            <input type="hidden" name="employee_family_member_mobile_dial_code" id="employee_family_member_mobile_dial_code" value="">
        </div>
        
    </div>

    <div class="row">
        <div class="col-md-3">
            Joining Date<br>
            <input type="text" name="employee_joiningdate" id="employee_joiningdate" placeholder="DD-MM-YYYY" class="form-control form-inputtext invalid" value="{{date('d-m-Y', strtotime($result[0]['employee_joiningdate']))}}"/>
        </div>
        <div class="col-md-3">
            Designation<br>
            <input type="text" name="employee_designation" id="employee_designation" class="form-control form-inputtext" value="{{$result[0]['employee_designation']}}"/>
        </div>
        <div class="col-md-3">
            Duties<br>
            <textarea name="employee_duties" id="employee_duties" class="form-control form-inputtext">{{$result[0]['employee_duties']}}</textarea>
        </div>
        <div class="col-md-3">
            Salary Offered <small>(per month)</small><br>
            <input type="text" name="employee_salary_offered" id="employee_salary_offered" maxlength="6" class="form-control form-inputtext mobileregax" value="{{$result[0]['employee_salary_offered']}}"/>
        </div>
        
    </div>

    <div class="row">
        <div class="col-md-3">
            Skills<br>
            <textarea name="employee_skills" id="employee_skills" class="form-control form-inputtext">{{$result[0]['employee_skills']}}</textarea>
        </div>
        <div class="col-md-3">
            Education<br>
            <textarea name="employee_education" id="employee_education" class="form-control form-inputtext">{{$result[0]['employee_education']}}</textarea>
        </div>
        <div class="col-md-3">
            Past Experience<br>
            <textarea name="employee_past_experience" id="employee_past_experience" class="form-control form-inputtext">{{$result[0]['employee_past_experience']}}</textarea>
        </div>
        <div class="col-md-3">
            Date of Birth<br>
            <input type="text" name="employee_dob" id="employee_dob" placeholder="DD-MM-YYYY" class="form-control form-inputtext" value="{{$result[0]['employee_dob']==''?'':date('d-m-Y', strtotime($result[0]['employee_dob']))}}"/>
        </div>
        
    </div>

    <div class="row">
        <div class="col-md-3">
            Marital Status<br>
            <div class="form-group">
                <input type="radio" name="employee_marital_status" value="1" autocomplete="off" <?php echo $result[0]['employee_marital_status']==1?'checked':'';?>>&nbsp;Single&nbsp;
                <input type="radio" name="employee_marital_status" value="2" autocomplete="off" <?php echo $result[0]['employee_marital_status']==2?'checked':'';?>>&nbsp;Married&nbsp;  
                <input type="radio" name="employee_marital_status" value="3" autocomplete="off" <?php echo $result[0]['employee_marital_status']==3?'checked':'';?>>&nbsp;Divorced&nbsp;
                <input type="radio" name="employee_marital_status" value="4" autocomplete="off" <?php echo $result[0]['employee_marital_status']==4?'checked':'';?>>&nbsp;Widow&nbsp;                         
            </div>
        </div>
        <div class="col-md-3">
            Address <small style="font-size:10px">(House No., Building, Street name etc)</small><br>
            <textarea name="employee_address" id="employee_address" placeholder="Address" class="form-control form-inputtext">{{$result[0]['employee_address']}}</textarea>
        </div>
        <div class="col-md-3">
            Area<br>
            <input type="text" name="employee_area" id="employee_area" class="form-control form-inputtext" value="{{$result[0]['employee_area']}}"/>
        </div>
        <div class="col-md-3">
            City / Town<br>
            <input type="text" name="employee_city_town" id="employee_city_town" class="form-control form-inputtext" value="{{$result[0]['employee_city_town']}}"/>
        </div>
        
    </div>

    <div class="row">
        <div class="col-md-3">
            State<br>
            <select class="form-control form-inputtext" name="state_id" id="state_id">
            <option value=""></option>    
                @foreach($state AS $state_key=>$state_value)
                    <?php if($result[0]['state_id']==$state_value->state_id){?>
                        <option value="{{$state_value->state_id}}" selected>{{$state_value->state_name}}</option>
                    <?php } else {?>
                        <option value="{{$state_value->state_id}}">{{$state_value->state_name}}</option>
                    <?php } ?>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            Postal / Zip Code<br>
            <input type="text" name="employee_zipcode" id="employee_zipcode" class="form-control form-inputtext" value="{{$result[0]['employee_zipcode']}}"/>
        </div>
        <div class="col-md-3">
            Country<br>
            <select class="form-control form-inputtext" name="country_id" id="country_id">
                <option value=""></option>  
                @foreach($country AS $country_key=>$country_value)
                    <?php if($result[0]['country_id']==$country_value->country_id){?>
                        <option value="{{$country_value->country_id}}" selected>{{$country_value->country_name}}</option>
                    <?php } else {?>
                        <option value="{{$country_value->country_id}}">{{$country_value->country_name}}</option>
                    <?php }?>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 employeePicture">
            <?php
            if($result[0]['employee_picture']=='')
            {
                ?>
                Add Profile Picture<br>
                <input type="file" class="" id="employee_picture" name="employee_picture" accept=".jpeg, .jpg, .png, .gif" autocomplete="off">
                <small>Max Image Size: <b>2mb.</b><br>Accepted Formats: <b>.jpeg, .jpg, .png, .gif</b></small>
                <input type="hidden" name="chkPicture" id="chkPicture" value="" />
                <?php
            }
            else
            {
                ?>
                <div class="media-img-wrap d-flex mr-10 pull-right center"><div class="avatar"><img src="<?php echo EMPLOYEE_IMAGE_URL.$result[0]['employee_picture']?>" class="img-fluid img-thumbnail" alt="img"><small class="cursor" onclick="removePicture_('<?php echo $result[0]['user_id']?>')">remove</small><input type="hidden" name="chkPicture" id="chkPicture" value="<?php echo $result[0]['employee_picture'];?>" /></div></div>
                <?php
            }
            ?>
        </div>
        
    </div>

    <div class="row pt-15">
        <div class="col-md-3">
            References<br>
            <textarea id="employee_reference" name="employee_reference" class="form-control form-inputtext">{{$result[0]['employee_reference']}}</textarea>
        </div>
        <div class="col-md-3">
            Resigned Date<br>
            <input type="text" name="employee_resigned_date" id="employee_resigned_date" placeholder="DD-MM-YYYY" class="form-control form-inputtext" value="{{$result[0]['employee_resigned_date']==''?'':date('d-m-Y', strtotime($result[0]['employee_resigned_date']))}}"/>
        </div>
        <div class="col-md-3">
            Resigned Reason<br>
            <textarea id="employee_resigned_reason" name="employee_resigned_reason" class="form-control form-inputtext">{{$result[0]['employee_resigned_reason']}}</textarea>
        </div>
        <div class="col-md-3">
            Remarks<br>
            <textarea id="employee_remarks" name="employee_remarks" class="form-control form-inputtext">{{$result[0]['employee_remarks']}}</textarea>
        </div>
        
    </div>
    <div class="row">
        <div class="col-md-3 RedBackground bold setPasswordDiv">
            <span class="PasswordLabel">Change Password?</span> <input type="checkbox" name="employee_login_" class="employee_login_" value="1">
            <span class="loginData"></span>
        </div>


        <div class="col-md-3 loginYesNo abcX" style="display:none;">
            Password<br>
            <input type="password" name="password" id="password" onkeyup="validateRole()" class="form-control form-inputtext invalid"/>

        </div>

        <div class="col-md-3 loginYesNo abcX" style="display:none;">
            Re-enter Password<br>
            <input type="password" name="encrypt_password" id="encrypt_password" class="form-control form-inputtext  invalid"/>
            <i class="fa fa-eye cursor eyePassword extraeye"></i>
        </div>

        <div class="col-md-3">
            <br>
            <input type="hidden" class="alertStatus" value="0" />
            <input type="hidden" name="adminpassword" id="adminpassword" value="" />
            <input type="hidden" name="is_master" id="is_master" value="<?php echo $result[0]['is_master']?>" />
            <input type="hidden" name="employee_login" id="employee_login" value="1" />
            <input type="hidden" name="employee_role_id_" id="employee_role_id_" value="<?php echo $result[0]['employee_role_id']?>" />
            <input type="hidden" name="user_id_" id="user_id_" value="<?php echo $result[0]['user_id']?>" />
            <input type="hidden" name="old_password_" id="old_password_" value="<?php echo $result[0]['password']?>" class="form-control form-inputtext"/>
            <input type="hidden" name="old_encrypt_password_" id="old_encrypt_password_" value="<?php echo $result[0]['encrypt_password']?>" class="form-control form-inputtext"/>
            <button type="submit" name="saveEmployeeData" id="saveEmployeeData" class="btn btn-primary savebtn"><i class="fa fa-save"></i>Update Profile</button>
        </div>
                    
                    
</div>
</form>

<link rel="stylesheet" href="{{URL::to('/')}}/public/build/css/intlTelInput.css">

<script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>
    <script src="{{URL::to('/')}}/public/dist/js/moment.min.js"></script>
    <script src="{{URL::to('/')}}/public/dist/js/daterangepicker.js"></script>

    <script type="text/javascript">
      $('.daterange').daterangepicker({ 

                         
                autoUpdateInput: false,        
           
                },function(start_date, end_date) {

            
        $('.daterange').val(start_date.format('DD-MM-YYYY')+' - '+end_date.format('DD-MM-YYYY'));

                     var inoutdate         =     $("#fromtodate").val();
                 

                    var totalnights       =     inoutdate.split(' - ');
                    $("#from_date").val(totalnights[0]);
                    $("#to_date").val(totalnights[1]);  
        });
    </script>

    

        <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/popper.js/js/popper.min.js"></script>
        <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="{{URL::to('/')}}/public/dist/js/datepicker.js"></script>
        <script type="text/javascript" src="{{URL::to('/')}}/public/build/js/intlTelInput.js"></script>
        <script type="text/javascript" src="{{URL::to('/')}}/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>

        <script type="text/javascript">
        $(document).ready(function(e){
            $('#searchCollapse').click(function(e){
                $('#searchbox').slideToggle();
            })

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
        })

        $(document).ready(function () {
            selectdiacode();
        });

        function selectdiacode() {
            var company_mobile = document.querySelector("#employee_mobileno");
            var company_dial_code = "";
            var company_mobile_dial_code_edit = "in";
      
            window.intlTelInput(company_mobile,
            {
                initialCountry: company_mobile_dial_code_edit,
                separateDialCode: true,
                autoHideDialCode: false,
                utilsScript: "{{URL::to('/')}}/public/build/js/utils.js",
            });

            var employee_alternate_mobile = document.querySelector("#employee_alternate_mobile");
            var company_dial_code = "";
            var company_mobile_dial_code_edit = "in";
      
            window.intlTelInput(employee_alternate_mobile,
            {
                initialCountry: company_mobile_dial_code_edit,
                separateDialCode: true,
                autoHideDialCode: false,
                utilsScript: "{{URL::to('/')}}/public/build/js/utils.js",
            });

            var employee_family_member_mobile = document.querySelector("#employee_family_member_mobile");
            var company_dial_code = "";
            var company_mobile_dial_code_edit = "in";
      
            window.intlTelInput(employee_family_member_mobile,
            {
                initialCountry: company_mobile_dial_code_edit,
                separateDialCode: true,
                autoHideDialCode: false,
                utilsScript: "{{URL::to('/')}}/public/build/js/utils.js",
            });
        }
        </script>

        <script type="text/javascript">
        //     CKEDITOR.replace('edit_template_data', {
        //     height: ['150px']
        // });
        //     CKEDITOR.replace('template_data', {
        //     height: ['150px']
        // });
        //     CKEDITOR.config.allowedContent = true;
        </script>

@endsection