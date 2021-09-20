<?php

namespace Retailcore\SalesReport\Models;

use Retailcore\Sales\Models\sales_bill;
use Retailcore\Sales\Models\sales_product_detail;
use Retailcore\Sales\Models\sales_bill_payment_detail;
use Retailcore\SalesReturn\Models\return_product_detail;
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
class productwiseBills_export implements FromArray, WithHeadings
{

   // use Exportable;

    private $myArray;
    private $myHeadings;

    public function __construct($myArray, $myHeadings){
        $this->myArray = $myArray;
        $this->myHeadings = $myHeadings;
    }

    public function array(): array{

       
        $newArray  = array();
        $state_id  =  company_profile::select('state_id','tax_type','decimal_points')->where('company_id',Auth::user()->company_id)->get();
       
        $tax_type        = $state_id[0]['tax_type'];
        $decimal_points  = $state_id[0]['decimal_points'];
        
        //return $this->myArray;
            foreach($this->myArray['sales'] as $sales)
            {
                    $count = '';
                    $rows    = [];
                   
                    
                    $state_id  =  company_profile::select('state_id')->where('company_id',Auth::user()->company_id)->get();
                   if($tax_type==1)
                   {
                              $totaligstper      =   $sales->igst_percent;
                              $totaligstamount   =   $sales->igst_amount;
                   } 
                   else
                   {
                      if($sales['sales_bill']['state_id']==$state_id[0]['state_id'])
                      {
                                  $totalcgstper      =   $sales->cgst_percent;
                                  $totalcgstamount   =   $sales->cgst_amount;
                                  $totalsgstper      =   $sales->sgst_percent;
                                  $totalsgstamount   =   $sales->sgst_amount;
                                  $totaligstper      =   '0';
                                  $totaligstamount   =   '0';
                      }
                      else
                      {
                                  $totalcgstper      =   '0';
                                  $totalcgstamount   =   '0';
                                  $totalsgstper      =   '0';
                                  $totalsgstamount   =   '0';
                                  $totaligstper      =   $sales->igst_percent;
                                  $totaligstamount   =   $sales->igst_amount;
                      }
                   }
                   if($sales['product']['supplier_barcode']!='' || $sales['product']['supplier_barcode']!=NULL)
                  {
                     $barcode = $sales['product']['supplier_barcode'];
                  }
                  else
                  {
                    $barcode = $sales['product']['product_system_barcode'];
                  }
                    
                   $rows[] = $sales['sales_bill']['bill_no'];
                   $rows[] = $sales['sales_bill']['bill_date'];
                   $rows[] = $sales['sales_bill']['customer']['customer_name'];
                   $rows[] = $sales['product']['product_name'];
                   $rows[] = $barcode;
                   $rows[] = round($sales->sellingprice_before_discount,2);
                   $rows[] = $sales->qty;
                   $rows[] = $sales->discount_percent!=''?$sales->discount_percent:'0';
                   $rows[] = $sales->discount_amount!=''?round($sales->discount_amount,2):'0';
                   $rows[] = $sales->overalldiscount_amount!=''?round($sales->overalldiscount_amount,2):'0';
                   $rows[] = $sales->sellingprice_afteroverall_discount!=''?round($sales->sellingprice_afteroverall_discount,2):'0';
                   if($tax_type==1)
                   {
                        $rows[] = $totaligstper;
                        $rows[] = $totaligstamount;
                   } 
                   else
                   {
                       $rows[] = $totalcgstper;
                       $rows[] = $totalcgstamount;
                       $rows[] = $totalsgstper;
                       $rows[] = $totalsgstamount;
                       $rows[] = $totaligstper;
                       $rows[] = $totaligstamount;
                   }
                   
                   $rows[] = round($sales->total_amount,$decimal_points);
                   $rows[] = $sales['sales_bill']['reference']['reference_name'];
           

               $newArray[]  = $rows;
 
        }

        foreach($this->myArray['returnbill'] as $returnbill)
            {
                    $count = '';
                    $rows    = [];
                   
                    
                    $state_id  =  company_profile::select('state_id','decimal_points')->where('company_id',Auth::user()->company_id)->get();
                    $decimal_points   =  $state_id[0]['decimal_points'];

                  if($tax_type==1)
                   {
                         $totaligstper      =   $returnbill->igst_percent;
                         $totaligstamount   =   -1 *($returnbill->igst_amount);
                   } 
                   else
                   {
                      if($returnbill['return_bill']['state_id']==$state_id[0]['state_id'])
                      {
                                  $totalcgstper      =   $returnbill->cgst_percent;
                                  $totalcgstamount   =   -1 *($returnbill->cgst_amount);
                                  $totalsgstper      =   $returnbill->sgst_percent;
                                  $totalsgstamount   =   -1 *($returnbill->sgst_amount);
                                  $totaligstper      =   '0';
                                  $totaligstamount   =   '0';
                      }
                      else
                      {
                                  $totalcgstper      =   '0';
                                  $totalcgstamount   =   '0';
                                  $totalsgstper      =   '0';
                                  $totalsgstamount   =   '0';
                                  $totaligstper      =   $returnbill->igst_percent;
                                  $totaligstamount   =   -1 *($returnbill->igst_amount);
                      }
                  }

                  if($returnbill['product']['supplier_barcode']!='' || $returnbill['product']['supplier_barcode']!=NULL)
                  {
                     $barcode = $returnbill['product']['supplier_barcode'];
                  }
                  else
                  {
                    $barcode = $returnbill['product']['product_system_barcode'];
                  }
                   $rows[] = $returnbill['return_bill']['sales_bill']['bill_no'];
                   $rows[] = $returnbill['return_bill']['bill_date'];
                   $rows[] = $returnbill['return_bill']['customer']['customer_name'];
                   $rows[] = $returnbill['product']['product_name'];
                   $rows[] = $barcode;
                   $rows[] = -1 *(round($returnbill->sellingprice_before_discount,2));
                   $rows[] = -1 *($returnbill->qty);
                   $rows[] = $returnbill->discount_percent!=''?$returnbill->discount_percent:'0';
                   $rows[] = $returnbill->discount_amount!=''?-1 *(round($returnbill->discount_amount,2)):'0';
                   $rows[] = $returnbill->overalldiscount_amount!=''?-1 *(round($returnbill->overalldiscount_amount,2)):'0';
                   $rows[] = $returnbill->sellingprice_afteroverall_discount!=''?-1 *(round($returnbill->sellingprice_afteroverall_discount,2)):'0';
                    if($tax_type==1)
                   {
                       $rows[] = $totaligstper;
                       $rows[] = $totaligstamount;
                   } 
                   else
                   {
                       $rows[] = $totalcgstper;
                       $rows[] = $totalcgstamount;
                       $rows[] = $totalsgstper;
                       $rows[] = $totalsgstamount;
                       $rows[] = $totaligstper;
                       $rows[] = $totaligstamount;
                   }
                   
                   $rows[] = -1 *(round($returnbill->total_amount,$decimal_points));
                   $rows[] = $returnbill['return_bill']['reference']['reference_name'];
           

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

