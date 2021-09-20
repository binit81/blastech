<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerCreditreceiptDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_creditreceipt_details', function (Blueprint $table) {
            $table->increments('customer_creditreceipt_detail_id');
            $table->integer('customer_creditreceipt_id')->unsigned();
            $table->foreign('customer_creditreceipt_id')->references('customer_creditreceipt_id')->on('customer_creditreceipts');            
            $table->integer('customer_creditaccount_id')->unsigned();
            $table->foreign('customer_creditaccount_id')->references('customer_creditaccount_id')->on('customer_creditaccounts');
            $table->integer('customer_id')->unsigned();
            $table->foreign('customer_id')->references('customer_id')->on('customers');
            $table->double('credit_amount',20,4);
            $table->double('payment_amount',20,4);
            $table->double('balance_amount',20,4);
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
        Schema::dropIfExists('customer_creditreceipt_details');
    }
}
