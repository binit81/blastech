<?php

Route::get('SalesReport', function()
{
    return 'SalesReport';
});
Route::group(['namespace'=>'Retailcore\SalesReport\Http\Controllers','middleware' => ['web','auth']],Function()
{

//reports

Route::get('view_bill', 'ViewBillController@index')->name('view_bill');
Route::get('view_datewise_billdata', ['as' => 'view_datewise_billdata', 'uses' => 'ViewBillController@view_datewise_billdata']);
Route::get('profit_loss_report', 'ProfitLossReportController@index')->name('profit_loss_report');
Route::get('datewise_billdetail', ['as' => 'datewise_billdetail', 'uses' => 'ViewBillController@datewise_billdetail']);
Route::get('view_productwise_bill', 'ViewProductwiseBillController@index')->name('view_productwise_bill');
Route::get('datewise_product_billdetail', ['as' => 'datewise_product_billdetail', 'uses' => 'ViewProductwiseBillController@datewise_product_billdetail']);
Route::get('productgst_perwise_report', 'ProductgstPerwiseReportController@index')->name('productgst_perwise_report');
Route::get('gstwise_billdetail', ['as' => 'gstwise_billdetail', 'uses' => 'ProductgstPerwiseReportController@gstwise_billdetail']);
Route::get('exportbill_details', ['as' => 'exportbill_details', 'uses' => 'ViewBillController@exportbill_details']);
Route::get('exportproductwise_details', ['as' => 'exportproductwise_details', 'uses' => 'ViewProductwiseBillController@exportroomwise_details']);
Route::get('exportgstwise_details', ['as' => 'exportgstwise_details', 'uses' => 'ProductgstPerwiseReportController@exportgstwise_details']);
Route::post('bill_delete',['as' => 'bill_delete', 'uses'=> 'ViewBillController@bill_delete']);
Route::get('viewbill_data',['as' => 'viewbill_data', 'uses'=> 'ViewBillController@viewbill_data']);
Route::get('view_bill_popup', ['as' => 'view_bill_popup', 'uses' => 'ViewBillController@view_bill_popup']);
Route::get('previous_invoice', ['as' => 'previous_invoice', 'uses' => 'ViewBillController@previous_invoice']);
Route::get('next_invoice', ['as' => 'next_invoice', 'uses' => 'ViewBillController@next_invoice']);
Route::get('viewbillcustomer_search',['as' => 'viewbillcustomer_search', 'uses'=> 'ViewBillController@viewbillcustomer_search']);
Route::get('view_returnbill_popup', ['as' => 'view_returnbill_popup', 'uses' => 'ViewBillController@view_returnbill_popup']);
Route::get('rprevious_invoice', ['as' => 'rprevious_invoice', 'uses' => 'ViewBillController@rprevious_invoice']);
Route::get('rnext_invoice', ['as' => 'rnext_invoice', 'uses' => 'ViewBillController@rnext_invoice']);
Route::get('stock_report', 'StockreportController@index')->name('stock_report');
Route::get('datewise_stock_detail', ['as' => 'datewise_stock_detail', 'uses' => 'StockreportController@datewise_stock_detail']);
Route::get('export_stockreport_details', ['as' => 'export_stockreport_details', 'uses' => 'StockreportController@export_stockreport_details']);
Route::post('category_search', ['as' => 'category_search', 'uses' => 'StockreportController@category_search']);
Route::post('brand_search', ['as' => 'brand_search', 'uses' => 'StockreportController@brand_search']);
Route::get('datewise_profitloss_detail', ['as' => 'datewise_profitloss_detail', 'uses' => 'ProfitLossReportController@datewise_profitloss_detail']);
Route::get('exportprofitloss_details', ['as' => 'exportprofitloss_details', 'uses' => 'ProfitLossReportController@exportprofitloss_details']);
Route::post('sales_check',['as'=>'sales_check','uses'=>'ViewBillController@sales_check']);
Route::get('reference_search',['as' => 'reference_search', 'uses'=> 'ViewBillController@reference_search']);


});

