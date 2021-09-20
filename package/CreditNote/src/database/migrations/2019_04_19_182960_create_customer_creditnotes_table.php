<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerCreditnotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_creditnotes', function (Blueprint $table) {
            $table->Increments('customer_creditnote_id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('companies');
            $table->integer('customer_id')->unsigned()->nullable();
            $table->foreign('customer_id')->references('customer_id')->on('customers');
            $table->integer('sales_bill_id')->unsigned();
            $table->foreign('sales_bill_id')->references('sales_bill_id')->on('sales_bills');
            $table->integer('return_bill_id')->unsigned();
            $table->foreign('return_bill_id')->references('return_bill_id')->on('return_bills');
            $table->integer('creditno_series')->nullable();
            $table->string('creditnote_no',55);
            $table->string('creditnote_date',55);
            $table->double('creditnote_amount',20,4);
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
        Schema::dropIfExists('customer_creditnotes');
    }
}
