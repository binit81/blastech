<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierGstsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_gsts', function (Blueprint $table) {
            $table->increments('supplier_gst_id');
            $table->integer('supplier_company_info_id')->unsigned();
            $table->foreign('supplier_company_info_id')->references('supplier_company_info_id')->on('supplier_company_infos');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('companies');
            $table->integer('supplier_treatment_id')->unsigned();
            $table->foreign('supplier_treatment_id')->references('supplier_treatment_id')->on('supplier_treatments');
            $table->string('supplier_gstin',10);
            $table->integer('state_id')->unsigned();
            $table->foreign('state_id')->references('state_id')->on('states');
            $table->text('supplier_address')->nullable();
            $table->string('supplier_area',10)->nullable();
            $table->string('supplier_gst_zipcode',10)->nullable();
            $table->string('supplier_gst_city',200)->nullable();
            $table->integer('country_id')->nullable()->unsigned();
            $table->foreign('country_id')->references('country_id')->on('countries');
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
        Schema::dropIfExists('supplier_gsts');
    }
}
