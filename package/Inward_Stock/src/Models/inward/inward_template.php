<?php

namespace Retailcore\Inward_Stock\Models\inward;

use Illuminate\Database\Eloquent\Model;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Retailcore\Company_Profile\Models\company_profile\company_profile;

class inward_template implements WithHeadings
{
    use Exportable;

    public $inward_type = '';

    public function __construct($inward_type)
    {
        $this->inward_type = $inward_type;

        $inward_type_from_comp = company_profile::where('company_id',Auth::user()->company_id)->select('inward_type','tax_type','tax_title','currency_title')->first();

        $this->tax_type = $inward_type_from_comp['tax_type'];
        $this->tax_title = $inward_type_from_comp['tax_title'];
        $this->tax_currency = $inward_type_from_comp['currency_title'];
    }

    public function headings(): array
    {
        $inward_type = $this->inward_type;

        if($inward_type == 1)
        {
            $inward_template_header = [];
            $inward_template_header[] = 'Barcode';
            $inward_template_header[] = 'Product name';
            $inward_template_header[] = 'Base price/cost rate';
            $inward_template_header[] = 'Discount percent';
            $inward_template_header[] = 'Scheme percent';

            if($this->tax_type == 1)
            {
                $inward_template_header[] = 'Cost '.$this->tax_title. ' %';
            }
            else {
                $inward_template_header[] = 'Cost gst %';
            }
            $inward_template_header[] = 'Extra charge';
            $inward_template_header[] = 'Profit %';
            $inward_template_header[] = 'Selling price';
            if($this->tax_type == 1)
            {
                $inward_template_header[] = 'Sell '.$this->tax_title.' %';
            }
            else {
                $inward_template_header[] = 'Sell gst %';
            }
            $inward_template_header[] = 'Offer price';
            $inward_template_header[] = 'Product mrp';
            $inward_template_header[] = 'Batch no';
            $inward_template_header[] = 'Add qty';
            $inward_template_header[] = 'Free qty';
            $inward_template_header[] = 'Mfg date(DD)';
            $inward_template_header[] = 'Mfg month(MM)';
            $inward_template_header[] = 'Mfg year(YYYY)';
            $inward_template_header[] = 'Expiry date(DD)';
            $inward_template_header[] = 'Expiry month(MM)';
            $inward_template_header[] = 'Expiry year(YYYY)';
            $inward_template_header[] = 'Days before product expiry';
            $inward_template_header[] = 'Product description';
            $inward_template_header[] = 'Product code';
            $inward_template_header[] = 'SKU';
            $inward_template_header[] = 'HSN';
            $inward_template_header[] = 'Brand';
            $inward_template_header[] = 'Category';
            $inward_template_header[] = 'Sub category';
            $inward_template_header[] = 'Colour';
            $inward_template_header[] = 'Size';
            $inward_template_header[] = 'UQC';
            $inward_template_header[] = 'Material';
            $inward_template_header[] = 'Alert product qty';

            return $inward_template_header;
        }
        else
        {
            $inward_template_header[] = 'Barcode';
            $inward_template_header[] = 'Product name';
            $inward_template_header[] = 'Base price/cost rate';
            if($this->tax_type == 1)
            {
                $inward_template_header[] = 'Cost '.$this->tax_title.' %';
            }
            else {
                $inward_template_header[] = 'Cost gst %';
            }
            $inward_template_header[] = 'Extra charge';
            $inward_template_header[] = 'Profit %';
            $inward_template_header[] = 'Selling price';
            if($this->tax_type == 1)
            {
                $inward_template_header[] = 'Sell '.$this->tax_title.' %';
            }
            else {
                $inward_template_header[] = 'Sell gst %';
            }
            $inward_template_header[] = 'Offer price';
            $inward_template_header[] = 'Product mrp';
            $inward_template_header[] = 'Add qty';
            $inward_template_header[] = 'Product description';
            $inward_template_header[] = 'Product code';
            $inward_template_header[] = 'SKU';
            $inward_template_header[] = 'HSN';
            $inward_template_header[] = 'Brand';
            $inward_template_header[] = 'Category';
            $inward_template_header[] = 'Sub category';
            $inward_template_header[] = 'Colour';
            $inward_template_header[] = 'Size';
            $inward_template_header[] = 'UQC';
            $inward_template_header[] = 'Material';
            $inward_template_header[] = 'Days before product expiry';
            $inward_template_header[] = 'Alert product qty';

            return $inward_template_header;

        }
    }





}

