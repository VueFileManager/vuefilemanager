<?php

use Domain\Homepage\Controllers\IndexController;
use Domain\Invoices\Controllers\AdminInvoiceController;
use Domain\Sharing\Controllers\SharePublicIndexController;
use Domain\Sharing\Controllers\WebCrawlerOpenGraphController;
use Domain\Subscriptions\Controllers\StripeWebhookController;
use Domain\SetupWizard\Controllers\CreateAdminAccountController;
use Domain\Localization\Controllers\CurrentLocalizationController;

// Setup Wizard
Route::post('/admin-setup', CreateAdminAccountController::class);

// Subscription Services
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);
Route::get('/invoice/{customer}/{token}', [AdminInvoiceController::class, 'show'])->middleware(['auth:sanctum']);

// Translations
Route::get('/translations/{lang}', CurrentLocalizationController::class);

// Get og site for web crawlers
if (Crawler::isCrawler()) {
    Route::get('/share/{shared}', WebCrawlerOpenGraphController::class);
} else {
    Route::get('/share/{shared}', SharePublicIndexController::class);
}

// Index
Route::get('/{any?}', IndexController::class)
    ->where('any', '.*');
