<?php

namespace Retailcore\Inward_Stock\Models\inward;
use App\Providers\AppServiceProvider;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Auth;
use Retailcore\Company_Profile\Models\company_profile\company_profile;
use Retailcore\Inward_Stock\Models\inward\inward_stock;
use Retailcore\Inward_Stock\Models\inward\inward_product_detail;
use Config;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
class inward_product_wise_excel  implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;


    public $from_date = '';
    public $to_date = '';
    public $barcode = '';
    public $product_name = '';
    public $batch_no = '';
    public $invoice_no = '';

    public function __construct($from_date,$to_date,$barcode,$product_name,$batch_no,$invoice_no)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->barcode = $barcode;
        $this->product_name = $product_name;
        $this->batch_no = $batch_no;
        $this->invoice_no = $invoice_no;



        $inward_type_from_comp = company_profile::where('company_id',Auth::user()->company_id)->select('inward_type','tax_type','tax_title','currency_title')->first();

        $this->inward_type = 1;
        $this->currency_title = '₹';
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
        $tax_type = $this->tax_type;
        $tax_title = $this->tax_title;
        $currency_title = $this->currency_title;

        $return_header_array = [];

        if($inward_type == 1)
        {
            $return_header_array[] = 'Barcode';
            $return_header_array[] = 'Invoice No.';
            $return_header_array[] = 'Batch No.';
            $return_header_array[] = 'Inward Date';
            $return_header_array[] = 'Product Name';

            if($tax_type == 1)
            {
                $return_header_array[] = 'Cost Rate '.$currency_title.'(Without '.$tax_title.')';
                $return_header_array[] = "Cost ".$tax_title.' '.$currency_title;
                $return_header_array[] = 'Cost Price '.$currency_title.'(With '.$tax_title.')';
            }
            if($tax_type == 2)
            {
                $return_header_array[] = 'Cost Rate ₹(Without GST)';
                $return_header_array[] = 'Cost IGST ₹';
                $return_header_array[] = 'Cost CGST ₹';
                $return_header_array[] = 'Cost SGST ₹';
                $return_header_array[] = 'Cost Price ₹(With GST)';
            }

            $return_header_array[] = 'Qty';
            $return_header_array[] = 'Free Qty';
            $return_header_array[] = 'Pending Return Qty';
            $return_header_array[] = 'Total Cost Rate';
            if($tax_type == 1)
            {
                $return_header_array[] = "Total ".$tax_title." %";
                $return_header_array[] = "Total ".$tax_title.' '.$currency_title;
            }
            if($tax_type == 2)
            {
                $return_header_array[] = 'Total CGST %';
                $return_header_array[] = 'Total CGST ₹';
                $return_header_array[] = 'Total SGST %';
                $return_header_array[] = 'Total SGST ₹';
                $return_header_array[] = 'Total IGST %';
                $return_header_array[] = 'Total IGST ₹';
            }
            $return_header_array[] = 'Total Cost Price'.$currency_title;
            $return_header_array[] = 'Profit'.$currency_title;
            $return_header_array[] = 'Sell Price'.$currency_title;
            $return_header_array[] = 'Selling GST'.$currency_title;
            $return_header_array[] = 'Offer Price'.$currency_title;
            $return_header_array[] = 'Product MRP'.$currency_title;

            return $return_header_array;


        }
        if($inward_type == 2)
        {

                $return_header_array[] = 'Barcode';
                $return_header_array[] = 'Invoice No.';
                $return_header_array[] = 'Inward Date';
                $return_header_array[] = 'Product Name';


            if($tax_type == 1)
            {
                $return_header_array[] = 'Cost Rate '.$currency_title.'(Without '.$tax_title.')';
                $return_header_array[] = "Cost ".$tax_title.' '.$currency_title;
                $return_header_array[] = 'Cost Price '.$currency_title.'(With '.$tax_title.')';
            }
            if($tax_type == 2)
            {
                $return_header_array[] = 'Cost Rate ₹(Without GST)';
                $return_header_array[] = 'Cost IGST ₹';
                $return_header_array[] = 'Cost CGST ₹';
                $return_header_array[] = 'Cost SGST ₹';
                $return_header_array[] = 'Cost Price ₹(With GST)';
            }


                $return_header_array[] = 'Qty';
                $return_header_array[] = 'Pending Return Qty';
                $return_header_array[] = 'Total Cost Rate';


            if($tax_type == 1)
            {
                $return_header_array[] = "Total ".$tax_title." %";
                $return_header_array[] = "Total ".$tax_title.' '.$currency_title;
            }
             if($tax_type == 2)
             {
                $return_header_array[] = 'Total CGST %';
                $return_header_array[] = 'Total CGST ₹';
                $return_header_array[] = 'Total SGST %';
                $return_header_array[] = 'Total SGST ₹';
                $return_header_array[] = 'Total IGST %';
                $return_header_array[] = 'Total IGST ₹';
             }

             $return_header_array[] = 'Total Cost Price '.$currency_title;
            $return_header_array[] = 'Profit'.$currency_title;
            $return_header_array[] = 'Sell Price'.$currency_title;
            $return_header_array[] = 'Selling GST'.$currency_title;
            $return_header_array[] = 'Offer Price'.$currency_title;
            $return_header_array[] = 'Product MRP'.$currency_title;

                return $return_header_array;
        }



    }

    public function map($product_wise_report): array
    {
        $count = '';

        $rows    = [];

        $barcode = '';

        $inward_type = $this->inward_type;

        $tax_type = $this->tax_type;
        $tax_title = $this->tax_title;
        $currency_title = $this->currency_title;


        if($product_wise_report['product_detail']['supplier_barcode'] != '')
        {
            $barcode =  $product_wise_report['product_detail']['supplier_barcode'];
        }
        else
        {
            $barcode = $product_wise_report['product_detail']['product_system_barcode'];
        }

        $rows[] = $barcode;
        $rows[] = $product_wise_report->inward_stock->invoice_no;
        if($inward_type == 1){
            $rows[] = $product_wise_report->batch_no;
        }
        $rows[] = $product_wise_report->inward_stock->inward_date;
        $rows[] = $product_wise_report->product_detail->product_name;
        $rows[] = $product_wise_report->cost_rate;


        if($tax_type == 1)
        {
            $rows[] = $product_wise_report->cost_igst_amount;
        }
        if($tax_type == 2)
        {
            $rows[] = $product_wise_report->cost_igst_amount;
            $rows[] = $product_wise_report->cost_cgst_amount;
            $rows[] = $product_wise_report->cost_sgst_amount;
        }


        $rows[] = $product_wise_report->cost_price;
        $rows[] = $product_wise_report->product_qty;
        if($inward_type == 1)
        {
            $rows[] = $product_wise_report->free_qty;
        }
        $rows[] = $product_wise_report->pending_return_qty;
        $rows[] = $product_wise_report->total_cost_rate_with_qty;


        if($tax_type == 1)
        {
            $rows[] = $product_wise_report->cost_igst_percent;
            $rows[] = $product_wise_report->total_igst_amount_with_qty;
        }
        if($tax_type == 2)
        {
            $rows[] = $product_wise_report->cost_cgst_percent;
            $rows[] = $product_wise_report->total_cgst_amount_with_qty;
            $rows[] = $product_wise_report->cost_sgst_percent;
            $rows[] = $product_wise_report->total_sgst_amount_with_qty;
            $rows[] = $product_wise_report->total_igst_amount_with_qty;
            $rows[] = $product_wise_report->cost_igst_percent;
        }

        $rows[] = $product_wise_report->total_cost;
        $rows[] = $product_wise_report->profit_amount;
        $rows[] = $product_wise_report->sell_price;
        $rows[] = $product_wise_report->selling_gst_amount;
        $rows[] = $product_wise_report->offer_price;
        $rows[] = $product_wise_report->product_mrp;
        return $rows;
    }



    public function query()
    {


        $from_date   =   $this->from_date;
        $to_date   =   $this->to_date;
        $barcode =   $this->barcode;
        $product_name =   $this->product_name;
        $batch_no =   $this->batch_no;
        $invoice_no =   $this->invoice_no;

        $sort_by = 'inward_product_detail_id';
        $sort_type = 'desc';
        $product_wise_report = inward_product_detail::query()
            ->where('company_id',Auth::user()->company_id)
            ->where('deleted_at','=',NULL)
            ->with('inward_stock')
            ->with('product_detail')
            ->orderBy($sort_by,$sort_type);

        if($from_date !='' && $to_date !='')
        {
            $product_wise_report->whereHas('inward_stock',function($q) use($from_date,$to_date)
            {
                $q->whereBetween('inward_date',[$from_date,$to_date]);
            });
        }

        if($barcode !='')
        {
            $product_wise_report->whereHas('product_detail',function ($q) use ($barcode)
                {
                    $q->where('product_system_barcode','=',$barcode);
                    $q->orWhere('supplier_barcode','=',$barcode);
                });
        }
        if($product_name !='')
        {
            $product_wise_report->whereHas('product_detail',function ($q) use ($product_name)
                {
                    $q->where('product_name','LIKE','%'.$product_name.'%');
                });
        }

        if ($batch_no != '')
        {
            $product_wise_report->where('batch_no','=', $batch_no)->where('batch_no','!=',NULL);
        }

        if ($invoice_no != '')
        {
            $product_wise_report->whereHas('inward_stock',function ($q) use ($invoice_no)
            {
                $q->where('invoice_no',$invoice_no);
            });
        }

        return $product_wise_report;
    }
}
