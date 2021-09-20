<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierDebitPaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_debit_payment_details', function (Blueprint $table)
        {
            $table->bigIncrements('supplier_debit_payment_detail_id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('companies');
            $table->integer('debit_note_id')->unsigned();
            $table->foreign('debit_note_id')->references('debit_note_id')->on('debit_notes');
            $table->integer('inward_stock_id')->unsigned();
            $table->foreign('inward_stock_id')->references('inward_stock_id')->on('inward_stocks');
            $table->integer('supplier_gst_id')->unsigned();
            $table->foreign('supplier_gst_id')->references('supplier_gst_id')->on('supplier_gsts');
            $table->double('debit_note_amount',20,4);
            $table->double('debit_note_used_amount',20,4);
            $table->double('debit_note_balance_amount',20,4);
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


    public function down()
    {
        Schema::dropIfExists('supplier_debit_payment_details');
    }
}
