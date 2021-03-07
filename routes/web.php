<?php

use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\AppFunctionsController;
use App\Http\Controllers\FileAccessController;
use App\Http\Controllers\General\SetupWizardController;
use App\Http\Controllers\Sharing\FileSharingController;
use App\Http\Controllers\WebhookController;

Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook']);
Route::post('/admin-setup', [SetupWizardController::class, 'create_admin_account']);

// Get user invoice
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/invoice/{customer}/{token}', [InvoiceController::class, 'show']);
});

// Get og site for web crawlers
if (Crawler::isCrawler()) {
    Route::get('/shared/{token}', [AppFunctionsController::class, 'og_site']);
} else {
    Route::get('/shared/{token}', [FileSharingController::class, 'index']);
}

Route::get('/{any?}', [AppFunctionsController::class, 'index'])->where('any', '.*');
