<?php

Route::get('PrintingFiles', function()
{
    return 'PrintingFiles';
});
Route::group(['namespace'=>'Retailcore\PrintingFiles\Http\Controllers','middleware' => ['web','auth']],Function()
{

//billing

Route::get('/print_bill', ['as' => 'print_bill', 'uses' => 'PrintingController@print_bill']);
Route::get('/thermalprint_bill', ['as' => 'thermalprint_bill', 'uses' => 'PrintingController@thermalprint_bill']);
Route::get('/print_creditnote', ['as' => 'print_creditnote', 'uses' => 'PrintingController@print_creditnote']);
Route::get('/print_creditreceipt', ['as' => 'print_creditreceipt', 'uses' => 'PrintingController@print_creditreceipt']);




});

