<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInwardStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inward_stocks', function (Blueprint $table)
        {
            $table->increments('inward_stock_id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('companies');
            $table->integer('supplier_gst_id')->unsigned();
            $table->foreign('supplier_gst_id')->references('supplier_gst_id')->on('supplier_gsts');
            $table->integer('state_id')->unsigned();
            $table->foreign('state_id')->references('state_id')->on('states');
            $table->string('invoice_no',100);
            $table->string('invoice_date',20);
            $table->string('inward_date',20);
            $table->integer('total_qty');
            $table->double('total_gross',20,4);
            $table->double('total_grand_amount',20,4);
            $table->double('total_cost_igst_amount',20,4)->nullable();
            $table->double('total_cost_cgst_amount',20,4)->nullable();
            $table->double('total_cost_sgst_amount',20,4)->nullable();
            $table->double('cost_rate',20,4)->nullable();
            $table->double('discount_amount',20,4)->nullable();
            $table->string('po_no',100)->nullable();
            $table->double('outstanding_payment_date',20,4)->nullable();
            $table->tinyInteger('is_payment_clear')->default('0')->comment = "1=yes payment is clear,0=outstanding payment";
            $table->integer('due_days')->nullable();
            $table->string('due_date',15)->nullable();
            $table->longText('note')->nullable();
            $table->string('challan_no',100)->nullable();
            $table->double('expense',20,4)->nullable();
            $table->double('expense_overall',20,4)->nullable();
            $table->tinyInteger('inward_type')->nullable()->comment = "1=fmcg,2=garment,3=combo";
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
        Schema::dropIfExists('inward_stocks');
    }
}
