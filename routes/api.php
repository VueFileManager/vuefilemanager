<?php
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
| Public API Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['api']], function () {

    // User reset password
    Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'Auth\ResetPasswordController@reset');

    // User authentification
    Route::post('/user/check', 'Auth\AuthController@check_account');
    Route::post('/user/register', 'Auth\AuthController@register');
    Route::post('/user/login', 'Auth\AuthController@login');
});

/*
|--------------------------------------------------------------------------
| Private API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:api', 'auth.cookie']], function () {

    // File route
    Route::get('/file/{name}', 'FileManagerController@get_file')->name('file');
    Route::get('/thumbnail/{name}', 'FileManagerController@get_thumbnail')->name('thumbnail');

    // User account routes
    Route::post('/user/password', 'UserAccountController@change_password');
    Route::put('/user/profile', 'UserAccountController@update_profile');
    Route::get('/logout', 'Auth\AuthController@logout');
    Route::get('/user', 'UserAccountController@user');

    // File manager routes
    Route::get('/folder/{unique_id}', 'FileManagerController@folder')->where('unique_id', '[0-9]+');
    Route::post('/remove-from-favourites', 'UserAccountController@remove_from_favourites');
    Route::get('/file-detail/{unique_id}', 'FileManagerController@get_file_detail');
    Route::post('/add-to-favourites', 'UserAccountController@add_to_favourites');
    Route::post('/create-folder', 'FileManagerController@create_folder');
    Route::delete('/empty-trash', 'FileManagerController@empty_trash');
    Route::post('/restore-item', 'FileManagerController@restore_item');
    Route::post('/rename-item', 'FileManagerController@rename_item');
    Route::post('/remove-item', 'FileManagerController@delete_item');
    Route::post('/upload-file', 'FileManagerController@upload_item');
    Route::get('/folder-tree', 'UserAccountController@folder_tree');
    Route::post('/move-item', 'FileManagerController@move_item');
    Route::get('/search', 'FileManagerController@search');
    Route::get('/trash', 'FileManagerController@trash');

    // Sharing routes
    Route::post('/share/generate', 'FileSharingController@generate_link');
    Route::post('/share/check', 'FileSharingController@check_password');
    Route::get('/shared', 'FileSharingController@get_shared');
});
