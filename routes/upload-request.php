<?php

use Domain\UploadRequest\Controllers\CreateFolderController;
use Domain\UploadRequest\Controllers\DeleteFileOrFolderController;
use Domain\UploadRequest\Controllers\GetFolderTreeForUploadRequestController;
use Tests\Domain\UploadRequest\RenameFileOrFolderController;
use Domain\UploadRequest\Controllers\GetUploadRequestController;
use Domain\UploadRequest\Controllers\CreateUploadRequestController;
use Domain\UploadRequest\Controllers\SetUploadRequestAsFilledController;
use Domain\UploadRequest\Controllers\UploadFilesForUploadRequestController;

Route::get('/{uploadRequest}', GetUploadRequestController::class);
Route::delete('/{uploadRequest}', SetUploadRequestAsFilledController::class);
Route::post('/{uploadRequest}/upload', UploadFilesForUploadRequestController::class);

// Edit
Route::patch('/{uploadRequest}/rename/{id}', RenameFileOrFolderController::class);
Route::post('/{uploadRequest}/create-folder', CreateFolderController::class);
Route::post('/{uploadRequest}/remove', DeleteFileOrFolderController::class);

Route::get('/{uploadRequest}/navigation', GetFolderTreeForUploadRequestController::class);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/', CreateUploadRequestController::class);
});
