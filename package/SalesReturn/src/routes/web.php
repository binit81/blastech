<?php

Route::get('SalesReturn', function()
{
    return 'SalesReturn';
});
Route::group(['namespace'=>'Retailcore\SalesReturn\Http\Controllers','middleware' => ['web','auth']],Function()
{


//sales return
Route::get('sales_return', 'ReturnBillController@index');
Route::post('returnbill_data',['as' => 'returnbill_data' , 'uses'=> 'ReturnBillController@returnbill_data']);
Route::post('returnbillsecond_data',['as' => 'returnbillsecond_data' , 'uses'=> 'ReturnBillController@returnbillsecond_data']);
Route::post('returnbilling_create', ['as' => 'returnbilling_create', 'uses' => 'ReturnBillController@returnbilling_create']);
Route::post('returnbillingprint_create', ['as' => 'returnbillingprint_create', 'uses' => 'ReturnBillController@returnbillingprint_create']);
Route::get('billno_search',['as' => 'billno_search', 'uses'=> 'ReturnBillController@billno_search']);
Route::get('returned_products', 'ReturnbillProductController@index');
Route::post('restock_products', ['as' => 'restock_products', 'uses' => 'ReturnbillProductController@restock_products']);
Route::get('viewreturn_data',['as' => 'viewreturn_data', 'uses'=> 'ReturnbillProductController@viewreturn_data']);
Route::get('returned_products', 'ReturnbillProductController@index');
Route::post('restock_products', ['as' => 'restock_products', 'uses' => 'ReturnbillProductController@restock_products']);
Route::get('viewreturn_data',['as' => 'viewreturn_data', 'uses'=> 'ReturnbillProductController@viewreturn_data']);


});

