<?php

namespace Retailcore\DamageProducts\Models\damageproducts;

use Illuminate\Database\Eloquent\Model;

class damage_type extends Model
{
    protected $primaryKey = 'damage_type_id'; //Default: id
    protected $guarded=['damage_type_id'];

}
