<?php

Route::get('CreditNote', function()
{
    return 'CreditNote';
});
Route::group(['namespace'=>'Retailcore\CreditNote\Http\Controllers','middleware' => ['web','auth']],Function()
{

// Customer credit Note

Route::get('creditnote_report', 'CreditnoteReportController@index')->name('creditnote_report');
Route::get('datewise_cuscreditnotedetail', ['as' => 'datewise_cuscreditnotedetail', 'uses' => 'CreditnoteReportController@datewise_cuscreditnotedetail']);
Route::get('view_creditnote_popup', ['as' => 'view_creditnote_popup', 'uses' => 'CreditnoteReportController@view_creditnote_popup']);





});

