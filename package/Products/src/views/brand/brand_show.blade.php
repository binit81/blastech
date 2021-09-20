<?php
/**
 * Created by PhpStorm.
 * User: Hemaxi
 * Date: 18/2/19
 * Time: 10:45 AM
 */
?>
@include('pagetitle')
@extends('master')

@section('main-hk-pg-wrapper')
    <div class="row">
        <div class="col-xl-9">
            <div class="hk-row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body fixed-height greybg">
                            <h5 class="card-title">Customer Details</h5>
                            <div class="row">
                                <div class="col-sm-7">
                                    <div class="row">
                                        <div class="col-sm-5 no-right">
                                            <label>Customer No.</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="search" class="form-control mt-15" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5  no-right">
                                            <label>Name</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control mt-15" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5 no-right">
                                            <label>Mobile No.</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control mt-15" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5 no-right">
                                            <label>Email</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control mt-15" placeholder="">
                                        </div>
                                    </div>
                                </div><!--col-md-7-->
                                <div class="col-sm-5">
                                    <div class="row">
                                        <div class="col-sm-4 no-right">
                                            <label>GSTIN</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control mt-15" placeholder="">
                                        </div>
                                    </div><!--row-->
                                    <div class="row">
                                        <div class="col-sm-4 no-right">
                                            <label>Address</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <textarea class="form-control mt-15" rows="3" placeholder=""></textarea>
                                        </div>
                                    </div><!--row-->
                                </div><!--col-md-5-->
                            </div>
                        </div><!--card-body-->
                    </div><!--card-->
                </div><!--col-md-8-->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body fixed-height greybg">
                            <h5 class="card-title">Invoice Details</h5>
                            <div class="row">
                                <div class="col-sm-5 no-right">
                                    <label>Invoice No.</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="search" class="form-control mt-15" placeholder="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5 no-right">
                                    <label>Invoice Date</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control mt-15" placeholder="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5 no-right">
                                    <label>Reference</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control mt-15" placeholder="">
                                </div>
                            </div>
                        </div><!--card-body-->
                    </div><!--card-->
                </div><!--col-md-4-->
            </div><!--hk-row-->

            <div class="hk-row">
                <!--<div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-sm-4">
                        <label>Room Type</label>
                        <div class="btn-group">
                          <div class="dropdown">
                              <button aria-expanded="false" data-toggle="dropdown" class="btn btn-primary dropdown-toggle " type="button">Dropdown <span class="caret"></span></button>
                              <div role="menu" class="dropdown-menu">
                                  <a class="dropdown-item" href="#">Action</a>
                                  <a class="dropdown-item" href="#">Another action</a>
                                  <a class="dropdown-item" href="#">Something else here</a>
                                  <div class="dropdown-divider"></div>
                                  <a class="dropdown-item" href="#">Separated link</a>
                              </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-8">
                        <label>Checkin Date</label>
                        <input class="form-control input-limit-datepicker" type="text" name="daterange" value="06/01/2018 - 06/07/2018">
                      </div>
                    </div>
                  </div>
                </div>-->
                <div class="card">
                    <div class="card-body greybg">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-wrap">
                                    <div class="table-responsive">
                                        <table class="table scrollable_table mb-0" >
                                            <thead>
                                            <tr class="header">
                                                <th class="center" style="width:23%" colspan="2">Checkin Date -  Checkout Date</th>
                                                <th style="width:10%" >Room No.</th>
                                                <th style="width:8%" >No. of Nights</th>
                                                <th style="width:23%" >Description</th>
                                                <th style="width:8%" >Tarrif</th>
                                                <th style="width:8%" >GST%</th>
                                                <th style="width:10%" >Discount%</th>
                                                <th style="width:10%" >Total Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td colspan="2" style="width:23%"><input class="form-control input-limit-datepicker full-width" type="text" name="daterange" value="06/01/2018 - 06/07/2018"></td>

                                                <td style="width:10%">
                                                    <input type="text" class="form-control mt-15" placeholder="">
                                                </td>
                                                <td class="bold" style="width:8%">4</td>
                                                <td style="width:23%">afd asdf utd7c r6ftewwe fhh</td>
                                                <td class="bold" style="width:8%">$98,540</td>
                                                <td style="width:8%">6</td>
                                                <td style="width:10%"><input type="text" class="form-control mt-15" placeholder=""></td>
                                                <td style="width:10%" class="bold">$98,540</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="width:23%"><input class="form-control input-limit-datepicker full-width" type="text" name="daterange" value="06/01/2018 - 06/07/2018"></td>
                                                <td style="width:10%">
                                                    <input type="text" class="form-control mt-15" placeholder="">
                                                </td>
                                                <td class="bold" style="width:8%">4</td>
                                                <td style="width:23%">afd asdf utd7c r6ftewwe fhh</td>
                                                <td class="bold" style="width:8%">$98,540</td>
                                                <td style="width:8%">6</td>
                                                <td style="width:10%"><input type="text" class="form-control mt-15" placeholder=""></td>
                                                <td style="width:10%" class="bold">$98,540</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="width:23%"><input class="form-control input-limit-datepicker full-width" type="text" name="daterange" value="06/01/2018 - 06/07/2018"></td>
                                                <td style="width:10%">
                                                    <input type="text" class="form-control mt-15" placeholder="">
                                                </td>
                                                <td class="bold" style="width:8%">4</td>
                                                <td style="width:23%">afd asdf utd7c r6ftewwe fhh</td>
                                                <td class="bold" style="width:8%">$98,540</td>
                                                <td style="width:8%">6</td>
                                                <td style="width:10%"><input type="text" class="form-control mt-15" placeholder=""></td>
                                                <td style="width:10%" class="bold">$98,540</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="width:23%"><input class="form-control input-limit-datepicker full-width" type="text" name="daterange" value="06/01/2018 - 06/07/2018"></td>
                                                <td style="width:10%">
                                                    <input type="text" class="form-control mt-15" placeholder="">
                                                </td>
                                                <td class="bold" style="width:8%">4</td>
                                                <td style="width:23%">afd asdf utd7c r6ftewwe fhh</td>
                                                <td class="bold" style="width:8%">$98,540</td>
                                                <td style="width:8%">6</td>
                                                <td style="width:10%"><input type="text" class="form-control mt-15" placeholder=""></td>
                                                <td style="width:10%" class="bold">$98,540</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="width:23%"><input class="form-control input-limit-datepicker full-width" type="text" name="daterange" value="06/01/2018 - 06/07/2018"></td>
                                                <td style="width:10%">
                                                    <input type="text" class="form-control mt-15" placeholder="">
                                                </td>
                                                <td class="bold" style="width:8%">4</td>
                                                <td style="width:23%">afd asdf utd7c r6ftewwe fhh</td>
                                                <td class="bold" style="width:8%">$98,540</td>
                                                <td style="width:8%">6</td>
                                                <td style="width:10%"><input type="text" class="form-control mt-15" placeholder=""></td>
                                                <td style="width:10%" class="bold">$98,540</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="width:23%"><input class="form-control input-limit-datepicker full-width" type="text" name="daterange" value="06/01/2018 - 06/07/2018"></td>
                                                <td style="width:10%">
                                                    <input type="text" class="form-control mt-15" placeholder="">
                                                </td>
                                                <td class="bold" style="width:8%">4</td>
                                                <td style="width:23%">afd asdf utd7c r6ftewwe fhh</td>
                                                <td class="bold" style="width:8%">$98,540</td>
                                                <td style="width:8%">6</td>
                                                <td style="width:10%"><input type="text" class="form-control mt-15" placeholder=""></td>
                                                <td style="width:10%" class="bold">$98,540</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="width:23%"><input class="form-control input-limit-datepicker full-width" type="text" name="daterange" value="06/01/2018 - 06/07/2018"></td>
                                                <td style="width:10%">
                                                    <input type="text" class="form-control mt-15" placeholder="">
                                                </td>
                                                <td class="bold" style="width:8%">4</td>
                                                <td style="width:23%">afd asdf utd7c r6ftewwe fhh</td>
                                                <td class="bold" style="width:8%">$98,540</td>
                                                <td style="width:8%">6</td>
                                                <td style="width:10%"><input type="text" class="form-control mt-15" placeholder=""></td>
                                                <td style="width:10%" class="bold">$98,540</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="width:23%"><input class="form-control input-limit-datepicker full-width" type="text" name="daterange" value="06/01/2018 - 06/07/2018"></td>
                                                <td style="width:10%">
                                                    <input type="text" class="form-control mt-15" placeholder="">
                                                </td>
                                                <td class="bold" style="width:8%">4</td>
                                                <td style="width:23%">afd asdf utd7c r6ftewwe fhh</td>
                                                <td class="bold" style="width:8%">$98,540</td>
                                                <td style="width:8%">6</td>
                                                <td style="width:10%"><input type="text" class="form-control mt-15" placeholder=""></td>
                                                <td style="width:10%" class="bold">$98,540</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="width:23%"><input class="form-control input-limit-datepicker full-width" type="text" name="daterange" value="06/01/2018 - 06/07/2018"></td>
                                                <td style="width:10%">
                                                    <input type="text" class="form-control mt-15" placeholder="">
                                                </td>
                                                <td class="bold" style="width:8%">4</td>
                                                <td style="width:23%">afd asdf utd7c r6ftewwe fhh</td>
                                                <td class="bold" style="width:8%">$98,540</td>
                                                <td style="width:8%">6</td>
                                                <td style="width:10%"><input type="text" class="form-control mt-15" placeholder=""></td>
                                                <td style="width:10%" class="bold">$98,540</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="width:23%"><input class="form-control input-limit-datepicker full-width" type="text" name="daterange" value="06/01/2018 - 06/07/2018"></td>
                                                <td style="width:10%">
                                                    <input type="text" class="form-control mt-15" placeholder="">
                                                </td>
                                                <td class="bold" style="width:8%">4</td>
                                                <td style="width:23%">afd asdf utd7c r6ftewwe fhh</td>
                                                <td class="bold" style="width:8%">$98,540</td>
                                                <td style="width:8%">6</td>
                                                <td style="width:10%"><input type="text" class="form-control mt-15" placeholder=""></td>
                                                <td style="width:10%" class="bold">$98,540</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div><!--table-wrap-->
                                </div><!--table-responsive-->
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--hk-row-->

        </div><!--col-xl-9-->
        <div class="col-xl-3">
            <div class="hk-row">

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body greenbg pl-0 pr-0">
                            <h5 class="card-title center">Amounts</h5>
                            <div class="row">
                                <div class="col-sm-6 no-right">
                                    <label>SGST</label>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control mt-15" value="2" readonly="">
                                </div>
                            </div><!--row-->
                            <div class="row">
                                <div class="col-sm-6 no-right">
                                    <label>CGST</label>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control mt-15" value="2" readonly="">
                                </div>
                            </div><!--row-->
                            <div class="row">
                                <div class="col-sm-6 no-right">
                                    <label>IGST</label>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control mt-15" value="2" readonly="">
                                </div>
                            </div><!--row-->
                            <div class="row">
                                <div class="col-sm-6 no-right">
                                    <label class="total bold pa-0">Total</label>
                                </div>
                                <div class="col-sm-6 pr-0">
                                    <input type="text" class="form-control mt-15 total bold" value="5446" readonly="">
                                </div>
                            </div><!--row-->
                        </div>
                    </div>
                </div><!--col-md-12-->


                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body greybg">
                            <h5 class="card-title center">Payment Method</h5>
                            <div class="row">
                                <div class="col-sm-6 no-right">
                                    <label>Card</label>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control mt-15" placeholder="">
                                </div>
                            </div><!--row-->
                            <div class="row">
                                <div class="col-sm-6 no-right">
                                    <label>Cheque</label>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control mt-15" placeholder="">
                                </div>
                            </div><!--row-->
                            <div class="row">
                                <div class="col-sm-6 no-right">
                                    <label>Net Banking</label>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control mt-15" placeholder="">
                                </div>
                            </div><!--row-->
                            <div class="row">
                                <div class="col-sm-6 no-right">
                                    <label>Redeem Points</label>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control mt-15" placeholder="">
                                </div>
                            </div><!--row-->
                            <div class="row">
                                <div class="col-sm-6 no-right">
                                    <label>Wallet</label>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control mt-15" placeholder="">
                                </div>
                            </div><!--row-->
                            <div class="row">
                                <div class="col-sm-6 no-right">
                                    <label>Balance Amt</label>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control mt-15" placeholder="">
                                </div>
                            </div><!--row-->
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="row">

                        <div class="col-md-6 margin">
                            <button type="button" class="btn btn-light">Draft</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-success">Save & Print</button>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-6">
                            <button type="button" class="btn btn-light">Cancel</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-info">Save & New</button>

                        </div>
                    </div>
                </div>


            </div><!--hk-row-->
        </div><!--col-xl-3-->
    </div><!--row-->


    <script type="text/javascript" src="{{asset('bower_components/jquery/js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('bower_components/jquery-ui/js/jquery-ui.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('bower_components/popper.js/js/popper.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('bower_components/bootstrap/js/bootstrap.min.js')}}"></script>


    <script src="{{asset('modulejs/common.js')}}"></script>

@endsection
