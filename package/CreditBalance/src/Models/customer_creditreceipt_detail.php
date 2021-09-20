<?php

namespace Retailcore\CreditBalance\Models;

use Illuminate\Database\Eloquent\Model;

class customer_creditreceipt_detail extends Model
{
    protected $primaryKey = 'customer_creditreceipt_detail_id'; //Default: id
    protected $guarded=['customer_creditreceipt_detail_id'];

    public function customer_creditreceipt()
    {
        return $this->hasOne('Retailcore\CreditBalance\Models\customer_creditreceipt','customer_creditreceipt_id','customer_creditreceipt_id');
    }
    public function customer_crerecp_payment()
    {
        return $this->hasMany('Retailcore\CreditBalance\Models\customer_crerecp_payment','customer_creditreceipt_id','customer_creditreceipt_id');
    }
}
