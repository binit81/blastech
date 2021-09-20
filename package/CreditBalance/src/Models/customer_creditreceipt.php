<?php

namespace Retailcore\CreditBalance\Models;

use Illuminate\Database\Eloquent\Model;

class customer_creditreceipt extends Model
{
    protected $primaryKey = 'customer_creditreceipt_id'; //Default: id
    protected $guarded=['customer_creditreceipt_id'];

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
        return $this->hasMany('Retailcore\CreditBalance\Models\customer_creditreceipt_detail','customer_creditreceipt_id','customer_creditreceipt_id');
    }
    public function customer_crerecp_payment()
    {
        return $this->hasMany('Retailcore\CreditBalance\Models\customer_crerecp_payment','customer_creditreceipt_id','customer_creditreceipt_id');
    }
    public function company()
    {
        return $this->hasOne('Retailcore\Company_Profile\Models\company_profile\company_profile','company_id','company_id');
    }
}
