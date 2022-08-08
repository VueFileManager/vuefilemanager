<?php

use Domain\Admin\Controllers\Users\UserController;
use Domain\Pages\Controllers\AdminPagesController;
use Domain\Settings\Controllers\FlushCacheController;
use Domain\Localization\Controllers\LanguageController;
use Domain\Admin\Controllers\Users\DeleteUserController;
use Domain\Settings\Controllers\UpgradeLicenseController;
use Domain\Settings\Controllers\GetServerStatusController;
use Domain\Settings\Controllers\GetSettingsValueController;
use Domain\Admin\Controllers\Dashboard\GetNewbiesController;
use Domain\Admin\Controllers\Users\ChangeUserRoleController;
use Domain\Settings\Controllers\UpdateSettingValueController;
use Domain\Admin\Controllers\Users\ResetUserPasswordController;
use Domain\Settings\Controllers\StoreEmailCredentialsController;
use Domain\Transactions\Controllers\GetAllTransactionsController;
use Domain\Admin\Controllers\Dashboard\GetDashboardDataController;
use Domain\Settings\Controllers\StoreStorageCredentialsController;
use Domain\Settings\Controllers\TestWebsocketConnectionController;
use Domain\Transactions\Controllers\GetUserTransactionsController;
use Domain\Localization\Controllers\UpdateLanguageStringController;
use Domain\Admin\Controllers\Users\ShowUserStorageCapacityController;
use Domain\Admin\Controllers\Dashboard\GetLatestTransactionsController;
use Domain\Admin\Controllers\Users\ChangeUserStorageCapacityController;
use Domain\Settings\Controllers\StoreSocialServiceCredentialsController;
use Domain\Settings\Controllers\StorePaymentServiceCredentialsController;
use Domain\Settings\Controllers\StoreBroadcastServiceCredentialsController;

// Dashboard
Route::group(['prefix' => 'dashboard'], function () {
    Route::get('/transactions', GetLatestTransactionsController::class);
    Route::get('/newbies', GetNewbiesController::class);
    Route::get('/', GetDashboardDataController::class);
});

// Users
Route::group(['prefix' => 'users'], function () {
    Route::patch('/{user}/capacity', ChangeUserStorageCapacityController::class);
    Route::post('/{user}/reset-password', ResetUserPasswordController::class);
    Route::get('/{user}/transactions', GetUserTransactionsController::class);
    Route::get('/{user}/storage', ShowUserStorageCapacityController::class);
    Route::patch('/{user}/role', ChangeUserRoleController::class);
    Route::delete('/{user}', DeleteUserController::class);
});

Route::get('/transactions', GetAllTransactionsController::class);
Route::apiResource('/pages', AdminPagesController::class);
Route::apiResource('/users', UserController::class)
    ->only(['index', 'show', 'store']);

// Settings
Route::group(['prefix' => 'settings'], function () {
    Route::patch('/', UpdateSettingValueController::class);
    Route::get('/', GetSettingsValueController::class);

    Route::get('/flush-cache', FlushCacheController::class);
    Route::post('/email', StoreEmailCredentialsController::class);
    Route::post('/storage', StoreStorageCredentialsController::class);

    Route::post('/payment-service', StorePaymentServiceCredentialsController::class);
    Route::post('/social-service', StoreSocialServiceCredentialsController::class);
    Route::post('/broadcast', StoreBroadcastServiceCredentialsController::class);
});

// Language
Route::patch('/languages/{language}/strings', UpdateLanguageStringController::class);
Route::apiResource('/languages', LanguageController::class);

// Miscellaneous
Route::get('/status', GetServerStatusController::class);
Route::post('/test-websockets', TestWebsocketConnectionController::class);
