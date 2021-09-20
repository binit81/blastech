<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierDebitreceiptDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_debitreceipt_details', function (Blueprint $table) {
            $table->increments('supplier_debitreceipt_detail_id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('companies');
            $table->integer('supplier_debitreceipt_id')->unsigned();
            $table->foreign('supplier_debitreceipt_id')->references('supplier_debitreceipt_id')->on('supplier_debitreceipts');
            $table->integer('debit_note_id')->unsigned()->nullable();
                $table->foreign('debit_note_id')->references('debit_note_id')->on('debit_notes');
            $table->integer('payment_method_id')->unsigned();
            $table->foreign('payment_method_id')->references('payment_method_id')->on('payment_methods');
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
        Schema::dropIfExists('supplier_debitreceipt_details');
    }
}
