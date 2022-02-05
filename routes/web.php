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


Route::get('/', 'member\BeforeLoginController@index');
Route::get('/login', 'member\BeforeLoginController@login');
Route::get('/sign-up', 'member\BeforeLoginController@signUp');
Route::get('/forgot-password', 'member\BeforeLoginController@forgotPassword');
Route::post('/process-forgot-password', 'member\BeforeLoginController@processForgotPassword')->name('process-forgot-password');
Route::get('/reset-password/{token}', 'member\BeforeLoginController@resetPassword');
Route::post('/reset-forgot-password', 'member\BeforeLoginController@processResetPassword')->name('process-reset-password');
Route::get('/verification', 'member\BeforeLoginController@verificationPage');
Route::post('/process-register', 'member\BeforeLoginController@processRegister')->name('process-register');
Route::post('/process-login', 'member\BeforeLoginController@processLogin')->name('member-process-login');
Route::post('/process-verification', 'member\BeforeLoginController@processVerification')->name('process-verification');


$MEMBER_PREFIX = "member";

/* Member Routes */
Route::group(['prefix' => $MEMBER_PREFIX], function(){

 /* After Login Pages */
    Route::group(['middleware' => 'member_auth'], function () {

        /* Dashboard Route */ 
        Route::get('dashboard', 'member\MemberController@index');
        Route::get('/', 'member\MemberController@index');

        /* Change Password Route */ 
        Route::get('change-password', 'member\BeforeLoginController@changePassword');
        Route::post('process-change-password', 'member\BeforeLoginController@processChangePassword')->name('process-change-password');
        
        /* Users */
        Route::resource('users', 'member\UsersController');
        Route::post('get-user-data-by-id', 'member\UsersController@getUserdataByID')->name('get-user-data-by-id');

        /* Logout Route */ 
        Route::get('logout', 'member\BeforeLoginController@getLogout');
    });
});

/* Logout Rout */ 
Route::get('member/logout', 'member\BeforeLoginController@getLogout');




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

        /* Member Routes */
        Route::resource('members', 'admin\MembersController');
        Route::post('get-member-data-by-id', 'admin\MembersController@getMemberdataByID')->name('get-member-data-by-id');
        Route::get('change-member-status/{id}', 'admin\MembersController@changeStatus');
        
        /* Users Routes */
        Route::resource('admin-users', 'admin\UsersController');
        Route::post('get-user-data-by-id-admin', 'admin\UsersController@getUserdataByID')->name('get-user-data-by-id-admin');
        
    	/* Logout Rout */ 
    	Route::get('logout', 'admin\AdminLoginController@getLogout')->name('logout');
    });
});


Route::get('/clear', function ()
{
    \Artisan::call('cache:clear');
    \Artisan::call('config:cache');
    \Artisan::call('view:clear');
    echo 'Done.';
});
