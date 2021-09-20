<?php

namespace Retailcore\Products\Models\product;

use Illuminate\Database\Eloquent\Model;

class colour extends Model
{
    protected $primaryKey = 'colour_id'; //Default: id
    protected $guarded=[];

    public function itemproduct()
    {
        return $this->hasOne('Retailcore\Products\Models\product\product','colour_id','colour_id');
    }
}
