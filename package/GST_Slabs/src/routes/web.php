<?php

Route::get('GST_Slabs', function()
{
    return 'GST_Slabs';
});
Route::group(['namespace'=>'Retailcore\GST_Slabs\Http\Controllers','middleware' => ['web','auth']],Function()
{
//GST SLABS
    Route::get('/gst_slabs', 'GST_Slabs\GstSlabsMasterController@index')->name('gst_slabs');
    Route::get('/gst_slabs_data', 'GST_Slabs\GstSlabsMasterController@gst_slabs_data')->name('gst_slabs_data');
    Route::post('/gstslabs_create', ['as' => 'gstslabs_create', 'uses' => 'GST_Slabs\GstSlabsMasterController@gstslabs_create']);
    Route::post('/gstslab_edit', ['as' => 'gstslab_edit', 'uses' => 'GST_Slabs\GstSlabsMasterController@gstslab_edit']);
    Route::post('/gstslabs_delete', ['as' => 'gstslabs_delete', 'uses' => 'GST_Slabs\GstSlabsMasterController@gstslabs_delete']);
    Route::get('/gst_slabs_fetch_data', ['as' => 'gst_slabs_fetch_data', 'uses' => 'GST_Slabs\GstSlabsMasterController@gst_slabs_fetch_data']);


});

