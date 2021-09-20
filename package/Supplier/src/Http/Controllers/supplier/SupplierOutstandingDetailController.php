<?php

namespace Retailcore\Supplier\Http\Controllers\supplier;
use Retailcore\Company_Profile\Models\company_profile\company_profile;
use App\Http\Controllers\Controller;
use Retailcore\Inward_Stock\Models\inward\inward_stock;
use Retailcore\Supplier\Models\supplier\supplier_debitreceipts;
use Retailcore\Supplier\Models\supplier\supplier_payment_detail;
use Retailcore\Sales\Models\payment_method;
use function foo\func;
use Illuminate\Http\Request;
use Auth;
class SupplierOutstandingDetailController extends Controller
{
    public function index()
    {

        $inward_stock = inward_stock::where('company_id',Auth::user()->company_id)
            ->whereNull('deleted_at')
            ->where('is_payment_clear','=','0')
            ->with('supplier_gstdetail')
            ->select('inward_stock_id','supplier_gst_id')
            ->selectRaw('GROUP_CONCAT(inward_stock_id) as inward_stock')
            ->groupBy('supplier_gst_id')
            ->get();

        $outstanding_payment = array();
        if(isset($inward_stock)  && $inward_stock != '')
        {
            foreach ($inward_stock AS $key=>$value) {

                $outstanding = supplier_payment_detail::where('company_id', Auth::user()->company_id)
                    ->selectRaw('inward_stock_id,GROUP_CONCAT(amount) AS amount,GROUP_CONCAT(outstanding_payment) AS outstanding_payment,GROUP_CONCAT(inward_stock_id) AS inward_stock_id,GROUP_CONCAT(supplier_payment_detail_id) AS supplier_payment_detail_id')
                    ->where('outstanding_payment', '!=', NULL)
                    ->where('outstanding_payment', '>', 0)
                    ->whereRaw("find_in_set(inward_stock_id,'" . $value['inward_stock'] . "')")
                    ->get();

                if(isset($outstanding) && $outstanding != '')
                {
                    $outstanding_payment[$key]['supplier_gst_id'] = $value['supplier_gst_id'];
                    $outstanding_payment[$key] = $outstanding;
                }
            }
        }
        // $outstanding_payment = $outstanding_payment->with('supplier_gstdetail');
       return view('supplier::supplier/supplier_payment',compact('outstanding_payment'));
    }

    public function list_outstanding_payment(Request $request)
    {
        $supplier_gst_id = decrypt($request->supplier_gst_id);

        $inward_stock = inward_stock::where('supplier_gst_id', $supplier_gst_id)
            ->where('company_id',Auth::user()->company_id)
            ->groupBy('supplier_gst_id')
            ->select('inward_stock_id')
            ->selectRaw('GROUP_CONCAT(inward_stock_id) as inward_stock')
            ->get();

        $outstanding_detail = '';

        if(isset($inward_stock) &&  isset($inward_stock[0]) && $inward_stock[0]['inward_stock'] != '') {
            $outstanding_detail = supplier_payment_detail::whereNull('deleted_at')
                ->where('company_id',Auth::user()->company_id)
                ->with('inward_stock')
                ->where('outstanding_payment', '!=', NULL)
                ->where('outstanding_payment', '>', 0)
                ->whereRaw("find_in_set(inward_stock_id,'" . $inward_stock[0]['inward_stock'] . "')")
                ->get();
        }
        $payment_methods = payment_method::where('is_active','=','1')->orderBy('payment_order','ASC')->get();


        $prefix = company_profile::select('debit_receipt_prefix')->where('company_id',Auth::user()->company_id)->first();


        $receipt_prefix = $prefix['debit_receipt_prefix'];
        $last_receipt = supplier_debitreceipts::where('company_id',Auth::user()->company_id)->whereNull('deleted_at')->max('supplier_debitreceipt_id');

       $last_receipt++;

       $final_receipt_no = isset($receipt_prefix) && $receipt_prefix != '' && $receipt_prefix != NULL ? $receipt_prefix.$last_receipt : $last_receipt;


        return view('supplier::supplier/view_debitnote_detail',compact('outstanding_detail','payment_methods','final_receipt_no'));
    }

}
