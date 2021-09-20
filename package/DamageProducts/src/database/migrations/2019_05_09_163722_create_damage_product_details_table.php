<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDamageProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('damage_product_details', function (Blueprint $table) {
            $table->increments('damage_product_detail_id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('companies');
            $table->integer('damage_product_id')->unsigned();
            $table->foreign('damage_product_id')->references('damage_product_id')->on('damage_products');
            //$table->integer('damage_type_id')->unsigned();
            //$table->foreign('damage_type_id')->references('damage_type_id')->on('damage_types');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('product_id')->on('products');
            //$table->string('product_system_barcode',100);
            //$table->string('product_supplier_barcode',100)->nullable();
            //$table->string('batch_no',255)->nullable();
            //$table->string('invoice_no',255)->nullable();
            //$table->integer('inward_stock_id')->unsigned();
            //->foreign('inward_stock_id')->references('inward_stock_id')->on('inward_stocks');
            $table->integer('inward_product_detail_id')->unsigned();
            $table->foreign('inward_product_detail_id')->references('inward_product_detail_id')->on('inward_product_details');
            $table->double('product_cost_rate',20,4);
            $table->double('product_cost_cgst_percent',20,4);
            $table->double('product_cost_cgst_amount',20,4);
            $table->double('product_cost_sgst_percent',20,4);
            $table->double('product_cost_sgst_amount',20,4);
            $table->double('product_cost_igst_percent',20,4);
            $table->double('product_cost_igst_amount',20,4);
            $table->double('product_total_cost_rate',20,4);
            $table->double('product_total_gst_amount',20,4);
            $table->double('product_cost_cgst_amount_with_qty',20,4);
            $table->double('product_cost_sgst_amount_with_qty',20,4);
            $table->double('product_cost_igst_amount_with_qty',20,4);
            //$table->double('product_cost_rate_with_qty',20,4);
            $table->double('product_total_cost_price',20,4);
            $table->double('product_mrp',20,4);
            $table->string('product_damage_qty',10);
            $table->longText('product_notes')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('user_id')->on('users');
            $table->integer('modified_by')->unsigned()->nullable();
            $table->foreign('modified_by')->references('user_id')->on('users');
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->foreign('deleted_by')->references('user_id')->on('users');
            $table->softDeletes('deleted_at');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('damage_product_details');
    }
}
