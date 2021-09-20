<?php

namespace Retailcore\Inward_Stock\Http\Controllers\inward;

use App\Http\Controllers\Controller;
use Retailcore\Company_Profile\Models\company_profile\company_profile;
use Retailcore\Inward_Stock\Models\inward\inward_gst_percent_wise_report;
use Illuminate\Http\Request;
use Retailcore\Inward_Stock\Models\inward\inward_product_detail;
use Retailcore\Inward_Stock\Models\inward\inward_getpercent_excel;
use Retailcore\Inward_Stock\Models\inward\inward_stock;
use Retailcore\Sales\Models\sales_bill;
use Retailcore\Sales\Models\sales_product_detail;
use Retailcore\SalesReturn\Models\return_bill;

use Auth;

class InwardGstPercentWiseReportController extends Controller
{

    public function index()
    {

        $inward_stock = inward_stock::where('company_id',Auth::user()->company_id)
            ->where('deleted_at','=',NULL)
            ->orderBy('inward_stock_id', 'DESC')
            ->paginate(10);

        $gst_slabs = inward_product_detail::select('cost_igst_percent','cost_cgst_percent','cost_sgst_percent')
            ->where('company_id',Auth::user()->company_id)
            ->where('deleted_at','=',NULL)
            ->orderBy('cost_sgst_percent', 'ASC')
            ->groupBy('cost_sgst_percent')
            ->get();


        $state_id  =  company_profile::select('state_id')->where('company_id',Auth::user()->company_id)->first();
        $company_state   = $state_id['state_id'];

        return view('inward_stock::inward/inwardgst_perwise_report',compact('gst_slabs','inward_stock','company_state'));
    }


    public function inward_gstperwise_search(Request $request)
    {
        $data = $request->all();

        $sort_by = $data['sortby'];
        $sort_type = $data['sorttype'];
        $query = (isset($data['query']) ? $data['query'] : '');

        $from_date   =  isset($query['from_date']) ? $query['from_date'] : '';
        $to_date   =  isset($query['to_date'])?$query['to_date'] : '';

        if($request->ajax()) {
            $inward_stock = inward_stock::where('company_id', Auth::user()->company_id)
                ->where('deleted_at', '=', NULL);

            if ($from_date != '')
            {
                $inward_stock->whereBetween('inward_date', [$from_date,$to_date]);

            }
            $inward_stock = $inward_stock->orderBy($sort_by,$sort_type)->paginate(10);

            $gst_slabs = inward_product_detail::select('cost_igst_percent', 'cost_cgst_percent', 'cost_sgst_percent')
                ->where('company_id', Auth::user()->company_id)
                ->where('deleted_at', '=', NULL)
                ->orderBy('cost_sgst_percent', 'ASC')
                ->groupBy('cost_sgst_percent')
                ->get();

            $state_id = company_profile::select('state_id')->where('company_id', Auth::user()->company_id)->first();
            $company_state = $state_id['state_id'];

            return view('inward_stock::inward/inwardgst_perwise_reportdata', compact('gst_slabs', 'inward_stock', 'company_state'))->render();
        }
    }

    public function inward_gst_wise_export_excel(Request $request)
    {
        //not done.work in process
        //return Excel::download(new inward_getpercent_excel($request->from_date,$request->to_date),'inward_gst_percent.xlsx');
    }
}
