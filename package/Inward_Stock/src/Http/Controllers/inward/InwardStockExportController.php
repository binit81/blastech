<?php

namespace Retailcore\Inward_Stock\Http\Controllers\inward;
use App\Http\Controllers\Controller;

use Retailcore\Inward_Stock\Models\inward\inward_batch_report_excel;
use Retailcore\Inward_Stock\Models\inward\inward_stock;
use Retailcore\Inward_Stock\Models\inward\inward_product_detail;
use Retailcore\Inward_Stock\Models\inward\inward_stock_export;
use Retailcore\Inward_Stock\Models\inward\inward_product_wise_excel;
use Retailcore\Inward_Stock\Models\inward\inward_supplier_wise_excel;
use Retailcore\Inward_Stock\Models\inward\inward_pricemaster_report_excel;
use Retailcore\Inward_Stock\Models\inward\inward_template;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InwardStockExportController extends Controller
{
    public function export_inward(Request $request)
    {
        return Excel::download(new inward_stock_export($request->from_date,$request->to_date,$request->invoice_no,$request->supplier_name), 'Inward-Export.xlsx');
    }

    public function product_wise_report_excel(Request $request)
    {
        return Excel::download(new inward_product_wise_excel($request->from_date,$request->to_date,$request->barcode,$request->product_name,$request->batch_no,$request->invoice_no),'Inward-Product.xlsx');
    }

    public function supplier_wise_report_export(Request $request)
    {
        return Excel::download(new inward_supplier_wise_excel($request->from_date,$request->to_date,$request->supplier_name),'Inward-Supplier-Report.xlsx');
    }

    /*public function inward_batch_report_export(Request $request)
    {
        return Excel::download(new inward_batch_report_excel($request->from_date,$request->to_date,$request->batch_no),'Inward-Batch-Report.xlsx');
    }*/

    public function inward_pricemaster_report_export(Request $request)
    {
        return Excel::download(new inward_pricemaster_report_excel($request->barcode,$request->product_name),'Inward-PriceMaster-Report.xlsx');
    }

    public function inward_template(Request $request)
    {
        $file_name = '';
        if(isset($request->inward_type) && $request->inward_type == 1)
        {
            $file_name = "fmcg_inward_stock_template.xlsx";
        }

        if(isset($request->inward_type) && $request->inward_type == 2)
        {
            $file_name = "garment_inward_stock_template.xlsx";
        }

        if($file_name != '')
        {
            return Excel::download(new inward_template($request->inward_type), $file_name);
        }
        else
        {
            exit;
        }
    }

}
