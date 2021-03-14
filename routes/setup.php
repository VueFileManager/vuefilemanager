<?php

use App\Http\Controllers\Setup\SetupWizardController;

Route::post('/purchase-code', [SetupWizardController::class, 'verify_purchase_code']);
Route::post('/database', [SetupWizardController::class, 'setup_database']);
Route::post('/stripe-credentials', [SetupWizardController::class, 'store_stripe_credentials']);
Route::post('/stripe-billings', [SetupWizardController::class, 'store_stripe_billings']);
Route::post('/stripe-plans', [SetupWizardController::class, 'store_stripe_plans']);
Route::post('/environment-setup', [SetupWizardController::class, 'store_environment_setup']);
Route::post('/app-setup', [SetupWizardController::class, 'store_app_settings']);