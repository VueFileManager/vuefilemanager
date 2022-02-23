<?php

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

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/', CreateUploadRequestController::class);
});
