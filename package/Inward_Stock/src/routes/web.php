<?php

Route::get('Inward_Stock', function()
{
    return 'Inward_Stock';
});
Route::group(['namespace'=>'Retailcore\Inward_Stock\Http\Controllers','middleware' => ['web','auth']],Function()
{

//INWARD
    // Route::get('/inward_stock_show', 'inward\InwardStockController@index')->name('inward_stock');
    Route::get('/inward_stock', 'inward\InwardStockController@fmcg_inward_stock_show')->name('inward_stock');
    Route::get('/inward_stock_show', 'inward\InwardStockController@garment_inward_stock_show')->name('inward_stock_show');
    Route::post('/edit_inward_stock', 'inward\InwardStockController@edit_inward_stock')->name('edit_inward_stock');
    Route::post('/delete_inward_stock', 'inward\InwardStockController@delete_inward_stock')->name('delete_inward_stock');
    Route::get('/view_inward_stock', 'inward\ViewinwardController@index')->name('view_inward_stock');
    Route::post('/product_search', ['as' => 'product_search', 'uses' => 'inward\InwardStockController@product_search']);

    Route::post('/supplier_search', ['as' => 'supplier_search', 'uses' => 'inward\InwardStockController@supplier_search']);
    Route::post('/batch_no_search', ['as' => 'batch_no_search', 'uses' => 'inward\InwardStockController@batch_no_search']);
    Route::post('/product_detail', ['as' => 'product_detail', 'uses' => 'inward\InwardStockController@product_detail']);
    /*Route::post('/add_inward_stock', ['as' => 'add_inward_stock', 'uses' => 'inward\InwardStockController@add_inward_stock']);*/
    Route::post('/add_fmcg_inward_stock', ['as' => 'add_fmcg_inward_stock', 'uses' => 'inward\InwardStockController@add_fmcg_inward_stock']);
    Route::post('/add_garment_inward_stock', ['as' => 'add_garment_inward_stock', 'uses' => 'inward\InwardStockController@add_garment_inward_stock']);
    Route::get('/inward_data', 'inward\InwardStockController@inward_data')->name('inward_data');
    Route::get('/inward_fetch_data', ['as' => 'inward_fetch_data', 'uses' => 'inward\ViewinwardController@inward_fetch_data']);
    Route::post('/invoice_number_search', ['as' => 'invoice_number_search', 'uses' => 'inward\ViewinwardController@invoice_number_search']);
    Route::post('/view_inward_detail', ['as' => 'view_inward_detail', 'uses' => 'inward\ViewinwardController@view_inward_detail']);
    Route::post('/import_inward', ['as' => 'import_inward', 'uses' => 'inward\ImportInwardController@import_inward']);


    //INWARD EXPORT TO EXCEL
    Route::get('/inward_stock_export', ['as' => 'inward_stock_export', 'uses' => 'inward\InwardStockExportController@export_inward']);
    Route::get('/product_wise_report_export', ['as' => 'product_wise_report_export', 'uses' => 'inward\InwardStockExportController@product_wise_report_excel']);
    Route::get('/supplier_wise_report_export', ['as' => 'supplier_wise_report_export', 'uses' => 'inward\InwardStockExportController@supplier_wise_report_export']);
    Route::get('/inward_batch_report_export', ['as' => 'inward_batch_report_export', 'uses' => 'inward\InwardStockExportController@inward_batch_report_export']);
    Route::get('/inward_pricemaster_report_export', ['as' => 'inward_pricemaster_report_export', 'uses' => 'inward\InwardStockExportController@inward_pricemaster_report_export']);


    //INWARD REPORT
    Route::get('batch_no_wise_report', 'inward\InwardBatchNoWiseReportController@index')->name('batch_no_wise_report');
    Route::get('batch_no_wise_record', 'inward\InwardBatchNoWiseReportController@batch_no_wise_record')->name('batch_no_wise_record');
    Route::get('product_wise_report', 'inward\InwardProductWiseReportController@index')->name('product_wise_report');
    Route::get('product_wise_record', 'inward\InwardProductWiseReportController@product_wise_record')->name('product_wise_record');
    Route::get('supplier_wise_report', 'inward\InwardSupplierWiseReportController@index')->name('supplier_wise_report');
    Route::get('supplier_wise_record', 'inward\InwardSupplierWiseReportController@supplier_wise_record')->name('supplier_wise_record');
    Route::get('price_master_report', 'inward\PriceMasterReportController@index')->name('price_master_report');
    Route::get('price_master_record', 'inward\PriceMasterReportController@price_master_record')->name('price_master_record');
    Route::get('/export_batchno_details', ['as' => 'export_batchno_details', 'uses' => 'inward\InwardBatchNoWiseReportController@export_batchno_details']);
    Route::get('inward_gst_percent_wise_report', 'inward\InwardGstPercentWiseReportController@index')->name('inward_gst_percent_wise_report');
    Route::get('inward_gstperwise_search', 'inward\InwardGstPercentWiseReportController@inward_gstperwise_search')->name('inward_gstperwise_search');
    Route::get('inward_gst_wise_export_excel', 'inward\InwardGstPercentWiseReportController@inward_gst_wise_export_excel')->name('inward_gst_wise_export_excel');


    //INWARD DOWNLOAD EXCEL TEMPLATE
    Route::get('inward_template','inward\InwardStockExportController@inward_template')->name('inward_template');

});

