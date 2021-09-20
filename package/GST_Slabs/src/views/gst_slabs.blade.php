<?php
/**
 * Created by PhpStorm.
 * User: Hemaxi
 * Date: 13/3/19
 * Time: 11:45 AM
 */

?>
@include('pagetitle')
@extends('master')
@section('main-hk-pg-wrapper')

        <div class="col-xl-12">

            <div class="hk-row">
                <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="hk-sec-title">Add GST Slabs</h5>

        <form name="gstslabsform" id="gstslabsform">
            <input type="hidden" name="gst_slabs_master_id" id="gst_slabs_master_id">

                <div class="col-sm">

                    <div class="row">

                        <div class="col-md-2">
                            <label class="form-label">Selling Price From &#x20b9;</label>
                            <input type="text" name="selling_price_from" id="selling_price_from" value="" maxlength="11" class="form-control form-inputtext number invalid" placeholder="">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Selling Price To &#x20b9;</label>
                            <input type="text" name="selling_price_to" id="selling_price_to" value="" maxlength="11" class="form-control form-inputtext number invalid" placeholder="">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">GST %</label>
                            <input type="text" name="percentage" id="percentage" value=""  class="form-control form-inputtext number invalid" maxlength="5" placeholder="">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Note</label>
                            <textarea class="form-control" name="gst_note" id="gst_note"></textarea>
                        </div>


                        <div class="col-md-4 pt-25">
                        <button type="button" name="addgstslab" class="btn btn-info addbutton saveBtn" id="addgstslab" data-container="body" data-toggle="popover" data-placement="bottom" data-content=""><i class="fa fa-save"></i>Add GST Slabs</button>

                        <button type="button" name="cancelgstslab"  class="btn btn-info resetbtn" id="cancelgstslab" data-container="body" data-toggle="popover" data-placement="bottom" data-content="">Reset</button>
                    </div>
                    </div>
                </div>
                

        </form>
                    </div>
                </div>
                </div>
            </div>
        </div>






        <div class="col-xl-12">
            <div class="hk-row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="hk-sec-title">GST Slabs List</h5>
                            <div class="row" >
                                <div class="col-md-9">
                                    <a name="deletegstslabs" id="deletegstslabs"><i class="fa fa-trash" style="font-size: 20px;color: red;margin-left: 20px"></i></a>
                                </div>

                                <div class="col-md-3 common-search">
                                    <div class="form-group">
                                        <input type="text" name-attr="serach" name="serach" id="serach" placeholder="search in records" class="form-control form-inputtext number" />
                                    </div>
                                </div>
                            </div>

                         <div class="table-wrap">
                             <div class="table-responsive" id="tablegstrecord">
                             @include('gst_slab::gst_slabs_data')
                             </div>
                        </div><!--table-wrap-->
                        </div>
                    </div>
                </div>
            </div>
        </div>





    {{--  <div class="hk-row">
<div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-wrap">
                                    <div class="table-responsive">
                                        <table class="table mb-0" >
                                            <thead>
                                            <tr>
                                                <th style="width:25%"><input type="checkbox" name="checkall" value="" id="checkall"></th>
                                                <th  style="width:25%">Selling Price From</th>
                                                <th style="width:25%" >Selling Price To</th>
                                                <th style="width:25%" >GST%</th>
                                            </tr>
                                            </thead>
                                            <tbody id="gstslabrecord">
                                            @foreach($gst_slabs AS $slabkey=>$slabvalue)

                                            <tr id="{{$slabvalue['gst_slabs_master_id']}}">
                                                <td style="width:25%;"><input type="checkbox" name="delete_gstslabs[]" value="{{$slabvalue->gst_slabs_master_id }}" id="delete_gstslabs{{$slabvalue->gst_slabs_master_id }}"></td>
                                                <td style="width:25%;text-align: right" ondblclick="editgstslabs('{{encrypt($slabvalue->gst_slabs_master_id)}}');">{{$slabvalue->selling_price_from}}</td>
                                                <td style="width:25%;text-align: right">{{$slabvalue->selling_price_to}}</td>
                                                <td style="width:25%;text-align: right">{{$slabvalue->percentage}}</td>
                                            </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div><!--table-wrap-->
                                </div><!--table-responsive-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>--}}

    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/popper.js/js/popper.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>

    <script src="{{URL::to('/')}}/public/modulejs/common.js"></script>

    <script src="{{URL::to('/')}}/public/modulejs/gstslabs/gstslabs.js"></script>
@endsection
