<?php

namespace Retailcore\Inward_Stock\Http\Controllers\inward;

use App\company;
use function foo\func;
use Retailcore\Inward_Stock\Models\inward\inward_batch_no_wise_report;
use Retailcore\Inward_Stock\Models\inward\inward_product_detail;
use Retailcore\Inward_Stock\Models\inward\inward_stock;
use Retailcore\Products\Models\product\product;
use Retailcore\Products\Models\product\price_master;
use Retailcore\Inward_Stock\Models\inward\batchnowise_report_export;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;
class InwardBatchNoWiseReportController extends Controller
{

    public function index()
    {
        $today_date   = date("d-m-Y");

        $inward_product =  inward_product_detail::where('company_id',Auth::User()->company_id)
            ->where('deleted_at', '=', NULL)
            ->where('batch_no','!=',NULL)
            ->with('product')
            ->withCount([
                'sales_product_detail as sold' => function($fquery) use ($today_date)
                {
                    $fquery->select(DB::raw('SUM(qty)'));
                    $fquery->with('sales_bill');
                    $fquery->whereHas('sales_bill',function ($q) use ($today_date){
                        $q->where('bill_date','=',$today_date);
                    });
                    $fquery->with('price_master_batch_wise');
                    $fquery->whereHas('price_master_batch_wise',function ($q)use($today_date)
                    {
                        $q->where('batch_no','=',DB::raw('inward_product_details.batch_no'));
                    });
                }
            ])
            ->withCount([
                'returnbill_product as return' => function($fquery) use ($today_date)
                {
                    $fquery->select(DB::raw('SUM(qty)'));
                    $fquery->with('return_bill');
                    $fquery->whereHas('return_bill',function ($q) use ($today_date){
                        $q->where('bill_date','=',$today_date);
                    });
                    $fquery->with('price_master_batch_wise');
                    $fquery->whereHas('price_master_batch_wise',function ($q)use($today_date)
                    {
                        $q->where('batch_no','=',DB::raw('inward_product_details.batch_no'));
                    });
                }
            ])
            ->withCount([
                'returnbill_product as restock' => function($fquery) use ($today_date) {
                    $fquery->select(DB::raw('SUM(restockqty)'));
                    $fquery->where('return_date','=',$today_date);
                }
            ])
            ->withCount([
                'damage_product_detail as damage' => function($fquery) use ($today_date)
                {
                    $fquery->select(DB::raw('SUM(product_damage_qty)'));
                    $fquery->with('damage_product')
                        ->whereHas('damage_product',function ($q) use($today_date){
                        $q->where('damage_type_id',1);
                        $q->where('damage_date','=',$today_date);
                    });
                }
            ])
            ->withCount([
                'damage_product_detail as damage_used' => function($fquery) use ($today_date)
                {
                    $fquery->select(DB::raw('SUM(product_damage_qty)'));
                    $fquery->with('damage_product')
                        ->whereHas('damage_product',function ($q) use($today_date){
                        $q->where('damage_type_id',2);
                        $q->where('damage_date','=',$today_date);
                    });
                }
            ])
            ->withCount([
                'debit_product_detail as return_to_supplier' => function($fquery) use ($today_date)
                {
                    $fquery->select(DB::raw('SUM(return_qty)'));
                    $fquery->with('debit_note')
                        ->whereHas('debit_note',function ($q) use($today_date)
                        {
                            $q->where('debit_date','=',$today_date);
                        });
                }
            ])
            ->groupBy('product_id','batch_no')
            ->orderBy('inward_product_detail_id', 'DESC');

        $product_all = collect();
        $alldata =  $product_all->merge($inward_product->get());


        $total_opening = 0;
        $opening_qty_total_cost = 0;
       foreach($alldata AS $key=>$value)
        {
            $opening =  inward_product_detail::where('deleted_at', '=', NULL)
                ->where('batch_no','=',$value['batch_no'])
                ->where('product_id','=',$value['product_id'])
                ->with('inward_stock')
                ->whereHas('inward_stock',function ($qs)use($today_date)
                {
                    $qs->where('inward_date','!=',$today_date);
                    $qs->where('inward_date','<>',$today_date);
                })
                ->select(DB::raw('cost_rate AS opening_cost_rate'),
                    DB::raw("SUM(pending_return_qty) as opening_qty"),
                    DB::raw("SUM(pending_return_qty*cost_rate) AS opening_qty_total_cost")
                )->get();

            $total_opening += $opening[0]['opening_qty'];
            $opening_qty_total_cost += $opening[0]['opening_qty_total_cost'];

            $total = inward_product_detail::where('deleted_at', '=', NULL)
                ->where('batch_no','=',$value['batch_no'])
                ->where('product_id','=',$value['product_id'])
                ->with('inward_stock')
                ->whereHas('inward_stock',function ($q)use($today_date)
                {
                    $q->where('inward_date','=',$today_date);
                })
                ->select('*',
               DB::raw("SUM(product_qty+free_qty) as total_qty"),
               DB::raw("SUM(pending_return_qty) as product_instock"),
               DB::raw("SUM(pending_return_qty*cost_rate) AS pending_qty_total_cost"),
               DB::raw("expiry_date  as expiry_date"))->get();

            $alldata[$key]['total_qty'] = $total[0]['total_qty'];
            $alldata[$key]['product_instock'] = $total[0]['product_instock'];
            $alldata[$key]['pending_qty_total_cost'] = $total[0]['pending_qty_total_cost'];
            $alldata[$key]['expiry_date'] = $total[0]['expiry_date'];


        }

        $total_inward_qty = $alldata->sum(function($value)
        {
            return ($value['total_qty']);
        });
        $total_pending_qty_total_cost = $alldata->sum(function($value) {
            return ($value['pending_qty_total_cost']);
        });
        $total_sold_qty = $alldata->sum(function($value) {
            return ($value['sold']);
        });
        $total_restock_qty = $alldata->sum(function($value) {
            return ($value['restock']);
        });
        $total_damage_qty = $alldata->sum(function($value) {
            return ($value['damage']);
        });
        $total_damage_used_qty = $alldata->sum(function($value) {
            return ($value['damage_used']);
        });
        $total_return_to_supplier_qty = $alldata->sum(function($value)
        {
            return ($value['return_to_supplier']);
        });
        $total_instock = $alldata->sum(function($value)
        {
            return ($value['product_instock']);
        });

        $inward_product  =  $inward_product->paginate(10);


        foreach($inward_product AS $key=>$value)
        {
            $opening =  inward_product_detail::where('deleted_at', '=', NULL)
                ->where('batch_no','=',$value['batch_no'])
                ->where('product_id','=',$value['product_id'])
                ->with('inward_stock')
                ->whereHas('inward_stock',function ($qs)use($today_date)
                {
                    $qs->where('inward_date','!=',$today_date);
                    $qs->where('inward_date','<>',$today_date);

                })
                ->select(DB::raw('cost_rate AS opening_cost_rate'),
                    DB::raw("SUM(pending_return_qty) as opening_qty"),
                    DB::raw("SUM(pending_return_qty*cost_rate) AS opening_qty_total_cost")
                )
                ->get();
            $inward_product[$key]['opening'] = $opening[0]['opening_qty'];
            $inward_product[$key]['opening_qty_total_cost'] = $opening[0]['opening_qty_total_cost'];


           $total = inward_product_detail::where('deleted_at', '=', NULL)
                ->where('batch_no','=',$value['batch_no'])
                ->where('product_id','=',$value['product_id'])
                ->with('inward_stock')
                ->whereHas('inward_stock',function ($q)use($today_date)
                {
                    $q->where('inward_date','=',$today_date);
                })
                ->select('*',
                    DB::raw("SUM(product_qty+free_qty) as total_qty"),
                    DB::raw("SUM(pending_return_qty) as product_instock"),
                    DB::raw("SUM(pending_return_qty*cost_rate) AS pending_qty_total_cost"),
                    DB::raw("expiry_date  as expiry_date")
                )->get();

            $inward_product[$key]['total_qty'] = $total[0]['total_qty'];
            $inward_product[$key]['product_instock'] = $total[0]['product_instock'];
            $inward_product[$key]['pending_qty_total_cost'] = $total[0]['pending_qty_total_cost'];
            $inward_product[$key]['expiry_date'] = $total[0]['expiry_date'];
        }



        return view('inward_stock::inward/batch_no_wise_report',
            compact('inward_product','total_opening','total_pending_qty_total_cost','opening_qty_total_cost',
                'total_inward_qty',
                'total_sold_qty',
                'total_restock_qty',
                'total_damage_qty',
                'total_damage_used_qty','total_return_to_supplier_qty','total_instock'));
    }


    function batch_no_wise_record(Request $request)
    {
        if($request->ajax())
        {
            
            $data            =      $request->all();
            $sort_by = $data['sortby'];
            $sort_type = $data['sorttype'];
            $query = isset($data['query']) && $data['query'] != '' ? $data['query'] : '';
            $query = str_replace(" ", "", $query);

            $inward_start_date =  date("Y-m-d", strtotime(date("d-m-Y")));

            $inward_end_date =  date("Y-m-d", strtotime(date("d-m-Y")));

            if($query['from_date']!='')
            {
                $inward_start_date           =      date("Y-m-d",strtotime($query['from_date']));
                $inward_end_date             =      date("Y-m-d",strtotime($query['to_date']));
            }

              $inward_product =  inward_product_detail::where('deleted_at', '=', NULL)
                ->where('batch_no','!=',NULL)
                ->with('product')
                ->with('inward_stock');




          if(isset($query) && $query != '' && $query['barcode'] != '')
            {
                if(strpos($query['barcode'], '_') !== false)
                {
                    $prodbarcode   =   explode('_',$query['barcode']);
                    $prod_barcode  =  $prodbarcode[0];
                }
                else
                {
                    $prod_barcode      =  $query['barcode'];
                }

                $product_id = product::select('product_id')
                    ->where('product_system_barcode','=',$prod_barcode)
                    ->orWhere('supplier_barcode','=',$prod_barcode)
                    ->where('company_id',Auth::user()->company_id)
                    ->first();

                $inward_product =  inward_product_detail::where('deleted_at',NULL)
                    ->where('deleted_at', '=', NULL)
                    ->where('batch_no','!=',NULL)
                    ->where('product_id','=',$product_id['product_id'])
                    ->with('product')
                    ->with('inward_stock');


            }


           $inward_product = $inward_product
                ->whereHas('inward_stock',function ($q) use($inward_start_date,$inward_end_date)
                {
                    $q->whereRaw("STR_TO_DATE(inward_stocks.inward_date,'%d-%m-%Y') between '$inward_start_date' and '$inward_end_date'");
                })
                ->select('*',DB::raw("SUM(product_qty+free_qty) as total_qty"),
                    DB::raw("SUM(pending_return_qty) as product_instock"),
                    DB::raw("SUM(pending_return_qty*cost_rate) AS pending_qty_total_cost")
                )
               ->withCount([
                   'sales_product_detail as sold' => function($fquery) use ($inward_start_date,$inward_end_date)
                   {
                       $fquery->select(DB::raw('SUM(qty)'));
                       $fquery->with('sales_bill');
                       $fquery->whereHas('sales_bill',function ($q) use ($inward_start_date,$inward_end_date)
                       {
                           $q->whereRaw("STR_TO_DATE(bill_date,'%d-%m-%Y') between '$inward_start_date' and '$inward_end_date'");
                       });
                       $fquery->with('price_master_batch_wise');
                       $fquery->whereHas('price_master_batch_wise',function ($q)
                       {
                           $q->where('batch_no','=',DB::raw('inward_product_details.batch_no'));
                       });
                   }
               ])
               ->withCount([
                   'returnbill_product as return' => function($fquery) use ($inward_start_date,$inward_end_date)
                   {
                       $fquery->select(DB::raw('SUM(qty)'));
                       $fquery->with('return_bill');
                       $fquery->whereHas('return_bill',function ($q) use ($inward_start_date,$inward_end_date){
                           $q->whereRaw("STR_TO_DATE(bill_date,'%d-%m-%Y') between '$inward_start_date' and '$inward_end_date'");
                       });
                       $fquery->with('price_master_batch_wise');
                       $fquery->whereHas('price_master_batch_wise',function ($q)
                       {
                           $q->where('batch_no','=',DB::raw('inward_product_details.batch_no'));
                       });
                   }
               ])
                ->withCount([
                    'returnbill_product as restock' => function($fquery) use ($inward_start_date,$inward_end_date) {
                        $fquery->select(DB::raw('SUM(restockqty)'));
                        $fquery->whereRaw("STR_TO_DATE(return_date,'%d-%m-%Y') between '$inward_start_date' and '$inward_end_date'");
                    }
                ])
                ->withCount([
                    'damage_product_detail as damage' => function($fquery) use ($inward_start_date,$inward_end_date)
                    {
                        $fquery->select(DB::raw('SUM(product_damage_qty)'));
                        $fquery->with('damage_product')
                            ->whereHas('damage_product',function ($q) use($inward_start_date,$inward_end_date){
                                $q->where('damage_type_id',1);
                                $q->whereRaw("STR_TO_DATE(damage_date,'%d-%m-%Y') between '$inward_start_date' and '$inward_end_date'");
                            });
                    }
                ])
                ->withCount([
                    'damage_product_detail as damage_used' => function($fquery) use ($inward_start_date,$inward_end_date)
                    {
                        $fquery->select(DB::raw('SUM(product_damage_qty)'));
                        $fquery->with('damage_product')
                            ->whereHas('damage_product',function ($q) use($inward_start_date,$inward_end_date){
                                $q->where('damage_type_id',2);
                                $q->whereRaw("STR_TO_DATE(damage_date,'%d-%m-%Y') between '$inward_start_date' and '$inward_end_date'");
                            });
                    }
                ])
                ->withCount([
                    'debit_product_detail as return_to_supplier' => function($fquery) use ($inward_start_date,$inward_end_date)
                    {
                        $fquery->select(DB::raw('SUM(return_qty)'));
                        $fquery->with('debit_note')
                            ->whereHas('debit_note',function ($q) use($inward_start_date,$inward_end_date)
                            {
                                $q->whereRaw("STR_TO_DATE(debit_date,'%d-%m-%Y') between '$inward_start_date' and '$inward_end_date'");
                            });
                    }
                ])
               ->groupBy('product_id','batch_no')
               ->orderBy($sort_by,$sort_type);

            //for getting opening qty

            $product_all = collect();
            $alldata =  $product_all->merge($inward_product->get());

            $total_inward_qty = $alldata->sum(function($value) {
                return ($value['total_qty']);
            });
            $total_pending_qty_total_cost = $alldata->sum(function($value) {
                return ($value['pending_qty_total_cost']);
            });
            $total_sold_qty = $alldata->sum(function($value) {
                return ($value['sold']);
            });
            $total_restock_qty = $alldata->sum(function($value) {
                return ($value['restock']);
            });
            $total_damage_qty = $alldata->sum(function($value) {
                return ($value['damage']);
            });
            $total_damage_used_qty = $alldata->sum(function($value) {
                return ($value['damage_used']);
            });
            $total_return_to_supplier_qty = $alldata->sum(function($value) {
                return ($value['return_to_supplier']);
            });
            $total_instock = $alldata->sum(function($value) {
                return ($value['product_instock'] + $value['opening']);
            });


            $total_opening = 0;
            $opening_qty_total_cost = 0;
            foreach($alldata AS $key=>$value)
            {
                $opening =  inward_product_detail::where('deleted_at', '=', NULL)
                    ->where('batch_no','=',$value['batch_no'])
                    ->where('product_id','=',$value['product_id'])
                    ->with('inward_stock')
                    ->whereHas('inward_stock',function ($q)use($inward_start_date)
                    {
                        $q->whereRaw("STR_TO_DATE(inward_stocks.inward_date,'%d-%m-%Y') < '$inward_start_date' ");
                    })
                    ->select(DB::raw('cost_rate AS opening_cost_rate'),
                        DB::raw("SUM(pending_return_qty) as opening_qty"),
                        DB::raw("SUM(pending_return_qty*cost_rate) AS opening_qty_total_cost")
                    )
                    ->get();

                $total_opening += $opening[0]['opening_qty'];
                $opening_qty_total_cost += $opening[0]['opening_qty_total_cost'];
            }

            $inward_product  = $inward_product->paginate(10);


            foreach($inward_product AS $key=>$value)
            {
                $opening =  inward_product_detail::where('deleted_at', '=', NULL)
                    ->where('batch_no','=',$value['batch_no'])
                    ->where('product_id','=',$value['product_id'])
                    ->with('inward_stock')
                    ->whereHas('inward_stock',function ($q)use($inward_start_date)
                    {
                        $q->whereRaw("STR_TO_DATE(inward_stocks.inward_date,'%d-%m-%Y') < '$inward_start_date' ");
                    })
                    ->select(DB::raw('cost_rate AS opening_cost_rate'),
                        DB::raw("SUM(pending_return_qty) as opening_qty"),
                        DB::raw("SUM(pending_return_qty*cost_rate) AS opening_qty_total_cost")
                    )
                    ->get();
                $inward_product[$key]['opening'] = $opening[0]['opening_qty'];
                $inward_product[$key]['opening_qty_total_cost'] = $opening[0]['opening_qty_total_cost'];

            }

            return view('inward_stock::inward/batch_no_wise_report_data',compact('inward_product','total_opening','total_pending_qty_total_cost','opening_qty_total_cost',
                'total_inward_qty',
                'total_sold_qty',
                'total_restock_qty',
                'total_damage_qty',
                'total_damage_used_qty','total_return_to_supplier_qty','total_instock'));

        }
            
                
    }

    public function export_batchno_details(Request $request)
    {
        return Excel::download(new batchnowise_report_export($request->from_date,$request->to_date,$request->productsearch), 'BatchnoWise-Report-Export.xlsx');

    }

}
