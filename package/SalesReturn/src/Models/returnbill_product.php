<?php

namespace Retailcore\SalesReturn\Models;

use Illuminate\Database\Eloquent\Model;

class returnbill_product extends Model
{
    protected $primaryKey = 'returnbill_product_id'; //Default: id
    protected $guarded=['returnbill_product_id'];

    public function product()
    {
        return $this->hasOne('Retailcore\Products\Models\product\product','product_id','product_id');
    }
    public function return_product_detail()
    {
        return $this->hasOne('Retailcore\SalesReturn\Models\return_product_detail','return_product_detail_id','return_product_detail_id');
    }
      public function return_bill()
     {
         return $this->hasOne('Retailcore\SalesReturn\Models\return_bill','return_bill_id','return_bill_id');
     }
    public function sales_product_detail()
    {
        return $this->hasOne('Retailcore\Sales\Models\sales_product_detail','sales_products_detail_id','sales_products_detail_id');
    }

    //for batch no report count sold qty
    public function price_master_batch_wise()
    {
        return $this->hasOne('Retailcore\Products\Models\product\price_master','price_master_id','price_master_id');
    }
}
