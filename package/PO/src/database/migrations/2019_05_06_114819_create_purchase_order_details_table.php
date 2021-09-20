<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->increments('purchase_order_detail_id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('companies');
            $table->integer('purchase_order_id')->unsigned();
            $table->foreign('purchase_order_id')->references('purchase_order_id')->on('purchase_orders');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('product_id')->on('products');
            $table->double('cost_rate',20,4);
            $table->double('cost_gst_percent',20,4);
            $table->double('cost_gst_amount',20,4);
            $table->integer('qty');
            $table->integer('free_qty')->nullable()->default(0);
            $table->double('total_gst',20,4)->nullable();
            $table->double('total_cost_with_gst',20,4);
            $table->double('total_cost_without_gst',20,4);
            $table->integer('received_qty')->nullable();
            $table->integer('pending_qty')->nullable();
            $table->tinyInteger('is_active')->default('1')->comment = "1=active,0=inactive";            $table->integer('created_by')->unsigned()->nullable();
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
        Schema::dropIfExists('purchase_order_details');
    }
}
