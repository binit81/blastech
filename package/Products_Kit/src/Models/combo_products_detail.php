<?php

namespace Retailcore\Products_Kit\Models;

use Illuminate\Database\Eloquent\Model;

class combo_products_detail extends Model
{
     protected $primaryKey = 'combo_products_detail_id'; //Default: id
     protected $guarded=['combo_products_detail_id'];

    public function product()
    {
        return $this->hasOne('Retailcore\Products\Models\product\product','product_id','product_id');
    }
    public function price_master()
    {
        return $this->hasMany('Retailcore\Products\Models\product\price_master','product_id','product_id')->where('deleted_at','=',NULL);
    }
   
}
