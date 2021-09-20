<?php

namespace Retailcore\Products\Models\product;

use Illuminate\Database\Eloquent\Model;

class size extends Model
{
    protected $primaryKey = 'size_id'; //Default: id
    protected $guarded=[];

    public function itemproduct()
    {
        return $this->hasOne('Retailcore\Products\Models\product\product','size_id','size_id');
    }
}
