<?php

namespace Retailcore\PO\Models\purchase_order;

use Illuminate\Database\Eloquent\Model;

class purchase_order_detail extends Model
{
   protected  $primaryKey = 'purchase_order_detail_id';
   protected  $guarded = ['purchase_order_detail_id'];

    public function purchase_order()
    {
        return $this->hasOne('Retailcore\PO\Models\purchase_order\purchase_order','purchase_order_id','purchase_order_id');
    }
    public function product()
    {
        return $this->hasOne('Retailcore\Products\Models\product\product','product_id','product_id');
    }
}
