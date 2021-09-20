<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarcodeSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barcode_sheets', function (Blueprint $table) {
            $table->increments('barcode_sheet_id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('companies');
            $table->string('label_name',100);
            $table->string('label_tagline',255);
            $table->string('layout_width',255);
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

        DB::table('barcode_sheets')->insert(array(

            'company_id' => '1',
            'label_name' => 'A4 (21 Stickers)',
            'label_tagline' => '3x7',
            'is_active' => '1'
            
        ));

        DB::table('barcode_sheets')->insert(array(
            'company_id' => '1',
            'label_name' => 'A4 (24 Stickers)',
            'label_tagline' => '3x8',
            'is_active' => '1'
            
        ));

        DB::table('barcode_sheets')->insert(array(

            'company_id' => '1',
            'label_name' => 'A4 (30 Stickers)',
            'label_tagline' => '5x6',
            'is_active' => '1'
            
        ));

        DB::table('barcode_sheets')->insert(array(

            'company_id' => '1',
            'label_name' => 'A4 (40 Stickers)',
            'label_tagline' => '4x10',
            'is_active' => '1'
            
        ));

        DB::table('barcode_sheets')->insert(array(

            'company_id' => '1',
            'label_name' => 'A4 (40 Stickers)',
            'label_tagline' => '5x8',
            'is_active' => '1'
            
        ));

        DB::table('barcode_sheets')->insert(array(

            'company_id' => '1',
            'label_name' => 'A4 (48 Stickers)',
            'label_tagline' => '4x12',
            'is_active' => '1'
            
        ));

        DB::table('barcode_sheets')->insert(array(

            'company_id' => '1',
            'label_name' => 'A4 (65 Stickers)',
            'label_tagline' => '5x13',
            'is_active' => '1'
            
        ));

        DB::table('barcode_sheets')->insert(array(

            'company_id' => '1',
            'label_name' => 'A4 (84 Stickers)',
            'label_tagline' => '4x21',
            'is_active' => '1'
            
        ));

        DB::table('barcode_sheets')->insert(array(

            'company_id' => '1',
            'label_name' => 'Label Barcode',
            'label_tagline' => '1x1',
            'is_active' => '1'
            
        ));

        DB::table('barcode_sheets')->insert(array(

            'company_id' => '1',
            'label_name' => 'Label Barcode',
            'label_tagline' => '2x1',
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
        Schema::dropIfExists('barcode_sheets');
    }
}
