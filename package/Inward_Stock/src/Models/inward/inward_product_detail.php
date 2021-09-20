<?php

namespace Retailcore\Inward_Stock\Models\inward;
use Illuminate\Database\Eloquent\Model;

class inward_product_detail extends Model
{
    protected $primaryKey = 'inward_product_detail_id'; //Default: id
    protected $guarded=['inward_product_detail_id'];

    public function product_detail()
    {
        return $this->hasOne('Retailcore\Products\Models\product\product','product_id','product_id');
    }

    public function inward_stock()
    {
        return $this->hasOne('Retailcore\Inward_Stock\Models\inward\inward_stock','inward_stock_id','inward_stock_id');
    }

	public function product()
    {
        return $this->hasOne('Retailcore\Products\Models\product\product','product_id','product_id');
    }
    public function kitproduct()
    {
        return $this->hasOne('Retailcore\Products\Models\product\product','product_id','product_id');
    }
    //for batch no report


    public function sales_product_detail()
    {
        return $this->hasMany('Retailcore\Sales\Models\sales_product_detail','product_id','product_id')->where('deleted_at','=',NULL);
    }

    public function returnbill_product()
    {
        return $this->hasMany('Retailcore\SalesReturn\Models\returnbill_product','product_id','product_id')->where('deleted_at','=',NULL);
    }

    public function damage_product_detail()
    {
        return $this->hasMany('Retailcore\DamageProducts\Models\damageproducts\damage_product_detail','product_id','product_id')->where('deleted_at','=',NULL);
    }
    public function debit_product_detail()
    {
        return $this->hasMany('Retailcore\Debit_Note\Models\debit_note\debit_product_detail','product_id','product_id')->where('deleted_at','=',NULL);
    }
}
