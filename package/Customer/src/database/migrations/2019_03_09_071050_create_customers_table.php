<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('customer_id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('companies');
            $table->string('customer_name',200);
            $table->tinyInteger('customer_gender')->nullable()->comment = "1=male,2=female,3=transgender";
            $table->string('customer_mobile_dial_code',10)->nullable();
            $table->string('customer_mobile',55)->unique()->nullable();
            $table->string('customer_email',55)->unique()->nullable();
            $table->string('customer_date_of_birth',50)->nullable();
            $table->integer('outstanding_duedays')->nullable();
            $table->integer('customer_source_id')->nullable()->unsigned();
            $table->foreign('customer_source_id')->references('customer_source_id')->on('customer_sources')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('customers');
    }
}
