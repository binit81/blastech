<?php

Route::get('Sales', function()
{
    return 'Sales';
});
Route::group(['namespace'=>'Retailcore\Sales\Http\Controllers','middleware' => ['web','auth']],Function()
{

//billing
Route::get('sales_bill', 'SalesBillController@index')->name('sales');
Route::post('edit_bill','SalesBillController@edit_bill')->name('edit_bill');
Route::post('customer_search', ['as' => 'customer_search', 'uses' => 'SalesBillController@customer_search']);
Route::post('customer_detail', ['as' => 'customer_search', 'uses' => 'SalesBillController@customer_detail']);
Route::get('sproduct_search',['as' => 'sproduct_search', 'uses'=> 'SalesBillController@sproduct_search']);
Route::get('bsproduct_search',['as' => 'bsproduct_search', 'uses'=> 'SalesBillController@bsproduct_search']);
Route::post('sproduct_detail',['as' => 'sproduct_detail' , 'uses'=> 'SalesBillController@sproduct_detail']);
Route::post('bsproduct_detail',['as' => 'bsproduct_detail' , 'uses'=> 'SalesBillController@bsproduct_detail']);
Route::post('search_pricedetail',['as' => 'search_pricedetail' , 'uses'=> 'SalesBillController@search_pricedetail']);
Route::post('billing_create', ['as' => 'billing_create', 'uses' => 'SalesBillController@billing_create']);
Route::post('billingprint_create', ['as' => 'billingprint_create', 'uses' => 'SalesBillController@billingprint_create']);

Route::get('refname_search',['as' => 'refname_search', 'uses'=> 'SalesBillController@refname_search']);
Route::get('creditnote_numbersearch',['as' => 'creditnote_numbersearch', 'uses'=> 'SalesBillController@creditnote_numbersearch']);
Route::post('creditnote_details',['as' => 'creditnote_details' , 'uses'=> 'SalesBillController@creditnote_details']);
Route::post('gstrange_detail', ['as' => 'gstrange_detail', 'uses' => 'SalesBillController@gstrange_detail']);


Route::get('charges_search',['as' => 'charges_search', 'uses'=> 'SalesBillController@charges_search']);
Route::get('product_popup_values', ['as' => 'product_popup_values', 'uses' => 'SalesBillController@product_popup_values']);




});

