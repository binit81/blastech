<?php

namespace Retailcore\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class sales_bill_payment_detail extends Model
{
     protected $primaryKey = 'sales_bill_payment_detail_id'; //Default: id
     protected $guarded=['sales_bill_payment_detail_id'];
     use SoftDeletes;

    
     public function payment_method()
    {
        return $this->hasMany('Retailcore\Sales\Models\payment_method','payment_method_id','payment_method_id')->whereNull('deleted_at')->orderBy('payment_order','ASC');
    }
}
