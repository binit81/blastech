<?php

namespace Retailcore\Products\Models\product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class price_master extends Model
{
    protected $primaryKey = 'price_master_id'; //Default: id
    protected $guarded=['price_master_id'];
    use SoftDeletes;

    public function inward_stock()
    {
        return $this->hasOne('Retailcore\Inward_Stock\Models\inward\inward_stock','inward_stock_id','inward_stock_id');
    }
    public function product()
    {
        return $this->hasOne('Retailcore\Products\Models\product\product','product_id','product_id');
    }

    public function batch_no_expiry_date()
    {
        return $this->hasOne('Retailcore\Inward_Stock\Models\inward\inward_product_detail','inward_stock_id','inward_stock_id');
    }
    public function inward_product_details()
    {
        return $this->hasOne('Retailcore\Inward_Stock\Models\inward\inward_product_detail','inward_stock_id','inward_stock_id');
    }
    public function sales_product_detail()
    {
        return $this->hasMany('Retailcore\Sales\Models\sales_product_detail','price_master_id','price_master_id')->where('deleted_at','=',NULL);
    }
    public function returnbill_product()
    {
        return $this->hasMany('Retailcore\SalesReturn\Models\returnbill_product','price_master_id','price_master_id')->where('deleted_at','=',NULL);
    }
    public function damage_product_detail()
    {
        return $this->hasMany('Retailcore\DamageProducts\Models\damageproducts\damage_product_detail','product_id','product_id')->where('deleted_at','=',NULL);
    }
    public function debit_product_detail()
    {
        return $this->hasMany('Retailcore\Debit_Note\Models\debit_note\debit_product_detail','price_master_id','price_master_id')->where('deleted_at','=',NULL);
    }

    public function damage_product()
    {
        return $this->hasMany('Retailcore\DamageProducts\Models\damageproducts\damage_product','damage_type_id','damage_type_id')->where('deleted_at','=',NULL);
    }

    public function inward_product_detail()
    {
        return $this->hasOne('Retailcore\Inward_Stock\Models\inward\inward_product_detail','product_id','product_id');
    }


    public function inward_product_detail_for_batchno()
    {
        return $this->hasMany('Retailcore\Inward_Stock\Models\inward\inward_product_detail','product_id','product_id')->where('store_id', $this->store_id);;
    }




}
