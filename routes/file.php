<?php

// Get avatars and system images
use App\Http\Controllers\FileManager\FileAccessController;
use App\Http\Controllers\Sharing\FileSharedAccessController;

Route::get('/avatars/{avatar}', [FileAccessController::class, 'get_avatar'])->name('avatar');
Route::get('/system/{image}', [FileAccessController::class, 'get_system_image']);

// Get public thumbnails and files
Route::get('/thumbnail/{name}/{shared}', [FileSharedAccessController::class, 'get_thumbnail_public']);
Route::get('/file/{name}/{shared}', [FileSharedAccessController::class, 'get_file_public']);
Route::get('/zip/{id}/public/{token}', [FileSharedAccessController::class, 'get_zip_public'])->name('zip_public');

// User master,editor,visitor access to image thumbnails and file downloads
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/thumbnail/{name}', [FileAccessController::class, 'get_thumbnail'])->name('thumbnail');
    Route::get('/file/{name}', [FileAccessController::class, 'get_file'])->name('file');
    Route::get('/zip/{id}', [FileAccessController::class, 'get_zip'])->name('zip');
});
