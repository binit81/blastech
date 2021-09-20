<?php

namespace Retailcore\Supplier\Http\Controllers\supplier;

use App\Http\Controllers\Controller;


use App\Http\Controllers\product\ProductImageController;
use Retailcore\Debit_Note\Models\debit_note\debit_note;
use Retailcore\Supplier\Models\supplier\supplier_debitreceipt_details;
use Retailcore\Supplier\Models\supplier\supplier_debitreceipts;
use Retailcore\Supplier\Models\supplier\supplier_outstanding_detail;
use Retailcore\Supplier\Models\supplier\supplier_payment_detail;
use Retailcore\Inward_Stock\Models\inward\inward_stock;
use Retailcore\Sales\Models\payment_method;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use Auth;
use DB;

class SupplierPaymentreceiptsController extends Controller
{

    public function index()
    {
        $payment_methods = payment_method::where('is_active', '=', '1')->orderBy('payment_method_id', 'asc')->get();
        $debit_receipt = supplier_debitreceipts::where('company_id', Auth::user()->user_id)
            ->with('supplier_gstdetail')
            ->with('supplier_debitreceipt_details')
            ->whereNull('deleted_at')
            ->orderBy('supplier_debitreceipt_id', 'DESC')
            ->paginate(10);

        return view('supplier::supplier/supplier_payment_receipt', compact('debit_receipt', 'payment_methods'));
    }


    public function supplier_debit_receipt_data(Request $request)
    {
        $payment_methods = payment_method::where('is_active', '=', '1')->orderBy('payment_method_id', 'asc')->get();
        if ($request->ajax()) {
            $debit_receipt = supplier_debitreceipts::where('company_id', Auth::user()->company_id)
                ->with('supplier_gstdetail')
                ->with('supplier_debitreceipt_details')
                ->whereNull('deleted_at')
                ->orderBy('supplier_debitreceipt_id', 'DESC')
                ->paginate(10);

            return view('supplier.supplier_payment_receipt_data', compact('debit_receipt', 'payment_methods'))->render();
        }
    }


    function supplier_debit_fetch_data(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            $sort_by = $data['sortby'];
            $sort_type = $data['sorttype'];
            //$query = $data['query'];

            //$query = str_replace(" ", "%", $query);

            $payment_methods = payment_method::where('is_active', '=', '1')->orderBy('payment_method_id', 'asc')->get();
            $debit_receipt = supplier_debitreceipts::where('company_id', Auth::user()->company_id)
                ->with('supplier_gstdetail')
                ->with('supplier_debitreceipt_details')
                ->whereNull('deleted_at')
                ->orderBy('supplier_debitreceipt_id', 'DESC')
                ->orderBy($sort_by, $sort_type)
                ->paginate(10);


            return view('supplier.supplier_debit_receipt_data', compact('debit_receipt', 'payment_methods'))->render();

        }
    }

    public function supplier_payment_delete(Request $request)
    {
        $data = $request->all();


        if (isset($data) && isset($data['deleted_id']) && $data['deleted_id'] != '') {
            $userId = Auth::User()->user_id;
            try {
                DB::beginTransaction();
                foreach ($data['deleted_id'] AS $key => $value) {
                    $amount = supplier_debitreceipts::where('company_id', Auth::user()->user_id)
                        ->where('supplier_debitreceipt_id', $value)
                        ->select('total_amount')->first();

                    $debit_receipt = supplier_debitreceipts::where('company_id', Auth::user()->user_id)
                        ->where('supplier_debitreceipt_id', $value)
                        ->update([
                            'deleted_by' => $userId,
                            'deleted_at' => date('Y-m-d H:i:s')
                        ]);

                    $debit_amount = supplier_debitreceipt_details::where('company_id', Auth::user()->user_id)
                        ->where('supplier_debitreceipt_id', $value)
                        ->where('payment_method_id', 9)
                        ->select('debit_note_id', 'amount')->first();

                    if (isset($debit_amount) && $debit_amount != '' && isset($debit_amount['debit_note_id']) && $debit_amount['debit_note_id'] != '') {
                        debit_note::where('company_id', Auth::user()->user_id)
                            ->where('debit_note_id', $debit_amount['debit_note_id'])
                            ->update([
                                'used_amount' => DB::raw('used_amount -' . $debit_amount['amount']),
                                'modified_by' => $userId
                            ]);
                    }


                    $debit_receipt_detail = supplier_debitreceipt_details::where('company_id', Auth::user()->user_id)
                        ->where('supplier_debitreceipt_id', $value)
                        ->update([
                            'deleted_by' => $userId,
                            'deleted_at' => date('Y-m-d H:i:s')
                        ]);


                    $payment_detail_id = supplier_outstanding_detail::where('company_id', Auth::user()->user_id)
                        ->where('supplier_debitreceipt_id', $value)
                        ->select('supplier_payment_detail_id', 'amount')->whereNull('deleted_at')
                        ->get();

                    $supplier_outstanding_detail = supplier_outstanding_detail::where('company_id', Auth::user()->user_id)
                        ->where('supplier_debitreceipt_id', $value)
                        ->update([
                            'deleted_by' => $userId,
                            'deleted_at' => date('Y-m-d H:i:s')
                        ]);

                    $total_amount = str_replace(',', '', $amount['total_amount']);

                    if (isset($payment_detail_id) && $payment_detail_id != '') {
                        foreach ($payment_detail_id AS $key => $value) {
                            if ($total_amount == 0) {
                                exit;
                            }
                            if (isset($value['supplier_payment_detail_id']) && $value['supplier_payment_detail_id'] != '') {

                                $supplier_payment_detail = supplier_payment_detail::where('supplier_payment_detail_id', $value['supplier_payment_detail_id'])
                                    ->where('payment_method_id', '6')
                                    ->where('company_id', Auth::user()->company_id)
                                    ->select('inward_stock_id', 'amount', 'outstanding_payment')->first();

                                $outstanding_amt = (isset($supplier_payment_detail['outstanding_payment']) && $supplier_payment_detail['outstanding_payment'] != NULL) ? $supplier_payment_detail['outstanding_payment'] : 0;
                                $add_debit = 0;


                                $add_debits = number_format($outstanding_amt + $value['amount'], 4);
                                $add_debit = str_replace(',', '', $add_debits);

                                $total_amount = number_format($total_amount - $value['amount'], 4);
                                $total_amount = str_replace(',', '', $total_amount);
                                /*if ($supplier_payment_detail['amount'] >= $total_amount) {

                                    $diffs = number_format($supplier_payment_detail['amount'] - $outstanding_amt, 4);
                                    $diff = str_replace(',','',$diffs);


                                    $add_debits = number_format($outstanding_amt + ($diff-$outstanding_amt), 4);
                                    $add_debit = str_replace(',','',$add_debits);


                                    $total_amount = number_format($total_amount - $diff, 4);
                                    $total_amount = str_replace(',','',$total_amount);
                                } else {

                                    $diffs = number_format($supplier_payment_detail['amount'] - $outstanding_amt);
                                    $diff = str_replace(',','',$diffs);

                                    $add_debits = number_format($outstanding_amt + $diff, 4);
                                    $add_debit = str_replace(',','',$add_debits);

                                    $total_amount = number_format($total_amount - $diff, 4);
                                    $total_amount = str_replace(',','',$total_amount);
                                }*/


                                $add_debit_detail = supplier_payment_detail::where('company_id', Auth::user()->company_id)
                                    ->where('supplier_payment_detail_id', $value['supplier_payment_detail_id'])
                                    ->where('payment_method_id', '6')
                                    ->whereNull('deleted_at')
                                    ->update([
                                        'outstanding_payment' => $add_debit
                                    ]);

                                if ($add_debit != '' && $add_debit != NULL && $add_debit != 0) {
                                    inward_stock::where('inward_stock_id', $supplier_payment_detail['inward_stock_id'])
                                        ->where('company_id', Auth::user()->company_id)
                                        ->update([
                                            'is_payment_clear' => 0
                                        ]);
                                }

                            }


                        }

                    }
                }
                DB::commit();
            }
            catch (\Illuminate\Database\QueryException $e)
            {
                DB::rollback();
                return json_encode(array("Success"=>"False","Message"=>$e->getMessage()));
            }

            return json_encode(array("Success" => "True", "Message" => "Debit Receipt Deleted Successfully!"));
        } else {
            return json_encode(array("Success" => "False", "Message" => "Something went wrong!please reselect debit receipt you want to delete!"));
        }
    }


}
