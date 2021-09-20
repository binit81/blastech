<?php

namespace Retailcore\CreditBalance\Models;

use Illuminate\Database\Eloquent\Model;

class customer_crerecp_payment extends Model
{
    protected $primaryKey = 'customer_crerecp_payment_id'; //Default: id
    protected $guarded=['customer_crerecp_payment_id'];

    public function payment_method()
    {
        return $this->hasOne('Retailcore\Sales\Models\payment_method','payment_method_id','payment_method_id')->whereNull('deleted_at')->orderBy('payment_order','ASC');
    }
}
