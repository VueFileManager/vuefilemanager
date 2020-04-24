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

// Public routes
Route::group(['middleware' => ['api']], function () {

    // User reset password
    Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'Auth\ResetPasswordController@reset');

    // User authentication
    Route::post('/user/check', 'Auth\AuthController@check_account');
    Route::post('/user/register', 'Auth\AuthController@register');
    Route::post('/user/login', 'Auth\AuthController@login');

    // Sharing
    Route::post('/shared/authenticate/{token}', 'Sharing\FileSharingController@authenticate');
    Route::get('/browse-public/{token}/{unique_id}', 'Sharing\FileSharingController@browse_public');
    Route::get('/file-public/{token}', 'Sharing\FileSharingController@file_public');
    Route::get('/shared/{token}', 'Sharing\FileSharingController@show');
});

// User master Routes
Route::group(['middleware' => ['auth:api', 'auth.cookie', 'scope:master']], function () {

    // User
    Route::post('/user/password', 'User\AccountController@change_password');
    Route::put('/user/profile', 'User\AccountController@update_profile');
    Route::get('/user', 'User\AccountController@user');

    // Browse
    Route::get('/folder/{unique_id}', 'FileBrowser\BrowseController@folder')->where('unique_id', '[0-9]+');
    Route::get('/file-detail/{unique_id}', 'FileBrowser\BrowseController@file_detail');
    Route::get('/folder-tree', 'FileBrowser\BrowseController@folder_tree');
    Route::get('/shared-all', 'FileBrowser\BrowseController@shared');
    Route::get('/search', 'FileBrowser\BrowseController@search');
    Route::get('/trash', 'FileBrowser\BrowseController@trash');

    // Edit functions
    Route::post('/move-item', 'FileFunctions\EditController@move_item');

    // Trash
    Route::post('/restore-item', 'FileFunctions\TrashController@restore');
    Route::delete('/empty-trash', 'FileFunctions\TrashController@clear');

    // Favourites
    Route::post('/remove-from-favourites', 'FileFunctions\FavouriteController@remove_from_favourites');
    Route::post('/add-to-favourites', 'FileFunctions\FavouriteController@add_to_favourites');

    // Share
    Route::delete('/share/remove', 'FileFunctions\ShareController@delete');
    Route::post('/share/generate', 'FileFunctions\ShareController@store');
    Route::post('/share/update', 'FileFunctions\ShareController@update');

    // Auth
    Route::get('/logout', 'Auth\AuthController@logout');
});

// Protected sharing routes for public user
Route::group(['middleware' => ['auth:api', 'auth.cookie', 'scope:visitor,editor']], function () {

    // Browse folders & files
    Route::get('/browse-private/{unique_id}', 'Sharing\FileSharingController@browse_private');
    Route::get('/file-private', 'Sharing\FileSharingController@file_private');
});

// User master,editor routes
Route::group(['middleware' => ['auth:api', 'auth.cookie', 'scope:master,editor,visitor']], function () {

    // File routes
    Route::get('/thumbnail/{name}', 'FileAccessController@get_thumbnail')->name('thumbnail');
    Route::get('/file/{name}', 'FileAccessController@get_file')->name('file');
});

// User master,editor routes
Route::group(['middleware' => ['auth:api', 'auth.cookie', 'scope:master,editor']], function () {

    // Edit items
    Route::post('/create-folder', 'FileFunctions\EditController@create_folder');
    Route::post('/rename-item', 'FileFunctions\EditController@rename_item');
    Route::post('/remove-item', 'FileFunctions\EditController@delete_item');
    Route::post('/upload-file', 'FileFunctions\EditController@upload_item');
});
