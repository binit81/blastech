<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->increments('payment_method_id');
            $table->string('payment_method_name',200);
            $table->string('html_name',100);
            $table->string('html_id',100);
            $table->integer('payment_order');
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
		
		DB::table('payment_methods')->insert(array(
		
            'payment_method_name' => 'Cash',
            'html_name' => 'cash',
            'html_id' => 'cash',
			'payment_order' => '8',
			'is_active' => '1',
			'created_by' => '1'
			
        ));
		
		DB::table('payment_methods')->insert(array(
		
            'payment_method_name' => 'Card',
            'html_name' => 'card',
            'html_id' => 'card',
			'payment_order' => '1',
			'is_active' => '1',
			'created_by' => '1'
			
        ));
		
		DB::table('payment_methods')->insert(array(
		
            'payment_method_name' => 'Cheque',
            'html_name' => 'cheque',
            'html_id' => 'cheque',
			'payment_order' => '2',
			'is_active' => '1',
			'created_by' => '1'
			
        ));
		
		DB::table('payment_methods')->insert(array(
		
            'payment_method_name' => 'Redeem Point',
            'html_name' => 'redeem_point',
            'html_id' => 'redeem_point',
			'payment_order' => '3',
			'is_active' => '0',
			'created_by' => '1'
			
        ));
		
		DB::table('payment_methods')->insert(array(
		
            'payment_method_name' => 'Wallet',
            'html_name' => 'wallet',
            'html_id' => 'wallet',
			'payment_order' => '4',
			'is_active' => '1',
			'created_by' => '1'
			
        ));
		
		DB::table('payment_methods')->insert(array(
		
            'payment_method_name' => 'Unpaid Amt.',
            'html_name' => 'outstanding_amount',
            'html_id' => 'outstanding_amount',
			'payment_order' => '7',
			'is_active' => '1',
			'created_by' => '1'
			
        ));
		
		DB::table('payment_methods')->insert(array(
		
            'payment_method_name' => 'Net Banking',
            'html_name' => 'net_banking',
            'html_id' => 'net_banking',
			'payment_order' => '5',
			'is_active' => '1',
			'created_by' => '1'
			
        ));
		DB::table('payment_methods')->insert(array(

            'payment_method_name' => 'Credit Note',
            'html_name' => 'credit_note',
            'html_id' => 'credit_note',
            'payment_order' => '6',
            'is_active' => '1',
            'created_by' => '1'
        ));
        DB::table('payment_methods')->insert(array(

            'payment_method_name' => 'Debit Note',
            'html_name' => 'debit_note',
            'html_id' => 'debit_note',
            'payment_order' => '9',
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
        Schema::dropIfExists('payment_methods');
    }
}
