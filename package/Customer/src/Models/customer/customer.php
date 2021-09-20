<?php

namespace Retailcore\Customer\Models\customer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class customer extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'customer_id'; //Default: id
    protected $guarded=['customer_id'];

    public function customer_address_detail()
    {
        return $this->hasOne('Retailcore\Customer\Models\customer\customer_address_detail','customer_id','customer_id');
    }

	public function customer_creditaccount()
    {
        return $this->hasOne('Retailcore\CreditBalance\Models\customer_creditaccount','customer_id','customer_id');
    }

    public function customer_source()
    {
        return $this->hasOne('Retailcore\Customer_Source\Models\customer_source\customer_source','customer_source_id','customer_source_id');
    }


}
