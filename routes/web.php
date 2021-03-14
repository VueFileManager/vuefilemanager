<?php

use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\App\SetupWizardController;
use App\Http\Controllers\App\AppFunctionsController;
use App\Http\Controllers\Sharing\FileSharingController;
use App\Http\Controllers\Subscription\StripeWebhookController;

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);
Route::post('/admin-setup', [SetupWizardController::class, 'create_admin_account']);

// Get user invoice from stripe service
Route::get('/invoice/{customer}/{token}', [InvoiceController::class, 'show'])->middleware(['auth:sanctum']);

// Get og site for web crawlers
if (Crawler::isCrawler()) {
    Route::get('/shared/{token}', [AppFunctionsController::class, 'og_site']);
} else {
    Route::get('/shared/{token}', [FileSharingController::class, 'index']);
}

// Show index.blade
Route::get('/{any?}', [AppFunctionsController::class, 'index'])->where('any', '.*');
