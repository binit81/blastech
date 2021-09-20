<?php

namespace Retailcore\Supplier\Models\supplier;

use Illuminate\Database\Eloquent\Model;

class supplier_debitreceipts extends Model
{
    protected $primaryKey = 'supplier_debitreceipt_id'; //Default: id
    protected $guarded = ['supplier_debitreceipt_id'];
    public $timestamps = true;

    public function supplier_gstdetail()
    {
        return $this->hasone('Retailcore\Supplier\Models\supplier\supplier_gst','supplier_gst_id','supplier_gst_id');
    }

    public function supplier_debitreceipt_details()
    {
        return $this->hasMany('Retailcore\Supplier\Models\supplier\supplier_debitreceipt_details','supplier_debitreceipt_id','supplier_debitreceipt_id');
    }
}
