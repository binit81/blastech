<?php

namespace Retailcore\SalesReport\Http\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Retailcore\Products\Models\product\product;
use Retailcore\Products\Models\product\category;
use Retailcore\Products\Models\product\brand;
use Retailcore\SalesReport\Models\stockreport_export;
use Retailcore\Sales\Models\sales_product_detail;
use Retailcore\Inward_Stock\Models\inward\inward_product_detail;
use Illuminate\Support\Facades\DB;
use Auth;
use Maatwebsite\Excel\Facades\Excel;

class StockreportController extends Controller
{
    public function index()
    {
       
    	/*$product = product::where('products.company_id',Auth::user()->company_id)
            ->select('products.*','products.product_id')
            ->where('products.deleted_at','=',NULL)            
            ->orderBy('products.product_id', 'DESC')
            ->where('products.item_type','=','1')
            ->with(array('inward_product_detail'=>function($query){
		        $query->select(DB::raw("SUM(inward_product_details.product_qty) as totalQty"),'inward_product_details.product_id')                                
                ->orWhere('inward_product_details.product_id', '=', 'products.product_id')
                ->groupBy('inward_product_details.product_id');
		    }))->paginate(10);*/

            //Join query but gives incorrect result while sum different columns especially when multijoin

             /*$product =   product::select('products.*',DB::raw("SUM(inward_product_details.product_qty) as totalinwardqty"),DB::raw("SUM(sales_product_details.qty) as totalsoldqty"))->leftJoin('inward_product_details', function ($injoin) {
                             $injoin->on('inward_product_details.product_id', '=', 'products.product_id')
                            ->where(DB::raw("DATE(inward_product_details.created_at)"),'<',date("Y-m-d"))  
                            ->groupBy('inward_product_details.product_id');
                            })
                            ->leftJoin('sales_product_details', function ($sajoin) {
                                 $sajoin->on('sales_product_details.product_id', '=', 'products.product_id')
                                 ->where(DB::raw("sales_product_details.bill_date"),'<',date("d-m-Y"));
                            })
                    
                    ->groupBy('products.product_id')
                    ->paginate(10);*/

                     // $product = product::select("products.*",DB::raw("(SELECT SUM(inward_product_details.product_qty + inward_product_details.free_qty) FROM inward_product_details WHERE inward_product_details.product_id = products.product_id and DATE(inward_product_details.created_at) < '$inwarddate' GROUP BY inward_product_details.product_id) as totalinwardqty"),DB::raw("(SELECT SUM(sales_product_details.qty) FROM sales_product_details WHERE sales_product_details.product_id = products.product_id and DATE(sales_product_details.created_at) < '$inwarddate' and sales_product_details.deleted_at IS NULL GROUP BY sales_product_details.product_id) as totalsoldqty"),DB::raw("(SELECT SUM(inward_product_details.product_qty + inward_product_details.free_qty) FROM inward_product_details WHERE inward_product_details.product_id = products.product_id and DATE(inward_product_details.created_at) = '$inwarddate' GROUP BY inward_product_details.product_id) as currentinward"),DB::raw("(SELECT SUM(sales_product_details.qty) FROM sales_product_details WHERE sales_product_details.product_id = products.product_id and DATE(sales_product_details.created_at) = '$inwarddate' and sales_product_details.deleted_at IS NULL GROUP BY sales_product_details.product_id) as currentsold"),DB::raw("(SELECT SUM(price_masters.product_qty *price_masters.offer_price)/SUM(price_masters.product_qty) FROM price_masters WHERE price_masters.product_id = products.product_id GROUP BY price_masters.product_id) as averagemrp"))->where('item_type', '=', 1)->orderBy('product_id','DESC')->paginate(10);

   ///final query/////////////////////////////////////////////////////////////////////////////////////////////////



             $inwarddate = date("Y-m-d");
             $billdate   = date("d-m-Y");

             $query  = product::where('company_id', Auth::user()->company_id)
                ->select('product_name','product_system_barcode','supplier_barcode','product_id','sku_code','category_id','brand_id')
                ->where('deleted_at', '=', NULL)
               ->withCount([
                    'inward_product_detail as totalinwardqty' => function($fquery) use ($inwarddate) {
                        $fquery->select(DB::raw('SUM(product_qty+free_qty)'));
                        $fquery->with('inward_stock')->whereHas('inward_stock',function ($q) use ($inwarddate){
                            $q->whereRaw("STR_TO_DATE(inward_stocks.inward_date,'%d-%m-%Y') < '$inwarddate'");
                        });
                       
                    }
                ])
                ->withCount([
                    'sales_product_detail as totalsoldqty' => function($fquery) use ($inwarddate) {
                        $fquery->select(DB::raw('SUM(qty)'));
                        $fquery->with('sales_bill')->whereHas('sales_bill',function ($q) use ($inwarddate){
                            $q->whereRaw("STR_TO_DATE(sales_bills.bill_date,'%d-%m-%Y') < '$inwarddate'");
                        });
                    }
                ])
                ->withCount([
                    'inward_product_detail as currentinward' => function($fquery) use ($inwarddate) {
                        $fquery->select(DB::raw('SUM(product_qty+free_qty)'));
                         $fquery->with('inward_stock')->whereHas('inward_stock',function ($q) use ($inwarddate) {
                            $q->whereRaw("STR_TO_DATE(inward_stocks.inward_date,'%d-%m-%Y') between '$inwarddate' and '$inwarddate'");
                        });
                       
                    }
                ])
                ->withCount([
                    'sales_product_detail as currentsold' => function($fquery) use ($inwarddate) {
                        $fquery->select(DB::raw('SUM(qty)'));
                         $fquery->with('sales_bill')->whereHas('sales_bill',function ($q) use ($inwarddate){
                            $q->whereRaw("STR_TO_DATE(sales_bills.bill_date,'%d-%m-%Y') between '$inwarddate' and '$inwarddate'");
                        });
                       
                    }
                ])
                ->withCount([
                    'price_master as averagemrp' => function($fquery) use ($inwarddate) {
                        $fquery->select(DB::raw('SUM(product_qty *offer_price)/SUM(product_qty)'));
                        $fquery->groupBy('product_id');
                    }
                ])
                ->withCount([
                    'returnbill_product as totalreturn' => function($fquery) use ($inwarddate) {
                        $fquery->select(DB::raw('SUM(qty)'));
                        $fquery->whereRaw("STR_TO_DATE(return_date,'%d-%m-%Y') < '$inwarddate'");
                    }
                ])
                ->withCount([
                    'returnbill_product as currentreturn' => function($fquery) use ($inwarddate) {
                        $fquery->select(DB::raw('SUM(qty)'));
                        $fquery->whereRaw("STR_TO_DATE(return_date,'%d-%m-%Y') between '$inwarddate' and '$inwarddate'");
                    }
                ])
                ->withCount([
                    'returnbill_product as totalrestock' => function($fquery) use ($inwarddate) {
                        $fquery->select(DB::raw('SUM(restockqty)'));
                        $fquery->whereRaw("STR_TO_DATE(return_date,'%d-%m-%Y') < '$inwarddate'");
                    }
                ])
                ->withCount([
                    'returnbill_product as totaldamage' => function($fquery) use ($inwarddate) {
                        $fquery->select(DB::raw('SUM(damageqty)'));
                        $fquery->whereRaw("STR_TO_DATE(return_date,'%d-%m-%Y') < '$inwarddate'");
                    }
                ])
                ->withCount([
                    'returnbill_product as currentrestock' => function($fquery) use ($inwarddate) {
                        $fquery->select(DB::raw('SUM(restockqty)'));
                        $fquery->whereRaw("STR_TO_DATE(return_date,'%d-%m-%Y') between '$inwarddate' and '$inwarddate'");
                    }
                ])
                ->withCount([
                    'returnbill_product as currentdamage' => function($fquery) use ($inwarddate) {
                        $fquery->select(DB::raw('SUM(damageqty)'));
                        $fquery->whereRaw("STR_TO_DATE(return_date,'%d-%m-%Y') between '$inwarddate' and '$inwarddate'");
                    }
                ])
                ->withCount([
                    'damage_product_detail as totalused' => function($fquery) use ($inwarddate)
                    {
                        $fquery->select(DB::raw('SUM(product_damage_qty)'));
                        $fquery->with('damage_product')->whereHas('damage_product',function ($q) use ($inwarddate){
                            $q->where('damage_type_id',2);
                            $q->whereRaw("STR_TO_DATE(damage_products.damage_date,'%d-%m-%Y') < '$inwarddate'");
                        });
                    }
                ])
                ->withCount([
                    'damage_product_detail as currentused' => function($fquery) use ($inwarddate)
                    {
                        $fquery->select(DB::raw('SUM(product_damage_qty)'));
                        $fquery->with('damage_product')->whereHas('damage_product',function ($q) use ($inwarddate){
                            $q->where('damage_type_id',2);
                            $q->whereRaw("STR_TO_DATE(damage_products.damage_date,'%d-%m-%Y') between '$inwarddate' and '$inwarddate'");
                        });
                        
                    }
                ])
                ->withCount([
                    'damage_product_detail as totalddamage' => function($fquery) use ($inwarddate)
                    {
                        $fquery->select(DB::raw('SUM(product_damage_qty)'));
                        $fquery->with('damage_product')->whereHas('damage_product',function ($q) use ($inwarddate){
                            $q->where('damage_type_id','!=',2);
                            $q->whereRaw("STR_TO_DATE(damage_products.damage_date,'%d-%m-%Y') < '$inwarddate'");
                        });
                       
                    }
                ])
                ->withCount([
                    'damage_product_detail as currentddamage' => function($fquery) use ($inwarddate)
                    {
                        $fquery->select(DB::raw('SUM(product_damage_qty)'));
                        $fquery->with('damage_product')->whereHas('damage_product',function ($q) use ($inwarddate){
                            $q->where('damage_type_id','!=',2);
                            $q->whereRaw("STR_TO_DATE(damage_products.damage_date,'%d-%m-%Y') between '$inwarddate' and '$inwarddate'");
                        });
                        
                    }
                ])
                ->withCount([
                    'debit_product_detail as totalsuppreturn' => function($fquery) use ($inwarddate) {
                        $fquery->select(DB::raw('SUM(return_qty)'));
                         $fquery->with('debit_note')->whereHas('debit_note',function ($q) use ($inwarddate){
                            $q->whereRaw("STR_TO_DATE(debit_notes.debit_date,'%d-%m-%Y') < '$inwarddate'");
                        });
                    }
                ])
                ->withCount([
                    'debit_product_detail as currentsuppreturn' => function($fquery) use ($inwarddate) {
                        $fquery->select(DB::raw('SUM(return_qty)'));
                         $fquery->with('debit_note')->whereHas('debit_note',function ($q) use ($inwarddate){
                            $q->whereRaw("STR_TO_DATE(debit_notes.debit_date,'%d-%m-%Y') between '$inwarddate' and '$inwarddate'");
                        });
                    }
                ])->where('item_type', '=', 1)->orderBy('product_id','DESC');

                $custom = collect();
                $data = $custom->merge($query->get());
                $product   =  $query->paginate(10);
               
                $totinwardqty = 0;
                $totsoldqty = 0;
                $totrestock = 0;
                $totusedqty = 0;
                $totdamageqty =0;
                $totsupprqty=0;
                $currinward = 0;
                $currsold = 0;
                $currrestock=0;
                $currusedqty=0;
                $currdamageqty =0;
                $currddamageqty=0;
                $ttotaldamage = 0;
                $currsupprqty = 0;
                $count=0;

            foreach ($data as $totproductkey=>$ttotproduct)
            {
               
             
                $count++;
                $totinwardqty          +=   $ttotproduct['totalinwardqty'];
                $totsoldqty            +=   $ttotproduct['totalsoldqty'];
                $totrestock            +=   $ttotproduct['totalrestock'];
                $totusedqty            +=   $ttotproduct['totalused'];
                $totdamageqty          +=   $ttotproduct['totalddamage'];
                $totsupprqty           +=   $ttotproduct['totalsuppreturn'];
                $currinward            +=   $ttotproduct['currentinward'];               
                $currsold              +=   $ttotproduct['currentsold'];
                $currrestock           +=   $ttotproduct['currentrestock'];
                $currusedqty           +=   $ttotproduct['currentused'];
                $currdamageqty         +=   $ttotproduct['currentdamage'];
                $currddamageqty        +=   $ttotproduct['currentddamage'];
                $currsupprqty          +=   $ttotproduct['currentsuppreturn'];

            }

               $totopening     =   $totinwardqty - $totsoldqty + $totrestock - $totusedqty - $totdamageqty - $totsupprqty;
               $totstock       =   $totopening +$currinward -$currsold + $currrestock-$currusedqty - $currddamageqty  - $currsupprqty;
               $ttotaldamage   =   $currdamageqty + $currddamageqty; 

    		 return view('salesreport::stock_report',compact('product','totopening','totstock','currinward','currsold','currrestock','currusedqty','ttotaldamage','currsupprqty','count'));
    }

    function datewise_stock_detail(Request $request)
    {
        if($request->ajax())
        {
            
            $data            =      $request->all();
            $sort_by = $data['sortby'];
            $sort_type = $data['sorttype'];
            $query = isset($data['query']) ? $data['query']  : '';


            if($query['from_date']!='')
            {
                 $inwardstartdate            =      date("Y-m-d",strtotime($query['from_date']));
                 $inwardenddate              =      date("Y-m-d",strtotime($query['to_date']));
                 $salesstartdate             =      $query['from_date'];
                 $salesenddate               =      $query['to_date'];
            }
            else
            {
                $inwardstartdate            =      date("Y-m-d");
                $inwardenddate              =      date("Y-m-d");
                $salesstartdate             =      date("d-m-Y");
                $salesenddate               =      date("d-m-Y");
            }
           

        $pquery  = product::where('company_id', Auth::user()->company_id)
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
                

              

            if(isset($query) && $query != '' && $query['productsearch'] != '')
            {

                 $prquery = product::select('product_id')
                     ->where('company_id',Auth::user()->company_id)
                     ->where('deleted_at','=',NULL);

                   if(strpos($query['productsearch'], '_') !== false)
                    {
                        $prodname      =   explode('_',$query['productsearch']);
                        $prod_barcode  =  $prodname[0];
                        $prod_name     =  $prodname[1];

                        $prquery->where('product_name', 'LIKE', "%$prod_name%")
                                 ->where('product_system_barcode', 'LIKE', "%$prod_barcode%")
                                 ->orWhere('supplier_barcode', 'LIKE', "%$prod_barcode%");
                    }
                    else
                    {
                        $prod_barcode   =   $query['productsearch'];
                        $prod_name      =   $query['productsearch'];
                        $prquery->where('product_name', 'LIKE', "%$prod_name%")
                                 ->orWhere('product_system_barcode', 'LIKE', "%$prod_barcode%");
                    }

                    $prodresult  =  $prquery->get();
                   

                     $pquery->whereIn('product_id',$prodresult);
            }

            if(isset($query) && $query != '' && $query['categoryname'] != '')
            {
                  $categoryname   =  $query['categoryname'];
                   $catresult = category::select('category_id')
                     ->where('company_id',Auth::user()->company_id)
                     ->where('deleted_at','=',NULL)
                     ->where('category_name', 'LIKE', "%$categoryname%")
                     ->get();

                      $pquery->whereIn('category_id',$catresult);
            }
            if(isset($query) && $query != '' && $query['brandname'] != '')
            {
                $brandname   =  $query['brandname'];
                 $brandresult = brand::select('brand_id')
                 ->where('company_id',Auth::user()->company_id)
                 ->where('deleted_at','=',NULL)
                 ->where('brand_type', 'LIKE', "%$brandname%")
                 ->get();
                  $pquery->whereIn('brand_id',$brandresult);
            }


                $custom = collect();
                $data = $custom->merge($pquery->get());
               
                $totinwardqty = 0;
                $totsoldqty = 0;
                $totrestock = 0;
                $totusedqty = 0;
                $totdamageqty =0;
                $totsupprqty=0;
                $currinward = 0;
                $currsold = 0;
                $currrestock=0;
                $currusedqty=0;
                $currdamageqty =0;
                $currddamageqty=0;
                $ttotaldamage = 0;
                $currsupprqty = 0;
                $count=0;

            foreach ($data as $totproductkey=>$ttotproduct)
            {
               
             
                $count++;
                $totinwardqty          +=   $ttotproduct['totalinwardqty'];
                $totsoldqty            +=   $ttotproduct['totalsoldqty'];
                $totrestock            +=   $ttotproduct['totalrestock'];
                $totusedqty            +=   $ttotproduct['totalused'];
                $totdamageqty          +=   $ttotproduct['totalddamage'];
                $totsupprqty           +=   $ttotproduct['totalsuppreturn'];
                $currinward            +=   $ttotproduct['currentinward'];               
                $currsold              +=   $ttotproduct['currentsold'];
                $currrestock           +=   $ttotproduct['currentrestock'];
                $currusedqty           +=   $ttotproduct['currentused'];
                $currdamageqty         +=   $ttotproduct['currentdamage'];
                $currddamageqty        +=   $ttotproduct['currentddamage'];
                $currsupprqty          +=   $ttotproduct['currentsuppreturn'];

            }

               $totopening     =   $totinwardqty - $totsoldqty + $totrestock - $totusedqty - $totdamageqty - $totsupprqty;
               $totstock       =   $totopening +$currinward -$currsold + $currrestock-$currusedqty - $currddamageqty  - $currsupprqty;
               $ttotaldamage   =   $currdamageqty + $currddamageqty;

            $product = $pquery->where('item_type', '=', 1)->orderBy($sort_by, $sort_type)
                          ->paginate(10);


            return view('salesreport::view_stockreport_data',compact('product','totopening','totstock','currinward','currsold','currrestock','currusedqty','ttotaldamage','currsupprqty','count'));
        }
            
                
    }

    public function export_stockreport_details(Request $request)
    {

        
          return Excel::download(new stockreport_export($request->from_date,$request->to_date,$request->productsearch,$request->categoryname,$request->brandname), 'StockReport-Export.xlsx');
       

    }
    public function category_search(Request $request)
    {


        $result = category::select('category_name')
             ->where('company_id',Auth::user()->company_id)
             ->where('deleted_at','=',NULL)
             ->where('category_name', 'LIKE', "%$request->search_val%")
             ->get();

        

        return json_encode(array("Success"=>"True","Data"=>$result) );
    }
    public function brand_search(Request $request)
    {


        $result = brand::select('brand_type')
             ->where('company_id',Auth::user()->company_id)
             ->where('deleted_at','=',NULL)
             ->where('brand_type', 'LIKE', "%$request->search_val%")
             ->get();

        

        return json_encode(array("Success"=>"True","Data"=>$result) );
    }


}
