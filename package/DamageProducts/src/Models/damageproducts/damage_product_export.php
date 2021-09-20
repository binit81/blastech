<?php

namespace Retailcore\DamageProducts\Models\damageproducts;

use Retailcore\Products\Models\product\product;
use Retailcore\Products\Models\product\colour;
use Retailcore\Products\Models\product\price_master;
use Retailcore\Company_Profile\Models\company_profile\company_profile;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use DB;

class damage_product_export implements FromQuery, WithHeadings, WithMapping
{

    use Exportable;

    public $from_date = '';
    public $to_date = '';
    public $damageproductsearch = '';
    public $DamageType='';

    public function __construct($from_date,$to_date,$damageproductsearch,$DamageType) {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->damageproductsearch = $damageproductsearch;
        $this->DamageType=$DamageType;

        $inward_type_from_comp = company_profile::where('company_id',Auth::user()->company_id)->select('inward_type','tax_type','tax_title','currency_title')->first();


        $this->tax_type = $inward_type_from_comp['tax_type'];
        $this->tax_title = $inward_type_from_comp['tax_title'];
        $this->tax_currency = $inward_type_from_comp['currency_title'];
    }

    public function headings(): array
    {
        $damage_product_wise_header = [];
            $damage_product_wise_header[] = 'Date';
            $damage_product_wise_header[] = 'Barcode';
            $damage_product_wise_header[] = 'Product Name';
            $damage_product_wise_header[] = 'Batch No.';
            $damage_product_wise_header[] = 'Invoice No.';
            $damage_product_wise_header[] = 'Cost Rate';
            $damage_product_wise_header[] = 'Quantity';
            $damage_product_wise_header[] = 'Total Cost Rate';

            if($this->tax_type == 1) {
                $damage_product_wise_header[] = $this->tax_title . "%";
                $damage_product_wise_header[] = $this->tax_title . ' ' . $this->tax_currency;
               }else
            {
                $damage_product_wise_header[] = 'Cost CGST %';
                $damage_product_wise_header[] = 'Cost CGST Amount';
                $damage_product_wise_header[] = 'Cost SGST %';
                $damage_product_wise_header[] = 'Cost SGST Amount';
                $damage_product_wise_header[] = 'Cost IGST %';
                $damage_product_wise_header[] = 'Cost IGST Amount';
            }
            $damage_product_wise_header[] = 'Total Cost Price';
            $damage_product_wise_header[] = 'MRP';
            $damage_product_wise_header[] = 'Notes';
            $damage_product_wise_header[] = 'Status';

            return $damage_product_wise_header;

    }

    public function map($damageproducts): array
   {
        $rows    = [];

       $barcode = '';

       if($damageproducts['product']['supplier_barcode'] != '')
       {
           $barcode =  $damageproducts['product']['supplier_barcode'];
       }
       else
       {
           $barcode = $damageproducts['product']['product_system_barcode'];
       }

       $rows[]         =   $damageproducts->created_at->format('d-m-Y');
       $rows[]         =   $barcode;
       $rows[]         =   $damageproducts->product['product_name'];
       $rows[]         =   $damageproducts->batch_no;
       $rows[]         =   $damageproducts->inward_product_detail->inward_stock->invoice_no;
       $rows[]         =   $damageproducts->product_cost_rate;
       $rows[]         =   $damageproducts->product_damage_qty;
       $rows[]         =   $damageproducts->product_total_cost_rate;

       if($this->tax_type == 1)
       {
           $rows[] = $damageproducts->product_cost_igst_percent;
           $rows[] = $damageproducts->product_cost_igst_amount_with_qty;
       }
       else {
           $rows[] = $damageproducts->product_cost_cgst_percent;
           $rows[] = $damageproducts->product_cost_cgst_amount_with_qty;
           $rows[] = $damageproducts->product_cost_sgst_percent;
           $rows[] = $damageproducts->product_cost_sgst_amount_with_qty;
           $rows[] = $damageproducts->product_cost_igst_percent;
           $rows[] = $damageproducts->product_cost_igst_amount_with_qty;
       }

       $rows[]         =   $damageproducts->product_total_cost_price;
       $rows[]         =   $damageproducts->product_mrp;
       $rows[]         =   $damageproducts->product_notes;
       $rows[]         =   $damageproducts->damage_product->damage_types->damage_type;


        return $rows;
        
    }

    public function query()
    {
        $company_id     =   Auth::user()->company_id;
        $fromDate       =   $this->from_date;
        $toDate         =   $this->to_date;

        $damageproducts = damage_product_detail::select('*')
        ->whereRaw('company_id='.$company_id)
        ->where('deleted_at','=',NULL)
        ->with('product');


        if($this->from_date!='')
        {
            $damageproducts->whereRaw("Date(damage_product_details.created_at) between '$this->from_date' and '$this->to_date'");
        }

        if($this->DamageType!='')
        {
            $damageproducts->where('damage_type_id','=',$this->DamageType);
        }

        if($this->damageproductsearch!='')
        {
            /*$exp                    =   explode('_',$this->damageproductsearch);
            $searchBarcode          =   $exp[0];
            $searchProductName      =   $exp[1];

            if($exp[0]!='')
            {
               $damageproducts->whereRaw("product_system_barcode='".$exp[0]."'"); 
            }*/
            $damageproducts->whereRaw("product_id='" . $this->damageproductsearch . "'");
        }

        $damageproducts->get();


        return $damageproducts;
    }
}

