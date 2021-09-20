<?php

namespace Retailcore\Inward_Stock\Models\inward;

use Retailcore\Products\Models\product\product;
use Retailcore\Products\Models\product\category;
use Retailcore\Products\Models\product\brand;
use Retailcore\Products\Models\product\price_master;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class batchnowise_report_export implements FromQuery, WithHeadings, WithMapping
{

    use Exportable;
    
    public $from_date = '';
    public $to_date = '';
    public $productsearch = '';
  
    public function __construct($from_date,$to_date,$productsearch) {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->productsearch = $productsearch;
    }


    public function headings(): array
    {
        return [
            'Barcode',
            'Product Name',
            'Batch No.',
            'Cost Rate',
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
            'Total Cost Rate',
            'Total MRP Value',
            'Expiry Date',
            'Expiry Days',
        ];
    }

 

public function map($inward_product): array
    {
        $barcode = '';
        $rows    = [];

        $inward_start_date =  date("Y-m-d", strtotime(date("d-m-Y")));

        if($this->from_date!='')
        {
            $inward_start_date  =date("Y-m-d",strtotime($this->from_date));
        }

        $opening =  inward_product_detail::where('deleted_at', '=', NULL)
            ->where('batch_no','=',$inward_product['batch_no'])
            ->where('product_id','=',$inward_product['product_id'])
            ->with('inward_stock')
            ->whereHas('inward_stock',function ($q)use($inward_start_date)
            {
                $q->whereRaw("STR_TO_DATE(inward_stocks.inward_date,'%d-%m-%Y') < '$inward_start_date' ");
            })
            ->select(DB::raw('cost_rate AS opening_cost_rate'),
                DB::raw("SUM(pending_return_qty) as opening_qty"),
                DB::raw("SUM(pending_return_qty*cost_rate) AS opening_qty_total_cost")
            )->get();

        $opening_qty = $opening[0]['opening_qty'];
        $opening_qty_total_cost = $opening[0]['opening_qty_total_cost'];


        if($inward_product->product->supplier_barcode != '')
        {
            $barcode =  $inward_product->product->supplier_barcode;
        }
        else
        {
            $barcode = $inward_product->product->product_system_barcode;
        }
        $inward_product->product_instock = isset($inward_product->product_instock) && $inward_product->product_instock != '' ?$inward_product->product_instock : 0;

        $product_instock = $inward_product->product_instock + $opening_qty;

        $total_cost_rate = (($inward_product->pending_qty_total_cost + $opening_qty_total_cost) )/($product_instock);

        $costrate = $total_cost_rate * $product_instock;

        $mrptotal = $inward_product->product_mrp * $product_instock;

        $diff = '';
        if($inward_product->expiry_date != null)
        {
            $now = strtotime(date('d-m-Y')); //CURRENT DATE
            $expiry_date = strtotime($inward_product->expiry_date);
            $datediff = $expiry_date-$now;
            $diff =  round($datediff / (60 * 60 * 24));
        }

            $rows[] = $barcode;
           $rows[] = $inward_product->product->product_name;
           $rows[] = $inward_product->batch_no;
           $rows[] = $total_cost_rate;
           $rows[] = $inward_product->product_mrp;
           $rows[] = $opening_qty;
           $rows[] = $inward_product->total_qty;
           $rows[] = $inward_product->sold;
           $rows[] = $inward_product->return;
           $rows[] = $inward_product->restock;
           $rows[] = $inward_product->damage;
           $rows[] = $inward_product->damage_used;
           $rows[] = $inward_product->return_to_supplier;
           $rows[] = $product_instock;
           $rows[] = $costrate;
           $rows[] = $mrptotal;
           $rows[] = $inward_product->expiry_date;
           $rows[] = $diff;

           return $rows;
    }

  
    public function query()
    {
        $inward_start_date =  date("Y-m-d", strtotime(date("d-m-Y")));

        $inward_end_date =  date("Y-m-d", strtotime(date("d-m-Y")));

        if($this->from_date!='')
        {
            $inward_start_date           =      date("Y-m-d",strtotime($this->from_date));
            $inward_end_date             =      date("Y-m-d",strtotime($this->to_date));
        }


        $inward_product = inward_product_detail::where('deleted_at', '=', NULL)
            ->where('batch_no','!=',NULL)
            ->with('product')
            ->with('inward_stock');

        if(isset($this->productsearch) && $this->productsearch != '')
        {
            if(strpos($this->productsearch, '_') !== false)
            {
                $prodbarcode   =   explode('_',$this->productsearch);
                $prod_barcode  =  $prodbarcode[0];
            }
            else
            {
                $prod_barcode  =  $this->productsearch;
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


        $inward_product = $inward_product->whereHas('inward_stock',function ($q) use($inward_start_date,$inward_end_date)
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
            ]);
        $inward_product->groupBy('product_id','batch_no');
        $inward_product->orderBy('inward_product_detail_id','DESC');

        return $inward_product;
    }

}


