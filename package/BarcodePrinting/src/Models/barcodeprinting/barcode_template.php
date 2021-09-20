<?php

namespace Retailcore\BarcodePrinting\Models\barcodeprinting;

use Illuminate\Database\Eloquent\Model;

class barcode_template extends Model
{
   protected $primaryKey = 'barcode_template_id'; //Default: barcode_template_id
   protected $guarded=['barcode_template_id'];

   public function barcode_sheet()
   {
       return $this->hasOne('Retailcore\BarcodePrinting\Models\barcodeprinting\barcode_sheet','barcode_sheet_id','barcode_sheet_id');
   }
}
