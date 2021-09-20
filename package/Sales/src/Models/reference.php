<?php

namespace Retailcore\Sales\Models;

use Illuminate\Database\Eloquent\Model;

class reference extends Model
{
    protected $primaryKey = 'reference_id'; //Default: id
    protected $guarded=['reference_id'];
}
