<?php

use Domain\SetupWizard\Controllers\StorePlansController;
use Domain\SetupWizard\Controllers\StoreBillingsController;
use Domain\SetupWizard\Controllers\StoreAppSettingsController;
use Domain\SetupWizard\Controllers\CreateAdminAccountController;
use Domain\SetupWizard\Controllers\VerifyPurchaseCodeController;
use Domain\SetupWizard\Controllers\StoreDatabaseCredentialsController;
use Domain\SetupWizard\Controllers\StoreEnvironmentSettingsController;
use Domain\SetupWizard\Controllers\StoreSubscriptionServiceCredentialsController;

Route::group(['prefix' => 'api/setup'], function () {
    Route::post('/stripe-credentials', StoreSubscriptionServiceCredentialsController::class);
    Route::post('/environment-setup', StoreEnvironmentSettingsController::class);
    Route::post('/database', StoreDatabaseCredentialsController::class);
    Route::post('/purchase-code', VerifyPurchaseCodeController::class);
    Route::post('/stripe-billings', StoreBillingsController::class);
    Route::post('/app-setup', StoreAppSettingsController::class);
    Route::post('/stripe-plans', StorePlansController::class);
});

Route::post('/admin-setup', CreateAdminAccountController::class)
    ->middleware('web');
