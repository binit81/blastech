<?php

namespace Retailcore\Products_Kit\Models;

use Illuminate\Database\Eloquent\Model;

class inward_kit_detail extends Model
{
     protected $primaryKey = 'inward_kit_detail_id'; //Default: id
     protected $guarded=['inward_kit_detail_id'];

    public function kitproduct()
    {
        return $this->hasOne('Retailcore\Products\Models\product\product','product_id','kitproduct_id');
    }
    public function itemproduct()
    {
        return $this->hasOne('Retailcore\Products\Models\product\product','product_id','product_id');
    }
    public function price_master()
    {
        return $this->hasMany('Retailcore\Products\Models\product\price_master','product_id','product_id')->where('deleted_at','=',NULL);
    }

    
}
