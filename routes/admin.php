<?php

use Domain\Plans\Controllers\PlansController;
use Domain\Admin\Controllers\Users\UserController;
use Domain\Pages\Controllers\AdminPagesController;
use Domain\Settings\Controllers\SetEmailController;
use Domain\Settings\Controllers\SetStripeController;
use Domain\Settings\Controllers\FlushCacheController;
use Domain\Invoices\Controllers\AdminInvoiceController;
use Domain\Localization\Controllers\LanguageController;
use Domain\Plans\Controllers\PlanSubscribersController;
use Domain\Admin\Controllers\Users\DeleteUserController;
use Domain\Settings\Controllers\GetSettingsValueController;
use Domain\Admin\Controllers\Dashboard\GetNewbiesController;
use Domain\Admin\Controllers\Users\ChangeUserRoleController;
use Domain\Settings\Controllers\UpdateSettingValueController;
use Domain\Admin\Controllers\Users\ShowUserInvoicesController;
use Domain\Admin\Controllers\Users\ResetUserPasswordController;
use Domain\Admin\Controllers\Dashboard\GetWidgetsValuesController;
use Domain\Admin\Controllers\Users\ShowUserSubscriptionController;
use Domain\Localization\Controllers\UpdateLanguageStringController;
use Domain\Admin\Controllers\Users\ShowUserStorageCapacityController;
use Domain\Admin\Controllers\Users\ChangeUserStorageCapacityController;

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
});

Route::apiResource('/users', UserController::class);

// Plans
Route::get('/plans/{id}/subscribers', PlanSubscribersController::class);
Route::apiResource('/', PlansController::class);

// Pages
Route::apiResource('/pages', AdminPagesController::class);

// Invoices
Route::get('/invoices', [AdminInvoiceController::class, 'index']);

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
