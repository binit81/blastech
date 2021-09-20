<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnbillProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returnbill_products', function (Blueprint $table) {
            $table->Increments('returnbill_product_id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('companies');
            $table->integer('sales_products_detail_id')->unsigned()->nullable();
            $table->foreign('sales_products_detail_id')->references('sales_products_detail_id')->on('sales_product_details');
            $table->integer('return_bill_id')->unsigned()->nullable();
            $table->foreign('return_bill_id')->references('return_bill_id')->on('return_bills');
            $table->integer('return_product_detail_id')->unsigned();
            $table->foreign('return_product_detail_id')->references('return_product_detail_id')->on('return_product_details');
            $table->string('return_date',55);
            $table->integer('price_master_id')->unsigned();
            $table->foreign('price_master_id')->references('price_master_id')->on('price_masters');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('product_id')->on('products');
            $table->integer('qty');
            $table->longText('inwardids')->nullable();
            $table->longText('inwardqtys')->nullable();
            $table->tinyInteger('restockstatus')->default('0')->comment = "1=restock,0=norestock";
            $table->tinyInteger('damagestatus')->default('0')->comment = "1=damage,0=nodamage";
            $table->tinyInteger('returnstatus')->default('0')->comment = "1=returndone,0=pending";
            $table->integer('restockqty');
            $table->longText('rinwardids')->nullable();
            $table->longText('rinwardqtys')->nullable();
            $table->integer('damageqty');
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
        Schema::dropIfExists('returnbill_products');
    }
}
