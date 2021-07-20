<?php

use Domain\Admin\Controllers\InvoiceController;
use Domain\Homepage\Controllers\IndexController;
use Domain\Sharing\Controllers\OGSiteController;
use Domain\Sharing\Controllers\BrowseShareController;
use Domain\SetupWizard\Controllers\SetupWizardController;
use Domain\Subscriptions\Controllers\StripeWebhookController;
use Domain\Localization\Controllers\CurrentLocalizationController;

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);
Route::post('/admin-setup', [SetupWizardController::class, 'create_admin_account']);

Route::get('/translations/{lang}', CurrentLocalizationController::class);

// Get user invoice from stripe service
Route::get('/invoice/{customer}/{token}', [InvoiceController::class, 'show'])->middleware(['auth:sanctum']);

// Get og site for web crawlers
if (Crawler::isCrawler()) {
    Route::get('/share/{shared}', [OGSiteController::class, 'og_site']);
} else {
    Route::get('/share/{shared}', [BrowseShareController::class, 'index']);
}

// Show index.blade
Route::get('/{any?}', IndexController::class)
    ->where('any', '.*');
