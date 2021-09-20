<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::prefix('v1')->namespace('API')->group(function ()
{
    // Login
    Route::post('/login','AuthController@postLogin');
    // Register
    Route::post('/register','AuthController@postRegister');
    // Protected with APIToken Middleware
    Route::middleware('APIToken')->group(function ()
    {
        // Logout
        Route::post('/logout','AuthController@postLogout');
    });
});*/

//Route::get('admin/profile', function () { })->middleware('auth');
/*Route::post('api/ecommerce','CustomerController@index’)->middleware('');*/

// routes for which you need to be an authenticated user (at any level)

Route::post('listing', 'Auth\RegisterController@register')->name('registerPost');


// User routes
/*Route::prefix('/{user}')->group(function ($userRoutes)
{
    $userRoutes->get('/', 'UserController@dashboard');
    $userRoutes->get('/dashboard', 'UserController@dashboard')->name('userDashboard');
    $userRoutes->get('/profile', 'UserController@profile')->name('userProfile');
    $userRoutes->get('/settings', 'UserController@settings')->name('userSettings');
    $userRoutes->get('/wishlist', 'UserController@wishlist')->name('userWishlist');

    $userRoutes->post('/profile/identity', 'UserController@updateIdentity')->name('userIdentityPost');
    $userRoutes->post('/profile/personal', 'UserController@updatePersonal')->name('userPersonalPost');
});*/
