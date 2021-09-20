<?php

namespace Retailcore\Inward_Stock\Models\Inward;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Auth;
use Retailcore\Company_Profile\Models\company_profile\company_profile;
use Retailcore\Inward_Stock\Models\inward\inward_stock;
use Retailcore\Inward_Stock\Models\inward\inward_product_detail;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
class inward_supplier_wise_excel implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public $from_date = '';
    public $to_date = '';
    public $barcode = '';
    public $product_name = '';

    public function __construct($from_date,$to_date,$supplier_name)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->supplier_name = $supplier_name;

        $inward_type_from_comp = company_profile::where('company_id',Auth::user()->company_id)->select('inward_type','tax_type','tax_title','currency_title')->first();

        $this->tax_type = $inward_type_from_comp['tax_type'];
        $this->tax_title = $inward_type_from_comp['tax_title'];
        $this->currency_title = $inward_type_from_comp['currency_title'];
    }

    public function headings(): array
    {
        $tax_name = 'GSTIN';
        if($this->tax_type == 1)
        {
            $tax_name = $this->tax_title;
        }
       $supplier_wise_header = [];
       $supplier_wise_header[] = 'Invoice No.';
       $supplier_wise_header[] = 'Po No.';
       $supplier_wise_header[] = 'Inward Date';
       $supplier_wise_header[] = 'Invoice Date';
       $supplier_wise_header[] = 'Supplier Name';
       $supplier_wise_header[] = 'Supplier '.$tax_name;
       $supplier_wise_header[] = 'Total Cost Rate';

       if($this->tax_type == 1)
       {
           $supplier_wise_header[] = 'Total Cost '.$this->tax_title.' '.$this->currency_title;
       }else {
           $supplier_wise_header[] = 'Total Cost CGST ₹';
           $supplier_wise_header[] = 'Total Cost SGST ₹';
           $supplier_wise_header[] = 'Total Cost IGST ₹';
       }

       $supplier_wise_header[] = 'Total Qty';
       $supplier_wise_header[] = 'Total Cost ₹';

       return $supplier_wise_header;

    }

    public function map($supplier_wise_report): array
    {
        $count = '';

        $rows    = [];

        if($supplier_wise_report['inward_product_detail'] != ''){
            $cost_rate = 0;
            foreach ($supplier_wise_report['inward_product_detail'] AS $key=>$value)
            {
                // print_r($value['cost_rate']);
                $cost_rate += $value['cost_rate'] * ($value['product_qty']+ $value['free_qty']);
            }
        }

        $rows[] = $supplier_wise_report->invoice_no;
        $rows[] = $supplier_wise_report->po_no;
        $rows[] = $supplier_wise_report->inward_date;
        $rows[] = $supplier_wise_report->invoice_date;
        $rows[] = $supplier_wise_report->supplier_gstdetail->supplier_company_info->supplier_first_name;
        $rows[] = $supplier_wise_report->supplier_gstdetail->supplier_gstin;
        $rows[] = $cost_rate;

        if($this->tax_type == 1)
        {
            $rows[] = $supplier_wise_report->total_cost_igst_amount;
        }
        else {
            $rows[] = $supplier_wise_report->total_cost_cgst_amount;
            $rows[] = $supplier_wise_report->total_cost_sgst_amount;
            $rows[] = $supplier_wise_report->total_cost_igst_amount;
        }
        $rows[] = $supplier_wise_report->total_qty;
        $rows[] = $supplier_wise_report->total_grand_amount;

        return $rows;
    }

    public function query()
    {
        $from_date   =   $this->from_date;
        $to_date   =   $this->to_date;
        $supplier_name =   $this->supplier_name;

        $supplier_wise_report = inward_stock::query()->where('company_id',Auth::user()->company_id)
            ->where('deleted_at','=',NULL)
            ->with('supplier_gstdetail')
            ->with('inward_product_detail');

        if($from_date !='' && $to_date !='')
        {
           $supplier_wise_report->whereBetween('inward_date',[$from_date,$to_date]);
        }

        if($supplier_name !='')
        {
            $supplier_wise_report->whereHas('supplier_gstdetail', function ($q) use ($supplier_name)
            {
               $q->where('supplier_gst_id', '=',$supplier_name);
            });

        }

        return $supplier_wise_report;


    }
}
