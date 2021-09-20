<?php

namespace Retailcore\Inward_Stock\Http\Controllers\inward;

use Retailcore\Company_Profile\Models\company_profile\company_profile;
use Retailcore\Inward_Stock\Models\inward\inward_stock;
use Retailcore\Inward_Stock\Models\inward\inward_supplier_wise_report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
class InwardSupplierWiseReportController extends Controller
{

    public function index()
    {
        $sort_by = 'inward_stock_id';
        $sort_type = 'desc';
        $supplier_wise_report = inward_stock::where('company_id',Auth::user()->company_id)
            ->where('deleted_at','=',NULL)
            ->with('supplier_gstdetail')
            ->with('inward_product_detail')
            ->orderBy($sort_by, $sort_type)->paginate(10);

        return view('inward_stock::inward/supplier_wise_report',compact('supplier_wise_report'));
    }


    public function supplier_wise_record(Request $request)
    {
        $data = $request->all();


        $sort_by = $data['sortby'];
        $sort_type = $data['sorttype'];
        $query = (isset($data['query']) ? $data['query'] : '');

        $query = str_replace(" ", "", $query);
        if($request->ajax())
        {
            if(!empty($query) && $query != '') {
                if ($query['from_date'] != '' && $query['to_date'] != '' && $query['supplier_name'] != '') {
                    $supplier_wise_report = inward_stock::where('company_id', Auth::user()->company_id)
                        ->where('deleted_at', '=', NULL)
                        ->whereBetween('inward_date', [$query['from_date'], $query['to_date']])
                        ->with('inward_product_detail')
                        ->with('supplier_gstdetail')
                        ->whereHas('supplier_gstdetail', function ($q) use ($query)
                        {
                            $q->where('supplier_gst_id','=',$query['supplier_name']);
                        })
                        ->orderBy($sort_by, $sort_type)->paginate(10);
                } elseif ($query['from_date'] == '' && $query['to_date'] == '' && $query['supplier_name'] != '') {
                    $supplier_wise_report = inward_stock::where('company_id', Auth::user()->company_id)
                        ->where('deleted_at', '=', NULL)
                        ->with('inward_product_detail')
                        ->with('supplier_gstdetail')
                        ->whereHas('supplier_gstdetail', function ($q) use ($query)
                        {
                            $q->where('supplier_gst_id', '=', $query['supplier_name']);
                        })
                        ->orderBy($sort_by, $sort_type)->paginate(10);
                } elseif ($query['from_date'] != '' && $query['to_date'] != '' && $query['supplier_name'] == '') {
                    $supplier_wise_report = inward_stock::where('company_id', Auth::user()->company_id)
                        ->where('deleted_at', '=', NULL)
                        ->with('inward_product_detail')
                        ->whereBetween('inward_date', [$query['from_date'], $query['to_date']])
                        ->orderBy($sort_by, $sort_type)->paginate(10);
                } else {
                    $supplier_wise_report = inward_stock::where('company_id', Auth::user()->company_id)
                        ->with('inward_product_detail')->orderBy($sort_by, $sort_type)->paginate(10);
                }
            }else
            {
                $supplier_wise_report = inward_stock::where('company_id', Auth::user()->company_id)
                    ->with('inward_product_detail')->orderBy($sort_by, $sort_type)->paginate(10);
            }
            return view('inward_stock::inward/supplier_wise_report_data', compact('supplier_wise_report'))->render();
        }
    }
}
