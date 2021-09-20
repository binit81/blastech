<?php

namespace Retailcore\SalesReport\Models;

use Retailcore\Products\Models\product\product;
use Retailcore\Products\Models\product\category;
use Retailcore\Products\Models\product\brand;
use Retailcore\Company_Profile\Models\company_profile\company_profile;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class stockreport_export implements FromQuery, WithHeadings, WithMapping
{

    use Exportable;
    
    public $from_date = '';
    public $to_date = '';
    public $productsearch = '';
    public $categoryname=''; 
    public $brandname ='';
  
    public function __construct($from_date,$to_date,$productsearch,$categoryname,$brandname) {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->productsearch = $productsearch;
        $this->categoryname=$categoryname;
        $this->brandname=$brandname;
    }



    public function headings(): array
    {
        return [
            'Barcode',
            'Product Name',
            'SKU Code',
            'Category',
            'Brand',
            'MRP',
            'Opening',
            'Inward',
            'Sold',
            'Return',
            'Restock',
            'Damage',
            'Used',
            'Supplier Return',
            'InStock',
            'Total MRP Value',
        ];
    }

 

public function map($product): array
    {
        $state_id  =  company_profile::select('decimal_points')->where('company_id',Auth::user()->company_id)->get();
       
         
         $decimal_points  = $state_id[0]['decimal_points'];
    
        $count = '';

        $opening   =   $product['totalinwardqty'] - $product['totalsoldqty'] + $product['totalrestock'] - $product['totalused'] - $product['totalddamage'] - $product['totalsuppreturn'];
        $stock     =   $opening +$product['currentinward'] -$product['currentsold'] + $product['currentrestock']-$product['currentused'] - $product['currentddamage']  - $product['currentsuppreturn'];
        $todayinward  = $product->currentinward != '' ?$product->currentinward : 0;
        $todaysold    = $product->currentsold != '' ?$product->currentsold : 0;

        $totaldamage  =  $product['currentdamage'] +  $product['currentddamage'];


        

        if($product->averagemrp !='')
        {
            $averagemrp   = $product->averagemrp;
        }
        else
        {
            $averagemrp    =  $product->offer_price != '' ?$product->offer_price : 0;
        }
        $totalmrpvalue  =  $averagemrp * $stock;
        if($product->supplier_barcode!='' && $product->supplier_barcode!=NULL)
        {
            $barcode  =   $product->supplier_barcode;
           
        }
        else
        {
             $barcode  =   $product->product_system_barcode;
        }
        
        $rows    = [];
           $rows[] = $barcode;
           $rows[] = $product->product_name;
           $rows[] = $product->sku_code;
           $rows[] = $product['category']['category_name'];
           $rows[] = $product['brand']['brand_type'];
           $rows[] = $averagemrp != '' ?round($averagemrp,$decimal_points) : '0';
           $rows[] = $opening != '' ?$opening : '0';          
           $rows[] = $product->currentinward != '' ?$product->currentinward : '0';
           $rows[] = $product->currentsold != '' ?$product->currentsold : '0';
           $rows[] = $product->currentreturn != '' ?$product->currentreturn : '0';
           $rows[] = $product->currentrestock != '' ?$product->currentrestock : '0';
           $rows[] = $totaldamage != '' ?$totaldamage : '0';
           $rows[] = $product->currentused != '' ?$product->currentused : '0';
           $rows[] = $product->currentsuppreturn != '' ?$product->currentsuppreturn : '0';
           $rows[] = $stock != '' ?$stock : '0'; 
           $rows[] = $totalmrpvalue != '' ?round($totalmrpvalue,$decimal_points) : '0';  

           
        
        return $rows;
    }

  
    public function query()
    {

                
            if(strpos($this->productsearch, '_') !== false)
            {
                $prodname    =   explode('_',$this->productsearch);
                $prod_barcode =  $prodname[0];
                $prod_name     =  $prodname[1];
            }
            else
            {
                $prod_barcode   =   $this->productsearch;
                $prod_name      =   $this->productsearch;
            }

           

            $prodresult = product::select('product_id')
             ->where('company_id',Auth::user()->company_id)
             ->where('deleted_at','=',NULL)
             ->where('product_name', 'LIKE', "%$prod_name%")
             ->where('product_system_barcode', 'LIKE', "%$prod_barcode%")
             ->orWhere('supplier_barcode', 'LIKE', "%$prod_barcode%")
             ->get();

           
             $catresult = category::select('category_id')
             ->where('company_id',Auth::user()->company_id)
             ->where('deleted_at','=',NULL)
             ->where('category_name', 'LIKE', "%$this->categoryname%")
             ->get();


            
             $brandresult = brand::select('brand_id')
             ->where('company_id',Auth::user()->company_id)
             ->where('deleted_at','=',NULL)
             ->where('brand_type', 'LIKE', "%$this->brandname%")
             ->get();

            if($this->from_date!='')
            {
                 $inwardstartdate            =      date("Y-m-d",strtotime($this->from_date));
                 $inwardenddate              =      date("Y-m-d",strtotime($this->to_date));
                 $salesstartdate             =      $this->from_date;
                 $salesenddate               =      $this->to_date;
            }
            else
            {
                $inwardstartdate            =      date("Y-m-d");
                $inwardenddate              =      date("Y-m-d");
                $salesstartdate             =      date("d-m-Y");
                $salesenddate               =      date("d-m-Y");
            }
           
           

        $query  = product::where('company_id', Auth::user()->company_id)
                ->select('product_name','product_system_barcode','supplier_barcode','product_id','sku_code','category_id','brand_id')
                ->where('deleted_at', '=', NULL)
                ->withCount([
                    'inward_product_detail as totalinwardqty' => function($fquery) use ($inwardstartdate) {
                        $fquery->select(DB::raw('SUM(product_qty+free_qty)'));
                        $fquery->with('inward_stock')->whereHas('inward_stock',function ($q) use ($inwardstartdate){
                            $q->whereRaw("STR_TO_DATE(inward_stocks.inward_date,'%d-%m-%Y') < '$inwardstartdate'");
                        });
                       
                    }
                ])
                ->withCount([
                    'sales_product_detail as totalsoldqty' => function($fquery) use ($inwardstartdate) {
                        $fquery->select(DB::raw('SUM(qty)'));
                        $fquery->with('sales_bill')->whereHas('sales_bill',function ($q) use ($inwardstartdate){
                            $q->whereRaw("STR_TO_DATE(sales_bills.bill_date,'%d-%m-%Y') < '$inwardstartdate'");
                        });
                    }
                ])
                ->withCount([
                    'inward_product_detail as currentinward' => function($fquery) use ($inwardstartdate,$inwardenddate) {
                        $fquery->select(DB::raw('SUM(product_qty+free_qty)'));
                         $fquery->with('inward_stock')->whereHas('inward_stock',function ($q) use ($inwardstartdate,$inwardenddate) {
                            $q->whereRaw("STR_TO_DATE(inward_stocks.inward_date,'%d-%m-%Y') between '$inwardstartdate' and '$inwardenddate'");
                        });
                       
                    }
                ])
                ->withCount([
                    'sales_product_detail as currentsold' => function($fquery) use ($inwardstartdate,$inwardenddate) {
                        $fquery->select(DB::raw('SUM(qty)'));
                         $fquery->with('sales_bill')->whereHas('sales_bill',function ($q) use ($inwardstartdate,$inwardenddate){
                            $q->whereRaw("STR_TO_DATE(sales_bills.bill_date,'%d-%m-%Y') between '$inwardstartdate' and '$inwardenddate'");
                        });
                       
                    }
                ])
                ->withCount([
                    'price_master as averagemrp' => function($fquery) use ($inwardstartdate) {
                        $fquery->select(DB::raw('SUM(product_qty *offer_price)/SUM(product_qty)'));
                        $fquery->groupBy('product_id');
                    }
                ])
                ->withCount([
                    'returnbill_product as totalreturn' => function($fquery) use ($inwardstartdate) {
                        $fquery->select(DB::raw('SUM(qty)'));
                        $fquery->whereRaw("STR_TO_DATE(return_date,'%d-%m-%Y') < '$inwardstartdate'");
                    }
                ])
                ->withCount([
                    'returnbill_product as currentreturn' => function($fquery) use ($inwardstartdate,$inwardenddate) {
                        $fquery->select(DB::raw('SUM(qty)'));
                        $fquery->whereRaw("STR_TO_DATE(return_date,'%d-%m-%Y') between '$inwardstartdate' and '$inwardenddate'");
                    }
                ])
                ->withCount([
                    'returnbill_product as totalrestock' => function($fquery) use ($inwardstartdate) {
                        $fquery->select(DB::raw('SUM(restockqty)'));
                        $fquery->whereRaw("STR_TO_DATE(return_date,'%d-%m-%Y') < '$inwardstartdate'");
                    }
                ])
                ->withCount([
                    'returnbill_product as totaldamage' => function($fquery) use ($inwardstartdate) {
                        $fquery->select(DB::raw('SUM(damageqty)'));
                        $fquery->whereRaw("STR_TO_DATE(return_date,'%d-%m-%Y') < '$inwardstartdate'");
                    }
                ])
                ->withCount([
                    'returnbill_product as currentrestock' => function($fquery) use ($inwardstartdate,$inwardenddate) {
                        $fquery->select(DB::raw('SUM(restockqty)'));
                        $fquery->whereRaw("STR_TO_DATE(return_date,'%d-%m-%Y') between '$inwardstartdate' and '$inwardenddate'");
                    }
                ])
                ->withCount([
                    'returnbill_product as currentdamage' => function($fquery) use ($inwardstartdate,$inwardenddate) {
                        $fquery->select(DB::raw('SUM(damageqty)'));
                        $fquery->whereRaw("STR_TO_DATE(return_date,'%d-%m-%Y') between '$inwardstartdate' and '$inwardenddate'");
                    }
                ])
                ->withCount([
                    'damage_product_detail as totalused' => function($fquery) use ($inwardstartdate)
                    {
                        $fquery->select(DB::raw('SUM(product_damage_qty)'));
                        $fquery->with('damage_product')->whereHas('damage_product',function ($q) use ($inwardstartdate){
                            $q->where('damage_type_id',2);
                            $q->whereRaw("STR_TO_DATE(damage_products.damage_date,'%d-%m-%Y') < '$inwardstartdate'");
                        });
                    }
                ])
                ->withCount([
                    'damage_product_detail as currentused' => function($fquery) use ($inwardstartdate,$inwardenddate)
                    {
                        $fquery->select(DB::raw('SUM(product_damage_qty)'));
                        $fquery->with('damage_product')->whereHas('damage_product',function ($q) use ($inwardstartdate,$inwardenddate){
                            $q->where('damage_type_id',2);
                            $q->whereRaw("STR_TO_DATE(damage_products.damage_date,'%d-%m-%Y') between '$inwardstartdate' and '$inwardenddate'");
                        });
                        
                    }
                ])
                ->withCount([
                    'damage_product_detail as totalddamage' => function($fquery) use ($inwardstartdate)
                    {
                        $fquery->select(DB::raw('SUM(product_damage_qty)'));
                        $fquery->with('damage_product')->whereHas('damage_product',function ($q) use ($inwardstartdate){
                            $q->where('damage_type_id','!=',2);
                            $q->whereRaw("STR_TO_DATE(damage_products.damage_date,'%d-%m-%Y') < '$inwardstartdate'");
                        });
                       
                    }
                ])
                ->withCount([
                    'damage_product_detail as currentddamage' => function($fquery) use ($inwardstartdate,$inwardenddate)
                    {
                        $fquery->select(DB::raw('SUM(product_damage_qty)'));
                        $fquery->with('damage_product')->whereHas('damage_product',function ($q) use ($inwardstartdate,$inwardenddate){
                            $q->where('damage_type_id','!=',2);
                            $q->whereRaw("STR_TO_DATE(damage_products.damage_date,'%d-%m-%Y') between '$inwardstartdate' and '$inwardenddate'");
                        });
                        
                    }
                ])
                ->withCount([
                    'debit_product_detail as totalsuppreturn' => function($fquery) use ($inwardstartdate) {
                        $fquery->select(DB::raw('SUM(return_qty)'));
                         $fquery->with('debit_note')->whereHas('debit_note',function ($q) use ($inwardstartdate){
                            $q->whereRaw("STR_TO_DATE(debit_notes.debit_date,'%d-%m-%Y') < '$inwardstartdate'");
                        });
                    }
                ])
                ->withCount([
                    'debit_product_detail as currentsuppreturn' => function($fquery) use ($inwardstartdate,$inwardenddate) {
                        $fquery->select(DB::raw('SUM(return_qty)'));
                         $fquery->with('debit_note')->whereHas('debit_note',function ($q) use ($inwardstartdate,$inwardenddate){
                            $q->whereRaw("STR_TO_DATE(debit_notes.debit_date,'%d-%m-%Y') between '$inwardstartdate' and '$inwardenddate'");
                        });
                    }
                ]);
                


            
            if($this->productsearch!='')
            {
                $query->whereIn('product_id',$prodresult);
            }
            if($this->categoryname!='')
            {
                $query->whereIn('category_id',$catresult);
            }
            if($this->brandname!='')
            {
                $query->whereIn('brand_id',$brandresult);
            }


         
            $product  =  $query->where('item_type', '=', 1)->orderBy('product_id','desc');
        
        return $product;


    }
}


