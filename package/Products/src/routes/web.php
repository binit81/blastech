<?php

Route::get('Products', function()
{
    return 'Products';
});
Route::group(['namespace'=>'Retailcore\Products\Http\Controllers','middleware' => ['web','auth']],Function()
{
//product and service
    Route::get('/product_show', 'product\ProductController@index')->name('product');
    Route::get('/product_data', 'product\ProductController@product_data')->name('product_data');
    Route::get('/service_data', 'product\ProductController@service_data')->name('service_data');
    Route::post('/product_create', ['as' => 'product_create', 'uses' => 'product\ProductController@product_create']);
    Route::post('/product_edit', ['as' => 'product_edit', 'uses' => 'product\ProductController@product_edit']);
    Route::post('/product_delete', ['as' => 'product_delete', 'uses' => 'product\ProductController@product_delete']);
    Route::get('/room_fetch_data', ['as' => 'room_fetch_data', 'uses' => 'product\ProductController@room_fetch_data']);
    Route::get('/product_fetch_data', ['as' => 'product_fetch_data', 'uses' => 'product\ProductController@product_fetch_data']);
    Route::post('/product_check', ['as' => 'product_check', 'uses' => 'product\ProductController@product_check']);
    Route::post('/inward_product_detail', ['as' => 'inward_product_detail', 'uses' => 'product\ProductController@inward_product_detail']);
    Route::post('/product_name_search', ['as' => 'product_name_search', 'uses' => 'product\ProductController@product_name_search']);
    Route::post('/product_barcode_search', ['as' => 'product_barcode_search', 'uses' => 'product\ProductController@product_barcode_search']);
    Route::get('/ProductremovePicture', ['as' => 'ProductremovePicture', 'uses' => 'product\ProductController@ProductremovePicture']);
    Route::get('/get_productImages', ['as' => 'get_productImages', 'uses' => 'product\ProductController@get_productImages']);



    //product export
    Route::get('/product_export', ['as' => 'product_export', 'uses' => 'product\ProductController@product_export']);


    //brand
    Route::get('/brand_show', 'product\BrandController@index')->name('brand');
    Route::post('/brand_create', ['as' => 'brand_create', 'uses' => 'product\BrandController@brand_create']);
    Route::get('/brand_edit', ['as' => 'brand_edit', 'uses' => 'product\BrandController@brand_edit']);
    Route::get('/brand_delete', ['as' => 'brand_delete', 'uses' => 'product\BrandController@brand_delete']);
    //for product module listing of brand
    Route::get('/get_brand', ['as' => 'get_brand', 'uses' => 'product\BrandController@get_brand']);

    //colour
    Route::get('/colour_show', 'product\ColourController@index')->name('colour');
    Route::post('/colour_create', ['as' => 'colour_create', 'uses' => 'product\ColourController@colour_create']);
    Route::get('/colour_edit', ['as' => 'colour_edit', 'uses' => 'product\ColourController@colour_edit']);
    Route::get('/colour_delete', ['as' => 'colour_delete', 'uses' => 'product\ColourController@colour_delete']);
    //get colour list for product module
    Route::get('/get_colour', ['as' => 'get_colour', 'uses' => 'product\ColourController@get_colour']);


    //size
    Route::get('/size_show', 'product\SizeController@index')->name('size');
    Route::post('/size_create', ['as' => 'size_create', 'uses' => 'product\SizeController@size_create']);
    Route::get('/size_edit', ['as' => 'size_edit', 'uses' => 'product\SizeController@size_edit']);
    Route::get('/size_delete', ['as' => 'size_delete', 'uses' => 'product\SizeController@size_delete']);
    //get size list for product module
    Route::get('/get_size', ['as' => 'get_size', 'uses' => 'product\SizeController@get_size']);

    //uqc
    Route::get('/uqc_show', 'product\UqcController@index')->name('uqc');
    Route::post('/uqc_create', ['as' => 'uqc_create', 'uses' => 'product\UqcController@uqc_create']);
    Route::get('/uqc_edit', ['as' => 'uqc_edit', 'uses' => 'product\UqcController@uqc_edit']);
    Route::get('/uqc_delete', ['as' => 'uqc_delete', 'uses' => 'product\UqcController@uqc_delete']);
    //get uqc list for product module
    Route::get('/get_uqc', ['as' => 'get_uqc', 'uses' => 'product\UqcController@get_uqc']);


    //category
    Route::get('/category_show', 'product\CategoryController@index')->name('category');
    Route::post('/category_create', ['as' => 'category_create', 'uses' => 'product\CategoryController@category_create']);
    Route::get('/category_edit', ['as' => 'category_edit', 'uses' => 'product\CategoryController@category_edit']);
    Route::get('/category_delete', ['as' => 'category_delete', 'uses' => 'product\CategoryController@category_delete']);
    //Category list for product module
    Route::get('/get_category', ['as' => 'get_category', 'uses' => 'product\CategoryController@get_category']);


    //Sub Category
    Route::get('/subcategory_show', 'product\SubcategoryController@index')->name('subcategory');
    Route::post('/subcategory_create', ['as' => 'subcategory_create', 'uses' => 'product\SubcategoryController@subcategory_create']);
    Route::get('/subcategory_edit', ['as' => 'subcategory_edit', 'uses' => 'product\SubcategoryController@subcategory_edit']);
    Route::get('/subcategory_delete', ['as' => 'subcategory_delete', 'uses' => 'product\SubcategoryController@subcategory_delete']);
    //for getting subcategory based on category for product module
    Route::post('/get_subcategory', ['as' => 'get_subcategory', 'uses' => 'product\SubcategoryController@get_subcategory']);


    //FOR GETTING DEPENDENCY RECORD OF PRODUCT
    Route::post('/product_dependency',['as' => 'product_dependency','uses' => 'product\ProductController@product_dependency']);
    //END OF GETTING DEPENDENCY RECORD OF PRODUCT

});

