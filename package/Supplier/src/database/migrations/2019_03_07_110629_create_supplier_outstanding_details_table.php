<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierOutstandingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_outstanding_details', function (Blueprint $table) {
            $table->increments('supplier_outstanding_detail_id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('companies');
            $table->integer('supplier_payment_detail_id')->unsigned();
            $table->foreign('supplier_payment_detail_id')->references('supplier_payment_detail_id')->on('supplier_payment_details');
            $table->integer('inward_stock_id')->unsigned();
            $table->foreign('inward_stock_id')->references('inward_stock_id')->on('inward_stocks');
            $table->integer('supplier_debitreceipt_id')->unsigned();
            $table->foreign('supplier_debitreceipt_id')->references('supplier_debitreceipt_id')->on('supplier_debitreceipts');
            $table->double('amount',20,4);
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
        Schema::dropIfExists('supplier_outstanding_details');
    }
}
