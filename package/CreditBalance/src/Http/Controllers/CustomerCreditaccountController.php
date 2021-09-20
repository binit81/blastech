<?php

namespace Retailcore\CreditBalance\Http\Controllers;

use Illuminate\Http\Request;
use Retailcore\CreditBalance\Models\customer_creditaccount;
use Retailcore\CreditBalance\Models\customer_creditreceipt;
use Retailcore\CreditBalance\Models\customer_creditreceipt_detail;
use Retailcore\CreditBalance\Models\customer_crerecp_payment;
use App\Http\Controllers\Controller;
use Retailcore\Sales\Models\sales_bill;
use Retailcore\Sales\Models\sales_product_detail;
use Retailcore\Sales\Models\sales_bill_payment_detail;
use Retailcore\Customer\Models\customer\customer;
use Retailcore\Customer\Models\customer\customer_address_detail;
use Retailcore\Sales\Models\payment_method;
use Retailcore\CreditBalance\Models\creditaccountreport_export;
use DB;
use Auth;
use Maatwebsite\Excel\Facades\Excel;

class CustomerCreditaccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $creditreceipt  = array();
        $customerbaldata = customer_creditaccount::select("*",DB::raw("COUNT(*) as totalinvoices"),DB::raw("SUM(credit_amount) as totalcreditamount"),DB::raw("SUM(balance_amount) as totalbalance"))
        ->withCount([
                    'totalcustomer_creditreceipt_detail as recdamt' => function($fquery) {
                        $fquery->select(DB::raw('SUM(payment_amount)'));  
                    }
                ])
          ->groupBy('customer_id')
          ->with([
                'customer_creditaccount' => function($fquery) {
                     $fquery->select('*');
                     $fquery->withCount([
                        'customer_creditreceipt_detail as receiptrecdamt' => function($q) {
                            $q->select(DB::raw('SUM(payment_amount)'));  
                        }
                    ]);
                   
                }
            ])
          ->orderBy('customer_creditaccount_id','DESC')
          ->where('company_id',Auth::user()->company_id)
          ->where('deleted_at','=',NULL)
          ->paginate(10);


        return view('creditbalance::customer_credit_summary',compact('customerbaldata','creditreceipt'));
    }
  public function view_creditreceipt_popup(Request $request)
  {

        if($request->ajax())
        {

            $creditreceipt = customer_creditreceipt_detail::where('deleted_at','=',NULL)
                ->where('customer_creditaccount_id','=',$request->billno)
                ->select('*')
                ->with('customer_creditreceipt')
                ->with('customer_crerecp_payment')
                ->get();
              
                 return view('creditbalance::view_creditreceipt_popup',compact('creditreceipt'));

         }
        
    }
    function customer_balance_record()
    {
         $customerbaldata = customer_creditaccount::select("*",DB::raw("SUM(credit_amount) as totalcreditamount"),DB::raw("SUM(balance_amount) as totalbalance"),DB::raw("(SELECT SUM(customer_creditreceipt_details.payment_amount) FROM customer_creditreceipt_details WHERE customer_creditreceipt_details.customer_id = customer_creditaccounts.customer_id and deleted_at IS NULL GROUP BY customer_creditreceipt_details.customer_id) as recdamt"))->groupBy('customer_id')->orderBy('customer_creditaccount_id','DESC')->where('company_id',Auth::user()->company_id)->where('deleted_at','=',NULL)->paginate(10);

         $customerbillbaldata = customer_creditaccount::select('*')
                                ->with(['sales_bill' => function($fquery){
                                        $fquery->select('sales_bill_id','bill_no');
                                    }
                                ])
                                ->withCount(['customer_creditreceipt_detail as totalcreditrecd' => function($fquery){
                                        $fquery->select(DB::raw('SUM(payment_amount)'));
                                    }
                                ])
                                ->where('company_id',Auth::user()->company_id)
                                ->where('deleted_at','=',NULL)->paginate(10);

                                // echo '<pre>';
                                // print_r($customerbillbaldata);
                                // exit;
                               

         $payment_methods = payment_method::where('is_active','=','1')->where('payment_method_id','!=',9)->orderBy('payment_method_id', 'asc')->get();

          $receipts = customer_creditreceipt::where('company_id',Auth::user()->company_id)
            ->where('deleted_at','=',NULL)
            ->orderBy('customer_creditreceipt_id', 'DESC')
            ->with('customer')
            ->with('customer_crerecp_payment')
            ->paginate(10);

        return view('creditbalance::customer_balance_record',compact('customerbaldata','payment_methods','receipts','customerbillbaldata'));
    }
    function datewise_creditbaldetail(Request $request)
    {
        if($request->ajax())
        {
            $creditreceipt   = array();
            $data            =      $request->all();
           

            $sort_by = $data['sortby'];
            $sort_type = $data['sorttype'];
            $query = isset($data['query']) ? $data['query']  : '';
       
       $squery = customer_creditaccount::select("*",DB::raw("COUNT(*) as totalinvoices"),DB::raw("SUM(credit_amount) as totalcreditamount"),DB::raw("SUM(balance_amount) as totalbalance"))
        ->withCount([
                    'totalcustomer_creditreceipt_detail as recdamt' => function($fquery) {
                        $fquery->select(DB::raw('SUM(payment_amount)'));  
                    }
                ]);


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
                 ->where('customer_mobile', 'LIKE', "%$cus_mobile%")
                 ->get();

                 $squery->whereIn('customer_id',$result);
                
            }

           $customerbaldata=$squery->groupBy('customer_id')
                            ->where('company_id',Auth::user()->company_id)
                            ->orderBy('customer_creditaccount_id','DESC')
                            ->where('deleted_at','=',NULL)->paginate(10);
         
            return view('creditbalance::customer_credit_summarydata',compact('customerbaldata','creditreceipt'))->render();
          }
    }

    public function exportcreditpayment_details(Request $request)
    {

            $data            =      $request->all(); 
            $customerid      =      $data['customerid'];

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

        $query = customer_creditaccount::select("*",DB::raw("COUNT(*) as totalinvoices"),DB::raw("SUM(credit_amount) as totalcreditamount"),DB::raw("SUM(balance_amount) as totalbalance"))
                ->withCount([
                            'totalcustomer_creditreceipt_detail as recdamt' => function($fquery) {
                                $fquery->select(DB::raw('SUM(payment_amount)'));  
                            }
                        ])
                  ->groupBy('customer_id')
                  ->with([
                        'customer_creditaccount' => function($fquery) {
                             $fquery->select('*');
                             $fquery->withCount([
                                'customer_creditreceipt_detail as receiptrecdamt' => function($q) {
                                    $q->select(DB::raw('SUM(payment_amount)'));  
                                }
                            ]);
                           
                        }
                    ])
                  ->orderBy('customer_creditaccount_id','DESC')
                  ->where('company_id',Auth::user()->company_id);
                  if($customerid!='')
                  {
                      $query->whereIn('customer_id',$result);
                  }

                  $creditaccount  = $query->where('deleted_at','=',NULL)->get();

                 
                   $header       = [];
                   $header[]  =  'Customer Name';
                   $header[]  =  'Mobile No.';
                   $header[]  =  'No. of Invoices'; 
                   $header[]  =  'Unpaid Amount';
                   $header[]  =  'Received Amount';
                   $header[]  =  'Balance Amount';
                   $overallcredit   =  [];
                   $overallcredit['creditaccount']   =  $creditaccount;
                

                  $excel = Excel::download(new creditaccountreport_export($overallcredit, $header), "CreditAccount-Export.xlsx");
                  return $excel;

       

    }
    public function customer_credit_ac(Request $request)
    {

        $last_invoice_id = customer_creditreceipt::where('company_id',Auth::user()->company_id)->get()->max('customer_creditreceipt_id');

        $f1     =    (date('m')<'04') ? date('y',strtotime('-1 year')) : date('y');
        $f2     =    (date('m')>'03') ? date('y',strtotime('+1 year')) : date('y');


        if($last_invoice_id == '')
        {
            $last_invoice_id = 1;
        }
        else
        {
            $last_invoice_id = $last_invoice_id  + 1;
        }
        
        
        $invoiceno         =       'cus-'.$last_invoice_id.'/'.$f1.'-'.$f2;  


        $payment_methods =      payment_method::where('is_active','=','1')->where('payment_method_id','!=',9)->orderBy('payment_order','ASC')->get();
        $cusid  = decrypt($request->id);
       

        $cuscreditdata = customer_creditaccount::where([
            ['customer_id','=',$cusid],
            ['company_id',Auth::user()->company_id]])
            ->select("customer_creditaccounts.*",DB::raw("(SELECT SUM(customer_creditreceipt_details.payment_amount) FROM customer_creditreceipt_details WHERE customer_creditreceipt_details.customer_creditaccount_id = customer_creditaccounts.customer_creditaccount_id and deleted_at IS NULL GROUP BY customer_creditreceipt_details.customer_creditaccount_id) as recdamt"))
            ->get();

            //dd($cuscreditdata);

        return view('creditbalance::customer_credit_ac',compact('cuscreditdata','payment_methods','invoiceno','cusid'));
       
    }

    public function save_customer_creditdetails(Request $request)
    {
        $data = $request->all();

        $userId = Auth::User()->user_id;
        $company_id = Auth::User()->company_id;


        $created_by = $userId;





       customer_creditreceipt::where('customer_creditreceipt_id',$data[1]['customer_creditreceipt_id'])->update(array(
            'modified_by' => Auth::User()->user_id,
            'updated_at' => date('Y-m-d H:i:s')
          ));


        $creditreceipt = customer_creditreceipt::updateOrCreate(
            ['customer_creditreceipt_id' => $data[1]['customer_creditreceipt_id'], 'company_id'=>$company_id,],
            ['customer_id'=>$data[1]['customer_id'],
            'receipt_no'=>$data[1]['invoice_no'],
                'receipt_date'=>$data[1]['invoice_date'],
                'remarks'=>$data[1]['remarks'],
                'total_amount'=>$data[1]['total_amount'],
                'created_by' =>$created_by,
                'is_active' => "1"
            ]
        );

       
       $customer_creditreceipt_id = $creditreceipt->customer_creditreceipt_id;

        $f1     =    (date('m')<'04') ? date('y',strtotime('-1 year')) : date('y');
        $f2     =    (date('m')>'03') ? date('y',strtotime('+1 year')) : date('y');

       
        
        $finalinvoiceno          =       'cus-'.$customer_creditreceipt_id.'/'.$f1.'-'.$f2;  

        if($data[1]['customer_creditreceipt_id']=='' || $data[1]['customer_creditreceipt_id']==null)
        {  

         customer_creditreceipt::where('customer_creditreceipt_id',$customer_creditreceipt_id)->update(array(
            'receipt_no' => $finalinvoiceno
         ));
       }
    
       customer_creditreceipt_detail::where('customer_creditreceipt_id',$customer_creditreceipt_id)->update(array(
            'modified_by' => Auth::User()->user_id,
            'updated_at' => date('Y-m-d H:i:s')
        ));


        customer_crerecp_payment::where('customer_creditreceipt_id',$customer_creditreceipt_id)->update(array(
            'deleted_by' => Auth::User()->user_id,
            'deleted_at' => date('Y-m-d H:i:s'),
            'total_bill_amount'=>0
        ));
    

    $paymentanswers     =    array();

         foreach($data[2] AS $key=>$value2)
          {
              
                
                $paymentanswers['customer_creditreceipt_id']     =  $customer_creditreceipt_id;
                $paymentanswers['total_bill_amount']             =  $value2['value'];
                $paymentanswers['payment_method_id']             =  $value2['id'];
                $paymentanswers['created_by']                    =  Auth::User()->user_id;
                $paymentanswers['deleted_at'] =  NULL;
                $paymentanswers['deleted_by'] =  NULL;
                
            
           
       
           $paymentdetail = customer_crerecp_payment::updateOrCreate(
               ['customer_creditreceipt_id' => $customer_creditreceipt_id,'customer_crerecp_payment_id'=>$value2['sales_payment_id'],],
               $paymentanswers);


            
    }

   $total_amount          =     $data[1]['total_amount'];
    
        $productdetail     =    array();

        $mcamera        =   explode(',',$data['1']['mCamera']);
        $countarray     =     count($mcamera);

          if($countarray ==1)
          {
            
                    foreach($data[0] AS $billkey=>$billvalue)
                    {

                       if($billvalue['customer_creditaccount_id']!='')
                      {

                              
                              
                              $productdetail['customer_creditaccount_id']            =    $billvalue['customer_creditaccount_id'];
                              $productdetail['customer_id']                          =    $data[1]['customer_id'];
                              $productdetail['credit_amount']                        =    $billvalue['credit_amount'];
                              $productdetail['created_by']                           =     Auth::User()->user_id;
                              $productdetail['is_active']                            =     "1";


                        
                           $balance_amt    =    $billvalue['credit_amount'] -  $data['1']['total_amount'];

                                customer_creditaccount::where('customer_creditaccount_id',$billvalue['customer_creditaccount_id'])->update(array('balance_amount' => $balance_amt
                              ));  


                                $productdetail['balance_amount']                    =   $balance_amt;
                                $productdetail['payment_amount']                    =   $data['1']['total_amount'];

                        

                           $billproductdetail = customer_creditreceipt_detail::updateOrCreate(
                           ['customer_creditreceipt_id' => $customer_creditreceipt_id,'customer_creditreceipt_detail_id'=>$billvalue['customer_creditreceipt_detail_id'],],
                           $productdetail);


                    }
                    }

          }
          else
          {
                foreach($data[0] AS $billkey=>$billvalue)
                    {

                       if($billvalue['customer_creditaccount_id']!='')
                      {

                              
                              
                              $productdetail['customer_creditaccount_id']            =    $billvalue['customer_creditaccount_id'];
                              $productdetail['customer_id']                          =    $data[1]['customer_id'];
                              $productdetail['credit_amount']                        =    $billvalue['credit_amount'];
                              $productdetail['created_by']                           =     Auth::User()->user_id;
                              $productdetail['is_active']                            =     "1";


                            if($total_amount > $billvalue['credit_amount'])
                            {
                                $count                              =    $total_amount - $billvalue['credit_amount'];
                                $balance_amt                        =    $billvalue['credit_amount'] -  $billvalue['credit_amount'];
                                $payment_amt                        =    $billvalue['credit_amount'];
                                $productdetail['balance_amount']    =    $billvalue['credit_amount'] -  $billvalue['credit_amount'];
                                $productdetail['payment_amount']    =    $billvalue['credit_amount'];

                                customer_creditaccount::where('customer_creditaccount_id',$billvalue['customer_creditaccount_id'])->update(array('balance_amount' => $balance_amt)); 
                         

                               $billproductdetail = customer_creditreceipt_detail::updateOrCreate(
                               ['customer_creditreceipt_id' => $customer_creditreceipt_id,'customer_creditreceipt_detail_id'=>$billvalue['customer_creditreceipt_detail_id'],],
                               $productdetail);

                            }
                            else
                            {
                                    $count              =        $total_amount - $billvalue['credit_amount'];

                                    $balance_amt        =        $billvalue['credit_amount'] - $total_amount;
                                    $payment_amt        =        $total_amount;
                                    $productdetail['balance_amount']    =    $billvalue['credit_amount'] - $total_amount;
                                    $productdetail['payment_amount']    =    $total_amount;

                                     customer_creditaccount::where('customer_creditaccount_id',$billvalue['customer_creditaccount_id'])->update(array('balance_amount' => $balance_amt)); 
                        

                               $billproductdetail = customer_creditreceipt_detail::updateOrCreate(
                               ['customer_creditreceipt_id' => $customer_creditreceipt_id,'customer_creditreceipt_detail_id'=>$billvalue['customer_creditreceipt_detail_id'],],
                               $productdetail);
                            }
                            

                               
                            if($count > 0)
                            {
                                $total_amount          =       $total_amount  - $billvalue['credit_amount'];
                               
                            }
                            if($count <= 0)
                            {
                               
                                 return json_encode(array("Success"=>"True","Message"=>"Payment has been successfully Received.","url"=>route('customer_credit_ac', ['id' => encrypt($data[1]['customer_id'])])));
                            }



                    }



                    }
          }
                

  

     


        if($billproductdetail)
        {
            
           if($data[1]['customer_creditreceipt_id'] != '')
          {   
              return json_encode(array("Success"=>"True","Message"=>"Payment has been successfully Received.","url"=>route('customer_credit_ac', ['id' => encrypt($data[1]['customer_id'])])));
          }
          else
          {
              return json_encode(array("Success"=>"True","Message"=>"Payment has been successfully Received.","url"=>route('customer_credit_ac', ['id' => encrypt($data[1]['customer_id'])])));
          }

               

           
        }
        else
        {
            return json_encode(array("Success"=>"False","Message"=>"Something Went Wrong"));
        }
        //return back()->withInput();

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
     * @param  \App\customer_creditaccount  $customer_creditaccount
     * @return \Illuminate\Http\Response
     */
    public function show(customer_creditaccount $customer_creditaccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\customer_creditaccount  $customer_creditaccount
     * @return \Illuminate\Http\Response
     */
    public function edit(customer_creditaccount $customer_creditaccount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\customer_creditaccount  $customer_creditaccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, customer_creditaccount $customer_creditaccount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\customer_creditaccount  $customer_creditaccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(customer_creditaccount $customer_creditaccount)
    {
        //
    }
}
