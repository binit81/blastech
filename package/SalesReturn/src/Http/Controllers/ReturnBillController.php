<?php

namespace Retailcore\SalesReturn\Http\Controllers;

use Retailcore\SalesReturn\Models\return_bill;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Retailcore\Sales\Models\sales_bill;
use Retailcore\Sales\Models\sales_product_detail;
use Retailcore\Sales\Models\sales_bill_payment_detail;
use Retailcore\Sales\Models\reference;
use Retailcore\CreditBalance\Models\customer_creditaccount;
use Retailcore\Products\Models\product\product;
use Retailcore\GST_Slabs\Models\GST_Slabs\gst_slabs_master;
use Retailcore\Customer\Models\customer\customer\customer;
use Retailcore\Customer\Models\customer\customer_address_detail;
use Retailcore\Sales\Models\payment_method;
use App\state;
use App\country;
use Retailcore\Company_Profile\Models\company_profile\company_profile;
use Auth;
use Retailcore\SalesReturn\Models\return_product_detail;
use Retailcore\SalesReturn\Models\return_bill_payment;
use Retailcore\CreditNote\Models\customer_creditnote;
use Retailcore\SalesReturn\Models\returnbill_product;
use Retailcore\CreditBalance\Models\customer_creditreceipt;
use Retailcore\CreditBalance\Models\customer_creditreceipt_detail;
use Retailcore\CreditBalance\Models\customer_crerecp_payment;
use Retailcore\CreditNote\Models\creditnote_payment;
use DB;

class ReturnBillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $state = state::all();
        $country = country::all();
        
         $payment_methods = payment_method::where('is_active','=','1')->where('payment_method_id','!=',9)->orderBy('payment_order','ASC')->get();

          $chargeslist      =   product::select('product_id','product_name') 
                              ->where('company_id',Auth::user()->company_id)
                              ->where('item_type','=',2)
                              ->get();

        
         return view('salesreturn::sales_return',compact('payment_methods','state','country','chargeslist'));
    }

    public function billno_search(Request $request)
    {

        if($request->search_val !='')
        {

            $json = [];
            $result = sales_bill::select('bill_no')
                ->where('bill_no', 'LIKE', "%$request->search_val%")
                ->where('company_id',Auth::user()->company_id)->get();

           
           

            if(!empty($result))
            {
           
                foreach($result as $billkey=>$billvalue){


                      $json[] = $billvalue['bill_no'];
                      
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

    public function returnbill_data(Request $request)
    {

        $returndate    =   company_profile::select('return_days','billtype')
                              ->where('company_id',Auth::user()->company_id)->get(); 
       

           $returndays    =    $returndate[0]['return_days'];
           $bill_type     =    $returndate[0]['billtype'];
          

           if($returndays == '' || $returndays == null)
           {
             
              return json_encode(array("Success"=>"False","Message"=>"Return Policy is not Defined. Please update it through Company Profile"));
           }
           else
           {

                    $to_date       =    date("Y-m-d");

                    $date          =    date_create($to_date);

                    date_sub($date,date_interval_create_from_date_string("".$returndays." days"));

                    $from_date      =   date_format($date,"Y-m-d");
                    $bill_no = $request->bill_no;
                  
              $bill_data = sales_bill::where([
                  ['bill_no','=',$bill_no],
                  ['company_id',Auth::user()->company_id]])
                  ->whereRaw("Date(sales_bills.created_at) between '$from_date' and '$to_date'")
                  ->with('customer')
                  ->with('customer_address_detail')
                  ->with('reference')
                  ->with('sales_bill_payment_detail.payment_method')
                  ->with('customer_creditaccount')
                  ->select('*')
                  ->first();

                 
                   $billproduct_data = sales_product_detail::where([
                  ['sales_bill_id','=',$bill_data['sales_bill_id']],
                  ['company_id',Auth::user()->company_id]])
                   ->with(['batchprice_master' => function ($bquery) {
                        $bquery->select('price_master_id','batch_no');
                    }])
                   ->with(['product' => function ($pquery) {
                        $pquery->select('product_id','product_name', 'product_system_barcode','supplier_barcode','sku_code');
                    }])
                  ->with(['return_product_detail' => function ($query) {
                        $query->select('sales_products_detail_id','qty', 'inwardids','inwardqtys');
                    }])
                  ->withCount([
                    'return_product_detail as totalreturnqty' => function($fquery) {
                        $fquery->select(DB::raw('SUM(qty)'));
                    }
                    ])
                   ->withCount([
                    'return_product_detail as totalreccharges' => function($fquery) {
                        $fquery->select(DB::raw('SUM(mrp)'));
                        $fquery->where('product_type','=',2);
                    }
                    ])
                  ->get();

// echo '<pre>';
// print_r($billproduct_data);
// echo '</pre>';
// exit;

                if($bill_data == null)
                {
                    return json_encode(array("Success"=>"Null","Message"=>"Sales return not Allowed after ".$returndays." Days from Purchase date"));
                }
                else
                {
                    return json_encode(array("Success"=>"True","Data"=>$bill_data,"ProductData"=>$billproduct_data,"Billtype"=>$bill_type));
                }

                
                  
            }


    }
    public function returnbillsecond_data(Request $request)
    {
        
        $returndate    =   company_profile::select('return_days','billtype')
                              ->where('company_id',Auth::user()->company_id)->get(); 
        
           $returndays    =    $returndate[0]['return_days'];
           $bill_type     =    $returndate[0]['billtype'];
        

           if($returndays == '' || $returndays == null)
           {
             
              return json_encode(array("Success"=>"False","Message"=>"Return Policy is not Defined in Master"));
           }
           else
           {
                    $productsearch = $request->productsearch;



                    if(strpos($productsearch, '_') !== false)
                    {
                    $proddata       =   explode('_',$productsearch);
                    $prod_barcode   =  $proddata[0];
                    $prod_name      =  $proddata[1];
                    }
                    else
                    {
                    $prod_barcode   =  $productsearch;
                    $prod_name      =  $productsearch;
                    }


                    $query = sales_product_detail::select('sales_bill_id')
                    ->where('company_id',Auth::user()->company_id)
                    ->where('deleted_at','=',NULL);

                    $to_date       =    date("Y-m-d");

                    $date          =    date_create($to_date);

                    date_sub($date,date_interval_create_from_date_string("".$returndays." days"));

                    $from_date      =   date_format($date,"Y-m-d");



                    if($productsearch!='')
                    {
                    $product = product::select('product_id')
                    ->where('product_system_barcode','LIKE', "%$prod_barcode%")
                    ->orWhere('supplier_barcode','LIKE', "%$prod_barcode%")
                    ->orWhere('product_name','LIKE',"%$prod_name%")
                    ->where('company_id',Auth::user()->company_id)
                    ->get();

                    $query->whereIn('product_id',$product);
                    }
                    if($from_date!='' && $to_date!='')
                    {
                    $query->whereRaw("Date(sales_product_details.created_at) between '$from_date' and '$to_date'");
                    }

                    $salesid = $query->get();


                    $bill_data = sales_bill::where('company_id',Auth::user()->company_id)
                    ->whereIn('sales_bill_id',$salesid)
                    ->with('customer')
                    ->with('sales_product_detail.product')
                    ->with('customer_creditaccount')
                    ->select('*')
                    ->get();


                    return json_encode(array("Success"=>"True","Data"=>$bill_data));
            }


    }
    public function returnbilling_create(Request $request)
    {
        $data = $request->all();

        $userId = Auth::User()->user_id;
        $company_id = Auth::User()->company_id;


        $created_by = $userId;

       $cstate_id = company_profile::select('state_id','decimal_points','credit_receipt_prefix','series_type')
                ->where('company_id',Auth::user()->company_id)->get(); 
                
               
         if($data[1]['customer_id'] == '')
         {
              $state_id   =    $cstate_id[0]['state_id'];
         }
         else{

               $custate   =   customer_address_detail::select('state_id')
                ->where('company_id',Auth::user()->company_id)
                ->where('customer_id','=',$data[1]['customer_id'])
                ->get(); 

                if($custate[0]['state_id'] == '' || $custate[0]['state_id'] == null)
                {
                    
                     $state_id   =    $cstate_id[0]['state_id'];
                }
                else
                {
                     $state_id   =    $custate[0]['state_id'];
                }
               
         }   
        if($data[1]['refname'] != '')
         {

              $result = reference::select('reference_id','reference_name')
                ->where('reference_name','=',$data[1]['refname'])
                ->where('company_id',Auth::user()->company_id)->first();

                if($result=='')
                {
                     $refss = reference::updateOrCreate(
                        ['reference_id' => '', 'company_id'=>$company_id,],
                        ['reference_name'=>$data[1]['refname'],
                            'created_by' =>$created_by,
                            'is_active' => "1"
                        ]
                      );

                     $refid   =  $refss->reference_id;
                }
                else
                {
                    $refid   =  $result['reference_id'];
                    
                }

                 
         }
         else
         {
              $refid   =  NULL;
         }
        
         
         $invoice_date            =     date("d-m-Y");
         $selling_after_discount  =     $data[1]['totalwithout_gst'] - $data[1]['roomwisediscount_amount'];

  try {
    DB::beginTransaction(); 


         //$state_id = customer_address_detail::select('state_id')->where('company_id',Auth::user()->company_id)->where('customer_id','=',$data[1]['customer_id'])->first();

          return_bill::where('return_bill_id',$data[1]['return_bill_id'])->update(array(
            'modified_by' => Auth::User()->user_id,
            'updated_at' => date('Y-m-d H:i:s')
          ));


        $sales = return_bill::updateOrCreate(
            ['return_bill_id' => $data[1]['return_bill_id'], 'company_id'=>$company_id,],
            ['customer_id'=>$data[1]['customer_id'],
            'sales_bill_id'=>$data[1]['sales_bill_id'],
                'bill_date'=>$invoice_date,
                'state_id'=>$state_id,
                'reference_id'=>$refid,
                'total_qty'=>$data[1]['overallqty'],
                'sellingprice_before_discount'=>$data[1]['totalwithout_gst'],
                'discount_percent'=>$data[1]['discount_percent'],
                'discount_amount'=>$data[1]['discount_amount'],
                'productwise_discounttotal'=>$data[1]['roomwisediscount_amount'],
                'sellingprice_after_discount'=>$selling_after_discount,
                'totalbillamount_before_discount'=>$data[1]['sales_total'],
                'total_igst_amount'=>$data[1]['total_igst'],
                'total_cgst_amount'=>$data[1]['total_cgst'],
                'total_sgst_amount'=>$data[1]['total_sgst'],
                'gross_total'=>$data[1]['grand_total'],
                'shipping_charges'=>$data[1]['charges_total'],
                'total_bill_amount'=>$data[1]['ggrand_total'],
                'created_by' =>$created_by,
                'is_active' => "1"
            ]
        );


       $return_bill_id = $sales->return_bill_id;

       

       return_product_detail::where('return_bill_id',$return_bill_id)->update(array(
            'modified_by' => Auth::User()->user_id,
            'updated_at' => date('Y-m-d H:i:s')
        ));

    
        $productdetail     =    array();
        $returnproductdetail     =    array();
       

         foreach($data[0] AS $billkey=>$billvalue)
          {

               if($billvalue['barcodesel']!='')
              {


                      $halfgstper      =     $billvalue['prodgstper']/2;
                      $halfgstamt      =     $billvalue['prodgstamt']/2;
                      // $productdetail['bill_date']                            =    $invoice_date;
                      $productdetail['sales_products_detail_id']             =    $billvalue['sales_product_id'];
                      $productdetail['product_id']                           =    $billvalue['productid'];
                      $productdetail['price_master_id']                      =    $billvalue['price_master_id'];
                      $productdetail['qty']                                  =    $billvalue['qty'];
                      $productdetail['mrp']                                  =    $billvalue['mrp'];
                      $productdetail['sellingprice_before_discount']         =    $billvalue['sellingprice_before_discount'];
                      $productdetail['discount_percent']                     =    $billvalue['discount_percent'];
                      $productdetail['discount_amount']                      =    $billvalue['discount_amount'];
                      $productdetail['sellingprice_after_discount']          =    $billvalue['totalsellingwgst'];
                      $productdetail['overalldiscount_percent']              =    $billvalue['overalldiscount_percent'];
                      $productdetail['overalldiscount_amount']               =    $billvalue['overalldiscount_amount'];
                      $productdetail['overallmrpdiscount_amount']            =    $billvalue['overallmrpdiscount_amount'];
                      $productdetail['sellingprice_afteroverall_discount']   =    $billvalue['totalsellingwgst']-$billvalue['overalldiscount_amount'];
                      $productdetail['cgst_percent']                         =    $halfgstper;
                      $productdetail['cgst_amount']                          =    $halfgstamt;
                      $productdetail['sgst_percent']                         =    $halfgstper;
                      $productdetail['sgst_amount']                          =    $halfgstamt;
                      $productdetail['igst_percent']                         =    $billvalue['prodgstper'];
                      $productdetail['igst_amount']                          =    $billvalue['prodgstamt'];
                      $productdetail['total_amount']                         =    $billvalue['totalamount'];
                      $productdetail['product_type']                         =     1;
                      $productdetail['created_by']                           =     Auth::User()->user_id;

             


                      $returnproductdetail['return_date']                          =    $invoice_date;
                      $returnproductdetail['product_id']                           =    $billvalue['productid'];
                      $returnproductdetail['price_master_id']                      =    $billvalue['price_master_id'];
                      $returnproductdetail['qty']                                  =    $billvalue['qty'];
                     
                      $returnproductdetail['created_by']                           =     Auth::User()->user_id;

                      $oldinwardids       =     explode(',',substr($billvalue['inwardids'],0,-1));
                      $oldinwardqtys      =     explode(',',substr($billvalue['inwardqtys'],0,-1));
                      //print_r($oldinwardids);

                      $restqty            =    $billvalue['qty'];
                      $ccount    =   0;  
                       $icount    =   0;
                       $pcount    =   0;
                       $done      =   0;
                       $firstout  =   0;
                      $rinwardqtys        =    '';
                      $rinwardids        =    '';

                      foreach($oldinwardids as $l=>$lval)
                      {
                        //echo $oldinwardids[$l];

                        if($oldinwardqtys[$l] >= $restqty && $firstout==0)
                            {  
                                  if($done == 0)
                                  {

                                    //echo 'hello';

                                          $rinwardids    .=   $oldinwardids[$l].',';
                                          $rinwardqtys   .=   $restqty.',';
                                      
                                          $pcount++;
                                          $done++;
                                 }
                           }
                           else
                           {
                              if($pcount==0 && $done == 0 && $icount==0)
                              {
                                  
                                 
                                  if($restqty  > $oldinwardqtys[$l])
                                  {
                                    //echo 'bbb';
                                    //echo $restqty;
                                      $rinwardids    .=   $oldinwardids[$l].',';
                                      $rinwardqtys   .=   $oldinwardqtys[$l].',';
                                      $ccount         =   $restqty  - $oldinwardqtys[$l];
                                     
                                  }
                                  else
                                  {
                                    //echo 'ccc';
                                    //echo $restqty;
                                      $rinwardids    .=   $oldinwardids[$l].',';
                                      $rinwardqtys   .=   $restqty.',';
                                      $ccount         =   $restqty  - $oldinwardqtys[$l];
                                      
                                  }


                                   if($ccount > 0)
                                    {
                                       $firstout++;                                      
                                       $restqty   =   $restqty  - $oldinwardqtys[$l];
                                   
                                       
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

                    // echo $rinwardids.'_______________'.$rinwardqtys;
                    // echo '<br>';


                  $productdetail['inwardids']                          =    $rinwardids;
                  $productdetail['inwardqtys']                         =    $rinwardqtys;
                  $returnproductdetail['inwardids']                    =    $rinwardids;
                  $returnproductdetail['inwardqtys']                   =    $rinwardqtys;


                   $billproductdetail = return_product_detail::updateOrCreate(
                   ['return_bill_id' => $return_bill_id,
                    'company_id'=>$company_id,'return_product_detail_id'=>$billvalue['return_product_id'],],
                   $productdetail);

                   $return_product_detail_id = $billproductdetail->return_product_detail_id;
                  

                   $returnbillproductdetail = returnbill_product::updateOrCreate(
                   ['company_id'=>$company_id,'return_product_detail_id'=>$return_product_detail_id,],
                   $returnproductdetail);






                  
      }
     
     
            
    }

      // exit;

     $chargesdetail     =    array();

       
      
         foreach($data[3] AS $chargeskey=>$chargesvalue)
          {
             if(!empty($chargesvalue))
             {
               if($chargesvalue['chargesamt']!='')
              {

                    if($chargesvalue['returnchargesamt']>0 && $chargesvalue['returnchargesamt']!='')
                    {
                      $halfgstper      =     $chargesvalue['csprodgstper']/2;
                      $halfgstamt      =     $chargesvalue['csprodgstamt']/2;
                      // $chargesdetail['bill_date']                            =    $invoice_date;
                      $chargesdetail['sales_products_detail_id']             =    $chargesvalue['csales_product_id'];
                      $chargesdetail['product_id']                           =    $chargesvalue['cproductid'];
                      $chargesdetail['qty']                                  =    $chargesvalue['cqty'];
                      $chargesdetail['mrp']                                  =    $chargesvalue['chargesamt'];
                      $chargesdetail['sellingprice_before_discount']         =    $chargesvalue['chargesamt'];
                      $chargesdetail['sellingprice_after_discount']          =    $chargesvalue['chargesamt'];
                      $chargesdetail['sellingprice_afteroverall_discount']   =    $chargesvalue['chargesamt'];
                      $chargesdetail['cgst_percent']                         =    $halfgstper;
                      $chargesdetail['cgst_amount']                          =    $halfgstamt;
                      $chargesdetail['sgst_percent']                         =    $halfgstper;
                      $chargesdetail['sgst_amount']                          =    $halfgstamt;
                      $chargesdetail['igst_percent']                         =    $chargesvalue['csprodgstper'];
                      $chargesdetail['igst_amount']                          =    $chargesvalue['csprodgstamt'];
                      $chargesdetail['total_amount']                         =    $chargesvalue['ctotalamount'];
                      $chargesdetail['product_type']                         =     2;
                      $chargesdetail['created_by']                           =     Auth::User()->user_id;

                
                 $billchargesdetail = return_product_detail::updateOrCreate(
                   ['return_bill_id' => $return_bill_id,
                    'company_id'=>$company_id,'return_product_detail_id'=>$chargesvalue['creturn_product_id'],],
                   $chargesdetail);
               }
      } 
    }  
  }




  
      

      return_bill_payment::where('return_bill_id',$return_bill_id)->update(array(
            'deleted_by' => Auth::User()->user_id,
            'deleted_at' => date('Y-m-d H:i:s'),
            'total_bill_amount'=>0
        ));


        $last_invoice_id = customer_creditnote::where('company_id',Auth::user()->company_id)->get()->max('customer_creditnote_id');
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
        
        $creditnote_no          =       'CRE-'.$last_invoice_id.'/'.$f1.'-'.$f2;  

         customer_creditnote::where('customer_creditnote_id',$data[1]['customer_creditnote_id'])->update(array(
            'modified_by' => Auth::User()->user_id,
            'updated_at' => date('Y-m-d H:i:s')
          ));

         $credit_amount  = $data[1]['ggrand_total'];
         $creditid = customer_creditnote::updateOrCreate(
            ['customer_creditnote_id' => $data[1]['customer_creditnote_id'], 'company_id'=>$company_id,],
            ['customer_id'=>$data[1]['customer_id'],
            'sales_bill_id'=>$data[1]['sales_bill_id'],
            'return_bill_id'=>$return_bill_id,
            'creditnote_no'=>$creditnote_no,
                'creditnote_date'=>$invoice_date,
                'creditnote_amount'=>$credit_amount,
                'balance_amount'=>$credit_amount,
                'created_by' =>$created_by,
                'is_active' => "1"
            ]
        );



       $customer_creditnote_id = $creditid->customer_creditnote_id;


              $todate       =    date('Y-m-d');
              
              $newyear      =   date('Y-04-01');
              
              $newmonth     =   date('Y-m-01');

//////////////////For Credit Number series Year Wise 
        if($cstate_id[0]['series_type']==1)
        {

            $f1     =    (date('m')<'04') ? date('y',strtotime('-1 year')) : date('y');
            $f2     =    (date('m')>'03') ? date('y',strtotime('+1 year')) : date('y');

            $finalinvoiceno          =       $cstate_id[0]['credit_receipt_prefix'].$customer_creditnote_id.'/'.$f1.'-'.$f2;  
            $billseries              =       NULL;
                
             
        }

 //////////////////For Bill series Month Wise        
        else
        {
            if($todate>=$newmonth)
              {

                  $newseries  =  customer_creditnote::select('creditno_series')
                                            ->whereRaw("STR_TO_DATE(customer_creditnotes.creditnote_date,'%d-%m-%Y') >= '$newmonth'")
                                            ->where('customer_creditnote_id','<',$customer_creditnote_id)
                                            ->orderBy('customer_creditnote_id','DESC')
                                            ->take('1')
                                            ->first();
                       
               
                  if($newseries=='')
                  {
                      $billseries  =  1;
                  }
                  else
                  {
                      $billseries   = $newseries['creditno_series']+1;
                      
                  }
                 
               
              }
              else
              {
                $newseries  =  customer_creditnote::select('creditno_series')
                                            ->whereRaw("STR_TO_DATE(customer_creditnotes.creditnote_date,'%d-%m-%Y') <= '$todate'")
                                            ->where('customer_creditnote_id','<',$customer_creditnote_id)
                                            ->orderBy('customer_creditnote_id','DESC')
                                            ->take('1')
                                            ->first();
                      $billseries   = $newseries['creditno_series']+1;

                
              }

              $f1     =    (date('m')<'04') ? date('y',strtotime('-1 year')) : date('y');
              $f2     =    (date('m')>'03') ? date('y',strtotime('+1 year')) : date('y');
             
              $co     =     strlen($billseries);  
    
              if($co<=2)
              $id1  = '00'.$billseries; 
              elseif($co<=3)
              $id1  = '0'.$billseries;      
              elseif($co<=4)
              $id1  = $billseries;
              $dd   = date('my');
              
              $finalinvoiceno = $cstate_id[0]['credit_receipt_prefix'].$dd.''.$id1;
        } 

        customer_creditnote::where('customer_creditnote_id',$customer_creditnote_id)->update(array(
            'creditnote_no' => $finalinvoiceno,
            'creditno_series' => $billseries
         ));
    

    $paymentanswers     =    array();


         foreach($data[2] AS $key=>$value2)
          {
                              
                $paymentanswers['return_bill_id']                =  $return_bill_id;
                $paymentanswers['customer_creditnote_id']        =  $customer_creditnote_id;
                $paymentanswers['total_bill_amount']             =  $value2['value'];
                $paymentanswers['payment_method_id']             =  $value2['id'];
                $paymentanswers['created_by']                    =  Auth::User()->user_id;
                $paymentanswers['deleted_at'] =  NULL;
                $paymentanswers['deleted_by'] =  NULL;
       
           $paymentdetail = return_bill_payment::updateOrCreate(
               ['return_bill_id' => $return_bill_id,],
               $paymentanswers);

            
    }

   //auto use of credit note in Customer credit receipts if bill contains Outstanding amount

  if($data[1]['creditaccountid']!='')
  {
 
        $receiptremarks  =  'Credit Receipt against Return';

        if($data[1]['totalcreditbalance']  >= $credit_amount)
        {
            $deductcredit    =  $credit_amount;
            $creditbalance   =  $data[1]['totalcreditbalance']  - $credit_amount;
        }
        else
        {
            $deductcredit    =   $data[1]['totalcreditbalance'];
            $creditbalance   =   0;
        }

        

        $creditreceipt = customer_creditreceipt::updateOrCreate(
            ['customer_creditreceipt_id' => '', 'company_id'=>$company_id,],
            ['customer_id'=>$data[1]['customer_id'],
             'return_bill_id' => $return_bill_id,
             'receipt_no'=>$data[1]['invoice_no'],
                'receipt_date'=>$invoice_date,
                'remarks'=>$creditnote_no,
                'receiptremarks'=>$receiptremarks,
                'total_amount'=>$deductcredit,
                'created_by' =>$created_by,
                'is_active' => "1"
            ]
        );

       
       $customer_creditreceipt_id = $creditreceipt->customer_creditreceipt_id;

        $f1     =    (date('m')<'04') ? date('y',strtotime('-1 year')) : date('y');
        $f2     =    (date('m')>'03') ? date('y',strtotime('+1 year')) : date('y');

       
        
        $finalinvoiceno          =       'cus-'.$customer_creditreceipt_id.'/'.$f1.'-'.$f2;  

        
         customer_creditreceipt::where('customer_creditreceipt_id',$customer_creditreceipt_id)->update(array(
            'receipt_no' => $finalinvoiceno
         ));
      

        $rpaymentanswers     =    array();


         foreach($data[2] AS $rkey=>$rvalue2)
          {
                              
                $rpaymentanswers['customer_creditreceipt_id']     =  $customer_creditreceipt_id;
                $rpaymentanswers['total_bill_amount']             =  $deductcredit;
                $rpaymentanswers['payment_method_id']             =  $value2['id'];
                $rpaymentanswers['created_by']                    =  Auth::User()->user_id;
                $rpaymentanswers['deleted_at'] =  NULL;
                $rpaymentanswers['deleted_by'] =  NULL;
       
           $rpaymentdetail = customer_crerecp_payment::updateOrCreate(
               ['customer_crerecp_payment_id' => '',],
               $rpaymentanswers);

            
         }

  
    $creditreceipt = customer_creditreceipt_detail::updateOrCreate(
            ['customer_creditreceipt_detail_id' => '',],
            ['customer_creditreceipt_id'=>$customer_creditreceipt_id,
            'customer_creditaccount_id'=>$data[1]['creditaccountid'],
                'customer_id'=>$data[1]['customer_id'],
                'credit_amount'=>$data[1]['totalcreditbalance'],
                'payment_amount'=>$deductcredit,
                'balance_amount'=>$creditbalance,  
                'created_by' =>$created_by,
                'is_active' => "1"
            ]
        );
    
   

        

                customer_creditaccount::where('customer_creditaccount_id',$data[1]['creditaccountid'])->update(array('balance_amount' => $creditbalance
              ));  





        $creditbalanceamt   =    $credit_amount - $deductcredit;
        $creditnotepayment = creditnote_payment::updateOrCreate(
            ['sales_bill_id' => $data[1]['sales_bill_id'],'return_bill_id' => $return_bill_id, 'company_id'=>$company_id,],
            ['customer_id'=>$data[1]['customer_id'],
                'customer_creditnote_id'=>$customer_creditnote_id,
                'creditnote_amount'=>$credit_amount,
                'used_amount'=>$deductcredit,
                'balance_amount'=>$creditbalanceamt,
                'created_by' =>$created_by,
                'is_active' => "1",
                'deleted_at' =>NULL,
                'deleted_by' =>NULL,
            ]
        );

          customer_creditnote::where('customer_creditnote_id',$customer_creditnote_id)->update(array(
                      'balance_amount' => $creditbalanceamt
                  ));    

    }

  DB::commit();
    } catch (\Illuminate\Database\QueryException $e)
    {
        DB::rollback();
        return json_encode(array("Success"=>"False","Message"=>$e->getMessage()));
    }



   

        if($paymentdetail)
        {
            
           if($data[1]['return_bill_id'] != '')
          {   
              return json_encode(array("Success"=>"True","Message"=>"Billing successfully Update!","url"=>"view_bill"));
          }
          else
          {
              return json_encode(array("Success"=>"True","Message"=>"Billing has been successfully added.","url"=>"sales_return"));
          }

               

           
        }
        else
        {
            return json_encode(array("Success"=>"False","Message"=>"Something Went Wrong"));
        }
        //return back()->withInput();

    }


public function returnbillingprint_create(Request $request)
    {
       $data = $request->all();

        $userId = Auth::User()->user_id;
        $company_id = Auth::User()->company_id;


        $created_by = $userId;

       $cstate_id = company_profile::select('state_id','decimal_points','credit_receipt_prefix','series_type')
                ->where('company_id',Auth::user()->company_id)->get(); 
                
               
         if($data[1]['customer_id'] == '')
         {
              $state_id   =    $cstate_id[0]['state_id'];
         }
         else{

               $custate   =   customer_address_detail::select('state_id')
                ->where('company_id',Auth::user()->company_id)
                ->where('customer_id','=',$data[1]['customer_id'])
                ->get(); 

                if($custate[0]['state_id'] == '' || $custate[0]['state_id'] == null)
                {
                    
                     $state_id   =    $cstate_id[0]['state_id'];
                }
                else
                {
                     $state_id   =    $custate[0]['state_id'];
                }
               
         }   
        if($data[1]['refname'] != '')
         {

              $result = reference::select('reference_id','reference_name')
                ->where('reference_name','=',$data[1]['refname'])
                ->where('company_id',Auth::user()->company_id)->first();

                if($result=='')
                {
                     $refss = reference::updateOrCreate(
                        ['reference_id' => '', 'company_id'=>$company_id,],
                        ['reference_name'=>$data[1]['refname'],
                            'created_by' =>$created_by,
                            'is_active' => "1"
                        ]
                      );

                     $refid   =  $refss->reference_id;
                }
                else
                {
                    $refid   =  $result['reference_id'];
                    
                }

                 
         }
         else
         {
              $refid   =  NULL;
         }
        
         
         $invoice_date            =     date("d-m-Y");
         $selling_after_discount  =     $data[1]['totalwithout_gst'] - $data[1]['roomwisediscount_amount'];

  try {
    DB::beginTransaction(); 


         //$state_id = customer_address_detail::select('state_id')->where('company_id',Auth::user()->company_id)->where('customer_id','=',$data[1]['customer_id'])->first();

          return_bill::where('return_bill_id',$data[1]['return_bill_id'])->update(array(
            'modified_by' => Auth::User()->user_id,
            'updated_at' => date('Y-m-d H:i:s')
          ));


        $sales = return_bill::updateOrCreate(
            ['return_bill_id' => $data[1]['return_bill_id'], 'company_id'=>$company_id,],
            ['customer_id'=>$data[1]['customer_id'],
            'sales_bill_id'=>$data[1]['sales_bill_id'],
                'bill_date'=>$invoice_date,
                'state_id'=>$state_id,
                'reference_id'=>$refid,
                'total_qty'=>$data[1]['overallqty'],
                'sellingprice_before_discount'=>$data[1]['totalwithout_gst'],
                'discount_percent'=>$data[1]['discount_percent'],
                'discount_amount'=>$data[1]['discount_amount'],
                'productwise_discounttotal'=>$data[1]['roomwisediscount_amount'],
                'sellingprice_after_discount'=>$selling_after_discount,
                'totalbillamount_before_discount'=>$data[1]['sales_total'],
                'total_igst_amount'=>$data[1]['total_igst'],
                'total_cgst_amount'=>$data[1]['total_cgst'],
                'total_sgst_amount'=>$data[1]['total_sgst'],
                'gross_total'=>$data[1]['grand_total'],
                'shipping_charges'=>$data[1]['charges_total'],
                'total_bill_amount'=>$data[1]['ggrand_total'],
                'created_by' =>$created_by,
                'is_active' => "1"
            ]
        );


       $return_bill_id = $sales->return_bill_id;

       

       return_product_detail::where('return_bill_id',$return_bill_id)->update(array(
            'modified_by' => Auth::User()->user_id,
            'updated_at' => date('Y-m-d H:i:s')
        ));

    
        $productdetail     =    array();
        $returnproductdetail     =    array();
       

         foreach($data[0] AS $billkey=>$billvalue)
          {

               if($billvalue['barcodesel']!='')
              {


                      $halfgstper      =     $billvalue['prodgstper']/2;
                      $halfgstamt      =     $billvalue['prodgstamt']/2;
                      // $productdetail['bill_date']                            =    $invoice_date;
                      $productdetail['sales_products_detail_id']             =    $billvalue['sales_product_id'];
                      $productdetail['product_id']                           =    $billvalue['productid'];
                      $productdetail['price_master_id']                      =    $billvalue['price_master_id'];
                      $productdetail['qty']                                  =    $billvalue['qty'];
                      $productdetail['mrp']                                  =    $billvalue['mrp'];
                      $productdetail['sellingprice_before_discount']         =    $billvalue['sellingprice_before_discount'];
                      $productdetail['discount_percent']                     =    $billvalue['discount_percent'];
                      $productdetail['discount_amount']                      =    $billvalue['discount_amount'];
                      $productdetail['sellingprice_after_discount']          =    $billvalue['totalsellingwgst'];
                      $productdetail['overalldiscount_percent']              =    $billvalue['overalldiscount_percent'];
                      $productdetail['overalldiscount_amount']               =    $billvalue['overalldiscount_amount'];
                      $productdetail['overallmrpdiscount_amount']            =    $billvalue['overallmrpdiscount_amount'];
                      $productdetail['sellingprice_afteroverall_discount']   =    $billvalue['totalsellingwgst']-$billvalue['overalldiscount_amount'];
                      $productdetail['cgst_percent']                         =    $halfgstper;
                      $productdetail['cgst_amount']                          =    $halfgstamt;
                      $productdetail['sgst_percent']                         =    $halfgstper;
                      $productdetail['sgst_amount']                          =    $halfgstamt;
                      $productdetail['igst_percent']                         =    $billvalue['prodgstper'];
                      $productdetail['igst_amount']                          =    $billvalue['prodgstamt'];
                      $productdetail['total_amount']                         =    $billvalue['totalamount'];
                      $productdetail['product_type']                         =     1;
                      $productdetail['created_by']                           =     Auth::User()->user_id;

             


                      $returnproductdetail['return_date']                          =    $invoice_date;
                      $returnproductdetail['product_id']                           =    $billvalue['productid'];
                      $returnproductdetail['price_master_id']                      =    $billvalue['price_master_id'];
                      $returnproductdetail['qty']                                  =    $billvalue['qty'];
                     
                      $returnproductdetail['created_by']                           =     Auth::User()->user_id;

                      $oldinwardids       =     explode(',',substr($billvalue['inwardids'],0,-1));
                      $oldinwardqtys      =     explode(',',substr($billvalue['inwardqtys'],0,-1));
                      //print_r($oldinwardids);

                      $restqty            =    $billvalue['qty'];
                      $ccount    =   0;  
                       $icount    =   0;
                       $pcount    =   0;
                       $done      =   0;
                       $firstout  =   0;
                      $rinwardqtys        =    '';
                      $rinwardids        =    '';

                      foreach($oldinwardids as $l=>$lval)
                      {
                        //echo $oldinwardids[$l];

                        if($oldinwardqtys[$l] >= $restqty && $firstout==0)
                            {  
                                  if($done == 0)
                                  {

                                    //echo 'hello';

                                          $rinwardids    .=   $oldinwardids[$l].',';
                                          $rinwardqtys   .=   $restqty.',';
                                      
                                          $pcount++;
                                          $done++;
                                 }
                           }
                           else
                           {
                              if($pcount==0 && $done == 0 && $icount==0)
                              {
                                  
                                 
                                  if($restqty  > $oldinwardqtys[$l])
                                  {
                                    //echo 'bbb';
                                    //echo $restqty;
                                      $rinwardids    .=   $oldinwardids[$l].',';
                                      $rinwardqtys   .=   $oldinwardqtys[$l].',';
                                      $ccount         =   $restqty  - $oldinwardqtys[$l];
                                     
                                  }
                                  else
                                  {
                                    //echo 'ccc';
                                    //echo $restqty;
                                      $rinwardids    .=   $oldinwardids[$l].',';
                                      $rinwardqtys   .=   $restqty.',';
                                      $ccount         =   $restqty  - $oldinwardqtys[$l];
                                      
                                  }


                                   if($ccount > 0)
                                    {
                                       $firstout++;                                      
                                       $restqty   =   $restqty  - $oldinwardqtys[$l];
                                   
                                       
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

                    // echo $rinwardids.'_______________'.$rinwardqtys;
                    // echo '<br>';


                  $productdetail['inwardids']                          =    $rinwardids;
                  $productdetail['inwardqtys']                         =    $rinwardqtys;
                  $returnproductdetail['inwardids']                    =    $rinwardids;
                  $returnproductdetail['inwardqtys']                   =    $rinwardqtys;


                   $billproductdetail = return_product_detail::updateOrCreate(
                   ['return_bill_id' => $return_bill_id,
                    'company_id'=>$company_id,'return_product_detail_id'=>$billvalue['return_product_id'],],
                   $productdetail);

                   $return_product_detail_id = $billproductdetail->return_product_detail_id;
                  

                   $returnbillproductdetail = returnbill_product::updateOrCreate(
                   ['company_id'=>$company_id,'return_product_detail_id'=>$return_product_detail_id,],
                   $returnproductdetail);






                  
      }
     
     
            
    }

      // exit;

     $chargesdetail     =    array();

       
      
         foreach($data[3] AS $chargeskey=>$chargesvalue)
          {
             if(!empty($chargesvalue))
             {
               if($chargesvalue['chargesamt']!='')
              {

                    if($chargesvalue['returnchargesamt']>0 && $chargesvalue['returnchargesamt']!='')
                    {
                      $halfgstper      =     $chargesvalue['csprodgstper']/2;
                      $halfgstamt      =     $chargesvalue['csprodgstamt']/2;
                      // $chargesdetail['bill_date']                            =    $invoice_date;
                      $chargesdetail['sales_products_detail_id']             =    $chargesvalue['csales_product_id'];
                      $chargesdetail['product_id']                           =    $chargesvalue['cproductid'];
                      $chargesdetail['qty']                                  =    $chargesvalue['cqty'];
                      $chargesdetail['mrp']                                  =    $chargesvalue['chargesamt'];
                      $chargesdetail['sellingprice_before_discount']         =    $chargesvalue['chargesamt'];
                      $chargesdetail['sellingprice_after_discount']          =    $chargesvalue['chargesamt'];
                      $chargesdetail['sellingprice_afteroverall_discount']   =    $chargesvalue['chargesamt'];
                      $chargesdetail['cgst_percent']                         =    $halfgstper;
                      $chargesdetail['cgst_amount']                          =    $halfgstamt;
                      $chargesdetail['sgst_percent']                         =    $halfgstper;
                      $chargesdetail['sgst_amount']                          =    $halfgstamt;
                      $chargesdetail['igst_percent']                         =    $chargesvalue['csprodgstper'];
                      $chargesdetail['igst_amount']                          =    $chargesvalue['csprodgstamt'];
                      $chargesdetail['total_amount']                         =    $chargesvalue['ctotalamount'];
                      $chargesdetail['product_type']                         =     2;
                      $chargesdetail['created_by']                           =     Auth::User()->user_id;

                
                 $billchargesdetail = return_product_detail::updateOrCreate(
                   ['return_bill_id' => $return_bill_id,
                    'company_id'=>$company_id,'return_product_detail_id'=>$chargesvalue['creturn_product_id'],],
                   $chargesdetail);
               }
      } 
    }  
  }




  
      

      return_bill_payment::where('return_bill_id',$return_bill_id)->update(array(
            'deleted_by' => Auth::User()->user_id,
            'deleted_at' => date('Y-m-d H:i:s'),
            'total_bill_amount'=>0
        ));


        $last_invoice_id = customer_creditnote::where('company_id',Auth::user()->company_id)->get()->max('customer_creditnote_id');
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
        
        $creditnote_no          =       'CRE-'.$last_invoice_id.'/'.$f1.'-'.$f2;  

         customer_creditnote::where('customer_creditnote_id',$data[1]['customer_creditnote_id'])->update(array(
            'modified_by' => Auth::User()->user_id,
            'updated_at' => date('Y-m-d H:i:s')
          ));

         $credit_amount  = $data[1]['ggrand_total'];
         $creditid = customer_creditnote::updateOrCreate(
            ['customer_creditnote_id' => $data[1]['customer_creditnote_id'], 'company_id'=>$company_id,],
            ['customer_id'=>$data[1]['customer_id'],
            'sales_bill_id'=>$data[1]['sales_bill_id'],
            'return_bill_id'=>$return_bill_id,
            'creditnote_no'=>$creditnote_no,
                'creditnote_date'=>$invoice_date,
                'creditnote_amount'=>$credit_amount,
                'balance_amount'=>$credit_amount,
                'created_by' =>$created_by,
                'is_active' => "1"
            ]
        );



       $customer_creditnote_id = $creditid->customer_creditnote_id;


              $todate       =    date('Y-m-d');
              
              $newyear      =   date('Y-04-01');
              
              $newmonth     =   date('Y-m-01');

//////////////////For Credit Number series Year Wise 
        if($cstate_id[0]['series_type']==1)
        {

            $f1     =    (date('m')<'04') ? date('y',strtotime('-1 year')) : date('y');
            $f2     =    (date('m')>'03') ? date('y',strtotime('+1 year')) : date('y');

            $finalinvoiceno          =       $cstate_id[0]['credit_receipt_prefix'].$customer_creditnote_id.'/'.$f1.'-'.$f2;  
            $billseries              =       NULL;
                
             
        }

 //////////////////For Bill series Month Wise        
        else
        {
            if($todate>=$newmonth)
              {

                  $newseries  =  customer_creditnote::select('creditno_series')
                                            ->whereRaw("STR_TO_DATE(customer_creditnotes.creditnote_date,'%d-%m-%Y') >= '$newmonth'")
                                            ->where('customer_creditnote_id','<',$customer_creditnote_id)
                                            ->orderBy('customer_creditnote_id','DESC')
                                            ->take('1')
                                            ->first();
                       
               
                  if($newseries=='')
                  {
                      $billseries  =  1;
                  }
                  else
                  {
                      $billseries   = $newseries['creditno_series']+1;
                      
                  }
                 
               
              }
              else
              {
                $newseries  =  customer_creditnote::select('creditno_series')
                                            ->whereRaw("STR_TO_DATE(customer_creditnotes.creditnote_date,'%d-%m-%Y') <= '$todate'")
                                            ->where('customer_creditnote_id','<',$customer_creditnote_id)
                                            ->orderBy('customer_creditnote_id','DESC')
                                            ->take('1')
                                            ->first();
                      $billseries   = $newseries['creditno_series']+1;

                
              }

              $f1     =    (date('m')<'04') ? date('y',strtotime('-1 year')) : date('y');
              $f2     =    (date('m')>'03') ? date('y',strtotime('+1 year')) : date('y');
             
              $co     =     strlen($billseries);  
    
              if($co<=2)
              $id1  = '00'.$billseries; 
              elseif($co<=3)
              $id1  = '0'.$billseries;      
              elseif($co<=4)
              $id1  = $billseries;
              $dd   = date('my');
              
              $finalinvoiceno = $cstate_id[0]['credit_receipt_prefix'].$dd.''.$id1;
        } 

        customer_creditnote::where('customer_creditnote_id',$customer_creditnote_id)->update(array(
            'creditnote_no' => $finalinvoiceno,
            'creditno_series' => $billseries
         ));
    

    $paymentanswers     =    array();


         foreach($data[2] AS $key=>$value2)
          {
                              
                $paymentanswers['return_bill_id']                =  $return_bill_id;
                $paymentanswers['customer_creditnote_id']        =  $customer_creditnote_id;
                $paymentanswers['total_bill_amount']             =  $value2['value'];
                $paymentanswers['payment_method_id']             =  $value2['id'];
                $paymentanswers['created_by']                    =  Auth::User()->user_id;
                $paymentanswers['deleted_at'] =  NULL;
                $paymentanswers['deleted_by'] =  NULL;
       
           $paymentdetail = return_bill_payment::updateOrCreate(
               ['return_bill_id' => $return_bill_id,],
               $paymentanswers);

            
    }

   //auto use of credit note in Customer credit receipts if bill contains Outstanding amount

  if($data[1]['creditaccountid']!='')
  {
 
        $receiptremarks  =  'Credit Receipt against Return';

        if($data[1]['totalcreditbalance']  >= $credit_amount)
        {
            $deductcredit    =  $credit_amount;
            $creditbalance   =  $data[1]['totalcreditbalance']  - $credit_amount;
        }
        else
        {
            $deductcredit    =   $data[1]['totalcreditbalance'];
            $creditbalance   =   0;
        }

        

        $creditreceipt = customer_creditreceipt::updateOrCreate(
            ['customer_creditreceipt_id' => '', 'company_id'=>$company_id,],
            ['customer_id'=>$data[1]['customer_id'],
             'return_bill_id' => $return_bill_id,
             'receipt_no'=>$data[1]['invoice_no'],
                'receipt_date'=>$invoice_date,
                'remarks'=>$creditnote_no,
                'receiptremarks'=>$receiptremarks,
                'total_amount'=>$deductcredit,
                'created_by' =>$created_by,
                'is_active' => "1"
            ]
        );

       
       $customer_creditreceipt_id = $creditreceipt->customer_creditreceipt_id;

        $f1     =    (date('m')<'04') ? date('y',strtotime('-1 year')) : date('y');
        $f2     =    (date('m')>'03') ? date('y',strtotime('+1 year')) : date('y');

       
        
        $finalinvoiceno          =       'cus-'.$customer_creditreceipt_id.'/'.$f1.'-'.$f2;  

        
         customer_creditreceipt::where('customer_creditreceipt_id',$customer_creditreceipt_id)->update(array(
            'receipt_no' => $finalinvoiceno
         ));
      

        $rpaymentanswers     =    array();


         foreach($data[2] AS $rkey=>$rvalue2)
          {
                              
                $rpaymentanswers['customer_creditreceipt_id']     =  $customer_creditreceipt_id;
                $rpaymentanswers['total_bill_amount']             =  $deductcredit;
                $rpaymentanswers['payment_method_id']             =  $value2['id'];
                $rpaymentanswers['created_by']                    =  Auth::User()->user_id;
                $rpaymentanswers['deleted_at'] =  NULL;
                $rpaymentanswers['deleted_by'] =  NULL;
       
           $rpaymentdetail = customer_crerecp_payment::updateOrCreate(
               ['customer_crerecp_payment_id' => '',],
               $rpaymentanswers);

            
         }

  
    $creditreceipt = customer_creditreceipt_detail::updateOrCreate(
            ['customer_creditreceipt_detail_id' => '',],
            ['customer_creditreceipt_id'=>$customer_creditreceipt_id,
            'customer_creditaccount_id'=>$data[1]['creditaccountid'],
                'customer_id'=>$data[1]['customer_id'],
                'credit_amount'=>$data[1]['totalcreditbalance'],
                'payment_amount'=>$deductcredit,
                'balance_amount'=>$creditbalance,  
                'created_by' =>$created_by,
                'is_active' => "1"
            ]
        );
    
   

        

                customer_creditaccount::where('customer_creditaccount_id',$data[1]['creditaccountid'])->update(array('balance_amount' => $creditbalance
              ));  





        $creditbalanceamt   =    $credit_amount - $deductcredit;
        $creditnotepayment = creditnote_payment::updateOrCreate(
            ['sales_bill_id' => $data[1]['sales_bill_id'],'return_bill_id' => $return_bill_id, 'company_id'=>$company_id,],
            ['customer_id'=>$data[1]['customer_id'],
                'customer_creditnote_id'=>$customer_creditnote_id,
                'creditnote_amount'=>$credit_amount,
                'used_amount'=>$deductcredit,
                'balance_amount'=>$creditbalanceamt,
                'created_by' =>$created_by,
                'is_active' => "1",
                'deleted_at' =>NULL,
                'deleted_by' =>NULL,
            ]
        );

          customer_creditnote::where('customer_creditnote_id',$customer_creditnote_id)->update(array(
                      'balance_amount' => $creditbalanceamt
                  ));    

    }

  DB::commit();
    } catch (\Illuminate\Database\QueryException $e)
    {
        DB::rollback();
        return json_encode(array("Success"=>"False","Message"=>$e->getMessage()));
    }


        if($paymentdetail)
        {
            
           if($data[1]['return_bill_id'] != '')
          {   
              return json_encode(array("Success"=>"True","Message"=>"Billing has been successfully returned.","url"=>route('print_creditnote', ['id' => encrypt($return_bill_id)]),"burl"=>"sales_return"));
          }
          else
          {
              return json_encode(array("Success"=>"True","Message"=>"Billing has been successfully returned.","url"=>route('print_creditnote', ['id' => encrypt($return_bill_id)]),"burl"=>"sales_return"));
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
     * @param  \App\return_bill  $return_bill
     * @return \Illuminate\Http\Response
     */
    public function show(return_bill $return_bill)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\return_bill  $return_bill
     * @return \Illuminate\Http\Response
     */
    public function edit(return_bill $return_bill)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\return_bill  $return_bill
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, return_bill $return_bill)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\return_bill  $return_bill
     * @return \Illuminate\Http\Response
     */
    public function destroy(return_bill $return_bill)
    {
        //
    }
}
