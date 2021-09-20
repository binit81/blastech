<?php

Route::get('DamageProducts', function()
{
    return 'DamageProducts';
});
Route::group(['namespace'=>'Retailcore\DamageProducts\Http\Controllers','middleware' => ['web','auth']],Function()
{


// Damage/Used Products
Route::get('/damage-used-products', 'damageproducts\DamageUsedProductsController@index')->name('damage-used-products');
Route::get('/damage_product_search',['as' => 'damage_product_search', 'uses'=> 'damageproducts\DamageUsedProductsController@damage_product_search']);
Route::post('/SaveDamageProducts',['as' => 'SaveDamageProducts', 'uses'=> 'damageproducts\DamageUsedProductsController@SaveDamageProducts']);
Route::get('/damage-used-report', 'damageproducts\DamageUsedProductsController@damage_used_report')->name('damage-used-report'); 
Route::get('/searchDamageProductReport',['as' => 'searchDamageProductReport', 'uses'=> 'damageproducts\DamageUsedProductsController@searchDamageProductReport']);
Route::get('/damage-used-product-wise', 'damageproducts\DamageUsedProductsController@damage_used_product_wise')->name('damage-used-product-wise'); 
Route::get('/damage_no_search',['as' => 'damage_no_search', 'uses'=> 'damageproducts\DamageUsedProductsController@damage_no_search']);
Route::get('/searchDamageProductReportGroup',['as' => 'searchDamageProductReportGroup', 'uses'=> 'damageproducts\DamageUsedProductsController@searchDamageProductReportGroup']);
Route::get('/exportdamage_details',['as' => 'exportdamage_details', 'uses'=> 'damageproducts\DamageUsedProductsController@exportdamage_details']);
Route::get('/exportdamageproduct_details',['as' => 'exportdamageproduct_details', 'uses'=> 'damageproducts\DamageUsedProductsController@exportdamageproduct_details']);
Route::post('/editDamage', ['as' => 'editDamage', 'uses' => 'damageproducts\DamageUsedProductsController@editDamage']);
Route::post('/delDamage', ['as' => 'delDamage', 'uses' => 'damageproducts\DamageUsedProductsController@delDamage']);

Route::get('/batch_damage_product_search',['as' => 'batch_damage_product_search', 'uses'=> 'damageproducts\DamageUsedProductsController@batch_damage_product_search']);
Route::get('/normal_damage_product_search',['as' => 'normal_damage_product_search', 'uses'=> 'damageproducts\DamageUsedProductsController@normal_damage_product_search']);

Route::post('/damage_product_detail_normal',['as' => 'damage_product_detail_normal' , 'uses'=> 'damageproducts\DamageUsedProductsController@damage_product_detail_normal']); 
Route::post('/damage_product_detail_batchno',['as' => 'damage_product_detail_batchno' , 'uses'=> 'damageproducts\DamageUsedProductsController@damage_product_detail_batchno']); 
Route::post('/damage_search_pricedetail',['as' => 'damage_search_pricedetail' , 'uses'=> 'damageproducts\DamageUsedProductsController@damage_search_pricedetail']); 



});

