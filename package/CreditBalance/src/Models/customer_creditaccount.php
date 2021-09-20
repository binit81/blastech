<?php

namespace Retailcore\CreditBalance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class customer_creditaccount extends Model
{
    protected $primaryKey = 'customer_creditaccount_id'; //Default: id
    protected $guarded=['customer_creditaccount_id'];
    use SoftDeletes;
    
    public function customer_creditaccount()
    {
        return $this->hasMany('Retailcore\CreditBalance\Models\customer_creditaccount','customer_id','customer_id')->orderBy('customer_creditaccount_id','DESC');
    }
    public function sales_bill()
    {
        return $this->hasOne('Retailcore\Sales\Models\sales_bill','sales_bill_id','sales_bill_id');
    }
    public function customer()
    {
        return $this->hasOne('Retailcore\Customer\Models\customer\customer','customer_id','customer_id');
    }
    public function customer_address_detail()
    {
        return $this->hasOne('Retailcore\Customer\Models\customer\customer_address_detail','customer_id','customer_id');
    }
    public function customer_creditreceipt_detail()
    {
        return $this->hasMany('Retailcore\CreditBalance\Models\customer_creditreceipt_detail','customer_creditaccount_id','customer_creditaccount_id')->where('deleted_by','=',NULL);
    }
    public function totalcustomer_creditreceipt_detail()
    {
        return $this->hasMany('Retailcore\CreditBalance\Models\customer_creditreceipt_detail','customer_id','customer_id')->where('deleted_by','=',NULL);
    }
}
