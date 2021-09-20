<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnBillPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_bill_payments', function (Blueprint $table) {
            $table->Increments('return_bill_payment_id');
            $table->integer('return_bill_id')->unsigned();
            $table->foreign('return_bill_id')->references('return_bill_id')->on('return_bills');
            $table->double('total_bill_amount',20,4);
            $table->integer('payment_method_id');
            $table->integer('customer_creditnote_id')->unsigned()->nullable();
            $table->foreign('customer_creditnote_id')->references('customer_creditnote_id')->on('customer_creditnotes');
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
        Schema::dropIfExists('return_bill_payments');
    }
}
