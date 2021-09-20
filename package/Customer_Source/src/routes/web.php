<?php

Route::get('Customer_Source', function()
{
   return 'Customer_Source';
});
Route::group(['namespace'=>'Retailcore\Customer_Source\Http\Controllers','middleware' => ['web','auth']],Function()
{
//CUSTOMER SOURCE MANAGER
    Route::get('/customer_source', 'customer_source\CustomerSourceController@index')->name('customer_source');
    Route::post('/customer_source_create', ['as' => 'customer_source_create', 'uses' => 'customer_source\CustomerSourceController@customer_source_create']);
    Route::post('/customer_source_edit', ['as' => 'customer_source_edit', 'uses' => 'customer_source\CustomerSourceController@customer_source_edit']);
    Route::post('/customer_source_delete', ['as' => 'customer_source_delete', 'uses' => 'customer_source\CustomerSourceController@customer_source_delete']);
    Route::get('/source_data', ['as' => 'source_data', 'uses' => 'customer_source\CustomerSourceController@source_data']);
    Route::get('/customer_source_fetch_data', ['as' => 'customer_source_fetch_data', 'uses' => 'customer_source\CustomerSourceController@customer_source_fetch_data']);


});

