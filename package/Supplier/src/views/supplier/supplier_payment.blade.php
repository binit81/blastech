@include('pagetitle')
    @extends('master')

    @section('main-hk-pg-wrapper')

        <style type="text/css">
            .display-4{
                font-size:1.5rem !important;
            }
            .table thead tr.header th {

                font-size: 0.95rem !important;
            }
            .table tbody tr td {

                font-size: 0.92rem !important;
            }

        </style>

        <div class="container">
        <form id="viewbillform" name="viewbillform">
          <!--   <section class="hk-sec-wrapper" style="padding: 0.8rem 1.5rem 0 1.5rem !important;margin-top:5px !important;margin-bottom:5px !important;">
                <center><h4 class="hk-sec-title"><b>Supplier Payable Summary</b></h4></center>
                    {{--<h5 class="hk-sec-title">Filter</h5>--}}

               {{-- <div class="row ma-0">
                    <div class="col-sm">
                        <div class="row">

                            <div class="col-md-3 ">
                                <div class="form-group">
                                    <input type="text" name="invoice_no" id="invoice_no" class="form-control form-inputtext" placeholder="Invoice No."/>
                                </div>
                            </div>
                            <div class="col-md-3 ">
                                <div class="form-group">
                                    <button type="button" class="btn btn-info" id="filter_invoice_supplier_debitnote">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>--}}{{-- <div class="row ma-0">
                    <div class="col-sm">
                        <div class="row">

                            <div class="col-md-3 ">
                                <div class="form-group">
                                    <input type="text" name="invoice_no" id="invoice_no" class="form-control form-inputtext" placeholder="Invoice No."/>
                                </div>
                            </div>
                            <div class="col-md-3 ">
                                <div class="form-group">
                                    <button type="button" class="btn btn-info" id="filter_invoice_supplier_debitnote">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>--}}

            </section> -->
              <div class="card">
                <div class="card-body pr-0 pl-0">
                    <div class="row ma-0">
                        <div class="col-sm-12 pa-0">
                            <div class="table-wrap">
                                <div class="table-responsive">
                                    <table class="table table-bordered tablesaw view-bill-screen table-hover w-100 display pb-30 dataTable dtr-inline tablesaw-swipe" data-tablesaw-mode="swipe"  data-tablesaw-sortable-switch data-tablesaw-minimap data-tablesaw-mode-switch role="grid" aria-describedby="datable_1_info">
                                        <thead>
                                        <tr class="header">
                                            <th scope="col" class="billsorting" data-sorting_type="asc" data-column_name="supplier_company_name"  data-tablesaw-sortable-col data-tablesaw-priority="1">Supplier Company<span id="supplier_company_name_icon"></span></th>

                                            <th scope="col" class="billsorting" data-sorting_type="asc" data-column_name="supplier_first_name" data-tablesaw-sortable-col data-tablesaw-priority="2">Supplier Name<span id="supplier_first_name_icon"></span></th>
                                            <th scope="col" class="billsorting" data-sorting_type="asc" data-column_name="supplier_company_mobile_no" data-tablesaw-sortable-col data-tablesaw-priority="3">Mobile No.<span id="supplier_company_mobile_no  _icon"></span></th>
                                            <th scope="col" class="billsorting" data-sorting_type="asc" data-column_name=""data-tablesaw-sortable-col data-tablesaw-priority="4">Outstanding Amount</th>
                                            <th scope="col" class="billsorting" data-sorting_type="asc" data-column_name=""data-tablesaw-sortable-col data-tablesaw-priority="5">Paid Amount</th>
                                            <th scope="col" class="billsorting" data-sorting_type="asc" data-column_name="" data-tablesaw-sortable-col data-tablesaw-priority="6">Amount Payable</th>
                                            <th scope="col" class="billsorting" data-sorting_type="asc" data-column_name="productwise_discounttotal" data-tablesaw-sortable-col data-tablesaw-priority="7">Action<span id="discount_amount_icon"></span></th>
                                        </tr>
                                        </thead>
                                        <tbody id="view_bill_record">
                                        @include('supplier::supplier.supplier_payment_data')
                                        </tbody>
                                    </table>
                                    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                                    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="sales_bill_id" />
                                    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="DESC" />
                                    <input type="hidden" name="fetch_data_url" id="fetch_data_url" value="datewise_billdetail" />
                                </div>
                            </div><!--table-wrap-->
                        </div>
                    </div>
                </div><!--card-body-->
            </div>


        </form>

        <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery/js/jquery.min.js"></script>
        <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/popper.js/js/popper.min.js"></script>
        <script type="text/javascript" src="{{URL::to('/')}}/public/bower_components/bootstrap/js/bootstrap.min.js"></script>

        <script src="{{URL::to('/')}}/public/modulejs/supplier/supplier_debit.js"></script>

@endsection
</html>
