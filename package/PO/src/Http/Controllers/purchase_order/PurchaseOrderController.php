<?php

namespace Retailcore\PO\Http\Controllers\Purchase_order;
use  App\Http\Controllers\Controller;

use Retailcore\Inward_Stock\Models\inward\inward_product_detail;
use Retailcore\Products\Models\product\price_master;
use Retailcore\PO\Models\purchase_order\purchase_order;
use Retailcore\Products\Models\product\product;
use Retailcore\Company_Profile\Models\company_profile\company_profile;
use Retailcore\PO\Models\purchase_order\purchase_order_detail;
use Retailcore\PO\Models\purchase_order\po_report_export_excel;
use function foo\func;
use Illuminate\Http\Request;
use Auth;
use DB;
use Maatwebsite\Excel\Facades\Excel;
class PurchaseOrderController extends Controller
{

    public function index()
    {
        $f1     =    (date('m')<'04') ? date('y',strtotime('-1 year')) : date('y');
        $f2     =    (date('m')>'03') ? date('y',strtotime('+1 year')) : date('y');

        $max_po = purchase_order::where('company_id',Auth::user()->company_id)->get()->max('purchase_order_id');

        if($max_po == '')
        {
            $max_po = 1;
        }
        else
        {
            $max_po++;
        }
        $po_no  =   "PO-".$max_po.'/'.$f1.'-'.$f2;

        $company_info  =  company_profile::select('po_terms_and_condition')->where('company_id',Auth::user()->company_id)->first();

        $po_terms_condition = $company_info['po_terms_and_condition'];

        return view('PO::purchase_order/purchase_order_show',compact('po_no','po_terms_condition'));
    }

    public function view_issue_po()
    {
        $purchase_order = purchase_order::where('company_id',Auth::user()->company_id)
                 ->where('deleted_at','=',NULL)
                 ->orderBy('purchase_order_id', 'DESC')
                 ->paginate(10);

           if(isset($purchase_order[0]) && $purchase_order[0] != '')
           {
               foreach ($purchase_order AS $key=>$value)
               {
                   $qty = purchase_order_detail::where('company_id', Auth::user()->company_id)
                       ->selectRaw('SUM(received_qty) AS received_qty,SUM(pending_qty) AS pending_qty')
                        ->where('received_qty', '!=', NULL)
                        ->where('purchase_order_id', '=', $value['purchase_order_id'])
                        ->groupBy('purchase_order_id')
                       ->first();

                   $recieved_qty = isset($qty['received_qty']) && $qty['received_qty'] != '' ? $qty['received_qty'] : 0;

                   $purchase_order[$key]['received_qty'] = $recieved_qty;
                   $purchase_order[$key]['pending_qty'] = $qty['pending_qty'];

               }
           }

        return view('PO::purchase_order/view_purchase_order',compact('purchase_order'));
    }

    function purchase_order_fetch_data(Request $request)
    {
        if($request->ajax())
        {
            $data = $request->all();
            $sort_by = isset($data['sortby'])?$data['sortby']:'purchase_order_id';
            $sort_type = isset($data['sorttype'])?$data['sorttype']:'desc';
            $query = isset($data['query']) ? $data['query'] : '';
            $query = str_replace(" ", "", $query);

            $purchase_order = purchase_order::where('company_id', Auth::user()->company_id)
                ->where('deleted_at', '=', NULL)->orderBy($sort_by, $sort_type)->paginate(10);

            if($query != '')
            {
                if ($query['from_date'] != '' || $query['to_date'] != '') {
                    $purchase_order = purchase_order::WhereBetween('po_date', [$query['from_date'], $query['to_date']])->orderBy($sort_by, $sort_type)->paginate(10);
                }
                if ($query['po_no'] != '') {
                    $purchase_order = purchase_order::where('po_no', 'like', "%".$query['po_no']."%")->orderBy($sort_by, $sort_type)->paginate(10);
                }
                if ($query['supplier_name'] != '') {
                    $purchase_order = purchase_order::where('supplier_gst_id', '=', $query['supplier_name'])->orderBy($sort_by, $sort_type)->paginate(10);
                }
            }
            if(isset($purchase_order[0]) && $purchase_order[0] != '')
            {
                foreach ($purchase_order AS $key=>$value)
                {
                    $qty = purchase_order_detail::where('company_id', Auth::user()->company_id)
                        ->selectRaw('SUM(received_qty) AS received_qty,SUM(pending_qty) AS pending_qty')
                        ->where('received_qty', '!=', NULL)
                        ->where('purchase_order_id', '=', $value['purchase_order_id'])
                        ->groupBy('purchase_order_id')
                        ->first();

                    $recieved_qty = isset($qty['received_qty']) && $qty['received_qty'] != '' ? $qty['received_qty'] : 0;

                    $purchase_order[$key]['received_qty'] = $recieved_qty;
                    $purchase_order[$key]['pending_qty'] = $qty['pending_qty'];
                }
            }
            $po_terms_condition  =  company_profile::select('po_terms_and_condition')->where('company_id',Auth::user()->company_id)->first();

            return view('PO::purchase_order/view_purchase_order_data', compact('purchase_order','po_terms_condition'))->render();
        }
    }

    public function po_product_detail(Request $request)
    {
        $result = product::where('product_id',$request->product_id)
            ->where('company_id',Auth::user()->company_id)
            ->where('item_type',1)
            ->with('uqc')
            ->with('size')
           ->get();

        $latest_price = inward_product_detail::where('company_id',Auth::user()->company_id)
            ->where('product_id',$request->product_id)
            ->whereNull('deleted_at')
            ->select('base_price')
            ->orderBy('updated_at','DESC')->first();

       if(isset($latest_price) && $latest_price != '' && isset($latest_price['base_price']) && $latest_price['base_price'] != '')
       {
           $result[0]['cost_rate'] = $latest_price['base_price'];
       }

       $price = price_master::where('product_id',$request->product_id)
            ->where('company_id',Auth::user()->company_id)
            ->whereNull('deleted_at')
            ->sum('product_qty');

        $result[0]['in_stock'] = $price;


        return json_encode(array("Success"=>"True","Data"=>$result));
    }


    public function add_purchase_order(Request $request)
    {
        $data = $request->all();
        $company_id = Auth::User()->company_id;

       if(isset($data['purchase_order']) && isset($data['purchase_order'][0]) && $data['purchase_order'][0] != '') {
            $purchase_order_data = $data['purchase_order'][0];

            $purchase_order_data['created_by'] = Auth::User()->user_id;
            $purchase_order_data['deleted_at'] = NULL;
            $purchase_order_data['deleted_by'] = NULL;

            try {
                DB::beginTransaction();
                $purchase_order_insert = purchase_order::updateOrCreate(
                    ['purchase_order_id' => $data['purchase_order_id'],
                        'company_id' => $company_id,
                        'po_no' => $purchase_order_data['po_no'],
                    ], $purchase_order_data);

                $purchase_order_id = $purchase_order_insert->purchase_order_id;

                if (isset($purchase_order_insert) && $purchase_order_insert != '') {
                    if (isset($data['purchase_order_detail']) && $data['purchase_order_detail'] != '')
                    {

                        foreach ($data['purchase_order_detail'] AS $key => $value) {

                            $value['created_by'] = Auth::User()->user_id;
                            $value['deleted_at'] = NULL;
                            $value['deleted_by'] = NULL;
                            $value['pending_qty'] = $value['qty'];
                            $value['received_qty'] = 0;

                            $purchase_detail_insert = purchase_order_detail::updateOrCreate(
                                ['purchase_order_detail_id' => $value['purchase_order_detail_id'],
                                    'company_id' => $company_id,
                                    'purchase_order_id' => $purchase_order_id,
                                    'product_id' => $value['product_id'],
                                ], $value);

                        }
                    }
                }

                if($purchase_order_id)
                {
                    $f1     =    (date('m')<'04') ? date('y',strtotime('-1 year')) : date('y');
                    $f2     =    (date('m')>'03') ? date('y',strtotime('+1 year')) : date('y');

                    $max_po = purchase_order::where('company_id',Auth::user()->company_id)->get()->max('purchase_order_id');

                    if($max_po == '')
                    {
                        $max_po = 1;
                    }
                    else
                    {
                        $max_po++;
                    }


                    $po_no  =   "PO-".$max_po.'/'.$f1.'-'.$f2;
                    DB::commit();

                    if ($data['purchase_order_id'] != '')
                    {

                        return json_encode(array("Success"=>"True","Message"=>"Purchase Order has been successfully updated.","po_no"=>$po_no,"url"=>"view_issue_po","purchase_order_id"=>encrypt($purchase_order_id)));
                    }
                    else
                    {
                        return json_encode(array("Success"=>"True","Message"=>"Purchase Order has been successfully added.","po_no"=>$po_no,"url"=>'',"purchase_order_id"=>encrypt($purchase_order_id)));
                    }
                }
                else
                {
                    return json_encode(array("Success"=>"False","Message"=>"Something Went Wrong"));
                }


            }catch (\Illuminate\Database\QueryException $e)
            {
                DB::rollback();
                return json_encode(array("Success" => "False", "Message" => $e->getMessage()));
                exit;
            }
        }

    }


    public function edit_purchase_order(Request $request)
    {

        $purchase_order_id = decrypt($request->purchase_order_id);

        $purchase_order = purchase_order::where([
            ['purchase_order_id','=',$purchase_order_id],
            ['company_id',Auth::user()->company_id]])
            ->with('purchase_order_detail.product.uqc')
            ->with('purchase_order_detail.product.size')
            ->with('supplier_gstdetail.supplier_company_info')
            ->select('*')
            ->first();

        foreach ($purchase_order['purchase_order_detail'] AS $key=>$value)
        {
            $price = price_master::where('product_id',$value['product_id'])
                ->whereNull('deleted_at')
                ->sum('product_qty');

            $purchase_order['purchase_order_detail'][$key]['in_stock'] = $price;
        }

        $data = json_encode($purchase_order);

        //type 1= edit po in issue po screen
        //type 2= take inward of this po

        if($request->type == 1)
        {
            $url = 'issue_po';
        }
        else
        {
            $inward_type = company_profile::select('inward_type')->where('company_id',Auth::user()->company_id)->first();

            if(!isset($inward_type) && $inward_type == '')
            {
                $url = 'inward_stock';
            }
            else if($inward_type['inward_type'] == 1)
            {
                $url = 'inward_stock';
            }
            else
            {
                $url = 'inward_stock_show';
            }
        }




        return json_encode(array("Success"=>"True","Data"=>$data,"url"=>$url));
    }


    public function po_report_export(Request $request)
    {
        return Excel::download(new po_report_export_excel($request->from_date,$request->to_date,$request->po_no,$request->supplier_name),'PO-Report.xlsx');
    }


    //for print po

    public function print_po(Request $request)
    {

        ///print_type = 1  ::: print po without pending and received qty
        ///print_type = 2  ::: print po with pending and received qty

        $po_id  = decrypt($request->id);
        $print_type  = decrypt($request->print_type);


        $purchase_order = purchase_order::where('company_id',Auth::user()->company_id)
                 ->where('deleted_at','=',NULL)
                 ->where('purchase_order_id','=',$po_id)
                 ->with('company')
                 ->with('purchase_order_detail')
            ->get();

        return view('PO::purchase_order/po_print',compact('purchase_order','print_type'));

    }

}

