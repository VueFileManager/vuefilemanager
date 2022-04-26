<?php

use Domain\Homepage\Controllers\IndexController;
use Domain\Settings\Controllers\DownloadLogController;
use Domain\Sharing\Controllers\SharePublicIndexController;
use Domain\Sharing\Controllers\WebCrawlerOpenGraphController;
use Domain\Sharing\Controllers\DirectlyDownloadFileController;
use Domain\Localization\Controllers\CurrentLocalizationController;

// Translations
Route::get('/translations/{lang}', CurrentLocalizationController::class);

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
