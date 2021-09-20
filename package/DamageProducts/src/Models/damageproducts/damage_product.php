<?php

namespace Retailcore\DamageProducts\Models\damageproducts;

use Illuminate\Database\Eloquent\Model;

class damage_product extends Model
{
    protected $primaryKey = 'damage_product_id'; //Default: id
    protected $guarded=['damage_product_id'];
   

    public function damage_types()
    {
        return $this->hasOne('Retailcore\DamageProducts\Models\damageproducts\damage_type','damage_type_id','damage_type_id');
    }

    public function damage_product_detail()
    {
        return $this->hasOne('Retailcore\DamageProducts\Models\damageproducts\damage_product_detail','damage_product_id','damage_product_id');
    }

    public function product()
    {
        return $this->hasOne('Retailcore\Products\Models\product\product','product_id','product_id');
    }


    public function damageproduct_detail()
    {
        return $this->hasMany('Retailcore\DamageProducts\Models\damageproducts\damage_product_detail','damage_product_id','damage_product_id');
    }
}
