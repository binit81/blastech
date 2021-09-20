<?php

namespace Retailcore\DamageProducts\Models\damageproducts;

use Illuminate\Database\Eloquent\Model;

class damage_product_detail extends Model
{
    //
    protected $primaryKey = 'damage_product_detail_id'; //Default: id
    protected $guarded=['damage_product_detail_id'];

    public function product()
    {
        return $this->hasOne('Retailcore\Products\Models\product\product','product_id','product_id');
    }

    public function price_master()
    {
        return $this->hasOne('Retailcore\Products\Models\product\price_master','price_master_id','price_id');
    }

    public function damage_types()
    {
        return $this->hasOne('Retailcore\DamageProducts\Models\damageproducts\damage_type','damage_type_id','damage_type_id');
    }

    public function inward_product_detail()
    {
        return $this->hasOne('Retailcore\Inward_Stock\Models\inward\inward_product_detail','inward_product_detail_id','inward_product_detail_id');
    }
     public function damage_product()
    {
        return $this->hasOne('Retailcore\DamageProducts\Models\damageproducts\damage_product','damage_product_id','damage_product_id');
    }
}


