<?php

namespace Retailcore\SalesReturn\Models;

use Illuminate\Database\Eloquent\Model;

class return_bill extends Model
{
    protected $primaryKey = 'return_bill_id'; //Default: id
    protected $guarded=['return_bill_id'];

    public function customer()
    {
        return $this->hasOne('Retailcore\Customer\Models\customer\customer','customer_id','customer_id');
    }
    public function customer_address_detail()
    {
        return $this->hasOne('Retailcore\Customer\Models\customer\customer_address_detail','customer_id','customer_id');
    }
    public function return_product_detail()
    {
        return $this->hasMany('Retailcore\SalesReturn\Models\return_product_detail','return_bill_id','return_bill_id')->where('deleted_at','=',NULL);
    }
  	public function return_bill_payment()
    {
        return $this->hasMany('Retailcore\SalesReturn\Models\return_bill_payment','return_bill_id','return_bill_id')->where('deleted_at','=',NULL);
    }
    public function company()
    {
        return $this->hasOne('Retailcore\Company_Profile\Models\company_profile\company_profile','company_id','company_id');
    }
    public function state()
    {
        return $this->hasOne('App\state','state_id','state_id');
    }
    public function customer_creditnote()
    {
        return $this->hasOne('Retailcore\CreditNote\Models\customer_creditnote','return_bill_id','return_bill_id');
    }
    public function sales_bill()
    {
        return $this->hasOne('Retailcore\Sales\Models\sales_bill','sales_bill_id','sales_bill_id');
    }
    public function reference()
    {
        return $this->hasOne('Retailcore\Sales\Models\reference','reference_id','reference_id');
    }
}
