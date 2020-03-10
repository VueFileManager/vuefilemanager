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

/*
|--------------------------------------------------------------------------
| Public API Routes for no needed user accounts
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['api']], function () {

});


/*
|--------------------------------------------------------------------------
| Public API Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['api']], function () {

    // User authentification
    Route::post('/user/check', 'PrivateCloud\AuthController@check_account');
    Route::post('/user/register', 'PrivateCloud\AuthController@register');
    Route::post('/user/login', 'PrivateCloud\AuthController@login');

    // User reset password
    Route::post('/password/email', 'PrivateCloud\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'PrivateCloud\ResetPasswordController@reset');
});

/*
|--------------------------------------------------------------------------
| Private API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['throttle:500,1', 'auth:api', 'auth.cookie']], function () {
    Route::get('/file/{name}', 'FileManagerController@get_file')->name('file');
});

Route::group(['middleware' => ['auth:api', 'auth.cookie']], function () {

    // User account
    Route::post('/user/password', 'PrivateCloud\UserAccountController@change_password');
    Route::put('/user/profile', 'PrivateCloud\UserAccountController@update_profile');
    Route::get('/user', 'PrivateCloud\UserAccountController@user');
    Route::get('/logout', 'PrivateCloud\AuthController@logout');

    // File manager routes
    Route::get('/folder/{unique_id}', 'PrivateCloud\FileManagerController@folder')->where('unique_id', '[0-9]+');
    Route::post('/remove-from-favourites', 'PrivateCloud\UserAccountController@remove_from_favourites');
    Route::get('/file-detail/{unique_id}', 'PrivateCloud\FileManagerController@get_file_detail');
    Route::post('/add-to-favourites', 'PrivateCloud\UserAccountController@add_to_favourites');
    Route::post('/create-folder', 'PrivateCloud\FileManagerController@create_folder');
    Route::delete('/empty-trash', 'PrivateCloud\FileManagerController@empty_trash');
    Route::post('/restore-item', 'PrivateCloud\FileManagerController@restore_item');
    Route::post('/rename-item', 'PrivateCloud\FileManagerController@rename_item');
    Route::post('/remove-item', 'PrivateCloud\FileManagerController@delete_item');
    Route::post('/upload-file', 'PrivateCloud\FileManagerController@upload_item');
    Route::post('/move-item', 'PrivateCloud\FileManagerController@move_item');
    Route::get('/search', 'PrivateCloud\FileManagerController@search');
    Route::get('/trash', 'PrivateCloud\FileManagerController@trash');
});
