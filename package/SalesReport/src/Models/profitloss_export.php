<?php

namespace Retailcore\SalesReport\Models;

use Retailcore\Sales\Models\sales_bill;
use Retailcore\Sales\Models\sales_product_detail;
use Retailcore\Sales\Models\sales_bill_payment_detail;
use Retailcore\SalesReturn\Models\return_product_detail;
use Retailcore\Inward_Stock\Models\inward\inward_product_detail;
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
class profitloss_export implements FromArray, WithHeadings
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
        
        //return $this->myArray;
            foreach($this->myArray['productdetails'] as $sales_value)
            {
                    $count = '';
                    $rows    = [];
                   
                    $totalsellingprice   =   $sales_value['qty'] * $sales_value['sellingprice_before_discount'];
                    $totaldiscount       =   $sales_value['discount_amount']  + $sales_value['overalldiscount_amount'];
                    $totaldiscount       =   $totaldiscount!=''?round($totaldiscount,2):'0';

                     $inwardids   = explode(',' ,substr($sales_value['inwardids'],0,-1));
                     $inwardqtys  = explode(',' ,substr($sales_value['inwardqtys'],0,-1));
                     $total_price = 0;

                     if($sales_value['inwardids'] !='' || $sales_value['inwardids'] !=null)
                    {

                      foreach($inwardids as $inidkey=>$inids)
                      {
                            $cost_price = inward_product_detail::select('cost_rate')->find($inids);
                             
                            $total_price += $cost_price['cost_rate'] * $inwardqtys[$inidkey];            
                      } 
                      $averagecost      =   ($total_price / $sales_value['qty']) * $sales_value['qty'];
                      $profitamt        =   $sales_value->sellingprice_afteroverall_discount  - $averagecost;
                      $profitper        =   ($profitamt * 100)/$averagecost;
                    }
                    else
                    {
                      $averagecost      =   0;
                      $profitamt        =   $sales_value->sellingprice_afteroverall_discount  - $averagecost;
                      $profitper        =   0;
                    }

                      if($sales_value['product']['supplier_barcode']!='' || $sales_value['product']['supplier_barcode']!=NULL)
                      {
                         $barcode = $sales_value['product']['supplier_barcode'];
                      }
                      else
                      {
                        $barcode = $sales_value['product']['product_system_barcode'];
                      }
                   

                   $rows[] = $sales_value['sales_bill']['bill_no'];
                   $rows[] = $sales_value['sales_bill']['bill_date'];
                   $rows[] = $sales_value['product']['product_name'];
                   $rows[] = $barcode;
                   $rows[] = $sales_value['qty'];
                   $rows[] = round($totalsellingprice,2);
                   $rows[] = $totaldiscount;             
                   $rows[] = round($sales_value->sellingprice_afteroverall_discount,2);
                   $rows[] = round($averagecost,2);
                   $rows[] = round($profitamt,2);
                   $rows[] = round($profitper,2);
           

               $newArray[]  = $rows;
 
        }

        foreach($this->myArray['rproductdetails'] as $return_value)
            {
                    $count = '';
                    $rows    = [];
                   
                    $totalsellingprice   =   $return_value['qty'] * $return_value['sellingprice_before_discount'];
                    $totaldiscount       =   $return_value['discount_amount']  + $return_value['overalldiscount_amount'];
                    $totaldiscount       =   $totaldiscount!=''?round($totaldiscount,2):'-0';

                     $inwardids  = explode(',' ,substr($return_value['inwardids'],0,-1));
                     $inwardqtys = explode(',' ,substr($return_value['inwardqtys'],0,-1));
                     $total_price = 0;

                    if($return_value['inwardids'] !='' || $return_value['inwardids'] !=null)
                   { 
                      foreach($inwardids as $inidkey=>$inids)
                      {
                            $cost_price = inward_product_detail::select('cost_rate')->find($inids);
                             
                            $total_price += $cost_price['cost_rate'] * $inwardqtys[$inidkey];            
                      } 
                      $averagecost      =   ($total_price / $return_value['qty']) * $return_value['qty'];
                      $profitamt        =   $return_value->sellingprice_afteroverall_discount  - $averagecost;
                      $profitper        =   ($profitamt * 100)/$averagecost;
                    }
                    else
                    {
                        $averagecost      =   0;
                        $profitamt        =   $return_value->sellingprice_afteroverall_discount  - $averagecost;
                        $profitper        =   0;
                    }

                      if($return_value['product']['supplier_barcode']!='' || $return_value['product']['supplier_barcode']!=NULL)
                      {
                         $barcode = $return_value['product']['supplier_barcode'];
                      }
                      else
                      {
                        $barcode = $return_value['product']['product_system_barcode'];
                      }

                     $rows[] = $return_value['return_bill']['sales_bill']['bill_no'];
                     $rows[] = $return_value['return_bill']['bill_date'];
                     $rows[] = $return_value['product']['product_name'];
                     $rows[] = $barcode;
                     $rows[] = -1 *($return_value['qty']);
                     $rows[] = -1 *(round($totalsellingprice,2));
                     $rows[] =  $totaldiscount;             
                     $rows[] = -1*(round($return_value->sellingprice_afteroverall_discount,2));
                     $rows[] = -1 *(round($averagecost,2));
                     $rows[] = -1 *(round($profitamt,2));
                     $rows[] = -1 *(round($profitper,2));
           

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

