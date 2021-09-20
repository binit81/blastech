<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_treatments', function (Blueprint $table) {
            $table->increments('supplier_treatment_id');
            $table->string('supplier_treatment_name',200);
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
		
		DB::table('supplier_treatments')->insert(array(
		
            'supplier_treatment_name' => 'Registered Business',
            'is_active' => '1'
			
        ));
		
		DB::table('supplier_treatments')->insert(array(
		
            'supplier_treatment_name' => 'Unregistered Business',
            'is_active' => '1'
			
        ));
		
		DB::table('supplier_treatments')->insert(array(
		
            'supplier_treatment_name' => 'Consumer',
            'is_active' => '1'
			
        ));
		
		DB::table('supplier_treatments')->insert(array(
		
            'supplier_treatment_name' => 'Overseas',
            'is_active' => '1'
			
        ));
		
		DB::table('supplier_treatments')->insert(array(
		
            'supplier_treatment_name' => 'Other',
            'is_active' => '1'
			
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_treatments');
    }
}
