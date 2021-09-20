<?php

namespace Retailcore\PO\Models\purchase_order;

use Illuminate\Database\Eloquent\Model;

class purchase_order extends Model
{
    protected  $primaryKey = 'purchase_order_id';
    protected $guarded = ['purchase_order_id'];


    public function supplier_gstdetail()
    {
        return $this->hasone('Retailcore\Supplier\Models\supplier\supplier_gst','supplier_gst_id','supplier_gst_id');
    }

    public function purchase_order_detail()
    {
        return $this->hasMany('Retailcore\PO\Models\purchase_order\purchase_order_detail','purchase_order_id','purchase_order_id');
    }

    public function purchase_order_product_detail()
    {
        return $this->hasOne('Retailcore\PO\Models\purchase_order\purchase_order_detail','purchase_order_id','purchase_order_id');
    }

    public function company()
    {
        return $this->hasOne('Retailcore\Company_Profile\Models\company_profile\company_profile','company_id','company_id');
    }

}
