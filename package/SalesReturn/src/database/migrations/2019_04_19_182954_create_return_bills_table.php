<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_bills', function (Blueprint $table) {
            $table->Increments('return_bill_id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('companies');
            $table->integer('customer_id')->nullable()->unsigned();
            $table->foreign('customer_id')->references('customer_id')->on('customers');
            $table->integer('sales_bill_id')->unsigned();
            $table->foreign('sales_bill_id')->references('sales_bill_id')->on('sales_bills');
            $table->string('bill_date',55);
            $table->integer('state_id')->unsigned();
            $table->foreign('state_id')->references('state_id')->on('states');
            $table->integer('reference_id')->unsigned()->nullable();
            $table->foreign('reference_id')->references('reference_id')->on('references');
            $table->integer('total_qty');
            $table->double('sellingprice_before_discount',20,4);
            $table->double('totalbillamount_before_discount',20,4);
            $table->double('discount_percent',20,4)->nullable();
            $table->double('discount_amount',20,4)->nullable();
            $table->double('productwise_discounttotal',20,4);
            $table->double('sellingprice_after_discount',20,4)->nullable();
            $table->double('roundoff_discount_percent',20,4)->nullable();
            $table->double('roundoff_discount_amount',20,4)->nullable();
            $table->double('total_igst_amount',20,4)->nullable();
            $table->double('total_cgst_amount',20,4)->nullable();
            $table->double('total_sgst_amount',20,4)->nullable();
            $table->double('gross_total',20,4);
            $table->double('shipping_charges_without_gst',20,4)->nullable();
            $table->double('shipping_charges_igst_percent',20,4)->nullable();
            $table->double('shipping_charges_igst_amount',20,4)->nullable();
            $table->double('shipping_charges_cgst_percent',20,4)->nullable();
            $table->double('shipping_charges_cgst_amount',20,4)->nullable();
            $table->double('shipping_charges_sgst_percent',20,4)->nullable();
            $table->double('shipping_charges_sgst_amount',20,4)->nullable();
            $table->double('shipping_charges',20,4)->nullable();
            $table->double('total_bill_amount',20,4);
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
        Schema::dropIfExists('return_bills');
    }
}
