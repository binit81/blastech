<?php

namespace Retailcore\Inward_Stock\Models\inward;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Auth;
use Retailcore\Inward_Stock\Models\inward\inward_stock;
use Retailcore\Products\Models\product\price_master;
use Retailcore\Inward_Stock\Models\inward\inward_product_detail;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class inward_getpercent_excel implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public $from_date = '';
    public $to_date = '';

    public function __construct($from_date,$to_date)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }

    public function headings(): array
    {
        return [
            'Barcode',
            'Invoice No.',
            'Po No.',
            'Batch No.',
            'Inward Date',
            'Invoice Date',
            'Supplier Name',
            'Product Name',
            'Product MRP ₹',
            'Offer Price ₹',
            'Wholesaler Price ₹',
            'Total Qty',
        ];
    }

    public function map($batch_no_excel): array
    {
        $count = '';

        $rows    = [];

        $barcode = '';

        if($batch_no_excel['product']['supplier_barcode'] != '')
        {
            $barcode =  $batch_no_excel['product']['supplier_barcode'];
        }
        else
        {
            $barcode = $batch_no_excel['product']['product_system_barcode'];
        }

        $rows[] = $barcode;
        $rows[] = $batch_no_excel->inward_stock->invoice_no;
        $rows[] = $batch_no_excel->inward_stock->po_no;
        $rows[] = $batch_no_excel->batch_no;
        $rows[] = $batch_no_excel->inward_stock->inward_date;
        $rows[] = $batch_no_excel->inward_stock->invoice_date;
        $rows[] = $batch_no_excel->inward_stock->supplier_gstdetail->supplier_company_info->supplier_first_name;
        $rows[] = $batch_no_excel->product->product_name;
        $rows[] = $batch_no_excel->product_mrp;
        $rows[] = $batch_no_excel->offer_price;
        $rows[] = $batch_no_excel->wholesaler_price;
        $rows[] = $batch_no_excel->product_qty;

        return $rows;
    }



    public function query()
    {

        $from_date   =   $this->from_date;
        $to_date   =   $this->to_date;
        $batch_no =   $this->batch_no;

        $batch_no_excel = price_master::query()->where('company_id',Auth::user()->company_id)
            ->where('deleted_at','=',NULL)
            ->where('batch_no','!=',NULL)
            ->with('inward_stock')
            ->with('product');

        if($from_date !='' && $to_date !='')
        {
            $batch_no_excel->whereHas('inward_stock', function ($q) use ($from_date,$to_date) {
                $q->whereBetween('inward_date', [$from_date,$to_date]);
            });
        }
        if($batch_no !='')
        {
            $batch_no_excel->where('batch_no','!=',NULL)
                ->where('batch_no', '=', $batch_no);

        }

        return $batch_no_excel;


    }
}
