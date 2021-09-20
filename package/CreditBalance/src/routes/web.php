<?php

Route::get('CreditBalance', function()
{
    return 'CreditBalance';
});
Route::group(['namespace'=>'Retailcore\CreditBalance\Http\Controllers','middleware' => ['web','auth']],Function()
{


// Customer credit Balance

Route::get('customer_credit_summary', 'CustomerCreditaccountController@index');
Route::get('cbillno_search',['as' => 'cbillno_search', 'uses'=> 'CustomerCreditreceiptController@cbillno_search']);
Route::get('customer_credit_ac', ['as' => 'customer_credit_ac', 'uses' => 'CustomerCreditaccountController@customer_credit_ac']);
Route::post('save_customer_creditdetails', ['as' => 'save_customer_creditdetails', 'uses' => 'CustomerCreditaccountController@save_customer_creditdetails']);
Route::get('view_customer_creditreceipt', 'CustomerCreditreceiptController@index');
Route::get('datewise_cuscreditdetail', ['as' => 'datewise_cuscreditdetail', 'uses' => 'CustomerCreditreceiptController@datewise_cuscreditdetail']);
Route::post('receipt_delete',['as' => 'receipt_delete', 'uses'=> 'CustomerCreditreceiptController@receipt_delete']);
Route::get('viewreceipt_data',['as' => 'viewreceipt_data', 'uses'=> 'CustomerCreditreceiptController@viewreceipt_data']);
Route::get('datewise_creditbaldetail', ['as' => 'datewise_creditbaldetail', 'uses' => 'CustomerCreditaccountController@datewise_creditbaldetail']);
Route::get('customer_balance_record', ['as' => 'customer_balance_record', 'uses' => 'CustomerCreditaccountController@customer_balance_record']);
Route::get('view_creditreceipt_popup', ['as' => 'view_creditreceipt_popup', 'uses' => 'CustomerCreditaccountController@view_creditreceipt_popup']);
Route::get('exportcreditpayment_details', ['as' => 'exportcreditpayment_details', 'uses' => 'CustomerCreditaccountController@exportcreditpayment_details']);
});

