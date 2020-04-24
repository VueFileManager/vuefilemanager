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

// Get user avatar
Route::get('/avatars/{avatar}', 'FileAccessController@get_avatar')->name('avatar');

// Get shared page
Route::get('/shared/{token}', 'Sharing\FileSharingController@index');

// Index Page
Route::get('/{any?}', 'AppFunctionsController@index')->where('any', '.*');