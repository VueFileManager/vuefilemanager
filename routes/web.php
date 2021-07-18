<?php

use Domain\Admin\Controllers\InvoiceController;
use Domain\Sharing\Controllers\BrowseShareController;
use Domain\Homepage\Controllers\AppFunctionsController;
use Domain\SetupWizard\Controllers\SetupWizardController;
use Domain\Subscriptions\Controllers\StripeWebhookController;

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);
Route::post('/admin-setup', [SetupWizardController::class, 'create_admin_account']);

Route::get('/translations/{lang}', [AppFunctionsController::class, 'get_translations']);

// Get user invoice from stripe service
Route::get('/invoice/{customer}/{token}', [InvoiceController::class, 'show'])->middleware(['auth:sanctum']);

// Get og site for web crawlers
if (Crawler::isCrawler()) {
    Route::get('/share/{shared}', [AppFunctionsController::class, 'og_site']);
} else {
    Route::get('/share/{shared}', [BrowseShareController::class, 'index']);
}

// Show index.blade
Route::get('/{any?}', [AppFunctionsController::class, 'index'])->where('any', '.*');
