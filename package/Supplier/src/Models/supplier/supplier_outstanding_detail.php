<?php

namespace Retailcore\Supplier\Models\supplier;

use Illuminate\Database\Eloquent\Model;

class supplier_outstanding_detail extends Model
{
    protected $primaryKey = 'supplier_outstanding_detail_id'; //Default: id
    protected $guarded=['supplier_outstanding_detail_id'];
}
