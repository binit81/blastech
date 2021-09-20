<?php

namespace Retailcore\Supplier\Models\supplier;

use Illuminate\Database\Eloquent\Model;

class supplier_gst extends Model
{
    protected $primaryKey = 'supplier_gst_id'; //Default: id
    protected $guarded=[];

    protected function supplier_treatment()
    {
        return $this->hasOne('Retailcore\Supplier\Models\supplier\supplier_treatment','supplier_treatment_id','supplier_treatment_id')->whereNull('deleted_at');
    }

    public function supplier_state()
    {
        return $this->hasOne('App\state','state_id','state_id');
    }
    public function supplier_country()
    {
        return $this->hasOne('App\country','country_id','country_id');
    }
    public function supplier_gst()
    {
        return $this->hasOne('Retailcore\Supplier\Models\supplier\supplier_gst', 'supplier_company_info_id', 'supplier_company_info_id')->whereNull('deleted_at');
    }
    public function supplier_company_info()
    {
        return $this->hasOne('Retailcore\Supplier\Models\supplier\supplier_company_info', 'supplier_company_info_id', 'supplier_company_info_id')->whereNull('deleted_at');
    }
}

