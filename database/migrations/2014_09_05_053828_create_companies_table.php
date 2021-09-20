<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table)
        {
            $table->increments('company_id');
            $table->string('company_name',255);
            $table->string('company_contact_person',200)->nullable();
            $table->text('company_address')->nullable();
            $table->string('company_contact_no',20)->nullable();
            $table->string('company_mobile_no',20)->nullable();
            $table->string('company_email',30);
            $table->string('company_fax_no',20)->nullable();
            $table->tinyInteger('is_active')->default('1')->comment = "1=active,0=inactive";
            $table->integer('created_superadmin_id')->nullable();
            $table->integer('modified_superadmin_id')->nullable();
            $table->integer('deleted_superadmin_id')->nullable();
            $table->softDeletes('deleted_at');
            $table->timestamps();
        });

        DB::table('companies')->insert(array(
        
          'company_name' => 'Retailcore',
          'company_contact_person' => 'Jignesh',
          'company_address' => 'Surat',
          'company_contact_no' => '9876543210',
          'company_mobile_no' => '9876543210',
          'company_email' => 'test@retailcore.in',
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
        Schema::dropIfExists('companies');
    }
}
