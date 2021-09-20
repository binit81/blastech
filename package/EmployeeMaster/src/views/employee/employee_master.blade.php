@include('pagetitle')
@extends('master')

@section('main-hk-pg-wrapper')

<style type="text/css">
.modal-dialog {
    max-width: 90% !important;
}
</style>


<link rel="stylesheet" href="{{URL::to('/')}}/public/bower_components/bootstrap-datepicker/css/bootstrap-datepicker.css">
<div class="container ml-20">


    <?php if($role_permissions['permission_export']==1){?>
    <span class="commonbreadcrumbtn badge exportBtn badge-pill mr-10" id="exportEmployeedata"><i class="ion ion-md-download"></i>&nbsp;Download Employee Data</span>
    <?php }?>

    <span class="commonbreadcrumbtn badge badge-danger badge-pill" id="searchCollapse"><i class="glyphicon glyphicon-search"></i>&nbsp;Search</span>

    <?php if($role_permissions['permission_add']==1){?>
    <span class="commonbreadcrumbtn badge badge-Info badge-pill addnewRole" onclick="addnewRole()"><i class="fa fa-plus"></i>&nbsp;New Permission</span>

    <span class="commonbreadcrumbtn badge badge-primary badge-pill" onclick="addnewEmployee()"><i class="fa fa-plus"></i>&nbsp;Add New Employee</span>
    <?php }?>

    <input type="hidden" id="permission_edit" value="<?php echo $role_permissions['permission_edit']?>" />
    <input type="hidden" id="permission_delete" value="<?php echo $role_permissions['permission_delete']?>" />

    <div class="row">
    <div class="col-xl-12"> 
        <section class="hk-sec-wrapper pt-15 pl-15 pb-0 collapse" id="searchbox">
            <div class="row pa-0">

                <div class="col-sm-2">                            
                     <div class="form-group">
                      <input type="text" name="employeeName" id="employeeName" class="form-control form-inputtext" placeholder="By Employee Name" autocomplete="off">
                    </div>
                </div>
                <div class="col-sm-2">                            
                     <div class="form-group">
                      <input type="text" name="mobileNo" id="mobileNo" class="form-control form-inputtext" placeholder="By Mobile No" autocomplete="off">
                    </div>
                </div>

                <div class="col-sm-2">                            
                     <div class="form-group">
                      <input type="text" name="empCode" id="empCode" class="form-control form-inputtext" placeholder="By Employee Code" autocomplete="off">
                    </div>
                </div>
                <div class="col-sm-2">                            
                     <div class="form-group">
                      <input type="text" name="empDesignation" id="empDesignation" class="form-control form-inputtext" placeholder="By Designation" autocomplete="off">
                    </div>
                </div>

                <div class="col-sm-2">                            
                     <div class="form-group">
                        <input type="radio" name="statusType" value="1" autocomplete="off">&nbsp;Active&nbsp;
                        <input type="radio" name="statusType" value="0" autocomplete="off">&nbsp;Inactive&nbsp;                         
                    </div>
                </div>
                <div class="col-sm-2">
                    <button type="button" class="btn searchBtn" id="SearchEmployeeData"><i class="fa fa-search"></i>Search</button>
                </div>
            </div>         
        </section>
    </div> 
</div>

    <div class="row">
        <div class="col-xl-12">
        <section class="hk-sec-wrapper pa-10">
    <div class="col-sm pa-0">
        <div class="table-wrap">
            <div class="table-responsive pa-0">
                <table class="table tablesaw table-bordered table-hover table-striped mb-0" data-tablesaw-mode="swipe"  data-tablesaw-sortable data-tablesaw-minimap data-tablesaw-mode-switch>
                    <thead class="thead-primary">
                        <tr class="header">
                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1" class="">&nbsp;&nbsp;Action</th>
                            <th >&nbsp;</th>
                            <th class="pa-10 leftAlign" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Name<br><small>Designation</small></th>
                            <th class="leftAlign" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Mobile No.</th>
                            <th>Email</th>
                            <th class="leftAlign" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Permission&nbsp;&nbsp;</th>
                            <th class="leftAlign" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">Duties</th>
                            <th class="leftAlign" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">Skills</th>
                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="7" class="leftAlign">Remarks</th>
                            <th class="center" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">Joining Date</th>
                            <th class="center" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="9">Status</th>
                            
                        </tr>
                    </thead>
                    <tbody id="employeesearchResult">
                        @include('employee::employee/view_employee_data')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
</div>
</div>

</div>

<!-- Show Resume -->
<div class="modal fade" id="showResume" tabindex="-1" role="dialog" aria-labelledby="showResume" aria-hidden="true">
    
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                
                <h5 class="modal-title">
                    <span id="employeeFullname" class="uppercase"></span>
                    <span class="badge badge-primary mt-15 mr-0" id="employeeRoleBadge"></span>
                </h5>

                <div class="ShowActionButtons" style="margin-left:550px;"></div>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">           

                <table width="100%" border="0" cellpadding="6" cellspacing="2">
                  <tr>
                    <td width="20%" align="center" valign="top"><span id="emp_profilepic"></span></td>
                    <td width="40%" valign="top">
                        <div class="pb-5">Date of Birth: <span id="emp_dob" class="bold"></span></div>
                        <div class="pb-5">Employee Code: <span id="emp_code" class="bold"></span></div>
                        <div class="pb-5">Email Address: <span id="emp_email" class="bold"></span></div>
                        <div class="pb-5">Mobile No.: <span id="emp_mobile" class="bold"></span></div>
                        <div class="pb-5">Alternate Phone No.: <span id="emp_alternate" class="bold"></span></div>
                        <div class="pb-5">Family Member Phone No.: <span id="emp_family" class="bold"></span></div>
                        <div class="pb-5">Joining Date: <span id="emp_joining" class="bold"></span></div>
                        <div class="pb-5">Salary Offered: <span id="emp_salary" class="bold"></span></div>
                        <div class="pb-5">Marital Status: <span id="emp_marital" class="bold"></span></div>
                    </td>
                    <td width="40%" align="left" valign="top">
                        <div class="pb-5"><div class="pb-5">Address: <span id="emp_address" class="bold"></span></div>
                        <div class="pb-5">Area: <span id="emp_area" class="bold"></span></div>
                        <div class="pb-5">City / Town: <span id="emp_city" class="bold"></span></div>
                        <div class="pb-5">State: <span id="emp_state" class="bold"></span></div>
                        <div class="pb-5">Country: <span id="emp_country" class="bold"></span></div>
                        <div class="pb-5">Postal / ZipCode: <span id="emp_postal" class="bold"></span></div>
                    </td>
                  </tr>
                  <tr>
                    </table>
                    <table width="100%" border="0" cellpadding="6" cellspacing="2">
                      <tr>
                        <td bgcolor="#D6D6D6">Designation</td>
                        <td bgcolor="#D6D6D6">Duties</td>
                        <td bgcolor="#D6D6D6">Education</td>
                        </tr>
                      <tr>
                        <td valign="top"><span id="emp_designation"></span></td>
                        <td valign="top"><span id="emp_duties"></span></td>
                        <td valign="top"><span id="emp_education"></span></td>
                        </tr>
                      <tr>
                        <td bgcolor="#D6D6D6">Skills</td>
                        <td bgcolor="#D6D6D6">Past Experience</td>
                        <td bgcolor="#D6D6D6">References</td>
                        </tr>
                      <tr>
                        <td valign="top"><span id="emp_skills"></span></td>
                        <td valign="top"><span id="emp_pastexp"></span></td>
                        <td valign="top"><span id="emp_references"></span></td>
                        </tr>
                      <tr>
                        <td bgcolor="#D6D6D6">Resigned Date</td>
                        <td bgcolor="#D6D6D6">Resigned Reason</td>
                        <td bgcolor="#D6D6D6">Remarks</td>
                        </tr>
                      <tr>
                        <td valign="top"><span id="emp_regsineddate"></span></td>
                        <td valign="top"><span id="emp_reason"></span></td>
                        <td valign="top"><span id="emp_remarks"></span></td>
                      </tr>
                    </table>
                  

            </div>
            
        </div>
        
    </div>
</div>
<!-- Set Role -->

<!-- New Password -->
<div class="modal fade" id="newPassword" tabindex="-1" role="dialog" aria-labelledby="newPassword" aria-hidden="true">
    
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Password <span class="badge badge-primary mt-15 mr-10 EmpNamePassword" id=""></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">           
              
              <div class="row">
                    <div class="col-md-4">
                        New Password<br>
                        <input type="password" name="new_password_" id="new_password_" class="form-control form-inputtext invalid"/>
                    </div>
                    <div class="col-md-4">
                        Re-enter Password<br>
                        <input type="password" name="confirm_password_" id="confirm_password_" class="form-control form-inputtext invalid"/>
                    </div>
                    <div class="col-md-4">
                        Admin Password<br>
                        <input type="password" name="admin_password_" id="admin_password_" class="form-control form-inputtext invalid"/>
                        <i class="fa fa-eye cursor eyePassword"></i>
                    </div>
                </div>

            </div>
            <div class="modal-footer employeeRoleButton">
                <div class="col-md-6">
                    
                </div>
                <div class="col-md-6" style="text-align:right;">
                    <input type="hidden" name="new_password_employee_id" id="new_password_employee_id" />
                    <button type="button" name="CreatePasswordBtn" id="CreatePasswordBtn" class="btn btn-primary savebtn"><i class="fa fa-save"></i>Create Password</button>
                </div>
            </div>
            
        </div>
        
    </div>
</div>
<!-- New Password -->

<!-- Set Role -->
<div class="modal fade" id="setRole" tabindex="-1" role="dialog" aria-labelledby="setRole" aria-hidden="true">
    
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Set Employee software permissions <span class="badge badge-danger mt-15 mr-10" id="RoleNamebadge" style="font-size:18px;"></span><span class="badge badge-primary mt-15 mr-0" id="Rolebadge"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">           
                
                <small>Change Software Permission:</small> 
                <?php
                foreach($roles as $k=>$val)
                {
                    ?>
                    <span class="badge badge-secondary mt-15 mr-0 cursor roleBadge" id="roleBadge{{$val->employee_role_id}}" onclick="showPermissions({{$val->employee_role_id}})">{{$val->role_name}}</span>
                    <?php
                }
                ?>

                <button type="button" name="ApplyRoletoEmployee" id="ApplyRoletoEmployee" class="btn btn-primary mb-20 mt-0 savebtn pull-right employeeRoleButton ApplyRoletoEmployee" style="display:none; margin-top:-8px !important;"><i class="fa fa-save"></i>Apply Permission</button>

                <hr class="pa-0 ma-0 mt-10 employeeRoleButton" style="display:none" />

                <div class="row rolePermissions">

                </div>

            </div>
            <div class="modal-footer employeeRoleButton" style="display:none">
                <div class="col-md-6">
                    
                </div>
                <div class="col-md-6" style="text-align:right;">
                    <input type="hidden" name="role_id" id="role_id" />
                    <input type="hidden" name="employee_id" id="employee_id" />
                    <button type="button" name="ApplyRoletoEmployee" id="ApplyRoletoEmployee" style="display:none;" class="btn btn-primary savebtn employeeRoleButton ApplyRoletoEmployee"><i class="fa fa-save"></i>Apply Permission</button>
                </div>
            </div>
            
        </div>
        
    </div>
</div>
<!-- Set Role -->

<!-- Add New Role -->
<div class="modal fade" id="addnewRole" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="addnewRole" aria-hidden="true">
    
    <div class="modal-dialog modal-lg" role="document">
        <form method="post" id="roleForm" name="roleForm">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Permission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" style="">
            
            
                <div class="row">
                    <div class="col-md-5 pa-0 ma-0">
                        Permission Name <input type="text" name="role_name" id="role_name" class="form-control form-inputtext invalid" style="margin-bottom:5px !important;"/><br>
                        <small class="pb-10 ma-0"><i>Note: type the <b><u>Permission Name</u></b> and then press tab key <img src="{{URL::to('/')}}/public/images/tab.jpg" title="tab" width="50" class="blinking" /> to enable the permission section.</i></small>
                    </div>
                    <div class="col-md-1 pa-0 ma-0"></div>
                    <div class="col-md-6 pa-0 ma-0">
                        
                        <?php
                        if(sizeof($roles))
                        {
                            ?>
                            <br clear="all" /> <small>Created Permissions:</small> 
                            <?php
                            foreach($roles as $k=>$val)
                            {
                                ?>
                                <span class="badge badge-secondary mt-15 mr-0 cursor roleBadge_" id="roleBadge_{{$val->employee_role_id}}" onclick="editPermission('{{$val->employee_role_id}}')" title="Edit Permissions">{{$val->role_name}} <i class="fa fa-pencil"></i></span>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>

                <hr class="pa-0 ma-0 mt-10 enableRole" style="display:none" />

                <div class="col-md-12 pa-0 ma-0 roleForm">

                </div>

            
           
            </div>
            <div class="modal-footer enableRole" style="display:none">
                <div class="col-md-6">
                    
                </div>
                <div class="col-md-6" style="text-align:right;">
                    <input type="hidden" class="alertStatus" value="0" />
                    <input type="hidden" name="employee_role_id" id="employee_role_id" />
                    <button type="button" name="saveEmployeeRole" id="saveEmployeeRole" class="btn btn-primary savebtn"><i class="fa fa-save"></i>Create Permission</button>
                </div>
            </div>
            
        </div>
        </form>
    </div>
</div>
<!-- Add New Role -->

<!-- Add New Employee -->
<div class="modal fade" id="addnewEmployee" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Employee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body" style="">
            <form name="employee_form" id="employee_form" method="post" enctype="multipart/form-data">  
                <input type="hidden" name="user_id" id="user_id" value="">
                <meta name="csrf-token" content="{{ csrf_token() }}" />
                <div class="row">
                    <div class="col-md-3">
                        First Name<br>
                        <input type="text" name="employee_firstname" id="employee_firstname" class="form-control form-inputtext invalid"/>
                    </div>
                    <div class="col-md-3">
                        Middle Name<br>
                        <input type="text" name="employee_middlename" id="employee_middlename" class="form-control form-inputtext invalid"/>
                    </div>
                    <div class="col-md-3">
                        Last Name<br>
                        <input type="text" name="employee_lastname" id="employee_lastname" class="form-control form-inputtext invalid"/>
                    </div>
                    <div class="col-md-3">
                        Employee Code<br>
                        <input type="text" name="employee_code" id="employee_code" class="form-control form-inputtext"/>
                    </div>
                    
                </div>

                <div class="row">
                    <div class="col-md-3">
                        Email<br>
                        <input type="text" name="email" id="email" class="form-control form-inputtext invalid"/>
                    </div>
                    <div class="col-md-3 mobno">
                        Mobile No.<br>
                        <input type="tel" style="width: 100%;" name="employee_mobileno" id="employee_mobileno" maxlength="15" class="form-control form-inputtext invalid mobileregax">
                        <input type="hidden" name="employee_mobileno_dial_code" id="employee_mobileno_dial_code" value="">
                    </div>
                    
                    <div class="col-md-3 altmobno">
                        Alternate Phone No.<br>
                        <input type="tel" style="width: 100%;" name="employee_alternate_mobile" id="employee_alternate_mobile" maxlength="15" class="form-control form-inputtext mobileregax">
                        <input type="hidden" name="employee_alternate_mobile_dial_code" id="employee_alternate_mobile_dial_code" value="">
                    </div>
                    <div class="col-md-3 fammobno">
                        Family Member Phone No.<br>
                        <input type="tel" style="width: 100%;" name="employee_family_member_mobile" id="employee_family_member_mobile" maxlength="15" class="form-control form-inputtext mobileregax">
                        <input type="hidden" name="employee_family_member_mobile_dial_code" id="employee_family_member_mobile_dial_code" value="">
                    </div>
                    
                </div>

                <div class="row">
                    <div class="col-md-3">
                        Joining Date<br>
                        <input type="text" name="employee_joiningdate" id="employee_joiningdate" placeholder="DD-MM-YYYY" class="form-control form-inputtext invalid"/>
                    </div>
                    <div class="col-md-3">
                        Designation<br>
                        <input type="text" name="employee_designation" id="employee_designation" class="form-control form-inputtext"/>
                    </div>
                    <div class="col-md-3">
                        Duties<br>
                        <textarea name="employee_duties" id="employee_duties" class="form-control form-inputtext"></textarea>
                    </div>
                    <div class="col-md-3">
                        Salary Offered <small>(per month)</small><br>
                        <input type="text" name="employee_salary_offered" id="employee_salary_offered" maxlength="6" class="form-control form-inputtext mobileregax"/>
                    </div>
                    
                </div>

                <div class="row">
                    <div class="col-md-3">
                        Skills<br>
                        <textarea name="employee_skills" id="employee_skills" class="form-control form-inputtext"></textarea>
                    </div>
                    <div class="col-md-3">
                        Education<br>
                        <textarea name="employee_education" id="employee_education" class="form-control form-inputtext"></textarea>
                    </div>
                    <div class="col-md-3">
                        Past Experience<br>
                        <textarea name="employee_past_experience" id="employee_past_experience" class="form-control form-inputtext"></textarea>
                    </div>
                    <div class="col-md-3">
                        Date of Birth<br>
                        <input type="text" name="employee_dob" id="employee_dob" placeholder="DD-MM-YYYY" class="form-control form-inputtext"/>
                    </div>
                    
                </div>

                <div class="row">
                    <div class="col-md-3">
                        Marital Status<br>
                        <div class="form-group">
                            <input type="radio" name="employee_marital_status" value="1" autocomplete="off" checked>&nbsp;Single&nbsp;
                            <input type="radio" name="employee_marital_status" value="2" autocomplete="off">&nbsp;Married&nbsp;  
                            <input type="radio" name="employee_marital_status" value="3" autocomplete="off">&nbsp;Divorced&nbsp;
                            <input type="radio" name="employee_marital_status" value="4" autocomplete="off">&nbsp;Widow&nbsp;                         
                        </div>
                    </div>
                    <div class="col-md-3">
                        Address <small style="font-size:10px">(House No., Building, Street name etc)</small><br>
                        <textarea name="employee_address" id="employee_address" placeholder="Address" class="form-control form-inputtext"></textarea>
                    </div>
                    <div class="col-md-3">
                        Area<br>
                        <input type="text" name="employee_area" id="employee_area" class="form-control form-inputtext"/>
                    </div>
                    <div class="col-md-3">
                        City / Town<br>
                        <input type="text" name="employee_city_town" id="employee_city_town" class="form-control form-inputtext"/>
                    </div>
                    
                </div>

                <div class="row">
                    <div class="col-md-3">
                        State<br>
                        <select class="form-control form-inputtext" name="state_id" id="state_id">
                        <option value=""></option>    
                            @foreach($state AS $state_key=>$state_value)
                                <option value="{{$state_value->state_id}}">{{$state_value->state_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        Postal / Zip Code<br>
                        <input type="text" name="employee_zipcode" id="employee_zipcode" class="form-control form-inputtext"/>
                    </div>
                    <div class="col-md-3">
                        Country<br>
                        <select class="form-control form-inputtext" name="country_id" id="country_id">
                            <option value=""></option>  
                            @foreach($country AS $country_key=>$country_value)
                                <option value="{{$country_value->country_id}}">{{$country_value->country_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 employeePicture">
                        Add Profile Picture<br>
                        <input type="file" class="" id="employee_picture" name="employee_picture" accept=".jpeg, .jpg, .png, .gif" autocomplete="off">
                        <small>.jpeg, .jpg, .png, .gif</small>
                        <input type="hidden" name="chkPicture" id="chkPicture" value="" />
                    </div>
                    
                </div>

                <div class="row pt-15">
                    <div class="col-md-3">
                        References<br>
                        <textarea id="employee_reference" name="employee_reference" class="form-control form-inputtext"></textarea>
                    </div>
                    <div class="col-md-3">
                        Resigned Date<br>
                        <input type="text" name="employee_resigned_date" id="employee_resigned_date" placeholder="DD-MM-YYYY" class="form-control form-inputtext"/>
                    </div>
                    <div class="col-md-3">
                        Resigned Reason<br>
                        <textarea id="employee_resigned_reason" name="employee_resigned_reason" class="form-control form-inputtext"></textarea>
                    </div>
                    <div class="col-md-3">
                        Remarks<br>
                        <textarea id="employee_remarks" name="employee_remarks" class="form-control form-inputtext"></textarea>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-md-3 RedBackground bold setPasswordDiv">
                        <span class="PasswordLabel">Set Password?</span> <input type="checkbox" name="employee_login" class="employee_login" value="1">
                        <span class="loginData"></span>
                    </div>


                    <div class="col-md-2 loginYesNo abcX" style="display:none;">
                        Password<br>
                        <input type="password" name="password" id="password" onkeyup="validateRole()" class="form-control form-inputtext invalid"/>

                    </div>

                    <div class="col-md-2 loginYesNo abcX" style="display:none;">
                        Re-enter Password<br>
                        <input type="password" name="encrypt_password" id="encrypt_password" class="form-control form-inputtext  invalid"/>
                        <i class="fa fa-eye cursor eyePassword extraeye"></i>
                    </div>

                    <div class="col-md-2 adminPassBox" style="display:none;">
                        Admin Password<br>
                        <input type="password" name="adminpassword" id="adminpassword" class="form-control form-inputtext  invalid"/>
                        
                        <i class="fa fa-eye cursor eyePassword"></i>
                    </div>
                    
                    <div class="col-md-3 setPasswordDiv loginYesNo" style="display:none;">
                        Select Permission<br>
                        <?php
                        if(sizeof($roles)!=0)
                        {
                            ?>
                            <select name="employee_role_id_" id="employee_role_id_" class="form-control form-inputtext">
                            <option value=""></option>
                            @foreach($roles AS $roleskey=>$rolesvalue)
                            <option value="{{$rolesvalue->employee_role_id}}">{{$rolesvalue->role_name}}</option>
                            @endforeach
                            </select>
                            <?php
                        }
                        ?>
                    </div> 
                </div>

            </div>
            <div class="modal-footer">
                <div class="col-md-6">
                    
                </div>
                <div class="col-md-6" style="text-align:right">
                    <input type="hidden" class="alertStatus" value="0" />
                    <input type="hidden" name="is_master" id="is_master" value="" />
                    <input type="hidden" name="user_id_" id="user_id_" value="" />
                    <input type="hidden" name="old_password_" id="old_password_" class="form-control form-inputtext"/>
                    <input type="hidden" name="old_encrypt_password_" id="old_encrypt_password_" class="form-control form-inputtext"/>
                    <button type="submit" name="saveEmployeeData" id="saveEmployeeData" class="btn btn-primary savebtn"><i class="fa fa-save"></i>Save Employee</button>
                </div>
            </div>
             </form>
        </div>
    </div>
</div>
<!-- Add New Employee -->


<link rel="stylesheet" href="{{URL::to('/')}}/public/build/css/intlTelInput.css">


    </div>

    

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

        <script src="{{URL::to('/')}}/public/modulejs/EmployeeMaster/employee.js"></script>
        <script src="{{URL::to('/')}}/public/modulejs/EmployeeMaster/employee_role.js"></script>
@endsection

