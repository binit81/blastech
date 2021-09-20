<?php

namespace Retailcore\Inward_Stock\Models\inward;

use Illuminate\Database\Eloquent\Model;

class supplier_payment_detail extends Model
{
    protected $primaryKey = 'supplier_payment_detail_id'; //Default: id
    protected $guarded=['supplier_payment_detail_id'];


    public function payment_method()
    {
        return $this->hasMany('Retailcore\Sales\Models\payment_method','payment_method_id','payment_method_id')->whereNull('deleted_at')->orderBy('payment_order','ASC');
    }
}
