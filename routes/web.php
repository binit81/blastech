<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});
Auth::routes(['verify' => true]);
Auth::routes();

Route::group(['middleware' => ['auth']], function () {

    Route::get('/home', 'HomeController@dashboard')->name('home');
    Route::get('/logout', 'HomeController@logout');

    Route::get('/dashboard', 'HomeController@dashboard');
    Route::get('/changePassword', 'HomeController@showChangePasswordForm');

    Route::post('/changePassword', 'HomeController@changePassword')->name('changePassword');
    Route::get('/my_profile', 'HomeController@my_profile')->name('my_profile');

    Route::post('/universal_search', 'HomeController@universal_search');

    Route::get('/removePicture',['as' => 'removePicture', 'uses'=> 'employee\EmployeeMasterController@removePicture']);

    Route::post('/employee_form_create',['as' => 'employee_form_create', 'uses'=> 'employee\EmployeeMasterController@employee_form_create']);






//Route::post('/billing_create', ['as' => 'billing_create', 'uses' => 'billing\HotelBillController@billing_create']);
//Route::post('/billingprint_create', ['as' => 'billingprint_create', 'uses' => 'billing\HotelBillController@billingprint_create']);
//Route::get('/sales-print', 'billing\SalesprintController@index');


//Route::get('/print_bill', ['as' => 'print_bill', 'uses' => 'billing\HotelBillController@print_bill']);
//Route::get('/view_bill_fetch_data', ['as' => 'view_bill_fetch_data', 'uses' => 'billing\ViewBillController@view_bill_fetch_data']);


//sales Bill



//backup
Route::get('/backups',['as' => 'backups', 'uses'=> 'BackupController@index']);
Route::get('/create', ['as' => 'create', 'uses'=> 'BackupController@create']);
Route::get('/download/{file_name}', ['as' => 'backupDownload', 'uses'=> 'BackupController@download']);
Route::get('/delete/{file_name}', ['as' => 'backupDelete', 'uses'=> 'BackupController@delete']);

});
