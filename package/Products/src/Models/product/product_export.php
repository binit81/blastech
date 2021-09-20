<?php

namespace Retailcore\Products\Models\product;

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
class product_export implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public $product_name = '';
    public $barcode = '';
    public $brand_id = '';
    public $category_id = '';
    public $subcategory_id = '';
    public $colour_id = '';
    public $size_id = '';
    public $uqc_id = '';

    public function __construct($product_name,$barcode,$brand_id,$category_id,$subcategory_id,$colour_id,$size_id,$uqc_id)
    {
        $this->product_name = $product_name;
        $this->barcode = $barcode;
        $this->brand_id = $brand_id;
        $this->category_id = $category_id;
        $this->subcategory_id = $subcategory_id;
        $this->colour_id = $colour_id;
        $this->size_id = $size_id;
        $this->uqc_id = $uqc_id;

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
        $product_header = [];
        $tax_label = 'GST';
        $tax_currency= '(&#8377)';

        if($this->tax_type == 1)
        {
            $tax_label = $this->tax_title;
            $tax_currency = '('.$this->currency_title.')';
        }
        if($inward_type == 1)
        {
                $product_header[] = 'System Barcode';
                $product_header[] = 'Product Name.';
                $product_header[] = 'Supplier Barcode.';
                $product_header[] = 'HSN';
                $product_header[] = 'Category';
                $product_header[] = 'Sub Category';
                $product_header[] = 'Brand';
                $product_header[] = 'Colour';
                $product_header[] = 'Size';
                $product_header[] = 'UQC';
                $product_header[] = 'Cost Rate';
                $product_header[] = 'Cost ' .$tax_label. '(%)';
                $product_header[] = 'Cost '.$tax_label.''.$tax_currency;
                $product_header[] = 'Cost Price';
                $product_header[] = 'Profit(%)';
                $product_header[] = 'Profit' .$tax_currency;
                $product_header[] = 'Selling Price';
                $product_header[] = 'Selling '.$tax_label. '(%)';
                $product_header[] = 'Selling '.$tax_label.''.$tax_currency;
                $product_header[] = 'Product MRP';
                $product_header[] = 'Offer Price';
                $product_header[] = 'Wholesale Price';
                $product_header[] = 'SKU';
                $product_header[] = 'Product Code';
                $product_header[] = 'HSN';
                $product_header[] = 'Alert Before Product Expiry(Days)';
                $product_header[] = 'Low Stock Alert';
                $product_header[] = 'Note';

        }else
        {
            $product_header[] = 'System Barcode';
                $product_header[] = 'Product Name.';
                $product_header[] = 'Supplier Barcode.';
                $product_header[] = 'HSN';
                $product_header[] = 'Category';
                $product_header[] = 'Sub Category';
                $product_header[] = 'Brand';
                $product_header[] = 'Colour';
                $product_header[] = 'Size';
                $product_header[] = 'UQC';
                $product_header[] = 'Cost Rate';
                $product_header[] = 'Cost'.$tax_label;
                $product_header[] = 'Cost'.$tax_label.''.$tax_currency;
                $product_header[] = 'Cost Price';
                $product_header[] = 'Profit(%)';
                $product_header[] = 'Profit(₹)';
                $product_header[] = 'Selling Price';
                $product_header[] = 'Selling'.$tax_label;
                $product_header[] = 'Selling'.$tax_label.''.$tax_currency;
                $product_header[] = 'Product MRP';
                $product_header[] = 'Offer Price';
                $product_header[] = 'Wholesale Price';
                $product_header[] = 'SKU';
                $product_header[] = 'Product Code';
                $product_header[] = 'HSN';
                $product_header[] = 'Low Stock Alert';
                $product_header[] = 'Note';

        }
        return $product_header;
    }

    public function map($product_excel): array
    {
        $count = '';
        $inward_type = $this->inward_type;
        $rows    = [];

        if($product_excel['category_id'] != NULL) {
            $categoryname = $product_excel->category->category_name;
        }
        else{
            $categoryname = '';
        }

        if ($product_excel['subcategory_id'] != NULL)
        {
            $subcategoryname = $product_excel->subcategory->subcategory_name;
        }
        else
        {
            $subcategoryname = '';
        }


if ($product_excel['brand_id'] != NULL) {
    $brandname = $product_excel->brand->brand_type;
} else {
    $brandname = '';
}

if ($product_excel['colour_id'] != NULL) {
    $colourname = $product_excel->colour->colour_name;
} else {
    $colourname = '';
}

if ($product_excel['size_id'] != NULL) {
    $sizename = $product_excel->size->size_name;
} else {
    $sizename = '';
}

        if ($product_excel['uqc_id'] != NULL) {
            $uqc_shortname = $product_excel->uqc->uqc_shortname;
        } else {
            $uqc_shortname = '';
        }



        $rows[] = $product_excel->product_system_barcode;
        $rows[] = $product_excel->product_name;

        $rows[] = $product_excel->supplier_barcode;
        $rows[] = $product_excel->hsn_sac_code;
        $rows[] = $categoryname;
        $rows[] = $subcategoryname;
        $rows[] = $brandname;
        $rows[] = $colourname;
        $rows[] = $sizename;
        $rows[] = $uqc_shortname;
        $rows[] = $product_excel->cost_rate;
        $rows[] = $product_excel->cost_gst_percent;
        $rows[] = $product_excel->cost_gst_amount;
        $rows[] = $product_excel->cost_price;
        $rows[] = $product_excel->profit_percent;
        $rows[] = $product_excel->profit_amount;
        $rows[] = $product_excel->selling_price;
        $rows[] = $product_excel->sell_gst_percent;
        $rows[] = $product_excel->sell_gst_amount;
        $rows[] = $product_excel->product_mrp;
        $rows[] = $product_excel->offer_price;
        $rows[] = $product_excel->wholesale_price;
        $rows[] = $product_excel->sku_code;
        $rows[] = $product_excel->product_code;
        $rows[] = $product_excel->hsn_sac_code;
        if($inward_type == 1) {
            $rows[] = $product_excel->days_before_product_expiry;
        }
        $rows[] = $product_excel->alert_product_qty;
        $rows[] = $product_excel->note;


        return $rows;
    }

    public function query()
    {

        $product_name   =   $this->product_name;
        $barcode   =   $this->barcode;
        $brand_id =   $this->brand_id;
        $category_id =   $this->category_id;
        $subcategory_id =   $this->subcategory_id;
        $colour_id =   $this->colour_id;
        $size_id =   $this->size_id;
        $uqc_id =   $this->uqc_id;


        $product_excel = product::query()
            ->where('company_id',Auth::user()->company_id)
            ->where('deleted_at','=',NULL)
            ->where('item_type','=','1')
            ->orderBy('product_id', 'DESC');

        if(isset($product_name) && $product_name !='' )
        {
            $product_excel->where('product_name', 'like', '%'.$product_name.'%');
        }

        if(isset($barcode) && $barcode != '')
        {
            $product_excel->where('product_system_barcode', 'like', '%'.$barcode.'%');
            $product_excel->orWhere('supplier_barcode', 'like', '%'.$barcode.'%');
        }
        if(isset($brand_id) && $brand_id != ''  && $brand_id != 0)
        {
            $product_excel->where('brand_id', '=', $brand_id);
        }
        if(isset($category_id) && $category_id != ''  && $category_id != 0)
        {
            $product_excel->where('category_id', '=', $category_id);
        }
        if(isset($subcategory_id) && $subcategory_id != ''  && $subcategory_id != 0)
        {
            $product_excel->where('subcategory_id', '=', $subcategory_id);
        }
        if(isset($colour_id) && $colour_id != ''  && $colour_id != 0)
        {
            $product_excel->where('colour_id', '=', $colour_id);
        }
        if(isset($size_id) && $size_id != '' && $size_id != 0)
        {
            $product_excel->where('size_id', '=', $size_id);
        }
        if(isset($uqc_id) && $uqc_id != ''  && $uqc_id != 0)
        {
            $product_excel->where('uqc_id', '=', $uqc_id);
        }
        return $product_excel;


    }
}
