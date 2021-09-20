<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyProfilesTable extends Migration
{

    public function up()
    {
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->increments('company_profile_id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('companies');
            $table->string('full_name',255);
            $table->string('personal_mobile_dial_code',10);
            $table->string('personal_mobile_no',15);
            $table->string('personal_email',50);
            $table->string('company_name',200);
            $table->string('company_mobile_dial_code',10)->nullable();
            $table->string('company_mobile',15)->nullable();
            $table->string('company_email',50)->nullable();
            $table->string('website',255)->nullable();
            $table->string('gstin',15)->nullable();
            $table->integer('state_id')->unsigned()->nullable();
            $table->foreign('state_id')->references('state_id')->on('states');
            $table->string('whatsapp_mobile_dial_code',10)->nullable();
            $table->string('whatsapp_mobile_number',15)->nullable();
            $table->string('facebook',255)->nullable();
            $table->string('instagram',255)->nullable();
            $table->string('pinterest',255)->nullable();
            $table->longText('company_address');
            $table->string('company_area',255);
            $table->string('company_city',100);
            $table->integer('company_pincode');
            $table->integer('country_id')->unsigned();
            $table->foreign('country_id')->references('country_id')->on('countries');
            $table->string('authorized_signatory_for',200)->nullable();
            $table->longText('terms_and_condition')->nullable();
            $table->string('additional_message',200)->nullable();
            $table->tinyInteger('tax_type')->default('2')->comment = "1=International Vat,2=Indian GST";
            $table->string('tax_title',55)->nullable();
            $table->string('currency_title',55)->nullable();
            $table->Integer('decimal_points')->default('0');
            $table->tinyInteger('billtype')->default('1')->comment = "1=withoutgstrange,2=withgstrange,3=batchnowise";
            $table->tinyInteger('series_type')->default('1')->comment = "1=regular,2=monthwise";
            $table->tinyInteger('billprint_type')->default('1')->comment = "1=A4/A5 Print,2=Thermal Print";
            $table->integer('return_days')->default('0');
            $table->string('bill_number_prefix',30)->nullable();
            $table->string('credit_receipt_prefix',30)->nullable();
            $table->string('debit_receipt_prefix',30)->nullable();
            $table->string('po_number_prefix',30)->nullable();
            $table->string('account_holder_name',100)->nullable();
            $table->string('account_number',20)->nullable();
            $table->string('bank_name',100)->nullable();
            $table->string('ifsc_code',10)->nullable();
            $table->string('branch',50)->nullable();
            $table->tinyInteger('navigation_type')->default('1')->comment="1=horizontal,2=vertical";
            $table->tinyInteger('inward_calculation')->default('1')->comment="1=without roundoff,2=with roundoff";
            $table->tinyInteger('inward_type')->default('1')->comment="1=fmcg,2=garment";
            $table->longText('po_terms_and_condition')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('user_id')->on('users');
            $table->integer('modified_by')->unsigned()->nullable();
            $table->foreign('modified_by')->references('user_id')->on('users');
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
        Schema::dropIfExists('company_profiles');
    }
}
