<?php

use App\Users\Controllers\GetAvatarController;
use Domain\Settings\Controllers\GetAppImageController;
use Domain\Files\Controllers\FileAccess\GetFileController;
use Domain\Files\Controllers\FileAccess\GetThumbnailController;
use Domain\Files\Controllers\FileAccess\VisitorGetFileController;
use Domain\Files\Controllers\FileAccess\VisitorGetThumbnailController;
use Domain\UploadRequest\Controllers\FileAccess\GetFileFromUploadRequestController;
use Domain\UploadRequest\Controllers\FileAccess\GetThumbnailFromUploadRequestController;

Route::get('/avatars/{avatar}', GetAvatarController::class);
Route::get('/system/{image}', GetAppImageController::class);

// Get Upload request thumbnails and files
Route::group(['middleware' => ['upload-request']], function () {
    Route::get('/thumbnail/{name}/upload-request/{uploadRequest}', GetThumbnailFromUploadRequestController::class);
    Route::get('/file/{name}/upload-request/{uploadRequest}', GetFileFromUploadRequestController::class);
});

// Get public thumbnails and files
Route::get('/thumbnail/{name}/shared/{shared}', VisitorGetThumbnailController::class);
Route::get('/file/{name}/shared/{shared}', VisitorGetFileController::class);

// User master,editor,visitor access to image thumbnails and file downloads
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/thumbnail/{name}', GetThumbnailController::class)->name('thumbnail');
    Route::get('/file/{name}', GetFileController::class)->name('file');
});
