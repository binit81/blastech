<?php

namespace Retailcore\Sales\Models;

use Illuminate\Database\Eloquent\Model;


class sales_product_detail extends Model
{
    // use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
    // protected $casts = [
    //     'inwardids' => 'json',
    // ];
    protected $primaryKey = 'sales_products_detail_id'; //Default: id
    protected $guarded=['sales_products_detail_id'];

    // public function inward_product_detail()
    // {
    //     return $this->belongsTo('Retailcore\Inward_Stock\Models\inward\inward_product_detail', 'inwardids->inward_product_detail_id');
    // }
    public function product()
    {
        return $this->hasOne('Retailcore\Products\Models\product\product','product_id','product_id');
    }
    public function sales_bill()
    {
        return $this->hasOne('Retailcore\Sales\Models\sales_bill','sales_bill_id','sales_bill_id');
    }
    public function price_master()
    {
        return $this->hasMany('Retailcore\Products\Models\product\price_master','product_id','product_id')->sum('product_qty')->groupBy('product_id');
    }
    public function batchprice_master()
    {
        return $this->hasOne('Retailcore\Products\Models\product\price_master','price_master_id','price_master_id');
    }
    public function return_product_detail()
    {
        return $this->hasMany('Retailcore\SalesReturn\Models\return_product_detail','sales_products_detail_id','sales_products_detail_id')->where('deleted_at','=',NULL);
    }
    // public function inward_product_detail()
    // {
    //     return $this->hasMany('Retailcore\Inward_Stock\Models\inward\inward_product_detail','inward_product_detail_id','inwardids')->where('deleted_at','=',NULL);

    // }


    //for batch no report count sold qty
    public function price_master_batch_wise()
    {
        return $this->hasOne('Retailcore\Products\Models\product\price_master','price_master_id','price_master_id');
    }
}
