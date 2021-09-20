<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDebitProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debit_product_details', function (Blueprint $table) {
            $table->increments('debit_product_detail_id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('companies');
            $table->integer('debit_note_id')->unsigned();
            $table->foreign('debit_note_id')->references('debit_note_id')->on('debit_notes');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('product_id')->on('products');
            $table->integer('price_master_id')->unsigned();
            $table->foreign('price_master_id')->references('price_master_id')->on('price_masters');
            $table->double('base_price',20,4);
            $table->double('cost_rate',20,4);
            $table->double('cost_gst_percent',20,4);
            $table->double('cost_gst_amount',20,4);
            $table->integer('return_qty');
            $table->double('total_gst',20,4)->nullable();
            $table->double('total_cost_rate',20,4);
            $table->double('total_cost_price',20,4);
            $table->longText('remarks')->nullable();
            $table->double('cost_igst_percent',20,4);
            $table->double('cost_igst_amount',20,4);
            $table->double('cost_cgst_percent',20,4);
            $table->double('cost_cgst_amount',20,4);
            $table->double('cost_sgst_percent',20,4);
            $table->double('cost_sgst_amount',20,4);
            $table->double('total_igst_amount_with_qty',20,4);
            $table->double('total_cgst_amount_with_qty',20,4);
            $table->double('total_sgst_amount_with_qty',20,4);
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
        Schema::dropIfExists('debit_product_details');
    }
}
