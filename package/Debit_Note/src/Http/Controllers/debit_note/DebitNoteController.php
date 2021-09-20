<?php

namespace Retailcore\Debit_Note\Http\Controllers\debit_note;
use App\Http\Controllers\Controller;

use Retailcore\Company_Profile\Models\company_profile\company_profile;
use Retailcore\Debit_Note\Models\debit_note\debit_note;
use Retailcore\Debit_Note\Models\debit_note\debit_product_detail;
use Retailcore\Inward_Stock\Models\inward\inward_product_detail;
use Retailcore\Inward_Stock\Models\inward\inward_stock;
use Retailcore\Products\Models\product\product;
use Retailcore\Products\Models\product\price_master;
use Illuminate\Http\Request;
use Auth;
use DB;
class DebitNoteController extends Controller
{

    public function index()
    {
        $f1 = (date('m') < '04') ? date('y', strtotime('-1 year')) : date('y');
        $f2 = (date('m') > '03') ? date('y', strtotime('+1 year')) : date('y');

        $max_debit = debit_note::where('company_id', Auth::user()->company_id)->get()->max('debit_note_id');
        $debit_prefix = company_profile::where('company_id', Auth::user()->company_id)->select('debit_receipt_prefix', 'tax_type')->first();
        $tax_type = $debit_prefix['tax_type'];
        if ($max_debit == '') {
            $max_debit = 1;
        } else {
            $max_debit++;
        }
        $debit_prefix_final = 'DEBIT-';

        if (isset($debit_prefix) && isset($debit_prefix['debit_receipt_prefix']) && $debit_prefix['debit_receipt_prefix'] != '' && $debit_prefix['debit_receipt_prefix'] != null) {
            $debit_prefix_final = $debit_prefix['debit_receipt_prefix'];
        }

        $debit_no = $debit_prefix_final . $max_debit . '/' . $f1 . '-' . $f2;

        $company_state = company_profile::select('state_id')->where('company_id', Auth::user()->company_id)->get()->first();
        $company_state_id = $company_state['state_id'];


        return view('debit_note::debit_note/debit_note_view', compact('debit_no', 'company_state_id', 'tax_type'));
    }


    public function invoice_no_search(Request $request)
    {

        $result = inward_stock::select('inward_stock_id', 'supplier_gst_id', 'invoice_no', 'inward_type')
            ->where('company_id', Auth::user()->company_id)
            ->whereNull('deleted_at')
            ->where('invoice_no', 'LIKE', "%$request->search_val%")
            ->with('supplier_gstdetail.supplier_company_info')
            ->get();

        if (isset($result) && $result != '') {
            foreach ($result AS $key => $value) {
                $group_product = inward_product_detail::where('inward_stock_id', $value['inward_stock_id'])
                    ->where('company_id', Auth::user()->company_id)
                    ->whereNull('deleted_at')
                    ->selectRaw('GROUP_CONCAT(product_id) AS products_val')->first();


                if (isset($group_product) && $group_product != '') {

                    $result[$key]['product_val'] = $group_product['products_val'];
                }

            }
        }

        return json_encode(array("Success" => "True", "Data" => $result));
    }


    public function debit_productsearch(Request $request)
    {

        $result = product::select('item_type', 'product_name', 'product_system_barcode', 'product_id', 'supplier_barcode')
            ->Where('product_system_barcode', '!=', "")
            ->whereNull('deleted_at')
            ->with('price_master')
            ->whereRaw("find_in_set(product_id,'" . $request->invoice_product . "')");

        // ->where('product_name', 'LIKE', "%$request->search_val%")
        //  ->where('product_system_barcode','LIKE', "%$request->search_val%")
        //  ->orWhere('product_code', 'LIKE', "%$request->search_val%")
        //  ->where('supplier_barcode', 'LIKE', "%$request->search_val%");
        /*->where(function($q) use($request)
       {
           $q->where('company_id',Auth::user()->company_id)
               ->whereNotNull('product_name')
               ->where('product_system_barcode','!=',"")

               ->where('item_type','=','1');
       });*/
        $result = $result->skip(0)->take(10)->get();
        return json_encode(array("Success" => "True", "Data" => $result));
    }


    public function inward_productdetail(Request $request)
    {
        $data = $request->all();

        $inward_product = inward_product_detail::where('product_id', $data['product_id'])
            ->where('inward_stock_id', $data['inward_stock_id'])
            ->where('company_id', Auth::user()->company_id)
            ->where('batch_no', $data['batch_no'])
            ->with('product')
            ->WhereNull('deleted_at')->get();


        $price = price_master::where('product_id', $data['product_id'])
            ->where('company_id', Auth::user()->company_id)
            ->whereNull('deleted_at')
            ->sum('product_qty');

        $inward_product[0]['in_stock'] = $price;
        return json_encode(array("Success" => "True", "Data" => $inward_product));

    }

    public function add_debit_note(Request $request)
    {
        $data = $request->all();
        $company_id = Auth::User()->company_id;

        $debit_note_id = $data['debit_note_id'];
        $userId = Auth::User()->user_id;
        if (isset($data['debit_note']) && isset($data['debit_note'][0]) && $data['debit_note'][0] != '') {
            $data['debit_note'][0]['created_by'] = $userId;
            try {
                DB::beginTransaction();
                $debit_insert = debit_note::updateOrCreate(
                    [
                        'debit_note_id' => $debit_note_id,
                        'company_id' => $company_id,
                        'inward_stock_id' => $data['debit_note'][0]['inward_stock_id'],
                        'supplier_gst_id' => $data['debit_note'][0]['supplier_gst_id']
                    ], $data['debit_note'][0]
                );

                $debit_note_id = $debit_insert->debit_note_id;

                if (isset($data['debit_product_detail']) && $data['debit_product_detail'] != '') {
                    foreach ($data['debit_product_detail'] AS $key => $value) {
                        inward_product_detail::
                        where('inward_product_detail_id', $value['inward_product_detail_id'])
                            ->where('company_id', $company_id)
                            ->where('inward_stock_id', $data['debit_note'][0]['inward_stock_id'])
                            ->where('supplier_gst_id', $data['debit_note'][0]['supplier_gst_id'])
                            ->where('product_id', $value['product_id'])
                            ->update(array(
                                'pending_return_qty' => $value['pending_qty']
                            ));

                        $price_master_qty = price_master::where('company_id', $company_id)
                            //->where('inward_stock_id',$data['debit_note'][0]['inward_stock_id'])
                            ->where('product_id', $value['product_id'])
                            ->where('batch_no', $value['batch_no'])
                            ->select('product_qty', 'price_master_id')->first();


                        $final_qty = 0;
                        if (isset($price_master_qty) && $price_master_qty != '') {

                            if (isset($value['debit_product_detail_id']) && $value['debit_product_detail_id'] != '') {
                                $debit_product_detail = debit_product_detail::where('company_id', $company_id)
                                    ->where('debit_product_detail_id', $value['debit_product_detail_id'])
                                    ->where('product_id', $value['product_id'])
                                    ->whereNull('deleted_at')
                                    ->select('return_qty')->first();

                                if (isset($debit_product_detail) && $debit_product_detail['return_qty'] != '') {
                                    $final_qty = ($debit_product_detail['return_qty'] + $price_master_qty['product_qty']);
                                }

                                $updated_qty = ($final_qty - $value['return_qty']);
                            } else {
                                $updated_qty = ($price_master_qty['product_qty'] - $value['return_qty']);
                            }


                            $price_master_qty_update = price_master::where('company_id', $company_id)
                                //->where('inward_stock_id',$data['debit_note'][0]['inward_stock_id'])
                                ->where('product_id', $value['product_id'])
                                ->where('price_master_id', $price_master_qty['price_master_id'])
                                ->update(array(
                                    'product_qty' => $updated_qty
                                ));

                            debit_product_detail::updateOrCreate(
                                [
                                    'company_id' => $company_id,
                                    'debit_product_detail_id' => $value['debit_product_detail_id'],
                                    'debit_note_id' => $debit_note_id,
                                    'product_id' => $value['product_id'],
                                ],
                                [
                                    'price_master_id' => $price_master_qty['price_master_id'],
                                    'base_price' => $value['base_price'],
                                    'cost_rate' => $value['cost_rate'],
                                    'cost_gst_percent' => $value['cost_gst_percent'],
                                    'cost_gst_amount' => $value['cost_gst_amount'],
                                    'return_qty' => $value['return_qty'],
                                    'total_gst' => $value['total_gst'],
                                    'total_cost_rate' => $value['total_cost_rate'],
                                    'total_cost_price' => $value['total_cost_price'],
                                    'remarks' => $value['remarks'],
                                    'cost_igst_percent' => $value['cost_igst_percent'],
                                    'cost_igst_amount' => $value['cost_igst_amount'],
                                    'cost_cgst_percent' => $value['cost_cgst_percent'],
                                    'cost_cgst_amount' => $value['cost_cgst_amount'],
                                    'cost_sgst_percent' => $value['cost_sgst_percent'],
                                    'cost_sgst_amount' => $value['cost_sgst_amount'],
                                    'total_igst_amount_with_qty' => $value['total_igst_amount_with_qty'],
                                    'total_cgst_amount_with_qty' => $value['total_cgst_amount_with_qty'],
                                    'total_sgst_amount_with_qty' => $value['total_sgst_amount_with_qty'],
                                ]
                            );

                        }

                    }

                    /*  inward_stock::where('inward_stock_id',$data['debit_note'][0]['inward_stock_id'])
                          ->where('company_id',$company_id)
                          ->whereNull('deleted_at');*/
                }


                if ($debit_note_id) {
                    $f1 = (date('m') < '04') ? date('y', strtotime('-1 year')) : date('y');
                    $f2 = (date('m') > '03') ? date('y', strtotime('+1 year')) : date('y');

                    $max_debit = debit_note::where('company_id', Auth::user()->company_id)->get()->max('debit_note_id');
                    $debit_prefix = company_profile::where('company_id', Auth::user()->company_id)->select('debit_receipt_prefix')->first();
                    if ($max_debit == '') {
                        $max_debit = 1;
                    } else {
                        $max_debit++;
                    }


                    $debit_prefix_final = 'DEBIT-';

                    if (isset($debit_prefix) && isset($debit_prefix['debit_receipt_prefix']) && $debit_prefix['debit_receipt_prefix'] != '' && $debit_prefix['debit_receipt_prefix'] != null) {
                        $debit_prefix_final = $debit_prefix['debit_receipt_prefix'];
                    }

                    $debit_no = $debit_prefix_final . $max_debit . '/' . $f1 . '-' . $f2;
                }
                DB::commit();
            } catch (\Illuminate\Database\QueryException $e)
            {
                DB::rollback();
                return json_encode(array("Success"=>"False","Message"=>$e->getMessage()));
            }

                    if ($data['debit_note_id'] != '') {

                        return json_encode(array("Success" => "True", "Message" => "Debit Note has been successfully updated.", "debit_no" => $debit_no, "url" => "view_debit_note", "debit_note_id" => encrypt($debit_note_id)));
                    } else {
                        return json_encode(array("Success" => "True", "Message" => "Debit Note has been successfully added.", "debit_no" => $debit_no, "url" => '', 'debit_note_id' => encrypt($debit_note_id)));
                    }
                } else {
                    return json_encode(array("Success" => "False", "Message" => "Something Went Wrong"));

        }


    }

    public function debit_note_delete(Request $request)
    {
        $userId = Auth::User()->user_id;

        $delete_id = $request->deleted_id;

        foreach ($delete_id AS $key => $value) {
            try {
                DB::beginTransaction();
                $debit_note = debit_note::where('debit_note_id', $value['debit_note_id'])
                    ->where('inward_stock_id', $value['inward_stock_id'])
                    ->where('supplier_gst_id', $value['supplier_gst_id'])
                    ->where('company_id', Auth::user()->company_id)
                    ->update([
                        'deleted_by' => $userId,
                        'deleted_at' => date('Y-m-d H:i:s')
                    ]);

                $debit_product_detail = debit_product_detail::select('debit_product_detail_id', 'product_id', 'return_qty')
                    ->where('debit_note_id', '=', $value['debit_note_id'])
                    ->where('company_id', Auth::user()->company_id)
                    ->whereNull('deleted_at')
                    ->get();


                if (isset($debit_product_detail) && isset($debit_product_detail[0]) && $debit_product_detail[0] != '') {
                    foreach ($debit_product_detail AS $debit_key => $debit_value) {
                        $inward_product_detail = inward_product_detail::select('inward_product_detail_id', 'pending_return_qty', 'batch_no', 'offer_price')
                            ->where('company_id', Auth::user()->company_id)
                            //->where('inward_stock_id',$value['inward_stock_id'])
                            ->where('supplier_gst_id', $value['supplier_gst_id'])
                            ->whereNull('deleted_at')
                            ->where('product_id', $debit_value['product_id'])->get();

                        $total_pending = $debit_value['return_qty'] + $inward_product_detail[0]['pending_return_qty'];

                        inward_product_detail::where('inward_product_detail_id', $inward_product_detail[0]['inward_product_detail_id'])
                            ->where('company_id', Auth::user()->company_id)
                            ->update(array(
                                'pending_return_qty' => $total_pending
                            ));


                        price_master::where('product_id', $debit_value['product_id'])
                            ->where('batch_no', $inward_product_detail[0]['batch_no'])
                            ->where('company_id', Auth::user()->company_id)
                            ->where('offer_price', $inward_product_detail[0]['offer_price'])->update(array(
                                'modified_by' => $userId,
                                'product_qty' => DB::raw('product_qty + ' . $debit_value['return_qty'])
                            ));


                        $debit_product_detail = debit_product_detail::where('debit_product_detail_id', $debit_value['debit_product_detail_id'])
                            ->update([
                                'deleted_by' => $userId,
                                'deleted_at' => date('Y-m-d H:i:s')
                            ]);
                    }
                }
                DB::commit();
            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollBack();
                return json_encode(array("Success" => "False", "Message" => $e->getMessage()));
            }
        }
        return json_encode(array("Success" => "True", "Message" => "Debit Note has been successfully deleted.!"));

    }


    //for print Debit Note
    public function print_debit_note(Request $request)
    {
        $debit_note_id = decrypt($request->id);

        $debit_note = debit_note::where('company_id', Auth::user()->company_id)
            ->where('deleted_at', '=', NULL)
            ->where('debit_note_id', '=', $debit_note_id)
            ->with('company')
            ->with('debit_product_details')
            ->get();

        $debit_gst_breakup = debit_product_detail::
        select('cost_cgst_percent', 'cost_sgst_percent', 'cost_igst_percent', 'cost_gst_percent',
            DB::raw("SUM(debit_product_details.total_cost_rate) as total_taxable_value"),
            DB::raw("SUM(debit_product_details.cost_rate) as total_cost_rate_value"),
            DB::raw("SUM(debit_product_details.cost_gst_percent) as total_gst"),
            DB::raw("SUM(debit_product_details.total_cgst_amount_with_qty) as total_cgst_amount"),
            DB::raw("SUM(debit_product_details.total_cgst_amount_with_qty) as total_cgst_amount_with_qty"),
            DB::raw("SUM(debit_product_details.cost_sgst_amount) as total_sgst_amount"),
            DB::raw("SUM(debit_product_details.total_sgst_amount_with_qty) as total_sgst_amount_with_qty"),
            DB::raw("SUM(debit_product_details.cost_igst_amount) as total_igst_amount"),
            DB::raw("SUM(debit_product_details.total_igst_amount_with_qty) as total_igst_amount_with_qty"),
            DB::raw("SUM(debit_product_details.total_cost_price) as total_grand"))
            ->where('debit_note_id', '=', $debit_note_id)->groupBy('cost_gst_percent')->get();


        return view('debit_note::debit_note/debit_note_print', compact('debit_note', 'debit_gst_breakup'));
    }


    //FOR GET DEBIT NOTE AMOUNT.THIS METHOD USED IN INWARD STOCK
    public function get_debit_note_amount(Request $request)
    {
        $debit_no = $request->debit_note_no;

        $amount = debit_note::where('company_id', Auth::user()->company_id)
            ->where('debit_no', $debit_no)
            ->where('deleted_at', NULL)->first();

        return json_encode(array("Success" => "True", "Data" => $amount));
    }


}