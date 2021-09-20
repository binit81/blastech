<?php

namespace Retailcore\Products\Models\product;

use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    protected $primaryKey = 'category_id'; //Default: id
    protected $guarded=[];
}
