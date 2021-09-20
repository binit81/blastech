<?php

Route::get('Debit_Note', function()
{
    return 'Debit_Note';
});
Route::group(['namespace'=>'Retailcore\Debit_Note\Http\Controllers','middleware' => ['web','auth']],Function()
{

//DEBIT NOTE
    Route::get('/debit_note',['as'=>'debit_note','uses' =>'debit_note\DebitNoteController@index']);
    Route::post('/invoice_no_search', ['as' => 'invoice_no_search', 'uses' => 'debit_note\DebitNoteController@invoice_no_search']);
    Route::post('/debit_productsearch', ['as' => 'debit_productsearch', 'uses' => 'debit_note\DebitNoteController@debit_productsearch']);
    Route::post('/inward_productdetail', ['as' => 'inward_productdetail', 'uses' => 'debit_note\DebitNoteController@inward_productdetail']);
    Route::post('/add_debit_note', ['as' => 'add_debit_note', 'uses' => 'debit_note\DebitNoteController@add_debit_note']);
    Route::get('/view_debit_note',['as'=>'view_debit_note','uses' =>'debit_note\ViewDebitNoteController@index']);

    Route::post('/view_debit_detail', ['as' => 'view_debit_detail', 'uses' => 'debit_note\ViewDebitNoteController@view_debit_detail']);
    Route::post('/edit_debit_note', ['as' => 'edit_debit_note','uses'=>'debit_note\DebitProductDetailController@edit_debit_note']);
    Route::post('/debit_note_delete', ['as' => 'debit_note_delete', 'uses' => 'debit_note\DebitNoteController@debit_note_delete']);
    Route::get('/debit_note_data', 'debit_note\ViewDebitNoteController@debit_note_data')->name('debit_note_data');
    Route::get('/debit_note_fetch_data', ['as' => 'debit_note_fetch_data', 'uses' => 'debit_note\ViewDebitNoteController@debit_note_fetch_data']);

    Route::get('/debit_note_report',['as' =>'debit_note_report','uses'=>'debit_note\DebitProductDetailController@debit_note_report']);
    Route::get('/print_debit_note',['as' =>'print_debit_note','uses'=>'debit_note\DebitNoteController@print_debit_note']);

    //FOR DEBIT NOTE AMOUNT.WHICH ARE SHOWN IN INWARD STOCK
    Route::post('/get_debit_note_amount',['as' =>'get_debit_note_amount','uses'=>'debit_note\DebitNoteController@get_debit_note_amount']);

    Route::get('/debit_no_wise_search_record',['as' => 'debit_no_wise_search_record' , 'uses'=> 'debit_note\DebitProductDetailController@debit_no_wise_search_record']);

    Route::get('debitnote_report_export','debit_note\DebitProductDetailController@debitnote_report_export')->name('debitnote_report_export');

});

