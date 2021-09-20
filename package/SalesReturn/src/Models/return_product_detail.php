<?php

namespace Retailcore\SalesReturn\Models;

use Illuminate\Database\Eloquent\Model;

class return_product_detail extends Model
{
    protected $primaryKey = 'return_product_detail_id'; //Default: id
    protected $guarded=['return_product_detail_id'];

  	public function product()
    {
        return $this->hasOne('Retailcore\Products\Models\product\product','product_id','product_id');
    }
    public function return_bill()
    {
        return $this->hasOne('Retailcore\SalesReturn\Models\return_bill','return_bill_id','return_bill_id');
    }
    
     
}
