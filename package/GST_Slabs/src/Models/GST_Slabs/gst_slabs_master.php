<?php

namespace Retailcore\GST_Slabs\Models\GST_Slabs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class gst_slabs_master extends Model
{

    use SoftDeletes;

    protected $primaryKey = 'gst_slabs_master_id'; //Default: id
    protected $guarded = ['gst_slabs_master_id'];



}
