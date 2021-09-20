<?php

namespace Retailcore\Sales\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Retailcore\Products\Models\product\price_master;
use Retailcore\Products\Models\product\product_image;
use Retailcore\Sales\Models\sales_bill;
use Retailcore\Sales\Models\sales_product_detail;
use Retailcore\Sales\Models\sales_bill_payment_detail;
use Retailcore\Sales\Models\reference;
use Retailcore\CreditBalance\Models\customer_creditaccount;
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
use Retailcore\Inward_Stock\Models\inward\inward_product_detail;
use Auth;
use DB;



class SalesBillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
  
    public function index()
    {
        $ppvalues = array();
        $state    = state::all();
        $country  = country::all();
        
      
       $payment_methods = payment_method::where('is_active','=','1')->where('payment_method_id','!=',9)->orderBy('payment_order','ASC')->get();
       $cstate_id = company_profile::select('state_id','billtype','bill_number_prefix','tax_type','billprint_type','series_type','bill_number_prefix')->where('company_id',Auth::user()->company_id)->get(); 
        $last_invoice_id = sales_bill::where('company_id',Auth::user()->company_id)->get()->max('sales_bill_id');


      


        if($last_invoice_id == '')
        {
            $last_invoice_id = 1;
        }
        else
        {
            $last_invoice_id = $last_invoice_id  + 1;
        }

        $todate       =    date('Y-m-d');
        
        $newyear      =   date('Y-04-01');
        
        $newmonth     =   date('Y-m-01');

//////////////////For Bill series Year Wise 
        if($cstate_id[0]['series_type']==1)
        {

            $f1     =    (date('m')<'04') ? date('y',strtotime('-1 year')) : date('y');
            $f2     =    (date('m')>'03') ? date('y',strtotime('+1 year')) : date('y');

            $invoiceno          =       $cstate_id[0]['bill_number_prefix'].$last_invoice_id.'/'.$f1.'-'.$f2;  
           
             
        }

 //////////////////For Bill series Month Wise        
        else
        {
            if($todate>=$newmonth)
              {

                  $newseries  =  sales_bill::select('bill_series')
                                            ->whereRaw("STR_TO_DATE(sales_bills.bill_date,'%d-%m-%Y') >= '$newmonth'")
                                            ->where('sales_bill_id','<',$last_invoice_id)
                                            ->orderBy('sales_bill_id','DESC')
                                            ->take('1')
                                            ->first();
                       
               
                  if($newseries=='')
                  {
                      $billseries  =  1;
                  }
                  else
                  {
                      $billseries   = $newseries['bill_series']+1;
                      
                  }
                 
               
              }
              else
              {
                $newseries  =  sales_bill::select('bill_series')
                                            ->whereRaw("STR_TO_DATE(sales_bills.bill_date,'%d-%m-%Y') <= '$todate'")
                                            ->where('sales_bill_id','<',$last_invoice_id)
                                            ->orderBy('sales_bill_id','DESC')
                                            ->take('1')
                                            ->first();
                      $billseries   = $newseries['bill_series']+1;

                
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
              
              $invoiceno = $cstate_id[0]['bill_number_prefix'].$dd.''.$id1;
        }

      

        $chargeslist      =   product::select('product_id','product_name','sell_gst_percent') 
                              ->where('company_id',Auth::user()->company_id)
                              ->where('item_type','=',2)
                              ->get();


       
        return view('sales::sales_bill',compact('payment_methods','invoiceno','state','country','chargeslist','ppvalues'));
    }

   public function refname_search(Request $request)
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
    public function creditnote_numbersearch(Request $request)
    {

        if($request->search_val !='')
        {

            $json = [];
            $result = customer_creditnote::select('creditnote_no')
                ->where('creditnote_no', 'LIKE', "%$request->search_val%")
                ->where('company_id',Auth::user()->company_id)->get();

           
           

            if(!empty($result))
            {
           
                foreach($result as $billkey=>$billvalue){


                      $json[] = $billvalue['creditnote_no'];
                      
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
    public function sproduct_search(Request $request)
    {

        if($request->search_val !='')
        {

            $json = [];
            $result = product::where('company_id',Auth::user()->company_id)
                ->select('product_name','product_system_barcode','product_id','hsn_sac_code')
                ->where('product_name', 'LIKE', "%$request->search_val%")
                ->where('item_type','=',1)
                ->orWhere('product_system_barcode', 'LIKE', "%$request->search_val%")
                ->orWhere('hsn_sac_code', 'LIKE', "%$request->search_val%")->take(10)->get();

            $sresult = product::select('supplier_barcode','product_name','product_system_barcode')
                ->where('company_id',Auth::user()->company_id)
                ->Where('supplier_barcode', 'LIKE', "%$request->search_val%")->take(10)->get();
               
           

             if(sizeof($result) != 0) 
            {
           
                foreach($result as $productkey=>$productvalue){


                      $json[$productkey]['label'] = $productvalue['product_system_barcode'].'_'.$productvalue['product_name'];
                      $json[$productkey]['barcode'] = $productvalue['product_system_barcode'];
                      $json[$productkey]['product_name'] = $productvalue['product_name'];
                      
                }
            }
           if(sizeof($sresult) != 0)
            {
               foreach($sresult as $sproductkey=>$sproductvalue){

                      $json[$sproductkey]['label'] = $sproductvalue['supplier_barcode'].'_'.$sproductvalue['product_name'];
                      $json[$sproductkey]['barcode'] = $sproductvalue['product_system_barcode'];
                      $json[$sproductkey]['product_name'] = $sproductvalue['product_name'];
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
    public function bsproduct_search(Request $request)
    {

        if($request->search_val !='')
        {
            $sresult = [];
            $json = [];

               $result = price_master::where('company_id', Auth::user()->company_id)
                  ->where('deleted_at', '=', NULL)
                  ->where('batch_no', 'LIKE', "%$request->search_val%")
                  ->where('product_qty','>',0)
                  ->with('product')
                  ->whereHas('product',function ($q) use($request){
                          $q->select('product_name','product_system_barcode','supplier_barcode');
                          $q->where('company_id', Auth::user()->company_id);
                    })->take(10)->get();


                  if(sizeof($result) == 0)
                  {

                      $sresult = product::where('company_id', Auth::user()->company_id)
                                ->select('product_name','product_system_barcode','supplier_barcode','product_id')
                                ->where('deleted_at', '=', NULL)
                                ->where('product_system_barcode', 'like', '%'.$request->search_val.'%')
                                ->orWhere('supplier_barcode', 'like', '%'.$request->search_val.'%')
                                ->orWhere('product_name', 'like', '%'.$request->search_val.'%')
                                ->groupBy('product_id')
                                ->with('price_master')
                                ->whereHas('price_master',function ($q) use($request){
                                        $q->select('batch_no');
                                        $q->where('batch_no','!=',NULL);
                                        $q->where('company_id', Auth::user()->company_id);
                                    })->take(10)->get();
                    }
                            // echo '<pre>';
                            // print_r($result);
                            //  echo '</pre>';
               

            if(sizeof($result) != 0)
            {
                // echo 'aaa';
                // print_r($result);
                foreach($result as $productkey=>$productvalue){

                      if($productvalue['supplier_barcode']!='' || $productvalue['supplier_barcode']!=null)
                      {
                          $json[$productkey]['label'] = $productvalue['product']['supplier_barcode'].'_'.$productvalue['product']['product_name'].'_'.$productvalue['batch_no'];
                          $json[$productkey]['barcode'] = $productvalue['product']['product_system_barcode'];
                          $json[$productkey]['product_name'] = $productvalue['product']['product_name'];
                          $json[$productkey]['batch_no'] = $productvalue['batch_no'];
                      }
                      else
                      {
                        $json[$productkey]['label'] = $productvalue['product']['product_system_barcode'].'_'.$productvalue['product']['product_name'].'_'.$productvalue['batch_no'];
                        $json[$productkey]['barcode'] = $productvalue['product']['product_system_barcode'];
                        $json[$productkey]['product_name'] = $productvalue['product']['product_name'];
                        $json[$productkey]['batch_no'] = $productvalue['batch_no'];
                      }


                      
                }
            }
           if(sizeof($sresult) != 0)
            {
             
               foreach($sresult as $sproductkey=>$sproductvalue){

                        foreach($sproductvalue['price_master'] as $psproductkey=>$psproductvalue){

                          if($sproductvalue['supplier_barcode']!='' || $sproductvalue['supplier_barcode']!=null)
                          {
                             $showbarcode   =   $sproductvalue['supplier_barcode'];
                          }
                          else
                          {
                             $showbarcode   =   $sproductvalue['product_system_barcode'];
                          }

                            if($psproductvalue['batch_no']!='' || $psproductvalue['batch_no']!=null)
                              {

                                $json[$sproductkey]['label'] = $showbarcode.'_'.$sproductvalue['product_name'].'_'.$psproductvalue['batch_no'];
                                $json[$sproductkey]['barcode'] = $sproductvalue['product_system_barcode'];
                                $json[$sproductkey]['product_name'] = $sproductvalue['product_name'];
                                $json[$sproductkey]['batch_no'] = $psproductvalue['batch_no'];
                            }
                            else
                            {   
                                $json[$sproductkey]['label'] = $showbarcode.'_'.$sproductvalue['product_name'].'_'.$psproductvalue['batch_no'];
                                $json[$sproductkey]['barcode'] = $sproductvalue['product_system_barcode'];
                                $json[$sproductkey]['product_name'] = $sproductvalue['product_name'];
                                $json[$sproductkey]['batch_no'] = $psproductvalue['batch_no'];

                            }

                        }
                            
                         
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

   

    public function sproduct_detail(Request $request)
    {
      
     
            $prod_barcode   =  $request->barcode;
            $prod_name      =  $request->product_name;

            $query = product::select('product_name','sku_code','hsn_sac_code','product_id','product_system_barcode','supplier_barcode','colour_id','size_id','uqc_id')
                     ->with('price_master')
                     ->where('product_name','LIKE',"%$request->product_name%")
                     ->where('product_system_barcode', 'LIKE', "%$request->barcode%");


              $result  =  $query->where('company_id',Auth::user()->company_id)
              ->with('colour','size','uqc')->get();
             
             
               if(sizeof($result) != 0)
               {
                    $overallqty =  price_master::where('company_id',Auth::user()->company_id)
                         ->where('product_id','=',$result[0]['product_id'])
                         ->sum('product_qty');
             
              

                  return json_encode(array("Success"=>"True","Data"=>$result,"Stock"=>$overallqty));
               }
               else
               {
                   return json_encode(array("Success"=>"False","Message"=>"There is something wrong with the Product selected"));
               }
                

    }
    public function bsproduct_detail(Request $request)
    {
      
      $prod_barcode     =  $request->barcode;
      $prod_name        =  $request->product_name;
      $batch_no         =  $request->batch_no;
      $presult   =  array();
      $ppresult  =  array();

      
      
        if($batch_no=='')
        {
          $presult = product::select('product_id')->where('product_system_barcode',$prod_barcode)
              ->where('product_name',$prod_name)
              // ->orWhere('supplier_barcode',$prod_barcode)
              ->where('company_id',Auth::user()->company_id)
              ->with('colour','size','uqc')
              ->get();
        }
        if(sizeof($presult) == 0) 
        {

          $ppresult = price_master::select('price_master_id')->where('batch_no',$batch_no)
              ->where('product_id',DB::raw("(SELECT products.product_id FROM products WHERE products.product_system_barcode = '$prod_barcode' and products.product_name = '$prod_name')"))
              ->where('company_id',Auth::user()->company_id)
              ->where('product_qty','>',0)
              ->get();   
        } 
       
          $result = array();
           //print_r($ppresult[0]['price_master_id']); 
          if(sizeof($presult) != 0) 
          {
             //echo 'aaa';
                $result = price_master::where('product_id',$presult[0]['product_id'])
                ->where('company_id',Auth::user()->company_id)
                 ->with(['product' => function ($pquery) {
                        $pquery->select('product_id','product_name', 'product_system_barcode','supplier_barcode','sku_code','hsn_sac_code');
                    }])
                ->with('product.colour','product.size','product.uqc')
                ->where('product_qty','>',0)
                ->whereRaw('batch_no IS NULL')
                ->orderBy('price_master_id','ASC')
                ->take(1)
                ->get();
          }
          if(sizeof($ppresult) != 0) 
          {
            //echo 'bbb';
               $result = price_master::where('price_master_id',$ppresult[0]['price_master_id'])
              ->where('company_id',Auth::user()->company_id)
               ->with(['product' => function ($pquery) {
                        $pquery->select('product_id','product_name', 'product_system_barcode','supplier_barcode','sku_code','hsn_sac_code');
                    }])
                ->with('product.colour','product.size','product.uqc')
               ->where('product_qty','>',0)
               ->get();
          }

              
    

      return json_encode(array("Success"=>"True","Data"=>$result));
    }
    public function charges_search(Request $request)
    {

        if($request->search_val !='')
        {

            $json = [];
            $result = product::select('product_name','product_id')
                ->where('product_name', 'LIKE', "%$request->search_val%")
                ->where('company_id',Auth::user()->company_id)
                ->Where('item_type','=','2')->get();

          
           

              if(!empty($result))
              {
           
                  foreach($result as $productkey=>$productvalue){


                      $json[] = $productvalue['product_name'];
                     
                     
                      
                }
            }
           
            return json_encode($json);
        }
        else
        {
          $json = [];
          return json_encode($json);
        }
       
        //return json_encode(array("Success"=>"True","Data"=>$result) );
    }
    public function creditnote_details(Request $request)
    {

       
        $result = customer_creditnote::where('creditnote_no',$request->creditnoteno)
           // ->where('customer_id',$request->customer_id)
            ->where('company_id',Auth::user()->company_id)            
            ->get();
       


      return json_encode(array("Success"=>"True","Data"=>$result));
    }
    public function search_pricedetail(Request $request)
    {

       
        $result = price_master::where('price_master_id',$request->price_id)
            ->where('company_id',Auth::user()->company_id)            
            ->get();
       


      return json_encode(array("Success"=>"True","Data"=>$result));
    }
    public function gstrange_detail(Request $request)
    {


        $result = gst_slabs_master::where('selling_price_from','<=',$request->sellingprice)
            ->where('selling_price_to','>=',$request->sellingprice)
            ->where('company_id',Auth::user()->company_id)
            ->get();
       
        if(sizeof($result) != 0) 
        {
          return json_encode(array("Success"=>"True","Data"=>$result));
        }
        else
        {
           return json_encode(array("Success"=>"False","Message"=>"GST Range has not been Specified for this Product."));
        }
            
      
        }
   
    public function customer_search(Request $request)
    {


        $result = customer::select('customer_name','customer_mobile','customer_id')
            ->where('customer_name', 'LIKE', "%$request->search_val%")
            ->where('company_id',Auth::user()->company_id)
            ->orWhere('customer_mobile', 'LIKE', "%$request->search_val%")
            ->get();


        

        return json_encode(array("Success"=>"True","Data"=>$result) );
    }

    public function customer_detail(Request $request)
    {


        $result = customer::where('customer_id',$request->customer_id)
            ->where('company_id',Auth::user()->company_id)
            ->with('customer_address_detail')
            ->withCount([
                    'customer_creditaccount as totalcuscreditbalance' => function($fquery) {
                        $fquery->select(DB::raw('SUM(balance_amount)'));
                    }
                ])
            ->get();

        

      return json_encode(array("Success"=>"True","Data"=>$result));
    }
    public function product_popup_values(Request $request)
    {


        $ppvalues = product::where('product_id',$request->productid)
            ->where('company_id',Auth::user()->company_id)
            ->with('product_image')
            ->get();
        //print_r($ppvalues);
        

      return view('sales::product_popup',compact('ppvalues'));
    }

    public function billing_create(Request $request)
    {
        $data = $request->all();

        $userId = Auth::User()->user_id;
        $company_id = Auth::User()->company_id;


        $created_by = $userId;

       
        $cstate_id = company_profile::select('state_id','billtype','bill_number_prefix','tax_type','billprint_type','series_type','bill_number_prefix')->where('company_id',Auth::user()->company_id)->get(); 
                
               
         if($data[1]['customer_id'] == '')
         {
              $state_id   =    $cstate_id[0]['state_id'];
         }
         else{

               if($data[1]['duedays']!='' && $data[1]['duedays']!=0)
               {
                    customer::where('customer_id',$data[1]['customer_id'])->update(array(
                    'modified_by' => Auth::User()->user_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'outstanding_duedays'=>$data[1]['duedays']
                  ));
               }
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

        
         $invoice_date            =     $data[1]['invoice_date'];
         $selling_after_discount  =     $data[1]['totalwithout_gst'] - $data[1]['roomwisediscount_amount'];
         $roundoff    =    round($data[1]['ggrand_total']) - $data[1]['ggrand_total'];

         //$state_id = customer_address_detail::select('state_id')->where('company_id',Auth::user()->company_id)->where('customer_id','=',$data[1]['customer_id'])->first();

          sales_bill::where('sales_bill_id',$data[1]['sales_bill_id'])->update(array(
            'modified_by' => Auth::User()->user_id,
            'updated_at' => date('Y-m-d H:i:s')
          ));

     try {
            DB::beginTransaction();    

          if($cstate_id[0]['tax_type'] == 1)
              {
                  $totalcgst      =     0;
                  $totalsgst      =     0;
              }
              else
              {
                  $totalcgst      =     $data[1]['total_cgst'];
                  $totalsgst      =     $data[1]['total_sgst'];
              } 

        $sales = sales_bill::updateOrCreate(
            ['sales_bill_id' => $data[1]['sales_bill_id'], 'company_id'=>$company_id,],
            ['customer_id'=>$data[1]['customer_id'],
            'bill_no'=>$data[1]['invoice_no'],
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
                'total_cgst_amount'=>$totalcgst,
                'total_sgst_amount'=>$totalsgst,
                'gross_total'=>$data[1]['grand_total'],
                'shipping_charges'=>$data[1]['charges_total'],
                'round_off'=>$roundoff,
                'total_bill_amount'=>$data[1]['ggrand_total'],
                'official_note'=>$data[1]['official_note'],
                'print_note'=>$data[1]['print_note'],
                'created_by' =>$created_by,
                'is_active' => "1"
            ]
        );


       $sales_bill_id = $sales->sales_bill_id;


  //////////////////////////////////// To make Bill series Month Wise and Year wise as per the value selected from Company Profile......

        $todate       =    date('Y-m-d');
        
        $newyear      =   date('Y-04-01');
        
        $newmonth     =   date('Y-m-01');
        
        $f1     =    (date('m')<'04') ? date('y',strtotime('-1 year')) : date('y');
        $f2     =    (date('m')>'03') ? date('y',strtotime('+1 year')) : date('y');

//////////////////For Bill series Year Wise 
        if ($cstate_id[0]['series_type'] == 1) {

                    $nseries = sales_bill::select('bill_series')
                                        ->where('sales_bill_id', '<', $sales_bill_id)
                                        ->where('company_id', Auth::user()->company_id)
                                        ->orderBy('sales_bill_id', 'DESC')
                                        ->take('1');

                    if($todate >= $newyear){
                       $nseries->whereRaw("STR_TO_DATE(sales_bills.bill_date,'%d-%m-%Y') >= '$newyear'");
                    }

                   
                        $billprefix = $cstate_id[0]['bill_number_prefix'];


                    $newseries = $nseries->first();

                    if ($newseries != '') {
                        $billseries = $newseries['bill_series'] + 1;
                    } else {
                        $billseries = 1;
                    }

                    $finalinvoiceno = $billprefix . $billseries . '/' . $f1 . '-' . $f2;
                }

 //////////////////For Bill series Month Wise        
        else
        {
            if($todate>=$newmonth)
              {

                  $newseries  =  sales_bill::select('bill_series')
                                            ->whereRaw("STR_TO_DATE(sales_bills.bill_date,'%d-%m-%Y') >= '$newmonth'")
                                            ->where('sales_bill_id','<',$sales_bill_id)
                                            ->orderBy('sales_bill_id','DESC')
                                            ->take('1')
                                            ->first();
                       
               
                  if($newseries=='')
                  {
                      $billseries  =  1;
                  }
                  else
                  {
                      $billseries   = $newseries['bill_series']+1;
                      
                  }
                 
               
              }
              else
              {
                $newseries  =  sales_bill::select('bill_series')
                                            ->whereRaw("STR_TO_DATE(sales_bills.bill_date,'%d-%m-%Y') <= '$todate'")
                                            ->where('sales_bill_id','<',$sales_bill_id)
                                            ->orderBy('sales_bill_id','DESC')
                                            ->take('1')
                                            ->first();
                      $billseries   = $newseries['bill_series']+1;

                
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
              
              $finalinvoiceno = $cstate_id[0]['bill_number_prefix'].$dd.''.$id1;
        }   
              

        

        if($data[1]['sales_bill_id']=='' || $data[1]['sales_bill_id']==null)
        {  

         sales_bill::where('sales_bill_id',$sales_bill_id)->update(array(
            'bill_no' => $finalinvoiceno,
            'bill_series' => $billseries
         ));
       }
    

       sales_product_detail::where('sales_bill_id',$sales_bill_id)->update(array(
            'modified_by' => Auth::User()->user_id,
            'updated_at' => date('Y-m-d H:i:s')
        ));

    
        $productdetail     =    array();

       

         foreach($data[0] AS $billkey=>$billvalue)
          {
              $inwardids    =  '';
              $inwardqtys   =  '';
               if($billvalue['barcodesel']!='')
              {

                  if($cstate_id[0]['tax_type'] == 1)
                  {
                      $halfgstper      =     0;
                      $halfgstamt      =     0;
                  }
                  else
                  {
                      $halfgstper      =     $billvalue['prodgstper']/2;
                      $halfgstamt      =     $billvalue['prodgstamt']/2;
                  }
                     
                      // $productdetail['bill_date']                            =    $invoice_date;
                      // $productdetail['product_system_barcode']               =    $billvalue['barcodesel'];
                      $productdetail['product_id']                           =    $billvalue['productid'];
                      $productdetail['price_master_id']                      =    $billvalue['price_master_id'];
                      $productdetail['qty']                                  =    $billvalue['qty'];
                      $productdetail['mrp']                                  =    $billvalue['mrp'];
                      $productdetail['sellingprice_before_discount']         =    $billvalue['sellingprice_before_discount'];
                      $productdetail['discount_percent']                     =    $billvalue['discount_percent'];
                      $productdetail['discount_amount']                      =    $billvalue['discount_amount'];
                      $productdetail['mrpdiscount_amount']                   =    $billvalue['mrpdiscount_amount'];
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

          
                if($billvalue['oldprice_master_id'] != ''){

                   price_master::where('price_master_id',$billvalue['oldprice_master_id'])->update(array(
                    'modified_by' => Auth::User()->user_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'product_qty' => DB::raw('product_qty + '.$billvalue['oldqty'])
                    ));
                 
                  }

                   price_master::where('price_master_id',$billvalue['price_master_id'])->update(array(
                  'modified_by' => Auth::User()->user_id,
                  'updated_at' => date('Y-m-d H:i:s'),
                  'product_qty' => DB::raw('product_qty - '.$billvalue['qty'])
                  ));

///////////////////First In First Out Logic//////////////////////////////////////////////////////////////

                    $oldinwardids       =     explode(',',substr($billvalue['inwardids'],0,-1));
                    $oldinwardqtys      =     explode(',',substr($billvalue['inwardqtys'],0,-1));

                
                       $ccount    =   0;  
                       $icount    =   0;
                       $pcount    =   0;
                       $done      =   0;
                       $firstout  =   0;
                       $restqty   =   $billvalue['qty'];


                         

              if($billvalue['price_master_id']!=$billvalue['oldprice_master_id'] || $billvalue['qty']!=$billvalue['oldqty'])  
               {    

                   if($billvalue['sales_product_id'] !='')
                       { 
                            foreach($oldinwardids as $l=>$val)
                            {
                                inward_product_detail::where('company_id',Auth::user()->company_id)
                                          ->where('inward_product_detail_id',$oldinwardids[$l])
                                          ->update(array(
                                              'modified_by' => Auth::User()->user_id,
                                              'updated_at' => date('Y-m-d H:i:s'),
                                              'pending_return_qty' => DB::raw('pending_return_qty + '.$oldinwardqtys[$l])
                                              ));

                            }  
                       }   
                       
                  if($billvalue['qty']>0)
                  {
                      $prodtype    =        product::select('product_type')
                                            ->where('company_id',Auth::user()->company_id)
                                            ->where('product_id',$billvalue['productid'])->get();

                       $prid      =         price_master::select('offer_price','batch_no')
                                            ->where('company_id',Auth::user()->company_id)
                                            ->where('price_master_id',$billvalue['price_master_id'])->get();

                       $qquery    =         inward_product_detail::select('inward_product_detail_id','pending_return_qty')
                                            ->where('product_id',$billvalue['productid'])
                                            ->where('company_id',Auth::user()->company_id)
                                            ->where('pending_return_qty','!=',0);

                                            
                     if($cstate_id[0]['billtype']==3)
                      {
                            $qquery->where('batch_no',$prid[0]['batch_no']);
                      }
                      if($prodtype[0]['product_type']==1)
                      {
                            $qquery->where('offer_price',$prid[0]['offer_price']);
                      }
                      
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
                                              'pending_return_qty' => DB::raw('pending_return_qty - '.$billvalue['qty'])
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

                }   
                if($inwardids!='')
                {
                  $productdetail['inwardids']                          =    $inwardids;
                  $productdetail['inwardqtys']                         =    $inwardqtys;
                }   
                else
                {  
                  $productdetail['inwardids']                          =    $billvalue['inwardids'];
                  $productdetail['inwardqtys']                         =    $billvalue['inwardqtys'];
                }
                 // echo $inwardids;
                 // echo $inwardqtys;

                $billproductdetail = sales_product_detail::updateOrCreate(
                   ['sales_bill_id' => $sales_bill_id,
                    'company_id'=>$company_id,'sales_products_detail_id'=>$billvalue['sales_product_id'],],
                   $productdetail);

              
      }
     
     
            
     }


          $chargesdetail     =    array();

       

         foreach($data[3] AS $chargeskey=>$chargesvalue)
          {

               if($chargesvalue['chargesamt']!='')
              {


                      $halfgstper      =     $chargesvalue['csprodgstper']/2;
                      $halfgstamt      =     $chargesvalue['csprodgstamt']/2;
                      // $chargesdetail['bill_date']                            =    $invoice_date;
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

                
               
                   $billchargesdetail = sales_product_detail::updateOrCreate(
                   ['sales_bill_id' => $sales_bill_id,
                    'company_id'=>$company_id,'sales_products_detail_id'=>$chargesvalue['csales_product_id'],],
                   $chargesdetail);

              
      }
     
     
            
    }  

      sales_bill_payment_detail::where('sales_bill_id',$sales_bill_id)->update(array(
            'deleted_by' => Auth::User()->user_id,
            'deleted_at' => date('Y-m-d H:i:s'),
            'total_bill_amount'=>0
        ));
    

    $paymentanswers     =    array();

         foreach($data[2] AS $key=>$value2)
          {
             if($value2['id']==3)
              {
                $paymentanswers['bankname']    =   $data[1]['bankname'];
                $paymentanswers['chequeno']    =   $data[1]['chequeno'];
                $paymentanswers['customer_creditnote_id']    =     NULL;
              }
              elseif($value2['id']==7)
              {
                $paymentanswers['bankname']    =     $data[1]['netbankname'];
                $paymentanswers['chequeno']    =     '';
                $paymentanswers['customer_creditnote_id']    =     NULL;
              }
              elseif($value2['id']==6)
              {
                $paymentanswers['bankname']    =     $data[1]['duedate'];
                $paymentanswers['chequeno']    =     '';
                $paymentanswers['customer_creditnote_id']    =     NULL;
              }
              elseif($value2['id']==8)
              {
                $paymentanswers['customer_creditnote_id']    =     $data[1]['creditnoteid'];
                $paymentanswers['bankname']='';
                $paymentanswers['chequeno'] =  '';

              }
              else
              {
                $paymentanswers['bankname']='';
                $paymentanswers['chequeno'] =  '';
                $paymentanswers['customer_creditnote_id']    =     NULL;
              }
           
                
                $paymentanswers['sales_bill_id']                 =  $sales_bill_id;
                $paymentanswers['total_bill_amount']             =  $value2['value'];
                $paymentanswers['payment_method_id']             =  $value2['id'];
                $paymentanswers['created_by']                    =  Auth::User()->user_id;
                $paymentanswers['deleted_at'] =  NULL;
                $paymentanswers['deleted_by'] =  NULL;
                
            
           
       
           $paymentdetail = sales_bill_payment_detail::updateOrCreate(
               ['sales_bill_id' => $sales_bill_id,'sales_bill_payment_detail_id'=>$value2['sales_payment_id'],],
               $paymentanswers);


            
    }

    if($data[1]['creditaccountid']=='')
    {

        customer_creditaccount::where('sales_bill_id',$sales_bill_id)->update(array(
            'deleted_by' => Auth::User()->user_id,
            'deleted_at' => date('Y-m-d H:i:s'),
            'credit_amount'=>0,
            'balance_amount'=>0
          ));
   
        foreach($data[2] AS $key=>$value3)
          {
              if($value3['id']==6)
              {
                  if($value3['value'] !='' || $value3['value']!=0)
                  {
                         $sales = customer_creditaccount::updateOrCreate(
                        ['sales_bill_id' => $sales_bill_id, 'company_id'=>$company_id,],
                        ['customer_id'=>$data[1]['customer_id'],
                            'bill_date'=>$invoice_date,
                            'duedate'=>$data[1]['duedate'],
                            'credit_amount'=>$value3['value'],
                            'balance_amount'=>$value3['value'],
                            'created_by' =>$created_by,
                            'deleted_at' =>NULL,
                            'deleted_by' =>NULL,
                            'is_active' => "1"
                            ]
                        );
                    }

                
              }
        
          }

    }
    else
    {
       if($data[1]['creditbalcheck']==0)
       {

            customer_creditaccount::where('sales_bill_id',$sales_bill_id)->update(array(
              'deleted_by' => Auth::User()->user_id,
              'deleted_at' => date('Y-m-d H:i:s'),
              'credit_amount'=>0,
              'balance_amount'=>0
            ));
     
          foreach($data[2] AS $key=>$value3)
            {
                if($value3['id']==6)
                {
                    if($value3['value'] !='' || $value3['value']!=0)
                    {
                           $sales = customer_creditaccount::updateOrCreate(
                          ['sales_bill_id' => $sales_bill_id, 'company_id'=>$company_id,],
                          ['customer_id'=>$data[1]['customer_id'],
                              'bill_date'=>$invoice_date,
                              'duedate'=>$data[1]['duedate'],
                              'credit_amount'=>$value3['value'],
                              'balance_amount'=>$value3['value'],
                              'created_by' =>$created_by,
                              'deleted_at' =>NULL,
                              'deleted_by' =>NULL,
                              'is_active' => "1"
                              ]
                          );
                      }

                  
                }
          
            }
       }
    }

    if($data[1]['editcreditnotepaymentid']!='')
    {

        creditnote_payment::where('creditnote_payment_id',$data[1]['editcreditnotepaymentid'])->update(array(
              'deleted_by' => Auth::User()->user_id,
              'deleted_at' => date('Y-m-d H:i:s'),
              'creditnote_amount'=>0,
              'used_amount'=>0,
              'balance_amount'=>0
            ));

        $updatecreditnoteamount    =  customer_creditnote::select('balance_amount')
                    ->where('customer_creditnote_id',$data[1]['editcreditnoteid'])
                    ->where('company_id',Auth::user()->company_id)
                    ->get();

          $updatecreditamount   =    $updatecreditnoteamount[0]['balance_amount'] + $data[1]['editcreditnoteamount'];

         customer_creditnote::where('customer_creditnote_id',$data[1]['editcreditnoteid'])->update(array(
                      'balance_amount' => $updatecreditamount
                  ));   
    }
    

    if($data[1]['creditnoteid']!='')
    {

        $creditbalanceamt   =    $data[1]['creditnoteamount'] - $data[1]['issueamount'];
        $creditnotepayment = creditnote_payment::updateOrCreate(
            ['sales_bill_id' => $sales_bill_id, 'company_id'=>$company_id,],
            ['customer_id'=>$data[1]['customer_id'],
                'customer_creditnote_id'=>$data[1]['creditnoteid'],
                'creditnote_amount'=>$data[1]['creditnoteamount'],
                'used_amount'=>$data[1]['issueamount'],
                'balance_amount'=>$creditbalanceamt,
                'created_by' =>$created_by,
                'is_active' => "1",
                'deleted_at' =>NULL,
                'deleted_by' =>NULL,
            ]
        );

          customer_creditnote::where('customer_creditnote_id',$data[1]['creditnoteid'])->update(array(
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
            
           if($data[1]['sales_bill_id'] != '')
          {   
              return json_encode(array("Success"=>"True","Message"=>"Billing successfully Update!","url"=>"view_bill"));
          }
          else
          {
              return json_encode(array("Success"=>"True","Message"=>"Billing has been successfully added.","url"=>"sales_bill"));
          }

               

           
        }
        else
        {
            return json_encode(array("Success"=>"False","Message"=>"Something Went Wrong"));
        }
        //return back()->withInput();

    }

public function billingprint_create(Request $request)
    {


      $data = $request->all();

        $userId = Auth::User()->user_id;
        $company_id = Auth::User()->company_id;


        $created_by = $userId;

       
        $cstate_id = company_profile::select('state_id','billtype','bill_number_prefix','tax_type','billprint_type','series_type','bill_number_prefix')->where('company_id',Auth::user()->company_id)->get(); 
                
               
         if($data[1]['customer_id'] == '')
         {
              $state_id   =    $cstate_id[0]['state_id'];
         }
         else{

               if($data[1]['duedays']!='' && $data[1]['duedays']!=0)
               {
                    customer::where('customer_id',$data[1]['customer_id'])->update(array(
                    'modified_by' => Auth::User()->user_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'outstanding_duedays'=>$data[1]['duedays']
                  ));
               }
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

        
         $invoice_date            =     $data[1]['invoice_date'];
         $selling_after_discount  =     $data[1]['totalwithout_gst'] - $data[1]['roomwisediscount_amount'];
         $roundoff    =    round($data[1]['ggrand_total']) - $data[1]['ggrand_total'];

         //$state_id = customer_address_detail::select('state_id')->where('company_id',Auth::user()->company_id)->where('customer_id','=',$data[1]['customer_id'])->first();

          sales_bill::where('sales_bill_id',$data[1]['sales_bill_id'])->update(array(
            'modified_by' => Auth::User()->user_id,
            'updated_at' => date('Y-m-d H:i:s')
          ));

     try {
            DB::beginTransaction();    

          if($cstate_id[0]['tax_type'] == 1)
              {
                  $totalcgst      =     0;
                  $totalsgst      =     0;
              }
              else
              {
                  $totalcgst      =     $data[1]['total_cgst'];
                  $totalsgst      =     $data[1]['total_sgst'];
              } 

        $sales = sales_bill::updateOrCreate(
            ['sales_bill_id' => $data[1]['sales_bill_id'], 'company_id'=>$company_id,],
            ['customer_id'=>$data[1]['customer_id'],
            'bill_no'=>$data[1]['invoice_no'],
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
                'total_cgst_amount'=>$totalcgst,
                'total_sgst_amount'=>$totalsgst,
                'gross_total'=>$data[1]['grand_total'],
                'shipping_charges'=>$data[1]['charges_total'],
                'round_off'=>$roundoff,
                'total_bill_amount'=>$data[1]['ggrand_total'],
                'official_note'=>$data[1]['official_note'],
                'print_note'=>$data[1]['print_note'],
                'created_by' =>$created_by,
                'is_active' => "1"
            ]
        );


       $sales_bill_id = $sales->sales_bill_id;


  //////////////////////////////////// To make Bill series Month Wise and Year wise as per the value selected from Company Profile......

              $todate       =    date('Y-m-d');
              
              $newyear      =   date('Y-04-01');
              
              $newmonth     =   date('Y-m-01');
              $f1     =    (date('m')<'04') ? date('y',strtotime('-1 year')) : date('y');
              $f2     =    (date('m')>'03') ? date('y',strtotime('+1 year')) : date('y');

//////////////////For Bill series Year Wise 
        if ($cstate_id[0]['series_type'] == 1) {

                    $nseries = sales_bill::select('bill_series')
                                        ->where('sales_bill_id', '<', $sales_bill_id)
                                        ->where('company_id', Auth::user()->company_id)
                                        ->orderBy('sales_bill_id', 'DESC')
                                        ->take('1');

                    if($todate >= $newyear){
                       $nseries->whereRaw("STR_TO_DATE(sales_bills.bill_date,'%d-%m-%Y') >= '$newyear'");
                    }

                   
                        $billprefix = $cstate_id[0]['bill_number_prefix'];


                    $newseries = $nseries->first();

                    if ($newseries != '') {
                        $billseries = $newseries['bill_series'] + 1;
                    } else {
                        $billseries = 1;
                    }

                    $finalinvoiceno = $billprefix . $billseries . '/' . $f1 . '-' . $f2;
                }
 //////////////////For Bill series Month Wise        
        else
        {
            if($todate>=$newmonth)
              {

                  $newseries  =  sales_bill::select('bill_series')
                                            ->whereRaw("STR_TO_DATE(sales_bills.bill_date,'%d-%m-%Y') >= '$newmonth'")
                                            ->where('sales_bill_id','<',$sales_bill_id)
                                            ->orderBy('sales_bill_id','DESC')
                                            ->take('1')
                                            ->first();
                       
               
                  if($newseries=='')
                  {
                      $billseries  =  1;
                  }
                  else
                  {
                      $billseries   = $newseries['bill_series']+1;
                      
                  }
                 
               
              }
              else
              {
                $newseries  =  sales_bill::select('bill_series')
                                            ->whereRaw("STR_TO_DATE(sales_bills.bill_date,'%d-%m-%Y') <= '$todate'")
                                            ->where('sales_bill_id','<',$sales_bill_id)
                                            ->orderBy('sales_bill_id','DESC')
                                            ->take('1')
                                            ->first();
                      $billseries   = $newseries['bill_series']+1;

                
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
              
              $finalinvoiceno = $cstate_id[0]['bill_number_prefix'].$dd.''.$id1;
        }   
              

        

        if($data[1]['sales_bill_id']=='' || $data[1]['sales_bill_id']==null)
        {  

         sales_bill::where('sales_bill_id',$sales_bill_id)->update(array(
            'bill_no' => $finalinvoiceno,
            'bill_series' => $billseries
         ));
       }
    

       sales_product_detail::where('sales_bill_id',$sales_bill_id)->update(array(
            'modified_by' => Auth::User()->user_id,
            'updated_at' => date('Y-m-d H:i:s')
        ));

    
        $productdetail     =    array();

       

         foreach($data[0] AS $billkey=>$billvalue)
          {
              $inwardids    =  '';
              $inwardqtys   =  '';
               if($billvalue['barcodesel']!='')
              {

                  if($cstate_id[0]['tax_type'] == 1)
                  {
                      $halfgstper      =     0;
                      $halfgstamt      =     0;
                  }
                  else
                  {
                      $halfgstper      =     $billvalue['prodgstper']/2;
                      $halfgstamt      =     $billvalue['prodgstamt']/2;
                  }
                     
                      // $productdetail['bill_date']                            =    $invoice_date;
                      // $productdetail['product_system_barcode']               =    $billvalue['barcodesel'];
                      $productdetail['product_id']                           =    $billvalue['productid'];
                      $productdetail['price_master_id']                      =    $billvalue['price_master_id'];
                      $productdetail['qty']                                  =    $billvalue['qty'];
                      $productdetail['mrp']                                  =    $billvalue['mrp'];
                      $productdetail['sellingprice_before_discount']         =    $billvalue['sellingprice_before_discount'];
                      $productdetail['discount_percent']                     =    $billvalue['discount_percent'];
                      $productdetail['discount_amount']                      =    $billvalue['discount_amount'];
                      $productdetail['mrpdiscount_amount']                   =    $billvalue['mrpdiscount_amount'];
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

          
                if($billvalue['oldprice_master_id'] != ''){

                   price_master::where('price_master_id',$billvalue['oldprice_master_id'])->update(array(
                    'modified_by' => Auth::User()->user_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'product_qty' => DB::raw('product_qty + '.$billvalue['oldqty'])
                    ));
                 
                  }

                   price_master::where('price_master_id',$billvalue['price_master_id'])->update(array(
                  'modified_by' => Auth::User()->user_id,
                  'updated_at' => date('Y-m-d H:i:s'),
                  'product_qty' => DB::raw('product_qty - '.$billvalue['qty'])
                  ));

///////////////////First In First Out Logic//////////////////////////////////////////////////////////////

                    $oldinwardids       =     explode(',',substr($billvalue['inwardids'],0,-1));
                    $oldinwardqtys      =     explode(',',substr($billvalue['inwardqtys'],0,-1));

                
                       $ccount    =   0;  
                       $icount    =   0;
                       $pcount    =   0;
                       $done      =   0;
                       $firstout  =   0;
                       $restqty   =   $billvalue['qty'];


                         

              if($billvalue['price_master_id']!=$billvalue['oldprice_master_id'] || $billvalue['qty']!=$billvalue['oldqty'])  
               {    

                   if($billvalue['sales_product_id'] !='')
                       { 
                            foreach($oldinwardids as $l=>$val)
                            {
                                inward_product_detail::where('company_id',Auth::user()->company_id)
                                          ->where('inward_product_detail_id',$oldinwardids[$l])
                                          ->update(array(
                                              'modified_by' => Auth::User()->user_id,
                                              'updated_at' => date('Y-m-d H:i:s'),
                                              'pending_return_qty' => DB::raw('pending_return_qty + '.$oldinwardqtys[$l])
                                              ));

                            }  
                       }   
                       
                  if($billvalue['qty']>0)
                  {
                      $prodtype    =        product::select('product_type')
                                            ->where('company_id',Auth::user()->company_id)
                                            ->where('product_id',$billvalue['productid'])->get();

                       $prid      =         price_master::select('offer_price','batch_no')
                                            ->where('company_id',Auth::user()->company_id)
                                            ->where('price_master_id',$billvalue['price_master_id'])->get();

                       $qquery    =         inward_product_detail::select('inward_product_detail_id','pending_return_qty')
                                            ->where('product_id',$billvalue['productid'])
                                            ->where('company_id',Auth::user()->company_id)
                                            ->where('pending_return_qty','!=',0);

                                            
                     if($cstate_id[0]['billtype']==3)
                      {
                            $qquery->where('batch_no',$prid[0]['batch_no']);
                      }
                      if($prodtype[0]['product_type']==1)
                      {
                            $qquery->where('offer_price',$prid[0]['offer_price']);
                      }
                      
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
                                              'pending_return_qty' => DB::raw('pending_return_qty - '.$billvalue['qty'])
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

                }   
                if($inwardids!='')
                {
                  $productdetail['inwardids']                          =    $inwardids;
                  $productdetail['inwardqtys']                         =    $inwardqtys;
                }   
                else
                {  
                  $productdetail['inwardids']                          =    $billvalue['inwardids'];
                  $productdetail['inwardqtys']                         =    $billvalue['inwardqtys'];
                }
                 // echo $inwardids;
                 // echo $inwardqtys;

                $billproductdetail = sales_product_detail::updateOrCreate(
                   ['sales_bill_id' => $sales_bill_id,
                    'company_id'=>$company_id,'sales_products_detail_id'=>$billvalue['sales_product_id'],],
                   $productdetail);

              
      }
     
     
            
     }


          $chargesdetail     =    array();

       

         foreach($data[3] AS $chargeskey=>$chargesvalue)
          {

               if($chargesvalue['chargesamt']!='')
              {


                      $halfgstper      =     $chargesvalue['csprodgstper']/2;
                      $halfgstamt      =     $chargesvalue['csprodgstamt']/2;
                      // $chargesdetail['bill_date']                            =    $invoice_date;
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

                
               
                   $billchargesdetail = sales_product_detail::updateOrCreate(
                   ['sales_bill_id' => $sales_bill_id,
                    'company_id'=>$company_id,'sales_products_detail_id'=>$chargesvalue['csales_product_id'],],
                   $chargesdetail);

              
      }
     
     
            
    }  

      sales_bill_payment_detail::where('sales_bill_id',$sales_bill_id)->update(array(
            'deleted_by' => Auth::User()->user_id,
            'deleted_at' => date('Y-m-d H:i:s'),
            'total_bill_amount'=>0
        ));
    

    $paymentanswers     =    array();

         foreach($data[2] AS $key=>$value2)
          {
             if($value2['id']==3)
              {
                $paymentanswers['bankname']    =   $data[1]['bankname'];
                $paymentanswers['chequeno']    =   $data[1]['chequeno'];
                $paymentanswers['customer_creditnote_id']    =     NULL;
              }
              elseif($value2['id']==7)
              {
                $paymentanswers['bankname']    =     $data[1]['netbankname'];
                $paymentanswers['chequeno']    =     '';
                $paymentanswers['customer_creditnote_id']    =     NULL;
              }
              elseif($value2['id']==6)
              {
                $paymentanswers['bankname']    =     $data[1]['duedate'];
                $paymentanswers['chequeno']    =     '';
                $paymentanswers['customer_creditnote_id']    =     NULL;
              }
              elseif($value2['id']==8)
              {
                $paymentanswers['customer_creditnote_id']    =     $data[1]['creditnoteid'];
                $paymentanswers['bankname']='';
                $paymentanswers['chequeno'] =  '';

              }
              else
              {
                $paymentanswers['bankname']='';
                $paymentanswers['chequeno'] =  '';
                $paymentanswers['customer_creditnote_id']    =     NULL;
              }
           
                
                $paymentanswers['sales_bill_id']                 =  $sales_bill_id;
                $paymentanswers['total_bill_amount']             =  $value2['value'];
                $paymentanswers['payment_method_id']             =  $value2['id'];
                $paymentanswers['created_by']                    =  Auth::User()->user_id;
                $paymentanswers['deleted_at'] =  NULL;
                $paymentanswers['deleted_by'] =  NULL;
                
            
           
       
           $paymentdetail = sales_bill_payment_detail::updateOrCreate(
               ['sales_bill_id' => $sales_bill_id,'sales_bill_payment_detail_id'=>$value2['sales_payment_id'],],
               $paymentanswers);


            
    }

    if($data[1]['creditaccountid']=='')
    {

        customer_creditaccount::where('sales_bill_id',$sales_bill_id)->update(array(
            'deleted_by' => Auth::User()->user_id,
            'deleted_at' => date('Y-m-d H:i:s'),
            'credit_amount'=>0,
            'balance_amount'=>0
          ));
   
        foreach($data[2] AS $key=>$value3)
          {
              if($value3['id']==6)
              {
                  if($value3['value'] !='' || $value3['value']!=0)
                  {
                         $sales = customer_creditaccount::updateOrCreate(
                        ['sales_bill_id' => $sales_bill_id, 'company_id'=>$company_id,],
                        ['customer_id'=>$data[1]['customer_id'],
                            'bill_date'=>$invoice_date,
                            'duedate'=>$data[1]['duedate'],
                            'credit_amount'=>$value3['value'],
                            'balance_amount'=>$value3['value'],
                            'created_by' =>$created_by,
                            'deleted_at' =>NULL,
                            'deleted_by' =>NULL,
                            'is_active' => "1"
                            ]
                        );
                    }

                
              }
        
          }

    }
    else
    {
       if($data[1]['creditbalcheck']==0)
       {

            customer_creditaccount::where('sales_bill_id',$sales_bill_id)->update(array(
              'deleted_by' => Auth::User()->user_id,
              'deleted_at' => date('Y-m-d H:i:s'),
              'credit_amount'=>0,
              'balance_amount'=>0
            ));
     
          foreach($data[2] AS $key=>$value3)
            {
                if($value3['id']==6)
                {
                    if($value3['value'] !='' || $value3['value']!=0)
                    {
                           $sales = customer_creditaccount::updateOrCreate(
                          ['sales_bill_id' => $sales_bill_id, 'company_id'=>$company_id,],
                          ['customer_id'=>$data[1]['customer_id'],
                              'bill_date'=>$invoice_date,
                              'duedate'=>$data[1]['duedate'],
                              'credit_amount'=>$value3['value'],
                              'balance_amount'=>$value3['value'],
                              'created_by' =>$created_by,
                              'deleted_at' =>NULL,
                              'deleted_by' =>NULL,
                              'is_active' => "1"
                              ]
                          );
                      }

                  
                }
          
            }
       }
    }

    if($data[1]['editcreditnotepaymentid']!='')
    {

        creditnote_payment::where('creditnote_payment_id',$data[1]['editcreditnotepaymentid'])->update(array(
              'deleted_by' => Auth::User()->user_id,
              'deleted_at' => date('Y-m-d H:i:s'),
              'creditnote_amount'=>0,
              'used_amount'=>0,
              'balance_amount'=>0
            ));

        $updatecreditnoteamount    =  customer_creditnote::select('balance_amount')
                    ->where('customer_creditnote_id',$data[1]['editcreditnoteid'])
                    ->where('company_id',Auth::user()->company_id)
                    ->get();

          $updatecreditamount   =    $updatecreditnoteamount[0]['balance_amount'] + $data[1]['editcreditnoteamount'];

         customer_creditnote::where('customer_creditnote_id',$data[1]['editcreditnoteid'])->update(array(
                      'balance_amount' => $updatecreditamount
                  ));   
    }
    

    if($data[1]['creditnoteid']!='')
    {

        $creditbalanceamt   =    $data[1]['creditnoteamount'] - $data[1]['issueamount'];
        $creditnotepayment = creditnote_payment::updateOrCreate(
            ['sales_bill_id' => $sales_bill_id, 'company_id'=>$company_id,],
            ['customer_id'=>$data[1]['customer_id'],
                'customer_creditnote_id'=>$data[1]['creditnoteid'],
                'creditnote_amount'=>$data[1]['creditnoteamount'],
                'used_amount'=>$data[1]['issueamount'],
                'balance_amount'=>$creditbalanceamt,
                'created_by' =>$created_by,
                'is_active' => "1",
                'deleted_at' =>NULL,
                'deleted_by' =>NULL,
            ]
        );

          customer_creditnote::where('customer_creditnote_id',$data[1]['creditnoteid'])->update(array(
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
         
         if($data[1]['sales_bill_id'] != '')
          {   

            if($cstate_id[0]['billprint_type']==1)
            {
                return json_encode(array("Success"=>"True","Message"=>"Billing has been successfully added.","url"=>route('print_bill', ['id' => encrypt($sales_bill_id)]),"burl"=>"sales_bill"));
            }
            else
            {
                return json_encode(array("Success"=>"True","Message"=>"Billing has been successfully added.","url"=>route('thermalprint_bill', ['id' => encrypt($sales_bill_id)]),"burl"=>"sales_bill"));
            }
              
          }
          else
          {
            if($cstate_id[0]['billprint_type']==1)
            {
              return json_encode(array("Success"=>"True","Message"=>"Billing has been successfully added.","url"=>route('print_bill', ['id' => encrypt($sales_bill_id)]),"burl"=>"sales_bill"));
            }
            else
            {
              return json_encode(array("Success"=>"True","Message"=>"Billing has been successfully added.","url"=>route('thermalprint_bill', ['id' => encrypt($sales_bill_id)]),"burl"=>"sales_bill"));
            }
          }

           
        }
        else
        {
            return json_encode(array("Success"=>"False","Message"=>"Something Went Wrong"));
        }
        

    }
    public function edit_bill(Request $request)
    {
        $bill_id = decrypt($request->bill_id);
       
        $bill_data = sales_bill::where([
            ['sales_bill_id','=',$bill_id],
            ['company_id',Auth::user()->company_id]])
            ->with('customer')
            ->with('reference')
            ->with('customer_address_detail')
            ->with('sales_product_detail.product.editprice_master','sales_product_detail.batchprice_master','sales_product_detail.product.colour','sales_product_detail.product.size','sales_product_detail.product.uqc')
            ->with('sales_bill_payment_detail.payment_method')
            ->with('customer_creditaccount')
            ->with('creditnote_payment.customer_creditnote')
            ->select('*')
            ->first();

      //dd($bill_data);
        return json_encode(array("Success"=>"True","Data"=>$bill_data,"url"=>"sales_bill"));


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
