<?php

namespace Retailcore\Debit_Note\Models\debit_note;

use Illuminate\Database\Eloquent\Model;

class debit_note extends Model
{
   protected  $primaryKey = 'debit_note_id';
   protected $guarded =['debit_note_id'];

   public function inward_stock()
   {
       return $this->hasone('Retailcore\Inward_Stock\Models\inward\inward_stock','inward_stock_id','inward_stock_id')->whereNull('deleted_at');
   }

   public function supplier_gstdetail()
   {
        return $this->hasone('Retailcore\Supplier\Models\supplier\supplier_gst','supplier_gst_id','supplier_gst_id');
   }

   public function debit_product_details()
   {
        return $this->hasMany('Retailcore\Debit_Note\Models\debit_note\debit_product_detail','debit_note_id','debit_note_id');
   }

   public function company()
   {
        return $this->hasOne('Retailcore\Company_Profile\Models\company_profile\company_profile','company_id','company_id');
   }
}
