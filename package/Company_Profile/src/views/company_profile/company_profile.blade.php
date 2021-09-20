<?php
/**
 * Created by PhpStorm.
 * User: Hemaxi
 * Date: 22/3/19
 * Time: 12:25 PM
 */
?>


@include('pagetitle')
@extends('master')

@section('main-hk-pg-wrapper')

    <link rel="stylesheet" href="{{URL::to('/')}}/public/build/css/intlTelInput.css">

    <div class="container" id="cmpform">
    <section class="">

        <form name="company_profile_form" id="company_profile_form" type="post">
            <?php
                $company_profile_id = (isset($company_profile) && $company_profile['company_profile_id'] != '' ?$company_profile['company_profile_id'] : '');
                $fullname = (isset($company_profile) && $company_profile['full_name'] != '' ?$company_profile['full_name'] : '');
                $personal_mobile_dial_code = (isset($company_profile) && $company_profile['personal_mobile_dial_code'] != '' ?$company_profile['personal_mobile_dial_code'] : '');
                $personal_mobile_no = (isset($company_profile) && $company_profile['personal_mobile_no'] != '' ?$company_profile['personal_mobile_no'] : '');
                $personal_email = (isset($company_profile) && $company_profile['personal_email'] != '' ?$company_profile['personal_email'] : '');
                $company_name = (isset($company_profile) && $company_profile['company_name'] != '' ?$company_profile['company_name'] : '');
                $company_email = (isset($company_profile) && $company_profile['company_email'] != '' ?$company_profile['company_email'] : '');
                $gstin = (isset($company_profile) && $company_profile['gstin'] != '' ?$company_profile['gstin'] : '');
                $company_mobile_dial_code = (isset($company_profile) && $company_profile['company_mobile_dial_code'] != '' ?$company_profile['company_mobile_dial_code'] : '');
                $company_mobile = (isset($company_profile) && $company_profile['company_mobile'] != '' ?$company_profile['company_mobile'] : '');
                $website = (isset($company_profile) && $company_profile['website'] != '' ?$company_profile['website'] : '');
                $state_id = (isset($company_profile) && $company_profile['state_id'] != '' ?$company_profile['state_id'] : '');
                $whatsapp_mobile_dial_code = (isset($company_profile) && $company_profile['whatsapp_mobile_dial_code'] != '' ?$company_profile['whatsapp_mobile_dial_code'] : '');
                $whatsapp_mobile_number = (isset($company_profile) && $company_profile['whatsapp_mobile_number'] != '' ?$company_profile['whatsapp_mobile_number'] : '');
                $facebook = (isset($company_profile) && $company_profile['facebook'] != '' ?$company_profile['facebook'] : '');
                $instagram = (isset($company_profile) && $company_profile['instagram'] != '' ?$company_profile['instagram'] : '');
                $pinterest = (isset($company_profile) && $company_profile['pinterest'] != '' ?$company_profile['pinterest'] : '');
                $company_address = (isset($company_profile) && $company_profile['company_address'] != '' ?$company_profile['company_address'] : '');
                $company_area = (isset($company_profile) && $company_profile['company_area'] != '' ?$company_profile['company_area'] : '');
                $company_city = (isset($company_profile) && $company_profile['company_city'] != '' ?$company_profile['company_city'] : '');
                $company_pincode = (isset($company_profile) && $company_profile['company_pincode'] != '' ?$company_profile['company_pincode'] : '');
                $country_id = (isset($company_profile) && $company_profile['country_id'] != '' ?$company_profile['country_id'] : '102');
                $authorized_signatory_for = (isset($company_profile) && $company_profile['authorized_signatory_for'] != '' ?$company_profile['authorized_signatory_for'] : '');
                $terms_and_condition = (isset($company_profile) && $company_profile['terms_and_condition'] != '' ?$company_profile['terms_and_condition'] : '');
                $additional_message  = (isset($company_profile) && $company_profile['additional_message'] != '' ?$company_profile['additional_message'] : '');
                $po_terms_and_condition  = (isset($company_profile) && $company_profile['po_terms_and_condition'] != '' ?$company_profile['po_terms_and_condition'] : '');
                $bill_number_prefix  = (isset($company_profile) && $company_profile['bill_number_prefix'] != '' ?$company_profile['bill_number_prefix'] : '');
                $debit_receipt_prefix  = (isset($company_profile) && $company_profile['debit_receipt_prefix'] != '' ?$company_profile['debit_receipt_prefix'] : '');
                $account_holder_name = (isset($company_profile) && $company_profile['account_holder_name'] != '' ? $company_profile['account_holder_name'] : '');
                $bank_name  = (isset($company_profile) && $company_profile['bank_name'] != '' ?$company_profile['bank_name'] : '');
                $account_number = (isset($company_profile) && $company_profile['account_number'] != '' ?$company_profile['account_number'] : '');
                $ifsc_code  = (isset($company_profile) && $company_profile['ifsc_code'] != '' ?$company_profile['ifsc_code'] : '');
                $branch = (isset($company_profile) && $company_profile['branch'] != '' ?$company_profile['branch'] : '');
                $inward_type = (isset($company_profile) && $company_profile['inward_type'] != '' ?$company_profile['inward_type'] : '');
                $inward_calculation = (isset($company_profile) && $company_profile['inward_calculation'] != '' ?$company_profile['inward_calculation'] : '');
                $tax_type = (isset($company_profile) && $company_profile['tax_type'] != '' ?$company_profile['tax_type'] : '');
                $tax_title = (isset($company_profile) && $company_profile['tax_title'] != '' ?$company_profile['tax_title'] : '');
                $currency_title = (isset($company_profile) && $company_profile['currency_title'] != '' ?$company_profile['currency_title'] : '');
                $decimal_points = (isset($company_profile) && $company_profile['decimal_points'] != 0 ?$company_profile['decimal_points'] : 0);
                $credit_receipt_prefix  = (isset($company_profile) && $company_profile['credit_receipt_prefix'] != '' ?$company_profile['credit_receipt_prefix'] : '');
                $po_number_prefix  = (isset($company_profile) && $company_profile['po_number_prefix'] != '' ?$company_profile['po_number_prefix'] : '');
                
            ?>

            <input type="hidden" name="company_profile_id" id="company_profile_id" value="<?php echo $company_profile_id ?>">
                <div class="col-xl-12">
                <div class="hk-row">
                    <div class="col-md-3" id="pi">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-text">Personal Information&nbsp;<button type="button" class="pa-0 ma-0  bold" style="font-size:10px;"  data-trigger="focus" data-placement="top" data-toggle="popover"  title="" data-content="This will not be shown on bill print." class="fa fa-info" ><i class="fa fa-eye cursor"></i></button>
                                </h5>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" maxlength="255" autocomplete="off" name="full_name"
                                               id="full_name" value="<?php echo $fullname ?>"
                                               class="form-control form-inputtext invalid" placeholder="">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Mobile No.</label>
                                        <input type="tel" autocomplete="off" name="personal_mobile_no"
                                               id="personal_mobile_no" value="<?php echo $personal_mobile_no ?>"
                                               style="width:100%;" maxlength="15"
                                               class="form-control form-inputtext mobileregax invalid" placeholder="">
                                        <input type="hidden" name="personal_mobile_dial_code"
                                               id="personal_mobile_dial_code" value="">

                                    </div>
                                    <div class="col-md-12" style="margin-top: 12px">
                                        <label class="form-label">Email&nbsp;<button type="button" class="pa-0 ma-0  bold" style="font-size:10px;"  data-trigger="focus" data-placement="top" data-toggle="popover"  title="" data-content="Report deliver to this email.if you want to add more emailid then you can enter email id by comma seprated!" class="fa fa-info" ><i class="fa fa-eye cursor"></i></button></label>
                                        <input type="text" autocomplete="off" maxlength="50" name="personal_email"
                                               id="personal_email" value="<?php echo $personal_email ?>"
                                               class="form-control form-inputtext invalid" placeholder="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5" id="ci">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-text">Company Information&nbsp;<button type="button" class="pa-0 ma-0  bold" style="font-size:10px;"  data-trigger="focus" data-placement="top" data-toggle="popover"  title="" data-content="This will be displayed on your bill print." class="fa fa-info" ><i class="fa fa-eye cursor"></i></button>
                                </h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Company Name</label>
                                        <input type="text" maxlength="200" autocomplete="off" name="company_name"
                                               id="company_name" value="<?php echo $company_name ?>"
                                               class="form-control form-inputtext invalid" placeholder="">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Mobile No.</label>
                                        <input type="tel" autocomplete="off" style="width: 100%;" name="company_mobile"
                                               id="company_mobile" value="<?php echo $company_mobile ?>" maxlength="15"
                                               class="form-control form-inputtext mobileregax" placeholder="">
                                        <input type="hidden" name="company_mobile_dial_code"
                                               id="company_mobile_dial_code" value="">
                                    </div>


                                    <div class="col-md-6 ">
                                        <label class="form-label">Email</label>
                                        <input type="text" autocomplete="off" maxlength="50" name="company_email"
                                               id="company_email" value="<?php echo $company_email ?>"
                                               class="form-control form-inputtext" placeholder="">
                                    </div>

                                    <div class="col-md-6 ">
                                        <label class="form-label">Website</label>
                                        <input type="text" maxlength="255" name="website" id="website"
                                               value="<?php echo $website ?>" class="form-control form-inputtext"
                                               placeholder="">
                                    </div>

                                    <div class="col-md-6 ">
                                        <label class="form-label">GSTIN</label>
                                        <input type="text" maxlength="15" name="gstin" id="gstin"
                                               value="<?php echo $gstin ?>" class="form-control form-inputtext"
                                               placeholder="">
                                    </div>

                                    <div class="col-md-6 ">
                                        <label class="form-label">State</label>
                                        <select class="form-control form-inputtext invalid"
                                                <?php if ($gstin != '' && $gstin != null) echo "true"; else echo "false";?>
                                                style="<?php if ($gstin != '') echo "color: black" ?>" name="state_id"
                                                id="state_id">
                                            <option value="">Select State</option>
                                            @foreach($state AS $state_key=>$state_value)
                                                <option value="{{$state_value->state_id}}" <?php if ($state_id != '' && $state_value['state_id'] == $state_id) echo "selected"  ?> >{{$state_value->state_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" id="sm">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-text">Social Media Handles&nbsp;<button type="button" class="pa-0 ma-0  bold" style="font-size:10px;"  data-trigger="focus" data-placement="top" data-toggle="popover"  title="" data-content="Enter this if you want to show on bill print." class="fa fa-info" ><i class="fa fa-eye cursor"></i></button>
                                </h5>
                                <div class="row">
                                    <div class="col-md-6 ">
                                        <label class="form-label">Whatsapp Mobile No.</label>
                                        <input type="tel" autocomplete="off" style="width: 100%;"
                                               name="whatsapp_mobile_number" id="whatsapp_mobile_number"
                                               value="<?php echo $whatsapp_mobile_number ?>" maxlength="15"
                                               class="form-control form-inputtext mobileregax" placeholder="">
                                        <input type="hidden" name="whatsapp_mobile_dial_code"
                                               id="whatsapp_mobile_dial_code" value="">
                                    </div>

                                    <div class="col-md-6 ">
                                        <label class="form-label">Facebook</label>
                                        <input type="text" maxlength="255" name="facebook" id="facebook"
                                               value="<?php echo $facebook ?>" class="form-control form-inputtext"
                                               placeholder="">
                                    </div>

                                    <div class="col-md-6 ">
                                        <label class="form-label">Instagram</label>
                                        <input type="text" maxlength="255" name="instagram" id="instagram"
                                               value="<?php echo $instagram ?>" class="form-control form-inputtext"
                                               placeholder="">
                                    </div>

                                    <div class="col-md-6 ">
                                        <label class="form-label">Pinterest</label>
                                        <input type="text" maxlength="255" name="pinterest" id="pinterest"
                                               value="<?php echo $pinterest ?>" class="form-control form-inputtext"
                                               placeholder="">
                                    </div>
                                </div>
                            </div>
                        </div>

                     


                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="hk-row">
                    <div class="col-md-5" id="ad">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-text">Address Detail
                                &nbsp;<button type="button" class="pa-0 ma-0  bold" style="font-size:10px;"  data-trigger="focus" data-placement="top" data-toggle="popover"  title="" data-content="This details will be shown at bottom of bill print." class="fa fa-info" ><i class="fa fa-eye cursor"></i></button></h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Shop no.,Building,Street etc.</label>
                                        <textarea name="company_address" id="company_address" class="form-control form-inputtext invalid" placeholder=""><?php echo $company_address ?></textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="form-label">Area</label>
                                                <input type="text" maxlength="255" name="company_area" id="company_area" value="<?php echo $company_area ?>" class="form-control form-inputtext invalid" placeholder="">
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label">City / Town</label>
                                                <input type="text" maxlength="100" name="company_city" id="company_city" value="<?php echo $company_city ?>" class="form-control form-inputtext invalid" placeholder="">
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label">Pin / Zip Code</label>
                                                <input type="text" maxlength="15" name="company_pincode"
                                                       id="company_pincode" value="<?php echo $company_pincode ?>"
                                                       class="form-control form-inputtext onlyinteger invalid"
                                                       placeholder="">
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label">Country</label>
                                                <select class="form-control form-inputtext" name="country_id" id="country_id">

                                                    @foreach($country AS $country_key=>$country_value)
                                                        <option
                                                            <?php if ($country_value['country_id'] == $country_id) echo "selected"  ?> value="{{$country_value->country_id}}">{{$country_value->country_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                            </div>
                                        </div>

                                    </div>
                            </div>


                                         <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-text">Return Policy
                                                     &nbsp;<button type="button" class="pa-0 ma-0  bold" style="font-size:10px;"  data-trigger="focus" data-placement="top" data-toggle="popover"  title="" data-content="Info to be shown of bottom of your bill" class="fa fa-info" ><i class="fa fa-eye cursor"></i></button>

                                                </h5>
                                                <div class="row ma-0">
                                                     <div class="col-md-5" style="border:0px solid !important;text-align:right !important;font-size:13px;margin:0 !important;"><b>Sales Return not allowed after</b></div>
                                                      <div class="col-md-2" style="border:0px solid !important;text-align:right !important;font-size:14px;margin-left:0 !important;">
                                                             <input type="text" class="form-control number" id="returndays" name="returndays"style="width:100% !important;height: calc(1.90rem) !important;" value="<?php echo $company_profile['return_days']; ?>">
                                                        </div>
                                                         <div class="col-md-5" style="border:0px solid !important;text-align:left !important;font-size:13px;margin-left:0 !important;">
                                                              <b>Days from Purchase Date.</b>
                                                        </div>
                                                </div>
                                                <div class="row">
                                                     <div class="col-md-6"> 
                                                             &nbsp;
                                                        </div>
                                                      <div class="col-md-6">
                                                              &nbsp;
                                                        </div>
                                                </div>
                                            </div>
                                        </div>







                    </div>

                    <div class="col-md-4" id="bf">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-text">Bill Footer
                                    &nbsp;<button type="button" class="pa-0 ma-0  bold" style="font-size:10px;"  data-trigger="focus" data-placement="top" data-toggle="popover"  title="" data-content="This details will be shown at bottom of bill print." class="fa fa-info" ><i class="fa fa-eye cursor"></i></button>
                                </h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Authorized Signatory</label>
                                        <input type="checkbox" maxlength="20" name="authorized_signatory"
                                               id="authorized_signatory"
                                               <?php  if ($authorized_signatory_for != '') echo "checked" ?>  class="form-control"
                                               placeholder="">
                                    </div>
                                    <?php if ($authorized_signatory_for != '')
                                        $display = "block";
                                    else
                                        $display = "none";
                                    ?>
                                    <div class="col-md-6" id="authority_for" style="display: <?php echo $display ?>">
                                        <label class="form-label">For</label>
                                        <input type="text" maxlength="200" name="authorized_signatory_for"
                                               id="authorized_signatory_for"
                                               value="<?php echo $authorized_signatory_for ?>"
                                               class="form-control form-inputtext" placeholder="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">Terms & Condition</label>
                                        <textarea name="terms_and_condition" id="terms_and_condition" class="form-control form-inputtext" placeholder=""><?php echo $terms_and_condition ?></textarea>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Additional Message</label>
                                        <textarea name="additional_message" id="additional_message" class="form-control form-inputtext" placeholder=""><?php echo $additional_message ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" id="bd">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-text">Bank Details
                                     &nbsp;<button type="button" class="pa-0 ma-0  bold" style="font-size:10px;"  data-trigger="focus" data-placement="top" data-toggle="popover"  title="" data-content="This details will be shown at bottom of bill print." class="fa fa-info" ><i class="fa fa-eye cursor"></i></button>
                                </h5>
                                <div class="row">


                                    <div class="col-md-12">
                                        <label class="form-label">AC Holder Name</label>
                                        <input type="text" autocomplete="off" maxlength="100" name="account_holder_name"
                                               id="account_holder_name" value="<?php echo $account_holder_name ?>"
                                               class="form-control form-inputtext" placeholder="">
                                    </div>


                                    <div class="col-md-12">
                                        <label class="form-label">Bank Name</label>
                                        <input type="text" autocomplete="off" maxlength="100" name="bank_name"
                                               id="bank_name" value="<?php echo $bank_name ?>"
                                               class="form-control form-inputtext" placeholder="">
                                    </div>


                                    <div class="col-md-12">
                                        <label class="form-label">Account No.</label>
                                        <input type="text" autocomplete="off" maxlength="20" name="account_number"
                                               id="account_number" value="<?php echo $account_number ?>"
                                               class="form-control form-inputtext onlyinteger" placeholder="">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">IFSC Code</label>
                                        <input type="text" autocomplete="off" maxlength="11" name="ifsc_code"
                                               id="ifsc_code" value="<?php echo $ifsc_code ?>"
                                               class="form-control form-inputtext" placeholder="">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Branch</label>
                                        <input type="text" autocomplete="off" maxlength="50" name="branch" id="branch"
                                               value="<?php echo $branch ?>" class="form-control form-inputtext"
                                               placeholder="">
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4" id="poterms">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-text">PO Terms & Condition
                                    
                                    &nbsp;<button type="button" class="pa-0 ma-0  bold" style="font-size:10px;"  data-trigger="focus" data-placement="top" data-toggle="popover"  title="" data-content="This details will be shown on purchase order screen." class="fa fa-info" ><i class="fa fa-eye cursor"></i></button>
                                </h5>

                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">Terms & Condition</label>
                                        <textarea name="po_terms_and_condition" id="po_terms_and_condition" class="form-control form-inputtext" placeholder=""><?php echo $po_terms_and_condition?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="col-md-8" id="poterms">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-text">Prefix<i class="fa fa-info-circle" title="This details will be shown at bottom of bill print."></i>
                                </h5>
                                   <div class="row">
                                                <div class="col-md-4">
                                                    <h5 class="card-text">Bill number Prefix<i class="fa fa-info-circle" title="This is shown before bill no. For example:ABC-123/19-20(ABC is Prefix & 123/19-20 is Bill No.)"></i>
                                                    </h5>                                                    
                                                      
                                                    <input type="text" autocomplete="off" maxlength="30" name="bill_number_prefix"
                                                           id="bill_number_prefix" value="<?php echo $bill_number_prefix ?>"
                                                           class="form-control form-inputtext" placeholder="">
                                                </div>
                                                <div class="col-md-4">
                                                    <h5 class="card-text">Credit Receipt Prefix<i class="fa fa-info-circle"
                                                                                                 title="This is shown before credit receipt no. For example:ABC-1(ABC is Prefix & 1 is credit receip No.)"></i>
                                                    </h5>
                                                    
                                                    <input type="text" autocomplete="off" maxlength="30" name="credit_receipt_prefix"
                                                           id="credit_receipt_prefix" value="<?php echo $credit_receipt_prefix ?>"
                                                           class="form-control form-inputtext" placeholder="">
                                                </div>
                                                 <div class="col-md-4">

                                                    <h5 class="card-text">Debit Receipt Prefix<i class="fa fa-info-circle"
                                                                                                 title="This is shown before debit receipt no. For example:ABC-1(ABC is Prefix & 1 is Debit receip No.)"></i>
                                                    </h5>

                                                    <input type="text" autocomplete="off" maxlength="30" name="debit_receipt_prefix"
                                                           id="debit_receipt_prefix" value="<?php echo $debit_receipt_prefix ?>"
                                                           class="form-control form-inputtext" placeholder="">
                                                </div>
                                                <div class="col-md-4">

                                                    <h5 class="card-text">PO Number Prefix<i class="fa fa-info-circle"
                                                                                                 title="This is shown before debit receipt no. For example:ABC-1(ABC is Prefix & 1 is Debit receip No.)"></i>
                                                    </h5>

                                                    <input type="text" autocomplete="off" maxlength="30" name="po_number_prefix"
                                                           id="po_number_prefix" value="<?php echo $po_number_prefix ?>"
                                                           class="form-control form-inputtext" placeholder="">
                                                </div>
                                                 <div class="col-md-4"></div>
                                                <div class="col-md-4"></div>
                                                <div class="col-md-12 rightAlign" style="padding-top: 12px;">
                                                    <button type="button" name="resetcompany_profile"
                                                            onclick="resetcompany_profiledata();" class="btn btn-light addbutton"
                                                            id="resetcompany_profile" data-container="body" data-toggle="popover"
                                                            data-placement="bottom" data-content="">Reset Company Profile
                                                    </button>

                                                    <button type="button" name="addcompanyprofile" class="btn btn-info addbutton"
                                                            id="addcompanyprofile" data-container="body" data-toggle="popover"
                                                            data-placement="bottom" data-content="">Update Information
                                                    </button>
                                                </div>
                                    </div>
                        
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            </div>





        </form>


    </div>

    </section>

    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/popper.js/js/popper.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/build/js/intlTelInput.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script src="{{URL::to('/')}}/public/modulejs/common.js"></script>
    <script src="{{URL::to('/')}}/public/modulejs/company_profile/company_profile.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#cke_company_address").addClass('invalid');
            selectdiacode();
        });


        function selectdiacode() {
            var company_mobile = document.querySelector("#company_mobile");
            var company_dial_code = "<?php echo $company_mobile_dial_code?>";
            var company_mobile_dial_code_edit = "in";
            if (company_dial_code != '') {
                var company_mobile_dial_code_edit = company_dial_code.split(',')[1];
            }
            window.intlTelInput(company_mobile,
                {
                    initialCountry: company_mobile_dial_code_edit,
                    separateDialCode: true,
                    autoHideDialCode: false,
                    utilsScript: "{{URL::to('/')}}/public/build/js/utils.js",
                });

            var personal_mobile_no = document.querySelector("#personal_mobile_no");

            var personal_dial_code = "<?php echo $personal_mobile_dial_code?>";
            var personal_mobile_dial_code_edit = "in";
            if (personal_dial_code != '') {
                var personal_mobile_dial_code_edit = personal_dial_code.split(',')[1];
            }

            window.intlTelInput(personal_mobile_no, {
                initialCountry: personal_mobile_dial_code_edit,
                separateDialCode: true,
                autoHideDialCode: false,
                utilsScript: "{{URL::to('/')}}/public/build/js/utils.js",
            });

            var whatsapp_mobile_number = document.querySelector("#whatsapp_mobile_number");
            var whatsapp_dial_code = "<?php echo $whatsapp_mobile_dial_code ?>";
            var whatsapp_dial_code_edit = "in";
            if(whatsapp_dial_code != '')
            {
                var whatsapp_dial_code_edit = whatsapp_dial_code.split(',')[1];
            }
            window.intlTelInput(whatsapp_mobile_number, {
                initialCountry: whatsapp_dial_code_edit,
                separateDialCode: true,
                autoHideDialCode: false,
                utilsScript: "{{URL::to('/')}}/public/build/js/utils.js",
            });
        }


        CKEDITOR.replace('company_address',{

        });

        CKEDITOR.replace('terms_and_condition', {
            height: ['100px']
        });
        CKEDITOR.replace('additional_message', {
            height: ['100px']
        });

        CKEDITOR.replace('po_terms_and_condition', {
            height: ['100px']
        });

    </script>

@endsection
