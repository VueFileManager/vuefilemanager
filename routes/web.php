<?php

use Domain\Homepage\Controllers\IndexController;
use Domain\Invoices\Controllers\GetInvoiceController;
use Domain\Sharing\Controllers\SharePublicIndexController;
use Domain\Sharing\Controllers\WebCrawlerOpenGraphController;
use Domain\Localization\Controllers\CurrentLocalizationController;

// Translations
Route::get('/translations/{lang}', CurrentLocalizationController::class);

// Invoices
Route::get('/invoices/{invoice}', GetInvoiceController::class)
    ->middleware('auth:sanctum');

// Get og site for web crawlers
if (Crawler::isCrawler()) {
    Route::get('/share/{share}', WebCrawlerOpenGraphController::class);
} else {
    Route::get('/share/{share}', SharePublicIndexController::class);
}

// Index
Route::get('/{any?}', IndexController::class)
    ->where('any', '.*');
