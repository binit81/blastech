<?php

namespace Retailcore\CreditNote\Http\Controllers;

use Retailcore\CreditNote\Models\creditnote_report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Retailcore\CreditNote\Models\customer_creditnote;
use Retailcore\Sales\Models\sales_bill;
use Retailcore\SalesReturn\Models\return_bill;
use Retailcore\SalesReturn\Models\return_product_detail;
use Retailcore\SalesReturn\Models\return_bill_payment;
use Retailcore\Products\Models\product\product;
use Retailcore\Customer\Models\customer\customer;
use Retailcore\Customer\Models\customer\customer_address_detail;
use Retailcore\Sales\Models\payment_method;
use App\state;
use App\country;
use Retailcore\Company_Profile\Models\company_profile\company_profile;
use Retailcore\CreditNote\Models\creditnote_payment;
use Auth;
use DB;



class CreditnoteReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $creditnotepayment  =  array();
         $receipts = customer_creditnote::select("*",DB::raw("(SELECT SUM(creditnote_payments.used_amount) FROM creditnote_payments WHERE creditnote_payments.customer_creditnote_id = customer_creditnotes.customer_creditnote_id and deleted_at IS NULL GROUP BY creditnote_payments.customer_creditnote_id) as usedamount"))->where('company_id',Auth::user()->company_id)->where('deleted_at','=',NULL)->orderBy('customer_creditnote_id', 'DESC')->with('customer')->paginate(10);

         
        return view('creditnote::creditnote_report',compact('receipts','creditnotepayment'));
    }
    function datewise_cuscreditnotedetail(Request $request)
    {
        if($request->ajax())
        {
            $data            =      $request->all();
            $creditnotepayment = array();
            $sort_by = $data['sortby'];
            $sort_type = $data['sorttype'];
            $query = isset($data['query']) ? $data['query']  : '';

            $cquery        =      customer_creditnote::select("*",DB::raw("(SELECT SUM(creditnote_payments.used_amount) FROM creditnote_payments WHERE creditnote_payments.customer_creditnote_id = customer_creditnotes.customer_creditnote_id and deleted_at IS NULL GROUP BY creditnote_payments.customer_creditnote_id) as usedamount"));


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

                 $cquery->whereIn('customer_id',$result);
            }
            if(isset($query) && $query != '' && $query['billno'] != '')
            {
                $cquery->where('creditnote_no', 'like', '%'.$query['billno'].'%');

            }
            if(isset($query) && $query != '' && $query['from_date'] != '' && $query['to_date'] != '')
            {

                 $rstart           =      date("Y-m-d",strtotime($query['from_date']));
                 $rend             =      date("Y-m-d",strtotime($query['to_date']));

                $cquery->whereRaw("STR_TO_DATE(creditnote_date,'%d-%m-%Y') between '$rstart' and '$rend'");
                // $cquery->where('creditnote_date','>=',$query['from_date'])->where('creditnote_date','<=',$query['to_date']);
                
            }
           
           

            $receipts = $cquery->orderBy($sort_by, $sort_type)->where('deleted_by','=',NULL)->with('customer')->paginate(10);

            
            
            return view('creditnote::creditnote_reportdata',compact('receipts','creditnotepayment'));
        }
            
                
    }
  public function view_creditnote_popup(Request $request)
  {

        if($request->ajax())
        {
            
            $creditnotepayment = creditnote_payment::where('company_id',Auth::user()->company_id)
                ->where('deleted_at','=',NULL)
                ->where('customer_creditnote_id','=',$request->billno)
                ->select('*')
                ->with('sales_bill')
                ->with('return_bill')
                ->with('customer')
                ->get();

             
                  return view('creditnote::creditnote_popup',compact('creditnotepayment'));

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
     * @param  \App\creditnote_report  $creditnote_report
     * @return \Illuminate\Http\Response
     */
    public function show(creditnote_report $creditnote_report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\creditnote_report  $creditnote_report
     * @return \Illuminate\Http\Response
     */
    public function edit(creditnote_report $creditnote_report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\creditnote_report  $creditnote_report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, creditnote_report $creditnote_report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\creditnote_report  $creditnote_report
     * @return \Illuminate\Http\Response
     */
    public function destroy(creditnote_report $creditnote_report)
    {
        //
    }
}
