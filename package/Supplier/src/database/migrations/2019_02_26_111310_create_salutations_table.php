<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalutationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salutations', function (Blueprint $table) {
            $table->increments('salutation_id');
            $table->string('salutation_prefix','10');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->nullable();
        });

        DB::table('salutations')->insert(array(
            'salutation_prefix' => 'Mr.',
        ));
        DB::table('salutations')->insert(array(
            'salutation_prefix' => 'Miss',
        ));
        DB::table('salutations')->insert(array(
            'salutation_prefix' => 'Ms.',
        ));
        DB::table('salutations')->insert(array(
            'salutation_prefix' => 'Dear.',
        ));

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salutations');
    }
}
