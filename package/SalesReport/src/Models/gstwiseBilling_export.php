<?php

namespace Retailcore\SalesReport\Models;

use Retailcore\Sales\Models\sales_bill;
use Retailcore\Sales\Models\sales_product_detail;
use Retailcore\SalesReturn\Models\return_product_detail;
use Retailcore\SalesReturn\Models\return_bill;
use Retailcore\Customer\Models\customer\customer;
use Retailcore\Sales\Models\payment_method;
use Retailcore\Products\Models\product\product;
use Retailcore\Company_Profile\Models\company_profile\company_profile;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;

// , WithBatchInserts, WithChunkReading
class gstwiseBilling_export implements FromArray, WithHeadings
{

   // use Exportable;

    private $myArray;
    private $myHeadings;

    public function __construct($myArray, $myHeadings){
        $this->myArray = $myArray;
        $this->myHeadings = $myHeadings;
    }

    public function array(): array{

        $gst_slabs = sales_product_detail::select('cgst_percent','sgst_percent','igst_percent')
            ->where('company_id',Auth::user()->company_id)
            ->where('deleted_at','=',NULL)
            ->orderBy('igst_percent', 'ASC')
            ->groupBy('igst_percent')
            ->get();
        $newArray  = array();
        
        //return $this->myArray;
            foreach($this->myArray['sales'] as $sales)
            {
                    $count = '';
                    $rows    = [];
                   
                    
                    $state_id  =  company_profile::select('state_id','tax_type','tax_title','currency_title','decimal_points')->where('company_id',Auth::user()->company_id)->get();
                    $company_state   = $state_id[0]['state_id'];
                    $tax_type        = $state_id[0]['tax_type'];
                    $decimal_points  = $state_id[0]['decimal_points'];
                   
                          
                   $rows[] = $sales['bill_no'];
                   $rows[] = $sales->bill_date;
                   $rows[] = $sales['customer']['customer_name'];
                   foreach($gst_slabs AS $gstkey=>$gst_value)
                    {
                         $scount  = 0;
                         $billtariff = 0;
                         $billcgst = 0;
                         $billsgst = 0;
                         $billigst = 0;

                        foreach($sales->sales_product_detail AS $salesroom_key=>$salesroom_value)
                        {
                            if($gst_value->igst_percent == $salesroom_value->igst_percent){
                                
                                $billtariff  +=  $salesroom_value->sellingprice_afteroverall_discount;
                                $billcgst    +=  $salesroom_value->cgst_amount;
                                $billsgst    +=  $salesroom_value->sgst_amount;
                                $billigst    +=  $salesroom_value->igst_amount;
                                $scount++;
                             }

                         }
                         if($tax_type==1)
                         {
                                if($scount == 0)
                                 {
                                  
                                    $rows[] =  '0';
                                    $rows[] =  '0';
                                 
                                 }
                                 else
                                 {
                                  
                                    $rows[] =  round($billtariff,2);
                                    $rows[] =  round($billigst,2);
                                 

                                 }
                         }
                         else
                         {
                                if($scount == 0)
                                 {
                                  
                                    $rows[] =  '0';
                                    $rows[] =  '0';
                                    $rows[] =  '0';
                                    $rows[] =  '0';
                                 
                                 }
                                 else
                                 {
                                  
                                    $rows[] =  round($billtariff,2);
                                  
                                    if($sales['state_id']==$company_state)
                                    {
                                      
                                        $rows[] =  round($billcgst,2);
                                        $rows[] =  round($billsgst,2);
                                        $rows[] =  '0.00';
                                       
                                    } 
                                    else
                                    {
                                       
                                        $rows[] =  '0.00';
                                        $rows[] =  '0.00';
                                        $rows[] =  round($billigst,2);
                                        
                                    }  
                                   

                                 }
                         }
                         
                        

                    }
                   $rows[] = round($sales->sellingprice_after_discount,2);
                   if($tax_type==1)
                   {
                        $rows[] = round($sales->total_igst_amount,2);
                   }
                   else
                   {
                        if($sales['state_id']==$company_state)
                        {
                           
                             $rows[] = round($sales->total_cgst_amount,2);
                             $rows[] = round($sales->total_sgst_amount,2);
                             $rows[] = '0.00';
                            
                        }
                        else
                        {
                           
                                $rows[] = '0.00';
                                $rows[] = '0.00';
                                $rows[] = round($sales->total_igst_amount,2);
                            
                        }
                   }
                   
                   $rows[] = round($sales->total_bill_amount,$decimal_points);
                   $rows[] = $sales['reference']['reference_name'];
           

               $newArray[]  = $rows;
 
        }

        foreach($this->myArray['returnbill'] as $returnbill)
        {
                    $count = '';
                    $rows    = [];
                   
                    
                    $state_id  =  company_profile::select('state_id','tax_type','tax_title','currency_title','decimal_points')->where('company_id',Auth::user()->company_id)->get();
                    $company_state   = $state_id[0]['state_id'];
                    $tax_type        = $state_id[0]['tax_type'];
                    $decimal_points  = $state_id[0]['decimal_points'];

                  
                   $rows[] = $returnbill['sales_bill']['bill_no'];
                   $rows[] = $returnbill->bill_date;
                   $rows[] = $returnbill['customer']['customer_name'];
                   foreach($gst_slabs AS $gstkey=>$gst_value)
                    {
                         $scount  = 0;
                         $billtariff = 0;
                         $billcgst = 0;
                         $billsgst = 0;
                         $billigst = 0;

                        foreach($returnbill->return_product_detail AS $returnroom_key=>$returnroom_value)
                        {
                            if($gst_value->igst_percent == $returnroom_value->igst_percent){
                                
                                $billtariff  +=  $returnroom_value->sellingprice_afteroverall_discount;
                                $billcgst    +=  $returnroom_value->cgst_amount;
                                $billsgst    +=  $returnroom_value->sgst_amount;
                                $billigst    +=  $returnroom_value->igst_amount;
                                $scount++;
                             }

                         }
                         if($tax_type==1)
                         {
                                if($scount == 0)
                                 {
                                  
                                    $rows[] =  '0';
                                    $rows[] =  '0';
                                   
                                 
                                 }
                                 else
                                 {
                                  
                                    $rows[] =  -1 * (round($billtariff,2));
                                    $rows[] =  -1 * (round($billigst,2));
                                  

                                 }
                         }
                         else
                         {
                                if($scount == 0)
                                 {
                                  
                                    $rows[] =  '0';
                                    $rows[] =  '0';
                                    $rows[] =  '0';
                                    $rows[] =  '0';
                                 
                                 }
                                 else
                                 {
                                  
                                    $rows[] =  -1 * (round($billtariff,2));
                                  
                                    if($sales['state_id']==$company_state)
                                    {
                                      
                                        $rows[] =  -1 * (round($billcgst,2));
                                        $rows[] =  -1 * (round($billsgst,2));
                                        $rows[] =  '0.00';
                                       
                                    } 
                                    else
                                    {
                                       
                                        $rows[] =  '0.00';
                                        $rows[] =  '0.00';
                                        $rows[] =  -1 * (round($billigst,2));
                                        
                                    }  
                                   

                                 }
                         }
                         
                        

                    }
                   $rows[] = -1 * (round($returnbill->sellingprice_after_discount,2));
                   if($tax_type==1)
                   {
                        $rows[] = -1 * (round($returnbill->total_igst_amount,2));
                   }
                   else
                   {
                        if($returnbill['state_id']==$company_state)
                        {
                           
                             $rows[] = -1 * (round($returnbill->total_cgst_amount,2));
                             $rows[] = -1 * (round($returnbill->total_sgst_amount,2));
                             $rows[] = '0.00';
                            
                        }
                        else
                        {
                           
                                $rows[] = '0.00';
                                $rows[] = '0.00';
                                $rows[] = -1 * (round($returnbill->total_igst_amount,2));
                            
                        }
                   }
                   
                   $rows[] = -1 * (round($returnbill->total_bill_amount,$decimal_points));
                   $rows[] = $returnbill['reference']['reference_name'];
           

               $newArray[]  = $rows;
 
        }
        // echo '<pre>';
        // print_r($newArray);
        // echo '</pre>';
        // exit;
       
       return $newArray;

    }
    public function headings(): array{
        return $this->myHeadings;
        
    }

   
}

