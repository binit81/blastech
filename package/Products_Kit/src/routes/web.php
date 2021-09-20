<?php

Route::get('Products_Kit', function()
{
    return 'Products_Kit';
});
Route::group(['namespace'=>'Retailcore\Products_Kit\Http\Controllers','middleware' => ['web','auth']],Function()
{
		Route::get('addproducts_kit', 'ComboProductsDetailController@index')->name('addproducts_kit');
		Route::post('productkit_create', ['as' => 'productkit_create', 'uses' => 'ComboProductsDetailController@productkit_create']);
		Route::get('kitproduct_search',['as' => 'kitproduct_search', 'uses'=> 'ComboProductsDetailController@kitproduct_search']);
		Route::post('kitproduct_detail',['as' => 'kitproduct_detail' , 'uses'=> 'ComboProductsDetailController@kitproduct_detail']);
		Route::post('kit_create', ['as' => 'kit_create', 'uses' => 'ComboProductsDetailController@kit_create']);
		Route::get('viewproducts_kit', 'ComboProductsDetailController@viewproducts_kit')->name('viewproducts_kit');
		Route::get('productskit_fetch_data', ['as' => 'productskit_fetch_data', 'uses' => 'ComboProductsDetailController@productskit_fetch_data']);
		Route::post('edit_productskit','ComboProductsDetailController@edit_productskit')->name('edit_productskit');
		Route::get('view_kitdetail_popup', ['as' => 'view_kitdetail_popup', 'uses' => 'ComboProductsDetailController@view_kitdetail_popup']);
		Route::get('inward_productskit', 'InwardKitDetailController@index')->name('inward_productskit');
		Route::get('inwardkit_search',['as' => 'inwardkit_search', 'uses'=> 'InwardKitDetailController@inwardkit_search']);
		Route::post('productkit_detail',['as' => 'productkit_detail' , 'uses'=> 'InwardKitDetailController@productkit_detail']);
		Route::post('createcombo_inward', ['as' => 'createcombo_inward', 'uses' => 'InwardKitDetailController@createcombo_inward']);
        Route::post('inward_productskit','InwardKitDetailController@inward_productskit')->name('inward_productskit');
        Route::get('view_kitinward', 'InwardKitDetailController@view_kitinward')->name('view_kitinward');
        Route::post('edit_kitinward','InwardKitDetailController@edit_kitinward')->name('edit_kitinward');

});

