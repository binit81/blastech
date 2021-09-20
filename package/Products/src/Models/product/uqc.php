<?php

namespace Retailcore\Products\Models\product;

use Illuminate\Database\Eloquent\Model;

class uqc extends Model
{
    protected $primaryKey = 'uqc_id'; //Default: id
    protected $guarded=[];
    
    public function itemproduct()
    {
        return $this->hasOne('Retailcore\Products\Models\product\product','uqc_id','uqc_id');
    }

}
