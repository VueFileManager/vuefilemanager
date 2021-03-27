<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AppFunctionsController;
use App\Http\Controllers\Admin\SettingController;

// Dashboard
Route::group(['prefix' => 'dashboard'], function () {
    Route::get('/newbies', [DashboardController::class, 'newbies']);
    Route::get('/', [DashboardController::class, 'index']);
});

// Users
Route::group(['prefix' => 'users'], function () {
    Route::patch('/{user}/capacity', [UserController::class, 'change_storage_capacity']);
    Route::post('/{user}/reset-password', [UserController::class, 'reset_password']);
    Route::get('/{user}/subscription', [UserController::class, 'subscription']);
    Route::delete('/{user}/delete', [UserController::class, 'delete_user']);
    Route::patch('/{user}/role', [UserController::class, 'change_role']);
    Route::get('/{user}/invoices', [UserController::class, 'invoices']);
    Route::get('/{user}/storage', [UserController::class, 'storage']);
    Route::get('/{user}/detail', [UserController::class, 'details']);
    Route::post('/create', [UserController::class, 'create_user']);
    Route::get('/', [UserController::class, 'users']);
});

// Plans
Route::group(['prefix' => 'plans'], function () {
    Route::get('/{id}/subscribers', [PlanController::class, 'subscribers']);
    Route::delete('/{id}', [PlanController::class, 'delete']);
    Route::patch('/{id}', [PlanController::class, 'update']);
    Route::get('/{id}', [PlanController::class, 'show']);
    Route::post('/', [PlanController::class, 'store']);
    Route::get('/', [PlanController::class, 'index']);
});

// Pages
Route::group(['prefix' => 'pages'], function () {
    Route::patch('/{page}', [PagesController::class, 'update']);
    Route::get('/{page}', [PagesController::class, 'show']);
    Route::get('/', [PagesController::class, 'index']);
});

// Invoices
Route::get('/invoices', [InvoiceController::class, 'index']);

// Settings
Route::group(['prefix' => 'settings'], function () {
    Route::get('/flush-cache', [SettingController::class, 'flush_cache']);
    Route::post('/stripe', [SettingController::class, 'set_stripe']);
    Route::post('/email', [SettingController::class, 'set_email']);
    Route::patch('/', [SettingController::class, 'update']);
    Route::get('/', [SettingController::class, 'show']);
});

// Language
Route::group(['prefix' => 'languages'], function () {
    Route::get('/{language}/strings', 'Admin\LanguageController@get_language_strings');
    Route::patch('/{language}/string', 'Admin\LanguageController@update_string');
    Route::delete('/{language}', 'Admin\LanguageController@delete_language');
    Route::patch('/{language}', 'Admin\LanguageController@update_language');
    Route::post('/', 'Admin\LanguageController@create_language');
    Route::get('/', 'Admin\LanguageController@get_languages');
});