<?php

namespace Retailcore\CreditNote\Models;

use Illuminate\Database\Eloquent\Model;

class customer_creditnote extends Model
{
    protected $primaryKey = 'customer_creditnote_id'; //Default: id
    protected $guarded=['customer_creditnote_id'];

    public function customer()
    {
        return $this->hasOne('Retailcore\Customer\Models\customer\customer','customer_id','customer_id');
    }
    public function sales_bill()
    {
        return $this->hasOne('Retailcore\Sales\Models\sales_bill','sales_bill_id','sales_bill_id');
    }
}
