<?php

namespace Retailcore\Supplier\Models\supplier;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class supplier_company_info extends Model
{
    protected $primaryKey = 'supplier_company_info_id'; //Default: id
    protected $guarded = ['supplier_company_info_id'];

    use SoftDeletes;

    public function supplier_gst()
    {
      return  $this->hasMany('Retailcore\Supplier\Models\supplier\supplier_gst', 'supplier_company_info_id', 'supplier_company_info_id')->whereNull('deleted_at')->whereNotNull('supplier_gstin');
    }
    public function supplier_bank()
    {
        return $this->hasMany('Retailcore\Supplier\Models\supplier\supplier_bank','supplier_company_info_id','supplier_company_info_id')->whereNull('deleted_at');
    }
    public function supplier_contact_detail()
    {
        return $this->hasMany('Retailcore\Supplier\Models\supplier\supplier_contact_details','supplier_company_info_id','supplier_company_info_id')->whereNull('deleted_at');
    }
    public function state_name()
    {
        return $this->hasOne('App\state','state_id','state_id');
    }
    public function country_name()
    {
        return $this->hasOne('App\Country','country_id','country_id');
    }



}
