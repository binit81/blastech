<?php

namespace Retailcore\Inward_Stock\Models\inward;

use Illuminate\Database\Eloquent\Model;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Auth;
use Retailcore\Company_Profile\Models\company_profile\company_profile;
use Retailcore\Inward_Stock\Models\inward\inward_stock;
use Retailcore\Products\Models\product\price_master;

use Retailcore\Inward_Stock\Models\inward\inward_product_detail;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class inward_pricemaster_report_excel implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public $from_date = '';
    public $to_date = '';
    public $batch_no = '';
    public $product_name = '';

    public function __construct($barcode, $product_name)
    {
        $this->barcode = $barcode;
        $this->product_name = $product_name;


        $inward_type_from_comp = company_profile::where('company_id',Auth::user()->company_id)->select('inward_type','tax_type','tax_title','currency_title')->first();

        $this->inward_type = 1;
        if(isset($inward_type_from_comp) && !empty($inward_type_from_comp) && $inward_type_from_comp['inward_type'] != '')
        {
            $this->inward_type = $inward_type_from_comp['inward_type'];
        }
        $this->tax_type = $inward_type_from_comp['tax_type'];
        $this->tax_title = $inward_type_from_comp['tax_title'];
        $this->currency_title = $inward_type_from_comp['currency_title'];
    }

    public function headings(): array
    {

        $inward_type = $this->inward_type;
        $tax_currency = '&#8377;';
        $tax_title = 'GST';

        if($this->tax_type == 1)
        {
            $tax_title = $this->tax_title;
            $tax_currency =$this->currency_title;
        }
        $price_master_header = [];

        if($inward_type == 1) {

            $price_master_header[] = 'Batch No';
            $price_master_header[] = 'Barcode';
            $price_master_header[] = 'Product Name';
            $price_master_header[] = 'Qty';
            $price_master_header[] = 'Selling Price '.$tax_currency;
            $price_master_header[] = 'Selling ' .$tax_title.' %';
            $price_master_header[] = 'Selling '. $tax_title.' '.$tax_currency;
            $price_master_header[] = 'Offer Price '.$tax_currency;
            $price_master_header[] = 'Product MRP '.$tax_currency;
            $price_master_header[] = 'Wholesaler Price '.$tax_currency;

        }else
        {
            $price_master_header[] = 'Barcode';
            $price_master_header[] = 'Product Name';
            $price_master_header[] = 'Qty';
            $price_master_header[] = 'Selling Price '.$tax_currency;
            $price_master_header[] = 'Selling ' .$tax_title.' %';
            $price_master_header[] = 'Selling ' .$tax_title.' '.$tax_currency;
            $price_master_header[] = 'Offer Price '.$tax_currency;
            $price_master_header[] = 'Product MRP '.$tax_currency;
            $price_master_header[] = 'Wholesaler Price '.$tax_currency;
        }
        return $price_master_header;
    }

    public function map($price_master_excel): array
    {
        $count = '';

        $rows = [];

        $barcode = '';

        $inward_type = $this->inward_type;

        if ($price_master_excel['product']['supplier_barcode'] != '') {
            $barcode = $price_master_excel['product']['supplier_barcode'];
        } else {
            $barcode = $price_master_excel['product']['product_system_barcode'];
        }
        if($inward_type == 1){
            $rows[] = $price_master_excel->batch_no;
        }
        $rows[] = $barcode;
        $rows[] = $price_master_excel->product->product_name;
        $rows[] = $price_master_excel->product_qty;
        $rows[] = $price_master_excel->sell_price;
        $rows[] = $price_master_excel->selling_gst_percent;
        $rows[] = $price_master_excel->selling_gst_amount;
        $rows[] = $price_master_excel->offer_price;
        $rows[] = $price_master_excel->product_mrp;
        $rows[] = $price_master_excel->wholesaler_price;

        return $rows;
    }


    public function query()
    {

        $barcode = $this->barcode;
        $product_name = $this->product_name;


        $price_master_excel = price_master::query()->where('company_id', Auth::user()->company_id)
            ->where('deleted_at', '=', NULL)
            ->with('inward_stock')
            ->with('product');

        if ($barcode != '') {

            $price_master_excel->whereHas('product',function ($q) use($barcode){
                $q->where('product_system_barcode', 'like', '%'.$barcode.'%');
                $q->orWhere('supplier_barcode', 'like', '%'.$barcode.'%');
            });
        }
        if ($product_name != '') {
            $price_master_excel->whereHas('product',function ($q) use($product_name){
                $q->where('product_name', 'like', '%'.$product_name.'%');
            });

        }
        $price_master_excel = $price_master_excel->orderBy('price_master_id', 'desc');
        return $price_master_excel;


    }
}

