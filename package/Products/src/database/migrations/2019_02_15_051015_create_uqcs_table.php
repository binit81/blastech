<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUqcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uqcs', function (Blueprint $table) {
            $table->increments('uqc_id');
            $table->string('uqc_name',200);
            $table->string('uqc_type',100);
            $table->string('uqc_shortname',100);
            $table->tinyInteger('sync_status')->nullable();
            $table->tinyInteger('is_active')->default('1')->comment = "1=active,0=inactive";
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('user_id')->on('users');
            $table->integer('modified_by')->unsigned()->nullable();
            $table->foreign('modified_by')->references('user_id')->on('users');
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->foreign('deleted_by')->references('user_id')->on('users');
            $table->softDeletes('deleted_at');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->nullable();

        });

        DB::table('uqcs')->insert(array(

          'uqc_name' => 'BAGS',
          'uqc_type' => 'Measure',
          'uqc_shortname' => 'BAG',
          'is_active' => '1',
          'created_by' => '1'
        
        ));

        DB::table('uqcs')->insert(array(

          'uqc_name' => 'BALE',
          'uqc_type' => 'Measure',
          'uqc_shortname' => 'BAL',
          'is_active' => '1',
          'created_by' => '1'
        
        ));

        DB::table('uqcs')->insert(array(

          'uqc_name' => 'BUNDLES',
          'uqc_type' => 'Measure',
          'uqc_shortname' => 'BDL',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'BUCKLES',
          'uqc_type' => 'Measure',
          'uqc_shortname' => 'BKL',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'BILLIONS OF UNITS',
          'uqc_type' => 'Measure',
          'uqc_shortname' => 'BOU',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'BOX',
          'uqc_type' => 'Measure',
          'uqc_shortname' => 'BOX',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'BOTTLES',
          'uqc_type' => 'Measure',
          'uqc_shortname' => 'BTL',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'BUNCHES',
          'uqc_type' => 'Measure',
          'uqc_shortname' => 'BUN',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'CANS',
          'uqc_type' => 'Measure',
          'uqc_shortname' => 'CAN',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'CUBIC METER',
          'uqc_type' => 'Measure',
          'uqc_shortname' => 'CBM',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'CUBIC METER',
          'uqc_type' => 'Volume',
          'uqc_shortname' => 'CBM',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'CUBIC CENTIMETER',
          'uqc_type' => 'Volume',
          'uqc_shortname' => 'CCM',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'CENTIMETER',
          'uqc_type' => 'Length',
          'uqc_shortname' => 'CMS',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'CARTONS',
          'uqc_type' => 'Measure',
          'uqc_shortname' => 'CTN',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'DOZEN',
          'uqc_type' => 'Measure',
          'uqc_shortname' => 'DOZ',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'DRUM',
          'uqc_type' => 'Measure',
          'uqc_shortname' => 'DRM',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'GREAT GROSS',
          'uqc_type' => 'Measure',
          'uqc_shortname' => 'GGR',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'GRAMS',
          'uqc_type' => 'Weight',
          'uqc_shortname' => 'GMS',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'GROSS',
          'uqc_type' => 'Measure',
          'uqc_shortname' => 'GRS',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'GROSS YARDS',
          'uqc_type' => 'Length',
          'uqc_shortname' => 'GYD',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'KILOGRAMS',
          'uqc_type' => 'Weight',
          'uqc_shortname' => 'KGS',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'KILOLITER',
          'uqc_type' => 'Volume',
          'uqc_shortname' => 'KLR',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'KILOMETRE',
          'uqc_type' => 'Length',
          'uqc_shortname' => 'KME',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'MILLILITRE',
          'uqc_type' => 'Volume',
          'uqc_shortname' => 'MLT',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'METERS',
          'uqc_type' => 'Length',
          'uqc_shortname' => 'MTR',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'NUMBERS',
          'uqc_type' => 'Measure',
          'uqc_shortname' => 'NOS',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'PACKS',
          'uqc_type' => 'Measure',
          'uqc_shortname' => 'PAC',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'PIECES',
          'uqc_type' => 'Measure',
          'uqc_shortname' => 'PCS',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'PAIRS',
          'uqc_type' => 'Measure',
          'uqc_shortname' => 'PRS',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'QUINTAL',
          'uqc_type' => 'Weight',
          'uqc_shortname' => 'QTL',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
        
        DB::table('uqcs')->insert(array(

          'uqc_name' => 'ROLLS',
          'uqc_type' => 'Measure',
          'uqc_shortname' => 'ROL',
          'is_active' => '1',
          'created_by' => '1'
        
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uqcs');
    }
}
