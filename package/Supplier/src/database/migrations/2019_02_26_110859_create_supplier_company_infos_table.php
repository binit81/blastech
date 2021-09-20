<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierCompanyInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_company_infos', function (Blueprint $table) {
            $table->increments('supplier_company_info_id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('companies');
            $table->string('supplier_company_name',100);
            $table->string('supplier_first_name',100);
            $table->string('supplier_last_name',100)->nullable();
            $table->string('supplier_company_dial_code',100)->nullable();
            $table->string('supplier_company_mobile_no',255)->nullable();
            $table->string('supplier_pan_no',10)->nullable();
            $table->text('supplier_company_address')->nullable();
            $table->string('supplier_company_area',10)->nullable();
            $table->string('supplier_company_zipcode',10)->nullable();
            $table->string('supplier_company_city',200)->nullable();
            $table->integer('state_id')->nullable()->unsigned();
            $table->foreign('state_id')->references('state_id')->on('states');
            $table->integer('country_id')->unsigned();
            $table->foreign('country_id')->references('country_id')->on('countries');
            $table->integer('supplier_payment_due_days')->nullable();
            $table->string('supplier_payment_due_date',15)->nullable();
            $table->longText('note')->nullable();
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
        Schema::dropIfExists('supplier_company_infos');
    }
}
