@include('pagetitle')
@extends('master')

@section('main-hk-pg-wrapper')
    {{--<div class="row">
        <div class="col-xl-12">

            <div class="hk-row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body fixed-height">
                            <form name="customerform" id="customerform">
                                <input type="hidden" name="customer_id" id="customer_id" value="">
                                <input type="hidden" name="customer_address_detail_id" id="customer_address_detail_id" value="">
                                <span id="customer_error" style="color: red;"></span>
                            <h5 class="card-title">Customer Details</h5>
                            <div class="row">

                                <div class="col-md-3 ">
                                    <label class="form-label">Customer Name</label>
                                    <input type="text" maxlength="50" name="customer_name" id="customer_name" value="" class="form-control form-inputtext" placeholder="">
                                </div>

                                <div class="col-md-3 ">
                                    <label class="form-label">Mobile No</label>
                                    <input type="text" name="customer_mobile" id="customer_mobile" value="" maxlength="15" class="form-control form-inputtext mobileregax" placeholder="">
                                </div>

                                <div class="col-md-3 ">
                                    <label class="form-label">Email Id</label>
                                    <input type="text" maxlength="50" name="customer_email" id="customer_email" value=""  class="form-control form-inputtext" placeholder="">
                                </div>

                                <div class="col-md-3 ">
                                    <label class="form-label">GSTIN</label>
                                    <input type="text" maxlength="15" name="customer_gstin" id="customer_gstin" value=""  class="form-control form-inputtext" placeholder="">
                                </div>

                                <div class="col-md-3 ">
                                    <label class="form-label">Flat no.,Building,Street etc.</label>
                                    <input type="text" maxlength="100" name="customer_address" id="customer_address" value=""  class="form-control form-inputtext" placeholder="">
                                </div>

                                <div class="col-md-3 ">
                                    <label class="form-label">Area</label>
                                    <input type="text" maxlength="100" name="customer_area" id="customer_area" value=""  class="form-control form-inputtext" placeholder="">
                                </div>

                                <div class="col-md-3 ">
                                    <label class="form-label">City</label>
                                    <input type="text" maxlength="100" name="customer_city" id="customer_city" value=""  class="form-control form-inputtext" placeholder="">
                                </div>

                                <div class="col-md-3 ">
                                    <label class="form-label">Pincode</label>
                                    <input type="text" maxlength="20" name="customer_pincode" id="customer_pincode" value=""  class="form-control form-inputtext" placeholder="">
                                </div>

                                <div class="col-md-3 ">
                                    <label class="form-label">State</label>
                                    <select name="state_id" id="state_id" class="form-control form-inputtext">
                                        @foreach($state AS $statekey=>$statevalue)
                                            <option value="{{$statevalue->state_id}}">{{$statevalue->state_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3 ">
                                    <label class="form-label">Country</label>
                                    <select name="country_id" id="country_id" class="form-control form-inputtext">
                                        @foreach($country AS $countrykey=>$countryvalue)
                                            <option value="{{$countryvalue->country_id}}">{{$countryvalue->country_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                    <div class="col-md-6">
                                        <button type="button" id="addcustomer" name="addcustomer" class="btn btn-info">Save</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" id="deletecustomer" name="deletecustomer" class="btn btn-danger">Delete</button>

                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-light">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>--}}

    <link rel="stylesheet" href="{{URL::to('/')}}/public/bower_components/bootstrap-datepicker/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="{{URL::to('/')}}/public/build/css/intlTelInput.css">

    <div class="container">

        <span class="commonbreadcrumbtn badge badge-pill downloadBtn"  id="download_customer_tmpate"><i class="ion ion-md-download"></i>&nbsp;Download Customer Template</span>
    <span class="commonbreadcrumbtn badge exportBtn badge-pill uploadBtn mr-0"  id="upload_customer_tmpate">&nbsp<i class="fa fa-upload"></i>&nbsp;&nbsp;Upload Customers</span>


    
    <span class=" commonbreadcrumbtn badge badge-primary badge-pill "  id="addnewcollapse"><i class="fa fa-plus"></i>&nbsp;Add new Customer</span>
    <span class="commonbreadcrumbtn badge badge-danger badge-pill"  id="searchCollapse"><i class="glyphicon glyphicon-search"></i>&nbsp;Search</span>

    



    <div class="col-xl-12 collapse" id="addnewbox">
        <div class="hk-row">
            <div class="card">
                <div class="card-body">
        <h5 class="hk-sec-title">Add New Customer </h5>
        <form name="customerform" id="customerform">
            <input type="hidden" name="customer_id" id="customer_id" value="">
            <input type="hidden" name="customer_address_detail_id" id="customer_address_detail_id" value="">

            <div class="hk-row">
                <div class="col-sm">
                    <div class="row">
                        <div class="col-md-3 ">
                            <label class="form-label">Customer Name</label>
                            <input type="text" maxlength="50" autocomplete="off" name="customer_name" id="customer_name" value="" class="form-control form-inputtext invalid" placeholder="">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Gender</label>
                            <div class="row">
                            <div class="col-md-4">
                                <input type="radio" class="form-control" id="male" name="gender" value="1" style="width:15% !important;height: calc(1.30rem) !important;" >Male
                            </div>
                            <div class="col-md-4">
                                <input type="radio" class="form-control" id="female" name="gender" value="2" style="width:15% !important;height: calc(1.30rem) !important;" >Female
                            </div>
                            <div class="col-md-4">
                                <input type="radio" class="form-control" id="transgender" name="gender" value="3" style="width:15% !important;height: calc(1.30rem) !important;" >Transgender
                            </div>
                            </div>

                        </div>

                        <div class="col-md-3 ">
                            <label class="form-label">Mobile No.</label>
                            <input type="tel" autocomplete="off" name="customer_mobile"  id="customer_mobile" value="" maxlength="15" class="form-control form-inputtext mobileregax" placeholder="">
                            <input type="hidden" name="customer_mobile_dial_code" id="customer_mobile_dial_code" value="">
                        </div>

                        <div class="col-md-3 ">
                            <label class="form-label">Email</label>
                            <input type="text" autocomplete="off" maxlength="50" name="customer_email" id="customer_email" value=""  class="form-control form-inputtext" placeholder="">
                        </div>

                        <div class="col-md-3 ">
                            <label class="form-label">GSTIN</label>
                            <input type="text" maxlength="15" name="customer_gstin" id="customer_gstin" value=""  class="form-control form-inputtext" placeholder="">
                        </div>

                        <div class="col-md-3 ">
                            <label class="form-label">Date of Birth</label>
                            <input type="text" maxlength="15" name="customer_date_of_birth" id="customer_date_of_birth" value=""  class="form-control form-inputtext" placeholder="">
                        </div>

                        <div class="col-md-3 ">
                            <label class="form-label">Flat no.,Building,Street etc.</label>
                            <input type="text" maxlength="100" name="customer_address" id="customer_address" value=""  class="form-control form-inputtext" placeholder="">
                        </div>

                        <div class="col-md-3 ">
                            <label class="form-label">Area</label>
                            <input type="text" maxlength="100" name="customer_area" id="customer_area" value=""  class="form-control form-inputtext" placeholder="">
                        </div>

                        <div class="col-md-3 ">
                            <label class="form-label">City / Town</label>
                            <input type="text" maxlength="100" name="customer_city" id="customer_city" value=""  class="form-control form-inputtext" placeholder="">
                        </div>

                        <div class="col-md-3 ">
                            <label class="form-label">Pin / Zip Code</label>
                            <input type="text" maxlength="20" name="customer_pincode" id="customer_pincode" value=""  class="form-control form-inputtext" placeholder="">
                        </div>

                        <div class="col-md-3 ">
                            <label class="form-label">State / Region</label>
                            <select name="state_id" id="state_id" class="form-control form-inputtext">
                                <option value="0">Select State</option>
                                @foreach($state AS $statekey=>$statevalue)
                                    <option value="{{$statevalue->state_id}}">{{$statevalue->state_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 ">
                            <label class="form-label">Country</label>
                            <select name="country_id" id="country_id" class="form-control form-inputtext">
                                <option value="">Select Country</option>
                                @foreach($country AS $countrykey=>$countryvalue)
                                    <option <?php if($countryvalue['country_id'] == '102') echo "selected"  ?> value="{{$countryvalue->country_id}}">{{$countryvalue->country_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                        <label class="form-label">Credit Period(days)</label>
                            <input type="text" maxlength="20" name="outstanding_duedays" id="outstanding_duedays" value=""  class="form-control form-inputtext number" placeholder="">
                        </div>

                        <div class="col-md-3 ">
                            <label class="form-label">How did you came to know about us?</label>
                            <select name="source" id="source" class="form-control form-inputtext">
                                <option value="">Select Source</option>
                                @foreach($customer_source AS $source_key=>$source_value)
                                    <option value="{{$source_value->customer_source_id}}">{{$source_value->source_name}}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-md-3 ">
                            <label class="form-label">Note(for internal use)</label>
                            <textarea  class="form-control form-inputtext" name="customer_note" id="customer_note" ></textarea>
                        </div>
                        <div class="col-md-3"></div>
                        <div class="col-md-3"></div>
                        <div class="col-md-3"></div>
                        <div class="col-md-3"></div>

                        <div class="col-md-3">
                            <button type="button" name="addcustomer" class="btn btn-info addbutton saveBtn" id="addcustomer" data-container="body" data-toggle="popover" data-placement="bottom" data-content=""><i class="fa fa-save"></i>Save</button>

                        <button type="button" name="resetcustomer" onclick="resetcustomerdata();" class="btn btn-info resetbtn" id="resetcustomer" data-container="body" data-toggle="popover" data-placement="bottom" data-content="">Reset</button>
                        </div>
                    </div>
                </div>
                
            </div>
        </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-12 collapse" id="searchbox">
        <div class="hk-row">
    <div class="card pa-20">
        <div class="" id="filterarea_block">
            <div class="hk-row common-search">
                <div class="col-md-3 pb-10">
                    <div class="form-group">
                        <input type="text" name-attr="customer_name" name="filter_customer_name"  placeholder="Customer Name" id="filter_customer_name" class="form-control form-inputtext" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" name-attr="customer_mobile" name="filter_mobile_no"  placeholder="Customer Mobile No." id="filter_mobile_no" class="form-control form-inputtext" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" name-attr="customer_email" name="filter_email_id"  placeholder="Customer Email ID." id="filter_email_id" class="form-control form-inputtext" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" name-attr="customer_gstin" name="filter_gst_in"  placeholder="Customer GSTIN" id="filter_gst_in" class="form-control form-inputtext" />
                    </div>
                </div>
                <div class="col-md-3 pb-10">
                    <div class="form-group">
                        <input type="text" name-attr="customer_date_of_birth" name="filter_date_of_birth"  placeholder="Customer Date Of Birth" id="filter_date_of_birth" class="form-control form-inputtext" />
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" name-attr="customer_area" name="filter_area"  placeholder="Customer Area" id="filter_area" class="form-control form-inputtext" />
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" name-attr="customer_city" name="filter_city"  placeholder="City / Town" id="filter_city" class="form-control form-inputtext" />
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" name-attr="customer_pincode" name="filer_pincode"  placeholder="Pin / Zip Code" id="filer_pincode" class="form-control form-inputtext" />
                    </div>
                </div>

                <div class="col-md-3 ">

                    <select name-attr="state_id" name="filter_state_id" id="filter_state_id" class="form-control form-inputtext">
                        <option value="0">Select State</option>
                        @foreach($state AS $statekey=>$statevalue)
                            <option value="{{$statevalue->state_id}}">{{$statevalue->state_name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <select name-attr="country_id" name="filter_country_id" id="filter_country_id" class="form-control form-inputtext">
                        <option value="0">Select Country</option>
                        @foreach($country AS $countrykey=>$countryvalue)
                            <option  value="{{$countryvalue->country_id}}">{{$countryvalue->country_name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <button type="button" class="btn btn-info searchBtn search_data" id="search_customer"><i class="fa fa-search"></i>Search</button>
                    <button type="button" name="resetfilter" onclick="resetcustomerfilterdata();" class="btn btn-info resetbtn" id="resetfilter" data-container="body" data-toggle="popover" data-placement="bottom" data-content="" data-original-title="" title="">Reset</button>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    <div class="col-xl-12">
        <div class="hk-row">
            <div class="card">
                <div class="card-body">
                    <div class="hk-row">
                        <div class="col-md-1">
                            <!-- <h5 class="hk-sec-title">Customer List</h5> -->
                            <a id="deletecustomer" name="deletecustomer" title="Delete">
                                <i class="fa fa-trash" style="font-size: 20px;color: red;margin-left: 20px;"></i></a>
                        </div>
                    </div>

                    <div class="table-wrap" >
                       <div class="table-responsive" id="customertablerecord">
                           @include('customer::customer_data')
                       </div>
                    </div><!--table-wrap-->
                </div>
            </div>
        </div>
    </div>


        <div class="modal fade" id="upload_customer_popup" style="border:1px solid !important;">
            <div class="modal-dialog" style="max-width:30% !important;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Upload Customers(Excel File)</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    {{--<i class="fa fa-info-circle" title="If you don't have template excel file,you can download from here."></i>--}}
                        <span class="badge badge-pill downloadBtn mt-10" style="width:50%;ursor:pointer;float:right;color:#ffffff;" id="download_customer_tmpate"><i class="ion ion-md-download"></i>&nbsp;Download Customer Template</span>
                    <br>
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                    <div class="card">
                                        <div class="card-body">

                                            <input type="file" class="" id="customerfileUpload"  accept=".xlsx, .xls" />
                                            <button type="button"  class="btn btn-info btn-block mt-10 uploadBtn" name="uploadcustomer" id="uploadcustomer">
                                                <i class="ion ion-md-cloud-upload"></i>&nbsp;Upload</button>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            </form>
        </div>




    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/popper.js/js/popper.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/build/js/intlTelInput.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/dist/js/datepicker.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/dist/js/xlsx.full.min.js"></script>


    <script src="{{URL::to('/')}}/public/modulejs/customer/customer.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
    selectdiacode();
    });

    function selectdiacode()
    {
    var input = document.querySelector("#customer_mobile");
    window.intlTelInput(input, {
    initialCountry: "in",
    separateDialCode: true,
    utilsScript: "{{URL::to('/')}}/public/build/js/utils.js",
    });
    }

        $('#searchCollapse').click(function(e){
            $('#searchbox').slideToggle();
        })

        $('#addnewcollapse').click(function(e){
            $('#addnewbox').slideToggle();
        })

</script>


@endsection
