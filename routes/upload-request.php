<?php

use Domain\UploadRequest\Controllers\CreateFolderController;
use Domain\UploadRequest\Controllers\GetUploadRequestController;
use Domain\UploadRequest\Controllers\DeleteFileOrFolderController;
use Domain\UploadRequest\Controllers\RenameFileOrFolderController;
use Domain\UploadRequest\Controllers\BrowseUploadRequestController;
use Domain\UploadRequest\Controllers\CreateUploadRequestController;
use Domain\UploadRequest\Controllers\MoveItemInUploadRequestController;
use Domain\UploadRequest\Controllers\SetUploadRequestAsFilledController;
use Domain\UploadRequest\Controllers\UploadFilesForUploadRequestController;
use Domain\UploadRequest\Controllers\GetFolderTreeForUploadRequestController;
use Domain\RemoteUpload\Controllers\UploadFilesRemotelyForUploadRequestController;

Route::get('/{uploadRequest}', GetUploadRequestController::class);

// Available only for active upload requests
Route::group(['middleware' => 'upload-request'], function () {
    // Detail
    Route::delete('/{uploadRequest}', SetUploadRequestAsFilledController::class);

    // Edit
    Route::post('/{uploadRequest}/upload/remote', UploadFilesRemotelyForUploadRequestController::class);
    Route::post('/{uploadRequest}/upload', UploadFilesForUploadRequestController::class);
    Route::patch('/{uploadRequest}/rename/{id}', RenameFileOrFolderController::class);
    Route::post('/{uploadRequest}/create-folder', CreateFolderController::class);
    Route::post('/{uploadRequest}/remove', DeleteFileOrFolderController::class);

    // Browsing
    Route::get('/{uploadRequest}/navigation', GetFolderTreeForUploadRequestController::class);
    Route::get('/{uploadRequest}/browse/{folder?}', BrowseUploadRequestController::class);
    Route::post('/{uploadRequest}/move', MoveItemInUploadRequestController::class);
});

// User functionality
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/', CreateUploadRequestController::class);
});
