<?php

use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\AppFunctionsController;
use App\Http\Controllers\FileAccessController;
use App\Http\Controllers\Sharing\FileSharingController;
use App\Http\Controllers\WebhookController;

Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook']);

// App public files
// TODO: testy
Route::get('/avatars/{avatar}', [FileAccessController::class, 'get_avatar'])->name('avatar');
Route::get('/system/{image}', [FileAccessController::class, 'get_system_image']);

// Get public thumbnails and files
// TODO: testy
Route::get('/thumbnail/{name}/public/{token}', [FileAccessController::class, 'get_thumbnail_public']);
Route::get('/file/{name}/public/{token}', [FileAccessController::class, 'get_file_public']);
Route::get('/zip/{id}/public/{token}', [FileAccessController::class, 'get_zip_public'])->name('zip_public');

// User master,editor,visitor access to image thumbnails and file downloads
Route::group(['middleware' => ['auth:api', 'auth.shared', 'auth.master', 'scope:master,editor,visitor']], function () {
    Route::get('/thumbnail/{name}', [FileAccessController::class, 'get_thumbnail'])->name('thumbnail');
    Route::get('/file/{name}', [FileAccessController::class, 'get_file'])->name('file');
    Route::get('/zip/{id}', [FileAccessController::class, 'get_zip'])->name('zip');
});

// Get user invoice
Route::group(['middleware' => ['auth:api', 'auth.master', 'scope:master']], function () {
    Route::get('/invoice/{customer}/{token}', [InvoiceController::class, 'show']);
});

// Get og site for web crawlers
if (Crawler::isCrawler()) {
    Route::get('/shared/{token}', [AppFunctionsController::class, 'og_site']);
} else {
    Route::get('/shared/{token}', [FileSharingController::class, 'index']);
}

Route::get('/{any?}', [AppFunctionsController::class, 'index'])->where('any', '.*');
