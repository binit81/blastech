<?php

namespace Retailcore\Customer\Models\customer;

use Illuminate\Database\Eloquent\Model;

class customer_address_detail extends Model
{
   protected $primaryKey = 'customer_address_detail_id'; //Default: id
    protected $guarded=[];

    public function state_name()
    {
        return $this->hasOne('App\state','state_id','state_id');
    }

    public function country_name()
    {
        return $this->hasOne('App\Country','country_id','country_id');
    }

}
