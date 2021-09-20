<?php

namespace Retailcore\Sales\Models;

use Illuminate\Database\Eloquent\Model;

class payment_method extends Model
{
    protected $primaryKey = 'payment_method_id'; //Default: id
    protected $guarded=[];
}
