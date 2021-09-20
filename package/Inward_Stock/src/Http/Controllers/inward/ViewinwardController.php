<?php

namespace Retailcore\Inward_Stock\Http\Controllers\inward;

use Retailcore\Company_Profile\Models\company_profile\company_profile;
use Retailcore\Inward_Stock\Models\inward\View_inward;
use Retailcore\Inward_Stock\Models\inward\inward_stock;
use Retailcore\Inward_Stock\Models\inward\inward_product_detail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
class ViewinwardController extends Controller
{

    public function index()
    {
        $inward_stock = inward_stock::where('company_id',Auth::user()->company_id)
            ->where('deleted_at','=',NULL)
            ->orderBy('inward_stock_id', 'DESC')
            ->with('supplier_gstdetail.supplier_company_info')
            ->select('*',DB::raw("(SELECT SUM(inward_product_details.pending_return_qty) FROM inward_product_details WHERE inward_product_details.inward_stock_id = inward_stocks.inward_stock_id and inward_product_details.deleted_at IS NULL)  as totalpendingqty"))
            ->paginate(10);

        return view('inward_stock::inward/view_inward_stock', compact('inward_stock'))->render();
    }

    function inward_fetch_data(Request $request)
    {
        if($request->ajax())
        {
            $data = $request->all();
            $sort_by = isset($data['sortby'])?$data['sortby'] : 'inward_stock_id';
            $sort_type = isset($data['sorttype'])?$data['sorttype']:'desc';
            $query = isset($data['query']) ? $data['query'] : '';

            $query = str_replace(" ", "", $query);

            $inward_stock = inward_stock::where('company_id', Auth::user()->company_id)
                ->where('deleted_at', '=', NULL)
                ->select('*',DB::raw("(SELECT SUM(inward_product_details.pending_return_qty) FROM inward_product_details WHERE inward_product_details.inward_stock_id = inward_stocks.inward_stock_id and inward_product_details.deleted_at IS NULL)  as totalpendingqty"));

            if(isset($query) && $query != '') {
                if ($query['from_date'] != '' || $query['to_date'] != '') {
                    $inward_stock = inward_stock::WhereBetween('inward_date', [$query['from_date'], $query['to_date']])->where('deleted_at', '=', NULL);
                }

                if ($query['invoice_no'] != '') {
                    $inward_stock = inward_stock::where('invoice_no', '=', $query['invoice_no'])->where('deleted_at', '=', NULL);
                }
                if ($query['supplier_name'] != '')
                {
                    $inward_stock = inward_stock::where('supplier_gst_id', '=', $query['supplier_name'])->where('deleted_at', '=', NULL);
                }
            }
            $inward_stock = $inward_stock->orderBy($sort_by, $sort_type)->paginate(10);

            return view('inward_stock::inward/inward_stock_data', compact('inward_stock'))->render();
        }
    }

    public function view_inward_detail(Request $request)
    {
        $inward_stock_id = decrypt($request->inward_stock_id);

        $inward_stock = inward_stock::where([
            ['inward_stock_id','=',$inward_stock_id],
            ['company_id',Auth::user()->company_id]])
            ->with('inward_product_detail.product_detail')
            ->with('supplier_payment_details.payment_method')
            ->with('supplier_gstdetail.supplier_company_info')
            ->select('*')
            ->where('deleted_at','=',NULL)
            ->first();

        $data = json_encode($inward_stock);

        return json_encode(array("Success"=>"True","Data"=>$data));

    }

    public function invoice_number_search(Request $request)
    {
        $result = inward_stock::select('invoice_no')
            ->where('company_id',Auth::user()->company_id)
            ->where('invoice_no','LIKE',"%$request->search_val%")
            ->whereNull('deleted_at')
            ->get();
        return json_encode(array("Success"=>"True","Data"=>$result));
    }


}
