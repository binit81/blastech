<?php

namespace Retailcore\PrintingFiles\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Retailcore\Products\Models\product\price_master;
use Retailcore\Sales\Models\sales_bill;
use Retailcore\Sales\Models\sales_product_detail;
use Retailcore\Sales\Models\sales_bill_payment_detail;
use Retailcore\SalesReturn\Models\return_bill;
use Retailcore\SalesReturn\Models\return_product_detail;
use Retailcore\SalesReturn\Models\return_bill_payment;
use Retailcore\CreditBalance\Models\customer_creditaccount;
use Retailcore\CreditBalance\Models\customer_creditreceipt;
use Retailcore\CreditBalance\Models\customer_creditreceipt_detail;
use Retailcore\Products\Models\product\product;
use Retailcore\GST_Slabs\Models\GST_Slabs\gst_slabs_master;
use Retailcore\Customer\Models\customer\customer;
use Retailcore\Customer\Models\customer\customer_address_detail;
use Retailcore\Sales\Models\payment_method;
use App\state;
use App\country;
use Retailcore\Company_Profile\Models\company_profile\company_profile;
use Retailcore\CreditNote\Models\customer_creditnote;
use Retailcore\CreditNote\Models\creditnote_payment;
use Auth;
use DB;



class PrintingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
  
     public function index()
    {

       
    }

   
   

    public function print_bill(Request $request)
    {

        $billid  = decrypt($request->id);
        
        $state = state::all();
        $country = country::all();
        $state_id  =  company_profile::select('state_id','tax_type','tax_title','currency_title')->where('company_id',Auth::user()->company_id)->get();
        $company_state   = $state_id[0]['state_id'];
        $tax_type        = $state_id[0]['tax_type'];
        $tax_title       = $state_id[0]['tax_title'];
        $taxname         = $tax_type==1?$tax_title:'IGST';
        $currtitle       = $state_id[0]['currency_title'];

        if($tax_type==1)
        {   
                $currency_title  = $currtitle==''||$currtitle==NULL?'INR':$currtitle;
        }
        else
        {
                $currency_title  = 'INR';
        }
        
       

       $payment_methods = payment_method::where('is_active','=','1')->get();

        $billingdata = sales_bill::where([
            ['sales_bill_id','=',$billid],
            ['company_id',Auth::user()->company_id]])
            ->select('*')
            ->with('reference')
            ->with('sales_bill_payment_detail')
            ->with('customer')
            ->with('customer_address_detail')
            ->with('company')
            ->withCount([
                    'sales_product_detail as overalldiscount' => function($fquery) {
                        $fquery->select(DB::raw('SUM(discount_amount + overalldiscount_amount )'));
                    }
                ])
            ->first();

             $billingproductdata = sales_product_detail::where('company_id',Auth::user()->company_id)
            ->where('sales_bill_id','=',$billid)
            ->where('qty','!=',0)
            ->with('product')
            ->get();

            /*$billingpaymentdata = sales_bill_payment_detail::where('sales_bill_id','=',$billid)
            ->with('payment_method')
            ->get();
            */
            $productcount = sales_product_detail::where('company_id',Auth::user()->company_id)
            ->where('sales_bill_id','=',$billid)
            ->count();

             $gstdata = sales_product_detail::select('cgst_percent','sgst_percent','igst_percent',DB::raw("SUM(sales_product_details.sellingprice_afteroverall_discount) as tottaxablevalue"),DB::raw("SUM(sales_product_details.cgst_amount) as totcgstamount"),DB::raw("SUM(sales_product_details.sgst_amount) as totsgstamount"),DB::raw("SUM(sales_product_details.igst_amount) as totigstamount"),DB::raw("SUM(sales_product_details.total_amount) as totgrand"))->where('sales_bill_id','=',$billid)->groupBy('igst_percent')->get();


       
        return view('printingfiles::sales/print_bill',compact('payment_methods','state','country','billingdata','billingproductdata','productcount','gstdata','tax_type','taxname','currency_title'));
       
    }
    public function thermalprint_bill(Request $request)
    {

        $billid  = decrypt($request->id);
        
        $state = state::all();
        $country = country::all();
        $state_id  =  company_profile::select('state_id','tax_type','tax_title','currency_title')->where('company_id',Auth::user()->company_id)->get();
        $company_state   = $state_id[0]['state_id'];
        $tax_type        = $state_id[0]['tax_type'];
        $tax_title       = $state_id[0]['tax_title'];
        $taxname         = $tax_type==1?$tax_title:'IGST';
        $currtitle       = $state_id[0]['currency_title'];
        if($tax_type==1)
        {   
                $currency_title  = $currtitle==''||$currtitle==NULL?'&#x20b9':$currtitle;
        }
        else
        {
                $currency_title  = '&#x20b9';
        }
       

       $payment_methods = payment_method::where('is_active','=','1')->get();

        $billingdata = sales_bill::where([
            ['sales_bill_id','=',$billid],
            ['company_id',Auth::user()->company_id]])
            ->select('*')
            ->with('reference')
            ->with('sales_bill_payment_detail')
            ->with('customer')
            ->with('customer_address_detail')
            ->with('company')
            ->first();

             $billingproductdata = sales_product_detail::where('company_id',Auth::user()->company_id)
            ->where('sales_bill_id','=',$billid)
            ->where('qty','!=',0)
            ->with('product')
            ->get();

            $gstdata = sales_product_detail::select('cgst_percent','sgst_percent','igst_percent',DB::raw("SUM(sales_product_details.sellingprice_afteroverall_discount) as tottaxablevalue"),DB::raw("SUM(sales_product_details.cgst_amount) as totcgstamount"),DB::raw("SUM(sales_product_details.sgst_amount) as totsgstamount"),DB::raw("SUM(sales_product_details.igst_amount) as totigstamount"),DB::raw("SUM(sales_product_details.total_amount) as totgrand"))->where('sales_bill_id','=',$billid)->groupBy('igst_percent')->get();

         
       
        return view('printingfiles::sales/thermalprint_bill',compact('payment_methods','state','country','billingdata','billingproductdata','gstdata','tax_type','taxname','currency_title'));
       
    }
    public function print_creditnote(Request $request)
    {

        $billid  = decrypt($request->id);
        
        $state = state::all();
        $country = country::all();
        $state_id  =  company_profile::select('state_id','tax_type','tax_title','currency_title')->where('company_id',Auth::user()->company_id)->get();
        $company_state   = $state_id[0]['state_id'];
        $tax_type        = $state_id[0]['tax_type'];
        $tax_title       = $state_id[0]['tax_title'];
        $taxname         = $tax_type==1?$tax_title:'IGST';
        $currtitle       = $state_id[0]['currency_title'];
        if($tax_type==1)
        {   
                $currency_title  = $currtitle==''||$currtitle==NULL?'&#x20b9':$currtitle;
        }
        else
        {
                $currency_title  = '&#x20b9';
        }
       

       $payment_methods = payment_method::where('is_active','=','1')->get();

        $billingdata = return_bill::where([
            ['return_bill_id','=',$billid],
            ['company_id',Auth::user()->company_id]])
            ->select('*')
            ->with('return_bill_payment')
            ->with('customer')
            ->with('customer_address_detail')
            ->with('company')
            ->withCount([
                    'return_product_detail as overalldiscount' => function($fquery) {
                        $fquery->select(DB::raw('SUM(discount_amount + overalldiscount_amount )'));
                    }
                ])
            ->first();

             $billingproductdata = return_product_detail::where('company_id',Auth::user()->company_id)
            ->where('return_bill_id','=',$billid)
            ->where('qty','!=',0)
            ->with('product')
            ->get();

           
            $productcount = return_product_detail::where('company_id',Auth::user()->company_id)
            ->where('return_bill_id','=',$billid)
            ->count();

            $gstdata = return_product_detail::select('cgst_percent','sgst_percent','igst_percent',DB::raw("SUM(return_product_details.sellingprice_afteroverall_discount) as tottaxablevalue"),DB::raw("SUM(return_product_details.cgst_amount) as totcgstamount"),DB::raw("SUM(return_product_details.sgst_amount) as totsgstamount"),DB::raw("SUM(return_product_details.igst_amount) as totigstamount"),DB::raw("SUM(return_product_details.total_amount) as totgrand"))->where('return_bill_id','=',$billid)->groupBy('igst_percent')->get();


       
        return view('printingfiles::creditnote/print_creditnote',compact('payment_methods','state','country','billingdata','billingproductdata','productcount','gstdata','tax_type','taxname','currency_title'));
       
    }
    public function print_creditreceipt(Request $request)
    {

        $billid  = decrypt($request->id);
        
        $state = state::all();
        $country = country::all();
        $state_id  =  company_profile::select('state_id','tax_type','tax_title','currency_title')->where('company_id',Auth::user()->company_id)->get();
        $company_state   = $state_id[0]['state_id'];
        $tax_type        = $state_id[0]['tax_type'];
        $tax_title       = $state_id[0]['tax_title'];
        $taxname         = $tax_type==1?$tax_title:'IGST';
        $currtitle       = $state_id[0]['currency_title'];
        if($tax_type==1)
        {   
                $currency_title  = $currtitle==''||$currtitle==NULL?'&#x20b9':$currtitle;
        }
        else
        {
                $currency_title  = '&#x20b9';
        }
       

       $payment_methods = payment_method::where('is_active','=','1')->get();

        $billingdata = customer_creditreceipt::where([
            ['customer_creditreceipt_id','=',$billid],
            ['company_id',Auth::user()->company_id]])
            ->select('*')
            ->with('customer')
            ->with('customer_address_detail')
            ->with('customer_crerecp_payment')
            ->with('company')
            ->get();

             $billingproductdata = customer_creditreceipt_detail::where('customer_creditreceipt_id','=',$billid)
            ->get();

       
        return view('printingfiles::creditreceipt/print_creditreceipt',compact('payment_methods','state','country','billingdata','billingproductdata','tax_type','taxname','currency_title'));
       
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
     * @param  \App\sales_bill  $sales_bill
     * @return \Illuminate\Http\Response
     */
    public function show(sales_bill $sales_bill)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\sales_bill  $sales_bill
     * @return \Illuminate\Http\Response
     */
    public function edit(sales_bill $sales_bill)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\sales_bill  $sales_bill
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, sales_bill $sales_bill)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\sales_bill  $sales_bill
     * @return \Illuminate\Http\Response
     */
    public function destroy(sales_bill $sales_bill)
    {
        //
    }
}
