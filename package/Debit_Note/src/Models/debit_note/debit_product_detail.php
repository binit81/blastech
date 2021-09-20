<?php

namespace Retailcore\Debit_Note\Models\debit_note;

use Illuminate\Database\Eloquent\Model;

class debit_product_detail extends Model
{
    protected $primaryKey = 'debit_product_detail_id';
    protected $guarded =['debit_product_detail_id'];

    public function product()
    {
        return $this->hasOne('Retailcore\Products\Models\product\product','product_id','product_id');
    }

    public function debit_note()
    {
        return $this->hasOne('Retailcore\Debit_Note\Models\debit_note\debit_note','debit_note_id','debit_note_id');
    }




}
