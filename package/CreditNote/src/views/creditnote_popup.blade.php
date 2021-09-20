<?php
/**
 * Created by PhpStorm.
 * User: Hemaxi
 * Date: 26/3/19
 * Time: 10:18 AM
 */

if(sizeof($creditnotepayment) != 0)
{

  
?>

<div class="billdata">
<div class="invoice-to-wrap pb-20">
     <div class="row">
    <div class="col-xl-12">
        
            <div class="invoice-from-wrap">
               
                <div class="row" style="margin:20px 0 0 0;">
                
                  
                </div>
            </div>
         
                             
        
                       <tr style="background:#88c241;border-bottom:1px #999 solid;border:1px #f3f3f3 solid;">
                        <table width="100%" cellpadding="6" border="0" frame="box"  style="border:1px solid #C0C0C0 !important;">
                            <thead>
                          <table border="0" frame="" width="100%" class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th scope="col" style="width:5%;cursor: pointer;"><b>SNo.</b></th>
                                    <th scope="col" style="width:11%;cursor: pointer;"><b>Bill No.</b></th>
                                    <th scope="col" style="width:10%;cursor: pointer;"><b>Bill Date</b></th>
                                    <th scope="col" style="width:14%;cursor: pointer;"><b>Bill Type</b></th>
                                    <th scope="col" style="width:17%;cursor: pointer;"><b>Customer Name</b></th>
                                    <th scope="col" style="width:14%;cursor: pointer;text-align:right !important;"><b>Credit Amt.</b></th>
                                    <th scope="col" style="width:14%;cursor: pointer;text-align:right !important;"><b>Used Amt.</b></th>
                                    <th scope="col" style="width:14%;cursor: pointer;text-align:right !important;"><b>Balance Amt.</b></th>

                                </tr>
                                </thead>
                               
                                <tbody id="productdetails">
                                  
                                  <?php
                               
                                 
                                  foreach($creditnotepayment as $saleskey=>$sales_value)
                                  {
                                   
                                    if ($saleskey % 2 == 0) {
                                            $tblclass = 'even';
                                        } else {
                                            $tblclass = 'odd';
                                        }
                                        $sr   =  $saleskey + 1;

                                        if($sales_value['return_bill_id']==null)
                                        {
                                          $billtype  =  'Sales Bill';
                                          $billno    =   $sales_value['sales_bill']['bill_no'];
                                        }
                                        else
                                        {
                                          $billtype  =  'Return Bill';
                                          $billno    =   $sales_value['sales_bill']['bill_no'];
                                        }

                                     
                                    ?>
                                    <tr class="<?php echo $tblclass ?>">
                                      <td class="leftAlign" style="padding: 0.70rem !important;">{{$sr}}</td>
                                      <td class="leftAlign" style="padding: 0.70rem !important;">{{$sales_value['sales_bill']['bill_no']}}</td>
                                      <td class="leftAlign" style="padding: 0.70rem !important;">{{$sales_value['created_at']->format('d-m-Y')}}</td>
                                      <td class="leftAlign" style="padding: 0.70rem !important;">{{$billtype}}</td>
                                      <td class="leftAlign" style="padding: 0.70rem !important;">{{$sales_value['customer']['customer_name']}}</td>
                                      <td class="rightAlign" style="text-align:right !important;padding: 0.70rem !important;">{{$sales_value['creditnote_amount']}}</td>
                                      <td class="rightAlign"  style="text-align:right !important;padding: 0.70rem !important;">{{$sales_value['used_amount']}}</td>
                                      <td class="rightAlign"  style="text-align:right !important;padding: 0.70rem !important;">{{$sales_value['balance_amount']}}</td>

                                      
                                    </tr>

                               
                                <?php
                              }
                              
                                ?>

                                </tbody>
                             
                        </table>


         
                 
            <br>
            <br>
            
            </div>
            
                
           
     
    </div>
</div>
</div>


<?php
}
?>