<?php

Route::get('Customer', function()
{
   return 'Customer';
});
Route::group(['namespace'=>'Retailcore\Customer\Http\Controllers','middleware' => ['web','auth']],Function()
{
//CUSTOMER MANAGER
    Route::get('/customer_show', 'customer\CustomerController@index')->name('customer_show');
    Route::get('/customer_data', 'customer\CustomerController@customer_data')->name('customer_data');
    Route::get('/refreshcustomer', 'customer\CustomerController@refreshcustomer')->name('refreshcustomer');
    Route::post('/customer_create', ['as' => 'customer_create', 'uses' => 'customer\CustomerController@customer_create']);
    Route::post('/customer_search', ['as' => 'customer_search', 'uses' => 'sales\SalesBillController@customer_search']);
    Route::post('/customer_detail', ['as' => 'customer_detail', 'uses' => 'sales\SalesBillController@customer_detail']);
    Route::post('/customer_edit', ['as' => 'customer_edit', 'uses' => 'customer\CustomerController@customer_edit']);
    Route::post('/customer_delete', ['as' => 'customer_delete', 'uses' => 'customer\CustomerController@customer_delete']);
    Route::get('/customer_fetch_data', ['as' => 'customer_fetch_data', 'uses' => 'customer\CustomerController@customer_fetch_data']);
    Route::post('/customer_check',['as'=>'customer_check','uses'=>'customer\CustomerController@customer_check']);

    //DOWNLOAD CUSTOMER TEMPALTE
    Route::get('customer_template','customer\CustomerController@customer_template')->name('customer_template');

    //FOR CUSTOMER DEPENDENT RECORD
    Route::post('customer_dependency',['as'=>'customer_dependency','uses'=>'customer\CustomerController@customer_dependency']);
    //END OF DEPENDENT RECORD
});

