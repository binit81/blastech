@include('pagetitle')
@extends('master')

@section('main-hk-pg-wrapper')

    <div class="container ml-20">
        <!-- Main Content -->
        <div class="hk-pg-wrapper mt-0 pt-0 pl-0 pr-40">
            <!-- Container -->
            <div class="container pa-0 ma-0 ml-30">


                <div class="row mt-0">
                    <div class="col-xl-3 pa-0 ma-0 fixedScroll">
                        <div class="hk-row">

                            <!-- TODAY SALE -->
                            <div class="col-md-12 pa-0 ma-0">
                                <div class="card card-sm bg-default">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <span class="d-block font-12 font-weight-500 text-dark text-uppercase mb-5"><b>Today Sale (&#8377)</b></span>
                                                <span class="d-block display-6 font-weight-600 text-green">{{number_format($finalTodaySales,2)}}</span>
                                            </div>
                                            <div>
                                                <div id="sparkline_4"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- TODAY SALE -->

                            <!-- MONTH SALE -->
                            <div class="col-md-12 pa-0 ma-0">
                                <div class="card card-sm bg-default">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <span class="d-block font-12 font-weight-500 text-dark text-uppercase mb-5"><b>Sale this Month (&#8377)</b></span>
                                                <span class="d-block display-6 font-weight-600 text-green">{{number_format($finalMonthSales,2)}}</span>
                                            </div>
                                            <div>
                                                <div id="sparkline_5"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- MONTH SALE -->

                            <!-- YEAR SALE -->
                            <div class="col-md-12 pa-0 ma-0">
                                <div class="card card-sm bg-default">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <span class="d-block font-12 font-weight-500 text-dark text-uppercase mb-5"><b>Sale this Year (&#8377)</b></span>
                                                <span class="d-block display-6 font-weight-600 text-green">{{number_format($finalYearSales,2)}}</span>
                                            </div>
                                            <div>
                                                <div id="sparkline_6"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- YEAR SALE -->

                            <!-- TOTAL BILLS -->
                            <div class="col-md-12 pa-0 ma-0">
                                <div class="card card-sm bg-default">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <span class="d-block font-12 font-weight-500 text-dark text-uppercase mb-5"><b>Sales Count</b></span>
                                                <span class="d-block display-6 font-weight-600 text-green">{{$salesCount}}</span>
                                            </div>
                                            <div>
                                                <div id="pie_chart_1"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- TOTAL BILLS -->

                        </div>
                    </div>

                    <div class="col-xl-9 pa-0 ma-0">

                        <!-- Low / Out of Stock Products -->
                        <div class="col-xl-12">
                            <div class="hk-row">
                                <div class="col-md-12">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <h6>Low / Out of Stock Products <span id="totalCount"></span></h6>


                                            <div class="table-responsive">
                                                <table class="table table-primary table-bordered mb-0">
                                                    <thead class="thead-primary_default">
                                                    <tr>
                                                        <th>Barcode</th>
                                                        <th>Product Name</th>
                                                        <th>Product Code</th>
                                                        <th>In Stock</th>
                                                        <th>Stock Alert</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    if(sizeof($lowStock)!=0)
                                                    {
                                                    ?>
                                                    @foreach($lowStock as $key=>$value)
                                                        <?php
                                                        $barcode    =   $value->supplier_barcode==''?$value->product_system_barcode:$value->supplier_barcode;
                                                        ?>
                                                        <tr>
                                                            <td class="leftAlign pa-5"><small>{{$barcode}}</small></td>
                                                            <td class="leftAlign pa-5"><small>{{$value->product_name}}</small></td>
                                                            <td class="leftAlign pa-5"><small>{{$value->product_code}}</small></td>
                                                            <td class=" pa-5"><small>{{$value->totalstock}}</small></td>
                                                            <td class=" pa-5"><small>{{$value->alert_product_qty}}</small></td>
                                                        </tr>
                                                    @endforeach

                                                    <?php
                                                    $count  =   $key+1;
                                                    }
                                                    else
                                                    {
                                                    ?>
                                                    <tr>
                                                        <td colspan="5">no result found...</td>
                                                    </tr>
                                                    <?php
                                                    $count  =   0;
                                                    }
                                                    ?>

                                                    <script type="text/javascript">
                                                        $(document).ready(function(e){
                                                            $('#totalCount').html('('+<?php echo $count;?>+')');
                                                        });
                                                    </script>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Low / Out of Stock Products -->

                        <!-- Customer's Outstanding Payments -->
                        <div class="col-xl-12">
                            <div class="hk-row">
                                <div class="col-md-12">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <h6>Customer's Outstanding Payments <span id="totalCountCustomer"></span></h6>
                                            <div class="table-responsive">
                                                <table class="table table-primary table-bordered mb-0">
                                                    <thead class="thead-primary_default">
                                                    <tr>
                                                        <th width="30%">Customer Name</th>
                                                        <th>Mobile</th>
                                                        <th>Email Address</th>
                                                        <th class="rightAlign">Balance Amt</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    if(sizeof($customerbaldata)!=0)
                                                    {
                                                    ?>
                                                    @foreach($customerbaldata as $key1=>$value1)
                                                        <tr>
                                                            <td class="leftAlign pa-5"><small>{{$value1->customer['customer_name']}}</small></td>
                                                            <td class="leftAlign pa-5"><small>{{$value1->customer['customer_mobile']}}</small></td>
                                                            <td class="leftAlign pa-5"><small>{{$value1->customer['customer_email']}}</small></td>
                                                            <td class="rightAlign pa-5"><small>{{number_format($value1->totalbalance,2)}}</small></td>
                                                        </tr>
                                                    @endforeach
                                                    <?php
                                                    $count =  ($key1+1);
                                                    }
                                                    else
                                                    {
                                                    ?>
                                                    <tr>
                                                        <td colspan="4">no result found...</td>
                                                    </tr>
                                                    <?php
                                                    $count  =   0;
                                                    }
                                                    ?>


                                                    <script type="text/javascript">
                                                        $(document).ready(function(e){
                                                            $('#totalCountCustomer').html('('+<?php echo $count;?>+')');
                                                        });
                                                    </script>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Customer's Outstanding Payments -->

                        <!-- Product Expiry Alert -->
                        <div class="col-xl-12">
                            <div class="hk-row">
                                <div class="col-md-12">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <h6>Product Expiry Alert <span id="expirt_total_count"></span></h6>


                                            <div class="table-responsive">
                                                <table class="table table-primary table-bordered mb-0">
                                                    <thead class="thead-primary_default">
                                                    <tr>
                                                        <th>Barcode</th>
                                                        <th>Product Name</th>
                                                        <th>Product Code</th>
                                                        <th>In Stock</th>
                                                        <th>Will Expire in(Days)</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    if(sizeof($expiry_near_product)!=0)
                                                    {
                                                    ?>
                                                    @foreach($expiry_near_product as $expiry_key=>$expiry_value)
                                                        <?php
                                                        $barcode    =   $expiry_value->supplier_barcode==''?$expiry_value->product_system_barcode:$expiry_value->supplier_barcode;

                                                        $near_expiry_product = '';
                                                        $diff = '';



                                                        $now = strtotime(date('d-m-Y')); //CURRENT DATE
                                                        $expiry_date = strtotime($expiry_value['inward_product_detail'][0]['expiry_date']);
                                                        $datediff = $expiry_date-$now;
                                                        $diff =  round($datediff / (60 * 60 * 24));


                                                        if($diff != '')
                                                        {
                                                        if($diff <= $expiry_value->days_before_product_expiry)
                                                        {

                                                        ?>
                                                        <tr>
                                                            <td class="leftAlign pa-5"><small>{{$barcode}}</small></td>
                                                            <td class="leftAlign pa-5"><small>{{$expiry_value->product_name}}</small></td>
                                                            <td class="leftAlign pa-5"><small>{{$expiry_value->product_code}}</small></td>
                                                            <td class="rightAlign pa-5"><small>{{$expiry_value['inward_product_detail'][0]['pending_return_qty']}}</small></td>
                                                            <td class="rightAlign pa-5"><small>{{$diff}}</small></td>
                                                        </tr>
                                                        <?php
                                                        }
                                                        }

                                                        ?>
                                                    @endforeach

                                                    <?php
                                                    $count  =   $expiry_key+1;
                                                    }
                                                    else
                                                    {
                                                    ?>
                                                    <tr>
                                                        <td colspan="5">no result found...</td>
                                                    </tr>
                                                    <?php
                                                    $count  =   0;
                                                    }
                                                    ?>

                                                    <script type="text/javascript">
                                                        $(document).ready(function(e){
                                                            $('#expirt_total_count').html('('+<?php echo $count;?>+')');
                                                        });
                                                    </script>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Product Expiry Alert -->

                    </div>


                </div>

            </div>



        </div>
        <!-- /Main Content -->
    </div>

    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script src="{{URL::to('/')}}/public/modulejs/common.js"></script>
    <!-- Sparkline JavaScript -->
    <script src="{{URL::to('/')}}/vendor/jquery.sparkline/dist/jquery.sparkline.min.js"></script>

    <script type="text/javascript">
        //$(document).ready(function(e){
        var sparklineLogin = function() {
            if( $('#sparkline_4').length > 0 ){
                $("#sparkline_4").sparkline([10,25,15,20,15,10], {
                    type: 'bar',
                    width: '100',
                    height: '45',
                    barWidth: '5',
                    resize: true,
                    barSpacing: '5',
                    barColor: '#0090B3',
                    highlightSpotColor: '#88c241'
                });
            }
            if( $('#sparkline_5').length > 0 ){
                $("#sparkline_5").sparkline([20,25,15,20,15,1], {
                    type: 'bar',
                    width: '100',
                    height: '45',
                    barWidth: '5',
                    resize: true,
                    barSpacing: '5',
                    barColor: '#0090B3',
                    highlightSpotColor: '#88c241'
                });
            }
            if( $('#sparkline_6').length > 0 ){
                $("#sparkline_6").sparkline([20,25,15,20,15,1], {
                    type: 'bar',
                    width: '100',
                    height: '45',
                    barWidth: '5',
                    resize: true,
                    barSpacing: '5',
                    barColor: '#0090B3',
                    highlightSpotColor: '#88c241'
                });
            }
        }

        sparklineLogin();
        //});
    </script>




@endsection

