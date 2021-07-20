<?php

use Domain\Admin\Controllers\UserController;
use Domain\Plans\Controllers\PlansController;
use Domain\Admin\Controllers\InvoiceController;
use Domain\Admin\Controllers\DashboardController;
use Domain\Pages\Controllers\AdminPagesController;
use Domain\Localization\Controllers\LanguageController;
use Domain\Settings\Controllers\AdminSettingsController;
use Domain\Settings\Controllers\FlushCacheController;
use Domain\Settings\Controllers\GetSettingsValueController;
use Domain\Settings\Controllers\SetEmailController;
use Domain\Settings\Controllers\SetStripeController;
use Domain\Settings\Controllers\UpdateSettingValueController;

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
    Route::get('/{id}/subscribers', [PlansController::class, 'subscribers']);
    Route::delete('/{id}', [PlansController::class, 'delete']);
    Route::patch('/{id}', [PlansController::class, 'update']);
    Route::get('/{id}', [PlansController::class, 'show']);
    Route::post('/', [PlansController::class, 'store']);
    Route::get('/', [PlansController::class, 'index']);
});

// Pages
Route::apiResource('/pages', AdminPagesController::class);

// Invoices
Route::get('/invoices', [InvoiceController::class, 'index']);

// Settings
Route::group(['prefix' => 'settings'], function () {
    Route::patch('/', UpdateSettingValueController::class);
    Route::get('/', GetSettingsValueController::class);

    Route::get('/flush-cache', FlushCacheController::class);
    Route::post('/stripe', SetStripeController::class);
    Route::post('/email', SetEmailController::class);
});

// Language
Route::group(['prefix' => 'languages'], function () {
    Route::get('/{language}', [LanguageController::class, 'get_language']);
    Route::patch('/{language}/strings', [LanguageController::class, 'update_string']);
    Route::delete('/{language}', [LanguageController::class, 'delete_language']);
    Route::patch('/{language}', [LanguageController::class, 'update_language']);
    Route::post('/', [LanguageController::class, 'create_language']);
    Route::get('/', [LanguageController::class, 'get_languages']);
});
