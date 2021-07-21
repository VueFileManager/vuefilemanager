<?php

use Domain\SetupWizard\Controllers\StorePlansController;
use Domain\SetupWizard\Controllers\StoreBillingsController;
use Domain\SetupWizard\Controllers\StoreAppSettingsController;
use Domain\SetupWizard\Controllers\VerifyPurchaseCodeController;
use Domain\SetupWizard\Controllers\StoreDatabaseCredentialsController;
use Domain\SetupWizard\Controllers\StoreEnvironmentSettingsController;
use Domain\SetupWizard\Controllers\StoreSubscriptionServiceCredentialsController;

// TODO: create middleware for setup wizard protection after successful installation
Route::post('/stripe-credentials', StoreSubscriptionServiceCredentialsController::class);
Route::post('/environment-setup', StoreEnvironmentSettingsController::class);
Route::post('/database', StoreDatabaseCredentialsController::class);
Route::post('/purchase-code', VerifyPurchaseCodeController::class);
Route::post('/stripe-billings', StoreBillingsController::class);
Route::post('/app-setup', StoreAppSettingsController::class);
Route::post('/stripe-plans', StorePlansController::class);
