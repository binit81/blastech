<?php

namespace Retailcore\SalesReport\Models;

use Retailcore\Sales\Models\sales_bill;
use Retailcore\Sales\Models\sales_product_detail;
use Retailcore\Sales\Models\sales_bill_payment_detail;
use Retailcore\SalesReturn\Models\return_bill;
use Retailcore\SalesReturn\Models\return_bill_payment;
use Retailcore\Customer\Models\customer\customer;
use Retailcore\Sales\Models\payment_method;
use Retailcore\Company_Profile\Models\company_profile\company_profile;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\FromCollection;
//use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\FromArray;
use DB;

// , WithBatchInserts, WithChunkReading
class viewbill_export implements FromArray, WithHeadings
{

    //use Exportable;
    private $myArray;
    private $myHeadings;

    public function __construct($myArray, $myHeadings){
        $this->myArray = $myArray;
        $this->myHeadings = $myHeadings;
    }

                

    public function array(): array{

         $payment_methods = payment_method::where('is_active','=','1')->where('payment_method_id','!=',9)->get();
         $state_id  =  company_profile::select('state_id','tax_type','decimal_points')->where('company_id',Auth::user()->company_id)->get();
       
         $tax_type        = $state_id[0]['tax_type'];
         $decimal_points  = $state_id[0]['decimal_points'];
           
        $newArray  = array();
        
        //return $this->myArray;
        foreach($this->myArray['sales'] as $sales)
        {
                $count = '';
                $rows    = [];
                $sellingbeforediscount = '';
                
                $state_id  =  company_profile::select('state_id')->where('company_id',Auth::user()->company_id)->get();
                $company_state   = $state_id[0]['state_id'];

                $sellingbeforediscount  =     $sales->sellingprice_after_discount + $sales->totaldiscount + $sales->totalcharges;
                $halfchargesgst         =     $sales->chargesgst / 2;
                if($tax_type==1)
                {
                    $totaligstamount   =   $sales->total_igst_amount + $sales->chargesgst;
                }
                else
                {
                     if($sales['state_id']==$company_state)
                      {
                                  $totalcgstamount   =   $sales->total_cgst_amount + $halfchargesgst;
                                  $totalsgstamount   =   $sales->total_sgst_amount + $halfchargesgst;
                                  $totaligstamount   =   '0';
                      }
                      else
                      {
                                  $totalcgstamount   =   '0';
                                  $totalsgstamount   =   '0';
                                  $totaligstamount   =   $sales->total_igst_amount + $sales->chargesgst;
                      }
                }

               
               $rows[] = '<b>'.$sales->bill_no.'</b>';
               $rows[] = $sales->bill_date;
               $rows[] = $sales['customer']['customer_name'];
               $rows[] = $sales->total_qty;
               $rows[] = $sellingbeforediscount;           
               $rows[] = $sales->totaldiscount!='' || $sales->totaldiscount!=null || $sales->totaldiscount!=0?$sales->totaldiscount:'0';          
               $rows[] = $sales->sellingprice_after_discount + $sales->totalcharges;
                if($tax_type==1)
                {
                    $rows[] = $totaligstamount;
                }
                else
                {
                     $rows[] = $totalcgstamount;
                     $rows[] = $totalsgstamount;
                     $rows[] = $totaligstamount;
                }
               
               $rows[] =round($sales->total_bill_amount,$decimal_points);
               foreach ($payment_methods as $payment_methods_value) {  
                $count  = 0;

               foreach($sales->sales_bill_payment_detail as $sales_payment_detail) { 

                       

                    if($payment_methods_value->payment_method_id == $sales_payment_detail->payment_method_id){

                        $rows[] = round($sales_payment_detail['total_bill_amount'],$decimal_points);
                        $count++;
                    }           
                }   
             
             if($count==0)
                {
                    $rows[]  = '0';
                }

           
        }
               $rows[] = $sales['reference']['reference_name'];
               $rows[] = $sales->official_note;
               $rows[] = $sales->print_note;

               $newArray[]  = $rows;
 
        }

        foreach($this->myArray['returnbill'] as $returnbill)
        {
                $count = '';
                $rows    = [];
                $sellingbeforediscount = '';
                
                $state_id  =  company_profile::select('state_id','decimal_points')->where('company_id',Auth::user()->company_id)->get();
                $company_state   = $state_id[0]['state_id'];
                $decimal_points  = $state_id[0]['decimal_points'];

                $sellingbeforediscount  =     $returnbill->sellingprice_after_discount + $returnbill->totaldiscount + $returnbill->totalcharges;
                $halfchargesgst         =     $returnbill->chargesgst / 2;
                if($tax_type==1)
                {
                    $totaligstamount   =   $returnbill->total_igst_amount + $returnbill->chargesgst;
                }
                else
                {

                    if($returnbill['state_id']==$company_state)
                    {
                                $totalcgstamount   =   $returnbill->total_cgst_amount + $halfchargesgst;
                                $totalsgstamount   =   $returnbill->total_sgst_amount + $halfchargesgst;
                                $totaligstamount   =   '0';
                    }
                    else
                    {
                                $totalcgstamount   =   '0';
                                $totalsgstamount   =   '0';
                                $totaligstamount   =   $returnbill->total_igst_amount + $returnbill->chargesgst;
                    }
               }
               $rows[] = $returnbill->sales_bill->bill_no;
               $rows[] = $returnbill->bill_date;
               $rows[] = $returnbill['customer']['customer_name'];
               $rows[] = -1 *($returnbill->total_qty);
               $rows[] = -1*($sellingbeforediscount);           
               $rows[] = -1 * ($returnbill->totaldiscount!='' || $returnbill->totaldiscount!=null || $returnbill->totaldiscount!=0?$returnbill->totaldiscount:'0');          
               $rows[] = -1 * ($returnbill->sellingprice_after_discount + $returnbill->totalcharges);
                if($tax_type==1)
                {
                    $rows[] = -1 * ($totaligstamount);
                }
                else
                {
                   $rows[] = -1 * ($totalcgstamount);
                   $rows[] = -1 * ($totalsgstamount);
                   $rows[] = -1 * ($totaligstamount);
                }
               
               $rows[] = -1 * (round($returnbill->total_bill_amount,$decimal_points));
               foreach ($payment_methods as $payment_methods_value) {  
                $count  = 0;

               foreach($returnbill->return_bill_payment as $return_payment_detail) { 

                       

                    if($payment_methods_value->payment_method_id == $return_payment_detail->payment_method_id){

                        $rows[] = -1 * (round($return_payment_detail['total_bill_amount'],$decimal_points));
                        $count++;
                    }           
                }   
             
             if($count==0)
                {
                    $rows[]  = '0';
                }
           
        }
               $rows[] = $returnbill['reference']['reference_name'];
               $rows[] = '';
               $rows[] = '';
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
    // public function batchSize(): int
    // {
    //     return 100;
    // }
    
    // public function chunkSize(): int
    // {
    //     return 100;
    // }

}

