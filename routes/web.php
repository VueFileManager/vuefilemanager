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

// Get File
Route::get('/avatars/{avatar}', 'FileManagerController@get_avatar')->name('avatar');


// Landing Page
Route::get('/', 'PrivateCloud\FileManagerController@index');
