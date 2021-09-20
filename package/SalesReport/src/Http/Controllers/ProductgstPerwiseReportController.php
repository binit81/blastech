<?php

namespace Retailcore\SalesReport\Http\Controllers;

use App\Http\Controllers\Controller;
use Retailcore\SalesReport\Models\productgst_perwise_report;
use Retailcore\SalesReport\Models\gstwiseBilling_export;
use Illuminate\Http\Request;
use Retailcore\Sales\Models\sales_bill;
use Retailcore\Sales\Models\reference;
use Retailcore\Sales\Models\sales_product_detail;
use Retailcore\SalesReturn\Models\return_bill;
use Retailcore\SalesReturn\Models\return_product_detail;
use Retailcore\Sales\Models\sales_bill_payment_detail;
use Retailcore\Sales\Models\payment_method;
use App\state;
use App\country;
use Auth;
use Retailcore\Customer\Models\customer\customer;
use Retailcore\Customer\Models\customer\customer_address_detail;
use Retailcore\Company_Profile\Models\company_profile\company_profile;
use Maatwebsite\Excel\Facades\Excel;

class ProductgstPerwiseReportController  extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date  =  date("Y-m-d");
        $sales = sales_bill::where('company_id',Auth::user()->company_id)
            ->whereRaw("STR_TO_DATE(sales_bills.bill_date,'%d-%m-%Y') between '$date' and '$date'")
            ->with('reference')
            ->where('deleted_at','=',NULL)
            ->orderBy('sales_bill_id', 'DESC')
            ->paginate(10);

        $gst_slabs = sales_product_detail::select('cgst_percent','sgst_percent','igst_percent')
            ->where('company_id',Auth::user()->company_id)
            ->orderBy('igst_percent', 'ASC')
            ->groupBy('igst_percent')
            ->get();


         $returnbill  =  return_bill::where('company_id',Auth::user()->company_id)
                    ->whereRaw("STR_TO_DATE(return_bills.bill_date,'%d-%m-%Y') between '$date' and '$date'")
                    ->with('reference')
                    ->where('deleted_at','=',NULL)
                    ->orderBy('return_bill_id', 'DESC')
                    ->paginate(10);

        $state_id  =  company_profile::select('state_id','tax_type','tax_title','currency_title')->where('company_id',Auth::user()->company_id)->get();
        $company_state   = $state_id[0]['state_id'];
        $tax_type        = $state_id[0]['tax_type'];
        $tax_title       = $state_id[0]['tax_title'];
        $taxname         = $tax_type==1?$tax_title:'IGST';



        return view('salesreport::productgst_perwise_report',compact('gst_slabs','sales','company_state','returnbill','tax_type','taxname'));


    }
    function gstwise_billdetail(Request $request)
    {


        if($request->ajax())
        {
            
            $data            =      $request->all();
           

            $sort_by        =       $data['sortby'];
            $sort_type      =       $data['sorttype'];
            $query          =       isset($data['query']) ? $data['query']  : '';

            $state_id  =  company_profile::select('state_id','tax_type','tax_title','currency_title')->where('company_id',Auth::user()->company_id)->get();
            $company_state   = $state_id[0]['state_id'];
            $tax_type        = $state_id[0]['tax_type'];
            $tax_title       = $state_id[0]['tax_title'];
            $taxname         = $tax_type==1?$tax_title:'IGST';


             $gst_slabs = sales_product_detail::select('cgst_percent','sgst_percent','igst_percent')
            ->where('company_id',Auth::user()->company_id)
            ->where('deleted_at','=',NULL)
            ->orderBy('igst_percent', 'ASC')
            ->groupBy('igst_percent')
            ->get();

            $squery           =      sales_bill::select('*');
            $rquery           =      return_bill::select('*');

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

                 // $squery->where('bill_date','>=',$query['from_date'])->where('bill_date','<=',$query['to_date']);
                 // $rquery->whereRaw("Date(return_bills.created_at) between '$rstart' and '$rend'");
            }

        
            $sales = $squery->with('reference')->where('company_id',Auth::user()->company_id)->orderBy($sort_by, $sort_type)
                   ->where('deleted_at','=',NULL)->paginate(10);
            $returnbill = $rquery->with('reference')->where('company_id',Auth::user()->company_id)->orderBy('return_bill_id', 'DESC')
                   ->where('deleted_at','=',NULL)->paginate(10);
        
            return view('salesreport::productgst_perwise_reportdata',compact('sales','gst_slabs','company_state','returnbill','tax_type','taxname'))->render();
        }
            
                
    }

    public function exportgstwise_details(Request $request)
    {

        $data            =      $request->all();
        $from_date       =      $request->from_date;
        $to_date         =      $request->to_date;
        $customerid      =      $request->customerid;
        $billno          =      $request->bill_no;
        $reference_name  =      $request->reference_name;
           


            $state_id  =  company_profile::select('state_id','tax_type','tax_title','currency_title')->where('company_id',Auth::user()->company_id)->get();
            $company_state   = $state_id[0]['state_id'];
            $tax_type        = $state_id[0]['tax_type'];
            $tax_title       = $state_id[0]['tax_title'];
            $taxname         = $tax_type==1?$tax_title:'IGST';


             $gst_slabs = sales_product_detail::select('cgst_percent','sgst_percent','igst_percent')
            ->where('company_id',Auth::user()->company_id)
            ->where('deleted_at','=',NULL)
            ->orderBy('igst_percent', 'ASC')
            ->groupBy('igst_percent')
            ->get();

            $squery           =      sales_bill::select('*');
            $rquery           =      return_bill::select('*');

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

                 $result = customer::select('customer_id')
                 ->where('company_id',Auth::user()->company_id)
                 ->where('deleted_at','=',NULL)
                 ->where('customer_name', 'LIKE', "%$cus_name%")
                 ->orwhere('customer_mobile', 'LIKE', "%$cus_mobile%")
                 ->get();
                 
                 $squery->whereIn('customer_id',$result);
                 $rquery->whereIn('customer_id',$result);
            }
            if($billno != '')
            {
                 $squery->where('bill_no', 'like', '%'.$billno.'%');

                 $tbill_no  =  sales_bill::select('sales_bill_id')->where('bill_no', 'like', '%'.$billno.'%')->where('company_id',Auth::user()->company_id)->get();
                 $rquery->whereIn('sales_bill_id', $tbill_no);
            }
            if($from_date != '' && $to_date != '')
            {
                
                 $rstart           =      date("Y-m-d",strtotime($from_date));
                 $rend             =      date("Y-m-d",strtotime($to_date));

                 $squery->whereRaw("STR_TO_DATE(sales_bills.bill_date,'%d-%m-%Y') between '$rstart' and '$rend'");
                 $rquery->whereRaw("STR_TO_DATE(return_bills.bill_date,'%d-%m-%Y') between '$rstart' and '$rend'");

                 // $squery->where('bill_date','>=',$from_date)->where('bill_date','<=',$to_date);
                 // $rquery->whereRaw("Date(return_bills.created_at) between '$rstart' and '$rend'");
            }
            if($reference_name != '')
            {
                 $rresult = reference::select('reference_id')
                 ->where('company_id',Auth::user()->company_id)
                 ->where('deleted_at','=',NULL)
                 ->where('reference_name', 'LIKE', "%$reference_name%")
                 ->get();

                 $squery->whereIn('reference_id',$rresult);
                 $rquery->whereIn('reference_id',$rresult);
            }

        
            $sales = $squery->with('reference')->where('company_id',Auth::user()->company_id)->with('sales_product_detail')->orderBy('sales_bill_id', 'DESC')->where('deleted_at','=',NULL)->get();

            $returnbill = $rquery->with('reference')->where('company_id',Auth::user()->company_id)->with('return_product_detail')->orderBy('return_bill_id', 'DESC')->where('deleted_at','=',NULL)->get();

             $rowheadings  = [];  
             $header       = [];
             $header[]  =  'Bill No.';  
             $header[]  =  'Bill Date';  
             $header[]  =  'Customer'; 
            foreach($gst_slabs AS $gstkey=>$gst_value)
            {
                $header[]  =  $gst_value['igst_percent'].'% Taxable';
                if($tax_type==1)
                {
                    $header[]  =  $gst_value['igst_percent'].'% '.$taxname; 
                }
                else
                {
                    $header[]  =  $gst_value['cgst_percent'].'% CGST';
                    $header[]  =  $gst_value['sgst_percent'].'% SGST';
                    $header[]  =  $gst_value['igst_percent'].'% IGST'; 
                }

                
            }
            $header[]  =  'Total Taxable'; 
            if($tax_type==1)
            {
                $header[]  =  'Total '.$taxname;
            }
            else
            {
                 $header[]  =  'Total CGST';
                 $header[]  =  'Total SGST';
                 $header[]  =  'Total IGST';
            }
             
             $header[]  =  'Total Amount';   
             $header[]  =  'Reference';         

            $overallsales   =  [];
            

            $overallsales['sales']        =  $sales;
            $overallsales['returnbill']   =  $returnbill;

          return Excel::download(new gstwiseBilling_export($overallsales, $header), 'GST%Wise-Report.xlsx');
       

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
     * @param  \App\roomgst_perwise_report  $roomgst_perwise_report
     * @return \Illuminate\Http\Response
     */
    public function show(roomgst_perwise_report $roomgst_perwise_report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\roomgst_perwise_report  $roomgst_perwise_report
     * @return \Illuminate\Http\Response
     */
    public function edit(roomgst_perwise_report $roomgst_perwise_report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\roomgst_perwise_report  $roomgst_perwise_report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, roomgst_perwise_report $roomgst_perwise_report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\roomgst_perwise_report  $roomgst_perwise_report
     * @return \Illuminate\Http\Response
     */
    public function destroy(roomgst_perwise_report $roomgst_perwise_report)
    {
        //
    }
}
