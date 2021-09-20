<?php

namespace Retailcore\Supplier\Models\supplier;

use Illuminate\Database\Eloquent\Model;

class supplier_payment_detail extends Model
{
    protected $primaryKey = 'supplier_payment_detail_id'; //Default: id
    protected $guarded=['supplier_payment_detail_id'];

    public function inward_stock()
    {
        return $this->hasone('Retailcore\Inward_Stock\Models\inward\inward_stock','inward_stock_id','inward_stock_id');
    }
    public function supplier_gstdetail()
    {
        return $this->hasone('Retailcore\Supplier\Models\supplier\supplier_gst','supplier_gst_id','supplier_gst_id');
    }

    public function payment_method()
    {
        return $this->hasMany('Retailcore\Sales\Models\payment_method','payment_method_id','payment_method_id')->whereNull('deleted_at')->orderBy('payment_order','ASC');
    }

}
