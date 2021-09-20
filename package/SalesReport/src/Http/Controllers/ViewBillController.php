<?php
namespace Retailcore\SalesReport\Http\Controllers;
use App\Http\Controllers\Controller;

use Retailcore\SalesReport\Models\view_bill;
use Illuminate\Http\Request;
use Retailcore\SalesReport\Models\viewbill_export;
use Retailcore\Products\Models\product\price_master;
use Retailcore\Sales\Models\sales_bill;
use Retailcore\Sales\Models\sales_product_detail;
use Retailcore\Sales\Models\sales_bill_payment_detail;
use Retailcore\SalesReturn\Models\return_bill;
use Retailcore\SalesReturn\Models\return_product_detail;
use Retailcore\SalesReturn\Models\return_bill_payment;
use Retailcore\Products\Models\product\product;
use Retailcore\Sales\Models\reference;
use Retailcore\Sales\Models\payment_method;
use Retailcore\Inward_Stock\Models\inward\inward_product_detail;
use Retailcore\CreditNote\Models\customer_creditnote;
use Retailcore\CreditBalance\Models\customer_creditaccount;
use Retailcore\CreditNote\Models\creditnote_payment;
use Retailcore\CreditBalance\Models\customer_creditreceipt_detail;
use App\state;
use App\country;
use Retailcore\Company_Profile\Models\company_profile\company_profile;
use Auth;
use Retailcore\Customer\Models\customer\customer;
use Retailcore\Customer\Models\customer\customer_address_detail;
use Maatwebsite\Excel\Facades\Excel;
use DB;


class ViewBillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $maxsales_id   =  '';
        $minsales_id   =  '';
        $rmaxsales_id   =  '';
        $rminsales_id   =  '';
        $returnsales   =  array();
        $payment_methods = payment_method::where('is_active','=','1')->where('payment_method_id','!=',9)->get();
        $state_id  =  company_profile::select('state_id','tax_type','tax_title','currency_title')->where('company_id',Auth::user()->company_id)->get();
        $company_state   = $state_id[0]['state_id'];
        $tax_type        = $state_id[0]['tax_type'];
        $tax_title       = $state_id[0]['tax_title'];
        $taxname         = $tax_type==1?$tax_title:'IGST';

        $date      =   date("Y-m-d");
      

        $squery = sales_bill::select("sales_bills.*",DB::raw("(SELECT SUM(sales_product_details.discount_amount + sales_product_details.overalldiscount_amount) FROM sales_product_details WHERE sales_product_details.sales_bill_id = sales_bills.sales_bill_id GROUP BY sales_product_details.sales_bill_id)  as totaldiscount"),DB::raw("(SELECT SUM(sales_product_details.mrp) FROM sales_product_details WHERE sales_product_details.sales_bill_id = sales_bills.sales_bill_id and product_type=2 GROUP BY sales_product_details.sales_bill_id)  as totalcharges"),DB::raw("(SELECT SUM(sales_product_details.igst_amount) FROM sales_product_details WHERE sales_product_details.sales_bill_id = sales_bills.sales_bill_id and product_type=2 GROUP BY sales_product_details.sales_bill_id)  as chargesgst"))
            ->with('reference')
            ->with('sales_bill_payment_detail')
            ->where('company_id',Auth::user()->company_id)
            ->whereRaw("STR_TO_DATE(sales_bills.bill_date,'%d-%m-%Y') between '$date' and '$date'")
            // ->whereRaw("Date(sales_bills.created_at) between '$date' and '$date'")
            // ->whereRaw("bill_date between '$sdate' and '$sdate'")
            ->where('deleted_at','=',NULL)
            ->where('is_active','=',1)
            ->orderBy('sales_bill_id', 'DESC');

            $scustom   =   collect();
            $sdata     =   $scustom->merge($squery->get());
            $sales     =   $squery->paginate(10);

          

            $rquery = return_bill::select("return_bills.*",DB::raw("(SELECT SUM(return_product_details.discount_amount + return_product_details.overalldiscount_amount) FROM return_product_details WHERE return_product_details.return_bill_id = return_bills.return_bill_id GROUP BY return_product_details.return_bill_id)  as totaldiscount"),DB::raw("(SELECT SUM(return_product_details.mrp) FROM return_product_details WHERE return_product_details.return_bill_id = return_bills.return_bill_id and product_type=2 GROUP BY return_product_details.return_bill_id)  as totalcharges"),DB::raw("(SELECT SUM(return_product_details.igst_amount) FROM return_product_details WHERE return_product_details.return_bill_id = return_bills.return_bill_id and product_type=2 GROUP BY return_product_details.return_bill_id)  as chargesgst"))
            ->with('reference')
            ->whereRaw("STR_TO_DATE(return_bills.bill_date,'%d-%m-%Y') between '$date' and '$date'")
            // ->whereRaw("Date(return_bills.created_at) between '$date' and '$date'")
            // ->whereRaw("bill_date between '$sdate' and '$sdate'")
            ->with('sales_bill')
            ->with('return_bill_payment')
            ->with('customer')
            ->where('company_id',Auth::user()->company_id)
            ->where('deleted_at','=',NULL)
            ->orderBy('return_bill_id', 'DESC');


            $rcustom      =  collect();
            $rdata        =  $rcustom->merge($rquery->get());
            $returnbill   =  $rquery->paginate(5);

                       
            $count = 0;
            $taxabletariff = 0;
            $totalcgst = 0;
            $totalsgst = 0;
            $totaligst = 0;
            $grandtotal = 0;

            foreach ($sdata as $totsales)
            {
                $count++;
                
                $halfchargesgst   =   $totsales->chargesgst / 2;
                
                $taxabletariff  +=   $totsales->sellingprice_after_discount + $totsales->totalcharges;

                if($tax_type==1)
                {
                    $totaligst       +=  $totsales->total_igst_amount + $totsales->chargesgst;
                }
                else
                {

                    if($totsales->state_id == $company_state)
                    {
                      $totalcgst       +=  $totsales->total_cgst_amount + $halfchargesgst;
                      $totalsgst       +=  $totsales->total_sgst_amount + $halfchargesgst; 
                    }
                    else
                    {
                      $totaligst       +=  $totsales->total_igst_amount + $totsales->chargesgst;  
                    }
                }

                                            
                $grandtotal     +=   $totsales->total_bill_amount;
            }


            $rtaxabletariff = 0;
            $rtotalcgst = 0;
            $rtotalsgst = 0;
            $rtotaligst = 0;
            $rgrandtotal = 0;

            foreach ($rdata as $rtotsales)
            {
               
                
                $rhalfchargesgst   =   $rtotsales->chargesgst / 2;
                
                $rtaxabletariff  +=   $rtotsales->sellingprice_after_discount + $rtotsales->totalcharges;

                if($tax_type==1)
                {
                    $rtotaligst       +=  $rtotsales->total_igst_amount + $rtotsales->chargesgst; 
                }
                else
                {

                    if($rtotsales->state_id == $company_state)
                    {
                      $rtotalcgst       +=  $rtotsales->total_cgst_amount + $rhalfchargesgst;
                      $rtotalsgst       +=  $rtotsales->total_sgst_amount + $rhalfchargesgst; 
                    }
                    else
                    {
                      $rtotaligst       +=  $rtotsales->total_igst_amount + $rtotsales->chargesgst;  
                    }
                }

                                            
                $rgrandtotal     +=   $rtotsales->total_bill_amount;
            }

            $todaytaxable   =   $taxabletariff - $rtaxabletariff;
            $todaycgst      =   $totalcgst - $rtotalcgst;
            $todaysgst      =   $totalsgst - $rtotalsgst;
            $todayigst      =   $totaligst - $rtotaligst;
            $todaygrand     =   $grandtotal - $rgrandtotal;

               $max_date  =  $sdata->max('bill_date');
               $min_date  =  $sdata->min('bill_date');
           

           
        return view('salesreport::view_bill',compact('sales','payment_methods','count','todaytaxable','todaycgst','todaysgst','todayigst','todaygrand','maxsales_id','minsales_id','company_state','returnbill','returnsales','rmaxsales_id','rminsales_id','tax_type','taxname','max_date','min_date'))->render();

        
    }
    

  public function view_bill_popup(Request $request)
  {

        if($request->ajax())
        {
            $payment_methods = payment_method::where('is_active','=','1')->where('payment_method_id','!=',9)->get();
             $state_id  =  company_profile::select('state_id','tax_type','tax_title','currency_title')->where('company_id',Auth::user()->company_id)->get();
            $company_state   = $state_id[0]['state_id'];
            $tax_type        = $state_id[0]['tax_type'];
            $tax_title       = $state_id[0]['tax_title'];
            $taxname         = $tax_type==1?$tax_title:'IGST';

            $sales = sales_bill::where('company_id',Auth::user()->company_id)
                ->where('deleted_at','=',NULL)
                ->where('sales_bill_id','=',$request->billno)
                ->select('*')
                ->with('sales_product_detail.product')
                ->with('sales_bill_payment_detail.payment_method')
                ->with('customer')
                ->with('customer_address_detail')
                ->with('company')
                ->with('state')
                ->get();

               $maxsales_id   =  sales_bill::max('sales_bill_id');
               $minsales_id   =  sales_bill::min('sales_bill_id');

              
                 return view('salesreport::view_bill_popup',compact('sales','maxsales_id','minsales_id','tax_type','taxname'));

         }
        
    }
  public function previous_invoice(Request $request)
  {

        if($request->ajax())
        {
            $billid   =  $request->billno;
            $payment_methods = payment_method::where('is_active','=','1')->where('payment_method_id','!=',9)->get();
             $state_id  =  company_profile::select('state_id','tax_type','tax_title','currency_title')->where('company_id',Auth::user()->company_id)->get();
            $company_state   = $state_id[0]['state_id'];
            $tax_type        = $state_id[0]['tax_type'];
            $tax_title       = $state_id[0]['tax_title'];
            $taxname         = $tax_type==1?$tax_title:'IGST';

            $sales = sales_bill::where('company_id',Auth::user()->company_id)
                ->where('deleted_at','=',NULL)
                ->where('sales_bill_id','<',$request->billno)
                ->select('*')
                ->with('sales_product_detail.product')
                ->with('sales_bill_payment_detail.payment_method')
                ->with('customer')
                ->with('customer_address_detail')
                ->with('company')
                ->with('state')            
                ->orderBy('sales_bill_id','DESC')
                ->take(1)
                ->get();

              $maxsales_id   =  sales_bill::max('sales_bill_id');
              $minsales_id   =  sales_bill::min('sales_bill_id');

          
              return view('salesreport::view_bill_popup',compact('sales','maxsales_id','minsales_id','tax_type','taxname'));

        }
        
    }
    public function next_invoice(Request $request)
   {

        if($request->ajax())
        {
            $billid   =  $request->billno;
            $payment_methods = payment_method::where('is_active','=','1')->where('payment_method_id','!=',9)->get();
             $state_id  =  company_profile::select('state_id','tax_type','tax_title','currency_title')->where('company_id',Auth::user()->company_id)->get();
            $company_state   = $state_id[0]['state_id'];
            $tax_type        = $state_id[0]['tax_type'];
            $tax_title       = $state_id[0]['tax_title'];
            $taxname         = $tax_type==1?$tax_title:'IGST';

            $sales = sales_bill::where('company_id',Auth::user()->company_id)
                ->where('deleted_at','=',NULL)
                ->where('sales_bill_id','>',$request->billno)
                ->select('*')
                ->with('sales_product_detail.product')
                ->with('sales_bill_payment_detail.payment_method')
                ->with('customer')
                ->with('customer_address_detail')
                ->with('company')
                ->with('state')            
                ->orderBy('sales_bill_id','ASC')
                ->take(1)
                ->get();

               
                $maxsales_id   =  sales_bill::max('sales_bill_id');
                $minsales_id   =  sales_bill::min('sales_bill_id');

          
             return view('salesreport::view_bill_popup',compact('sales','maxsales_id','minsales_id','tax_type','taxname'));
            

        }
        
    }
     public function viewbillcustomer_search(Request $request)
    {

         $json = [];
         $result = customer::select('customer_name','customer_mobile')
             ->where('company_id',Auth::user()->company_id)
             ->where('deleted_at','=',NULL)
             ->where('customer_name', 'LIKE', "%$request->search_val%")
             ->orwhere('customer_mobile', 'LIKE', "%$request->search_val%")
             ->get();

       
            foreach($result as $customerkey=>$customervalue){


                  $json[] = $customervalue['customer_name'].'_'.$customervalue['customer_mobile'];
                  
            }
        
        
        return json_encode($json);
       
        //return json_encode(array("Success"=>"True","Data"=>$result) );
    }

    public function customerbill_search(Request $request)
    {


        $result = customer::select('customer_name AS result','customer_mobile')
             ->where('company_id',Auth::user()->company_id)
             ->where('deleted_at','=',NULL)
             ->where('customer_name', 'LIKE', "%$request->search_val%")
             ->orwhere('customer_mobile', 'LIKE', "%$request->search_val%")
             ->get();

        

        return json_encode(array("Success"=>"True","Data"=>$result) );
    }
    public function reference_search(Request $request)
    {

        if($request->search_val !='')
        {

            $json = [];
            $result = reference::select('reference_name')
                ->where('reference_name', 'LIKE', "%$request->search_val%")
                ->where('company_id',Auth::user()->company_id)->get();

           
           

            if(!empty($result))
            {
           
                foreach($result as $billkey=>$billvalue){


                      $json[] = $billvalue['reference_name'];
                      
                }
            }
           
            return json_encode($json);
        }
        else
        {
          $json = [];
          return json_encode($json);
        }
       
        
    }

   
    function datewise_billdetail(Request $request)
    {
        if($request->ajax())
        {
            
            $payment_methods =      payment_method::where('is_active','=','1')->where('payment_method_id','!=',9)->get();
            $state_id  =  company_profile::select('state_id','tax_type','tax_title','currency_title')->where('company_id',Auth::user()->company_id)->get();
            $company_state   = $state_id[0]['state_id'];
            $tax_type        = $state_id[0]['tax_type'];
            $tax_title       = $state_id[0]['tax_title'];
            $taxname         = $tax_type==1?$tax_title:'IGST';
            $data            =      $request->all();
           

            // $sort_by = $data['sortby'];
            // $sort_type = $data['sorttype'];
            $query = isset($data['query']) ? $data['query']  : '';
            // print_r($query);


         $squery           =      sales_bill::select("sales_bills.*",DB::raw("(SELECT SUM(sales_product_details.discount_amount + sales_product_details.overalldiscount_amount) FROM sales_product_details WHERE sales_product_details.sales_bill_id = sales_bills.sales_bill_id GROUP BY sales_product_details.sales_bill_id)  as totaldiscount"),DB::raw("(SELECT SUM(sales_product_details.mrp) FROM sales_product_details WHERE sales_product_details.sales_bill_id = sales_bills.sales_bill_id and product_type=2 GROUP BY sales_product_details.sales_bill_id)  as totalcharges"),DB::raw("(SELECT SUM(sales_product_details.igst_amount) FROM sales_product_details WHERE sales_product_details.sales_bill_id = sales_bills.sales_bill_id and product_type=2 GROUP BY sales_product_details.sales_bill_id)  as chargesgst"))->with('reference')->where('company_id',Auth::user()->company_id)->where('deleted_at','=',NULL)->where('is_active','=',1);


            $rquery = return_bill::select("return_bills.*",DB::raw("(SELECT SUM(return_product_details.discount_amount + return_product_details.overalldiscount_amount) FROM return_product_details WHERE return_product_details.return_bill_id = return_bills.return_bill_id GROUP BY return_product_details.return_bill_id)  as totaldiscount"),DB::raw("(SELECT SUM(return_product_details.mrp) FROM return_product_details WHERE return_product_details.return_bill_id = return_bills.return_bill_id and product_type=2 GROUP BY return_product_details.return_bill_id)  as totalcharges"),DB::raw("(SELECT SUM(return_product_details.igst_amount) FROM return_product_details WHERE return_product_details.return_bill_id = return_bills.return_bill_id and product_type=2 GROUP BY return_product_details.return_bill_id)  as chargesgst"))->with('reference')->where('company_id',Auth::user()->company_id)->where('deleted_by','=',NULL);

            if(isset($query) && $query != '' && $query['customerid'] != '')
            {

                if(strpos($query['customerid'], '_') !== false)
                {
                    $cusname   =   explode('_',$query['customerid']);
                    $cus_name   =  $cusname[0];
                    $cus_mobile  =  $cusname[1];
                }
                else
                {
                    $cus_name   =   $query['customerid'];
                    $cus_mobile =   $query['customerid'];
                }

                 $result = customer::select('customer_id')
                 ->where('company_id',Auth::user()->company_id)
                 ->where('deleted_at','=',NULL)
                 ->where('customer_name', 'LIKE', "%$cus_name%")
                 ->orwhere('customer_mobile', 'LIKE', "%$cus_mobile%")
                 ->get();

                 $squery->whereIn('customer_id',$result);
                 $rquery->whereIn('customer_id',$result);
            }
            if(isset($query) && $query != '' && $query['reference_name'] != '')
            {
                $ref_name =  $query['reference_name'];
                 $rresult = reference::select('reference_id')
                 ->where('company_id',Auth::user()->company_id)
                 ->where('deleted_at','=',NULL)
                 ->where('reference_name', 'LIKE', "%$ref_name%")
                 ->get();

                 $squery->whereIn('reference_id',$rresult);
                 $rquery->whereIn('reference_id',$rresult);
            }
            if(isset($query) && $query != '' && $query['billno'] != '')
            {
                 $squery->where('bill_no', 'like', '%'.$query['billno'].'%');

                 $tbill_no  =  sales_bill::select('sales_bill_id')->where('bill_no', 'like', '%'.$query['billno'].'%')->where('company_id',Auth::user()->company_id)->get();
                $rquery->whereIn('sales_bill_id', $tbill_no);
            }
            if(isset($query) && $query != '' && $query['from_date'] != '' && $query['to_date'] != '')
            {
                
                 $rstart           =      date("Y-m-d",strtotime($query['from_date']));
                 $rend             =      date("Y-m-d",strtotime($query['to_date']));
                 $squery->whereRaw("STR_TO_DATE(sales_bills.bill_date,'%d-%m-%Y') between '$rstart' and '$rend'");
                 $rquery->whereRaw("STR_TO_DATE(return_bills.bill_date,'%d-%m-%Y') between '$rstart' and '$rend'");
                
            }
            
            // $squery->whereRaw("Date(sales_bills.created_at) between '$rstart' and '$rend'");
            // $rquery->whereRaw("Date(return_bills.created_at) between '$rstart' and '$rend'");

           
           


            $scustom   =   collect();
            $sdata     =   $scustom->merge($squery->get());

            $sales = $squery->orderBy('sales_bill_id', 'DESC')
                   ->paginate(10);

          

            $rcustom   =   collect();
            $rdata     =   $rcustom->merge($rquery->get());

            $returnbill  =  $rquery->with('return_bill_payment')->orderBy('return_bill_id', 'DESC')->paginate(10); 



            $count = 0;
            $taxabletariff = 0;
            $totalcgst = 0;
            $totalsgst = 0;
            $totaligst = 0;
            $grandtotal = 0;

            foreach ($sdata as $totsales)
            {
                $count++;
                
                $halfchargesgst   =   $totsales->chargesgst / 2;
                
                $taxabletariff  +=   $totsales->sellingprice_after_discount + $totsales->totalcharges;

                if($tax_type==1)
                {
                    $totaligst       +=  $totsales->total_igst_amount + $totsales->chargesgst;  
                }
                else
                {

                    if($totsales->state_id == $company_state)
                    {
                      $totalcgst       +=  $totsales->total_cgst_amount + $halfchargesgst;
                      $totalsgst       +=  $totsales->total_sgst_amount + $halfchargesgst; 
                    }
                    else
                    {
                      $totaligst       +=  $totsales->total_igst_amount + $totsales->chargesgst;  
                    }
                }

                                            
                $grandtotal     +=   $totsales->total_bill_amount;
            }

            $rtaxabletariff = 0;
            $rtotalcgst = 0;
            $rtotalsgst = 0;
            $rtotaligst = 0;
            $rgrandtotal = 0;


            foreach ($rdata as $rtotsales)
            {
               
                
                $rhalfchargesgst   =   $rtotsales->chargesgst / 2;
                
                $rtaxabletariff  +=   $rtotsales->sellingprice_after_discount + $rtotsales->totalcharges;

                 if($tax_type==1)
                {
                    $rtotaligst         +=  $rtotsales->total_igst_amount + $rtotsales->chargesgst;
                }
                else
                {

                    if($rtotsales->state_id == $company_state)
                    {
                      $rtotalcgst       +=  $rtotsales->total_cgst_amount + $rhalfchargesgst;
                      $rtotalsgst       +=  $rtotsales->total_sgst_amount + $rhalfchargesgst; 
                    }
                    else
                    {
                      $rtotaligst       +=  $rtotsales->total_igst_amount + $rtotsales->chargesgst;  
                    }   
                }

                                            
                $rgrandtotal     +=   $rtotsales->total_bill_amount;
            }

            $todaytaxable   =   $taxabletariff - $rtaxabletariff;
            $todaycgst      =   $totalcgst - $rtotalcgst;
            $todaysgst      =   $totalsgst - $rtotalsgst;
            $todayigst      =   $totaligst - $rtotaligst;
            $todaygrand     =   $grandtotal - $rgrandtotal;

            if($query['from_date']=='')
            {
                     $max_date  =  $sdata->max('bill_date');
                     $min_date  =  $sdata->min('bill_date');
            }
            else
            {   
                     $max_date  =  $query['from_date'];
                     $min_date  =  $query['to_date'];
            }
          
           
          
        
              return view('salesreport::view_bill_data',compact('sales','payment_methods','count','todaytaxable','todaycgst','todaysgst','todaygrand','company_state','todayigst','returnbill','tax_type','taxname','max_date','min_date'))->render();
        }
            
                
    }

   public function view_returnbill_popup(Request $request)
  {

        if($request->ajax())
        {
            $payment_methods = payment_method::where('is_active','=','1')->where('payment_method_id','!=',9)->get();
             $state_id  =  company_profile::select('state_id','tax_type','tax_title','currency_title')->where('company_id',Auth::user()->company_id)->get();
            $company_state   = $state_id[0]['state_id'];
            $tax_type        = $state_id[0]['tax_type'];
            $tax_title       = $state_id[0]['tax_title'];
            $taxname         = $tax_type==1?$tax_title:'IGST';

            $returnsales = return_bill::where('company_id',Auth::user()->company_id)
                ->where('deleted_at','=',NULL)
                ->where('return_bill_id','=',$request->billno)
                ->select('*')
                ->with('sales_bill')
                ->with('return_product_detail.product')
                ->with('return_bill_payment.payment_method','return_bill_payment.customer_creditnote')
                ->with('customer')
                ->with('customer_address_detail')
                ->with('company')
                ->with('state')
                ->get();

               $rmaxsales_id   =  return_bill::max('return_bill_id');
               $rminsales_id   =  return_bill::min('return_bill_id');

              
                  return view('salesreport::view_returnbill_popup',compact('returnsales','rmaxsales_id','rminsales_id','tax_type','taxname'));

         }
        
    }
  public function rprevious_invoice(Request $request)
  {

        if($request->ajax())
        {
            $billid   =  $request->billno;
            $payment_methods = payment_method::where('is_active','=','1')->where('payment_method_id','!=',9)->get();
            $state_id  =  company_profile::select('state_id','tax_type','tax_title','currency_title')->where('company_id',Auth::user()->company_id)->get();
            $company_state   = $state_id[0]['state_id'];
            $tax_type        = $state_id[0]['tax_type'];
            $tax_title       = $state_id[0]['tax_title'];
            $taxname         = $tax_type==1?$tax_title:'IGST';

            $returnsales = return_bill::where('company_id',Auth::user()->company_id)
                ->where('deleted_at','=',NULL)
                ->where('return_bill_id','<',$request->billno)
                ->select('*')
                ->with('sales_bill')
                ->with('return_product_detail.product')
                ->with('return_bill_payment.payment_method','return_bill_payment.customer_creditnote')
                ->with('customer')
                ->with('customer_address_detail')
                ->with('company')
                ->with('state')        
                ->orderBy('return_bill_id','DESC')
                ->take(1)
                ->get();
//dd($returnsales);
//exit;
              $rmaxsales_id   =  return_bill::max('return_bill_id');
              $rminsales_id   =  return_bill::min('return_bill_id');

          
              return view('salesreport::view_returnbill_popup',compact('returnsales','rmaxsales_id','rminsales_id','tax_type','taxname'));

        }
        
    }
    public function rnext_invoice(Request $request)
   {

        if($request->ajax())
        {
            $billid   =  $request->billno;
            $payment_methods = payment_method::where('is_active','=','1')->where('payment_method_id','!=',9)->get();
            $state_id  =  company_profile::select('state_id','tax_type','tax_title','currency_title')->where('company_id',Auth::user()->company_id)->get();
            $company_state   = $state_id[0]['state_id'];
            $tax_type        = $state_id[0]['tax_type'];
            $tax_title       = $state_id[0]['tax_title'];
            $taxname         = $tax_type==1?$tax_title:'IGST';

            $returnsales = return_bill::where('company_id',Auth::user()->company_id)
                ->where('deleted_at','=',NULL)
                ->where('return_bill_id','>',$request->billno)
                ->select('*')
                ->with('sales_bill')
                ->with('return_product_detail.product')
                ->with('return_bill_payment.payment_method','return_bill_payment.customer_creditnote')
                ->with('customer')
                ->with('customer_address_detail')
                ->with('company')
                ->with('state')          
                ->orderBy('return_bill_id','ASC')
                ->take(1)
                ->get();

               
                $rmaxsales_id   =  return_bill::max('return_bill_id');
                $rminsales_id   =  return_bill::min('return_bill_id');

          
              return view('salesreport::view_returnbill_popup',compact('returnsales','rmaxsales_id','rminsales_id','tax_type','taxname'));
            

        }
        
    }

    public function exportbill_details(Request $request)
    {

            $payment_methods =      payment_method::where('is_active','=','1')->where('payment_method_id','!=',9)->get();
            $state_id  =  company_profile::select('state_id','tax_type','tax_title','currency_title')->where('company_id',Auth::user()->company_id)->get();
            $company_state   = $state_id[0]['state_id'];
            $tax_type        = $state_id[0]['tax_type'];
            $tax_title       = $state_id[0]['tax_title'];
            $taxname         = $tax_type==1?$tax_title:'IGST';
            $data            =      $request->all();           
            $from_date       =      $data['from_date'];
            $to_date         =      $data['to_date'];
            $billno          =      $data['bill_no'];
            $customerid      =      $data['customerid'];
            $reference_name  =      $data['reference_name'];

            if(strpos($customerid, '_') !== false)
            {
                $cusname   =   explode('_',$customerid);
                $cus_name   =  $cusname[0];
                $cus_mobile  =  $cusname[1];
            }
            else
            {
                $cus_name   =   $customerid;
                $cus_mobile =   $customerid;
            }

            
            $result = customer::select('customer_id')
             ->where('company_id',Auth::user()->company_id)
             ->where('deleted_at','=',NULL)
             ->where('customer_name', 'LIKE', "%$cus_name%")
             ->orwhere('customer_mobile', 'LIKE', "%$cus_mobile%")
             ->get();
            $rresult = reference::select('reference_id')
             ->where('company_id',Auth::user()->company_id)
             ->where('deleted_at','=',NULL)
             ->where('reference_name', 'LIKE', "%$reference_name%")
             ->get();
           
            $start           =      $from_date;
            $end             =      $to_date;

            $rstart           =      date("Y-m-d",strtotime($from_date));
            $rend             =      date("Y-m-d",strtotime($to_date));

            $query           =      sales_bill::select("sales_bills.*",DB::raw("(SELECT SUM(sales_product_details.discount_amount + sales_product_details.overalldiscount_amount) FROM sales_product_details WHERE sales_product_details.sales_bill_id = sales_bills.sales_bill_id GROUP BY sales_product_details.sales_bill_id)  as totaldiscount"),DB::raw("(SELECT SUM(sales_product_details.mrp) FROM sales_product_details WHERE sales_product_details.sales_bill_id = sales_bills.sales_bill_id and product_type=2 GROUP BY sales_product_details.sales_bill_id)  as totalcharges"),DB::raw("(SELECT SUM(sales_product_details.igst_amount) FROM sales_product_details WHERE sales_product_details.sales_bill_id = sales_bills.sales_bill_id and product_type=2 GROUP BY sales_product_details.sales_bill_id)  as chargesgst"))->with('reference');

            if($from_date!='' && $to_date!='')
            {
                $query->whereRaw("STR_TO_DATE(sales_bills.bill_date,'%d-%m-%Y') between '$rstart' and '$rend'");
                // $query->where('bill_date','>=',$start)->where('bill_date','<=',$end);
            }
            if($billno!='')
            {
                $query->where('bill_no', 'like', '%'.$billno.'%');

            }
            if($customerid!='')
            {
                $query->whereIn('customer_id',$result);
            }
            if($reference_name != '')
            {
                 $query->whereIn('reference_id',$rresult);
            }

            $sales = $query->with('sales_bill_payment_detail')->where('deleted_by','=',NULL)->orderBy('sales_bill_id','DESC')
                   ->get();


            $rquery = return_bill::select("return_bills.*",DB::raw("(SELECT SUM(return_product_details.discount_amount + return_product_details.overalldiscount_amount) FROM return_product_details WHERE return_product_details.return_bill_id = return_bills.return_bill_id GROUP BY return_product_details.return_bill_id)  as totaldiscount"),DB::raw("(SELECT SUM(return_product_details.mrp) FROM return_product_details WHERE return_product_details.return_bill_id = return_bills.return_bill_id and product_type=2 GROUP BY return_product_details.return_bill_id)  as totalcharges"),DB::raw("(SELECT SUM(return_product_details.igst_amount) FROM return_product_details WHERE return_product_details.return_bill_id = return_bills.return_bill_id and product_type=2 GROUP BY return_product_details.return_bill_id)  as chargesgst"))->with('reference');
            
            

            if($from_date!='' && $to_date!='')
            {
                $rquery->whereRaw("STR_TO_DATE(return_bills.bill_date,'%d-%m-%Y') between '$rstart' and '$rend'");
                // $rquery->whereRaw("Date(return_bills.created_at) between '$rstart' and '$rend'");
            }
             if($billno!='')
            {
                $tbill_no  =  sales_bill::select('sales_bill_id')->where('bill_no', 'like', '%'.$billno.'%')->where('company_id',Auth::user()->company_id)->get();
               $rquery->whereIn('sales_bill_id', $tbill_no);
            }
            if($reference_name != '')
            {
                $rquery->whereIn('reference_id',$rresult);
            }
            $returnbill  =  $rquery->with('return_bill_payment')->where('company_id',Auth::user()->company_id)->where('deleted_at','=',NULL)->orderBy('return_bill_id', 'DESC')->get(); 

             $overallsales   =  [];
             $header       = [];
             $header[]  =  'Bill No.';  
             $header[]  =  'Bill Date';  
             $header[]  =  'Customer'; 
             $header[]  =  'TotalQty'; 
             $header[]  =  'SellingPrice';
             $header[]  =  'Discount Amount';
             $header[]  =  'Taxable Amount';
             if($tax_type==1)
             {
                 $header[]  =  $taxname.' Amount';
             }
             else
             {
                 $header[]  =  'CGST Amount';
                 $header[]  =  'SGST Amount'; 
                 $header[]  =  'IGST Amount';
                
             }
             
             $header[]  =  'Total Amount';
             $header[]  =  'Cash'; 
             $header[]  =  'Card';
             $header[]  =  'Cheque';
             $header[]  =  'Wallet';
             $header[]  =  'Outstanding';
             $header[]  =  'Net Banking';
             $header[]  =  'Credit Note';
             $header[]  =  'Reference';
             $header[]  =  'Note for Internal Use';
             $header[]  =  'Note for Customer';

            

            $overallsales['sales']        =  $sales;
            $overallsales['returnbill']   =  $returnbill;



        $excel = Excel::download(new viewbill_export($overallsales, $header), "ViewBill-Export.xlsx");
        return $excel;
      

    }

    public function bill_delete(request $request)
    {
        $userId = Auth::User()->user_id;

     try {
        DB::beginTransaction();    


                    $creditnoteid      =  sales_bill_payment_detail::select('customer_creditnote_id','total_bill_amount')
                                                                ->where('sales_bill_id',$request->deleted_id)
                                                                ->where('payment_method_id',8)
                                                                ->where('deleted_at',NULL)->first();
                     if($creditnoteid!='')
                     {
                        customer_creditnote::where('customer_creditnote_id',$creditnoteid['customer_creditnote_id'])->update(array(
                                  'balance_amount' => DB::raw('balance_amount + '.$creditnoteid['total_bill_amount']))); 
                        creditnote_payment::where('sales_bill_id',$request->deleted_id)
                                            ->where('customer_creditnote_id',$creditnoteid['customer_creditnote_id'])
                                            ->update([
                                                 'deleted_by' => $userId,
                                                'deleted_at' => date('Y-m-d H:i:s')
                                                ]);
                     }                                           
                     $creditid      =  sales_bill_payment_detail::select('total_bill_amount')
                                                                ->where('sales_bill_id',$request->deleted_id)
                                                                ->where('payment_method_id',6)
                                                                ->where('deleted_at',NULL)->first();

                      if($creditid!='')
                     {

                        $creditaccid    =  customer_creditaccount::select('customer_creditaccount_id')
                                                                    ->where('sales_bill_id',$request->deleted_id)
                                                                    ->where('deleted_at',NULL)->first();

                        $creditrecpid    =  customer_creditreceipt_detail::where('customer_creditaccount_id',$creditaccid->customer_creditaccount_id)->get();

                        //print_r($creditaccid->customer_creditaccount_id);
                        //print_r($creditrecpid);

                        //exit;
                        if(sizeof($creditrecpid)!=0)
                        {
                                return json_encode(array("Success"=>"False","Message"=>"Outstanding Amount against this bill has already been received So can't this Bill Now!"));
                        }
                        else
                        {
                             customer_creditaccount::where('sales_bill_id',$request->deleted_id)
                                            ->update([
                                                 'deleted_by' => $userId,
                                                'deleted_at' => date('Y-m-d H:i:s')
                                                ]);
                        }
                       
                     }     
                    
                    
                    $bill_delete =  sales_bill::where('sales_bill_id', $request->deleted_id)
                        ->update([
                     'deleted_by' => $userId,
                    'deleted_at' => date('Y-m-d H:i:s')
                    ]);
                    

                    $billproduct_data = sales_product_detail::select('*')
                    ->where('sales_bill_id',$request->deleted_id)
                    ->get();

                    foreach($billproduct_data as $billdatakey=>$billdatavalue)
                    {
                       if($billdatavalue['inwardids'] !='' || $billdatavalue['inwardids'] !=null)
                        {

                           $inwardids  = explode(',' ,substr($billdatavalue['inwardids'],0,-1));
                           $inwardqtys = explode(',' ,substr($billdatavalue['inwardqtys'],0,-1));
                           

                            foreach($inwardids as $inidkey=>$inids)
                            {
                                inward_product_detail::where('company_id',Auth::user()->company_id)
                                                  ->where('inward_product_detail_id',$inids)
                                                  ->update(array(
                                                      'modified_by' => Auth::User()->user_id,
                                                      'updated_at' => date('Y-m-d H:i:s'),
                                                      'pending_return_qty' => DB::raw('pending_return_qty + '.$inwardqtys[$inidkey])
                                                      ));        
                            } 
                           
                        }

                        $productqty    =  price_master::select('product_qty')
                                ->where('price_master_id',$billdatavalue['price_master_id'])
                                ->where('company_id',Auth::user()->company_id)
                                ->get();

                        $updateqty   =    $productqty[0]['product_qty'] +  $billdatavalue['qty'];


                        price_master::where('price_master_id',$billdatavalue['price_master_id'])->update(array(
                                  'product_qty' => $updateqty)); 

                    }  
                    $billdata_delete =  sales_product_detail::where('sales_bill_id', $request->deleted_id)
                        ->update([
                     'deleted_by' => $userId,
                    'deleted_at' => date('Y-m-d H:i:s')
                    ]);
                   
                    $billpayment_delete =  sales_bill_payment_detail::where('sales_bill_id', $request->deleted_id)
                        ->update([
                     'deleted_by' => $userId,
                    'deleted_at' => date('Y-m-d H:i:s')
                    ]);

             DB::commit();
      } catch (\Illuminate\Database\QueryException $e)
      {
          DB::rollback();
          return json_encode(array("Success"=>"False","Message"=>$e->getMessage()));
      }

       
        if($billdata_delete)
        {
            return json_encode(array("Success"=>"True","Message"=>"Bill has been successfully deleted.!"));
        }
        else
        {
            return json_encode(array("Success"=>"False","Message"=>"Something Went Wrong!"));
        }

    }

    public function sales_check(Request $request)
    {
        $sales_excel_data = $request->all();

        if(isset($sales_excel_data) && $sales_excel_data != '')
        {
            $error = 0;
            foreach ($sales_excel_data AS $key=>$value) {

                $validate_value['state_name'] = $value['State'];
                $validate_value['product_code'] = $value['Product Code'];
                $validate_value['reference_name'] = $value['Portal'];

                
                if ($value['Product Code'] != '')
                {
                    if (!product::where('product_code', $value['Product Code'])->exists())
                    {
                        $error = 1;
                        return json_encode(array("Success" => "False", "Message" => "Product Code " . $value['Product Code'] . " Not Found!"));
                        exit;
                    }
                }                
                if ($value['State'] != '')
                {
                    if (!state::where('state_name', $value['State'])->exists())
                    {
                        $error = 1;
                        return json_encode(array("Success" => "False", "Message" => "State Name " . $value['State'] . " Not Found!"));
                        exit;
                    }
                }
                if ($value['Portal'] != '')
                {
                    if (!reference::where('reference_name', $value['Portal'])->exists())
                    {
                        $error = 1;
                        return json_encode(array("Success" => "False", "Message" => "Portal Name " . $value['Portal'] . " Not Found!"));
                        exit;
                    }
                }
            }

          if($error == 0)
          {
              $userId = Auth::User()->user_id;
              $company_id = Auth::User()->company_id;
              $created_by = $userId;
              try{


                  $state_id = company_profile::select('state_id')
                      ->where('company_id',Auth::user()->company_id)->get();

                  foreach ($sales_excel_data AS $key=>$value)
                  {
                     $day   =  date("d",strtotime($value['Date']));
                     $month =  date("m", strtotime($value['Month']));
                     $year  =  date("Y", strtotime($value['Year']));

                     $invoiceddate  =  $day.'-'.$month.'-'.$year;

                    
                    
                ///code to check if order no already exist

                      $sales_id = sales_bill::select('sales_bill_id')
                                  ->where('company_id',Auth::user()->company_id)
                                  ->where('order_no',$value['Order ID/PO NO'])
                                  ->first();

                        $productdetail     =    array();
        //if exist then update amount in same order billno.    
                        if($sales_id!='')
                        {
                            $sales_bill_id = $sales_id->sales_bill_id;
                            $productid = product::select('product_id','product_system_barcode')
                                       ->where('product_code',$value['Product Code'])
                                       ->where('company_id',Auth::user()->company_id)
                                       ->first();
                             $priceid  = price_master::select('price_master_id','selling_gst_percent')
                                         ->where('product_id',$productid['product_id'])
                                         ->where('company_id',Auth::user()->company_id)
                                         ->where('product_qty','>',0)
                                         ->orderBy('price_master_id','ASC')
                                         ->first();  

                            if($priceid!='')
                            {            

                             $mrp             =    $value['Price (Rs)']  / $value['Order Qty'];
                             $gstamt          =    ($mrp/($priceid['selling_gst_percent']+100)) * $priceid['selling_gst_percent'];
                             $gstamount       =     $gstamt * $value['Order Qty'];
                             $halfgstamount   =     $gstamount /2;
                             $halfgstper      =     $priceid['selling_gst_percent'] /2;

                             $sellingprice    =     $mrp - $gstamt;

                           
                        
                              $productdetail['bill_date']                            =    $invoiceddate;
                              $productdetail['product_system_barcode']               =    $productid['product_system_barcode'];
                              $productdetail['product_id']                           =    $productid['product_id'];
                              $productdetail['price_master_id']                      =    $priceid['price_master_id'];
                              $productdetail['qty']                                  =    $value['Order Qty'];
                              $productdetail['mrp']                                  =    $mrp;
                              $productdetail['sellingprice_before_discount']         =    $sellingprice;
                              $productdetail['discount_percent']                     =    0;
                              $productdetail['discount_amount']                      =    0;
                              $productdetail['sellingprice_after_discount']          =    $sellingprice;
                              $productdetail['overalldiscount_percent']              =    0;
                              $productdetail['overalldiscount_amount']               =    0;
                              $productdetail['sellingprice_afteroverall_discount']   =    $sellingprice;
                              $productdetail['cgst_percent']                         =    $halfgstper;
                              $productdetail['cgst_amount']                          =    $halfgstamount;
                              $productdetail['sgst_percent']                         =    $halfgstper;
                              $productdetail['sgst_amount']                          =    $halfgstamount;
                              $productdetail['igst_percent']                         =    $priceid['selling_gst_percent'];
                              $productdetail['igst_amount']                          =    $gstamount;
                              $productdetail['total_amount']                         =    $value['Price (Rs)'];
                              $productdetail['product_type']                         =     1;
                              $productdetail['created_by']                           =     Auth::User()->user_id;

                              price_master::where('price_master_id',$priceid['price_master_id'])->update(array(
                              'modified_by' => Auth::User()->user_id,
                              'updated_at' => date('Y-m-d H:i:s'),
                              'product_qty' => DB::raw('product_qty - '.$value['Order Qty'])
                              ));    

                                   /////FIFO logic

                                           $ccount    =   0;  
                                           $icount    =   0;
                                           $pcount    =   0;
                                           $done      =   0;
                                           $firstout  =   0;
                                           $restqty   =   $value['Order Qty']; 
                                           $inwardids    =  '';
                                           $inwardqtys   =  '';           

                                      if($value['Order Qty']>0)
                                      {
                                         

                                           $qquery    =         inward_product_detail::select('inward_product_detail_id','pending_return_qty')
                                                                ->where('product_id',$productid['product_id'])
                                                                ->where('company_id',Auth::user()->company_id)
                                                                ->where('pending_return_qty','!=',0);

                                          $inwarddetail  =  $qquery->where('deleted_at','=',NULL)->orderBy('inward_product_detail_id','ASC')->get();

                                     
                                                      
                                           foreach($inwarddetail as $inwarddata)
                                           {
                                              //echo $inwarddata['pending_return_qty'];
                                                if($inwarddata['pending_return_qty'] >= $restqty && $firstout==0)
                                                {  
                                                      if($done == 0)
                                                      {

                                                        //echo 'hello';

                                                              $inwardids    .=   $inwarddata['inward_product_detail_id'].',';
                                                              $inwardqtys   .=   $restqty.',';
                                                          
                                                              inward_product_detail::where('company_id',Auth::user()->company_id)
                                                              ->where('inward_product_detail_id',$inwarddata['inward_product_detail_id'])
                                                              ->update(array(
                                                                  'modified_by' => Auth::User()->user_id,
                                                                  'updated_at' => date('Y-m-d H:i:s'),
                                                                  'pending_return_qty' => DB::raw('pending_return_qty - '.$value['Order Qty'])
                                                                  ));
                                                              $pcount++;
                                                              $done++;
                                                     }
                                               }
                                               else
                                               {
                                                  if($pcount==0 && $done == 0 && $icount==0)
                                                  {
                                                      
                                                     
                                                      if($restqty  > $inwarddata['pending_return_qty'])
                                                      {
                                                        //echo 'bbb';
                                                        //echo $restqty;
                                                          $inwardids    .=   $inwarddata['inward_product_detail_id'].',';
                                                          $inwardqtys   .=   $inwarddata['pending_return_qty'].',';
                                                          $ccount       =   $restqty  - $inwarddata['pending_return_qty'];
                                                          inward_product_detail::where('company_id',Auth::user()->company_id)
                                                          ->where('inward_product_detail_id',$inwarddata['inward_product_detail_id'])
                                                          ->update(array(
                                                              'modified_by' => Auth::User()->user_id,
                                                              'updated_at' => date('Y-m-d H:i:s'),
                                                              'pending_return_qty' => DB::raw('pending_return_qty - '.$inwarddata['pending_return_qty'])
                                                              ));
                                                      }
                                                      else
                                                      {
                                                        //echo 'ccc';
                                                        //echo $restqty;
                                                          $inwardids    .=   $inwarddata['inward_product_detail_id'].',';
                                                          $inwardqtys   .=   $restqty.',';
                                                          $ccount   =   $restqty  - $inwarddata['pending_return_qty'];
                                                          inward_product_detail::where('company_id',Auth::user()->company_id)
                                                          ->where('inward_product_detail_id',$inwarddata['inward_product_detail_id'])
                                                          ->update(array(
                                                              'modified_by' => Auth::User()->user_id,
                                                              'updated_at' => date('Y-m-d H:i:s'),
                                                              'pending_return_qty' => DB::raw('pending_return_qty - '.$restqty)
                                                              ));
                                                      }


                                                       if($ccount > 0)
                                                        {
                                                           $firstout++;
                                                           // echo $pcount;
                                                           // echo $done;
                                                           // echo $icount;
                                                           $restqty   =   $restqty  - $inwarddata['pending_return_qty'];
                                                          // echo $restqty;

                                                           
                                                        }
                                                        if($ccount <= 0)
                                                        {
                                                          //echo 'no';
                                                          $firstout++;
                                                           $icount++;
                                                             
                                                        }
                                                       
                                                  }
                                               }

                                           }    
                                       }        

                                    
                                    if($inwardids!='')
                                    {
                                      $productdetail['inwardids']                          =    $inwardids;
                                      $productdetail['inwardqtys']                         =    $inwardqtys;
                                    }   
                                    else
                                    {  
                                      $productdetail['inwardids']                          =    NULL;
                                      $productdetail['inwardqtys']                         =    NULL;
                                    }


                ///end of FIFO Logic


                              $billproductdetail = sales_product_detail::updateOrCreate(
                               ['sales_bill_id' => $sales_bill_id,
                                'company_id'=>$company_id,'sales_products_detail_id'=>'',],
                               $productdetail); 

                           $sales =  sales_bill::where('sales_bill_id',$sales_bill_id)->update(array(
                              'modified_by' => Auth::User()->user_id,
                              'updated_at' => date('Y-m-d H:i:s'),
                              'total_qty' => DB::raw('total_qty + '.$value['Order Qty']),
                              'sellingprice_before_discount' => DB::raw('sellingprice_before_discount + '.$sellingprice),
                              'discount_percent'=>0,
                              'discount_amount'=>0,
                              'productwise_discounttotal'=>0,
                              'sellingprice_after_discount' => DB::raw('sellingprice_after_discount + '.$sellingprice),
                              'totalbillamount_before_discount' => DB::raw('totalbillamount_before_discount + '.$sellingprice),
                              'total_igst_amount' => DB::raw('total_igst_amount + '.$gstamount),
                              'total_cgst_amount' => DB::raw('total_cgst_amount + '.$halfgstamount),
                              'total_sgst_amount' => DB::raw('total_sgst_amount + '.$halfgstamount),
                              'gross_total' => DB::raw('gross_total + '.$value['Price (Rs)']),
                              'shipping_charges'=>0,
                              'total_bill_amount'=>DB::raw('total_bill_amount + '.$value['Price (Rs)'])
                              )); 

                            sales_bill_payment_detail::where('sales_bill_id',$sales_bill_id)->update(array(
                              'modified_by' => Auth::User()->user_id,
                              'updated_at' => date('Y-m-d H:i:s'),
                              'total_bill_amount' => DB::raw('total_bill_amount + '.$value['Price (Rs)'])
                              )); 
                              customer_creditaccount::where('sales_bill_id',$sales_bill_id)->update(array(
                              'modified_by' => Auth::User()->user_id,
                              'updated_at' => date('Y-m-d H:i:s'),
                              'credit_amount' => DB::raw('credit_amount + '.$value['Price (Rs)']),
                              'balance_amount' => DB::raw('balance_amount + '.$value['Price (Rs)'])
                              ));  
                          }
                          else
                          {
                              return json_encode(array("Success" => "False", "Message" => "Stock is not avaiable for Product code " . $value['Product Code'] . " "));
                                    exit;
                          }
                             
                        }
                        ////would create a new bill
                        else
                        {
                            $productid = product::select('product_id','product_system_barcode')
                                       ->where('product_code',$value['Product Code'])
                                       ->where('company_id',Auth::user()->company_id)
                                       ->first();
                             $priceid  = price_master::select('price_master_id','selling_gst_percent')
                                         ->where('product_id',$productid['product_id'])
                                         ->where('company_id',Auth::user()->company_id)
                                         ->where('product_qty','>',0)
                                         ->orderBy('price_master_id','ASC')
                                         ->first(); 
                                    
                              if($priceid!='')
                              {            



                                     $mrp             =    $value['Price (Rs)']  / $value['Order Qty'];
                                     $gstamt          =    ($mrp/($priceid['selling_gst_percent']+100)) * $priceid['selling_gst_percent'];
                                     $gstamount       =     $gstamt * $value['Order Qty'];
                                     $halfgstamount   =     $gstamount /2;
                                     $halfgstper      =     $priceid['selling_gst_percent'] /2;

                                     $sellingprice    =     $mrp - $gstamt;

                                     $refid   = reference::select('reference_id')
                                               ->where('reference_name',$value['Portal'])
                                               ->where('company_id',Auth::user()->company_id)
                                               ->first();

                                     $stateid = state::select('state_id')
                                               ->where('state_name',$value['State'])
                                               ->first();
                                     $companyprofile = company_profile::select('state_id')
                                     ->where('company_id',Auth::user()->company_id)
                                     ->first();

                                      $dial_code = '';
                                      if($value['CONTACT NO'] != '')
                                      {
                                          
                                              $dial_code = company_profile::select('company_mobile_dial_code')
                                                  ->where('company_id',Auth::user()->company_id)->first();

                                              $code = explode(',',$dial_code['company_mobile_dial_code']);


                                              $dial_code = $code[0];
                                         
                                      } 

                                    $customer = customer::updateOrCreate(
                                      ['customer_id' => '', 'company_id' => $company_id,],
                                      [
                                          'created_by' => $created_by,
                                          'company_id' => $company_id,
                                          'customer_name' => (isset($value['Name']) ? $value['Name'] : ''),
                                          'customer_mobile_dial_code' => (isset($dial_code) ? $dial_code : ''),
                                          'customer_mobile' => (isset($value['CONTACT NO']) && $value['CONTACT NO'] != '' ? $value['CONTACT NO'] : NULL),
                                          'customer_email' => NULL,
                                          'is_active' => "1"
                                      ]
                                   );

                                   $customer_id = $customer->customer_id;
                                   $customer_address = customer_address_detail::updateOrCreate(
                                  ['customer_id' => $customer_id,
                                   'company_id'=>$company_id,],
                                  [
                                      'created_by' =>$created_by,
                                      'customer_gstin' => (isset($value['GST NO'])?$value['GST NO'] : ''),
                                      'customer_address_type' => '1',
                                      'customer_address' => '',
                                      'customer_area' => '',
                                      'customer_city' => (isset($value['City '])?$value['City '] : ''),
                                      'customer_pincode' =>'',
                                      'state_id' => (isset($value['State']) && $value['State'] != ''?$stateid['state_id'] : $companyprofile['state_id']),
                                      'country_id' => 102,
                                      'is_active' => "1"
                                   ]
                                 );
                                   $invoice_no    = '';
                                     $sales = sales_bill::updateOrCreate(
                                    ['sales_bill_id' => '', 'company_id'=>$company_id,],
                                    ['customer_id'=>$customer_id,
                                        'bill_no'=>$invoice_no,
                                        'order_no'=>$value['Order ID/PO NO'],
                                        'bill_date'=>$invoiceddate,
                                        'state_id'=>$stateid['state_id'],
                                        'reference_id'=>$refid['reference_id'],
                                        'total_qty'=>$value['Order Qty'],
                                        'sellingprice_before_discount'=>$sellingprice,
                                        'discount_percent'=>0,
                                        'discount_amount'=>0,
                                        'productwise_discounttotal'=>0,
                                        'sellingprice_after_discount'=>$sellingprice,
                                        'totalbillamount_before_discount'=>$sellingprice,
                                        'total_igst_amount'=>$gstamount,
                                        'total_cgst_amount'=>$halfgstamount,
                                        'total_sgst_amount'=>$halfgstamount,
                                        'gross_total'=>$value['Price (Rs)'],
                                        'shipping_charges'=>0,
                                        'total_bill_amount'=>$value['Price (Rs)'],
                                        'created_by' =>$created_by,
                                        'is_active' => "1"
                                    ]
                                );

                                   $sales_bill_id = $sales->sales_bill_id;

                                    $f1     =    (date('m')<'04') ? date('y',strtotime('-1 year')) : date('y');
                                    $f2     =    (date('m')>'03') ? date('y',strtotime('+1 year')) : date('y');

                                   
                                    
                                    $finalinvoiceno          =       $sales_bill_id.'/'.$f1.'-'.$f2; 

                                     sales_bill::where('sales_bill_id',$sales_bill_id)->update(array(
                                        'bill_no' => $finalinvoiceno
                                     ));


                                
                                      $productdetail['bill_date']                            =    $invoiceddate;
                                      $productdetail['product_system_barcode']               =    $productid['product_system_barcode'];
                                      $productdetail['product_id']                           =    $productid['product_id'];
                                      $productdetail['price_master_id']                      =    $priceid['price_master_id'];
                                      $productdetail['qty']                                  =    $value['Order Qty'];
                                      $productdetail['mrp']                                  =    $mrp;
                                      $productdetail['sellingprice_before_discount']         =    $sellingprice;
                                      $productdetail['discount_percent']                     =    0;
                                      $productdetail['discount_amount']                      =    0;
                                      $productdetail['sellingprice_after_discount']          =    $sellingprice;
                                      $productdetail['overalldiscount_percent']              =    0;
                                      $productdetail['overalldiscount_amount']               =    0;
                                      $productdetail['sellingprice_afteroverall_discount']   =    $sellingprice;
                                      $productdetail['cgst_percent']                         =    $halfgstper;
                                      $productdetail['cgst_amount']                          =    $halfgstamount;
                                      $productdetail['sgst_percent']                         =    $halfgstper;
                                      $productdetail['sgst_amount']                          =    $halfgstamount;
                                      $productdetail['igst_percent']                         =    $priceid['selling_gst_percent'];
                                      $productdetail['igst_amount']                          =    $gstamount;
                                      $productdetail['total_amount']                         =    $value['Price (Rs)'];
                                      $productdetail['product_type']                         =     1;
                                      $productdetail['created_by']                           =     Auth::User()->user_id;

                                  price_master::where('price_master_id',$priceid['price_master_id'])->update(array(
                                  'modified_by' => Auth::User()->user_id,
                                  'updated_at' => date('Y-m-d H:i:s'),
                                  'product_qty' => DB::raw('product_qty - '.$value['Order Qty'])
                                  ));   

                               /////FIFO logic

                               $ccount    =   0;  
                               $icount    =   0;
                               $pcount    =   0;
                               $done      =   0;
                               $firstout  =   0;
                               $restqty   =   $value['Order Qty']; 
                               $inwardids    =  '';
                               $inwardqtys   =  '';           

                          if($value['Order Qty']>0)
                          {
                             

                               $qquery    =         inward_product_detail::select('inward_product_detail_id','pending_return_qty')
                                                    ->where('product_id',$productid['product_id'])
                                                    ->where('company_id',Auth::user()->company_id)
                                                    ->where('pending_return_qty','!=',0);

                              $inwarddetail  =  $qquery->where('deleted_at','=',NULL)->orderBy('inward_product_detail_id','ASC')->get();

                         
                                          
                               foreach($inwarddetail as $inwarddata)
                               {
                                  //echo $inwarddata['pending_return_qty'];
                                    if($inwarddata['pending_return_qty'] >= $restqty && $firstout==0)
                                    {  
                                          if($done == 0)
                                          {

                                            //echo 'hello';

                                                  $inwardids    .=   $inwarddata['inward_product_detail_id'].',';
                                                  $inwardqtys   .=   $restqty.',';
                                              
                                                  inward_product_detail::where('company_id',Auth::user()->company_id)
                                                  ->where('inward_product_detail_id',$inwarddata['inward_product_detail_id'])
                                                  ->update(array(
                                                      'modified_by' => Auth::User()->user_id,
                                                      'updated_at' => date('Y-m-d H:i:s'),
                                                      'pending_return_qty' => DB::raw('pending_return_qty - '.$value['Order Qty'])
                                                      ));
                                                  $pcount++;
                                                  $done++;
                                         }
                                   }
                                   else
                                   {
                                      if($pcount==0 && $done == 0 && $icount==0)
                                      {
                                          
                                         
                                          if($restqty  > $inwarddata['pending_return_qty'])
                                          {
                                            //echo 'bbb';
                                            //echo $restqty;
                                              $inwardids    .=   $inwarddata['inward_product_detail_id'].',';
                                              $inwardqtys   .=   $inwarddata['pending_return_qty'].',';
                                              $ccount       =   $restqty  - $inwarddata['pending_return_qty'];
                                              inward_product_detail::where('company_id',Auth::user()->company_id)
                                              ->where('inward_product_detail_id',$inwarddata['inward_product_detail_id'])
                                              ->update(array(
                                                  'modified_by' => Auth::User()->user_id,
                                                  'updated_at' => date('Y-m-d H:i:s'),
                                                  'pending_return_qty' => DB::raw('pending_return_qty - '.$inwarddata['pending_return_qty'])
                                                  ));
                                          }
                                          else
                                          {
                                            //echo 'ccc';
                                            //echo $restqty;
                                              $inwardids    .=   $inwarddata['inward_product_detail_id'].',';
                                              $inwardqtys   .=   $restqty.',';
                                              $ccount   =   $restqty  - $inwarddata['pending_return_qty'];
                                              inward_product_detail::where('company_id',Auth::user()->company_id)
                                              ->where('inward_product_detail_id',$inwarddata['inward_product_detail_id'])
                                              ->update(array(
                                                  'modified_by' => Auth::User()->user_id,
                                                  'updated_at' => date('Y-m-d H:i:s'),
                                                  'pending_return_qty' => DB::raw('pending_return_qty - '.$restqty)
                                                  ));
                                          }


                                           if($ccount > 0)
                                            {
                                               $firstout++;
                                               // echo $pcount;
                                               // echo $done;
                                               // echo $icount;
                                               $restqty   =   $restqty  - $inwarddata['pending_return_qty'];
                                              // echo $restqty;

                                               
                                            }
                                            if($ccount <= 0)
                                            {
                                              //echo 'no';
                                              $firstout++;
                                               $icount++;
                                                 
                                            }
                                           
                                      }
                                   }

                               }    
                           }        

                        
                        if($inwardids!='')
                        {
                          $productdetail['inwardids']                          =    $inwardids;
                          $productdetail['inwardqtys']                         =    $inwardqtys;
                        }   
                        else
                        {  
                          $productdetail['inwardids']                          =    NULL;
                          $productdetail['inwardqtys']                         =    NULL;
                        }


                        ///end of FIFO Logic
                                      $billproductdetail = sales_product_detail::updateOrCreate(
                                       ['sales_bill_id' => $sales_bill_id,
                                        'company_id'=>$company_id,'sales_products_detail_id'=>'',],
                                       $productdetail); 



                                       $sales_payment = sales_bill_payment_detail::updateOrCreate(
                                        ['sales_bill_payment_detail_id' => ''],
                                        ['sales_bill_id'=>$sales_bill_id,
                                            'total_bill_amount'=>$value['Price (Rs)'],
                                            'payment_method_id'=>6,
                                            'created_by' =>$created_by,
                                            'is_active' => "1"
                                        ]
                                      );  
                                       $sales_credit = customer_creditaccount::updateOrCreate(
                                        ['sales_bill_id' => $sales_bill_id, 'company_id'=>$company_id,],
                                        ['customer_id'=>$customer_id,
                                            'bill_date'=>$invoiceddate,
                                            'credit_amount'=>$value['Price (Rs)'],
                                            'balance_amount'=>$value['Price (Rs)'],
                                            'created_by' =>$created_by,
                                            'deleted_at' =>NULL,
                                            'deleted_by' =>NULL,
                                            'is_active' => "1"
                                            ]
                                        );

                                }
                                else
                                {
                                    return json_encode(array("Success" => "False", "Message" => "Stock is not avaiable for Product code " . $value['Product Code'] . " "));
                                    exit;
                                }                                     

                        }


       



                      
                      if ($sales)
                      {
                          if(!next( $sales_excel_data ))
                          {

                              return json_encode(array("Success" => "True", "Message" => "Sales has been successfully Added."));
                          }
                      }
                      else
                      {
                          return json_encode(array("Success" => "False", "Message" => "Something Went Wrong"));
                          exit;
                      }
                  }
              }catch (\Exception $e)
              {
                  return json_encode(array("Success" => "False", "Message" => $e->getMessage()));
                  exit;
              }
          }
        }
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
     * @param  \App\view_bill  $view_bill
     * @return \Illuminate\Http\Response
     */
    public function show(view_bill $view_bill)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\view_bill  $view_bill
     * @return \Illuminate\Http\Response
     */
    public function edit(view_bill $view_bill)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\view_bill  $view_bill
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, view_bill $view_bill)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\view_bill  $view_bill
     * @return \Illuminate\Http\Response
     */
    public function destroy(view_bill $view_bill)
    {
        //
    }
}
