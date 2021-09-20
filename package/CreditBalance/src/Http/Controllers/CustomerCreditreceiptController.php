<?php

namespace Retailcore\CreditBalance\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Retailcore\Sales\Models\payment_method;
use Retailcore\CreditBalance\Models\customer_creditaccount;
use Retailcore\CreditBalance\Models\customer_creditreceipt;
use Retailcore\CreditBalance\Models\customer_creditreceipt_detail;
use Retailcore\CreditBalance\Models\customer_crerecp_payment;
use Retailcore\Customer\Models\customer\customer;
use Auth;

class CustomerCreditreceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

         $payment_methods = payment_method::where('is_active','=','1')->where('payment_method_id','!=',9)->orderBy('payment_method_id', 'asc')->get();

          $receipts = customer_creditreceipt::where('company_id',Auth::user()->company_id)
            ->where('deleted_at','=',NULL)
            ->orderBy('customer_creditreceipt_id', 'DESC')
            ->with('customer')
            ->with('customer_crerecp_payment')
            ->paginate(10);

         return view('creditbalance::view_customer_creditreceipt',compact('payment_methods','receipts'));
    }

    public function cbillno_search(Request $request)
    {

        if($request->search_val !='')
        {

            $json   = [];
            $result = customer_creditreceipt::select('receipt_no')
                ->where('receipt_no', 'LIKE', "%$request->search_val%")
                ->where('company_id',Auth::user()->company_id)->get();


            if(!empty($result))
            {
           
                foreach($result as $billkey=>$billvalue){


                      $json[] = $billvalue['receipt_no'];
                      
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


    public function viewreceipt_data(Request $request)
    {

     if($request->ajax())
     {
        
        $payment_methods = payment_method::where('is_active','=','1')->where('payment_method_id','!=',9)->orderBy('payment_method_id', 'asc')->get();

          $receipts = customer_creditreceipt::where('company_id',Auth::user()->company_id)
            ->where('deleted_at','=',NULL)
            ->orderBy('customer_creditreceipt_id', 'DESC')
            ->with('customer')
            ->with('customer_crerecp_payment')
            ->paginate(10);


             
           
        return view('creditbalance::view_customer_creditreceiptdata',compact('payment_methods','receipts'));

    }
        
    }

    function datewise_cuscreditdetail(Request $request)
    {
        if($request->ajax())
        {
            
            $payment_methods =      payment_method::where('is_active','=','1')->where('payment_method_id','!=',9)->get();

            $data            =      $request->all();
           

            $sort_by = $data['sortby'];
            $sort_type = $data['sorttype'];
            $query = isset($data['query']) ? $data['query']  : '';

            $cquery           =      customer_creditreceipt::select('*');

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
                 $cquery->where('receipt_no', 'like', '%'.$query['billno'].'%');
            }
            if(isset($query) && $query != '' && $query['from_date'] != '' && $query['to_date'] != '')
            {
                 $rstart           =      date("Y-m-d",strtotime($query['from_date']));
                 $rend             =      date("Y-m-d",strtotime($query['to_date']));

                 $cquery->whereRaw("STR_TO_DATE(receipt_date,'%d-%m-%Y') between '$rstart' and '$rend'");
                 // $cquery->where('receipt_date','>=',$query['from_date'])->where('receipt_date','<=',$query['to_date']);
            }
           

           
            $receipts = $cquery->where('company_id',Auth::user()->company_id)->orderBy($sort_by, $sort_type)->where('deleted_by','=',NULL)->with('customer')
            ->with('customer_crerecp_payment')->paginate(10);

            
            
            return view('creditbalance::view_customer_creditreceiptdata',compact('payment_methods','receipts'));
        }
            
                
    }

    public function receipt_delete(request $request)
    {
        $userId = Auth::User()->user_id;

        
        $bill_delete =  customer_creditreceipt::where('customer_creditreceipt_id', $request->deleted_id)
            ->update([
         'deleted_by' => $userId,
        'deleted_at' => date('Y-m-d H:i:s')
        ]);
        

        $billproduct_data = customer_creditreceipt_detail::select('*')
        ->where('customer_creditreceipt_id',$request->deleted_id)
        ->get();

        foreach($billproduct_data as $billdatakey=>$billdatavalue)
        {
           
            $productqty    =  customer_creditaccount::select('balance_amount')
                    ->where('customer_creditaccount_id',$billdatavalue['customer_creditaccount_id'])
                    ->get();

            $balance_amount   =    $productqty[0]['balance_amount'] +  $billdatavalue['payment_amount'];

            customer_creditaccount::where('customer_creditaccount_id',$billdatavalue['customer_creditaccount_id'])->update(array(
                      'balance_amount' => $balance_amount)); 

        }  
        $billdata_delete =  customer_creditreceipt_detail::where('customer_creditreceipt_id', $request->deleted_id)
            ->update([
         'deleted_by' => $userId,
        'deleted_at' => date('Y-m-d H:i:s')
        ]);
       
        $billpayment_delete =  customer_crerecp_payment::where('customer_creditreceipt_id', $request->deleted_id)
            ->update([
         'deleted_by' => $userId,
        'deleted_at' => date('Y-m-d H:i:s')
        ]);
       
        if($billdata_delete)
        {
            return json_encode(array("Success"=>"True","Message"=>"Receipt has been successfully deleted.!"));
        }
        else
        {
            return json_encode(array("Success"=>"False","Message"=>"Something Went Wrong!"));
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
     * @param  \App\customer_creditreceipt  $customer_creditreceipt
     * @return \Illuminate\Http\Response
     */
    public function show(customer_creditreceipt $customer_creditreceipt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\customer_creditreceipt  $customer_creditreceipt
     * @return \Illuminate\Http\Response
     */
    public function edit(customer_creditreceipt $customer_creditreceipt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\customer_creditreceipt  $customer_creditreceipt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, customer_creditreceipt $customer_creditreceipt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\customer_creditreceipt  $customer_creditreceipt
     * @return \Illuminate\Http\Response
     */
    public function destroy(customer_creditreceipt $customer_creditreceipt)
    {
        //
    }
}
