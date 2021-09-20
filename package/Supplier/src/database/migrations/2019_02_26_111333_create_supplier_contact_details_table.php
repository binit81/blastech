<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierContactDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_contact_details', function (Blueprint $table) {
            $table->increments('supplier_contact_details_id');
            $table->integer('supplier_company_info_id')->unsigned();
            $table->foreign('supplier_company_info_id')->references('supplier_company_info_id')->on('supplier_company_infos');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('companies');
            $table->integer('salutation_id')->unsigned();
            $table->foreign('salutation_id')->references('salutation_id')->on('salutations');
            $table->string('supplier_contact_firstname',200);
            $table->string('supplier_contact_lastname',200)->nullable();
            $table->string('supplier_contact_designation',200)->nullable();
            $table->string('supplier_contact_email_id',100);
            $table->string('supplier_date_of_birth',20)->nullable();
            $table->string('supplier_contact_mobile_no',15);
            $table->string('supplier_contact_dial_code',10)->nullable();
            $table->string('supplier_whatsapp_no',15)->nullable();
            $table->string('supplier_whatsapp_dial_code',10)->nullable();
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
        Schema::dropIfExists('supplier_contact_details');
    }
}
