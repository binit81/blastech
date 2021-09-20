@include('pagetitle')
@extends('master')

@section('main-hk-pg-wrapper')


    <link rel="stylesheet" href="{{URL::to('/')}}/public/bower_components/bootstrap-datepicker/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="{{URL::to('/')}}/public/build/css/intlTelInput.css">

    <div class="container">
        
    <span class="commonbreadcrumbtn badge badge-primary badge-pill"  id="addnewcollapse"><i class="fa fa-plus"></i>&nbsp;Add new Customer Source</span>
    

    <div class="col-xl-12 collapse" id="addnewbox">
        <div class="hk-row">
            <div class="card">
                <div class="card-body">
        <h5 class="hk-sec-title">Add New Customer Source</h5>
        <form name="customer_source_form" id="customer_source_form">
            <input type="hidden" name="customer_source_id" id="customer_source_id" value="">


            <div class="hk-row">
                <div class="col-sm">
                    <div class="row">
                        <div class="col-md-3 ">
                            <label class="form-label">Customer Source Name</label>
                            <input type="text" maxlength="50" autocomplete="off" name="source_name" id="source_name" value="" class="form-control form-inputtext invalid" placeholder="">
                        </div>


                        <div class="col-md-3 ">
                            <label class="form-label">Note(for internal use)</label>
                            <textarea  class="form-control form-inputtext" name="customer_source_note" id="customer_source_note"></textarea>
                        </div>

                        <div class="col-md-3">
                            <button type="button" name="add_customer_source" class="btn btn-info addbutton saveBtn" id="add_customer_source" data-container="body" data-toggle="popover" data-placement="bottom" data-content=""><i class="fa fa-save"></i>Add Customer Source</button>

                        <button type="button" name="resetcustomersource" onclick="resetcustomersourcedata();" class="btn btn-info resetbtn" id="resetcustomersource" data-container="body" data-toggle="popover" data-placement="bottom" data-content="">Reset</button>
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
                        <input type="text" name-attr="source_name" name="filter_source_name"  placeholder="Source Name" id="filter_source_name" class="form-control form-inputtext" />
                    </div>
                </div>

                <div class="col-md-3">
                    <button type="button" class="btn btn-info searchBtn search_data" id="search_customer_source"><i class="fa fa-search"></i>Search</button>
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
                            <a id="deletesource" name="deletesource" title="Delete">
                                <i class="fa fa-trash" style="font-size: 20px;color: red;margin-left: 20px;"></i></a>
                        </div>
                    </div>

                    <div class="table-wrap" >
                       <div class="table-responsive" id="sourcetablerecord">
                            @include("customer_source::customer_source_data")
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/popper.js/js/popper.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>

    <script src="{{URL::to('/')}}/public/modulejs/common.js"></script>
    <script src="{{URL::to('/')}}/public/modulejs/customer_source/customer_source.js"></script>



<script type="text/javascript">
    $(document).ready(function(e){
        $('#searchCollapse').click(function(e){
            $('#searchbox').slideToggle();
        })

        $('#addnewcollapse').click(function(e){
            $('#addnewbox').slideToggle();
        })
    })
    </script>

@endsection
