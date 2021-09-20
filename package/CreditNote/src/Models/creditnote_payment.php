<?php

namespace Retailcore\CreditNote\Models;

use Illuminate\Database\Eloquent\Model;

class creditnote_payment extends Model
{
    protected $primaryKey = 'creditnote_payment_id'; //Default: id
    protected $guarded=['creditnote_payment_id'];

    public function customer_creditnote()
    {
        return $this->hasOne('Retailcore\CreditNote\Models\customer_creditnote','customer_creditnote_id','customer_creditnote_id')->where('deleted_at','=',NULL);
    }
     public function sales_bill()
    {
        return $this->hasOne('Retailcore\Sales\Models\sales_bill','sales_bill_id','sales_bill_id')->where('deleted_at','=',NULL);
    }
    public function return_bill()
    {
        return $this->hasOne('Retailcore\SalesReturn\Models\return_bill','return_bill_id','return_bill_id')->where('deleted_at','=',NULL);
    }
    public function customer()
    {
        return $this->hasOne('Retailcore\Customer\Models\customer\customer','customer_id','customer_id');
    }
}
