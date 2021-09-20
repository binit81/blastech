<?php

namespace Retailcore\Supplier\Models\supplier;

use Illuminate\Database\Eloquent\Model;

class supplier_contact_details extends Model
{
    protected $primaryKey = 'supplier_contact_details_id'; //Default: id
    protected $guarded=['supplier_contact_details_id'];
}
