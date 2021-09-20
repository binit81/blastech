<?php

namespace Retailcore\SalesReturn\Models;

use Illuminate\Database\Eloquent\Model;

class return_bill_payment extends Model
{
    protected $primaryKey = 'return_bill_payment_id'; //Default: id
    protected $guarded=['return_bill_payment_id'];

    public function payment_method()
    {
        return $this->hasMany('Retailcore\Sales\Models\payment_method','payment_method_id','payment_method_id')->whereNull('deleted_at')->orderBy('payment_order','ASC');
    }
    public function customer_creditnote()
    {
        return $this->hasOne('Retailcore\CreditNote\Models\customer_creditnote','customer_creditnote_id','customer_creditnote_id')->whereNull('deleted_at');
    }
}
