<?php

use App\Users\Controllers\GetAvatarController;
use Domain\Settings\Controllers\GetAppImageController;
use Domain\Files\Controllers\FileAccess\GetFileController;
use Domain\Files\Controllers\FileAccess\GetThumbnailController;
use Domain\Files\Controllers\FileAccess\VisitorGetFileController;
use Domain\Files\Controllers\FileAccess\VisitorGetThumbnailController;

Route::get('/avatars/{avatar}', GetAvatarController::class);
Route::get('/system/{image}', GetAppImageController::class);

// Get public thumbnails and files
Route::get('/thumbnail/{name}/shared/{shared}', VisitorGetThumbnailController::class);
Route::get('/file/{name}/shared/{shared}', VisitorGetFileController::class);

// User master,editor,visitor access to image thumbnails and file downloads
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/thumbnail/{name}', GetThumbnailController::class)->name('thumbnail');
    Route::get('/file/{name}', GetFileController::class)->name('file');
});
