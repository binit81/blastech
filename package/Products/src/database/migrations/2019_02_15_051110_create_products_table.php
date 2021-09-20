<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('product_id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('companies');
            $table->tinyInteger('product_type')->default('1')->comment = "1=fmcg,2=garment";
            $table->integer('item_type')->nullable()->comment = "1=product,2=service";
            $table->string('product_name',200);
            $table->integer('brand_id')->unsigned()->nullable();
            $table->foreign('brand_id')->references('brand_id')->on('brands');
            $table->integer('category_id')->unsigned()->nullable();
            $table->foreign('category_id')->references('category_id')->on('categories');
            $table->integer('subcategory_id')->unsigned()->nullable();
            $table->foreign('subcategory_id')->references('subcategory_id')->on('subcategories');
            $table->integer('colour_id')->unsigned()->nullable();
            $table->foreign('colour_id')->references('colour_id')->on('colours');
            $table->integer('size_id')->unsigned()->nullable();
            $table->foreign('size_id')->references('size_id')->on('sizes');
            $table->integer('uqc_id')->unsigned()->nullable();
            $table->foreign('uqc_id')->references('uqc_id')->on('uqcs');
            $table->double('cost_rate',15,4);
            $table->double('cost_price',15,4);
            $table->double('selling_price',15,4);
            $table->double('offer_price',15,4)->comment = "Original MRP";
            $table->double('product_mrp',15,4);
            $table->double('wholesale_price',15,4)->nullable();
            $table->double('cost_gst_percent',15,4)->nullable();
            $table->double('cost_gst_amount',15,4)->nullable();
            $table->double('extra_charge',15,4)->nullable()->default(0);
            $table->double('profit_percent',15,4)->nullable();
            $table->double('profit_amount',15,4)->nullable();
            $table->double('sell_gst_percent',15,4)->nullable();
            $table->double('sell_gst_amount',15,4)->nullable();
            $table->string('product_system_barcode',50)->unique();
            $table->string('supplier_barcode',50)->nullable()->unique();
            $table->tinyInteger('is_ean')->nullable();
            $table->integer('alert_product_qty')->nullable();
            $table->string('product_ean_barcode',13)->nullable();
            $table->double('minimum_qty',8,4)->nullable();
            $table->longText('sku_code')->nullable();
            $table->longText('product_code')->nullable();
            $table->longText('product_description')->nullable();
            $table->integer('hsn_sac_code')->nullable();
            $table->integer('days_before_product_expiry')->nullable();
            $table->double('group_disc',15,4)->nullable();
            $table->double('group_module',15,4)->nullable();
            $table->longText('note')->nullable();
            $table->tinyInteger('is_active')->default('1')->comment = "1=active,0=inactive";
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
        Schema::dropIfExists('products');
    }
}
