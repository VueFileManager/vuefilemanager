<?php

// Get avatars and system images
use App\Users\Controllers\GetAvatarController;
use Domain\Zipping\Controllers\GetZipController;
use Domain\Settings\Controllers\GetIAppController;
use Domain\Zipping\Controllers\VisitorGetZipController;
use Domain\Files\Controllers\FileAccess\GetFileController;
use Domain\Files\Controllers\FileAccess\GetThumbnailController;
use Domain\Files\Controllers\FileAccess\VisitorGetFileController;
use Domain\Files\Controllers\FileAccess\VisitorGetThumbnailController;

Route::get('/avatars/{avatar}', GetAvatarController::class);
Route::get('/system/{image}', GetIAppController::class);

// Get public thumbnails and files
Route::get('/thumbnail/{name}/{shared}', VisitorGetThumbnailController::class);
Route::get('/zip/{id}/public/{token}', VisitorGetZipController::class);
Route::get('/file/{name}/{shared}', VisitorGetFileController::class);

// User master,editor,visitor access to image thumbnails and file downloads
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/thumbnail/{name}', GetThumbnailController::class)->name('thumbnail');
    Route::get('/file/{name}', GetFileController::class)->name('file');
    Route::get('/zip/{id}', GetZipController::class)->name('zip');
});
