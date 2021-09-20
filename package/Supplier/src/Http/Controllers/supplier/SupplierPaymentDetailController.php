<?php

namespace Retailcore\Supplier\Http\Controllers\supplier;
use App\Http\Controllers\Controller;

use App\Http\Controllers\PaymentMethodController;
use Retailcore\Debit_Note\Models\debit_note\debit_note;
use Retailcore\Inward_Stock\Models\inward\inward_stock;
use Retailcore\Products\Models\product\product;
use Retailcore\Supplier\Models\supplier\supplier_debitreceipt_details;
use Retailcore\Supplier\Models\supplier\supplier_debitreceipts;
use Retailcore\Supplier\Models\supplier\supplier_payment_detail;
use Retailcore\Supplier\Models\supplier\supplier_outstanding_detail;
use Illuminate\Http\Request;
use Auth;
use DB;
use function MongoDB\BSON\toJSON;

class SupplierPaymentDetailController extends Controller
{

    public function save_supplier_debitdetail(Request $request)
    {
        $data = $request->all();

        $company_id = Auth::User()->company_id;
        $user_id = Auth::User()->user_id;
        $supplier_debitreceipt_id = '';

        try
        {
            DB::beginTransaction();
        if(isset($data) && isset($data['supplier_receipt']) && $data['supplier_receipt'] != '')
        {
            if($data['supplier_receipt'][0]['total_amount_pay'] == $data['supplier_receipt'][0]['total'])
            {
                inward_stock::where('supplier_gst_id',$data['supplier_receipt'][0]['supplier_gst_id'])
                    ->where('company_id',$company_id)
                    ->update(array(
                        'is_payment_clear' => 1
                    ));
            }

            $debit_receipt =  supplier_debitreceipts::insertGetId([
                    'company_id' => $company_id,
                    'supplier_gst_id' => $data['supplier_receipt'][0]['supplier_gst_id'],
                    'receipt_no' => $data['supplier_receipt'][0]['receipt_no'],
                    'receipt_date' => $data['supplier_receipt'][0]['debit_date'],
                    'remarks' => $data['supplier_receipt'][0]['remarks'],
                    'total_amount' => $data['supplier_receipt'][0]['total_amount_pay'],
                    'created_by' => $user_id,
                     'created_at' => date('Y-m-d H:i:s')
                ]);

            $supplier_debitreceipt_id = $debit_receipt;



            if(isset($data['payment_detail']) && $data['payment_detail'] != '')
            {
                foreach($data['payment_detail'] AS $key=>$value)
                {
                    supplier_debitreceipt_details::insert([
                        'supplier_debitreceipt_id' => $supplier_debitreceipt_id,
                        'company_id' => $company_id,
                        'debit_note_id' => $value['supplier_debit_note_id'],
                        'payment_method_id' => $value['id'],
                        'amount' => $value['value'],
                        'created_by' => $user_id,
                       'created_at' => date('Y-m-d H:i:s')
                    ]);



                    if($value['id'] == 9)
                    {
                        debit_note::where('company_id', $company_id)
                            ->where('debit_note_id',$value['supplier_debit_note_id'])
                            ->whereNull('deleted_at')
                            ->update(array(
                                'used_amount' => DB::raw('used_amount + '.$value['value']),
                                'modified_by'=> Auth::User()->user_id
                            ));
                    }




                }
            }
        }

        $total_amount_pay = 0.00;
        if(isset($data) && isset($data['supplier_receipt']) && $data['supplier_receipt'] != '')
        {
            $total_amount_pay = $data['supplier_receipt'][0]['total_amount_pay'];
        }

        if(isset($data) && isset($data['debit_detail']) && $data['debit_detail'] != '')
        {
            $debit_detail = $data['debit_detail'];

            foreach($debit_detail AS $key=>$value)
            {
                if($total_amount_pay > 0)
                {
                    $payment_debit = supplier_payment_detail::select('amount','outstanding_payment')
                        ->where('company_id',$company_id)
                        ->where('supplier_payment_detail_id', $value['supplier_payment_detail_id'])
                        ->where('inward_stock_id', $value['inward_stock_id'])->first();

                    $debit_amt = $payment_debit['outstanding_payment'];

                    if($debit_amt <= $total_amount_pay)
                    {

                        $total_amount_py = number_format($total_amount_pay - $debit_amt,4);
                        $total_amount_pay = str_replace(',', '', $total_amount_py);
                        $deduct_am = number_format($debit_amt - $debit_amt,4);
                        $deduct_amt = str_replace(',', '', $deduct_am);
                    }
                    else
                     {
                         $deduct_a = number_format($debit_amt - $total_amount_pay,4);
                         $deduct_amt = str_replace(',', '', $deduct_a);
                         $total_amount_p = number_format($total_amount_pay - ($debit_amt - $deduct_amt),4);
                         $total_amount_pay = str_replace(',', '', $total_amount_p);
                     }

                       $update_debit_payment = supplier_payment_detail::where('inward_stock_id',$value['inward_stock_id'])
                           ->where('supplier_payment_detail_id',$value['supplier_payment_detail_id'])
                           ->where('company_id',$company_id)
                           ->update(array(
                              'outstanding_payment' => $deduct_amt
                           ));


                        if($deduct_amt == number_format(0))
                        {
                            inward_stock::where('inward_stock_id',$value['inward_stock_id'])
                                ->where('company_id',$company_id)
                                ->whereNull('deleted_at')
                                ->where('is_payment_clear',0)
                                ->update([
                                   'is_payment_clear' => 1
                                ]);
                        }

                        $amt_ded = number_format($debit_amt - $deduct_amt,4);

                        $amt_deducted = str_replace(',','',$amt_ded);

                        supplier_outstanding_detail::insert([
                           'company_id' => $company_id,
                           'supplier_payment_detail_id' => $value['supplier_payment_detail_id'],
                           'inward_stock_id' => $value['inward_stock_id'],
                           'supplier_debitreceipt_id' => $supplier_debitreceipt_id,
                           'amount' => $amt_deducted,
                           'created_by' => $user_id,
                            'created_at' => date('Y-m-d H:i:s')
                       ]);
                }
            }
        }
            DB::commit();
        }catch (\Illuminate\Database\QueryException $e)
        {
            DB::rollback();
            return json_encode(array("Success"=>"False","Message"=>$e->getMessage()));
        }

        return json_encode(array("Success"=>"True","Message"=>"Amount Debit Successfully!","url"=>"supplier_payment"));


    }


}
