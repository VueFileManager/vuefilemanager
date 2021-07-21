<?php

use Domain\Admin\Controllers\Dashboard\GetNewbiesController;
use Domain\Admin\Controllers\Dashboard\GetWidgetsValuesController;
use Domain\Admin\Controllers\Users\UserController;
use Domain\Admin\Controllers\Users\ChangeUserRoleController;
use Domain\Admin\Controllers\Users\ChangeUserStorageCapacityController;
use Domain\Admin\Controllers\Users\DeleteUserController;
use Domain\Admin\Controllers\Users\ResetUserPasswordController;
use Domain\Admin\Controllers\Users\ShowUserInvoicesController;
use Domain\Admin\Controllers\Users\ShowUserStorageCapacityController;
use Domain\Admin\Controllers\Users\ShowUserSubscriptionController;
use Domain\Plans\Controllers\PlansController;
use Domain\Admin\Controllers\InvoiceController;
use Domain\Pages\Controllers\AdminPagesController;
use Domain\Settings\Controllers\SetEmailController;
use Domain\Settings\Controllers\SetStripeController;
use Domain\Settings\Controllers\FlushCacheController;
use Domain\Localization\Controllers\LanguageController;
use Domain\Settings\Controllers\GetSettingsValueController;
use Domain\Settings\Controllers\UpdateSettingValueController;
use Domain\Localization\Controllers\UpdateLanguageStringController;

// Dashboard
Route::group(['prefix' => 'dashboard'], function () {
    Route::get('/newbies', GetNewbiesController::class);
    Route::get('/', GetWidgetsValuesController::class);
});

// Users
Route::group(['prefix' => 'users'], function () {
    Route::patch('/{user}/capacity', ChangeUserStorageCapacityController::class);
    Route::post('/{user}/reset-password', ResetUserPasswordController::class);
    Route::get('/{user}/storage', ShowUserStorageCapacityController::class);
    Route::patch('/{user}/role', ChangeUserRoleController::class);
    Route::delete('/{user}/delete', DeleteUserController::class);

    // Subscription
    Route::get('/{user}/subscription', ShowUserSubscriptionController::class);
    Route::get('/{user}/invoices', ShowUserInvoicesController::class);

    // Resource
    Route::apiResource('/', UserController::class);
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
Route::patch('/languages/{language}/strings', UpdateLanguageStringController::class);
Route::apiResource('/languages', LanguageController::class);
