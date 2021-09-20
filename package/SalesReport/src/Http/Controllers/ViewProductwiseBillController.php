<?php

namespace Retailcore\SalesReport\Http\Controllers;
use App\Http\Controllers\Controller;
use Retailcore\SalesReport\Models\productwiseBills_export;
use Illuminate\Http\Request;
use Retailcore\Sales\Models\sales_bill;
use Retailcore\Sales\Models\reference;
use Retailcore\SalesReturn\Models\return_bill;
use Retailcore\SalesReturn\Models\return_product_detail;
use Retailcore\Sales\Models\sales_product_detail;
use Retailcore\Sales\Models\sales_bill_payment_detail;
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

class ViewProductwiseBillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $state_id  =  company_profile::select('state_id','tax_type','tax_title','currency_title')->where('company_id',Auth::user()->company_id)->get();
        $company_state   = $state_id[0]['state_id'];
        $tax_type        = $state_id[0]['tax_type'];
        $tax_title       = $state_id[0]['tax_title'];
        $taxname         = $tax_type==1?$tax_title:'IGST';
        
        $date        =    date("Y-m-d");

        $squery = sales_product_detail::where('company_id',Auth::user()->company_id)
            ->where('deleted_by','=',NULL)
            ->with('sales_bill')->whereHas('sales_bill',function ($q) use ($date){
                   $q->whereRaw("STR_TO_DATE(sales_bills.bill_date,'%d-%m-%Y') between '$date' and '$date'");
            })
            ->with('sales_bill.reference')
            ->with('product')          
            ->orderBy('sales_bill_id', 'DESC');

        $rquery  =   return_product_detail::where('company_id',Auth::user()->company_id)
            ->where('deleted_by','=',NULL)
            ->with('return_bill')->whereHas('return_bill',function ($q) use ($date){
                   $q->whereRaw("STR_TO_DATE(return_bills.bill_date,'%d-%m-%Y') between '$date' and '$date'");
                  })
            ->with('return_bill.reference')
            ->with('product')
            ->orderBy('return_bill_id', 'DESC');


        $scustom   =   collect();
        $sdata     =   $scustom->merge($squery->get());

        $rcustom   =   collect();
        $rdata     =   $rcustom->merge($rquery->get());

        $sales_room_details  = $squery->paginate(10);
        $return_room_details  = $rquery->paginate(10);

            $totaltariff = 0;
            $totaldiscount = 0;
            $taxabletariff = 0;
            $totalcgst = 0;
            $totalsgst = 0;
            $totaligst = 0;
            $grandtotal = 0;
            $halfchargesgst = 0;

            $rtotaltariff = 0;
            $rtotaldiscount = 0;
            $rtaxabletariff = 0;
            $rtotalcgst = 0;
            $rtotalsgst = 0;
            $rtotaligst = 0;
            $rgrandtotal = 0; 
            $rhalfchargesgst =0;

            foreach ($sdata as $totsales)
            {
               
                $totaltariff    +=  $totsales->sellingprice_before_discount*$totsales->total_days;
                $totaldiscount  +=  $totsales->discount_amount;
                $taxabletariff  +=  $totsales->sellingprice_afteroverall_discount;
                $halfchargesgst   =   $totsales->chargesgst / 2;
                if($tax_type==1)
                {
                    $totaligst       +=  $totsales->igst_amount + $totsales->chargesgst;  
                }
                else
                {
                    if($totsales['sales_bill']['state_id'] == $company_state)
                    {
                      $totalcgst       +=  $totsales->cgst_amount + $halfchargesgst;
                      $totalsgst       +=  $totsales->sgst_amount + $halfchargesgst; 
                    }
                    else
                    {
                      $totaligst       +=  $totsales->igst_amount + $totsales->chargesgst;  
                    }
                }
                
                $grandtotal     +=  $totsales->total_amount;
            }
            foreach ($rdata as $rtotsales)
            {
               
                $rtotaltariff   +=  $rtotsales->sellingprice_before_discount*$rtotsales->total_days;
                $rtotaldiscount  +=  $rtotsales->discount_amount;
                $rtaxabletariff  +=  $rtotsales->sellingprice_afteroverall_discount;
                $rhalfchargesgst =   $rtotsales->chargesgst / 2;
                if($tax_type==1)
                {
                    $rtotaligst       +=  $rtotsales->igst_amount + $rtotsales->chargesgst; 
                }
                else
                {
                    if($rtotsales['return_bill']['state_id'] == $company_state)
                    {
                      $rtotalcgst       +=  $rtotsales->cgst_amount + $halfchargesgst;
                      $rtotalsgst       +=  $rtotsales->sgst_amount + $halfchargesgst; 
                    }
                    else
                    {
                      $rtotaligst       +=  $rtotsales->igst_amount + $rtotsales->chargesgst;  
                    }
                }
                
                $rgrandtotal     +=  $rtotsales->total_amount;
            }
            
            $todaytaxable  = $taxabletariff - $rtaxabletariff;
            $todaycgst     = $totalcgst - $rtotalcgst;
            $todaysgst     = $totalsgst - $rtotalsgst;
            $todayigst     = $totaligst - $rtotaligst;
            $todaygrand    = $grandtotal - $rgrandtotal;
            

         return view('salesreport::view_productwise_bill',compact('sales_room_details','todaytaxable','todaycgst','todaysgst','todaygrand','todayigst','company_state','return_room_details','tax_type','taxname'));

    }

    function datewise_product_billdetail(Request $request)
    {
        if($request->ajax())
        {
                $state_id  =  company_profile::select('state_id','tax_type','tax_title','currency_title')->where('company_id',Auth::user()->company_id)->get();
                $company_state   = $state_id[0]['state_id'];
                $tax_type        = $state_id[0]['tax_type'];
                $tax_title       = $state_id[0]['tax_title'];
                $taxname         = $tax_type==1?$tax_title:'IGST';
                $data            =      $request->all();
                $sort_by = $data['sortby'];
                $sort_type = $data['sorttype'];
                $query = isset($data['query']) ? $data['query']  : '';

            $squery           =      sales_product_detail::select('*')->where('deleted_at','=',NULL);
            $rquery           =      return_product_detail::select('*')->where('deleted_at','=',NULL);
           
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

                $totalcustomer = customer::select('customer_id')
                 ->where('company_id',Auth::user()->company_id)
                 ->where('deleted_at','=',NULL)
                 ->where('customer_name', 'LIKE', "%$cus_name%")
                 ->orwhere('customer_mobile', 'LIKE', "%$cus_mobile%")
                 ->get();

                 $totalsalesid = sales_bill::select('sales_bill_id')
                 ->where('company_id',Auth::user()->company_id)
                 ->where('deleted_at','=',NULL)
                 ->whereIn('customer_id',$totalcustomer)
                 ->get();

                  $totalreturnid = return_bill::select('return_bill_id')
                 ->where('company_id',Auth::user()->company_id)
                 ->where('deleted_at','=',NULL)
                 ->whereIn('customer_id',$totalcustomer)
                 ->get();

                 $squery->whereIn('sales_bill_id',$totalsalesid);
                 $rquery->whereIn('return_bill_id',$totalreturnid);
            }
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
            if(isset($query) && $query != '' && $query['reference_name'] != '')
            {

                $ref_name = $query['reference_name'];

                $totalrefid = reference::select('reference_id')
                 ->where('company_id',Auth::user()->company_id)
                 ->where('deleted_at','=',NULL)
                 ->where('reference_name', 'LIKE', "%$ref_name%")
                 ->get();

                 $totalsalesrid = sales_bill::select('sales_bill_id')
                 ->where('company_id',Auth::user()->company_id)
                 ->where('deleted_at','=',NULL)
                 ->whereIn('reference_id',$totalrefid)
                 ->get();

                  $totalreturnrid = return_bill::select('return_bill_id')
                 ->where('company_id',Auth::user()->company_id)
                 ->where('deleted_at','=',NULL)
                 ->whereIn('reference_id',$totalrefid)
                 ->get();

                 $squery->whereIn('sales_bill_id',$totalsalesrid);
                 $rquery->whereIn('return_bill_id',$totalreturnrid);
            }
            if(isset($query) && $query != '' && $query['from_date'] != '' && $query['to_date'] != '')
            {
                
                 $rstart           =      date("Y-m-d",strtotime($query['from_date']));
                 $rend             =      date("Y-m-d",strtotime($query['to_date']));

                 $squery->with('sales_bill')->whereHas('sales_bill',function ($q) use ($rstart,$rend){
                   $q->whereRaw("STR_TO_DATE(sales_bills.bill_date,'%d-%m-%Y') between '$rstart' and '$rend'");
                  });
                 $rquery->with('return_bill')->whereHas('return_bill',function ($q) use ($rstart,$rend){
                   $q->whereRaw("STR_TO_DATE(return_bills.bill_date,'%d-%m-%Y') between '$rstart' and '$rend'");
                  });
            }
           
            $scustom   =   collect();
            $sdata     =   $scustom->merge($squery->get());
          

            $sales_room_details = $squery->with('sales_bill.reference')->where('company_id',Auth::user()->company_id)->orderBy($sort_by, $sort_type)->paginate(10);

            $rcustom   =   collect();
            $rdata     =   $rcustom->merge($rquery->get());

            $return_room_details = $rquery->with('return_bill.reference')->where('company_id',Auth::user()->company_id)->orderBy('return_bill_id','DESC')->paginate(10);


            $totaltariff = 0;
            $totaldiscount = 0;
            $taxabletariff = 0;
            $totalcgst = 0;
            $totalsgst = 0;
            $totaligst = 0;
            $grandtotal = 0;
            $halfchargesgst = 0;

            $rtotaltariff = 0;
            $rtotaldiscount = 0;
            $rtaxabletariff = 0;
            $rtotalcgst = 0;
            $rtotalsgst = 0;
            $rtotaligst = 0;
            $rgrandtotal = 0; 
            $rhalfchargesgst =0;

            foreach ($sdata as $totsales)
            {
               
                $totaltariff    +=  $totsales->sellingprice_before_discount*$totsales->total_days;
                $totaldiscount  +=  $totsales->discount_amount;
                $taxabletariff  +=  $totsales->sellingprice_afteroverall_discount;
                $halfchargesgst   =   $totsales->chargesgst / 2;
                if($tax_type==1)
                {
                      $totaligst       +=  $totsales->igst_amount + $totsales->chargesgst;  
                }
                else
                {
                    if($totsales['sales_bill']['state_id'] == $company_state)
                    {
                      $totalcgst       +=  $totsales->cgst_amount + $halfchargesgst;
                      $totalsgst       +=  $totsales->sgst_amount + $halfchargesgst; 
                    }
                    else
                    {
                      $totaligst       +=  $totsales->igst_amount + $totsales->chargesgst;  
                    }
                }
                
                $grandtotal     +=  $totsales->total_amount;
            }
            foreach ($rdata as $rtotsales)
            {
               
                $rtotaltariff   +=  $rtotsales->sellingprice_before_discount*$rtotsales->total_days;
                $rtotaldiscount  +=  $rtotsales->discount_amount;
                $rtaxabletariff  +=  $rtotsales->sellingprice_afteroverall_discount;
                $rhalfchargesgst =   $rtotsales->chargesgst / 2;
                if($tax_type==1)
                {
                      $rtotaligst       +=  $rtotsales->igst_amount + $rtotsales->chargesgst;  
                }
                else
                {
                      if($rtotsales['return_bill']['state_id'] == $company_state)
                      {
                        $rtotalcgst       +=  $rtotsales->cgst_amount + $halfchargesgst;
                        $rtotalsgst       +=  $rtotsales->sgst_amount + $halfchargesgst; 
                      }
                      else
                      {
                        $rtotaligst       +=  $rtotsales->igst_amount + $rtotsales->chargesgst;  
                      }
                }
                $rgrandtotal     +=  $rtotsales->total_amount;
            }
            
            $todaytaxable  = $taxabletariff - $rtaxabletariff;
            $todaycgst     = $totalcgst - $rtotalcgst;
            $todaysgst     = $totalsgst - $rtotalsgst;
            $todayigst     = $totaligst - $rtotaligst;
            $todaygrand    = $grandtotal - $rgrandtotal;

        
            return view('salesreport::view_productwise_bill_data',compact('sales_room_details','todaytaxable','todaycgst','todaysgst','todaygrand','todayigst','company_state','return_room_details','tax_type','taxname'))->render();
        }
            
                
    }

    public function exportroomwise_details(Request $request)
    {
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
                $barcode         =      $data['barcode'];
                $reference_name  =      $data['reference_name'];

            $squery           =      sales_product_detail::select('*');
            $rquery           =      return_product_detail::select('*');
           
            if($customerid != '')
            {

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

                $totalcustomer = customer::select('customer_id')
                 ->where('company_id',Auth::user()->company_id)
                 ->where('deleted_at','=',NULL)
                 ->where('customer_name', 'LIKE', "%$cus_name%")
                 ->orwhere('customer_mobile', 'LIKE', "%$cus_mobile%")
                 ->get();

                 $totalsalesid = sales_bill::select('sales_bill_id')
                 ->where('company_id',Auth::user()->company_id)
                 ->where('deleted_at','=',NULL)
                 ->whereIn('customer_id',$totalcustomer)
                 ->get();

                  $totalreturnid = return_bill::select('return_bill_id')
                 ->where('company_id',Auth::user()->company_id)
                 ->where('deleted_at','=',NULL)
                 ->whereIn('customer_id',$totalcustomer)
                 ->get();

                 $squery->whereIn('sales_bill_id',$totalsalesid);
                 $rquery->whereIn('sales_bill_id',$totalreturnid);
            }
            if($barcode != '')
            {
                if(strpos($barcode, '_') !== false)
                {
                    $prodbarcode   =   explode('_',$barcode);
                    $prod_barcode      =  $prodbarcode[0];
                    $prod_name    =  $prodbarcode[1];
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
            if($reference_name != '')
            {

               

                $totalrefid = reference::select('reference_id')
                 ->where('company_id',Auth::user()->company_id)
                 ->where('deleted_at','=',NULL)
                 ->where('reference_name', 'LIKE', "%$reference_name%")
                 ->get();

                 $totalsalesrid = sales_bill::select('sales_bill_id')
                 ->where('company_id',Auth::user()->company_id)
                 ->where('deleted_at','=',NULL)
                 ->whereIn('reference_id',$totalrefid)
                 ->get();

                  $totalreturnrid = return_bill::select('return_bill_id')
                 ->where('company_id',Auth::user()->company_id)
                 ->where('deleted_at','=',NULL)
                 ->whereIn('reference_id',$totalrefid)
                 ->get();

                 $squery->whereIn('sales_bill_id',$totalsalesrid);
                 $rquery->whereIn('return_bill_id',$totalreturnrid);
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

                  $squery->with('sales_bill')->whereHas('sales_bill',function ($q) use ($rstart,$rend){
                   $q->whereRaw("STR_TO_DATE(sales_bills.bill_date,'%d-%m-%Y') between '$rstart' and '$rend'");
                  });
                 $rquery->with('return_bill')->whereHas('return_bill',function ($q) use ($rstart,$rend){
                   $q->whereRaw("STR_TO_DATE(return_bills.bill_date,'%d-%m-%Y') between '$rstart' and '$rend'");
                  });
            }
           

            $sales = $squery->with('sales_bill.reference')->where('company_id',Auth::user()->company_id)->orderBy('sales_products_detail_id', 'DESC')->where('qty','!=',0)->where('deleted_at','=',NULL)->get();
            $returnbill = $rquery->with('return_bill.reference')->where('company_id',Auth::user()->company_id)->orderBy('return_product_detail_id','DESC')->where('qty','!=',0)->where('deleted_at','=',NULL)->get();

            $overallsales   =  [];
             $header       = [];
             $header[]  =  'Bill No.';  
             $header[]  =  'Bill Date';  
             $header[]  =  'Customer Name'; 
             $header[]  =  'Product Name'; 
             $header[]  =  'Barcode';
             $header[]  =  'SellingPrice';
             $header[]  =  'Qty';
             $header[]  =  'Discount Percent';
             $header[]  =  'Discount Amount';
             $header[]  =  'Overall Discount Amount';
             $header[]  =  'Taxable Amount';
             if($tax_type==1)
             {
                 $header[]  =  $taxname.' Percent';
                 $header[]  =  $taxname.' Amount';
             }
             else
             {
                 $header[]  =  'CGST Percent';
                 $header[]  =  'CGST Amount';
                 $header[]  =  'SGST Percent';
                 $header[]  =  'SGST Amount'; 
                 $header[]  =  'IGST Percent';
                 $header[]  =  'IGST Amount';
                
             }
             
             $header[]  =  'Total Amount';
             $header[]  =  'Reference';
            

            $overallsales['sales']        =  $sales;
            $overallsales['returnbill']   =  $returnbill;
        
          return Excel::download(new productwiseBills_export($overallsales, $header), 'Productwise-Export.xlsx');
       

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
