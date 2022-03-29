<?php

use Domain\Homepage\Controllers\IndexController;
use Domain\Invoices\Controllers\GetInvoiceController;
use Domain\Settings\Controllers\DownloadLogController;
use App\Socialite\Controllers\SocialiteCallbackController;
use Domain\Sharing\Controllers\SharePublicIndexController;
use Domain\Sharing\Controllers\WebCrawlerOpenGraphController;
use Domain\Sharing\Controllers\DirectlyDownloadFileController;
use Domain\Localization\Controllers\CurrentLocalizationController;

Route::get('/socialite/{provider}/callback', SocialiteCallbackController::class);

// Translations
Route::get('/translations/{lang}', CurrentLocalizationController::class);

// Invoices
Route::get('/invoices/{invoice}', GetInvoiceController::class)
    ->middleware('auth:sanctum');

Route::get('/admin/log/{log}', DownloadLogController::class)
    ->middleware(['auth:sanctum', 'admin']);

// Get og site for web crawlers
if (Crawler::isCrawler()) {
    Route::get('/share/{share}/direct', WebCrawlerOpenGraphController::class);
    Route::get('/share/{share}', WebCrawlerOpenGraphController::class);
} else {
    Route::get('/share/{share}/direct', DirectlyDownloadFileController::class);
    Route::get('/share/{share}', SharePublicIndexController::class);
}

// Index
Route::get('/{any?}', IndexController::class)
    ->where('any', '.*');
