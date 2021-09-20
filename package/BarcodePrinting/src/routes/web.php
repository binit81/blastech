<?php

Route::get('BarcodePrinting', function()
{
    return 'BarcodePrinting';
});
Route::group(['namespace'=>'Retailcore\BarcodePrinting\Http\Controllers','middleware' => ['web','auth']],Function()
{

//BarcodePrinting
Route::get('/barcode-printing', 'barcodeprinting\BarcodePrintingController@index')->name('barcode-printing');
Route::post('/bar_product_search',['as' => 'bar_product_search', 'uses'=> 'barcodeprinting\BarcodePrintingController@bar_product_search']);
Route::post('/barcode_search',['as' => 'barcode_search', 'uses'=> 'barcodeprinting\BarcodePrintingController@barcode_search']);
Route::post('/product_code',['as' => 'product_code', 'uses'=> 'barcodeprinting\BarcodePrintingController@product_code']);
Route::post('/invoice_no',['as' => 'invoice_no', 'uses'=> 'barcodeprinting\BarcodePrintingController@invoice_no']);
Route::get('/searchBarcodePrintProduct', ['as' => 'searchBarcodePrintProduct', 'uses' => 'barcodeprinting\BarcodePrintingController@searchBarcodePrintProduct']);
Route::get('/barcode-sticker', 'barcodeprinting\BarcodePrintingController@BarcodePrintingSticker');
Route::post('/barcode_product_detail',['as' => 'barcode_product_detail', 'uses'=> 'barcodeprinting\BarcodePrintingController@barcode_product_detail']);
Route::post('/fetchTemplateData',['as' => 'fetchTemplateData', 'uses'=> 'barcodeprinting\BarcodePrintingController@fetchTemplateData']);
Route::post('/GenerateBarcode',['as' => 'GenerateBarcode', 'uses'=> 'barcodeprinting\BarcodePrintingController@GenerateBarcode']);
Route::post('/fetchBarcodeLabels',['as' => 'fetchBarcodeLabels', 'uses'=> 'barcodeprinting\BarcodePrintingController@fetchBarcodeLabels']);
Route::post('/template_save',['as' => 'template_save', 'uses'=> 'barcodeprinting\BarcodePrintingController@template_save']);
Route::post('/edit_template_save',['as' => 'edit_template_save', 'uses'=> 'barcodeprinting\BarcodePrintingController@edit_template_save']);
Route::post('/saveBarcodeTemplateToUser',['as' => 'saveBarcodeTemplateToUser', 'uses'=> 'barcodeprinting\BarcodePrintingController@saveBarcodeTemplateToUser']);
Route::post('/editTemplate', ['as' => 'editTemplate', 'uses' => 'barcodeprinting\BarcodePrintingController@editTemplate']);
Route::post('/deleteTemplate', ['as' => 'deleteTemplate', 'uses' => 'barcodeprinting\BarcodePrintingController@deleteTemplate']);

Route::post('/supplier_barcode_search',['as' => 'supplier_barcode_search', 'uses'=> 'barcodeprinting\BarcodePrintingController@supplier_barcode_search']);
Route::post('/category_search',['as' => 'category_search', 'uses'=> 'barcodeprinting\BarcodePrintingController@category_search']);
Route::post('/brand_search',['as' => 'brand_search', 'uses'=> 'barcodeprinting\BarcodePrintingController@brand_search']);
Route::post('/size_search',['as' => 'size_search', 'uses'=> 'barcodeprinting\BarcodePrintingController@size_search']);
Route::post('/colour_search',['as' => 'colour_search', 'uses'=> 'barcodeprinting\BarcodePrintingController@colour_search']);
Route::post('/sku_search',['as' => 'sku_search', 'uses'=> 'barcodeprinting\BarcodePrintingController@sku_search']);


});

