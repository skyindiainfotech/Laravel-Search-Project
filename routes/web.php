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

Route::get('test', function () {
    echo "test one";
});

$MEMBER_PREFIX = "member";
Route::get('/', 'member\MemberController@index');
Route::get('/login', 'member\MemberController@login');

/* Admin Routes */
Route::group(['prefix' => $MEMBER_PREFIX], function(){



});


$ADMIN_PREFIX = "admin";

/* Admin Routes */
Route::group(['prefix' => $ADMIN_PREFIX], function(){

    Route::get('/', 'admin\AdminLoginController@getLogin');

    //Route::get('/', [AdminLoginController::class, 'getLogin']);

	Route::get('login', 'admin\AdminLoginController@getLogin')->name('login');
    Route::post('process-login', 'admin\AdminLoginController@postLogin')->name('login-process');

    

    /* After Login Pages */
    Route::group(['middleware' => 'admin_auth'], function () {
        /* Dashboard Rout */ 
    	Route::get('dashboard', 'admin\AdminController@index')->name('dashboard');

    	/* Logout Rout */ 
    	Route::get('logout', 'admin\AdminLoginController@getLogout')->name('logout');
    });
});

/* Member Routes */
//Route::get('/', 'admin\AdminLoginController@landingPage');


Route::get('/clear', function ()
{
    \Artisan::call('cache:clear');
    \Artisan::call('config:cache');
    \Artisan::call('view:clear');
    echo 'Done.';
});
