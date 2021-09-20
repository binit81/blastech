<?php

namespace Retailcore\BarcodePrinting\Models\barcodeprinting;

use Illuminate\Database\Eloquent\Model;

class barcode_sheet extends Model
{
    protected $primaryKey = 'barcode_sheet_id'; //Default: barcode_sheet_id
   protected $guarded=['barcode_sheet_id'];
}
