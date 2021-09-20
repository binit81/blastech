<?php
namespace Retailcore\Inward_Stock\Models\inward;

use Illuminate\Database\Eloquent\Model;

class inward_stock extends Model
{
    protected $primaryKey = 'inward_stock_id'; //Default: id
    protected $guarded=['inward_stock_id'];


    public function product()
    {
        return $this->hasOne('Retailcore\Products\Models\product\product','product_id','product_id');
    }
    public function kitinward_product_detail()
    {
        return $this->hasOne('Retailcore\Inward_Stock\Models\inward\inward_product_detail','inward_stock_id','inward_stock_id')->whereNull('deleted_at');
    }
    public function inward_kit_detail()
    {
        return $this->hasMany('Retailcore\Products_Kit\Models\inward_kit_detail','inward_stock_id','inward_stock_id')->whereNull('deleted_at');
    }
    public function inward_product_detail()
    {
        return $this->hasMany('Retailcore\Inward_Stock\Models\inward\inward_product_detail','inward_stock_id','inward_stock_id')->whereNull('deleted_at');
    }

    public function supplier_payment_details()
    {
        return $this->hasMany('Retailcore\Supplier\Models\supplier\supplier_payment_detail','inward_stock_id','inward_stock_id')->whereNull('deleted_at')->orderBy('payment_method_id','DESC');
    }

    public function price_masters()
    {
        return $this->hasMany('Retailcore\Products\Models\product\price_master','inward_stock_id','inward_stock_id');
    }
    public function supplier_gstdetail()
    {
        return $this->hasone('Retailcore\Supplier\Models\supplier\supplier_gst','supplier_gst_id','supplier_gst_id');
    }



    public function sales_product_detail()
    {
        return $this->hasMany('Retailcore\Sales\Models\sales_product_detail','product_id','product_id')->where('deleted_at','=',NULL);
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


}
