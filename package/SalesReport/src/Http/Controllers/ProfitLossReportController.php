<?php

namespace Retailcore\SalesReport\Http\Controllers;
use App\Http\Controllers\Controller;
use Retailcore\SalesReport\Models\profitloss_export;
use Illuminate\Http\Request;
use Retailcore\Sales\Models\sales_bill;
use Retailcore\SalesReturn\Models\return_bill;
use Retailcore\SalesReturn\Models\return_product_detail;
use Retailcore\Sales\Models\sales_product_detail;
use Retailcore\Sales\Models\sales_bill_payment_detail;
use Retailcore\Inward_Stock\Models\inward\inward_product_detail;
use Retailcore\Sales\Models\payment_method;
use App\state;
use App\country;
use Auth;
use Retailcore\Customer\Models\customer\customer;
use Retailcore\Products\Models\product\product;
use Retailcore\Company_Profile\Models\company_profile\company_profile;
use Retailcore\Customer\Models\customer\customer_address_detail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class ProfitLossReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $state_id        =  company_profile::select('state_id')->where('company_id',Auth::user()->company_id)->get();
        $company_state   = $state_id[0]['state_id'];
        
        $date        =    date("Y-m-d");

        $productdetails =  sales_product_detail::select('*')
                  ->where('company_id',Auth::user()->company_id)
                  ->where('deleted_by','=',NULL)
                  ->where('qty','!=',0)
                  ->with('sales_bill')->whereHas('sales_bill',function ($q) use ($date){
                   $q->whereRaw("STR_TO_DATE(sales_bills.bill_date,'%d-%m-%Y') between '$date' and '$date'");
                    })
                  ->orderBy('sales_products_detail_id','DESC')
                  ->paginate(10);

          $rproductdetails =  return_product_detail::select('*')
          ->where('company_id',Auth::user()->company_id)
          ->where('deleted_by','=',NULL)
          ->where('qty','!=',0) 
          ->with('return_bill')->whereHas('return_bill',function ($q) use ($date){
                   $q->whereRaw("STR_TO_DATE(return_bills.bill_date,'%d-%m-%Y') between '$date' and '$date'");
                  })
          ->orderBy('return_product_detail_id','DESC')
          ->paginate(10);

                  // echo '<pre>';
                  // print_r($productdetails);
                  // echo '</pre>';
                  // exit;

         return view('salesreport::profit_loss_report',compact('productdetails','rproductdetails','company_state'));

    }

    function datewise_profitloss_detail(Request $request)
    {
        if($request->ajax())
        {
               $state_id  =  company_profile::select('state_id')->where('company_id',Auth::user()->company_id)->get();
                $company_state   = $state_id[0]['state_id'];
                $data            =      $request->all();
                $sort_by = $data['sortby'];
                $sort_type = $data['sorttype'];
                $query = isset($data['query']) ? $data['query']  : '';

            $squery           =      sales_product_detail::select('*');
            $rquery           =      return_product_detail::select('*');
           
            
            if(isset($query) && $query != '' && $query['barcode'] != '')
            {
                if(strpos($query['barcode'], '_') !== false)
                {
                    $prodbarcode   =   explode('_',$query['barcode']);
                    $prod_barcode      =  $prodbarcode[0];
                    $prod_name    =  $prodbarcode[1];
                }
                else
                {
                    $prod_barcode      =  $query['barcode'];
                    $prod_name         =  $query['barcode'];
                }
                 $product = product::select('product_id')
                ->where('product_system_barcode','LIKE', "%$prod_barcode%")
                ->orWhere('product_name','LIKE',"%$prod_name%")
                ->where('company_id',Auth::user()->company_id)
                ->get();

                $squery->whereIn('product_id',$product);
                $rquery->whereIn('product_id',$product);
            }
            if(isset($query) && $query != '' && $query['billno'] != '')
            {
                 

                  $tbill_no  =  sales_bill::select('sales_bill_id')->where('bill_no', 'like', '%'.$query['billno'].'%')->where('company_id',Auth::user()->company_id)->get();

                  $squery->whereIn('sales_bill_id', $tbill_no);

                  $treturn_id = return_bill::select('return_bill_id')
                 ->where('company_id',Auth::user()->company_id)
                 ->where('deleted_at','=',NULL)
                 ->whereIn('sales_bill_id',$tbill_no)
                 ->get();

                  $rquery->whereIn('return_bill_id', $treturn_id);
            }
            if(isset($query) && $query != '' && $query['from_date'] != '' && $query['to_date'] != '')
            {
                
                 $rstart           =      date("Y-m-d",strtotime($query['from_date']));
                 $rend             =      date("Y-m-d",strtotime($query['to_date']));

                 // $squery->whereRaw("Date(created_at) between '$rstart' and '$rend'");
                 // $rquery->whereRaw("Date(created_at) between '$rstart' and '$rend'");

                 $squery->with('sales_bill')->whereHas('sales_bill',function ($q) use ($rstart,$rend){
                   $q->whereRaw("STR_TO_DATE(sales_bills.bill_date,'%d-%m-%Y') between '$rstart' and '$rend'");
                  });
                 $rquery->with('return_bill')->whereHas('return_bill',function ($q) use ($rstart,$rend){
                   $q->whereRaw("STR_TO_DATE(return_bills.bill_date,'%d-%m-%Y') between '$rstart' and '$rend'");
                  });
            }
           
           

            $productdetails = $squery->orderBy($sort_by, $sort_type)->where('qty','!=',0)->where('deleted_at','=',NULL)->paginate(10);

            // $rcustom   =   collect();
            // $rdata     =   $rcustom->merge($rquery->get());

             $rproductdetails = $rquery->orderBy('return_product_detail_id','DESC')->where('deleted_at','=',NULL)->paginate(10);

            return view('salesreport::profit_loss_reportdata',compact('productdetails','rproductdetails'))->render();
        }
            
                
    }

    public function exportprofitloss_details(Request $request)
    {
           
           $state_id  =  company_profile::select('state_id')->where('company_id',Auth::user()->company_id)->get();
            $company_state   = $state_id[0]['state_id'];

            $data            =      $request->all();           
            $from_date       =      $data['from_date'];
            $to_date         =      $data['to_date'];
            $billno          =      $data['bill_no'];
            $customerid      =      $data['customerid'];
            $barcode         =      $data['barcode'];

            $squery           =      sales_product_detail::select('*');
            $rquery           =      return_product_detail::select('*');
           
            
            if($barcode != '')
            {
                if(strpos($barcode, '_') !== false)
                {
                    $prodbarcode   =   explode('_',$barcode);
                    $prod_barcode  =  $prodbarcode[0];
                    $prod_name     =  $prodbarcode[1];
                }
                else
                {
                    $prod_barcode      =  $barcode;
                    $prod_name         =  $barcode;
                }
                 $product = product::select('product_id')
                ->where('product_system_barcode','LIKE', "%$prod_barcode%")
                ->orWhere('product_name','LIKE',"%$prod_name%")
                ->where('company_id',Auth::user()->company_id)
                ->get();

                $squery->whereIn('product_id',$product);
                $rquery->whereIn('product_id',$product);
            }
            if($billno != '')
            {
                 $tbill_no  =  sales_bill::select('sales_bill_id')->where('bill_no', 'like', '%'.$billno.'%')->where('company_id',Auth::user()->company_id)->get();

                  $squery->whereIn('sales_bill_id', $tbill_no);

                  $treturn_id = return_bill::select('return_bill_id')
                 ->where('company_id',Auth::user()->company_id)
                 ->where('deleted_at','=',NULL)
                 ->whereIn('sales_bill_id',$tbill_no)
                 ->get();

                  $rquery->whereIn('return_bill_id', $treturn_id);
            }
            if($from_date != '' && $to_date != '')
            {
                
                 $rstart           =      date("Y-m-d",strtotime($from_date));
                 $rend             =      date("Y-m-d",strtotime($to_date));

                 // $squery->whereRaw("Date(created_at) between '$rstart' and '$rend'");
                 // $rquery->whereRaw("Date(created_at) between '$rstart' and '$rend'");
                 $squery->with('sales_bill')->whereHas('sales_bill',function ($q) use ($rstart,$rend){
                   $q->whereRaw("STR_TO_DATE(sales_bills.bill_date,'%d-%m-%Y') between '$rstart' and '$rend'");
                  });
                 $rquery->with('return_bill')->whereHas('return_bill',function ($q) use ($rstart,$rend){
                   $q->whereRaw("STR_TO_DATE(return_bills.bill_date,'%d-%m-%Y') between '$rstart' and '$rend'");
                  });
            }
           
           

            $productdetails = $squery->orderBy('sales_products_detail_id', 'DESC')->where('qty','!=',0)->where('deleted_at','=',NULL)->get();
            $rproductdetails = $rquery->orderBy('return_product_detail_id','DESC')->where('deleted_at','=',NULL)->get();

            $overallsales   =  [];
            $header         =  ['Bill No.','Bill Date','Product Name','Barcode','Qty','SellingPrice','Discount Amount','Taxable Amount','Average Cost','Profit/Loss','Profit/Loss%'];

            $overallsales['productdetails']    =  $productdetails;
            $overallsales['rproductdetails']   =  $rproductdetails;
        
          return Excel::download(new profitloss_export($overallsales, $header), 'ProfitLoss-Export.xlsx');
       

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\view_roomwise_bill  $view_roomwise_bill
     * @return \Illuminate\Http\Response
     */
    public function show(view_roomwise_bill $view_roomwise_bill)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\view_roomwise_bill  $view_roomwise_bill
     * @return \Illuminate\Http\Response
     */
    public function edit(view_roomwise_bill $view_roomwise_bill)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\view_roomwise_bill  $view_roomwise_bill
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, view_roomwise_bill $view_roomwise_bill)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\view_roomwise_bill  $view_roomwise_bill
     * @return \Illuminate\Http\Response
     */
    public function destroy(view_roomwise_bill $view_roomwise_bill)
    {
        //
    }
}
