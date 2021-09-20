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
            


            <div class="card">
                <div class="card-body pr-0 pl-0">
                    <div class="row ma-0">
                        <div class="col-sm-12 pa-0">
                            <div class="table-wrap">
                                <div class="table-responsive">
                                        <table class="table table-bordered tablesaw view-bill-screen table-hover w-100 display pb-30 dataTable dtr-inline tablesaw-swipe" data-tablesaw-mode="swipe"  data-tablesaw-sortable-switch data-tablesaw-minimap data-tablesaw-mode-switch role="grid" aria-describedby="datable_1_info" id="debit_receipt_table">

                                            <div class="row">
                                                <div class="col-md-9">
                                                    <a id="delete_supplier_payment" name="delete_supplier_payment">
                                                        <i class="fa fa-trash" style="font-size: 20px;color: red;margin-left: 20px;"></i></a>
                                                </div>
                                            </div>

                                        <thead>
                                        <tr class="header">
                                            <th scope="col" class="tablesaw-swipe-cellpersist" data-tablesaw-priority="persist"  style="width:1%">
                                                <div class="custom-control custom-checkbox checkbox-primary">
                                                    <input type="checkbox"  class="custom-control-input" id="checkalldebitreceipt" name="checkalldebitreceipt">
                                                    <label class="custom-control-label" for="checkalldebitreceipt"></label>
                                                </div>
                                            </th>
                                            <th scope="col" class="billsorting" data-sorting_type="asc" data-column_name="receipt_no" data-tablesaw-sortable-col data-tablesaw-priority="1">Receipt No.<span id="receipt_no_icon"></span></th>

                                            <th scope="col" class="billsorting" data-sorting_type="asc" data-column_name="receipt_date" data-tablesaw-sortable-col data-tablesaw-priority="2">Receipt Date<span id="receipt_date_icon"></span></th>

                                            <th scope="col" class="billsorting" data-sorting_type="asc" data-column_name="" data-tablesaw-sortable-col data-tablesaw-priority="3">Supplier Name</th>

                                            <th scope="col" class="billsorting" data-sorting_type="asc" data-column_name="" data-tablesaw-sortable-col data-tablesaw-priority="4">Supplier GSTIN</th>
                                            <th scope="col" class="billsorting" data-sorting_type="asc" data-column_name="" data-tablesaw-sortable-col data-tablesaw-priority="5">Remarks</th>
                                            <th scope="col" class="billsorting" data-sorting_type="asc" data-column_name="" data-tablesaw-sortable-col data-tablesaw-priority="6">Debit Date/Time</th>
                                            <th scope="col" class="billsorting" data-sorting_type="asc" data-column_name=""data-tablesaw-sortable-col data-tablesaw-priority="7">Total Amount</th>

                                            @foreach($payment_methods AS $payment_methods_key=>$payment_methods_value)
                                                <?php
                                                if($payment_methods_value->payment_method_id!=6 && $payment_methods_value->payment_method_id != 8 && $payment_methods_value->payment_method_id != 4)
                                                {
                                                ?>
                                                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="8">{{$payment_methods_value->payment_method_name}}</th>
                                                <?php
                                                }
                                                ?>
                                            @endforeach
                                        </tr>
                                        </thead>
                                        <tbody id="view_debit_receipt_record">
                                        @include('supplier::supplier.supplier_payment_receipt_data')
                                        </tbody>
                                    </table>
                                    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                                    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="supplier_debitreceipt_id" />
                                    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="DESC" />
                                    <input type="hidden" name="fetch_data_url" id="fetch_data_url" value="supplier_debit_fetch_data" />
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

        <script src="{{URL::to('/')}}/public/modulejs/supplier/supplier_debit_receipt.js"></script>


@endsection
</html>
