<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->increments('purchase_order_id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('companies');
            $table->integer('supplier_gst_id')->unsigned();
            $table->foreign('supplier_gst_id')->references('supplier_gst_id')->on('supplier_gsts');
            $table->string('po_no',100);
            $table->string('po_date',30)->nullable();
            $table->string('delivery_to',100)->nullable();
            $table->longText('address')->nullable();
            $table->longText('terms_condition')->nullable();
            $table->string('delivery_date',30)->nullable();
            $table->integer('total_qty');
            $table->double('total_cost_rate',20,4);
            $table->double('total_gst',20,4);
            $table->double('total_cost_price',20,4);
            $table->longText('note')->nullable();
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
        Schema::dropIfExists('purchase_orders');
    }
}
