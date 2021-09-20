<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarcodeTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */


    public function up()
    {

        Schema::create('barcode_templates', function (Blueprint $table) {
            $table->increments('barcode_template_id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('companies');
            $table->integer('barcode_sheet_id')->unsigned();
            $table->foreign('barcode_sheet_id')->references('barcode_sheet_id')->on('barcode_sheets');
            $table->string('barcode_type',10)->comment = "in pixels";
            $table->string('template_name',200);
            $table->longText('template_data');
            $table->string('template_label_width',10);
            $table->string('template_label_height',10);
            $table->string('template_label_size_type',10);
            $table->string('template_label_font_size',10)->comment = "in pixels";
            $table->string('template_label_margin_top',10)->comment = "in pixels";
            $table->string('template_label_margin_right',10)->comment = "in pixels";
            $table->string('template_label_margin_bottom',10)->comment = "in pixels";
            $table->string('template_label_margin_left',10)->comment = "in pixels";
            $table->tinyInteger('is_active')->default('1')->comment = "1=active,0=inactive";
            $table->integer('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('user_id')->on('users');
            $table->integer('modified_by')->unsigned()->nullable();
            $table->foreign('modified_by')->references('user_id')->on('users');
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->foreign('deleted_by')->references('user_id')->on('users');
            $table->softDeletes('deleted_at');
            $table->timestamps();
        });

        Schema::disableForeignKeyConstraints();

        Schema::table('users', function (Blueprint $table) {
            $table->integer('barcode_template_id')->unsigned()->after('company_id')->nullable();
            $table->foreign('barcode_template_id')->references('barcode_template_id')->on('barcode_templates');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('barcode_templates');



    }


}
